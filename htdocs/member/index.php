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
require_once import($CFG->lib_member);

log_request();
die_if_blocked();
die_if_maintainance();
//die_if_not_accepted_cookies();

die_if_not_member();

//die_error(HTTP_STATUS_403);


define('TMP_MEMBER_NAME', $_SESSION[WHOAMI]->member_name);
define('TMP_MEMBER_HASH', $_SESSION[WHOAMI]->member_hash);
define('TMP_CLASS_NAME', $_SESSION[WHOAMI]->class->class_name);
define('TMP_CLASS_PIN', $_SESSION[WHOAMI]->class->class_pin);

 ?>
<!DOCTYPE html>

<head>
	<title>Member</title>
	<!--<link rel="stylesheet" type="text/css" href="student-page.css">-->
    <link href="<?php echo $CFG->url_assets_dir.'css/student-questions.css' ?>" rel="stylesheet"/>
    <script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>"></script>
    <script>
        var Member_name = "<?php echo TMP_MEMBER_NAME; ?>";
        var Member_hash = "<?php echo TMP_MEMBER_HASH; ?>";
        //var CLASS_NAME = "<?php echo TMP_CLASS_NAME; ?>";
        var Class_pin = <?php echo TMP_CLASS_PIN; ?>;

        var MemberObj = new Member(Member_name, Class_pin);
        MemberObj.hash = Member_hash;
        MemberObj.update();
    </script>
    <style>
    	ul {
       	    list-style-type: none;
        }
    </style>
</head>

<body>
	<div id="topBar">
		<div id="studentName" class="topBarComponent">User: <?php echo TMP_MEMBER_NAME; ?></div>
		<div id="className"  class="topBarComponent">Class: <?php echo TMP_CLASS_NAME; ?></div>
		<div id="classCode" class="topBarComponent">Code: <?php echo TMP_CLASS_PIN; ?></div>
		<!--<div id="appName" class="topBarComponent">K<img id="logo" src="Kognition logo.png">gnition</div>-->
        <div id="appName" class="topBarComponent">K<p style="display: inline; font-size: 80%">⛭</p>gnition</div>
	</div>



	<div id="questions">
		<h1>Questions:</h1>
		<div id="foo"> <!-- rename me -->
			<!--ggg-->
		</div>
		<ul>
		<ul id="questions-list">
		</ul>
	</div>

</body>

<script>
	function openNames() {
		var div = document.getElementById("names");
		if (div.style.display == "block") {
			div.style.display = "none";
		}
		else {
			div.style.display = "block";
		}
	}

	/*function generateList() { // pass 'arr'
		var arr = []; //["What is recursion?", "Define the method used to ge...", "Name two high-level program..."];
		for (var i in arr) {
			var node = document.createElement("LI");
			/*node.onclick = function()
			{
				window.top.location.replace('/member/answer');
			};*\ // Temp code
			var textnode = document.createTextNode(arr[i]);
			node.appendChild(textnode);
			document.getElementById("questions").appendChild(node);
		}
	}*/

    function populateQuestions(api_out)
    {
        //console.log(api_out);
        var questions = api_out['questions'];

        document.getElementById("questions-list").innerHTML = '';

        for (var question_index in questions)
        {
            //console.log(question);

            if (questions[question_index]['closed'] == "1" || !questions[question_index]['exists']) //add already_answered
            {
                continue;
            }

            var node = document.createElement("LI");
			node.onclick = function()
			{
				window.top.location.replace('/member/answer?question_hash=' + questions[question_index]['question_hash']);
			};
			var textnode = document.createTextNode(questions[question_index]['question_text']);
			node.appendChild(textnode);
			document.getElementById("questions-list").appendChild(node);
        }
    }
	//generateList();

    MemberObj.get_questions(populateQuestions);
    
    setInterval(function(){MemberObj.get_questions(populateQuestions);}, 3000);
</script>

</html>
