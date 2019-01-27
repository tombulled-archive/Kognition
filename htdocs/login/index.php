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
        <link href="<?php echo $CFG->url_assets_dir.'css/login-style.css' ?>" rel="stylesheet"/>
	</head>

	<body>

		<div id="links">
			<div id="twitter"></div>
		</div>

		<div id="container">
			<div id="create" style="cursor: pointer;" onclick="window.location='/login/register';">Sign Up
			</div>
			<form action="/login/" method="post">
				<img src="<?php echo $CFG->url_assets_dir.'images/user.png' ?>" id="userImg"/>
				<b>Login Form</b>
				<input type="email" name="" value="" placeholder="Email" id="email" />
				<input type="password" name="" value="" placeholder="Password" id="password" />
				<input type="submit" name="" value="LOGIN" placeholder="" id="login" />
				<a href="#"> Forgotten Password? </a>
			</form>
		</div>

	</body>

	<script type="text/javascript">

	</script>
</html>
