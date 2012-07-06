<?php
	require 'sql_lib.php';

	$ListID = preg_replace("/[^0-9]/", '', $_POST['ListID']);

	$result = mysql_query("SELECT * FROM Tasks WHERE Title = '' AND ListID = '$ListID';");

	if (mysql_num_rows($result) > 0)
	{
		// do nothing, there is already a blank task in the user's list
	}
	else
	{
		mysql_query("INSERT INTO Tasks(ListID) VALUES ('$ListID');") or die('Error, insert query failed');
	}

?>