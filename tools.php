<?php
	require 'sql_lib.php';

    //ini_set('display_errors', 'On');
    //error_reporting(E_ALL | E_STRICT);

    if(isset($_COOKIE["UserID"]))
    {
        $logged_in = TRUE;
        $UserID = $_COOKIE["UserID"];
    }
    else
    {
        $UserID = false;
        header('Location: login.php');
    }

    check_for_captio();
?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>To Do</title>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,800">
<link rel="stylesheet" type="text/css" media="screen" href="style.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js" type="text/javascript"></script>
<script src="http://jquery-ui.googlecode.com/svn/tags/latest/external/jquery.bgiframe-2.1.2.js" type="text/javascript"></script>
<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>
<script src="listactions.js" type="text/javascript"></script>
</head>

<body>
<div id="wrapper">

	<div id="menu">
        <a href="#" style="color:#C0C0C0;" onclick="javascript:showElement('left-menu')"><span>Menu</span></a>
        <ul id="left-menu" class="left-menu" style="display:none;">
			<li><a href="index.php">Homepage</a></li>
			<li><a href="todo.php">To Do</a></li>
			<li class="current_page_item"><a href="tools.php">Archived</a></li>
			<ri>
                <a href="action.php">Gmail Login</a>
                <a href="logout.php">Log Out</a>
            </ri>
		</ul>
	</div> <!-- end #menu -->

	<div id="page">

<?php
	$result = my_archives($_COOKIE["UserID"]);
	$ListID = $result["ListID"];

    echo '<h1>Archived</h1>';

	echo '<ul>';

   	$contents = list_contents($ListID);
	foreach ($contents as $task)
	{
		$Title = $task["Title"];
		echo '<li align="left"><h4>'.$Title.'</h4></li>';
	}

    echo '</ul>';
?>


	</div>  <!-- end #page -->

</div>  <!-- end #wrapper -->

</body>
</html>