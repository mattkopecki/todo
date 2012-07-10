<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Log In</title>
<link href='http://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>

	<h1>To Do</h1>

	<div id="menu">
		<ul>
			<li><a href="index.php">Homepage</a></li>
			<ri class="current_page_item"><a href="#">Log In</a></ri>
		</ul>
	</div> <!-- end #menu -->

<div id="content">

	<h2>Please enter your log-in information</h2>
	<form action="index.php" method="post">
        <table border="1" cellspacing="3" cellpadding="3">
            <tr>
                <td>User ID:</td>
                <td><input type="text" size=40 name="userid" /></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" size=40 name="password" /></td>
            </tr>
        </table>
        <input type="submit" value="Log On" />
        <input type="reset" value="Clear" />
    </form>

    <?php
	  	if ($_GET["success"])
		{
			echo "<font color=red>Your login information was incorrect; please try again.</font>";
		}
	?>

	<div style="clear: both;">&nbsp;</div>

	<h2>Sign Up</h2>
	<form action="newuser.php" method="post">
	<table border="1" cellspacing="3" cellpadding="3">
    	<tr>
        	<td>User ID:</td>
        	<td><input type="text" size=40 name="userid" /></td>
        </tr>
		<tr>
        	<td>Email:</td>
        	<td><input type="text" size=40 name="email" /></td>
        </tr>
      	<tr>
        	<td>Password:</td>
        	<td><input type="password" size=40 name="password" /></td>
      	</tr>
    </table>
    <input type="submit" value="Create" />
    <input type="reset" value="Clear" />
  	</form>
  	<?php
  		if ($_GET["failure"])
		{
			echo "<font color=red>Your username is already taken; please try again.</font>";
		}
  	?>


	<div id="footer">
		<p>Copyright (c) 2012 Matt Kopecki. All rights reserved.</p>
	</div> <!-- end #footer -->

</div> <!-- end #wrapper -->
</body>
</html>
