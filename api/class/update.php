<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/class/update.php - Kognition Class.Update() API Method
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
require_once import($CFG->lib_host);
require_once import($CFG->lib_class);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$class_pin = required_param(CLASS_PIN, PARAM_INT, METHOD_GET);
$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);

die_if_not_class($class_pin);
die_if_not_host($host_hash);

$class = get_class_from_pin($class_pin);
$host = get_host_from_hash($host_hash);

$host->touch();

die_if_not_owns_class($class, $host);

$api_out = array();

$class_attrs = $class->attr_array_non_recursive();
$host_attrs = $host->attr_array_non_recursive();

$api_out[CLASS_] = $class_attrs;
$api_out[HOST] = $host_attrs;

request_success($api_out);

 ?>
