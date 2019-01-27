<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/server/find_classes.php - Kognition Server.Find_Classes() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

handle_lost_session();

$total = optional_param(TOTAL, PARAM_INT, METHOD_GET) ?? DEFAULT_FIND_CLASSES_TOTAL;

$classes = discover_classes($total);

$api_out = array();

$classes_attrs = array();

foreach ($classes as $class)
{
    $classes_attrs_entry = array();

    $class_attrs = $class->attr_array_non_recursive();
    $host_attrs = $class->host->attr_array_non_recursive();

    unset($host_attrs[HOST_HASH]);

    $classes_attrs_entry[CLASS_] = $class_attrs;
    $classes_attrs_entry[HOST] = $host_attrs;

    array_push($classes_attrs, $classes_attrs_entry);
}

$api_out[CLASSES] = $classes_attrs;

request_success($api_out);

 ?>
