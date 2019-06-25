<?php

// This file is part of Kognition
//
// Message here.

/**
 * errors/403.php - Kognition Error 403
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once import($CFG->lib_setup);
require_once import($CFG->lib_core);

log_request();

die_if_maintainance();

status(HTTP_STATUS_403, FORBIDDEN);

 ?>
<!DOCTYPE html>
<head>

	<title>Error 403 - Denied</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" type="text/css" href="http://kognition.ihostfull.com/assets/css/denied-style.css">-->
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->url_assets_dir.'css/denied-style.css' ?>">
</head>

<body>
	<div class="main">
	</div>
	<script>
	</script>
</body>
</html>
