<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/config_lib.php';
require_once $CFG->lib_dir . 'setup_lib.php';
require_once $CFG->lib_dir . 'core_lib.php';

log_request();
die_if_blocked();
die_if_maintainance();

//$_SESSION[ACCEPTED_COOKIES] = true; //obviously remove me and do legit
//die_if_not_accepted_cookies();

 ?>
 <!DOCTYPE html>
 <head>
    <title>Kognition - Home</title>
 	<meta name="viewport" content="width=device-width, initial-scale=1">
   	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
   	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link href="<?php echo $CFG->url_assets_dir.'css/animate.css' ?>" rel="stylesheet"/>
 	<link href="<?php echo $CFG->url_assets_dir.'css/waypoints.css' ?>" rel="stylesheet"/>
    <link href="<?php echo $CFG->url_assets_dir.'css/style.css' ?>" rel="stylesheet" type="text/css">
    <script src="<?php echo $CFG->url_assets_dir.'js/jquery.waypoints.min.js' ?>" type="text/javascript"></script>
 	<script src="<?php echo $CFG->url_assets_dir.'js/waypoints.js' ?>" type="text/javascript"></script>
    <script src="<?php echo $CFG->url_assets_dir.'js/api.js' ?>" type="text/javascript"></script>

    <link rel="apple-touch-icon" sizes="57x57" href="/assets/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <meta name="keywords" content="Kognition, Learn, Teach, Student, Classroom, Virtual, School, College">
    <meta name="description" content="Create a virtual disposable classroom">
    <meta name="author" content="Kognition">
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
 					<h1>K<!--<p style="display: inline; font-size: 65%">⛭</p>--><img src="https://www.cobry.co.uk/wp-content/uploads/2018/07/Cog.png" style="
                                    height: 50px;
                                    width: 50px;
                                    display: inline;">gnition</h1> <!-- temp fix --> <!-- Unicode Cogs: ⚙⛭ -->
 				</section>

 				<!--Class pin input field again with animation-->
 				<section class="os-animation" data-os-animation="bounceInUp" data-os-animation-delay="1.2s">
 					<input id="inputSession" class="classId" placeholder="Class Pin" oninput="javascript:filter_input()" type = "number" maxlength = "6">
 			   </section>

 			   <!--This will be the link to join the session-->
 				<section class="os-animation" data-os-animation="bounceInUp" data-os-animation-delay="1.1s">
 					<a class="btn" id="button" href="javascript:submit_class()">Submit</a>
 				</section>
 				<!--Info at the bottom of the screen-->
 				<section class="text" style="bottom:40px"> <!-- temp fix -->
 					<center>The online classroom service,</center>
 					<center>Taking education into the future!</center>
                    <!-- <ToImplement>: There needs to be a link to take teachers to a different page -->
                    <br> <!-- change me -->
                    <br>
                    <center>
                        <a href="create" class="text" style="text-decoration: none; font-size: 95%"> <!-- temp fix -->
                            <i>Not a Student?</i>
                        </a>
                    </center>
                    <!-- </ToImplement> -->
 				</section>
 			</div>
 		</div>
 	</div>
    <script type="text/javascript">
        var glob_class_pin = null;
        var glob_name = null;
        var glob_mode = 'CLASS';

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
            var input = document.getElementById('inputSession');

            if (input.value.length > input.maxLength && glob_mode == 'CLASS')
            {
                input.value = input.value.slice(0, input.maxLength);
            }
        }

        function submit_class(class_pin = null, pinged = null)
        {
            if (class_pin == null)
            {
                class_pin = parseInt(document.getElementById('inputSession').value);
            }

            if (class_pin.toString().length != 6)
            {
                alert('InvalidLengthError: Must be 6 digits long');

                return;
            }

            glob_class_pin = class_pin;

            if (pinged == null)
            {
                ping_class(class_pin);
                return;
            }

            if (!pinged['success'])
            {
                alert('PingError: ' + pinged['message']);
                return;

            if (!pinged['class']['exists'])
            {
                alert('PingError: That class doesn\'t exist');
                return;
            }

            }
            if (pinged['class']['closed'])
            {
                alert('PingError: That class is closed');
                return;
            }

            changeInputToName();
            _focus_input();
        }

        function ping_class(class_pin)
        {
            var ClassObj = new Class(null, class_pin, null);

            var callback = function (api_out)
            {
                submit_class(class_pin, api_out);
            }

            ClassObj.ping_class(callback);
        }

        function changeInputToName()
        {
            document.getElementById("inputSession").placeholder="Name";
 			document.getElementById("button").innerHTML="Join Session";
 			var name = document.getElementById("inputSession").value;
 			document.getElementById("inputSession").value="";
            //document.getElementById("button").onclick = function() {submit_name();}
            document.getElementById("button").href = "javascript:submit_name()";
            document.getElementById("inputSession").oninput = null;
            document.getElementById("inputSession").type = 'text';
            document.getElementById("inputSession").maxLength = "30";
            glob_mode = 'NAME';
        }

        function submit_name(name = null, created = null)
        {
            if (name == null)
            {
                name = document.getElementById('inputSession').value;
            }

            glob_name = name;

            if (created == null)
            {
                create_member(glob_class_pin, glob_name);
                return;
            }
        }

        function create_member(class_pin, name)
        {
            var MemberObj = new Member(name, class_pin);

            var callback = function (api_out)
            {
                handle_created(class_pin, name, api_out);
            }

            MemberObj.create(callback);
        }

        function handle_created(class_pin, name, created)
        {
            if (!created['success'])
            {
                alert('CreateError: ' + created['message']);
                return;
            }

            window.location.replace("member/");
        }
 	</script>
    <!-- This script should probably be part of a library, this is all temporary -->
 	<!--<script type="text/javascript">
        var glob_class_pin = null;
        var glob_name = null;
        var glob_mode = 'CLASS';

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
            var input = document.getElementById('inputSession');

            if (input.value.length > input.maxLength && glob_mode == 'CLASS')
            {
                input.value = input.value.slice(0, input.maxLength);
            }
        }

        function submit_class(class_pin = null, pinged = null)
        {
            if (class_pin == null)
            {
                class_pin = parseInt(document.getElementById('inputSession').value);
            }

            if (class_pin.toString().length != 6)
            {
                alert('InvalidLengthError: Must be 6 digits long');

                return;
            }

            glob_class_pin = class_pin;

            if (pinged == null)
            {
                ping_class(class_pin);
                return;
            }

            if (!pinged['success'])
            {
                alert('PingError: ' + pinged['message']);
                return;

            if (!pinged['class']['exists'])
            {
                alert('PingError: That class doesn\'t exist');
                return;
            }

            }
            if (pinged['class']['closed'])
            {
                alert('PingError: That class is closed');
                return;
            }

            changeInputToName();
            _focus_input();
        }

        function ping_class(class_pin)
        {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    submit_class(class_pin, JSON.parse(this.responseText));
                }
            };

            //xhttp.open("GET", "api/server/ping_class.php?class_pin=" + class_pin + "&no_cache=" + Math.random(), true);
            xhttp.open("GET", "api/class/ping?class_pin=" + class_pin + "&no_cache=" + Math.random(), true);
            xhttp.send();
        }

        function changeInputToName()
        {
            document.getElementById("inputSession").placeholder="Name";
 			document.getElementById("button").innerHTML="Join Session";
 			var name = document.getElementById("inputSession").value;
 			document.getElementById("inputSession").value="";
            //document.getElementById("button").onclick = function() {submit_name();}
            document.getElementById("button").href = "javascript:submit_name()";
            document.getElementById("inputSession").oninput = null;
            document.getElementById("inputSession").type = 'text';
            document.getElementById("inputSession").maxLength = "30";
            glob_mode = 'NAME';
        }

        function submit_name(name = null, created = null)
        {
            if (name == null)
            {
                name = document.getElementById('inputSession').value;
            }

            glob_name = name;

            if (created == null)
            {
                create_member(glob_class_pin, glob_name);
                return;
            }
        }

        function create_member(class_pin, name)
        {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    handle_created(class_pin, name, JSON.parse(this.responseText));
                }
            };

            xhttp.open("GET", "api/member/create?class_pin=" + class_pin + "&member_name=" + name + "&no_cache=" + Math.random(), true);
            xhttp.send();
        }

        function handle_created(class_pin, name, created)
        {
            if (!created['success'])
            {
                alert('CreateError: ' + created['message']);
                return;
            }

            window.location.replace("member/");
        }
 	</script>-->
    <!--<script>
        var glob_class_pin = null;
        var glob_name = null;
        var glob_mode = 'CLASS';

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
            var input = document.getElementById('inputSession');

            if (input.value.length > input.maxLength && glob_mode == 'CLASS')
            {
                input.value = input.value.slice(0, input.maxLength);
            }
        }

        function submit_class(class_pin = null, pinged = null)
        {
            if (class_pin == null)
            {
                class_pin = parseInt(document.getElementById('inputSession').value);
            }

            if (class_pin.toString().length != 6)
            {
                alert('InvalidLengthError: Class pin must be 6 digits long');

                return;
            }

            glob_class_pin = class_pin;

            if (pinged == null)
            {
                return submit_class(class_pin, ping_class(class_pin));
            }

            if (!pinged['success'])
            {
                alert('PingError: ' + pinged['message']);
                return;

            if (!pinged['class']['exists'])
            {
                alert('PingError: That class doesn\'t exist');
                return;
            }

            }
            if (pinged['class']['closed'])
            {
                alert('PingError: That class is closed');
                return;
            }

            changeInputToName();
            _focus_input();
        }

        function changeInputToName()
        {
            document.getElementById("inputSession").placeholder="Name";
 			document.getElementById("button").innerHTML="Join Session";
 			var name = document.getElementById("inputSession").value;
 			document.getElementById("inputSession").value="";
            //document.getElementById("button").onclick = function() {submit_name();}
            document.getElementById("button").href = "javascript:submit_name()";
            document.getElementById("inputSession").oninput = null;
            document.getElementById("inputSession").type = 'text';
            document.getElementById("inputSession").maxLength = "30";
            glob_mode = 'NAME';
        }

        function submit_name(name = null, created = null)
        {
            if (name == null)
            {
                name = document.getElementById('inputSession').value;
            }

            glob_name = name;

            if (created == null)
            {
                return submit_name(name, create_member(glob_class_pin, glob_name));
            }
        }

        function create_member(class_pin, name, created=false)
        {
            var member = new Member(name, class_pin);
            member.create();

            if (!member.exists)
            {
                alert('CreateError: Member creation failed');
                return;
            }

            window.location.replace("member/");
        }

        function ping_class(class_pin, pinged = null)
        {
            if (pinged != null)
            {
                return pinged
            }

            var class = new Class(null, class_pin, null);


            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function()
            {
                if (this.readyState == 4 && this.status == 200)
                {
                    submit_class(class_pin, JSON.parse(this.responseText));
                }
            };

            //xhttp.open("GET", "api/server/ping_class.php?class_pin=" + class_pin + "&no_cache=" + Math.random(), true);
            xhttp.open("GET", "api/class/ping?class_pin=" + class_pin + "&no_cache=" + Math.random(), true);
            xhttp.send();
        }
    </script>-->
    <!--Start Cookie Script--> <!--<script type="text/javascript" charset="UTF-8" src="http://chs03.cookie-script.com/s/fdc450abe426d36ecade676de2ee74c7.js"></script>--> <!--End Cookie Script-->
    <script type="text/javascript" charset="UTF-8" src="<?php echo $CFG->url_assets_dir.'js/custom-cookie-script-tray.js' ?>"></script>
 </body>
 </html>
