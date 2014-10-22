<?php
function printMainGame($data = "ThisIsTheGame")
{
    ?>

    <!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <script src="js/jquery-1.11.1.min.js"></script>

        <link rel="stylesheet" type="text/css" href="css/general.css">
    </head>
    <body>
    <div class="mainGame">
        <div class="yourPoints">12345</div>
        <div class="currentScale">Ionian</div>
        <div class="yourLevel">Level 2</div>
        <div class="remainingTime"></div>
        <div class="gameContent">
            <div class="loading"></div>
            <div class="question">
                <div class="question notesOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerNotes">
                        <div class="answerNote" data-note="C">C</div>
                        <div class="answerNote" data-note="C#">C#</div>
                        <div class="answerNote" data-note="D">D</div>
                        <div class="answerNote" data-note="D#">D#</div>
                        <div class="answerNote" data-note="E">E</div>
                        <div class="answerNote" data-note="F">F</div>
                        <div class="answerNote" data-note="F#">F#</div>
                        <div class="answerNote" data-note="G">G</div>
                        <div class="answerNote" data-note="G#">G#</div>
                        <div class="answerNote" data-note="A">A</div>
                        <div class="answerNote" data-note="A#">A#</div>
                        <div class="answerNote" data-note="B">B</div>
                    </div>
                    <div class="nextButton"><a href="javascript:hideQuestion()">NEXT!!</a></div>
                </div>
                <div class="question degreeOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerDegrees">
                        <div class="answerDegree" data-degree="1">I</div>
                        <div class="answerDegree" data-degree="2">II</div>
                        <div class="answerDegree" data-degree="3">III</div>
                        <div class="answerDegree" data-degree="4">IV</div>
                        <div class="answerDegree" data-degree="5">V</div>
                        <div class="answerDegree" data-degree="6">VI</div>
                        <div class="answerDegree" data-degree="7">VII</div>
                    </div>
                    <div class="nextButton"><a href="javascript:hideQuestion()">NEXT!!</a></div>
                </div>
                <div class="question areaOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerAreas">
                        <div class="answerArea" data-area="T">Tonic</div>
                        <div class="answerArea" data-area="SD">Subdominant</div>
                        <div class="answerArea" data-area="D">Dominant</div>
                    </div>
                    <div class="nextButton"><a href="javascript:hideQuestion()">NEXT!!</a></div>
                </div>
                <div class="question substitutionOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerChords">
                        <div class="answerChord" data-chord="1">CMaj7</div>
                        <div class="answerChord" data-chord="2">Dm7</div>
                        <div class="answerChord" data-chord="3">Em7</div>
                        <div class="answerChord" data-chord="4">FMaj7</div>
                        <div class="answerChord" data-chord="5">G7</div>
                        <div class="answerChord" data-chord="6">Am7</div>
                        <div class="answerChord" data-chord="7">B7</div>
                    </div>
                    <div class="nextButton"><a href="javascript:hideQuestion()">NEXT!!</a></div>
                </div>
            </div>
        </div>
        <div class="button bottomRight">
            <a href="index.php">GO HOME!</a>
        </div>
    </div>
    </body>
    <script src="js/game.js"></script>
    <script>
        startGame();
    </script>
    </html>
<?php
}

?>