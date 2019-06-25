<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/question/edit.php - Kognition Question.Edit() API Method
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
$question_hash = required_param(QUESTION_HASH, PARAM_STR, METHOD_GET);
$question_name = optional_param(QUESTION_NAME, PARAM_STR, METHOD_GET);
$question_text = optional_param(QUESTION_TEXT, PARAM_STR, METHOD_GET);
$question_closed = optional_param(QUESTION_CLOSED, PARAM_BOOL, METHOD_GET);

$question_text = $ESCAPER->escapeHtml($question_text); //should i just be here like this?
$question_name = $ESCAPER->escapeHtml($question_name); //should i just be here like this?

die_if_not_host($host_hash);
die_if_not_question($question_hash);

$host = get_host_from_hash($host_hash);
$question = get_question_from_hash($question_hash);

$host->touch();

$edit_success = $question->edit
(
    array
    (
        QUESTION_NAME => $question_name,
        QUESTION_TEXT => $question_text,
        QUESTION_CLOSED => $question_closed
    )
);

if ($edit_success)
{
    //request_success();

    redirect
    (
        $CFG->url_api_dir .
        'question/update?' .
        HOST_HASH . '=' . $host_hash . '&' .
        QUESTION_HASH . '=' . $question_hash
    );
}
else
{
    request_failed('Edit failed');
}

 ?>
