<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/answer/delete.php - Kognition Answer.Delete() API Method
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
require_once import($CFG->lib_answer);
require_once import($CFG->lib_member);
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$answer_hash = required_param(ANSWER_HASH, PARAM_STR, METHOD_GET);
$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);
//$member_hash = optional_param(MEMBER_HASH, PARAM_STR, METHOD_GET);
//$host_hash = optional_param(HOST_HASH, PARAM_STR, METHOD_GET);

//die_if_none('Neither member nor host', $member_hash ?? false, $host_hash ?? false);
//die_if_none('Neither member nor host', $member_hash, $host_hash);

die_if_not_answer($answer_hash);
die_if_not_member($member_hash);

$answer = get_answer_from_hash($answer_hash);
$member = get_member_from_hash($member_hash);

$member->touch();

//die_if_cant_answer($question, $member);

die_if_didnt_answer($answer, $member);

$delete_success = $answer->delete();

if ($delete_success)
{
    request_success();
}
else
{
    request_failed('Delete failed');
}

 ?>
