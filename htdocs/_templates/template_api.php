<?php

// This file is part of Kognition
//
// Message here.

/**
 * _templates/template_api.php - Kognition API Template
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

die_if_changed_ip();
//die_if_assigned_role();

handle_lost_session();

// PHP Here.

die();

 ?>
