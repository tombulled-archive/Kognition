<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/kick.php - Kognition Host.Kick() API Method
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

die_if_changed_ip();

handle_lost_session();

$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);
$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);

die_if_not_host($host_hash);
die_if_not_member($member_hash);

$host = get_host_from_hash($host_hash = $host_hash);
$member = get_member_from_hash($member_hash);

$host->touch();

die_if_not_in_class($host->class, $member);

$kick_success = $host->kick_member($member);

if ($kick_success)
{
    request_success();
}
else
{
    request_failed('Kick failed');
}

 ?>
