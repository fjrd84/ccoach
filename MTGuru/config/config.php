<?php 
//require_once ".../classes/User.php";
require_once "lang.php";

/**
 * TODO: Integrate in framework. This is just an example.
 */
function initConfig(){
	session_start();
	$_SESSION['currentUser']['userName'] = 'jdonado';
	$_SESSION['currentUser']['pass'] = 'pass';
	$_SESSION['currentUser']['points'] = '12345';
	$_SESSION['currentUser']['level'] = '2';
    $_SESSION['lang']='en';
}