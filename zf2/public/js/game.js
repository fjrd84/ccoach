/*global $, baseUrl, jQuery, alert*/
var questions, // Array with all the questions
    currentType = '',
    trainingQuestionType,
    trainingMode = false,
    currentQuestion = -1, // Position of the current question in the array
    currentAnswer = 0,
    currentQDiv,
    counter = 10000000, // Initial value of the counter (countdown)
    resetCounter = 100, // Amount to which the counter will be reset
    points, // Total number of points
    attemptsCount = 0, // Number of attempts for getting the right answer
    answers = [],
    solutionShown = false; // It tells if the solution has been shown or not
/*rightAnswers = [],
 feedbackTime = 1000,*/

///// FUNCTION DEFINITIONS ////////////////////////////////////////////////////////////////////////////

function showFeedback(message) {
    $('.toastMessage').empty();
    $('.toastMessage').append(message);
    $('.toastMessage').fadeIn(300);
    setTimeout(function () {
        $('.toastMessage').fadeOut(500);
    }, 600);
}

/**
 * It redirects back home.
 */
function goHome() {
    'use strict';
    $('.mainGame').children().fadeOut(500);
    $('.loading').fadeIn(300);
    window.location = baseUrl;
}

/**
 * When all the questions have been replied, the results are sent to the server.
 */
function finishRound() {
    'use strict';
    var form = document.createElement("form"),
        element1 = document.createElement("input");

    form.method = "POST";
    form.action = baseUrl + '/mtguru/index/results';

    element1.value = JSON.stringify(answers);
    element1.name = 'answers';
    form.appendChild(element1);

    document.body.appendChild(form);

    form.submit();
}

/**
 * It updates the common fields for every question.
 */
function updateCommon() {
    'use strict';
    var currentKey = $(".currentKey"),
        currentScale = $(".currentScale");
    currentKey.text(questions[currentQuestion].key);
    currentScale.text(questions[currentQuestion].mode);
}

/**
 * It displays a generic question and generic answer items.
 */
function genericQuestion() {
    currentQDiv = $(".genericQuestion");
    displayQuestion(false);
    displayAnswerItems();
}


/**
 * Used to display generic components for replying any question (only used if there's no specific
 * treatment needed for a question).
 */
function displayAnswerItems() {
    'use strict';
    var questionDiv = $(".genericQuestion"),
        i,
        newDiv,
        shown = (questions[currentQuestion].shown).split(",");
    questionDiv.find("#answerItems").empty();

    for (i = 0; i < shown.length; i += 1) {
        newDiv = '<div class="answerItem" data-item="' + shown[i] + '">' + shown[i] + '</div>';
        questionDiv.find("#answerItems").append(newDiv);
    }
    currentQDiv = questionDiv;
    updateCommon();
    questionDiv.fadeIn(1000);
}

/**
 * It tells if sharp notes are present among the right answers.
 */
function useSharps(notes) {
    'use strict';
    var numAnswers = notes.length,
        i;
    for (i = 0; i < numAnswers; i += 1) {
        if (notes[i].indexOf('#') > -1) {
            return true;
        }
        if (notes[i].indexOf('b') > -1) {
            return false;
        }
    }
    // If no sharps nor flats are found, a random boolean is returned.
    return Math.random() < 0.5;
}

/**
 * Used to display a question about the notes of a chord/interval.
 */
function questionNotesOfX() {
    'use strict';
    var pushedNotes = questions[currentQuestion].pushedNotes.split(','),
        questionDiv = $(".genericQuestion");
    // The note tester is shown for the user to tell the answer notes
    $('.noteTesterWrapper').removeClass('hidden');
    $('.noteTesterWrapper').fadeIn(300);
    noteTester.resetNotes();
    noteTester.useSharps = useSharps(questions[currentQuestion].expected);
    if(currentType === 'notesOfScale'){
        noteTester.allowEverything = true;
    }else{
        noteTester.allowEverything = false;
    }
    if(pushedNotes[0]!==""){
        noteTester.pushNotes(pushedNotes);
    }
    noteTester.addListeners();
    currentQDiv = questionDiv;
    displayQuestion();
}

/**
 * It shuffles an array.
 * @param o
 * @returns {*}
 */
function shuffle(o) {
    for (var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
};

/**
 * It randomizes the order of the elements in a CSV string, with ',' as separator.
 */
function shuffleElements(elements) {
    var elementsArray = elements.split(',');
    elementsArray = shuffle(elementsArray);
    return elementsArray.join();

}

/**
 * It displays the common elements of a question (question and question element).
 */
function displayQuestion(randomize) {
    'use strict';
    randomize = randomize === undefined ? true : randomize;
    currentQDiv.find("#answerItems").empty();
    currentQDiv.find(".questionText").text(questions[currentQuestion].text);
    if(randomize){
        currentQDiv.find(".questionElement").text(shuffleElements(questions[currentQuestion].questionElement));
    }else{
        currentQDiv.find(".questionElement").text(questions[currentQuestion].questionElement);
    }
    currentQDiv.fadeIn(1000);
}

/**
 * It displays a question of type 'chordOfNotes'.
 */
function questionChordOfNotes() {
    'use strict';
    var chordNotes = questions[currentQuestion].questionElement.split(',');
    currentQDiv = $(".genericQuestion");
    // The note tester is shown for the user to see the notes of the chord
    $('.noteTesterWrapper').removeClass('hidden');
    $('.noteTesterWrapper').fadeIn(300);
    noteTester.resetNotes();
    noteTester.useSharps = useSharps(questions[currentQuestion].questionElement);
    noteTester.pushNotes(chordNotes);
    displayQuestion(true); // In the american notation, the notes will be randomized
    displayAnswerItems();
}

/**
 * It displays a question of type 'chordOfNotes'.
 */
function questionScaleOfNotes() {
    'use strict';
    var scaleNotes = questions[currentQuestion].questionElement.split(',');
    currentQDiv = $(".genericQuestion");
    // The note tester is shown for the user to see the notes of the chord
    $('.noteTesterWrapper').removeClass('hidden');
    $('.noteTesterWrapper').fadeIn(300);
    $('#comboBoxWrapper').removeClass('hidden');
    $('#comboBoxWrapper').fadeIn(300);
    noteTester.resetNotes();
    noteTester.useSharps = useSharps(questions[currentQuestion].questionElement);
    noteTester.pushNotes(scaleNotes);
    displayQuestion(false); // In the american notation, the notes will NOT be randomized
    //displayAnswerItems();
    fillInCombobox();
}

/**
 * It fills in the combobox with the current options.
 * @param options
 */
function fillInCombobox(){
    'use strict';
    var questionDiv = $(".genericQuestion"),
        i,
        shown = (questions[currentQuestion].shown).split(","),
        comboBox = $("#comboBoxWrapper .comboBox");
    comboBox.empty();
    comboBox.append('<option value="">'+'Select...'+'</option>')
    for (i = 0; i < shown.length; i += 1) {
        comboBox.append('<option value="'+shown[i]+'">'+shown[i]+'</option>')
    }
}

/**
 * It displays a question of type 'chordOfNotes'.
 */
function questionIntervalOfNotes() {
    'use strict';
    var intervalNotes = questions[currentQuestion].questionElement.split(',');
    currentQDiv = $(".genericQuestion");
    // The note tester is shown for the user to see the notes of the chord
    $('.noteTesterWrapper').removeClass('hidden');
    $('.noteTesterWrapper').fadeIn(300);
    $('.intervalSelectorWrapper').removeClass('hidden');
    $('.intervalSelectorWrapper').fadeIn(300);
    intervalSelector.resetSelector();
    intervalSelector.addListeners();
    noteTester.resetNotes();
    noteTester.removeListeners();
    noteTester.useSharps = useSharps(questions[currentQuestion].questionElement);
    noteTester.pushNotes(intervalNotes);
    displayQuestion(false);
}

/**
 * It adds the given answer to the array of answers and checks if it is right.
 * @param answer
 */
/*function addAnswerOld(answer) {
 'use strict';
 if (currentAnswer === 0) {
 answers[currentQuestion] = [];
 if (questions[currentQuestion].expected.toString().indexOf(',') === -1) {
 rightAnswers = [];
 rightAnswers[0] = questions[currentQuestion].expected.toString();
 } else {
 rightAnswers = questions[currentQuestion].expected.split(",");
 }
 }

 //answers[currentQuestion][currentAnswer] = answer;
 answers[currentQuestion][0] = questions[currentQuestion].type;
 currentAnswer += 1;
 if ($.inArray(answer.toString(), rightAnswers) === -1) {
 // When a bad answer is given, we advance automatically to the next question
 // (but this one will be asked again later).
 answers[currentQuestion].push('0');
 showBad();
 questions[questions.length] = questions[currentQuestion];
 setTimeout(nextQuestion, delayAfter);
 return;
 }
 answers[currentQuestion].push('1');
 showGood();
 // When all the right answers have been given, the next question is shown.
 if (currentAnswer === rightAnswers.length) {
 setTimeout(nextQuestion, delayAfter);
 }
 }*/

/**
 * It jumps into the next question (second part). 
 */
function nextQuestionCont() {
    'use strict';
    counter = resetCounter;
    currentType = questions[currentQuestion].type;

    $('.gameWrapper').fadeIn(300);

    // In case a specific question type requires a special treatment, it will be performed here.
    switch (currentType) {
        case 'notesOfChord':
        case 'notesOfInterval':
        case 'notesOfScale':
            questionNotesOfX();
            break;
        case 'chordOfNotes':
            questionChordOfNotes();
            break;
        case 'intervalOfNotes':
            questionIntervalOfNotes();
            break;
        case 'scaleOfNotes':
            questionScaleOfNotes();
            break;
        default:
            genericQuestion();
    }

    // Event listeners
    $(".answerItem").click(function () {
        addAnswer($(this));
    });
}

function addAnswer(answerElement) {
    'use strict';
    var value = answerElement.data("item");
    sendAnswer(value);
}


function timerDown() {
    'use strict';
    counter = counter - 1;
    if (counter / resetCounter < 0.1) {
        $('.whiteDot1').fadeOut(300);
    } else if (counter / resetCounter < 0.25) {
        $('.whiteDot2').fadeOut(300);
    } else if (counter / resetCounter < 0.50) {
        $('.whiteDot3').fadeOut(300);
    } else if (counter / resetCounter < 0.75) {
        $('.whiteDot4').fadeOut(300);
    }

    if (counter === 0) {
        showFeedback('Too late!!');
        showSolution();
    }
}

/**
 * It sets back the question parameters to default values.
 */
function resetParameters() {
    'use strict';
    $('.whiteDot1, .whiteDot2, .whiteDot3, .whiteDot4').fadeIn(300);
    $('.noteTesterWrapper').addClass('hidden')
    $('.intervalSelectorWrapper').addClass('hidden');
    $('#comboBoxWrapper').addClass('hidden');
    solutionShown = false;
    attemptsCount = 0;
}

/**
 * The questions are asked to the user
 */
function nextQuestion() {
    'use strict';

    // For a new question, the control parameters are reset.
    resetParameters();
    // Answering tools are hidden
    $('.gameWrapper').fadeOut(300);

    currentQuestion += 1;
    if (currentQuestion >= questions.length) {
        alert('finished!');
        startGame();
        //finishRound();
        return;
    }

    currentAnswer = 0;

    $(".answeredItem").each(function () {
        $(this).removeClass("answeredItem");
        $(this).addClass("answerItem");
    });
    // For a proper fade in/out animation, a timeout is set to effectively display the next question.
    setTimeout(nextQuestionCont, 400);
}

/**
 * When starting the game, questions are retrieved from the server
 */
function startGame() {
    'use strict';
    var getVars = "";
    if (trainingQuestionType === undefined || trainingQuestionType === "") {
        getVars = "";
    } else {
        getVars = {"questionType": trainingQuestionType};
        $('.yourPoints').empty();
        $('.yourPoints').append('Training');
        trainingMode = true;
    }
    $.get(baseUrl + '/mtguru/ajax/game', getVars, function (data) {
        // TODO: Handle errors in data
        processData(JSON.parse(data));
    });
}

function updatePoints() {
    'use strict';
    $('.yourPoints').empty();
    $('.yourPoints').append(points);
}

/**
 * The incoming data for the questions is processed here.
 * @param data
 */
function processData(data) {
    'use strict';
    console.log(data);
    $('.loading').hide();
    //$('.yourLevel').append(data.user.level);
    // Only the points of the current session will be displayed
    points = 0;//data.user.points;
    questions = data.questions;
    currentQuestion = -1;
    currentQDiv = null;
    if (!trainingMode) {
        updatePoints();
    }
    nextQuestion();
}

/**
 * It updates the answers array with the results of the current question.
 */
function updateAnswers() {
    'use strict';
    // solutionShown? attemptsCount? question?
    var answerInfo = {};
    answerInfo.type = questions[currentQuestion].type;
    answerInfo.questionElement = questions[currentQuestion].questionElement;
    answerInfo.solutionShown = solutionShown;
    answerInfo.attemptsCount = attemptsCount;
    answerInfo.timeLeft = counter;
    answers.push(answerInfo);
}

/**
 * A right answer has been given. The answers array is updated and the next question is displayed.
 */
function rightAnswer() {
    'use strict';
    // When the solution has been shown, no points are added
    if (!solutionShown) {
        console.log('well done!!');
        showFeedback('Well done!!');
        if (attemptsCount == 0) {
            points += 10;
        } else {
            points += Math.floor(5 / attemptsCount);
        }
    }
    updateAnswers();
    if (!trainingMode) {
        updatePoints();
    }
    nextQuestion();
}

/**
 * A wrong answer has been given, and feedback must be shown.
 */
function wrongAnswer() {
    'use strict';
    attemptsCount += 1;
    console.log('wrong answer!');
    // todo: feedback info about the current question
    $('.feedbackDiv').fadeIn(300);
}

/**
 * The user gave a wrong response but wants to try again
 */
function tryAgain() {
    'use strict';
    $('.feedbackDiv').fadeOut(300);
}

/**
 * The right solution is displayed on the noteTester
 */
function showSolution() {
    'use strict';
    solutionShown = true;
    switch (currentType) {
        case 'notesOfChord':
        case 'notesOfInterval':
        case 'notesOfScale':
            showSolutionNotesOfX();
            break;
        case 'chordOfNotes':
            showSolutionChordOfNotes();
            break;
        case 'intervalOfNotes':
            showSolutionIntervalOfNotes();
            break;
        case 'scaleOfNotes':
            showSolutionScaleOfNotes();
            break;
        default:
            showSolutionGeneric();
            break;
    }
    $('.feedbackDiv').fadeOut(300);
}

/**
 * It shows the solution for a question of type 'notesOfChord'
 */
function showSolutionNotesOfX() {
    'use strict';
    var expectedNotes = questions[currentQuestion].expected.split(',');
    noteTester.resetNotes();
    noteTester.pushNotes(expectedNotes);
}

/**
 * It shows the solution for a question of type 'chordOfNotes'
 */
function showSolutionChordOfNotes() {
    $('.answerItem').each(function () {
        if ($(this).data('item') == questions[currentQuestion].expected) {
            $(this).addClass('answeredItem');
        }
    });
}

/**
 * It shows the solution for a question of type 'intervalOfNotes'
 */
function showSolutionIntervalOfNotes() {
    intervalSelector.selectInterval(questions[currentQuestion].expected);
}

/**
 * It shows the solution for a question of type 'scaleOfNotes'
 */
function showSolutionScaleOfNotes() {
    $('#comboBoxWrapper .comboBox').val(questions[currentQuestion].expected);
}

/**
 * It shows the solution for a generic question.
 */
function showSolutionGeneric() {
    'use strict';
    // TODO
}

function sendAnswer(data) {
    'use strict';
    switch (currentType) {
        case 'notesOfChord':
        case 'notesOfInterval':
        case 'notesOfScale':
            sendNotes();
            break;
        case 'intervalOfNotes':
            sendInterval();
            break;
        case 'scaleOfNotes':
            sendScale();
            break;
        default:
            sendData(data);
            break;
    }
}

/**
 * The keyboard can get a bit messy after trying some attempts. This method will clean it to its original state
 * after showing the current question.
 */
function resetAnswer() {
    'use strict';
    switch (currentType) {
        case 'notesOfChord':
        case 'notesOfInterval':
        case 'notesOfScale':
            noteTester.resetNotes();
            noteTester.pushNotes(questions[currentQuestion].pushedNotes.split(','));
            break;
        case 'intervalOfNotes':
            intervalSelector.resetSelector();
            break;
        default:
            // todo
            break;
    }
}

/**
 * Used when only one element is expected as an answer.
 */
function sendData(data) {
    'use strict';
    if (data === questions[currentQuestion].expected) {
        rightAnswer();
    } else {
        wrongAnswer();
    }
}

/**
 * The user sends the notes currently selected on the noteTester.
 */
function sendNotes() {
    'use strict';
    var answeredNotes = noteTester.getNotesNoOctaves(),
        expectedNotes = questions[currentQuestion].expected.split(','),
        noteIndex,
        i;
    if (answeredNotes.length !== expectedNotes.length) {
        wrongAnswer();
        return;
    }
    for (i = 0; i < answeredNotes.length; i += 1) {
        noteIndex = $.inArray(answeredNotes[i], expectedNotes);
        if (noteIndex === -1) {
            wrongAnswer();
            return;
        }
    }
    rightAnswer();
}

/**
 * The currently seleted interval is sent an answer.
 */
function sendInterval(){
    'use strict';
    sendData(intervalSelector.getInterval());
}

function sendScale(){
    'use strict';
    sendData($('#comboBoxWrapper .comboBox').val());
}

//////////////////////////////////////////////////////////////////////////////////////


// Timer events
setInterval(function () {
    'use strict';
    timerDown();
}, 500);