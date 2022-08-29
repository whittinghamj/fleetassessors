<?php
// error reporting
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// get data
$query = $conn->query( "
    SELECT * 
    FROM `jobs` 
" );
$data = $query->fetch( PDO::FETCH_ASSOC );

// create blank array
$results = array();

$count = 0;

// loop over data
foreach( $data as $bit ) {
    // convert added to added_date
    $date = date( "d-m-Y", $bit['added'] );

    // update record
    $update = $conn->exec( "UPDATE `jobs` SET `added_date` = '".$date."' WHERE `id` = '".$bit['id']['id']."' " );

}

