<?php

// This file is part of Kognition
//
// Message here.

/**
 * scripts/upload/upload_image.php - Kognition Image Uploader Script
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

log_request();
die_if_blocked();
die_if_maintainance();

//die_error(HTTP_STATUS_403);

$member_hash = required_param(MEMBER_HASH, PARAM_STR, METHOD_GET);
$question_hash = required_param(QUESTION_HASH, PARAM_STR, METHOD_GET);
$file = required_file(UPLOAD_FILE, FILE_IMAGE, MAX_FILE_IMAGE_SIZE);

//$file_hash = generate_hash(TABLE_IMAGES);

//$file_name = upload_file($file);//, $file_hash);
//register_file($file, $file_name)

//$file_hash = upload_image($file, TABLE_IMAGES); //TODO: Make TABLE_IMAGES | Done???
$file_hash = upload_image($file, $member_hash, $question_hash);

$api_out = array();
$api_out[FILE_HASH] = $file_hash;

request_success($api_out);

 ?>
