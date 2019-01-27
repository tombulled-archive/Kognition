<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/delete.php - Kognition Host.Delete() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);

die_if_not_host($host_hash);

$host = get_host_from_hash($host_hash = $host_hash);

$host->touch();

$delete_success = $host->delete();

if ($delete_success)
{
    request_success();
}
else
{
    request_failed('Delete failed');
}

//kill_session();

 ?>
