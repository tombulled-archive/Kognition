<?php

// This file is part of Kognition
//
// Message here.

/**
 * errors/500.php - Kognition Error 500
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

status(HTTP_STATUS_500, INTERNAL_SERVER_ERROR);

 ?>
<!DOCTYPE html>
<head>

	<title>Error 500 - Internal Server Error</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../assets/css/error-style.css">
</head>

<body>
	<div class="main">
	</div>
	<script>
	</script>
</body>
</html>
