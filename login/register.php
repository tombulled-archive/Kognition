<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once $CFG->lib_dir . 'setup_lib.php';
require_once $CFG->lib_dir . 'core_lib.php';

log_request();
die_if_blocked();
die_if_maintainance();

//$_SESSION[ACCEPTED_COOKIES] = true; //obviously remove me and do legit
//die_if_not_accepted_cookies();

 ?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?php echo $CFG->url_assets_dir.'css/register-style.css' ?>">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <link href="<?php echo $CFG->url_assets_dir.'css/animate.css' ?>" rel="stylesheet"/>
        <link href="<?php echo $CFG->url_assets_dir.'css/waypoints.css' ?>" rel="stylesheet"/>

		<script src="<?php echo $CFG->url_assets_dir.'js/jquery.waypoints.min.js' ?>" type="text/javascript"></script>
		<script src="<?php echo $CFG->url_assets_dir.'js/waypoints.js' ?>" type="text/javascript"></script>
	</head>

	<body>
		<div id="container">
			<form action="" method="post">
				<img src="<?php echo $CFG->url_assets_dir.'images/kog.png' ?>" id="kogImg" class="drop-menu os-animation" data-os-animation="bounceInDown" data-os-animation-delay="0s"/>
				<b>Welcome to Kognition</b>
				<p></p>
				<a>Enter your email:</a>
				<input type="email" name="" value="" placeholder="Email" id="" />

				<a>Create a password:</a>
				<input type="password" name="" value="" placeholder="Password" id="" />

				<a>Enter your password again:</a>
				<input type="password" name="" value="" placeholder="Password" id="" />

				<input type="submit" name="" value="JOIN" placeholder="" id="register"/>
			</form>
		</div>
	</body>

	<script>

	</script>
</html>
