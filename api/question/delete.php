<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/question/delete.php - Kognition Question.Delete() API Method
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

die_if_not_host($host_hash);
die_if_not_question($question_hash);

$host = get_host_from_hash($host_hash);
$question = get_question_from_hash($question_hash);

$host->touch();

die_if_not_owns_question($question, $host);

$delete_success = $question->delete();

if ($delete_success)
{
    request_success();
}
else
{
    request_failed('Delete failed');
}

 ?>
