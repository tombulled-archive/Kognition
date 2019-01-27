<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/edit.php - Kognition Host.Edit() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);
$host_name = optional_param(HOST_NAME, PARAM_STR, METHOD_GET);

$host_name = $ESCAPER->escapeHtml($host_name); //should i just be here like this?

die_if_not_host($host_hash);

$host = get_host_from_hash($host_hash);

$host->touch();

$edit_success = $host->edit
(
    array
    (
        HOST_NAME => $host_name
    )
);

if ($edit_success)
{
    //request_success();

    redirect
    (
        $CFG->url_api_dir .
        'host/update?' .
        HOST_HASH . '=' . $host_hash . '&' .
        SHOW_MEMBERS . '=' . DEFAULT_UPDATE_SHOW_MEMBERS
    );
}
else
{
    request_failed('Edit failed');
}

 ?>
