<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/setup_lib.php - Kognition Setup library
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
//require_once import($CFG->lib_core);
require_once import($CFG->lib_db);
require_once import($CFG->lib_class);
require_once import($CFG->lib_member);
require_once import($CFG->lib_host);
require_once import($CFG->lib_question);
require_once import($CFG->lib_answer);
require_once import($CFG->lib_admin);

//die_if_directly_requested(__FILE__);

function no_session()
{
    return !isset($_SESSION);
}

function session_var_exists($var_key)
{
    return isset($_SESSION[$var_key]);
}

function set_session()
{
    if (no_session())
    {
        session_name(SESSION_NAME);
        session_start();
    }
}

function set_timezone()
{
    global $CFG;

    if (date_default_timezone_get() != $CFG->timezone)
    {
        date_default_timezone_set($CFG->timezone);
    }
}

function create_session_var($var_key, $init_val=null)
{
    if (! session_var_exists($var_key))
    {
        $_SESSION[$var_key] = $init_val;
    }
}

function create_session_cookie($var_key, $expire=EXPIRE_NOW, $path=PATH_ROOT)
{
    if (! is_null($_SESSION[$var_key]) && (! cookie_exists($var_key) || $_COOKIE[$var_key] != $_SESSION[$var_key]))
    {
        setcookie($var_key, $_SESSION[$var_key], $expire, $path);
    }
}

function ip_has_obj($ip)
{
    global $DB_CORE;

    $host_hash = $_SESSION[HOSTS]->find(IP, $ip)[0][HOST_HASH] ?? null;
    $member_hash = $_SESSION[MEMBERS]->find(IP, $ip)[0][MEMBER_HASH] ?? null;

    return !is_null($host_hash) || !is_null($member_hash);
}

function check_lost_session()
{
    return;

    /*$has_obj = ip_has_obj(REQUEST_IP);

    if ($has_obj && is_null($_SESSION[WHOAMI]))
    {
        $_SESSION[SESSION_LOST] = true;
    }*/
}

function kill_session()
{
    //session_destroy(); //test me

    $_SESSION[HOST_HASH] = null;
    $_SESSION[MEMBER_HASH] = null;
    $_SESSION[CLASS_PIN] = null;
    $_SESSION[WHOAMI] = null;
    $_SESSION[IP_ADDRESS] = null;

    //$_SESSION[ADMIN_HASH] = null;
    $_SESSION[IS_ADMIN] = null;

    delete_session_cookie(MEMBER_HASH);
    delete_session_cookie(HOST_HASH);
    delete_session_cookie(CLASS_PIN);

    //delete_session_cookie(ADMIN_HASH);
}

function delete_session_cookie($var_key, $path=PATH_ROOT)
{
    unset($_COOKIE[$var_key]);
    setcookie($var_key, "", time()-3600, $path);
}

set_session();

create_session_var(MEMBER_HASH, null);
create_session_var(HOST_HASH, null);
create_session_var(CLASS_PIN, null);

//create_session_var(ADMIN_HASH, null);
create_session_var(IS_ADMIN, null);

create_session_var(WHOAMI, null);
create_session_var(IP_ADDRESS, null);

create_session_var(ACCEPTED_COOKIES, DEFAULT_ACCEPTED_COOKIES);

create_session_var(MEMBERS, new Table(TABLE_MEMBERS, MEMBER_HASH));
create_session_var(HOSTS, new Table(TABLE_HOSTS, HOST_HASH));
create_session_var(CLASSES, new Table(TABLE_CLASSES, CLASS_PIN));
create_session_var(BLACKLIST, new Table(TABLE_IP_BLACKLIST, IP));
create_session_var(ACTIVITY_LOG, new Table(TABLE_ACTIVITY_LOG, ID));
create_session_var(QUESTIONS, new Table(TABLE_QUESTIONS, QUESTION_HASH));
create_session_var(ANSWERS, new Table(TABLE_ANSWERS, ANSWER_HASH));

//create_session_var(ADMINS, new Table(TABLE_ADMINS, ADMIN_HASH));
create_session_var(ADMINS, new Table(TABLE_ADMINS, ADMIN_USERNAME));

create_session_var(IMAGES, new Table(TABLE_IMAGES, FILE_HASH)); //NEW

create_session_var(SESSION_LOST, false);

create_session_cookie(MEMBER_HASH, EXPIRE_NOW, PATH_ROOT);
create_session_cookie(HOST_HASH, EXPIRE_NOW, PATH_ROOT);
create_session_cookie(CLASS_PIN, EXPIRE_NOW, PATH_ROOT);

//create_session_cookie(ADMIN_HASH, EXPIRE_NOW, PATH_ROOT);
create_session_cookie(IS_ADMIN, EXPIRE_NOW, PATH_ROOT);

create_session_cookie(ACCEPTED_COOKIES, EXPIRE_NOW, PATH_ROOT);

sql_success_or_die(!$DB_CORE->connect_error);
sql_success_or_die($DB_CORE->query($SQL->create_db_core));
sql_success_or_die($DB_CORE->select_db(MYSQL_DB_CORE));
//sql_success_or_die('SET CHARACTER SET utf8');
sql_success_or_die($DB_CORE->query($SQL->create_table_classes));
sql_success_or_die($DB_CORE->query($SQL->create_table_hosts));
sql_success_or_die($DB_CORE->query($SQL->create_table_members));
sql_success_or_die($DB_CORE->query($SQL->create_table_ip_blacklist));
sql_success_or_die($DB_CORE->query($SQL->create_table_activity_log));
sql_success_or_die($DB_CORE->query($SQL->create_table_questions));
sql_success_or_die($DB_CORE->query($SQL->create_table_answers));
sql_success_or_die($DB_CORE->query($SQL->create_table_admins));

sql_success_or_die($DB_CORE->query($SQL->create_table_images)); //NEW

check_lost_session();

set_timezone();

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]))
{
    $status_code = HTTP_STATUS_403;

    include_once "$CFG->error_dir$status_code.".PHP;

    die();
}

 ?>
