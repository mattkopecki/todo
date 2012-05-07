<?php
	require "sql_lib.php";

	// iPhone Version:
	if(strpos($_SERVER['HTTP_USER_AGENT'],'iPhone') !== FALSE || strpos($_SERVER['HTTP_USER_AGENT'],'iPod') !== FALSE)
	{
 		 header('Location: http://kopecki1.projects.cs.illinois.edu/mobile.php');
 		 exit();
	}
		// Android Version:
		if(strpos($_SERVER['HTTP_USER_AGENT'],'Android') !== FALSE)
	{
 		 header('Location: http://kopecki1.projects.cs.illinois.edu/mobile.php');
  		exit();
	}

	if (isset($_POST["userid"]) and isset($_POST["password"]))
	{
		$result = logon_user($_POST["userid"], $_POST["password"]);
		$logged_in = FALSE;
		if($result)
		{
			$logged_in = TRUE;
			$UserID = $result["UserID"];
			$UserName = $result["UserName"];
			$email = $result["email"];
			setcookie("UserID", $UserID);
			header('Location: todo.php');
		}
		else
		{
			header('Location: login.php?success=no');
		}
	}
	else if($_COOKIE["UserID"])
	{
		$logged_in = TRUE;
		$UserID = $_COOKIE["UserID"];
	}
	else
	{
		$UserID = false;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>To Do</title>
<link href='http://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header">
			<div id="logo">
				<h1>To Do</h1>
				<p>My To Do List</p>
			</div>
		</div>
	</div>
	<!-- end #header -->
	<div id="menu">
		<ul>
			<li class="current_page_item"><a href="#">Homepage</a></li>

            <?php
			echo $logged_in ? "<li><a href=\"todo.php\">To Do</a></li>" : "";
			echo $logged_in ? "<li><a href=\"tools.php\">Tools</a></li>" : "";
			?>

			<?php if ($logged_in) {echo "<ri><a href=\"logout.php\">Log Out</a></ri>";} else {echo "<ri><a href=\"login.php\">Log&nbsp;&nbsp;In</a></ri>";} ?>
		</ul>
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="content">
					<div class="post">
						<h2 class="title"><font color="black">Welcome to your To Do List</font></h2>
					</div>
				</div>
				<!-- end #content -->
				<div id="sidebar">
				</div>
				<!-- end #sidebar -->
				<div style="clear: both;">&nbsp;</div>
			</div>
		</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer">
	<p>Copyright (c) 2012 Matt Kopecki. All rights reserved.</p>
</div>
<!-- end #footer -->
</body>
</html>