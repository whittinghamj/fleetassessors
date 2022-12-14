<?php

// include main functions
include( dirname(__FILE__).'/includes/core.php' );
include( dirname(__FILE__).'/includes/functions.php' );

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php echo $globals['platform_name']; ?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
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

	<link href="assets/css/apple/app.min.css" rel="stylesheet" />
	<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
</head>
<body class="pace-top">
	<div id="page-loader" class="fade show">
		<span class="spinner"></span>
	</div>
	
	<div class="login-cover">
		<div class="login-cover-image" style="background-image: url(assets/img/login-bg/accident-damage-reports.jpg)" data-id="login-cover-image"></div>
		<div class="login-cover-bg"></div>
	</div>
	
	<div id="page-container" class="fade">
		<div class="login login-v2" data-pageload-addclass="animated fadeIn">
			<div class="login-header">
				<div class="brand">
					<!--
						<span class="logo"><i class="ion-ios-cloud"></i></span> 
					-->
					<?php echo $globals['platform_name']; ?>
					<small></small>
				</div>
				<div class="icon">
					<i class="fa fa-lock"></i>
				</div>
			</div>
			<div class="login-content">
				<form action="login.php" method="POST" class="margin-bottom-0">
					<div class="form-group m-b-20">
						<input type="text" name="email" class="form-control form-control-lg" placeholder="Email Address" required />
					</div>
					<div class="form-group m-b-20">
						<input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required />
					</div>
					<!--
						<div class="checkbox checkbox-css m-b-20">
							<input type="checkbox" id="remember_checkbox" /> 
							<label for="remember_checkbox">
								Remember Me
							</label>
						</div>
					-->
					<div class="login-buttons">
						<button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
					</div>
					<!--
						<div class="m-t-20">
							Not a member yet? Click <a href="register.php">here</a> to register.
						</div>
					-->
				</form>
			</div>
		</div>

		<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
	</div>
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/js/app.min.js"></script>
	<script src="assets/js/theme/apple.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/js/demo/login-v2.demo.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<?php if( !empty( $_SESSION['alert']['status'] ) ) { ?>
		<script>
			document.getElementById( 'status_message' ).innerHTML = '<hr><div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> fade show m-b-0"><small><?php echo $_SESSION['alert']['message']; ?></small></div>';
		</script>
		<?php unset( $_SESSION['alert'] ); ?>
	<?php } ?>
</body>
</html>

