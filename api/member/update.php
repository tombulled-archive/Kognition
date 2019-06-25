<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/member/update.php - Kognition Member.Update() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);

die_if_not_member($member_hash);

$member = get_member_from_hash($member_hash);

$member->touch();

$api_out = array();

$member_attrs = $member->attr_array_non_recursive();
$class_attrs = $member->class->attr_array_non_recursive();
$host_attrs = $member->class->host->attr_array_non_recursive();

unset($host_attrs[HOST_HASH]);

$api_out[MEMBER] = $member_attrs;
$api_out[CLASS_] = $class_attrs;
$api_out[HOST] = $host_attrs;

request_success($api_out);

 ?>
