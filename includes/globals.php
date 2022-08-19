<?php

// site vars
$globals['url']					= 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].'/';
$globals['platform_name']		= 'CoastLine';
$globals['platform_version']	= '1.0.0';
$globals['copyright']			= 'Written by Jamie Whittingham.';

// get settings table contents
$query = $conn->query( "SELECT `name`,`value` FROM `system_settings` " );
$globals_temp = $query->fetchAll( PDO::FETCH_ASSOC );

foreach( $globals_temp as $bits ) {
	$globals[$bits['name']] = $bits['value'];
}