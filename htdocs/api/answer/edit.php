<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/answer/edit.php - Kognition Answer.Edit() API Method
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
$answer_tinymce = optional_param(ANSWER_TINYMCE, PARAM_STR, METHOD_GET);

$answer_tinymce = $ESCAPER->escapeHtml($answer_tinymce); //should i just be here like this?

$answers = array
(
    ANSWER_MODE_TINYMCE => $answer_tinymce
);

die_if_not_answer($answer_hash);
die_if_not_member($member_hash);

$answer = get_answer_from_hash($answer_hash);
$member = get_member_from_hash($member_hash);

$member->touch();

die_if_didnt_answer($answer, $member);
die_if_question_closed($answer->question);

$submitted_answer = $answers[$answer->question->answer_mode];

die_if_invalid_answer($answer->question, $submitted_answer);

$edit_success = $answer->edit
(
    array
    (
        ANSWER_TINYMCE => $answer_tinymce
    )
);

if ($edit_success)
{
    //request_success();

    //$redirect_params = array
    //(
    //    ANSWER_HASH => $answer_hash,
    //    MEMBER_HASH => $member_hash,
    //);

    redirect
    (
        $CFG->url_api_dir .
        'answer/update?' .
        ANSWER_HASH . '=' . $answer_hash . '&' .
        MEMBER_HASH . '=' . $member_hash
    );
}
else
{
    request_failed('Edit failed');
}

 ?>
