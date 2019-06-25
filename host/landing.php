<?php

// This file is part of Kognition
//
// Message here.

/**
 * index.php - Kognition Directory Listing Preventer
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
require_once import($CFG->lib_host);

log_request();
die_if_blocked();
die_if_maintainance();
//die_if_not_accepted_cookies();

die_if_not_host();


define('TMP_CLASS_NAME', $_SESSION[WHOAMI]->class->class_name);
define('TMP_CLASS_PIN', $_SESSION[WHOAMI]->class->class_pin);

 ?>
<!DOCTYPE html>

<head>
	<title>Host</title>
	<!--<link rel="stylesheet" type="text/css" href="student-page.css">-->
    <link href="<?php echo $CFG->url_assets_dir.'css/host-landing.css' ?>" rel="stylesheet"/>
    <script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>" type="text/javascript"></script>
</head>

<body>
	<div id="topBar">
		<div id="className"  class="topBarComponent">Class: <?php echo TMP_CLASS_NAME; ?></div>
			<!--<div id="appName" class="topBarComponent">K<img id="logo" src="Kognition logo.png">gnition</div>-->
        <div id="appName" class="topBarComponent">K<p style="display: inline; font-size: 80%">⛭</p>gnition</div>
        <!-- where are the rest? -->
	</div>
	<div id="classPin">
		<?php echo TMP_CLASS_PIN; ?>
	</div>

	<div id="nameBar" onclick="openNames()">
		Joined Students:
	</div>

	<div id="names">
		<ul id="students">
			<!--<li>Student1</li>
			<li>Student2</li>
			<li>Student3</li>
			<li>Student4</li>
			<li>Student5</li>
			<li>Student6</li>
			<li>Student7</li>
			<li>Student8</li>
			<li>Student9</li>
			<li>Student10</li>
			<li>Student11</li>
			<li>Student12</li>
			<li>Student13</li>
			<li>Student14</li>
			<li>Student15</li>
			<li>Student16</li>-->
		</ul>
	</div>

    <br><br><br><br>
    <div id="nameBar" onclick="window.top.location.replace('/host/')">
		Begin!
	</div>
</body>

<script>
var HostObj = new Host(null, null);
host_hash = getCookie('host_hash');
HostObj.update(show_members=true, function(api_out, host=HostObj){host._on_update(api_out);});
//var Joined = [];

	function openNames() {
		var div = document.getElementById("names");
		if (div.style.display == "block") {
			div.style.display = "none";
		}
		else {
			div.style.display = "block";
		}
	}

    async function showJoiners()
    {
        host_hash = getCookie('host_hash');
        //console.log(member_hash);

        HostObj.hash = host_hash;

        //console.log(HostObj.members);

        var myNode = document.getElementById("students");
        while (myNode.firstChild) {
    myNode.removeChild(myNode.firstChild);
}

        //while (true)
        //{
            HostObj.update(show_members=true, function(api_out, host=HostObj){host._on_update(api_out);});

            for (index = 0; index < HostObj.members.length; index ++)
            {
                var member = HostObj.members[index];

                //if ...

                //Joined.push(member);

                //console.log(member);

                var node = document.createElement("LI");
    			var textnode = document.createTextNode(member['member_name']);
    			node.appendChild(textnode);
    			document.getElementById("students").appendChild(node);
            }

            //sleep(3000);

            //break;
        //}

        //setInterval(showJoiners, 3000);

    }

    function getCookie(name) {
      var value = "; " + document.cookie;
      var parts = value.split("; " + name + "=");
      if (parts.length == 2) return parts.pop().split(";").shift();
    }

    /*
    async function spoofJoiners()
    {
        var users = ["Sam Cliff", "Bill Wellington", "Samantha Ford", "Henry Jenkins", "Sophie Lit", "Millie Smith", "Julian Norm"];
		for (var user in users) {
            await sleep(1400);
			var node = document.createElement("LI");
			var textnode = document.createTextNode(users[user]);
			node.appendChild(textnode);
			document.getElementById("students").appendChild(node);
		}
    }
    */

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    openNames();
    //showJoiners();
    //spoofJoiners();
    setInterval(showJoiners, 1000);
</script>

</html>
