<?php

// This file is part of Kognition
//
// Message here.

/**
 * admin/cron.php - Kognition Cron
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
//require_once import($CFG->lib_server);
//require_once import($CFG->lib_core);
//require_once import($CFG->lib_setup);
require_once import($CFG->lib_cron);

/*
class CronTaskCleanHosts extends CronTask
{
    public function begin()
    {

    }
}*/

$db_clean_hosts_begin = function()
{
    //If anonymous function, can't use $this | Instead use class inheritance

    echo 'Finding old hosts...';
    br();
    html_tab();
    echo '... started ' . time_timestamp();// . '.';
    br();

    $hosts = $_SESSION[HOSTS]->get_all(NO_LIMIT, array(TIMESTAMP_LAST_ACCESSED . ' <= (select CURRENT_TIMESTAMP - INTERVAL 1 DAY) AND \'1\''=>1)); //yuck yuck yuck crycrycry, make '1' a constant in config_lib

    echo 'done.';
    br();

    echo 'Deleting old hosts...';
    br();
    html_tab();
    echo '... started ' . time_timestamp();// . '.';
    br();

    foreach ($hosts as $host_array)
    {
        //$host = get_host_from_hash();
        $_SESSION[HOSTS]->remove($host_array[HOST_HASH]);
        $_SESSION[CLASSES]->remove($host_array[CLASS_PIN]);
    }

    echo 'done.';
    br();
};

$db_clean_hosts = new CronTask
(
    $task_name = 'Remove old hosts and their classes',
    $task_description = '',
    $task_path = 'tool_db\task\clean_old_hosts', //this is fluff for now
    $task_exec = $db_clean_hosts_begin
);

$tasks = array
(
    $db_clean_hosts
);

cron_log_server_time();

//br(3);

foreach ($tasks as $task)
{
    $task->log_started();
    $task->begin();
    $task->log_dbqueries();
    $task->log_seconds();
    $task->log_completed();

    //br(2);
}

cron_log_completed();
cron_log_seconds();

 ?>
