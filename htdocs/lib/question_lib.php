<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/question_lib.php - Kognition Question library
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

class QuestionObj
{
    public $question_hash;
    public $question_name;
    public $question_text;
    public $answer_mode;
    public $closed = DEFAULT_QUESTION_CLOSED;
    public $host;
    public $timestamp;
    public $exists = DEFAULT_QUESTION_EXISTS;

    public function __construct
        (
            $question_hash = null,
            $question_name = null,
            $question_text = null,
            $answer_mode = null,
            $closed = DEFAULT_QUESTION_CLOSED,
            $host = null,
            $timestamp = null,
            $exists = DEFAULT_QUESTION_EXISTS
        )
    {
        $this->question_hash = $question_hash;
        $this->question_name = $question_name;
        $this->question_text = $question_text;
        $this->answer_mode = $answer_mode;
        $this->closed = $closed;
        $this->host = $host;
        $this->timestamp = $timestamp;
        $this->exists = $exists;
    }

    public function update()
    {
        return array_merge($this->attr_array(), request_success_array());
    }

    public function attr_array()
    {
        $host_attrs = (!is_null($this->host) && method_exists($this->host, ATTR_ARRAY))
            //? $this->class->attr_array()
            ? $this->host->ping() //dont use me
            : null;

        return array
        (
            QUESTION_HASH=>$this->question_hash,
            QUESTION_NAME=>$this->question_name,
            QUESTION_TEXT=>$this->question_text,
            ANSWER_MODE=>$this->answer_mode,
            CLOSED=>$this->closed,
            HOST=>$host_attrs,
            TIMESTAMP=>$this->timestamp,
            EXISTS=>$this->exists
        );
    }

    public function attr_array_no_host()
    {
        return array
        (
            QUESTION_HASH=>$this->question_hash,
            QUESTION_NAME=>$this->question_name,
            QUESTION_TEXT=>$this->question_text,
            ANSWER_MODE=>$this->answer_mode,
            CLOSED=>$this->closed,
            TIMESTAMP=>$this->timestamp,
            EXISTS=>$this->exists
        );
    }

    public function attr_array_non_recursive()
    {
        return array
        (
            QUESTION_HASH=>$this->question_hash,
            QUESTION_NAME=>$this->question_name,
            QUESTION_TEXT=>$this->question_text,
            ANSWER_MODE=>$this->answer_mode,
            CLOSED=>$this->closed,
            TIMESTAMP=>$this->timestamp,
            EXISTS=>$this->exists
        );
    }

    public function edit($key_vals)
    {
        $this->question_name = $key_vals[QUESTION_NAME] ?? $this->question_name;
        $this->question_text = $key_vals[QUESTION_TEXT] ?? $this->question_text;
        $this->closed = $key_vals[QUESTION_CLOSED] ?? $this->closed;

        if ($this->exists)
        {
            return $this->commit();
        }

        return true; //check correct
    }

    public function commit()
    {
        return update_question($this);
    }

    /*
    public function ping()
    {
        $attrs = $this->attr_array_no_host(); //attr_array_no_class?

        unset($attrs[HOST_HASH]);

        return array_merge($attrs, request_success_array());
    }
    */

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

    public function deregister()
    {
        return deregister_question($this);
    }

    public function register()
    {
        return register_question($this);
    }
}

function create_question($question_text, $answer_mode, $question_name=null, $host=null)
{
    $question = new QuestionObj
    (
        $question_hash = generate_hash(QUESTIONS),
        $question_name = $question_name,
        $question_text = $question_text,
        $answer_mode = $answer_mode,
        $closed = DEFAULT_QUESTION_CLOSED,
        $host = $host,
        $timestamp = timestamp(),
        $exists = true
    );

    return $question;
}

function die_if_not_answer_mode($answer_mode)
{
    global $ANSWER_MODES;

    if (!in_array($answer_mode, $ANSWER_MODES))
    {
        //request_failed("Invalid answer_mode: '$answer_mode'");
        request_failed("Invalid answer_mode");
    }
}

function die_if_not_question($question_hash)
{
    if (! question_hash_exists($question_hash))
    {
        //request_failed("Invalid question_hash: '$question_hash'");
        request_failed("Invalid question_hash");
    }
}

function die_if_not_host_owns_question($question, $host)
{
    if (!host_has_question($host, $question))
    {
        request_failed('Question does not belong to members class');
    }
}

function die_if_already_answered($question, $member)
{
    if (member_already_answered($question, $member))
    {
        request_failed('Member has already answered question');
    }
}

function die_if_question_closed($question)
{
    if ($question->closed)
    {
        request_failed('Question is closed');
    }
}

function die_if_cant_answer($question, $member)
{
    die_if_not_host_owns_question($question, $member->class->host);

    die_if_already_answered($question, $member);

    die_if_question_closed($question);
}

function die_if_not_owns_question($question, $host)
{
    if (!host_has_question($host, $question))
    {
        request_failed('Host doesn\'t own question');
    }
}

function die_if_cant_create_question($host)
{
    if ($host->get_total_questions() >= MAX_HOST_QUESTIONS)
    {
        request_failed("Host has created maximum number of questions");
    }
}

 ?>
