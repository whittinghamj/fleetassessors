<?php

// set vars
$online = 0;
$offline = 0;
$last_month = strtotime( '-1 month' );

// include main functions
include( dirname(__FILE__).'/../includes/core.php' );
include( dirname(__FILE__).'/../includes/functions.php' );

if( $globals['dev'] == true ) {
	console_output( '========================================' );
	console_output( 'Assign Orders to Fallback Florists' );
	console_output( 'Date & Time: '.date( 'Y-m-d H:i:s', time() ) );
	console_output( '========================================' );
	console_output( '' );
}

// blank arrays
$orders 				= array();
$florists 				= array();

// get data
$all_orders 			= get_orders();

// loop over data and find orders over 4 hours old
foreach( $all_orders as $order ) {
	$now = time();
	$order_age = ( $now - $order['added'] );
	if( $order['status'] == 'new_order' && $order_age > $globals['order_age_fallback'] ) {
		$order['delivery_details'] = get_delivery_detail( $order['delivery_id'] );
		$orders[] = $order;
	}
}
console_output( 'Total Fallback Orders: '.number_format( count( $orders ) ) );

// get data
$all_florists 		= get_users( 'florist' );

// loop over data and find only elite florsts
foreach( $all_florists as $florist ) {
	if( $florist['fallback_florist'] == 'yes' ) {
		$florists[] = $florist;
	}
}
console_output( 'Total Elite Florists: '.number_format( count( $florists ) ) );

// loop over data
foreach( $florists as $florist ) {
	// get coverage area for 
	$coverage_area = explode( ',', $florist['secondary_coverage_area'] );
	$coverage_area = array_filter( $coverage_area );

	// bank array
	$fallback_orders = array();

	// loop over fallback orders and see if this florist covers the delivery address
	foreach( $orders as $order ) {
		// is this order in the same country as selected florist
		if( $order['delivery_details']['address_country'] == $florist['address_country'] ) {
			// is order in the coverage area
			foreach( $coverage_area as $id => $area ) {
				// php version of LIKE to match coverage area zipcode to delivery zipcode
				if( strpos( $order['delivery_details']['address_zip'], $area ) !== false ) {
				    // match found, assign to elite florist
					
					// save data
					$update = $conn->exec( "UPDATE `orders` SET `status` = 'building' WHERE `id` = '".$order['id']."' " );
					$update = $conn->exec( "UPDATE `orders` SET `accepted` = 'yes' WHERE `id` = '".$order['id']."' " );
					$update = $conn->exec( "UPDATE `orders` SET `destination_florist_id` = '".$florist['id']."' WHERE `id` = '".$order['id']."' " );
					$update = $conn->exec( "UPDATE `orders` SET `fallback_order` = 'yes' WHERE `id` = '".$order['id']."' " );

					console_output( 'Elite Florist Matched: ('.$florist['id'].') '.$florist['company_name'] );

					break;
				}
			}
		}
	}
}

if( $globals['dev'] == true ) {
	console_output( '' );
	console_output( '========================================' );
	console_output( '' );
	console_output( '========================================' );
	console_output( 'Finished' );
	console_output( '========================================' );
}

