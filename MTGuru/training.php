<?php
require_once "classes/General/Knowledge.php";
require_once "config/config.php";
require_once "widgets/training.php";
//initConfig();
$knowledge = \classes\General\Knowledge::getInstance();
$knowledge->readFiles();
printTraining($knowledge);
?>