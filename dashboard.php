<?php

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

// dev check
if( $account_details['email'] == 'jamie.whittingham@gmail.com' ) {
	$dev_check = true;
} else {
	$dev_check = false;
}

// admin check
if( $account_details['type'] == 'admin' ) {
	$admin_check = true;
} else {
	$admin_check = false;
}

// staff check
if( $account_details['type'] == 'staff' ) {
	$staff_check = true;
} else {
	$staff_check = false;
}

// engineer check
if( $account_details['type'] == 'engineer' ) {
	$engineer_check = true;
} else {
	$engineer_check = false;
}

// customer check
if( $account_details['type'] == 'customer' ) {
	$customer_check = true;
} else {
	$customer_check = false;
}

// build 404 panel
$not_found = '
<div class="error">
	<div class="error-code">404</div>
	<div class="error-content">
		<div class="error-message">We couldn\'t find it...</div>
		<div class="error-desc mb-3 mb-sm-4 mb-md-5">
			The page you\'re looking for doesn\'t exist. <br />
			Perhaps, there pages will help find what you\'re looking for.
		</div>
		<div>
			<a href="javascript:history.back()" class="btn btn-xs btn-lime p-l-20 p-r-20">Go back</a>
		</div>
	</div>
</div>
';

// stripe keys
define("STRIPE_SECRET_KEY", "sk_test_sa0QRUIVgFphzWQZ0gypyAv0");
define("STRIPE_PUBLISHABLE_KEY", "pk_test_iUFUXx45G0sVuoHoKC1BeiXi");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $globals['platform_name']; ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<!-- icons -->
	<link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png?v=1">
	<link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png?v=1">
	<link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png?v=1">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png?v=1">
	<link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png?v=1">
	<link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png?v=1">
	<link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png?v=1">
	<link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png?v=1">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png?v=1">
	<link rel="icon" type="image/png?v=1" sizes="192x192"  href="assets/favicon/android-icon-192x192.png?v=1">
	<link rel="icon" type="image/png?v=1" sizes="96x96" href="assets/favicon/favicon-96x96.png?v=1">
	<link rel="manifest" href="assets/favicon/manifest.json">
	<meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png?v=1">
	
	<!-- core css -->
	<link href="https://fonts.googleapiss.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/css/facebook/app.min.css" rel="stylesheet">
	<link href="assets/css/apple/theme/blue.min.css" rel="stylesheet">
	<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />

	<!-- datatables -->
	<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">

	<!-- website tutorial -->
	<link href="assets/intro/introjs.css" rel="stylesheet">

	<!-- select2 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<!-- apple switch -->
	<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet">

	<!-- highcharts -->
	<link href="assets/css/highcharts.css" rel="stylesheet">

	<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	
	<?php if( get( 'c' ) == 'invoice' ) { ?>
		<link href="assets/css/default/invoice-print.min.css" rel="stylesheet" />
	<?php } ?>

	<?php if( get( 'c' ) == 'staging' ) { ?>
		<link href="assets/plugins/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" />
	<?php } ?>

	<?php if( get( 'c' ) == 'user' ) { ?>
		<link href="assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet" />
	<?php } ?>

	<!-- custom css -->
	<style type="text/css">
		.hidden{ 
		    display: none;
		}

		select option:disabled {
		    color: red;
		    font-weight: bold;
		}

		@media print {
		  	.hidden-print {
		    	display: none !important;
		  	}
		}

		@import "compass/css3";

		.rating-box {
			color: $star-default-color;
			text-shadow: 0px 1px 10px rgba(0, 0, 0, 1);
			margin: 3rem auto;
			height: 3rem;
			width: 25rem;
		}

		.rating-star{
			font-size: 3rem;
			width: 3rem;
			height: 3rem;
			padding: 0 2rem;
			position: relative;
			display: block;
			float:left;
		}

		.full-star:before {
			color: $star-color;
			content: "\2605";
			position: absolute;
			left: 0;
			overflow: hidden;
		}

		.empty-star:before {
			content: "\2605";
			position: absolute;
			left: 0;
			overflow: hidden;
		}

		.half-star:before {
			color: $star-color;
			content: "\2605";
			width: 50%;
			position: absolute;
			left: 0;
			overflow: hidden;
		}

		.half-star:after {
			content: '\2605';
			position: absolute;
			left: 1.5rem;
			width: 50%;
			text-indent: -1.5rem;
			overflow: hidden;
		}

		#map {
			width: 100%;
			height: 440px;
		}

		ul.tagit {
		    padding: 1px 5px;
		    overflow: auto;
		    margin-left: inherit; /* usually we don't want the regular ul margins. */
		    margin-right: inherit;
		}
		ul.tagit li {
		    display: block;
		    float: left;
		    margin: 2px 5px 2px 0;
		}
		ul.tagit li.tagit-choice {    
		    position: relative;
		    line-height: inherit;
		}
		input.tagit-hidden-field {
		    display: none;
		}
		ul.tagit li.tagit-choice-read-only { 
		    padding: .2em .5em .2em .5em; 
		} 

		ul.tagit li.tagit-choice-editable { 
		    padding: .2em 18px .2em .5em; 
		} 

		ul.tagit li.tagit-new {
		    padding: .25em 4px .25em 0;
		}

		ul.tagit li.tagit-choice a.tagit-label {
		    cursor: pointer;
		    text-decoration: none;
		}
		ul.tagit li.tagit-choice .tagit-close {
		    cursor: pointer;
		    position: absolute;
		    right: .1em;
		    top: 50%;
		    margin-top: -8px;
		    line-height: 17px;
		}

		/* used for some custom themes that don't need image icons */
		ul.tagit li.tagit-choice .tagit-close .text-icon {
		    display: none;
		}

		ul.tagit li.tagit-choice input {
		    display: block;
		    float: left;
		    margin: 2px 5px 2px 0;
		}
		ul.tagit input[type="text"] {
		    -moz-box-sizing:    border-box;
		    -webkit-box-sizing: border-box;
		    box-sizing:         border-box;

		    -moz-box-shadow: none;
		    -webkit-box-shadow: none;
		    box-shadow: none;

		    border: none;
		    margin: 0;
		    padding: 0;
		    width: inherit;
		    background-color: inherit;
		    outline: none;
		}

		@-webkit-keyframes invalid {
		 	from { background-color: #fad0cd; }
		  	to { background-color: inherit; }
		}
		@-moz-keyframes invalid {
			from { background-color: #fad0cd; }
			to { background-color: inherit; }
		}
		@-o-keyframes invalid {
			from { background-color: #fad0cd; }
			to { background-color: inherit; }
		}
		@keyframes invalid {
			from { background-color: #fad0cd; }
			to { background-color: inherit; }
		}

		.order_overdue_css {
			-webkit-animation: invalid 2s infinite; /* Safari 4+ */
			-moz-animation:    invalid 2s infinite; /* Fx 5+ */
			-o-animation:      invalid 2s infinite; /* Opera 12+ */
			animation:         invalid 2s infinite; /* IE 10+ */
		}

		.order_fallback_css {
			background-color: #f7fdaf;
		}

		.order_out_for_delivery_css {
			
		}

		.order_complete_css {
			
		}

		.banner {
			background: #a770ef;
			background: -webkit-linear-gradient(to right, #a770ef, #cf8bf3, #fdb99b);
			background: linear-gradient(to right, #a770ef, #cf8bf3, #fdb99b);
		}
	</style>
</head>

<body class="">  
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed page-content-full-height">
		<div id="header" class="header navbar-inverse hidden-print">
			<div class="navbar-header">
				<a href="dashboard.php" class="navbar-brand"><!-- <img src="assets/img/logo/logo.svg?v=1" height="100%" alt="Logo"> -->&nbsp;&nbsp;<b><?php echo $globals['platform_name']; ?></b></a>

				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<ul class="navbar-nav navbar-right">
				<!--
					<li class="navbar-form">
						<form action="" method="POST" name="search">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Enter keyword">
								<button type="submit" onclick="processing();" class="btn btn-search"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</li>
				-->
				<!--
					<li class="dropdown">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14">
							<i class="fa fa-bell"></i>
							<span class="label">5</span>
						</a>
						<div class="dropdown-menu media-list dropdown-menu-right">
							<div class="dropdown-header">NOTIFICATIONS (5)</div>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-bug media-object bg-silver-darker"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
									<div class="text-muted f-s-10">3 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<img src="assets/img/user/user-1.jpg" class="media-object" alt="">
									<i class="fab fa-default-messenger text-blue media-object-icon"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">John Smith</h6>
									<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
									<div class="text-muted f-s-10">25 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<img src="assets/img/user/user-2.jpg" class="media-object" alt="">
									<i class="fab fa-default-messenger text-blue media-object-icon"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Olivia</h6>
									<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
									<div class="text-muted f-s-10">35 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-plus media-object bg-silver-darker"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New User Registered</h6>
									<div class="text-muted f-s-10">1 hour ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-envelope media-object bg-silver-darker"></i>
									<i class="fab fa-default text-warning media-object-icon f-s-14"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New Email From John</h6>
									<div class="text-muted f-s-10">2 hour ago</div>
								</div>
							</a>
							<div class="dropdown-footer text-center">
								<a href="javascript:;">View more</a>
							</div>
						</div>
					</li>
				-->
				<li class="dropdown navbar-user hidden-print">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="<?php echo get_gravatar( $account_details['email'] ); ?>" alt="avatar">
						<span class="d-none d-md-inline" style="color: white;"><?php echo $account_details['full_name']; ?></span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="?c=user" class="dropdown-item">Account Settings</a>
						<div class="dropdown-divider"></div>
						<a href="logout.php" class="dropdown-item">Sign Out</a>
					</div>
				</li>
			</ul>
		</div>
		
		<div id="sidebar" class="sidebar">
			<div data-scrollbar="true" data-height="100%">
				<!--
					<ul class="nav">
						<li class="nav-profile">
							<a href="javascript:;" data-toggle="nav-profile">
								<div class="cover with-shadow"></div>
								<div class="image">
									<img src="assets/img/user/user-13.jpg" alt="">
								</div>
								<div class="info">
									<b class="caret pull-right"></b>Sean Ngu
									<small>Front end developer</small>
								</div>
							</a>
						</li>
						<li>
							<ul class="nav nav-profile">
								<li><a href="javascript:;"><i class="fa fa-cog"></i> Settings</a></li>
								<li><a href="javascript:;"><i class="fa fa-pencil-alt"></i> Send Feedback</a></li>
								<li><a href="javascript:;"><i class="fa fa-question-circle"></i> Helps</a></li>
							</ul>
						</li>
					</ul>
				-->
				<?php if( $dev_check ) { ?>
					<ul class="nav"><li class="nav-header">Dev Section</li>
						<li <?php if( get( 'c' ) == 'dev' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=dev">
								<i class="fa fa-code bg-gradient-purple"></i>
								<span>Global Vars</span> 
							</a>
						</li>
						<li <?php if( get( 'c' ) == 'staging' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=staging">
								<i class="fa fa-rocket bg-gradient-pink"></i>
								<span>Staging</span> 
							</a>
						</li>
					</ul>
				<?php } ?>

				<ul class="nav"><li class="nav-header">Navigation</li>
					<li <?php if( get( 'c' ) == '' || get( 'c' ) == 'home' ) { echo'class="active"'; } ?>>
						<a href="dashboard.php">
							<i class="fa fa-home bg-blue"></i>
							<span>Home</span> 
						</a>
					</li>
					<?php if( $admin_check || $staff_check ) { ?>
						<li <?php if( get( 'c' ) == 'customer' || get( 'c' ) == 'customers' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=customers">
								<i class="fa fa-users bg-orange"></i>
								<span>Customers</span> 
							</a>
						</li>
					<?php } ?>
					<li <?php if( get( 'c' ) == 'job' || get( 'c' ) == 'jobs' ) { echo'class="active"'; } ?>>
						<a href="dashboard.php?c=jobs">
							<i class="fa fa-car bg-green"></i>
							<span>Jobs</span> 
						</a>
					</li>
					<?php if( $admin_check || $staff_check ) { ?>
						<li <?php if( get( 'c' ) == 'provider' || get( 'c' ) == 'providers' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=providers">
								<i class="fa fa-address-card bg-purple"></i>
								<span>Providers</span> 
							</a>
						</li>
					<?php } ?>
					<li>
						<a href="logout.php">
							<i class="fa fa-sign-out-alt bg-red"></i>
							<span>Sign Out</span> 
						</a>
					</li>
				</ul>

				<?php if( $admin_check || $staff_check ) { ?>
					<ul class="nav"><li class="nav-header">Admin Section</li>
						<li class="has-sub 
							<?php if( get( 'c' ) == 'tools' ) { echo'active'; } ?>
							<?php if( get( 'c' ) == 'vrn_lookup' ) { echo'active'; } ?>
						">
							<a href="javascript:;">
								<b class="caret"></b>
								<i class="fa fa-first-order"></i>
								<span>Tools</span>
							</a>
							<ul class="sub-menu">
								<li <?php if( get( 'c' ) == 'vrn_lookup' ) { echo'class="active"'; } ?>><a href="dashboard.php?c=vrn_lookup">VRN Lookup</a></li>
							</ul>
						</li>
						<li <?php if( get( 'c' ) == 'user' || get( 'c' ) == 'users' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=users">
								<i class="fa fa-user"></i>
								<span>Users</span> 
							</a>
						</li>
						<li <?php if( get( 'c' ) == 'system_settings' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=system_settings">
								<i class="fa fa-cogs"></i>
								<span>System Settings</span> 
							</a>
						</li>
					</ul>
				<?php } ?>
			</div>
		</div>

		<?php
			$c = get( 'c' );
			switch( $c ) {
				// dev section
				case "dev":
					if( $dev_check ) {
						dev();
					} else {
						access_denied();
					}
					break;

				case "staging":
					if( $dev_check ) {
						staging();
					} else {
						access_denied();
					}
					break;

				// production section
				case "access_denied":
					access_denied();
					break;

				case "customer":
					if( $admin_check || $staff_check ) {
						customer();
					} else {
						access_denied();
					}
					break;

				case "customers":
					if( $admin_check || $staff_check ) {
						customers();
					} else {
						access_denied();
					}
					break;

				case "system_settings":
					if( $admin_check ) {
						system_settings();
					} else {
						access_denied();
					}
					break;

				case "user":
					user();
					break;

				case "users":
					if( $admin_check ) {
						users();
					} else {
						access_denied();
					}
					break;

				default:
					home();
					break;
			}
		?>

		<!-- dev section -->
		<?php function dev() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Dev</li>
				</ol>
				
				<h1 class="page-header">Dev</h1>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">Account Details</h2>
						<div class="panel-heading-btn">

						</div>
					</div>
					<div class="panel-body">
						<?php debug( $account_details ); ?>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">Encrypt Email</h2>
						<div class="panel-heading-btn">

						</div>
					</div>
					<div class="panel-body">
						<?php echo obfuscate_email( 'jamie.whittingham@gmail.com' ); ?>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">PHP Session Details</h2>
						<div class="panel-heading-btn">

						</div>
					</div>
					<div class="panel-body">
						<?php debug( $_SESSION ); ?>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">Globals</h2>
						<div class="panel-heading-btn">

						</div>
					</div>
					<div class="panel-body">
						<?php debug( $globals ); ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function staging() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Staging</li>
				</ol>
				
				<h1 class="page-header">Staging</h1>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">Playground</h2>
						<div class="panel-heading-btn">

						</div>
					</div>
					<div class="panel-body">
						<div class="container-fluid">
						    <div class="px-lg-5">
						        <div class="row">
						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294929/matthew-hamilton-351641-unsplash_zmvozs.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Red paint cup</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPG</span></p>
						                            <div class="badge badge-danger px-3 rounded-pill font-weight-normal">New</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294927/cody-davis-253928-unsplash_vfcdcl.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Blorange</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">PNG</span></p>
						                            <div class="badge badge-primary px-3 rounded-pill font-weight-normal">Trend</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294928/nicole-honeywill-546848-unsplash_ymprvp.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">And She Realized</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPG</span></p>
						                            <div class="badge badge-warning px-3 rounded-pill font-weight-normal text-white">Featured</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294927/dose-juice-1184444-unsplash_bmbutn.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">DOSE Juice</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPEG</span></p>
						                            <div class="badge badge-success px-3 rounded-pill font-weight-normal">Hot</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294926/cody-davis-253925-unsplash_hsetv7.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Pineapple</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">PNG</span></p>
						                            <div class="badge badge-primary px-3 rounded-pill font-weight-normal">New</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294928/tim-foster-734470-unsplash_xqde00.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Yellow banana</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPG</span></p>
						                            <div class="badge badge-warning px-3 rounded-pill font-weight-normal text-white">Featured</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294927/mike-meyers-737494-unsplash_yd11yq.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Teal Gameboy</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPEG</span></p>
						                            <div class="badge badge-info px-3 rounded-pill font-weight-normal">Hot</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294930/ronald-cuyan-434484-unsplash_iktjid.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Color in Guatemala.</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">PNG</span></p>
						                            <div class="badge badge-warning px-3 rounded-pill font-weight-normal text-white">Featured</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294929/matthew-hamilton-351641-unsplash_zmvozs.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Red paint cup</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPG</span></p>
						                            <div class="badge badge-danger px-3 rounded-pill font-weight-normal">New</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294927/cody-davis-253928-unsplash_vfcdcl.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Lorem ipsum dolor</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">PNG</span></p>
						                            <div class="badge badge-primary px-3 rounded-pill font-weight-normal">Trend</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294928/nicole-honeywill-546848-unsplash_ymprvp.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Lorem ipsum dolor</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPG</span></p>
						                            <div class="badge badge-warning px-3 rounded-pill font-weight-normal text-white">Featured</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->

						            <!-- Gallery item -->
						            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
						                <div class="bg-white rounded shadow-sm">
						                    <img src="https://res.cloudinary.com/mhmd/image/upload/v1556294927/dose-juice-1184444-unsplash_bmbutn.jpg" alt="" class="img-fluid card-img-top" />
						                    <div class="p-4">
						                        <h5><a href="#" class="text-dark">Lorem ipsum dolor</a></h5>
						                        <p class="small text-muted mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						                        <div class="d-flex align-items-center justify-content-between rounded-pill bg-light px-3 py-2 mt-4">
						                            <p class="small mb-0"><i class="fa fa-picture-o mr-2"></i><span class="font-weight-bold">JPEG</span></p>
						                            <div class="badge badge-success px-3 rounded-pill font-weight-normal">Hot</div>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <!-- End -->
						        </div>
						        <div class="py-5 text-right"><a href="#" class="btn btn-dark px-5 py-3 text-uppercase">Show me more</a></div>
						    </div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- production section -->
		<?php function access_denied() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<div id="content" class="content">
				<div class="panel panel-inverse">
					<div class="panel-heading" style="background-color: red;">
						<h2 class="panel-title"><font color="white">Access Denied</font></h2>
						<div class="panel-heading-btn">

						</div>
					</div>
					<div class="panel-body">
						<h3>
							<strong>
								<center>
									You do not have permission to access this page. Please contact an administrator.
								</center>
							</strong>
						</h3>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function home() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php $stats['total_users'] 		= total_users(); ?>
			<?php $stats['total_customers'] 	= total_customers(); ?>
			<?php $stats['total_jobs'] 			= total_jobs(); ?>
			<?php $stats['pending_jobs'] 		= total_jobs( 'pending' ); ?>
			<?php $stats['total_providers'] 	= total_providers(); ?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item active"><a href="dashboard.php">Dashboard</a></li>
				</ol>

				<h1 class="page-header">Dashboard</h1>

				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-12">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-xl-8 col-xs-12">
									</div>
									<div class="col-xl-4 col-xs-12 text-right">
										<div class="btn-group">
											<a href="#" class="btn btn-xs btn-primary" onclick="tutorial_page_home();">Tutorial</a>
										</div>
										<?php if( $dev_check ) { ?>
											<div class="btn-group">
												<a class="btn btn-xs btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev Output</a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- admin dashboard tiles -->
				<?php if( $admin_check || $staff_check ) { ?>
					<div class="row">
						<!--
							<div class="col-xl-3 col-xl-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-blue text-white"><i class="ion-ios-analytics"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">TODAY'S VISITS</div>
										<div class="stats-number">7,842,900</div>
										<div class="stats-progress progress">
											<div class="progress-bar" style="width: 70.1%;"></div>
										</div>
										<div class="stats-desc text-inverse-lighter">Better than last week (70.1%)</div>
									</div>
								</div>
							</div>
						-->
						<div class="col-xl-3 col-xs-6">
							<div class="widget widget-stats bg-white text-inverse">
								<div class="stats-icon stats-icon-square bg-gradient-green text-white"><i class="fa fa-car"></i></div>
								<div class="stats-content">
									<div class="stats-title text-inverse-lighter">
										Pending Jobs
										<span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Pending Jobs" data-placement="top" data-content="Jobs that have yet to be approved."></i></span>
									</div>
									<div class="stats-number"><?php echo $stats['pending_jobs']; ?></div>
									<div class="stats-progress progress">
										<div class="progress-bar" style="width: 0%;"></div>
									</div>
									<div class="stats-desc text-inverse-lighter"><a href="?c=jobs">Find out more ...</a></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-xs-6">
							<div class="widget widget-stats bg-white text-inverse">
								<div class="stats-icon stats-icon-square bg-gradient-green text-white"><i class="fa fa-car"></i></div>
								<div class="stats-content">
									<div class="stats-title text-inverse-lighter">
										Total Jobs
										<span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Total Jobs" data-placement="top" data-content="Includes all jobs from all customers."></i></span>
									</div>
									<div class="stats-number"><?php echo $stats['total_jobs']; ?></div>
									<div class="stats-progress progress">
										<div class="progress-bar" style="width: 0%;"></div>
									</div>
									<div class="stats-desc text-inverse-lighter"><a href="?c=jobs">Find out more ...</a></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-xs-6">
							<div class="widget widget-stats bg-white text-inverse">
								<div class="stats-icon stats-icon-square bg-gradient-orange text-white"><i class="fa fa-users"></i></div>
								<div class="stats-content">
									<div class="stats-title text-inverse-lighter">
										Total Customers
										<span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Total Customers" data-placement="top" data-content="Includes active and inactive customer accounts."></i></span>
									</div>
									<div class="stats-number"><?php echo $stats['total_customers']; ?></div>
									<div class="stats-progress progress">
										<div class="progress-bar" style="width: 0%;"></div>
									</div>
									<div class="stats-desc text-inverse-lighter"><a href="?c=customers">Find out more ...</a></div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-xs-6">
							<div class="widget widget-stats bg-white text-inverse">
								<div class="stats-icon stats-icon-square bg-gradient-purple text-white"><i class="fa fa-address-card"></i></div>
								<div class="stats-content">
									<div class="stats-title text-inverse-lighter">
										Total Providers
										<span class="ml-2"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Total Providers" data-placement="top" data-content="Includes active and inactive providers."></i></span>
									</div>
									<div class="stats-number"><?php echo $stats['total_providers']; ?></div>
									<div class="stats-progress progress">
										<div class="progress-bar" style="width: 0%;"></div>
									</div>
									<div class="stats-desc text-inverse-lighter"><a href="?c=providers">Find out more ...</a></div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">

								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>

		<?php function customers() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// get data
				$customers 	= get_customers();
			?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Customers</li>
				</ol>
				
				<h1 class="page-header">Customers</h1>

				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<?php if( $dev_check ) { ?>
					<div class="row">
						<div class="col-xl-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-xl-8 col-xs-12">
										</div>
										<div class="col-xl-4 col-xs-12 text-right">
											<div class="btn-group">
												<a class="btn btn-xs btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev Output</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- customers -->
				<?php if( !isset( $customers[0]['id'] ) ) { ?>
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Customers</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#customer_add">Add</button>
							</div>
						</div>
						<div class="panel-body">
							<center>
								<h3>
									No customers found.
								</h2>
							</center>
						</div>
					</div>
				<?php } else { ?>
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Customers</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#customer_add">Add</button>
							</div>
						</div>
						<div class="panel-body">
							<table id="table_customers" class="table table-striped table-bordered table-td-valign-middle">
								<thead>
									<tr>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Company</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Primary Contact</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Jobs</strong></th>
										<th class="text-nowrap" data-orderable="false" width=""></th>
										<th class="text-nowrap" data-orderable="false" width="1px"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// build table
										foreach( $customers as $customer ) {
											// status
											$customer['status_raw'] = $customer['status'];
											if( $customer['status'] == 'pending' ) {
												$customer['status'] = '<button class="btn btn-xs btn-info btn-block">Pending</button>';
											} elseif( $customer['status'] == 'active' ) {
												$customer['status'] = '<button class="btn btn-xs btn-success btn-block">Active</button>';
											} elseif( $customer['status'] == 'suspended' ) {
												$customer['status'] = '<button class="btn btn-xs btn-warning btn-block">Suspended</button>';
											} elseif( $customer['status'] == 'terminated' ) {
												$customer['status'] = '<button class="btn btn-xs btn-danger btn-block">Terminated</button>';
											}					

											// output
											echo '
												<tr>
													<td class="text-nowrap">
														<a href="?c=customer&id='.$customer['id'].'">'.$customer['id'].'</a>
													</td>
													<td class="text-nowrap">
														'.$customer['company_name'].'
													</td>
													<td class="text-nowrap">
														'.$customer['primary_contact']['full_name'].'
													</td>
													<td class="text-nowrap">
														'.$customer['total_jobs'].'
													</td>
													<td class="text-nowrap">
													</td>
													<td class="text-nowrap">
														<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
														<div class="dropdown-menu dropdown-menu-right" role="menu">
															<a href="?c=customer&id='.$customer['id'].'" class="dropdown-item">Edit</a>
															<a href="#" onclick="customer_delete( '.$customer['id'].' )" class="dropdown-item">Delete</a>
														</div>
													</td>
												</tr>
											';
										}
									?>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xl-12">
									<p><font color="red"><strong>*</strong></font> Orders that are flashing red have been pending for at least 3 hours. Please accept orders if you can fulfill them.</p>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<!-- add customer modal -->
			<form class="form" method="post" action="actions.php?a=customer_add">
				<div class="modal fade" id="customer_add" tabindex="-1" role="dialog" aria-labelledby="customer_add" aria-hidden="true">
				   	<div class="modal-dialog modal-notice">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Add Customer</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
						 		<div class="row">
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Company Name</strong></label>
											<input type="text" id="company_name" name="company_name" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>First Name</strong></label>
											<input type="text" id="first_name" name="first_name" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Last Name</strong></label>
											<input type="text" id="last_name" name="last_name" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Email</strong></label>
											<input type="email" id="email" name="email" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Password</strong></label>
											<input type="text" id="password" name="password" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Address 1</strong></label>
											<input type="text" id="address_1" name="address_1" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Address 2</strong></label>
											<input type="text" id="address_2" name="address_2" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>City</strong></label>
											<input type="text" id="address_city" name="address_city" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>State / County</strong></label>
											<input type="text" id="address_state" name="address_state" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Zip / Postcode</strong></label>
											<input type="text" id="address_zip" name="address_zip" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Country</strong></label>
											<select name="address_country" class="form-control select2">
												<option value="AF">Afghanistan</option>
												<option value="AX">Åland Islands</option>
												<option value="AL">Albania</option>
												<option value="DZ">Algeria</option>
												<option value="AS">American Samoa</option>
												<option value="AD">Andorra</option>
												<option value="AO">Angola</option>
												<option value="AI">Anguilla</option>
												<option value="AQ">Antarctica</option>
												<option value="AG">Antigua and Barbuda</option>
												<option value="AR">Argentina</option>
												<option value="AM">Armenia</option>
												<option value="AW">Aruba</option>
												<option value="AU">Australia</option>
												<option value="AT">Austria</option>
												<option value="AZ">Azerbaijan</option>
												<option value="BS">Bahamas</option>
												<option value="BH">Bahrain</option>
												<option value="BD">Bangladesh</option>
												<option value="BB">Barbados</option>
												<option value="BY">Belarus</option>
												<option value="BE">Belgium</option>
												<option value="BZ">Belize</option>
												<option value="BJ">Benin</option>
												<option value="BM">Bermuda</option>
												<option value="BT">Bhutan</option>
												<option value="BO">Bolivia, Plurinational State of</option>
												<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
												<option value="BA">Bosnia and Herzegovina</option>
												<option value="BW">Botswana</option>
												<option value="BV">Bouvet Island</option>
												<option value="BR">Brazil</option>
												<option value="IO">British Indian Ocean Territory</option>
												<option value="BN">Brunei Darussalam</option>
												<option value="BG">Bulgaria</option>
												<option value="BF">Burkina Faso</option>
												<option value="BI">Burundi</option>
												<option value="KH">Cambodia</option>
												<option value="CM">Cameroon</option>
												<option value="CA">Canada</option>
												<option value="CV">Cape Verde</option>
												<option value="KY">Cayman Islands</option>
												<option value="CF">Central African Republic</option>
												<option value="TD">Chad</option>
												<option value="CL">Chile</option>
												<option value="CN">China</option>
												<option value="CX">Christmas Island</option>
												<option value="CC">Cocos (Keeling) Islands</option>
												<option value="CO">Colombia</option>
												<option value="KM">Comoros</option>
												<option value="CG">Congo</option>
												<option value="CD">Congo, the Democratic Republic of the</option>
												<option value="CK">Cook Islands</option>
												<option value="CR">Costa Rica</option>
												<option value="CI">Côte d'Ivoire</option>
												<option value="HR">Croatia</option>
												<option value="CU">Cuba</option>
												<option value="CW">Curaçao</option>
												<option value="CY">Cyprus</option>
												<option value="CZ">Czech Republic</option>
												<option value="DK">Denmark</option>
												<option value="DJ">Djibouti</option>
												<option value="DM">Dominica</option>
												<option value="DO">Dominican Republic</option>
												<option value="EC">Ecuador</option>
												<option value="EG">Egypt</option>
												<option value="SV">El Salvador</option>
												<option value="GQ">Equatorial Guinea</option>
												<option value="ER">Eritrea</option>
												<option value="EE">Estonia</option>
												<option value="ET">Ethiopia</option>
												<option value="FK">Falkland Islands (Malvinas)</option>
												<option value="FO">Faroe Islands</option>
												<option value="FJ">Fiji</option>
												<option value="FI">Finland</option>
												<option value="FR">France</option>
												<option value="GF">French Guiana</option>
												<option value="PF">French Polynesia</option>
												<option value="TF">French Southern Territories</option>
												<option value="GA">Gabon</option>
												<option value="GM">Gambia</option>
												<option value="GE">Georgia</option>
												<option value="DE">Germany</option>
												<option value="GH">Ghana</option>
												<option value="GI">Gibraltar</option>
												<option value="GR">Greece</option>
												<option value="GL">Greenland</option>
												<option value="GD">Grenada</option>
												<option value="GP">Guadeloupe</option>
												<option value="GU">Guam</option>
												<option value="GT">Guatemala</option>
												<option value="GG">Guernsey</option>
												<option value="GN">Guinea</option>
												<option value="GW">Guinea-Bissau</option>
												<option value="GY">Guyana</option>
												<option value="HT">Haiti</option>
												<option value="HM">Heard Island and McDonald Islands</option>
												<option value="VA">Holy See (Vatican City State)</option>
												<option value="HN">Honduras</option>
												<option value="HK">Hong Kong</option>
												<option value="HU">Hungary</option>
												<option value="IS">Iceland</option>
												<option value="IN">India</option>
												<option value="ID">Indonesia</option>
												<option value="IR">Iran, Islamic Republic of</option>
												<option value="IQ">Iraq</option>
												<option value="IE">Ireland</option>
												<option value="IM">Isle of Man</option>
												<option value="IL">Israel</option>
												<option value="IT">Italy</option>
												<option value="JM">Jamaica</option>
												<option value="JP">Japan</option>
												<option value="JE">Jersey</option>
												<option value="JO">Jordan</option>
												<option value="KZ">Kazakhstan</option>
												<option value="KE">Kenya</option>
												<option value="KI">Kiribati</option>
												<option value="KP">Korea, Democratic People's Republic of</option>
												<option value="KR">Korea, Republic of</option>
												<option value="KW">Kuwait</option>
												<option value="KG">Kyrgyzstan</option>
												<option value="LA">Lao People's Democratic Republic</option>
												<option value="LV">Latvia</option>
												<option value="LB">Lebanon</option>
												<option value="LS">Lesotho</option>
												<option value="LR">Liberia</option>
												<option value="LY">Libya</option>
												<option value="LI">Liechtenstein</option>
												<option value="LT">Lithuania</option>
												<option value="LU">Luxembourg</option>
												<option value="MO">Macao</option>
												<option value="MK">Macedonia, the former Yugoslav Republic of</option>
												<option value="MG">Madagascar</option>
												<option value="MW">Malawi</option>
												<option value="MY">Malaysia</option>
												<option value="MV">Maldives</option>
												<option value="ML">Mali</option>
												<option value="MT">Malta</option>
												<option value="MH">Marshall Islands</option>
												<option value="MQ">Martinique</option>
												<option value="MR">Mauritania</option>
												<option value="MU">Mauritius</option>
												<option value="YT">Mayotte</option>
												<option value="MX">Mexico</option>
												<option value="FM">Micronesia, Federated States of</option>
												<option value="MD">Moldova, Republic of</option>
												<option value="MC">Monaco</option>
												<option value="MN">Mongolia</option>
												<option value="ME">Montenegro</option>
												<option value="MS">Montserrat</option>
												<option value="MA">Morocco</option>
												<option value="MZ">Mozambique</option>
												<option value="MM">Myanmar</option>
												<option value="NA">Namibia</option>
												<option value="NR">Nauru</option>
												<option value="NP">Nepal</option>
												<option value="NL">Netherlands</option>
												<option value="NC">New Caledonia</option>
												<option value="NZ">New Zealand</option>
												<option value="NI">Nicaragua</option>
												<option value="NE">Niger</option>
												<option value="NG">Nigeria</option>
												<option value="NU">Niue</option>
												<option value="NF">Norfolk Island</option>
												<option value="MP">Northern Mariana Islands</option>
												<option value="NO">Norway</option>
												<option value="OM">Oman</option>
												<option value="PK">Pakistan</option>
												<option value="PW">Palau</option>
												<option value="PS">Palestinian Territory, Occupied</option>
												<option value="PA">Panama</option>
												<option value="PG">Papua New Guinea</option>
												<option value="PY">Paraguay</option>
												<option value="PE">Peru</option>
												<option value="PH">Philippines</option>
												<option value="PN">Pitcairn</option>
												<option value="PL">Poland</option>
												<option value="PT">Portugal</option>
												<option value="PR">Puerto Rico</option>
												<option value="QA">Qatar</option>
												<option value="RE">Réunion</option>
												<option value="RO">Romania</option>
												<option value="RU">Russian Federation</option>
												<option value="RW">Rwanda</option>
												<option value="BL">Saint Barthélemy</option>
												<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
												<option value="KN">Saint Kitts and Nevis</option>
												<option value="LC">Saint Lucia</option>
												<option value="MF">Saint Martin (French part)</option>
												<option value="PM">Saint Pierre and Miquelon</option>
												<option value="VC">Saint Vincent and the Grenadines</option>
												<option value="WS">Samoa</option>
												<option value="SM">San Marino</option>
												<option value="ST">Sao Tome and Principe</option>
												<option value="SA">Saudi Arabia</option>
												<option value="SN">Senegal</option>
												<option value="RS">Serbia</option>
												<option value="SC">Seychelles</option>
												<option value="SL">Sierra Leone</option>
												<option value="SG">Singapore</option>
												<option value="SX">Sint Maarten (Dutch part)</option>
												<option value="SK">Slovakia</option>
												<option value="SI">Slovenia</option>
												<option value="SB">Solomon Islands</option>
												<option value="SO">Somalia</option>
												<option value="ZA">South Africa</option>
												<option value="GS">South Georgia and the South Sandwich Islands</option>
												<option value="SS">South Sudan</option>
												<option value="ES">Spain</option>
												<option value="LK">Sri Lanka</option>
												<option value="SD">Sudan</option>
												<option value="SR">Suriname</option>
												<option value="SJ">Svalbard and Jan Mayen</option>
												<option value="SZ">Swaziland</option>
												<option value="SE">Sweden</option>
												<option value="CH">Switzerland</option>
												<option value="SY">Syrian Arab Republic</option>
												<option value="TW">Taiwan, Province of China</option>
												<option value="TJ">Tajikistan</option>
												<option value="TZ">Tanzania, United Republic of</option>
												<option value="TH">Thailand</option>
												<option value="TL">Timor-Leste</option>
												<option value="TG">Togo</option>
												<option value="TK">Tokelau</option>
												<option value="TO">Tonga</option>
												<option value="TT">Trinidad and Tobago</option>
												<option value="TN">Tunisia</option>
												<option value="TR">Turkey</option>
												<option value="TM">Turkmenistan</option>
												<option value="TC">Turks and Caicos Islands</option>
												<option value="TV">Tuvalu</option>
												<option value="UG">Uganda</option>
												<option value="UA">Ukraine</option>
												<option value="AE">United Arab Emirates</option>
												<option value="GB" selected>United Kingdom</option>
												<option value="US">United States</option>
												<option value="UM">United States Minor Outlying Islands</option>
												<option value="UY">Uruguay</option>
												<option value="UZ">Uzbekistan</option>
												<option value="VU">Vanuatu</option>
												<option value="VE">Venezuela, Bolivarian Republic of</option>
												<option value="VN">Viet Nam</option>
												<option value="VG">Virgin Islands, British</option>
												<option value="VI">Virgin Islands, U.S.</option>
												<option value="WF">Wallis and Futuna</option>
												<option value="EH">Western Sahara</option>
												<option value="YE">Yemen</option>
												<option value="ZM">Zambia</option>
												<option value="ZW">Zimbabwe</option>
											</select>
										</div>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Continue</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			</form>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $customers ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>








		<?php function invoice() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// get data
				$order_id = get( 'id' ) ;
				$order = get_order( $order_id );
				$products = get_products();
			?>

			<div id="content" class="content">
				<!-- sanity check -->
				<?php if( !isset( $order['id'] ) ) { ?>
						<?php echo $not_found; ?>
				<?php } else { ?>
					<ol class="breadcrumb hidden-print float-xl-right">
						<li class="breadcrumb-item"><a href="?c=home">Home</a></li>
						<li class="breadcrumb-item"><a href="?c=orders">Orders</a></li>
						<li class="breadcrumb-item active">Invoice: <?php echo $order_id; ?></li>
					</ol>
					<h1 class="page-header hidden-print">Invoice: <?php echo $order_id; ?> <!-- <small>header small text goes here...</small> --></h1>
					<div class="invoice">
						<div class="invoice-company hidden-print">
							<span class="pull-right">
								<!-- <a href="javascript:;" class="btn btn-xs btn-white m-b-10"><i class="fa fa-file-pdf t-plus-1 text-danger fa-fw fa-lg"></i> Export as PDF</a> -->
								<a href="javascript:;" onclick="window.print()" class="btn btn-xs btn-white m-b-10"><i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</a>
							</span>
							FlowerNetwork Team
						</div>
						<div class="invoice-header">
							<div class="invoice-from">
								<small>from</small>
								<address class="m-t-5 m-b-5">
									<strong class="text-inverse">FlowerNetwork Team.</strong><br />
									Street Address<br />
									City, Zip Code<br />
									Phone: (123) 456-7890<br />
									Fax: (123) 456-7890
								</address>
							</div>
							<div class="invoice-to">
								<small>to</small>
								<address class="m-t-5 m-b-5">
									<strong class="text-inverse"><?php echo $order['customer']['full_name']; ?></strong><br />
									<?php echo $order['customer']['address_1']; ?><br />
									<?php echo $order['customer']['address_city']; ?>, <?php echo $order['customer']['address_state']; ?>, <?php echo $order['customer']['address_zip']; ?><br />
									Phone: <?php echo $order['customer']['tel_cell']; ?><br />
								</address>
							</div>
							<div class="invoice-date">
								<!-- <small>Invoice / July period</small> -->
								<div class="date text-inverse m-t-5"><?php echo date( 'Y-m-d', $order['added'] ); ?></div>
								<div class="invoice-detail">
									#<?php echo $order_id; ?>
								</div>
							</div>
						</div>
						<!-- end invoice-header -->
						<!-- begin invoice-content -->
						<div class="invoice-content">
							<div class="table-responsive">
								<table class="table table-invoice">
									<thead>
										<tr>
											<th>Item</th>
											<th class="text-center text-nowrap" width="1px">Price</th>
											<th class="text-center text-nowrap" width="1px">Qty</th>
											<th class="text-center text-nowrap" width="1px">Total</th>
										</tr>
									</thead>
									<tbody>
										<?php
											// build table
											foreach( $order['order_items'] as $order_item ) {
												// match item to product
												foreach( $products as $product ) {
													if( $product['id'] == $order_item['product_id'] ) {
														break;
													}
												}

												// calculate line total
												$order_item['price_total'] = $product['price'] * $order_item['qty'];

												// free delivery check
												if( $product['free_delivery'] == 'yes' ) {
													$globals['delivery_fee'] = '0.00';
												}

												// output
												echo '
													<tr>
														<td>
															<span class="text-inverse">'.$product['title'].'</span><br />
															<small>'.$product['preview_text'].'</small>
														</td>
														<td class="text-center">
															$'.number_format( $product['price'], 2 ).'
														</td>
														<td class="text-center">'
															.$order_item['qty'].'
														</td>
														<td class="text-right">
															$'.number_format( $order_item['price_total'], 2 ).'
														</td>
													</tr>
												';
											}
										?>
									</tbody>
								</table>
							</div>
							<div class="invoice-price">
								<div class="invoice-price-left">
									<div class="invoice-price-row">
										<div class="sub-price">
											<small>Subtotal</small>
											<span class="text-inverse">$<?php echo number_format( $order['total_price'], 2 ); ?></span>
										</div>
										<div class="sub-price">
											<i class="fa fa-plus text-muted"></i>
										</div>
										<div class="sub-price">
											<small>Delivery Fee</small>
											<span class="text-inverse">$<?php echo $globals['delivery_fee']; ?></span>
										</div>
									</div>
								</div>
								<div class="invoice-price-right">
									<small>TOTAL</small> <span class="f-w-600">$<?php echo number_format( $order['total_price'] + $globals['delivery_fee'], 2 ); ?></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-12 col-sm-12">
								<dl class="row">
									<dt class="text-inverse text-right col-6 text-truncate">Status</dt>
									<dd class="col-6"><?php echo strtoupper( $order['payment_details']['status'] ); ?></dd>

									<dt class="text-inverse text-right col-6 text-truncate">Transaction ID</dt>
									<dd class="col-6"><?php echo $order['payment_details']['stripe_id']; ?></dd>

									<dt class="text-inverse text-right col-6 text-truncate">Date</dt>
									<dd class="col-6"><?php echo date( 'Y-m-d h:i', $order['payment_details']['added'] ); ?></dd>

									<dt class="text-inverse text-right col-6 text-truncate">Amount</dt>
									<dd class="col-6">$<?php echo number_format( $order['payment_details']['amount'], 2 ); ?></dd>

									<dt class="text-inverse text-right col-6 text-truncate">Card</dt>
									<dd class="col-6">X-<?php echo substr( $order['payment_details']['card_number'], -4 ); ?></dd>
								</dl>
							</div>
						</div>

						<div class="invoice-note">
							* If you have any questions concerning this order, please contact support@<?php echo $globals['url']; ?> 
						</div>
						<div class="invoice-footer">
							<p class="text-center m-b-5 f-w-600">
								THANK YOU FOR YOUR BUSINESS
							</p>
							<p class="text-center">
								<span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> <?php echo $globals['url']; ?></span>
								<span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:731-555-1234</span>
								<span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> support@support@<?php echo $globals['url']; ?></span>
							</p>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<?php function message() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// get data
				$message_id = get( 'id' ) ;
				$message = get_message( $message_id );
			?>

			<div id="content" class="content content-full-width">
				<!-- sanity check -->
				<?php if( !isset( $message['id'] ) ) { ?>
						<?php echo $not_found; ?>
				<?php } else { ?>
					<div class="vertical-box with-grid inbox bg-silver">
						<div class="vertical-box-column width-200">
							<div class="vertical-box">
								<div class="wrapper">
									<div class="d-flex align-items-center justify-content-center">
										<a href="#emailNav" data-toggle="collapse" class="btn btn-xs btn-inverse btn-xs mr-auto d-block d-lg-none">
											<i class="fa fa-cog"></i>
										</a>
										<a href="?c=message_new" class="btn btn-xs btn-inverse p-l-40 p-r-40 btn-xs">
											Compose
										</a>
									</div>
								</div>
								<div class="vertical-box-row collapse d-lg-table-row" id="emailNav">
									<div class="vertical-box-cell">
										<div class="vertical-box-inner-cell">
											<div data-scrollbar="true" data-height="100%">
												<div class="wrapper p-0">
													<div class="nav-title"><b>Filters</b></div>
													<ul class="nav nav-inbox">
														<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers">Customers</a></li>
														<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists">Florists</a></li>
														<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk">Help Desk</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="vertical-box-column">
							<div class="vertical-box">
								<div class="wrapper clearfix">
									<div class="pull-left">
										<div class="btn-group mr-2">
											<a href="?c=messages" class="btn btn-white btn-xs"><i class="fa fa-fw fa-arrow-left"></i> <span class="d-none d-lg-inline">Back</span></a>
										</div>
										<div class="btn-group mr-2">
											<a href="?c=message_reply&id=<?php echo $message['id']; ?>" class="btn btn-white btn-xs"><i class="fa fa-fw fa-reply"></i> <span class="d-none d-lg-inline">Reply</span></a>
										</div>
										<div class="btn-group mr-2">
											<a href="#" onclick="message_delete( <?php echo $message['id']; ?> )" class="btn btn-white btn-xs"><i class="fa fa-fw fa-trash"></i> <span class="d-none d-lg-inline">Delete</span></a>
										</div>
									</div>
									<div class="pull-right">
										
									</div>
								</div>
								<div class="vertical-box-row bg-white">
									<div class="vertical-box-cell">
										<div class="vertical-box-inner-cell">
											<div data-scrollbar="true" data-height="100%">
												<?php if( !isset( $message['id'] ) ) { ?>
													<center><h3>Message not found.</h2></center>
												<?php } else { ?>
													<div class="wrapper">
														<h3 class="m-t-0 m-b-15 f-w-500"><?php echo $message['subject']; ?></h2>
														<ul class="media-list underline m-b-15 p-b-15">
															<li class="media media-sm clearfix">
																<a href="javascript:;" class="pull-left">
																	<img class="media-object rounded-corner" alt="" src="<?php echo get_gravatar( $message['sender']['email'] ); ?>" alt="avatar"/>
																</a>
																<div class="media-body">
																	<div class="email-from text-inverse f-s-14 m-b-3 f-w-600">
																		from <?php echo $message['sender']['full_name']; ?> <?php echo '<'.$message['sender']['email'].'>'; ?>
																	</div>
																	<div class="m-b-3"><i class="fa fa-clock fa-fw"></i> <?php echo date( 'Y-m-d h:i', $message['added'] ); ?></div>
																	<div class="email-to">
																		To: <?php echo $account_details['email']; ?>
																	</div>
																</div>
															</li>
														</ul>
														<ul class="attached-document clearfix">
															
														</ul>

														<p class="text-inverse"> 
															<?php echo $message['message']; ?>
														</p>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="wrapper text-right clearfix">
									
								</div>
							</div>
						</div>
					</div>
				<?php } ?>					
			</div>
		<?php } ?>

		<?php function message_new() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php $users = get_users(); ?>
			
			<div id="content" class="content content-full-width">
				<div class="vertical-box with-grid inbox bg-silver">
					<div class="vertical-box-column width-200">
						<div class="vertical-box">
							<div class="wrapper">
								<div class="d-flex align-items-center justify-content-center">
									<a href="#emailNav" data-toggle="collapse" class="btn btn-xs btn-inverse btn-xs mr-auto d-block d-lg-none">
										<i class="fa fa-cog"></i>
									</a>
									<a href="?c=message_new" class="btn btn-xs btn-inverse p-l-40 p-r-40 btn-xs">
										Compose
									</a>
								</div>
							</div>
							<div class="vertical-box-row collapse d-lg-table-row" id="emailNav">
								<div class="vertic-box-cell">
									<div class="vertical-box-inner-cell">
										<div data-scrollbar="true" data-height="100%">
											<div class="wrapper p-0">
												<div class="nav-title"><b>Filters</b></div>
												<ul class="nav nav-inbox">
													<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers"><i class="fa fa-inbox fa-fw m-r-5"></i> Customers </a></li>
													<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists"><i class="fa fa-flag fa-fw m-r-5"></i> Florists</a></li>
													<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk"><i class="fa fa-envelope fa-fw m-r-5"></i> Help Desk</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="vertical-box-column">
						<div class="vertical-box">
							<div class="wrapper">
								<span class="btn-group mr-2">
									<a href="#" onclick="document.getElementById( 'email_message' ).submit();" class="btn btn-xs btn-white "><i class="fa fa-fw fa-envelope"></i> <span class="hidden-xs">Send</span></a>
								</span>
								<span class="pull-right">
									<a href="?c=messages" class="btn btn-xs btn-white"><i class="fa fa-fw fa-times"></i> <span class="hidden-xs">Discard</span></a>
								</span>
							</div>
							<div class="vertical-box-row bg-white">
								<div class="vertical-box-cell">
									<div class="vertical-box-inner-cell">
										<form action="actions.php?a=message_send" method="POST" id="email_message" name="email_to_form">
											<div data-scrollbar="true" data-height="100%" class="p-15">
												<div class="email-to">
													<!--
														<span class="float-right-link">
															<a href="#" data-click="add-cc" data-name="Cc" class="m-r-5">Cc</a>
															<a href="#" data-click="add-cc" data-name="Bcc">Bcc</a>
														</span>
													-->
													<label class="control-label">To:</label>
													<select name="to_id" class="primary line-mode form-control form-control-sm select2" required>
														<option selected disabled>Select a recipient</option>
														<?php foreach( $users as $user ) { ?>
															<option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?> (<?php echo $user['email']; ?>)</option>
														<?php } ?>
													</select>
												</div>
			
												<div data-id="extra-cc"></div>
			
												<div class="email-subject">
													<input type="text" name="subject" class="form-control form-control-lg" placeholder="Subject" />
												</div>
												<div class="email-content p-t-15">
													<textarea name="message" class="textarea form-control" id="wysihtml5" placeholder="Enter text ..." rows="20"></textarea>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="wrapper text-right">
								<a href="?c=messages" class="btn btn-xs btn-white p-l-40 p-r-40 m-r-5">Discard</a>
								<a href="#" onclick="document.getElementById( 'email_message' ).submit();" class="btn btn-xs btn-primary p-l-40 p-r-40">Send</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function message_reply() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php $message_id = get( 'id' ); ?>
			<?php $message = get_message( $message_id ); ?>
			
			<div id="content" class="content content-full-width">
				<div class="vertical-box with-grid inbox bg-silver">
					<div class="vertical-box-column width-200">
						<div class="vertical-box">
							<div class="wrapper">
								<div class="d-flex align-items-center justify-content-center">
									<a href="#emailNav" data-toggle="collapse" class="btn btn-xs btn-inverse btn-xs mr-auto d-block d-lg-none">
										<i class="fa fa-cog"></i>
									</a>
									<a href="?c=message_new" class="btn btn-xs btn-inverse p-l-40 p-r-40 btn-xs">
										Compose
									</a>
								</div>
							</div>
							<div class="vertical-box-row collapse d-lg-table-row" id="emailNav">
								<div class="vertic-box-cell">
									<div class="vertical-box-inner-cell">
										<div data-scrollbar="true" data-height="100%">
											<div class="wrapper p-0">
												<div class="nav-title"><b>Filters</b></div>
												<ul class="nav nav-inbox">
													<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers"><i class="fa fa-inbox fa-fw m-r-5"></i> Customers </a></li>
													<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists"><i class="fa fa-flag fa-fw m-r-5"></i> Florists</a></li>
													<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk"><i class="fa fa-envelope fa-fw m-r-5"></i> Help Desk</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="vertical-box-column">
						<div class="vertical-box">
							<div class="wrapper">
								<span class="btn-group mr-2">
									<a href="#" onclick="document.getElementById( 'email_message' ).submit();" class="btn btn-white btn-xs"><i class="fa fa-fw fa-envelope"></i> <span class="hidden-xs">Send</span></a>
								</span>
								<span class="pull-right">
									<a href="?c=message&id=<?php echo $message['id']; ?>" class="btn btn-white btn-xs"><i class="fa fa-fw fa-times"></i> <span class="hidden-xs">Discard</span></a>
								</span>
							</div>
							<div class="vertical-box-row bg-white">
								<div class="vertical-box-cell">
									<div class="vertical-box-inner-cell">
										<form action="actions.php?a=message_send" method="POST" id="email_message" name="email_to_form">
											<div data-scrollbar="true" data-height="100%" class="p-15">
												<div class="email-to">
													<!--
														<span class="float-right-link">
															<a href="#" data-click="add-cc" data-name="Cc" class="m-r-5">Cc</a>
															<a href="#" data-click="add-cc" data-name="Bcc">Bcc</a>
														</span>
													-->
													<label class="control-label">To:</label>
													<input type="hidden" name="to_id" value="<?php echo $message['from_id']; ?>">
													<span class="primary line-mode form-control form-control-sm">
														<?php echo $message['sender']['full_name']; ?>
													</span>
												</div>
			
												<div data-id="extra-cc"></div>
			
												<div class="email-subject">
													<input type="text" name="subject" class="form-control form-control-lg" value="Re: <?php echo $message['subject']; ?>" readonly/>
												</div>
												<div class="email-content p-t-15">
													<textarea name="message" class="textarea form-control" id="wysihtml5" rows="20">
														
														<br><br>
														<hr>
														On <?php echo date( 'Y-m-d h:i', $message['added'] ); ?> <?php echo $message['sender']['full_name']; ?> <<?php echo $message['sender']['email']; ?>> wrote:
														<br><br>

														<?php echo $message['message']; ?>
													</textarea>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="wrapper text-right">
								<a href="?c=messages" class="btn btn-white p-l-40 p-r-40 m-r-5">Discard</a>
								<a href="#" onclick="document.getElementById( 'email_message' ).submit();" class="btn btn-xs btn-primary p-l-40 p-r-40">Send</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function messages() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>
			
			<?php
				// apply message filter
				if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) {
					$messages = get_messages( 'customer' );
				} elseif( get( 'filter' ) == 'florists' ) {
					$messages = get_messages( 'florist' );
				} elseif( get( 'filter' ) == 'helpdesk' ) {
					$messages = get_messages( 'helpdesk' );
				}
			?>

			<div class="row">
				<div class="col-xl-12">
					<div id="status_message"></div><div id="kyc_status_message"></div>
				</div>
			</div>

			<div id="content" class="content content-full-width">
				<div class="vertical-box with-grid inbox bg-silver">
					<div class="vertical-box-column width-200">
						<div class="vertical-box">
							<div class="wrapper">
								<div class="d-flex align-items-center justify-content-center">
									<a href="#emailNav" data-toggle="collapse" class="btn btn-xs btn-inverse btn-xs mr-auto d-block d-lg-none">
										<i class="fa fa-cog"></i>
									</a>
									<a href="?c=message_new" class="btn btn-xs btn-inverse p-l-40 p-r-40 btn-xs">
										Compose
									</a>
								</div>
							</div>
							<div class="vertical-box-row collapse d-lg-table-row" id="emailNav">
								<div class="vertical-box-cell">
									<div class="vertical-box-inner-cell">
										<div data-scrollbar="true" data-height="100%">
											<div class="wrapper p-0">
												<div class="nav-title"><b>Filters</b></div>
												<ul class="nav nav-inbox">
													<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers"><i class="fa fa-inbox fa-fw m-r-5"></i> Customers </a></li>
													<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists"><i class="fa fa-flag fa-fw m-r-5"></i> Florists</a></li>
													<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk"><i class="fa fa-envelope fa-fw m-r-5"></i> Help Desk</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="vertical-box-column">
						<div class="vertical-box">
							<div class="wrapper">
								<div class="btn-toolbar align-items-center">
									<div class="custom-control custom-checkbox mr-2">
										<!--
											<input type="checkbox" class="custom-control-input" data-checked="email-checkbox" id="emailSelectAll" data-change="email-select-all" />
											<label class="custom-control-label" for="emailSelectAll"></label>
										-->
									</div>
									<!--
										<div class="dropdown mr-2">
											<button class="btn btn-white btn-xs" data-toggle="dropdown">
												View All <span class="caret m-l-3"></span>
											</button>
											<div class="dropdown-menu">
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2"></i> All</a>
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-muted"></i> Unread</a>
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-blue"></i> Contacts</a>
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-success"></i> Groups</a>
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-warning"></i> Newsletters</a>
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-danger"></i> Social updates</a>
												<a href="javascript:;" class="dropdown-item"><i class="fa fa-circle f-s-9 fa-fw mr-2 text-indigo"></i> Everything else</a>
											</div>
										</div>
									-->
									<!-- <button class="btn btn-xs btn-white mr-2"><i class="fa fa-redo"></i></button> -->
									<div class="btn-group">

									</div>
									<!--
										<div class="btn-group ml-auto">
											<button class="btn btn-white btn-xs">
												<i class="fa fa-chevron-left"></i>
											</button>
											<button class="btn btn-white btn-xs">
												<i class="fa fa-chevron-right"></i>
											</button>
										</div>
									-->
								</div>
							</div>

							<div class="vertical-box-row">
								<div class="vertical-box-cell">
									<div class="vertical-box-inner-cell bg-white">
										<div data-scrollbar="true" data-height="100%">
											<ul class="list-group list-group-lg no-radius list-email">
												<?php 
													if( !isset( $messages[0] ) ) {
														echo '<center><h3>No messages.</h2></center>';
													} else {
														foreach( $messages as $message ) {
															// strip html tags from preview of $message['message']
															$message['message'] = preg_replace( '#<[^>]+>#', ' ', $message['message'] ); // " ABC  DEF "
															$message['message'] = preg_replace( '#\s+#', ' ', $message['message'] );     // " ABC DEF "

															// output
															echo '
																<li class="list-group-item '.$message['status'].'">
																	<!-- 
																		<div class="email-checkbox">
																			<div class="custom-control custom-checkbox">
																				<input type="checkbox" class="custom-control-input" data-checked="email-checkbox" id="emailCheckbox1">
																				<label class="custom-control-label" for="emailCheckbox1"></label>
																			</div>
																		</div>
																	-->
																	<a href="?c=message&id='.$message['id'].'" class="email-user bg-blue">
																		<span class="text-white">'.$message['sender']['initials'].'</span>
																	</a>
																	<div class="email-info">
																		<a href="?c=message&id='.$message['id'].'">
																			<span class="email-sender">'.$message['sender']['full_name'].' '.( $message['filter'] == 'florist' ? '( '.$message['sender']['company_name'].' )' : '' ).'</span>
																			<span class="email-title">'.$message['subject'].'</span>
																			<span class="email-desc">'.truncate( $message['message'], 50 ).'</span>
																			<span class="email-time">'.date( 'Y-m-d h:i', $message['added'] ) .'</span>
																		</a>
																	</div>
																</li>
															';
														}
													}
												?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="wrapper clearfix d-flex align-items-center">
								<div class="text-inverse f-w-600"><?php echo number_format( count( $messages ) ); ?> messages</div>
								<!--
									<div class="btn-group ml-auto">
										<button class="btn btn-white btn-xs">
											<i class="fa fa-fw fa-chevron-left"></i>
										</button>
										<button class="btn btn-white btn-xs">
											<i class="fa fa-fw fa-chevron-right"></i>
										</button>
									</div>
								-->
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function order() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php 
				// get data
				$order_id = get( 'id' );
				$order = get_order( $order_id );

				// get products
				$products = get_products_to_order();

				// set readonly car
				if( $admin_check || $staff_check || $order['status'] == 'pending' && $order['ordering_florist_id'] == $account_details['id'] ) {
					$readonly = '';
				} else {
					// $readonly = 'readonly';
					$readonly = 'disabled=""';
				}

				// get florists
				$florists = get_users( 'florist' );
			?>

			<div id="content" class="content">
				<!-- sanity check -->
				<?php if( !isset( $order['id'] ) ) { ?>
					<?php echo $not_found; ?>
				<?php } else { ?>
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php?c=orders">Orders</a></li>
						<li class="breadcrumb-item active">Order: <?php echo $order_id ?></li>
					</ol>

					<h1 class="page-header">Order: <?php echo $order_id ?></h1>

					<div class="row">
						<div class="col-xl-12">
							<div id="status_message"></div><div id="kyc_status_message"></div>
							<?php if( $order['status'] == 'complete' ) { ?>
								<div class="alert alert-success show m-b-0">This Order is marked as complete.</div> <br>
							<?php } ?>
						</div>
					</div>

					<div class="row">
						<div class="col-xl-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-xl-8 col-xs-12">
										</div>
										<div class="col-xl-4 col-xs-12 text-right">
											<div class="btn-group">
												<a href="?c=orders" type="button" class="btn btn-xs btn-white">Back</a>
												<!-- <button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button> -->
												<?php if( $order['status'] == 'pending' && $order['payment_status'] == 'unpaid' ) { ?>
													<a href="#" onclick="order_delete( <?php echo $order['id']; ?> )" class="btn btn-xs btn-danger">Delete</a>
													<!-- <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#payment_modal">Checkout</button> -->
												<?php } elseif( $order['status'] == 'new_order' && $order['payment_status'] == 'paid' && $order['accepted'] == 'no' ) { ?>
													<a href="#" onclick="order_accept( <?php echo $order['id']; ?> )" class="btn btn-xs btn-lime">Accept Order</a>
												<?php } elseif( $order['status'] == 'new_order' && $order['payment_status'] == 'paid' && $order['accepted'] == 'yes' ) { ?>
													
												<?php } ?>
											</div>
											<?php if( $dev_check ) { ?>
												<div class="btn-group">
													<a class="btn btn-xs btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev Output</a>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<!-- admin options -->
						<?php if( $admin_check || $staff_check ) { ?>
							<div class="col-xl-12 col-xl-12 col-sm-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Admin Section</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-2 col-sm-12">
												<form class="form" method="post" action="actions.php?a=order_edit_status">
													<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
													<div class="input-group m-b-2">
														<label class="bmd-label-floating"><strong>Status</strong></label>
													</div>
													<div class="input-group m-b-10">
														<select name="status" class="form-control">
															<option value="pending" <?php if( $order['status'] == 'pending' ) { echo 'selected'; } ?> >Step 1: Pending - Order is being created.</option>
															<option value="new_order" <?php if( $order['status'] == 'new_order' ) { echo 'selected'; } ?> >Step 2: New Order - Order is waiting to be accepted by a florist.</option>
															<option value="being_built" <?php if( $order['status'] == 'being_built' ) { echo 'selected'; } ?> >Step 3: Order has been accepted and being built.</option>
															<option value="out_for_delivery" <?php if( $order['status'] == 'out_for_delivery' ) { echo 'selected'; } ?> >Step 4: Out for Delivery - order is out for delivery.</option>
															<option value="complete" <?php if( $order['status'] == 'complete' ) { echo 'selected'; } ?> >Step 5: Complete - Order has been delivered.</option>
															<option value="delivery_failed" <?php if( $order['status'] == 'delivery_failed' ) { echo 'selected'; } ?> >Step 5: Delivery Failed.</option>
														</select>
														<div class="input-group-append">
															<button type="submit" onclick="processing();" class="btn btn-xs btn-primary no-caret">Save</button>
														</div>
													</div>
												</form>
											</div>
											<div class="col-xl-2 col-sm-12">
												<form class="form" method="post" action="actions.php?a=order_assign_florist">
													<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
													<div class="input-group m-b-2">
														<label class="bmd-label-floating"><strong>Assigned to Florist</strong></label>
													</div>
													<div class="input-group m-b-10">
														<select name="florist_id" class="form-control select2">
															<option value="" <?php if( $order['destination_florist_id'] == '' ) { echo 'selected'; } ?> >Not Assigned</option>
															<?php foreach( $florists as $florist ) { ?>
																<option value="<?php echo $florist['id']; ?>" <?php if( $order['destination_florist_id'] == $florist['id'] ) { echo 'selected'; } ?> ><?php echo $florist['company_name']; ?> (<?php echo $florist['email']; ?>)</option>
															<?php } ?>
														</select>
														<div class="input-group-append">
															<button type="submit" onclick="processing();" class="btn btn-xs btn-primary no-caret">Save</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

						<!-- accepted order process -->
						<?php if( $order['accepted'] == 'yes'  && $order['status'] != 'complete' ) { ?>
							<div class="col-xl-12 col-xl-12 col-sm-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Order Progress</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-12">
												<a href="#" class="btn btn-xl btn-white disabled">Order Placed</a>
												<a href="#" class="btn btn-xl btn-white disabled">Order Accepted</a>
												<?php if( $order['status'] == 'being_built' ) { ?>
													<a href="#" class="btn btn-xl btn-info disabled">Order Being Built</a>
													<a href="#" onclick="progress_order_to_stage( <?php echo $order_id; ?>, 'out_for_delivery' );" class="btn btn-xl btn-lime ">Out for Delivery</a>
												<?php } ?>
												<?php if( $order['status'] == 'out_for_delivery' ) { ?>
													<a href="#" class="btn btn-xl btn-white disabled">Order Being Built</a>
													<a href="#" class="btn btn-xl btn-info disabled">Out for Delivery</a>
													<a href="#" onclick="progress_order_to_stage( <?php echo $order_id; ?>, 'complete' );" class="btn btn-xl btn-lime">Order Complete</a>
													<a href="#" onclick="progress_order_to_stage( <?php echo $order_id; ?>, 'delivery_failed' );" class="btn btn-xl btn-danger">Delivery Failed</a>
												<?php } ?>
												<?php if( $order['status'] == 'delivery_failed' ) { ?>
													<a href="#" class="btn btn-xl btn-white disabled">Order Being Built</a>
													<a href="#" class="btn btn-xl btn-white disabled">Out for Delivery</a>
													<a href="#" class="btn btn-xl btn-danger disabled">Delivery Failed</a>
													<a href="#" onclick="progress_order_to_stage( <?php echo $order_id; ?>, 'out_for_delivery' );" class="btn btn-xl btn-lime ">Out for Delivery</a>
													<a href="#" onclick="progress_order_to_stage( <?php echo $order_id; ?>, 'complete' );" class="btn btn-xl btn-danger">Order Failed</a>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

						<div class="col-xl-6 col-sm-12">
							<!-- customer details -->
							<form class="form" method="post" action="actions.php?a=user_edit_from_order">
								<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
								<input type="hidden" name="user_id" value="<?php echo $order['customer_id']; ?>">

								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Customer Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												<?php if( empty( $readonly ) ) { ?>
													<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>First Name</strong></label>
													<input type="text" name="first_name" class="form-control" value="<?php echo $order['customer']['first_name']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Jo</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Last Name</strong></label>
													<input type="text" name="last_name" class="form-control" value="<?php echo $order['customer']['last_name']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Bloggs</small>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Email</strong></label>
													<input type="text" name="email" class="form-control" value="<?php echo $order['customer']['email']; ?>" required <?php echo $readonly; ?>>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Landline Tel</strong></label>
													<input type="text" name="tel_landline" class="form-control" value="<?php echo $order['customer']['tel_landline']; ?>" <?php echo $readonly; ?>>
													<small>Example: +44 1254 745560</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Cell Tel</strong></label>
													<input type="text" name="tel_cell" class="form-control" value="<?php echo $order['customer']['tel_cell']; ?>" <?php echo $readonly; ?>>
													<small>Example: +44 1254 745560</small>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Address 1</strong></label>
													<input type="text" name="address_1" class="form-control" value="<?php echo $order['customer']['address_1']; ?>" required <?php echo $readonly; ?>>
													<small>Example: 123 Awesome Street</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Address 2</strong></label>
													<input type="text" name="address_2" class="form-control" value="<?php echo $order['customer']['address_2']; ?>" <?php echo $readonly; ?>>
													<small>Example: PO BOX 1</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>City</strong></label>
													<input type="text" name="address_city" class="form-control" value="<?php echo $order['customer']['address_city']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Awesomeville</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>State / County</strong></label>
													<input type="text" name="address_state" class="form-control" value="<?php echo $order['customer']['address_state']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Florida</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Zip / Post Code</strong></label>
													<input type="text" name="address_zip" class="form-control" value="<?php echo $order['customer']['address_zip']; ?>" required <?php echo $readonly; ?>>
													<small>Example: 12345</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Country</strong></label>
													<select name="address_country" class="form-control select2" <?php echo $readonly; ?>>
														<option value="AF" <?php if( $order['customer']['address_country'] == 'AF' ) { echo 'selected'; } ?> >Afghanistan</option>
														<option value="AX" <?php if( $order['customer']['address_country'] == 'AX' ) { echo 'selected'; } ?> >Åland Islands</option>
														<option value="AL" <?php if( $order['customer']['address_country'] == 'AL' ) { echo 'selected'; } ?> >Albania</option>
														<option value="DZ" <?php if( $order['customer']['address_country'] == 'DZ' ) { echo 'selected'; } ?> >Algeria</option>
														<option value="AS" <?php if( $order['customer']['address_country'] == 'AS' ) { echo 'selected'; } ?> >American Samoa</option>
														<option value="AD" <?php if( $order['customer']['address_country'] == 'AD' ) { echo 'selected'; } ?> >Andorra</option>
														<option value="AO" <?php if( $order['customer']['address_country'] == 'AO' ) { echo 'selected'; } ?> >Angola</option>
														<option value="AI" <?php if( $order['customer']['address_country'] == 'AI' ) { echo 'selected'; } ?> >Anguilla</option>
														<option value="AQ" <?php if( $order['customer']['address_country'] == 'AQ' ) { echo 'selected'; } ?> >Antarctica</option>
														<option value="AG" <?php if( $order['customer']['address_country'] == 'AG' ) { echo 'selected'; } ?> >Antigua and Barbuda</option>
														<option value="AR" <?php if( $order['customer']['address_country'] == 'AR' ) { echo 'selected'; } ?> >Argentina</option>
														<option value="AM" <?php if( $order['customer']['address_country'] == 'AM' ) { echo 'selected'; } ?> >Armenia</option>
														<option value="AW" <?php if( $order['customer']['address_country'] == 'AW' ) { echo 'selected'; } ?> >Aruba</option>
														<option value="AU" <?php if( $order['customer']['address_country'] == 'AU' ) { echo 'selected'; } ?> >Australia</option>
														<option value="AT" <?php if( $order['customer']['address_country'] == 'AT' ) { echo 'selected'; } ?> >Austria</option>
														<option value="AZ" <?php if( $order['customer']['address_country'] == 'AZ' ) { echo 'selected'; } ?> >Azerbaijan</option>
														<option value="BS" <?php if( $order['customer']['address_country'] == 'BS' ) { echo 'selected'; } ?> >Bahamas</option>
														<option value="BH" <?php if( $order['customer']['address_country'] == 'BH' ) { echo 'selected'; } ?> >Bahrain</option>
														<option value="BD" <?php if( $order['customer']['address_country'] == 'BD' ) { echo 'selected'; } ?> >Bangladesh</option>
														<option value="BB" <?php if( $order['customer']['address_country'] == 'BB' ) { echo 'selected'; } ?> >Barbados</option>
														<option value="BY" <?php if( $order['customer']['address_country'] == 'BY' ) { echo 'selected'; } ?> >Belarus</option>
														<option value="BE" <?php if( $order['customer']['address_country'] == 'BE' ) { echo 'selected'; } ?> >Belgium</option>
														<option value="BZ" <?php if( $order['customer']['address_country'] == 'BZ' ) { echo 'selected'; } ?> >Belize</option>
														<option value="BJ" <?php if( $order['customer']['address_country'] == 'BJ' ) { echo 'selected'; } ?> >Benin</option>
														<option value="BM" <?php if( $order['customer']['address_country'] == 'BM' ) { echo 'selected'; } ?> >Bermuda</option>
														<option value="BT" <?php if( $order['customer']['address_country'] == 'BT' ) { echo 'selected'; } ?> >Bhutan</option>
														<option value="BO" <?php if( $order['customer']['address_country'] == 'BO' ) { echo 'selected'; } ?> >Bolivia, Plurinational State of</option>
														<option value="BQ" <?php if( $order['customer']['address_country'] == 'BQ' ) { echo 'selected'; } ?> >Bonaire, Sint Eustatius and Saba</option>
														<option value="BA" <?php if( $order['customer']['address_country'] == 'BA' ) { echo 'selected'; } ?> >Bosnia and Herzegovina</option>
														<option value="BW" <?php if( $order['customer']['address_country'] == 'BW' ) { echo 'selected'; } ?> >Botswana</option>
														<option value="BV" <?php if( $order['customer']['address_country'] == 'BV' ) { echo 'selected'; } ?> >Bouvet Island</option>
														<option value="BR" <?php if( $order['customer']['address_country'] == 'BR' ) { echo 'selected'; } ?> >Brazil</option>
														<option value="IO" <?php if( $order['customer']['address_country'] == 'IO' ) { echo 'selected'; } ?> >British Indian Ocean Territory</option>
														<option value="BN" <?php if( $order['customer']['address_country'] == 'BN' ) { echo 'selected'; } ?> >Brunei Darussalam</option>
														<option value="BG" <?php if( $order['customer']['address_country'] == 'BG' ) { echo 'selected'; } ?> >Bulgaria</option>
														<option value="BF" <?php if( $order['customer']['address_country'] == 'BF' ) { echo 'selected'; } ?> >Burkina Faso</option>
														<option value="BI" <?php if( $order['customer']['address_country'] == 'BI' ) { echo 'selected'; } ?> >Burundi</option>
														<option value="KH" <?php if( $order['customer']['address_country'] == 'KH' ) { echo 'selected'; } ?> >Cambodia</option>
														<option value="CM" <?php if( $order['customer']['address_country'] == 'CM' ) { echo 'selected'; } ?> >Cameroon</option>
														<option value="CA" <?php if( $order['customer']['address_country'] == 'CA' ) { echo 'selected'; } ?> >Canada</option>
														<option value="CV" <?php if( $order['customer']['address_country'] == 'CV' ) { echo 'selected'; } ?> >Cape Verde</option>
														<option value="KY" <?php if( $order['customer']['address_country'] == 'KY' ) { echo 'selected'; } ?> >Cayman Islands</option>
														<option value="CF" <?php if( $order['customer']['address_country'] == 'CF' ) { echo 'selected'; } ?> >Central African Republic</option>
														<option value="TD" <?php if( $order['customer']['address_country'] == 'TD' ) { echo 'selected'; } ?> >Chad</option>
														<option value="CL" <?php if( $order['customer']['address_country'] == 'CL' ) { echo 'selected'; } ?> >Chile</option>
														<option value="CN" <?php if( $order['customer']['address_country'] == 'CN' ) { echo 'selected'; } ?> >China</option>
														<option value="CX" <?php if( $order['customer']['address_country'] == 'CX' ) { echo 'selected'; } ?> >Christmas Island</option>
														<option value="CC" <?php if( $order['customer']['address_country'] == 'CC' ) { echo 'selected'; } ?> >Cocos (Keeling) Islands</option>
														<option value="CO" <?php if( $order['customer']['address_country'] == 'CO' ) { echo 'selected'; } ?> >Colombia</option>
														<option value="KM" <?php if( $order['customer']['address_country'] == 'KM' ) { echo 'selected'; } ?> >Comoros</option>
														<option value="CG" <?php if( $order['customer']['address_country'] == 'CG' ) { echo 'selected'; } ?> >Congo</option>
														<option value="CD" <?php if( $order['customer']['address_country'] == 'CD' ) { echo 'selected'; } ?> >Congo, the Democratic Republic of the</option>
														<option value="CK" <?php if( $order['customer']['address_country'] == 'CK' ) { echo 'selected'; } ?> >Cook Islands</option>
														<option value="CR" <?php if( $order['customer']['address_country'] == 'CR' ) { echo 'selected'; } ?> >Costa Rica</option>
														<option value="CI" <?php if( $order['customer']['address_country'] == 'CI' ) { echo 'selected'; } ?> >Côte d'Ivoire</option>
														<option value="HR" <?php if( $order['customer']['address_country'] == 'HR' ) { echo 'selected'; } ?> >Croatia</option>
														<option value="CU" <?php if( $order['customer']['address_country'] == 'CU' ) { echo 'selected'; } ?> >Cuba</option>
														<option value="CW" <?php if( $order['customer']['address_country'] == 'CW' ) { echo 'selected'; } ?> >Curaçao</option>
														<option value="CY" <?php if( $order['customer']['address_country'] == 'CY' ) { echo 'selected'; } ?> >Cyprus</option>
														<option value="CZ" <?php if( $order['customer']['address_country'] == 'CZ' ) { echo 'selected'; } ?> >Czech Republic</option>
														<option value="DK" <?php if( $order['customer']['address_country'] == 'DK' ) { echo 'selected'; } ?> >Denmark</option>
														<option value="DJ" <?php if( $order['customer']['address_country'] == 'DJ' ) { echo 'selected'; } ?> >Djibouti</option>
														<option value="DM" <?php if( $order['customer']['address_country'] == 'DM' ) { echo 'selected'; } ?> >Dominica</option>
														<option value="DO" <?php if( $order['customer']['address_country'] == 'DO' ) { echo 'selected'; } ?> >Dominican Republic</option>
														<option value="EC" <?php if( $order['customer']['address_country'] == 'EC' ) { echo 'selected'; } ?> >Ecuador</option>
														<option value="EG" <?php if( $order['customer']['address_country'] == 'EG' ) { echo 'selected'; } ?> >Egypt</option>
														<option value="SV" <?php if( $order['customer']['address_country'] == 'SV' ) { echo 'selected'; } ?> >El Salvador</option>
														<option value="GQ" <?php if( $order['customer']['address_country'] == 'GQ' ) { echo 'selected'; } ?> >Equatorial Guinea</option>
														<option value="ER" <?php if( $order['customer']['address_country'] == 'ER' ) { echo 'selected'; } ?> >Eritrea</option>
														<option value="EE" <?php if( $order['customer']['address_country'] == 'EE' ) { echo 'selected'; } ?> >Estonia</option>
														<option value="ET" <?php if( $order['customer']['address_country'] == 'ET' ) { echo 'selected'; } ?> >Ethiopia</option>
														<option value="FK" <?php if( $order['customer']['address_country'] == 'FK' ) { echo 'selected'; } ?> >Falkland Islands (Malvinas)</option>
														<option value="FO" <?php if( $order['customer']['address_country'] == 'FO' ) { echo 'selected'; } ?> >Faroe Islands</option>
														<option value="FJ" <?php if( $order['customer']['address_country'] == 'FJ' ) { echo 'selected'; } ?> >Fiji</option>
														<option value="FI" <?php if( $order['customer']['address_country'] == 'FI' ) { echo 'selected'; } ?> >Finland</option>
														<option value="FR" <?php if( $order['customer']['address_country'] == 'FR' ) { echo 'selected'; } ?> >France</option>
														<option value="GF" <?php if( $order['customer']['address_country'] == 'GF' ) { echo 'selected'; } ?> >French Guiana</option>
														<option value="PF" <?php if( $order['customer']['address_country'] == 'PF' ) { echo 'selected'; } ?> >French Polynesia</option>
														<option value="TF" <?php if( $order['customer']['address_country'] == 'TF' ) { echo 'selected'; } ?> >French Southern Territories</option>
														<option value="GA" <?php if( $order['customer']['address_country'] == 'GA' ) { echo 'selected'; } ?> >Gabon</option>
														<option value="GM" <?php if( $order['customer']['address_country'] == 'GM' ) { echo 'selected'; } ?> >Gambia</option>
														<option value="GE" <?php if( $order['customer']['address_country'] == 'GE' ) { echo 'selected'; } ?> >Georgia</option>
														<option value="DE" <?php if( $order['customer']['address_country'] == 'DE' ) { echo 'selected'; } ?> >Germany</option>
														<option value="GH" <?php if( $order['customer']['address_country'] == 'GH' ) { echo 'selected'; } ?> >Ghana</option>
														<option value="GI" <?php if( $order['customer']['address_country'] == 'GI' ) { echo 'selected'; } ?> >Gibraltar</option>
														<option value="GR" <?php if( $order['customer']['address_country'] == 'GR' ) { echo 'selected'; } ?> >Greece</option>
														<option value="GL" <?php if( $order['customer']['address_country'] == 'GL' ) { echo 'selected'; } ?> >Greenland</option>
														<option value="GD" <?php if( $order['customer']['address_country'] == 'GD' ) { echo 'selected'; } ?> >Grenada</option>
														<option value="GP" <?php if( $order['customer']['address_country'] == 'GP' ) { echo 'selected'; } ?> >Guadeloupe</option>
														<option value="GU" <?php if( $order['customer']['address_country'] == 'GU' ) { echo 'selected'; } ?> >Guam</option>
														<option value="GT" <?php if( $order['customer']['address_country'] == 'GT' ) { echo 'selected'; } ?> >Guatemala</option>
														<option value="GG" <?php if( $order['customer']['address_country'] == 'GG' ) { echo 'selected'; } ?> >Guernsey</option>
														<option value="GN" <?php if( $order['customer']['address_country'] == 'GN' ) { echo 'selected'; } ?> >Guinea</option>
														<option value="GW" <?php if( $order['customer']['address_country'] == 'GW' ) { echo 'selected'; } ?> >Guinea-Bissau</option>
														<option value="GY" <?php if( $order['customer']['address_country'] == 'GY' ) { echo 'selected'; } ?> >Guyana</option>
														<option value="HT" <?php if( $order['customer']['address_country'] == 'HT' ) { echo 'selected'; } ?> >Haiti</option>
														<option value="HM" <?php if( $order['customer']['address_country'] == 'HM' ) { echo 'selected'; } ?> >Heard Island and McDonald Islands</option>
														<option value="VA" <?php if( $order['customer']['address_country'] == 'VA' ) { echo 'selected'; } ?> >Holy See (Vatican City State)</option>
														<option value="HN" <?php if( $order['customer']['address_country'] == 'HN' ) { echo 'selected'; } ?> >Honduras</option>
														<option value="HK" <?php if( $order['customer']['address_country'] == 'HK' ) { echo 'selected'; } ?> >Hong Kong</option>
														<option value="HU" <?php if( $order['customer']['address_country'] == 'HU' ) { echo 'selected'; } ?> >Hungary</option>
														<option value="IS" <?php if( $order['customer']['address_country'] == 'IS' ) { echo 'selected'; } ?> >Iceland</option>
														<option value="IN" <?php if( $order['customer']['address_country'] == 'IN' ) { echo 'selected'; } ?> >India</option>
														<option value="ID" <?php if( $order['customer']['address_country'] == 'ID' ) { echo 'selected'; } ?> >Indonesia</option>
														<option value="IR" <?php if( $order['customer']['address_country'] == 'IR' ) { echo 'selected'; } ?> >Iran, Islamic Republic of</option>
														<option value="IQ" <?php if( $order['customer']['address_country'] == 'IQ' ) { echo 'selected'; } ?> >Iraq</option>
														<option value="IE" <?php if( $order['customer']['address_country'] == 'IE' ) { echo 'selected'; } ?> >Ireland</option>
														<option value="IM" <?php if( $order['customer']['address_country'] == 'IM' ) { echo 'selected'; } ?> >Isle of Man</option>
														<option value="IL" <?php if( $order['customer']['address_country'] == 'IL' ) { echo 'selected'; } ?> >Israel</option>
														<option value="IT" <?php if( $order['customer']['address_country'] == 'IT' ) { echo 'selected'; } ?> >Italy</option>
														<option value="JM" <?php if( $order['customer']['address_country'] == 'JM' ) { echo 'selected'; } ?> >Jamaica</option>
														<option value="JP" <?php if( $order['customer']['address_country'] == 'JP' ) { echo 'selected'; } ?> >Japan</option>
														<option value="JE" <?php if( $order['customer']['address_country'] == 'JE' ) { echo 'selected'; } ?> >Jersey</option>
														<option value="JO" <?php if( $order['customer']['address_country'] == 'JO' ) { echo 'selected'; } ?> >Jordan</option>
														<option value="KZ" <?php if( $order['customer']['address_country'] == 'KZ' ) { echo 'selected'; } ?> >Kazakhstan</option>
														<option value="KE" <?php if( $order['customer']['address_country'] == 'KE' ) { echo 'selected'; } ?> >Kenya</option>
														<option value="KI" <?php if( $order['customer']['address_country'] == 'KI' ) { echo 'selected'; } ?> >Kiribati</option>
														<option value="KP" <?php if( $order['customer']['address_country'] == 'KP' ) { echo 'selected'; } ?> >Korea, Democratic People's Republic of</option>
														<option value="KR" <?php if( $order['customer']['address_country'] == 'KR' ) { echo 'selected'; } ?> >Korea, Republic of</option>
														<option value="KW" <?php if( $order['customer']['address_country'] == 'KW' ) { echo 'selected'; } ?> >Kuwait</option>
														<option value="KG" <?php if( $order['customer']['address_country'] == 'KG' ) { echo 'selected'; } ?> >Kyrgyzstan</option>
														<option value="LA" <?php if( $order['customer']['address_country'] == 'LA' ) { echo 'selected'; } ?> >Lao People's Democratic Republic</option>
														<option value="LV" <?php if( $order['customer']['address_country'] == 'LV' ) { echo 'selected'; } ?> >Latvia</option>
														<option value="LB" <?php if( $order['customer']['address_country'] == 'LB' ) { echo 'selected'; } ?> >Lebanon</option>
														<option value="LS" <?php if( $order['customer']['address_country'] == 'LS' ) { echo 'selected'; } ?> >Lesotho</option>
														<option value="LR" <?php if( $order['customer']['address_country'] == 'LR' ) { echo 'selected'; } ?> >Liberia</option>
														<option value="LY" <?php if( $order['customer']['address_country'] == 'LY' ) { echo 'selected'; } ?> >Libya</option>
														<option value="LI" <?php if( $order['customer']['address_country'] == 'LI' ) { echo 'selected'; } ?> >Liechtenstein</option>
														<option value="LT" <?php if( $order['customer']['address_country'] == 'LT' ) { echo 'selected'; } ?> >Lithuania</option>
														<option value="LU" <?php if( $order['customer']['address_country'] == 'LU' ) { echo 'selected'; } ?> >Luxembourg</option>
														<option value="MO" <?php if( $order['customer']['address_country'] == 'MO' ) { echo 'selected'; } ?> >Macao</option>
														<option value="MK" <?php if( $order['customer']['address_country'] == 'MK' ) { echo 'selected'; } ?> >Macedonia, the former Yugoslav Republic of</option>
														<option value="MG" <?php if( $order['customer']['address_country'] == 'MG' ) { echo 'selected'; } ?> >Madagascar</option>
														<option value="MW" <?php if( $order['customer']['address_country'] == 'MW' ) { echo 'selected'; } ?> >Malawi</option>
														<option value="MY" <?php if( $order['customer']['address_country'] == 'MY' ) { echo 'selected'; } ?> >Malaysia</option>
														<option value="MV" <?php if( $order['customer']['address_country'] == 'MV' ) { echo 'selected'; } ?> >Maldives</option>
														<option value="ML" <?php if( $order['customer']['address_country'] == 'ML' ) { echo 'selected'; } ?> >Mali</option>
														<option value="MT" <?php if( $order['customer']['address_country'] == 'MT' ) { echo 'selected'; } ?> >Malta</option>
														<option value="MH" <?php if( $order['customer']['address_country'] == 'MH' ) { echo 'selected'; } ?> >Marshall Islands</option>
														<option value="MQ" <?php if( $order['customer']['address_country'] == 'MQ' ) { echo 'selected'; } ?> >Martinique</option>
														<option value="MR" <?php if( $order['customer']['address_country'] == 'MR' ) { echo 'selected'; } ?> >Mauritania</option>
														<option value="MU" <?php if( $order['customer']['address_country'] == 'MU' ) { echo 'selected'; } ?> >Mauritius</option>
														<option value="YT" <?php if( $order['customer']['address_country'] == 'YT' ) { echo 'selected'; } ?> >Mayotte</option>
														<option value="MX" <?php if( $order['customer']['address_country'] == 'MX' ) { echo 'selected'; } ?> >Mexico</option>
														<option value="FM" <?php if( $order['customer']['address_country'] == 'FM' ) { echo 'selected'; } ?> >Micronesia, Federated States of</option>
														<option value="MD" <?php if( $order['customer']['address_country'] == 'MD' ) { echo 'selected'; } ?> >Moldova, Republic of</option>
														<option value="MC" <?php if( $order['customer']['address_country'] == 'MC' ) { echo 'selected'; } ?> >Monaco</option>
														<option value="MN" <?php if( $order['customer']['address_country'] == 'MN' ) { echo 'selected'; } ?> >Mongolia</option>
														<option value="ME" <?php if( $order['customer']['address_country'] == 'ME' ) { echo 'selected'; } ?> >Montenegro</option>
														<option value="MS" <?php if( $order['customer']['address_country'] == 'MS' ) { echo 'selected'; } ?> >Montserrat</option>
														<option value="MA" <?php if( $order['customer']['address_country'] == 'MA' ) { echo 'selected'; } ?> >Morocco</option>
														<option value="MZ" <?php if( $order['customer']['address_country'] == 'MZ' ) { echo 'selected'; } ?> >Mozambique</option>
														<option value="MM" <?php if( $order['customer']['address_country'] == 'MM' ) { echo 'selected'; } ?> >Myanmar</option>
														<option value="NA" <?php if( $order['customer']['address_country'] == 'NA' ) { echo 'selected'; } ?> >Namibia</option>
														<option value="NR" <?php if( $order['customer']['address_country'] == 'NR' ) { echo 'selected'; } ?> >Nauru</option>
														<option value="NP" <?php if( $order['customer']['address_country'] == 'NP' ) { echo 'selected'; } ?> >Nepal</option>
														<option value="NL" <?php if( $order['customer']['address_country'] == 'NL' ) { echo 'selected'; } ?> >Netherlands</option>
														<option value="NC" <?php if( $order['customer']['address_country'] == 'NC' ) { echo 'selected'; } ?> >New Caledonia</option>
														<option value="NZ" <?php if( $order['customer']['address_country'] == 'NZ' ) { echo 'selected'; } ?> >New Zealand</option>
														<option value="NI" <?php if( $order['customer']['address_country'] == 'NI' ) { echo 'selected'; } ?> >Nicaragua</option>
														<option value="NE" <?php if( $order['customer']['address_country'] == 'NE' ) { echo 'selected'; } ?> >Niger</option>
														<option value="NG" <?php if( $order['customer']['address_country'] == 'NG' ) { echo 'selected'; } ?> >Nigeria</option>
														<option value="NU" <?php if( $order['customer']['address_country'] == 'NU' ) { echo 'selected'; } ?> >Niue</option>
														<option value="NF" <?php if( $order['customer']['address_country'] == 'NF' ) { echo 'selected'; } ?> >Norfolk Island</option>
														<option value="MP" <?php if( $order['customer']['address_country'] == 'MP' ) { echo 'selected'; } ?> >Northern Mariana Islands</option>
														<option value="NO" <?php if( $order['customer']['address_country'] == 'NO' ) { echo 'selected'; } ?> >Norway</option>
														<option value="OM" <?php if( $order['customer']['address_country'] == 'OM' ) { echo 'selected'; } ?> >Oman</option>
														<option value="PK" <?php if( $order['customer']['address_country'] == 'PK' ) { echo 'selected'; } ?> >Pakistan</option>
														<option value="PW" <?php if( $order['customer']['address_country'] == 'PW' ) { echo 'selected'; } ?> >Palau</option>
														<option value="PS" <?php if( $order['customer']['address_country'] == 'PS' ) { echo 'selected'; } ?> >Palestinian Territory, Occupied</option>
														<option value="PA" <?php if( $order['customer']['address_country'] == 'PA' ) { echo 'selected'; } ?> >Panama</option>
														<option value="PG" <?php if( $order['customer']['address_country'] == 'PG' ) { echo 'selected'; } ?> >Papua New Guinea</option>
														<option value="PY" <?php if( $order['customer']['address_country'] == 'PY' ) { echo 'selected'; } ?> >Paraguay</option>
														<option value="PE" <?php if( $order['customer']['address_country'] == 'PE' ) { echo 'selected'; } ?> >Peru</option>
														<option value="PH" <?php if( $order['customer']['address_country'] == 'PH' ) { echo 'selected'; } ?> >Philippines</option>
														<option value="PN" <?php if( $order['customer']['address_country'] == 'PN' ) { echo 'selected'; } ?> >Pitcairn</option>
														<option value="PL" <?php if( $order['customer']['address_country'] == 'PL' ) { echo 'selected'; } ?> >Poland</option>
														<option value="PT" <?php if( $order['customer']['address_country'] == 'PT' ) { echo 'selected'; } ?> >Portugal</option>
														<option value="PR" <?php if( $order['customer']['address_country'] == 'PR' ) { echo 'selected'; } ?> >Puerto Rico</option>
														<option value="QA" <?php if( $order['customer']['address_country'] == 'QA' ) { echo 'selected'; } ?> >Qatar</option>
														<option value="RE" <?php if( $order['customer']['address_country'] == 'RE' ) { echo 'selected'; } ?> >Réunion</option>
														<option value="RO" <?php if( $order['customer']['address_country'] == 'RO' ) { echo 'selected'; } ?> >Romania</option>
														<option value="RU" <?php if( $order['customer']['address_country'] == 'RU' ) { echo 'selected'; } ?> >Russian Federation</option>
														<option value="RW" <?php if( $order['customer']['address_country'] == 'RW' ) { echo 'selected'; } ?> >Rwanda</option>
														<option value="BL" <?php if( $order['customer']['address_country'] == 'BL' ) { echo 'selected'; } ?> >Saint Barthélemy</option>
														<option value="SH" <?php if( $order['customer']['address_country'] == 'SH' ) { echo 'selected'; } ?> >Saint Helena, Ascension and Tristan da Cunha</option>
														<option value="KN" <?php if( $order['customer']['address_country'] == 'KN' ) { echo 'selected'; } ?> >Saint Kitts and Nevis</option>
														<option value="LC" <?php if( $order['customer']['address_country'] == 'LC' ) { echo 'selected'; } ?> >Saint Lucia</option>
														<option value="MF" <?php if( $order['customer']['address_country'] == 'MF' ) { echo 'selected'; } ?> >Saint Martin (French part)</option>
														<option value="PM" <?php if( $order['customer']['address_country'] == 'PM' ) { echo 'selected'; } ?> >Saint Pierre and Miquelon</option>
														<option value="VC" <?php if( $order['customer']['address_country'] == 'VC' ) { echo 'selected'; } ?> >Saint Vincent and the Grenadines</option>
														<option value="WS" <?php if( $order['customer']['address_country'] == 'WS' ) { echo 'selected'; } ?> >Samoa</option>
														<option value="SM" <?php if( $order['customer']['address_country'] == 'SM' ) { echo 'selected'; } ?> >San Marino</option>
														<option value="ST" <?php if( $order['customer']['address_country'] == 'ST' ) { echo 'selected'; } ?> >Sao Tome and Principe</option>
														<option value="SA" <?php if( $order['customer']['address_country'] == 'SA' ) { echo 'selected'; } ?> >Saudi Arabia</option>
														<option value="SN" <?php if( $order['customer']['address_country'] == 'SN' ) { echo 'selected'; } ?> >Senegal</option>
														<option value="RS" <?php if( $order['customer']['address_country'] == 'RS' ) { echo 'selected'; } ?> >Serbia</option>
														<option value="SC" <?php if( $order['customer']['address_country'] == 'SC' ) { echo 'selected'; } ?> >Seychelles</option>
														<option value="SL" <?php if( $order['customer']['address_country'] == 'SL' ) { echo 'selected'; } ?> >Sierra Leone</option>
														<option value="SG" <?php if( $order['customer']['address_country'] == 'SG' ) { echo 'selected'; } ?> >Singapore</option>
														<option value="SX" <?php if( $order['customer']['address_country'] == 'SX' ) { echo 'selected'; } ?> >Sint Maarten (Dutch part)</option>
														<option value="SK" <?php if( $order['customer']['address_country'] == 'SK' ) { echo 'selected'; } ?> >Slovakia</option>
														<option value="SI" <?php if( $order['customer']['address_country'] == 'SI' ) { echo 'selected'; } ?> >Slovenia</option>
														<option value="SB" <?php if( $order['customer']['address_country'] == 'SB' ) { echo 'selected'; } ?> >Solomon Islands</option>
														<option value="SO" <?php if( $order['customer']['address_country'] == 'SO' ) { echo 'selected'; } ?> >Somalia</option>
														<option value="ZA" <?php if( $order['customer']['address_country'] == 'ZA' ) { echo 'selected'; } ?> >South Africa</option>
														<option value="GS" <?php if( $order['customer']['address_country'] == 'GS' ) { echo 'selected'; } ?> >South Georgia and the South Sandwich Islands</option>
														<option value="SS" <?php if( $order['customer']['address_country'] == 'SS' ) { echo 'selected'; } ?> >South Sudan</option>
														<option value="ES" <?php if( $order['customer']['address_country'] == 'ES' ) { echo 'selected'; } ?> >Spain</option>
														<option value="LK" <?php if( $order['customer']['address_country'] == 'LK' ) { echo 'selected'; } ?> >Sri Lanka</option>
														<option value="SD" <?php if( $order['customer']['address_country'] == 'SD' ) { echo 'selected'; } ?> >Sudan</option>
														<option value="SR" <?php if( $order['customer']['address_country'] == 'SR' ) { echo 'selected'; } ?> >Suriname</option>
														<option value="SJ" <?php if( $order['customer']['address_country'] == 'SJ' ) { echo 'selected'; } ?> >Svalbard and Jan Mayen</option>
														<option value="SZ" <?php if( $order['customer']['address_country'] == 'SZ' ) { echo 'selected'; } ?> >Swaziland</option>
														<option value="SE" <?php if( $order['customer']['address_country'] == 'SE' ) { echo 'selected'; } ?> >Sweden</option>
														<option value="CH" <?php if( $order['customer']['address_country'] == 'CH' ) { echo 'selected'; } ?> >Switzerland</option>
														<option value="SY" <?php if( $order['customer']['address_country'] == 'SY' ) { echo 'selected'; } ?> >Syrian Arab Republic</option>
														<option value="TW" <?php if( $order['customer']['address_country'] == 'TW' ) { echo 'selected'; } ?> >Taiwan, Province of China</option>
														<option value="TJ" <?php if( $order['customer']['address_country'] == 'TJ' ) { echo 'selected'; } ?> >Tajikistan</option>
														<option value="TZ" <?php if( $order['customer']['address_country'] == 'TZ' ) { echo 'selected'; } ?> >Tanzania, United Republic of</option>
														<option value="TH" <?php if( $order['customer']['address_country'] == 'TH' ) { echo 'selected'; } ?> >Thailand</option>
														<option value="TL" <?php if( $order['customer']['address_country'] == 'TL' ) { echo 'selected'; } ?> >Timor-Leste</option>
														<option value="TG" <?php if( $order['customer']['address_country'] == 'TG' ) { echo 'selected'; } ?> >Togo</option>
														<option value="TK" <?php if( $order['customer']['address_country'] == 'TK' ) { echo 'selected'; } ?> >Tokelau</option>
														<option value="TO" <?php if( $order['customer']['address_country'] == 'TO' ) { echo 'selected'; } ?> >Tonga</option>
														<option value="TT" <?php if( $order['customer']['address_country'] == 'TT' ) { echo 'selected'; } ?> >Trinidad and Tobago</option>
														<option value="TN" <?php if( $order['customer']['address_country'] == 'TN' ) { echo 'selected'; } ?> >Tunisia</option>
														<option value="TR" <?php if( $order['customer']['address_country'] == 'TR' ) { echo 'selected'; } ?> >Turkey</option>
														<option value="TM" <?php if( $order['customer']['address_country'] == 'TM' ) { echo 'selected'; } ?> >Turkmenistan</option>
														<option value="TC" <?php if( $order['customer']['address_country'] == 'TC' ) { echo 'selected'; } ?> >Turks and Caicos Islands</option>
														<option value="TV" <?php if( $order['customer']['address_country'] == 'TV' ) { echo 'selected'; } ?> >Tuvalu</option>
														<option value="UG" <?php if( $order['customer']['address_country'] == 'UG' ) { echo 'selected'; } ?> >Uganda</option>
														<option value="UA" <?php if( $order['customer']['address_country'] == 'UA' ) { echo 'selected'; } ?> >Ukraine</option>
														<option value="AE" <?php if( $order['customer']['address_country'] == 'AE' ) { echo 'selected'; } ?> >United Arab Emirates</option>
														<option value="GB" <?php if( $order['customer']['address_country'] == 'GB' ) { echo 'selected'; } ?> >United Kingdom</option>
														<option value="US" <?php if( $order['customer']['address_country'] == 'US' ) { echo 'selected'; } ?> >United States</option>
														<option value="UM" <?php if( $order['customer']['address_country'] == 'UM' ) { echo 'selected'; } ?> >United States Minor Outlying Islands</option>
														<option value="UY" <?php if( $order['customer']['address_country'] == 'UY' ) { echo 'selected'; } ?> >Uruguay</option>
														<option value="UZ" <?php if( $order['customer']['address_country'] == 'UZ' ) { echo 'selected'; } ?> >Uzbekistan</option>
														<option value="VU" <?php if( $order['customer']['address_country'] == 'VU' ) { echo 'selected'; } ?> >Vanuatu</option>
														<option value="VE" <?php if( $order['customer']['address_country'] == 'VE' ) { echo 'selected'; } ?> >Venezuela, Bolivarian Republic of</option>
														<option value="VN" <?php if( $order['customer']['address_country'] == 'VN' ) { echo 'selected'; } ?> >Viet Nam</option>
														<option value="VG" <?php if( $order['customer']['address_country'] == 'VG' ) { echo 'selected'; } ?> >Virgin Islands, British</option>
														<option value="VI" <?php if( $order['customer']['address_country'] == 'VI' ) { echo 'selected'; } ?> >Virgin Islands, U.S.</option>
														<option value="WF" <?php if( $order['customer']['address_country'] == 'WF' ) { echo 'selected'; } ?> >Wallis and Futuna</option>
														<option value="EH" <?php if( $order['customer']['address_country'] == 'EH' ) { echo 'selected'; } ?> >Western Sahara</option>
														<option value="YE" <?php if( $order['customer']['address_country'] == 'YE' ) { echo 'selected'; } ?> >Yemen</option>
														<option value="ZM" <?php if( $order['customer']['address_country'] == 'ZM' ) { echo 'selected'; } ?> >Zambia</option>
														<option value="ZW" <?php if( $order['customer']['address_country'] == 'ZW' ) { echo 'selected'; } ?> >Zimbabwe</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>

							<!-- recipient details -->
							<form class="form" method="post" action="actions.php?a=order_edit_delivery_details">
								<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
								<input type="hidden" name="delivery_id" value="<?php echo $order['delivery_id']; ?>">
								
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Recipient Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												<?php if( empty( $readonly ) ) { ?>
													<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Delivery Date</strong></label>
													<div class="input-group date" id="datepicker-disabled-past" data-date-format="yyyy-mm-dd" data-date-start-date="Date.default">
														<input type="text" name="delivery_date" class="form-control" value="<?php echo $order['delivery_date']; ?>" placeholder="Select a date" <?php echo $readonly; ?>>
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>First Name</strong></label>
													<input type="text" name="first_name" class="form-control" value="<?php echo $order['delivery_details']['first_name']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Jo</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Last Name</strong></label>
													<input type="text" name="last_name" class="form-control" value="<?php echo $order['delivery_details']['last_name']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Bloggs</small>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Email</strong></label>
													<input type="text" name="email" class="form-control" value="<?php echo $order['delivery_details']['email']; ?>" required <?php echo $readonly; ?>>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Landline Tel</strong></label>
													<input type="text" name="tel_landline" class="form-control" value="<?php echo $order['delivery_details']['tel_landline']; ?>" <?php echo $readonly; ?>>
													<small>Example: +44 1254 745560</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Cell Tel</strong></label>
													<input type="text" name="tel_cell" class="form-control" value="<?php echo $order['delivery_details']['tel_cell']; ?>" <?php echo $readonly; ?>>
													<small>Example: +44 1254 745560</small>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Address 1</strong></label>
													<input type="text" name="address_1" class="form-control" value="<?php echo $order['delivery_details']['address_1']; ?>" required <?php echo $readonly; ?>>
													<small>Example: 123 Awesome Street</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Address 2</strong></label>
													<input type="text" name="address_2" class="form-control" value="<?php echo $order['delivery_details']['address_2']; ?>" <?php echo $readonly; ?>>
													<small>Example: PO BOX 1</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>City</strong></label>
													<input type="text" name="address_city" class="form-control" value="<?php echo $order['delivery_details']['address_city']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Awesomeville</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>State / County</strong></label>
													<input type="text" name="address_state" class="form-control" value="<?php echo $order['delivery_details']['address_state']; ?>" required <?php echo $readonly; ?>>
													<small>Example: Florida</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Zip / Post Code</strong></label>
													<input type="text" name="address_zip" class="form-control" value="<?php echo $order['delivery_details']['address_zip']; ?>" required <?php echo $readonly; ?>>
													<small>Example: 12345</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Country</strong></label>
													<select name="address_country" class="form-control select2" <?php echo $readonly; ?>>
														<option value="AF" <?php if( $order['delivery_details']['address_country'] == 'AF' ) { echo 'selected'; } ?> >Afghanistan</option>
														<option value="AX" <?php if( $order['delivery_details']['address_country'] == 'AX' ) { echo 'selected'; } ?> >Åland Islands</option>
														<option value="AL" <?php if( $order['delivery_details']['address_country'] == 'AL' ) { echo 'selected'; } ?> >Albania</option>
														<option value="DZ" <?php if( $order['delivery_details']['address_country'] == 'DZ' ) { echo 'selected'; } ?> >Algeria</option>
														<option value="AS" <?php if( $order['delivery_details']['address_country'] == 'AS' ) { echo 'selected'; } ?> >American Samoa</option>
														<option value="AD" <?php if( $order['delivery_details']['address_country'] == 'AD' ) { echo 'selected'; } ?> >Andorra</option>
														<option value="AO" <?php if( $order['delivery_details']['address_country'] == 'AO' ) { echo 'selected'; } ?> >Angola</option>
														<option value="AI" <?php if( $order['delivery_details']['address_country'] == 'AI' ) { echo 'selected'; } ?> >Anguilla</option>
														<option value="AQ" <?php if( $order['delivery_details']['address_country'] == 'AQ' ) { echo 'selected'; } ?> >Antarctica</option>
														<option value="AG" <?php if( $order['delivery_details']['address_country'] == 'AG' ) { echo 'selected'; } ?> >Antigua and Barbuda</option>
														<option value="AR" <?php if( $order['delivery_details']['address_country'] == 'AR' ) { echo 'selected'; } ?> >Argentina</option>
														<option value="AM" <?php if( $order['delivery_details']['address_country'] == 'AM' ) { echo 'selected'; } ?> >Armenia</option>
														<option value="AW" <?php if( $order['delivery_details']['address_country'] == 'AW' ) { echo 'selected'; } ?> >Aruba</option>
														<option value="AU" <?php if( $order['delivery_details']['address_country'] == 'AU' ) { echo 'selected'; } ?> >Australia</option>
														<option value="AT" <?php if( $order['delivery_details']['address_country'] == 'AT' ) { echo 'selected'; } ?> >Austria</option>
														<option value="AZ" <?php if( $order['delivery_details']['address_country'] == 'AZ' ) { echo 'selected'; } ?> >Azerbaijan</option>
														<option value="BS" <?php if( $order['delivery_details']['address_country'] == 'BS' ) { echo 'selected'; } ?> >Bahamas</option>
														<option value="BH" <?php if( $order['delivery_details']['address_country'] == 'BH' ) { echo 'selected'; } ?> >Bahrain</option>
														<option value="BD" <?php if( $order['delivery_details']['address_country'] == 'BD' ) { echo 'selected'; } ?> >Bangladesh</option>
														<option value="BB" <?php if( $order['delivery_details']['address_country'] == 'BB' ) { echo 'selected'; } ?> >Barbados</option>
														<option value="BY" <?php if( $order['delivery_details']['address_country'] == 'BY' ) { echo 'selected'; } ?> >Belarus</option>
														<option value="BE" <?php if( $order['delivery_details']['address_country'] == 'BE' ) { echo 'selected'; } ?> >Belgium</option>
														<option value="BZ" <?php if( $order['delivery_details']['address_country'] == 'BZ' ) { echo 'selected'; } ?> >Belize</option>
														<option value="BJ" <?php if( $order['delivery_details']['address_country'] == 'BJ' ) { echo 'selected'; } ?> >Benin</option>
														<option value="BM" <?php if( $order['delivery_details']['address_country'] == 'BM' ) { echo 'selected'; } ?> >Bermuda</option>
														<option value="BT" <?php if( $order['delivery_details']['address_country'] == 'BT' ) { echo 'selected'; } ?> >Bhutan</option>
														<option value="BO" <?php if( $order['delivery_details']['address_country'] == 'BO' ) { echo 'selected'; } ?> >Bolivia, Plurinational State of</option>
														<option value="BQ" <?php if( $order['delivery_details']['address_country'] == 'BQ' ) { echo 'selected'; } ?> >Bonaire, Sint Eustatius and Saba</option>
														<option value="BA" <?php if( $order['delivery_details']['address_country'] == 'BA' ) { echo 'selected'; } ?> >Bosnia and Herzegovina</option>
														<option value="BW" <?php if( $order['delivery_details']['address_country'] == 'BW' ) { echo 'selected'; } ?> >Botswana</option>
														<option value="BV" <?php if( $order['delivery_details']['address_country'] == 'BV' ) { echo 'selected'; } ?> >Bouvet Island</option>
														<option value="BR" <?php if( $order['delivery_details']['address_country'] == 'BR' ) { echo 'selected'; } ?> >Brazil</option>
														<option value="IO" <?php if( $order['delivery_details']['address_country'] == 'IO' ) { echo 'selected'; } ?> >British Indian Ocean Territory</option>
														<option value="BN" <?php if( $order['delivery_details']['address_country'] == 'BN' ) { echo 'selected'; } ?> >Brunei Darussalam</option>
														<option value="BG" <?php if( $order['delivery_details']['address_country'] == 'BG' ) { echo 'selected'; } ?> >Bulgaria</option>
														<option value="BF" <?php if( $order['delivery_details']['address_country'] == 'BF' ) { echo 'selected'; } ?> >Burkina Faso</option>
														<option value="BI" <?php if( $order['delivery_details']['address_country'] == 'BI' ) { echo 'selected'; } ?> >Burundi</option>
														<option value="KH" <?php if( $order['delivery_details']['address_country'] == 'KH' ) { echo 'selected'; } ?> >Cambodia</option>
														<option value="CM" <?php if( $order['delivery_details']['address_country'] == 'CM' ) { echo 'selected'; } ?> >Cameroon</option>
														<option value="CA" <?php if( $order['delivery_details']['address_country'] == 'CA' ) { echo 'selected'; } ?> >Canada</option>
														<option value="CV" <?php if( $order['delivery_details']['address_country'] == 'CV' ) { echo 'selected'; } ?> >Cape Verde</option>
														<option value="KY" <?php if( $order['delivery_details']['address_country'] == 'KY' ) { echo 'selected'; } ?> >Cayman Islands</option>
														<option value="CF" <?php if( $order['delivery_details']['address_country'] == 'CF' ) { echo 'selected'; } ?> >Central African Republic</option>
														<option value="TD" <?php if( $order['delivery_details']['address_country'] == 'TD' ) { echo 'selected'; } ?> >Chad</option>
														<option value="CL" <?php if( $order['delivery_details']['address_country'] == 'CL' ) { echo 'selected'; } ?> >Chile</option>
														<option value="CN" <?php if( $order['delivery_details']['address_country'] == 'CN' ) { echo 'selected'; } ?> >China</option>
														<option value="CX" <?php if( $order['delivery_details']['address_country'] == 'CX' ) { echo 'selected'; } ?> >Christmas Island</option>
														<option value="CC" <?php if( $order['delivery_details']['address_country'] == 'CC' ) { echo 'selected'; } ?> >Cocos (Keeling) Islands</option>
														<option value="CO" <?php if( $order['delivery_details']['address_country'] == 'CO' ) { echo 'selected'; } ?> >Colombia</option>
														<option value="KM" <?php if( $order['delivery_details']['address_country'] == 'KM' ) { echo 'selected'; } ?> >Comoros</option>
														<option value="CG" <?php if( $order['delivery_details']['address_country'] == 'CG' ) { echo 'selected'; } ?> >Congo</option>
														<option value="CD" <?php if( $order['delivery_details']['address_country'] == 'CD' ) { echo 'selected'; } ?> >Congo, the Democratic Republic of the</option>
														<option value="CK" <?php if( $order['delivery_details']['address_country'] == 'CK' ) { echo 'selected'; } ?> >Cook Islands</option>
														<option value="CR" <?php if( $order['delivery_details']['address_country'] == 'CR' ) { echo 'selected'; } ?> >Costa Rica</option>
														<option value="CI" <?php if( $order['delivery_details']['address_country'] == 'CI' ) { echo 'selected'; } ?> >Côte d'Ivoire</option>
														<option value="HR" <?php if( $order['delivery_details']['address_country'] == 'HR' ) { echo 'selected'; } ?> >Croatia</option>
														<option value="CU" <?php if( $order['delivery_details']['address_country'] == 'CU' ) { echo 'selected'; } ?> >Cuba</option>
														<option value="CW" <?php if( $order['delivery_details']['address_country'] == 'CW' ) { echo 'selected'; } ?> >Curaçao</option>
														<option value="CY" <?php if( $order['delivery_details']['address_country'] == 'CY' ) { echo 'selected'; } ?> >Cyprus</option>
														<option value="CZ" <?php if( $order['delivery_details']['address_country'] == 'CZ' ) { echo 'selected'; } ?> >Czech Republic</option>
														<option value="DK" <?php if( $order['delivery_details']['address_country'] == 'DK' ) { echo 'selected'; } ?> >Denmark</option>
														<option value="DJ" <?php if( $order['delivery_details']['address_country'] == 'DJ' ) { echo 'selected'; } ?> >Djibouti</option>
														<option value="DM" <?php if( $order['delivery_details']['address_country'] == 'DM' ) { echo 'selected'; } ?> >Dominica</option>
														<option value="DO" <?php if( $order['delivery_details']['address_country'] == 'DO' ) { echo 'selected'; } ?> >Dominican Republic</option>
														<option value="EC" <?php if( $order['delivery_details']['address_country'] == 'EC' ) { echo 'selected'; } ?> >Ecuador</option>
														<option value="EG" <?php if( $order['delivery_details']['address_country'] == 'EG' ) { echo 'selected'; } ?> >Egypt</option>
														<option value="SV" <?php if( $order['delivery_details']['address_country'] == 'SV' ) { echo 'selected'; } ?> >El Salvador</option>
														<option value="GQ" <?php if( $order['delivery_details']['address_country'] == 'GQ' ) { echo 'selected'; } ?> >Equatorial Guinea</option>
														<option value="ER" <?php if( $order['delivery_details']['address_country'] == 'ER' ) { echo 'selected'; } ?> >Eritrea</option>
														<option value="EE" <?php if( $order['delivery_details']['address_country'] == 'EE' ) { echo 'selected'; } ?> >Estonia</option>
														<option value="ET" <?php if( $order['delivery_details']['address_country'] == 'ET' ) { echo 'selected'; } ?> >Ethiopia</option>
														<option value="FK" <?php if( $order['delivery_details']['address_country'] == 'FK' ) { echo 'selected'; } ?> >Falkland Islands (Malvinas)</option>
														<option value="FO" <?php if( $order['delivery_details']['address_country'] == 'FO' ) { echo 'selected'; } ?> >Faroe Islands</option>
														<option value="FJ" <?php if( $order['delivery_details']['address_country'] == 'FJ' ) { echo 'selected'; } ?> >Fiji</option>
														<option value="FI" <?php if( $order['delivery_details']['address_country'] == 'FI' ) { echo 'selected'; } ?> >Finland</option>
														<option value="FR" <?php if( $order['delivery_details']['address_country'] == 'FR' ) { echo 'selected'; } ?> >France</option>
														<option value="GF" <?php if( $order['delivery_details']['address_country'] == 'GF' ) { echo 'selected'; } ?> >French Guiana</option>
														<option value="PF" <?php if( $order['delivery_details']['address_country'] == 'PF' ) { echo 'selected'; } ?> >French Polynesia</option>
														<option value="TF" <?php if( $order['delivery_details']['address_country'] == 'TF' ) { echo 'selected'; } ?> >French Southern Territories</option>
														<option value="GA" <?php if( $order['delivery_details']['address_country'] == 'GA' ) { echo 'selected'; } ?> >Gabon</option>
														<option value="GM" <?php if( $order['delivery_details']['address_country'] == 'GM' ) { echo 'selected'; } ?> >Gambia</option>
														<option value="GE" <?php if( $order['delivery_details']['address_country'] == 'GE' ) { echo 'selected'; } ?> >Georgia</option>
														<option value="DE" <?php if( $order['delivery_details']['address_country'] == 'DE' ) { echo 'selected'; } ?> >Germany</option>
														<option value="GH" <?php if( $order['delivery_details']['address_country'] == 'GH' ) { echo 'selected'; } ?> >Ghana</option>
														<option value="GI" <?php if( $order['delivery_details']['address_country'] == 'GI' ) { echo 'selected'; } ?> >Gibraltar</option>
														<option value="GR" <?php if( $order['delivery_details']['address_country'] == 'GR' ) { echo 'selected'; } ?> >Greece</option>
														<option value="GL" <?php if( $order['delivery_details']['address_country'] == 'GL' ) { echo 'selected'; } ?> >Greenland</option>
														<option value="GD" <?php if( $order['delivery_details']['address_country'] == 'GD' ) { echo 'selected'; } ?> >Grenada</option>
														<option value="GP" <?php if( $order['delivery_details']['address_country'] == 'GP' ) { echo 'selected'; } ?> >Guadeloupe</option>
														<option value="GU" <?php if( $order['delivery_details']['address_country'] == 'GU' ) { echo 'selected'; } ?> >Guam</option>
														<option value="GT" <?php if( $order['delivery_details']['address_country'] == 'GT' ) { echo 'selected'; } ?> >Guatemala</option>
														<option value="GG" <?php if( $order['delivery_details']['address_country'] == 'GG' ) { echo 'selected'; } ?> >Guernsey</option>
														<option value="GN" <?php if( $order['delivery_details']['address_country'] == 'GN' ) { echo 'selected'; } ?> >Guinea</option>
														<option value="GW" <?php if( $order['delivery_details']['address_country'] == 'GW' ) { echo 'selected'; } ?> >Guinea-Bissau</option>
														<option value="GY" <?php if( $order['delivery_details']['address_country'] == 'GY' ) { echo 'selected'; } ?> >Guyana</option>
														<option value="HT" <?php if( $order['delivery_details']['address_country'] == 'HT' ) { echo 'selected'; } ?> >Haiti</option>
														<option value="HM" <?php if( $order['delivery_details']['address_country'] == 'HM' ) { echo 'selected'; } ?> >Heard Island and McDonald Islands</option>
														<option value="VA" <?php if( $order['delivery_details']['address_country'] == 'VA' ) { echo 'selected'; } ?> >Holy See (Vatican City State)</option>
														<option value="HN" <?php if( $order['delivery_details']['address_country'] == 'HN' ) { echo 'selected'; } ?> >Honduras</option>
														<option value="HK" <?php if( $order['delivery_details']['address_country'] == 'HK' ) { echo 'selected'; } ?> >Hong Kong</option>
														<option value="HU" <?php if( $order['delivery_details']['address_country'] == 'HU' ) { echo 'selected'; } ?> >Hungary</option>
														<option value="IS" <?php if( $order['delivery_details']['address_country'] == 'IS' ) { echo 'selected'; } ?> >Iceland</option>
														<option value="IN" <?php if( $order['delivery_details']['address_country'] == 'IN' ) { echo 'selected'; } ?> >India</option>
														<option value="ID" <?php if( $order['delivery_details']['address_country'] == 'ID' ) { echo 'selected'; } ?> >Indonesia</option>
														<option value="IR" <?php if( $order['delivery_details']['address_country'] == 'IR' ) { echo 'selected'; } ?> >Iran, Islamic Republic of</option>
														<option value="IQ" <?php if( $order['delivery_details']['address_country'] == 'IQ' ) { echo 'selected'; } ?> >Iraq</option>
														<option value="IE" <?php if( $order['delivery_details']['address_country'] == 'IE' ) { echo 'selected'; } ?> >Ireland</option>
														<option value="IM" <?php if( $order['delivery_details']['address_country'] == 'IM' ) { echo 'selected'; } ?> >Isle of Man</option>
														<option value="IL" <?php if( $order['delivery_details']['address_country'] == 'IL' ) { echo 'selected'; } ?> >Israel</option>
														<option value="IT" <?php if( $order['delivery_details']['address_country'] == 'IT' ) { echo 'selected'; } ?> >Italy</option>
														<option value="JM" <?php if( $order['delivery_details']['address_country'] == 'JM' ) { echo 'selected'; } ?> >Jamaica</option>
														<option value="JP" <?php if( $order['delivery_details']['address_country'] == 'JP' ) { echo 'selected'; } ?> >Japan</option>
														<option value="JE" <?php if( $order['delivery_details']['address_country'] == 'JE' ) { echo 'selected'; } ?> >Jersey</option>
														<option value="JO" <?php if( $order['delivery_details']['address_country'] == 'JO' ) { echo 'selected'; } ?> >Jordan</option>
														<option value="KZ" <?php if( $order['delivery_details']['address_country'] == 'KZ' ) { echo 'selected'; } ?> >Kazakhstan</option>
														<option value="KE" <?php if( $order['delivery_details']['address_country'] == 'KE' ) { echo 'selected'; } ?> >Kenya</option>
														<option value="KI" <?php if( $order['delivery_details']['address_country'] == 'KI' ) { echo 'selected'; } ?> >Kiribati</option>
														<option value="KP" <?php if( $order['delivery_details']['address_country'] == 'KP' ) { echo 'selected'; } ?> >Korea, Democratic People's Republic of</option>
														<option value="KR" <?php if( $order['delivery_details']['address_country'] == 'KR' ) { echo 'selected'; } ?> >Korea, Republic of</option>
														<option value="KW" <?php if( $order['delivery_details']['address_country'] == 'KW' ) { echo 'selected'; } ?> >Kuwait</option>
														<option value="KG" <?php if( $order['delivery_details']['address_country'] == 'KG' ) { echo 'selected'; } ?> >Kyrgyzstan</option>
														<option value="LA" <?php if( $order['delivery_details']['address_country'] == 'LA' ) { echo 'selected'; } ?> >Lao People's Democratic Republic</option>
														<option value="LV" <?php if( $order['delivery_details']['address_country'] == 'LV' ) { echo 'selected'; } ?> >Latvia</option>
														<option value="LB" <?php if( $order['delivery_details']['address_country'] == 'LB' ) { echo 'selected'; } ?> >Lebanon</option>
														<option value="LS" <?php if( $order['delivery_details']['address_country'] == 'LS' ) { echo 'selected'; } ?> >Lesotho</option>
														<option value="LR" <?php if( $order['delivery_details']['address_country'] == 'LR' ) { echo 'selected'; } ?> >Liberia</option>
														<option value="LY" <?php if( $order['delivery_details']['address_country'] == 'LY' ) { echo 'selected'; } ?> >Libya</option>
														<option value="LI" <?php if( $order['delivery_details']['address_country'] == 'LI' ) { echo 'selected'; } ?> >Liechtenstein</option>
														<option value="LT" <?php if( $order['delivery_details']['address_country'] == 'LT' ) { echo 'selected'; } ?> >Lithuania</option>
														<option value="LU" <?php if( $order['delivery_details']['address_country'] == 'LU' ) { echo 'selected'; } ?> >Luxembourg</option>
														<option value="MO" <?php if( $order['delivery_details']['address_country'] == 'MO' ) { echo 'selected'; } ?> >Macao</option>
														<option value="MK" <?php if( $order['delivery_details']['address_country'] == 'MK' ) { echo 'selected'; } ?> >Macedonia, the former Yugoslav Republic of</option>
														<option value="MG" <?php if( $order['delivery_details']['address_country'] == 'MG' ) { echo 'selected'; } ?> >Madagascar</option>
														<option value="MW" <?php if( $order['delivery_details']['address_country'] == 'MW' ) { echo 'selected'; } ?> >Malawi</option>
														<option value="MY" <?php if( $order['delivery_details']['address_country'] == 'MY' ) { echo 'selected'; } ?> >Malaysia</option>
														<option value="MV" <?php if( $order['delivery_details']['address_country'] == 'MV' ) { echo 'selected'; } ?> >Maldives</option>
														<option value="ML" <?php if( $order['delivery_details']['address_country'] == 'ML' ) { echo 'selected'; } ?> >Mali</option>
														<option value="MT" <?php if( $order['delivery_details']['address_country'] == 'MT' ) { echo 'selected'; } ?> >Malta</option>
														<option value="MH" <?php if( $order['delivery_details']['address_country'] == 'MH' ) { echo 'selected'; } ?> >Marshall Islands</option>
														<option value="MQ" <?php if( $order['delivery_details']['address_country'] == 'MQ' ) { echo 'selected'; } ?> >Martinique</option>
														<option value="MR" <?php if( $order['delivery_details']['address_country'] == 'MR' ) { echo 'selected'; } ?> >Mauritania</option>
														<option value="MU" <?php if( $order['delivery_details']['address_country'] == 'MU' ) { echo 'selected'; } ?> >Mauritius</option>
														<option value="YT" <?php if( $order['delivery_details']['address_country'] == 'YT' ) { echo 'selected'; } ?> >Mayotte</option>
														<option value="MX" <?php if( $order['delivery_details']['address_country'] == 'MX' ) { echo 'selected'; } ?> >Mexico</option>
														<option value="FM" <?php if( $order['delivery_details']['address_country'] == 'FM' ) { echo 'selected'; } ?> >Micronesia, Federated States of</option>
														<option value="MD" <?php if( $order['delivery_details']['address_country'] == 'MD' ) { echo 'selected'; } ?> >Moldova, Republic of</option>
														<option value="MC" <?php if( $order['delivery_details']['address_country'] == 'MC' ) { echo 'selected'; } ?> >Monaco</option>
														<option value="MN" <?php if( $order['delivery_details']['address_country'] == 'MN' ) { echo 'selected'; } ?> >Mongolia</option>
														<option value="ME" <?php if( $order['delivery_details']['address_country'] == 'ME' ) { echo 'selected'; } ?> >Montenegro</option>
														<option value="MS" <?php if( $order['delivery_details']['address_country'] == 'MS' ) { echo 'selected'; } ?> >Montserrat</option>
														<option value="MA" <?php if( $order['delivery_details']['address_country'] == 'MA' ) { echo 'selected'; } ?> >Morocco</option>
														<option value="MZ" <?php if( $order['delivery_details']['address_country'] == 'MZ' ) { echo 'selected'; } ?> >Mozambique</option>
														<option value="MM" <?php if( $order['delivery_details']['address_country'] == 'MM' ) { echo 'selected'; } ?> >Myanmar</option>
														<option value="NA" <?php if( $order['delivery_details']['address_country'] == 'NA' ) { echo 'selected'; } ?> >Namibia</option>
														<option value="NR" <?php if( $order['delivery_details']['address_country'] == 'NR' ) { echo 'selected'; } ?> >Nauru</option>
														<option value="NP" <?php if( $order['delivery_details']['address_country'] == 'NP' ) { echo 'selected'; } ?> >Nepal</option>
														<option value="NL" <?php if( $order['delivery_details']['address_country'] == 'NL' ) { echo 'selected'; } ?> >Netherlands</option>
														<option value="NC" <?php if( $order['delivery_details']['address_country'] == 'NC' ) { echo 'selected'; } ?> >New Caledonia</option>
														<option value="NZ" <?php if( $order['delivery_details']['address_country'] == 'NZ' ) { echo 'selected'; } ?> >New Zealand</option>
														<option value="NI" <?php if( $order['delivery_details']['address_country'] == 'NI' ) { echo 'selected'; } ?> >Nicaragua</option>
														<option value="NE" <?php if( $order['delivery_details']['address_country'] == 'NE' ) { echo 'selected'; } ?> >Niger</option>
														<option value="NG" <?php if( $order['delivery_details']['address_country'] == 'NG' ) { echo 'selected'; } ?> >Nigeria</option>
														<option value="NU" <?php if( $order['delivery_details']['address_country'] == 'NU' ) { echo 'selected'; } ?> >Niue</option>
														<option value="NF" <?php if( $order['delivery_details']['address_country'] == 'NF' ) { echo 'selected'; } ?> >Norfolk Island</option>
														<option value="MP" <?php if( $order['delivery_details']['address_country'] == 'MP' ) { echo 'selected'; } ?> >Northern Mariana Islands</option>
														<option value="NO" <?php if( $order['delivery_details']['address_country'] == 'NO' ) { echo 'selected'; } ?> >Norway</option>
														<option value="OM" <?php if( $order['delivery_details']['address_country'] == 'OM' ) { echo 'selected'; } ?> >Oman</option>
														<option value="PK" <?php if( $order['delivery_details']['address_country'] == 'PK' ) { echo 'selected'; } ?> >Pakistan</option>
														<option value="PW" <?php if( $order['delivery_details']['address_country'] == 'PW' ) { echo 'selected'; } ?> >Palau</option>
														<option value="PS" <?php if( $order['delivery_details']['address_country'] == 'PS' ) { echo 'selected'; } ?> >Palestinian Territory, Occupied</option>
														<option value="PA" <?php if( $order['delivery_details']['address_country'] == 'PA' ) { echo 'selected'; } ?> >Panama</option>
														<option value="PG" <?php if( $order['delivery_details']['address_country'] == 'PG' ) { echo 'selected'; } ?> >Papua New Guinea</option>
														<option value="PY" <?php if( $order['delivery_details']['address_country'] == 'PY' ) { echo 'selected'; } ?> >Paraguay</option>
														<option value="PE" <?php if( $order['delivery_details']['address_country'] == 'PE' ) { echo 'selected'; } ?> >Peru</option>
														<option value="PH" <?php if( $order['delivery_details']['address_country'] == 'PH' ) { echo 'selected'; } ?> >Philippines</option>
														<option value="PN" <?php if( $order['delivery_details']['address_country'] == 'PN' ) { echo 'selected'; } ?> >Pitcairn</option>
														<option value="PL" <?php if( $order['delivery_details']['address_country'] == 'PL' ) { echo 'selected'; } ?> >Poland</option>
														<option value="PT" <?php if( $order['delivery_details']['address_country'] == 'PT' ) { echo 'selected'; } ?> >Portugal</option>
														<option value="PR" <?php if( $order['delivery_details']['address_country'] == 'PR' ) { echo 'selected'; } ?> >Puerto Rico</option>
														<option value="QA" <?php if( $order['delivery_details']['address_country'] == 'QA' ) { echo 'selected'; } ?> >Qatar</option>
														<option value="RE" <?php if( $order['delivery_details']['address_country'] == 'RE' ) { echo 'selected'; } ?> >Réunion</option>
														<option value="RO" <?php if( $order['delivery_details']['address_country'] == 'RO' ) { echo 'selected'; } ?> >Romania</option>
														<option value="RU" <?php if( $order['delivery_details']['address_country'] == 'RU' ) { echo 'selected'; } ?> >Russian Federation</option>
														<option value="RW" <?php if( $order['delivery_details']['address_country'] == 'RW' ) { echo 'selected'; } ?> >Rwanda</option>
														<option value="BL" <?php if( $order['delivery_details']['address_country'] == 'BL' ) { echo 'selected'; } ?> >Saint Barthélemy</option>
														<option value="SH" <?php if( $order['delivery_details']['address_country'] == 'SH' ) { echo 'selected'; } ?> >Saint Helena, Ascension and Tristan da Cunha</option>
														<option value="KN" <?php if( $order['delivery_details']['address_country'] == 'KN' ) { echo 'selected'; } ?> >Saint Kitts and Nevis</option>
														<option value="LC" <?php if( $order['delivery_details']['address_country'] == 'LC' ) { echo 'selected'; } ?> >Saint Lucia</option>
														<option value="MF" <?php if( $order['delivery_details']['address_country'] == 'MF' ) { echo 'selected'; } ?> >Saint Martin (French part)</option>
														<option value="PM" <?php if( $order['delivery_details']['address_country'] == 'PM' ) { echo 'selected'; } ?> >Saint Pierre and Miquelon</option>
														<option value="VC" <?php if( $order['delivery_details']['address_country'] == 'VC' ) { echo 'selected'; } ?> >Saint Vincent and the Grenadines</option>
														<option value="WS" <?php if( $order['delivery_details']['address_country'] == 'WS' ) { echo 'selected'; } ?> >Samoa</option>
														<option value="SM" <?php if( $order['delivery_details']['address_country'] == 'SM' ) { echo 'selected'; } ?> >San Marino</option>
														<option value="ST" <?php if( $order['delivery_details']['address_country'] == 'ST' ) { echo 'selected'; } ?> >Sao Tome and Principe</option>
														<option value="SA" <?php if( $order['delivery_details']['address_country'] == 'SA' ) { echo 'selected'; } ?> >Saudi Arabia</option>
														<option value="SN" <?php if( $order['delivery_details']['address_country'] == 'SN' ) { echo 'selected'; } ?> >Senegal</option>
														<option value="RS" <?php if( $order['delivery_details']['address_country'] == 'RS' ) { echo 'selected'; } ?> >Serbia</option>
														<option value="SC" <?php if( $order['delivery_details']['address_country'] == 'SC' ) { echo 'selected'; } ?> >Seychelles</option>
														<option value="SL" <?php if( $order['delivery_details']['address_country'] == 'SL' ) { echo 'selected'; } ?> >Sierra Leone</option>
														<option value="SG" <?php if( $order['delivery_details']['address_country'] == 'SG' ) { echo 'selected'; } ?> >Singapore</option>
														<option value="SX" <?php if( $order['delivery_details']['address_country'] == 'SX' ) { echo 'selected'; } ?> >Sint Maarten (Dutch part)</option>
														<option value="SK" <?php if( $order['delivery_details']['address_country'] == 'SK' ) { echo 'selected'; } ?> >Slovakia</option>
														<option value="SI" <?php if( $order['delivery_details']['address_country'] == 'SI' ) { echo 'selected'; } ?> >Slovenia</option>
														<option value="SB" <?php if( $order['delivery_details']['address_country'] == 'SB' ) { echo 'selected'; } ?> >Solomon Islands</option>
														<option value="SO" <?php if( $order['delivery_details']['address_country'] == 'SO' ) { echo 'selected'; } ?> >Somalia</option>
														<option value="ZA" <?php if( $order['delivery_details']['address_country'] == 'ZA' ) { echo 'selected'; } ?> >South Africa</option>
														<option value="GS" <?php if( $order['delivery_details']['address_country'] == 'GS' ) { echo 'selected'; } ?> >South Georgia and the South Sandwich Islands</option>
														<option value="SS" <?php if( $order['delivery_details']['address_country'] == 'SS' ) { echo 'selected'; } ?> >South Sudan</option>
														<option value="ES" <?php if( $order['delivery_details']['address_country'] == 'ES' ) { echo 'selected'; } ?> >Spain</option>
														<option value="LK" <?php if( $order['delivery_details']['address_country'] == 'LK' ) { echo 'selected'; } ?> >Sri Lanka</option>
														<option value="SD" <?php if( $order['delivery_details']['address_country'] == 'SD' ) { echo 'selected'; } ?> >Sudan</option>
														<option value="SR" <?php if( $order['delivery_details']['address_country'] == 'SR' ) { echo 'selected'; } ?> >Suriname</option>
														<option value="SJ" <?php if( $order['delivery_details']['address_country'] == 'SJ' ) { echo 'selected'; } ?> >Svalbard and Jan Mayen</option>
														<option value="SZ" <?php if( $order['delivery_details']['address_country'] == 'SZ' ) { echo 'selected'; } ?> >Swaziland</option>
														<option value="SE" <?php if( $order['delivery_details']['address_country'] == 'SE' ) { echo 'selected'; } ?> >Sweden</option>
														<option value="CH" <?php if( $order['delivery_details']['address_country'] == 'CH' ) { echo 'selected'; } ?> >Switzerland</option>
														<option value="SY" <?php if( $order['delivery_details']['address_country'] == 'SY' ) { echo 'selected'; } ?> >Syrian Arab Republic</option>
														<option value="TW" <?php if( $order['delivery_details']['address_country'] == 'TW' ) { echo 'selected'; } ?> >Taiwan, Province of China</option>
														<option value="TJ" <?php if( $order['delivery_details']['address_country'] == 'TJ' ) { echo 'selected'; } ?> >Tajikistan</option>
														<option value="TZ" <?php if( $order['delivery_details']['address_country'] == 'TZ' ) { echo 'selected'; } ?> >Tanzania, United Republic of</option>
														<option value="TH" <?php if( $order['delivery_details']['address_country'] == 'TH' ) { echo 'selected'; } ?> >Thailand</option>
														<option value="TL" <?php if( $order['delivery_details']['address_country'] == 'TL' ) { echo 'selected'; } ?> >Timor-Leste</option>
														<option value="TG" <?php if( $order['delivery_details']['address_country'] == 'TG' ) { echo 'selected'; } ?> >Togo</option>
														<option value="TK" <?php if( $order['delivery_details']['address_country'] == 'TK' ) { echo 'selected'; } ?> >Tokelau</option>
														<option value="TO" <?php if( $order['delivery_details']['address_country'] == 'TO' ) { echo 'selected'; } ?> >Tonga</option>
														<option value="TT" <?php if( $order['delivery_details']['address_country'] == 'TT' ) { echo 'selected'; } ?> >Trinidad and Tobago</option>
														<option value="TN" <?php if( $order['delivery_details']['address_country'] == 'TN' ) { echo 'selected'; } ?> >Tunisia</option>
														<option value="TR" <?php if( $order['delivery_details']['address_country'] == 'TR' ) { echo 'selected'; } ?> >Turkey</option>
														<option value="TM" <?php if( $order['delivery_details']['address_country'] == 'TM' ) { echo 'selected'; } ?> >Turkmenistan</option>
														<option value="TC" <?php if( $order['delivery_details']['address_country'] == 'TC' ) { echo 'selected'; } ?> >Turks and Caicos Islands</option>
														<option value="TV" <?php if( $order['delivery_details']['address_country'] == 'TV' ) { echo 'selected'; } ?> >Tuvalu</option>
														<option value="UG" <?php if( $order['delivery_details']['address_country'] == 'UG' ) { echo 'selected'; } ?> >Uganda</option>
														<option value="UA" <?php if( $order['delivery_details']['address_country'] == 'UA' ) { echo 'selected'; } ?> >Ukraine</option>
														<option value="AE" <?php if( $order['delivery_details']['address_country'] == 'AE' ) { echo 'selected'; } ?> >United Arab Emirates</option>
														<option value="GB" <?php if( $order['delivery_details']['address_country'] == 'GB' ) { echo 'selected'; } ?> >United Kingdom</option>
														<option value="US" <?php if( $order['delivery_details']['address_country'] == 'US' ) { echo 'selected'; } ?> >United States</option>
														<option value="UM" <?php if( $order['delivery_details']['address_country'] == 'UM' ) { echo 'selected'; } ?> >United States Minor Outlying Islands</option>
														<option value="UY" <?php if( $order['delivery_details']['address_country'] == 'UY' ) { echo 'selected'; } ?> >Uruguay</option>
														<option value="UZ" <?php if( $order['delivery_details']['address_country'] == 'UZ' ) { echo 'selected'; } ?> >Uzbekistan</option>
														<option value="VU" <?php if( $order['delivery_details']['address_country'] == 'VU' ) { echo 'selected'; } ?> >Vanuatu</option>
														<option value="VE" <?php if( $order['delivery_details']['address_country'] == 'VE' ) { echo 'selected'; } ?> >Venezuela, Bolivarian Republic of</option>
														<option value="VN" <?php if( $order['delivery_details']['address_country'] == 'VN' ) { echo 'selected'; } ?> >Viet Nam</option>
														<option value="VG" <?php if( $order['delivery_details']['address_country'] == 'VG' ) { echo 'selected'; } ?> >Virgin Islands, British</option>
														<option value="VI" <?php if( $order['delivery_details']['address_country'] == 'VI' ) { echo 'selected'; } ?> >Virgin Islands, U.S.</option>
														<option value="WF" <?php if( $order['delivery_details']['address_country'] == 'WF' ) { echo 'selected'; } ?> >Wallis and Futuna</option>
														<option value="EH" <?php if( $order['delivery_details']['address_country'] == 'EH' ) { echo 'selected'; } ?> >Western Sahara</option>
														<option value="YE" <?php if( $order['delivery_details']['address_country'] == 'YE' ) { echo 'selected'; } ?> >Yemen</option>
														<option value="ZM" <?php if( $order['delivery_details']['address_country'] == 'ZM' ) { echo 'selected'; } ?> >Zambia</option>
														<option value="ZW" <?php if( $order['delivery_details']['address_country'] == 'ZW' ) { echo 'selected'; } ?> >Zimbabwe</option>
													</select>
												</div>
											</div>
											<div class="col-xl-12 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Notes</strong></label>
													<textarea name="notes" class="form-control" rows="2" <?php echo $readonly; ?>><?php echo $order['delivery_details']['notes']; ?></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

						<div class="col-xl-6 col-sm-12">
							<div class="row">
								<!-- order notes -->
								<div class="col-xl-6 col-sm-12">
									<form class="form" method="post" action="actions.php?a=order_edit_notes">
										<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
										
										<div class="panel panel-inverse">
											<div class="panel-heading">
												<h2 class="panel-title">Order Notes</h2>
												<div class="panel-heading-btn">
													<div class="btn-group">
														<?php if( empty( $readonly ) ) { ?>
															<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-xl-12 col-sm-12">
														<div class="form-group">
															<label class="bmd-label-floating"><strong>Notes</strong></label>
															<textarea id="notes" name="notes" class="form-control" rows="2" <?php echo $readonly; ?>><?php echo $order['notes']; ?></textarea>
															<small>Order specific notes. Eg: white lillies instead of red.</small>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>

								<!-- card message -->
								<div class="col-xl-6 col-sm-12">
									<form class="form" method="post" action="actions.php?a=order_card_message">
										<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
										
										<div class="panel panel-inverse">
											<div class="panel-heading">
												<h2 class="panel-title">Card Message</h2>
												<div class="panel-heading-btn">
													<div class="btn-group">
														<?php if( empty( $readonly ) ) { ?>
															<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-xl-12 col-sm-12">
														<div class="form-group">
															<label class="bmd-label-floating"><strong>Message</strong></label>
															<textarea id="card_message" name="card_message" class="form-control" rows="2" onkeyup="countChar(this)" placeholder="Send a message card (optional)" <?php echo $readonly; ?>><?php echo $order['card_message']; ?></textarea>
															Characters remaining: <span id="charNum">100</span> of 100
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>

							<!-- shopping cart -->
							<?php if( empty( $order['delivery_id'] ) || empty( $order['delivery_date'] ) ) { ?>
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Shopping Cart</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">

											</div>
										</div>
									</div>
									<div class="panel-body">
										<center>
											<h3>
												Please enter the delivery details first.
											</h2>
										</center>
									</div>
								</div>
							<?php } else { ?>
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Shopping Cart</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">

											</div>
										</div>
									</div>
									<div class="panel-body">
										<?php if( $order['payment_status'] == 'unpaid' ) { ?>
											<p class="lead">
												Add New Item.
											</p>
											<form class="form" method="post" action="actions.php?a=order_add_item">
												<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
												<div class="row">
													<div class="col-xl-10 col-sm-12">
														<label class="bmd-label-floating"><strong>Item</strong></label>
														<select name="product_id" class="form-control select2">
															<option disabled="disabled">----- Select an item -----</option>
															<?php foreach( $products as $product ) { ?>
																<option value="<?php echo $product['id']; ?>" ><?php echo $product['title']; ?> - $<?php echo $product['price']; ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="col-xl-1 col-sm-12">
														<label class="bmd-label-floating"><strong>Qty</strong></label>
														<input type="text" name="qty" class="form-control" value="1" required>
													</div>
													<div class="col-xl-1 col-sm-12">
														<label class="bmd-label-floating"><strong>Action</strong></label>
														<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Add</button>
													</div>
												</div>
											</form>
										<?php } ?>

										<?php if( isset( $order['order_items'][0]['id'] ) ) { ?>
											<?php if( $order['payment_status'] == 'unpaid' ) { ?>
												<hr>
												<p class="lead">
													Existing Items.
												</p>
											<?php } ?>
											<div class="invoice">
												<div class="invoice-content">
													<div class="table-responsive">
														<table class="table table-invoice">
															<thead>
																<tr>
																	<th>Item</th>
																	<th class="text-center text-nowrap" width="1px">Price</th>
																	<th class="text-center text-nowrap" width="1px">Qty</th>
																	<th class="text-center text-nowrap" width="1px">Total</th>
																	<th class="text-right" width="1px"></th>
																</tr>
															</thead>
															<tbody>
																<?php
																	// build table
																	foreach( $order['order_items'] as $order_item ) {
																		// match item to product
																		foreach( $products as $product ) {
																			if( $product['id'] == $order_item['product_id'] ) {
																				break;
																			}
																		}

																		// calculate line total
																		$order_item['price_total'] = $product['price'] * $order_item['qty'];

																		// free delivery check
																		if( $product['free_delivery'] == 'yes' ) {
																			$globals['delivery_fee'] = '0.00';
																		}

																		// output
																		echo '
																			<tr>
																				<td>
																					<span class="text-inverse">'.$product['title'].'</span><br />
																					<small>'.$product['preview_text'].'</small>
																				</td>
																				<td class="text-center">
																					$'.number_format( $product['price'], 2 ).'
																				</td>
																				<td class="text-center">'
																					.$order_item['qty'].'
																				</td>
																				<td class="text-right">
																					$'.number_format( $order_item['price_total'], 2 ).'
																				</td>
																				<td class="text-right">
																					'.( $order['payment_status'] == 'unpaid' ? '<a href="actions.php?a=order_delete_item&order_id='.$order['id'].'&product_id='.$product['id'].'&id='.$order_item['id'].'" class="btn btn-xs btn-danger">X</a>' : '' ).'
																				</td>
																			</tr>
																		';
																	}
																?>
															</tbody>
														</table>
													</div>
													<div class="invoice-price">
														<div class="invoice-price-left">
															<div class="invoice-price-row">
																<div class="sub-price">
																	<small>Subtotal</small>
																	<span class="text-inverse">$<?php echo number_format( $order['total_price'], 2 ); ?></span>
																</div>
																<div class="sub-price">
																	<i class="fa fa-plus text-muted"></i>
																</div>
																<div class="sub-price">
																	<small>Delivery Fee</small>
																	<span class="text-inverse">$<?php echo $globals['delivery_fee']; ?></span>
																</div>
															</div>
														</div>
														<div class="invoice-price-right">
															<small>TOTAL</small> <span class="f-w-600">$<?php echo number_format( $order['total_price'] + $globals['delivery_fee'], 2 ); ?></span>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
									<?php if( isset( $order['order_items'][0]['id'] ) ) { ?>
										<?php if( $order['payment_status'] == 'unpaid' ) { ?>
											<div class="panel-footer text-right">
												<div class="btn-group">
													<a href="#" onclick="order_delete( <?php echo $order['id']; ?> )" class="btn btn-xs btn-danger">Delete</a>
													<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#payment_modal">Checkout</button>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							<?php } ?>

							<!-- payment details -->
							<?php if( !empty( $order['payment_id'] ) ) { ?>
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Payment Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												<?php if( $admin_check || $staff_check ) { ?>
													<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#refund_modal">Refund</button>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-12 col-sm-12">
												<dl class="row">
													<dt class="text-inverse text-right col-4 text-truncate">Status</dt>
													<dd class="col-8"><?php echo strtoupper( $order['payment_details']['status'] ); ?></dd>

													<dt class="text-inverse text-right col-4 text-truncate">Transaction ID</dt>
													<dd class="col-8"><?php echo $order['payment_details']['stripe_id']; ?></dd>

													<dt class="text-inverse text-right col-4 text-truncate">Date</dt>
													<dd class="col-8"><?php echo date( 'Y-m-d h:i', $order['payment_details']['added'] ); ?></dd>

													<dt class="text-inverse text-right col-4 text-truncate">Amount</dt>
													<dd class="col-8">$<?php echo number_format( $order['payment_details']['amount'], 2 ); ?></dd>

													<dt class="text-inverse text-right col-4 text-truncate">Card</dt>
													<dd class="col-8">X-<?php echo substr( $order['payment_details']['card_number'], -4 ); ?></dd>
												</dl>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $order ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>

			<!-- payment modal -->
			<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="payment_modal" aria-hidden="true">
			   	<div class="modal-dialog">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Payment</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
							<form id="frmStripePayment" action="payment_capture.php" method="post">
								<input type='hidden' id="order_id" name='order_id' value='<?php echo $order['id']; ?>'>
								<input type='hidden' id="email" name='email' value='<?php echo $order['customer']['email']; ?>'>
								<input type='hidden' name='amount' value='<?php echo $order['total_price'] + $globals['delivery_fee']; ?>'>
								<input type='hidden' name='currency_code' value='USD'>
								<input type='hidden' name='item_name' value='FlowerNetworkTeam Order'>
								<input type='hidden' name='item_number' value='Order #<?php echo $order['id']; ?>'>

								<div id="error-message"></div>

								<div class="row hidden-xs">
									<div class="col-xl-12 col-sm-12 text-center">
										<img src="images/credit_cards.jpg" height="25px" alt=""><br><br>
									</div>
								</div>

								<div class="row">
									<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Card Holder</strong></label>
											<input type="text" id="card-name" name="card-name" class="form-control" value="<?php echo $order['customer']['full_name']; ?>" required>
										</div>
									</div>
									<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Card Number</strong></label>
											<input type="text" id="card-number" name="card-number" class="form-control" maxlength="16" required>
										</div>
									</div>
									<div class="col-xl-3 col-sm-3">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Exp Month</strong></label>
											<select id="month" name="month" class="form-control">
												<option value="01" <?php if( date( 'm', time() ) == '01' ) { echo 'selected'; } ?> >01</option>
												<option value="02" <?php if( date( 'm', time() ) == '02' ) { echo 'selected'; } ?> >02</option>
												<option value="03" <?php if( date( 'm', time() ) == '03' ) { echo 'selected'; } ?> >03</option>
												<option value="04" <?php if( date( 'm', time() ) == '04' ) { echo 'selected'; } ?> >04</option>
												<option value="05" <?php if( date( 'm', time() ) == '05' ) { echo 'selected'; } ?> >05</option>
												<option value="06" <?php if( date( 'm', time() ) == '06' ) { echo 'selected'; } ?> >06</option>
												<option value="07" <?php if( date( 'm', time() ) == '07' ) { echo 'selected'; } ?> >07</option>
								                <option value="08" <?php if( date( 'm', time() ) == '08' ) { echo 'selected'; } ?> >08</option>
								                <option value="09" <?php if( date( 'm', time() ) == '09' ) { echo 'selected'; } ?> >09</option>
								                <option value="10" <?php if( date( 'm', time() ) == '10' ) { echo 'selected'; } ?> >10</option>
								                <option value="11" <?php if( date( 'm', time() ) == '11' ) { echo 'selected'; } ?> >11</option>
								                <option value="12" <?php if( date( 'm', time() ) == '12' ) { echo 'selected'; } ?> >12</option>
											</select>
										</div>
									</div>
									<div class="col-xl-3 col-sm-3">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Exp Year</strong></label>
											<select id="year" name="year" class="form-control">
												<option value="21">2021</option>
												<option value="22">2022</option>
												<option value="23">2023</option>
												<option value="24">2024</option>
												<option value="25">2025</option>
												<option value="26">2026</option>
												<option value="27">2027</option>
												<option value="28">2028</option>
												<option value="29">2029</option>
												<option value="30">2030</option>
											</select>
										</div>
									</div>
								    <div class="col-xl-3 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>CVC</strong></label>
											<input type="text" id="cvc" name="cvc" class="form-control" maxlength="4" required>
										</div>
									</div>
									<div class="col-xl-12 col-sm-12 text-center">
										<div class="form-group">
											<!-- <label class="bmd-label-floating"><strong><font color="white">.</font></strong></label> -->
								   			<input type="submit" name="pay_now" value="Submit" id="submit-btn" class="btn btn-xs btn-primary" onclick="stripePay( event );">
								   		</div>
								    </div>
								</div>
							</form>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>

			<!-- refund modal -->
			<div class="modal fade" id="refund_modal" tabindex="-1" role="dialog" aria-labelledby="refund_modal" aria-hidden="true">
			   	<div class="modal-dialog">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Refund</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									Coming soon.
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>

			<code class="code-container"></code>
			<div id="output"></div>
		<?php } ?>

		<?php function orders() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// get data
				if( $admin_check || $staff_check ) {
					$orders = get_orders();
				} else {
					$orders = get_orders_for_coverage_area();
					$my_orders = get_orders_for_me();
				}
				
				$customers = get_users( 'customer' );
				$florists = get_users_summary( 'florist' );
				$delivery_details = get_delivery_details();
				$products = get_products_to_order();
			?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Orders</li>
				</ol>
				
				<h1 class="page-header">Orders</h1>

				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<?php if( $dev_check ) { ?>
					<div class="row">
						<div class="col-xl-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-xl-8 col-xs-12">
										</div>
										<div class="col-xl-4 col-xs-12 text-right">
											<div class="btn-group">
												<a class="btn btn-xs btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev Output</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if( !isset( $orders[0]['id'] ) && !isset( $my_orders[0]['id'] ) ) { ?>
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Orders</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#order_add">Add</button>
							</div>
						</div>
						<div class="panel-body">
							<center>
								<h3>
									No orders found.
								</h2>
							</center>
						</div>
					</div>
				<?php } ?>

				<!-- orders -->
				<?php if( $admin_check || $staff_check || isset( $orders[0]['id'] ) ) { ?>
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Orders</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#order_add">Add</button>
							</div>
						</div>
						<div class="panel-body">
							<table id="table_orders" class="table table-striped table-bordered table-td-valign-middle">
								<thead>
									<tr>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
										<?php if( $admin_check || $staff_check ) { ?>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Florist</strong></th>
										<?php } ?>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Order Date</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Delivery Date</strong></th>
										<th class="text-nowrap" data-orderable="false" width=""><strong>Sender</strong></th>
										<th class="text-nowrap" data-orderable="false" width=""><strong>Receiver</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Payment</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Progress</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// build table
										foreach( $orders as $order ) {
											// check if order is in florists coverage area
											if( !empty( $order['delivery_id'] ) ) { // delivery_id is set, continue
												
											}

											// status
											$order['status_raw'] = $order['status'];
											if( $order['status'] == 'pending' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">Pending</button>';
											} elseif( $order['status'] == 'new_order' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">New Order</button>';
											} elseif( $order['status'] == 'being_built' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">Being Built</button>';
											} elseif( $order['status'] == 'out_for_delivery' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">Out for Delivery</button>';
											} elseif( $order['status'] == 'complete' ) {
												$order['status'] = '<button class="btn btn-xs btn-lime btn-block">Complete</button>';
											} elseif( $order['status'] == 'delivery_failed' ) {
												$order['status'] = '<button class="btn btn-xs btn-danger btn-block">Delivery Failed</button>';
											}

											// payment status
											$order['payment_statys_raw'] = $order['payment_status'];
											if( $order['payment_status'] == 'pending' ) {
												$order['payment_status'] = '<button class="btn btn-info btn-block">Pending</button>';
											} elseif( $order['payment_status'] == 'unpaid' ) {
												$order['payment_status'] = '<button class="btn btn-xs btn-warning btn-block">Unpaid</button>';
											} elseif( $order['payment_status'] == 'declined' ) {
												$order['payment_status'] = '<button class="btn btn-xs btn-danger btn-block">Declined</button>';
											} elseif( $order['payment_status'] == 'paid' ) {
												$order['payment_status'] = '<button class="btn btn-xs btn-lime btn-block">Paid</button>';
											}

											// find customer
											foreach( $customers as $customer ) {
												if( $customer['id'] == $order['customer_id'] ) {
													break;
												}
											}

											// find delivery details
											foreach( $delivery_details as $delivery_detail ) {
												if( $delivery_detail['id'] == $order['delivery_id'] ) {
													break;
												}
											}

											// finder ordering florist details
											foreach( $florists as $ordering_florist ) {
												if( $ordering_florist['id'] == $order['ordering_florist_id'] ) {
													break;
												}
											}

											// finder destination florist details
											if( empty( $order['destination_florist_id'] ) ) {
												$destination_florist['id'] = '';
												$destination_florist['company_name'] = 'Not Assigned Yet';
											} else {
												foreach( $florists as $destination_florist ) {
													if( $destination_florist['id'] == $order['destination_florist_id'] ) {
														break;
													}
												}
											}

											// order css styling
											$order_css = '';
											$now = time();
											$order_age = ( $now - $order['added'] );
											if( $order['accepted'] == 'no' && $order_age > $globals['order_age_warning'] ) {
												$order_css = 'order_overdue_css';
											}
											if( $order['status_raw'] == 'out_for_delivery' ) {
												$order_css = 'order_out_for_delivery_css';
											}
											if( $order['status_raw'] == 'complete' ) {
												$order_css = 'order_complete_css';
											}

											// output
											echo '
												<tr class="'.$order_css.'">
													<td class="text-nowrap">
														<a href="?c=order&id='.$order['id'].'">'.$order['id'].'</a>
													</td>
													'.( $admin_check || $staff_check ? '
														<td class="text-nowrap">
															<a href="?c=user&id='.$ordering_florist['id'].'">'.$ordering_florist['company_name'].'</a> > <a href="?c=user&id='.$destination_florist['id'].'">'.$destination_florist['company_name'].'</a>
														</td>
													' : '' ).'
													<td class="text-nowrap">
														'.date( 'Y-m-d', $order['added'] ).'
													</td>
													<td class="text-nowrap">
														'.( $order['delivery_date'] != '' ? $order['delivery_date'] : 'Not Set' ).'
													</td>
													<td class="text-nowrap">
														'.$customer['full_name'].' <br>
														'.$customer['address_city'].', '.$customer['address_state'].', '.$customer['address_country'].'
													</td>
													<td class="text-nowrap">
														'.( $order['delivery_id'] != '' ? 
															$delivery_detail['full_name'].'<br>'.$delivery_detail['address_city'].', '.$delivery_detail['address_state'].', '.$delivery_detail['address_country'] : 
														'Not Set' ).'
													</td>
													<td class="text-nowrap">
														'.$order['payment_status'].'
													</td>
													<td class="text-nowrap">
														'.$order['status'].'
													</td>
													<td class="text-nowrap">
														<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
														<div class="dropdown-menu dropdown-menu-right" role="menu">
															<!-- <a href="#order_summary_'.$order['id'].'" data-toggle="modal" data-target="#order_summary_'.$order['id'].'" class="dropdown-item">Order Summary</a> -->
															<a href="?c=order&id='.$order['id'].'" class="dropdown-item">View Full Order</a>
															'.( $admin_check || $staff_check || $order['status'] == 'pending' && $order['ordering_florist_id'] == $account_details['id'] ? '<a href="#" onclick="order_delete( '.$order['id'].' )" class="dropdown-item">Delete</a>' : '' ).'
														</div>
													</td>
												</tr>
											';
										}
									?>
								</tbody>
							</table>
							<div class="row">
								<div class="col-xl-12">
									<p><font color="red"><strong>*</strong></font> Orders that are flashing red have been pending for at least 3 hours. Please accept orders if you can fulfill them.</p>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- accepted orders -->
				<?php if( $admin_check || $staff_check ) { ?>

				<?php } elseif( isset( $my_orders[0]['id'] ) ) { ?>
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Accepted Orders</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#order_add">Add</button>
							</div>
						</div>
						<div class="panel-body">
							<table id="table_accepted_orders" class="table table-striped table-bordered table-td-valign-middle">
								<thead>
									<tr>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Delivery Date</strong></th>
										<th class="text-nowrap" data-orderable="false" width=""><strong>Sender</strong></th>
										<th class="text-nowrap" data-orderable="false" width=""><strong>Receiver</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Progress</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// build table
										foreach( $my_orders as $order ) {
											// check if order is in florists coverage area
											if( !empty( $order['delivery_id'] ) ) { // delivery_id is set, continue
												
											}

											// status
											$order['status_raw'] = $order['status'];
											if( $order['status'] == 'pending' ) {
												$order['status'] = '<button class="btn btn-info btn-block">Pending</button>';
											} elseif( $order['status'] == 'new_order' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">New Order</button>';
											} elseif( $order['status'] == 'being_built' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">Being Built</button>';
											} elseif( $order['status'] == 'out_for_delivery' ) {
												$order['status'] = '<button class="btn btn-xs btn-info btn-block">Out for Delivery</button>';
											} elseif( $order['status'] == 'complete' ) {
												$order['status'] = '<button class="btn btn-xs btn-lime btn-block">Complete</button>';
											} elseif( $order['status'] == 'delivery_failed' ) {
												$order['status'] = '<button class="btn btn-xs btn-danger btn-block">Delivery Failed</button>';
											}

											// payment status
											$order['payment_statys_raw'] = $order['payment_status'];
											if( $order['payment_status'] == 'pending' ) {
												$order['payment_status'] = '<button class="btn btn-info btn-block">Pending</button>';
											} elseif( $order['payment_status'] == 'unpaid' ) {
												$order['payment_status'] = '<button class="btn btn-xs btn-warning btn-block">Unpaid</button>';
											} elseif( $order['payment_status'] == 'declined' ) {
												$order['payment_status'] = '<button class="btn btn-xs btn-danger btn-block">Declined</button>';
											} elseif( $order['payment_status'] == 'paid' ) {
												$order['payment_status'] = '<button class="btn btn-xs btn-lime btn-block">Paid</button>';
											}

											// find customer
											foreach( $customers as $customer ) {
												if( $customer['id'] == $order['customer_id'] ) {
													break;
												}
											}

											// find delivery details
											foreach( $delivery_details as $delivery_detail ) {
												if( $delivery_detail['id'] == $order['delivery_id'] ) {
													break;
												}
											}

											// finder ordering florist details
											foreach( $florists as $ordering_florist ) {
												if( $ordering_florist['id'] == $order['ordering_florist_id'] ) {
													break;
												}
											}

											// finder destination florist details
											if( empty( $order['destination_florist_id'] ) ) {
												$destination_florist['id'] = '';
												$destination_florist['company_name'] = 'Not Assigned Yet';
											} else {
												foreach( $florists as $destination_florist ) {
													if( $destination_florist['id'] == $order['destination_florist_id'] ) {
														break;
													}
												}
											}

											// order css styling
											$order_css = '';
											$now = time();
											$order_age = ( $now - $order['added'] );
											if( $order['accepted'] == 'no' && $order_age > $globals['order_age_warning'] ) {
												$order_css = 'order_overdue_css';
											}
											if( $order['fallback_order'] == 'yes' ) {
												$order_css = 'order_fallback_css';
											}
											if( $order['status_raw'] == 'out_for_delivery' ) {
												$order_css = 'order_out_for_delivery_css';
											}
											if( $order['status_raw'] == 'complete' ) {
												$order_css = 'order_complete_css';
											}

											// output
											echo '
												<tr class="'.$order_css.'">
													<td class="text-nowrap">
														<a href="?c=order&id='.$order['id'].'">'.$order['id'].'</a>
													</td>
													<td class="text-nowrap">
														'.( $order['delivery_date'] != '' ? $order['delivery_date'] : 'Not Set' ).'
													</td>
													<td class="text-nowrap">
														'.$customer['full_name'].' <br>
														'.$customer['address_city'].', '.$customer['address_state'].', '.$customer['address_country'].'
													</td>
													<td class="text-nowrap">
														'.( $order['delivery_id'] != '' ? 
															$delivery_detail['full_name'].'<br>'.$delivery_detail['address_city'].', '.$delivery_detail['address_state'].', '.$delivery_detail['address_country'] : 
														'Not Set' ).'
													</td>
													<td class="text-nowrap">
														'.$order['status'].'
													</td>
													<td class="text-nowrap">
														<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
														<div class="dropdown-menu dropdown-menu-right" role="menu">
															<a href="?c=order&id='.$order['id'].'" class="dropdown-item">View Full Order</a>
														</div>
													</td>
												</tr>
											';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				<?php } ?>
			</div>

			<!-- create order summary modals -->

			<?php 
				/*
				foreach( $orders as $order) {
					// find customer
					foreach( $customers as $customer ) {
						if( $customer['id'] == $order['customer_id'] ) {
							break;
						}
					}

					// find delivery details
					foreach( $delivery_details as $delivery_detail ) {
						if( $delivery_detail['id'] == $order['delivery_id'] ) {
							break;
						}
					}

					// get ordered products
					$order['order_items'] = get_order_items( $order['id'] );
					$items = '';
					foreach( $order['order_items'] as $order_item ) {
						// match item to product
						foreach( $products as $product ) {
							if( $product['id'] == $order_item['product_id'] ) {
								$items .= $order_item['qty'].' x '.$product['title'].'<br>';
							}
						}
					}

					// calculate network fee
					$network_fee = calculate_network_fee( $order['destination_florist_id'], $order['total_price'] );
					$florist_profit = ( $order['total_price'] - $network_fee );

					// order summary
					echo '
						<div class="modal fade" id="order_summary_'.$order['id'].'" tabindex="-1" role="dialog" aria-labelledby="order_summary_'.$order['id'].'" aria-hidden="true">
						   	<div class="modal-dialog modal-xl">
							  	<div class="modal-content">
								 	<div class="modal-header">
										<h5 class="modal-title" id="order_summary_'.$order['id'].'">Order Summary</h5>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
											x
										</button>
								 	</div>
								 	<div class="modal-body">
								 		<div class="row">
											<div class="col-xl-4 col-sm-12">
												<p class="lead">
													Sender Details
												</p>
												'.$customer['full_name'].' <br>
												'.$customer['address_1'].' <br>
												'.( !empty( $customer['address_2'] ) ? $customer['address_2'].' <br>' : '' ).'
												'.$customer['address_city'].', '.$customer['address_state'].' <br>
												'.$customer['address_zip'].', '.code_to_country( $customer['address_country'] ).' <br>
												'.( !empty( $customer['tel_landline'] ) ? 'Phone: '.$customer['tel_landline'].' <br>' : '' ).'
												'.( !empty( $customer['tel_cell'] ) ? 'Cell: '.$customer['tel_cell'].' <br>' : '' ).'
											</div>
											<div class="col-xl-4 col-sm-12">
												<p class="lead">
													Receiver Details
												</p>
												'.$delivery_detail['full_name'].' <br>
												'.$delivery_detail['address_1'].' <br>
												'.( !empty( $delivery_detail['address_2'] ) ? $delivery_detail['address_2'].' <br>' : '' ).'
												'.$delivery_detail['address_city'].', '.$delivery_detail['address_state'].' <br>
												'.$delivery_detail['address_zip'].', '.code_to_country( $delivery_detail['address_country'] ).' <br>
												'.( !empty( $delivery_detail['tel_landline'] ) ? 'Phone: '.$delivery_detail['tel_landline'].' <br>' : '' ).'
												'.( !empty( $delivery_detail['tel_cell'] ) ? 'Cell: '.$delivery_detail['tel_cell'].' <br>' : '' ).'
											</div>
											<div class="col-xl-4 col-sm-12">
												<p class="lead">
													Order Details
												</p>
												<strong>Order Date:</strong> '.date( "Y-m-d", $order['added'] ).' <br>
												<strong>Deliver Date:</strong> '.$order['delivery_date'].' <br>
												<strong>Order Total:</strong> $'.number_format( $order['total_price'], 2 ).' <br>
												<!--
													<strong>Network Fee:</strong> $'.$network_fee.' ('.$account_details['subscription']['network_percentage'].'%)<br>
													<strong>Florist Gets:</strong> $'.$florist_profit.'
												-->
											</div>
										</div>
										<br><br>
										<div class="row">
											<div class="col-xl-12 col-sm-12">
												<p class="lead">
													Items
												</p>
												'.$items.'
											</div>
										</div>
								 	</div>
								 	<div class="modal-footer">
								 		<div class="btn-group">
											<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
											'.( $order['status'] == 'new_order' ? '<a href="#" onclick="order_accept( '.$order['id'].' )" class="btn btn-xs btn-lime">Accept Order</a>' : '' ).'
											<a href="?c=order&id='.$order['id'].'" class="btn btn-xs btn-primary">View Order</a>
										</div>
									</div>
							  	</div>
						   	</div>
						</div>
					';
				}
				*/
			?>

			<!-- add order modal -->
			<form class="form" method="post" action="actions.php?a=order_add">
				<div class="modal fade" id="order_add" tabindex="-1" role="dialog" aria-labelledby="order_add" aria-hidden="true">
				   	<div class="modal-dialog modal-notice">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Add Order</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
						 		<div class="row">
						 			<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Customer</strong></label>
											<select name="customer_id" class="form-control select2" onchange="new_customer( this.value )">
												<option value="new_customer">New Customer</option>
												<option disabled="disabled">----- or -----</option>
												<?php foreach( $customers as $customer ) { ?>
													<?php if( $customer['type'] == 'customer' ) { ?>
														<option value="<?php echo $customer['id']; ?>"><?php echo $customer['full_name'].' ( '.$customer['email'].' )'; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div id="new_customer" class="row">
									<div class="col-xl-12 col-sm-12">
										<hr>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>First Name</strong></label>
											<input type="text" id="first_name" name="first_name" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Last Name</strong></label>
											<input type="text" id="last_name" name="last_name" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Email</strong></label>
											<input type="email" id="email" name="email" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Password</strong></label>
											<input type="text" id="password" name="password" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Address 1</strong></label>
											<input type="text" id="address_1" name="address_1" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Address 2</strong></label>
											<input type="text" id="address_2" name="address_2" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>City</strong></label>
											<input type="text" id="address_city" name="address_city" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>State / County</strong></label>
											<input type="text" id="address_state" name="address_state" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Country</strong></label>
											<select name="address_country" class="form-control select2">
												<option value="AF">Afghanistan</option>
												<option value="AX">Åland Islands</option>
												<option value="AL">Albania</option>
												<option value="DZ">Algeria</option>
												<option value="AS">American Samoa</option>
												<option value="AD">Andorra</option>
												<option value="AO">Angola</option>
												<option value="AI">Anguilla</option>
												<option value="AQ">Antarctica</option>
												<option value="AG">Antigua and Barbuda</option>
												<option value="AR">Argentina</option>
												<option value="AM">Armenia</option>
												<option value="AW">Aruba</option>
												<option value="AU">Australia</option>
												<option value="AT">Austria</option>
												<option value="AZ">Azerbaijan</option>
												<option value="BS">Bahamas</option>
												<option value="BH">Bahrain</option>
												<option value="BD">Bangladesh</option>
												<option value="BB">Barbados</option>
												<option value="BY">Belarus</option>
												<option value="BE">Belgium</option>
												<option value="BZ">Belize</option>
												<option value="BJ">Benin</option>
												<option value="BM">Bermuda</option>
												<option value="BT">Bhutan</option>
												<option value="BO">Bolivia, Plurinational State of</option>
												<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
												<option value="BA">Bosnia and Herzegovina</option>
												<option value="BW">Botswana</option>
												<option value="BV">Bouvet Island</option>
												<option value="BR">Brazil</option>
												<option value="IO">British Indian Ocean Territory</option>
												<option value="BN">Brunei Darussalam</option>
												<option value="BG">Bulgaria</option>
												<option value="BF">Burkina Faso</option>
												<option value="BI">Burundi</option>
												<option value="KH">Cambodia</option>
												<option value="CM">Cameroon</option>
												<option value="CA">Canada</option>
												<option value="CV">Cape Verde</option>
												<option value="KY">Cayman Islands</option>
												<option value="CF">Central African Republic</option>
												<option value="TD">Chad</option>
												<option value="CL">Chile</option>
												<option value="CN">China</option>
												<option value="CX">Christmas Island</option>
												<option value="CC">Cocos (Keeling) Islands</option>
												<option value="CO">Colombia</option>
												<option value="KM">Comoros</option>
												<option value="CG">Congo</option>
												<option value="CD">Congo, the Democratic Republic of the</option>
												<option value="CK">Cook Islands</option>
												<option value="CR">Costa Rica</option>
												<option value="CI">Côte d'Ivoire</option>
												<option value="HR">Croatia</option>
												<option value="CU">Cuba</option>
												<option value="CW">Curaçao</option>
												<option value="CY">Cyprus</option>
												<option value="CZ">Czech Republic</option>
												<option value="DK">Denmark</option>
												<option value="DJ">Djibouti</option>
												<option value="DM">Dominica</option>
												<option value="DO">Dominican Republic</option>
												<option value="EC">Ecuador</option>
												<option value="EG">Egypt</option>
												<option value="SV">El Salvador</option>
												<option value="GQ">Equatorial Guinea</option>
												<option value="ER">Eritrea</option>
												<option value="EE">Estonia</option>
												<option value="ET">Ethiopia</option>
												<option value="FK">Falkland Islands (Malvinas)</option>
												<option value="FO">Faroe Islands</option>
												<option value="FJ">Fiji</option>
												<option value="FI">Finland</option>
												<option value="FR">France</option>
												<option value="GF">French Guiana</option>
												<option value="PF">French Polynesia</option>
												<option value="TF">French Southern Territories</option>
												<option value="GA">Gabon</option>
												<option value="GM">Gambia</option>
												<option value="GE">Georgia</option>
												<option value="DE">Germany</option>
												<option value="GH">Ghana</option>
												<option value="GI">Gibraltar</option>
												<option value="GR">Greece</option>
												<option value="GL">Greenland</option>
												<option value="GD">Grenada</option>
												<option value="GP">Guadeloupe</option>
												<option value="GU">Guam</option>
												<option value="GT">Guatemala</option>
												<option value="GG">Guernsey</option>
												<option value="GN">Guinea</option>
												<option value="GW">Guinea-Bissau</option>
												<option value="GY">Guyana</option>
												<option value="HT">Haiti</option>
												<option value="HM">Heard Island and McDonald Islands</option>
												<option value="VA">Holy See (Vatican City State)</option>
												<option value="HN">Honduras</option>
												<option value="HK">Hong Kong</option>
												<option value="HU">Hungary</option>
												<option value="IS">Iceland</option>
												<option value="IN">India</option>
												<option value="ID">Indonesia</option>
												<option value="IR">Iran, Islamic Republic of</option>
												<option value="IQ">Iraq</option>
												<option value="IE">Ireland</option>
												<option value="IM">Isle of Man</option>
												<option value="IL">Israel</option>
												<option value="IT">Italy</option>
												<option value="JM">Jamaica</option>
												<option value="JP">Japan</option>
												<option value="JE">Jersey</option>
												<option value="JO">Jordan</option>
												<option value="KZ">Kazakhstan</option>
												<option value="KE">Kenya</option>
												<option value="KI">Kiribati</option>
												<option value="KP">Korea, Democratic People's Republic of</option>
												<option value="KR">Korea, Republic of</option>
												<option value="KW">Kuwait</option>
												<option value="KG">Kyrgyzstan</option>
												<option value="LA">Lao People's Democratic Republic</option>
												<option value="LV">Latvia</option>
												<option value="LB">Lebanon</option>
												<option value="LS">Lesotho</option>
												<option value="LR">Liberia</option>
												<option value="LY">Libya</option>
												<option value="LI">Liechtenstein</option>
												<option value="LT">Lithuania</option>
												<option value="LU">Luxembourg</option>
												<option value="MO">Macao</option>
												<option value="MK">Macedonia, the former Yugoslav Republic of</option>
												<option value="MG">Madagascar</option>
												<option value="MW">Malawi</option>
												<option value="MY">Malaysia</option>
												<option value="MV">Maldives</option>
												<option value="ML">Mali</option>
												<option value="MT">Malta</option>
												<option value="MH">Marshall Islands</option>
												<option value="MQ">Martinique</option>
												<option value="MR">Mauritania</option>
												<option value="MU">Mauritius</option>
												<option value="YT">Mayotte</option>
												<option value="MX">Mexico</option>
												<option value="FM">Micronesia, Federated States of</option>
												<option value="MD">Moldova, Republic of</option>
												<option value="MC">Monaco</option>
												<option value="MN">Mongolia</option>
												<option value="ME">Montenegro</option>
												<option value="MS">Montserrat</option>
												<option value="MA">Morocco</option>
												<option value="MZ">Mozambique</option>
												<option value="MM">Myanmar</option>
												<option value="NA">Namibia</option>
												<option value="NR">Nauru</option>
												<option value="NP">Nepal</option>
												<option value="NL">Netherlands</option>
												<option value="NC">New Caledonia</option>
												<option value="NZ">New Zealand</option>
												<option value="NI">Nicaragua</option>
												<option value="NE">Niger</option>
												<option value="NG">Nigeria</option>
												<option value="NU">Niue</option>
												<option value="NF">Norfolk Island</option>
												<option value="MP">Northern Mariana Islands</option>
												<option value="NO">Norway</option>
												<option value="OM">Oman</option>
												<option value="PK">Pakistan</option>
												<option value="PW">Palau</option>
												<option value="PS">Palestinian Territory, Occupied</option>
												<option value="PA">Panama</option>
												<option value="PG">Papua New Guinea</option>
												<option value="PY">Paraguay</option>
												<option value="PE">Peru</option>
												<option value="PH">Philippines</option>
												<option value="PN">Pitcairn</option>
												<option value="PL">Poland</option>
												<option value="PT">Portugal</option>
												<option value="PR">Puerto Rico</option>
												<option value="QA">Qatar</option>
												<option value="RE">Réunion</option>
												<option value="RO">Romania</option>
												<option value="RU">Russian Federation</option>
												<option value="RW">Rwanda</option>
												<option value="BL">Saint Barthélemy</option>
												<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
												<option value="KN">Saint Kitts and Nevis</option>
												<option value="LC">Saint Lucia</option>
												<option value="MF">Saint Martin (French part)</option>
												<option value="PM">Saint Pierre and Miquelon</option>
												<option value="VC">Saint Vincent and the Grenadines</option>
												<option value="WS">Samoa</option>
												<option value="SM">San Marino</option>
												<option value="ST">Sao Tome and Principe</option>
												<option value="SA">Saudi Arabia</option>
												<option value="SN">Senegal</option>
												<option value="RS">Serbia</option>
												<option value="SC">Seychelles</option>
												<option value="SL">Sierra Leone</option>
												<option value="SG">Singapore</option>
												<option value="SX">Sint Maarten (Dutch part)</option>
												<option value="SK">Slovakia</option>
												<option value="SI">Slovenia</option>
												<option value="SB">Solomon Islands</option>
												<option value="SO">Somalia</option>
												<option value="ZA">South Africa</option>
												<option value="GS">South Georgia and the South Sandwich Islands</option>
												<option value="SS">South Sudan</option>
												<option value="ES">Spain</option>
												<option value="LK">Sri Lanka</option>
												<option value="SD">Sudan</option>
												<option value="SR">Suriname</option>
												<option value="SJ">Svalbard and Jan Mayen</option>
												<option value="SZ">Swaziland</option>
												<option value="SE">Sweden</option>
												<option value="CH">Switzerland</option>
												<option value="SY">Syrian Arab Republic</option>
												<option value="TW">Taiwan, Province of China</option>
												<option value="TJ">Tajikistan</option>
												<option value="TZ">Tanzania, United Republic of</option>
												<option value="TH">Thailand</option>
												<option value="TL">Timor-Leste</option>
												<option value="TG">Togo</option>
												<option value="TK">Tokelau</option>
												<option value="TO">Tonga</option>
												<option value="TT">Trinidad and Tobago</option>
												<option value="TN">Tunisia</option>
												<option value="TR">Turkey</option>
												<option value="TM">Turkmenistan</option>
												<option value="TC">Turks and Caicos Islands</option>
												<option value="TV">Tuvalu</option>
												<option value="UG">Uganda</option>
												<option value="UA">Ukraine</option>
												<option value="AE">United Arab Emirates</option>
												<option value="GB">United Kingdom</option>
												<option value="US">United States</option>
												<option value="UM">United States Minor Outlying Islands</option>
												<option value="UY">Uruguay</option>
												<option value="UZ">Uzbekistan</option>
												<option value="VU">Vanuatu</option>
												<option value="VE">Venezuela, Bolivarian Republic of</option>
												<option value="VN">Viet Nam</option>
												<option value="VG">Virgin Islands, British</option>
												<option value="VI">Virgin Islands, U.S.</option>
												<option value="WF">Wallis and Futuna</option>
												<option value="EH">Western Sahara</option>
												<option value="YE">Yemen</option>
												<option value="ZM">Zambia</option>
												<option value="ZW">Zimbabwe</option>
											</select>
										</div>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Continue</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			</form>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $orders ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>

		<?php function product() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php 
				// get data
				$product_id = get( 'id' );
				$product = get_product( $product_id );
				$product_categories = get_product_categories();

				// set readonly car
				if( $admin_check || $staff_check ) {
					$readonly = '';
				} else {
					$readonly = 'readonly';
				}
			?>

			<div id="content" class="content">
				<!-- sanity check -->
				<?php if( !isset( $product['id'] ) ) { ?>
					<?php echo $not_found; ?>
				<?php } else { ?>
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php?c=products">Products</a></li>
						<li class="breadcrumb-item active">Product: <?php echo $product['title'] ?></li>
					</ol>

					<h1 class="page-header">Product: <?php echo $product['title']; ?></h1>

					<div class="row">
						<div class="col-xl-12">
							<div id="status_message"></div><div id="kyc_status_message"></div>
						</div>
					</div>

					<form class="form" method="post" action="actions.php?a=product_edit">
						<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
					
						<!-- form controls -->
						<div class="row">
							<div class="col-xl-12">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-8 col-xs-12">
											</div>
											<div class="col-xl-4 col-xs-12 text-right">
												<div class="btn-group">
													<a href="?c=products" type="button" class="btn btn-xs btn-white">Back</a>
													<?php if( $admin_check || $staff_check ) { ?>
														<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-6 col-sm-12">
								<!-- product details -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Product Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Status</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<select name="status" class="form-control">
															<option value="active" <?php if( $product['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
															<option value="inactive" <?php if( $product['status'] == 'inactive' ) { echo 'selected'; } ?> >Inactive</option>
														</select>
													<?php } else { ?>
														<br>
														<?php echo ucfirst( $product['status'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Published</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<select name="published" class="form-control">
															<option value="yes" <?php if( $product['published'] == 'yes' ) { echo 'selected'; } ?> >Yes</option>
															<option value="no" <?php if( $product['published'] == 'no' ) { echo 'selected'; } ?> >No</option>
														</select>
														<small>Is this product published live on all platforms.</small>
													<?php } else { ?>
														<br>
														<?php echo ucfirst( $product['published'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Star Rating</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<select name="star_rating" class="form-control">
															<option value="1" <?php if( $product['star_rating'] == '1' ) { echo 'selected'; } ?> >1</option>
															<option value="2" <?php if( $product['star_rating'] == '2' ) { echo 'selected'; } ?> >2</option>
															<option value="3" <?php if( $product['star_rating'] == '3' ) { echo 'selected'; } ?> >3</option>
															<option value="4" <?php if( $product['star_rating'] == '4' ) { echo 'selected'; } ?> >4</option>
															<option value="5" <?php if( $product['star_rating'] == '5' ) { echo 'selected'; } ?> >5</option>
														</select>
														<small>Set a product star rating that customers can see.</small>
													<?php } else { ?>
														<br>
														<?php 
															for ($x = 0; $x <= $product['star_rating']; $x++) {
																echo '<img src="images/gold_star.png" width="15px" alt=""> ';
															} 
														?>
													<?php } ?>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-6 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Title</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<input type="text" name="title" class="form-control" value="<?php echo $product['title']; ?>" required>
													<?php } else { ?>
														<br>
														<?php echo ucfirst( $product['title'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-6 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Preview Text</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<input type="text" name="preview_text" class="form-control" value="<?php echo $product['preview_text']; ?>" required>
													<?php } else { ?>
														<br>
														<?php echo ucfirst( $product['title'] ); ?>
													<?php } ?>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Price (small)</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<div class="input-group">
															<span class="input-group-addon">$</span>
															<input type="text" name="price_s" class="form-control" placeholder="29.99" value="<?php echo $product['price_s']; ?>" required>
														</div>
													<?php } else { ?>
														<br>
														$<?php echo ucfirst( $product['price_s'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Price (medium)</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<div class="input-group">
															<span class="input-group-addon">$</span>
															<input type="text" name="price_m" class="form-control" placeholder="29.99" value="<?php echo $product['price_m']; ?>" required>
														</div>
													<?php } else { ?>
														<br>
														$<?php echo ucfirst( $product['price_m'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Price (large)</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<div class="input-group">
															<span class="input-group-addon">$</span>
															<input type="text" name="price_l" class="form-control" placeholder="29.99" value="<?php echo $product['price_l']; ?>" required>
														</div>
													<?php } else { ?>
														<br>
														$<?php echo ucfirst( $product['price_l'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Free Delivery</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<select name="free_delivery" class="form-control">
															<option value="yes" <?php if( $product['free_delivery'] == 'yes' ) { echo 'selected'; } ?> >Yes</option>
															<option value="no" <?php if( $product['free_delivery'] == 'no' ) { echo 'selected'; } ?> >No</option>
														</select>
													<?php } else { ?>
														<br>
														<?php echo ucfirst( $product['free_delivery'] ); ?>
													<?php } ?>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Free Vase</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<select name="free_vase" class="form-control">
															<option value="yes" <?php if( $product['free_vase'] == 'yes' ) { echo 'selected'; } ?> >Yes</option>
															<option value="no" <?php if( $product['free_vase'] == 'no' ) { echo 'selected'; } ?> >No</option>
														</select>
													<?php } else { ?>
														<br>
														<?php echo ucfirst( $product['free_vase'] ); ?>
													<?php } ?>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xl-12 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Description</strong></label>
													<?php if( $admin_check || $staff_check ) { ?>
														<textarea id="editor1" name="description" class="ckeditor"  rows="30"><?php echo $product['description']; ?></textarea>
													<?php } else { ?>
														<hr>
														<?php echo $product['description']; ?>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- categories -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Categories</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<?php if( $admin_check || $staff_check ) { ?>
												<?php foreach( $product_categories as $product_category ) { ?>
													<div class="col-xl-12 col-sm-12">
														<input type="checkbox" name="categories[]" value="<?php echo $product_category['id']; ?>" <?php if( is_array( $product['categories'] ) && in_array( $product_category['id'], $product['categories'] ) ) { echo 'checked'; } ?> > <?php echo $product_category['name']; ?>
													</div>	
												<?php } ?>
											<?php } else { ?>
												<ul>
													<?php foreach( $product_categories as $product_category ) { ?>
														<?php if( is_array( $product['categories'] ) && in_array( $product_category['id'], $product['categories'] ) ) { ?>
															<li><?php echo $product_category['name']; ?></li>
														<?php } ?>
													<?php } ?>
												</ul>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</form>

							<div class="col-xl-6 col-sm-12">
								<!-- photos -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Product Images</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<?php if( $admin_check || $staff_check ) { ?>
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<form name="upload_form" id="upload_form" enctype="multipart/form-data" method="post">
								                    	<input type="hidden" name="product" id="product_id" value="<?php echo $product_id; ?>">

							                            <div class="row">
							                            	<div class="col-lg-12 col-xs-12">
							                                    <div class="form-group">
							                                        <div class="input-group">
							                                            <span class="input-group-btn">
							                                                <span class="btn btn-file">
							                                                    Browse&hellip; <input type="file" name="file1" id="file1"> <input type="button" class="btn btn-xs btn-primary" value="Upload File" onclick="uploadFile()">
							                                                </span>
							                                            </span>
							                                            <input type="text" class="form-control hidden" readonly>
							                                        </div>
							                                    </div>
							                                </div>
							                            </div>
							                        	<div class="row">
							                                <div class="col-lg-12 col-xs-12">
							                                    <center>
							                                        <progress id="progressBar" value="0" max="100" style="width:100%;"></progress>
							                                        <span id="loaded_n_total"></span> <span id="status"></span>
							                                    </center>
							                                </div>
							                            </div>
							                        </form>
												</div>
											</div>

											<hr>
										<?php } ?>

										<div class="row">
											<div class="col-xl-12 col-sm-12">
												<?php if( $admin_check || $staff_check ) { ?>
													<table id="table_product_images" class="table table-striped table-bordered table-td-valign-middle">
														<thead>
															<tr>
																<th class="text-nowrap" data-orderable="false" width=""><strong>Image</strong></th>
																<th class="text-nowrap" data-orderable="false" width="1px"><strong>Primary</strong></th>
																<th class="text-nowrap" data-orderable="false" width="1px"></th>
															</tr>
														</thead>
														<tbody>
															<?php if( is_array( $product['images'] ) ) { ?>
																<?php
																	// build table
																	foreach( $product['images'] as $image ) {
																		// primary status
																		if( $image['primary'] == 'yes' ) {
																			$image['primary'] = '<button class="btn btn-xs btn-lime btn-block">Yes</button>';
																		} else {
																			$image['primary'] = '<button class="btn btn-xs btn-white btn-block">No</button>';
																		}

																		// output
																		echo '
																			<tr>
																				<td class="text-nowrap">
																					<img src="product_images/'.$image['file_name'].'" alt="" width="256px">
																				</td>
																				<td class="text-nowrap">
																					'.$image['primary'].'
																				</td>
																				<td class="text-nowrap">
																					<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
																					<div class="dropdown-menu dropdown-menu-right" role="menu">
																						<a href="actions.php?a=product_image_make_primary&id='.$image['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\' )">Make Primary</a>
																						<a href="actions.php?a=product_image_delete&id='.$image['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\' )">Delete</a>
																					</div>
																				</td>
																			</tr>
																		';
																	}
																?>
															<?php } ?>
														</tbody>
													</table>
												<?php } else { ?>
													<div class="row">
														<?php foreach( $product['images'] as $image ) { ?>
															<div class="col-xl-2 col-sm-12">
																<img src="product_images/<?php echo $image['file_name']; ?>" alt="" width="100%">
															</div>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
				<?php } ?>
			</div>
		<?php } ?>

		<?php function products() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// get data
				$products = get_products();
				$product_categories = get_product_categories();
			?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Products</li>
				</ol>
				
				<h1 class="page-header">Products</h1>

				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">Products</h2>
						<div class="panel-heading-btn">
							<?php if( $admin_check || $staff_check ) { ?>
								<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#product_add">Add</button>
							<?php } ?>
						</div>
					</div>
					<div class="panel-body">
						<?php if( !isset( $products[0] ) ) { ?>
							<center>
								<h3>
									No products found.
								</h2>
							</center>
						<?php } else { ?>
							<table id="table_products" class="table table-striped table-bordered table-td-valign-middle">
								<thead>
									<tr>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong></strong></th>
										<th class="text-nowrap" data-orderable="false" width=""><strong>Title</strong></th>
										<th class="text-nowrap" data-orderable="false" width=""><strong>Description</strong></th>
										<th class="" data-orderable="false" width="250px"><strong>Categories</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Price</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"><strong>Published</strong></th>
										<th class="text-nowrap" data-orderable="false" width="1px"></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// build table
										foreach( $products as $product ) {
											// published status
											$product['published_raw'] = $product['published'];
											if( $product['published'] == 'yes' ) {
												$product['published_status'] = '<button class="btn btn-xs btn-primary btn-block">Yes</button>';
											} elseif( $product['published'] == 'no' ) {
												$product['published_status'] = '<button class="btn btn-xs btn-warning btn-block">No</button>';
											}

											// find primary image
											foreach( $product['images'] as $product_image ) {
												if( $product_image['primary'] == 'yes' ) {
													break;
												}
											}

											// strip html tags from preview of $product['description']
											$product['description'] = preg_replace( '#<[^>]+>#', ' ', $product['description'] ); // " ABC  DEF "
											$product['description'] = preg_replace( '#\s+#', ' ', $product['description'] );     // " ABC DEF "

											// build product categories
											$categories = array();
											foreach( $product_categories as $product_category ) {
												if( in_array( $product_category['id'], $product['categories'] ) ) {
													$categories[] = $product_category['name'];
												}
											}
											$categories = implode( ', ', $categories );

											// output
											echo '
												<tr>
													<td class="text-nowrap">
														<a href="?c=product&id='.$product['id'].'">'.$product['id'].'</a>
													</td>
													<td class="text-nowrap">
														<img src="product_images/'.$product_image['file_name'].'" loading="lazy" class="lazyload" width="100px" alt="">
													</td>
													<td class="text-nowrap">
														'.$product['title'].' <br>
														<small>'.$product['preview_text'].'</small>
													</td>
													<td class="text-nowrap">
														'.truncate( $product['description'], 250 ).'
													</td>
													<td class="">
														'.$categories.'
													</td>
													<td class="text-nowrap">
														'.$product['price'].'
													</td>
													<td class="text-nowrap">
														'.$product['published_status'].'
													</td>
													<td class="text-nowrap">
														<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
														<div class="dropdown-menu dropdown-menu-right" role="menu">
															'.( $admin_check || $staff_check ? '<a href="?c=product&id='.$product['id'].'" class="dropdown-item">View / Edit</a>' : '<a href="?c=product&id='.$product['id'].'" class="dropdown-item">View</a>' ).'
															'.( $admin_check || $staff_check ? '<a href="actions.php?a=product_delete&id='.$product['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\' )">Delete</a>' : '' ).'
														</div>
													</td>
												</tr>
											';
										}
									?>
								</tbody>
							</table>
						<?php } ?>
					</div>
				</div>
			</div>

			<form class="form" method="post" action="actions.php?a=product_add">
				<div class="modal fade" id="product_add" tabindex="-1" role="dialog" aria-labelledby="product_add" aria-hidden="true">
				   	<div class="modal-dialog modal-notice">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Add Product</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
						 		<div class="row">
									<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Title</strong></label>
											<input type="text" id="title" name="title" class="form-control" placeholder="12 Red Rose Hand-tied" required>
										</div>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Continue</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			</form>
		<?php } ?>

		<?php function subscription() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php 
				// get data
				$subscription_id 				= get( 'id' );
				$subscription 					= get_subscription_plan( $subscription_id );
				$subscription['users'] 			= get_subscription_users( $subscription_id );
				$florists 						= get_users( 'florist' );
			?>

			<div id="content" class="content">
				<!-- sanity check -->
				<?php if( !isset( $subscription['id'] ) ) { ?>
					<?php echo $not_found; ?>
				<?php } else { ?>
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php?c=subscriptions">Subscriptions</a></li>
						<li class="breadcrumb-item active">Subscription Plan: <?php echo $subscription['name'] ?></li>
					</ol>

					<h1 class="page-header">Subscription Plan: <?php echo $subscription['name']; ?></h1>

					<div class="row">
						<div class="col-xl-12">
							<div id="status_message"></div><div id="kyc_status_message"></div>
						</div>
					</div>

					<form class="form" method="post" action="actions.php?a=subscription_edit">
						<input type="hidden" name="subscription_id" value="<?php echo $subscription['id']; ?>">
					
						<!-- form controls -->
						<div class="row">
							<div class="col-xl-12">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-8 col-xs-12">
											</div>
											<div class="col-xl-4 col-xs-12 text-right">
												<div class="btn-group">
													<a href="?c=subscriptions" type="button" class="btn btn-xs btn-white">Back</a>
													<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
												</div>
												<?php if( $dev_check ) { ?>
													<div class="btn-group">
														<a href="#" class="btn btn-xs btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- form content -->
						<div class="row">
							<div class="col-xl-6 col-sm-12">
								<!-- subscription details -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Subscription Plan Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Status</strong></label>
													<select name="status" class="form-control">
														<option value="active" <?php if( $subscription['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
														<option value="pending" <?php if( $subscription['status'] == 'pending' ) { echo 'selected'; } ?> >Pending</option>
														<option value="retired" <?php if( $subscription['status'] == 'retired' ) { echo 'selected'; } ?> >Retired</option>
														<option value="suspended" <?php if( $subscription['status'] == 'suspended' ) { echo 'selected'; } ?> >Suspended</option>
														<option value="terminated" <?php if( $subscription['status'] == 'terminated' ) { echo 'selected'; } ?> >Terminated</option>
													</select>
												</div>
											</div>

											<div class="col-xl-9 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Name</strong></label>
													<input type="text" name="name" class="form-control" value="<?php echo $subscription['name']; ?>">
													<small>Example: Basic Florist Subscription</small>
												</div>
											</div>

											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Billing Cycle</strong></label>
													<select name="cap_cycle" class="form-control">
														<option value="1_day" <?php if( $subscription['cap_cycle'] == '1_day' ) { echo 'selected'; } ?> >1 Day</option>
														<option value="1_week" <?php if( $subscription['cap_cycle'] == '1_week' ) { echo 'selected'; } ?> >1 Week</option>
														<option value="1_month" <?php if( $subscription['cap_cycle'] == '1_month' ) { echo 'selected'; } ?> >1 Month</option>
														<option value="1_year" <?php if( $subscription['cap_cycle'] == '1_year' ) { echo 'selected'; } ?> >1 Year</option>
													</select>
												</div>
											</div>

											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Cap Amount</strong></label>
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" name="cap_amount" class="form-control" value="<?php echo $subscription['cap_amount']; ?>">
													</div>
												</div>
											</div>

											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Network Percentage per Order</strong></label>
													<div class="input-group">
														<input type="text" name="network_percentage" class="form-control" value="<?php echo $subscription['network_percentage']; ?>">
														<span class="input-group-addon">%</span>
													</div>
												</div>
											</div>

											<div class="col-xl-12 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Notes</strong></label>
													<textarea name="notes" class="form-control" rows="7"><?php echo $subscription['notes']; ?></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6 col-sm-12">
								<!-- photos -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Users</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												<a href="#" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#subscription_add_user">Add</a>
											</div>
										</div>
									</div>
									<div class="panel-body">
										<table id="table_subscription_users" class="table table-striped table-bordered table-td-valign-middle">
											<thead>
												<tr>
													<th class="text-nowrap" data-orderable="false" width=""><strong>Name</strong></th>
													<th class="text-nowrap" data-orderable="false" width="1px"><strong>Email</strong></th>
													<th class="text-nowrap" data-orderable="false" width="1px"><strong>Company</strong></th>
													<th class="text-nowrap" data-orderable="false" width="1px"><strong>Status</strong></th>
													<th class="text-nowrap" data-orderable="false" width="1px"></th>
												</tr>
											</thead>
											<tbody>
												<?php if( is_array( $subscription['users'] ) ) { ?>
													<?php
														// build table
														foreach( $subscription['users'] as $user ) {
															// status
															if( $user['subscription_status'] == 'active' ) {
																$user['subscription_status'] = '<button class="btn btn-xs btn-lime btn-block">Active</button>';
															} elseif( $user['subscription_status'] == 'pending' ) {
																$user['subscription_status'] = '<button class="btn btn-xs btn-info btn-block">Pending</button>';
															} elseif( $user['subscription_status'] == 'suspended' ) {
																$user['subscription_status'] = '<button class="btn btn-xs btn-warning btn-block">Suspended</button>';
															} elseif( $user['subscription_status'] == 'terminated' ) {
																$user['subscription_status'] = '<button class="btn btn-xs btn-danger btn-block">Terminated</button>';
															}
															// output
															echo '
																<tr>
																	<td class="text-nowrap">
																		'.$user['full_name'].'
																	</td>
																	<td class="text-nowrap">
																		'.$user['email'].'
																	</td>
																	<td class="text-nowrap">
																		'.$user['company_name'].'
																	</td>
																	<td class="text-nowrap">
																		'.$user['subscription_status'].'
																	</td>
																	<td class="text-nowrap">
																		<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
																		<div class="dropdown-menu dropdown-menu-right" role="menu">
																			<a href="?c=user&id='.$user['id'].'" class="dropdown-item">View / Edit</a>
																		</div>
																	</td>
																</tr>
															';
														}
													?>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</form>
				<?php } ?>
			</div>

			<!-- add subscription to user modal -->
			<form class="form" method="post" action="actions.php?a=subscription_add_user">
				<input type="hidden" name="subscription_id" value="<?php echo $subscription['id']; ?>">

				<div class="modal fade" id="subscription_add_user" tabindex="-1" role="dialog" aria-labelledby="subscription_add_user" aria-hidden="true">
				   	<div class="modal-dialog modal-notice">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Add Subscription to User</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
								<div class="row">
									<div class="col-xl-12 col-sm-12">
										<p>Please select which user you wish to assign this subscription plan to. Changes will take effect immediately.</p>
									</div>
									<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>User</strong></label>
											<select name="user_id" class="form-control select2">
												<?php foreach( $florists as $florist ) { ?>
													<option value="<?php echo $florist['id']; ?>"><?php echo $florist['full_name'].' ( '.$florist['email'].' )'; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Continue</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			</form>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $subscription ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>

		<?php function subscriptions() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// get data
				$subscriptions = get_subscription_plans();
			?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Subscription Plans</li>
				</ol>
				
				<h1 class="page-header">Subscriptions Plans</h1>

				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-12">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-xl-8 col-xs-12">
									</div>
									<div class="col-xl-4 col-xs-12 text-right">
										<?php if( $dev_check ) { ?>
											<div class="btn-group">
												<a class="btn btn-xs btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev Output</a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h2 class="panel-title">Subscriptions Plans</h2>
						<div class="panel-heading-btn">
							<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#subscription_add">Add</button>
						</div>
					</div>
					<div class="panel-body">
						<table id="table_subscriptions" class="table table-striped table-bordered table-td-valign-middle">
							<thead>
								<tr>
									<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
									<th class="text-nowrap" data-orderable="false" width=""><strong>Name</strong></th>
									<th class="text-nowrap" data-orderable="false" width="1px"><strong>Status</strong></th>
									<th class="text-nowrap" data-orderable="false" width="1px"></th>
								</tr>
							</thead>
							<tbody>
								<?php
									// build table
									foreach( $subscriptions as $subscription ) {
										// status
										$subscription['status_raw'] = $subscription['status'];
										if( $subscription['status'] == 'active' ) {
											$subscription['status'] = '<button class="btn btn-xs btn-lime btn-block">Active</button>';
										} elseif( $subscription['status'] == 'pending' ) {
											$subscription['status'] = '<button class="btn btn-xs btn-info btn-block">Pending</button>';
										} elseif( $subscription['status'] == 'retired' ) {
											$subscription['status'] = '<button class="btn btn-xs btn-danger btn-block">Retired</button>';
										} elseif( $subscription['status'] == 'suspended' ) {
											$subscription['status'] = '<button class="btn btn-xs btn-warning btn-block">Suspended</button>';
										} elseif( $subscription['status'] == 'terminated' ) {
											$subscription['status'] = '<button class="btn btn-xs btn-danger btn-block">Terminated</button>';
										}

										// output
										echo '
											<tr class="">
												<td class="text-nowrap">
													<a href="?c=subscription&id='.$subscription['id'].'">'.$subscription['id'].'</a>
												</td>
												<td class="text-nowrap">
													'.$subscription['name'].'
												</td>
												<td class="text-nowrap">
													'.$subscription['status'].'
												</td>
												<td class="text-nowrap">
													<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
													<div class="dropdown-menu dropdown-menu-right" role="menu">
														<a href="?c=subscription&id='.$subscription['id'].'" class="dropdown-item">View / Edit</a>
													</div>
												</td>
											</tr>
										';
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- add subscription modal -->
			<form class="form" method="post" action="actions.php?a=subscription_add">
				<div class="modal fade" id="subscription_add" tabindex="-1" role="dialog" aria-labelledby="subscription_add" aria-hidden="true">
				   	<div class="modal-dialog modal-notice">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Add Subscription Plan</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
								<div class="row">
									<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Name</strong></label>
											<input type="text" name="name" class="form-control" required>
										</div>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Continue</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			</form>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $subscriptions ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>

		<?php function user() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php 
				// get data
				if( $admin_check && isset( $_GET['id'] ) || $staff_check && isset( $_GET['id'] ) ) {
					$user_id = get( 'id' );
					$user = account_details( $user_id );
					$payouts = get_florist_payouts( $user_id );
				} else{
					$user = account_details( $account_details['id'] );
					$payouts = get_florist_payouts( $account_details['id'] );
				}

				$addresses_countries = get_global_countries();
				$subscriptions = get_subscription_plans();
			?>

			<div id="content" class="content">
				<!-- sanity check -->
				<?php if( !isset( $user['id'] ) ) { ?>
					<?php echo $not_found; ?>
				<?php } else { ?>
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php?c=users">Users</a></li>
						<li class="breadcrumb-item active">User: <?php echo $user['full_name']; ?></li>
					</ol>

					<h1 class="page-header">User: <?php echo $user['full_name']; ?></h1>

					<div class="row">
						<div class="col-xl-12">
							<div id="status_message"></div><div id="kyc_status_message"></div>
						</div>
					</div>

					<form class="form" method="post" action="actions.php?a=user_edit">
						<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

						<div class="row">
							<div class="col-xl-12">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-8 col-xs-12">
											</div>
											<div class="col-xl-4 col-xs-12 text-right">
												<div class="btn-group">
													<a href="?c=users" type="button" class="btn btn-xs btn-white">Back</a>
													<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Save</button>
												</div>
												<?php if( $dev_check ) { ?>
													<div class="btn-group">
														<a href="#" class="btn btn-xs btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<!-- admin options -->
							<?php if( $admin_check || $staff_check ) { ?>
								<div class="col-xl-12 col-xl-12 col-sm-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Admin Section</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-1 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Status</strong></label>
														<select name="status" class="form-control">
															<option value="active" <?php if( $user['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
															<option value="invited" <?php if( $user['status'] == 'invited' ) { echo 'selected'; } ?> >Invited</option>
															<option value="terminated" <?php if( $user['status'] == 'pending' ) { echo 'selected'; } ?> >Pending</option>
															<option value="suspended" <?php if( $user['status'] == 'suspended' ) { echo 'selected'; } ?> >Suspended</option>
															<option value="terminated" <?php if( $user['status'] == 'terminated' ) { echo 'selected'; } ?> >Terminated</option>
														</select>
													</div>
												</div>
												<div class="col-xl-1 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Type</strong></label>
														<select name="type" class="form-control">
															<option value="customer" <?php if( $user['type'] == 'customer' ) { echo 'selected'; } ?> >Customer</option>
															<option value="florist" <?php if( $user['type'] == 'florist' ) { echo 'selected'; } ?> >Florist</option>
															<option disabled="disabled">----- CAUTION -----</option>
															<option value="staff" <?php if( $user['type'] == 'staff' ) { echo 'selected'; } ?> >Staff</option>
															<?php if( $admin_check ) { ?>
																<option value="admin" <?php if( $user['type'] == 'admin' ) { echo 'selected'; } ?>>Admin</option>
															<?php } ?>
														</select>
													</div>
												</div>
												<?php if( $admin_check && $user['type'] == 'florist' || $staff_check && $user['type'] == 'florist' ) { ?>
													<div class="col-xl-1 col-sm-12">
														<div class="form-group">
															<label class="bmd-label-floating"><strong>Fallback Florist</strong></label>
															<select name="fallback_florist" class="form-control">
																<option value="no" <?php if( $user['fallback_florist'] == 'no' ) { echo 'selected'; } ?> >No</option>
																<option value="yes" <?php if( $user['fallback_florist'] == 'yes' ) { echo 'selected'; } ?> >Yes</option>
															</select>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>

							<div class="col-xl-6 col-sm-12">
								<!-- contact details -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Contact Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">
												
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-12 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Company Name</strong></label>
													<input type="text" name="company_name" class="form-control" value="<?php echo $user['company_name']; ?>">
													<small>Example: Epic Flowers</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>First Name</strong></label>
													<input type="text" name="first_name" class="form-control" value="<?php echo $user['first_name']; ?>" required>
													<small>Example: Jo</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Last Name</strong></label>
													<input type="text" name="last_name" class="form-control" value="<?php echo $user['last_name']; ?>" required>
													<small>Example: Bloggs</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Landline Tel</strong></label>
													<input type="text" name="tel_landline" class="form-control" value="<?php echo $user['tel_landline']; ?>">
													<small>Example: +44 1254 745560</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Cell Tel</strong></label>
													<input type="text" name="tel_cell" class="form-control" value="<?php echo $user['tel_cell']; ?>">
													<small>Example: +44 1254 745560</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Address 1</strong></label>
													<input type="text" name="address_1" class="form-control" value="<?php echo $user['address_1']; ?>" required>
													<small>Example: 123 Awesome Street</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Address 2</strong></label>
													<input type="text" name="address_2" class="form-control" value="<?php echo $user['address_2']; ?>">
													<small>Example: PO BOX 1</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>City</strong></label>
													<input type="text" name="address_city" class="form-control" value="<?php echo $user['address_city']; ?>" required>
													<small>Example: Awesomeville</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>State / County</strong></label>
													<input type="text" name="address_state" class="form-control" value="<?php echo $user['address_state']; ?>" required>
													<small>Example: Florida</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Zip / Post Code</strong></label>
													<input type="text" name="address_zip" class="form-control" value="<?php echo $user['address_zip']; ?>" required>
													<small>Example: 12345</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Country</strong></label>
													<select name="address_country" class="form-control select2">
														<option value="AF" <?php if( $user['address_country'] == 'AF' ) { echo 'selected'; } ?> >Afghanistan</option>
														<option value="AX" <?php if( $user['address_country'] == 'AX' ) { echo 'selected'; } ?> >Åland Islands</option>
														<option value="AL" <?php if( $user['address_country'] == 'AL' ) { echo 'selected'; } ?> >Albania</option>
														<option value="DZ" <?php if( $user['address_country'] == 'DZ' ) { echo 'selected'; } ?> >Algeria</option>
														<option value="AS" <?php if( $user['address_country'] == 'AS' ) { echo 'selected'; } ?> >American Samoa</option>
														<option value="AD" <?php if( $user['address_country'] == 'AD' ) { echo 'selected'; } ?> >Andorra</option>
														<option value="AO" <?php if( $user['address_country'] == 'AO' ) { echo 'selected'; } ?> >Angola</option>
														<option value="AI" <?php if( $user['address_country'] == 'AI' ) { echo 'selected'; } ?> >Anguilla</option>
														<option value="AQ" <?php if( $user['address_country'] == 'AQ' ) { echo 'selected'; } ?> >Antarctica</option>
														<option value="AG" <?php if( $user['address_country'] == 'AG' ) { echo 'selected'; } ?> >Antigua and Barbuda</option>
														<option value="AR" <?php if( $user['address_country'] == 'AR' ) { echo 'selected'; } ?> >Argentina</option>
														<option value="AM" <?php if( $user['address_country'] == 'AM' ) { echo 'selected'; } ?> >Armenia</option>
														<option value="AW" <?php if( $user['address_country'] == 'AW' ) { echo 'selected'; } ?> >Aruba</option>
														<option value="AU" <?php if( $user['address_country'] == 'AU' ) { echo 'selected'; } ?> >Australia</option>
														<option value="AT" <?php if( $user['address_country'] == 'AT' ) { echo 'selected'; } ?> >Austria</option>
														<option value="AZ" <?php if( $user['address_country'] == 'AZ' ) { echo 'selected'; } ?> >Azerbaijan</option>
														<option value="BS" <?php if( $user['address_country'] == 'BS' ) { echo 'selected'; } ?> >Bahamas</option>
														<option value="BH" <?php if( $user['address_country'] == 'BH' ) { echo 'selected'; } ?> >Bahrain</option>
														<option value="BD" <?php if( $user['address_country'] == 'BD' ) { echo 'selected'; } ?> >Bangladesh</option>
														<option value="BB" <?php if( $user['address_country'] == 'BB' ) { echo 'selected'; } ?> >Barbados</option>
														<option value="BY" <?php if( $user['address_country'] == 'BY' ) { echo 'selected'; } ?> >Belarus</option>
														<option value="BE" <?php if( $user['address_country'] == 'BE' ) { echo 'selected'; } ?> >Belgium</option>
														<option value="BZ" <?php if( $user['address_country'] == 'BZ' ) { echo 'selected'; } ?> >Belize</option>
														<option value="BJ" <?php if( $user['address_country'] == 'BJ' ) { echo 'selected'; } ?> >Benin</option>
														<option value="BM" <?php if( $user['address_country'] == 'BM' ) { echo 'selected'; } ?> >Bermuda</option>
														<option value="BT" <?php if( $user['address_country'] == 'BT' ) { echo 'selected'; } ?> >Bhutan</option>
														<option value="BO" <?php if( $user['address_country'] == 'BO' ) { echo 'selected'; } ?> >Bolivia, Plurinational State of</option>
														<option value="BQ" <?php if( $user['address_country'] == 'BQ' ) { echo 'selected'; } ?> >Bonaire, Sint Eustatius and Saba</option>
														<option value="BA" <?php if( $user['address_country'] == 'BA' ) { echo 'selected'; } ?> >Bosnia and Herzegovina</option>
														<option value="BW" <?php if( $user['address_country'] == 'BW' ) { echo 'selected'; } ?> >Botswana</option>
														<option value="BV" <?php if( $user['address_country'] == 'BV' ) { echo 'selected'; } ?> >Bouvet Island</option>
														<option value="BR" <?php if( $user['address_country'] == 'BR' ) { echo 'selected'; } ?> >Brazil</option>
														<option value="IO" <?php if( $user['address_country'] == 'IO' ) { echo 'selected'; } ?> >British Indian Ocean Territory</option>
														<option value="BN" <?php if( $user['address_country'] == 'BN' ) { echo 'selected'; } ?> >Brunei Darussalam</option>
														<option value="BG" <?php if( $user['address_country'] == 'BG' ) { echo 'selected'; } ?> >Bulgaria</option>
														<option value="BF" <?php if( $user['address_country'] == 'BF' ) { echo 'selected'; } ?> >Burkina Faso</option>
														<option value="BI" <?php if( $user['address_country'] == 'BI' ) { echo 'selected'; } ?> >Burundi</option>
														<option value="KH" <?php if( $user['address_country'] == 'KH' ) { echo 'selected'; } ?> >Cambodia</option>
														<option value="CM" <?php if( $user['address_country'] == 'CM' ) { echo 'selected'; } ?> >Cameroon</option>
														<option value="CA" <?php if( $user['address_country'] == 'CA' ) { echo 'selected'; } ?> >Canada</option>
														<option value="CV" <?php if( $user['address_country'] == 'CV' ) { echo 'selected'; } ?> >Cape Verde</option>
														<option value="KY" <?php if( $user['address_country'] == 'KY' ) { echo 'selected'; } ?> >Cayman Islands</option>
														<option value="CF" <?php if( $user['address_country'] == 'CF' ) { echo 'selected'; } ?> >Central African Republic</option>
														<option value="TD" <?php if( $user['address_country'] == 'TD' ) { echo 'selected'; } ?> >Chad</option>
														<option value="CL" <?php if( $user['address_country'] == 'CL' ) { echo 'selected'; } ?> >Chile</option>
														<option value="CN" <?php if( $user['address_country'] == 'CN' ) { echo 'selected'; } ?> >China</option>
														<option value="CX" <?php if( $user['address_country'] == 'CX' ) { echo 'selected'; } ?> >Christmas Island</option>
														<option value="CC" <?php if( $user['address_country'] == 'CC' ) { echo 'selected'; } ?> >Cocos (Keeling) Islands</option>
														<option value="CO" <?php if( $user['address_country'] == 'CO' ) { echo 'selected'; } ?> >Colombia</option>
														<option value="KM" <?php if( $user['address_country'] == 'KM' ) { echo 'selected'; } ?> >Comoros</option>
														<option value="CG" <?php if( $user['address_country'] == 'CG' ) { echo 'selected'; } ?> >Congo</option>
														<option value="CD" <?php if( $user['address_country'] == 'CD' ) { echo 'selected'; } ?> >Congo, the Democratic Republic of the</option>
														<option value="CK" <?php if( $user['address_country'] == 'CK' ) { echo 'selected'; } ?> >Cook Islands</option>
														<option value="CR" <?php if( $user['address_country'] == 'CR' ) { echo 'selected'; } ?> >Costa Rica</option>
														<option value="CI" <?php if( $user['address_country'] == 'CI' ) { echo 'selected'; } ?> >Côte d'Ivoire</option>
														<option value="HR" <?php if( $user['address_country'] == 'HR' ) { echo 'selected'; } ?> >Croatia</option>
														<option value="CU" <?php if( $user['address_country'] == 'CU' ) { echo 'selected'; } ?> >Cuba</option>
														<option value="CW" <?php if( $user['address_country'] == 'CW' ) { echo 'selected'; } ?> >Curaçao</option>
														<option value="CY" <?php if( $user['address_country'] == 'CY' ) { echo 'selected'; } ?> >Cyprus</option>
														<option value="CZ" <?php if( $user['address_country'] == 'CZ' ) { echo 'selected'; } ?> >Czech Republic</option>
														<option value="DK" <?php if( $user['address_country'] == 'DK' ) { echo 'selected'; } ?> >Denmark</option>
														<option value="DJ" <?php if( $user['address_country'] == 'DJ' ) { echo 'selected'; } ?> >Djibouti</option>
														<option value="DM" <?php if( $user['address_country'] == 'DM' ) { echo 'selected'; } ?> >Dominica</option>
														<option value="DO" <?php if( $user['address_country'] == 'DO' ) { echo 'selected'; } ?> >Dominican Republic</option>
														<option value="EC" <?php if( $user['address_country'] == 'EC' ) { echo 'selected'; } ?> >Ecuador</option>
														<option value="EG" <?php if( $user['address_country'] == 'EG' ) { echo 'selected'; } ?> >Egypt</option>
														<option value="SV" <?php if( $user['address_country'] == 'SV' ) { echo 'selected'; } ?> >El Salvador</option>
														<option value="GQ" <?php if( $user['address_country'] == 'GQ' ) { echo 'selected'; } ?> >Equatorial Guinea</option>
														<option value="ER" <?php if( $user['address_country'] == 'ER' ) { echo 'selected'; } ?> >Eritrea</option>
														<option value="EE" <?php if( $user['address_country'] == 'EE' ) { echo 'selected'; } ?> >Estonia</option>
														<option value="ET" <?php if( $user['address_country'] == 'ET' ) { echo 'selected'; } ?> >Ethiopia</option>
														<option value="FK" <?php if( $user['address_country'] == 'FK' ) { echo 'selected'; } ?> >Falkland Islands (Malvinas)</option>
														<option value="FO" <?php if( $user['address_country'] == 'FO' ) { echo 'selected'; } ?> >Faroe Islands</option>
														<option value="FJ" <?php if( $user['address_country'] == 'FJ' ) { echo 'selected'; } ?> >Fiji</option>
														<option value="FI" <?php if( $user['address_country'] == 'FI' ) { echo 'selected'; } ?> >Finland</option>
														<option value="FR" <?php if( $user['address_country'] == 'FR' ) { echo 'selected'; } ?> >France</option>
														<option value="GF" <?php if( $user['address_country'] == 'GF' ) { echo 'selected'; } ?> >French Guiana</option>
														<option value="PF" <?php if( $user['address_country'] == 'PF' ) { echo 'selected'; } ?> >French Polynesia</option>
														<option value="TF" <?php if( $user['address_country'] == 'TF' ) { echo 'selected'; } ?> >French Southern Territories</option>
														<option value="GA" <?php if( $user['address_country'] == 'GA' ) { echo 'selected'; } ?> >Gabon</option>
														<option value="GM" <?php if( $user['address_country'] == 'GM' ) { echo 'selected'; } ?> >Gambia</option>
														<option value="GE" <?php if( $user['address_country'] == 'GE' ) { echo 'selected'; } ?> >Georgia</option>
														<option value="DE" <?php if( $user['address_country'] == 'DE' ) { echo 'selected'; } ?> >Germany</option>
														<option value="GH" <?php if( $user['address_country'] == 'GH' ) { echo 'selected'; } ?> >Ghana</option>
														<option value="GI" <?php if( $user['address_country'] == 'GI' ) { echo 'selected'; } ?> >Gibraltar</option>
														<option value="GR" <?php if( $user['address_country'] == 'GR' ) { echo 'selected'; } ?> >Greece</option>
														<option value="GL" <?php if( $user['address_country'] == 'GL' ) { echo 'selected'; } ?> >Greenland</option>
														<option value="GD" <?php if( $user['address_country'] == 'GD' ) { echo 'selected'; } ?> >Grenada</option>
														<option value="GP" <?php if( $user['address_country'] == 'GP' ) { echo 'selected'; } ?> >Guadeloupe</option>
														<option value="GU" <?php if( $user['address_country'] == 'GU' ) { echo 'selected'; } ?> >Guam</option>
														<option value="GT" <?php if( $user['address_country'] == 'GT' ) { echo 'selected'; } ?> >Guatemala</option>
														<option value="GG" <?php if( $user['address_country'] == 'GG' ) { echo 'selected'; } ?> >Guernsey</option>
														<option value="GN" <?php if( $user['address_country'] == 'GN' ) { echo 'selected'; } ?> >Guinea</option>
														<option value="GW" <?php if( $user['address_country'] == 'GW' ) { echo 'selected'; } ?> >Guinea-Bissau</option>
														<option value="GY" <?php if( $user['address_country'] == 'GY' ) { echo 'selected'; } ?> >Guyana</option>
														<option value="HT" <?php if( $user['address_country'] == 'HT' ) { echo 'selected'; } ?> >Haiti</option>
														<option value="HM" <?php if( $user['address_country'] == 'HM' ) { echo 'selected'; } ?> >Heard Island and McDonald Islands</option>
														<option value="VA" <?php if( $user['address_country'] == 'VA' ) { echo 'selected'; } ?> >Holy See (Vatican City State)</option>
														<option value="HN" <?php if( $user['address_country'] == 'HN' ) { echo 'selected'; } ?> >Honduras</option>
														<option value="HK" <?php if( $user['address_country'] == 'HK' ) { echo 'selected'; } ?> >Hong Kong</option>
														<option value="HU" <?php if( $user['address_country'] == 'HU' ) { echo 'selected'; } ?> >Hungary</option>
														<option value="IS" <?php if( $user['address_country'] == 'IS' ) { echo 'selected'; } ?> >Iceland</option>
														<option value="IN" <?php if( $user['address_country'] == 'IN' ) { echo 'selected'; } ?> >India</option>
														<option value="ID" <?php if( $user['address_country'] == 'ID' ) { echo 'selected'; } ?> >Indonesia</option>
														<option value="IR" <?php if( $user['address_country'] == 'IR' ) { echo 'selected'; } ?> >Iran, Islamic Republic of</option>
														<option value="IQ" <?php if( $user['address_country'] == 'IQ' ) { echo 'selected'; } ?> >Iraq</option>
														<option value="IE" <?php if( $user['address_country'] == 'IE' ) { echo 'selected'; } ?> >Ireland</option>
														<option value="IM" <?php if( $user['address_country'] == 'IM' ) { echo 'selected'; } ?> >Isle of Man</option>
														<option value="IL" <?php if( $user['address_country'] == 'IL' ) { echo 'selected'; } ?> >Israel</option>
														<option value="IT" <?php if( $user['address_country'] == 'IT' ) { echo 'selected'; } ?> >Italy</option>
														<option value="JM" <?php if( $user['address_country'] == 'JM' ) { echo 'selected'; } ?> >Jamaica</option>
														<option value="JP" <?php if( $user['address_country'] == 'JP' ) { echo 'selected'; } ?> >Japan</option>
														<option value="JE" <?php if( $user['address_country'] == 'JE' ) { echo 'selected'; } ?> >Jersey</option>
														<option value="JO" <?php if( $user['address_country'] == 'JO' ) { echo 'selected'; } ?> >Jordan</option>
														<option value="KZ" <?php if( $user['address_country'] == 'KZ' ) { echo 'selected'; } ?> >Kazakhstan</option>
														<option value="KE" <?php if( $user['address_country'] == 'KE' ) { echo 'selected'; } ?> >Kenya</option>
														<option value="KI" <?php if( $user['address_country'] == 'KI' ) { echo 'selected'; } ?> >Kiribati</option>
														<option value="KP" <?php if( $user['address_country'] == 'KP' ) { echo 'selected'; } ?> >Korea, Democratic People's Republic of</option>
														<option value="KR" <?php if( $user['address_country'] == 'KR' ) { echo 'selected'; } ?> >Korea, Republic of</option>
														<option value="KW" <?php if( $user['address_country'] == 'KW' ) { echo 'selected'; } ?> >Kuwait</option>
														<option value="KG" <?php if( $user['address_country'] == 'KG' ) { echo 'selected'; } ?> >Kyrgyzstan</option>
														<option value="LA" <?php if( $user['address_country'] == 'LA' ) { echo 'selected'; } ?> >Lao People's Democratic Republic</option>
														<option value="LV" <?php if( $user['address_country'] == 'LV' ) { echo 'selected'; } ?> >Latvia</option>
														<option value="LB" <?php if( $user['address_country'] == 'LB' ) { echo 'selected'; } ?> >Lebanon</option>
														<option value="LS" <?php if( $user['address_country'] == 'LS' ) { echo 'selected'; } ?> >Lesotho</option>
														<option value="LR" <?php if( $user['address_country'] == 'LR' ) { echo 'selected'; } ?> >Liberia</option>
														<option value="LY" <?php if( $user['address_country'] == 'LY' ) { echo 'selected'; } ?> >Libya</option>
														<option value="LI" <?php if( $user['address_country'] == 'LI' ) { echo 'selected'; } ?> >Liechtenstein</option>
														<option value="LT" <?php if( $user['address_country'] == 'LT' ) { echo 'selected'; } ?> >Lithuania</option>
														<option value="LU" <?php if( $user['address_country'] == 'LU' ) { echo 'selected'; } ?> >Luxembourg</option>
														<option value="MO" <?php if( $user['address_country'] == 'MO' ) { echo 'selected'; } ?> >Macao</option>
														<option value="MK" <?php if( $user['address_country'] == 'MK' ) { echo 'selected'; } ?> >Macedonia, the former Yugoslav Republic of</option>
														<option value="MG" <?php if( $user['address_country'] == 'MG' ) { echo 'selected'; } ?> >Madagascar</option>
														<option value="MW" <?php if( $user['address_country'] == 'MW' ) { echo 'selected'; } ?> >Malawi</option>
														<option value="MY" <?php if( $user['address_country'] == 'MY' ) { echo 'selected'; } ?> >Malaysia</option>
														<option value="MV" <?php if( $user['address_country'] == 'MV' ) { echo 'selected'; } ?> >Maldives</option>
														<option value="ML" <?php if( $user['address_country'] == 'ML' ) { echo 'selected'; } ?> >Mali</option>
														<option value="MT" <?php if( $user['address_country'] == 'MT' ) { echo 'selected'; } ?> >Malta</option>
														<option value="MH" <?php if( $user['address_country'] == 'MH' ) { echo 'selected'; } ?> >Marshall Islands</option>
														<option value="MQ" <?php if( $user['address_country'] == 'MQ' ) { echo 'selected'; } ?> >Martinique</option>
														<option value="MR" <?php if( $user['address_country'] == 'MR' ) { echo 'selected'; } ?> >Mauritania</option>
														<option value="MU" <?php if( $user['address_country'] == 'MU' ) { echo 'selected'; } ?> >Mauritius</option>
														<option value="YT" <?php if( $user['address_country'] == 'YT' ) { echo 'selected'; } ?> >Mayotte</option>
														<option value="MX" <?php if( $user['address_country'] == 'MX' ) { echo 'selected'; } ?> >Mexico</option>
														<option value="FM" <?php if( $user['address_country'] == 'FM' ) { echo 'selected'; } ?> >Micronesia, Federated States of</option>
														<option value="MD" <?php if( $user['address_country'] == 'MD' ) { echo 'selected'; } ?> >Moldova, Republic of</option>
														<option value="MC" <?php if( $user['address_country'] == 'MC' ) { echo 'selected'; } ?> >Monaco</option>
														<option value="MN" <?php if( $user['address_country'] == 'MN' ) { echo 'selected'; } ?> >Mongolia</option>
														<option value="ME" <?php if( $user['address_country'] == 'ME' ) { echo 'selected'; } ?> >Montenegro</option>
														<option value="MS" <?php if( $user['address_country'] == 'MS' ) { echo 'selected'; } ?> >Montserrat</option>
														<option value="MA" <?php if( $user['address_country'] == 'MA' ) { echo 'selected'; } ?> >Morocco</option>
														<option value="MZ" <?php if( $user['address_country'] == 'MZ' ) { echo 'selected'; } ?> >Mozambique</option>
														<option value="MM" <?php if( $user['address_country'] == 'MM' ) { echo 'selected'; } ?> >Myanmar</option>
														<option value="NA" <?php if( $user['address_country'] == 'NA' ) { echo 'selected'; } ?> >Namibia</option>
														<option value="NR" <?php if( $user['address_country'] == 'NR' ) { echo 'selected'; } ?> >Nauru</option>
														<option value="NP" <?php if( $user['address_country'] == 'NP' ) { echo 'selected'; } ?> >Nepal</option>
														<option value="NL" <?php if( $user['address_country'] == 'NL' ) { echo 'selected'; } ?> >Netherlands</option>
														<option value="NC" <?php if( $user['address_country'] == 'NC' ) { echo 'selected'; } ?> >New Caledonia</option>
														<option value="NZ" <?php if( $user['address_country'] == 'NZ' ) { echo 'selected'; } ?> >New Zealand</option>
														<option value="NI" <?php if( $user['address_country'] == 'NI' ) { echo 'selected'; } ?> >Nicaragua</option>
														<option value="NE" <?php if( $user['address_country'] == 'NE' ) { echo 'selected'; } ?> >Niger</option>
														<option value="NG" <?php if( $user['address_country'] == 'NG' ) { echo 'selected'; } ?> >Nigeria</option>
														<option value="NU" <?php if( $user['address_country'] == 'NU' ) { echo 'selected'; } ?> >Niue</option>
														<option value="NF" <?php if( $user['address_country'] == 'NF' ) { echo 'selected'; } ?> >Norfolk Island</option>
														<option value="MP" <?php if( $user['address_country'] == 'MP' ) { echo 'selected'; } ?> >Northern Mariana Islands</option>
														<option value="NO" <?php if( $user['address_country'] == 'NO' ) { echo 'selected'; } ?> >Norway</option>
														<option value="OM" <?php if( $user['address_country'] == 'OM' ) { echo 'selected'; } ?> >Oman</option>
														<option value="PK" <?php if( $user['address_country'] == 'PK' ) { echo 'selected'; } ?> >Pakistan</option>
														<option value="PW" <?php if( $user['address_country'] == 'PW' ) { echo 'selected'; } ?> >Palau</option>
														<option value="PS" <?php if( $user['address_country'] == 'PS' ) { echo 'selected'; } ?> >Palestinian Territory, Occupied</option>
														<option value="PA" <?php if( $user['address_country'] == 'PA' ) { echo 'selected'; } ?> >Panama</option>
														<option value="PG" <?php if( $user['address_country'] == 'PG' ) { echo 'selected'; } ?> >Papua New Guinea</option>
														<option value="PY" <?php if( $user['address_country'] == 'PY' ) { echo 'selected'; } ?> >Paraguay</option>
														<option value="PE" <?php if( $user['address_country'] == 'PE' ) { echo 'selected'; } ?> >Peru</option>
														<option value="PH" <?php if( $user['address_country'] == 'PH' ) { echo 'selected'; } ?> >Philippines</option>
														<option value="PN" <?php if( $user['address_country'] == 'PN' ) { echo 'selected'; } ?> >Pitcairn</option>
														<option value="PL" <?php if( $user['address_country'] == 'PL' ) { echo 'selected'; } ?> >Poland</option>
														<option value="PT" <?php if( $user['address_country'] == 'PT' ) { echo 'selected'; } ?> >Portugal</option>
														<option value="PR" <?php if( $user['address_country'] == 'PR' ) { echo 'selected'; } ?> >Puerto Rico</option>
														<option value="QA" <?php if( $user['address_country'] == 'QA' ) { echo 'selected'; } ?> >Qatar</option>
														<option value="RE" <?php if( $user['address_country'] == 'RE' ) { echo 'selected'; } ?> >Réunion</option>
														<option value="RO" <?php if( $user['address_country'] == 'RO' ) { echo 'selected'; } ?> >Romania</option>
														<option value="RU" <?php if( $user['address_country'] == 'RU' ) { echo 'selected'; } ?> >Russian Federation</option>
														<option value="RW" <?php if( $user['address_country'] == 'RW' ) { echo 'selected'; } ?> >Rwanda</option>
														<option value="BL" <?php if( $user['address_country'] == 'BL' ) { echo 'selected'; } ?> >Saint Barthélemy</option>
														<option value="SH" <?php if( $user['address_country'] == 'SH' ) { echo 'selected'; } ?> >Saint Helena, Ascension and Tristan da Cunha</option>
														<option value="KN" <?php if( $user['address_country'] == 'KN' ) { echo 'selected'; } ?> >Saint Kitts and Nevis</option>
														<option value="LC" <?php if( $user['address_country'] == 'LC' ) { echo 'selected'; } ?> >Saint Lucia</option>
														<option value="MF" <?php if( $user['address_country'] == 'MF' ) { echo 'selected'; } ?> >Saint Martin (French part)</option>
														<option value="PM" <?php if( $user['address_country'] == 'PM' ) { echo 'selected'; } ?> >Saint Pierre and Miquelon</option>
														<option value="VC" <?php if( $user['address_country'] == 'VC' ) { echo 'selected'; } ?> >Saint Vincent and the Grenadines</option>
														<option value="WS" <?php if( $user['address_country'] == 'WS' ) { echo 'selected'; } ?> >Samoa</option>
														<option value="SM" <?php if( $user['address_country'] == 'SM' ) { echo 'selected'; } ?> >San Marino</option>
														<option value="ST" <?php if( $user['address_country'] == 'ST' ) { echo 'selected'; } ?> >Sao Tome and Principe</option>
														<option value="SA" <?php if( $user['address_country'] == 'SA' ) { echo 'selected'; } ?> >Saudi Arabia</option>
														<option value="SN" <?php if( $user['address_country'] == 'SN' ) { echo 'selected'; } ?> >Senegal</option>
														<option value="RS" <?php if( $user['address_country'] == 'RS' ) { echo 'selected'; } ?> >Serbia</option>
														<option value="SC" <?php if( $user['address_country'] == 'SC' ) { echo 'selected'; } ?> >Seychelles</option>
														<option value="SL" <?php if( $user['address_country'] == 'SL' ) { echo 'selected'; } ?> >Sierra Leone</option>
														<option value="SG" <?php if( $user['address_country'] == 'SG' ) { echo 'selected'; } ?> >Singapore</option>
														<option value="SX" <?php if( $user['address_country'] == 'SX' ) { echo 'selected'; } ?> >Sint Maarten (Dutch part)</option>
														<option value="SK" <?php if( $user['address_country'] == 'SK' ) { echo 'selected'; } ?> >Slovakia</option>
														<option value="SI" <?php if( $user['address_country'] == 'SI' ) { echo 'selected'; } ?> >Slovenia</option>
														<option value="SB" <?php if( $user['address_country'] == 'SB' ) { echo 'selected'; } ?> >Solomon Islands</option>
														<option value="SO" <?php if( $user['address_country'] == 'SO' ) { echo 'selected'; } ?> >Somalia</option>
														<option value="ZA" <?php if( $user['address_country'] == 'ZA' ) { echo 'selected'; } ?> >South Africa</option>
														<option value="GS" <?php if( $user['address_country'] == 'GS' ) { echo 'selected'; } ?> >South Georgia and the South Sandwich Islands</option>
														<option value="SS" <?php if( $user['address_country'] == 'SS' ) { echo 'selected'; } ?> >South Sudan</option>
														<option value="ES" <?php if( $user['address_country'] == 'ES' ) { echo 'selected'; } ?> >Spain</option>
														<option value="LK" <?php if( $user['address_country'] == 'LK' ) { echo 'selected'; } ?> >Sri Lanka</option>
														<option value="SD" <?php if( $user['address_country'] == 'SD' ) { echo 'selected'; } ?> >Sudan</option>
														<option value="SR" <?php if( $user['address_country'] == 'SR' ) { echo 'selected'; } ?> >Suriname</option>
														<option value="SJ" <?php if( $user['address_country'] == 'SJ' ) { echo 'selected'; } ?> >Svalbard and Jan Mayen</option>
														<option value="SZ" <?php if( $user['address_country'] == 'SZ' ) { echo 'selected'; } ?> >Swaziland</option>
														<option value="SE" <?php if( $user['address_country'] == 'SE' ) { echo 'selected'; } ?> >Sweden</option>
														<option value="CH" <?php if( $user['address_country'] == 'CH' ) { echo 'selected'; } ?> >Switzerland</option>
														<option value="SY" <?php if( $user['address_country'] == 'SY' ) { echo 'selected'; } ?> >Syrian Arab Republic</option>
														<option value="TW" <?php if( $user['address_country'] == 'TW' ) { echo 'selected'; } ?> >Taiwan, Province of China</option>
														<option value="TJ" <?php if( $user['address_country'] == 'TJ' ) { echo 'selected'; } ?> >Tajikistan</option>
														<option value="TZ" <?php if( $user['address_country'] == 'TZ' ) { echo 'selected'; } ?> >Tanzania, United Republic of</option>
														<option value="TH" <?php if( $user['address_country'] == 'TH' ) { echo 'selected'; } ?> >Thailand</option>
														<option value="TL" <?php if( $user['address_country'] == 'TL' ) { echo 'selected'; } ?> >Timor-Leste</option>
														<option value="TG" <?php if( $user['address_country'] == 'TG' ) { echo 'selected'; } ?> >Togo</option>
														<option value="TK" <?php if( $user['address_country'] == 'TK' ) { echo 'selected'; } ?> >Tokelau</option>
														<option value="TO" <?php if( $user['address_country'] == 'TO' ) { echo 'selected'; } ?> >Tonga</option>
														<option value="TT" <?php if( $user['address_country'] == 'TT' ) { echo 'selected'; } ?> >Trinidad and Tobago</option>
														<option value="TN" <?php if( $user['address_country'] == 'TN' ) { echo 'selected'; } ?> >Tunisia</option>
														<option value="TR" <?php if( $user['address_country'] == 'TR' ) { echo 'selected'; } ?> >Turkey</option>
														<option value="TM" <?php if( $user['address_country'] == 'TM' ) { echo 'selected'; } ?> >Turkmenistan</option>
														<option value="TC" <?php if( $user['address_country'] == 'TC' ) { echo 'selected'; } ?> >Turks and Caicos Islands</option>
														<option value="TV" <?php if( $user['address_country'] == 'TV' ) { echo 'selected'; } ?> >Tuvalu</option>
														<option value="UG" <?php if( $user['address_country'] == 'UG' ) { echo 'selected'; } ?> >Uganda</option>
														<option value="UA" <?php if( $user['address_country'] == 'UA' ) { echo 'selected'; } ?> >Ukraine</option>
														<option value="AE" <?php if( $user['address_country'] == 'AE' ) { echo 'selected'; } ?> >United Arab Emirates</option>
														<option value="GB" <?php if( $user['address_country'] == 'GB' ) { echo 'selected'; } ?> >United Kingdom</option>
														<option value="US" <?php if( $user['address_country'] == 'US' ) { echo 'selected'; } ?> >United States</option>
														<option value="UM" <?php if( $user['address_country'] == 'UM' ) { echo 'selected'; } ?> >United States Minor Outlying Islands</option>
														<option value="UY" <?php if( $user['address_country'] == 'UY' ) { echo 'selected'; } ?> >Uruguay</option>
														<option value="UZ" <?php if( $user['address_country'] == 'UZ' ) { echo 'selected'; } ?> >Uzbekistan</option>
														<option value="VU" <?php if( $user['address_country'] == 'VU' ) { echo 'selected'; } ?> >Vanuatu</option>
														<option value="VE" <?php if( $user['address_country'] == 'VE' ) { echo 'selected'; } ?> >Venezuela, Bolivarian Republic of</option>
														<option value="VN" <?php if( $user['address_country'] == 'VN' ) { echo 'selected'; } ?> >Viet Nam</option>
														<option value="VG" <?php if( $user['address_country'] == 'VG' ) { echo 'selected'; } ?> >Virgin Islands, British</option>
														<option value="VI" <?php if( $user['address_country'] == 'VI' ) { echo 'selected'; } ?> >Virgin Islands, U.S.</option>
														<option value="WF" <?php if( $user['address_country'] == 'WF' ) { echo 'selected'; } ?> >Wallis and Futuna</option>
														<option value="EH" <?php if( $user['address_country'] == 'EH' ) { echo 'selected'; } ?> >Western Sahara</option>
														<option value="YE" <?php if( $user['address_country'] == 'YE' ) { echo 'selected'; } ?> >Yemen</option>
														<option value="ZM" <?php if( $user['address_country'] == 'ZM' ) { echo 'selected'; } ?> >Zambia</option>
														<option value="ZW" <?php if( $user['address_country'] == 'ZW' ) { echo 'selected'; } ?> >Zimbabwe</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- login details -->
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Login Details</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">

											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Email</strong></label>
													<input type="text" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
													<small>Example: joe.bloggs@gmail.com</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Password</strong></label>
													<input type="text" name="password_1" class="form-control" value="<?php echo $user['password']; ?>" required>
													<small>Only use letters and numbers.</small>
												</div>
											</div>
											<div class="col-xl-3 col-sm-12">
												<div class="form-group">
													<label class="bmd-label-floating"><strong>Confirm Password</strong></label>
													<input type="text" name="password_2" class="form-control" value="<?php echo $user['password']; ?>" required>
													<small>Only use letters and numbers.</small>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- subscription plan -->
								<?php if( $user['type'] == 'florist' ) { ?>
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Subscription Plan</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Subscription Plan</strong></label>
														<select name="subscription_id" class="form-control select2">
															<?php foreach( $subscriptions as $subscription ) { ?>
																<?php if( $subscription['type'] == 'florist' ) { ?>
																	<option value="<?php echo $subscription['id']; ?>" <?php if( $subscription['id'] == $user['subscription_id'] ) { echo 'selected'; } ?>><?php echo $subscription['name'].' - $'.$subscription['cap_amount']; ?></option>
																<?php } ?>
															<?php } ?>
														</select>
														<small>Changing the subscription plan will take effect immediately.</small>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Cap Cycle</strong></label>
														<br>
														<?php
															if( $user['subscription']['cap_cycle'] == '1_day' ) { echo '1 Day'; }
															if( $user['subscription']['cap_cycle'] == '1_week' ) { echo '1 Week'; }
															if( $user['subscription']['cap_cycle'] == '1_month' ) { echo '1 Month'; }
															if( $user['subscription']['cap_cycle'] == '1_year' ) { echo '1 Year'; }
														?>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Network Fee</strong></label>
														<br>
														<?php echo $user['subscription']['network_percentage']; ?>%
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Target Cap Amount</strong></label>
														<br>
														$<?php echo number_format( $user['subscription']['cap_amount'], 2 ); ?>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Cap Amount this Cycle</strong></label>
														<br>
														$<?php echo number_format( $user['cap_total'], 2 ); ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>

							<div class="col-xl-6 col-sm-12">
								<!-- payout details -->
								<?php if( $user['type'] == 'florist' ) { ?>
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Payout Details</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													Please make sure your account details are correct and up-to-date.<br><br>
												</div>
											</div>
											<div id="payput_bank_details" class="row ">
												<div class="col-xl-4 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Account Holder</strong></label>
														<input type="text" name="bank_account_name" class="form-control" value="<?php echo $user['bank_account_name']; ?>">
														<small>Example: Mr John P Smith</small>
													</div>
												</div>
												<div class="col-xl-4 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Account Number</strong></label>
														<input type="text" name="bank_account_number" class="form-control" value="<?php echo $user['bank_account_number']; ?>">
													</div>
												</div>
												<div class="col-xl-4 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Routing Number / Sort Code</strong></label>
														<input type="text" name="bank_sort_code" class="form-control" value="<?php echo $user['bank_sort_code']; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>

								<!-- coverage area -->
								<?php if( $user['type'] == 'florist' ) { ?>
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Coverage Area</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													<a href="#" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#add_coverage_area_modal">Add</a>
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Coverage Area</strong></label>
														<input id="coverage_area" name="coverage_area" class="form-control" value="<?php echo $user['coverage_area']; ?>" readonly="readonly">
														<small>Enter each location and press the 'Tab' or 'Enter' key to continue and add additional coverage locations or use the 'Add' button above..</small>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>

								<!-- seconady coverage area -->
								<?php if( $user['fallback_florist'] == 'yes' ) { ?>
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Elite Florist / Secondary Coverage Area</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													<a href="#" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#add_secondary_coverage_area_modal">Add</a>
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<p>You have agreed to participate in our 'Elite Florist' program. This means you will be our catch-all florist for the locations you specify below. These should be outside your normal coverage area. Orders that are not accepted by a florist within 3 hours will appear assigned to you based upon your secondary coverage area.</p>
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Secondary Coverage Area</strong></label>
														<input id="secondary_coverage_area" name="secondary_coverage_area" class="form-control" value="<?php echo $user['secondary_coverage_area']; ?>" readonly="readonly">
														<small>Use the 'Add' button above to add each secondary coverage area you and willing to service as an Elite florist. These areas are unique and only you will service orders in these areas.</small>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>

								<!-- notes -->
								<?php if( $admin_check || $staff_check ) { ?>
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Notes</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Notes</strong></label>
														<textarea name="notes" class="form-control" rows="7"><?php echo $user['notes']; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</form>

					<!-- payouts -->
					<?php if( $user['type'] == 'florist' ) { ?>
						<div class="row">
							<div class="col-xl-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h2 class="panel-title">Payouts</h2>
										<div class="panel-heading-btn">
											<div class="btn-group">

											</div>
										</div>
									</div>
									<div class="panel-body">
										<?php if( !isset( $user['payouts'][0] ) ) { ?>
											<center>
												<h3>
													No payouts found.
												</h2>
											</center>
										<?php } else { ?>
											<table id="table_payouts" class="table table-striped table-bordered table-td-valign-middle">
												<thead>
													<tr>
														<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
														<th class="text-nowrap" data-orderable="false" width="1px"><strong>Created</strong></th>
														<th class="text-nowrap" data-orderable="false" width="1px"><strong>Amount</strong></th>
														<th class="text-nowrap" data-orderable="false" width="1px"><strong>Status</strong></th>
														<th class="text-nowrap" data-orderable="false" width="1px"></th>
													</tr>
												</thead>
												<tbody>
													<?php
														// build table
														foreach( $payouts as $payout ) {
															// status
															if( $payout['status'] == 'pending' ) {
																$payout['status_html'] = '<button class="btn btn-info btn-block">Pending</button>';
																$payout['status_table_color'] = 'table-warning';
															} elseif( $payout['status'] == 'paid' ) {
																$payout['status_html'] = '<button class="btn btn-xs btn-lime btn-block">Paid</button>';
																$payout['status_table_color'] = 'table-success';
															} elseif( $payout['status'] == 'rejected' ) {
																$payout['status_html'] = '<button class="btn btn-xs btn-danger btn-block">Rejected</button>';
																$payout['status_table_color'] = 'table-danger';
															} elseif( $payout['status'] == 'error' ) {
																$payout['status_html'] = '<button class="btn btn-xs btn-danger btn-block">Error</button>';
																$payout['status_table_color'] = 'table-danger';
															}

															// payment for
															if( $payout['payment_for'] == 'host' ){
																$payout['payment_for'] = 'Hosting Fee';
															} elseif( $payout['payment_for'] == 'referral' ) {
																$payout['payment_for'] = 'Referral Fee';
															}

															// output
															echo '
																<tr class="">
																	<td class="text-nowrap">
																		'.$payout['id'].'
																	</td>
																	<td class="text-nowrap">
																		'.date( "Y-m-d H:i:s", $payout['added'] ).'
																	</td>
																	<td class="text-nowrap">
																		'.$payout['notes'].'
																	</td>
																	<td class="text-nowrap">
																		'.$payout['payment_for'].'
																	</td>
																	<td class="text-nowrap">
																		'.$payout['amount'].' '.strtoupper( $payout['currency'] ).'
																	</td>
																	<td class="text-nowrap">
																		'.$payout['status_html'].'
																	</td>
																	<td class="text-nowrap">
																		'.( $admin_check || $staff_check ? '
																			<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
																			<div class="dropdown-menu dropdown-menu-right" role="menu">
																				<!-- <a href="?c=payout&id='.$payout['id'].'" class="dropdown-item">View / Edit</a> -->
																				<a href="#quick_details_'.$payout['id'].'" data-toggle="modal" data-target="#quick_details_'.$payout['id'].'" class="dropdown-item">View / Edit</a>
																				'.( $payout['status'] == 'pending' ? '<a href="actions.php?a=payout_update&action=paid&id='.$payout['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\' )">Mark Paid</a>' : '' ).'
																				'.( $payout['status'] == 'pending' ? '<a href="actions.php?a=payout_update&action=delete&id='.$payout['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\' )">Delete</a>' : '' ).'
																			</div>
																			' : '' ).'
																	</td>
																</tr>
															';

															// quick details
															if( $admin_check || $staff_check ) {
																echo '
																	<form>
																		<div class="modal fade" id="quick_details_'.$payout['id'].'" tabindex="-1" role="dialog" aria-labelledby="quick_details_'.$payout['id'].'" aria-hidden="true">
																		   	<div class="modal-dialog modal-notice">
																			  	<div class="modal-content">
																				 	<div class="modal-header">
																						<h5 class="modal-title" id="quick_details_'.$payout['id'].'">Payout Details</h5>
																						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
																							x
																						</button>
																				 	</div>
																				 	<div class="modal-body">
																				 		<div class="row">
																				 			<div class="col-xl-12">
																					 			<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Status</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.ucfirst( $payout['status'] ).'" readonly/>
																									</div>
																								</div>
																							</div>
																					 		<div class="col-xl-12">
																					 			<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Payee</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.$user['first_name'].' '.$user['last_name'].'" readonly/>
																									</div>
																								</div>
																							</div>
																							<div class="col-xl-12">
																					 			<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Created</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.date( "Y-m-d H:i:s", $payout['added'] ).'" readonly/>
																									</div>
																								</div>
																							</div>
																							<div class="col-xl-12">
																								<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Paid</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.date( "Y-m-d H:i:s", $payout['paid'] ).'" readonly/>
																									</div>
																								</div>
																							</div>
																							<div class="col-xl-12">
																								<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Payout Method</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.( $user['payout_type'] == 'fiat' ? 'Bank Transfer' : 'HNT' ).'" readonly/>
																									</div>
																								</div>
																							</div>
																							<div class="col-xl-12">
																					 			<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Amount</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.$payout['amount'].' '.strtoupper( $payout['currency'] ).'" readonly/>
																									</div>
																								</div>
																							</div>
																							<div class="col-xl-12">
																								<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Transaction ID</strong></label>
																									<div class="col-xl-9">
																										<input type="text" class="form-control m-b-5" value="'.$payout['transaction_id'].'" readonly/>
																									</div>
																								</div>
																							</div>
																							<div class="col-xl-12">
																								<div class="form-group row m-b-15">
																									<label class="col-form-label col-xl-3"><strong>Notes</strong></label>
																									<div class="col-xl-9">
																										<textarea name="notes" class="form-control" rows="3" readonly>'.$payout['notes'].'</textarea>
																									</div>
																								</div>
																							</div>
																						</div>
																				 	</div>
																				 	<div class="modal-footer">
																				 		<div class="btn-group">
																							<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
																						</div>
																					</div>
																			  	</div>
																		   	</div>
																		</div>
																	</form>
																';
															}
														}
													?>
												</tbody>
											</table>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>

			<!-- add coverage area modal -->
			<div class="modal fade" id="add_coverage_area_modal" tabindex="-1" role="dialog" aria-labelledby="add_coverage_area_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Add New Coverage Area</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<p>
										To add new coverage area(s), select the country, state and city you witsh to cover. When making your selections, use your keyboards 'ctrl' key to make multiple selections and click the 'add' button.
									</p>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<select id="global_address_country" name="global_address_country" class="form-control select2">
											<option value="">Select a country</option>
											<?php foreach( $addresses_countries as $address_country ) { ?>
												<option value="<?php echo $address_country['country']; ?>" ><?php echo code_to_country( $address_country['country'] ); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<span id="global_address_state_container_loading" class="hidden text-center">
											<img src="images/ajax-loader-bar.gif" alt="content loading">
										</span>
										<span id="global_address_state_container" class="hidden">
											<select id="global_address_state" name="global_address_state" class="form-control select2">
												<option value="">Select country first</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<span id="global_address_city_container_loading" class="hidden text-center">
											<img src="images/ajax-loader-bar.gif" alt="content loading">
										</span>
										<span id="global_address_city_container" class="hidden">
											<select id="global_address_city" name="global_address_city" class="form-control select2">
												<option value="">Select state first</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<span id="global_address_zipcode_container_loading" class="hidden text-center">
											<img src="images/ajax-loader-bar.gif" alt="content loading">
										</span>
										<span id="global_address_zipcode_container" class="hidden">
											<select id="global_address_zipcode" name="global_address_zipcode" class="form-control select2" multiple="multiple">
												<option value="">Select city first</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-xl-12 col-sm-12">
									<p><font color="red"><strong>*</strong></font> After each selection, please allow for the next field to populate. Large lists can take a moment to render in your browser. Try now to double click.</p>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
								<a href="#" onclick="ajax_submit_coverage_area();" class="btn btn-xs btn-primary">Add</a>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>

			<!-- add secondary coverage area modal -->
			<div class="modal fade" id="add_secondary_coverage_area_modal" tabindex="-1" role="dialog" aria-labelledby="add_secondary_coverage_area_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Add New Secondary Coverage Area</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<p>
										To add new coverage area(s), select the country, state and city you witsh to cover. When making your selections, use your keyboards 'ctrl' key to make multiple selections and click the 'add' button. Items that are greyed out have already been selected by another florist.
									</p>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<select id="secondary_global_address_country" name="secondary_global_address_country" class="form-control select2">
											<option value="">Select a country</option>
											<?php foreach( $addresses_countries as $address_country ) { ?>
												<option value="<?php echo $address_country['country']; ?>" ><?php echo code_to_country( $address_country['country'] ); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<span id="secondary_global_address_state_container_loading" class="hidden text-center">
											<img src="images/ajax-loader-bar.gif" alt="content loading">
										</span>
										<span id="secondary_global_address_state_container" class="hidden">
											<select id="secondary_global_address_state" name="secondary_global_address_state" class="form-control select2">
												<option value="">Select country first</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<span id="secondary_global_address_city_container_loading" class="hidden text-center">
											<img src="images/ajax-loader-bar.gif" alt="content loading">
										</span>
										<span id="secondary_global_address_city_container" class="hidden">
											<select id="secondary_global_address_city" name="secondary_global_address_city" class="form-control select2">
												<option value="">Select state first</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-xl-3 col-sm-12">
									<div class="form-group">
										<span id="secondary_global_address_zipcode_container_loading" class="hidden text-center">
											<img src="images/ajax-loader-bar.gif" alt="content loading">
										</span>
										<span id="secondary_global_address_zipcode_container" class="hidden">
											<select id="secondary_global_address_zipcode" name="secondary_global_address_zipcode" class="form-control select2" multiple="multiple">
												<option value="">Select city first</option>
											</select>
										</span>
									</div>
								</div>
								<div class="col-xl-12 col-sm-12">
									<p><font color="red"><strong>*</strong></font> After each selection, please allow for the next field to populate. Large lists can take a moment to render in your browser. Try now to double click.</p>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
								<a href="#" onclick="ajax_submit_secondary_coverage_area();" class="btn btn-xs btn-primary">Add</a>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $user ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>

		<?php function users() { ?>
			<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

			<?php
				// match filter
				if( get( 'filter' ) == 'customers' ) {
					$users = get_users_summary( 'customer' );
					$page_name = 'Customers';
				} elseif( get( 'filter' ) == 'florists' ) {
					$users = get_users_summary( 'florist' );
					$page_name = 'Florists';
				} elseif( get( 'filter' ) == 'staff_members' ) {
					$users = get_users_summary( 'staff' );
					$page_name = 'Staff Members';
				} elseif( get( 'filter' ) == 'admins' ) {
					$users = get_users_summary( 'admin' );
					$page_name = 'Admins';
				}

				// get data
				$departments = get_departments();
			?>

			<div id="content" class="content">
				<ol class="breadcrumb float-xl-right">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active"><?php echo $page_name ?></li>
				</ol>

				<h1 class="page-header"><?php echo $page_name; ?></h1>

				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<?php if( $dev_check ) { ?>
					<div class="row">
						<div class="col-xl-12">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-xl-8 col-xs-12">
										</div>
										<div class="col-xl-4 col-xs-12 text-right">
											<div class="btn-group">
												<a class="btn btn-xs btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev Output</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<div class="row">
					<div class="col-xl-12">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h2 class="panel-title"><?php echo $page_name; ?></h2>
								<div class="panel-heading-btn">
									<div class="btn-group">
										<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#user_add">Add</button>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<table id="table_users" class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap" data-orderable="true"><strong>Name</strong></th>
											<?php if( get( 'filter' ) == 'customers' || get( 'filter' ) == 'florists' ) { ?>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Company</strong></th>
											<?php } ?>
											<?php if( get( 'filter' ) == 'staff_members' || get( 'filter' ) == 'admins' ) { ?>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Department</strong></th>
											<?php } ?>
											<th class="text-nowrap" data-orderable="true" width="1px"><strong>Email</strong></th>
											<th class="text-nowrap" data-orderable="true" width="1px"><strong>Landline</strong></th>
											<th class="text-nowrap" data-orderable="true" width="1px"><strong>Cell</strong></th>
											<?php if( get( 'filter' ) == 'customers' || get( 'filter' ) == 'florists' ) { ?>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Address</strong></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Subscription Plan</strong></th>
											<?php } ?>
											<th class="text-nowrap" data-orderable="false" width="1px"></th>
										</tr>
									</thead>
									<tbody>
										<?php
											// build table
											foreach( $users as $user ) {
												// user status
												if( $user['status'] == 'active' ) {
													$user['account_status'] = '<button class="btn btn-xs btn-lime btn-block">Active</button>';
												} elseif( $user['status'] == 'suspended' ) {
													$user['account_status'] = '<button class="btn btn-xs btn-warning btn-block">Suspended</button>';
												} elseif( $user['status'] == 'terminated' ) {
													$user['account_status'] = '<button class="btn btn-xs btn-danger btn-block">Terminated</button>';
												} elseif( $user['status'] == 'expired' ) {
													$user['account_status'] = '<button class="btn btn-xs btn-warning btn-block">Expired</button>';
												} elseif( $user['status'] == 'pending' ) {
													$user['account_status'] = '<button class="btn btn-xs btn-warning btn-block">Pending</button>';
												} elseif( $user['status'] == 'invited' ) {
													$user['account_status'] = '<button class="btn btn-xs btn-warning btn-block">Invited</button>';
												}

												// subscription w status
												if( get( 'filter' ) == 'customers' || get( 'filter' ) == 'florists') {
													if( $user['subscription_status'] == 'active' ) {
														$user['subscription_status'] = '<button class="btn btn-xs btn-lime btn-block">Active</button>';
													} elseif( $user['subscription_status'] == 'suspended' ) {
														$user['subscription_status'] = '<button class="btn btn-xs btn-warning btn-block">Suspended</button>';
													} elseif( $user['subscription_status'] == 'terminated' ) {
														$user['subscription_status'] = '<button class="btn btn-xs btn-danger btn-block">Terminated</button>';
													} elseif( $user['subscription_status'] == 'expired' ) {
														$user['subscription_status'] = '<button class="btn btn-xs btn-warning btn-block">Expired</button>';
													} elseif( $user['subscription_status'] == 'pending' ) {
														$user['subscription_status'] = '<button class="btn btn-xs btn-info btn-block">Pending</button>';
													}
												}

												// match department
												if( $user['type'] == 'admin' || $user['type'] == 'staff' ) {
													// find user's department
													foreach( $departments as $department ) {
														if( $user['department_id'] == $department['id'] ) {
															break;
														}
													}
												}

												// output
												echo '
													<tr>
														<td class="text-nowrap">
															'.$user['full_name'].'
														</td>
														<td class="text-nowrap">
															'.( get( 'filter' ) == 'customers' || get( 'filter' ) == 'florists' ? $user['company_name'] : '' ).'
															'.( get( 'filter' ) == 'staff_members' || get( 'filter' ) == 'admins' ? $department['name'] : '' ).'
														</td>
														<td class="text-nowrap">
															'.$user['email'].'
														</td>
														<td class="text-nowrap">
															'.$user['tel_landline'].'
														</td>
														<td class="text-nowrap">
															'.$user['tel_cell'].'
														</td>
														'.( get( 'filter' ) == 'customers' || get( 'filter' ) == 'florists' ? '
															<td class="text-nowrap">
																'.$user['address_1'].', '.( !empty( $user['address_2'] ) ? $user['address_2'].',' : '' ).'
																'.$user['address_city'].', '.$user['address_state'].', '.$user['address_zip'].', '.$user['address_country'].'
															</td>
														' : '' ).'
														'.( get( 'filter' ) == 'florists' ? '
															<td class="text-nowrap">
																'.$user['subscription_status'].'
															</td>
														' : '' ).'
														<td class="text-nowrap">
															<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
															<div class="dropdown-menu dropdown-menu-right" role="menu">
																<a href="?c=user&id='.$user['id'].'" class="dropdown-item">View / Edit</a>
																<a href="actions.php?a=user_delete&id='.$user['id'].'" class="dropdown-item" onclick="return confirm(\'Are you sure?\' )">Delete</a>
															</div>
														</td>
													</tr>
												';
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- add user modal -->
			<form class="form" method="post" action="actions.php?a=user_add">
				<div class="modal fade" id="user_add" tabindex="-1" role="dialog" aria-labelledby="user_add" aria-hidden="true">
				   	<div class="modal-dialog modal-notice">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Add User</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
								<div class="row">
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>First Name</strong></label>
											<input type="text" name="first_name" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Last Name</strong></label>
											<input type="text" name="last_name" class="form-control" required>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Email</strong></label>
											<input type="email" name="email" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Password</strong></label>
											<input type="text" name="password" class="form-control" required>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xl-12 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Account Type</strong></label>
											<select name="type" class="form-control">
												<option value="customer" selected>Customer</option>
												<option value="florist">Florist</option>
												<option disabled="disabled">----- CAUTION -----</option>
												<option value="florist">Staff Member</option>
												<?php if( $admin_check ) { ?>
													<option value="admin">Admin</option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Cancel</button>
									<button type="submit" onclick="processing();" class="btn btn-xs btn-primary">Continue</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			</form>

			<!-- dev modal -->
			<div class="modal fade" id="dev_modal" tabindex="-1" role="dialog" aria-labelledby="dev_modal" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Dev</h5>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								x
							</button>
					 	</div>
					 	<div class="modal-body">
					 		<div class="row">
					 			<div class="col-xl-12 col-sm-12">
									<?php debug( $users ); ?>
								</div>
							</div>
					 	</div>
					 	<div class="modal-footer">
					 		<div class="btn-group">
								<button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>

		<!--
			<div id="footer" class="footer">
				<?php echo $globals['copyright']; ?>
			</div>
		-->
		
		<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
	
		<?php if( $account_details['accept_terms'] == 'no' ){ ?>
			<div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Terms &amp; Conditions <small>(scroll to accept)</small></h5>
					 	</div>
					 	<div class="modal-body">
							<h2>Welcome to <?php echo $globals['platform_name']; ?></h2>
							<p>These terms and conditions outline the rules and regulations for the use of <?php echo $globals['platform_name']; ?>'s Website.</p> <br> 

							<p>By accessing this website we assume you accept these terms and conditions in full. Do not continue to use <?php echo $globals['platform_name']; ?>'s website 
							if you do not accept all of the terms and conditions stated on this page.</p>
							<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice
							and any or all Agreements: "Client", "You" and "Your" refers to you, the person accessing this website
							and accepting the Company's terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers
							to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves, or either the Client
							or ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake
							the process of our assistance to the Client in the most appropriate manner, whether by formal meetings
							of a fixed duration, or any other means, for the express purpose of meeting the Client's needs in respect
							of provision of the Company's stated services/products, in accordance with and subject to, prevailing law
							of . Any use of the above terminology or other words in the singular, plural,
							capitalisation and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p><h2>Cookies</h2>
							<p>We employ the use of cookies. By using <?php echo $globals['platform_name']; ?>'s website you consent to the use of cookies 
							in accordance with <?php echo $globals['platform_name']; ?>'s privacy policy.</p><p>Most of the modern day interactive web sites
							use cookies to enable us to retrieve user details for each visit. Cookies are used in some areas of our site
							to enable the functionality of this area and ease of use for those people visiting. Some of our 
							affiliate / advertising partners may also use cookies.</p><h2>License</h2>
							<p>Unless otherwise stated, <?php echo $globals['platform_name']; ?> and/or it's licensors own the intellectual property rights for
							all material on <?php echo $globals['platform_name']; ?>. All intellectual property rights are reserved. You may view and/or print
							pages from <?php echo $globals['url']; ?> for your own personal use subject to restrictions set in these terms and conditions.</p>
							<p>You must not:</p>
							<ol>
							<li>Republish material from <?php echo $globals['url']; ?></li>
							<li>Sell, rent or sub-license material from <?php echo $globals['url']; ?></li>
							<li>Reproduce, duplicate or copy material from <?php echo $globals['url']; ?></li>
							</ol>
							<p>Redistribute content from <?php echo $globals['platform_name']; ?> (unless content is specifically made for redistribution).</p>
							<h2>Hyperlinking to our Content</h2>
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
							party's site.
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
							the visibility associated with the hyperlink outweighs the absence of <?php echo $globals['platform_name']; ?>; and (d) where the
							link is in the context of general resource information or is otherwise consistent with editorial content
							in a newsletter or similar product furthering the mission of the organization.</p>

							<p>These organizations may link to our home page, to publications or to other Web site information so long as
							the link: (a) is not in any way misleading; (b) does not falsely imply sponsorship, endorsement or approval
							of the linking party and it products or services; and (c) fits within the context of the linking party's
							site.</p>

							<p>If you are among the organizations listed in paragraph 2 above and are interested in linking to our website,
							you must notify us by sending an e-mail to <a href="mailto:info@<?php echo $globals['domain']; ?>" title="send an email to info@<?php echo $globals['domain']; ?>">info@TheFlowerNetwork.io</a>.
							Please include your name, your organization name, contact information (such as a phone number and/or e-mail
							address) as well as the URL of your site, a list of any URLs from which you intend to link to our Web site,
							and a list of the URL(s) on our site to which you would like to link. Allow 2-3 weeks for a response.</p>

							<p>Approved organizations may hyperlink to our Web site as follows:</p>

							<ol>
							<li>By use of our corporate name; or</li>
							<li>By use of the uniform resource locator (Web address) being linked to; or</li>
							<li>By use of any other description of our Web site or material being linked to that makes sense within the
							context and format of content on the linking party's site.</li>
							</ol>
							<p>No use of <?php echo $globals['platform_name']; ?>'s logo or other artwork will be allowed for linking absent a trademark license
							agreement.</p>
							<h2>Iframes</h2>
							<p>Without prior approval and express written permission, you may not create frames around our Web pages or
							use other techniques that alter in any way the visual presentation or appearance of our Web site.</p>
							<h2>Reservation of Rights</h2>
							<p>We reserve the right at any time and in its sole discretion to request that you remove all links or any particular
							link to our Web site. You agree to immediately remove all links to our Web site upon such request. We also
							reserve the right to amend these terms and conditions and its linking policy at any time. By continuing
							to link to our Web site, you agree to be bound to and abide by these linking terms and conditions.</p>
							<h2>Removal of links from our website</h2>
							<p>If you find any link on our Web site or any linked web site objectionable for any reason, you may contact
							us about this. We will consider requests to remove links but will have no obligation to do so or to respond
							directly to you.</p>
							<p>Whilst we endeavour to ensure that the information on this website is correct, we do not warrant its completeness
							or accuracy; nor do we commit to ensuring that the website remains available or that the material on the
							website is kept up to date.</p>
							<h2>Content Liability</h2>
							<p>We shall have no responsibility or liability for any content appearing on your Web site. You agree to indemnify
							and defend us against all claims arising out of or based upon your Website. No link(s) may appear on any
							page on your Web site or within any context containing content or materials that may be interpreted as
							libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or
							other violation of, any third party rights.</p>
							<h2>Disclaimer</h2>
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
							<p>To the extent that the website and the information and services on the website are provided free of charge,
							we will not be liable for any loss or damage of any nature.</p>
							</p>
					 	</div>
					 	<div class="modal-footer justify-content-center">
					 		<div class="btn-group">
					 			<a href="logout.php" class="btn btn-xs btn-danger">I Don't Accept</a>
					 			<a href="actions.php?a=accept_terms" class="btn btn-xs btn-lime">I Accept</a>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>
	</div>
	
	<!-- core js -->
	<script src="assets/js/app.min.js"></script>
	<script src="assets/js/theme/apple.min.js"></script>

	<!-- datatables -->
	<script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="assets/js/demo/table-manage-default.demo.js"></script>

	<!-- website tutorial -->
	<script type="text/javascript" src="assets/intro/intro.js"></script>

	<!-- select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<!-- matchheight.js -->
	<script src="assets/js/matchheight/jquery.matchHeight.js" type="text/javascript"></script>

	<!-- apple switch -->
	<script src="assets/plugins/switchery/switchery.min.js" type="8c3720d2a681d0399123c034-text/javascript"></script>

	<!-- notifications -->
	<script src="https://rawgit.com/asmsuechan/jquery_push_notification/master/push_notification.js"></script>
	<script>
		/*
		function sendNotification(title, message, url) {
 			//First, check if notifications are supported or not in your browser!
 			if (!Notification) {
 				console.log('Push notifications are not supported in your browser..');
 				return;
 			}
		}

		if( Notification.permission !== "granted" ) {
 			Notification.requestPermission();
 		} else {
 			var notification = new Notification( 'New Order', {
 				icon: 'https://cdn1.iconfinder.com/data/icons/twitter-ui-colored/48/JD-24-128.png',
 				body: 'Joe Bloggs just placed an order.',
 				url: 'https://google.com',
 			} );
 		}

 		notification.onclick = function() {
        	window.open( url ) ;
      	};
		*/
		
		var intervalId = window.setInterval( function() {
			// check_for_notifications();
		}, 2000 );

		// play sound in browser
		function check_for_notifications() {
			$.get( "actions.php?a=get_notifications", function( data ) {
				if( data.id != '' ) {
					$.notify("Custom Title", {
	  					title: "This is title!"
					} );
					// $.notify(function(){
					// 	title: data.title;
						// icon: "https://<?php echo $globals['url']; ?>/backend/images/flower_icon.png";
						// location.href = "https://<?php echo $globals['url']; ?>/backend/";
					// } );
					
					console.log( data.title );
					console.log( data.message );
				}
			} );
		}
	</script>

	<!-- sweetalert -->
	<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/js/demo/ui-modal-notification.demo.js"></script>
	<script src="assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
	
	<!-- global javascript functions -->
	<script>
		// select2
		$(document).ready(function() {
			$( '.select2' ).select2();
		} );
		$( '.select2' ).select2( {
			selectOnClose: true
		} );

		// sweetalerts
		var handleSweetNotification = function() {
			$( '[data-click="swal-primary"]' ).click(function(e) {
				e.preventDefault();
				swal({
					title: 'Are you sure?',
					text: 'You will not be able to recover this imaginary file!',
					icon: 'info',
					buttons: {
						cancel: {
							text: 'Cancel',
							value: null,
							visible: true,
							className: 'btn btn-xs btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Primary',
							value: true,
							visible: true,
							className: 'btn btn-xs btn-primary',
							closeModal: true
						}
					}
				} );
			} );

			$( '[data-click="swal-info"]' ).click(function(e) {
				e.preventDefault();
				swal({
					title: 'Are you sure?',
					text: 'You will not be able to recover this imaginary file!',
					icon: 'info',
					buttons: {
						cancel: {
							text: 'Cancel',
							value: null,
							visible: true,
							className: 'btn btn-xs btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Info',
							value: true,
							visible: true,
							className: 'btn btn-info',
							closeModal: true
						}
					}
				} );
			} );

			$( '[data-click="swal-success"]' ).click(function(e) {
				e.preventDefault();
				swal({
					title: 'Are you sure?',
					text: 'You will not be able to recover this imaginary file!',
					icon: 'success',
					buttons: {
						cancel: {
							text: 'Cancel',
							value: null,
							visible: true,
							className: 'btn btn-xs btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Success',
							value: true,
							visible: true,
							className: 'btn btn-xs btn-lime',
							closeModal: true
						}
					}
				} );
			} );

			$( '[data-click="swal-warning"]' ).click(function(e) {
				e.preventDefault();
				swal({
					title: 'Are you sure?',
					text: 'You will not be able to recover this imaginary file!',
					icon: 'warning',
					buttons: {
						cancel: {
							text: 'Cancel',
							value: null,
							visible: true,
							className: 'btn btn-xs btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Warning',
							value: true,
							visible: true,
							className: 'btn btn-xs btn-warning',
							closeModal: true
						}
					}
				} );
			} );

			$( '[data-click="swal-danger"]' ).click(function(e) {
				e.preventDefault();
				swal({
					title: 'Are you sure?',
					text: 'You will not be able to recover this imaginary file!',
					icon: 'error',
					buttons: {
						cancel: {
							text: 'Cancel',
							value: null,
							visible: true,
							className: 'btn btn-xs btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Warning',
							value: true,
							visible: true,
							className: 'btn btn-xs btn-danger',
							closeModal: true
						}
					}
				} );
			} );
		};

		// set status_message
		function set_status_message(status, message){
			$.ajax({
				cache: false,
				type: "GET",
				url: "actions.php?a=set_status_message&status=" + status + "&message=" + message,
				success: function(data) {
					
				}
			} );	
		}

		// flashing div
		function blink( selector ){
			$( selector ).fadeOut(' slow', function() {
			    $( this ).fadeIn(' slow', function() {
			        blink( this );
			    } );
			} );
		}

		// laxy load images
        let script = document.createElement( "script" );
        script.async = true;
        script.src = "https://cdnjs.cloudflare.com/ajax/libs/lazysizes/4.1.8/lazysizes.min.js";
        document.body.appendChild( script );

        // global modals
        function processing( id ) {
			swal({
				title: 'Processing',
				text: 'Please wait one moment.',
				icon: 'success',
				buttons: {
					
				}
			} ).then(function( e ) {
			    // placeholder
			} );
		}

		function progress_order_to_stage( id, status ) {
			console.log( 'order id: ' + id );
			console.log( 'order status: ' + status );
			if( status == 'out_for_delivery' ) {
				popup_title = 'Out for Delivery';
				popup_text = 'Great job, mark this order as out for delivery?';
				popup_icon = 'success';
			}
			if( status == 'complete' ) {
				popup_title = 'Order Complete';
				popup_text = 'Awesome work, mark this order as complete?';
				popup_icon = 'success';
			}
			if( status == 'delivery_failed' ) {
				popup_title = 'Delivery Failed';
				popup_text = 'Oh dear, looks like something went wrong. Please contact the receiver to arrange delivery.';
				popup_icon = 'error';
			}
			swal({
				title: popup_title,
				text: popup_text,
				icon: popup_icon,
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-xs btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Proceed',
						value: true,
						visible: true,
						className: 'btn btn-xs btn-lime',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'updating order: ' + id + ' to status: ' + status );

			    	// process action
			    	window.location = "actions.php?a=order_update_status&id=" + id + '&status=' + status;
			    }
			} );
		}

    	function order_accept( id ) {
			swal({
				title: 'Accept Order?',
				text: 'Please confirm that you would like to accept this order.',
				icon: 'success',
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-xs btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Accept',
						value: true,
						visible: true,
						className: 'btn btn-xs btn-lime',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'accepting order: ' + id );

			    	// process action
			    	window.location = "actions.php?a=order_accept&id=" + id;
			    }
			} );
		}

    	function order_delete( id ) {
			swal({
				title: 'Delete Order?',
				text: 'You will not be able to undo this action.',
				icon: 'error',
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-xs btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Delete',
						value: true,
						visible: true,
						className: 'btn btn-xs btn-danger',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'deleting order: ' + id );

			    	// process action
			    	window.location = "actions.php?a=order_delete&id=" + id;
			    }
			} );
		}

		function message_delete( id ) {
			swal({
				title: 'Are you sure?',
				text: 'You will not be able to undo this action.',
				icon: 'error',
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-xs btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Delete',
						value: true,
						visible: true,
						className: 'btn btn-xs btn-danger',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'deleting message: ' + id );

			    	// process action
			    	window.location = "actions.php?a=message_delete&id=" + id;
			    }
			} );
		}
	</script>

	<!-- mapbox -->
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<script src="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.js"></script>
	<link href="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css" rel="stylesheet" />

	<?php if( !empty( $_SESSION['alert']['status'] ) ) { ?>
		<script>
			document.getElementById( 'status_message' ).innerHTML = '<div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> fade show m-b-0"><?php echo $_SESSION['alert']['message']; ?></div> <br>';
			setTimeout(function() {
				$( '#status_message' ).fadeOut( 'fast' );
			}, 5000 );
		</script>
		<?php unset( $_SESSION['alert'] ); ?>
	<?php } ?>

	<?php if( get( 'c' ) == '' || get( 'c' ) == 'home' ) { ?>
		<!-- highcharts -->
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<?php } ?>

	<?php if( get( 'c' ) == 'message_new' || get( 'c' ) == 'message_reply' ) { ?>
		<script src="assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
		<script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
		<script src="assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js"></script>
		<script src="assets/js/demo/email-compose.demo.js"></script>
	<?php } ?>

	<?php if( get( 'c' ) == 'messages' ) { ?>
		<script src="assets/js/demo/email-inbox.demo.js"></script>

		<script>
			// refresh every 60 seconds
			setInterval( function() {
				window.location.reload();
            }, 60000 ); 
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'order' ) { ?>
		<script src="assets/plugins/moment/min/moment.min.js"></script>
		<script src="assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
		<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
		<script src="assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

		<script src="assets/plugins/highlight.js/highlight.min.js"></script>
		
		<script>
			$('#datepicker-disabled-past').datepicker({
				todayHighlight: true,
				autoclose: true
			} );

			// card message char count
			function countChar( val ) {
		        var len = val.value.length;
		        if( len >= 100 ) {
					val.value = val.value.substring( 0, 100 );
		        } else {
					$('#charNum').text( 100 - len );
		        }
	      	};

			function order_submit( id ) {
				swal({
					title: 'Are you sure?',
					text: 'You will be unable to make changes to this order once it is submitted.',
					icon: 'success',
					buttons: {
						cancel: {
							text: 'Cancel',
							value: null,
							visible: true,
							className: 'btn btn-xs btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Delete',
							value: true,
							visible: true,
							className: 'btn btn-xs btn-primary',
							closeModal: true
						}
					}
				} ).then(function( e ) {
				    if( e == true ) {
				    	console.log( 'deleting message: ' + id );

				    	// process action
				    	window.location = "actions.php?a=order_submit&id=" + id;
				    }
				} );
			}
		</script>

		<!-- stripe payment scripts -->
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
		<script>
		    function cardValidation() {
		        var valid = true;
		        var name = $( "#card-name" ).val();
		        var email = $( "#email" ).val();
		        var cardNumber = $( "#card-number" ).val();
		        var month = $( "#month" ).val();
		        var year = $( "#year" ).val();
		        var cvc = $( "#cvc" ).val();

		        $( "#error-message" ).html( "" ).hide();

		        if (name.trim() == "" ) {
		            valid = false;
		        }
		        if (email.trim() == "" ) {
		            valid = false;
		        }
		        if (cardNumber.trim() == "" ) {
		            valid = false;
		        }
		        if (month.trim() == "" ) {
		            valid = false;
		        }
		        if (year.trim() == "" ) {
		            valid = false;
		        }
		        if (cvc.trim() == "" ) {
		            valid = false;
		        }

		        if (valid == false) {
		            $( "#error-message" ).html( '<div class="row"><div class="col-xl-12 col-sm-12"><div class="alert alert-danger fade show m-b-0">All fields are required.</div></div></div></div> <br>' ).show();
		        }

		        return valid;
		    }
		    //set your publishable key
		    Stripe.setPublishableKey( "<?php echo STRIPE_PUBLISHABLE_KEY; ?>" );

		    //callback to handle the response from stripe
		    function stripeResponseHandler( status, response ) {
		        if (response.error) {
		            //enable the submit button
		            $( "#submit-btn" ).show();
		            $( "#loader" ).css( "display", "none" );
		            //display the errors on the form
		            $( "#error-message" ).html( '<div class="row"><div class="col-xl-12 col-sm-12"><div class="alert alert-danger fade show m-b-0">'+response.error.message+'</div></div></div></div> <br>' ).show();
		        } else {
		            //get token id
		            var token = response["id"];
		            //insert the token into the form
		            $( "#frmStripePayment" ).append( "<input type='hidden' name='token' value='" + token + "' />" );
		            //submit form to the server
		            $( "#frmStripePayment" ).submit();
		        }
		    }

		    function stripePay( e ) {
		        e.preventDefault();
		        var valid = cardValidation();

		        if( valid == true ) {
		            $( "#submit-btn" ).hide();
		            $( "#loader" ).css( "display", "inline-block" );
		            Stripe.createToken(
		                {
		                    number: $( "#card-number" ).val(),
		                    cvc: $( "#cvc" ).val(),
		                    exp_month: $( "#month" ).val(),
		                    exp_year: $( "#year" ).val(),
		                },
		                stripeResponseHandler
		            );

		            //submit from callback
		            return false;
		        }
		    }
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'orders' ) { ?>
		<script type="text/javascript">
			// refresh every 600 seconds
			setInterval( function() {
				window.location.reload();
            }, 600000 ); 

			// data tables > table_orders
			$(function () {
				$( '#table_orders' ).DataTable({
					"order": [[ 0, "desc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [10, 25, 50, 100, 500],
					"pageLength": 10,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );

			// data tables > table_fallback_orders
			$(function () {
				$( '#table_fallback_orders' ).DataTable({
					"order": [[ 0, "desc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [10, 25, 50, 100, 500],
					"pageLength": 10,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );

			// data tables > table_accepted_orders
			$(function () {
				$( '#table_accepted_orders' ).DataTable({
					"order": [[ 0, "desc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [50, 100, 500],
					"pageLength": 50,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );

			function new_customer( value ) {
				$( '#new_customer' ).addClass( 'hidden' );

				if( value == 'new_customer' ) {
					$( '#new_customer' ).removeClass( 'hidden' );
					$( '#first_name' ).addAttr( 'required' );
					$( '#last_name' ).addAttr( 'required' );
					$( '#email' ).addAttr( 'required' );
					$( '#password' ).addAttr( 'required' );
					$( '#address_1' ).addAttr( 'required' );
					$( '#address_2' ).addAttr( 'required' );
					$( '#address_city' ).addAttr( 'required' );
					$( '#address_state' ).addAttr( 'required' );
					$( '#address_zip' ).addAttr( 'required' );
					$( '#address_country' ).addAttr( 'required' );
				} else {
					$( '#first_name' ).removeAttr( 'required' );
					$( '#last_name' ).removeAttr( 'required' );
					$( '#email' ).removeAttr( 'required' );
					$( '#password' ).removeAttr( 'required' );
					$( '#address_1' ).removeAttr( 'required' );
					$( '#address_2' ).removeAttr( 'required' );
					$( '#address_city' ).removeAttr( 'required' );
					$( '#address_state' ).removeAttr( 'required' );
					$( '#address_zip' ).removeAttr( 'required' );
					$( '#address_country' ).removeAttr( 'required' );
				}
			}
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'product' ) { ?>
		<script src="assets/plugins/ckeditor/ckeditor.js"></script>
		<script src="assets/plugins/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js"></script>
		<script src="assets/js/demo/form-wysiwyg.demo.js"></script>
		<script src="assets/plugins/highlight.js/highlight.min.js"></script>
		<script src="assets/js/demo/render.highlight.js"></script>

		<script>
			function _(el){
				return document.getElementById(el);
			}

			function uploadFile() {
				var file = _( "file1" ).files[0];
				var product_id = _( "product_id" ).value;
				// alert(file.name+" | "+file.size+" | "+file.type);
				var formdata = new FormData();
				formdata.append( "file1", file );
				formdata.append( "product_id", product_id );
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener( "progress", progressHandler, false );
				ajax.addEventListener( "load", completeHandler, false) ;
				ajax.addEventListener( "error", errorHandler, false) ;
				ajax.addEventListener( "abort", abortHandler, false );
				ajax.open( "POST", "actions.php?a=product_image_add" );
				ajax.send( formdata );
			}

			function progressHandler( event ) {
				_( "loaded_n_total" ).innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
				var percent = (event.loaded / event.total ) * 100;
				_( "progressBar" ).value = Math.round( percent );
				_( "status" ).innerHTML = Math.round( percent )+"% uploaded... please wait";
			}

			function completeHandler( event) {
				_( "status" ).innerHTML = event.target.responseText;
				_( "progressBar" ).value = 0;
				setTimeout( function() {
					// set_status_message( 'success', 'Product image has been uploaded.' );
					window.location = window.location;
				}, 1000 );
			}

			function errorHandler(event){
				_( "status" ).innerHTML = "Upload Failed";
				setTimeout( function() {
					$( '#status' ).fadeOut( 'fast' );
				}, 5000 );
			}

			function abortHandler( event ){
				_( "status" ).innerHTML = "Upload Aborted";
				setTimeout( function() {
					$( '#status' ).fadeOut( 'fast' );
				}, 5000 );
			}
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'products' ) { ?>
		<script type="text/javascript">
			// data tables > table_products
			$(function () {
				$( '#table_products' ).DataTable({
					"order": [[ 1, "desc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [50, 100, 500],
					"pageLength": 50,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'subscription' ) { ?>
		<script type="text/javascript">
			// data tables > table_subscription_users
			$(function () {
				$( '#table_subscription_users' ).DataTable({
					"order": [[ 0, "asc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [25, 50, 100, 500],
					"pageLength": 25,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'subscriptions' ) { ?>
		<script type="text/javascript">
			// data tables > table_subscriptions
			$(function () {
				$( '#table_subscriptions' ).DataTable({
					"order": [[ 0, "asc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [25, 50, 100, 500],
					"pageLength": 25,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'user' ) { ?>
		<?php
			if( $admin_check && isset( $_GET['id'] ) || $staff_check && isset( $_GET['id'] ) ) {
				$user_id = get( 'id' );
			} else{
				$user_id = $account_details['id'];
			}
		?>
		
		<script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
		<script>
			// coverage area tags
			$(document).ready(function () {
			    $("#coverage_area").tagit({
			    	allowSpaces: true,
			        // placeholderText: 'Enter coverage areas...'
			    } );
			    $("#secondary_coverage_area").tagit({
			    	allowSpaces: true,
			        // placeholderText: 'Enter coverage areas...'
					
					// remove user_id for this area
			        afterTagRemoved: function(event, ui) {
				        console.log( 'removing tag:' + ui.tagLabel );
				        $.ajax( {
			                type:'POST',
			                data: {user_id: <?php echo $user_id; ?>, area: ui.tagLabel},
			                url:'actions.php?a=user_edit_remove_secondary_coverage_area',
			                success:function( html ){
			                	console.log( 'we removed the area from this user' );
							}
			            } ); 
				    }
			    } );
			    $("#input2").tagit({
			        placeholderText: 'Add tags here'
			    } );
			} );

			// dynamic global addresses for coverage areas
			$(document).ready( function() {
			    $( '#global_address_country' ).on( 'change', function() {
			    	// show loading spinner
			    	$( '#global_address_state_container_loading' ).removeClass( 'hidden' );

			    	// map vars
			        var countryID = $( this ).val();
			        console.log( 'Query: actions.php?a=ajax_get_global_states&country=' + countryID );
			        if( countryID ){
			            $.ajax( {
			                type:'GET',
			                url:'actions.php?a=ajax_get_global_states&country=' + countryID,
			                // data:'country=' + countryID,
			                success:function( html ){
			                	console.log( 'we got an ajax reply with states' );

			                	// hide loading spinner
			                	$( '#global_address_state_container_loading' ).addClass( 'hidden' );

			                	// show content
			                    $( '#global_address_state_container' ).removeClass( 'hidden' );
			                    $( '#global_address_state' ).html( html );
								$( '#global_address_city' ).html( '<option value="">Select state first</option>' ); 
			                }
			            } ); 
			        }else{
			            $( '#global_address_state' ).html( '<option value="">Select country first</option>' );
			            $( '#global_address_city' ).html( '<option value="">Select state first</option>' ); 
			        }
			    } );
			    
			    $( '#global_address_state' ).on( 'change', function() {
			    	// show loading spinner
			    	$( '#global_address_city_container_loading' ).removeClass( 'hidden' );

			    	// map vars
			    	var countryID = $( '#global_address_country' ).val();
			        var stateID = $( this ).val();

			        console.log( 'Query: actions.php?a=ajax_get_global_cities&type=primary&country=' + countryID + '&state=' + stateID );
			        if( stateID ){
			            $.ajax({
			                type:'GET',
			                url:'actions.php?a=ajax_get_global_cities&country=' + countryID + '&state=' + stateID,
			                // data:'state_id='+stateID,
			                success:function( html ){
			                	console.log( 'we got an ajax reply with cities' );

			                	// hide loading spinner
			                	$( '#global_address_city_container_loading' ).addClass( 'hidden' );

			                	// show content
			                	$( '#global_address_city_container' ).removeClass( 'hidden' );
			                    $( '#global_address_city' ).html( html );
			                }
			            } ); 
			        }else{
			            $( '#global_address_city' ).html( '<option value="">Select state first</option>' ); 
			        }
			    } );

			    $( '#global_address_city' ).on( 'change', function() {
			    	// show loading spinner
			    	$( '#global_address_zipcode_container_loading' ).removeClass( 'hidden' );

			    	// map vars
			    	var countryID = $( '#global_address_country' ).val();
			    	var stateID = $( '#global_address_state' ).val();
			        var cityID = $( this ).val();

			        console.log( 'Query: actions.php?a=ajax_get_global_cities&type=primary&country=' + countryID + '&state=' + stateID + '&city=' + cityID);
			        if( stateID ){
			            $.ajax({
			                type:'GET',
			                url:'actions.php?a=ajax_get_global_zipcodes&type=primary&country=' + countryID + '&state=' + stateID + '&city=' + cityID,
			                // data:'state_id='+stateID,
			                success:function( html ){
			                	console.log( 'we got an ajax reply with zipcodes' );

			                	// hide loading spinner
			                	$( '#global_address_zipcode_container_loading' ).addClass( 'hidden' );

			                	// show content
			                	$( '#global_address_zipcode_container' ).removeClass( 'hidden' );
			                    $( '#global_address_zipcode' ).html( html );
			                }
			            } ); 
			        }else{
			            $( '#global_address_zipcode' ).html( '<option value="">Select city first</option>' ); 
			        }
			    } );
			} );

			// dynamic global addresses for secondary coverage areas
			$(document).ready( function() {
			    $( '#secondary_global_address_country' ).on( 'change', function() {
			    	// show loading spinner
			    	$( '#secondary_global_address_state_container_loading' ).removeClass( 'hidden' );

			    	// map vars
			        var countryID = $( this ).val();
			        console.log( 'Query: actions.php?a=ajax_get_global_states&country=' + countryID );

			        if( countryID ){
			            $.ajax( {
			                type:'GET',
			                url:'actions.php?a=ajax_get_global_states&country=' + countryID,
			                // data:'country=' + countryID,
			                success:function( html ){
			                	console.log( 'we got an ajax reply with states' );
			                	
			                	// hide loading spinner
			                	$( '#secondary_global_address_state_container_loading' ).addClass( 'hidden' );

			                	// show content
			                    $( '#secondary_global_address_state_container' ).removeClass( 'hidden' );
			                    $( '#secondary_global_address_state' ).html( html );
								$( '#secondary_global_address_city' ).html( '<option value="">Select state first</option>' ); 
			                }
			            } ); 
			        }else{
			            $( '#secondary_global_address_state' ).html( '<option value="">Select country first</option>' );
			            $( '#secondary_global_address_city' ).html( '<option value="">Select state first</option>' ); 
			        }
			    } );
			    
			    $( '#secondary_global_address_state' ).on( 'change', function() {
			    	// show loading spinner
			    	$( '#secondary_global_address_city_container_loading' ).removeClass( 'hidden' );

			    	// map vars
			    	var countryID = $( '#secondary_global_address_country' ).val();
			        var stateID = $( this ).val();

			        console.log( 'Query: actions.php?a=ajax_get_global_cities&type=secondary&country=' + countryID + '&state=' + stateID );
			        if( stateID ){
			            $.ajax({
			                type:'GET',
			                url:'actions.php?a=ajax_get_global_cities&type=secondary&country=' + countryID + '&state=' + stateID,
			                success:function( html ){
			                	console.log( 'we got an ajax reply with cities' );

			                	// hide loading spinner
			                	$( '#secondary_global_address_city_container_loading' ).addClass( 'hidden' );

			                	// show content
			                	$( '#secondary_global_address_city_container' ).removeClass( 'hidden' );
			                    $( '#secondary_global_address_city' ).html( html );
			                }
			            } ); 
			        }else{
			            $( '#secondary_global_address_city' ).html( '<option value="">Select state first</option>' ); 
			        }
			    } );

			    $( '#secondary_global_address_city' ).on( 'change', function() {
			    	// show loading spinner
			    	$( '#secondary_global_address_zipcode_container_loading' ).removeClass( 'hidden' );

			    	// map vars
			    	var countryID = $( '#secondary_global_address_country' ).val();
			    	var stateID = $( '#secondary_global_address_state' ).val();
			        var cityID = $( this ).val();

			        console.log( 'Query: actions.php?a=ajax_get_global_zipcodes&type=secondary&country=' + countryID + '&state=' + stateID + '&city=' + cityID);
			        if( stateID ){
			            $.ajax({
			                type:'GET',
			                url:'actions.php?a=ajax_get_global_zipcodes&type=secondary&country=' + countryID + '&state=' + stateID + '&city=' + cityID,
			                // data:'state_id='+stateID,
			                success:function( html ){
			                	console.log( 'we got an ajax reply with zipcodes' );

			                	// hide loading spinner
			                	$( '#secondary_global_address_zipcode_container_loading' ).addClass( 'hidden' );

			                	// show content
			                	$( '#secondary_global_address_zipcode_container' ).removeClass( 'hidden' );
			                    $( '#secondary_global_address_zipcode' ).html( html );
			                }
			            } ); 
			        }else{
			            $( '#secondary_global_address_zipcode' ).html( '<option value="">Select city first</option>' ); 
			        }
			    } );
			} );

			// save coverage areas
			function ajax_submit_coverage_area() {
				// show processing modal
				processing();

				// map vars
				var new_coverage_area = $( '#global_address_zipcode' ).val();

				console.log( 'Inline submit triggered' );
				$.ajax({
	                type:'POST',
	                url:'actions.php?a=user_edit_coverage_area',
	                data: {user_id: <?php echo $user_id; ?>, coverage_area: new_coverage_area},
	                success:function( html ){
	                	console.log( 'we submitted coverage_area for this user' );
	                	console.log( html );

	                	// reload the page
	                	location.reload();
	                }
	            } ); 
			}

			// save secondary coverage areas
			function ajax_submit_secondary_coverage_area() {
				// show processing modal
				processing();

				// map vars
				var secondary_coverage_area = $( '#secondary_global_address_zipcode' ).val();

				console.log( 'Inline submit triggered' );
				$.ajax({
	                type:'POST',
	                url:'actions.php?a=user_edit_secondary_coverage_area',
	                data: {user_id: <?php echo $user_id; ?>, secondary_coverage_area: secondary_coverage_area},
	                success:function( html ){
	                	console.log( 'we submitted secondary_coverage_area for this user' );
	                	console.log( html );

	                	// reload the page
	                	location.reload();
	                }
	            } ); 
			}
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'users' ) { ?>
		<script type="text/javascript">
			// data tables > table_users
			$(function () {
				$( '#table_users' ).DataTable({
					"order": [[ 0, "asc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No data found."
					},
					"oLanguage": {
						"sSearch": "Filter: "
					},
					"paging": true,
					"processing": true,
					"lengthChange": true,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"lengthMenu": [50, 100, 500],
					"pageLength": 50,
					search: {
					   search: '<?php if( isset( $_GET['search'] ) ) { echo $_GET['search']; } ?>'
					}
				} );
			} );
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'staging' ) { ?>
		<script src="assets/plugins/smartwizard/dist/js/jquery.smartWizard.js"></script>
		<script src="assets/js/demo/form-wizards.demo.js"></script></script>
	<?php } ?>

	<?php if( $account_details['accept_terms'] == 'no' ){ ?>
		<script>
			$( window).on( 'load',function() {
				$( '#modal-terms' ).modal( { 
						backdrop: 'static', 
						keyboard: false, 
					}
				);

				$( "#modal-terms" ).css( {
					background:"rgb(0, 0, 0)",
					opacity: ".50 !important",
					filter: "Alpha(Opacity=50)",
					width: "100%",
				} );
			} );
		</script>
	<?php } ?>

	<!-- introjs functions -->
	<script>
		function tutorial_page_home(){
			/*
			var intro = introJs();
			intro.setOptions({
				exitOnEsc: false,
				exitOnOverlayClick: false,
				showStepNumbers: false,
				showProgress: true,
				steps: [
					{
						element: document.querySelector('.tutorial_dashboard_tiles'),
						intro: "Dashboard tiles serve as a quick summary of data relating to your account.",
						position: 'right'
					},
					{
						element: document.querySelector('.tutorial_referral_url'),
						intro: "You can share a unique referral URL with other florists. This will help grow the platform and could also grab you a nice referral reward in our Referral Lotto.",
						position: 'top'
					},
				]
			});

			intro.start();
			*/
			alert( 'test' );

			introJs().start();
			introJs().addHints();
		}
	</script>
</body>
</html>