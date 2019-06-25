<?php

// This file is part of Kognition
//
// Message here.

/**
 * admin/login.php - Kognition Admin Login Page
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

is_admin() && redirect($CFG->url_admin_dir);
//die_if_not_accepted_cookies();

/*
$admin_username = optional_param(ADMIN_USERNAME, PARAM_STR, METHOD_GET);
$admin_password = optional_param(ADMIN_PASSWORD, PARAM_STR, METHOD_GET);

echo $admin_username;
br();
echo $admin_password;
br();
br();

if (!is_null($admin_username) && !is_null($admin_password))
{

}
*/

//die_error(HTTP_STATUS_403);

//die_if_not_admin();

//echo 'admin login here';



 ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
 width: 100%;
 padding: 12px 20px;
 margin: 8px 0;
 display: inline-block;
 border: 1px solid #ccc;
 box-sizing: border-box;
}

button {
 background-color: #4CAF50;
 color: white;
 padding: 14px 20px;
 margin: 8px 0;
 border: none;
 cursor: pointer;
 width: 100%;
}

button:hover {
 opacity: 0.8;
}

.cancelbtn {
 width: auto;
 padding: 10px 18px;
 background-color: #f44336;
}

.imgcontainer {
 text-align: center;
 margin: 24px 0 12px 0;
}

img.avatar {
 width: 40%;
 border-radius: 50%;
}

.container {
 padding: 16px;
}

span.psw {
 float: right;
 padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
 span.psw {
    display: block;
    float: none;
 }
 .cancelbtn {
    width: 100%;
 }
}
</style>
</head>
<body>

<h2>Login Form</h2>

<!--<form action="login.php">-->
<div class="imgcontainer">
 <img src="img_avatar2.png" alt="Avatar" class="avatar">
</div>

<div class="container">
 <label for="uname"><b>Username</b></label>
 <input type="text" placeholder="Enter Username" name="admin_username" id="input_admin_username" required>

 <label for="psw"><b>Password</b></label>
 <input type="password" placeholder="Enter Password" name="admin_password" id="input_admin_password" required>

 <button type="submit" onclick="login()" id="form_submit">Login</button>
 <label>
   <input type="checkbox" checked="checked" name="remember_me_toggle"> Remember me
 </label>
</div>

<div class="container" style="background-color:#f1f1f1">
 <button type="button" class="cancelbtn">Cancel</button>
 <span class="psw">Forgot <a href="#">password?</a></span>
</div>
<!--</form>-->

<script>
    function login()
    {
        var admin_username = document.getElementById('input_admin_username').value;
        var admin_password = document.getElementById('input_admin_password').value;
        //remember_me

        //console.log(admin_username + " " + admin_password);

        var url = "http://localhost/api/admin/login?admin_username=" + admin_username + "&admin_password=" + admin_password;

        http_get(url, on_login);
    }

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

    function on_login(api_out)
    {
        //console.log(api_out);

        if (!api_out['success'])
        {
            alert(api_out['message']);

            return;
        }

        var admin = api_out['admin'];

        var url = "http://localhost/admin/";

        window.top.location.replace(url);
    }

    document.querySelector("#input_admin_password").addEventListener("keyup", event => {
        if(event.key !== "Enter") return; // Use `.key` instead.
        document.querySelector("#form_submit").click(); // Things you want to do.
        event.preventDefault(); // No need to `return false;`.
    });
</script>

</body>
</html>
