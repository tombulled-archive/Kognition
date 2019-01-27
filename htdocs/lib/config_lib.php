<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/config_lib.php - Kognition Configuration library
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

function import($lib_name)
{
    global $CFG;

    return $CFG->lib_dir . $lib_name.DOT.PHP;
}

function import_plugin($plugin_name)
{
    global $CFG;

    return $CFG->lib_plugin_dir . $plugin_name.DOT.PHP;
}

function define_self($key)
{
    define($key, $key);
}

function define_lower($key)
{
    define($key, strtolower($key));
}

global $CFG;

$CFG = new stdClass();
$CFG->name = 'Kognition';
$CFG->company = 'Pavilion Project B';
$CFG->lang = 'en';
$CFG->timezone = 'Europe/London';
$CFG->htdocs_dir = $_SERVER['DOCUMENT_ROOT'] .'/';
$CFG->lib_dir = $CFG->htdocs_dir . 'lib/';
$CFG->lib_plugin_dir = $CFG->lib_dir . 'plugins/';
$CFG->api_dir = $CFG->htdocs_dir . 'api/';
$CFG->error_dir = $CFG->htdocs_dir . 'error/';
$CFG->dir_uploads = $CFG->htdocs_dir . 'uploads/';
$CFG->under_maintenance = false; //NOTE: Check me on server push
$CFG->local = true; //NOTE: Check me on server push
$CFG->log_requests = true;
$CFG->debug_sql = false; //NOTE: Check me on server push
$CFG->site_version = 1.0;
$CFG->api_version = 1.0;
$CFG->db_full = false;
$CFG->protocol = 'http';
$CFG->url_root = "$CFG->protocol://$_SERVER[HTTP_HOST]/";
$CFG->url_error_dir = $CFG->url_root . 'error/';
$CFG->url_assets_dir = $CFG->url_root . 'assets/';
$CFG->url_plugins_dir = $CFG->url_root . 'plugins/';
$CFG->url_api_dir = $CFG->url_root . 'api/';
$CFG->url_admin_dir = $CFG->url_root . 'admin/';
$CFG->lib_answer = 'answer_lib';
$CFG->lib_class = 'class_lib';
$CFG->lib_config = 'config_lib';
$CFG->lib_core = 'core_lib';
$CFG->lib_db = 'db_lib';
$CFG->lib_host = 'host_lib';
$CFG->lib_member = 'member_lib';
$CFG->lib_question = 'question_lib';
$CFG->lib_server = 'server_lib';
$CFG->lib_setup = 'setup_lib';
$CFG->lib_cron = 'cron_lib';
$CFG->lib_admin = 'admin_lib';
$CFG->lib_plugin_zendescaper = 'zend-escaper/src/Escaper';
$CFG->exclude_php_extension = true;
$CFG->allow_directory_listings = false; //UTILISE ME

define_self('AND');

define('PARAM_INT', gettype(1));
define('PARAM_STR', gettype(''));
define('PARAM_BOOL', gettype(true));

define('METHOD_POST', $_POST);
define('METHOD_GET', $_GET);

define('REQUEST_URL', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
define('REQUEST_IP', $_SERVER['REMOTE_ADDR']);
define('REQUEST_USER_AGENT', $_SERVER['HTTP_USER_AGENT'] ?? null);

define('HTTP_STATUS_303', 303);
define('HTTP_STATUS_400', 400);
define('HTTP_STATUS_401', 401);
define('HTTP_STATUS_403', 403);
define('HTTP_STATUS_404', 404);
define('HTTP_STATUS_500', 500);
define('HTTP_STATUS_503', 503);
define('HTTP_STATUS_504', 504);

define('BAD_REQUEST', 'BAD REQUEST');
define('SEE_OTHER', 'SEE OTHER');
define('UNAUTHORIZED', 'UNAUTHORIZED');
define('FORBIDDEN', 'FORBIDDEN');
define('NOT_FOUND', 'NOT FOUND');
define('INTERNAL_SERVER_ERROR', 'INTERNAL SERVER ERROR');
define('SERVICE_UNAVAILABLE', 'SERVICE UNAVAILABLE');
define('GATEWAY_TIMEOUT', 'GATEWAY TIMEOUT');

define('CLASS_PIN_LEN', 6); //Must be <= 10?
define('HASH_LEN', 50); // Must be multiple of 2
define('MAX_NAME_LEN', 30);
define('MAX_IP_LEN', 30);
define('MAX_USER_AGENT_LEN', 255);
define('ID_LEN', 6);
define('MAX_URI_LEN', 255);

define('MAX_ANSWER_MODE_LEN', 255);
define('MAX_ANSWER_TINYMCE_LEN', 2000);
define('MAX_QUESTION_TEXT_LEN', 255);
define('MAX_QUESTION_NAME_LEN', 255);

define('MAX_CLASS_SIZE', 30); //check if free or premium
define('MAX_HOST_QUESTIONS', 5); //is this suitable?

define('ANSWER_MODE_TINYMCE', 'tinymce');
define('ANSWER_MODE_IMAGE', 'image');

global $ANSWER_MODES;

$ANSWER_MODES = array
(
    ANSWER_MODE_TINYMCE,
    ANSWER_MODE_IMAGE,
);

define_lower('ATTR_ARRAY');
define_self('OK');
define_lower('IP_ADDRESS');

define_lower('HOST_HASH');
define_lower('CLASS_PIN');
define_lower('HOST_NAME');
define_lower('CLASS_NAME');
define_lower('CLASS_CLOSED');

define_lower('QUESTION_CLOSED');

define_lower('MEMBER_NAME');
define_lower('MEMBER_HASH');
define_lower('MEMBER');
define_lower('WHOAMI');
define_lower('HOST');
define_lower('MEMBERS');
define_lower('HOSTS');
define_lower('CLASSES');

define('CLASS_', 'class');

define_lower('IPOBJMAP');
define_lower('QUESTION_HASH');

define_lower('QUESTIONS');
define_lower('QUESTION');
define_lower('ANSWERS');
define_lower('ANSWER');

define_lower('TOTAL');
define_lower('SHOW_MEMBERS');

define_lower('ANSWER_TINYMCE');
define_lower('ANSWER_IMAGE');
define_lower('QUESTION_TEXT');
define_lower('QUESTION_NAME');
define_lower('ANSWER_MODE');
define_lower('ANSWER_HASH');

define_lower('RAG_STATUS');
define('RAG_STATUS_RED', 'red');
define('RAG_STATUS_AMBER', 'amber');
define('RAG_STATUS_GREEN', 'green');

global $RAG_STATUSES;

$RAG_STATUSES = array
(
    RAG_STATUS_RED,
    RAG_STATUS_AMBER,
    RAG_STATUS_GREEN
);

define_lower('TIMESTAMP_HOSTED');
define_lower('EXISTS');
define_lower('IP');
define_lower('USER_AGENT');
define_lower('TIMESTAMP_JOINED');
define_lower('CLOSED');
define_lower('TIMESTAMP_CREATED');
define_lower('TIMESTAMP_UPLOADED');

define('FK_CLASSES', 'fk_classes_');
define('FK_HOSTS', 'fk_hosts_');
define('FK_MEMBERS', 'fk_members_');
define('FK_ANSWERS', 'fk_answers_');
define('FK_QUESTIONS', 'fk_questions_');

define_lower('BLACKLIST');
define_lower('ACTIVITY_LOG');
define_lower('ID');
define_lower('TIMESTAMP');
define_lower('URI');

define('SESSION_NAME', strtoupper($CFG->name).'_SESSION');

define('EXPIRE_NOW', 0);

define('PATH_ROOT', '/');

define_lower('TABLE_MEMBERS');
define_lower('TABLE_HOSTS');
define_lower('TABLE_CLASSES');
define_lower('TABLE_IP_BLACKLIST');
define_lower('TABLE_ACTIVITY_LOG');
define_lower('TABLE_ANSWERS');
define_lower('TABLE_QUESTIONS');

define('CLASS_DEFAULT_CLOSED', false);
define('CLASS_DEFAULT_EXISTS', false);
define('HOST_DEFAULT_EXISTS', false);
define('MEMBER_DEFAULT_EXISTS', false);

define('DEFAULT_QUESTION_EXISTS', false);
define('DEFAULT_QUESTION_CLOSED', false);
define('DEFAULT_ANSWER_EXISTS', false);

define('DEFAULT_FIND_CLASSES_TOTAL', 10);
define('DEFAULT_SELECT_ALL_LIMIT', 10);
define('DEFAULT_UPDATE_SHOW_MEMBERS', false);

define('DEFAULT_MEMBER_RAG_STATUS', RAG_STATUS_GREEN);

define('NO_LIMIT', null);

if ($CFG->local)
{
    define('MYSQL_SERVER', 'localhost');
    define('MYSQL_USERNAME', 'root');
    define('MYSQL_PASSWORD', '');
    define('MYSQL_DB_CORE', 'db_core');
}
else
{
    define('MYSQL_SERVER', 'sql101.ihostfull.com'); //Is it dangerous storing these here?
    define('MYSQL_USERNAME', 'uoolo_21901618');
    define('MYSQL_PASSWORD', 'AppsForGood');
    define('MYSQL_DB_CORE', 'uoolo_21901618_db_core');
}

define_lower('DB_CORE');

define_lower('SESSION_LOST');

define_lower('PHP');
define_lower('MAINTENANCE');

define('DOT', '.');

define('DEFAULT_ACCEPTED_COOKIES', true); //Change me
define_lower('ACCEPTED_COOKIES');

define('DEFAULT_CLASS_PUBLIC', true); //Change me?
define_lower('CLASS_PUBLIC');

define_lower('TIMESTAMP_LAST_ACCESSED');

define_lower('TABLE_ADMINS');
//define_lower('ADMIN_HASH');
define_lower('ADMIN_USERNAME');
define_lower('ADMIN_PASSWORD_HASH');
//define_lower()
define('MAX_PASSWORD_HASH_LEN', 60); //bcrypt
define_lower('ADMIN_NAME');
define('MAX_USERNAME_LEN', 30);
define_lower('ADMINS');
define_lower('ADMIN');
define_lower('ADMIN_PASSWORD');
define('DEFAULT_ADMIN_EXISTS', true);
define_lower('IS_ADMIN');

define_lower('UPLOAD_FILE');
define_lower('UPLOAD_FILE_IMAGE');
define_lower('NAME');
define_lower('FILE_IMAGE');
define('MAX_FILE_IMAGE_SIZE', 1000000); //500000
define_lower('TMP_NAME');
define_lower('SIZE');
define_lower('IMAGES');
define_lower('FILE_HASH');
define_lower('FILE_NAME');
define('MAX_FILE_NAME_LEN', 255);
define_lower('TABLE_IMAGES');

define('EXTENSION_GIF', 'gif');
define('EXTENSION_JPG', 'jpg');
define('EXTENSION_PNG', 'png');
define('EXTENSION_JPEG', 'jpeg');

global $ALLOWED_FILE_SIZES;

$ALLOWED_FILE_SIZES = array
(
    FILE_IMAGE => MAX_FILE_IMAGE_SIZE
);

global $ALLOWED_FILE_EXTENSIONS;

$ALLOWED_FILE_EXTENSIONS = array
(
        FILE_IMAGE => array
        (
            EXTENSION_PNG,
            EXTENSION_GIF,
            EXTENSION_JPG,
            EXTENSION_JPEG
        )
);

global $TABLE_IDENTIFIER_MAP;

$TABLE_IDENTIFIER_MAP = array
(
    //TABLE_HOSTS => HOSTS,
    TABLE_IMAGES => IMAGES
);

global $SQL;

$SQL = new stdClass();
$SQL->create_db_core = "CREATE DATABASE IF NOT EXISTS " . MYSQL_DB_CORE;
$SQL->create_table_classes =
    "CREATE TABLE IF NOT EXISTS " . TABLE_CLASSES . " " .
    "(" .
        CLASS_PIN . " INT(" . CLASS_PIN_LEN . ") UNSIGNED NOT NULL PRIMARY KEY, " .
        CLASS_NAME . " VARCHAR(" . MAX_NAME_LEN . ") NOT NULL, " .
        CLOSED . " BOOLEAN NOT NULL, " .
        TIMESTAMP_CREATED . " DATETIME, " .
        CLASS_PUBLIC . " BOOLEAN NOT NULL " .
    ")";
$SQL->create_table_hosts =
    "CREATE TABLE IF NOT EXISTS " . TABLE_HOSTS . " " .
    "(" .
        HOST_NAME . " VARCHAR(" . MAX_NAME_LEN . ") NOT NULL, " .
        HOST_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        CLASS_PIN . " INT(" . CLASS_PIN_LEN . ") UNSIGNED NOT NULL, " .
        TIMESTAMP_HOSTED . " DATETIME NOT NULL, " .
        IP . " VARCHAR(" . MAX_IP_LEN . ") NOT NULL, " .
        USER_AGENT . " VARCHAR(" . MAX_USER_AGENT_LEN . "), " .
        TIMESTAMP_LAST_ACCESSED . " DATETIME NOT NULL, " .
        "FOREIGN KEY " . FK_HOSTS . CLASS_PIN . "(" . CLASS_PIN. ") " .
        "REFERENCES " . TABLE_CLASSES . "(" . CLASS_PIN . ") " .
        "ON UPDATE CASCADE " .
        "ON DELETE CASCADE" .
    ")";
$SQL->create_table_members =
    "CREATE TABLE IF NOT EXISTS " . TABLE_MEMBERS . " " .
    "(" .
        MEMBER_NAME . " VARCHAR(" . MAX_NAME_LEN . ") NOT NULL, " .
        MEMBER_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        CLASS_PIN . " INT(" . CLASS_PIN_LEN . ") UNSIGNED NOT NULL, " .
        TIMESTAMP_JOINED . " DATETIME NOT NULL, " .
        IP . " VARCHAR(" . MAX_IP_LEN . ") NOT NULL, " .
        USER_AGENT . " VARCHAR(" . MAX_USER_AGENT_LEN . "), " .
        RAG_STATUS . " ENUM('" . RAG_STATUS_RED . "', '" . RAG_STATUS_AMBER . "', '" . RAG_STATUS_GREEN . "'), " .
        TIMESTAMP_LAST_ACCESSED . " DATETIME NOT NULL, " .
        "FOREIGN KEY " . FK_MEMBERS . CLASS_PIN . "(" . CLASS_PIN. ") " .
        "REFERENCES " . TABLE_CLASSES . "(" . CLASS_PIN . ") " .
        "ON UPDATE CASCADE " .
        "ON DELETE CASCADE" .
    ")";
$SQL->create_table_ip_blacklist =
    "CREATE TABLE IF NOT EXISTS " . TABLE_IP_BLACKLIST . " " .
    "(" .
        IP . " VARCHAR(" . MAX_IP_LEN . ") NOT NULL PRIMARY KEY" .
    ")";
$SQL->create_table_activity_log =
    "CREATE TABLE IF NOT EXISTS " . TABLE_ACTIVITY_LOG . " " .
    "(" .
        ID . " INT(" . ID_LEN . ") NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
        IP . " VARCHAR(" . MAX_IP_LEN . ") NOT NULL, " .
        URI . " VARCHAR(" . MAX_URI_LEN . ") NOT NULL, " .
        USER_AGENT . " VARCHAR(" . MAX_USER_AGENT_LEN . "), " .
        TIMESTAMP . " DATETIME NOT NULL" .
    ")";
$SQL->create_table_answers =
    "CREATE TABLE IF NOT EXISTS " . TABLE_ANSWERS . " " .
    "(" .
        ANSWER_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        MEMBER_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL, " .
        ANSWER_TINYMCE . " VARCHAR(" . MAX_ANSWER_TINYMCE_LEN . "), " .
        ANSWER_IMAGE . " VARCHAR(" . HASH_LEN . "), " .
        QUESTION_HASH . " VARCHAR(" . HASH_LEN . "), " .
        TIMESTAMP . " DATETIME NOT NULL, " .
        "FOREIGN KEY " . FK_ANSWERS . MEMBER_HASH . "(" . MEMBER_HASH . ") " .
        "REFERENCES " . TABLE_MEMBERS . "(" . MEMBER_HASH . ") " .
        "ON UPDATE CASCADE " .
        "ON DELETE CASCADE, " .
        "FOREIGN KEY " . FK_ANSWERS . QUESTION_HASH . "(" . QUESTION_HASH . ") " .
        "REFERENCES " . TABLE_QUESTIONS . "(" . QUESTION_HASH . ") " .
        "ON UPDATE CASCADE " .
        "ON DELETE CASCADE" .
    ")";
$SQL->create_table_questions =
    "CREATE TABLE IF NOT EXISTS " . TABLE_QUESTIONS . " " .
    "(" .
        QUESTION_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        QUESTION_NAME . " VARCHAR(" . MAX_QUESTION_NAME_LEN . "), " .
        QUESTION_TEXT . " VARCHAR(" . MAX_QUESTION_TEXT_LEN . ") NOT NULL, " .
        ANSWER_MODE . " ENUM('" . ANSWER_MODE_TINYMCE . "', '" . ANSWER_MODE_IMAGE ."') NOT NULL, " .
        CLOSED . " BOOLEAN NOT NULL, " .
        HOST_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL, " .
        TIMESTAMP . " DATETIME NOT NULL, " .
        "FOREIGN KEY " . FK_QUESTIONS . HOST_HASH . "(" . HOST_HASH . ") " .
        "REFERENCES " . TABLE_HOSTS . "(" . HOST_HASH . ") " .
        "ON UPDATE CASCADE " .
        "ON DELETE CASCADE" .
    ")";
$SQL->create_table_admins =
    "CREATE TABLE IF NOT EXISTS " . TABLE_ADMINS . " " .
    "(" .
        //ADMIN_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        //ADMIN_USERNAME . " VARCHAR(" . MAX_USERNAME_LEN . ") NOT NULL, " .
        ADMIN_USERNAME . " VARCHAR(" . MAX_USERNAME_LEN . ") NOT NULL PRIMARY KEY, " .
        ADMIN_PASSWORD_HASH . " VARCHAR(" . MAX_PASSWORD_HASH_LEN . "), " .
        ADMIN_NAME . " VARCHAR(" . MAX_NAME_LEN . ")" .
    ")";
$SQL->create_table_images =
    "CREATE TABLE IF NOT EXISTS " . TABLE_IMAGES . " " .
    "(" .
        //ADMIN_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        //ADMIN_USERNAME . " VARCHAR(" . MAX_USERNAME_LEN . ") NOT NULL, " .
        //ADMIN_USERNAME . " VARCHAR(" . MAX_USERNAME_LEN . ") NOT NULL PRIMARY KEY, " .
        //ADMIN_PASSWORD_HASH . " VARCHAR(" . MAX_PASSWORD_HASH_LEN . "), " .
        //ADMIN_NAME . " VARCHAR(" . MAX_NAME_LEN . ")" .
        FILE_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL PRIMARY KEY, " .
        FILE_NAME . " VARCHAR(" . MAX_FILE_NAME_LEN . "), " .
        MEMBER_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL, " .
        QUESTION_HASH . " VARCHAR(" . HASH_LEN . ") NOT NULL, " .
        TIMESTAMP_UPLOADED . " DATETIME NOT NULL" .
    ")";

global $DB_CORE;

$DB_CORE = new mysqli(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD);

require_once import_plugin($CFG->lib_plugin_zendescaper);
$ESCAPER = new Escaper();

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]))
{
    $status_code = HTTP_STATUS_403;

    include_once "$CFG->error_dir$status_code.".PHP;

    die();
}

 ?>
