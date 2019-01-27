<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/question/get_answer.php - Kognition Question.Get_Answer() API Method
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
require_once import($CFG->lib_answer);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);
$question_hash = required_param(QUESTION_HASH, PARAM_STR, METHOD_GET);

die_if_not_member($member_hash);
die_if_not_question($question_hash);

$member = get_member_from_hash($member_hash);
$question = get_question_from_hash($question_hash);

$member->touch();

$answer = get_members_answer($member, $question);

if ($answer)
{
    redirect($CFG->url_api_dir . 'answer/update?answer_hash=' . $answer->answer_hash); //.php
}
else
{
    request_failed('Get answer failed');
}

 ?>
