<?php

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// vars
$email	 						= post( 'email' );
$password 						= post( 'password' );

// login check
$query 		= $conn->query( "
	SELECT * 
	FROM `users` 
	WHERE `email` = '".$email."' 
	AND `password` = '".$password."' 
" );
$user 		= $query->fetch( PDO::FETCH_ASSOC );

if( isset( $user['id'] ) ) {
	if( $user['status'] == 'active' ) {
		// set session vars
		$_SESSION['logged_in']					= true;
		$_SESSION['account']['id']				= $user['id'];
		$_SESSION['account']['type']			= $user['type'];		

		// save data
		$update = $conn->exec( "UPDATE `users` SET `last_login_timestamp` = '".time()."' WHERE `id` = '".$user['id']."' " );
		$update = $conn->exec( "UPDATE `users` SET `last_login_ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '".$user['id']."' " );

		// redirect
		go( "dashboard.php" );
	}else{
		// set status message
		status_message( "danger", "Account Status: ".ucfirst( $user['status'] ) );

		// redirect
		go( "index.php" );
	}
}else{
	// set status message
	status_message( "danger", "Username and / or password incorrect" );

	// redirect
	go( "index.php" );
}

?>