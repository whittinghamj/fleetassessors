<?php

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// vars
$first_name	 						= post( 'first_name' );
$last_name	 						= post( 'last_name' );
$company_name	 					= post( 'company_name' );
$email	 							= post( 'email' );
$password	 						= post( 'password' );

// existing user check
$query 		= $conn->query( "
	SELECT * 
	FROM `users` 
	WHERE `email` = '".$email."' 
" );
$user 		= $query->fetch( PDO::FETCH_ASSOC );

if( isset( $user['id'] ) ) {
	// set status message
	status_message( "danger", "Email has already been used." );

	// redirect
	go( "index.php" );
}else{
	// save data
	$insert = $conn->exec( "INSERT INTO `users` 
		(`added`,`type`,`email`,`password`,`first_name`,`last_name`,`company_name`,`status`)
		VALUE
		('".time()."', 
		'florist', 
		'".$email."',
		'".$password."',
		'".$first_name."',
		'".$last_name."',
		'".$company_name."',
		'active'
	)" );

	$user_id = $conn->lastInsertId();

	// send email confirmation
	// coming soon

	// set session vars
	$_SESSION['logged_in']					= true;
	$_SESSION['account']['id']				= $user_id;
	$_SESSION['account']['type']			= 'florist';		

	// set status message
	status_message( "danger", "Account created. Please confirm your email address and complete your profile. " );

	// redirect
	go( "dashboard.php" );
}

?>