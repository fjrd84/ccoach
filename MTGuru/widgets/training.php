<?php
function printTraining($knowledge)
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
            <?php echo $_SESSION['txt'][$_SESSION['lang']]['home']['welcome'] . ' ' . $_SESSION['currentUser']['userName'] . '!!'; ?>
        </div>
        <div class="points"><?php echo 'Points: ' . $_SESSION['currentUser']['points']; ?></div>
        <div class="level"><?php echo 'Level: ' . $_SESSION['currentUser']['level']; ?></div>
        <div class="trainingButtons">
        <?php
        $questionTypes = $knowledge->getQuestionTypes();
        $numQuestionTypes = count($questionTypes);
        for ($questionTypeIndex = 0; $questionTypeIndex < $numQuestionTypes; $questionTypeIndex++) {
            echo '<div class="trainingTypeButton">';
            echo '<a href="game.php?questionType='.$questionTypes[$questionTypeIndex][0].'">' . $questionTypes[$questionTypeIndex][0] . '</a>';
            echo '</div>';
        }
        ?>
        </div>
        <div class="button" style="clear:both">
            <a href="index.php"><?= $_SESSION['txt'][$_SESSION['lang']]['home']['home'] ?></a>
        </div>
    </div>
    </body>
    </html>
<?php
}

?>