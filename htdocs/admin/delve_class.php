<?php

// This file is part of Kognition
//
// Message here.

/**
 * admin/delve_class.php - Kognition Admin Delve Class Page
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
require_once import($CFG->lib_admin);
require_once import($CFG->lib_class);
require_once import($CFG->lib_member);
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();
//die_if_not_accepted_cookies();

//die_error(HTTP_STATUS_403);

//die_if_not_admin();

is_admin() || redirect($CFG->url_admin_dir . "login");

$class_pin = required_param(CLASS_PIN, PARAM_INT, METHOD_GET);

die_if_not_class($class_pin);

$class = get_class_from_pin($class_pin);
$host = $class->host;

$members = $class->get_members();

$admin = $_SESSION[WHOAMI];



?>
<!DOCTYPE html>
<html>
<head>
<style>
table {
 font-family: arial, sans-serif;
 border-collapse: collapse;
 width: 100%;
}

td, th {
 border: 1px solid #dddddd;
 text-align: left;
 padding: 8px;
}

tr:nth-child(even) {
 background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Class</h2>

<table>
<tr>
 <th>Name</th>
 <th>Pin</th>
 <th>Closed</th>
 <th>Public</th>
 <th>Created</th>
</tr>
<?php
    $class_name = $class->class_name;
    $class_pin = $class->class_pin;
    $class_closed = array(0 => 'False', 1 => 'True')[$class->closed];
    $class_public = array(0 => 'False', 1 => 'True')[$class->class_public];
    $class_created = $class->timestamp_created;

    echo "<tr>\n";
    echo "\t<td>$class_name</td>\n";
    echo "\t<td>$class_pin</td>\n";
    echo "\t<td>$class_closed</td>\n";
    echo "\t<td>$class_public</td>\n";
    echo "\t<td>$class_created</td>\n";
    echo "</tr>\n";
 ?>
</table>

<h2>Host</h2>

<table>
<tr>
 <th>Name</th>
 <th>Hash</th>
 <th>Created</th>
 <th>Last Accessed</th>
 <th>IP</th>
 <th>User Agent</th>
</tr>
<?php
    $host_name = $host->host_name;
    $host_hash = $host->host_hash;
    $host_created = $host->timestamp_hosted;
    $host_last_accessed = $host->timestamp_last_accessed;
    $host_ip = $host->_ip;
    $host_user_agent = $host->_user_agent;

    echo "<tr>\n";
    echo "\t<td>$host_name</td>\n";
    echo "\t<td>$host_hash</td>\n";
    echo "\t<td>$host_created</td>\n";
    echo "\t<td>$host_last_accessed</td>\n";
    echo "\t<td>$host_ip</td>\n";
    echo "\t<td>$host_user_agent</td>\n";
    echo "</tr>\n";
 ?>
</table>

<h2>Members</h2>

<table>
<tr>
 <th>Name</th>
 <th>Hash</th>
 <th>RAG Status</th>
 <th>Created</th>
 <th>Last Accessed</th>
 <th>IP</th>
 <th>User Agent</th>
</tr>
<?php
    foreach ($members as $member)
    {
        $member_name = $member->member_name;
        $member_hash = $member->member_hash;
        $member_rag_status = $member->rag_status;
        $member_created = $member->timestamp_joined;
        $member_last_accessed = $member->timestamp_last_accessed;
        $member_ip = $member->_ip;
        $member_user_agent = $member->_user_agent;

        echo "<tr>\n";
        echo "\t<td>$member_name</td>\n";
        echo "\t<td>$member_hash</td>\n";
        echo "\t<td>$member_rag_status</td>\n";
        echo "\t<td>$member_created</td>\n";
        echo "\t<td>$member_last_accessed</td>\n";
        echo "\t<td>$member_ip</td>\n";
        echo "\t<td>$member_user_agent</td>\n";
        echo "</tr>\n";
    }
 ?>
</table>
