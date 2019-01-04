<?php

require_once('settings.php');

// Handles login form
if(!isset($_POST['email']) || !isset($_POST['password'])){
	$_SESSION['registration_feedback'] = 'Please fill in all the required information.';
}else{
	$email = validateUserInput($_POST['email'], $SETTINGS['main_db']);
	$password = validateUserInput($_POST['password'], $SETTINGS['main_db']);
	loginUser($email, $password, $SETTINGS['main_db']);
	//header("Location: ?screen=home"); // Redirect the user
}
?>