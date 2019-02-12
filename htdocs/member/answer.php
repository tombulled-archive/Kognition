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

$question_hash = required_param(QUESTION_HASH, PARAM_STR, METHOD_GET);

die_if_not_question($question_hash);
$question = get_question_from_hash($question_hash);

//die_error(HTTP_STATUS_403);


define('TMP_MEMBER_NAME', $_SESSION[WHOAMI]->member_name);
define('TMP_MEMBER_HASH', $_SESSION[WHOAMI]->member_hash);
define('TMP_CLASS_NAME', $_SESSION[WHOAMI]->class->class_name);
define('TMP_CLASS_PIN', $_SESSION[WHOAMI]->class->class_pin);
define('TMP_QUESTION', $question->question_text);
define('TMP_QUESTION_HASH', $question->question_hash);

 ?>
<!DOCTYPE html>

<html>
	<head>
		<title>Apps For Good Students</title>
        <link href="<?php echo $CFG->url_assets_dir.'css/student-page.css' ?>" rel="stylesheet"/>
        <script src="<?php echo $CFG->url_assets_dir.'js/student-page.js' ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $CFG->url_assets_dir.'js/jquery.min.js' ?>"></script>
        <script type="text/javascript" src="<?php echo $CFG->url_plugins_dir.'tinymce/tinymce.min.js' ?>"></script>
        <script type="text/javascript" src="<?php echo $CFG->url_plugins_dir.'tinymce/init-tinymce.js' ?>"></script>
        <script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>" type="text/javascript"></script>
        <script src="<?php echo $CFG->url_assets_dir.'js/underscore.js' ?>" type="text/javascript"></script>
        <style>
            /* ADD ME TO .CSS FILE */
            /*Container, container body, iframe*/
            .mce-tinymce, .mce-container-body, #code_ifr {
                min-height: 100% !important;
                border-width: 0px !important; /* added */
            }
            /*Container body*/
            .mce-container-body {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
            }
            /*Editing area*/
            .mce-container-body .mce-edit-area {
                position: absolute;
                top: 69px;
                bottom: 37px;
                left: 0;
                right: 0;
            }
            /*Footer*/
            .mce-tinymce .mce-statusbar {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
            }
        </style>
        <script>
            var Member_name = "<?php echo TMP_MEMBER_NAME; ?>";
            var Member_hash = "<?php echo TMP_MEMBER_HASH; ?>";
            //var CLASS_NAME = "<?php echo TMP_CLASS_NAME; ?>";
            var Class_pin = <?php echo TMP_CLASS_PIN; ?>;
            var Question_hash = "<?php echo TMP_QUESTION_HASH; ?>";

            var MemberObj = new Member(Member_name, Class_pin);
            MemberObj.hash = Member_hash;
            MemberObj.update();

            var QuestionObj = new Question(Question_hash, null, null, null, null);
            //QuestionObj.update();
        </script>
	</head>
	<body>
		<div id="topBar">
			<div id="studentName" class="topBarComponent">User: <?php echo TMP_MEMBER_NAME; ?></div>
			<div id="className"  class="topBarComponent">Class: <?php echo TMP_CLASS_NAME; ?></div>
			<div id="classCode" class="topBarComponent">Code: <?php echo TMP_CLASS_PIN; ?></div>
			<!--<div id="appName" class="topBarComponent">K<img id="logo" src="Kognition logo.png">gnition</div>-->
            <div id="appName" class="topBarComponent">K<p style="display: inline; font-size: 80%">⛭</p>gnition</div>
		</div>

		<div id="questionSpace">
			<ul>
				<li><?php echo TMP_QUESTION; ?></li>
			</ul>
		</div>

        <div id="answerBox">
			<div id="toolBar">
				<div id="inputMethodPanel" class="toolbarPanel">
					<p class="toolbarPanelDescriptor">Input Methods</p>
					<div id="textButton" class="inputMethodButton toolbarButton" onclick="change_input_method(event, 'textResponseArea')">Text</div>
					<div id="whiteboardButton" class="inputMethodButton toolbarButton" onclick="//change_input_method(event, 'whiteboardResponseArea')">Board</div>
				</div>
                <div class="toolbarButton" id="submitAnswerButton" onclick="submit_answer()">Submit</div>
			</div>
			<div class="responseArea" id="textResponseArea" style="width: 100%; height:100%; padding: 0px"> <!-- put me in css stylesheet -->
                <textarea class="tinymce" id="textResponseAreaText" name="Answer"></textarea> <!--  rows=20 cols=130 placeholder="Your answer here..." -->
            </div>
			<div class="responseArea" id="whiteboardResponseArea" style="width: 100%; height:100%; padding: 0px">... Whiteboard Placeholder ...</div>
		</div>

		<!--<div id="colourBar">
			<div id="colourBarGreen" class="colourBarComponent"></div>
			<div id="colourBarAmber" class="colourBarComponent"></div>
			<div id="colourBarRed" class="colourBarComponent"></div>
		</div>--> <!-- Until we implement them properly, they don't need to be seen -->

        <script type="text/javascript">
			main();//runs main() when page is initially loaded

            var AnswerObj = new Answer(MemberObj.hash, QuestionObj.question_hash);

            //tinymce.get('textResponseAreaText').focus();

			function main(){
				document.getElementById("textButton").click();//activates text input by default

                setTimeout(insert_current_answer, 2000);

                //setTimeout(tinymce.get('textResponseAreaText').focus, 2000);
			}

            function insert_current_answer()
            {
                QuestionObj.get_answer(MemberObj.hash, callback=on_get_answer)
            }

            function on_get_answer(api_out)
            {
                console.log("on_get_answer");
                console.log(api_out);

                if (!api_out['success'])
                {
                    return;
                }

                var answer_hash = api_out['answer']['answer_hash'];

                console.log("answer hash: " + answer_hash);

                AnswerObj.answer_hash = answer_hash;

                console.log("answer hash: " + AnswerObj.answer_hash);

                var answer_tinymce = api_out['answer']['answer_tinymce'];

                //console.log(answer_tinymce);

                answer_tinymce = _.unescape(answer_tinymce);

                //console.log(answer_tinymce);

                //tinymce.activeEditor.setContent(html, {format: 'raw'});
                tinymce.get('textResponseAreaText').setContent(answer_tinymce, {format: 'raw'});

                //console.log('INSERTED');
            }

            function submit_answer(answer=null)
            {
                if (answer == null)
                {
                    answer_tinymce = tinymce.get('textResponseAreaText').getContent();

                    //console.log(answer); //Do something: Send to Server.

                    /*var AnswerObj = new Answer
                    (
                            MemberObj.hash,
                            QuestionObj.question_hash,
                            answer_tinymce = answer_tinymce,
                    );*/

                    AnswerObj.answer_tinymce = answer_tinymce;

                    console.log("answer hash: " + AnswerObj.answer_hash);

                    if (AnswerObj.answer_hash == null)
                    {
                        AnswerObj.create(function(api_out, answer=AnswerObj){answer._on_update(api_out); on_answered(api_out);});
                    }
                    else {
                        AnswerObj.edit(answer_tinymce=answer_tinymce, function(api_out, answer=AnswerObj){answer._on_update(api_out); on_answered(api_out);});
                    }
                }

                /*
                alert('Answer sent!');
                window.top.location.replace('/member/');*/ //Temp code
            }

            function on_answered(api_out=null)
            {
                //console.log(answer);

                //var AnswerObj = answer;

                console.log(AnswerObj);

                if (! AnswerObj.answer_hash)
                {
                    alert("Answer failed to send: " + api_out["message"]);
                }
                else
                {
                    alert("Answer sent sucessfully!");
                    window.top.location.replace("/member/");
                }
            }

			function change_input_method(event, responseAreaID)
			{
                //return; // for now: Lock them into Text-mode

				var i, inputMethodButtonArray, responseAreaArray;

				//removes current input method from page
				responseAreaArray = document.getElementsByClassName("responseArea");
				for(i=0;i<responseAreaArray.length;i++){
					responseAreaArray[i].style.display = "none";
				}

				//deactivates any active input method buttons
				inputMethodButtonArray = document.getElementsByClassName("inputMethodButton");
				for(i=0;i<inputMethodButtonArray.length;i++){
					inputMethodButtonArray[i].className = inputMethodButtonArray[i].className.replace(" active","");
				}

				//activates new input method and button
				document.getElementById(responseAreaID).style.display = "block";
				event.currentTarget.className += " active";
			}

            /*
            function resize_tinymce()
            {
                var text_tinymce = document.getElementById('textResponseArea').getElementsByTagName('div')[0];

                text_tinymce.style.height = '100%';

            }

            resize_tinymce();
            */
        </script>
	</body>
</html>
