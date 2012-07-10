<?php
	require 'sql_lib.php';

	$TaskID = preg_replace("/[^0-9]/", '', $_POST['TaskID']);

	//mysql_query("DELETE FROM Tasks WHERE TaskID = '$TaskID';") or die('Error, delete query failed');

	$UserID = $_COOKIE["UserID"];

	$row = mysql_fetch_row(mysql_query("SELECT Title FROM Tasks WHERE TaskID='$TaskID';"));

	if ($row[0]=="") { }
	elseif ($row[0]==" ") {	}
	elseif ($row[0]=="  ") { }
	elseif ($row[0]=="   ") { }
	else {
		// if we get here, the field is not empty and should be archived
		$row = mysql_fetch_row(mysql_query("SELECT ListID FROM Lists WHERE UserID = '$UserID' AND Lists.Title='Archived';"));
		$ArchivedID = $row[0];
		mysql_query("UPDATE Tasks SET ListID = '$ArchivedID', `Order` = '0', `Date` = NOW() WHERE TaskID = '$TaskID';");
	}
?>