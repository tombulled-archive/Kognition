<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/class_lib.php - Kognition Class library
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

class ClassObj
{
    public $class_pin;
    public $class_name;
    public $closed = CLASS_DEFAULT_CLOSED;
    public $timestamp_created;
    public $host;
    public $exists = CLASS_DEFAULT_EXISTS;
    public $class_public = DEFAULT_CLASS_PUBLIC;

    public function __construct
        (
            $class_pin=null,
            $class_name=null,
            $closed=CLASS_DEFAULT_CLOSED,
            $timestamp_created=null,
            $host=null,
            $exists=CLASS_DEFAULT_EXISTS,
            $class_public=DEFAULT_CLASS_PUBLIC
        )
    {
        $this->class_pin = $class_pin;
        $this->class_name = $class_name;
        $this->closed = $closed;
        $this->timestamp_created = $timestamp_created;
        $this->host = $host;
        $this->exists = $exists;
        $this->class_public = $class_public;
    }

    public function ping() //deprecated?
    {
        $class_attrs = $this->attr_array_non_recursive();

        unset($class_attrs[HOST][HOST_HASH]);

        return $class_attrs;
    }

    public function attr_array()
    {
        $host_attrs = (!is_null($this->host) && method_exists($this->host, 'attr_array_no_class'))
            ? $this->host->attr_array_no_class()
            : null;

        return array
        (
            CLASS_PIN=>$this->class_pin,
            CLASS_NAME=>$this->class_name,
            CLOSED=>$this->closed,
            TIMESTAMP_CREATED=>$this->timestamp_created,
            HOST=>$host_attrs,
            EXISTS=>$this->exists,
            CLASS_PUBLIC=>$this->class_public
        );
    }

    public function attr_array_no_host()
    {
        return array
        (
            CLASS_PIN=>$this->class_pin,
            CLASS_NAME=>$this->class_name,
            CLOSED=>$this->closed,
            TIMESTAMP_CREATED=>$this->timestamp_created,
            EXISTS=>$this->exists,
            CLASS_PUBLIC=>$this->class_public
        );
    }

    public function attr_array_non_recursive()
    {
        return array
        (
            CLASS_PIN=>$this->class_pin,
            CLASS_NAME=>$this->class_name,
            CLOSED=>$this->closed,
            TIMESTAMP_CREATED=>$this->timestamp_created,
            EXISTS=>$this->exists,
            CLASS_PUBLIC=>$this->class_public
        );
    }

    public function register()
    {
        return register_class($this);
    }

    public function deregister()
    {
        return deregister_class($this);
    }

    public function get_primary_key()
    {
        return $this->class_pin;
    }

    public function get_members()
    {
        return get_all_members_of_class($this);
    }

    public function get_total_members()
    {
        return count($this->get_members());
    }

    public function edit($key_vals)
    {
        $this->class_name = $key_vals[CLASS_NAME] ?? $this->class_name;
        $this->closed = $key_vals[CLOSED] ?? $this->closed;
        $this->class_public = $key_vals[CLASS_PUBLIC] ?? $this->class_public; //Just update everything?

        if ($this->exists)
        {
            return $this->commit();
        }

        return false; //the DB did not get updated, however the object did
    }

    public function commit()
    {
        return update_class($this);
    }
}

function valid_class_pin($class_pin)
{
    return int_length($class_pin) == CLASS_PIN_LEN;
}

function die_if_invalid_class_pin($class_pin)
{
    if (! valid_class_pin($class_pin))
    {
        //request_failed("Invalid class_pin: '$class_pin'");
        request_failed("Invalid class_pin");
    }
}

function die_if_not_class($class_pin)
{
    die_if_invalid_class_pin($class_pin);

    if (! class_pin_exists($class_pin))
    {
        //request_failed("Invalid class_pin: '$class_pin'");
        request_failed("Invalid class_pin");
    }
}

function create_class($class_name, $host=null)
{
    $class = new ClassObj
    (
        $class_pin = generate_pin(),
        $class_name = $class_name,
        $closed = CLASS_DEFAULT_CLOSED,
        $timestamp_created = timestamp(),
        $host = $host,
        $exists = true,
        $class_public = DEFAULT_CLASS_PUBLIC
    );

    return $class;
}

function die_if_class_closed($class)
{
    if ($class->closed)
    {
        request_failed("Class closed");
    }
}

function die_if_not_in_class($class, $member)
{
    if (!member_in_class($class, $member))
    {
        request_failed('Member is not in class');
    }
}

function die_if_not_owns_class($class, $host)
{
    if ($class->class_pin != $host->class->class_pin)
    {
        request_failed('Host doesn\'t own class');
    }
}

function die_if_class_full($class)
{
    if ($class->get_total_members() >= MAX_CLASS_SIZE)
    {
        request_failed("Class is full");
    }
}

function die_if_cant_join_class($class)
{
    die_if_class_closed($class);
    die_if_class_full($class);
}

 ?>
