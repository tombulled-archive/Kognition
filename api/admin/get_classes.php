<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/admin/get_classes.php - Kognition Admin.Get_Classes() API Method
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
require_once import($CFG->lib_admin);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();

handle_lost_session();

die_if_not_admin();

$admin = $_SESSION[WHOAMI];

$classes = discover_classes(NO_LIMIT, $public_essential=false);

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

//$hosts = get_all_hosts();

//$api_out = array();

//$admin_attrs = $admin->attr_array_non_recursive();

//$api_out[ADMIN] = $admin_attrs;

request_success($api_out);

 ?>
