<?php

require 'sql_lib.php';

$list=(int)$_GET['listid'];

$task=(int)$_GET['taskid'];

$text=htmlspecialchars($_GET['text']);
$text=stripslashes($text);

update_task($list, $task, $text, $_COOKIE["UserID"]);

//return json_encode(array("status" => true, "added" => true));

?>