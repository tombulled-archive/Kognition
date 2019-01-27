<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/server/whoami.php - Kognition Server.Whoami() API Method
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
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();

$api_out = array();

$api_out[HOST] = null;
$api_out[MEMBER] = null;
$api_out[CLASS_] = null;

if ($_SESSION[WHOAMI] instanceof HostObj)
{
    $api_out[HOST] = $_SESSION[WHOAMI]->attr_array_non_recursive();
    $api_out[CLASS_] = $_SESSION[WHOAMI]->class->attr_array_non_recursive();
}
elseif ($_SESSION[WHOAMI] instanceof MemberObj)
{
    $api_out[MEMBER] = $_SESSION[WHOAMI]->attr_array_non_recursive();
    $api_out[CLASS_] = $_SESSION[WHOAMI]->class->attr_array_non_recursive();
}

request_success($api_out);

 ?>
