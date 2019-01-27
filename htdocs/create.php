<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once $CFG->lib_dir . 'setup_lib.php';
require_once $CFG->lib_dir . 'core_lib.php';

log_request();
die_if_blocked();
die_if_maintainance();
//die_if_not_accepted_cookies();

//Code here.

 ?>
 <!DOCTYPE html>
 <head>
    <title>Pavilion Project B: Kognition</title>
 	<meta name="viewport" content="width=device-width, initial-scale=1">
   	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
   	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link href="<?php echo $CFG->url_assets_dir.'css/animate.css' ?>" rel="stylesheet"/>
 	<link href="<?php echo $CFG->url_assets_dir.'css/waypoints.css' ?>" rel="stylesheet"/>
    <link href="<?php echo $CFG->url_assets_dir.'css/style.css' ?>" rel="stylesheet" type="text/css">
    <script src="<?php echo $CFG->url_assets_dir.'js/jquery.waypoints.min.js' ?>" type="text/javascript"></script>
 	<script src="<?php echo $CFG->url_assets_dir.'js/waypoints.js' ?>" type="text/javascript"></script>
    <script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>" type="text/javascript"></script>
 </head>
 <body style="overflow:hidden"> <!-- temp fix -->
 	<!--<audio autoplay="autoplay">
 		<source src="assets/music/background.mp3" type="audio/mpeg">
 	</audio>-->
 	<div class="intro">
 		<div class="inner" id="primary-text">
 			<div class="content">
 				<!--Title section with os-animation-->
 				<section id="title" class="os-animation" data-os-animation="bounceInUp" data-os-animation-delay="1s">
 					<h1>Create a Class</h1> <!-- temp fix --> <!-- Unicode Cogs: ⚙⛭ -->
 				</section>

 				<!--Class pin input field again with animation-->
 				<section class="os-animation" data-os-animation="bounceInUp" data-os-animation-delay="1.2s">
 					<input id="inputSession" class="classId" placeholder="Class Name" oninput="javascript:filter_input()">
 			   </section>

 			   <!--This will be the link to join the session-->
 				<section class="os-animation" data-os-animation="bounceInUp" data-os-animation-delay="1.1s">
 					<a class="btn" id="button" href="javascript:submit_class_name()">Submit</a>
 				</section>
 				<!--Info at the bottom of the screen-->
 				<section class="text" style="bottom:40px"> <!-- temp fix -->
 					<center>The online classroom service,</center>
 					<center>Taking education into the future!</center>
 				</section>
 			</div>
 		</div>
 	</div>
    <!-- This script should probably be part of a library, this is all temporary -->
 	<script type="text/javascript">
        var glob_class_name = null;
        var glob_host_name = null;
        var glob_mode = 'CLASS_NAME';

        _focus_input();

        document.onkeydown = function (e)
        {
            e = e || window.event;
            switch (e.which || e.keyCode)
            {
                case 13 : //'<Enter>'
                    document.getElementById("button").click();
                    break;
            }
        }

        function _focus_input()
        {
            document.getElementById('inputSession').focus();
        }

        function filter_input()
        {
            //var input = document.getElementById('inputSession');

            //if (input.value.length > input.maxLength && glob_mode == 'CLASS')
            //{
            //    input.value = input.value.slice(0, input.maxLength);
            //}
            return;
        }

        function submit_class_name(class_name = null)
        {
            if (class_name == null)
            {
                class_name = document.getElementById('inputSession').value;
            }

            glob_class_name = class_name;

            changeInputToHostName();
            _focus_input();
        }

        function changeInputToHostName()
        {
            var name = document.getElementById("inputSession").value;
            var inputSession = document.getElementById("inputSession");
            var button = document.getElementById("button");

            inputSession.placeholder="Host Name";
 			button.innerHTML="Join Session";
 			inputSession.value="";
            button.href = "javascript:submit_host_name()";
            inputSession.oninput = null;
            inputSession.type = 'text';
            inputSession.maxLength = "30";

            glob_mode = 'HOST_NAME';
        }

        function submit_host_name(host_name = null, created = null)
        {
            if (host_name == null)
            {
                host_name = document.getElementById('inputSession').value;
            }

            glob_host_name = host_name;

            if (created == null)
            {
                create_host(glob_class_name, glob_host_name);
                return;
            }
        }

        function create_host(class_name, host_name)
        {
            var HostObj = new Host(host_name, class_name);

            var callback = function (api_out)
            {
                handle_created(class_name, host_name, api_out);
            }

            HostObj.create(callback);
        }

        function handle_created(class_name, host_name, created)
        {
            if (!created['success'])
            {
                alert('CreateError: ' + created['message']);
                return;
            }

            window.location.replace("host/landing");
        }
 	</script>
 </body>
 </html>
