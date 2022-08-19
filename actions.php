<?php

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// phpmail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// login check
if( !isset( $_SESSION['logged_in'] ) || $_SESSION['logged_in'] != true ) {
	// set status message
	status_message( "danger", "Login session expired." );

	// redirect
	go( 'index.php' );
} else {
	$account_details = account_details( $_SESSION['account']['id'] );
}

// admin check
if( $account_details['type'] == 'admin' ) {
	$admin_check = true;
} else {
	$admin_check = false;
}

// dev check
if( $account_details['email'] == 'jamie.whittingham@gmail.com' ) {
	$dev_check = true;
} else {
	$dev_check = false;
}

// staff check
if( $account_details['type'] == 'staff' ) {
	$staff_check = true;
} else {
	$staff_check = false;
}

// stripe keys
define("STRIPE_SECRET_KEY", "sk_test_sa0QRUIVgFphzWQZ0gypyAv0");
define("STRIPE_PUBLISHABLE_KEY", "pk_test_iUFUXx45G0sVuoHoKC1BeiXi");


$a = get( 'a' );

switch( $a ) {
    case "accept_terms":
        accept_terms();
        break;

    case "ajax_get_global_countries":
        ajax_get_global_countries();
        break;

    case "ajax_get_global_states":
        ajax_get_global_states();
        break;

    case "ajax_get_global_cities":
        ajax_get_global_cities();
        break;

    case "ajax_get_global_zipcodes":
        ajax_get_global_zipcodes();
        break;

    case "get_notifications":
        get_notifications();
        break;

    case "message_send":
        message_send();
        break;

    case "message_mark_read":
        message_mark_read();
        break;

    case "message_delete":
        message_delete();
        break;

    case "messages_delete":
        messages_delete();
        break;

    case "order_accept":
        order_accept();
        break;

    case "order_add":
        order_add();
        break;

    case "order_add_item":
        order_add_item();
        break;

    case "order_assign_florist":
        order_assign_florist();
        break;

    case "order_card_message":
        order_card_message();
        break;

    case "order_edit":
        order_edit();
        break;

    case "order_edit_delivery_details":
        order_edit_delivery_details();
        break;

    case "order_edit_notes":
        order_edit_notes();
        break;

    case "order_edit_status":
        order_edit_status();
        break;

    case "order_delete":
        order_delete();
        break;

    case "order_delete_item":
        order_delete_item();
        break;

    case "order_update_status":
        order_update_status();
        break;

    case "order_submit":
        order_submit();
        break;

	case "product_add":
        product_add();
        break;

    case "product_image_add":
        product_image_add();
        break;

    case "product_image_delete":
        product_image_delete();
        break;

    case "product_image_make_primary":
        product_image_make_primary();
        break;

	case "product_edit":
        product_edit();
        break;

	case "product_delete":
        product_delete();
        break;

    case "subscription_add":
        subscription_add();
        break;

    case "subscription_add_user":
        subscription_add_user();
        break;

    case "subscription_edit":
        subscription_edit();
        break;

    case "subscription_delete":
        subscription_delete();
        break;

    case "system_settings":
        system_settings();
        break;

    case "user_add":
        user_add();
        break;

    case "user_edit":
        user_edit();
        break;

    case "user_edit_coverage_area":
        user_edit_coverage_area();
        break;

    case "user_edit_secondary_coverage_area":
        user_edit_secondary_coverage_area();
        break;

    case "user_edit_from_order":
        user_edit_from_order();
        break;

    case "user_edit_remove_secondary_coverage_area":
        user_edit_remove_secondary_coverage_area();
        break;

    case "user_delete":
        user_delete();
        break;

	default:
		home();
		break;
}

function ajax_get_global_countries() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// get data
    $query = $conn->query( "
        SELECT `id`,`country` 
        FROM `global_addresses` 
        GROUP BY `country` 
        ORDER BY `country` ASC 
    " );
	$data = $query->fetchAll( PDO::FETCH_ASSOC );

    // sanity check
	$data = stripslashes_deep( $data );

	json_output( $data );
}

function ajax_get_global_states() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$country 			= get( 'country' );

	// get data
    $query = $conn->query( "
        SELECT `state` 
        FROM `global_addresses` 
        WHERE `country` = '".$country."' 
        AND `state` != '' 
        GROUP BY `state` 
        ORDER BY `state` ASC 
    " );
	$states = $query->fetchAll( PDO::FETCH_ASSOC );

    // sanity check
	$states = stripslashes_deep( $states );

	echo '<option value="" selected>Select a state</option>';
	foreach( $states as $state ) {
		echo '<option value="'.$state['state'].'">'.$state['state'].'</option>';
	}
}

function ajax_get_global_cities() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$country 			= get( 'country' );
	$state 				= get( 'state' );
	$type 				= get( 'type' );

	// get data
    $query = $conn->query( "
        SELECT `city` 
        FROM `global_addresses` 
        WHERE `country` = '".$country."' 
        AND `state` = '".$state."' 
        GROUP BY `city` 
        ORDER BY `city` ASC 
    " );
	$cities = $query->fetchAll( PDO::FETCH_ASSOC );

    // sanity check
	$cities = stripslashes_deep( $cities );

	echo '<option value="" selected>Select a city</option>';
	foreach( $cities as $city ) {
		echo '<option value="'.$city['city'].'">'.$city['city'].'</option>';
	}
}

function ajax_get_global_zipcodes() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$country 			= get( 'country' );
	$state 				= get( 'state' );
	$city 				= get( 'city' );
	$type 				= get( 'type' );

	// get data
    $query = $conn->query( "
        SELECT `user_id`,`zip_code` 
        FROM `global_addresses` 
        WHERE `country` = '".$country."' 
        AND `state` = '".$state."' 
        AND `city` = '".$city."' 
        GROUP BY `zip_code` 
        ORDER BY `zip_code` ASC 
    " );
	$zipcodes = $query->fetchAll( PDO::FETCH_ASSOC );

    // sanity check
	$zipcodes = stripslashes_deep( $zipcodes );

	// echo '<option value="" selected>Select a zip / postal code</option>';
	foreach( $zipcodes as $zipcode ) {
		if( $type == 'primary' ) {
			echo '<option value="'.$zipcode['zip_code'].'">'.$zipcode['zip_code'].'</option>';
		} else {
			if( empty( $zipcode['user_id'] ) ) {
				echo '<option value="'.$zipcode['zip_code'].'">'.$zipcode['zip_code'].'</option>';
			} else {
				echo '<option value="'.$zipcode['zip_code'].'" disabled>'.$zipcode['zip_code'].' - (already claimed)</option>';
			}
		}
	}
}

function home() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	die( 'access denied to function name '.get( 'a' ) );
}

function accept_terms() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// save data
	$update = $conn->exec( "UPDATE `users` SET `accept_terms` = 'yes' WHERE `id` = '".$account_details['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `accept_terms_date` = '".time()."' WHERE `id` = '".$account_details['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `accept_terms_ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '".$account_details['id']."' " );

	// set status message
	status_message( "success", "Terms &amp; Conditions have been accepted." );

	// redirect
	go( 'dashboard.php' );
}

function get_notifications() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// set headers
	header("Content-Type:application/json; charset=utf-8" );

	// bank array
	$data = array();

	// get data
	$query = $conn->query( "
        SELECT * 
        FROM `notifications` 
        WHERE `user_id` = '".$account_details['id']."' 
        AND `status` = 'pending' 
    " );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	// sanity check
	$data = stripslashes_deep( $data );

	// update data
	// $update = $conn->exec( "UPDATE `notifications` SET `status` = 'read' WHERE `id` = '".$data['id']."' " );

	json_output( $data );
}

function message_send() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$to_id 							= post( 'to_id' );
	$subject 						= post( 'subject' );
	$message 						= post( 'message' );

	// save data
	$insert = $conn->exec( "INSERT INTO `messages` 
		(`added`,`status`,`filter`,`to_id`,`from_id`,`subject`,`message`)
		VALUE
		('".time()."', 
		'unread', 
		'florist',
		'".$to_id."',
		'".$account_details['id']."',
		'".$subject."',
		'".$message."'
	)" );

	$user_id = $conn->lastInsertId();

	// set status message
	status_message( "success", "Message has been sent." );

	// redirect
	go( 'dashboard.php?c=messages' );
}

function message_mark_read() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id 						= get( 'id' );

	// update data
	$update = $conn->exec( "UPDATE `messages` SET `status` = 'read' WHERE `id` = '".$id."' " );
}

function message_delete() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id = get( 'id' );

	// sanity check
	$message = get_message( $id );
	if( $message['to_id'] == $account_details['id'] || $message['from_id'] == $account_details['id'] || $account_details['type'] == 'admin' ) {
		// pass

		// delete data
		$delete = $conn->exec( "DELETE FROM `messages` WHERE `id` = '".$id."' " );

		// set status message
		status_message( "success", "Message has been deleted." );
	} else {
		// fail

		// set status message
		status_message( "danger", "Permission Denied." );
	}

	// redirect
	go( 'dashboard.php?c=messages' );
}

function messages_delete() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	debug( $_POST );
	die();

	// map fields
	$id = get( 'id' );

	// sanity check
	$message = get_message( $id );
	if( $message['to_id'] == $account_details['id'] || $message['from_id'] == $account_details['id'] || $account_details['type'] == 'admin' ) {
		// pass

		// delete data
		$delete = $conn->exec( "DELETE FROM `messages` WHERE `id` = '".$id."' " );

		// set status message
		status_message( "success", "Message has been deleted." );
	} else {
		// fail

		// set status message
		status_message( "danger", "Permission Denied." );
	}

	// redirect
	go( 'dashboard.php?c=messages' );
}

function order_accept() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 						= get( 'id' );

	// get data
	$order 							= get_order( $order_id );

	// has order already been accepted
	if( $order['accepted'] == 'no' ) {
		// save data
		$update = $conn->exec( "UPDATE `orders` SET `status` = 'being_built' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `destination_florist_id` = '".$account_details['id']."' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `accepted` = 'yes' WHERE `id` = '".$order_id."' " );

		// set status message
		status_message( "success", "Order has been accepted." );
	} else {
		// set status message
		status_message( "danger", "Unfortunately this order has already been accepted by another florist." );
	}

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_add() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$customer_id 					= post( 'customer_id' );
	if( $customer_id == 'new_customer' ) {
		$first_name 					= post( 'first_name' );
		$last_name 						= post( 'last_name' );
		$email 							= post( 'email' );
		$password 						= post( 'password' );
		$type 							= 'customer';

		// save data
		$insert = $conn->exec( "INSERT IGNORE INTO `users` 
			(`added`,`type`,`email`,`password`,`first_name`,`last_name`,`status`)
			VALUE
			('".time()."', 
			'".$type."', 
			'".$email."',
			'".$password."',
			'".$first_name."',
			'".$last_name."',
			'active'
		)" );

		$customer_id = $conn->lastInsertId();
	}

	// save data
	$insert = $conn->exec( "INSERT IGNORE INTO `orders` 
		(`added`,`status`,`customer_id`,`ordering_florist_id`)
		VALUE
		('".time()."', 
		'pending', 
		'".$customer_id."',
		'".$account_details['id']."'
	)" );

	$order_id = $conn->lastInsertId();
	
	// set status message
	status_message( "success", "Order has been created." );

	// redirect
	go( 'dashboard.php?c=order&id='.$order_id );
}

function order_add_item() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 							= post( 'order_id' );
	$product_id 						= post( 'product_id' );
	$qty 								= post( 'qty' );

	// get data
	$order = get_order( $order_id );
	$product = get_product( $product_id );

	// save data
	$insert = $conn->exec( "INSERT INTO `order_items` 
		(`added`,`order_id`,`product_id`,`qty`)
		VALUE
		('".time()."', 
		'".$order_id."',
		'".$product_id."',
		'".$qty."'
	)" );

	$order_item_id = $conn->lastInsertId();
	
	// update order total
	$item_total = ( $product['price'] * $qty );
	$order_total = ( $order['total_price'] + $item_total );

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `total_price` = '".$order_total."' WHERE `id` = '".$order_id."' " );

	// set status message
	status_message( "success", "Item has been added to order." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_assign_florist() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 						= post( 'order_id' );
	$florist_id 					= post( 'florist_id' );
	if( empty( $florist_id ) ) {
		$florist_id = NULL;
	}

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `destination_florist_id` = '".$florist_id."' WHERE `id` = '".$order_id."' " );
	if( empty( $florist_id ) ) {
		$update = $conn->exec( "UPDATE `orders` SET `status` = 'new_order' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `accepted` = 'no' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `fallback_order` = 'no' WHERE `id` = '".$order_id."' " );
	} else {
		$update = $conn->exec( "UPDATE `orders` SET `status` = 'being_built' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `accepted` = 'yes' WHERE `id` = '".$order_id."' " );
	}
	
	// set status message
	status_message( "success", "Order has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_card_message() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 						= post( 'order_id' );
	$card_message 					= post( 'card_message' );

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `card_message` = '".$card_message."' WHERE `id` = '".$order_id."' " );

	// set status message
	status_message( "success", "Card Message has been saved." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_delete() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id 							= get( 'id' );

	// delete data
	$delete = $conn->exec( "DELETE FROM `orders` WHERE `id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `order_items` WHERE `order_id` = '".$id."' " );

	// set status message
	status_message( "success", "Order has been deleted." );

	// redirect
	go( 'dashboard.php?c=orders' );
}

function order_delete_item() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id 				= get( 'id' );
	$order_id 			= get( 'order_id' );
	$product_id 		= get( 'product_id' );

	// get data
	$order 				= get_order( $order_id );
	$order_item 		= get_order_item( $id );
	$product 			= get_product( $product_id );

	// delete data
	$delete = $conn->exec( "DELETE FROM `order_items` WHERE `id` = '".$id."' AND `order_id` = '".$order_id."' " );

	// update order total
	$item_total = ( $product['price'] * $order_item['qty'] );
	$order_total = ( $order['total_price'] - $item_total );

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `total_price` = '".$order_total."' WHERE `id` = '".$order_id."' " );

	// set status message
	status_message( "success", "Item has been removed from order." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_edit() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 				= post( 'order_id' );

	// set status message
	status_message( "success", "This function has not been built yet." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_edit_delivery_details() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$delivery_id 					= post( 'delivery_id' );
	$order_id 						= post( 'order_id' );
	$delivery_date 					= post( 'delivery_date' );
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );
	$address_1 						= post( 'address_1' );
	$address_2 						= post( 'address_2' );
	$address_city 					= post( 'address_city' );
	$address_state 					= post( 'address_state' );
	$address_zip 					= post( 'address_zip' );
	$address_country 				= post( 'address_country' );
	$tel_landline 					= post( 'tel_landline' );
	$tel_cell 						= post( 'tel_cell' );
	$notes 							= post( 'notes' );

	// forward geocoding
	$address_full[] = $address_1;
	$address_full[] = $address_2;
	$address_full[] = $address_city;
	$address_full[] = $address_state;
	$address_full[] = $address_zip;
	$address_full[] = $address_country;
	$address = implode( ', ', $address_full );
	$geocode_data = forward_geocoding( $address );

	if( isset( $geocode_data['features'][0]['id'] ) ) {
		$address_lat = $geocode_data['features'][0]['geometry']['coordinates'][1];
		$address_lng = $geocode_data['features'][0]['geometry']['coordinates'][0];
	} else {
		$address_lat = '';
		$address_lng = '';
	}

	// does data already exist
	$delivery_detail = get_delivery_detail( $delivery_id );
	if( isset( $delivery_detail['id'] ) ) {
		// save data
		$update = $conn->exec( "UPDATE `delivery_details` SET `first_name` = '".$first_name."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `last_name` = '".$last_name."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `email` = '".$email."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_1` = '".$address_1."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_2` = '".$address_2."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_city` = '".$address_city."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_state` = '".$address_state."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_zip` = '".$address_zip."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_country` = '".$address_country."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_lat` = '".$address_lat."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `address_lng` = '".$address_lng."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `tel_landline` = '".$tel_landline."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `tel_cell` = '".$tel_cell."' WHERE `id` = '".$delivery_id."' " );
		$update = $conn->exec( "UPDATE `delivery_details` SET `notes` = '".$notes."' WHERE `id` = '".$delivery_id."' " );
	} else {
		// save data
		$insert = $conn->exec( "INSERT INTO `delivery_details` 
			(`added`,`order_id`,`email`,`first_name`,`last_name`,`address_1`,`address_2`,`address_city`,`address_state`,`address_zip`,`address_country`,`address_lat`,`address_lng`,`notes`)
			VALUE
			('".time()."', 
			'".$order_id."', 
			'".$email."',
			'".$first_name."',
			'".$last_name."',
			'".$address_1."',
			'".$address_2."',
			'".$address_city."',
			'".$address_state."',
			'".$address_zip."',
			'".$address_country."',
			'".$address_lat."',
			'".$address_lng."',
			'".$notes."'
		)" );

		$delivery_id = $conn->lastInsertId();

		// save new delivery_id to order
		$update = $conn->exec( "UPDATE `orders` SET `delivery_id` = '".$delivery_id."' WHERE `id` = '".$order_id."' " );
	}

	// update delivery date
	$update = $conn->exec( "UPDATE `orders` SET `delivery_date` = '".$delivery_date."' WHERE `id` = '".$order_id."' " );

	// find nearest florist
	// $nearest_florist = find_closest_florist( $delivery_id );

	// save data
	// $update = $conn->exec( "UPDATE `orders` SET `destination_florist_id` = '".$nearest_florist[0]."' WHERE `id` = '".$order_id."' " );

	// set status message
	status_message( "success", "Delivery Details have been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_edit_notes() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 						= post( 'order_id' );
	$notes 							= post( 'notes' );

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `notes` = '".$notes."' WHERE `id` = '".$order_id."' " );

	// set status message
	status_message( "success", "Order has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_edit_status() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 						= post( 'order_id' );
	$status 						= post( 'status' );

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `status` = '".$status."' WHERE `id` = '".$order_id."' " );
	if( $status == 'new_order' ) {
		$update = $conn->exec( "UPDATE `orders` SET `accepted` = 'no' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `destination_florist_id` = '' WHERE `id` = '".$order_id."' " );
		$update = $conn->exec( "UPDATE `orders` SET `fallback_order` = 'no' WHERE `id` = '".$order_id."' " );
	}

	// set status message
	status_message( "success", "Order has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_submit() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id 							= get( 'id' );

	// save data
	if( $admin_check || $staff_check ) {
		$update = $conn->exec( "UPDATE `orders` SET `status` = 'new_order' WHERE `id` = '".$id."' " );
	} else {
		$update = $conn->exec( "UPDATE `orders` SET `status` = 'new_order' WHERE `id` = '".$id."' AND `ordering_florist_id` = '".$account_details['id']."' " );
	}
	// set status message
	status_message( "success", "Order has been submitted." );

	// redirect
	go( 'dashboard.php?c=orders' );
}

function order_update_status() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$order_id 						= get( 'id' );
	$status 						= get( 'status' );

	// save data
	$update = $conn->exec( "UPDATE `orders` SET `status` = '".$status."' WHERE `id` = '".$order_id."' " );

	// process order complete
	if( $status == 'complete' ) {
		// get data
		$order = get_order( $order_id );
		$user = get_user( $order['destination_florist_id'] );

		// calculate nework fee and florist profit
		$network_fee = calculate_network_fee( $order['destination_florist_id'], $order['total_price'] );
		$florist_payment_amount = ( $order['total_price'] - $network_fee );

		// calculate florist new monthly cap total
		$cap_total = ( $user['cap_total'] + $florist_payment_amount );

		// save data
		$insert = $conn->exec( "INSERT INTO `user_transactions` 
			(`added`,`status`,`user_id`,`order_id`,`payment_id`,`order_amount`,`network_fee`,`florist_payment_amount`)
			VALUE
			('".time()."',
			'complete',
			'".$order['destination_florist_id']."',
			'".$order_id."',
			'".$order['payment_id']."',
			'".$order['total_price']."',
			'".$network_fee."',
			'".$florist_payment_amount."'
		)" );

		// save data
		$update = $conn->exec( "UPDATE `users` SET `cap_total` = '".$cap_total."' WHERE `id` = '".$order['destination_florist_id']."' " );

		$user_transaction_id = $conn->lastInsertId();
	}

	// set status message
	status_message( "success", "Order has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function product_add() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$title 					= post( 'title' );

	// save data
	$insert = $conn->exec( "INSERT INTO `products` 
		(`added`,`title`)
		VALUE
		('".time()."', 
		'".$title."'
	)" );

	$product_id = $conn->lastInsertId();

	// save data
	$insert = $conn->exec( "INSERT INTO `product_images` 
	    (`added`,`file_name`,`file_type`,`file_size`,`product_id`,`primary`)
	    VALUE
	    ('".time()."',
	    'default-product-image.png',
	    'image/png',
	    '0',
	    '".$product_id."',
	    'yes'
	)" );
	
	// set status message
	status_message( "success", "Product has been created." );

	// redirect
	go( 'dashboard.php?c=product&id='.$product_id );
}

function product_edit() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$product_id 					= post( 'product_id' );
	$title 							= post( 'title' );
	$preview_text 					= post( 'preview_text' );
	$description 					= post( 'description' );
	$price_s 						= post( 'price_s' );
	$price_m 						= post( 'price_m' );
	$price_l 						= post( 'price_l' );
	$star_rating 					= post( 'star_rating' );
	$categories						= post_array( 'categories' );
	$free_delivery 					= post( 'free_delivery' );
	$free_vase 						= post( 'free_vase' );

	// sanity check
	$price 							= preg_replace( "/[^0-9\.]/", "", $price );
	$price_special 					= preg_replace( "/[^0-9\.]/", "", $price_special );

	// manipulate data
	$categories 					= serialize( $categories );

	// save data
	$update = $conn->exec( "UPDATE `products` SET `title` = '".$title."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `preview_text` = '".$preview_text."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `description` = '".$description."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `price_s` = '".$price_s."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `price_m` = '".$price_m."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `price_l` = '".$price_l."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `star_rating` = '".$star_rating."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `categories` = '".$categories."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `free_delivery` = '".$free_delivery."' WHERE `id` = '".$product_id."' " );
	$update = $conn->exec( "UPDATE `products` SET `free_vase` = '".$free_vase."' WHERE `id` = '".$product_id."' " );

	// set status message
	status_message( "success", "Product has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function product_delete() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id 							= get( 'id' );

	// delete data
	$delete = $conn->exec( "DELETE FROM `products` WHERE `id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `product_images` WHERE `product_id` = '".$id."' " );

	// set status message
	status_message( "success", "Product has been deleted." );

	// redirect
	go( 'dashboard.php?c=products' );
}

function product_image_add() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$product_id 				= post('product_id');
	$path_parts 				= pathinfo($_FILES["file1"]["name"]);
	$extension 					= $path_parts['extension'];

	$fileName 					= $_FILES["file1"]["name"]; // The file name
	$random_string 				= random_string();
	$base64_filename 			= base64_encode( $random_string.'-'.$fileName );
	
	$fileTmpLoc 				= $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType 					= $_FILES["file1"]["type"]; // The type of file it is
	$fileSize 					= $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg 				= $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if( !$fileTmpLoc ) { // if file not chosen
		echo "Please select a photo to upload first.";
		exit();
	}
	
	// handle the uploaded file
	if( move_uploaded_file( $fileTmpLoc, "product_images/".$base64_filename ) ) {
		
		// save to the database
		$insert = $conn->exec( "INSERT INTO `product_images` 
		    (`added`,`file_name`,`file_type`,`file_size`,`product_id`)
		    VALUE
		    ('".time()."',
		    '".$base64_filename."',
		    '".$fileType."',
		    '".$fileSize."',
		    '".$product_id."'
		)" );
		
		// report
		echo "<font color='#18B117'><b>Upload Complete</b></font>";
		
	}else{
		echo "ERROR: Oops, something went very wrong. Please try again or contact support for more help.";
		exit();
	}	
}

function product_image_delete() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id = get( 'id' );

	// get data
	$image = get_product_image( $id );

	
	if( $image['primary'] == 'yes' ) { // is this a primary image
		// set status message
		status_message( "danger", "Cannot delete a primary image." );
	} elseif( !unlink( 'product_images/'.$image['file_name'] ) ) { // delete file and data
		// set status message
		status_message( "danger", "Product Image has not been deleted." );
	} else {
		// delete data
		$delete = $conn->exec( "DELETE FROM `product_images` WHERE `id` = '".$id."' " );

		// set status message
		status_message( "success", "Product Image has been deleted." );
	}

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function product_image_make_primary() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$id 	 						= get( 'id' );

	// get data
	$image = get_product_image( $id );

	// remove primary from all product images
	$update = $conn->exec( "UPDATE `product_images` SET `primary` = 'no' WHERE `product_id` = '".$image['product_id']."' " );

	// save data
	$update = $conn->exec( "UPDATE `product_images` SET `primary` = 'yes' WHERE `id` = '".$id."' " );

	// set status message
	status_message( "success", "Primary product image has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function subscription_add() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// security check
	if( $admin_check || $staff_check ) {
		// map fields
		$name 							= post( 'name' );

		// does subscription already exist
		$query = $conn->query( "
	        SELECT `id` 
	        FROM `user_subscriptions` 
	        WHERE `name` = '".$nam."' 
	    " );
		$subscription = $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $user['id'] ) ) {
			// set status message
			status_message( "danger", 'A subscription with the name "'.$subscription['name'].'" already exists.' );

			// redirect
			go( $_SERVER['HTTP_REFERER'] );
		} else {
			// save data
			$insert = $conn->exec( "INSERT IGNORE INTO `user_subscriptions` 
				(`name`)
				VALUE
				('".$name."'
			)" );

			$subscription_id = $conn->lastInsertId();

			// set status message
			status_message( "success", "Subscription Plan has been added." );

			// redirect
			go( 'dashboard.php?c=subscription&id='.$subscription_id );
		}
	} else {
		// redirect
		go( $_SERVER['HTTP_REFERER'] );
	}
}

function subscription_add_user() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// security check
	if( $admin_check || $staff_check ) {
		// map fields
		$subscription_id 					= post( 'subscription_id' );
		$user_id 							= post( 'user_id' );

		// does subscription already exist
		$update = $conn->exec( "UPDATE `users` SET `subscription_id` = '".$subscription_id."' WHERE `id` = '".$user_id."' " );

		// set status message
		status_message( "success", "User subscription has been updated." );
	}

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function subscription_edit() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$subscription_id 				= post( 'subscription_id' );
	$name 							= post( 'name' );
	$status 						= post( 'status' );
	$cap_cycle 					= post( 'cap_cycle' );
	$cap_amount 					= post( 'cap_amount' );
	$network_percentage 			= post( 'network_percentage' );
	$notes 							= post( 'notes' );

	// sanity check
	$cap_amount 					= preg_replace( "/[^0-9\.]/", "", $cap_amount );
	$network_percentage 			= preg_replace( "/[^0-9\.]/", "", $network_percentage );

	// save data
	$update = $conn->exec( "UPDATE `user_subscriptions` SET `name` = '".$name."' WHERE `id` = '".$subscription_id."' " );
	$update = $conn->exec( "UPDATE `user_subscriptions` SET `status` = '".$status."' WHERE `id` = '".$subscription_id."' " );
	$update = $conn->exec( "UPDATE `user_subscriptions` SET `cap_cycle` = '".$cap_cycle."' WHERE `id` = '".$subscription_id."' " );
	$update = $conn->exec( "UPDATE `user_subscriptions` SET `cap_amount` = '".$cap_amount."' WHERE `id` = '".$subscription_id."' " );
	$update = $conn->exec( "UPDATE `user_subscriptions` SET `network_percentage` = '".$network_percentage."' WHERE `id` = '".$subscription_id."' " );
	$update = $conn->exec( "UPDATE `user_subscriptions` SET `notes` = '".$notes."' WHERE `id` = '".$subscription_id."' " );

	// set status message
	status_message( "success", "Subscription Plan has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function user_add() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// security check
	if( $admin_check || $staff_check ) {
		// map fields
		$first_name 					= post( 'first_name' );
		$last_name 						= post( 'last_name' );
		$email 							= post( 'email' );
		$password 						= post( 'password' );
		$type 							= post( 'type' );

		// does user already exist
		$query = $conn->query( "
	        SELECT `id` 
	        FROM `users` 
	        WHERE `type` = '".$type."' 
	        AND `email` = '".$email."' 
	    " );
		$user = $query->fetch( PDO::FETCH_ASSOC );
		if( isset( $user['id'] ) ) {
			// set status message
			status_message( "danger", $email." already has an account." );

			// redirect
			go( $_SERVER['HTTP_REFERER'] );
		} else {
			// save data
			$insert = $conn->exec( "INSERT IGNORE INTO `users` 
				(`added`,`type`,`email`,`password`,`first_name`,`last_name`,`status`)
				VALUE
				('".time()."', 
				'".$type."', 
				'".$email."',
				'".$password."',
				'".$first_name."',
				'".$last_name."',
				'active'
			)" );

			$user_id = $conn->lastInsertId();

			// set status message
			status_message( "success", "User has been added." );

			// redirect
			go( 'dashboard.php?c=user&id='.$user_id );
		}
	} else {
		// redirect
		go( $_SERVER['HTTP_REFERER'] );
	}
}

function user_edit() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$user_id 						= post( 'user_id' );
	$company_name 					= post( 'company_name' );
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );
	$password_1 					= post( 'password_1' );
	$password_2 					= post( 'password_2' );
	$type 							= post( 'type' );
	$status 						= post( 'status' );
	$fallback_florist 				= post( 'fallback_florist' );
	$address_1 						= post( 'address_1' );
	$address_2 						= post( 'address_2' );
	$address_city 					= post( 'address_city' );
	$address_state 					= post( 'address_state' );
	$address_zip 					= post( 'address_zip' );
	$address_country 				= post( 'address_country' );
	$tel_landline 					= post( 'tel_landline' );
	$tel_cell 						= post( 'tel_cell' );
	$coverage_area 					= post( 'coverage_area' );
	$secondary_coverage_area 		= post( 'secondary_coverage_area' );
	$notes 							= post( 'notes' );
	$subscription_id 				= post( 'subscription_id' );
	$bank_account_name 				= post( 'bank_account_name' );
	$bank_account_number			= post( 'bank_account_number' );
	$bank_sort_code					= post( 'bank_sort_code' );
	$bank_sort_code 				= str_replace( array( ' ', '-' ), '', $bank_sort_code );

	// forward geocoding
	$address_full[] = $address_1;
	$address_full[] = $address_2;
	$address_full[] = $address_city;
	$address_full[] = $address_state;
	$address_full[] = $address_zip;
	$address_full[] = $address_country;
	$address = implode( ', ', $address_full );
	$geocode_data = forward_geocoding( $address );

	if( isset( $geocode_data['features'][0]['id'] ) ) {
		$address_lat = $geocode_data['features'][0]['geometry']['coordinates'][1];
		$address_lng = $geocode_data['features'][0]['geometry']['coordinates'][0];
	} else {
		$address_lat = '';
		$address_lng = '';
	}

	// do passwords match
	if( $password_1 != $password_2 ) {
		// set status message
		status_message( "danger", "Passwords do not match, please try again." );
		
		// redirect
		go( $_SERVER['HTTP_REFERER'] );
	}

	// save data
	if( $admin_check || $staff_check ) {
		$update = $conn->exec( "UPDATE `users` SET `status` = '".$status."' WHERE `id` = '".$user_id."' " );
		$update = $conn->exec( "UPDATE `users` SET `type` = '".$type."' WHERE `id` = '".$user_id."' " );
		$update = $conn->exec( "UPDATE `users` SET `fallback_florist` = '".$fallback_florist."' WHERE `id` = '".$user_id."' " );
		$update = $conn->exec( "UPDATE `users` SET `notes` = '".$notes."' WHERE `id` = '".$user_id."' " );
	}
	$update = $conn->exec( "UPDATE `users` SET `company_name` = '".$company_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `first_name` = '".$first_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `last_name` = '".$last_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `password` = '".$password_1."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `tel_landline` = '".$tel_landline."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `tel_cell` = '".$tel_cell."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_1` = '".$address_1."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_2` = '".$address_2."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_city` = '".$address_city."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_state` = '".$address_state."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_zip` = '".$address_zip."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_country` = '".$address_country."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_lat` = '".$address_lat."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_lng` = '".$address_lng."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `coverage_area` = '".$coverage_area."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `secondary_coverage_area` = '".$secondary_coverage_area."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `bank_account_name` = '".$bank_account_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `bank_account_number` = '".$bank_account_number."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `bank_sort_code` = '".$bank_sort_code."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `subscription_id` = '".$subscription_id."' WHERE `id` = '".$user_id."' " );

	// set status message
	status_message( "success", "User has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function user_edit_from_order() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$user_id 						= post( 'user_id' );
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );

	$address_1 						= post( 'address_1' );
	$address_2 						= post( 'address_2' );
	$address_city 					= post( 'address_city' );
	$address_state 					= post( 'address_state' );
	$address_zip 					= post( 'address_zip' );
	$address_country 				= post( 'address_country' );

	$tel_landline 					= post( 'tel_landline' );
	$tel_cell 						= post( 'tel_cell' );

	// forward geocoding
	$address_full[] = $address_1;
	$address_full[] = $address_2;
	$address_full[] = $address_city;
	$address_full[] = $address_state;
	$address_full[] = $address_zip;
	$address_full[] = $address_country;
	$address = implode( ', ', $address_full );
	$geocode_data = forward_geocoding( $address );

	if( isset( $geocode_data['features'][0]['id'] ) ) {
		$address_lat = $geocode_data['features'][0]['geometry']['coordinates'][1];
		$address_lng = $geocode_data['features'][0]['geometry']['coordinates'][0];
	} else {
		$address_lat = '';
		$address_lng = '';
	}

	// save data
	$update = $conn->exec( "UPDATE `users` SET `first_name` = '".$first_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `last_name` = '".$last_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `tel_landline` = '".$tel_landline."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `tel_cell` = '".$tel_cell."' WHERE `id` = '".$user_id."' " );

	$update = $conn->exec( "UPDATE `users` SET `address_1` = '".$address_1."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_2` = '".$address_2."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_city` = '".$address_city."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_state` = '".$address_state."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_zip` = '".$address_zip."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_country` = '".$address_country."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_lat` = '".$address_lat."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_lng` = '".$address_lng."' WHERE `id` = '".$user_id."' " );

	// set status message
	status_message( "success", "Customer has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function user_edit_coverage_area() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$user_id 						= post( 'user_id' );
	$coverage_area 					= post_array( 'coverage_area' );
	$coverage_area 					= implode( ',', $coverage_area );

	// get data
	$user 							= get_user( $user_id );

	// build new data
	$coverage_area 					= $user['coverage_area'].','.$coverage_area; 

	// save data
	$update = $conn->exec( "UPDATE `users` SET `coverage_area` = '".$coverage_area."' WHERE `id` = '".$user_id."' " );

	debug( $coverage_area );
}

function user_edit_secondary_coverage_area() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$user_id 						= post( 'user_id' );
	$secondary_coverage_area 		= post_array( 'secondary_coverage_area' );

	// get data
	$user 							= get_user( $user_id );

	// mark each area as taken in the database
	foreach( $secondary_coverage_area as $id => $data ) {
		$update = $conn->exec( "UPDATE `global_addresses` SET `user_id` = '".$user_id."' WHERE `country` = '".$user['address_country']."' AND `zip_code` = '".$data."' " );
	}

	// build new data
	$secondary_coverage_area 		= implode( ',', $secondary_coverage_area );
	$secondary_coverage_area 		= $user['secondary_coverage_area'].','.$secondary_coverage_area; 

	// save data
	$update = $conn->exec( "UPDATE `users` SET `secondary_coverage_area` = '".$secondary_coverage_area."' WHERE `id` = '".$user_id."' " );

	debug( $secondary_coverage_area );
}

function user_edit_remove_secondary_coverage_area() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// map fields
	$user_id 						= post( 'user_id' );
	$area 							= post( 'area' );

	// get data
	$user 							= get_user( $user_id );

	// mark each area as free in the database
	$update = $conn->exec( "UPDATE `global_addresses` SET `user_id` = '' WHERE `country` = '".$user['address_country']."' AND `zip_code` = '".$data."' " );

	// build new data
	$secondary_coverage_area 		= explode( ',', $user['secondary_coverage_area'] );
	$item 							= array_search( $area, $secondary_coverage_area );
	unset( $secondary_coverage_area[$item] );
	$secondary_coverage_area 		= implode( ',', $secondary_coverage_area );

	// save data
	$update = $conn->exec( "UPDATE `users` SET `secondary_coverage_area` = '".$secondary_coverage_area."' WHERE `id` = '".$user_id."' " );

	debug( $secondary_coverage_area );
}

function user_delete() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// security check
	if( $account_details['type'] == 'admin' ) {
	
		// map fields
		$id 							= get( 'id' );

		// delete data
		$delete = $conn->exec( "DELETE FROM `users` WHERE `id` = '".$id."' " );

		// set status message
		status_message( "success", "User has been deleted." );

		// redirect
		go( 'dashboard.php?c=users' );
	} else {
		// redirect
		go( 'dashboard.php' );
	}
}

function system_settings() {
	global $conn, $globals, $account_details, $admin_check, $staff_check;

	// admin check
	if( $account_details['type'] == 'admin' ) {
		// map fields
		$fqdn 								= post( 'fqdn' );
		$ffmpeg_segment_duraction 			= post( 'ffmpeg_segment_duraction' );
		$ffmpeg_segments 					= post( 'ffmpeg_segments' );
		$ffmpeg_probe_size 					= post( 'ffmpeg_probe_size' );
		$ffmpeg_analyze_duraction 			= post( 'ffmpeg_analyze_duraction' );

		// save data
		$update = $conn->exec( "UPDATE `system_settings` SET `value` = '".$fqdn."' WHERE `name` = 'fqdn' " );
		$update = $conn->exec( "UPDATE `system_settings` SET `value` = '".$ffmpeg_segment_duraction."' WHERE `name` = 'ffmpeg_segment_duraction' " );
		$update = $conn->exec( "UPDATE `system_settings` SET `value` = '".$ffmpeg_segments."' WHERE `name` = 'ffmpeg_segments' " );
		$update = $conn->exec( "UPDATE `system_settings` SET `value` = '".$ffmpeg_probe_size."' WHERE `name` = 'ffmpeg_probe_size' " );
		$update = $conn->exec( "UPDATE `system_settings` SET `value` = '".$ffmpeg_analyze_duraction."' WHERE `name` = 'ffmpeg_analyze_duraction' " );

		// set status message
		status_message( "success", "System Settings have been updated." );
	}

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}
