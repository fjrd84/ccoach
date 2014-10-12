<?php
function printHome()
{
    ?>
    <!DOCTYPE head PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/general.css">
    </head>
    <body>
    <div class="home">
        <div class="welcome">
            <?php echo $GLOBALS['txt'][$_SESSION['lang']]['home']['welcome'] . ' ' . $_SESSION['currentUser']['userName'] . '!!';?>
        </div>
        <div class="points"><?php echo 'Points: ' . $_SESSION['currentUser']['points']; ?></div>
        <div class="level"><?php echo 'Level: ' . $_SESSION['currentUser']['level']; ?></div>
        <div href="game.php" class="button">
            <a href="game.php">GO PLAY!</a>
        </div>
    </div>
    </body>
    </html>
<?php
}
?>