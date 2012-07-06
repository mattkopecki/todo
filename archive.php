<?php
	require 'sql_lib.php';

	$TaskID = $_POST['TaskID'];
	$UserID = $_COOKIE["UserID"];

	//$ArchivedID = mysql_query("SELECT ListID FROM Lists WHERE UserID = '$UserID' AND Lists.Title='Archived';")
	//				   or die('Error, search of archived list query failed');
$ArchivedID = 4;
	mysql_query("UPDATE Tasks SET ListID = '$ArchivedID' WHERE TaskID = '$TaskID';");

?>