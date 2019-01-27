<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/member/_handle_created.php - Kognition Member._Handle_Created() API Method
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

log_request();
die_if_blocked();
die_if_maintainance();

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);

redirect($CFG->url_api_dir . 'member/update?member_hash=' . $member_hash); //.php');

 ?>
