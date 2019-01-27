<?php

// This file is part of Kognition
//
// Message here.

/**
 * _templates/template_index_page.php - Kognition Page Template
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once import($CFG->lib_setup);
require_once import($CFG->lib_core);

log_request();

die_if_blocked();
die_if_maintainance();
//die_if_not_accepted_cookies();

handle_lost_session();

// PHP Here.

 ?>
 <!-- HTMML Here.-->
