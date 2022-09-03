<?php
// error reporting
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

$account_details = account_details( $_SESSION['account']['id'] );
debug( $account_details );


$jobs = get_all_jobs();

debug( $jobs );