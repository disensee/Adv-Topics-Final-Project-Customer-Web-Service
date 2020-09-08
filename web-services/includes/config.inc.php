<?php
// this is the main configuration file for the website

session_start(); // enable sessions for all requests

// Set up custom error and exception handling
// Note the myErrorHandler and myExceptionHandler functions are defined below
set_error_handler("myErrorHandler");
set_exception_handler("myExceptionHandler");

// detect which environment the code is running in
if($_SERVER['SERVER_NAME'] == "localhost"){
	// DEV ENVIRONMENT SETTINGS
	define("DEBUG_MODE", true);
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB_NAME", "adv_topics");
	define("SITE_ADMIN_EMAIL", "PUT EMAIL ADDRESS HERE");
	define("SITE_DOMAIN", $_SERVER['SERVER_NAME']);
}else{
	// PRODUCTION SETTINGS
	define("DEBUG_MODE", false); 
	// you may want to set DEBUG_MODE to true when you 
	// are first setting up your live site, but once you get
	// everything working you'd want it off.
	define("DB_HOST", "localhost");
	define("DB_USER", "dylanise_adv_user");
	define("DB_PASSWORD", "3Vn7}GDCfIeM");
	define("DB_NAME", "dylanise_adv_topics");
	define("SITE_ADMIN_EMAIL", "PUT EMAIL ADDRESS HERE");
	define("SITE_DOMAIN", $_SERVER['SERVER_NAME']);
}

// if we are in debug mode then display all errors and set error reporting to all 
if(DEBUG_MODE){
	// turn on error messages
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// the $link variable will be our connection to the database
$link = null;


///////////////////////////
// GLOBAL FUNCTIONS
//////////////////////////

function get_link(){

	global $link;
		
	if($link == null){
		
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if(!$link){
			throw new Exception(mysqli_connect_error()); 
		}
	}

	return $link;
}

/*
*  CUSTOM ERROR HANDLING FUNCTION
*/
function myErrorHandler($errno, $errstr, $errfile, $errline){

	$str = "THIS IS OUR CUSTOM ERROR HANDLER<br>";
	$str .= "ERROR NUMBER: " . $errno . "<br>ERROR MSG: " . $errstr . "<br>FILE: " . $errfile . "<br>LINE NUMBER: " . $errline . "<br><br>";
	
	if(DEBUG_MODE){
		echo($str);
	}else{
		// You might want to send all the super globals with the error message 
		$str .= print_r($_POST, true);
		$str .= print_r($_GET, true);
		$str .= print_r($_SERVER, true);
		$str .= print_r($_FILES, true);
		$str .= print_r($_COOKIE, true);
		$str .= print_r($_SESSION, true);
		$str .= print_r($_REQUEST, true);
		$str .= print_r($_ENV, true);
		
		//send email to web admin
		//mail(SITE_ADMIN_EMAIL, SITE_DOMAIN . " - ERROR", $str);
		
		//TODO: echo a nice message to the user, or redirect to an error page
		die("We are sorry, there has been an error. But we have been notified and are working in it.");
	}
}


/*
* CUSTOM EXCEPTION HANDLING FUNCTION
*/
function myExceptionHandler($exception) {

	$str = "<h1>THIS IS OUR CUSTOM EXCEPTION HANDLER</h1>";
	

    if(DEBUG_MODE){
    	echo($str);
		var_dump($exception);
	}else{
		//How to handle exceptions???
		// You might want to send all the super globals with the error message 
		$str .= print_r($exception, true);
		$str .= print_r($_POST, true);
		$str .= print_r($_GET, true);
		$str .= print_r($_SERVER, true);
		$str .= print_r($_FILES, true);
		$str .= print_r($_COOKIE, true);
		$str .= print_r($_SESSION, true);
		$str .= print_r($_REQUEST, true);
		$str .= print_r($_ENV, true);
		
		//send email to web admin
		//mail(SITE_ADMIN_EMAIL, SITE_DOMAIN . " - EXCEPTION", $str);
		die("We're sorry, there was an error and we have been notified of it (TODO: redirect to a nice looking 'sorry' page)");
	}
}

