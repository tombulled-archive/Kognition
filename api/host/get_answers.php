<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/get_answers.php - Kognition Host.Get_Answers() API Method
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
require_once import($CFG->lib_question);
require_once import($CFG->lib_answer);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);
$question_hash = required_param(QUESTION_HASH, PARAM_STR, METHOD_GET);

die_if_not_host($host_hash);
die_if_not_question($question_hash);

$host = get_host_from_hash($host_hash);
$question = get_question_from_hash($question_hash);

$host->touch();

$answers = get_questions_answers($question);

$api_out = array();

$answers_attrs = array();

foreach ($answers as $answer)
{
    $answers_entry_attrs = array();

    $answer_attrs = $answer->attr_array_non_recursive();
    $member_attrs = $answer->member->attr_array_non_recursive();

    $answers_entry_attrs[ANSWER] = $answer_attrs;
    $answers_entry_attrs[MEMBER] = $member_attrs;

    array_push($answers_attrs, $answers_entry_attrs);
}

$api_out[ANSWERS] = $answers_attrs;

request_success($api_out);

 ?>
