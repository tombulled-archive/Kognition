<?php

// This file is part of Kognition
//
// Message here.

/**
 * index.php - Kognition Host Index Page
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
<!--<script src="<?php echo $CFG->url_assets_dir.'js/host-page.js' ?>"></script>-->
<html>
<link href="<?php echo $CFG->url_assets_dir.'css/host-page.css' ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>" type="text/javascript"></script>
<script src="<?php echo $CFG->url_assets_dir.'js/underscore.js' ?>" type="text/javascript"></script>
<head>
<title>Kognition Teacher View</title>
</head>
<body>
<div id = "HostPageTopBar">
			<div id="className"  class="HostPageTopBarComponent">Class: <?php echo TMP_CLASS_NAME; ?></div>
			<div id="classCode" class="HostPageTopBarComponent" style="overflow:hidden;">Code: <?php echo TMP_CLASS_PIN; ?></div>
			<div id="Option1" class="HostPageTopBarComponent" onclick="popup('Option1Pop')"><!--<img id="Option1Image" src="Option1Icon.png">--></div>
			<div class="popup" id="Option1Pop"></div>
			<div id="Option2" class="HostPageTopBarComponent" onclick="Option2Funtion()"><!--<img id="Option2Image" src="Option2Icon.png">-->
				<!--<span class="Option2Pop" id="Option2PopId"></span>-->
			</div>
			<div id="Option3" class="HostPageTopBarComponent" onclick="Option3Funtion()"><!--<img id="Option3Image" src="Option3Icon.png">-->
				<!--<span class="Option3Pop" id="Option3PopId"></span>-->
			</div>
			<!--<div id="appName" class="HostPageTopBarComponent">-->
				<!--K-->
				<!--<img id="logo"
						 src="https://www.cobry.co.uk/wp-content/uploads/2018/07/Cog.png"
						 style="
						 		height: 50px;
								width: 50px;
								display: inline;"
				>--> <!-- KOG ICON HERE -->
				<!--gnition-->
		  <!--</div>-->
          <div id="appName" class="HostPageTopBarComponent">K<p style="display: inline; font-size: 80%">⛭</p>gnition</div>

</div>
<div id ="MainArea">
	<div id="HostLeftArea" >
		<div id="HostLeftAreaTitle">Your Class:</div>
		<ul id="HostLeftAreaStudents">
			<!--<li>Dan</li>
			<li>Tom </li>
			<li>Rob </li>
			<li>Ed </li>
			<li>Ben</li>
			<li>Joe </li>
			<li>Ollie </li>
			<li>Jeff </li>-->
		</ul>
	</div>
	<div id = "CurrentlyShown">
		<div id = "UpperCurrentlyShown">
			<div id = "UpperCurrentlyShownTitle"></div>
		</div>
		<div id = "MidCurrentlyShown">
			<div id = "MidCurrentlyShownTitle">Question: <b><p style="display:inline;" id="current_question_text"></p></b></div>
			<div id = "MidCurrentlyQuestion">Answers:</div>
            <div id="current_question_answers_area">
                <ul id="current_question_answers_list">
                </ul>
            </div>

		</div>
        <div id="current_answer_output" style="margin-left: 10px; margin-top: 10px;"></div>

	</div>
	<div id= "HostRightArea">
			<div id="HostUpperRightArea" >
				<div id="HostUpperRightAreaTitle">Questions:</div>
					<div id="HostUpperUpperRightArea" >
						<div id="HostUpperUpperUpperRightAreaTitle">New Question:</div>
						<div id="HostUpperUpperRightAreaMiddle">
							<div id="QuestionTextField"><form action="/action_page.php"> <!-- ADD ACTION PAGE -->
							<textarea id="QuestionTextFieldTextArea" style="width:95%;resize:none" cols=50 rows=8></textarea><br>
							</div>
						</div>
						<div id="HostUpperUpperRightAreaBottom">
							<button type="button" id="HostUpperUpperRightAreaBottomButton" onclick="javascript:submitNewQuestion();">SUBMIT!</button>
						</div>
					</div>
					<div id="HostLowerUpperRightArea">
						<div id="HostLowerUpperRightAreaTitle">Current Questions:</div>
						<ul id="HostLowerUpperRightAreaQuestionList">
							<!--<li>Question_1</li>
							<li>Question_2</li>
							<li>Question_3</li>
							<li>Question_4</li>
							<li>Question_5</li>
							<li>Question_6</li>
							<li>Question_7</li>
							<li>Question_8</li>-->
						</ul>
					</div>
			</div>
		<div id="HostLowerRightArea" >
			<div id="HostLowerRightAreaTitle">Polls:</div>
			<div id="HostLowerRightAreaMiddle"></div>
			<div id="HostLowerRightAreaBottom"></div>
		</div>
	</div>
</div>

<script>
var HostObj = new Host(null, null);

host_hash = getCookie('host_hash');
class_pin = getCookie('class_pin');

HostObj.update(show_members=true, function(api_out, host=HostObj){host._on_update(api_out);});

    async function showMembers()
    {
        host_hash = getCookie('host_hash');
        //class_pin = getCookie('class_pin');

        HostObj.hash = host_hash;
        //HostObj.hash = host_hash;

        //console.log(HostObj.members);

        var myNode = document.getElementById("HostLeftAreaStudents");
        while (myNode.firstChild) { myNode.removeChild(myNode.firstChild); }

        HostObj.update(show_members=true, function(api_out, host=HostObj){host._on_update(api_out);});

        for (index = 0; index < HostObj.members.length; index ++)
        {
            var member = HostObj.members[index];

            var node = document.createElement("LI");
			var textnode = document.createTextNode(member['member_name']);
			node.appendChild(textnode);
			document.getElementById("HostLeftAreaStudents").appendChild(node);
        }

    }

    async function showQuestions()
    {
        host_hash = getCookie('host_hash');

        HostObj.hash = host_hash;

        //console.log(HostObj.members);

        var myNode = document.getElementById("HostLowerUpperRightAreaQuestionList");
        while (myNode.firstChild) { myNode.removeChild(myNode.firstChild); }

        //HostObj.update(show_members=true, function(api_out, host=HostObj){host._on_update(api_out);});
        HostObj.get_questions(function(api_out, host=HostObj){host._on_get_questions(api_out);});

        for (index = 0; index < HostObj.questions.length; index ++)
        {
            var question = HostObj.questions[index];

            var node_li = document.createElement("LI");
            var node_a = document.createElement("A");

            console.log(question);

			var textnode = document.createTextNode(question['question_name']);
			node_a.appendChild(textnode);
            node_li.appendChild(node_a);

            node_a['href'] = '#';
            node_a.onclick = function(event, Question=question){update_focused_question(Question);};

			document.getElementById("HostLowerUpperRightAreaQuestionList").appendChild(node_li);
        }

    }

    function update_focused_question(question)
    {
        //console.log('in update_focused_question');
        //console.log(event);
        //console.log('HERE:');
        //console.log(question);

        // UPDATE CENTRE HERE

        var question_hash = question['question_hash'];
        var question_text = question['question_text'];

        HostObj.get_answers(question_hash, function(api_out, Question=question){on_get_answers(api_out)});

        var node_current_question_text = document.getElementById('current_question_text');
        var node_text = document.createTextNode(question_text);

        node_current_question_text.appendChild(node_text);
    }

    function on_get_answers(api_out)
    {
        //console.log('On get answers');
        //console.log(api_out);
        //console.log('');

        var answers = api_out['answers'];

        var ul = document.getElementById("current_question_answers_list");

        for (answer of answers)
        {
            var answer_answer = answer['answer'];
            var answer_member = answer['member'];

            console.log(answer);

            var member_name = answer_member['member_name'];
            var answer_tinymce = answer_answer['answer_tinymce'];

            var node_li = document.createElement("LI");
            var node_a = document.createElement('A');
            var node_text = document.createTextNode(member_name);

            node_a['href'] = '#';
            node_a.onclick = function(event, Answer=answer){on_show_answer(answer);};

            node_a.appendChild(node_text);
            node_li.appendChild(node_a);

            ul.appendChild(node_li);
        }

        console.log('');
    }

    function on_show_answer(answer)
    {
        console.log('on_show_answer');
        console.log(answer);
        console.log('');

        var answer_answer = answer['answer'];

        var answer_tinymce = answer_answer['answer_tinymce'];
        var answer_tinymce_decoded = _.unescape(answer_tinymce);

        var div_output = document.getElementById('current_answer_output');

        div_output.innerHTML = answer_tinymce_decoded;
    }

    function getCookie(name) {
      var value = "; " + document.cookie;
      var parts = value.split("; " + name + "=");
      if (parts.length == 2) return parts.pop().split(";").shift();
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function submitNewQuestion()
    {
        var text_area = document.getElementById('QuestionTextFieldTextArea');

        var question_text = text_area.value;

        console.log(question_text);

        var QuestionObj = new Question(null, host_hash, question_text, class_pin, ANSWER_MODE_TINYMCE, question_text, false);

        var callback = function (api_out)
        {
            //handle_question_created(class_name, host_name, api_out);
            alert('Question sent!');

            text_area.value = '';
        }

        QuestionObj.create(callback);
    }

    showMembers();
    showQuestions();

    setInterval(showMembers, 4 * 1000);
    setInterval(showQuestions, 4 * 1000);
</script>

</body>
</html>
<!--  comments                 REMEMBER CTRL ALT SHIFT R -->
<!-- https://html-online.com/articles/simple-popup-box/          http://kognition.ihostfull.com/   -->
