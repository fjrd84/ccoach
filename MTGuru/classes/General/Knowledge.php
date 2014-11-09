<?php
namespace classes\General;

class Knowledge
{
    // knowledge
    private $distances;
    private $intervals;
    private $chords;
    private $notes;
    private $scales;
    // singleton instance
    private static $instance;

    // private constructor function
    // to prevent external instantiation
    private function __construct()
    {
    }

    // getInstance method
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * It reads the information in all the knowledge files
     */
    public function readFiles()
    {
        $this->distances = $this->parseFile('knowledge/distances.txt');
        $this->intervals = $this->parseFile('knowledge/intervals.txt');
        $this->chords = $this->parseFile('knowledge/chords.txt');
        $this->notes = $this->parseFile('knowledge/notes.txt');
        $this->scales = $this->parseFile('knowledge/scales.txt');
    }

    /**
     * It parses a knowledge file
     * @param $fileName
     * @return array
     */
    private function parseFile($fileName)
    {
        $handle = fopen($fileName, 'r');
        $data = fread($handle, filesize($fileName));
        $data = explode("\r\n", $data);
        $numData = count($data);
        $parsed = array();
        for ($i = 0; $i < $numData; $i++) {
            if ($data[$i] != "") {
                $parsed[$i] = explode(" ", $data[$i]);
            }
        }
        return $parsed;
    }

    /**
     * It returns the notes of a given chord
     * @param $chord CMaj7, Em7, DM
     */
    public function getNotesChord($chord)
    {
        list($tonic, $chordType) = $this->getTonicAndTypeOfChord($chord);
        $notes = array();
        $notes[0] = $tonic;
        $numChords = count($this->chords);
        $chordIndex = -1;
        for ($i = 0; $i < $numChords; $i++) {
            if ($this->chords[$i][0] === $chordType) {
                $chordIndex = $i;
                break;
            }
        }
        // Unknown chord
        if ($chordIndex == -1) {
            return -1;
        }
        $numIntervals = count($this->chords[$chordIndex]);
        for ($i = 1; $i < $numIntervals; $i++) {
            $notes[$i] = $this->getNoteInterval($tonic, $this->chords[$chordIndex][$i]);
        }
        return $notes;
    }

    /**
     * It returns an array with the tonic and type of a given chord.
     */
    public function getTonicAndTypeOfChord($chord)
    {
        $tonic = substr($chord, 0, 1);
        $alteration = substr($chord, 1, 1);
        // the tonic might be flat or sharp
        if ($alteration === 'b' || $alteration === '#') {
            $tonic .= $alteration;
            $chordType = substr($chord, 2);
        } else {
            $chordType = substr($chord, 1);
        }
        return array($tonic, $chordType);
    }

    /**
     * It returns the notes of the scale
     * @param $tonic
     * @param $scale
     * @return array|int
     */
    public function getNotesScale($tonic, $scale)
    {
        $notes = array();
        $notes[0] = $tonic;
        $numScales = count($this->scales);
        $scaleIndex = -1;
        for ($i = 0; $i < $numScales; $i++) {
            if ($this->scales[$i][0] === $scale) {
                $scaleIndex = $i;
                break;
            }
        }
        // Unknown scale
        if ($scaleIndex == -1) {
            return -1;
        }
        $numIntervals = count($this->scales[$scaleIndex]);
        for ($i = 1; $i < $numIntervals; $i++) {
            $notes[$i] = $this->getNoteInterval($tonic, $this->scales[$scaleIndex][$i]);
        }
        return $notes;
    }

    /**
     * It returns the note for a given interval with a known tonic.
     * @param $tonic C, D, E, F...
     * @param $intervalType 3M, 4J, 7m...
     * @return string C, D#, Gb...
     */
    public function getNoteInterval($tonic, $intervalType)
    {
        $numIntervals = count($this->intervals);
        $distance = 0;
        // The tone distance for this interval is looked up
        for ($i = 0; $i < $numIntervals; $i++) {
            if ($this->intervals[$i][0] === $intervalType) {
                $distance = $this->intervals[$i][1];
                break;
            }
        }
        // If the interval is unknown, -1 is returned
        if ($distance == 0) {
            return -1;
        }

        $tonicIndex = $this->indexOfNote(substr($tonic, 0, 1));
        $intervalIndex = substr($intervalType, 0, 1) - 1;
        $intervalNote = $this->notes[($tonicIndex + $intervalIndex) % 7][0];
        // The distance of the notes without alterations is calculated
        $baseDistance = $this->getDistance($tonic, $intervalNote);
        if ($baseDistance == $distance) {
            return $intervalNote;
        } elseif ($baseDistance - 0.5 == $distance) {
            if ($intervalNote != 'C' && $intervalNote != 'F') { // Cb and Fb make no sense.
                return $intervalNote . 'b';
            } else {
                return $this->notes[($tonicIndex + $intervalIndex - 1) % 7][0];
            }
        } elseif ($baseDistance + 0.5 == $distance) {
            if ($intervalNote != 'E' && $intervalNote != 'B') { // E# and B# make no sense
                return $intervalNote . '#';
            } else {
                return $this->notes[($tonicIndex + $intervalIndex + 1) % 7][0];
            }
        } elseif ($baseDistance + 1 == $distance) { // Double sharp case (the next note will be selected)
            $intervalNote = $this->notes[($tonicIndex + $intervalIndex + 1) % 7][0];
            return $intervalNote;
        } elseif ($baseDistance - 1 == $distance) { // Double flat case (the previous note will be selected)
            $intervalNote = $this->notes[($tonicIndex + $intervalIndex - 1) % 7][0];
            return $intervalNote;
        } else {
            return -1; // it means something went wrong (obviously, it SHOULDN'T happen...)
        }
    }

    /**
     * It returns all possible intervals with the given notes (the first one as a tonic!)
     */
    public function getIntervalsNotes($tonic, $note)
    {
        $intervals = array();
        $currentDistance = $this->getDistance($tonic, $note);
        $numIntervals = count($this->intervals);
        for ($i = 0; $i < $numIntervals; $i++) {
            // Right intervals are considered all that match the tone distance
            if ($currentDistance == $this->intervals[$i][1]) {
                $intervals[] = $this->intervals[$i][0];
            }
        }
        // There will be none, one or two elements
        return $intervals;
    }

    /**
     * It returns the tone distance between two notes
     * @param $note1 C, D, E, F...
     * @param $note2 C, D, E, F...
     * @return float|int Distance between the notes
     */
    public function getDistance($note1, $note2)
    {
        $distance = 0;
        // same note
        if ($note1 === $note2) {
            return 0;
        }
        // flat note 1
        if (strrpos($note1, 'b') > -1) {
            $distance += 0.5;
        }
        // sharp note 1
        if (strrpos($note1, '#') > -1) {
            $distance -= 0.5;
        }
        // flat note 2
        if (strrpos($note2, 'b') > -1) {
            $distance -= 0.5;
        }
        // sharp note 2
        if (strrpos($note2, '#') > -1) {
            $distance += 0.5;
        }
        // same note, different alteration
        if (substr($note1, 0, 1) == substr($note2, 0, 1)) {
            return $distance;
        }
        // different notes: distance calculation
        $index1 = $this->indexOfNote(substr($note1, 0, 1));
        while ($distance < 6) {
            $distance += $this->distances[$index1][2];
            if ($this->distances[$index1][1] === substr($note2, 0, 1)) {
                return $distance;
            } else {
                $index1 = ($index1 + 1) % 7;
            }
        }
        return -1;
    }

    /**
     * It returns the distance in tones for a given interval
     * @param $interval
     */
    public function getIntervalDistance($interval)
    {
        $numOfIntervals = count($this->intervals);
        for ($i = 0; $i < $numOfIntervals; $i++) {
            if ($this->intervals[$i][0] == $interval) {
                return $this->intervals[$i][1];
            }
        }
    }

    /**
     * It returns the numeric index of a note
     * @param $note C, D, E, F, G...
     * @return int 0, 1, 2, 3, 4...
     */
    public function indexOfNote($note)
    {
        for ($i = 0; $i < 7; $i++) {
            if ($note === $this->notes[$i][0]) {
                return $i;
            }
        }
        return -1;
    }

    /**
     * It returns a random note
     * @return string
     */
    public function getRandomNote()
    {
        // TODO: Adapt to the new notes schema
        $index = rand(0, 6); // random note
        $alteration = rand(0, 2); // random alteration
        $note = $this->notes[$index][0];
        if ($alteration == 0 && $note != "C" && $note != "F") {
            return $note . "b";
        } elseif ($alteration == 1 && $note != "E" && $note != "B") {
            return $note . "#";
        }
        return $note;
    }

    /**
     * It returns a random chord
     * @return string
     */
    public function getRandomChord($note = null)
    {
        if ($note == null) {
            $note = $this->getRandomNote();
        }
        $chordType = $this->chords[rand(0, count($this->chords) - 1)][0];
        return $note . $chordType;
    }

    /**
     * It returns a random chord
     * @return string
     */
    public function getRandomInterval()
    {
        return $this->intervals[rand(0, count($this->intervals) - 1)][0];;
    }

    /**
     * It returns a random scale
     * @return string
     */
    public function getRandomScale()
    {
        return $this->scales[rand(0, count($this->scales) - 1)][0];;
    }

    /**
     * It returns the index of a known scale.
     * @param $scale name of the scale
     * @return int index of the scale.
     */
    public function getScaleIndex($scale){
        $numScales = count($this->scales);
        for($index = 0; $index < $numScales; $index++){
            if($this->scales[$index][0]==$scale){
                return $index;
            }
        }
        return -1;
    }

    /**
     * It returns an array with all the equivalent intervals (the parameter interval included).
     * @param $interval
     */
    public function getEquivalentIntervals($interval)
    {
        $equivalents = array();
        $intervalDistance = $this->getIntervalDistance($interval);
        $numIntervals = count($this->intervals);
        for ($i = 0; $i < $numIntervals; $i++) {
            if ($this->intervals[$i][1] == $intervalDistance) {
                $equivalents[] = $this->intervals[$i][0];
            }
        }
        return $equivalents;
    }

    /**
     * It returns an array with all the notes (including alterations)
     */
    public function getAllNotes($references)
    {
        $notes = array();
        $alteration = -1;
        $numReferences = count($references);
        // If any of the references is a flat or a sharp note, it will be used as default alteration
        for ($i = 0; $i < $numReferences; $i++) {
            if (strpos($references[$i], "#") > -1) {
                $alteration = 1;
            } elseif (strpos($references[$i], "b") > -1) {
                $alteration = 2;
            }
        }
        // If no alteration has been found, a random one will be used.
        if ($alteration == -1) {
            // If the given reference note has no alteration, flat or sharp alterations might be shown
            $alteration = rand(1, 2);
        }
        $numNotes = count($this->notes);
        for ($i = 0; $i < $numNotes; $i++) {
            if ($alteration == 1) {
                $notes[] = $this->notes[$i][0];
            }
            if ($this->notes[$i][$alteration] != -1) {
                $notes[] = $this->notes[$i][$alteration];
            }
            if ($alteration == 2) {
                $notes[] = $this->notes[$i][0];
            }
        }
        return $notes;
    }

    /**
     * It returns an array with all the known chord types
     */
    public function getAllChordTypes()
    {
        $chordTypes = array();
        $numberOfTypes = count($this->chords);
        for ($i = 0; $i < $numberOfTypes; $i++) {
            $chordTypes[$i] = $this->chords[$i][0];
        }
        return $chordTypes;
    }


    /**
     * It returns an array with all the known intervals
     */
    public function getAllIntervals()
    {
        $intervals = array();
        $numberOfTypes = count($this->intervals);
        for ($i = 0; $i < $numberOfTypes; $i++) {
            $intervals[$i] = $this->intervals[$i][0];
        }
        return $intervals;
    }

    /**
     * It calculates all the possible known chords that can be formed with ALL or SOME the given notes.
     *
     * @param $notes Input array of notes [C, D, E, F...]
     * @param $allNotes True if we want only the chords tha use ALL the notes
     * @return array Found chords
     */
    public function getAllPossibleChords($notes, $allNotes)
    {
        //in_arrray($note, $notes);
        $allPossibleChords = array();
        // With each note as a tonic, compute all possible intervals
        $numNotes = count($notes);
        // An array with the following structure will be created:
        // $possibleIntervalsWithTonicAndNote
        // [0][1][0]=4+
        // [0][1][1]=5-
        // [0][2][0]=3M
        // [0][3][0]=7m
        // [1][0][0]=4-
        // [1][0][1]=5+
        // [1][2][0]...
        // ...
        // Where the [index1][index2] stand for the notes index in the $notes array and [index3] are the possible
        // intervals with this note
        // Another array with only the tonic and all its possible intervals is also created
        // $possibleIntervalsWithTonic
        $possibleIntervalsWithTonicAndNote = array();
        $possibleIntervalsWithTonic = array();
        for ($tonicIndex = 0; $tonicIndex < $numNotes; $tonicIndex++) {
            $currentTonic = $notes[$tonicIndex];
            $possibleIntervalsWithTonicAndNote[$tonicIndex] = array();
            // With all possible intervals, compute all possible chords (using ALL of the available intervals)
            for ($noteIndex = 0; $noteIndex < $numNotes; $noteIndex++) {
                // Only intervals between different notes will be computed
                if ($tonicIndex != $noteIndex) {
                    $currentPossibleIntervals = $this->getIntervalsNotes($currentTonic, $notes[$noteIndex]);
                    $possibleIntervalsWithTonicAndNote[$tonicIndex][$noteIndex] = $currentPossibleIntervals;
                    $possibleIntervalsWithTonic[$tonicIndex] .= implode(",", $currentPossibleIntervals) . ",";
                }
            }
        }
        // Now we must find which of the known chords match all their intervals with a tonic and all of the other notes
        for ($tonicIndex = 0; $tonicIndex < $numNotes; $tonicIndex++) {
            $allFoundChords = $this->findChordWithIntervals(explode(",", $possibleIntervalsWithTonic[$tonicIndex]));
            $numFoundChords = count($allFoundChords);
            for ($indexFoundChord = 0; $indexFoundChord < $numFoundChords; $indexFoundChord++) {
                $numIntervalsFoundChord = count($this->chords[$this->getIndexOfChord($allFoundChords[$indexFoundChord])]);
                // If the number of intervals of a found chord is the same as the number of notes we have, the chords uses
                // all the notes. Both include the tonic!!
                if ($allNotes) {
                    if ($numIntervalsFoundChord == $numNotes) {
                        $allPossibleChords[] = $notes[$tonicIndex] . $allFoundChords[$indexFoundChord];
                    }
                }else{
                    $allPossibleChords[] = $notes[$tonicIndex] . $allFoundChords[$indexFoundChord];
                }
            }
        }
        //$allIndexes=range(0,$numNotes);
        /*foreach($array as $element){
            unset($currentElement);
        }*/
        return $allPossibleChords;
    }

    public function getIndexOfChord($chordType)
    {
        $numChords = count($this->chords);
        for ($chordIndex = 0; $chordIndex < $numChords; $chordIndex++) {
            if ($chordType == $this->chords[$chordIndex][0]) {
                return $chordIndex;
            }
        }
        // If the chord is unknown
        return -1;
    }

    /**
     * It returns an array with the chords that can be formed with ALL OR SOME of the passed intervals.
     * @param $intervals Array with intervals
     */
    public function findChordWithIntervals($intervals)
    {
        $possibleChords = array();
        $numChords = count($this->chords);
        for ($chordIndex = 0; $chordIndex < $numChords; $chordIndex++) {
            $numIntervals = count($this->chords[$chordIndex]);
            $allIntervalsFound = true;
            for ($intervalIndex = 1; $intervalIndex < $numIntervals; $intervalIndex++) {
                if (!in_array($this->chords[$chordIndex][$intervalIndex], $intervals)) {
                    $allIntervalsFound = false;
                }
            }
            if ($allIntervalsFound) {
                $possibleChords[] = $this->chords[$chordIndex][0];
            }
        }
        return $possibleChords;
    }

    /**
     * It returns an array with the chords that can be formed with ALL OR SOME of the passed intervals.
     * @param $intervals Array with intervals
     */
    public function findChordWithAllIntervals($intervals)
    {
        $possibleChords = array();
        $numChords = count($this->chords);
        for ($chordIndex = 0; $chordIndex < $numChords; $chordIndex++) {
            $numIntervalsChord = count($this->chords[$chordIndex]);
            // we must add one because the first element of chords is the chord name, and no interval
            if ($numIntervalsChord < count($intervals) + 1) {
                continue; // In case not all of the passed intervals form a chord, it is not interesting.
            }
            $allIntervalsFound = true;
            for ($intervalIndex = 1; $intervalIndex < $numIntervalsChord; $intervalIndex++) {
                if (!in_array($this->chords[$chordIndex][$intervalIndex], $intervals)) {
                    $allIntervalsFound = false;
                }
            }
            if ($allIntervalsFound) {
                $possibleChords[] = $this->chords[$chordIndex][0];
            }
        }
        return $possibleChords;
    }
}