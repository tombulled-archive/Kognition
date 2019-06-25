<?php

// This file is part of Kognition
//
// Message here.

/**
 * errors/503.php - Kognition Error 503
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

status(HTTP_STATUS_503, SERVICE_UNAVAILABLE);

 ?>
<!DOCTYPE html>
<head>

	<title>Error 503 - Maintenance</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" type="text/css" href="../assets/css/maintenance-style.css">-->
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->url_assets_dir.'css/maintenance-style.css' ?>">
</head>

<body>
	<div class="main">
	</div>
	<script>
	</script>
</body>
</html>
