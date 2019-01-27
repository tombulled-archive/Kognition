<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/member/get_questions.php - Kognition Member.Get_Questions() API Method
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
require_once import($CFG->lib_question);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);

die_if_not_member($member_hash);

$member = get_member_from_hash($member_hash);

$member->touch();

$questions = get_classes_questions($member->class);

$api_out = array();

$questions_attrs = array();

foreach ($questions as $question)
{
    $question_attrs = $question->attr_array_non_recursive();

    array_push($questions_attrs, $question_attrs);
}

$api_out[QUESTIONS] = $questions_attrs;

request_success($api_out);

 ?>
