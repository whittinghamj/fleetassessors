<?php
// error reporting
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// create blank array
$results = array();

// set $count
$count = 0;

// get data
$query = $conn->query( "
    SELECT * 
    FROM `jobs` 
    WHERE `customer_id` = IN ('4')
" );

$results = $query->fetchAll( PDO::FETCH_ASSOC );

debug( $results );