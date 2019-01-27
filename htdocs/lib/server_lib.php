<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/server_lib.php - Kognition Server library
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
require_once import($CFG->lib_db);

die_if_directly_requested(__FILE__);

function generate_hash($table_name=null, $len=HASH_LEN)
{
    do
    {
        $hash = bin2hex(random_bytes($len/2));
    } while (hash_exists($hash, $table_name));

    return $hash;
}

function generate_pin($len=CLASS_PIN_LEN)
{
    do
    {
        //$pin = random_int(0, pow(10, $len) - 1);
        $pin = random_int(pow(10, $len - 1), pow(10, $len) - 1); //TEST ME
    } while (pin_exists($pin));

    return $pin;
}

function upload_file($file, $table=null)
{
    global $CFG;
    global $TABLE_IDENTIFIER_MAP;

    $file_hash = generate_hash($table);//TABLE_IMAGES);
    $target_file = $CFG->dir_uploads . $file_hash;

    move_uploaded_file($file[TMP_NAME], $target_file);

    if (!is_null($table))
    {
        $table_identifier = $TABLE_IDENTIFIER_MAP[$table];

        $_SESSION[$table_identifier]->add
        (
            array
            (
                FILE_HASH => $file_hash,
                FILE_NAME => $file[NAME]
            )
        );

        return $file_hash;
    }
}

function upload_image($file, $member_hash, $question_hash, $table=TABLE_IMAGES)
{
    global $CFG;
    global $TABLE_IDENTIFIER_MAP;

    $file_hash = generate_hash($table);//TABLE_IMAGES);
    $target_file = $CFG->dir_uploads . $file_hash;

    move_uploaded_file($file[TMP_NAME], $target_file);

    if (!is_null($table))
    {
        $table_identifier = $TABLE_IDENTIFIER_MAP[$table];

        $_SESSION[$table_identifier]->add
        (
            array
            (
                FILE_HASH => $file_hash,
                FILE_NAME => $file[NAME],
                MEMBER_HASH => $member_hash,
                QUESTION_HASH => $question_hash,
                TIMESTAMP_UPLOADED => timestamp(),
            )
        );

        return $file_hash;
    }
}

function get_obj_from_ip($ip)
{
    global $DB_CORE;

    $host_hash = $_SESSION[HOSTS]->find(IP, $ip)[0][HOST_HASH] ?? null;
    $member_hash = $_SESSION[MEMBERS]->find(IP, $ip)[0][MEMBER_HASH] ?? null;

    if (!is_null($host_hash))
    {
        return get_host_from_hash($host_hash);
    }
    if (!is_null($member_hash))
    {
        return get_member_from_hash($member_hash);
    }
}

function host_hash_exists($host_hash)
{
    return $_SESSION[HOSTS]->has($host_hash);
}

function get_host_from_hash($host_hash, $class=null)
{
    $table_vals = $_SESSION[HOSTS]->get
    (
        $host_hash,
        HOST_NAME,
        HOST_HASH,
        CLASS_PIN,
        TIMESTAMP_HOSTED,
        IP,
        USER_AGENT,
        TIMESTAMP_LAST_ACCESSED
    )[0];

    $host_name = $table_vals[HOST_NAME];
    $host_hash = $table_vals[HOST_HASH];
    $class_pin = (int)$table_vals[CLASS_PIN];
    $timestamp_hosted = $table_vals[TIMESTAMP_HOSTED];
    $ip = $table_vals[IP];
    $user_agent = $table_vals[USER_AGENT];
    $timestamp_last_accessed = $table_vals[TIMESTAMP_LAST_ACCESSED];

    $host = new HostObj
    (
        $host_name,
        $host_hash,
        null,
        $timestamp_hosted,
        true,
        $ip,
        $user_agent,
        $timestamp_last_accessed
    );

    if (is_null($class))
    {
        $class = get_class_from_pin($class_pin, $host);
    }

    $host->class = $class;

    return $host;
}

function get_host_from_pin($class_pin, $class=null)
{
    $host_hash = $_SESSION[HOSTS]->find(CLASS_PIN, $class_pin)[0][HOST_HASH];

    return get_host_from_hash($host_hash, $class);
}

function register_host($host)
{
    if (!is_admin())
    {
        $_SESSION[HOST_HASH] = $host->host_hash;
        $_SESSION[WHOAMI] = $host;

        $_SESSION[IP_ADDRESS] = REQUEST_IP;
    }

    $vals = array
    (
        HOST_NAME => $host->host_name,
        HOST_HASH => $host->host_hash,
        CLASS_PIN => $host->class->class_pin,
        TIMESTAMP_HOSTED => $host->timestamp_hosted,
        IP => $host->_ip,
        USER_AGENT => $host->_user_agent,
        TIMESTAMP_LAST_ACCESSED => $host->timestamp_last_accessed
    );

    $_SESSION[HOSTS]->add($vals);

    return true; //check correct
}

function deregister_host($host)
{
    if ($_SESSION[WHOAMI] && ($_SESSION[WHOAMI]->get_primary_key() == $host->get_primary_key()))
    {
        kill_session();
    }

    $_SESSION[HOSTS]->remove($host->host_hash);

    return true; //check correct
}

function update_host($host)
{
    $_SESSION[HOSTS]->update
    (
        $host->host_hash,
        array
        (
            HOST_NAME => $host->host_name,
            TIMESTAMP_LAST_ACCESSED => $host->timestamp_last_accessed
        )
    );

    return true;
}

function get_hosts_questions($host)
{
    $raw_questions = $_SESSION[QUESTIONS]->get_all(NO_LIMIT, array(HOST_HASH=>$host->host_hash));
    $questions = array();

    foreach ($raw_questions as $raw_question)
    {
        $question_hash = $raw_question[QUESTION_HASH];
        $question_name = $raw_question[QUESTION_NAME];
        $question_text = $raw_question[QUESTION_TEXT];
        $answer_mode = $raw_question[ANSWER_MODE];
        $closed = $raw_question[CLOSED];
        $host_hash = $raw_question[HOST_HASH];
        $timestamp = $raw_question[TIMESTAMP];

        $question = new QuestionObj
        (
            $question_hash,
            $question_name,
            $question_text,
            $answer_mode,
            $closed,
            null,
            $timestamp,
            true
        );

        $host = get_host_from_hash($host_hash);

        $question->host = $host;

        array_push($questions, $question);
    }

    return $questions;
}

function host_has_question($host, $question)
{
    return (bool)$_SESSION[QUESTIONS]->find(HOST_HASH, $host->host_hash);
}

/*
function get_all_hosts()
{

}
*/

/*
function host_owns_question($question, $host)
{
    return class_has_question();
}
*/

function class_pin_exists($class_pin)
{
    return $_SESSION[CLASSES]->has($class_pin);
}

function get_class_from_pin($class_pin, $host=null)
{
    $table_vals = $_SESSION[CLASSES]->get
    (
        $class_pin,
        CLASS_PIN,
        CLASS_NAME,
        CLOSED,
        TIMESTAMP_CREATED,
        CLASS_PUBLIC
    )[0];

    $class_pin = (int)$table_vals[CLASS_PIN];
    $class_name = $table_vals[CLASS_NAME];
    $closed = (boolean)$table_vals[CLOSED];
    $timestamp_created = $table_vals[TIMESTAMP_CREATED];
    $class_public = (boolean)$table_vals[CLASS_PUBLIC];

    $class = new ClassObj
    (
        $class_pin,
        $class_name,
        $closed,
        $timestamp_created,
        null,
        true,
        $class_public
    );

    if (is_null($host))
    {
        $host = get_host_from_pin($class_pin, $class);
    }

    $class->host = $host;

    return $class;
}

function register_class($class)
{
    if (!is_admin())
    {
        $_SESSION[CLASS_PIN] = $class->class_pin;

        $_SESSION[IP_ADDRESS] = REQUEST_IP;
    }

    $vals = array
    (
        CLASS_PIN=> $class->class_pin,
        CLASS_NAME => $class->class_name,
        CLOSED => $class->closed,
        TIMESTAMP_CREATED => $class->timestamp_created,
        CLASS_PUBLIC => $class->class_public
    );

    $_SESSION[CLASSES]->add($vals);

    return true; //check correct
}

function deregister_class($class)
{

    if ($_SESSION[WHOAMI] && ($_SESSION[WHOAMI]->class->class_pin == $class->class_pin))
    {
        kill_session();
    }

    $_SESSION[CLASSES]->remove($class->class_pin);

    return true; //check correct
}

function discover_classes($total, $public_essential=true)
{
    $raw_classes = $_SESSION[CLASSES]->get_all($total);
    $classes = array();

    foreach ($raw_classes as $raw_class)
    {
        $class_pin = (int)$raw_class[CLASS_PIN];
        $class_name = $raw_class[CLASS_NAME];
        $closed = (boolean)$raw_class[CLOSED];
        $timestamp_created = $raw_class[TIMESTAMP_CREATED];
        $class_public = (boolean)$raw_class[CLASS_PUBLIC];

        $class = new ClassObj
        (
            $class_pin,
            $class_name,
            $closed,
            $timestamp_created,
            null,
            true,
            $class_public
        );

        $host = get_host_from_pin($class_pin, $class);

        $class->host = $host;

        if (($public_essential && $class->class_public) || !$public_essential)
        //if ($public_essential && $class->class_public)
        {
            array_push($classes, $class);
        }
    }

    return $classes;
}

function get_classes_questions($class)
{
    $host = get_host_from_pin($class->class_pin);

    return get_hosts_questions($host); //This is bad practice
}

function get_all_members_of_class($class)
{
    $raw_members = $_SESSION[MEMBERS]->get_all(NO_LIMIT, array(CLASS_PIN=>$class->class_pin));
    $members = array();

    foreach ($raw_members as $raw_member)
    {
        $member_name= $raw_member[MEMBER_NAME];
        $member_hash = $raw_member[MEMBER_HASH];
        $class_pin = (int)$raw_member[CLASS_PIN];
        $timestamp_joined = $raw_member[TIMESTAMP_JOINED];
        $ip = $raw_member[IP];
        $user_agent = $raw_member[USER_AGENT];
        $rag_status = $raw_member[RAG_STATUS];
        $timestamp_last_accessed = $raw_member[TIMESTAMP_LAST_ACCESSED];

        $member = new MemberObj
        (
            $member_hash,
            $member_name,
            null,
            $timestamp_joined,
            true,
            $rag_status,
            $ip,
            $user_agent,
            $timestamp_last_accessed
        );

        $class = get_class_from_pin($class_pin);

        $member->class = $class;

        array_push($members, $member);
    }

    return $members;
}

function update_class($class)
{
    $_SESSION[CLASSES]->update
    (
        $class->class_pin,
        array
        (
            CLASS_NAME => $class->class_name,
            CLOSED => $class->closed,
            CLASS_PUBLIC => $class->class_public
        )
    );

    return true;
}

function member_hash_exists($member_hash)
{
    return $_SESSION[MEMBERS]->has($member_hash);
}

function get_member_from_hash($member_hash, $class=null)
{
    $table_vals = $_SESSION[MEMBERS]->get
    (
        $member_hash,
        MEMBER_NAME,
        MEMBER_HASH,
        CLASS_PIN,
        TIMESTAMP_JOINED,
        IP,
        USER_AGENT,
        RAG_STATUS,
        TIMESTAMP_LAST_ACCESSED
    )[0];

    $member_name= $table_vals[MEMBER_NAME];
    $member_hash = $table_vals[MEMBER_HASH];
    $class_pin = (int)$table_vals[CLASS_PIN];
    $timestamp_joined = $table_vals[TIMESTAMP_JOINED];
    $ip = $table_vals[IP];
    $user_agent = $table_vals[USER_AGENT];
    $rag_status = $table_vals[RAG_STATUS];
    $timestamp_last_accessed = $table_vals[TIMESTAMP_LAST_ACCESSED];

    $member = new MemberObj
    (
        $member_hash,
        $member_name,
        null,
        $timestamp_joined,
        true,
        $rag_status,
        $ip,
        $user_agent,
        $timestamp_last_accessed
    );

    if (is_null($class))
    {
        $class = get_class_from_pin($class_pin);
    }

    $member->class = $class;

    return $member;
}

function register_member($member)
{
    if (!is_admin())
    {
        $_SESSION[MEMBER_HASH] = $member->member_hash;
        $_SESSION[WHOAMI] = $member;

        $_SESSION[IP_ADDRESS] = REQUEST_IP;
    }

    $vals = array
    (
        MEMBER_NAME => $member->member_name,
        MEMBER_HASH => $member->member_hash,
        CLASS_PIN => $member->class->class_pin,
        TIMESTAMP_JOINED => $member->timestamp_joined,
        IP => $member->_ip,
        USER_AGENT => $member->_user_agent,
        RAG_STATUS => $member->rag_status,
        TIMESTAMP_LAST_ACCESSED => $member->timestamp_last_accessed
    );

    $_SESSION[MEMBERS]->add($vals);

    return true; //check correct
}

function deregister_member($member)
{
    if ($_SESSION[WHOAMI] && ($_SESSION[WHOAMI]->get_primary_key() == $member->get_primary_key()))
    {
        kill_session();
    }

    $_SESSION[MEMBERS]->remove($member->member_hash);

    return true; //check correct
}

function update_member($member)
{
    $_SESSION[MEMBERS]->update
    (
        $member->member_hash,
        array
        (
            MEMBER_NAME => $member->member_name,
            RAG_STATUS => $member->rag_status,
            TIMESTAMP_LAST_ACCESSED => $member->timestamp_last_accessed
        )
    );

    return true; //check correct
}

function member_already_answered($question, $member)
{
    return (bool)$_SESSION[ANSWERS]->get_all(NO_LIMIT, array(QUESTION_HASH=>$question->question_hash,MEMBER_HASH=>$member->member_hash));
}

function member_in_class($class, $member)
{
    //do a db check here?

    return $class->class_pin == $member->class->class_pin;
}

function handle_lost_session()
{
    if ($_SESSION[SESSION_LOST])
    {
        $obj = get_obj_from_ip(REQUEST_IP);
        $class = $obj->class ?? null;

        if (is_null($obj)) //Is handle_lost_session working? //or class null?
        {
            return; //return what?
        }

        $_SESSION[WHOAMI] = $_SESSION[WHOAMI] ?? $obj; //added to ensure killed once de-registered.

        $obj->deregister();

        if ($obj instanceof HostObj) //I'm not imported here.
        {
            $class->deregister();
        }

        $_SESSION[SESSION_LOST] = false;
    }

    return true; //check correct
}

function register_question($question)
{
    global $ANSWER_MODES;

    $vals = array
    (
        QUESTION_HASH => $question->question_hash,
        QUESTION_NAME => $question->question_name,
        QUESTION_TEXT => $question->question_text,
        ANSWER_MODE => $question->answer_mode,
        CLOSED => $question->closed,
        HOST_HASH => $question->host->host_hash,
        TIMESTAMP => $question->timestamp
    );

    $_SESSION[QUESTIONS]->add($vals);

    return true; //check correct
}

function deregister_question($question)
{
    $_SESSION[QUESTIONS]->remove($question->question_hash);

    return true; //check correct
}

function question_hash_exists($question_hash)
{
    return $_SESSION[QUESTIONS]->has($question_hash);
}

function get_question_from_hash($question_hash, $host=null)
{
    $table_vals = $_SESSION[QUESTIONS]->get
    (
        $question_hash,
        QUESTION_HASH,
        QUESTION_NAME,
        QUESTION_TEXT,
        ANSWER_MODE,
        CLOSED,
        HOST_HASH,
        TIMESTAMP
    )[0];

    $question_hash = $table_vals[QUESTION_HASH];
    $question_name = $table_vals[QUESTION_NAME];
    $question_text = $table_vals[QUESTION_TEXT];
    $answer_mode = $table_vals[ANSWER_MODE];
    $closed = (boolean)$table_vals[CLOSED];
    $host_hash = $table_vals[HOST_HASH];
    $timestamp = $table_vals[TIMESTAMP];

    $question = new QuestionObj
    (
        $question_hash,
        $question_name,
        $question_text,
        $answer_mode,
        $closed,
        null,
        $timestamp,
        true
    );

    if (is_null($host))
    {
        $host = get_host_from_hash($host_hash);
    }

    $question->host = $host;

    return $question;
}

function update_question($question)
{
    $_SESSION[QUESTIONS]->update
    (
        $question->question_hash,
        array
        (
            QUESTION_NAME => $question->question_name,
            QUESTION_TEXT => $question->question_text,
            CLOSED => $question->closed
        )
    );

    return true; //check correct
}

function get_questions_answers($question)
{
    $raw_answers = $_SESSION[ANSWERS]->get_all(NO_LIMIT, array(QUESTION_HASH=>$question->question_hash));
    $answers = array();

    foreach ($raw_answers as $raw_answer)
    {
        $answer_hash = $raw_answer[ANSWER_HASH];
        $member_hash = $raw_answer[MEMBER_HASH];
        $answer_tinymce = $raw_answer[ANSWER_TINYMCE];
        $answer_image = $raw_answer[ANSWER_IMAGE];
        $question_hash = $raw_answer[QUESTION_HASH];
        $timestamp = $raw_answer[TIMESTAMP];

        $answer = new AnswerObj
        (
            $answer_hash,
            null,
            $answer_tinymce,
            $answer_image,
            null,
            $timestamp
        );

        $member = get_member_from_hash($member_hash);
        $question = get_question_from_hash($question_hash);

        $answer->member = $member;
        $answer->question = $question;

        array_push($answers, $answer);
    }

    return $answers;
}

function register_answer($answer)
{
    $vals = array
    (
        ANSWER_HASH => $answer->answer_hash,
        MEMBER_HASH => $answer->member->member_hash,
        ANSWER_TINYMCE => $answer->answer_tinymce,
        ANSWER_IMAGE => $answer->answer_image,
        QUESTION_HASH => $answer->question->question_hash,
        TIMESTAMP=> $answer->timestamp
    );

    $_SESSION[ANSWERS]->add($vals);

    return true; //check correct
}

function deregister_answer($answer)
{
    $_SESSION[ANSWERS]->remove($answer->answer_hash);

    return true; //check correct
}

function answer_hash_exists($answer_hash)
{
    return $_SESSION[ANSWERS]->has($answer_hash);
}

function get_answer_from_hash($answer_hash, $member=null, $question=null)
{
    $table_vals = $_SESSION[ANSWERS]->get
    (
        $answer_hash,
        ANSWER_HASH,
        MEMBER_HASH,
        ANSWER_TINYMCE,
        ANSWER_IMAGE,
        QUESTION_HASH,
        TIMESTAMP
    )[0];

    $answer_hash = $table_vals[ANSWER_HASH];
    $member_hash = $table_vals[MEMBER_HASH];
    $answer_tinymce = $table_vals[ANSWER_TINYMCE];
    $answer_image = $table_vals[ANSWER_IMAGE];
    $question_hash = $table_vals[QUESTION_HASH];
    $timestamp = $table_vals[TIMESTAMP];

    $answer = new AnswerObj
    (
        $answer_hash,
        null,
        $answer_tinymce,
        $answer_image,
        null,
        $timestamp,
        true
    );

    $member = get_member_from_hash($member_hash);
    $question = get_question_from_hash($question_hash);

    $answer->member = $member;
    $answer->question = $question;

    return $answer;
}

function update_answer($answer)
{
    $_SESSION[ANSWERS]->update
    (
        $answer->answer_hash,
        array
        (
            ANSWER_TINYMCE => $answer->answer_tinymce,
            ANSWER_IMAGE => $answer->answer_image,
        )
    );

    return true; //check correct
}

function get_members_answer($member, $question)
{
    if (! member_already_answered($question, $member))
    {
        return;
    }

    $table_vals = $_SESSION[ANSWERS]->get_all(NO_LIMIT, array(QUESTION_HASH=>$question->question_hash,MEMBER_HASH=>$member->member_hash))[0];

    $answer_hash = $table_vals[ANSWER_HASH];
    $member_hash = $table_vals[MEMBER_HASH];
    $answer_tinymce = $table_vals[ANSWER_TINYMCE];
    $answer_image = $table_vals[ANSWER_IMAGE];
    $question_hash = $table_vals[QUESTION_HASH];
    $timestamp = $table_vals[TIMESTAMP];

    $answer = new AnswerObj
    (
        $answer_hash,
        null,
        $answer_tinymce,
        $answer_image,
        null,
        $timestamp,
        true
    );

    $answer->member = $member;
    $answer->question = $question;

    return $answer;
}

/*
function admin_hash_exists($admin_hash)
{
    return $_SESSION[ADMINS]->has($admin_hash);
}
*/

function admin_username_exists($username)
{
    return $_SESSION[ADMINS]->has($username);
}

//function admin_has_password_hash($username, $password_hash)
function admin_has_password($username, $password)
{
    //return (bool)$_SESSION[ADMINS]->find(ADMIN_USERNAME, $username);

    $admin = get_admin_from_username($username);

    //return bcrypt_verify($)
    //return $admin->admin_password_hash == $password_hash;
    //echo $password;
    //echo $admin->admin_password_hash;
    return bcrypt_verify($password, $admin->admin_password_hash);
}

function get_admin_from_username($username)
{
    $table_vals = $_SESSION[ADMINS]->get
    (
        $username,
        ADMIN_USERNAME,
        ADMIN_PASSWORD_HASH,
        ADMIN_NAME
    )[0];

    $admin_username = $table_vals[ADMIN_USERNAME];
    $admin_password_hash = $table_vals[ADMIN_PASSWORD_HASH];
    $admin_name = $table_vals[ADMIN_NAME];

    $admin = new AdminObj
    (
        $admin_username,
        $admin_password_hash,
        $admin_name,
        true
    );

    return $admin;
}

function login_admin($admin)
{
    //$_SESSION[MEMBER_HASH] = $member->member_hash;
    $_SESSION[WHOAMI] = $admin;

    $_SESSION[IP_ADDRESS] = REQUEST_IP;

    $_SESSION[IS_ADMIN] = true;

    return true; //check correct
}

 ?>
