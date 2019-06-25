<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/host_lib.php - Kognition Host library
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

die_if_directly_requested(__FILE__);

class HostObj
{
    public $host_name;
    public $host_hash;
    public $class;
    public $timestamp_hosted;
    public $exists = HOST_DEFAULT_EXISTS;
    public $_ip;
    public $_user_agent;
    public $timestamp_last_accessed;

    public function __construct
        (
            $host_name=null,
            $host_hash=null,
            $class=null,
            $timestamp_hosted=null,
            $exists=HOST_DEFAULT_EXISTS,
            $_ip=null,
            $_user_agent=null,
            $timestamp_last_accessed=null
        )
    {
        $this->host_name = $host_name;
        $this->host_hash = $host_hash;
        $this->class = $class;
        $this->timestamp_hosted = $timestamp_hosted;
        $this->exists = $exists;
        $this->_ip = $_ip;
        $this->_user_agent = $_user_agent;
        $this->timestamp_last_accessed = $timestamp_last_accessed;
    }

    public function update() //DEPRECATED
    {
        return $this->attr_array();
    }

    public function update_with_members()
    {
        $update = $this->update();

        $update[CLASS_][MEMBERS] = array();

        $members = $this->class->get_members();

        foreach ($members as $member)
        {
            array_push($update[CLASS_][MEMBERS], $member->attr_array_no_class());
        }

        return $update;
    }

    public function attr_array()
    {
        $class_attrs = (!is_null($this->class) && method_exists($this->class, 'attr_array_no_host'))
            ? $this->class->attr_array_no_host()
            : null;

        return array
        (
            HOST_NAME=>$this->host_name,
            HOST_HASH=>$this->host_hash,
            CLASS_=>$class_attrs,
            TIMESTAMP_HOSTED=>$this->timestamp_hosted,
            EXISTS=>$this->exists,
            TIMESTAMP_LAST_ACCESSED=>$this->timestamp_last_accessed
        );
    }

    public function attr_array_no_class()
    {
        return array
        (
            HOST_NAME=>$this->host_name,
            HOST_HASH=>$this->host_hash,
            TIMESTAMP_HOSTED=>$this->timestamp_hosted,
            EXISTS=>$this->exists,
            TIMESTAMP_LAST_ACCESSED=>$this->timestamp_last_accessed
        );
    }

    public function attr_array_non_recursive()
    {
        return array
        (
            HOST_NAME=>$this->host_name,
            HOST_HASH=>$this->host_hash,
            TIMESTAMP_HOSTED=>$this->timestamp_hosted,
            EXISTS=>$this->exists,
            TIMESTAMP_LAST_ACCESSED=>$this->timestamp_last_accessed
        );
    }

    public function end_host()
    {
        if (host_hash_exists($this->host_hash))
        {
            $this->deregister();
            deregister_class($this->class); //added, classes not getting de-registered.
        }

        return true; //check correct
    }

    public function delete()
    {
        if ($this->exists)
        {
            $deregister_success = $this->deregister();

            if ($deregister_success)
            {
                $this->exists = false;

                return true;
            }
            else
            {
                return false;
            }
        }

        return true; //check me
    }

    public function leave()
    {
        $this->end_host();
    }

    public function register()
    {
        return register_host($this);
    }

    public function deregister()
    {
        $deregister_class_success = $this->class->deregister();
        $deregister_host_success = deregister_host($this);

        return $deregister_class_success && $deregister_host_success; //use all()
    }

    public function edit($key_vals)
    {
        $this->host_name = $key_vals[HOST_NAME] ?? $this->host_name;
        $this->timestamp_last_accessed = $key_vals[TIMESTAMP_LAST_ACCESSED] ?? $this->timestamp_last_accessed;

        if (host_hash_exists($this->host_hash)) //use $this->exists
        {
            return $this->commit();
        }

        return false;
    }

    public function commit()
    {
        return update_host($this);
        //update_class?
    }

    public function kick_member($member)
    {
        $delete_member_success = $member->delete(); //For Now: 'Kicking' a Member essentially 'Kills' them. A bit savage...

        return $delete_member_success;
    }

    public function get_primary_key()
    {
        return $this->host_hash;
    }

    public function ping() //deprecated ???
    {
        $attrs = $this->attr_array(); //attr_array_no_class?

        unset($attrs[HOST_HASH]);

        return $attrs;
    }

    public function get_questions() //TEST ME
    {
        return get_hosts_questions($this);
    }

    public function get_total_questions() //TEST ME
    {
        return count($this->get_questions()); //BAD PRACTICE
    }

    public function touch()
    {
        return $this->edit(array(TIMESTAMP_LAST_ACCESSED => timestamp()));
    }
}

function create_host($host_name, $class=null)
{
    $host = new HostObj
    (
        $host_name = $host_name,
        $host_hash = generate_hash(),
        $class = $class,
        $timestamp_hosted = timestamp(),
        $exists = true,
        $_ip = REQUEST_IP,
        $_user_agent = REQUEST_USER_AGENT,
        $timestamp_last_accessed = timestamp()
    );

    return $host;
}

function valid_host_hash($host_hash)
{
    return strlen($host_hash) == HASH_LEN;
}

function die_if_invalid_host_hash($host_hash)
{
    if (! valid_host_hash($host_hash))
    {
        //request_failed("Invalid host_hash: '$host_hash'");
        request_failed("Invalid host_hash");
    }
}

function die_if_not_host($host_hash=null)
{

    if (is_null($host_hash))
    {
        if (!is_null($_SESSION[WHOAMI]) && property_exists($_SESSION[WHOAMI], HOST_HASH))
        {
            $host_hash = $_SESSION[WHOAMI]->host_hash;
        }
        else
        {
            request_failed("You are not a host");
        }
    }

    die_if_invalid_host_hash($host_hash);

    if (! host_hash_exists($host_hash))
    {
        //request_failed("Invalid host_hash: '$host_hash'");
        request_failed("Invalid host_hash");
    }
}

 ?>
