<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/member_lib.php - Kognition Member library
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

class MemberObj
{
    public $member_name;
    public $member_hash;
    public $class;
    public $timestamp_joined;
    public $exists = MEMBER_DEFAULT_EXISTS;
    public $rag_status = DEFAULT_MEMBER_RAG_STATUS;
    public $_ip;
    public $_user_agent;
    public $timestamp_last_accessed;

    public function __construct
        (
            $member_hash=null,
            $member_name=null,
            $class=null,
            $timestamp_joined=null,
            $exists=MEMBER_DEFAULT_EXISTS,
            $rag_status = DEFAULT_MEMBER_RAG_STATUS,
            $_ip=null,
            $_user_agent=null,
            $timestamp_last_accessed=null
        )
    {
        $this->member_name = $member_name;
        $this->member_hash = $member_hash;
        $this->class = $class;
        $this->timestamp_joined = $timestamp_joined;
        $this->exists = $exists;
        $this->rag_status = $rag_status;
        $this->_ip = $_ip;
        $this->_user_agent = $_user_agent;
        $this->timestamp_last_accessed = $timestamp_last_accessed;
    }

    public function update()
    {
        return array_merge($this->attr_array(), request_success_array());
    }

    public function attr_array()
    {
        $class_attrs = (!is_null($this->class) && method_exists($this->class, ATTR_ARRAY))
            //? $this->class->attr_array()
            ? $this->class->ping()
            : null;

        return array
        (
            MEMBER_NAME=>$this->member_name,
            MEMBER_HASH=>$this->member_hash,
            CLASS_=>$class_attrs,
            TIMESTAMP_JOINED=>$this->timestamp_joined,
            EXISTS=>$this->exists,
            RAG_STATUS=>$this->rag_status,
            TIMESTAMP_LAST_ACCESSED=>$this->timestamp_last_accessed
        );
    }

    public function attr_array_no_class()
    {
        return array
        (
            MEMBER_NAME=>$this->member_name,
            MEMBER_HASH=>$this->member_hash,
            TIMESTAMP_JOINED=>$this->timestamp_joined,
            EXISTS=>$this->exists,
            RAG_STATUS=>$this->rag_status,
            TIMESTAMP_LAST_ACCESSED=>$this->timestamp_last_accessed
        );
    }

    public function attr_array_non_recursive()
    {
        return array
        (
            MEMBER_NAME=>$this->member_name,
            MEMBER_HASH=>$this->member_hash,
            TIMESTAMP_JOINED=>$this->timestamp_joined,
            EXISTS=>$this->exists,
            RAG_STATUS=>$this->rag_status,
            TIMESTAMP_LAST_ACCESSED=>$this->timestamp_last_accessed
        );
    }

    public function leave() //deprecated
    {
        if (member_hash_exists($this->member_hash))
        {
            $this->deregister();
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
            }

            return $deregister_success;
        }

        return true; //check correct
    }

    public function register()
    {
        return register_member($this);
    }

    public function deregister()
    {
        return deregister_member($this);
    }

    public function edit($key_vals)
    {
        $this->member_name = $key_vals[MEMBER_NAME] ?? $this->member_name;
        $this->rag_status = $key_vals[RAG_STATUS] ?? $this->rag_status;
        $this->timestamp_last_accessed = $key_vals[TIMESTAMP_LAST_ACCESSED] ?? $this->timestamp_joined;

        if (member_hash_exists($this->member_hash))
        {
            $this->commit();
        }

        return true; //check correct;
    }

    public function commit()
    {
        update_member($this);
    }

    public function get_primary_key()
    {
        return $this->member_hash;
    }

    /*
    public function ping()
    {
        $attrs = $this->attr_array();

        unset($attrs[MEMBER_HASH]);

        return array_merge($attrs, request_success_array());
    }
    */

    public function touch()
    {
        $this->edit(array(TIMESTAMP_LAST_ACCESSED => timestamp()));
    }
}

function valid_member_hash($member_hash)
{
    return strlen($member_hash) == HASH_LEN;
}

function die_if_invalid_member_hash($member_hash)
{
    if (! valid_member_hash($member_hash))
    {
        //request_failed("Invalid member_hash: '$member_hash'");
        request_failed("Invalid member_hash");
    }
}

function die_if_not_member($member_hash=null)
{
    if (is_null($member_hash))
    {
        if (!is_null($_SESSION[WHOAMI]) && property_exists($_SESSION[WHOAMI], MEMBER_HASH))
        {
            $member_hash = $_SESSION[WHOAMI]->member_hash;
        }
        else
        {
            request_failed("You are not a member");
        }
    }

    die_if_invalid_member_hash($member_hash);

    if (! member_hash_exists($member_hash))
    {
        //request_failed("Invalid member_hash: '$member_hash'");
        request_failed("Invalid member_hash");
    }
}

function create_member($member_name, $class=null)
{
    $member = new MemberObj
    (
        $member_hash = generate_hash(),
        $member_name = $member_name,
        $class = $class,
        $timestamp_joined = timestamp(),
        $exists = true,
        $rag_status = DEFAULT_MEMBER_RAG_STATUS,
        $_ip = REQUEST_IP,
        $_user_agent = REQUEST_USER_AGENT,
        $timestamp_last_accessed = timestamp()
    );

    return $member;
}

function die_if_not_rag_status($rag_status)
{
    global $RAG_STATUSES;

    if (!in_array($rag_status, $RAG_STATUSES))
    {
        //% (format) $rag_status
        request_failed("Invalid rag_status");
    }
}

 ?>
