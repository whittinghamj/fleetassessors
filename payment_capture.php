<?php
// stripe keys
// define("STRIPE_SECRET_KEY", "sk_test_sa0QRUIVgFphzWQZ0gypyAv0");
// define("STRIPE_PUBLISHABLE_KEY", "pk_test_iUFUXx45G0sVuoHoKC1BeiXi");

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// login check
if( !isset( $_SESSION['logged_in'] ) || $_SESSION['logged_in'] != true ) {
    // set status message
    status_message( "danger", "Login session expired." );

    // redirect
    go( 'index.php' );
} else {
    $account_details = account_details( $_SESSION['account']['id'] );
}

use \PhpPot\Service\StripePayment;

if( !empty( $_POST["token"] ) ) {
    require_once( 'StripePayment.php' );
    $stripePayment = new StripePayment();
    
    $stripeResponse = $stripePayment->chargeAmountFromCard( $_POST );
    
    // echo '<pre>';
    // print_r( $_POST );
    // print_r( $stripeResponse );
    // echo '</pre>';
    // die();

    $order_id = post( 'order_id' );

    // sanity check
    if( $stripeResponse['status'] != 'paid' ) {
        // set status message
        status_message( "danger", "Payment was declined." );
    } else {
        // modify values
        $stripeResponse['amount']                   = ( $stripeResponse['amount'] / 100 );
        $stripeResponse['amount_captured']          = ( $stripeResponse['amount_captured'] / 100 );

        // save data
        $insert = $conn->exec( "INSERT INTO `order_payments` 
            (`added`,`status`,`order_id`,`stripe_id`,`amount`,`amount_captured`,`balance_transaction`,`captured`,`payment_method`,`card_id`,`card_number`,`card_cvc`,`card_exp_month`,`card_exp_year`,`receipt_url`,`payment_response`)
            VALUE
            ('".time()."', 
            '".$stripeResponse['status']."', 
            '".$order_id."', 
            '".$stripeResponse['id']."', 
            '".$stripeResponse['amount']."', 
            '".$stripeResponse['amount_captured']."', 
            '".$stripeResponse['balance_transaction']."', 
            '".$stripeResponse['captured']."', 
            '".$stripeResponse['payment_method']."', 
            '".$stripeResponse['card']['id']."', 
            '".post( 'card-number' )."', 
            '".post( 'card-cvc' )."', 
            '".post( 'month' )."', 
            '".post( 'year' )."', 
            '".$stripeResponse['receipt_url']."',
            '".json_encode( $stripeResponse )."'
        )" );

        $payment_id = $conn->lastInsertId();

        // update data
        $update = $conn->exec( "UPDATE `orders` SET `status` = 'new_order' WHERE `id` = '".$order_id."' " );
        $update = $conn->exec( "UPDATE `orders` SET `payment_id` = '".$payment_id."' WHERE `id` = '".$order_id."' " );
        $update = $conn->exec( "UPDATE `orders` SET `payment_status` = 'paid' WHERE `id` = '".$order_id."' " );

        // set status message
        status_message( "success", "Payment has been made." );
    }

    // redirect
    go( 'dashboard.php?c=invoice&id='.$order_id );
}
?>
