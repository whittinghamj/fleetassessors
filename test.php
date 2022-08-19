<?php

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

$account_details = account_details( $_SESSION['account']['id'] );

$tutorials = array();
$tutorials['home']                  = 'unfinished';
$tutorials['message']               = 'unfinished';
$tutorials['messages']              = 'unfinished';
$tutorials['message_new']           = 'unfinished';
$tutorials['order']                 = 'unfinished';
$tutorials['orders']                = 'unfinished';
$tutorials['product']               = 'unfinished';
$tutorials['products']              = 'unfinished';
$tutorials['user']                  = 'unfinished';
$tutorials['users']                 = 'unfinished';
$tutorials['subscription']          = 'unfinished';
$tutorials['subscriptions']         = 'unfinished';

$output = serialize( $tutorials );

echo $output;