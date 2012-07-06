<?php
	require("sql_lib.php");

	$action = mysql_real_escape_string($_POST['action']);
	$updateRecordsArray = $_POST['recordsArray'];

	if ($action == "updateRecordsListings")
	{

		$listingCounter = 1;
		foreach ($updateRecordsArray as $TaskID)
		{

			$query = "UPDATE Tasks SET Order = " . $listingCounter . " WHERE TaskID = " . $TaskID;
			mysql_query($query) or die('Error, insert query failed');
			$listingCounter = $listingCounter + 1;
		}

		echo '<pre>';
		print_r($updateRecordsArray);	// this text is displayed in the resp div at the top of the page in todo.php
		echo '</pre>';
	}

?>