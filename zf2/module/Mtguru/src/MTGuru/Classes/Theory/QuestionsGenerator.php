<?php

namespace MTGuru\Classes\Theory;

use MTGuru\Classes\Theory\Knowledge;
use Zend\I18n\Translator\Translator;

class QuestionsGenerator
{
    public $knowledge;
    public $translator;
    private $currentUser;
    private $userManagement;
    private $currentSkills;
    private $numberOfQuestions = 7;
    private $displayedHelp = [];

    public function __construct($userManagement)
    {
        $this->userManagement = $userManagement;
        $this->currentUser = $userManagement->getCurrentUser();
        $test = $userManagement->getQuestionTypes();
        $this->currentSkills = $userManagement->getUpdatedSkills();
    }


    /**
     * This function generates a question set to be shown on the game. For achieving this, it follows these steps:
     * Question pool generation (according to the currently available questions for this user)
     *
     * Begin loop
     * Select a random question type within the pool, and its skill
     * Generate a question for that question type and skill.
     * Repeat a N times to generate a number of N questions (7 by default).
     *
     * Return a JSON with the generated questions.
     *
     * @param $translator
     * @return string
     */
    public function generateQuestions($translator)
    {
        $this->translator = $translator;
        $numSkills = count($this->currentSkills);
        $questionsPool = array();
        $questionsPoolCount = 0;
        // Only questions for the available skills for the current user will be generated
        for ($i = 0; $i < $numSkills; $i++) {
            $questionType = $this->currentSkills[$i]->getQuestionType()->getQuestionIdent();
            $skill = $this->currentSkills[$i]->getCurrentSkill();
            // Skill goes from 0 to 2
            // Lesser skilled question types are more likely to appear
            $weigh = 3 - $skill;
            while ($weigh > 0) {
                $questionsPool[$questionsPoolCount] = array();
                $questionsPool[$questionsPoolCount]['questionType'] = $questionType;
                $questionsPool[$questionsPoolCount]['skill'] = $skill;
                ++$questionsPoolCount;
                --$weigh;
            }
        }

        $knowledge = Knowledge::getInstance();
        $knowledge->readFiles();
        $this->knowledge = $knowledge;
        $questions = array();
        for ($i = 0; $i < $this->numberOfQuestions; $i++) {
            if (!isset($_GET['questionType'])) {
                // A random question type is picked from the pool
                $randomIndex = rand(0, $questionsPoolCount - 1);
                $questionType = $questionsPool[$randomIndex]['questionType'];
                $questionSkill = $questionsPool[$randomIndex]['skill'];
                $helpPage = $this->getHelpPage($questionType);
                // If the skill is -1, the help page will be first displayed (only if it has not been displayed before).
                if ($questionSkill == -1 && !in_array($helpPage, $this->displayedHelp)) {
                    $this->displayedHelp[] = $helpPage;
                    $questions[] = $this->displayHelp($helpPage);
                }
            } else {
                // When training, the help page will always be displayed at the beginning
                $questionType = $_GET['questionType'];
                $questionSkill = 2; // Todo: get user skill for this question type
                if ($i == 0) {
                    $questions[] = $this->displayHelp($this->getHelpPage($questionType));
                }
            }
            switch ($questionType) {
                case 'notesOfChord':
                    $questions[] = $this->notesOfChordQuestion($knowledge, $questionSkill);
                    break;
                case 'notesOfScale':
                    $questions[] = $this->notesOfScaleQuestion($knowledge, $questionSkill);
                    break;
                case 'scaleOfNotes':
                    $questions[] = $this->scaleOfNotesQuestion($knowledge, $questionSkill);
                    break;
                case 'chordOfNotes':
                    $questions[] = $this->chordOfNotesQuestion($knowledge, $questionSkill);
                    break;
                case 'chordBelongsToScale':
                    $questions[] = $this->chordBelongsToScaleQuestion($knowledge, $questionSkill);
                    break;
                case 'notesOfInterval':
                    $questions[] = $this->notesOfIntervalQuestion($knowledge, $questionSkill);
                    break;
                case 'intervalOfNotes':
                    $questions[] = $this->intervalOfNotesQuestion($knowledge, $questionSkill);
                    break;
                case 'degreeOfChord':
                    $questions[] = $this->degreeOfChordQuestion($knowledge, $questionSkill);
                    break;
                case 'chordOfDegree':
                    $questions[] = $this->chordOfDegreeQuestion($knowledge, $questionSkill);
                    break;
                /*
                 * To be developed:
                case 'areaOfChord':
                    $questions[] = $this->areaOfChordQuestion($knowledge, $question['skill']);
                    break;
                case 'substitutionOfChord':
                    $questions[] = $this->substitutionOfChordQuestion($knowledge, $question['skill']);
                    break;*/
            }
        }

        $result = json_encode(array('questions' => $questions, 'user' => $this->currentUser->getUserParams()));

        return $result;
    }

    /**
     * It creates an entry to tell the game to display a help page.
     * @param $helpPage
     * @return array
     */
    public function displayHelp($helpPage)
    {
        $question = array();
        $question['type'] = 'displayHelp';
        $question['helpTitle'] = 'Help title - '.$helpPage; // todo: translate the help title for the current question type
        $question['helpPage'] = $helpPage;
        return $question;
    }

    /**
     * It returns the help page associated to a specific question type.
     * @param $questionType
     * @return string
     */
    public function getHelpPage($questionType)
    {
        switch ($questionType) {
            case 'notesOfChord':
            case 'chordOfNotes':
                $helpPage = 'chordsHelp';
                break;
            case 'notesOfInterval':
            case 'intervalOfNotes':
                $helpPage = 'intervalsHelp';
                break;
            case 'notesOfScale':
            case 'scaleOfNotes':
            case 'chordBelongsToScale':
                $helpPage = 'scalesHelp';
                break;
            case 'degreeOfChord':
            case 'chordOfDegree':
                $helpPage = 'degreesHelp';
                break;
            default:
                $helpPage = 'intervalsHelp';
                break;
        }
        return $helpPage;
    }

    /**
     * It creates a new question where the notes of a chord are asked.
     *
     * E.g.:
     * key: 'C',
     * mode: 'ionian',
     * text: 'Tell me the notes of this chord',
     * type: 'notesOfChord',
     * chord: 'CMaj7',
     * expected: 'C,E,G,B'
     */
    public function notesOfChordQuestion($knowledge, $skill)
    {
        $chordQuestion = array();
        $chordQuestion['helpPage'] = 'chordsHelp';
        $chordQuestion['key'] = '';
        $chordQuestion['mode'] = '';
        $chordQuestion['pushedNotes'] = '';
        $chordQuestion['type'] = 'notesOfChord';
        $chordQuestion['text'] = $this->translator->translate('questions_notesOfChord');
        $note = $knowledge->getRandomNote();
        $chord = $knowledge->getRandomChord($note);
        $chordQuestion['questionElement'] = $chord;
        $notes = $knowledge->getNotesChord($chord);
        $allNotes = $knowledge->getAllNotes($notes);
        $chordQuestion['expected'] = implode(',', $notes);
        $chordQuestion['shown'] = implode(',', $allNotes);
        return $chordQuestion;
    }

    /**
     * The notes of a random chord are shown and the player must guess which chord it is.
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function chordOfNotesQuestion($knowledge, $skill)
    {
        $chordQuestion = array();
        $chordQuestion['key'] = '';
        $chordQuestion['mode'] = '';
        $chordQuestion['helpPage'] = 'chordsHelp';
        $chordQuestion['type'] = 'chordOfNotes';
        $chordQuestion['text'] = $this->translator->translate('questions_chordOfNotes');
        $chord = $knowledge->getRandomChord();
        list($tonic, $chordType) = $knowledge->getTonicAndTypeOfChord($chord);
        $notes = $knowledge->getNotesChord($chord);
        //shuffle($notes); // The notes are randomly ordered
        $chordQuestion['questionElement'] = implode(',', $notes);
        // Chords
        $wrongAnswers = $knowledge->getWrongChords($notes, 3);
        array_push($wrongAnswers, $chord);
        shuffle($wrongAnswers);
        $chordQuestion['expected'] = $chord;
        $chordQuestion['shown'] = implode(',', $wrongAnswers);
        return $chordQuestion;
    }

    /**
     * Two random notes are shown, and the user must say which interval they form
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function intervalOfNotesQuestion($knowledge, $skill)
    {
        list($tonic, $intervalNote, $interval) = $knowledge->getRandomIntervalNotes($skill);
        $allIntervals = $knowledge->getAllIntervals();
        $intervalQuestion = array();
        $intervalQuestion['key'] = '';
        $intervalQuestion['mode'] = '';
        $intervalQuestion['helpPage'] = 'intervalsHelp';
        $intervalQuestion['type'] = 'intervalOfNotes';
        $intervalQuestion['text'] = $this->translator->translate('questions_intervalOfNotes');
        $intervalQuestion['questionElement'] = $tonic . ',' . $intervalNote;
        $intervalQuestion['expected'] = $interval;
        $intervalQuestion['shown'] = implode(',', $allIntervals);
        return $intervalQuestion;
    }

    /**
     * A note and an interval are shown, and the user must say which note corresponds to such pair
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function notesOfIntervalQuestion($knowledge, $skill)
    {
        list($tonic, $intervalNote, $interval) = $knowledge->getRandomIntervalNotes($skill);
        $allNotes = $knowledge->getAllNotes(array($intervalNote));
        $intervalQuestion = array();
        $intervalQuestion['key'] = '';
        $intervalQuestion['mode'] = '';
        $intervalQuestion['helpPage'] = 'intervalsHelp';
        $intervalQuestion['type'] = 'notesOfInterval';
        $intervalQuestion['text'] = $this->translator->translate('questions_notesOfInterval');
        $intervalQuestion['questionElement'] = 'Tonic: ' . $tonic . ', Interval: ' . $interval;
        $intervalQuestion['pushedNotes'] = $tonic;
        $intervalQuestion['expected'] = $tonic . ',' . $intervalNote;
        $intervalQuestion['shown'] = implode(',', $allNotes);
        return $intervalQuestion;
    }

    /**
     * A chord, a key and an scale are shown, and the user must say which degree it belongs to.
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function degreeOfChordQuestion($knowledge, $skill)
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
        $question['helpPage'] = 'degreesHelp';
        $question['type'] = 'degreeOfChord';
        $question['text'] = $this->translator->translate('questions_degreeOfChord');
        $question['questionElement'] = 'Chord: ' . $randomChord;
        $question['expected'] = $degree;
        $question['shown'] = implode(',', $allDegrees);
        return $question;
    }

    /**
     * A scale and a degree are shown, and the user must specify which of the proposed chords
     * are a right choice for that degree in that scale.
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function chordOfDegreeQuestion($knowledge, $skill)
    {
        $tonic = $knowledge->getRandomNote();
        $scale = $knowledge->getRandomScale();
        $notesScale = $knowledge->getNotesScale($tonic, $scale);
        $allPossibleChords = $knowledge->getAllPossibleChords($notesScale, false);
        $randomChord = $allPossibleChords[rand(0, count($allPossibleChords) - 1)];
        list($tonicOfChord, $chordType) = $knowledge->getTonicAndTypeOfChord($randomChord);
        $degree = array_search($tonicOfChord, $notesScale) + 1;
        // Now we get only the valid chords of this degree for the given scale
        $numPossibleChords = count($allPossibleChords);
        $validChords = array();
        for ($possibleChordIndex = 0; $possibleChordIndex < $numPossibleChords; $possibleChordIndex++) {
            list($tonicOfCurrentChord, $chordType) = $knowledge->getTonicAndTypeOfChord($allPossibleChords[$possibleChordIndex]);
            if ($tonicOfCurrentChord == $tonicOfChord) {
                $validChords[] = $allPossibleChords[$possibleChordIndex];
            }
        }

        // Preparation of the shown question elements
        $allAlterations = $knowledge->getAllAlterationsOfNote($tonicOfChord);
        $chordTypes = $knowledge->getAllChordTypes();
        $shownElements = array();
        $numPossibleTonics = count($allAlterations);
        $numChordTypes = count($chordTypes);
        for ($baseNoteIndex = 0; $baseNoteIndex < $numPossibleTonics; $baseNoteIndex++) {
            for ($chordTypeIndex = 0; $chordTypeIndex < $numChordTypes; $chordTypeIndex++) {
                $shownElements[] = $allAlterations[$baseNoteIndex] . $chordTypes[$chordTypeIndex];
            }
        }

        $question = array();
        $question['key'] = 'Key: ' . $tonic;
        $question['mode'] = 'Scale: ' . $scale;
        $question['helpPage'] = 'degreesHelp';
        $question['type'] = 'chordOfDegree';
        $question['text'] = $this->translator->translate('questions_chordOfDegree');
        $question['questionElement'] = 'Degree: ' . $degree;
        $question['expected'] = implode(',', $validChords);
        $question['shown'] = implode(',', $shownElements);
        return $question;
    }

    /**
     * A chord, a key and an scale are shown, and the user must say which degree it belongs to.
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function chordBelongsToScaleQuestion($knowledge, $skill)
    {
        $tonic = $knowledge->getRandomNote();
        $scale = $knowledge->getRandomScale();
        $yes = $this->translator->translate('questions_yes');
        $no = $this->translator->translate('questions_no');
        $notesScale = $knowledge->getNotesScale($tonic, $scale);
        $allPossibleChords = $knowledge->getAllPossibleChords($notesScale, false);
        $randomChord = $allPossibleChords[rand(0, count($allPossibleChords) - 1)];
        $belongsToScale = $yes;
        list($tonicChord, $chordType) = $knowledge->getTonicAndTypeOfChord($randomChord);
        // Sometimes a random chord will be generated that might not belong to the scale
        $randomNumber = rand(0, 100);
        if ($randomNumber > 50) {
            $randomChord = $knowledge->getRandomChord($tonicChord);
            if (!in_array($randomChord, $allPossibleChords)) {
                $belongsToScale = $no;
            }
        }
        $question = array();
        $question['key'] = 'Key: ' . $tonic;
        $question['mode'] = 'Scale: ' . $scale;
        $question['helpPage'] = 'scalesHelp';
        $question['type'] = 'chordBelongsToScale';
        $question['text'] = $this->translator->translate('questions_chordBelongsToScale');
        $question['questionElement'] = 'Chord: ' . $randomChord;
        $question['expected'] = $belongsToScale;
        $question['shown'] = $yes . ',' . $no;
        return $question;
    }

    /**
     * The notes of a certain scale are asked.
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function notesOfScaleQuestion($knowledge, $skill)
    {
        $tonic = $knowledge->getRandomNote();
        $scale = $knowledge->getRandomScale();
        $notesScale = $knowledge->getNotesScale($tonic, $scale);
        $allNotes = $knowledge->getAllNotes($notesScale);
        $question = array();
        $question['key'] = 'Key: ' . $tonic;
        $question['pushedNotes'] = '';
        $question['helpPage'] = 'scalesHelp';
        $question['mode'] = 'Scale: ' . $scale;
        $question['type'] = 'notesOfScale';
        $question['text'] = $this->translator->translate('questions_notesOfScale');
        $question['questionElement'] = 'Key: ' . $tonic . ' Scale: ' . $scale;
        $question['expected'] = implode(',', $notesScale);
        $question['shown'] = implode(',', $allNotes);
        return $question;
    }

    /**
     * The name of the scale for a given group of notes is asked
     * @param $knowledge
     * @param $skill
     * @return array
     */
    public function scaleOfNotesQuestion($knowledge, $skill)
    {
        $tonic = $knowledge->getRandomNote();
        $scale = $knowledge->getRandomScale();
        $notesScale = $knowledge->getNotesScale($tonic, $scale);
        $allNotes = $knowledge->getAllNotes($notesScale);
        $allScales = $knowledge->getAllScales();
        $question = array();
        $question['key'] = '';
        $question['mode'] = '';
        $question['helpPage'] = 'scalesHelp';
        $question['type'] = 'scaleOfNotes';
        $question['text'] = $this->translator->translate('questions_scaleOfNotes');
        $question['questionElement'] = implode(',', $notesScale);
        $question['expected'] = $scale;
        $question['shown'] = implode(',', $allScales);
        return $question;
    }
} 