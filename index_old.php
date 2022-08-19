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
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/default/app.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
</head>
<body class='pace-top'>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->

	
	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN login -->
		<div class="login login-v2 fw-bold">
			<!-- BEGIN login-cover -->
			<div class="login-cover">
				<div class="login-cover-img" style="background-image: url(assets/img/login-bg/login-bg-14.jpg)" data-id="login-cover-image"></div>
				<div class="login-cover-bg"></div>
			</div>
			<!-- END login-cover -->
			
			<!-- BEGIN login-container -->
			<div class="login-container">
				<!-- BEGIN login-header -->
				<div class="login-header">
					<div class="brand">
						<div class="d-flex align-items-center">
							The <b>Flower</b> Network
						</div>
						<small>Flower made simple</small>
					</div>
					<div class="icon">
						<i class="fa fa-lock"></i>
					</div>
				</div>
				<!-- END login-header -->
				
				<!-- BEGIN login-content -->
				<div class="login-content">
					<form action="login.php" method="POST">
						<div class="form-floating mb-20px">
							<input type="text" name="email" class="form-control fs-13px h-45px border-0" placeholder="Email Address" />
							<label for="emailAddress" class="d-flex align-items-center text-gray-600 fs-13px">Email Address</label>
						</div>
						<div class="form-floating mb-20px">
							<input type="password" name="password" class="form-control fs-13px h-45px border-0" placeholder="Password" />
							<label for="emailAddress" class="d-flex align-items-center text-gray-600 fs-13px">Password</label>
						</div>
						<!--
							<div class="form-check mb-20px">
								<input class="form-check-input border-0" type="checkbox" value="1" id="rememberMe" />
								<label class="form-check-label fs-13px text-gray-500" for="rememberMe">
									Remember Me
								</label>
							</div>
						-->
						<div class="mb-20px">
							<button type="submit" class="btn btn-success d-block w-100 h-45px btn-lg">Sign In</button>
						</div>
						<!--
							<div class="text-gray-500">
								Not a member yet? Click <a href="javascript:;" class="text-white">here</a> to register.
							</div>
						-->
					</form>
				</div>
				<!-- END login-content -->
			</div>
			<!-- END login-container -->
		</div>
		<!-- END login -->
		
		<!-- BEGIN login-bg -->
		<!--
			<div class="login-bg-list clearfix">
				<div class="login-bg-list-item active"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="assets/img/login-bg/login-bg-17.jpg" style="background-image: url(assets/img/login-bg/login-bg-17.jpg)"></a></div>
				<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="assets/img/login-bg/login-bg-16.jpg" style="background-image: url(assets/img/login-bg/login-bg-16.jpg)"></a></div>
				<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="assets/img/login-bg/login-bg-15.jpg" style="background-image: url(assets/img/login-bg/login-bg-15.jpg)"></a></div>
				<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="assets/img/login-bg/login-bg-14.jpg" style="background-image: url(assets/img/login-bg/login-bg-14.jpg)"></a></div>
				<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="assets/img/login-bg/login-bg-13.jpg" style="background-image: url(assets/img/login-bg/login-bg-13.jpg)"></a></div>
				<div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="assets/img/login-bg/login-bg-12.jpg" style="background-image: url(assets/img/login-bg/login-bg-12.jpg)"></a></div>
			</div>
		-->
		<!-- END login-bg -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="assets/js/vendor.min.js"></script>
	<script src="assets/js/app.min.js"></script>
	<script src="assets/js/theme/default.min.js"></script>
	<!-- ================== END core-js ================== -->
	
	<!-- ================== BEGIN page-js ================== -->
	<script src="assets/js/demo/login-v2.demo.js"></script>
	<!-- ================== END page-js ================== -->

	<?php if( !empty( $_SESSION['alert']['status'] ) ) { ?>
		<script>
			document.getElementById( 'status_message' ).innerHTML = '<hr><div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> fade show m-b-0"><small><?php echo $_SESSION['alert']['message']; ?></small></div>';
		</script>
		<?php unset( $_SESSION['alert'] ); ?>
	<?php } ?>
</body>
</html>