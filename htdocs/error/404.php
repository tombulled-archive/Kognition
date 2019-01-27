<?php

// This file is part of Kognition
//
// Message here.

/**
 * errors/404.php - Kognition Error 404
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

status(HTTP_STATUS_404, NOT_FOUND);

 ?>
<!DOCTYPE html>
<head>

	<title>Error 404 - Cannot find URL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" type="text/css" href="../assets/css/lost-style.css">-->
    <link rel="stylesheet" type="text/css" href="<?php echo $CFG->url_assets_dir.'css/lost-style.css' ?>">
</head>

<body>
	<div class="main">
	</div>
	<script>
	</script>
</body>
</html>
