<?php
	require 'sql_lib.php';

    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_STRICT);

    if(isset($_COOKIE["UserID"]))
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
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,800">
<link rel="stylesheet" type="text/css" media="screen" href="style.css" />
<link rel='stylesheet' href='popbox.css' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js" type="text/javascript"></script>
<script src="http://jquery-ui.googlecode.com/svn/tags/latest/external/jquery.bgiframe-2.1.2.js" type="text/javascript"></script>
<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js" type="text/javascript"></script>
<script src="listactions.js" type="text/javascript"></script>
<script type='text/javascript' charset='utf-8' src='popbox.js'></script>
</head>

<body>
<div id="wrapper">

	<div id="menu">
    <a href="#" onclick="javascript:showElement('left-menu')"><span>&#x2759;&nbsp;&nbsp;Menu</span></a>
		<ul id="left-menu" class="left-menu" style="display:none;">
			<li><a href="index.php">Homepage</a></li>
			<li class="current_page_item"><a href="todo.php">To Do</a></li>
			<li><a href="tools.php">Archived</a></li>
			<li><a href="action.php">Gmail Login</a></li>
            <li><a href="logout.php">Log Out</a></li>
		</ul>
	</div> <!-- end #menu -->

<div id="page">

<div class="column leftcol">
<?php
	$results = my_lists($_COOKIE["UserID"]);
	if (count($results) == 0) { echo "<p>You have no Lists.</p>"; }
	else
	{
	    foreach($results as $list)
	    {
	    	$ListTitle = $list["Title"];
	    	$ListID = $list["ListID"];
	    	echo '<div style="margin-top:20px; clear:both;"><h2 style="display:inline;">'.$ListTitle.'</h2><input class="grey" type="button" id="add'.$ListID.'" value="&#x271A;" style="visibility:hidden; float:right; margin-top:13px; margin-right:16px;" onclick="addRow(\'list'.$ListID.'\')"/>';

	    	$contents = list_contents($ListID);

		echo '<form action="todo.php" method="POST">';
		echo '<div id="list'.$ListID.'" align="left"  border="0" style="clear:both">';
        echo '<ul id="sortable1" class="connectedSortable">';

		foreach ($contents as $task)
		{
			$Title = $task["Title"];
			$TaskID = $task["TaskID"];

			echo '<li id="TaskID_'.$TaskID.'" align="left">
                <div style="white-space:nowrap;">
                    :&nbsp;:&nbsp;
                    <input class="grey" type="text" id="'.$TaskID.'" value="'.$Title.'" size="44" onkeypress="enterKeyPress(event,'.$ListID.');" onblur="saveTask('.$TaskID.','.$ListID.',this.value);">
                    <input class="grey" type="button" id="archive'.$TaskID.'" value="&#x2714;" onclick="archive(\''.$TaskID.'\')" style="display:block; float:right;"/>
                    <input class="grey" type="button" id="delete'.$TaskID.'" value="&#x2716;" onclick="deleteRow(\'list'.$ListID.'\', \'t'.$TaskID.'\')" style="display:block; float:right;"/>
                </div>
                </li>';

		}
        echo '</ul>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
	    }
	}
?>
</div>  <!-- end leftcol -->

<div class="column rightcol">
<?php
	$results = my_todo_list($_COOKIE["UserID"]);
	if (count($results) == 0) { echo "<p>You have no Lists.</p>"; }
	else
	{
	    foreach($results as $list)
	    {
	    	$ListTitle = $list["Title"];
	    	$ListID = $list["ListID"];

	    	$contents = list_contents($ListID);
            echo '<div><h1 style="display:inline">TO DO LIST</h1><input type="button" id="add'.$ListID.'" value="&#x271A;" style="visibility:hidden; float:right; margin-top:13px; margin-right:16px;" onclick="addRow(\'list'.$ListID.'\')"/></div>';

		echo '<form action="todo.php" method="POST">';
		echo '<div id="list'.$ListID.'" align="left" border="0"><ul id="sortable2" class="connectedSortable">';

		foreach ($contents as $task)
		{
			$Title = $task["Title"];
			$TaskID = $task["TaskID"];

			echo '<li id="TaskID_'.$TaskID.'" align="left">
                <div style="white-space:nowrap;">
                    :&nbsp;:&nbsp;
                    <input type="text" id="TaskID_'.$TaskID.'" value="'.$Title.'" size="47" onkeypress="enterKeyPress(event,'.$ListID.');" onblur="saveTask('.$TaskID.','.$ListID.',this.value);">
                    <input type="button" id="archive_'.$TaskID.'" value="&#x2714;" onclick="archive(\''.$TaskID.'\')" style="display:block; float:right;"/>
                    <input type="button" id="delete_'.$TaskID.'" value="&#x2716;" onclick="deleteRow(\'list'.$ListID.'\', \'t'.$TaskID.'\')" style="display:block; float:right;"/>
                </div>
                </li>';

        }
        echo '</ul></div>';
        echo '</form>';
	    }
	}
?>
</div>  <!-- end rightcol -->

<div class="bottom">
<?php
    $ServerName = "{imap.gmail.com:993/imap/ssl}INBOX";
    $Username = "mattkopecki@gmail.com";
    $Password = "ymadqdjghxeakbhq";

    $mailbox = imap_open($ServerName, $Username, $Password) or die("Could not open Mailbox");

    if ($header = imap_check($mailbox))
    {
        $msgCount = $header->Nmsgs;
        if ($msgCount == 0)
        {
            echo "<h2> INBOX ZERO </h2>";
        }
        else
        {
            // the gmail inbox has messages
            if ($msgCount == 1) { echo "<h2>1 MESSAGE</h2>"; }
            else { echo "<h2>" . $msgCount . " MESSAGES</h2>"; }

            $overview = imap_fetch_overview($mailbox,"1:$msgCount",0);
            $size=sizeof($overview);

            echo '<dl id="draggable2">';

            for($i=$size-1; $i>=0; $i--)
            {
                $val = $overview[$i];
                $sequence = $val->msgno;
                $from = $val->from;
                $date = $val->date;
                $seen = $val->seen;
                if ($val->subject) {$subject = $val->subject;} else $subject = "(no subject)";
                $UID = imap_uid($mailbox, $sequence);

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
                    if      (strcasecmp($dayName, "Mon,")==0) $date = "Monday";
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

                //$body = imap_fetchbody($mailbox,$sequence,1); // this is slow. I need some way to only get the body on demand
                $body = 'this text is a placeholder for the body of the message';

                if (strlen($body) > 60)
                {
                    $body = substr($body,0,59) ."...";
                }

                echo '<div id='.$UID.'>
                    <dt>
                        <div style="white-space:nowrap; display:block;">
                        <span class="from">'.$from.'</span>
                        <span class="maildate">'.$date.'</span>
                        </div>
                    </dt>
                    <dd>
                        <div class="subject">'.$subject.'</div>
                        <div class="mailbody">'.$body.'</div>
                    </dd>
                    </div>';
            }

            echo '</dl>';

        }

    }
    else
    {
    	echo "failed";
    }

    imap_close($mailbox);
?>
</div>  <!-- end bottom -->

<!--
<div class='popbox' style="visibility:hidden;">
    <a class='open' href='#'>Click Here!</a>

    <div class='collapse'>
        <div class='box'>

            <?php

            //need to set the message number of the selected message
            //$selectedLi = ;

            $ServerName = "{imap.gmail.com:993/imap/ssl}INBOX";
            $Username = "mattkopecki@gmail.com";
            $Password = "ymadqdjghxeakbhq";

            $mailbox = imap_open($ServerName, $Username, $Password) or die("Could not open Mailbox");

            if ($hdr = imap_check($mailbox))
            {
                $msgCount = $hdr->Nmsgs;
                $overview = imap_fetch_overview($mailbox,"1:$msgCount",0);
                $size=sizeof($overview);

                for($i=$size-1; $i>=0; $i--)
                {
                    if ($i == $selectedLi)  // then this is the selected message that we want to turn in to a list item
                    {
                        $sequence = $overview[$i]->msgno;
                        $subject = $overview[$i]->subject;  // if this is the grabbed area, it will be the new task
                        $from = $overview[$i]->from;    //if this is the grabbed area, the new task will have this
                        $messageBody = imap_fetchbody($mailbox,$sequence,1);

                        $success = create_list_item($subject, $messageBody, $_COOKIE["UserID"]);
                        if ($success)
                        {
                            // move to archived mailbox
                            imap_mail_move($mailbox, $sequence, '[Gmail]/All Mail');
                            imap_expunge($mailbox);
                        }
                        else {
                            // the message wasn't removed from the inbox but was successfully converted to a task
                        }
                    }
                }
            }

            imap_close($mailbox);

            ?>

            <a href="#" class="close">close</a>
        </div>
    </div>
</div>
-->

</div>  <!-- end #page -->

</div>  <!-- end #wrapper -->

</body>
</html>