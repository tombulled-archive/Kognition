<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/admin_lib.php - Kognition Admin library
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
//require_once import($CFG->lib_setup);

die_if_directly_requested(__FILE__);

class AdminObj
{
    //public $admin_hash;
    public $admin_username;
    public $admin_password_hash;
    public $admin_name;
    public $_exists = DEFAULT_ADMIN_EXISTS;

    public function __construct
        (
            //$admin_hash = null,
            $admin_username = null,
            $admin_password_hash = null,
            $admin_name = null,
            $_exists = DEFAULT_ADMIN_EXISTS
        )
    {
        //$this->admin_hash = $admin_hash;
        $this->admin_username = $admin_username;
        $this->admin_password_hash = $admin_password_hash;
        $this->admin_name = $admin_name;
        $this->_exists = $_exists;
    }

    public function login()
    {
        return login_admin($this);
    }

    public function attr_array()
    {
        return array
        (
            ADMIN_USERNAME => $this->admin_username,
            //ADMIN_PASSWORD_HASH => $this->admin_password_hash,
            ADMIN_NAME => $this->admin_name,
            EXISTS => $this->_exists
        );
    }

    public function attr_array_non_recursive()
    {
        return $this->attr_array();
    }
}

//function die_if_not_admin_credentials($username, $password_hash)
function die_if_not_admin_credentials($username, $password)
{
    //return $_SESSION[ADMINS]->has($username) && ;

    die_if_invalid_admin_username($username);
    //die_if_not_admins_password($username, $password_hash);
    die_if_not_admins_password($username, $password);
}

function die_if_invalid_admin_username($username)
{
    if (!admin_username_exists($username))
    {
        request_failed("Invalid admin credentials");
    }
}

//function die_if_not_admins_password($username, $password_hash)
function die_if_not_admins_password($username, $password)
{
    //if (!admin_has_password_hash($username, $password_hash))
    if (!admin_has_password($username, $password))
    {
        request_failed("Invalid admin credentials");
    }
}

function filter_admin_username($username)
{
    global $ESCAPER;

    return $ESCAPER->escapeHtml($username);
}

function die_if_not_admin($admin_username=null)
{
    if (is_null($admin_username))
    {
        //echo (int)($_SESSION[WHOAMI] instanceof AdminObj); //////////
        if (!is_null($_SESSION[WHOAMI]) && $_SESSION[WHOAMI] instanceof AdminObj)
        {
            $admin_username = $_SESSION[WHOAMI]->admin_username;
        }
        else
        {
            request_failed("You are not an admin");
        }
    }

    //die_if_invalid_admin_hash($admin_hash);

    if (!admin_username_exists($admin_username))
    {
        //request_failed("Invalid host_hash: '$host_hash'");
        request_failed("Invalid admin credentials");
    }
}

function is_admin($admin_username=null)
{
    if (is_null($admin_username))
    {
        if (!is_null($_SESSION[WHOAMI]) && $_SESSION[WHOAMI] instanceof AdminObj)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    return admin_username_exists($admin_username);
}

/*
function valid_admin_hash($admin_hash)
{
    return strlen($admin_hash) == HASH_LEN;
}

function die_if_invalid_admin_hash($admin_hash)
{
    if (! valid_admin_hash($admin_hash))
    {
        //request_failed("Invalid host_hash: '$host_hash'");
        request_failed("Invalid admin_hash");
    }
}

function die_if_not_admin($admin_hash=null)
{
    if (is_null($admin_hash))
    {
        if (!is_null($_SESSION[WHOAMI]) && $_SESSION[WHOAMI] instanceof AdminObj)
        {
            $admin_hash = $_SESSION[WHOAMI]->admin_hash;
        }
        else
        {
            request_failed("You are not an admin");
        }
    }

    die_if_invalid_admin_hash($admin_hash);

    if (!admin_hash_exists($admin_hash))
    {
        //request_failed("Invalid host_hash: '$host_hash'");
        request_failed("Invalid admin_hash");
    }
}

function is_admin($admin_hash=null)
{
    if (is_null($admin_hash))
    {
        if (!is_null($_SESSION[WHOAMI]) && $_SESSION[WHOAMI] instanceof AdminObj)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    return admin_hash_exists($admin_hash);
}*/

?>
