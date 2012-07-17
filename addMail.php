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
        $from = preg_replace('#\<.*?\>#s','',$from);

        $subject = $overview[0]->subject;

        // this isn't working right now. it has to do with the message types and the correct sequence number (here 1.1 and 1)
        //$messageBody = imap_fetchbody($mailbox,$UID,1.1,FT_UID);
		//if(!strlen($bodyText)>0){ $messageBody = imap_fetchbody($mailbox,$UID,1,FT_UID); }

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