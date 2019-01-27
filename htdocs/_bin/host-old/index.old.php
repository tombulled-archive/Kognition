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

//die_error(HTTP_STATUS_503);

define('TMP_HOST_NAME', $_SESSION[WHOAMI]->host_name);
define('TMP_HOST_HASH', $_SESSION[WHOAMI]->host_hash);
define('TMP_CLASS_NAME', $_SESSION[WHOAMI]->class->class_name);
define('TMP_CLASS_PIN', $_SESSION[WHOAMI]->class->class_pin);

 ?>
 <!DOCTYPE html>
 <html>
 <!--<link href ="host-dashboard.css" rel="stylesheet" type="text/css"/>-->
 <head>
     <title>Kognition Teacher View</title> <!-- FIX THIS PAGE -->

     <link href="<?php echo $CFG->url_assets_dir.'css/host-dashboard.css' ?>" rel="stylesheet"/>
     <script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>"></script>

     <script>
         var Host_name = "<?php echo TMP_HOST_NAME; ?>";
         var Host_hash = "<?php echo TMP_HOST_HASH; ?>";
         var Class_name = "<?php echo TMP_CLASS_NAME; ?>";
         var Class_pin = <?php echo TMP_CLASS_PIN; ?>;

         var HostObj = new Host(Host_name, Class_name);
         HostObj.hash = Host_hash;
         HostObj.update();
     </script>
 </head>
 <body>
 <div id = "Kognition_Teacher_View_Top_Bar">
 			<div id="appName" class="Kognition_Teacher_View_Top_BarComponent">K<p style="display: inline; font-size: 80%">⛭</p><!--<img id="logo" src="Kognition logo.png">-->gnition</div>
 			<div id="studentName" class="Kognition_Teacher_View_Top_BarComponent">User: <?php echo TMP_HOST_NAME; ?></div>
 			<div id="className"  class="Kognition_Teacher_View_Top_BarComponent">Class: <?php echo TMP_CLASS_NAME; ?></div>
 			<div id="classCode" class="Kognition_Teacher_View_Top_BarComponent">Code: <?php echo TMP_CLASS_PIN; ?></div>
 			<!--<div id="empty" class="Kognition_Teacher_View_Top_BarComponent">     </div>-->
 </div>

 <div id="questions" style="background: white;"> <!--TEMPORARY: REMOVE ME -->
     <script>
        function populateQuestions(api_out)
        {
            console.log(api_out);
            var questions = api_out['questions'];

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
    				window.top.location.replace('/host/question?question_hash=' + questions[question_index]['question_hash']);
    			};
    			var textnode = document.createTextNode(questions[question_index]['question_text']);
    			node.appendChild(textnode);
    			document.getElementById("questions").appendChild(node);
            }
        }

        HostObj.get_questions(populateQuestions);
     </script>
 </div>
 <!--
 <div id = "PieChartArea">

 	<div id="PieChart" class="PieChartAreaComponent">
 		<img id="pie" src="piechart.png">
 	</div>

 </div>
 <div id ="SideExamplesArea">
 <div id="ExamplePlaceholder"></div>
 </div>
-->
 <!--<div id = "CurrentlyShown">
 <div id = "CurrentlyShownPlaceHolder"><img id ="CurrentlyShownPlaceHolderImage" src = "CurrentlyShownPlaceHolderImage.png" ></div>-->
 </div>
 </body>
 </html>
 <!--
 <!DOCTYPE html>

 <head>
 	<title>Kognition - Host</title>
    <link href="<?php echo $CFG->url_assets_dir.'css/host-landing.css' ?>" rel="stylesheet" type="text/css"/>
 </head>

 <body>
 	<div id="topBar">
 		<div id="className"  class="topBarComponent">Class: <?php echo TMP_CLASS_NAME; ?></div>
 			<!-<div id="appName" class="topBarComponent">K<img id="logo" src="Kognition logo.png">gnition</div>->
         <div id="appName" class="topBarComponent">K<p style="display: inline; font-size: 80%">⛭</p>gnition</div>
 	</div>
 	<div id="classPin">
 		<?php echo TMP_CLASS_PIN; ?>
 	</div>

 	<div id="nameBar" onclick="openNames()">
 		Click to see class
 	</div>

 	<div id="names">
 		<ul>
 			<li>Student1</li>
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
 			<li>Student16</li>
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
 </script>

 </html>-->
