<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/update.php - Host.Update() API Method
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
$show_members = optional_param(SHOW_MEMBERS, PARAM_BOOL, METHOD_GET) ?? DEFAULT_UPDATE_SHOW_MEMBERS;

die_if_not_host($host_hash);

$host = get_host_from_hash($host_hash);

$host->touch();

$api_out = array();

$host_attrs = $host->attr_array_non_recursive();
$class_attrs = $host->class->attr_array_non_recursive();

if ($show_members)
{
    $members_attrs = array();

    $members = $host->class->get_members();

    foreach ($members as $member)
    {
        $member_attrs = $member->attr_array_non_recursive();

        array_push($members_attrs, $member_attrs);
    }

    $class_attrs[MEMBERS] = $members_attrs;
}

$api_out[HOST] = $host_attrs;
$api_out[CLASS_] = $class_attrs;

request_success($api_out);

 ?>
