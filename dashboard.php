<?php
// error reporting
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);

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
		</div>
		<div>
			<a href="javascript:history.back()" class="btn btn-lime p-l-20 p-r-20">Go back</a>
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
	
	<!-- theme -->
	<link href="assets/css/apple/app.min.css" rel="stylesheet">
	<link href="assets/css/apple/theme/blue.min.css" rel="stylesheet">

	<!-- ion icons -->
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

	<?php if( get( 'c' ) == '' || get( 'c' ) == 'home' ) { ?>
		<!-- highcharts -->
		<style>
			.highcharts-figure,
			.highcharts-data-table table {
			    min-width: 320px;
			    max-width: 800px;
			    margin: 1em auto;
			}

			.highcharts-data-table table {
			    font-family: Verdana, sans-serif;
			    border-collapse: collapse;
			    border: 1px solid #ebebeb;
			    margin: 10px auto;
			    text-align: center;
			    width: 100%;
			    max-width: 500px;
			}

			.highcharts-data-table caption {
			    padding: 1em 0;
			    font-size: 1.2em;
			    color: #555;
			}

			.highcharts-data-table th {
			    font-weight: 600;
			    padding: 0.5em;
			}

			.highcharts-data-table td,
			.highcharts-data-table th,
			.highcharts-data-table caption {
			    padding: 0.5em;
			}

			.highcharts-data-table thead tr,
			.highcharts-data-table tr:nth-child(even) {
			    background: #f8f8f8;
			}

			.highcharts-data-table tr:hover {
			    background: #f1f7ff;
			}

			input[type="number"] {
			    min-width: 50px;
			}
		</style>
	<?php } ?>

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

<body class=''>
	<div id="app" class="app app-header-fixed app-sidebar-fixed">
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
								<a href="dashboard.php?c=dev" onclick="processing();">
									<i class="fa fa-code bg-gradient-purple"></i>
									<span>Global Vars</span> 
								</a>
							</li>
							<li <?php if( get( 'c' ) == 'staging' ) { echo'class="active"'; } ?>>
								<a href="dashboard.php?c=staging" onclick="processing();">
									<i class="fa fa-rocket bg-gradient-pink"></i>
									<span>Staging</span> 
								</a>
							</li>
						</ul>
					<?php } ?>

					<ul class="nav"><li class="nav-header">Navigation</li>
						<li <?php if( get( 'c' ) == '' || get( 'c' ) == 'home' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php" onclick="processing();">
								<i class="fa fa-home bg-blue"></i>
								<span>Home</span> 
							</a>
						</li>
						<?php if( $admin_check || $staff_check ) { ?>
							<li <?php if( get( 'c' ) == 'customer' || get( 'c' ) == 'customers' ) { echo'class="active"'; } ?>>
								<a href="dashboard.php?c=customers" onclick="processing();">
									<i class="fa fa-users bg-orange"></i>
									<span>Customers</span> 
								</a>
							</li>
						<?php } ?>
						<li <?php if( get( 'c' ) == 'job' || get( 'c' ) == 'jobs' ) { echo'class="active"'; } ?>>
							<a href="dashboard.php?c=jobs" onclick="processing();">
								<i class="fa fa-car bg-green"></i>
								<span>Jobs</span> 
							</a>
						</li>
						<?php if( $admin_check || $staff_check ) { ?>
							<li <?php if( get( 'c' ) == 'provider' || get( 'c' ) == 'providers' ) { echo'class="active"'; } ?>>
								<a href="dashboard.php?c=providers" onclick="processing();">
									<i class="fa fa-address-card bg-purple"></i>
									<span>Providers</span> 
								</a>
							</li>
						<?php } ?>
						<li class="has-sub 
								<?php if( get( 'c' ) == 'tools' ) { echo'active'; } ?>
								<?php if( get( 'c' ) == 'vrn_lookup' || get( 'c' ) == 'vrn_lookup_results' ) { echo'active'; } ?>
							">
								<a href="javascript:;">
									<b class="caret"></b>
									<i class="fa fa-flask bg-yellow"></i>
									<span>Tools</span>
								</a>
								<ul class="sub-menu">
									<li <?php if( get( 'c' ) == 'vrn_lookup' || get( 'c' ) == 'vrn_lookup_results' ) { echo'class="active"'; } ?>><a href="dashboard.php?c=vrn_lookup" onclick="processing();">VRN Lookup</a></li>
								</ul>
							</li>
						<li>
							<a href="logout.php" onclick="processing();">
								<i class="fa fa-sign-out-alt bg-red"></i>
								<span>Sign Out</span> 
							</a>
						</li>
					</ul>

					<?php if( $admin_check || $staff_check ) { ?>
						
					<?php } ?>

					<?php if( $admin_check ) { ?>
						<ul class="nav"><li class="nav-header">Admin Section</li>
							<li <?php if( get( 'c' ) == 'user' || get( 'c' ) == 'users' ) { echo'class="active"'; } ?>>
								<a href="dashboard.php?c=users">
									<i class="fa fa-users"></i>
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

					case "jobs":
						jobs();
						break;

					case "job":
						job();
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

					case "vrn_lookup":
						vrn_lookup();
						break;

					case "vrn_lookup_results":
						vrn_lookup_results();
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
													<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

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
													<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Google Maps</h2>
							<div class="panel-heading-btn">

							</div>
						</div>
						<div class="panel-body">
							<div class="widget-map-body">
								<iframe class="d-block" src="https://www.google.com/maps?q=11+Augusta+St,+Accrington+BB5+2HX&output=embed" width="100%" height="230" frameborder="0" style="border:0" allowfullscreen></iframe>

								<hr>

								<iframe src="https://www.google.com/maps?q=11+Augusta+St,+Accrington+BB5+2HX&output=embed"></iframe>
							</div>
						</div>
					</div>

					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">divs</h2>
							<div class="panel-heading-btn">

							</div>
						</div>
						<div class="panel-body">
							<style>
								.row.display-flex {
									display: flex;
									flex-wrap: wrap;
								}
								
								.row.display-flex > [class*='col-'] {
									flex-grow: 1;
								}

								/* only for demo not required */
								.box {
									border:1px #666 solid;
									height: 100%;
								}

								.row.display-flex [class*='col-'] {
									background-color: #cceeee;
								}
							</style>
							<div class="container">
							    <div class="row display-flex">
							        <div class="col-lg-4 col-md-6 col-sm-12">
							            <div class="box">
							                <h3>Some text</h3>
							                <img src="//placehold.it/150x180" class="center-block">
							            </div>
							        </div>
							        <div class="col-lg-4 col-md-6 col-sm-12">
							            <div class="box">
							                <h3>Some text</h3>
							                <br>asdf
							                <br>asdf
							                <br>asdf
							                <br>asdf
							                <br>asdf
							                <img src="//placehold.it/150x150" class="center-block">
							            </div>
							        </div>
							        <div class="col-lg-4 col-md-6 col-sm-12">
							            <div class="box">
							                <h3>Some text more text that wraps to new link</h3>
							                <img src="//placehold.it/150x150" class="center-block">
							            </div>
							        </div>
							        <div class="col-lg-4 col-md-6 col-sm-12">
							            <div class="box">
							                <h3>Some text</h3>
							                <img src="//placehold.it/150x150" class="center-block">
							            </div>
							        </div>
							        <div class="col-lg-4 col-md-6 col-sm-12">
							            <div class="box">
							                <h3>Some text</h3>
							                <img src="//placehold.it/150x150" class="center-block">
							            </div>
							        </div>
							        <div class="col-lg-4 col-md-6 col-sm-12">
							            <div class="box">
							                <h3>Some text</h3>
							                <img src="//placehold.it/150x150" class="center-block">
							            </div>
							        </div>
							    </div>
							</div>
						</div>
					</div>

					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">VAT</h2>
							<div class="panel-heading-btn">

							</div>
						</div>
						<div class="panel-body">
							<?php
								$price_ex_vat = 1253;
								$price_inc_vat = vat_add( $price_ex_vat, 20 );
							?>
							Ex VAT = £<?php echo $price_ex_vat; ?> <br>
							Inc VAT = <?php echo $price_inc_vat; ?> <br>

							<hr>

							<?php
								//The VAT rate.
								$vat = 20;

								//Divisor (for our math).
								$vatDivisor = 1 + ($vat / 100);

								//The gross price, including VAT.
								$price = 1504;

								//Determine the price before VAT.
								$priceBeforeVat = $price / $vatDivisor;

								//Determine how much of the gross price was VAT.
								$vatAmount = $price - $priceBeforeVat;

								//Print out the price before VAT.
								echo number_format($priceBeforeVat, 2), '<br>';

								//Print out how much of the gross price was VAT.
								echo 'VAT @ ' . $vat . '% - ' . number_format($vatAmount, 2), '<br>';

								//Print out the gross price.
								echo $price;
								?>

								<hr>

								<?php
									$vat = vat_remove( '1504', '20' ); 
									debug( $vat );
								?>
						</div>
					</div>

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


			<!-- access denied view -->
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


			<!-- customer views -->
			<?php function customers() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<?php
					// get data
					$customers = get_customers();
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

					<!-- dev options -->
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
													<a class="btn btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<!-- customers -->
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Customers</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-primary" data-toggle="modal" data-target="#customer_add">Add Customer</button>
							</div>
						</div>
						<div class="panel-body">
							<?php if( !isset( $customers[0]['id'] ) ) { ?>
								<center>
									<h3>
										No customers found.
									</h3>
								</center>
							<?php } else { ?>
								<table id="table_customers" class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Company</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Primary Contact</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Jobs</strong></th>
											<th class="text-nowrap" data-orderable="false" width=""></th>
											<th class="text-nowrap" data-orderable="false" width="1px">Status</th>
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
													$customer['status'] = '<button class="btn btn-info btn-block">Pending</button>';
												} elseif( $customer['status'] == 'active' ) {
													$customer['status'] = '<button class="btn btn-success btn-block">Active</button>';
												} elseif( $customer['status'] == 'suspended' ) {
													$customer['status'] = '<button class="btn btn-warning btn-block">Suspended</button>';
												} elseif( $customer['status'] == 'terminated' ) {
													$customer['status'] = '<button class="btn btn-danger btn-block">Terminated</button>';
												}					

												// output
												echo '
													<tr>
														<td class="text-nowrap">
															<a href="?c=customer&id='.$customer['id'].'">'.$customer['id'].'</a>
														</td>
														<td class="text-nowrap">
															<a href="?c=customer&id='.$customer['id'].'">'.$customer['company_name'].'</a>
														</td>
														<td class="text-nowrap">
															'.$customer['primary_contact']['full_name'].' <br>
															<small>'.$customer['primary_contact']['phone'].'</small>
														</td>
														<td class="text-nowrap">
															'.$customer['total_jobs'].'
														</td>
														<td class="text-nowrap">
														</td>
														<td class="text-nowrap">
															'.$customer['status'].'
														</td>
														<td class="text-nowrap">
															<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
															<div class="dropdown-menu dropdown-menu-right" role="menu">
																<a href="?c=customer&id='.$customer['id'].'" class="dropdown-item">View / Edit</a>
																<a href="#" onclick="customer_delete( '.$customer['id'].' )" class="dropdown-item">Delete</a>
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
										<div class="col-xl-12 col-sm-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Company Name</strong></label>
												<input type="text" id="company_name" name="company_name" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>First Name</strong></label>
												<input type="text" id="first_name" name="first_name" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Last Name</strong></label>
												<input type="text" id="last_name" name="last_name" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Phone</strong></label>
												<input type="text" id="phone" name="phone" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Email</strong></label>
												<input type="email" id="email" name="email" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Address 1</strong></label>
												<input type="text" id="address_1" name="address_1" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Address 2</strong></label>
												<input type="text" id="address_2" name="address_2" class="form-control">
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>City</strong></label>
												<input type="text" id="address_city" name="address_city" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>County</strong></label>
												<input type="text" id="address_state" name="address_state" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Postcode</strong></label>
												<input type="text" id="address_zip" name="address_zip" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" onclick="processing();" class="btn btn-primary">Continue</button>
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
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>

			<?php function customer() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<?php 
					// get data
					$customer_id 	= get( 'id' );

					// sanity check
					if( empty( $customer_id ) ) {

					}
					$customer 		= get_customer( $customer_id );
					$users 			= get_users( 'customer' );
				?>

				<div id="content" class="content">
					<!-- sanity check -->
					<?php if( !isset( $customer['id'] ) ) { ?>
						<?php echo $not_found; ?>
					<?php } else { ?>
						<ol class="breadcrumb float-xl-right">
							<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="dashboard.php?c=customers">Customers</a></li>
							<li class="breadcrumb-item active"><?php echo $customer['company_name']; ?></li>
						</ol>

						<h1 class="page-header">Customer: <?php echo $customer['company_name']; ?></h1>

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
														<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

						<form class="form" method="post" action="actions.php?a=customer_edit">
							<input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">

							<!-- top summary boxes -->
							<div class="row">
								<div class="col-xl-2 col-xs-6">
									<div class="widget widget-stats bg-white text-inverse">
										<div class="stats-content">
											<div class="stats-title text-inverse-lighter">
												Total Jobs
											</div>
											<div class="stats-number"><?php echo number_format( count( $customer['jobs'] ) ); ?></div>
										</div>
									</div>
								</div>
								<div class="col-xl-2 col-xs-6">
									<div class="widget widget-stats bg-white text-inverse">
										<div class="stats-content">
											<div class="stats-title text-inverse-lighter">
												Approved Uplift
											</div>
											<div class="stats-number">£<?php echo number_format( $customer['total_approved_uplifts'], 2 ); ?></div>
										</div>
									</div>
								</div>

							</div>

							<div class="row">
								<!-- left column -->
								<div class="col-xl-6 col-sm-12">
									<!-- options -->
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Admin Options</h2>
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
														<select name="status" class="form-control select2">
															<option value="active" <?php if( $customer['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
															<option value="suspended" <?php if( $customer['status'] == 'suspended' ) { echo 'selected'; } ?> >Suspended</option>
															<option value="terminated" <?php if( $customer['status'] == 'terminated' ) { echo 'selected'; } ?> >Terminated</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								
									<!-- contact details -->
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Customer Details</h2>
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
														<input type="text" name="company_name" class="form-control" value="<?php echo $customer['company_name']; ?>">
														<small>Example: Mike's Body Shop</small>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Address 1</strong></label>
														<input type="text" name="address_1" class="form-control" value="<?php echo $customer['address_1']; ?>" required>
														<small>Example: 123 Awesome Street</small>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Address 2</strong></label>
														<input type="text" name="address_2" class="form-control" value="<?php echo $customer['address_2']; ?>">
														<small>Example: PO BOX 1</small>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>City</strong></label>
														<input type="text" name="address_city" class="form-control" value="<?php echo $customer['address_city']; ?>" required>
														<small>Example: Awesomeville</small>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>County</strong></label>
														<input type="text" name="address_state" class="form-control" value="<?php echo $customer['address_state']; ?>" required>
														<small>Example: Florida</small>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Postcode</strong></label>
														<input type="text" name="address_zip" class="form-control" value="<?php echo $customer['address_zip']; ?>" required>
														<small>Example: 12345</small>
													</div>
												</div>
												<div class="col-xl-3 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Country</strong></label>
														<select name="address_country" class="form-control select2">
															<option value="AF" <?php if( $customer['address_country'] == 'AF' ) { echo 'selected'; } ?> >Afghanistan</option>
															<option value="AX" <?php if( $customer['address_country'] == 'AX' ) { echo 'selected'; } ?> >Åland Islands</option>
															<option value="AL" <?php if( $customer['address_country'] == 'AL' ) { echo 'selected'; } ?> >Albania</option>
															<option value="DZ" <?php if( $customer['address_country'] == 'DZ' ) { echo 'selected'; } ?> >Algeria</option>
															<option value="AS" <?php if( $customer['address_country'] == 'AS' ) { echo 'selected'; } ?> >American Samoa</option>
															<option value="AD" <?php if( $customer['address_country'] == 'AD' ) { echo 'selected'; } ?> >Andorra</option>
															<option value="AO" <?php if( $customer['address_country'] == 'AO' ) { echo 'selected'; } ?> >Angola</option>
															<option value="AI" <?php if( $customer['address_country'] == 'AI' ) { echo 'selected'; } ?> >Anguilla</option>
															<option value="AQ" <?php if( $customer['address_country'] == 'AQ' ) { echo 'selected'; } ?> >Antarctica</option>
															<option value="AG" <?php if( $customer['address_country'] == 'AG' ) { echo 'selected'; } ?> >Antigua and Barbuda</option>
															<option value="AR" <?php if( $customer['address_country'] == 'AR' ) { echo 'selected'; } ?> >Argentina</option>
															<option value="AM" <?php if( $customer['address_country'] == 'AM' ) { echo 'selected'; } ?> >Armenia</option>
															<option value="AW" <?php if( $customer['address_country'] == 'AW' ) { echo 'selected'; } ?> >Aruba</option>
															<option value="AU" <?php if( $customer['address_country'] == 'AU' ) { echo 'selected'; } ?> >Australia</option>
															<option value="AT" <?php if( $customer['address_country'] == 'AT' ) { echo 'selected'; } ?> >Austria</option>
															<option value="AZ" <?php if( $customer['address_country'] == 'AZ' ) { echo 'selected'; } ?> >Azerbaijan</option>
															<option value="BS" <?php if( $customer['address_country'] == 'BS' ) { echo 'selected'; } ?> >Bahamas</option>
															<option value="BH" <?php if( $customer['address_country'] == 'BH' ) { echo 'selected'; } ?> >Bahrain</option>
															<option value="BD" <?php if( $customer['address_country'] == 'BD' ) { echo 'selected'; } ?> >Bangladesh</option>
															<option value="BB" <?php if( $customer['address_country'] == 'BB' ) { echo 'selected'; } ?> >Barbados</option>
															<option value="BY" <?php if( $customer['address_country'] == 'BY' ) { echo 'selected'; } ?> >Belarus</option>
															<option value="BE" <?php if( $customer['address_country'] == 'BE' ) { echo 'selected'; } ?> >Belgium</option>
															<option value="BZ" <?php if( $customer['address_country'] == 'BZ' ) { echo 'selected'; } ?> >Belize</option>
															<option value="BJ" <?php if( $customer['address_country'] == 'BJ' ) { echo 'selected'; } ?> >Benin</option>
															<option value="BM" <?php if( $customer['address_country'] == 'BM' ) { echo 'selected'; } ?> >Bermuda</option>
															<option value="BT" <?php if( $customer['address_country'] == 'BT' ) { echo 'selected'; } ?> >Bhutan</option>
															<option value="BO" <?php if( $customer['address_country'] == 'BO' ) { echo 'selected'; } ?> >Bolivia, Plurinational State of</option>
															<option value="BQ" <?php if( $customer['address_country'] == 'BQ' ) { echo 'selected'; } ?> >Bonaire, Sint Eustatius and Saba</option>
															<option value="BA" <?php if( $customer['address_country'] == 'BA' ) { echo 'selected'; } ?> >Bosnia and Herzegovina</option>
															<option value="BW" <?php if( $customer['address_country'] == 'BW' ) { echo 'selected'; } ?> >Botswana</option>
															<option value="BV" <?php if( $customer['address_country'] == 'BV' ) { echo 'selected'; } ?> >Bouvet Island</option>
															<option value="BR" <?php if( $customer['address_country'] == 'BR' ) { echo 'selected'; } ?> >Brazil</option>
															<option value="IO" <?php if( $customer['address_country'] == 'IO' ) { echo 'selected'; } ?> >British Indian Ocean Territory</option>
															<option value="BN" <?php if( $customer['address_country'] == 'BN' ) { echo 'selected'; } ?> >Brunei Darussalam</option>
															<option value="BG" <?php if( $customer['address_country'] == 'BG' ) { echo 'selected'; } ?> >Bulgaria</option>
															<option value="BF" <?php if( $customer['address_country'] == 'BF' ) { echo 'selected'; } ?> >Burkina Faso</option>
															<option value="BI" <?php if( $customer['address_country'] == 'BI' ) { echo 'selected'; } ?> >Burundi</option>
															<option value="KH" <?php if( $customer['address_country'] == 'KH' ) { echo 'selected'; } ?> >Cambodia</option>
															<option value="CM" <?php if( $customer['address_country'] == 'CM' ) { echo 'selected'; } ?> >Cameroon</option>
															<option value="CA" <?php if( $customer['address_country'] == 'CA' ) { echo 'selected'; } ?> >Canada</option>
															<option value="CV" <?php if( $customer['address_country'] == 'CV' ) { echo 'selected'; } ?> >Cape Verde</option>
															<option value="KY" <?php if( $customer['address_country'] == 'KY' ) { echo 'selected'; } ?> >Cayman Islands</option>
															<option value="CF" <?php if( $customer['address_country'] == 'CF' ) { echo 'selected'; } ?> >Central African Republic</option>
															<option value="TD" <?php if( $customer['address_country'] == 'TD' ) { echo 'selected'; } ?> >Chad</option>
															<option value="CL" <?php if( $customer['address_country'] == 'CL' ) { echo 'selected'; } ?> >Chile</option>
															<option value="CN" <?php if( $customer['address_country'] == 'CN' ) { echo 'selected'; } ?> >China</option>
															<option value="CX" <?php if( $customer['address_country'] == 'CX' ) { echo 'selected'; } ?> >Christmas Island</option>
															<option value="CC" <?php if( $customer['address_country'] == 'CC' ) { echo 'selected'; } ?> >Cocos (Keeling) Islands</option>
															<option value="CO" <?php if( $customer['address_country'] == 'CO' ) { echo 'selected'; } ?> >Colombia</option>
															<option value="KM" <?php if( $customer['address_country'] == 'KM' ) { echo 'selected'; } ?> >Comoros</option>
															<option value="CG" <?php if( $customer['address_country'] == 'CG' ) { echo 'selected'; } ?> >Congo</option>
															<option value="CD" <?php if( $customer['address_country'] == 'CD' ) { echo 'selected'; } ?> >Congo, the Democratic Republic of the</option>
															<option value="CK" <?php if( $customer['address_country'] == 'CK' ) { echo 'selected'; } ?> >Cook Islands</option>
															<option value="CR" <?php if( $customer['address_country'] == 'CR' ) { echo 'selected'; } ?> >Costa Rica</option>
															<option value="CI" <?php if( $customer['address_country'] == 'CI' ) { echo 'selected'; } ?> >Côte d'Ivoire</option>
															<option value="HR" <?php if( $customer['address_country'] == 'HR' ) { echo 'selected'; } ?> >Croatia</option>
															<option value="CU" <?php if( $customer['address_country'] == 'CU' ) { echo 'selected'; } ?> >Cuba</option>
															<option value="CW" <?php if( $customer['address_country'] == 'CW' ) { echo 'selected'; } ?> >Curaçao</option>
															<option value="CY" <?php if( $customer['address_country'] == 'CY' ) { echo 'selected'; } ?> >Cyprus</option>
															<option value="CZ" <?php if( $customer['address_country'] == 'CZ' ) { echo 'selected'; } ?> >Czech Republic</option>
															<option value="DK" <?php if( $customer['address_country'] == 'DK' ) { echo 'selected'; } ?> >Denmark</option>
															<option value="DJ" <?php if( $customer['address_country'] == 'DJ' ) { echo 'selected'; } ?> >Djibouti</option>
															<option value="DM" <?php if( $customer['address_country'] == 'DM' ) { echo 'selected'; } ?> >Dominica</option>
															<option value="DO" <?php if( $customer['address_country'] == 'DO' ) { echo 'selected'; } ?> >Dominican Republic</option>
															<option value="EC" <?php if( $customer['address_country'] == 'EC' ) { echo 'selected'; } ?> >Ecuador</option>
															<option value="EG" <?php if( $customer['address_country'] == 'EG' ) { echo 'selected'; } ?> >Egypt</option>
															<option value="SV" <?php if( $customer['address_country'] == 'SV' ) { echo 'selected'; } ?> >El Salvador</option>
															<option value="GQ" <?php if( $customer['address_country'] == 'GQ' ) { echo 'selected'; } ?> >Equatorial Guinea</option>
															<option value="ER" <?php if( $customer['address_country'] == 'ER' ) { echo 'selected'; } ?> >Eritrea</option>
															<option value="EE" <?php if( $customer['address_country'] == 'EE' ) { echo 'selected'; } ?> >Estonia</option>
															<option value="ET" <?php if( $customer['address_country'] == 'ET' ) { echo 'selected'; } ?> >Ethiopia</option>
															<option value="FK" <?php if( $customer['address_country'] == 'FK' ) { echo 'selected'; } ?> >Falkland Islands (Malvinas)</option>
															<option value="FO" <?php if( $customer['address_country'] == 'FO' ) { echo 'selected'; } ?> >Faroe Islands</option>
															<option value="FJ" <?php if( $customer['address_country'] == 'FJ' ) { echo 'selected'; } ?> >Fiji</option>
															<option value="FI" <?php if( $customer['address_country'] == 'FI' ) { echo 'selected'; } ?> >Finland</option>
															<option value="FR" <?php if( $customer['address_country'] == 'FR' ) { echo 'selected'; } ?> >France</option>
															<option value="GF" <?php if( $customer['address_country'] == 'GF' ) { echo 'selected'; } ?> >French Guiana</option>
															<option value="PF" <?php if( $customer['address_country'] == 'PF' ) { echo 'selected'; } ?> >French Polynesia</option>
															<option value="TF" <?php if( $customer['address_country'] == 'TF' ) { echo 'selected'; } ?> >French Southern Territories</option>
															<option value="GA" <?php if( $customer['address_country'] == 'GA' ) { echo 'selected'; } ?> >Gabon</option>
															<option value="GM" <?php if( $customer['address_country'] == 'GM' ) { echo 'selected'; } ?> >Gambia</option>
															<option value="GE" <?php if( $customer['address_country'] == 'GE' ) { echo 'selected'; } ?> >Georgia</option>
															<option value="DE" <?php if( $customer['address_country'] == 'DE' ) { echo 'selected'; } ?> >Germany</option>
															<option value="GH" <?php if( $customer['address_country'] == 'GH' ) { echo 'selected'; } ?> >Ghana</option>
															<option value="GI" <?php if( $customer['address_country'] == 'GI' ) { echo 'selected'; } ?> >Gibraltar</option>
															<option value="GR" <?php if( $customer['address_country'] == 'GR' ) { echo 'selected'; } ?> >Greece</option>
															<option value="GL" <?php if( $customer['address_country'] == 'GL' ) { echo 'selected'; } ?> >Greenland</option>
															<option value="GD" <?php if( $customer['address_country'] == 'GD' ) { echo 'selected'; } ?> >Grenada</option>
															<option value="GP" <?php if( $customer['address_country'] == 'GP' ) { echo 'selected'; } ?> >Guadeloupe</option>
															<option value="GU" <?php if( $customer['address_country'] == 'GU' ) { echo 'selected'; } ?> >Guam</option>
															<option value="GT" <?php if( $customer['address_country'] == 'GT' ) { echo 'selected'; } ?> >Guatemala</option>
															<option value="GG" <?php if( $customer['address_country'] == 'GG' ) { echo 'selected'; } ?> >Guernsey</option>
															<option value="GN" <?php if( $customer['address_country'] == 'GN' ) { echo 'selected'; } ?> >Guinea</option>
															<option value="GW" <?php if( $customer['address_country'] == 'GW' ) { echo 'selected'; } ?> >Guinea-Bissau</option>
															<option value="GY" <?php if( $customer['address_country'] == 'GY' ) { echo 'selected'; } ?> >Guyana</option>
															<option value="HT" <?php if( $customer['address_country'] == 'HT' ) { echo 'selected'; } ?> >Haiti</option>
															<option value="HM" <?php if( $customer['address_country'] == 'HM' ) { echo 'selected'; } ?> >Heard Island and McDonald Islands</option>
															<option value="VA" <?php if( $customer['address_country'] == 'VA' ) { echo 'selected'; } ?> >Holy See (Vatican City State)</option>
															<option value="HN" <?php if( $customer['address_country'] == 'HN' ) { echo 'selected'; } ?> >Honduras</option>
															<option value="HK" <?php if( $customer['address_country'] == 'HK' ) { echo 'selected'; } ?> >Hong Kong</option>
															<option value="HU" <?php if( $customer['address_country'] == 'HU' ) { echo 'selected'; } ?> >Hungary</option>
															<option value="IS" <?php if( $customer['address_country'] == 'IS' ) { echo 'selected'; } ?> >Iceland</option>
															<option value="IN" <?php if( $customer['address_country'] == 'IN' ) { echo 'selected'; } ?> >India</option>
															<option value="ID" <?php if( $customer['address_country'] == 'ID' ) { echo 'selected'; } ?> >Indonesia</option>
															<option value="IR" <?php if( $customer['address_country'] == 'IR' ) { echo 'selected'; } ?> >Iran, Islamic Republic of</option>
															<option value="IQ" <?php if( $customer['address_country'] == 'IQ' ) { echo 'selected'; } ?> >Iraq</option>
															<option value="IE" <?php if( $customer['address_country'] == 'IE' ) { echo 'selected'; } ?> >Ireland</option>
															<option value="IM" <?php if( $customer['address_country'] == 'IM' ) { echo 'selected'; } ?> >Isle of Man</option>
															<option value="IL" <?php if( $customer['address_country'] == 'IL' ) { echo 'selected'; } ?> >Israel</option>
															<option value="IT" <?php if( $customer['address_country'] == 'IT' ) { echo 'selected'; } ?> >Italy</option>
															<option value="JM" <?php if( $customer['address_country'] == 'JM' ) { echo 'selected'; } ?> >Jamaica</option>
															<option value="JP" <?php if( $customer['address_country'] == 'JP' ) { echo 'selected'; } ?> >Japan</option>
															<option value="JE" <?php if( $customer['address_country'] == 'JE' ) { echo 'selected'; } ?> >Jersey</option>
															<option value="JO" <?php if( $customer['address_country'] == 'JO' ) { echo 'selected'; } ?> >Jordan</option>
															<option value="KZ" <?php if( $customer['address_country'] == 'KZ' ) { echo 'selected'; } ?> >Kazakhstan</option>
															<option value="KE" <?php if( $customer['address_country'] == 'KE' ) { echo 'selected'; } ?> >Kenya</option>
															<option value="KI" <?php if( $customer['address_country'] == 'KI' ) { echo 'selected'; } ?> >Kiribati</option>
															<option value="KP" <?php if( $customer['address_country'] == 'KP' ) { echo 'selected'; } ?> >Korea, Democratic People's Republic of</option>
															<option value="KR" <?php if( $customer['address_country'] == 'KR' ) { echo 'selected'; } ?> >Korea, Republic of</option>
															<option value="KW" <?php if( $customer['address_country'] == 'KW' ) { echo 'selected'; } ?> >Kuwait</option>
															<option value="KG" <?php if( $customer['address_country'] == 'KG' ) { echo 'selected'; } ?> >Kyrgyzstan</option>
															<option value="LA" <?php if( $customer['address_country'] == 'LA' ) { echo 'selected'; } ?> >Lao People's Democratic Republic</option>
															<option value="LV" <?php if( $customer['address_country'] == 'LV' ) { echo 'selected'; } ?> >Latvia</option>
															<option value="LB" <?php if( $customer['address_country'] == 'LB' ) { echo 'selected'; } ?> >Lebanon</option>
															<option value="LS" <?php if( $customer['address_country'] == 'LS' ) { echo 'selected'; } ?> >Lesotho</option>
															<option value="LR" <?php if( $customer['address_country'] == 'LR' ) { echo 'selected'; } ?> >Liberia</option>
															<option value="LY" <?php if( $customer['address_country'] == 'LY' ) { echo 'selected'; } ?> >Libya</option>
															<option value="LI" <?php if( $customer['address_country'] == 'LI' ) { echo 'selected'; } ?> >Liechtenstein</option>
															<option value="LT" <?php if( $customer['address_country'] == 'LT' ) { echo 'selected'; } ?> >Lithuania</option>
															<option value="LU" <?php if( $customer['address_country'] == 'LU' ) { echo 'selected'; } ?> >Luxembourg</option>
															<option value="MO" <?php if( $customer['address_country'] == 'MO' ) { echo 'selected'; } ?> >Macao</option>
															<option value="MK" <?php if( $customer['address_country'] == 'MK' ) { echo 'selected'; } ?> >Macedonia, the former Yugoslav Republic of</option>
															<option value="MG" <?php if( $customer['address_country'] == 'MG' ) { echo 'selected'; } ?> >Madagascar</option>
															<option value="MW" <?php if( $customer['address_country'] == 'MW' ) { echo 'selected'; } ?> >Malawi</option>
															<option value="MY" <?php if( $customer['address_country'] == 'MY' ) { echo 'selected'; } ?> >Malaysia</option>
															<option value="MV" <?php if( $customer['address_country'] == 'MV' ) { echo 'selected'; } ?> >Maldives</option>
															<option value="ML" <?php if( $customer['address_country'] == 'ML' ) { echo 'selected'; } ?> >Mali</option>
															<option value="MT" <?php if( $customer['address_country'] == 'MT' ) { echo 'selected'; } ?> >Malta</option>
															<option value="MH" <?php if( $customer['address_country'] == 'MH' ) { echo 'selected'; } ?> >Marshall Islands</option>
															<option value="MQ" <?php if( $customer['address_country'] == 'MQ' ) { echo 'selected'; } ?> >Martinique</option>
															<option value="MR" <?php if( $customer['address_country'] == 'MR' ) { echo 'selected'; } ?> >Mauritania</option>
															<option value="MU" <?php if( $customer['address_country'] == 'MU' ) { echo 'selected'; } ?> >Mauritius</option>
															<option value="YT" <?php if( $customer['address_country'] == 'YT' ) { echo 'selected'; } ?> >Mayotte</option>
															<option value="MX" <?php if( $customer['address_country'] == 'MX' ) { echo 'selected'; } ?> >Mexico</option>
															<option value="FM" <?php if( $customer['address_country'] == 'FM' ) { echo 'selected'; } ?> >Micronesia, Federated States of</option>
															<option value="MD" <?php if( $customer['address_country'] == 'MD' ) { echo 'selected'; } ?> >Moldova, Republic of</option>
															<option value="MC" <?php if( $customer['address_country'] == 'MC' ) { echo 'selected'; } ?> >Monaco</option>
															<option value="MN" <?php if( $customer['address_country'] == 'MN' ) { echo 'selected'; } ?> >Mongolia</option>
															<option value="ME" <?php if( $customer['address_country'] == 'ME' ) { echo 'selected'; } ?> >Montenegro</option>
															<option value="MS" <?php if( $customer['address_country'] == 'MS' ) { echo 'selected'; } ?> >Montserrat</option>
															<option value="MA" <?php if( $customer['address_country'] == 'MA' ) { echo 'selected'; } ?> >Morocco</option>
															<option value="MZ" <?php if( $customer['address_country'] == 'MZ' ) { echo 'selected'; } ?> >Mozambique</option>
															<option value="MM" <?php if( $customer['address_country'] == 'MM' ) { echo 'selected'; } ?> >Myanmar</option>
															<option value="NA" <?php if( $customer['address_country'] == 'NA' ) { echo 'selected'; } ?> >Namibia</option>
															<option value="NR" <?php if( $customer['address_country'] == 'NR' ) { echo 'selected'; } ?> >Nauru</option>
															<option value="NP" <?php if( $customer['address_country'] == 'NP' ) { echo 'selected'; } ?> >Nepal</option>
															<option value="NL" <?php if( $customer['address_country'] == 'NL' ) { echo 'selected'; } ?> >Netherlands</option>
															<option value="NC" <?php if( $customer['address_country'] == 'NC' ) { echo 'selected'; } ?> >New Caledonia</option>
															<option value="NZ" <?php if( $customer['address_country'] == 'NZ' ) { echo 'selected'; } ?> >New Zealand</option>
															<option value="NI" <?php if( $customer['address_country'] == 'NI' ) { echo 'selected'; } ?> >Nicaragua</option>
															<option value="NE" <?php if( $customer['address_country'] == 'NE' ) { echo 'selected'; } ?> >Niger</option>
															<option value="NG" <?php if( $customer['address_country'] == 'NG' ) { echo 'selected'; } ?> >Nigeria</option>
															<option value="NU" <?php if( $customer['address_country'] == 'NU' ) { echo 'selected'; } ?> >Niue</option>
															<option value="NF" <?php if( $customer['address_country'] == 'NF' ) { echo 'selected'; } ?> >Norfolk Island</option>
															<option value="MP" <?php if( $customer['address_country'] == 'MP' ) { echo 'selected'; } ?> >Northern Mariana Islands</option>
															<option value="NO" <?php if( $customer['address_country'] == 'NO' ) { echo 'selected'; } ?> >Norway</option>
															<option value="OM" <?php if( $customer['address_country'] == 'OM' ) { echo 'selected'; } ?> >Oman</option>
															<option value="PK" <?php if( $customer['address_country'] == 'PK' ) { echo 'selected'; } ?> >Pakistan</option>
															<option value="PW" <?php if( $customer['address_country'] == 'PW' ) { echo 'selected'; } ?> >Palau</option>
															<option value="PS" <?php if( $customer['address_country'] == 'PS' ) { echo 'selected'; } ?> >Palestinian Territory, Occupied</option>
															<option value="PA" <?php if( $customer['address_country'] == 'PA' ) { echo 'selected'; } ?> >Panama</option>
															<option value="PG" <?php if( $customer['address_country'] == 'PG' ) { echo 'selected'; } ?> >Papua New Guinea</option>
															<option value="PY" <?php if( $customer['address_country'] == 'PY' ) { echo 'selected'; } ?> >Paraguay</option>
															<option value="PE" <?php if( $customer['address_country'] == 'PE' ) { echo 'selected'; } ?> >Peru</option>
															<option value="PH" <?php if( $customer['address_country'] == 'PH' ) { echo 'selected'; } ?> >Philippines</option>
															<option value="PN" <?php if( $customer['address_country'] == 'PN' ) { echo 'selected'; } ?> >Pitcairn</option>
															<option value="PL" <?php if( $customer['address_country'] == 'PL' ) { echo 'selected'; } ?> >Poland</option>
															<option value="PT" <?php if( $customer['address_country'] == 'PT' ) { echo 'selected'; } ?> >Portugal</option>
															<option value="PR" <?php if( $customer['address_country'] == 'PR' ) { echo 'selected'; } ?> >Puerto Rico</option>
															<option value="QA" <?php if( $customer['address_country'] == 'QA' ) { echo 'selected'; } ?> >Qatar</option>
															<option value="RE" <?php if( $customer['address_country'] == 'RE' ) { echo 'selected'; } ?> >Réunion</option>
															<option value="RO" <?php if( $customer['address_country'] == 'RO' ) { echo 'selected'; } ?> >Romania</option>
															<option value="RU" <?php if( $customer['address_country'] == 'RU' ) { echo 'selected'; } ?> >Russian Federation</option>
															<option value="RW" <?php if( $customer['address_country'] == 'RW' ) { echo 'selected'; } ?> >Rwanda</option>
															<option value="BL" <?php if( $customer['address_country'] == 'BL' ) { echo 'selected'; } ?> >Saint Barthélemy</option>
															<option value="SH" <?php if( $customer['address_country'] == 'SH' ) { echo 'selected'; } ?> >Saint Helena, Ascension and Tristan da Cunha</option>
															<option value="KN" <?php if( $customer['address_country'] == 'KN' ) { echo 'selected'; } ?> >Saint Kitts and Nevis</option>
															<option value="LC" <?php if( $customer['address_country'] == 'LC' ) { echo 'selected'; } ?> >Saint Lucia</option>
															<option value="MF" <?php if( $customer['address_country'] == 'MF' ) { echo 'selected'; } ?> >Saint Martin (French part)</option>
															<option value="PM" <?php if( $customer['address_country'] == 'PM' ) { echo 'selected'; } ?> >Saint Pierre and Miquelon</option>
															<option value="VC" <?php if( $customer['address_country'] == 'VC' ) { echo 'selected'; } ?> >Saint Vincent and the Grenadines</option>
															<option value="WS" <?php if( $customer['address_country'] == 'WS' ) { echo 'selected'; } ?> >Samoa</option>
															<option value="SM" <?php if( $customer['address_country'] == 'SM' ) { echo 'selected'; } ?> >San Marino</option>
															<option value="ST" <?php if( $customer['address_country'] == 'ST' ) { echo 'selected'; } ?> >Sao Tome and Principe</option>
															<option value="SA" <?php if( $customer['address_country'] == 'SA' ) { echo 'selected'; } ?> >Saudi Arabia</option>
															<option value="SN" <?php if( $customer['address_country'] == 'SN' ) { echo 'selected'; } ?> >Senegal</option>
															<option value="RS" <?php if( $customer['address_country'] == 'RS' ) { echo 'selected'; } ?> >Serbia</option>
															<option value="SC" <?php if( $customer['address_country'] == 'SC' ) { echo 'selected'; } ?> >Seychelles</option>
															<option value="SL" <?php if( $customer['address_country'] == 'SL' ) { echo 'selected'; } ?> >Sierra Leone</option>
															<option value="SG" <?php if( $customer['address_country'] == 'SG' ) { echo 'selected'; } ?> >Singapore</option>
															<option value="SX" <?php if( $customer['address_country'] == 'SX' ) { echo 'selected'; } ?> >Sint Maarten (Dutch part)</option>
															<option value="SK" <?php if( $customer['address_country'] == 'SK' ) { echo 'selected'; } ?> >Slovakia</option>
															<option value="SI" <?php if( $customer['address_country'] == 'SI' ) { echo 'selected'; } ?> >Slovenia</option>
															<option value="SB" <?php if( $customer['address_country'] == 'SB' ) { echo 'selected'; } ?> >Solomon Islands</option>
															<option value="SO" <?php if( $customer['address_country'] == 'SO' ) { echo 'selected'; } ?> >Somalia</option>
															<option value="ZA" <?php if( $customer['address_country'] == 'ZA' ) { echo 'selected'; } ?> >South Africa</option>
															<option value="GS" <?php if( $customer['address_country'] == 'GS' ) { echo 'selected'; } ?> >South Georgia and the South Sandwich Islands</option>
															<option value="SS" <?php if( $customer['address_country'] == 'SS' ) { echo 'selected'; } ?> >South Sudan</option>
															<option value="ES" <?php if( $customer['address_country'] == 'ES' ) { echo 'selected'; } ?> >Spain</option>
															<option value="LK" <?php if( $customer['address_country'] == 'LK' ) { echo 'selected'; } ?> >Sri Lanka</option>
															<option value="SD" <?php if( $customer['address_country'] == 'SD' ) { echo 'selected'; } ?> >Sudan</option>
															<option value="SR" <?php if( $customer['address_country'] == 'SR' ) { echo 'selected'; } ?> >Suriname</option>
															<option value="SJ" <?php if( $customer['address_country'] == 'SJ' ) { echo 'selected'; } ?> >Svalbard and Jan Mayen</option>
															<option value="SZ" <?php if( $customer['address_country'] == 'SZ' ) { echo 'selected'; } ?> >Swaziland</option>
															<option value="SE" <?php if( $customer['address_country'] == 'SE' ) { echo 'selected'; } ?> >Sweden</option>
															<option value="CH" <?php if( $customer['address_country'] == 'CH' ) { echo 'selected'; } ?> >Switzerland</option>
															<option value="SY" <?php if( $customer['address_country'] == 'SY' ) { echo 'selected'; } ?> >Syrian Arab Republic</option>
															<option value="TW" <?php if( $customer['address_country'] == 'TW' ) { echo 'selected'; } ?> >Taiwan, Province of China</option>
															<option value="TJ" <?php if( $customer['address_country'] == 'TJ' ) { echo 'selected'; } ?> >Tajikistan</option>
															<option value="TZ" <?php if( $customer['address_country'] == 'TZ' ) { echo 'selected'; } ?> >Tanzania, United Republic of</option>
															<option value="TH" <?php if( $customer['address_country'] == 'TH' ) { echo 'selected'; } ?> >Thailand</option>
															<option value="TL" <?php if( $customer['address_country'] == 'TL' ) { echo 'selected'; } ?> >Timor-Leste</option>
															<option value="TG" <?php if( $customer['address_country'] == 'TG' ) { echo 'selected'; } ?> >Togo</option>
															<option value="TK" <?php if( $customer['address_country'] == 'TK' ) { echo 'selected'; } ?> >Tokelau</option>
															<option value="TO" <?php if( $customer['address_country'] == 'TO' ) { echo 'selected'; } ?> >Tonga</option>
															<option value="TT" <?php if( $customer['address_country'] == 'TT' ) { echo 'selected'; } ?> >Trinidad and Tobago</option>
															<option value="TN" <?php if( $customer['address_country'] == 'TN' ) { echo 'selected'; } ?> >Tunisia</option>
															<option value="TR" <?php if( $customer['address_country'] == 'TR' ) { echo 'selected'; } ?> >Turkey</option>
															<option value="TM" <?php if( $customer['address_country'] == 'TM' ) { echo 'selected'; } ?> >Turkmenistan</option>
															<option value="TC" <?php if( $customer['address_country'] == 'TC' ) { echo 'selected'; } ?> >Turks and Caicos Islands</option>
															<option value="TV" <?php if( $customer['address_country'] == 'TV' ) { echo 'selected'; } ?> >Tuvalu</option>
															<option value="UG" <?php if( $customer['address_country'] == 'UG' ) { echo 'selected'; } ?> >Uganda</option>
															<option value="UA" <?php if( $customer['address_country'] == 'UA' ) { echo 'selected'; } ?> >Ukraine</option>
															<option value="AE" <?php if( $customer['address_country'] == 'AE' ) { echo 'selected'; } ?> >United Arab Emirates</option>
															<option value="GB" <?php if( $customer['address_country'] == 'GB' ) { echo 'selected'; } ?> >United Kingdom</option>
															<option value="US" <?php if( $customer['address_country'] == 'US' ) { echo 'selected'; } ?> >United States</option>
															<option value="UM" <?php if( $customer['address_country'] == 'UM' ) { echo 'selected'; } ?> >United States Minor Outlying Islands</option>
															<option value="UY" <?php if( $customer['address_country'] == 'UY' ) { echo 'selected'; } ?> >Uruguay</option>
															<option value="UZ" <?php if( $customer['address_country'] == 'UZ' ) { echo 'selected'; } ?> >Uzbekistan</option>
															<option value="VU" <?php if( $customer['address_country'] == 'VU' ) { echo 'selected'; } ?> >Vanuatu</option>
															<option value="VE" <?php if( $customer['address_country'] == 'VE' ) { echo 'selected'; } ?> >Venezuela, Bolivarian Republic of</option>
															<option value="VN" <?php if( $customer['address_country'] == 'VN' ) { echo 'selected'; } ?> >Viet Nam</option>
															<option value="VG" <?php if( $customer['address_country'] == 'VG' ) { echo 'selected'; } ?> >Virgin Islands, British</option>
															<option value="VI" <?php if( $customer['address_country'] == 'VI' ) { echo 'selected'; } ?> >Virgin Islands, U.S.</option>
															<option value="WF" <?php if( $customer['address_country'] == 'WF' ) { echo 'selected'; } ?> >Wallis and Futuna</option>
															<option value="EH" <?php if( $customer['address_country'] == 'EH' ) { echo 'selected'; } ?> >Western Sahara</option>
															<option value="YE" <?php if( $customer['address_country'] == 'YE' ) { echo 'selected'; } ?> >Yemen</option>
															<option value="ZM" <?php if( $customer['address_country'] == 'ZM' ) { echo 'selected'; } ?> >Zambia</option>
															<option value="ZW" <?php if( $customer['address_country'] == 'ZW' ) { echo 'selected'; } ?> >Zimbabwe</option>
														</select>
													</div>
												</div>
												<br>
												<hr>
												<br>
												<div class="col-xl-12 col-sm-12">
													<div class="widget-map-body">
														<iframe class="d-block" src="https://www.google.com/maps?q=<?php echo $customer['full_address']; ?>&output=embed" width="100%" height="230" frameborder="0" style="border:0" allowfullscreen></iframe>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- right column -->
								<div class="col-xl-6 col-sm-12">
									<!-- contacts -->
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
														<label class="bmd-label-floating"><strong>Primary</strong></label>
														<select name="primary_contact_id" id="primary_contact_id" class="form-control select2">
															<option value="" <?php if( empty( $customer['primary_contact_id'] ) ) { echo 'selected'; } ?> >None</option>
															<?php foreach( $users as $user ) { ?>
																<option value="<?php echo $user['id']; ?>" <?php if( $user['id'] == $customer['primary_contact_id'] ) { echo 'selected'; } ?> ><?php echo $user['full_name']; ?> - <?php echo $user['phone']; ?></option>
															<?php } ?>
														</select>
													</div>
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Secondary</strong></label>
														<select name="secondary_contact_id" id="secondary_contact_id" class="form-control select2">
															<option value="" <?php if( empty( $customer['secondary_contact_id'] ) ) { echo 'selected'; } ?> >None</option>
															<?php foreach( $users as $user ) { ?>
																<option value="<?php echo $user['id']; ?>" <?php if( $user['id'] == $customer['secondary_contact_id'] ) { echo 'selected'; } ?> ><?php echo $user['full_name']; ?> - <?php echo $user['phone']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- customer notes -->
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
														<label class="bmd-label-floating"><strong>Notes</strong> <small>(Internal use only. Not visible to customers.)</small></label>
														<textarea name="notes" id="notes" class="form-control" rows="7"><?php echo $customer['notes']; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
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
														<a href="?c=customers" type="button" onclick="processing();" class="btn btn-white">Back</a>
														<button type="submit" onclick="saving();" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- jobs -->
							<div class="row">
								<!-- jobs -->
								<div class="col-xl-12 col-sm-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Jobs</h2>
											<div class="panel-heading-btn">
												<button class="btn btn-primary" data-toggle="modal" data-target="#job_add">Add Job</button>
											</div>
										</div>
										<div class="panel-body">
											<?php if( !isset( $customer['jobs'][0]['id'] ) ) { ?>
												<center>
													<h3>
														No jobs found.
													</h3>
												</center>
											<?php } else { ?>
												<table id="table_jobs" class="table table-striped table-bordered table-td-valign-middle">
													<thead>
														<tr>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>Added</strong></th>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>VRN</strong></th>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>Estimator</strong></th>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>Initial Est</strong></th>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>Initial Uplift Est</strong></th>
															<th class="text-nowrap" data-orderable="false" width="1px"><strong>Approved Uplift Element</strong></th>
															<th class="text-nowrap" data-orderable="false" width=""></th>
															<th class="text-nowrap" data-orderable="false" width="1px">Status</th>
															<th class="text-nowrap" data-orderable="false" width="1px"></th>
														</tr>
													</thead>
													<tbody>
														<?php
															// build table
															foreach( $customer['jobs'] as $job ) {
																// status 
																$job['status_raw'] = $job['status'];
																if( $job['status'] == 'pending' ) {
																	$job['status'] = '<button class="btn btn-info btn-block">Pending</button>';
																} elseif( $job['status'] == 'active' ) {
																	$job['status'] = '<button class="btn btn-info btn-block">Active</button>';
																} elseif( $job['status'] == 'suspended' ) {
																	$job['status'] = '<button class="btn btn-danger btn-block">Cancelled</button>';
																} elseif( $job['status'] == 'complete' ) {
																	$job['status'] = '<button class="btn btn-success btn-block">Complete</button>';
																}				

																$initial_estimate = vat_details( $job['initial_estimate'] );
																$uplift_estimate = vat_details( $job['uplift_estimate'] );
																$approved_estimate = vat_details( $job['approved_estimate'] );

																// output
																echo '
																	<tr>
																		<td class="text-nowrap">
																			<a href="?c=job&id='.$job['id'].'">'.$job['id'].'</a>
																		</td>
																		<td class="text-nowrap">
																			'.date( "M dS Y", $job['added'] ).'
																		</td>
																		<td class="text-nowrap">
																			'.$job['vrn'].' <br>
																			'.$job['vrn_details']['year'].' '.$job['vrn_details']['make'].', '.$job['vrn_details']['model'].'
																		</td>
																		<td class="text-nowrap">
																			'.$job['estimator'].'
																		</td>
																		<td class="text-nowrap">
																			£'.number_format( $job['initial_estimate'], 2 ).'
																		</td>
																		<td class="text-nowrap">
																			£'.number_format( $job['uplift_estimate'], 2 ).'
																		</td>
																		<td class="text-nowrap">
																			£'.number_format( $job['approved_estimate'], 2 ).'
																		</td>
																		<td class="text-nowrap">
																		</td>
																		<td class="text-nowrap">
																			'.$job['status'].'
																		</td>
																		<td class="text-nowrap">
																			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
																			<div class="dropdown-menu dropdown-menu-right" role="menu">
																				<a href="?c=job&id='.$job['id'].'" class="dropdown-item">View / Edit</a>
																				<a href="#" onclick="job_delete( '.$job['id'].' );" class="dropdown-item">Delete</a>
																			</div>
																		</td>
																	</tr>
																';
															}
														?>
													</tbody>
												</table>
												<strong><font color="red">*</font></strong> Prices are excluding VAT unless otherwise stated.
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</form>
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
						 				<h2>$customer</h2>
										<?php debug( $customer ); ?>

										<h2>$users</h2>
										<?php debug( $users ); ?>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>

				<!-- add job modal -->
				<form class="form" method="post" action="actions.php?a=job_add">
					<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
					<div class="modal fade" id="job_add" tabindex="-1" role="dialog" aria-labelledby="job_add" aria-hidden="true">
					   	<div class="modal-dialog modal-notice">
						  	<div class="modal-content">
							 	<div class="modal-header">
									<h5 class="modal-title" id="myModalLabel">Add Job</h5>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
										x
									</button>
							 	</div>
							 	<div class="modal-body">
							 		<div class="row">
										<div class="col-xl-6 col-sm-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>VRN</strong></label>
												<input type="text" id="vrn" name="vrn" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-sm-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Initial Est Inc VAT</strong></label>
												<input type="text" id="initial_estimate" name="initial_estimate" class="form-control" placeholder="1503.24" required>
											</div>
										</div>
									</div>
							 	</div>
							 	<div class="modal-footer">
							 		<div class="btn-group">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" onclick="processing();" class="btn btn-primary">Continue</button>
									</div>
								</div>
						  	</div>
					   	</div>
					</div>
				</form>
			<?php } ?>


			<!-- home view -->
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
													<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<!-- admin / staff dashboard tiles -->
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
									<div class="stats-icon stats-icon-square bg-gradient-green text-white d-none d-sm-block"><i class="fa fa-car"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Pending Jobs
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Pending Jobs" data-placement="top" data-content="Jobs that have yet to be approved."></i></span>
										</div>
										<div class="stats-number"><?php echo $stats['pending_jobs']; ?></div>
										<div class="stats-progress progress">
											<div class="progress-bar" style="width: 100%;"></div>
										</div>
										<div class="stats-desc text-inverse-lighter"><a href="?c=jobs">Find out more ...</a></div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-green text-white d-none d-sm-block"><i class="fa fa-car"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Total Jobs
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Total Jobs" data-placement="top" data-content="Includes all jobs from all customers."></i></span>
										</div>
										<div class="stats-number"><?php echo $stats['total_jobs']; ?></div>
										<div class="stats-progress progress">
											<div class="progress-bar" style="width: 100%;"></div>
										</div>
										<div class="stats-desc text-inverse-lighter"><a href="?c=jobs">Find out more ...</a></div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-orange text-white d-none d-sm-block"><i class="fa fa-users"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Total Customers
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Total Customers" data-placement="top" data-content="Includes active and inactive customer accounts."></i></span>
										</div>
										<div class="stats-number"><?php echo $stats['total_customers']; ?></div>
										<div class="stats-progress progress">
											<div class="progress-bar" style="width: 100%;"></div>
										</div>
										<div class="stats-desc text-inverse-lighter"><a href="?c=customers">Find out more ...</a></div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-purple text-white d-none d-sm-block"><i class="fa fa-address-card"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Total Providers
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Total Providers" data-placement="top" data-content="Includes active and inactive providers."></i></span>
										</div>
										<div class="stats-number"><?php echo $stats['total_providers']; ?></div>
										<div class="stats-progress progress">
											<div class="progress-bar" style="width: 100%;"></div>
										</div>
										<div class="stats-desc text-inverse-lighter"><a href="?c=providers">Find out more ...</a></div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<!-- stats -->
					<div class="row">
						<div class="col-xl-4 col-sm-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h2 class="panel-title">Software Updates</h2>
									<div class="panel-heading-btn">
										<button class="btn btn-primary" data-toggle="modal" data-target="#contact_genex">Contact Genex Support</button>
									</div>
								</div>
								<div class="panel-body">
									<dl>
										<dt class="text-inverse">Aug 24 2022 @ 13:00</dt>
										<dd>Initial platform release.</dd>
									</dl>
								</div>
							</div>
						</div>

						<div class="col-xl-4 col-sm-12">
							<div id="highchart_container"></div>
						</div>
					</div>

					<!-- other bits -->
					<div class="row">
						<div class="col-xl-4 col-sm-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h2 class="panel-title">Software Updates</h2>
									<div class="panel-heading-btn">
										<button class="btn btn-primary" data-toggle="modal" data-target="#contact_genex">Contact Genex Support</button>
									</div>
								</div>
								<div class="panel-body">
									<dl>
										<dt class="text-inverse">Aug 24 2022 @ 13:00</dt>
										<dd>Initial platform release.</dd>
									</dl>
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
						 				Nothing to show.
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>

				<!-- contact genex modal -->
				<div class="modal fade" id="contact_genex" tabindex="-1" role="dialog" aria-labelledby="contact_genex" aria-hidden="true">
				   	<div class="modal-dialog modal-xl">
					  	<div class="modal-content">
						 	<div class="modal-header">
								<h5 class="modal-title" id="myModalLabel">Contact Genex Support</h5>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									x
								</button>
						 	</div>
						 	<div class="modal-body">
						 		<div class="row">
						 			<div class="col-xl-12 col-sm-12">
						 				<strong>Email:</strong> <a href="mailto:jamie.whittingham@gmail.com">jamie.whittingham@gmail.com</a> <br>
						 				<strong>Phone:</strong> <a href="tel:+447399973949">+44 (0) 7399 973949</a>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>


			<!-- job views -->
			<?php function jobs() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<?php
					// get data
					$jobs 	= get_all_jobs();
					$customers = get_customers();
				?>

				<div id="content" class="content">
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
						<li class="breadcrumb-item active">Jobs</li>
					</ol>
					
					<h1 class="page-header">Jobs</h1>

					<div class="row">
						<div class="col-xl-12">
							<div id="status_message"></div><div id="kyc_status_message"></div>
						</div>
					</div>

					<!-- dev options -->
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
													<a class="btn btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<!-- customers -->
					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">Jobs</h2>
							<div class="panel-heading-btn">
								<button class="btn btn-primary" data-toggle="modal" data-target="#job_add">Add Job</button>
							</div>
						</div>
						<div class="panel-body">
							<?php if( !isset( $jobs[0]['id'] ) ) { ?>
								<center>
									<h3>
										No jobs found.
									</h3>
								</center>
							<?php } else { ?>
								<table id="table_jobs" class="table table-striped table-bordered table-td-valign-middle">
									<thead>
										<tr>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>ID</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Added</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Customer</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>VRN</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Estimator</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Initial Est</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Initial Uplift Est</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Approved Uplift Element</strong></th>
											<th class="text-nowrap" data-orderable="false" width=""></th>
											<th class="text-nowrap" data-orderable="false" width="1px"><strong>Status</strong></th>
											<th class="text-nowrap" data-orderable="false" width="1px"></th>
										</tr>
									</thead>
									<tbody>
										<?php
											// build table
											foreach( $jobs as $job ) {
												// status 
												$job['status_raw'] = $job['status'];
												if( $job['status'] == 'pending' ) {
													$job['status'] = '<button class="btn btn-info btn-block">Pending</button>';
												} elseif( $job['status'] == 'active' ) {
													$job['status'] = '<button class="btn btn-info btn-block">Active</button>';
												} elseif( $job['status'] == 'suspended' ) {
													$job['status'] = '<button class="btn btn-danger btn-block">Cancelled</button>';
												} elseif( $job['status'] == 'complete' ) {
													$job['status'] = '<button class="btn btn-success btn-block">Complete</button>';
												}	

												// cal vat details
												$initial_estimate = vat_details( $job['initial_estimate'] );
												$uplift_estimate = vat_details( $job['uplift_estimate'] );
												$approved_estimate = vat_details( $job['approved_estimate'] );

												// build revised estimate
												$revised_estimate = $initial_estimate['ex_vat'] + $uplift_estimate['ex_vat'];


												// output
												echo '
													<tr>
														<td class="text-nowrap">
															<a href="?c=job&id='.$job['id'].'">'.$job['id'].'</a>
														</td>
														<td class="text-nowrap">
															'.date( "Y-m-d H:i:s", $job['added'] ).'
														</td>
														<td class="text-nowrap">
															<a href="?c=customer&id='.$job['customer_id'].'">'.$job['customer']['company_name'].'</a>
														</td>
														<td class="text-nowrap">
															'.$job['vrn'].' <br>
															'.$job['vrn_details']['year'].' '.$job['vrn_details']['make'].', '.$job['vrn_details']['model'].'
														</td>
														<td class="text-nowrap">
															'.$job['estimator'].'
														</td>
														<td class="text-nowrap">
															£'.number_format( $job['initial_estimate'], 2 ).'
														</td>
														<td class="text-nowrap">
															£'.number_format( $job['uplift_estimate'], 2 ).'
														</td>
														<td class="text-nowrap">
															£'.number_format( $job['approved_estimate'], 2 ).'
														</td>
														<td class="text-nowrap">
														</td>
														<td class="text-nowrap">
															'.$job['status'].'
														</td>
														<td class="text-nowrap">
															<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
															<div class="dropdown-menu dropdown-menu-right" role="menu">
																<a href="?c=job&id='.$job['id'].'" class="dropdown-item">View / Edit</a>
																<a href="#" onclick="job_delete( '.$job['id'].' )" class="dropdown-item">Delete</a>
															</div>
														</td>
													</tr>
												';
											}
										?>
									</tbody>
								</table>
								<strong><font color="red">*</font></strong> Prices are excluding VAT unless otherwise stated.
							<?php } ?>
						</div>
					</div>
				</div>

				<!-- add job modal -->
				<form class="form" method="post" action="actions.php?a=job_add">
					<div class="modal fade" id="job_add" tabindex="-1" role="dialog" aria-labelledby="job_add" aria-hidden="true">
					   	<div class="modal-dialog modal-notice">
						  	<div class="modal-content">
							 	<div class="modal-header">
									<h5 class="modal-title" id="myModalLabel">Add Job</h5>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
										x
									</button>
							 	</div>
							 	<div class="modal-body">
							 		<div class="row">
							 			<div class="col-xl-12 col-sm-12">
							 				<div class="form-group">
												<label class="bmd-label-floating"><strong>Customer</strong></label>
												<select name="customer_id" class="form-control select2">
													<?php foreach( $customers as $customer ) { ?>
														<option value="<?php echo $customer['id']; ?>"><?php echo $customer['company_name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-xl-6 col-sm-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>VRN</strong></label>
												<input type="text" id="vrn" name="vrn" class="form-control" required>
											</div>
										</div>
										<div class="col-xl-6 col-sm-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Initial Est Inc VAT</strong></label>
												<input type="text" id="initial_estimate" name="initial_estimate" class="form-control" placeholder="1503.24" required>
											</div>
										</div>
									</div>
							 	</div>
							 	<div class="modal-footer">
							 		<div class="btn-group">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" onclick="processing();" class="btn btn-primary">Continue</button>
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
										<?php debug( $jobs ); ?>
										<?php debug( $customers ); ?>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>

			<?php function job() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<?php 
					// get data
					$job_id 		= get( 'id' );

					// sanity check
					if( empty( $job_id ) ) {

					}
					$job 			= get_job( $job_id );
					$providers 		= get_providers();

					$markup 		= $job['uplift_estimate'];
					$profit 		= $job['approved_estimate'];
					$revised_estimate = ( $job['initial_estimate'] + $job['uplift_estimate'] );
					// $approved_estimate = ( $job['initial_estimate'] + $job['approved_estimate'] );
				?>

				<div id="content" class="content">
					<!-- sanity check -->
					<?php if( !isset( $job['id'] ) ) { ?>
						<?php echo $not_found; ?>
					<?php } else { ?>
						<ol class="breadcrumb float-xl-right">
							<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="dashboard.php?c=jobs">Jobs</a></li>
							<li class="breadcrumb-item active">ID: <?php echo $job['id']; ?> / VRN: <?php echo $job['vrn']; ?></li>
						</ol>

						<h1 class="page-header">ID: <?php echo $job['id']; ?> / VRN: <?php echo $job['vrn']; ?></h1>

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
														<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

						<!-- top summary boxes -->
						<div class="row">
							<div class="col-xl-2 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-info text-white d-none d-sm-block"><i class="fa fa-file"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Initial Estimate
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Initial Estimate" data-placement="top" data-content="This is the initial estimate submitted by the customer. This figure excludes VAT."></i></span>
										</div>
										<div class="stats-number">£<?php echo number_format( $job['initial_estimate'], 2 ); ?></div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-orange text-white d-none d-sm-block"><i class="fa fa-check-square"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Initial Uplift Estimate
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Initial Uplift Estimate" data-placement="top" data-content="This is the initial uplift estimate. This figure excludes VAT."></i></span>
										</div>
										<div class="stats-number">£<?php echo number_format( $job['uplift_estimate'], 2 ); ?></div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-green text-white d-none d-sm-block"><i class="fa fa-check"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Approved Uplift Element
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Approved Uplift Element" data-placement="top" data-content="This is the approved uplift element. This figure excludes VAT."></i></span>
										</div>
										<div class="stats-number">£<?php echo number_format( $job['approved_estimate'], 2 ); ?></div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-green text-white d-none d-sm-block"><i class="fa fa-chart-line"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Revised Estimate
											<span class="ml-2 d-none d-sm-block"><i class="fa fa-info-circle" data-toggle="popover" data-trigger="hover" data-title="Revised Estimate" data-placement="top" data-content="This is the revised estimate sent to the customer. This figure excludes VAT."></i></span>
										</div>
										<div class="stats-number">£<?php echo number_format( $revised_estimate, 2 ); ?></div>
									</div>
								</div>
							</div>
							<div class="col-xl-2 col-xs-6">
								<div class="widget widget-stats bg-white text-inverse">
									<div class="stats-icon stats-icon-square bg-gradient-green text-white d-none d-sm-block"><i class="fa fa-check-circle"></i></div>
									<div class="stats-content">
										<div class="stats-title text-inverse-lighter">
											Profit
										</div>
										<div class="stats-number">£<?php echo number_format( $profit, 2 ); ?></div>
									</div>
								</div>
							</div>
						</div>

						<form class="form" method="post" action="actions.php?a=job_edit">
							<input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">

							<!-- customer / job notes -->
							<div class="row">
								<div class="col-xl-6 col-sm-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Customer</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Name</strong></small></label>
														<div class="row">
															<div class="col-xl-4 col-sm-12">
																<input type="text" name="company_name" class="form-control" value="<?php echo $job['customer']['company_name']; ?>" readonly>
															</div>
															<div class="col-xl-4 col-sm-12">
																<input type="text" name="address" class="form-control" value="<?php echo $job['customer']['address_1']; ?>, " readonly>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-xl-12 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Primary Contact</strong></small></label>
														<div class="row">
															<div class="col-xl-4 col-sm-12">
																<input type="text" name="full_name" class="form-control" value="<?php echo $job['customer']['primary_contact']['full_name']; ?>" readonly>
															</div>
															<div class="col-xl-4 col-sm-12">
																<input type="text" name="phone" class="form-control" value="<?php echo $job['customer']['primary_contact']['phone']; ?>" readonly>
															</div>
															<div class="col-xl-4 col-sm-12">
																<input type="text" name="email" class="form-control" value="<?php echo $job['customer']['primary_contact']['email']; ?>" readonly>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-6 col-sm-12">
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
														<label class="bmd-label-floating"><strong>Notes</strong> <small>(Internal use only. Not visible to customers.)</small></label>
														<textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $job['notes']; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- job options -->
							<div class="row">
								<div class="col-xl-12 col-sm-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Job Options</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-1 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Added</strong></label>
														<input type="text" name="added" class="form-control" value="<?php echo date( "Y-m-d H:i:s", $job['added'] ); ?>" readonly>
													</div>
												</div>
												<div class="col-xl-1 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Last Updated</strong></label>
														<input type="text" name="updated" class="form-control" value="<?php echo date( "Y-m-d H:i:s", $job['updated'] ); ?>" readonly>
													</div>
												</div>
												<div class="col-xl-1 col-sm-12">
													<div class="form-group">
														<?php if( $job['status'] == 'cancelled' || $job['status'] == 'complete' ) { ?>
															<label class="bmd-label-floating"><strong>Completion Time</strong></label>
														<?php } else { ?>
															<label class="bmd-label-floating"><strong>Job Age</strong></label>
														<?php } ?>
														<input type="text" class="form-control" value="<?php echo $job['job_age']; ?> day(s)" readonly>
													</div>
												</div>
												<div class="col-xl-1 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Status</strong></label>
														<select name="status" class="form-control select2">
															<option value="active" <?php if( $job['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
															<option value="cancelled" <?php if( $job['status'] == 'cancelled' ) { echo 'selected'; } ?> >Cancelled</option>
															<option value="pending" <?php if( $job['status'] == 'pending' ) { echo 'selected'; } ?> >Pending</option>
															<option value="complete" <?php if( $job['status'] == 'complete' ) { echo 'selected'; } ?> >Complete</option>
														</select>
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Provider</strong></label>
														<select name="provider_id" class="form-control select2">
															<?php foreach( $providers as $provider ) { ?>
																<option value="<?php echo $provider['id']; ?>" <?php if( $provider['id'] == $job['provider_id'] ) { echo 'selected'; } ?> ><?php echo $provider['name']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Initial Estimate Ex VAT</strong></label>
														<input type="text" name="initial_estimate" class="form-control" value="<?php echo $job['initial_estimate']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Estimator</strong></label>
														<input type="text" name="estimator" class="form-control" value="<?php echo $job['estimator']; ?>" placeholder="Joe Bloggs">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- initial uplift estimate -->
							<div class="row">
								<div class="col-xl-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Initial Uplift Breakdown</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Labour (£)</strong></label>
														<input type="text" name="uplift_labour" class="form-control" value="<?php echo $job['uplift_labour']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Paint (£)</strong></label>
														<input type="text" name="uplift_paint" class="form-control" value="<?php echo $job['uplift_paint']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Additional (£)</strong></label>
														<input type="text" name="uplift_additional" class="form-control" value="<?php echo $job['uplift_additional']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Parts (£)</strong></label>
														<input type="text" name="uplift_parts" class="form-control" value="<?php echo $job['uplift_parts']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Check (£)</strong></label>
														<input type="text" name="uplift_check" class="form-control" value="<?php echo $job['uplift_check']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Total (£)</strong></label>
														<input type="text" name="uplift_total" class="form-control" value="<?php echo number_format( $job['uplift_estimate'], 2 ); ?>" readonly>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- approved uplift estimate -->
							<div class="row">
								<div class="col-xl-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h2 class="panel-title">Approved Uplift Breakdown</h2>
											<div class="panel-heading-btn">
												<div class="btn-group">
													
												</div>
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Labour (£)</strong></label>
														<input type="text" name="approved_labour" class="form-control" value="<?php echo $job['approved_labour']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Paint (£)</strong></label>
														<input type="text" name="approved_paint" class="form-control" value="<?php echo $job['approved_paint']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Additional (£)</strong></label>
														<input type="text" name="approved_additional" class="form-control" value="<?php echo $job['approved_additional']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Parts (£)</strong></label>
														<input type="text" name="approved_parts" class="form-control" value="<?php echo $job['approved_parts']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Check (£)</strong></label>
														<input type="text" name="approved_check" class="form-control" value="<?php echo $job['approved_check']; ?>" placeholder="0.00">
													</div>
												</div>
												<div class="col-xl-2 col-sm-12">
													<div class="form-group">
														<label class="bmd-label-floating"><strong>Total (£)</strong></label>
														<input type="text" name="approved_total" class="form-control" value="<?php echo number_format( $job['approved_estimate'], 2 ); ?>" readonly>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							

							<!-- form options -->
							<div class="row">
								<div class="col-xl-12">
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<div class="col-xl-8 col-xs-12">
												</div>
												<div class="col-xl-4 col-xs-12 text-right">
													<div class="btn-group">
														<a href="javascript:history.back();" type="button" onclick="processing();" class="btn btn-white">Back</a>
														<button type="submit" onclick="saving();" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
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
						 				<h2>$job</h2>
										<?php debug( $job ); ?>
										<h2>$providers</h2>
										<?php debug( $providers ); ?>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>


			<!-- user functions -->
			<?php function users() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<?php
					// get data
					$users = get_users();
				?>

				<div id="content" class="content">
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
						<li class="breadcrumb-item active">Users</li>
					</ol>

					<h1 class="page-header">Users</h1>

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
													<a class="btn btn-purple text-white" data-toggle="modal" data-target="#dev_modal">Dev</a>
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
									<h2 class="panel-title">Users</h2>
									<div class="panel-heading-btn">
										<div class="btn-group">
											<button class="btn btn-primary" data-toggle="modal" data-target="#user_add">Add User</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<table id="table_users" class="table table-striped table-bordered table-td-valign-middle">
										<thead>
											<tr>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>ID</strong></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Name</strong></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Type</strong></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Email</strong></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Phone</strong></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Address</strong></th>
												<th class="text-nowrap" data-orderable="false" width=""></th>
												<th class="text-nowrap" data-orderable="true" width="1px"><strong>Status</strong></th>
												<th class="text-nowrap" data-orderable="false" width="1px"></th>
											</tr>
										</thead>
										<tbody>
											<?php
												// build table
												foreach( $users as $user ) {
													// user status
													if( $user['status'] == 'active' ) {
														$user['account_status'] = '<button class="btn btn-success btn-block">Active</button>';
													} elseif( $user['status'] == 'suspended' ) {
														$user['account_status'] = '<button class="btn btn-warning btn-block">Suspended</button>';
													} elseif( $user['status'] == 'terminated' ) {
														$user['account_status'] = '<button class="btn btn-danger btn-block">Terminated</button>';
													} elseif( $user['status'] == 'pending' ) {
														$user['account_status'] = '<button class="btn btn-info btn-block">Terminated</button>';
													}

													// account type
													if( $user['type'] == 'admin' ) {
														$user['type_button'] = '<button class="btn btn-lime btn-block">Admin</button>';
													} elseif( $user['type'] == 'staff' ) {
														$user['type_button'] = '<button class="btn btn-warning btn-block">Staff</button>';
													} elseif( $user['type'] == 'customer' ) {
														$user['type_button'] = '<button class="btn btn-info btn-block">Customer</button>';
													}

													// output
													echo '
														<tr>
															<td class="text-nowrap">
																<a href="?c=user&id='.$user['id'].'">'.$user['id'].'</a>
															</td>
															<td class="text-nowrap">
																<a href="?c=user&id='.$user['id'].'">'.$user['full_name'].'</a>
															</td>
															<td class="text-nowrap">
																'.$user['type_button'].'
															</td>
															<td class="text-nowrap">
																<a href="mailto:'.$user['email'].'">'.$user['email'].'</a>
															</td>
															<td class="text-nowrap">
																<a href="tel:'.$user['phone'].'">'.$user['phone'].'</a>
															</td>
															<td class="text-nowrap">
																'.$user['full_address'].'
															</td>
															<td class="text-nowrap">
															</td>
															<td class="text-nowrap">
																'.$user['account_status'].'
															</td>
															<td class="text-nowrap">
																<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions<b class="caret"></b></button>
																<div class="dropdown-menu dropdown-menu-right" role="menu">
																	<a href="?c=user&id='.$user['id'].'" class="dropdown-item">View / Edit</a>
																	'.( $user['id'] != $account_details['id'] ? '<a href="#" onclick="user_delete( '.$user['id'].' )" class="dropdown-item">Delete</a>' : '' ).'
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
												<label class="bmd-label-floating"><strong>Phone</strong></label>
												<input type="text" name="phone" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xl-12 col-sm-12">
											<div class="form-group">
												<label class="bmd-label-floating"><strong>Account Type</strong></label>
												<select name="type" class="form-control">
													<option value="customer" selected>Customer</option>
													<option value="staff">Staff Member</option>
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
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" onclick="processing();" class="btn btn-primary">Continue</button>
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
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
					} else{
						$user = account_details( $account_details['id'] );
					}

					$customers = get_customers();
				?>

				<div id="content" class="content">
					<!-- sanity check -->
					<?php if( !isset( $user['id'] ) ) { ?>
						<?php echo $not_found; ?>
					<?php } else { ?>
						<ol class="breadcrumb float-xl-right">
							<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="dashboard.php?c=users">Users</a></li>
							<li class="breadcrumb-item active"><?php echo $user['full_name']; ?></li>
						</ol>

						<h1 class="page-header">User: <?php echo $user['full_name']; ?></h1>

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
														<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

						<form class="form" method="post" action="actions.php?a=user_edit">
							<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

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
													<div class="col-xl-1 col-lg-1 col-md-1 col-sm-12 col-xs-12">
														<div class="form-group">
															<label class="bmd-label-floating"><strong>Account Status</strong></label>
															<select name="status" class="form-control">
																<option value="active" <?php if( $user['status'] == 'active' ) { echo 'selected'; } ?> >Active</option>
																<option value="terminated" <?php if( $user['status'] == 'pending' ) { echo 'selected'; } ?> >Pending</option>
																<option value="suspended" <?php if( $user['status'] == 'suspended' ) { echo 'selected'; } ?> >Suspended</option>
																<option value="terminated" <?php if( $user['status'] == 'terminated' ) { echo 'selected'; } ?> >Terminated</option>
															</select>
														</div>
													</div>

													<div class="col-xl-1 col-lg-1 col-md-1 col-sm-12 col-xs-12">
														<div class="form-group">
															<label class="bmd-label-floating"><strong>Account Type</strong></label>
															<select name="type" class="form-control">
																<option value="customer" <?php if( $user['type'] == 'customer' ) { echo 'selected'; } ?> >Customer</option>
																<option value="staff" <?php if( $user['type'] == 'staff' ) { echo 'selected'; } ?> >Staff Member</option>
																<?php if( $admin_check ) { ?>
																	<option value="admin" <?php if( $user['type'] == 'admin' ) { echo 'selected'; } ?>>Admin</option>
																<?php } ?>
															</select>
														</div>
													</div>
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
														<label class="bmd-label-floating"><strong>Phone</strong></label>
														<input type="text" name="tel_landline" class="form-control" value="<?php echo $user['phone']; ?>">
														<small>Example: +44 (0) 1254 745560</small>
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
														<input type="text" name="password" class="form-control" value="<?php echo $user['password']; ?>" required>
														<small>Only use letters and numbers. Min 8 characters.</small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-xl-6 col-sm-12">
									<!-- notes -->
									<?php if( $admin_check ) { ?>
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
															<label class="bmd-label-floating"><strong>Notes</strong> <small>(Admin use only. Only visible to admin users.)</small></label>
															<textarea name="notes" class="form-control" rows="7"><?php echo $user['notes']; ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
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
														<a href="?c=users" type="button" onclick="processing();" class="btn btn-white">Back</a>
														<button type="submit" onclick="saving();" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
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
										<?php debug( $user ); ?>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>


			<!-- vrn views -->
			<?php function vrn_lookup() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<div id="content" class="content">
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php">Tools</a></li>
						<li class="breadcrumb-item active">VRN Lookup</li>
					</ol>
					
					<h1 class="page-header">VRN Lookup</h1>

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
													<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">VRN Lookup</h2>
							<div class="panel-heading-btn">

							</div>
						</div>
						<div class="panel-body">
							Enter a UK license plate number
							<form class="form" method="post" action="actions.php?a=vrn_lookup">
								<div class="form-group input-group-append">
									<input type="text" name="vrn" id="vrn" class="form-control" placeholder="eg: ND08 NVK" />
									<button type="submit" onclick="processing();" class="btn btn-success">Search</button>
								</div>
							</form>
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
										Nothing to show.
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>

			<?php function vrn_lookup_results() { ?>
				<?php global $conn, $globals, $account_details, $admin_check, $dev_check, $staff_check, $not_found; ?>

				<?php
					$vrn = get( 'vrn' );
					$vrn = get_vrn( $vrn );
				?>
				
				<div id="content" class="content">
					<ol class="breadcrumb float-xl-right">
						<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php">Tools</a></li>
						<li class="breadcrumb-item"><a href="dashboard.php?c=vrn_lookup">VRN Lookup</a></li>
						<li class="breadcrumb-item active">VRN Results</li>
					</ol>
					
					<h1 class="page-header">VRN Results</h1>

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
													<a href="#" class="btn btn-purple" data-toggle="modal" data-target="#dev_modal">Dev</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="panel panel-inverse">
						<div class="panel-heading">
							<h2 class="panel-title">VRN Results</h2>
							<div class="panel-heading-btn">

							</div>
						</div>
						<div class="panel-body">
							<?php if( !isset( $vrn['id']) ) { ?>
								<h3>
									<center>
										<strong>No record found.</strong>
									</center>
								</h3>
							<?php } else { ?>
								<!-- tax and mot -->
								<div class="row">
									<?php if( $vrn['is_taxed'] == 'Taxed' ) { ?>
										<div class="offset-xl-4 col-xl-2 col-md-6">
											<div class="widget widget-stats bg-green">
												<div class="stats-icon"><i class="fa fa-check"></i></div>
												<div class="stats-info">
													<strong><p>TAX</p></strong>
												</div>
												<div class="stats-link">
													<a href="javascript:;"><?php echo strtoupper( $vrn['next_tax_date'] ); ?></a>
												</div>
											</div>
										</div>
									<?php } elseif( $vrn['is_taxed'] == 'SORN' ) { ?>
										<div class="offset-xl-4 col-xl-2 col-md-6">
											<div class="widget widget-stats bg-green">
												<div class="stats-icon"><i class="fa fa-check"></i></div>
												<div class="stats-info">
													<strong><p>SORN</p></strong>
												</div>
												<div class="stats-link">
													<a href="javascript:;">N/a</a>
												</div>
											</div>
										</div>
									<?php } else { ?>
										<div class="offset-xl-4 col-xl-2 col-md-6">
											<div class="widget widget-stats bg-red">
												<div class="stats-icon"><i class="fa fa-check"></i></div>
												<div class="stats-info">
													<strong><p><?php echo strtoupper( $vrn['is_taxed'] ); ?></p></strong>
												</div>
												<div class="stats-link">
													<a href="javascript:;"><?php echo strtoupper( $vrn['next_tax_date'] ); ?></a>
												</div>
											</div>
										</div>
									<?php } ?>

									<?php if( $vrn['is_mot_valid'] == 'valid' ) { ?>
										<div class="col-xl-2 col-md-6">
											<div class="widget widget-stats bg-green">
												<div class="stats-icon"><i class="fa fa-check"></i></div>
												<div class="stats-info">
													<strong><p>MOT</p></strong>
												</div>
												<div class="stats-link">
													<a href="javascript:;"><?php echo strtoupper( $vrn['next_mot_date'] ); ?></a>
												</div>
											</div>
										</div>
									<?php } else { ?>
										<div class="col-xl-2 col-md-6">
											<div class="widget widget-stats bg-red">
												<div class="stats-icon"><i class="fa fa-times"></i></div>
												<div class="stats-info">
													<strong><p>MOT</p></strong>
												</div>
												<div class="stats-link">
													<a href="javascript:;"><?php echo strtoupper( $vrn['next_mot_date'] ); ?></a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>

								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Year</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['year']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Make</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['make']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Model</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['model']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Color</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['color']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Fuel Type</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['fuel']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Cylinder Capacity</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['cylinder_capacity']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Top Speed</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['fuel']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">BHP</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['bhp']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Vehicle Type</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['vehicle_type']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Body Style</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['body_style']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">CO2 Emissions</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['co2_emissions']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Insurance Group</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['insurance_group']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">First v5 Issued</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['first_v5_issued']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Latest V5 Issued</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['last_v5_issued']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Avg Miles per year</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['average_miles_per_year']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Total MOTs Issued</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['total_mots']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">Last MOT Date</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['last_mot_date']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">MOT Due Date</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['next_mot_date']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="offset-xl-4 col-xl-4 col-md-12">
										<div class="row mb-15px">
											<label class="form-label col-form-label col-md-3">TAX Due Date</label>
											<div class="col-md-9">
												<input type="email" class="form-control mb-5px" value="<?php echo $vrn['next_tax_date']; ?>" readonly/>
											</div>
										</div>
									</div>
								</div>
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
												<a href="?c=vrn_lookup" type="button" onclick="processing();" class="btn btn-white">Back</a>
											</div>
										</div>
									</div>
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
										<?php debug( $vrn ); ?>
									</div>
								</div>
						 	</div>
						 	<div class="modal-footer">
						 		<div class="btn-group">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
						 			<a href="logout.php" class="btn btn-danger">I Don't Accept</a>
						 			<a href="actions.php?a=accept_terms" class="btn btn-lime">I Accept</a>
								</div>
							</div>
					  	</div>
				   	</div>
				</div>
			<?php } ?>
		</div>
	</dev>
	
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

		var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';

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
							className: 'btn btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Primary',
							value: true,
							visible: true,
							className: 'btn btn-primary',
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
							className: 'btn btn-default',
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
							className: 'btn btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Success',
							value: true,
							visible: true,
							className: 'btn btn-lime',
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
							className: 'btn btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Warning',
							value: true,
							visible: true,
							className: 'btn btn-warning',
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
							className: 'btn btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Warning',
							value: true,
							visible: true,
							className: 'btn btn-danger',
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
				title: 'Loading',
				text: 'Please wait one moment.',
				buttons: {
					
				}
			} ).then(function( e ) {
			    // placeholder
			} );
		}

		function saving( id ) {
			swal({
				title: 'Saving Data',
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
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Proceed',
						value: true,
						visible: true,
						className: 'btn btn-lime',
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
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Accept',
						value: true,
						visible: true,
						className: 'btn btn-lime',
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

    	function customer_delete( id ) {
			swal({
				title: 'Delete Customer?',
				text: 'This action will delete the customer record and all jobs for this customer. \nThis action CANNOT be undone.',
				icon: 'error',
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Delete',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'deleting customer: ' + id );

			    	// process action
			    	window.location = "actions.php?a=customer_delete&id=" + id;
			    }
			} );
		}

    	function user_delete( id ) {
			swal({
				title: 'Delete User?',
				text: 'This action will delete the user account. \nThis action CANNOT be undone.',
				icon: 'error',
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Delete',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'deleting customer: ' + id );

			    	// process action
			    	window.location = "actions.php?a=user_delete&id=" + id;
			    }
			} );
		}

		function job_delete( id ) {
			swal({
				title: 'Delete Job?',
				text: 'This action will delete the job. \nThis action CANNOT be undone.',
				icon: 'error',
				buttons: {
					cancel: {
						text: 'Cancel',
						value: null,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					},
					confirm: {
						text: 'Delete',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true
					}
				}
			} ).then(function( e ) {
			    if( e == true ) {
			    	console.log( 'deleting job: ' + id );

			    	// process action
			    	window.location = "actions.php?a=job_delete&id=" + id;
			    }
			} );
		}
	</script>

	<!-- mapbox -->
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
	<script src="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.js"></script>
	<link href="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css" rel="stylesheet" />

	<!-- status alerts -->
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

		<script>
			Highcharts.chart("highchart_container", {
			    chart: {
			        plotBackgroundColor: null,
			        plotBorderWidth: null,
			        plotShadow: false,
			        type: "pie",
			    },
			    title: {
			        text: "Cancelled vs Approved Jobs",
			    },
			    tooltip: {
			        pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>",
			    },
			    accessibility: {
			        point: {
			            valueSuffix: "%",
			        },
			    },
			    plotOptions: {
			        pie: {
			            allowPointSelect: true,
			            cursor: "pointer",
			            dataLabels: {
			                enabled: true,
			                format: "<b>{point.name}</b>: {point.percentage:.1f} %",
			            },
			        },
			    },
			    series: [
			        {
			            name: "Brands",
			            colorByPoint: true,
			            data: [
			                {
			                    name: "Chrome",
			                    y: 61.41,
			                    sliced: true,
			                    selected: true,
			                },
			                {
			                    name: "Internet Explorer",
			                    y: 11.84,
			                },
			                {
			                    name: "Firefox",
			                    y: 10.85,
			                },
			                {
			                    name: "Edge",
			                    y: 4.67,
			                },
			                {
			                    name: "Safari",
			                    y: 4.18,
			                },
			                {
			                    name: "Sogou Explorer",
			                    y: 1.64,
			                },
			                {
			                    name: "Opera",
			                    y: 1.6,
			                },
			                {
			                    name: "QQ",
			                    y: 1.2,
			                },
			                {
			                    name: "Other",
			                    y: 2.61,
			                },
			            ],
			        },
			    ],
			});

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
							className: 'btn btn-default',
							closeModal: true,
						},
						confirm: {
							text: 'Delete',
							value: true,
							visible: true,
							className: 'btn btn-primary',
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

	<?php if( get( 'c' ) == 'customers' ) { ?>
		<script type="text/javascript">
			// data tables > table_customers
			$(function () {
				$( '#table_customers' ).DataTable({
					"order": [[ 1, "asc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No customers found."
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
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'customer' ) { ?>
		<script type="text/javascript">
			// data tables > table_jobs
			$(function () {
				$( '#table_jobs' ).DataTable({
					"order": [[ 0, "desc" ]],
					"responsive": true,
					"columnDefs": [{
						"targets"  : 'no-sort',
						"orderable": false,
					}],
					"language": {
						"emptyTable": "No jobs found."
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
					"order": [[ 1, "asc" ]],
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