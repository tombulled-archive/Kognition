<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/class/ping.php - Kognition Class.Ping() API Method
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
require_once import($CFG->lib_class);
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();

handle_lost_session();

$class_pin = required_param(CLASS_PIN, PARAM_INT, METHOD_GET);

die_if_not_class($class_pin);

$class = get_class_from_pin($class_pin);

$api_out = array();

$api_out[CLASS_] = $class->ping();

request_success($api_out);

 ?>
