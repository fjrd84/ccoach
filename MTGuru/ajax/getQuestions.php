<?php
error_reporting(E_ERROR | E_PARSE);
require_once '../config/config.php';
require_once 'classes/General/Knowledge.php';
$currentDir = getcwd();
$numberOfQuestions = 10;
$maxNotesChord = 4;
$knowledge = \classes\General\Knowledge::getInstance();
$knowledge->readFiles();
$questions = array();
for ($i = 0; $i < $numberOfQuestions; $i++) {
    $questionType = getQuestionType();
    switch ($questionType) {
        case 'notesOfChord':
            $questions[] = notesOfChordQuestion($knowledge);
            break;
        case 'notesOfScale':
            $questions[] = notesOfScaleQuestion($knowledge);
            break;
        case 'chordOfNotes':
            $questions[] = chordOfNotesQuestion($knowledge);
            break;
        case 'chordBelongToScale':
            $questions[] = chordBelongToScaleQuestion($knowledge);
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
    $questionTypes[] = 'degreeOfChord';
    $questionTypes[] = 'notesOfScale';
    $questionTypes[] = 'chordBelongToScale';
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
    $chordQuestion['key'] = '';
    $chordQuestion['mode'] = '';
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
    $chordQuestion['key'] = 'key';
    $chordQuestion['mode'] = 'mode';
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
    $intervalQuestion['mode'] = '';
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
    $intervalQuestion['mode'] = '';
    $intervalQuestion['type'] = 'notesOfInterval';
    $intervalQuestion['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['notesOfInterval'];
    $intervalQuestion['questionElement'] = 'Tonic: ' . $tonic . ', interval:' . $interval;
    $intervalQuestion['expected'] = $intervalNote;
    $intervalQuestion['shown'] = implode(',', $allNotes);
    return $intervalQuestion;
}

/**
 * A chord, a key and an scale is shown, and the user must say which degree it belongs to.
 * @param $knowledge
 */
function degreeOfChordQuestion($knowledge)
{
    $tonic = $knowledge->getRandomNote();
    $scale = $knowledge->getRandomScale();
    $notesScale = $knowledge->getNotesScale($tonic, $scale);
    $allPossibleChords = $knowledge->getAllPossibleChords($notesScale, false);
    $randomChord = $allPossibleChords[rand(0, count($allPossibleChords) - 1)];
    list($tonicChord, $chordType) = $knowledge->getTonicAndTypeOfChord($randomChord);
    $degree = array_search($tonicChord, $notesScale) + 1;
    $allDegrees = range(1, 7);
    $question = array();
    $question['key'] = 'Key: ' . $tonic;
    $question['mode'] = 'Scale: ' . $scale;
    $question['type'] = 'degreeOfChord';
    $question['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['degreeOfChord'];
    $question['questionElement'] = 'Chord: ' . $randomChord;
    $question['expected'] = $degree;
    $question['shown'] = implode(',', $allDegrees);
    return $question;
}

/**
 * A chord, a key and an scale is shown, and the user must say which degree it belongs to.
 * @param $knowledge
 */
function chordBelongToScaleQuestion($knowledge)
{
    $tonic = $knowledge->getRandomNote();
    $scale = $knowledge->getRandomScale();
    $yes = $belongsToScale = $_SESSION['txt'][$_SESSION['lang']]['questions']['yes'];
    $no = $belongsToScale = $_SESSION['txt'][$_SESSION['lang']]['questions']['no'];
    $notesScale = $knowledge->getNotesScale($tonic, $scale);
    $allPossibleChords = $knowledge->getAllPossibleChords($notesScale, false);
    $randomChord = $allPossibleChords[rand(0, count($allPossibleChords) - 1)];
    $belongsToScale = $yes;
    list($tonicChord, $chordType) = $knowledge->getTonicAndTypeOfChord($randomChord);
    // Sometimes a random chord will be generated that might not belong to the scale
    if (rand(0, 10) > 4) {
        $randomChord = $knowledge->getRandomChord($tonicChord);
        if (!in_array($randomChord, $allPossibleChords)) {
            $belongsToScale = $no;
        }
    }
    $question = array();
    $question['key'] = 'Key: ' . $tonic;
    $question['mode'] = 'Scale: ' . $scale;
    $question['type'] = 'chordBelongToScale';
    $question['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['chordBelongToScale'];
    $question['questionElement'] = 'Chord: ' . $randomChord;
    $question['expected'] = $belongsToScale;
    $question['shown'] = $yes.','.$no;
    return $question;
}

/**
 * The notes of a certain scale are asked.
 * @param $knowledge
 */
function notesOfScaleQuestion($knowledge)
{
    $tonic = $knowledge->getRandomNote();
    $scale = $knowledge->getRandomScale();
    $notesScale = $knowledge->getNotesScale($tonic, $scale);
    $allNotes = $knowledge->getAllNotes($notesScale);
    $question = array();
    $question['key'] = 'Key: ' . $tonic;
    $question['mode'] = 'Scale: ' . $scale;
    $question['type'] = 'notesOfScale';
    $question['text'] = $_SESSION['txt'][$_SESSION['lang']]['questions']['notesOfScale'];
    $question['questionElement'] = '';
    $question['expected'] = implode(',', $notesScale);
    $question['shown'] = implode(',', $allNotes);
    return $question;
}