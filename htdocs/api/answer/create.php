<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/answer/create.php - Kognition Answer.Create() API Method
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
require_once import($CFG->lib_question);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);
$question_hash = required_param(QUESTION_HASH, PARAM_STR, METHOD_GET);
$answer_tinymce = optional_param(ANSWER_TINYMCE, PARAM_STR, METHOD_GET);
$file_image = optional_file(UPLOAD_FILE_IMAGE, FILE_IMAGE, MAX_FILE_IMAGE_SIZE);

$answer_tinymce = $ESCAPER->escapeHtml($answer_tinymce);

$answers = array
(
    ANSWER_MODE_TINYMCE => $answer_tinymce,
    ANSWER_MODE_IMAGE => $file_image,
);

die_if_not_member($member_hash);
die_if_not_question($question_hash);

$member = get_member_from_hash($member_hash);
$question = get_question_from_hash($question_hash);

$member->touch();

die_if_cant_answer($question, $member);

$submitted_answer = $answers[$question->answer_mode];

die_if_invalid_answer($question, $submitted_answer); //make me compatible with file_image, is for now.

$answer = create_answer($question, $submitted_answer, $member);

$create_success = $answer->register();

if ($create_success)
{
    redirect($CFG->url_api_dir . 'answer/update?answer_hash=' . $answer->answer_hash); //.php
}
else
{
    request_failed('Create failed');
}

 ?>
