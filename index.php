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
	<h1>To Do</h1>

	<div id="menu">
		<ul>
			<li class="current_page_item"><a href="#">Homepage</a></li>

            <?php
			echo $logged_in ? "<li><a href=\"todo.php\">To Do</a></li>" : "";
			echo $logged_in ? "<li><a href=\"tools.php\">Tools</a></li>" : "";
			?>

			<?php if ($logged_in) {echo "<ri><a href=\"logout.php\">Log Out</a></ri>";} else {echo "<ri><a href=\"login.php\">Log In</a></ri>";} ?>
		</ul>
	</div><!-- end #menu -->
<div id="content">

<div id="footer">
	<p>Copyright (c) 2012 Matt Kopecki. All rights reserved.</p>
</div> <!-- end #footer -->

</div> <!-- end #content -->

</body>
</html>