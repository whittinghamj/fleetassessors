<?php

// dashboard stats > jobs per day
function dashboard_stats_jobs_per_customer() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$stats = array();

	// get data
	$query = $conn->query( "
		SELECT FROM_UNIXTIME(`added`, '%d.%m.%Y') as ndate,
		count( id ) as post_count
		FROM `jobs`
		GROUP BY ndate
	" );

	$stats = $query->fetchAll( PDO::FETCH_ASSOC );

	// sanity check
	$stats = stripslashes_deep( $stats );

	return $stats;
}

// dashboard stats > job statuses pie chart
function dashboard_stats_statuses() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$stats = array();
	$stats['approved'] 		= 0;
	$stats['cancelled'] 	= 0;
	$stats['new'] 			= 0;
	$stats['rejected'] 		= 0;
	$stats['submitted'] 	= 0;

	// get data
	$query = $conn->query( "
		SELECT `id`,`status` 
		FROM `jobs` 
	" );

	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each order
	foreach( $data as $bit ) {
		// count status value
		if( $bit['status'] == 'approved' ) {
			$stats['approved']++;
		}
		if( $bit['status'] == 'cancelled' ) {
			$stats['cancelled']++;
		}
		if( $bit['status'] == 'new' ) {
			$stats['new']++;
		}
		if( $bit['status'] == 'rejected' ) {
			$stats['rejected']++;
		}
		if( $bit['status'] == 'submitted' ) {
			$stats['submitted']++;
		}
	}

	// sanity check
	$stats = stripslashes_deep( $stats );

	return $stats;
}

// dashboard stats > jobs per customer pie chart
function dashboard_stats_jobs_per_customer() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$stats = array();

	// get data
	$query = $conn->query( "
		SELECT `id`,`status`,`customer_id`
		FROM `jobs` 
	" );

	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each order
	foreach( $data as $bit ) {
		$stats[$count] = $bit;

		// fill customer details
		$stats[$count]['customer'] = get_customer_lite( $bit['customer_id'] );

		$count++;
	}

	// sanity check
	$stats = stripslashes_deep( $stats );

	return $stats;
}

// strip everything but numbers
function only_numbers( $string ) {
	$string 		= str_replace( ' ', '', $string );
	$string 		= str_replace( '£', '', $string );
	$string 		= str_replace( ',', '', $string );
	$string 		= preg_replace( "/[^0-9\.]/", "", $string );

	return $string;
}

// action security check
function action_security_check( $security_levels ) {
	global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check;

	foreach( $security_levels as $security_level ) {
		error_log( 'checkpoint ' . $security_level );

		// admin check
		if( $security_level == 'admin' ) {
			if( $admin_check ) { break; } else { error_log('failed '.$security_level.' checkpoint'); go( 'dashboard.php?c=access_denied' ); }
		}

		// staff check
		if( $security_level == 'staff' ) {
			if( $staff_check ) { break; } else { error_log('failed '.$security_level.' checkpoint'); go( 'dashboard.php?c=access_denied' ); }
		}
	}

	error_log( 'checkpoint passed' );
}

// add vat
function vat_add( $price, $vat = 20 ) {
    $nett = $price;
    $gross = $nett * ( $vat / 100 ) + $nett;
    return $gross;
}

// remove vat
function vat_remove( $price, $vat = 20 ) {
	$data['vat'] = $vat;
	$data['inc_vat'] = $price;

	//Divisor (for our math).
	$vatDivisor = 1 + ( $vat / 100 );

	//Determine the price before VAT.
	$priceBeforeVat = $price / $vatDivisor;

	//Determine how much of the gross price was VAT.
	$vatAmount = $price - $priceBeforeVat;

	//Print out the price before VAT.
	// echo number_format($priceBeforeVat, 2), '<br>';
	$data['ex_vat'] = $priceBeforeVat ;

	//Print out how much of the gross price was VAT.
	// echo 'VAT @ ' . $vat . '% - ' . number_format($vatAmount, 2), '<br>';
	$data['vat_amount'] = $vatAmount;

	//Print out the gross price.
	// echo $price;

	return $priceBeforeVat;
}

// price with vat details
function vat_details( $price, $vat = 20 ) {
	$data['vat'] = $vat;
	$data['inc_vat'] = round( $price, 2 );

	//Divisor (for our math).
	$vatDivisor = 1 + ( $vat / 100 );

	//Determine the price before VAT.
	$priceBeforeVat = $price / $vatDivisor;

	//Determine how much of the gross price was VAT.
	$vatAmount = $price - $priceBeforeVat;

	//Print out the price before VAT.
	// echo number_format($priceBeforeVat, 2), '<br>';
	$data['ex_vat'] = round( $priceBeforeVat, 2 );

	//Print out how much of the gross price was VAT.
	// echo 'VAT @ ' . $vat . '% - ' . number_format($vatAmount, 2), '<br>';
	$data['vat_amount'] = round( $vatAmount, 2 );

	//Print out the gross price.
	// echo $price;

	return $data;
}

// map functions to find nearest florist
function distance( $a, $b ) {
	list( $lat1, $lon1 ) = $a;
	list( $lat2, $lon2 ) = $b;

	$theta = $lon1 - $lon2;
	$dist = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) + cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
	$dist = acos( $dist );
	$dist = rad2deg( $dist ) ;
	$miles = $dist * 60 * 1.1515;
	return $miles;
}

// count jobs with status filter
function total_jobs( $status = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // get data
    if( $admin_check || $staff_check ) {
		if( empty( $status ) ) {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
			";
		} else {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
		    	WHERE `status` = '".$status."'
			";
		}
	} else {
		if( empty( $status ) ) {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
			";
		} else {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
		    	WHERE `status` = '".$status."' 
			";
		}
	}
	// get data
    $query      = $conn->query( $sql );

    // sanity check
    $data    	= $query->fetch(PDO::FETCH_ASSOC);

    return $data['total'];
}

// count jobs for customer
function total_jobs_( $customer_id = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // get data
    if( $admin_check || $staff_check ) {
		if( empty( $status ) ) {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
			";
		} else {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
		    	WHERE `status` = '".$status."'
			";
		}
	} else {
		if( empty( $status ) ) {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
			";
		} else {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `jobs` 
		    	WHERE `status` = '".$status."' 
			";
		}
	}
	// get data
    $query      = $conn->query( $sql );

    // sanity check
    $data    	= $query->fetch(PDO::FETCH_ASSOC);

    return $data['total'];
}

// count jobs for customer
function total_jobs_for_customer( $customer_id = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // get data
    $sql = "
		SELECT count(`id`) as total 
    	FROM `jobs` 
    	WHERE `customer_id` = '".$customer_id."'
	";
	// get data
    $query      = $conn->query( $sql );

    // sanity check
    $data    	= $query->fetch(PDO::FETCH_ASSOC);

    return $data['total'];
}

function obfuscate_email( $email ) {
	$em	= explode( "@",$email );
	$name = implode( '@', array_slice( $em, 0, count( $em )-1 ) );
	$len= floor( strlen( $name) / 2 );

	return substr( $name, 0, $len ) . str_repeat( '*', $len ) . "@" . end( $em );	
}

// count providers with status filter
function total_providers( $status = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // get data
    if( $admin_check || $staff_check ) {
		if( empty( $status ) ) {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `providers` 
			";
		} else {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `providers` 
		    	WHERE `status` = '".$status."'
			";
		}
	} else {
		if( empty( $status ) ) {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `providers` 
			";
		} else {
			$sql = "
				SELECT count(`id`) as total 
		    	FROM `providers` 
		    	WHERE `status` = '".$status."' 
			";
		}
	}
	// get data
    $query      = $conn->query( $sql );

    // sanity check
    $data    	= $query->fetch(PDO::FETCH_ASSOC);

    return $data['total'];
}

// count users with type filter
function total_users( $type = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // get data
    if( empty( $type ) ) {
		$sql = "
			SELECT count(`id`) as total 
	    	FROM `users` 
		";
	} else {
		$sql = "
			SELECT count(`id`) as total 
	    	FROM `users` 
	    	WHERE `type` = '".$type."'  
		";
	}
	// get data
    $query      = $conn->query( $sql );

    // sanity check
    $data    	= $query->fetch(PDO::FETCH_ASSOC);

    return $data['total'];
}

// count customers with type filter
function total_customers( $type = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // get data
    if( empty( $type ) ) {
		$sql = "
			SELECT count(`id`) as total 
	    	FROM `customers` 
		";
	} else {
		$sql = "
			SELECT count(`id`) as total 
	    	FROM `customers` 
	    	WHERE `type` = '".$type."'  
		";
	}
	// get data
    $query      = $conn->query( $sql );

    // sanity check
    $data    	= $query->fetch(PDO::FETCH_ASSOC);

    return $data['total'];
}

// calculate total profit
function total_profit( $start_time = '' ) {
    global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

    // defaults
    $total 					= array();
    $total['gross'] 		= '0.00'; // before outgoings
    $total['payouts'] 		= '0.00'; // outgoings
    $total['net'] 			= '0.00'; // whats left

    // get data
    if( $admin_check || $staff_check ) {
	    if( empty( $start_time ) ) {
			$sql = "
				SELECT * 
		    	FROM `user_transactions` 
		    	WHERE `status` = 'complete' 
			";
		} else {
			$sql = "
				SELECT * 
		    	FROM `user_transactions` 
		    	WHERE `status` = 'complete' 
		    	AND `added` > '".$start_time."' 
			";
		}
	} else {
		if( empty( $start_time ) ) {
			$sql = "
				SELECT * 
		    	FROM `user_transactions` 
		    	WHERE `status` = 'complete' 
		    	AND `user_id` = '".$account_details['id']."' 
			";
		} else {
			$sql = "
				SELECT * 
		    	FROM `user_transactions` 
		    	WHERE `status` = 'complete' 
		    	AND `user_id` = '".$account_details['id']."' 
		    	AND `added` > '".$start_time."' 
			";
		}
	}
	// get data
    $query      = $conn->query( $sql );
    $data    	= $query->fetchAll(PDO::FETCH_ASSOC);

    // loop over data
    foreach( $data as $bit ) {
    	if( $admin_check || $staff_check ) {
	    	$total['gross'] 		= ( $total['gross'] + $bit['order_amount'] );
	    	$total['payouts'] 		= ( $total['payouts'] + $bit['florist_payment_amount'] );
	    	$total['net'] 			= ( $total['net'] + $bit['network_fee'] );
	    } else {
	    	$total['gross'] 		= ( $total['gross'] + $bit['florist_payment_amount'] );
    	}
    }

    return $total;
}

// shorten string of text to X character long with trailing ...
function truncate( $string, $length, $dots = "..." ) {
	$string = trim( $string );

	if( strlen( $string ) > $length ) {
		$string = wordwrap( $string, $length );
		$string = explode( "\n", $string, 2 );
			$string = $string[0] . $dots;
	}

	return $string;
}

// get the first letter of each word in string of text
function get_first_letters( $string ) {
	$expr = '/(?<=\s|^)[a-z]/i';
	preg_match_all( $expr, $string, $matches );

	$result = implode( '', $matches[0] );

	$result = strtoupper( $result );

	return $result;
}

// partially hide email with *****
function mask_email( $email ) {
	$em	= explode( "@",$email );
	$name = implode( '@', array_slice( $em, 0, count( $em )-1 ) );
	$len= floor( strlen( $name) / 2 );

	return substr( $name, 0, $len ) . str_repeat( '*', $len ) . "@" . end( $em );	
}

// get the difference between two dates
function get_date_diff( $time1, $time2, $precision = 2 ) {
	
	// If not numeric then convert timestamps
	if( !is_int( $time1 ) ) {
		$time1 = strtotime( $time1 );
	}
	if( !is_int( $time2 ) ) {
		$time2 = strtotime( $time2 );
	}

	// If time1 > time2 then swap the 2 values
	if( $time1 > $time2 ) {
		list( $time1, $time2 ) = array( $time2, $time1 );
	}

	// Set up intervals and diffs arrays
	$intervals = array( 'year', 'month', 'day', 'hour', 'minute', 'second' );
	$diffs = array();

	foreach( $intervals as $interval ) {
		// Create temp time from time1 and interval
		$ttime = strtotime( '+1 ' . $interval, $time1 );
		// Set initial values
		$add = 1;
		$looped = 0;
		// Loop until temp time is smaller than time2
		while ( $time2 >= $ttime ) {
			// Create new temp time from time1 and interval
			$add++;
			$ttime = strtotime( "+" . $add . " " . $interval, $time1 );
			$looped++;
		}

		$time1 = strtotime( "+" . $looped . " " . $interval, $time1 );
		$diffs[ $interval ] = $looped;
	}

	$count = 0;
	$times = array();
	foreach( $diffs as $interval => $value ) {
		// Break if we have needed precission
		if( $count >= $precision ) {
			break;
		}
		// Add value and interval if value is bigger than 0
		if( $value > 0 ) {
			if( $value != 1 ) {
				$interval .= "s";
			}
			// Add value and interval to times array
			$times[] = $value . " " . $interval;
			$count++;
		}
	}

	// Return string with times
	return implode( ", ", $times );
}

// sort an array
function array_sort( $data ) {
	usort( $data, function( $a, $b ) {
		return $a['name'] <=> $b['name'];
	});
}

// print all php local and global vars
function print_all_vars() {
	$arr = get_defined_vars();
	return debug( $arr );
}

// convert object to array
function objectToArray( $object ) {
	if( !is_object( $object ) && !is_array( $object ) ) {
		return $object;
	}
	return array_map( 'objectToArray', ( array ) $object );
}

// search a multi dimensional array
function search_multi_array( $dataArray, $search_value, $key_to_search ) {
	// This function will search the revisions for a certain value
	// related to the associative key you are looking for.
	$keys = array();
	foreach( $dataArray as $key => $cur_value ) {
		if( $cur_value[$key_to_search] == $search_value ) {
			$keys[] = $key;
		}
	}

	return $keys;
}

// get user account
function get_user( $id ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `users` 
		WHERE `id` = '".$id."' 
	" );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	// sanity check
	if( isset( $data['id'] ) ) {
		// create full_name
		$data['full_name']		= $data['first_name'].' '.$data['last_name'];

		// create initials
		$data['initials']		= get_first_letters( $data['full_name'] );

		// full address
		$data['full_address'] 	= $data['address_1'].', '.$data['address_city'].', '.$data['address_state'].', '.$data['address_zip'].', '.$data['address_country'];
		if( $data['full_address'] == ', , , , ' ) {
			$data['full_address'] = '';
		}
	}

	// sanity check
	$data = stripslashes_deep( $data );

	return $data;
}

// get all users with a type filter
function get_users( $type = '' ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$users = array();

	// process filter
	if( empty( $type ) ) {
		$query = $conn->query( "
			SELECT * 
			FROM `users` 
			ORDER BY `last_name`,`first_name` ASC 
		" );
	} else {
		$query = $conn->query( "
			SELECT * 
			FROM `users` 
			WHERE `type` = '".$type."' 
			ORDER BY `last_name`,`first_name` ASC 
		" );
	}

	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each user
	foreach( $data as $bit ) {
		// add existing data
		$users[$count] = $bit;

		// full name
		$users[$count]['full_name']	 	= $bit['first_name'].' '.$bit['last_name'];

		// create initials
		$data['initials']				= get_first_letters( $users[$count]['full_name'] );

		// full address
		$users[$count]['full_address'] 	= $bit['address_1'].', '.$bit['address_city'].', '.$bit['address_state'].', '.$bit['address_zip'].', '.$bit['address_country'];
		if( $users[$count]['full_address'] == ', , , , ' ) {
			$users[$count]['full_address'] = '';
		}

		$count++;
	}

	// sanity check
	$users = stripslashes_deep( $users );

	return $users;
}

// get jobs for customer
function get_jobs( $customer = '' ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$jobs = array();

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `jobs` 
		WHERE `customer_id` = '".$customer."' 
	" );

	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each user
	foreach( $data as $bit ) {
		// add existing data
		$jobs[$count] = $bit;

		// vrn details
		$jobs[$count]['vrn_details'] = get_vrn( $bit['vrn'] );

		$count++;
	}

	// sanity check
	$jobs = stripslashes_deep( $jobs );

	return $jobs;
}

// get all jobs
function get_all_jobs() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$jobs = array();

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `jobs` 
	" );

	$jobs = $query->fetchAll( PDO::FETCH_ASSOC );

	// sanity check
	$jobs = stripslashes_deep( $jobs );

	return $jobs;
}

// get all jobs lite
function get_all_jobs_lite( $customer = '' ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$jobs = array();

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `jobs` 
	" );

	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each user
	foreach( $data as $bit ) {
		// add existing data
		$jobs[$count] = $bit;

		// vrn details
		$jobs[$count]['vrn_details'] = get_vrn( $bit['vrn'] );

		$count++;
	}

	// sanity check
	$jobs = stripslashes_deep( $jobs );

	return $jobs;
}

// get job details
function get_job( $id ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `jobs` 
		WHERE `id` = '".$id."' 
	" );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	// calculate age or completion time
	if( $data['status'] == 'cancelled' || $data['status'] == 'complete' ) {
		// calculate completion time
		$datediff = $data['updated'] - $data['added'];
	} else {
		// calculate job age
		$datediff = time() - $data['added'];
	}

	$data['job_age'] = round( $datediff / ( 60 * 60 * 24 ) );

	// sanity check
	$data = stripslashes_deep( $data );

	return $data;
}

// get vrn details
function get_vrn( $vrn ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `vrn_database` 
		WHERE `vrn` = '".$vrn."' 
	" );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	// time left until next mot
	$time_until_next_mot = strtotime( $data['next_mot_date'] );

	// is mot valid
	$time_diff = ( $time_until_next_mot - time() );
	if( $time_diff < 1 ) {
		$data['is_mot_valid'] = 'invalid';
	} else {
		$data['is_mot_valid'] = 'valid';
	}

	// sanity check
	$data = stripslashes_deep( $data );

	return $data;
}

// get all vrn details
function get_all_vrns() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$vrns = array();

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `vrn_database` 
	" );
	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each user
	foreach( $data as $bit ) {
		// add existing data
		$vrns[$count] = $bit;

		// time left until next mot
		$time_until_next_mot = strtotime( $bit['next_mot_date'] );

		// is mot valid
		$time_diff = ( $time_until_next_mot - time() );
		if( $time_diff < 1 ) {
			$vrns[$count]['is_mot_valid'] = 'invalid';
		} else {
			$vrns[$count]['is_mot_valid'] = 'valid';
		}

		$count++;
	}

	// sanity check
	$vrns = stripslashes_deep( $vrns );

	return $vrns;
}

// get all customers details
function get_customers() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$customers = array();

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `customers` 
	" );

	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each order
	foreach( $data as $bit ) {
		// add existing data
		$customers[$count] = $bit;

		// add additional data
		$customers[$count]['total_jobs']					= total_jobs_for_customer( $bit['id'] );
		$customers[$count]['primary_contact']				= get_user( $bit['primary_contact_id'] );

		// build full address
		$customers[$count]['full_address'] 					= $bit['address_1'].', '.$bit['address_city'].', '.$bit['address_state'].', '.$bit['address_zip'].', '.$bit['address_country'];
		if( $customers[$count]['full_address'] == ', , , , ' ) {
			$customers[$count]['full_address'] = '';
		}

		$count++;
	}

	// sanity check
	$customers = stripslashes_deep( $customers );

	return $customers;
}

// get customer details
function get_customer( $id = '' ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `customers` 
		WHERE `id` = '".$id."' 
	" );
	$data = $query->fetch( PDO::FETCH_ASSOC );

	// find added_by
	$data['added_by_user'] = array();
	$users = get_users();
	foreach( $users as $user ) {
		if( $data['added_by'] == $user['id'] ) {
			$data['added_by_user'] = $user;
			break;
		}
	}

	// get primary contact details
	$data['primary_contact'] = get_user( $data['primary_contact_id'] );

	// get secondary contact details
	$data['secondary_contact'] = get_user( $data['secondary_contact_id'] );

	// build full address
	$data['full_address'] = $data['address_1'].', '.$data['address_city'].', '.$data['address_state'].', '.$data['address_zip'].', '.$data['address_country'];

	// get jobs
	$data['jobs'] = get_jobs( $id );

	// calculate total profit from all approved jobs
	$total_profit = 0;
	foreach( $data['jobs'] as $job ) {
		$total_profit = ( $total_profit + $job['approved_estimate'] );
	}
	$data['total_approved_uplifts'] = $total_profit;

	// sanity check
	$data = stripslashes_deep( $data );

	return $data;
}

// get all providers
function get_providers() {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	// create blank array
	$providers = array();

	// get data
	$query = $conn->query( "
		SELECT * 
		FROM `providers` 
		ORDER BY `name` ASC
	" );
	$data = $query->fetchAll( PDO::FETCH_ASSOC );

	$count = 0;

	// loop over data to add additional details about each product
	foreach( $data as $bit ) {
		// add existing data
		$providers[$count] = $bit;

		$count++;
	}

	// sanity check
	$providers = stripslashes_deep( $providers );

	return $providers;
}



function stripslashes_deep( $value ) {
	// $value = is_array( $value ) ? array_map( 'stripslashes_deep', $value ) : stripslashes( $value );
	// return $value;

	/*
	foreach( $value as &$val ) {
		if( is_array( $val ) ) {
			$val = unstrip_array( $val );
		}else{
			$val = stripslashes( $val );
		}
	}

	return $value;
	*/

	return map_deep( $value, 'stripslashes_from_strings_only' );
}

function stripslashes_from_strings_only( $value ) {
	return is_string( $value ) ? stripslashes( $value ) : $value;

	// return $var;
}

function map_deep( $value, $callback ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $index => $item ) {
			$value[ $index ] = map_deep( $item, $callback );
		}
	}
 
	return $value;
}

function super_unique( $array,$key ) {
	$temp_array = [];
	foreach( $array as &$v ) {
		if( !isset( $temp_array[$v[$key]] ) )
		$temp_array[$v[$key]] =& $v;
	}
	$array = array_values( $temp_array );
	return $array;
}
	
function multi_unique( $src ) {
	$output = array_map( "unserialize" , array_unique( array_map( "serialize", $src ) ) );
	return $output;
}

function encrypt_old( $string, $key = 32 ) {
	$result = '';
	for($i=0, $k= strlen($string); $i<$k; $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result .= $char;
	}
	return base64_encode($result);
}

function decrypt_old( $string, $key = 32 ) {
	$result = '';
	$string = base64_decode($string);
	for($i=0,$k=strlen($string); $i< $k ; $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}
	return $result;
}

function encrypt( $string ) {
	// Store the cipher method
	$ciphering = "AES-128-CTR";

	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length( $ciphering );
	$options = 0;

	// Non-NULL Initialization Vector for encryption
	$encryption_iv = '1234567891011121';

	// Store the encryption key
	$encryption_key = "admin1372";

	// Use openssl_encrypt() function to encrypt the data
	$encryption = openssl_encrypt( $string, $ciphering, $encryption_key, $options, $encryption_iv );

	return $encryption;
}

function decrypt( $string ) {
	// Store the cipher method
	$ciphering = "AES-128-CTR";

	$options = 0;

	// Non-NULL Initialization Vector for decryption
	$decryption_iv = '1234567891011121';

	// Store the decryption key
	$decryption_key = "admin1372";

	// Use openssl_decrypt() function to decrypt the data
	$decryption = openssl_decrypt( $string, $ciphering, $decryption_key, $options, $decryption_iv );

	return $decryption;
}

function formatSizeUnits($bytes) {
	if ($bytes >= 1073741824)
	{
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576)
	{
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024)
	{
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	}
	elseif ($bytes > 1)
	{
		$bytes = $bytes . ' bytes';
	}
	elseif ($bytes == 1)
	{
		$bytes = $bytes . ' byte';
	}
	else
	{
		$bytes = '0 bytes';
	}

	return $bytes;
}

function ping( $host ) {
	exec( sprintf( 'ping -c 5 -W 5 %s', escapeshellarg( $host ) ), $res, $rval );
	return $rval === 0;
}

function uptime( int $seconds = null, int $requiredParts = null ) {
	if( $seconds != NULL ) {
		$from	= new \DateTime('@0');
		$to		= new \DateTime("@$seconds" );
		$interval = $from->diff($to);
		$str	= '';

		$parts = [
			'y' => 'y',
			'm' => 'm',
			'd' => 'd',
			'h' => 'h',
			'i' => 'm',
			's' => 's',
		];

		$includedParts = 0;

		foreach ($parts as $key => $text) {
			if ($requiredParts && $includedParts >= $requiredParts) {
				break;
			}

			$currentPart = $interval->{$key};

			if (empty($currentPart)) {
				continue;
			}

			if (!empty($str)) {
				$str .= ', ';
			}

			$str .= sprintf('%d%s', $currentPart, $text);

			if ($currentPart > 1) {
				// handle plural
				$str .= '';
			}

			$includedParts++;
		}

		return $str;
	} else {
		return '';
	}
}

function code_to_country( $code ) {
	$code = strtoupper($code);

	$countryList = array(
		'AF' => 'Afghanistan',
		'AX' => 'Aland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas the',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island (Bouvetoya)',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
		'VG' => 'British Virgin Islands',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros the',
		'CD' => 'Congo',
		'CG' => 'Congo the',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote d\'Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FO' => 'Faroe Islands',
		'FK' => 'Falkland Islands (Malvinas)',
		'FJ' => 'Fiji the Fiji Islands',
		'FI' => 'Finland',
		'FR' => 'France, French Republic',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia the',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island and McDonald Islands',
		'VA' => 'Holy See (Vatican City State)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'Korea',
		'KR' => 'Korea',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyz Republic',
		'LA' => 'Lao',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libyan Arab Jamahiriya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'AN' => 'Netherlands Antilles',
		'NL' => 'Netherlands the',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territory',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn Islands',
		'PL' => 'Poland',
		'PT' => 'Portugal, Portuguese Republic',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthelemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin',
		'PM' => 'Saint Pierre and Miquelon',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome and Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia (Slovak Republic)',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia, Somali Republic',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard & Jan Mayen Islands',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland, Swiss Confederation',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States of America',
		'UM' => 'United States Minor Outlying Islands',
		'VI' => 'United States Virgin Islands',
		'UY' => 'Uruguay, Eastern Republic of',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	if( !$countryList[$code] ) return $code;
	else return $countryList[$code];
}

function country_to_code( $country ) {
	$countryList = array(
		'Afghanistan' => 'AF',
		'Aland Islands' => 'AX',
		'Albania' => 'AL',
		'Algeria' => 'DZ',
		'American Samoa' => 'AS',
		'Andorra' => 'AD',
		'Angola' => 'AO',
		'Anguilla' => 'AI',
		'Antarctica' => 'AQ',
		'Antigua and Barbuda' => 'AG',
		'Argentina' => 'AR',
		'Armenia' => 'AM',
		'Aruba' => 'AW',
		'Australia' => 'AU',
		'Austria' => 'AT',
		'Azerbaijan' => 'AZ',
		'Bahamas the' => 'BS',
		'Bahrain' => 'BH',
		'Bangladesh' => 'BD',
		'Barbados' => 'BB',
		'Belarus' => 'BY',
		'Belgium' => 'BE',
		'Belize' => 'BZ',
		'Benin' => 'BJ',
		'Bermuda' => 'BM',
		'Bhutan' => 'BT',
		'Bolivia' => 'BO',
		'Bosnia and Herzegovina' => 'BA',
		'Botswana' => 'BW',
		'Bouvet Island (Bouvetoya)' => 'BV',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
		'VG' => 'British Virgin Islands',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros the',
		'CD' => 'Congo',
		'CG' => 'Congo the',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote d\'Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FO' => 'Faroe Islands',
		'FK' => 'Falkland Islands (Malvinas)',
		'FJ' => 'Fiji the Fiji Islands',
		'FI' => 'Finland',
		'FR' => 'France, French Republic',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia the',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island and McDonald Islands',
		'VA' => 'Holy See (Vatican City State)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'Korea',
		'KR' => 'Korea',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyz Republic',
		'LA' => 'Lao',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libyan Arab Jamahiriya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'AN' => 'Netherlands Antilles',
		'NL' => 'Netherlands the',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territory',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn Islands',
		'PL' => 'Poland',
		'PT' => 'Portugal, Portuguese Republic',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthelemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin',
		'PM' => 'Saint Pierre and Miquelon',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome and Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia (Slovak Republic)',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia, Somali Republic',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard & Jan Mayen Islands',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland, Swiss Confederation',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States of America',
		'UM' => 'United States Minor Outlying Islands',
		'VI' => 'United States Virgin Islands',
		'UY' => 'Uruguay, Eastern Republic of',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Vietnam',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	if( !$countryList[$code] ) return $code;
	else return $countryList[$code];
}

// get account details
function account_details( $id ) {
	global $conn;
	
	// get local stored user record
	$query					= $conn->query( "SELECT * FROM `users` WHERE `id` = '".$id."' " );
	$data					= $query->fetch( PDO::FETCH_ASSOC );

	// create full_name
	$data['full_name']		= $data['first_name'].' '.$data['last_name'];
	
	// create initials
	$data['initials']		= get_first_letters( $data['full_name'] );

	// sanitize data
	$data = stripslashes_deep( $data );

	return $data;
}

function user_by_auth_token( $email_token ) {
	global $conn;
	
	// get local stored user record
	$query					= $conn->query("SELECT * FROM `users` WHERE `email_token` = '".$email_token."' ");
	$data					= $query->fetch(PDO::FETCH_ASSOC);

	// create full_name
	$data['full_name']		= $data['first_name'].' '.$data['last_name'];

	$data = stripslashes_deep( $data );

	return $data;
}

// console output
function console_output( $data ) {
	$timestamp = date( "Y-m-d H:i:s", time() );
	echo "[" . $timestamp . "] - " . $data . "\n";
}

// json output
function json_output( $data ) {
	$data['timestamp']		= time();
	$data 					= json_encode( $data );
	echo $data;
	die();
}

function formatbytes( $size, $precision = 2 ) {
	$base = log( $size, 1024 );
	$suffixes = array( '', 'K', 'M', 'G', 'T' );	

	// return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	return round( pow( 1024, $base - floor( $base ) ), $precision) ;
}

function filesize_formatted( $path ) {
	$size = filesize($path);
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
	$power = $size > 0 ? floor( log( $size, 1024 ) ) : 0;
	return number_format( $size / pow( 1024, $power ), 2, '.', ',' ) . ' ' . $units[$power];
}

function human_filesize( $size, $precision = 2 ) {
	$units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
	$step = 1024;
	$i = 0;
	while (($size / $step) > 0.9) {
		$size = $size / $step;
		$i++;
	}
	return round( $size, $precision ).' '.$units[$i];
}

// calculate percentage
function percentage( $val1, $val2, $precision ) {
	// sanity - remove non-number chars
	$val1 = preg_replace( "/[^0-9]/", "", $val1 );
	$val2 = preg_replace( "/[^0-9]/", "", $val2 );

	$division = $val1 / $val2;
	$res = $division * 100;
	$res = round( $res, $precision );
	return $res;
}

// go to url
function go( $link = '' ) {
	header( 'Location: ' . $link );
	die();
}

function url( $url = '' ) {
	$host = $_SERVER['HTTP_HOST'];
	$host = !preg_match( '/^http/', $host) ? 'http://' . $host : $host;
	$path = preg_replace( '/\w+\.php/', '', $_SERVER['REQUEST_URI'] );
	$path = preg_replace( '/\?.*$/', '', $path );
	$path = !preg_match( '/\/$/', $path ) ? $path . '/' : $path;
	if ( preg_match( '/http:/', $host)&& is_ssl() ) {
		$host = preg_replace( '/http:/', 'https:', $host );
	}
	if ( preg_match( '/https:/', $host ) && !is_ssl() ) {
		$host = preg_replace( '/https:/', 'http:', $host );
	}
	return $host . $path . $url;
}

// handle post data
function post( $key = null ) {
	if( is_null( $key ) ) {
		return $_POST;
	}
	$post = isset( $_POST[$key] ) ? $_POST[$key] : null;
	if( is_string( $post ) ) {
		$post = trim( $post );
	}

	// $post = addslashes( $post );
	return $post;
}

// handle post data in array format
function post_array( $key = null ) {
	if( is_null( $key ) ) {
		return $_POST;
	}
	$post = isset( $_POST[$key] ) ? $_POST[$key] : null;
	if ( is_string($post) ) {
		$post = trim($post);
	}

	return $post;
}

// handle get data
function get( $key = null ) {
	if( is_null( $key ) ) {
		return $_GET;
	}
	$get = isset( $_GET[$key] ) ? $_GET[$key] : null;
	if ( is_string( $get) ) {
		$get = trim( $get );
	}
	// $get = addslashes($get);
	return $get;
}

// handle request data
function request( $key = null ) {
	if ( is_null($key) ) {
		return $_REQUEST;
	}
	$request = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
	if ( is_string($request) ) {
		$request = trim($request);
	}
	// $get = addslashes($get);
	return $request;
}

// get gravatar image based upon email address
function get_gravatar( $email ){
	$image = 'https://www.gravatar.com/avatar.php?gravatar_id='.md5( $email );

	return $image;
}

// debug 
function debug( $input ) {
	$output = '<pre>';
	if ( is_array($input) || is_object($input) ) {
		$output .= print_r($input, true);
	} else {
		$output .= $input;
	}
	$output .= '</pre>';
	echo $output;
}

// set status message 
function status_message( $status, $message ) {
	$_SESSION['alert']['status']		= $status;
	$_SESSION['alert']['message']		= $message;
}

// load remote content
function remote_content( $url ) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);

	return $result;
}

// generate a random string of letters and numbers
function random_string( $length = 10 ) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen( $characters );
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand( 0, $charactersLength - 1 )];
	}
	return $randomString;
}

// accept terms modal
function accept_terms_modal() {
	global $conn, $account_details, $globals, $global_settings;

	$modal = '
		<div class="modal fade" id="cms_terms_modal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Terms and Conditions</h4>
					</div>
					<div class="modal-body">
						<p>
							<h2>Welcome to Stiliam CMS</h2>
							<p>These terms and conditions outline the rules and regulations for the use of Stiliam CMS\'s Website.</p> <br /> 

							<p>By accessing this website we assume you accept these terms and conditions in full. Do not continue to use Stiliam CMS\'s website 
							if you do not accept all of the terms and conditions stated on this page.</p>
							<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice
							and any or all Agreements: "Client", "You" and "Your" refers to you, the person accessing this website
							and accepting the Company\'s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers
							to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves, or either the Client
							or ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake
							the process of our assistance to the Client in the most appropriate manner, whether by formal meetings
							of a fixed duration, or any other means, for the express purpose of meeting the Client\'s needs in respect
							of provision of the Company\'s stated services/products, in accordance with and subject to, prevailing law
							of . Any use of the above terminology or other words in the singular, plural,
							capitalisation and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>
							<h4>Cookies</h4>
							<p>We employ the use of cookies. By using Stiliam CMS\'s website you consent to the use of cookies 
							in accordance with Stiliam CMS\'s privacy policy.</p><p>Most of the modern day interactive web sites
							use cookies to enable us to retrieve user details for each visit. Cookies are used in some areas of our site
							to enable the functionality of this area and ease of use for those people visiting. Some of our 
							affiliate / advertising partners may also use cookies.</p>
							<h4>License</h4>
							<p>Unless otherwise stated, Stiliam CMS and/or it\'s licensors own the intellectual property rights for
							all material on Stiliam CMS. All intellectual property rights are reserved. You may view and/or print
							pages from https://www.stiliam.com for your own personal use subject to restrictions set in these terms and conditions.</p>
							<p>You must not:</p>
							<ol>
								<li>Republish material from https://www.stiliam.com</li>
								<li>Sell, rent or sub-license material from https://www.stiliam.com</li>
								<li>Reproduce, duplicate or copy material from https://www.stiliam.com</li>
							</ol>
							<p>Redistribute content from Stiliam CMS (unless content is specifically made for redistribution).</p>
							<h4>Hyperlinking to our Content</h4>
							<ol>
								<li>The following organizations may link to our Web site without prior written approval:
									<ol>
									<li>Government agencies;</li>
									<li>Search engines;</li>
									<li>News organizations;</li>
									<li>Online directory distributors when they list us in the directory may link to our Web site in the same
										manner as they hyperlink to the Web sites of other listed businesses; and</li>
									<li>Systemwide Accredited Businesses except soliciting non-profit organizations, charity shopping malls,
										and charity fundraising groups which may not hyperlink to our Web site.</li>
									</ol>
								</li>
							</ol>
							<ol start="2">
								<li>These organizations may link to our home page, to publications or to other Web site information so long
									as the link: (a) is not in any way misleading; (b) does not falsely imply sponsorship, endorsement or
									approval of the linking party and its products or services; and (c) fits within the context of the linking
									party\'s site.
								</li>
								<li>We may consider and approve in our sole discretion other link requests from the following types of organizations:
									<ol>
										<li>commonly-known consumer and/or business information sources such as Chambers of Commerce, American
											Automobile Association, AARP and Consumers Union;</li>
										<li>dot.com community sites;</li>
										<li>associations or other groups representing charities, including charity giving sites,</li>
										<li>online directory distributors;</li>
										<li>internet portals;</li>
										<li>accounting, law and consulting firms whose primary clients are businesses; and</li>
										<li>educational institutions and trade associations.</li>
									</ol>
								</li>
							</ol>
							<p>We will approve link requests from these organizations if we determine that: (a) the link would not reflect
							unfavorably on us or our accredited businesses (for example, trade associations or other organizations
							representing inherently suspect types of business, such as work-at-home opportunities, shall not be allowed
							to link); (b)the organization does not have an unsatisfactory record with us; (c) the benefit to us from
							the visibility associated with the hyperlink outweighs the absence of Stiliam CMS; and (d) where the
							link is in the context of general resource information or is otherwise consistent with editorial content
							in a newsletter or similar product furthering the mission of the organization.</p>

							<p>These organizations may link to our home page, to publications or to other Web site information so long as
							the link: (a) is not in any way misleading; (b) does not falsely imply sponsorship, endorsement or approval
							of the linking party and it products or services; and (c) fits within the context of the linking party\'s
							site.</p>

							<p>If you are among the organizations listed in paragraph 2 above and are interested in linking to our website,
							you must notify us by sending an e-mail to <a href="mailto:info@stiliam.com" title="send an email to info@stiliam.com">info@stiliam.com</a>.
							Please include your name, your organization name, contact information (such as a phone number and/or e-mail
							address) as well as the URL of your site, a list of any URLs from which you intend to link to our Web site,
							and a list of the URL(s) on our site to which you would like to link. Allow 2-3 weeks for a response.</p>

							<p>Approved organizations may hyperlink to our Web site as follows:</p>

							<ol>
								<li>By use of our corporate name; or</li>
								<li>By use of the uniform resource locator (Web address) being linked to; or</li>
								<li>By use of any other description of our Web site or material being linked to that makes sense within the
									context and format of content on the linking party\'s site.</li>
							</ol>
							<p>No use of Stiliam CMS\'s logo or other artwork will be allowed for linking absent a trademark license
							agreement.</p>
							<h4>Iframes</h4>
							<p>Without prior approval and express written permission, you may not create frames around our Web pages or
							use other techniques that alter in any way the visual presentation or appearance of our Web site.</p>
							<h4>Reservation of Rights</h4>
							<p>We reserve the right at any time and in its sole discretion to request that you remove all links or any particular
							link to our Web site. You agree to immediately remove all links to our Web site upon such request. We also
							reserve the right to amend these terms and conditions and its linking policy at any time. By continuing
							to link to our Web site, you agree to be bound to and abide by these linking terms and conditions.</p>
							<h4>Removal of links from our website</h4>
							<p>If you find any link on our Web site or any linked web site objectionable for any reason, you may contact
							us about this. We will consider requests to remove links but will have no obligation to do so or to respond
							directly to you.</p>
							<p>Whilst we endeavour to ensure that the information on this website is correct, we do not warrant its completeness
							or accuracy; nor do we commit to ensuring that the website remains available or that the material on the
							website is kept up to date.</p>
							<h4>Content Liability</h4>
							<p>We shall have no responsibility or liability for any content appearing on your Web site. You agree to indemnify
							and defend us against all claims arising out of or based upon your Website. No link(s) may appear on any
							page on your Web site or within any context containing content or materials that may be interpreted as
							libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or
							other violation of, any third party rights.</p>
							<h4>Disclaimer</h4>
							<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website (including, without limitation, any warranties implied by law in respect of satisfactory quality, fitness for purpose and/or the use of reasonable care and skill). Nothing in this disclaimer will:</p>
							<ol>
							<li>limit or exclude our or your liability for death or personal injury resulting from negligence;</li>
							<li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
							<li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
							<li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
							</ol>
							<p>The limitations and exclusions of liability set out in this Section and elsewhere in this disclaimer: (a)
							are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer or
							in relation to the subject matter of this disclaimer, including liabilities arising in contract, in tort
							(including negligence) and for breach of statutory duty.</p>
							<p>We will not be liable for any loss or damage of any nature.</p>
						</p>
					</div>
					<div class="modal-footer justify-content-between">
						<a href="actions.php?a=accept_terms" class="btn btn-block btn-success">Accept Terms &amp; Conditions</a>
						<a href="logout.php" class="btn btn-block btn-danger">Reject Terms &amp; Conditions</a>
					</div>
				</div>
			</div>
		</div>
			';

	echo $modal;
}

// check if env is www or cli
function is_cli() {
	if( defined( 'STDIN' ) ) {
		return 'cli';
	}

	if( php_sapi_name() === 'cli' ) {
		return 'cli';
	}

	if( array_key_exists( 'SHELL' , $_ENV) ) {
		return 'cli';
	}

	if( empty( $_SERVER['REMOTE_ADDR'] ) and !isset( $_SERVER['HTTP_USER_AGENT'] ) and count( $_SERVER['argv'] ) > 0 ) {
		return 'cli';
	} 

	if( !array_key_exists( 'REQUEST_METHOD' , $_SERVER) ) {
		return 'cli';
	}

	return 'web';
}

function getDifferenceChange( $number_one, $number_two ) {
	$difference = $number_one - $number_two;

	return $difference;
}

function getPercentageChange( $number_one, $number_two ) {
	$percentage = ( 1 - $number_one / $number_two ) * 100;
	$percentage	= round( $percentage, 2 );

	return $percentage;
}

// send telegram message
function send_telegram( $api_token, $chat_id, $message ) {
	$apiToken = $api_token;

	$data = [
		'chat_id' => '@'.$chat_id,
		'text' => $message
	];

	$response = @file_get_contents( "https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query( $data ) );
}

// get external data via curl
function get_data( $url ) {
	$curl = curl_init();

	curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36' );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
	// curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, 0 );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl, CURLOPT_TIMEOUT, 5 );
	curl_setopt( $curl, CURLOPT_URL, $url );
	$data = curl_exec( $curl );

	return $data;
}

// calculate percentage change
function calculate_percentage_change( $base_bumber, $percentage ) {
	global $conn, $account_details, $globals, $admin_check, $dev_check, $customer_check, $staff_check;

	$difference = ( $base_bumber / 100 ) * $percentage;

	$result = $base_bumber + $difference;

	return $result;
}