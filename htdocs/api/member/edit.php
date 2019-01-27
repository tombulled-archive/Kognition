<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/member/edit.php - Kognition Member.Edit() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);
$member_name = optional_param(MEMBER_NAME, PARAM_STR, METHOD_GET);
$rag_status = optional_param(RAG_STATUS, PARAM_STR, METHOD_GET);

if (! is_null($rag_status))
{
    $rag_status = strtolower($rag_status);

    die_if_not_rag_status($rag_status);
}

if (! is_null($member_name))
{
    $member_name = $ESCAPER->escapeHtml($member_name); //should i just be here like this?
}

die_if_not_member($member_hash);

$member = get_member_from_hash($member_hash);

$member->touch();

$edit_success = $member->edit
(
    array
    (
        MEMBER_NAME => $member_name,
        RAG_STATUS => $rag_status,
    )
);

if ($edit_success)
{
    //request_success();

    //DEFAULT_UPDATE_SHOW_MEMBERS

    redirect
    (
        $CFG->url_api_dir .
        'member/update?' .
        MEMBER_HASH . '=' . $member_hash
    );
}
else
{
    request_failed('Edit failed');
}

 ?>
