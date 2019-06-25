<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/host/create.php - Kognition Host.Create() API Method
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
require_once import($CFG->lib_class);
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();
die_if_assigned_role();

handle_lost_session();

$host_name = required_param(HOST_NAME, PARAM_STR, METHOD_GET);
$class_name = required_param(CLASS_NAME, PARAM_STR, METHOD_GET);

$host_name = $ESCAPER->escapeHtml($host_name); //should i just be here like this?
$class_name = $ESCAPER->escapeHtml($class_name); //should i just be here like this?

$host = create_host($host_name);
$class = create_class($class_name);

$host->class = $class;
$class->host = $host;

$register_class_success = $class->register();
$register_host_success = $host->register();

if ($register_class_success && $register_host_success)
{
    redirect($CFG->url_api_dir . 'host/_handle_created?host_hash=' . $host->host_hash); //.php');
}
else
{
    request_failed('Create failed');
}

 ?>
