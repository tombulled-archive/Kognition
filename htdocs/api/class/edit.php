<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/class/edit.php - Kognition Class.Edit() API Method
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

$class_pin = required_param(CLASS_PIN, PARAM_INT, METHOD_GET);
$host_hash = required_param(HOST_HASH, PARAM_STR, METHOD_GET);
$class_name = optional_param(CLASS_NAME, PARAM_STR, METHOD_GET);
$closed = optional_param(CLOSED, PARAM_BOOL, METHOD_GET);
$class_public = optional_param(CLASS_PUBLIC, PARAM_BOOL, METHOD_GET);

$class_name = $ESCAPER->escapeHtml($class_name); //should i just be here like this?

die_if_not_host($host_hash);
die_if_not_class($class_pin);

$class = get_class_from_pin($class_pin);
$host = get_host_from_hash($host_hash);

$host->touch();

die_if_not_owns_class($class, $host);

$edit_success = $class->edit
(
    array
    (
        CLASS_NAME => $class_name,
        CLOSED => $closed,
        CLASS_PUBLIC => $class_public
    )
);

if ($edit_success)
{
    //request_success();

    redirect
    (
        $CFG->url_api_dir .
        'class/update?' .
        CLASS_PIN . '=' . $class_pin . '&' .
        HOST_HASH . '=' . $host_hash
    );
}
else
{
    request_failed('Edit failed');
}

 ?>
