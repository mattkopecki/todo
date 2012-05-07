<?php
	require "sql_lib.php";

	if (isset($_POST["userid"]) and isset($_POST["password"]) and isset($_POST["email"]))
	{
		$result = user_exists($_POST["userid"]);

		if($result)
		{
			header('Location: login.php?failure=yes');
		}
		else
		{
			create_user($_POST["userid"], $_POST["password"], $_POST["email"]);
			$result = logon_user($_POST["userid"], $_POST["password"]);
			$logged_in = TRUE;
			$UserID = $result["UserID"];
			$UserName = $result["UserName"];
			$email = $result["email"];
			setcookie("UserID", $UserID);
			header('Location: todo.php');
		}
	}
?>
