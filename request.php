<?php

	require_once('settings.php');

	$email = $_SESSION['email'];
	$password = $_SESSION['password'];

	$sql = $SETTINGS['main_db']->query("SELECT id, firstname, lastname, email, password, avatar FROM accounts WHERE email = '$email' AND password = '$password'");
	$user = $sql->fetch_array();		    
	echo $user['firstname']." ".$user['lastname'];

?>