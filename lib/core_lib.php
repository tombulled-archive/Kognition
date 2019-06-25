<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/core_lib.php - Kognition Core library
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once import($CFG->lib_setup);

die_if_directly_requested(__FILE__);

function optional_param($param_name, $param_type, $method, $allow_cookie=true)
{
    $cookie = $allow_cookie ? $_COOKIE[$param_name] ?? null : null;

    if (! isset($method[$param_name]) && is_null($cookie))
    {
        return null;
    }

    //$val = is_null($cookie) ? $method[$param_name] : $cookie;
    $val = $method[$param_name] ?? $cookie;

    if (! valid_type($val, $param_type))
    {
        return null;
    }

    return convert_type($val, $param_type);
}

function required_param($param_name, $param_type, $method, $allow_cookie=true)
{
    $cookie = $allow_cookie ? $_COOKIE[$param_name] ?? null : null;

    if (! isset($method[$param_name]) && is_null($cookie))
    {
        die_missing_param($param_name);
    }

    //$val = is_null($cookie) ? $method[$param_name] : $cookie;
    $val = $method[$param_name] ?? $cookie;

    if (! valid_type($val, $param_type))
    {
        die_invalid_param($param_name);
    }

    $conv_val = convert_type($val, $param_type);

    if (is_null($conv_val))
    {
        die_invalid_param($param_name);
    }

    return $conv_val;
}

function required_file($file_name, $file_type, $max_size)//, $allow_empty=false)
{
    //global $ALLOWED_FILE_EXTENSIONS;
    global $ALLOWED_FILE_SIZES;

    if (!isset($_FILES[$file_name]))
    {
        die_missing_file($file_name); //make me
    }

    $file = $_FILES[$file_name];
    //$file_extension = get_upload_extension($file);//strtolower(pathinfo($file[NAME], PATHINFO_EXTENSION));
    //$file_size = getimagesize($_FILES["file_upload"]["tmp_name"]
    //$is_image = is_upload_image($file);

    //echo $file_type;
    //die();

    //if (!in_array($file_extension, $ALLOWED_FILE_EXTENSIONS[$file_type]))
    if (!valid_file_type($file, $file_type)) //if extension not allowed
    {
        die_invalid_file($file_name);
    }

    /*if (!$allow_empty && is_upload_empty($file))
    {

    }*/

    if ($file[SIZE] > $max_size) //$ALLOWED_FILE_SIZES[$file_type])
    {
        die_file_too_large();//$file_name); //too large
    }

    return $file;
}

function optional_file($file_name, $file_type, $max_size)//, $allow_empty=false)
{
    //global $ALLOWED_FILE_EXTENSIONS;
    global $ALLOWED_FILE_SIZES;

    if (!isset($_FILES[$file_name]))
    {
        //die_missing_file($file_name); //make me
        return null;
    }

    $file = $_FILES[$file_name];
    //$file_extension = get_upload_extension($file);//strtolower(pathinfo($file[NAME], PATHINFO_EXTENSION));
    //$file_size = getimagesize($_FILES["file_upload"]["tmp_name"]
    //$is_image = is_upload_image($file);

    //echo $file_type;
    //die();

    //if (!in_array($file_extension, $ALLOWED_FILE_EXTENSIONS[$file_type]))
    if (!valid_file_type($file, $file_type)) //if extension not allowed
    {
        //die_invalid_file($file_name);
        return null;
    }

    /*if (!$allow_empty && is_upload_empty($file))
    {

    }*/

    if ($file[SIZE] > $max_size) //$ALLOWED_FILE_SIZES[$file_type])
    {
        //die_file_too_large();//$file_name); //too large
        return null;
    }

    return $file;
}

function valid_type($str_var, $type)
{
    switch($type)
    {
        case PARAM_INT:
            return ctype_digit($str_var);
        case PARAM_STR:
            return true;
        case PARAM_BOOL:
            return in_array(strtolower($str_var), array('true', 'false', '1', '0'));
        //...
    }
}

function convert_type($str_var, $type)
{
    switch($type)
    {
        case PARAM_INT:
            return intval($str_var);
        case PARAM_STR:
            return $str_var;
        case PARAM_BOOL:
            return array('true'=>true,'false'=>false,'1'=>true,'0'=>false)[strtolower($str_var)];
        //...
    }
}

function valid_file_type($file, $type)
{
    global $ALLOWED_FILE_EXTENSIONS;

    switch ($type)
    {
        case FILE_IMAGE:
            return (!isset($ALLOWED_FILE_EXTENSIONS[$type]) || in_array(get_upload_extension($file), $ALLOWED_FILE_EXTENSIONS[$type])) && is_upload_image($file);
    }
}

/*
function upload_file($file)
{
    global $CFG;

    $file_hash = generate_hash();//TABLE_IMAGES);
    $target_file = $CFG->dir_uploads . $file_hash;

    move_uploaded_file($file[TMP_NAME], $target_file);
}
*/

function die_missing_param($param_name)
{
    request_failed("Required param missing: '$param_name'"); //No injection here
}

function die_invalid_param($param_name)
{
    //request_failed("Invalid param value: '$param_name'");
    request_failed("Invalid param value");
}

function die_invalid_file($file_name)
{
    //request_failed("Invalid file: '$file_name'");
    request_failed("Invalid file");
}

function die_missing_file($file_name)
{
    request_failed("Required file missing: '$file_name'");
}

function die_file_too_large()//$file_name)
{
    request_failed('File is too large');
}

function get_upload_extension($file)
{
    return strtolower(pathinfo($file[NAME], PATHINFO_EXTENSION));
}

function is_upload_image($file)
{
    return getimagesize($file[TMP_NAME]) !== false;
}

function log_request()
{
    global $CFG;

    if (!$CFG->log_requests)
    {
        return;
    }

    $_SESSION[ACTIVITY_LOG]->add
    (
        array
        (
            ID => null,
            IP => REQUEST_IP,
            URI => REQUEST_URL,
            USER_AGENT => REQUEST_USER_AGENT,
            TIMESTAMP => timestamp()
        )
    );
}

function die_if_maintainance()
{
    global $CFG;

    if ($CFG->under_maintenance)
    {
        redirect_error(HTTP_STATUS_503, SERVICE_UNAVAILABLE);
    }
}

function redirect($url, $status_code = HTTP_STATUS_303, $die=true)
{
    header('Location: ' . $url, true, $status_code);

    if ($die)
    {
        die();
    }
}

function redirect_error($status_code = HTTP_STATUS_403)
{
    global $CFG;

    $url = "$CFG->url_error_dir$status_code.".PHP;

    redirect($url, HTTP_STATUS_303);
}

function int_length($int_val)
{
    return strlen((string)$int_val);
}

function timestamp()
{
    return date('Y-m-d H:i:s', time());
}

function request_success_array()
{
    return array
    (

        'success'=>true,
        'message'=>OK,
        'timestamp_response'=>timestamp()
    );
}

function request_failed_array($message='')
{
    return array
    (

        'success'=>false,
        'message'=>$message,
        'timestamp_response'=>timestamp()
    );
}

function die_if_blocked()
{
    global $CFG;

    $ip = $_SESSION[BLACKLIST]->find(IP, REQUEST_IP)[0][IP] ?? null;

    if (!is_null($ip))
    {
        status(HTTP_STATUS_403, FORBIDDEN);
    }
}

function cookie_exists($cookie_name)
{
    return isset($_COOKIE[$cookie_name]);
}

function die_if_assigned_role($admin_count=false)
{
    /*echo 'not is_null whoami: ' . (int)!is_null($_SESSION[WHOAMI]);
    br();
    echo 'admin_count: ' . (int)$admin_count;
    br();
    echo 'is_admin: ' . (int)is_admin();
    br();
    echo 'not admin_count: ' . (int)!$admin_count;
    br();*/
    //echo (int)($admin_count && $_SESSION[WHOAMI] instanceof AdminObj);

    //echo (int)(1 && ((0 && 1) || 1));
    //if (!is_null($_SESSION[WHOAMI]) && (($admin_count && is_admin()) || !$admin_count))
    if (!is_null($_SESSION[WHOAMI]))
    {
        if ($admin_count && is_admin())
        {
            request_failed("You've already been assigned a role");
        }
    }
}

function die_if_changed_ip()
{
    if ($_SESSION[IP_ADDRESS] != REQUEST_IP && !is_null($_SESSION[WHOAMI]))
    {
        request_failed("Your IP has changed");
    }
}

function br($times=1)
{
    for ($index = 0; $index < $times; $index ++)
    {
        echo '<br>';
    }
}

function html_spaces($total)
{
    for ($index = 0; $index < $total; $index ++)
    {
        echo '&nbsp;';
    }
}

function html_tab()
{
    html_spaces(4);
}

function implode_assosciative_array($sep, $key_val_sep, $assoc_array)
{
    $key_vals = array();

    foreach ($assoc_array as $key => $val)
    {
        $key_vals[] = "$key$key_val_sep$val";
    }

    return implode($sep, $key_vals);
}

function die_if_directly_requested($file)
{
    global $CFG;

    if (basename($file) == basename($_SERVER["SCRIPT_FILENAME"]))
    {
        log_request();
        die_if_blocked();
        die_if_maintainance();

        die_error(HTTP_STATUS_403);
    }
}

function status($status_code, $message=OK)
{
    header("HTTP/1.1 $status_code $message");
}

function die_error($status_code)
{
    global $CFG;

    include_once "$CFG->error_dir$status_code.".PHP;

    die();
}

function request_success($api_out=array())
{
    echo json_encode(array_merge($api_out, request_success_array()));
    //echo htmlspecialchars(json_encode(array_merge($api_out, request_success_array())), ENT_QUOTES, 'UTF-8');

    die();
}

function request_failed($message='')
{
    echo json_encode(request_failed_array($message));

    die();
}

function all(...$args)
{
    //return !in_array(false, $args, true);

    foreach ($args as $arg)
    {
        if (!$arg)
        {
            return false;
        }
    }

    return true;
}

function any(...$args)
{
    //return in_array(true, $args, true);

    foreach ($args as $arg)
    {
        if ($arg)
        {
            return true;
        }
    }

    return false;
}

function die_if_none($message, ...$args)
{
    if (!any(...$args))
    {
        request_failed($message);
    }
}

function flatten_assosciative_array($assoc_array)
{
    $array = array();

    foreach ($assoc_array as $key=>$val)
    {
        array_push($array, $key, $val);
    }

    return $array;
}

function create_initialised_array($init_val, $len)
{
    return array_fill(0, $len, $init_val);
}

function die_if_not_accepted_cookies()
{
    if (!$_SESSION[ACCEPTED_COOKIES])
    {
        request_failed("You must first accept this sites cookies");
    }
    //die_error
}

function debug_sql($sql)
{
    global $CFG;

    if ($CFG->debug_sql)
    {
        echo 'SQLDebug: ';
        echo $sql;
        br();
    }
}

function bcrypt_hash($data)
{
    return password_hash($data, PASSWORD_BCRYPT);
}

function bcrypt_verify($data, $hash)
{
    return password_verify($data, $hash);
}

 ?>
