<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/_handle_created.php - Kognition Host._Handle_Created() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);

//die_if_not_host($host_hash);

//$host = get_host_from_hash($host_hash);

//redirect($CFG->url_api_dir . 'host/update.php');
redirect($CFG->url_api_dir . 'host/update?host_hash=' . $host_hash);

 ?>
