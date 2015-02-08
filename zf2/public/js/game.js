/*global $, jQuery, alert*/
var questions,
    currentType = '',
    trainingQuestionType,
    currentQuestion = -1,
    currentAnswer = 0,
    currentQDiv,
    counter = 9999,
    resetCounter = 9999,
    delayAfter = 300,
    answers = [],
    rightAnswers = [],
    feedbackTime = 1000;

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

function hideFeedback(divClass) {
    'use strict';
    if (divClass !== 'undefined') {
        $("." + divClass).fadeOut(500);
    } else {
        $(".feedbackDiv").fadeOut(500);
    }
}

function showFeedback(message) {
    'use strict';
    var feedbackDiv = $(".feedbackDiv"),
        div = document.createElement("div"),
        className = (Math.floor(Math.random() * 1000)).toString();
    div.className = "feedbackText " + className;
    div.style.display = "none";
    div.innerHTML = message;
    feedbackDiv.append(div);
    $("." + className).fadeIn(1000);
    //feedbackDiv.text("Good!!");
    window.setTimeout(function () {
        hideFeedback(className);
    }, feedbackTime);
}

/**
 * It shows a "Well done" message
 */
function showGood() {
    'use strict';
    showFeedback("Good!!");
}

/**
 * It shows a "You made a mistake" message
 */
function showBad() {
    'use strict';
    showFeedback("That was wrong!!");
}

function showTooLate() {
    'use strict';
    showFeedback("Too late!!");
}

/**
 * It updates the common fields for every question.
 */
function updateCommon() {
    'use strict';
    var currentKey = $(".currentKey"),
        currentScale = $(".currentScale");
    currentKey.text(questions.questions[currentQuestion].key);
    currentScale.text(questions.questions[currentQuestion].mode);
}

function genericQuestion() {
    'use strict';
    var questionDiv = $(".genericQuestion"),
        i,
        newDiv,
        shown = (questions.questions[currentQuestion].shown).split(",");
    questionDiv.find(".answerItems").empty();

    for (i = 0; i < shown.length; i += 1) {
        newDiv = '<div class="answerItem" data-item="' + shown[i] + '">' + shown[i] + '</div>';
        questionDiv.find(".answerItems").append(newDiv);
    }
    currentQDiv = questionDiv;
    updateCommon();
    questionDiv.find(".questionText").text(questions.questions[currentQuestion].text);
    questionDiv.find(".questionElement").text(questions.questions[currentQuestion].questionElement);
    //questionDiv.append("NOTES OF CHORD!!");
    questionDiv.fadeIn(1000);
}

/**
 * It tells if sharps are between the right answers.
 */
function useSharps() {
    var numAnswers = questions.questions[currentQuestion].expected.length,
        i = 0;
    for (; i < numAnswers; i += 1) {
        if (questions.questions[currentQuestion].expected[i].indexOf('#') > -1) {
            return true;
        } else if (questions.questions[currentQuestion].expected[i].indexOf('b') > -1) {
            return false;
        }
    }
    // If no sharps nor flats are found, a random boolean is returned.
    return Math.random() < .5;
}

function notesOfChord() {
    'use strict';
    var questionDiv = $(".genericQuestion"),
        i,
        newDiv,
        shown = (questions.questions[currentQuestion].shown).split(",");

    // The note tester is shown for the user to tell the answer notes
    $('.noteTesterWrapper').fadeIn(300);
    noteTester.resetNotes();
    noteTester.useSharps = useSharps();
    questionDiv.find(".answerItems").empty();
    /*
     for (i = 0; i < shown.length; i += 1) {
     newDiv = '<div class="answerItem" data-item="' + shown[i] + '">' + shown[i] + '</div>';
     questionDiv.find(".answerItems").append(newDiv);
     }*/
    currentQDiv = questionDiv;
    questionDiv.find(".questionText").text(questions.questions[currentQuestion].text);
    questionDiv.find(".questionElement").text(questions.questions[currentQuestion].questionElement);
    questionDiv.fadeIn(1000);
}

/**
 * It adds the given answer to the array of answers and checks if it is right.
 * @param answer
 */
function addAnswer(answer) {
    'use strict';
    if (currentAnswer === 0) {
        answers[currentQuestion] = [];
        if (questions.questions[currentQuestion].expected.toString().indexOf(',') === -1) {
            rightAnswers = [];
            rightAnswers[0] = questions.questions[currentQuestion].expected.toString();
        } else {
            rightAnswers = questions.questions[currentQuestion].expected.split(",");
        }
    }

    //answers[currentQuestion][currentAnswer] = answer;
    answers[currentQuestion][0] = questions.questions[currentQuestion].type;
    currentAnswer += 1;
    if ($.inArray(answer.toString(), rightAnswers) === -1) {
        // When a bad answer is given, we advance automatically to the next question
        // (but this one will be asked again later).
        answers[currentQuestion].push('0');
        showBad();
        questions.questions[questions.questions.length] = questions.questions[currentQuestion];
        setTimeout(nextQuestion, delayAfter);
        return;
    }
    answers[currentQuestion].push('1');
    showGood();
    // When all the right answers have been given, the next question is shown.
    if (currentAnswer === rightAnswers.length) {
        setTimeout(nextQuestion, delayAfter);
    }
}

function nextQuestionCont() {
    'use strict';
    counter = resetCounter;
    currentType = questions.questions[currentQuestion].type;

    // In case a specific question type requires a special treatment, it will be performed here.
    switch (currentType) {
        case 'notesOfChord':
            notesOfChord();
            break;
        default:
            genericQuestion();
    }

    // Event listeners
    $(".answerItem").click(function () {
        addAnswer($(this).data("item"));
        $(this).removeClass("answerItem");
        $(this).addClass("answeredItem");
    });
}


function timerDown() {
    'use strict';
    $(".remainingTime").
        text(counter);
    counter = counter - 1;
    if (counter === 0) {
        showTooLate();
        nextQuestion();
    }
}

// Timer events
setInterval(function () {
    'use strict';
    timerDown();
}, 500);

/**
 * The questions are asked to the user
 */
function nextQuestion() {
    'use strict';
    // Answering tools are hidden
    $('.noteTesterWrapper').fadeOut(300);
    currentQuestion += 1;
    if (currentQuestion >= questions.questions.length) {
        finishRound();
        return;
    }

    currentAnswer = 0;
// && questions.questions[currentQuestion].type != currentType
    if (currentQDiv !== null) {
        currentQDiv.fadeOut(400);
    }
    $(".answeredItem").each(function () {
        $(this).removeClass("answeredItem");
        $(this).addClass("answerItem");
    });
    setTimeout(nextQuestionCont, 400);
}

/**
 * When starting the game, questions are retrieved from the server
 */
function startGame() {
    'use strict';
    var getVars = "";
    if (trainingQuestionType === "undefined") {
        getVars = "";
    } else {
        getVars = {"questionType": trainingQuestionType};
    }
    $.get(baseUrl + '/mtguru/ajax/game', getVars, function (data) {
        // TODO: Handle errors in data
        processData(JSON.parse(data));
    });
}

function processData(data) {
    'use strict';
    console.log(data);
    $(".loading").remove();
    questions = data;
    currentQuestion = -1;
    currentQDiv = null;
    nextQuestion();
}


function rightAnswer() {
    console.log('well done!!');
}

function wrongAnswer(expectedNotes) {
    var numNotes = expectedNotes.length,
        expectedNotesOct = noteTester.notesIntoOctaves(expectedNotes),
        i;
    noteTester.resetNotes();
    for (i = 0; i < numNotes; i += 1) {
        noteTester.pushNote(expectedNotesOct[i]);
    }
}

function sendNotes() {
    'use strict';
    var answeredNotes = noteTester.getNotesNoOctaves(),
        expectedNotes = questions.questions[currentQuestion].expected.split(','),
        noteIndex,
        i;
    if (answeredNotes.length !== expectedNotes.length) {
        wrongAnswer(expectedNotes);
        return;
    }
    for (i = 0; i < answeredNotes.length; i += 1) {
        noteIndex = $.inArray(answeredNotes[i], expectedNotes);
        if (noteIndex === -1) {
            wrongAnswer(expectedNotes);
            return;
        }
    }
    rightAnswer();
}