<?php
/** SESSION-RELATED SETTINGS **/
// Force users to have cookies enabled
ini_set('session.use_only_cookies', 1);
// Session-related security setting
ini_set('session.use_trans_sid', 0);
// Define maximum session validation time
ini_set('session.gc_maxlifetime', 60 * 60 * 24); // 1 day
// Define timezone
date_default_timezone_set("Europe/Lisbon");

/** OTHER SETTINGS **/
// Start user session
session_start();
// Create connection to the database
$SETTINGS['main_db'] = new mysqli('localhost', 'root', 'ovelha19?', 'socialmedia');
if ($SETTINGS['main_db']->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 
// Allowed website screens (pages)
$SETTINGS['allowed_screens'] = array("home", "about", "logout"); 
// Default screen that is shown when none or invalid is provided
$SETTINGS['default_screen'] = "home";
// Path where user-uploaded images will be stored in the server
$SETTINGS['server_images_path'] = "image_uploads/";
// Accounts table name in the db
$SETTINGS['db_accounts_table'] = 'accounts';


/** CHECKINGS **/

// If the user is logged in, check if his/her session was hijacked
if(isset($_SESSION['security_check']) && isset($_SESSION['email']) && $_SESSION['security_check'] != 
	hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $_SESSION['email']))
	terminateUserSession();

/** FUNCTIONS **/
/* Terminates a user session properly */
function terminateUserSession(){
	if(isset($_SESSION)){
		$_SESSION = array();
		setcookie(session_name(), '', time() - 2592000, '/');
		session_unset();
		session_regenerate_id(); 
		session_destroy();
	}
}
/* General function that validates (sanitizes) user input */
function validateUserInput($input, $db){
	$input = $db->real_escape_string($input);
	$input = stripslashes($input);
	$input = strip_tags($input);
	$input = htmlentities($input);
	return $input;
}

/* Authenticates (or not...) this user */
function loginUser($username, $password, $db){
	$password = hash('ripemd128', $password);
    $stmt = $db->prepare("SELECT * FROM accounts WHERE username = ? AND password = ?");
    if(!$stmt){
        die($db->error);
    }else{
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $user = $stmt->get_result();
        if($user->num_rows > 0){
	        $user = $user->fetch_assoc();
			session_regenerate_id();        
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	      	$_SESSION['security_check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $user['email']);
	      	$_SESSION['id'] = $user['id'];
	      	$_SESSION['email'] = $user['email'];
	      	$_SESSION['password'] = $user['password'];
	     	$_SESSION['firstname'] = $user['firstname'];
			$_SESSION['lastname'] = $user['lastname'];
	     	$_SESSION['username'] = $user['username'];
			$_SESSION['userAvatar'] = $user['avatar'];
			$_SESSION['messageLogin'] = "Welcome " . $user['email'] . "!";
		}else{
			$_SESSION['messageLogin'] = "Ups! Username or password wrong! Please try again.";
		}
        $stmt->close();  
    }
}
?>