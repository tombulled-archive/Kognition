<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/answer_lib.php - Kognition Answer library
 *
 * Message here.
 *
 * @package     kognition_core
 * @copyright   none
 * @license     none
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once import($CFG->lib_core);

die_if_directly_requested(__FILE__);

class AnswerObj
{
    public $answer_hash;
    public $member;
    public $answer_tinymce;
    public $answer_image;
    public $question;
    public $timestamp;
    public $exists = DEFAULT_ANSWER_EXISTS;

    public function __construct
        (
            $answer_hash = null,
            $member = null,
            $answer_tinymce = null,
            $answer_image = null,
            $question = null,
            $timestamp = null,
            $exists = DEFAULT_ANSWER_EXISTS
        )
    {
        $this->answer_hash = $answer_hash;
        $this->member = $member;
        $this->answer_tinymce = $answer_tinymce;
        $this->answer_image = $answer_image;
        $this->question = $question;
        $this->timestamp = $timestamp;
        $this->exists = $exists;
    }

    public function update()
    {
        return array_merge($this->attr_array(), request_success_array());
    }

    public function attr_array()
    {
        $member_attrs = !is_null($this->member)
            ? $this->member->attr_array()
            : null;
        $question_attrs = !is_null($this->question)
            ? $this->question->attr_array_no_host()
            : null;

        return array
        (
            ANSWER_HASH=>$this->answer_hash,
            MEMBER=>$member_attrs,
            ANSWER_TINYMCE=>$this->answer_tinymce,
            ANSWER_IMAGE=>$this->answer_image,
            QUESTION=>$question_attrs,
            TIMESTAMP=>$this->timestamp,
            EXISTS=>$this->exists
        );
    }

    public function attr_array_non_recursive()
    {
        return array
        (
            ANSWER_HASH=>$this->answer_hash,
            ANSWER_TINYMCE=>$this->answer_tinymce,
            ANSWER_IMAGE=>$this->answer_image,
            TIMESTAMP=>$this->timestamp,
            EXISTS=>$this->exists
        );
    }

    public function attr_array_no_question()
    {
        $member_attrs = !is_null($this->member)
            ? $this->member->attr_array()
            : null;

        return array
        (
            ANSWER_HASH=>$this->answer_hash,
            MEMBER=>$member_attrs,
            ANSWER_TINYMCE=>$this->answer_tinymce,
            ANSWER_IMAGE=>$this->answer_image,
            TIMESTAMP=>$this->timestamp,
            EXISTS=>$this->exists
        );
    }

    public function register()
    {
        return register_answer($this);
    }

    public function deregister()
    {
        return deregister_answer($this);
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

    public function edit($key_vals)
    {
        $this->answer_tinymce = $key_vals[ANSWER_TINYMCE] ?? $this->answer_tinymce;
        $this->answer_image = $key_vals[ANSWER_IMAGE] ?? $this->answer_image;

        if ($this->exists)
        {
            return $this->commit();
        }

        return true; //check correct
    }

    public function commit()
    {
        return update_answer($this);
    }
}

function create_answer($question, $submitted_answer, $member=null)
{
    $answer = new AnswerObj
    (
        $answer_hash = generate_hash(ANSWERS),
        $member = $member,
        $answer_tinymce = null,
        $answer_image = null,
        $question = $question,
        $timestamp = timestamp(),
        $exists = true
    );

    switch ($question->answer_mode)
    {
        case ANSWER_MODE_TINYMCE:
            $answer->answer_tinymce = $submitted_answer;
        case ANSWER_MODE_IMAGE:
            //$answer->answer_image = $submitted_answer;
            $file_image_hash = upload_image($submitted_answer, $member->member_hash, $question->question_hash);
            $answer->answer_image = $file_image_hash;
    }

    return $answer;
}

function die_if_invalid_answer($question, $submitted_answer)
{
    if (is_null($submitted_answer)) //Do This For Now. Actually perform checks here in the future though
    {
        //request_failed("Invalid answer for mode '$question->answer_mode': '$submitted_answer'");
        request_failed("Invalid answer provided for answer mode");
    }
}

function die_if_didnt_answer($answer, $member)
{
    //use db?

    if ($answer->member->member_hash != $member->member_hash)
    {
        request_failed('Member doesn\'t own answer');
    }
}

function die_if_not_answer($answer_hash)
{
    if (!answer_hash_exists($answer_hash))
    {
        //request_failed("Invalid answer_hash: '$answer_hash'");
        request_failed("Invalid answer_hash");
    }
}

 ?>
