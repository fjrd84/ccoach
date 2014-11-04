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
        <div class="currentKey">C</div>
        <div class="yourLevel">Level 2</div>
        <div class="remainingTime"></div>
        <div class="feedbackDiv">Feedback...</div>
        <div class="gameContent">
            <div class="loading"></div>
            <div class="question">
                <div class="question notesOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerItems">
                        <div class="answerItem" data-item="C">C</div>
                        <div class="answerItem" data-item="C#">C#</div>
                        <div class="answerItem" data-item="D">D</div>
                        <div class="answerItem" data-item="D#">D#</div>
                        <div class="answerItem" data-item="E">E</div>
                        <div class="answerItem" data-item="F">F</div>
                        <div class="answerItem" data-item="F#">F#</div>
                        <div class="answerItem" data-item="G">G</div>
                        <div class="answerItem" data-item="G#">G#</div>
                        <div class="answerItem" data-item="A">A</div>
                        <div class="answerItem" data-item="A#">A#</div>
                        <div class="answerItem" data-item="B">B</div>
                    </div>
                    <div class="nextButton"><a href="javascript:nextQuestion()">NEXT!!</a></div>
                </div>
                <div class="question degreeOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerItems">
                        <div class="answerItem" data-item="I">I</div>
                        <div class="answerItem" data-item="II">II</div>
                        <div class="answerItem" data-item="III">III</div>
                        <div class="answerItem" data-item="IV">IV</div>
                        <div class="answerItem" data-item="V">V</div>
                        <div class="answerItem" data-item="VI">VI</div>
                        <div class="answerItem" data-item="VII">VII</div>
                    </div>
                    <div class="nextButton"><a href="javascript:nextQuestion()">NEXT!!</a></div>
                </div>
                <div class="question areaOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerItems">
                        <div class="answerItem" data-item="T">Tonic</div>
                        <div class="answerItem" data-item="SD">Subdominant</div>
                        <div class="answerItem" data-item="D">Dominant</div>
                    </div>
                    <div class="nextButton"><a href="javascript:nextQuestion()">NEXT!!</a></div>
                </div>
                <div class="question substitutionOfChord" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionChord">CMaj7</div>
                    <div class="answerItems">
                        <div class="answerItem" data-item="CMaj7">CMaj7</div>
                        <div class="answerItem" data-item="Dm7">Dm7</div>
                        <div class="answerItem" data-item="Em7">Em7</div>
                        <div class="answerItem" data-item="FMaj7">FMaj7</div>
                        <div class="answerItem" data-item="G7">G7</div>
                        <div class="answerItem" data-item="Am7">Am7</div>
                        <div class="answerItem" data-item="B7">B7</div>
                    </div>
                    <div class="nextButton"><a href="javascript:nextQuestion()">NEXT!!</a></div>
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