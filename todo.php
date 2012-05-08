<?php
	require 'sql_lib.php';

    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);

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
        header('Location: login.php');
    }

    check_for_captio();
?>


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>To Do</title>
<link href='http://fonts.googleapis.com/css?family=proxima-nova' rel='stylesheet' type='text/css'>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
$(document).ready(function(){
    $('input').keyup(function(e){
        if(e.which==40)      // 40 is down arrow
            $(this).closest('li').next().find('input').focus();
        else if(e.which==38) // 38 is up arrow
            $(this).closest('li').prev().find('input').focus();
    });
});

</script>
<script language="javascript">

function addRow(listID) {
    var list = document.getElementById(listID);
    var newLI = document.createElement("li");

    var element = document.createElement("input");
    element.type = "text";
    element.size = "80";
    element.name = "task[]";

    newLI.appendChild(element);
    list.insertBefore(newLI, list.lastChild.nextSibling);
    element.focus();
}

function deleteRow(listID,itemID) {
  try {
    var list = document.getElementById(listID);
    var item = document.getElementById(itemID);
    var firstRow = list.firstChild;

    function shouldDelete(listID, itemID, row) {
        if (row == document.getElementById(listID).lastChild.nextSibling)
        {}
        else if (row.childNodes[2].id == itemID){
            row.parentNode.removeChild(row);
        }
        else {
            shouldDelete(listID, itemID, row.nextSibling);
        }
    }

    shouldDelete(listID, itemID, firstRow);
  }
  catch(e) {
    alert(e);
  }
}

function enterKeyPress(e,id) {
    // look for window.event in case event isn't passed in
    if (typeof e == 'undefined' && window.event) {
        e = window.event;
    }
    if (e.keyCode == 13) {
        document.getElementById('add'+id).click();
    }
}

function saveTask(itemId,listId,textValue) {
        var mygetrequest=new XMLHttpRequest()
        mygetrequest.onreadystatechange=function(){
            if (mygetrequest.readyState==4){
                if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
                }
                else{
                    alert("An error has occured making the request")
                }
            }
        }
        var taskvalue=encodeURIComponent(itemId)
        var listvalue=encodeURIComponent(listId)
        var textvalue=encodeURIComponent(textValue)
        mygetrequest.open("GET", "additem.php?taskid="+taskvalue+"&listid="+listvalue+"&text="+textvalue, true)
        mygetrequest.send(null)
}
</script>
</head>
<body>
<div id="wrapper">

	<div id="menu">
		<ul>
			<li><a href="index.php">Homepage</a></li>
			<li class="current_page_item"><a href="todo.php">To Do</a></li>
			<li><a href="tools.php">Tools</a></li>
			<ri><a href="logout.php">Log Out</a></ri>
		</ul>
	</div>
	<!-- end #menu -->

<div id="page">

<div class="column leftcol">
<?php
	$results = my_lists($_COOKIE["UserID"]);
	if (count($results) == 0)
	{
		echo "<p>You have no Lists.</p>";
	}
	else
	{
	    foreach($results as $list)
	    {
	    	$ListTitle = $list["Title"];
	    	$ListID = $list["ListID"];
	    	echo '<div style="clear:both"><h1>'.$ListTitle.'</h1>';

	    	$contents = list_contents($ListID);

		echo '<form action="todo.php" method="POST">';
		echo '<div id="list'.$ListID.'" align="left"  border="0" style="clear:both">';
        echo '<ul>';

		foreach ($contents as $task)
		{
			$Title = $task["Title"];
			$TaskID = $task["TaskID"];

			echo '<li align="left">
                <div style="white-space:nowrap;">
                    <input type="text" name="task[]" id="'.$TaskID.'" value="'.$Title.'" size="60" onkeypress="enterKeyPress(event,'.$ListID.');" onblur="saveTask('.$TaskID.','.$ListID.',this.value);">
                    <input type="button" id="delete'.$ListID.'t'.$TaskID.'" value="X" onclick="deleteRow(\'list'.$ListID.'\', \'delete'.$ListID.'t'.$TaskID.'\')" style="display:block; float:right;"/>
                </div>
                </li>';

		}
        echo '</ul>';
		echo '</div>';
		echo '</form>';
		echo '<input type="button" id="add'.$ListID.'" value="Add Row" onclick="addRow(\'list'.$ListID.'\')" style="visibility:hidden"/>  </div>';
	    }
	}
?>
</div>
<!-- end leftcol -->

<div class="column rightcol">
<h1>TO DO LIST</h1>
<?php
	$results = my_todo_list($_COOKIE["UserID"]);
	if (count($results) == 0)
	{
		echo "<p>You have no Lists.</p>";
	}
	else
	{
	    foreach($results as $list)
	    {
	    	$ListTitle = $list["Title"];
	    	$ListID = $list["ListID"];

	    	$contents = list_contents($ListID);

		echo '<form action="todo.php" method="POST">';
		echo '<div id="list'.$ListID.'" align="left" border="0"><ul>';

		foreach ($contents as $task)
		{
			$Title = $task["Title"];
			$TaskID = $task["TaskID"];

			echo '<li align="left">
                <div style="white-space:nowrap;">
                    <input type="text" name="task[]" id="'.$TaskID.'" value="'.$Title.'" size="60" onkeypress="enterKeyPress(event,'.$ListID.');" onblur="saveTask('.$TaskID.','.$ListID.',this.value);">
                    <input type="button" id="delete'.$ListID.'t'.$TaskID.'" value="X" onclick="deleteRow(\'list'.$ListID.'\', \'delete'.$ListID.'t'.$TaskID.'\')" style="display:block; float:right;"/>
                </div>
                </li>';

        }
        echo '</ul></div>';
        echo '</form>';
        echo '<input type="button" id="add'.$ListID.'" value="Add Row" onclick="addRow(\'list'.$ListID.'\')" style="visibility:hidden"/>';
	    }
	}
?>
</div>
<!-- end rightcol -->

<div class="bottom">
<?php

    $ServerName = "{imap.gmail.com:993/imap/ssl}INBOX";
    $Username = "mattkopecki@gmail.com";
    $Password = "Iceman282";

    $mailbox = imap_open($ServerName, $Username, $Password) or die("Could not open Mailbox");

    if ($hdr = imap_check($mailbox))
    {
        echo "<h1>" . $hdr->Nmsgs . " MESSAGES</h1>\n\n<br>";
    	$msgCount = $hdr->Nmsgs;
    }
    else
    {
    	echo "failed";
    }
    $overview = imap_fetch_overview($mailbox,"1:$msgCount",0);
    $size=sizeof($overview);

    echo '<dl>';

    for($i=$size-1; $i>=0; $i--)
    {
    	$val = $overview[$i];
        $sequence = $val->msgno;
    	$from = $val->from;
    	$date = $val->date;
        $subject = $val->subject;
    	$seen = $val->seen;

        $from = preg_replace("/\"/","",$from);

    	list($dayName,$day,$month,$year,$time) = preg_split("/ /",$date);
        $time = substr($time,0,5);
        $date = $year."-".$month."-".$day;


        // convert the date display to a more natural format
        if (date('Y-m-d') == date('Y-m-d', strtotime((string)$date)))
        {
            $date = $time;
        }
        else if ($date < strtotime('-7 days'))
        {
            if (strcasecmp($dayName, "Mon,")==0) $date = "Monday";
            else if (strcasecmp($dayName, "Tue,")==0) $date = "Tuesday";
            else if (strcasecmp($dayName, "Wed,")==0) $date = "Wednesday";
            else if (strcasecmp($dayName, "Thu,")==0) $date = "Thursday";
            else if (strcasecmp($dayName, "Fri,")==0) $date = "Friday";
            else if (strcasecmp($dayName, "Sat,")==0) $date = "Saturday";
            else if (strcasecmp($dayName, "Sun,")==0) $date = "Sunday";
        }
        else
        {
            $date = $month."-".$day."-".$year;
        }

        // check for any emails from Captio
        preg_match('/\b(capt\w+)\b/', $from, $match);
        if ($match)
        {
            $messageBody = imap_fetchbody($mailbox,$sequence,1);
            $success = parse_from_captio($subject, $messageBody, $_COOKIE["UserID"]);
            if ($success)
            {
                imap_mail_move($mailbox, $sequence, '[Gmail]/All Mail');
                imap_expunge($mailbox);
                continue;  // process the next message and don't add this one to the Messages box
            }
            else {
                continue;
            }
        }

        if (strlen($subject) > 60)
        {
        	$subject = substr($subject,0,59) ."...";
        }

        //$body = imap_fetchbody($mailbox,$sequence,1);	// this is slow. I need some way to only get the body on demand
        $body = 'this text makes up the body of the message';

        if (strlen($body) > 60)
        {
        	$body = substr($body,0,59) ."...";
        }

    	echo '<dt>
                <div style="white-space:nowrap; display:block;">
                <span class="from">'.$from.'</span>
                <span class="maildate">'.$date.'</span>
                </div>
            </dt>
            <dd>
                <div class="subject">'.$subject.'</div>
                <div class="mailbody">'.$body.'</div>
            </dd>';
    }

    echo '</dl>';

    imap_close($mailbox);
?>
</div>
<!-- end bottom -->

</div>
<!-- end #page -->

</div>
<!-- end #wrapper -->

</body>
</html>