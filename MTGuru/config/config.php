<?php 
//require_once ".../classes/User.php";
initConfig();


/**
 * TODO: Integrate in framework. This is just an example.
 */
function initConfig(){
	session_start();
    chdir("c:/S4L/VM/default/s4l/MTGuru/");
    require_once "config/lang.php";
	$_SESSION['currentUser']['userName'] = 'jdonado';
	$_SESSION['currentUser']['pass'] = 'pass';
	$_SESSION['currentUser']['points'] = '12345';
	$_SESSION['currentUser']['level'] = '2';
    $_SESSION['lang']='en';
}