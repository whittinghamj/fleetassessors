<?php
// error reporting
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

$customers = get_customers();

$account_details['id']      = '3';
$account_details['type']    = 'customer';

debug( $customers );


// find customers for this user
$array_of_customers = array();
foreach( $customers as $customer ) {
    if( $customer['primary_contact_id'] == $account_details['id'] || $customer['secondary_contact_id'] == $account_details['id'] ) {
        $array_of_customers[] = $customer['id'];
    }
}
if( isset( $array_of_customers[0] ) ) {
    $list_of_customers = "'".implode("', '", $array_of_customers )."'";
}

echo "SELECT * 
    FROM `jobs` 
    WHERE `customer_id` IN ('".$list_of_customers."')\n\n";

// get data
$query = $conn->query( "
    SELECT * 
    FROM `jobs` 
    WHERE `customer_id` IN ('".$list_of_customers."')
" );

$jobs = $query->fetchAll( PDO::FETCH_ASSOC );

debug( $jobs );