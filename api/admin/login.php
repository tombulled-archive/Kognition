<?php

// This file is part of Kognition
//
// Message here.

/**
 * api/admin/login.php - Kognition Admin.Login() API Method
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
//require_once import($CFG->lib_member);
//require_once import($CFG->lib_class);
require_once import($CFG->lib_admin);

log_request();
die_if_blocked();
die_if_maintainance();

die_if_changed_ip();
die_if_assigned_role();

handle_lost_session();

$admin_username = required_param(ADMIN_USERNAME, PARAM_STR, METHOD_GET);
$admin_password = required_param(ADMIN_PASSWORD, PARAM_STR, METHOD_GET); //HERE LAST

//$admin_password_hash = bcrypt_hash($admin_password);

//echo $admin_password_hash;

//$admin_username = $ESCAPER->escapeHtml($admin_username);
$admin_username = filter_admin_username($admin_username);

//die_if_not_admin_credentials($admin_username, $admin_password_hash);
die_if_not_admin_credentials($admin_username, $admin_password);

$admin = get_admin_from_username($admin_username);

$login_success = $admin->login();

if ($login_success)
{
    redirect($CFG->url_api_dir . 'admin/_handle_login'); //.php');
}
else
{
    request_failed('Login failed');
}



/*$member_name = required_param(MEMBER_NAME, PARAM_STR, METHOD_GET);
$class_pin = required_param(CLASS_PIN, PARAM_INT, METHOD_GET);

$member_name = $ESCAPER->escapeHtml($member_name); //should i just be here like this?
//$member_name = addslashes($member_name);
//$member_name = $ESCAPER->escapeHtml(addslashes($member_name));

die_if_not_class($class_pin);

$class = get_class_from_pin($class_pin);

die_if_cant_join_class($class);

$member = create_member($member_name, $class);

$create_success = $member->register();

if ($create_success)
{
    redirect($CFG->url_api_dir . 'member/_handle_created.php');
}
else
{
    request_failed('Create failed');
}*/

 ?>
