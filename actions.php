<?php
// error reporting
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

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
	// accept terms function
    case "accept_terms":
        accept_terms();
        break;


    // customer functions
    case "customer_add":
        customer_add();
        break;

    case "customer_edit":
        customer_edit();
        break;

    case "customer_delete":
        customer_delete();
        break;


    // job functions
    case "job_add":
        job_add();
        break;

    case "job_edit":
        job_edit();
        break;

    case "job_delete":
        job_delete();
        break;


    // user functions
    case "user_add":
        user_add();
        break;

    case "user_edit":
        user_edit();
        break;

    case "user_delete":
        user_delete();
        break;


    // other functions
    case "system_settings":
        system_settings();
        break;


    // tools functions
    case "vrn_lookup":
        vrn_lookup();
        break;


    // dev function
    case "dev":
        dev();
        break;

	default:
		home();
		break;
}

function home() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	die( 'access denied to function name '.get( 'a' ) );
}

function dev() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	action_security_check( array( 'admin','staff' ) );

	echo 'We passed the checkpoint.';
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


// customer functions
function customer_add() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// security point
	action_security_check( array( 'admin','staff' ) );

	// map fields
	$company_name 					= post( 'company_name' );
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );
	$phone 							= post( 'phone' );
	$phone 							= only_numbers( $phone );
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
		(`added`,`status`,`type`,`first_name`,`last_name`,`email`,`address_1`,`address_2`,`address_city`,`address_state`,`address_zip`,`address_country`,`added_by`)
		VALUE
		('".time()."', 
		'active', 
		'customer',
		'".$first_name."',
		'".$last_name."',
		'".$email."',
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

	// security point
	action_security_check( array( 'admin','staff' ) );

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

function customer_delete() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// security point
	action_security_check( array( 'admin','staff' ) );

	// map fields
	$id 							= get( 'id' );

	// delete data
	$delete = $conn->exec( "DELETE FROM `jobs` WHERE `customer_id` = '".$id."' " );
	$delete = $conn->exec( "DELETE FROM `customers` WHERE `id` = '".$id."' " );

	// set status message
	status_message( "success", "Customer has been deleted." );

	// redirect
	go( 'dashboard.php?c=customers' );
}


// vrn lookup
function vrn_lookup() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// map fields
	$vrn 					= post( 'vrn' );
	$vrn 					= str_replace( ' ', '', $vrn );
	$vrn 					= trim( $vrn );
	$vrn 					= strtoupper( $vrn );

	// does vrn already exist
	$query = $conn->query( "
	        SELECT `id`, `added`, `last_checked` 
	        FROM `vrn_database` 
	        WHERE `vrn` = '".$vrn."' 
	    " );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $data['id'] ) ) {
		// update data
		$vrn_id = $data['id'];

		error_log( " " );
		error_log( "Using local DVLA dataset" );
		error_log( " " );
	} else {
		// api lookup
		$remote_data = file_get_contents( 'https://www.rapidcarcheck.co.uk/FreeAccess/?vrm='.$vrn.'&auth=ACCESSAPIENDPOINT&site=https://spotonmotorsmanchester.co.uk' );
		$remote_data = json_decode( $remote_data, true );

		// check if we found something
		if( isset( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make'] ) ) {
			// save data
			$insert = $conn->exec( "INSERT INTO `vrn_database` 
				(`added`,`vrn`)
				VALUE
				('".time()."', 
				'".$vrn."'
			)" );

			$vrn_id = $conn->lastInsertId();

			// update data
			$update = $conn->exec( "UPDATE `vrn_database` SET `make` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make'] )."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `model` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Model'] )."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `color` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Colour']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['YearOfManufacture']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `fuel` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['FuelType']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `cylinder_capacity` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['CylinderCapacity']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `top_speed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TopSpeed']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `bhp` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Bhp']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `vehicle_type` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['VehicleType']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `body_style` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['BodyStyle']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `insurance_group` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['InsuranceGroup']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `last_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfLastV5CIssued']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `first_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfFirstRegistration']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `average_miles_per_year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['AverageMileagePerYear']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `total_mots` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TotalMotRecords']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `last_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['LastMotDate']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `next_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateMotDue']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `is_mot_valid` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['IsMOTDue']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `is_taxed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['RoadTaxStatusDescription']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `next_tax_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateRoadTaxDue']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `co2_emissions` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Co2Emissions']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `last_checked` = '".time()."' WHERE `id` = '".$vrn_id."' " );
		} else {
			// redirect
			go( 'dashboard.php?c=vrn_lookup_results&vrn=nothing_found' );
		}
	}

	// check if we should update the data
	$added = $data['added'];
	$last_checked = $data['last_checked'];
	$time_diff = ($last_checked - $added);
	if( $time_diff > 7776000 ) {
		// api lookup
		$remote_data = file_get_contents( 'https://www.rapidcarcheck.co.uk/FreeAccess/?vrm='.$vrn.'&auth=ACCESSAPIENDPOINT&site=https://spotonmotorsmanchester.co.uk' );
		$remote_data = json_decode( $remote_data, true );

		// update data
		$update = $conn->exec( "UPDATE `vrn_database` SET `make` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make'] )."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `model` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Model'] )."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `color` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Colour']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['YearOfManufacture']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `fuel` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['FuelType']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `cylinder_capacity` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['CylinderCapacity']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `top_speed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TopSpeed']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `bhp` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Bhp']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `vehicle_type` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['VehicleType']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `body_style` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['BodyStyle']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `insurance_group` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['InsuranceGroup']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfLastV5CIssued']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `first_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfFirstRegistration']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `average_miles_per_year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['AverageMileagePerYear']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `total_mots` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TotalMotRecords']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['LastMotDate']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `next_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateMotDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `is_mot_valid` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['IsMOTDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `is_taxed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['RoadTaxStatusDescription']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `next_tax_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateRoadTaxDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `co2_emissions` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Co2Emissions']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_checked` = '".time()."' WHERE `id` = '".$vrn_id."' " );
	}

	// redirect
	go( 'dashboard.php?c=vrn_lookup_results&vrn='.$vrn );
}


// job functions
function job_add() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// map fields
	$customer_id 			= post( 'customer_id' );
	$vrn 					= post( 'vrn' );
	$vrn 					= str_replace( ' ', '', $vrn );
	$vrn 					= trim( $vrn );
	$vrn 					= strtoupper( $vrn );
	$initial_estimate 		= post( 'initial_estimate' );
	$initial_estimate		= only_numbers( $initial_estimate );
	$initial_estimate 		= round( $initial_estimate, 2 );
	$initial_estimate 		= vat_details( $initial_estimate );

	// does vrn already exist
	$query = $conn->query( "
	        SELECT `id`, `added`, `last_checked` 
	        FROM `vrn_database` 
	        WHERE `vrn` = '".$vrn."' 
	    " );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	if( isset( $data['id'] ) ) {
		// update data
		$vrn_id = $data['id'];

		error_log( " " );
		error_log( "Using local DVLA dataset" );
		error_log( " " );
	} else {
		// api lookup
		$remote_data = file_get_contents( 'https://www.rapidcarcheck.co.uk/FreeAccess/?vrm='.$vrn.'&auth=ACCESSAPIENDPOINT&site=https://spotonmotorsmanchester.co.uk' );
		$remote_data = json_decode( $remote_data, true );

		// check if we found something
		if( isset( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make'] ) ) {
			// save data
			$insert = $conn->exec( "INSERT INTO `vrn_database` 
				(`added`,`vrn`)
				VALUE
				('".time()."', 
				'".$vrn."',
			)" );

			$vrn_id = $conn->lastInsertId();

			// update data
			$update = $conn->exec( "UPDATE `vrn_database` SET `make` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make'] )."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `model` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Model'] )."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `color` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Colour']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['YearOfManufacture']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `fuel` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['FuelType']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `cylinder_capacity` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['CylinderCapacity']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `top_speed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TopSpeed']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `bhp` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Bhp']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `vehicle_type` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['VehicleType']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `body_style` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['BodyStyle']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `insurance_group` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['InsuranceGroup']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `last_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfLastV5CIssued']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `first_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfFirstRegistration']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `average_miles_per_year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['AverageMileagePerYear']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `total_mots` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TotalMotRecords']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `last_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['LastMotDate']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `next_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateMotDue']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `is_mot_valid` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['IsMOTDue']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `is_taxed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['RoadTaxStatusDescription']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `next_tax_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateRoadTaxDue']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `co2_emissions` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Co2Emissions']."' WHERE `id` = '".$vrn_id."' " );
			$update = $conn->exec( "UPDATE `vrn_database` SET `last_checked` = '".time()."' WHERE `id` = '".$vrn_id."' " );
		} else {
			// redirect
			go( 'dashboard.php?c=vrn_lookup_results&vrn=nothing_found' );
		}
	}

	// check if we should update the data
	$added = $data['added'];
	$last_checked = $data['last_checked'];
	$time_diff = ($last_checked - $added);
	if( $time_diff > 7776000 ) {
		// api lookup
		$remote_data = file_get_contents( 'https://www.rapidcarcheck.co.uk/FreeAccess/?vrm='.$vrn.'&auth=ACCESSAPIENDPOINT&site=https://spotonmotorsmanchester.co.uk' );
		$remote_data = json_decode( $remote_data, true );

		// update data
		$update = $conn->exec( "UPDATE `vrn_database` SET `make` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Make'] )."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `model` = '".ucwords( $remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Model'] )."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `color` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Colour']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['YearOfManufacture']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `fuel` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['FuelType']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `cylinder_capacity` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['CylinderCapacity']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `top_speed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TopSpeed']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `bhp` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Bhp']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `vehicle_type` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['VehicleType']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `body_style` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['BodyStyle']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `insurance_group` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['InsuranceGroup']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfLastV5CIssued']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `first_v5_issued` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateOfFirstRegistration']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `average_miles_per_year` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['AverageMileagePerYear']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `total_mots` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['TotalMotRecords']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['LastMotDate']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `next_mot_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateMotDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `is_mot_valid` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['IsMOTDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `is_taxed` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['RoadTaxStatusDescription']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `next_tax_date` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['DateRoadTaxDue']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `co2_emissions` = '".$remote_data['Results']['InitialVehicleCheckModel']['BasicVehicleDetailsModel']['Co2Emissions']."' WHERE `id` = '".$vrn_id."' " );
		$update = $conn->exec( "UPDATE `vrn_database` SET `last_checked` = '".time()."' WHERE `id` = '".$vrn_id."' " );
	}

	// save data - job
	$insert = $conn->exec( "INSERT IGNORE INTO `jobs` 
		(`added`,`added_date`,`updated`,`customer_id`,`added_by`,`vrn`,`initial_estimate_inc_vat`,`initial_estimate`)
		VALUE
		('".time()."',
		'".date( "d-m-Y", time() )."'
		'".time()."',
		'".$customer_id."',
		'".$account_details['id']."',
		'".$vrn."',
		'".$initial_estimate['inc_vat']."',
		'".$initial_estimate['ex_vat']."'
	)" );

	$job_id = $conn->lastInsertId();

	// set status message
	status_message( "success", "Job has been created." );

	// redirect
	go( 'dashboard.php?c=job&id='.$job_id );
}

function job_edit() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// security point
	action_security_check( array( 'admin','staff' ) );

	// map fields
	$job_id 						= post( 'job_id' );
	$status 						= post( 'status' );
	$provider_id 					= post( 'provider_id' );
	$estimator 						= post( 'estimator' );
	$initial_estimate 				= post( 'initial_estimate' );
	$initial_estimate 				= only_numbers( $initial_estimate );
	$initial_estimate 				= round( $initial_estimate, 2 );
	
	$uplift_labour 					= post( 'uplift_labour' );
	$uplift_labour 					= only_numbers( $uplift_labour );
	$uplift_labour	 				= round( $uplift_labour, 2 );
	if( empty( $uplift_labour ) ) { $uplift_labour = '0.00'; }

	$uplift_paint 					= post( 'uplift_paint' );
	$uplift_paint 					= only_numbers( $uplift_paint );
	$uplift_paint	 				= round( $uplift_paint, 2 );
	if( empty( $uplift_paint ) ) { $uplift_paint = '0.00'; }

	$uplift_additional 				= post( 'uplift_additional' );
	$uplift_additional 				= only_numbers( $uplift_additional );
	$uplift_additional 				= round( $uplift_additional, 2 );
	if( empty( $uplift_additional ) ) { $uplift_additional = '0.00'; }

	$uplift_parts 					= post( 'uplift_parts' );
	$uplift_parts 					= only_numbers( $uplift_parts );
	$uplift_parts 					= round( $uplift_parts, 2 );
	if( empty( $uplift_parts ) ) { $uplift_parts = '0.00'; }

	$uplift_check 					= post( 'uplift_check' );
	$uplift_check 					= only_numbers( $uplift_check );
	$uplift_check 					= round( $uplift_check, 2 );
	if( empty( $uplift_check ) ) { $uplift_check = '0.00'; }

	$uplift_total 					= ( $uplift_labour + $uplift_paint + $uplift_additional + $uplift_parts + $uplift_check );

	$approved_labour 				= post( 'approved_labour' );
	$approved_labour 				= only_numbers( $approved_labour );
	$approved_labour	 			= round( $approved_labour, 2 );
	if( empty( $approved_labour ) ) { $approved_labour = '0.00'; }

	$approved_paint 				= post( 'approved_paint' );
	$approved_paint 				= only_numbers( $approved_paint );
	$approved_paint	 				= round( $approved_paint, 2 );
	if( empty( $approved_paint ) ) { $approved_paint = '0.00'; }

	$approved_additional 			= post( 'approved_additional' );
	$approved_additional 			= only_numbers( $approved_additional );
	$approved_additional	 		= round( $approved_additional, 2 );
	if( empty( $approved_additional ) ) { $approved_additional = '0.00'; }

	$approved_parts 				= post( 'approved_parts' );
	$approved_parts 				= only_numbers( $approved_parts );
	$approved_parts	 				= round( $approved_parts, 2 );
	if( empty( $approved_parts ) ) { $approved_parts = '0.00'; }

	$approved_check 				= post( 'approved_check' );
	$approved_check 				= only_numbers( $approved_check );
	$approved_check	 				= round( $approved_check, 2 );
	if( empty( $approved_check ) ) { $approved_check = '0.00'; }

	$approved_total 				= ( $approved_labour + $approved_paint + $approved_additional + $approved_parts + $approved_check );

	$notes 							= post( 'notes' );

	// save data
	$update = $conn->exec( "UPDATE `jobs` SET `status` = '".$status."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `provider_id` = '".$provider_id."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `estimator` = '".$estimator."' WHERE `id` = '".$job_id."' " );

	$update = $conn->exec( "UPDATE `jobs` SET `initial_estimate` = '".$initial_estimate."' WHERE `id` = '".$job_id."' " );
	
	$update = $conn->exec( "UPDATE `jobs` SET `uplift_labour` = '".$uplift_labour."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `uplift_paint` = '".$uplift_paint."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `uplift_additional` = '".$uplift_additional."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `uplift_parts` = '".$uplift_parts."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `uplift_check` = '".$uplift_check."' WHERE `id` = '".$job_id."' " );

	$update = $conn->exec( "UPDATE `jobs` SET `approved_labour` = '".$approved_labour."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `approved_paint` = '".$approved_paint."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `approved_additional` = '".$approved_additional."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `approved_parts` = '".$approved_parts."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `approved_check` = '".$approved_check."' WHERE `id` = '".$job_id."' " );

	$update = $conn->exec( "UPDATE `jobs` SET `uplift_estimate` = '".$uplift_total."' WHERE `id` = '".$job_id."' " );
	$update = $conn->exec( "UPDATE `jobs` SET `approved_estimate` = '".$approved_total."' WHERE `id` = '".$job_id."' " );

	$update = $conn->exec( "UPDATE `jobs` SET `notes` = '".$notes."' WHERE `id` = '".$job_id."' " );

	$update = $conn->exec( "UPDATE `jobs` SET `updated` = '".time()."' WHERE `id` = '".$job_id."' " );

	// set status message
	status_message( "success", "Job has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function job_delete() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// security point
	action_security_check( array( 'admin','staff' ) );

	// map fields
	$id 							= get( 'id' );

	// delete data
	$delete = $conn->exec( "DELETE FROM `jobs` WHERE `id` = '".$id."' " );

	// set status message
	status_message( "success", "Job has been deleted." );

	// redirect to the right place
	$url = $_SERVER['HTTP_REFERER'];
	$query = parse_url( $url, PHP_URL_QUERY );
	parse_str( $query, $url_bits );
	if( $url_bits['c'] == 'job' || $url_bits['c'] == 'jobs' ) {
		go( 'dashboard.php?c=jobs' );
	} elseif( $url_bits['c'] == 'customer' ) {
		go( 'dashboard.php?c=customer&id='.$url_bits['id'] );
	} elseif( $url_bits['c'] == 'customers' ) {
		go( 'dashboard.php?c=customers' );
	} else {
		go( $_SERVER['HTTP_REFERER'] );
	}
}


// user functions
function user_add() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// security point
	action_security_check( array( 'admin','staff' ) );

	// map fields
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );
	$phone 							= post( 'phone' );
	$type 							= post( 'type' );

	// does user already exist
	$query = $conn->query( "
        SELECT `id` 
        FROM `users` 
        WHERE `email` = '".$email."' 
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
			(`added`,`added_by`,`type`,`email`,`phone`,`first_name`,`last_name`,`status`)
			VALUE
			('".time()."', 
			'".$account_details['id']."',
			'".$type."', 
			'".$email."',
			'".$phone."',
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
}

function user_edit() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// map fields
	$user_id 						= post( 'user_id' );
	$first_name 					= post( 'first_name' );
	$last_name 						= post( 'last_name' );
	$email 							= post( 'email' );
	$password 						= post( 'password' );
	$type 							= post( 'type' );
	$status 						= post( 'status' );
	$address_1 						= post( 'address_1' );
	$address_2 						= post( 'address_2' );
	$address_city 					= post( 'address_city' );
	$address_state 					= post( 'address_state' );
	$address_zip 					= post( 'address_zip' );
	$address_country 				= post( 'address_country' );
	$phone 							= post( 'phone' );
	$notes 							= post( 'notes' );

	// save data
	if( $admin_check || $staff_check ) {
		$update = $conn->exec( "UPDATE `users` SET `status` = '".$status."' WHERE `id` = '".$user_id."' " );
		$update = $conn->exec( "UPDATE `users` SET `type` = '".$type."' WHERE `id` = '".$user_id."' " );
		$update = $conn->exec( "UPDATE `users` SET `notes` = '".$notes."' WHERE `id` = '".$user_id."' " );
	}
	$update = $conn->exec( "UPDATE `users` SET `first_name` = '".$first_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `last_name` = '".$last_name."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `password` = '".$password."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `phone` = '".$phone."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_1` = '".$address_1."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_2` = '".$address_2."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_city` = '".$address_city."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_state` = '".$address_state."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_zip` = '".$address_zip."' WHERE `id` = '".$user_id."' " );
	$update = $conn->exec( "UPDATE `users` SET `address_country` = '".$address_country."' WHERE `id` = '".$user_id."' " );

	// set status message
	status_message( "success", "User has been updated." );

	// redirect
	go( $_SERVER['HTTP_REFERER'] );
}

function user_delete() {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	// security point
	action_security_check( array( 'admin','staff' ) );

	// map fields
	$id 							= get( 'id' );

	// update customer records to remove primary or secondary contact ids
	$update = $conn->exec( "UPDATE `customers` SET `primary_contact_id` = '' WHERE `primary_contact_id` = '".$id."' " );
	$update = $conn->exec( "UPDATE `customers` SET `secondary_contact_id` = '' WHERE `secondary_contact_id` = '".$id."' " );

	// delete data
	$delete = $conn->exec( "DELETE FROM `users` WHERE `id` = '".$id."' " );

	// set status message
	status_message( "success", "User has been deleted." );

	// redirect
	go( 'dashboard.php?c=users' );
}


// system settings
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
