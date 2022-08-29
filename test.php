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
" );
$data = $query->fetchAll( PDO::FETCH_ASSOC );

// loop over data
foreach( $data as $bit ) {
    // debug( $bit );

    // convert added to added_date
    $date = date( "d-m-Y", $bit['added'] );

    console_output( "added = ".$bit['added']." | added_date = ".$date );

    // update record
    // $update = $conn->exec( "UPDATE `jobs` SET `added_date` = '".$date."' WHERE `id` = '".$bit['id']['id']."' " );

}

