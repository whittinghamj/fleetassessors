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

    case "customer_add":
        customer_add();
        break;

    case "customer_edit":
        customer_edit();
        break;

    case "customer_delete":
        customer_delete();
        break;

    case "job_add":
        job_add();
        break;

    case "job_edit":
        job_edit();
        break;

    case "job_delete":
        job_delete();
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

    case "user_delete":
        user_delete();
        break;

    case "vrn_lookup":
        vrn_lookup();
        break;

	default:
		home();
		break;
}

function home() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	die( 'access denied to function name '.get( 'a' ) );
}

function accept_terms() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// save data
	$update = $conn->exec( "UPDATE `users` SET `accept_terms` = 'yes' WHERE `id` = '".$account_details['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `accept_terms_timestamp` = '".time()."' WHERE `id` = '".$account_details['id']."' " );
	$update = $conn->exec( "UPDATE `users` SET `accept_terms_ip` = '".$_SERVER['REMOTE_ADDR']."' WHERE `id` = '".$account_details['id']."' " );

	// set status message
	status_message( "success", "Terms &amp; Conditions have been accepted." );

	// redirect
	go( 'dashboard.php' );
}

function customer_add() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// map fields
	$company_name 					= post( 'company_name' );
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );
	$password 						= post( 'password' );
	$address_1 						= post( 'address_1' );
	$address_2 						= post( 'address_2' );
	$address_city 					= post( 'address_city' );
	$address_state 					= post( 'address_state' );
	$address_zip 					= post( 'address_zip' );
	$address_country 				= post( 'address_country' );

	// does user already exist
	$query = $conn->query( "
	        SELECT `id` 
	        FROM `users` 
	        WHERE `email` = '".$email."' 
	    " );
	$user = $query->fetch( PDO::FETCH_ASSOC );

	// sanity check
	if( isset( $user['id'] ) ) {
		// set status message
		status_message( "danger", "A customer / user with the email address <strong>".$email."</strong> already exists." );

		// redirect
		go( 'dashboard.php?c=customers' );
	}

	// save data - user
	$insert = $conn->exec( "INSERT IGNORE INTO `users` 
		(`added`,`status`,`type`,`first_name`,`last_name`,`email`,`password`,`address_1`,`address_2`,`address_city`,`address_state`,`address_zip`,`address_country`,`added_by`)
		VALUE
		('".time()."', 
		'active', 
		'customer',
		'".$first_name."',
		'".$last_name."',
		'".$email."',
		'".$password."',
		'".$address_1."',
		'".$address_2."',
		'".$address_city."',
		'".$address_state."',
		'".$address_zip."',
		'".$address_country."',
		'".$account_details['id']."'
	)" );

	$user_id = $conn->lastInsertId();
	
	// save data - customer
	$insert = $conn->exec( "INSERT IGNORE INTO `customers` 
		(`added`,`status`,`company_name`,`primary_contact_id`,`address_1`,`address_2`,`address_city`,`address_state`,`address_zip`,`address_country`,`added_by`)
		VALUE
		('".time()."', 
		'active', 
		'".$company_name."',
		'".$user_id."',
		'".$address_1."',
		'".$address_2."',
		'".$address_city."',
		'".$address_state."',
		'".$address_zip."',
		'".$address_country."',
		'".$account_details['id']."'
	)" );

	$customer_id = $conn->lastInsertId();

	// set status message
	status_message( "success", "Customer has been added." );

	// redirect
	go( 'dashboard.php?c=customer&id='.$customer_id );
}

function customer_edit() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// map fields
	$customer_id 					= post( 'customer_id' );
	$company_name 					= post( 'company_name' );
	$status 						= post( 'status' );
	$address_1 						= post( 'address_1' );
	$address_2 						= post( 'address_2' );
	$address_city 					= post( 'address_city' );
	$address_state 					= post( 'address_state' );
	$address_zip 					= post( 'address_zip' );
	$address_country 				= post( 'address_country' );
	$notes 							= post( 'notes' );
	$primary_contact_id 			= post( 'primary_contact_id' );
	$secondary_contact_id 			= post( 'secondary_contact_id' );

	// save data
	$update = $conn->exec( "UPDATE `customers` SET `status` = '".$status."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `company_name` = '".$company_name."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `address_1` = '".$address_1."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `address_2` = '".$address_2."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `address_city` = '".$address_city."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `address_state` = '".$address_state."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `address_zip` = '".$address_zip."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `address_country` = '".$address_country."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `primary_contact_id` = '".$primary_contact_id."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `secondary_contact_id` = '".$secondary_contact_id."' WHERE `id` = '".$customer_id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `notes` = '".$notes."' WHERE `id` = '".$customer_id."' " );

	// set status message
	status_message( "success", "Customer has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function vrn_lookup() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	debug( $_POST );
	die();
	
	// map fields
	$vrn 					= post( 'vrn' );
	$vrn 					= str_replace( ' ', $vrn );
	$vrn 					= strtoupper( $vrn );

	// api lookup
	$remote_data = file_get_contents( 'https://www.rapidcarcheck.co.uk/FreeAccess/?vrm='.$vrn.'&auth=ACCESSAPIENDPOINT&site=https://spotonmotorsmanchester.co.uk' );
	$remote_data = json_decode( $remote_data, true );

	// check if we found something
	if( isset( $remote_data['HasError'] == false ) ) {
		// does vrn already exist
		$query = $conn->query( "
		        SELECT `id` 
		        FROM `vrn_database` 
		        WHERE `vrn` = '".$vrn."' 
		    " );
		$vrn = $query->fetch( PDO::FETCH_ASSOC );

		// sanity check
		if( isset( $vrn['id'] ) ) {
			// update data
			$vrn_id = $vrn['id'];
		} else {
			// save data
			$insert = $conn->exec( "INSERT INTO `vrn_database` 
				(`added`,`vrn`)
				VALUE
				('".time()."', 
				'".$vrn."'
			)" );

			$vrn_id = $conn->lastInsertId();
		}

		// update data
		$update = $conn->exec( "UPDATE `vrn_database` SET `make` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `model` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Model']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `color` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Color']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `year` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['YearOfManufacture']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `fuel` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['FuelType']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `cylinder_capacity` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['CylinderCapacity']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `top_speed` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TopSpeed']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `bhp` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Bhp']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `vehicle_type` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['VehicleType']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `body_type` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['BodyStyle']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `insurance_group` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['InsuranceGroup']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_v5_issued` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfLastV5CIssued']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `first_v5_issued` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfFirstRegistration']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `average_miles_per_year` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['AverageMileagePerYear']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `total_mots` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TotalMotRecords']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_mot_date` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['LastMotDate']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `next_mot_date` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateMotDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `is_mot_valid` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['IsMOTDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `is_taxed` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['RoadTaxStatusDescription']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `next_tax_date` = '".$remote_data['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateRoadTaxDue']."' WHERE `id` = '".$vrn_id."' " );

		// redirect
		go( 'dashboard.php?c=vrn_lookup_results&id='.$vrn_id );
	} else {
		// redirect
		go( 'dashboard.php?c=vrn_lookup_results&id=nothing_found' );
	}
}




function order_add_item() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// map fields
	$order_id 				= post( 'order_id' );

	// set status message
	status_message( "success", "This function has not been built yet." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function order_edit_delivery_details() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

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
