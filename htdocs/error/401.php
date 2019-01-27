<?php

// This file is part of Kognition
//
// Message here.

/**
 * errors/401.php - Kognition Error 401
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

status(HTTP_STATUS_401, UNAUTHORIZED);

die();

 ?>
