<?php
error_reporting(E_ERROR | E_PARSE);
require_once '../config/config.php';
require_once 'classes/General/Knowledge.php';
$currentDir = getcwd();
$numberOfQuestions = 10;
$knowledge = \classes\General\Knowledge::getInstance();
$knowledge->readFiles();
$questions = array();
for ($i = 0; $i < $numberOfQuestions; $i++) {
    $questionType = getQuestionType();
    switch ($questionType) {
        case 'notesOfChord':
            $questions[] = notesOfChordQuestion($knowledge);
            break;
        case 'chordOfNotes':
            $questions[] = chordOfNotesQuestion($knowledge);
            break;
        case 'notesOfInterval':
            $questions[] = notesOfIntervalQuestion($knowledge);
            break;
        case 'intervalOfNotes':
            $questions[] = intervalOfNotesQuestion($knowledge);
            break;
        case 'degreeOfChord':
            $questions[] = degreeOfChordQuestion($knowledge);
            break;
        case 'areaOfChord':
            $questions[] = areaOfChordQuestion($knowledge);
            break;
        case 'substitutionOfChord':
            $questions[] = substitutionOfChordQuestion($knowledge);
            break;
    }
}

$result = json_encode(array('questions' => $questions));

echo $result;

function getQuestionType()
{
    /*
    More questions:
    Intervals
    Given the notes, say the chord.
    Which of the given notes does not belong to the scale?
    Which scale sounds now?
    Which chord/interval sounds now?
    */
    $questionTypes = array();
    $questionTypes[] = 'notesOfChord';
    $questionTypes[] = 'chordOfNotes';
    $questionTypes[] = 'intervalOfNotes';
    $questionTypes[] = 'notesOfInterval';
    //$questionTypes[]='degreeOfChord';
    //$questionTypes[]='areaOfChord';
    //$questionTypes[]='substitutionOfChord';
    $index = rand(0, count($questionTypes) - 1);
    return $questionTypes[$index];
}

/**
 *
 * key: 'C',
 * mode: 'ionian',
 * text: 'Tell me the notes of this chord',
 * type: 'notesOfChord',
 * chord: 'CMaj7',
 * expected: 'C,E,G,B'
 */
function notesOfChordQuestion($knowledge)
{
    $chordQuestion = array();
    $chordQuestion['key'] = 'key'; // TODO
    $chordQuestion['mode'] = 'mode'; // TODO
    $chordQuestion['type'] = 'notesOfChord';
    $chordQuestion['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['notesOfChord'];
    $note = $knowledge->getRandomNote();
    $chord = $knowledge->getRandomChord($note);
    $chordQuestion['chord'] = $chord;
    $notes = $knowledge->getNotesChord($chord);
    $allNotes = $knowledge->getAllNotes($notes);
    $chordQuestion['expected'] = implode(',', $notes);
    $chordQuestion['shown'] = implode(',', $allNotes);
    return $chordQuestion;
}

/**
 * The notes of a random chord are shown and the player must guess which chord it is.
 * @param $knowledge
 * @return array
 */
function chordOfNotesQuestion($knowledge)
{
    $chordQuestion = array();
    $chordQuestion['key'] = 'key'; // TODO
    $chordQuestion['mode'] = 'mode'; // TODO
    $chordQuestion['type'] = 'chordOfNotes';
    $chordQuestion['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['chordOfNotes'];
    $chord = $knowledge->getRandomChord();
    list($tonic, $chordType) = $knowledge->getTonicAndTypeOfChord($chord);
    $notes = $knowledge->getNotesChord($chord);
    shuffle($notes); // The notes are randomly ordered
    $chordQuestion['questionElement'] = implode(',', $notes);
    $allChordTypes = $knowledge->getAllChordTypes();
    $chordQuestion['expected'] = $tonic . ',' . $chordType;
    $chordQuestion['shown'] = implode(',', $notes) . ',' . implode(',', $allChordTypes);
    return $chordQuestion;
}

/**
 * Two random notes are shown, and the user must say which interval they form
 * @param $knowledge
 * @return array
 */
function intervalOfNotesQuestion($knowledge)
{
    $tonic = $knowledge->getRandomNote();
    $interval = $knowledge->getRandomInterval();
    $intervalNote = $knowledge->getNoteInterval($tonic, $interval);
    $allIntervals = $knowledge->getAllIntervals();
    $intervalQuestion = array();
    $intervalQuestion['key'] = '';
    $intervalQuestion['mode'] = ''; // TODO
    $intervalQuestion['type'] = 'intervalOfNotes';
    $intervalQuestion['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['intervalOfNotes'];
    $intervalQuestion['questionElement'] = $tonic . ' ' . $intervalNote;
    $intervalQuestion['expected'] = implode(',', $knowledge->getEquivalentIntervals($interval));
    $intervalQuestion['shown'] = implode(',', $allIntervals);
    return $intervalQuestion;
}

/**
 * A note and an interval are shown, and the user must say which note corresponds to such pair
 * @param $knowledge
 * @return array
 */
function notesOfIntervalQuestion($knowledge)
{
    $tonic = $knowledge->getRandomNote();
    $interval = $knowledge->getRandomInterval();
    $intervalNote = $knowledge->getNoteInterval($tonic, $interval);
    $allNotes = $knowledge->getAllNotes(array($intervalNote));
    $intervalQuestion = array();
    $intervalQuestion['key'] = '';
    $intervalQuestion['mode'] = ''; // TODO
    $intervalQuestion['type'] = 'notesOfInterval';
    $intervalQuestion['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['notesOfInterval'];
    $intervalQuestion['questionElement'] = 'Tonic: ' . $tonic . ', interval:' . $interval;
    $intervalQuestion['expected'] = $intervalNote;
    $intervalQuestion['shown'] = implode(',', $allNotes);
    return $intervalQuestion;
}