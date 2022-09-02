<?php
// error reporting
ini_set ( 'display_errors', 1);
ini_set ( 'display_startup_errors', 1);
error_reporting (E_ALL);

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// redirect to index.php
go( '../index.php' );