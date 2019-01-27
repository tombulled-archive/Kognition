<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/server/reset.php - Kognition Server.Reset() API Method
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once import($CFG->lib_setup);
require_once import($CFG->lib_server);
require_once import($CFG->lib_core);
require_once import($CFG->lib_member);
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();

kill_session();

request_success();

 ?>
