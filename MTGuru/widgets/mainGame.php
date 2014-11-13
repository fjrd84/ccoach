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
        <div class="currentScale"></div>
        <div class="currentKey"></div>
        <div class="yourLevel">Level 2</div>
        <div class="remainingTime"></div>
        <div class="feedbackDiv"></div>
        <div class="nextButton"><a href="javascript:nextQuestion()">NEXT!!</a></div>
        <div class="gameContent">
            <div class="loading"></div>
            <div class="question">
                <div class="question genericQuestion" style="display:none">
                    <div class="questionText">Question text</div>
                    <div class="questionElement"></div>
                    <div id="answerItemsWrapper">
                        <div class="answerItems">
                            <div class="answerItem" data-item="CMaj7">CMaj7</div>
                        </div>
                    </div>
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