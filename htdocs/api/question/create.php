<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/question/create.php - Kognition Question.Create() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);
$question_text = required_param(QUESTION_TEXT, PARAM_STR, METHOD_GET);
$answer_mode = required_param(ANSWER_MODE, PARAM_STR, METHOD_GET);
$question_name = optional_param(QUESTION_NAME, PARAM_STR, METHOD_GET);

$question_text = $ESCAPER->escapeHtml($question_text); //should i just be here like this?
$question_name = $ESCAPER->escapeHtml($question_name); //should i just be here like this?

$answer_mode = strtolower($answer_mode);

die_if_not_host($host_hash);
die_if_not_answer_mode($answer_mode);

$host = get_host_from_hash($host_hash);

$host->touch();

die_if_cant_create_question($host);

$question = create_question($question_text, $answer_mode, $question_name, $host);

$register_success = $question->register();

if ($register_success)
{
    redirect($CFG->url_api_dir . 'question/update.php?question_hash=' . $question->question_hash);
}
else
{
    request_failed('Create failed');
}

 ?>
