/**
 * Created by jdonado on 12/10/14.
 */

var questions;
var currentQuestion = 0;
var currentQDiv;

function startGame() {
    //alert("Game is starting! :D");
    $.get("ajax/getQuestions.php", { "choices[]": ["Jon", "Susan"] }, function (data) {
        //$( ".result" ).html( data );
        //alert( "Load was performed." );
        processData(JSON.parse(JSON.stringify(eval("(" + data + ")"))));
    });
}

function processData(data) {
    console.log(data);
    $(".loading").remove();
    questions = data;
    currentQuestion = 0;
    currentQDiv = null;
    nextQuestion();
}

/**
 * The questions are asked to the user
 */
function nextQuestion() {

        if (currentQuestion >= questions.questions.length) {
            finishRound();
        }

        switch (questions.questions[currentQuestion++].type) {
            case "notesOfChord":
                // notes of chord...
                notesOfChord();
                break;
            case "degreeOfChord":
                // degree of chord...
                degreeOfChord();
                break;
            case "areaOfChord":
                // area of chord...
                areaOfChord();
                break;
            default:
                //default behaviour...
                break;
        }

}

function notesOfChord() {
    var questionDiv = $(".notesOfChord");
    currentQDiv = questionDiv;
    questionDiv.append("NOTES OF CHORD!!");
    questionDiv.fadeIn(1000);
}

function hideQuestion(){
    if(currentQDiv!=null){
    currentQDiv.hide("fast", nextQuestion());
    }
}

function degreeOfChord() {
    var questionDiv = $(".degreeOfChord");
    currentQDiv = questionDiv;
    questionDiv.append("DEGREE OF CHORD!!");
    //$(".question").fadeOut(0);
    questionDiv.fadeIn(1000);
}

function areaOfChord() {
    var questionDiv = $(".areaOfChord");
    currentQDiv = questionDiv;
    questionDiv.append("AREA OF CHORD!!");
    //$(".question").fadeOut(0);
    questionDiv.fadeIn(1000);
}

function finishRound() {
    alert("Se acabó lo que se daba!");
}