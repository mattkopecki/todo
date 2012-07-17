<?php
	require 'sql_lib.php';

	$ListID = preg_replace("/[^0-9]/", '', $_POST['ListID']);
	$UID = $_POST['MailID'];

	$replyrequired = true;

    $ServerName = "{imap.gmail.com:993/imap/ssl}INBOX";
    $Username = "mattkopecki@gmail.com";
    $Password = "ymadqdjghxeakbhq";

    $mailbox = imap_open($ServerName, $Username, $Password) or die("Could not open Mailbox");

    $messageNumber = imap_msgno($mailbox, $UID);

    if ($hdr = imap_check($mailbox))
    {
        $overview = imap_fetch_overview($mailbox, $messageNumber,0);

        $from = $overview[0]->from;
        $subject = $overview[0]->subject;
        $messageBody = imap_fetchbody($mailbox,$messageNumber,1);

        $from = preg_replace('#\<.*?\>#s','',$from);

        //$text = $from.": ".$subject." -- ".$messageBody;
		$text = "Reply to ".$from."- ".$subject;

		if (strlen($text) > 300)
        {
            $text = substr($text,0,299) ."...";
        }

		mysql_query("INSERT INTO Tasks(ListID,Title) VALUES ('$ListID','$text');");

        if (!$replyrequired)
        {
        	imap_mail_move($mailbox, $messageNumber, '[Gmail]/All Mail');
        	imap_expunge($mailbox);
        }
    }

    imap_close($mailbox);

?>