<?php
require_once "widgets/mainGame.php";
$questionType = $_GET['questionType'];
if(isset($questionType)){
    echo '<script>var trainingQuestionType="'.$questionType.'";</script>';
}
printMainGame();