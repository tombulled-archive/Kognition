<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/answer/update.php - Kognition Answer.Update() API Method
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
require_once import($CFG->lib_answer);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$answer_hash = required_param(ANSWER_HASH, PARAM_STR, METHOD_GET);
$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);

die_if_not_answer($answer_hash);
die_if_not_member($member_hash);

$answer = get_answer_from_hash($answer_hash);
$member = get_member_from_hash($member_hash);

$member->touch();

die_if_didnt_answer($answer, $member);

$api_out = array();

$answer_attrs = $answer->attr_array_non_recursive();
$member_attrs = $member->attr_array_non_recursive();
$question_attrs = $answer->question->attr_array_non_recursive();

$api_out[ANSWER] = $answer_attrs;
$api_out[MEMBER] = $member_attrs;
$api_out[QUESTION] = $question_attrs;

request_success($api_out);

 ?>
