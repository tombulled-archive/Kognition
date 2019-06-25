<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/cron_lib.php - Kognition Cron library
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once import($CFG->lib_server);
require_once import($CFG->lib_core);
require_once import($CFG->lib_setup);

die_if_directly_requested(__FILE__);

class CronTask
{
    public $task_name = 'Cron task';
    public $task_description = 'Cron task';
    public $task_path = '';
    public $_log_prefix = '...';
    public $_used_dbqueries = 0;
    public $_used_seconds = 0.0;
    public $_used_memory = 0.0;
    public $task_exec = null;

    public function __construct
        (
            $task_name = 'Cron task',
            $task_description = 'Cron task',
            $task_path = '',
            $task_exec = null
        )
    {
        $this->task_name = $task_name;
        $this->task_description = $task_description;
        $this->task_path = $task_path;
        $this->task_exec = $task_exec;
    }

    public function log_started()
    {
        echo "Execute scheduled task: $this->task_name ($this->task_path)";

        br();

        echo "$this->_log_prefix started " . time_timestamp(); // . '. Current memory use ?.? MB';

        br();
    }

    public function log_completed()
    {
        echo "Scheduled task complete: $this->task_name ($this->task_path)";

        br();
    }

    public function log_dbqueries()
    {
        echo "$this->_log_prefix used $this->_used_dbqueries dbqueries";

        br();
    }

    public function log_seconds()
    {
        echo "$this->_log_prefix used $this->_used_seconds seconds";

        br();
    }

    public function begin()
    {
        if (!is_null($this->task_exec))
        {
            $_anon_func = $this->task_exec;
            $_anon_func();
        }
    }
}

function server_timestamp()
{
    return date('D, d M Y H:i:s', time());
}

function time_timestamp()
{
    return date('H:i:s', time());
}

function cron_log_server_time()
{
    echo "Server Time: " . server_timestamp();

    br();
}

function cron_log_completed()
{
    echo 'Cron script completed correctly';

    br();

    echo 'Cron completed at ' . time_timestamp() . '.'; //Memory used

    br();
}

function cron_log_seconds()
{
    global $used_seconds;

    echo "Execution took $used_seconds seconds";

    br();
}

$used_memory = 0.0;
$used_seconds = 0.0;

 ?>
