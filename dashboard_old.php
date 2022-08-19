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

// check $account_details to confirm completed profile
if( get( 'c' ) != 'user' && empty( $account_details['address_1'] ) ) {
	// set status message
	status_message( "warning", "Please complete your profile." );

	// redirect
	go( 'dashboard.php?c=user' );
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php echo $globals['platform_name']; ?> - Flowers made simple</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/default/app.min.css" rel="stylesheet" />

	<!-- select2 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
	
	<!-- ================== BEGIN page-css ================== -->
	<?php if( get( 'c' ) == 'message_new' ) { ?>
		<link href="assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet" />
		<link href="assets/plugins/summernote/dist/summernote-lite.css" rel="stylesheet" />
	<?php } ?>
	<!-- ================== END page-css ================== -->
</head>
<body>
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>

	<div id="app" class="app app-header-fixed app-sidebar-fixed">
		<div id="header" class="app-header">
			<div class="navbar-header">
				<a href="dashboard.php" class="navbar-brand"><span class="navbar-logo"></span> The<b>Flower</b>Network</a>
				<button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="navbar-nav">
				<!--
					<div class="navbar-item navbar-form">
						<form action="" method="POST" name="search">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Enter keyword" />
								<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</div>
				-->
				<div class="navbar-item dropdown">
					<!--
						<a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
							<i class="fa fa-bell"></i>
							<span class="badge">5</span>
						</a>
						<div class="dropdown-menu media-list dropdown-menu-end">
							<div class="dropdown-header">NOTIFICATIONS (5)</div>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-bug media-object bg-gray-500"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
									<div class="text-muted fs-10px">3 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<img src="assets/img/user/user-1.jpg" class="media-object" alt="" />
									<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">John Smith</h6>
									<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
									<div class="text-muted fs-10px">25 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<img src="assets/img/user/user-2.jpg" class="media-object" alt="" />
									<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Olivia</h6>
									<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
									<div class="text-muted fs-10px">35 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-plus media-object bg-gray-500"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New User Registered</h6>
									<div class="text-muted fs-10px">1 hour ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-envelope media-object bg-gray-500"></i>
									<i class="fab fa-google text-warning media-object-icon fs-14px"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New Email From John</h6>
									<div class="text-muted fs-10px">2 hour ago</div>
								</div>
							</a>
							<div class="dropdown-footer text-center">
								<a href="javascript:;" class="text-decoration-none">View more</a>
							</div>
						</div>
					-->
				</div>
				
				<div class="navbar-item navbar-user dropdown">
					<a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
						<img src="<?php echo get_gravatar( $account_details['email'] ); ?>" alt="avatar" /> 
						<span>
							<span class="d-none d-md-inline"><?php echo $account_details['full_name']; ?></span>
							<b class="caret"></b>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-1">
						<a href="?c=user" class="dropdown-item">Edit Profile</a>
						<div class="dropdown-divider"></div>
						<a href="logout.php" class="dropdown-item">Sign Out</a>
					</div>
				</div>
			</div>
		</div>
	
		<div id="sidebar" class="app-sidebar">
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<div class="menu">
					<div class="menu-profile">
						<a href="?c=user" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
							<div class="menu-profile-image">
								<img src="<?php echo get_gravatar( $account_details['email'] ); ?>" alt="avatar" />
							</div>
							<div class="menu-profile-info">
								<div class="d-flex align-items-center">
									<div class="flex-grow-1">
										<?php echo $account_details['full_name']; ?>
									</div>
								</div>
								<small><?php echo $account_details['company_name']; ?></small>
							</div>
						</a>
					</div>

					<div class="menu-header">Navigation</div>
					<div class="menu-item <?php if( get( 'c' ) == '' || get( 'c' ) == 'home' ) { echo'active'; } ?>">
						<a href="dashboard.php" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-home"></i>
							</div>
							<div class="menu-text">Home</div>
						</a>
					</div>
					<div class="menu-item <?php if( get( 'c' ) == 'catalogue' || get( 'c' ) == 'catalogue_item' ) { echo'active'; } ?>">
						<a href="?c=catalogue" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-book"></i>
							</div>
							<div class="menu-text">Catalogue</div>
						</a>
					</div>
					<div class="menu-item <?php if( get( 'c' ) == 'message' || get( 'c' ) == 'messages' ) { echo'active'; } ?>">
						<a href="?c=messages" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-comments"></i>
							</div>
							<div class="menu-text">Messages</div>
						</a>
					</div>
					<div class="menu-item <?php if( get( 'c' ) == 'order' || get( 'c' ) == 'orders' ) { echo'active'; } ?>">
						<a href="?c=orders" class="menu-link">
							<div class="menu-icon">
								<i class="fa fa-shopping-cart"></i>
							</div>
							<div class="menu-text">Orders</div>
						</a>
					</div>

					<!--
						<div class="menu-item has-sub active">
							<a href="javascript:;" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-th-large"></i>
								</div>
								<div class="menu-text">Dashboard</div>
								<div class="menu-caret"></div>
							</a>
							<div class="menu-submenu">
								<div class="menu-item">
									<a href="index.html" class="menu-link"><div class="menu-text">Dashboard v1</div></a>
								</div>
								<div class="menu-item active">
									<a href="index_v2.html" class="menu-link"><div class="menu-text">Dashboard v2</div></a>
								</div>
								<div class="menu-item">
									<a href="index_v3.html" class="menu-link"><div class="menu-text">Dashboard v3</div></a>
								</div>
							</div>
						</div>
					-->

					<?php if( $account_details['type'] == 'admin' ) { ?>
						<div class="menu-header">Admin Section</div>

						<div class="menu-item <?php if( get( 'c' ) == 'customer' || get( 'c' ) == 'customers' ) { echo'active'; } ?>">
							<a href="?c=customers" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-address-card"></i>
								</div>
								<div class="menu-text">Customers</div>
							</a>
						</div>
						<div class="menu-item <?php if( get( 'c' ) == 'user' || get( 'c' ) == 'users' ) { echo'active'; } ?>">
							<a href="?c=users" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-users"></i>
								</div>
								<div class="menu-text">Florists</div>
							</a>
						</div>
					<?php } ?>

					<?php if( $account_details['email'] == 'jamie.whittingham@gmail.com' ) { ?>
						<div class="menu-header">Dev Section</div>

						<div class="menu-item <?php if( get( 'c' ) == 'dev' ) { echo'active'; } ?>">
							<a href="?c=dev" class="menu-link">
								<div class="menu-icon">
									<i class="fa fa-code"></i>
								</div>
								<div class="menu-text">Dev</div>
							</a>
						</div>
					<?php } ?>
					
					<div class="menu-item d-flex">
						<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="app-sidebar-bg"></div>
		<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
		
		<?php
			$c = get( 'c' );
			switch( $c ) {
				case "dev":
					dev();
					break;

				case "catalogue":
					catalogue();
					break;

				case "catalogue_item":
					catalogue_item();
					break;

				case "customer":
					if( $account_details['type'] == 'admin' ) {
						customer();
					} else {
						home();
					}
					break;

				case "customers":
					if( $account_details['type'] == 'admin' ) {
						customers();
					} else {
						home();
					}
					break;

				case "message":
					message();
					break;

				case "message_new":
					message_new();
					break;

				case "messages":
					messages();
					break;

				case "order":
					chat();
					break;

				case "orders":
					orders();
					break;

				case "process_payment":
					process_payment();
					break;

				case "system_settings":
					if( $account_details['type'] == 'admin' ) {
						system_settings();
					} else {
						home();
					}
					break;

				case "user":
					user();
					break;

				case "users":
					if( $account_details['type'] == 'admin' ) {
						users();
					} else {
						home();
					}
					break;

				default:
					home();
					break;
			}
		?>

		<?php function default_content() { ?>
			<?php global $conn, $globals, $account_details; ?>
			
			<div id="content" class="app-content">
				<ol class="breadcrumb float-xl-end">
					<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:;">Library</a></li>
					<li class="breadcrumb-item active">Data</li>
				</ol>

				<h1 class="page-header">Page Header <small>header small text goes here...</small></h1>
				
				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">Panel Title here</h4>
						<div class="panel-heading-btn">
							
						</div>
					</div>
					<div class="panel-body">
						Panel Content Here
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function dev() { ?>
			<?php global $conn, $globals, $account_details; ?>
			
			<div id="content" class="app-content">
				<ol class="breadcrumb float-xl-end">
					<li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
					<li class="breadcrumb-item active">Dev</li>
				</ol>

				<h1 class="page-header">Dev Page <small>might be broken</small></h1>
				
				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">$_SESSION vars</h4>
						<div class="panel-heading-btn">
							
						</div>
					</div>
					<div class="panel-body">
						<?php debug( $_SESSION ); ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function home() { ?>
			<?php global $conn, $globals, $account_details; ?>

			<?php $users = get_users( 'customer' ); ?>
			
			<div id="content" class="app-content">
				<ol class="breadcrumb float-xl-end">
					<li class="breadcrumb-item active">Home</li>
				</ol>

				<h1 class="page-header">Home <!-- <small>header small text goes here...</small> --></h1>
				
				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">Panel Title here</h4>
						<div class="panel-heading-btn">
							
						</div>
					</div>
					<div class="panel-body">
						Panel Content Here <hr>
						<div class="row">
							<div class="col-xl-6 col-sm-12">
								<div class="form-group">
									<label class="bmd-label-floating"><strong>Existing Customer</strong></label>
									<select name="customer_id" class="form-control form-control-sm select2">
										<option selected disabled>Select a customer</option>
										<?php foreach( $users as $user ) { ?>
											<option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?> (<?php echo $user['email']; ?>)</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function message() { ?>
			<?php global $conn, $globals, $account_details; ?>
		
			<?php $message_id = get( 'id' ); ?>
			<?php $message = get_message( $message_id ); ?>

			<div id="content" class="app-content p-0">
				<div class="mailbox">
					<div class="mailbox-sidebar">
						<div class="mailbox-sidebar-header d-flex justify-content-center">
							<a href="#emailNav" data-bs-toggle="collapse" class="btn btn-inverse btn-sm me-auto d-block d-lg-none">
								<i class="fa fa-cog"></i>
							</a>
							<a href="?c=message_new" class="btn btn-inverse ps-40px pe-40px btn-sm">
								Compose
							</a>
						</div>
						<div class="mailbox-sidebar-content collapse d-lg-block" id="emailNav">
							<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
								<div class="nav-title"><b>Filters</b></div>
								<ul class="nav nav-inbox">
									<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers">Customers <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">2</span></a></li>
									<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists">Florists</a></li>
									<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk">Help Desk <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">1</span></a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="mailbox-content">
						<div class="mailbox-content-header">
							<?php if( isset( $message['id'] ) ) { ?>
								<div class="btn-toolbar">
									<div class="btn-group me-2">
										<a href="?c=message_reply&id=<?php echo $message['id']; ?>" class="btn btn-white btn-sm"><i class="fa fa-fw fa-reply"></i> <span class="d-none d-lg-inline">Reply</span></a>
									</div>
									<div class="btn-group me-2">
										<a href="actions.php?a=message_delete&id=<?php echo $message['id']; ?>" class="btn btn-white btn-sm" onclick="return confirm('Are you sure?')"><i class="fa fa-fw fa-trash"></i> <span class="d-none d-lg-inline">Delete</span></a>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="mailbox-content-body">
							<?php if( !isset( $message['id'] ) ) { ?>
								<center><h3>Message not found.</h3></center>
							<?php } else { ?>
								<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
									<div class="p-3">
										<h3 class="mb-3"><?php echo $message['subject']; ?></h3>
										<div class="d-flex mb-3">
											<a href="javascript:;">
												<img class="rounded-pill" width="48" alt="" src="<?php echo get_gravatar( $message['sender']['email'] ); ?>" alt="avatar"/>
											</a>
											<div class="ps-3">
												<div class="email-from text-inverse fs-14px mb-3px fw-bold">
													from <?php echo $message['sender']['full_name']; ?> <?php echo '<'.$message['sender']['email'].'>'; ?>
												</div>
												<div class="mb-3px"><i class="fa fa-clock fa-fw"></i> <?php echo date( 'Y-m-d h:i', $message['added'] ); ?></div>
												<div class="email-to">
													To: <?php echo $account_details['email']; ?>
												</div>
											</div>
										</div>
										<hr class="bg-gray-500" />

										<p class="text-inverse"> 
											<?php echo $message['message']; ?>
										</p>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="mailbox-content-footer d-flex align-items-center justify-content-end">
							
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function message_new() { ?>
			<?php global $conn, $globals, $account_details; ?>

			<?php $users = get_users( 'user' ); ?>
			
			<div id="content" class="app-content p-0">
				<div class="mailbox">
					<div class="mailbox-sidebar">
						<div class="mailbox-sidebar-header d-flex justify-content-center">
							<a href="#emailNav" data-bs-toggle="collapse" class="btn btn-inverse btn-sm me-auto d-block d-lg-none">
								<i class="fa fa-cog"></i>
							</a>
							<a href="?c=message_new" class="btn btn-inverse ps-40px pe-40px btn-sm">
								Compose
							</a>
						</div>
						<div class="mailbox-sidebar-content collapse d-lg-block" id="emailNav">
							<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
								<div class="nav-title"><b>Filters</b></div>
								<ul class="nav nav-inbox">
									<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers">Customers <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">2</span></a></li>
									<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists">Florists</a></li>
									<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk">Help Desk <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">1</span></a></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="mailbox-content">
						<div class="mailbox-content-header">
							<div class="btn-toolbar align-items-center">
								<div class="btn-group me-2">
									<!-- <a href="javascript:;" class="btn btn-white btn-sm"><i class="fa fa-fw fa-envelope"></i> <span class="hidden-xs">Send</span></a> -->
								</div>
							</div>
						</div>
						<form action="actions.php?a=message_send" method="POST" name="email_message" class="mailbox-form">
							<div class="mailbox-content-body">
								<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
									<div class="mailbox-to">
										<label class="control-label">To:</label>
											<select name="to_id" class="primary line-mode form-control form-control-sm select2" style="width: 100%;" required>
												<option selected disabled>Select a recipient</option>
												<?php foreach( $users as $user ) { ?>
													<option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?> (<?php echo $user['company_name']; ?>)</option>
												<?php } ?>
											</select>
										<!--
											<div class="mailbox-float-link">
												<a href="#" data-click="add-cc" data-name="Cc" class="me-5px">Cc</a>
												<a href="#" data-click="add-cc" data-name="Bcc">Bcc</a>
											</div>
										-->
									</div>

									<div data-id="extra-cc"></div>

									<div class="mailbox-subject">
										<input type="text" name="subject" class="form-control" placeholder="Subject" required>
									</div>
									<div class="mailbox-input">
										<textarea name="message" class="summernote" required></textarea>
									</div>
								</div>
							</div>
							<div class="mailbox-content-footer d-flex align-items-center justify-content-end">
								<a href="?c=messages" class="btn btn-white ps-40px pe-40px me-5px">Discard</a>
								<button type="submit" class="btn btn-primary ps-40px pe-40px">Send</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function messages() { ?>
			<?php global $conn, $globals, $account_details; ?>
			
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

			<div id="content" class="app-content p-0">
				<div class="mailbox">
					<div class="mailbox-sidebar">
						<div class="mailbox-sidebar-header d-flex justify-content-center">
							<a href="#emailNav" data-bs-toggle="collapse" class="btn btn-inverse btn-sm me-auto d-block d-lg-none">
								<i class="fa fa-cog"></i>
							</a>
							<a href="?c=message_new" class="btn btn-inverse ps-40px pe-40px btn-sm">
								Compose
							</a>
						</div>
						<div class="mailbox-sidebar-content collapse d-lg-block" id="emailNav">
							<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
								<div class="nav-title"><b>Filters</b></div>
								<ul class="nav nav-inbox">
									<li <?php if( get( 'filter' ) == '' || get( 'filter' ) == 'customers' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=customers">Customers <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">2</span></a></li>
									<li <?php if( get( 'filter' ) == 'florists' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=florists">Florists</a></li>
									<li <?php if( get( 'filter' ) == 'helpdesk' ) { echo 'class="active"'; } ?>><a href="?c=messages&filter=helpdesk">Help Desk <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px">1</span></a></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="mailbox-content">
						<div class="mailbox-content-header">
							<div class="btn-toolbar align-items-center">
								<!--
									<div class="form-check me-2">
										<input type="checkbox" class="form-check-input" data-checked="email-checkbox" id="emailSelectAll" data-change="email-select-all" />
										<label class="form-check-label" for="emailSelectAll"></label>
									</div>
									<div class="w-100 d-sm-none d-block mb-2 hide" data-email-action="divider"></div>
									<div class="btn-group">
										<button class="btn btn-sm btn-white" data-email-action="delete"><i class="fa fa-times me-1"></i> <span class="hidden-xs">Delete</span></button>
									</div>
								-->
								<div class="btn-group ms-auto">
									<button class="btn btn-white btn-sm">
										<i class="fa fa-chevron-left"></i>
									</button>
									<button class="btn btn-white btn-sm">
										<i class="fa fa-chevron-right"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="mailbox-content-body">
							<div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
								<ul class="list-group list-group-lg no-radius list-email">
									<?php 
										if( !isset( $messages[0] ) ) {
											echo '<center><h3>No messages.</h3></center>';
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
																<div class="form-check">
																	<input type="checkbox" class="form-check-input" data-checked="email-checkbox" id="emailCheckbox1">
																	<label class="form-check-label" for="emailCheckbox1"></label>
																</div>
															</div>
														-->
														<a href="?c=message&id='.$message['id'].'" class="email-user bg-blue">
															<span class="text-white">'.$message['sender']['initials'].'</span>
														</a>
														<div class="email-info">
															<a href="?c=message&id='.$message['id'].'">
																<span class="email-sender">'.$message['sender']['full_name'].' '.( $message['filter'] == 'florist' ? '('.$message['sender']['company_name'].')' : '' ).'</span>
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
						<div class="mailbox-content-footer d-flex align-items-center">
							<div class="text-inverse fw-bold"><?php echo number_format( count( $messages ) ); ?> messages.</div>
							<div class="btn-group ms-auto">
								<button class="btn btn-white btn-sm">
									<i class="fa fa-fw fa-chevron-left"></i>
								</button>
								<button class="btn btn-white btn-sm">
									<i class="fa fa-fw fa-chevron-right"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function order() { ?>
			<?php global $conn, $globals, $account_details; ?>
			
			<div id="content" class="app-content">
				<ol class="breadcrumb float-xl-end">
					<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
					<li class="breadcrumb-item"><a href="javascript:;">Library</a></li>
					<li class="breadcrumb-item active">Data</li>
				</ol>

				<h1 class="page-header">Page Header <small>header small text goes here...</small></h1>
				
				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">Panel Title here</h4>
						<div class="panel-heading-btn">
							
						</div>
					</div>
					<div class="panel-body">
						Panel Content Here
					</div>
				</div>
			</div>
		<?php } ?>

		<?php function orders() { ?>
			<?php global $conn, $globals, $account_details; ?>
			
			<?php 
				$orders = get_orders();
				$users = get_users( 'customer' );
			?>

			<div id="content" class="app-content">
				<ol class="breadcrumb float-xl-end">
					<li class="breadcrumb-item"><a href="?c=home">Home</a></li>
					<li class="breadcrumb-item active">Orders</li>
				</ol>

				<h1 class="page-header">Orders <!-- <small>header small text goes here...</small> --></h1>
				
				<div class="row">
					<div class="col-xl-12">
						<div id="status_message"></div><div id="kyc_status_message"></div>
					</div>
				</div>

				<div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">Orders</h4>
						<div class="panel-heading-btn">
							<div class="btn-group">
								<a href="#order_add" class="btn btn-green" data-bs-toggle="modal">Add Order</a>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<?php if( !isset( $orders[0] ) ) { ?>
							<center><h3>No orders.</h3></center>
						<?php } else { ?>
							Order table will go here.
						<?php } ?>
						<?php debug( $orders); ?>
						
						<div class="row">
							<div class="col-xl-6 col-sm-12">
								<div class="form-group">
									<label class="bmd-label-floating"><strong>Existing Customer</strong></label>
									<select name="customer_id" class="form-control form-control-sm select2">
										<option selected disabled>Select a customer</option>
										<?php foreach( $users as $user ) { ?>
											<option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?> (<?php echo $user['email']; ?>)</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

				<div class="modal fade" id="order_add">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header">
				                <h4 class="modal-title">Add Order</h4>
				                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				            </div>
				            <div class="modal-body">
				            	<div class="row">
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Existing Customer</strong></label>
											<select name="customer_id" class="form-control form-control-sm select2">
												<option selected disabled>Select a customer</option>
												<?php foreach( $users as $user ) { ?>
													<option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?> (<?php echo $user['email']; ?>)</option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<hr>
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
											<label class="bmd-label-floating"><strong>Email Address</strong></label>
											<input type="text" name="customer_email" class="form-control" required>
										</div>
									</div>
									<div class="col-xl-6 col-sm-12">
										<div class="form-group">
											<label class="bmd-label-floating"><strong>Tel</strong></label>
											<input type="text" name="customer_tel" class="form-control" required>
										</div>
									</div>
								</div>
				            </div>
				            <div class="modal-footer">
				                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
				                <a href="javascript:;" class="btn btn-success">Action</a>
				            </div>
				        </div>
				    </div>
				</div>
		<?php } ?>

		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>

		<?php if( $account_details['accept_terms'] == 'no' ){ ?>
			<div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-labelledby="modal-terms" aria-hidden="true">
			   	<div class="modal-dialog modal-xl">
				  	<div class="modal-content">
					 	<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">Terms &amp; Conditions <small>(scroll to accept)</small></h5>
					 	</div>
					 	<div class="modal-body">
							<h2>Welcome to TheFlowerNetwork</h2>
							<p>These terms and conditions outline the rules and regulations for the use of TheFlowerNetwork's Website.</p> <br> 

							<p>By accessing this website we assume you accept these terms and conditions in full. Do not continue to use TheFlowerNetwork's website 
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
							<p>We employ the use of cookies. By using TheFlowerNetwork's website you consent to the use of cookies 
							in accordance with TheFlowerNetwork's privacy policy.</p><p>Most of the modern day interactive web sites
							use cookies to enable us to retrieve user details for each visit. Cookies are used in some areas of our site
							to enable the functionality of this area and ease of use for those people visiting. Some of our 
							affiliate / advertising partners may also use cookies.</p><h2>License</h2>
							<p>Unless otherwise stated, TheFlowerNetwork and/or it's licensors own the intellectual property rights for
							all material on TheFlowerNetwork. All intellectual property rights are reserved. You may view and/or print
							pages from <?php echo $globals['url']; ?> for your own personal use subject to restrictions set in these terms and conditions.</p>
							<p>You must not:</p>
							<ol>
							<li>Republish material from <?php echo $globals['url']; ?></li>
							<li>Sell, rent or sub-license material from <?php echo $globals['url']; ?></li>
							<li>Reproduce, duplicate or copy material from <?php echo $globals['url']; ?></li>
							</ol>
							<p>Redistribute content from TheFlowerNetwork (unless content is specifically made for redistribution).</p>
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
							the visibility associated with the hyperlink outweighs the absence of TheFlowerNetwork; and (d) where the
							link is in the context of general resource information or is otherwise consistent with editorial content
							in a newsletter or similar product furthering the mission of the organization.</p>

							<p>These organizations may link to our home page, to publications or to other Web site information so long as
							the link: (a) is not in any way misleading; (b) does not falsely imply sponsorship, endorsement or approval
							of the linking party and it products or services; and (c) fits within the context of the linking party's
							site.</p>

							<p>If you are among the organizations listed in paragraph 2 above and are interested in linking to our website,
							you must notify us by sending an e-mail to <a href="mailto:info@TheFlowerNetwork.io" title="send an email to info@TheFlowerNetwork.io">info@TheFlowerNetwork.io</a>.
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
							<p>No use of TheFlowerNetwork's logo or other artwork will be allowed for linking absent a trademark license
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
					 			<a href="actions.php?a=accept_terms" class="btn btn-xs btn-green">I Accept</a>
							</div>
						</div>
				  	</div>
			   	</div>
			</div>
		<?php } ?>
	</div>
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="assets/js/vendor.min.js"></script>
	<script src="assets/js/app.min.js"></script>
	<script src="assets/js/theme/default.min.js"></script>

	<!-- select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
	<script>
		$('.select2').select2({
			theme: "bootstrap-5", 
			placeholder: 'Make a selection'
		});
	</script>
	<!-- ================== END core-js ================== -->

	<?php if( !empty( $_SESSION['alert']['status'] ) ) { ?>
		<script>
			document.getElementById( 'status_message' ).innerHTML = '<div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> fade show m-b-0"><?php echo $_SESSION['alert']['message']; ?></div> <br>';
			setTimeout(function() {
				$('#status_message').fadeOut( 'fast' );
			}, 5000 );
		</script>
		<?php unset( $_SESSION['alert'] ); ?>
	<?php } ?>

	<?php if( get( 'c' ) == '' || get( 'c' ) == 'home' ) { ?>
		<script src="assets/plugins/d3/d3.min.js"></script>
		<script src="assets/plugins/nvd3/build/nv.d3.min.js"></script>
		<script src="assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
		<script src="assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
		<script src="assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
		<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>		
	<?php } ?>

	<?php if( get( 'c' ) == 'message_new' ) { ?>
		<script src="assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
		<script src="assets/plugins/tag-it/js/tag-it.min.js"></script>
		<script src="assets/plugins/summernote/dist/summernote-lite.min.js"></script>
		<script src="assets/js/demo/email-compose.demo.js"></script>
	<?php } ?>

	<?php if( get( 'c' ) == 'message' || get( 'c' ) == 'messages' ) { ?>
		<script src="assets/js/demo/email-inbox.demo.js"></script>

		<?php 
			$id = get( 'id' );
			$message = get_message( $id );

			if( isset( $message['id'] ) ) {
				?>
					<script>
						$.ajax({
			                async: false,
			                type: "GET",
			                global: false,
			                dataType: "json",
			                url: "actions.php?a=message_mark_read&id=<?php echo $id; ?>",
			                success: function (data) {
			                    console.log( 'message id: <?php echo $id; ?> marked as read' );
			                },
			            });
			        </script>
				<?
			}
		?>
	<?php } ?>

	<?php if( get( 'c' ) == 'miner' ) { ?>
		<?php 
	 		$miner_id = get( 'id' );
	 		$miner = get_miner( $miner_id );

	 		// get witnesses
			$witnesses = file_get_contents( 'https://api.helium.io/v1/hotspots/'.$miner['miner_uuid'].'/witnesses' );
			$witnesses = json_decode( $witnesses, true );
	 	?>

	 	<!-- matchheight -->
	 	<script type="text/javascript">
			$(function() {
				$('.hotspot_map').matchHeight({
			        target: $('.hotspot_daily_rewards')
			    });
			});
        </script>

        <!-- auto submit form for hotspot_make update -->
        <script>
        	$(function () {
	            $("#hotspot_make").live("change keyup", function () {
	                $("#miner_details").submit();
	            });
	        });
       	</script>

		<!-- highcharts -->
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>
		<script src="https://code.highcharts.com/modules/accessibility.js"></script>

		<!-- get dates and rewards and prep for highchart usage -->
		<?php 
			$query = $conn->query( "

			SELECT * FROM (
			   SELECT * FROM rewards_hotspot_daily_totals WHERE `hotspot_id` = '".$miner_id."' ORDER BY id DESC LIMIT 30
			)Var1
			   ORDER BY id ASC;
		    " );

			$reward_data = $query->fetchAll( PDO::FETCH_ASSOC );
			$reward_data = stripslashes_deep( $reward_data );

			$dates = array_column( $reward_data, 'date' );
			$dates = implode( "', '", $dates );

			$totals = array_column( $reward_data, 'total' );
			$totals = implode( ", ", $totals );
		?>
		<script>
			Highcharts.chart('daily_rewards', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'HNT Rewards'
			    },
			    subtitle: {
			        text: 'Source: Helium Blockchain'
			    },
			    xAxis: {
			        categories: [
			            '<?php echo $dates; ?>',
			        ],
			        crosshair: true
			    },
			    yAxis: {
			        min: 0,
			        title: {
			            text: 'HNT Rewards'
			        }
			    },
			    tooltip: {
			        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
			        footerFormat: '</table>',
			        shared: true,
			        useHTML: true
			    },
			    plotOptions: {
			        column: {
			            pointPadding: 0.2,
			            borderWidth: 0
			        }
			    },
			    series: [{
			        name: 'HNT',
			        data: [<?php echo $totals; ?>]
			    }]
			});
		</script>

	 	<!-- mapbox -->
		<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
		<script src="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.js"></script>
		<link href="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css" rel="stylesheet" />

		<script>
            var framesPerSecond = 20;
            var initialOpacity = 1;
            var opacity = initialOpacity;
            var initialRadius = 4;
            var radius = initialRadius;
            var maxRadius = 10;

            var speedFactor = 100; // number of frames per longitude degree
            var animation; // to store and cancel the animation

            mapboxgl.accessToken = 'pk.eyJ1Ijoid2hpdHRpbmdoYW1qIiwiYSI6ImNra3A3dHpmdjA5ejcyb3A4eXdvZWdkYjQifQ.o7czsjpiO6o7Km4RnBqJNg';
            var map = new mapboxgl.Map({
                container: 'map',
                // style: 'mapbox://styles/mapbox/dark-v10',
                style: 'mapbox://styles/mapbox/<?php echo $globals['mapbox_theme']; ?>', 
                center: [<?php echo $miner['long'].', '.$miner['lat']; ?>],
                zoom: 12
            });

            map.addControl(new mapboxgl.FullscreenControl());

            var marker = new mapboxgl.Marker({ "color": "<?php if( $miner['status'] == 'online' ) { echo '#007cbf'; } else { echo '#ff5733'; } ?>" })
            .setLngLat([<?php echo $miner['long'].', '.$miner['lat']; ?>])
            .addTo(map);

            map.on("load", function () {
                // add witnesses
                <?php if( isset( $witnesses['data'][0] ) ) { foreach( $witnesses['data'] as $witness ) { ?>
                    map.addSource("route_<?php echo $witness['address']; ?>", {
                        type: "geojson",
                        data: {
                            type: "Feature",
                            properties: {},
                            geometry: {
                                type: "LineString",
                                coordinates: [
                                    [<?php echo $miner['long'].', '.$miner['lat']; ?>],
                                    [<?php echo $witness['lng']; ?>, <?php echo $witness['lat']; ?>],
                                ],
                            },
                        },
                    });
                    map.addLayer({
                        id: "route_<?php echo $witness['address']; ?>",
                        type: "line",
                        source: "route_<?php echo $witness['address']; ?>",
                        layout: {
                            "line-join": "round",
                            "line-cap": "round",
                        },
                        paint: {
                            "line-color": "#ff9900",
                            "line-width": 2,
                        },
                    });

                    // Point 1
                    map.addLayer({
                        id: "circle1_<?php echo $witness['address']; ?>",
                        source: "route_<?php echo $witness['address']; ?>",
                        type: "circle",
                        paint: {
                            "circle-radius": initialRadius,
                            "circle-radius-transition": {
                                duration: 0,
                            },
                            "circle-opacity-transition": {
                                duration: 0,
                            },
                            "circle-color": "#36d293",
                        },
                    });
                    map.addLayer({
                        id: "route2_<?php echo $witness['address']; ?>",
                        source: "route_<?php echo $witness['address']; ?>",
                        type: "circle",
                        paint: {
                            "circle-radius": initialRadius,
                            "circle-color": "#007cbf",
                        },
                    });
                <?php } } ?>
            });
        </script>
	<?php } ?>

	<?php if( get( 'c' ) == 'miners' ) { ?>
		<script type="text/javascript">
			// data tables 
			$(function () {
				$( '#table_miners' ).DataTable({
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
					   search: '<?php if( isset( $_GET['filter'] ) ) { echo $_GET['filter']; } ?>'
					}
				});
			});
		</script>

		<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
		<script src="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.js"></script>
		<link href="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css" rel="stylesheet" />

		<script>
		    mapboxgl.accessToken = "pk.eyJ1Ijoid2hpdHRpbmdoYW1qIiwiYSI6ImNra3A3dHpmdjA5ejcyb3A4eXdvZWdkYjQifQ.o7czsjpiO6o7Km4RnBqJNg";
		    var map = new mapboxgl.Map({
		        container: "map", // container ID
		        style: "mapbox://styles/mapbox/<?php echo $globals['mapbox_theme']; ?>",
		        center: [-2.582861, 53.5154517],
		        zoom: 6,
		    });

		    map.on("load", function () {
		        map.addSource("hotspot_locations", {
		            type: "geojson",
		            // Use a URL for the value for the `data` property.
		            data: "https://TheFlowerNetwork.io/dashboard/actions.php?a=ajax_miners_location",
		        });

		        map.addLayer({
		            id: "earthquakes-layer",
		            type: "circle",
		            source: "hotspot_locations",
		            paint: {
		                "circle-radius": 8,
		                "circle-stroke-width": 1,
		                "circle-color": "#36d293",
		                "circle-stroke-color": "white",
		            },
		        });
		    });
		</script>

	<?php } ?>

	<?php if( get( 'c' ) == 'user' ) { ?>
		<script>
			function change_payout_details(value) {
				$('#payput_bank_details').addClass('hidden');
				$('#payput_crypto_details').addClass('hidden');

				if( value == 'btc' ) {
					$('#payput_crypto_details').removeClass('hidden');
				}else if( value == 'hnt') {
					$('#payput_crypto_details').removeClass('hidden');
				}else if( value == 'gbp') {
					$('#payput_bank_details').removeClass('hidden');
				}else if( value == 'usd') {
					$('#payput_bank_details').removeClass('hidden');
				}
			}
		</script>

		<script type="text/javascript">
			// data tables 
			$(function () {
				$( '#table_miners' ).DataTable({
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
					"lengthMenu": [10, 25, 50, 100, 500],
					"pageLength": 10,
					search: {
					   search: '<?php if( isset( $_GET['filter'] ) ) { echo $_GET['filter']; } ?>'
					}
				});
			});

			$(function () {
				$( '#table_payouts' ).DataTable({
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
					   search: '<?php if( isset( $_GET['filter'] ) ) { echo $_GET['filter']; } ?>'
					}
				});
			});

			$(function () {
				$( '#table_user_kyc_documents' ).DataTable({
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
					"paging": false,
					"processing": true,
					"lengthChange": false,
					"searching": false,
					"ordering": false,
					"info": false,
					"autoWidth": false,
					"lengthMenu": [10, 25, 50, 100, 500],
					"pageLength": 10,
					search: {
					   search: '<?php if( isset( $_GET['filter'] ) ) { echo $_GET['filter']; } ?>'
					}
				});
			});

			function tutorial_1(){
				var intro = introJs();
				intro.setOptions({
					exitOnEsc: false,
					exitOnOverlayClick: false,
					showStepNumbers: false,
					showProgress: true,
					steps: [
						{
							element: document.querySelector('.tutorial_settings_cluster_name'),
							intro: "Give your Cluster a friendly easy to identify name.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_origin_domain'),
							intro: "Enter the domain name that this cluster will be protecting.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_origin_ip_address'),
							intro: "Enter the IP address of the origin server that this cluster will be protecting.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_origin_port'),
							intro: "Enter the TCP port of the origin server that this cluster will be protecting.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_cluster_notes'),
							intro: "You can enter notes about this Cluster. These notes are private and only visible to you. You should NOT enter sensative information such as passwords or login details here.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_save_1'),
							intro: "Click here to save your changes.",
							position: 'top'
						},
					]
				});

				intro.start();
			}

			function _(el){
				return document.getElementById(el);
			}

			function uploadFile(){
				var file = _("file1").files[0];
				var user_id = _("user_id").value;
				var document_type = _("document_type").value;
				// alert(file.name+" | "+file.size+" | "+file.type);
				var formdata = new FormData();
				formdata.append("file1", file);
				formdata.append("user_id", user_id);
				formdata.append("document_type", document_type);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				ajax.addEventListener("error", errorHandler, false);
				ajax.addEventListener("abort", abortHandler, false);
				ajax.open("POST", "actions.php?a=user_kyc_upload");
				ajax.send(formdata);
			}

			function progressHandler(event){
				_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
				var percent = (event.loaded / event.total) * 100;
				_("progressBar").value = Math.round(percent);
				_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
			}

			function completeHandler(event){
				_("status").innerHTML = event.target.responseText;
				_("progressBar").value = 0;
				setTimeout(function() {
					// set_status_message('success', 'Media has been uploaded to this case.');
					window.location = window.location;
				}, 1000);
			}

			function errorHandler(event){
				_("status").innerHTML = "Upload Failed";
				setTimeout(function() {
					$('#status').fadeOut('fast');
				}, 10000);
			}

			function abortHandler(event){
				_("status").innerHTML = "Upload Aborted";
				setTimeout(function() {
					$('#status').fadeOut('fast');
				}, 1000);
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
					"lengthMenu": [25, 50, 100, 500],
					"pageLength": 25,
					search: {
					   search: '<?php if( isset( $_GET['filter'] ) ) { echo $_GET['filter']; } ?>'
					}
				});
			});

			function tutorial_1(){
				var intro = introJs();
				intro.setOptions({
					exitOnEsc: false,
					exitOnOverlayClick: false,
					showStepNumbers: false,
					showProgress: true,
					steps: [
						{
							element: document.querySelector('.tutorial_settings_cluster_name'),
							intro: "Give your Cluster a friendly easy to identify name.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_origin_domain'),
							intro: "Enter the domain name that this cluster will be protecting.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_origin_ip_address'),
							intro: "Enter the IP address of the origin server that this cluster will be protecting.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_origin_port'),
							intro: "Enter the TCP port of the origin server that this cluster will be protecting.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_cluster_notes'),
							intro: "You can enter notes about this Cluster. These notes are private and only visible to you. You should NOT enter sensative information such as passwords or login details here.",
							position: 'top'
						},
						{
							element: document.querySelector('.tutorial_settings_save_1'),
							intro: "Click here to save your changes.",
							position: 'top'
						},
					]
				});

				intro.start();
			}
		</script>
	<?php } ?>

	<?php if( get( 'c' ) == 'company_payouts' ) { ?>
		<script type="text/javascript">
			// data tables > table_users
			$(function () {
				$( '#table_payouts' ).DataTable({
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
					   search: '<?php if( isset( $_GET['filter'] ) ) { echo $_GET['filter']; } ?>'
					}
				});
			});
		</script>
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

	<!-- keep alive -->
	<script>
		var intervalId = window.setInterval( function() {
			keepalive();
		}, 60000);

		function keepalive() {
			$.get( "200.php", function( data, status ) {
				console.log( "Action: keepalive \nCode1: " + data + "\nStatus: " + status );
			});
		}
	</script>
</body>
</html>