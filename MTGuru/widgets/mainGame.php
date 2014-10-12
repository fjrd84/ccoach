<?php
function printMainGame($data = "ThisIsTheGame")
{
    ?>

    <!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/game.js"></script>
        <link rel="stylesheet" type="text/css" href="css/general.css">
    </head>
    <body>
    <div class="mainGame">
        <div class="yourPoints">12345</div>
        <div class="currentScale">Ionian</div>
        <div class="yourLevel">Level 2</div>
        <div class="remainingTime"></div>
        <div class="gameContent">MAIN GAME</div>
        <div class="button bottomRight">
            <a href="index.php">GO HOME!</a>
        </div>
    </div>
    </body>
    </html>
<?php
}

?>