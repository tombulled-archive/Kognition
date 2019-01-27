<?php

// This file is part of Kognition
//
// Message here.

/**
 * lib/db_lib.php - Kognition Database library
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

class Table
{
    public $table_name;
    public $primary_key_field;

    public function __construct($table_name, $primary_key_field)
    {
        $this->table_name = $table_name;
        $this->primary_key_field = $primary_key_field;
    }

    public function get($primary_key, ...$field_names)
    {
        global $DB_CORE;

        $sql = sql_make_select_where
        (
            $this->table_name,
            $field_names,
            array
            (
                $this->primary_key_field => $primary_key
            )
        );

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);

        $result = sql_process_query
        (
            $query
        );

        return $result;

        /*
        $query = sql_bind_make_select_where
        (
            $this->table_name,
            $field_names,
            array
            (
                $this->primary_key_field => $primary_key
            )
        );

        $query_exec = $query->execute();

        //var_dump($query_exec);

        sql_success_or_die($query_exec);

        $result = sql_process_query
        (
            $query->get_result()
        );

        print_r($result);
        die();

        $query->close();

        return $result;
        */
    }

    public function has($primary_key)
    {
        global $DB_CORE;

        $sql = sql_make_select_where
        (
            $this->table_name,
            array
            (
                $this->primary_key_field
            ),
            array
            (
                $this->primary_key_field => $primary_key
            )
        );

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);

        $result = sql_process_query($query);

        return sizeof($result) > 0;
    }

    public function add($key_vals)
    {
        global $DB_CORE;

        $sql = sql_make_insert
        (
            $this->table_name,
            $key_vals
        );

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);
    }

    public function remove($primary_key)
    {
        global $DB_CORE;

        $sql = sql_make_delete_where
        (
            $this->table_name,
            array
            (
                $this->primary_key_field => $primary_key
            )
        );

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);
    }

    public function find($field_name, $val)
    {
        global $DB_CORE;

        $sql = sql_make_select_where
        (
            $this->table_name,
            array
            (
                $this->primary_key_field
            ),
            array
            (
                $field_name => $val
            )
        );

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);

        $result = sql_process_query
        (
            $query
        );

        return $result;
    }

    public function update($primary_key, $key_vals)
    {
        global $DB_CORE;

        $sql = sql_make_update_where
        (
            $this->table_name,
            array
            (
                $this->primary_key_field => $primary_key
            ),
            $key_vals
        );

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);

        return true; //check me
    }

    public function get_all($limit = NO_LIMIT, $where=array(1=>1))
    {
        global $DB_CORE;

        $sql = sql_make_select_where
        (
            $this->table_name,
            array
            (
                '*'
            ),
            $where
        ) .
        ($limit ? (" LIMIT " . $limit) : "");

        debug_sql($sql);

        $query = $DB_CORE->query
        (
            $sql
        );

        sql_success_or_die($query);

        $result = sql_process_query
        (
            $query
        );

        return $result;
    }
}

function hash_exists($hash, $table_name=null)
{
    if (!is_null($table_name) && isset($_SESSION[$table_name]))
    {
        return $_SESSION[$table_name]->has($hash);
    }
    else
    {
        return $_SESSION[MEMBERS]->has($hash) || $_SESSION[HOSTS]->has($hash);
    }
}

function pin_exists($pin)
{
    return $_SESSION[CLASSES]->has($pin);
}

function sql_success_or_die($output)
{
    global $DB_CORE;

    if ($output === FALSE)
    {
        //request_failed("SQLError: $DB_CORE->error");
        request_failed("SQLError");
    }

    return $output;
}

function sql_process_query($result)
{
    $rows = array();

    if (!is_null($result) && $result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc())
        {
            $rows[] = $row;
        }
    }

    return $rows;

}

function sql_make_select($table_name, ...$field_names)
{
    $sql = "SELECT " . implode(", ", $field_names) . " FROM " . $table_name;

    return $sql;
}

function sql_make_select_where($table_name, $field_names, $where)
{
    $sql = "SELECT " . implode(", ", $field_names) . " FROM " . $table_name . " WHERE " . implode_assosciative_array("' AND ", "='", $where) . "'";

    return $sql;
}

/*
function sql_bind_make_select_where($table_name, $field_names, $where, $bool_sep='AND')
{
    global $DB_CORE;

    $raw_prepared_query =
        "SELECT " .
        implode(", ", create_initialised_array('?', count($field_names))) .
        " FROM " .
        $table_name .
        " WHERE " .
        implode(" $bool_sep ",create_initialised_array("?=?", count($where)));

    //echo $raw_prepared_query;

    $prepared_query = $DB_CORE->prepare($raw_prepared_query);

    sql_success_or_die($prepared_query);

    $vals = array_merge($field_names, flatten_assosciative_array($where));

    $prepared_query->bind_param(implode("", array_fill(0, count($field_names)+count($where)*2, 's')), ...$vals);

    return $prepared_query;
}
*/

function sql_make_insert($table_name, $key_vals)
{
    $sql =
        "INSERT INTO $table_name " .
        "(" .
            implode
            (
                ", ",
                array_keys
                (
                    $key_vals
                )
            ) .
        ") " .
        "VALUES " .
        "(" .
            "'" .
            implode
            (
                "', '",
                array_values
                (
                    $key_vals
                )
            ) .
            "'" .
        ")";

    return $sql;
}

function sql_make_delete_where($table_name, $where)
{
    $sql = "DELETE FROM $table_name WHERE " . implode_assosciative_array("' AND ", "='", $where) . "'";

    return $sql;
}

function sql_make_update_where($table_name, $where, $key_vals)
{
    $sql = "UPDATE $table_name SET " . implode_assosciative_array("', ", "='", $key_vals) . "' WHERE ". implode_assosciative_array("' AND ", "='", $where) . "'";

    return $sql;
}

 ?>
