<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : GradientBlack
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20111121

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>PlayReq</title>
<link href='http://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header">
			<div id="logo">
				<h1>PlayReq</h1>
				<p>Social Playlisting<a href="http://www.freecsstemplates.org/"></a></p>
			</div>
		</div>
	</div>
	<!-- end #header -->
	<div id="menu">
		<ul>
			<li><a href="index.php">Homepage</a></li>
			<li><a href="library.php">Library</a></li>
			<li><a href="playlists.php">Playlists</a></li>
			<li><a href="events.php">Events</a></li>
			<li class="current_page_item"><a href="tools.php">Tools</a></li>
			<ri><a href="logout.php">Log&nbsp;&nbsp;Out</a></ri>
		</ul>
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="page-bgtop">
			<div align="center" id="page-bgbtm">
				<div align="center">
					<div align="center" class="post">
						<h2 class="title" align="center"><font  color="black"><a href="PlayReqHost.app.zip">Download PlayReqHost</a></font></h2>
						<h2 class="title" align="center"><font color="black"><a href="PlayReqImport.app.zip">Download PlayReqImport</a></font></h2>
						<?php
						echo '<h3 class="title"><font color="black">Your User ID is ';
						echo $_COOKIE["PlayReq"];
						echo '</font></h3>';
						?>
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
</div>
<div id="footer">
	<p>Copyright (c) 2012 PlayReq Inc.. All rights reserved.</p>
</div>
<!-- end #footer -->
</body>
</html>