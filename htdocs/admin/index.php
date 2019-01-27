<?php

// This file is part of Kognition
//
// Message here.

/**
 * admin/index.php - Kognition Admin Dashboard Page
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
require_once import($CFG->lib_admin);

log_request();
die_if_blocked();
die_if_maintainance();
//die_if_not_accepted_cookies();

//die_error(HTTP_STATUS_403);

//die_if_not_admin();

is_admin() || redirect($CFG->url_admin_dir . "login");

$admin = $_SESSION[WHOAMI];

$classes = discover_classes(NO_LIMIT, $public_essential=false);

/*
echo "<h1>Classes:</h1>";
echo "<ul>";

foreach ($classes as $class)
{
    echo "<li><a href=''>$class->class_name</a></li>";
}

echo "</ul>";
*/

 ?>
<!DOCTYPE html>
<html>
<head>
<style>
table {
 font-family: arial, sans-serif;
 border-collapse: collapse;
 width: 100%;
}

td, th {
 border: 1px solid #dddddd;
 text-align: left;
 padding: 8px;
}

tr:nth-child(even) {
 background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Classes</h2>

<table>
<tr>
 <th>Host Name</th>
 <th>Class Name</th>
 <th>Class Pin</th>
</tr>
<?php
foreach ($classes as $class)
{
    $host_name = $class->host->host_name;
    $class_name = $class->class_name;
    $class_pin = $class->class_pin;

    echo "<tr onclick=\"delve('$host_name', '$class_name', '$class_pin')\">\n";
    echo "\t<td>$host_name</td>\n";
    echo "\t<td>$class_name</td>\n";
    echo "\t<td>$class_pin</td>\n";
    echo "</tr>";
}
 ?>
</table>

<script>
    function delve(host_name, class_name, class_pin)
    {
        var url = "http://localhost/admin/delve_class?class_pin=" + class_pin;

        window.top.location.replace(url);
        /*
        var url = "http://localhost/api/admin/delve_class?class_pin=" + class_pin;

        http_get(url, on_delve);
        */
    }

    /*
    function http_get(url, _callback=null)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //document.getElementById("demo").innerHTML = this.responseText;
          _callback(JSON.parse(this.responseText));
        }
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function on_delve(api_out)
    {
        console.log(api_out);

        if (!api_out['success'])
        {
            alert(api_out['message']);

            return;
        }


    }*/
</script>

</body>
</html>
