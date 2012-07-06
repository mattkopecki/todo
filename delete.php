<?php
	require 'sql_lib.php';

	$ListID = preg_replace("/[^0-9]/", '', $_POST['ListID']);	// not used right now, since the page reloads
	$TaskID = preg_replace("/[^0-9]/", '', $_POST['TaskID']);

	mysql_query("DELETE FROM Tasks WHERE TaskID = '$TaskID';") or die('Error, delete query failed');


?>