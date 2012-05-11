<?php
	define('DB_USER', 'kopecki1_mpk');
	define('DB_HOST', 'localhost');
	define('DB_PASS', 'cs411');
	define('DB_DB', 'kopecki1_todo');

	$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect to MySQL: '.mysql_error());
	@mysql_select_db(DB_DB) or die('Could not select database: '.mysql_error());

	function logon_user($id, $pass)
	{
		$result = mysql_query("SELECT * FROM Users WHERE UserName='$id' AND Password='$pass';");
		return mysql_fetch_assoc($result);
	}

	function user_exists($id)
	{
		$result = mysql_query("SELECT * FROM Users WHERE UserName='$id';");

		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;
	}

	function create_user($id, $pass, $email)
	{
		if(!user_exists($id))
		{
			mysql_query("INSERT INTO Users(UserName,Password,email) VALUES ('$id','$pass','$email');");
		}
	}

	function my_lists($id)
	{
		$result = mysql_query("SELECT * FROM Lists,Users WHERE Users.UserID='$id' AND Users.UserID=Lists.UserID AND NOT Lists.Title='To Do List';");
		$retval = array();
		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}
		return $retval;
	}

	function my_todo_list($id)
	{
		$result = mysql_query("SELECT * FROM Lists,Users WHERE Users.UserID='$id' AND Users.UserID=Lists.UserID AND Lists.Title='To Do List';");
		$retval = array();
		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}
		return $retval;
	}

	function list_contents($id)
	{
		$result = mysql_query("SELECT * FROM Tasks WHERE ListID='$id';");
		$retval = array();
		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}
		return $retval;
	}

	function update_task($ListID, $TaskID, $text, $UserID)
	{
		//echo "l:".$ListID." t:".$TaskID."   ".$text;

		$checkList = mysql_query("SELECT * FROM Lists, Users WHERE Users.UserID='$UserID' AND Users.UserID=Lists.UserID AND Lists.ListID='$ListID';");

		if(mysql_num_rows($checkList) > 0)	// ListID exists
		{
			$checkTask = mysql_query("SELECT * FROM Tasks WHERE ListID='$ListID' AND TaskID='$TaskID';");

			if(mysql_num_rows($checkTask) > 0)	// TaskID exists
			{
				//update task's text
				mysql_query("UPDATE Tasks SET Tasks.Title='$text' WHERE Tasks.TaskID='$TaskID';");
			}
			else {
				// TaskID does not exist, create a new one
				mysql_query("INSERT INTO Tasks(ListID,Title) VALUES ('$ListID','$Title');");
			}
		}
		else {
			// ListID does not exist
		}
	}

	function parse_from_captio($subject, $messageBody, $UserID)
	{
		preg_match('#\[(.*?)\]#', $subject, $match);
		$listname = $match[1];

		$checkListExists = mysql_query("SELECT Lists.ListID FROM Lists, Users WHERE Users.UserID='$UserID' AND Users.UserID=Lists.UserID AND Lists.Title='$listname';");

		if(mysql_num_rows($checkListExists) > 0)	// List exists with that listname
		{
			$array = mysql_fetch_assoc($checkListExists);
			$ListID = $array["ListID"];

			mysql_query("INSERT INTO Tasks(ListID,Title) VALUES ('$ListID','$messageBody');");

			return true;
		}
		else {
			// List does not exist with that name
			return false;
		}
	}

	function check_for_captio()
	{
	    $ServerName = "{imap.gmail.com:993/imap/ssl}INBOX";
	    $Username = "mattkopecki@gmail.com";
	    $Password = "MPK282kop";

	    $mailbox = imap_open($ServerName, $Username, $Password) or die("Could not open Mailbox");

	    if ($hdr = imap_check($mailbox))
	    {
	        $msgCount = $hdr->Nmsgs;
	        $overview = imap_fetch_overview($mailbox,"1:$msgCount",0);
	        $size=sizeof($overview);

	        for($i=$size-1; $i>=0; $i--)
	        {
	            $val = $overview[$i];
	            $sequence = $val->msgno;
	            $from = $val->from;

	            preg_match('/\b(captio\w+)\b/', $from, $match);
	            if ($match)
	            {
	                $subject = $val->subject;
	                $messageBody = imap_fetchbody($mailbox,$sequence,1);
	                $success = parse_from_captio($subject, $messageBody, $_COOKIE["UserID"]);
	                if ($success)
	                {
	                    // move to archived mailbox
	                    imap_mail_move($mailbox, $sequence, '[Gmail]/All Mail');
	                    imap_expunge($mailbox);
	                    echo 'new mail, time to reload!';
	                    continue;  // process the next message and don't add this one to the Messages box
	                }
	                else {
	                    continue;
	                }
	            }
	        }
	    }

	    imap_close($mailbox);
	}

/*
	function song_exists($Title,$Artist)
	{
		$result = mysql_query("SELECT * FROM Songs WHERE Title='$Title' AND Artist='$Artist';");

		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;
	}

	function add_song($Title,$Artist,$Album,$Year)
	{
		if(!song_exists($Title,$Artist))
		{
			mysql_query("INSERT INTO Songs(Title,Artist,Album,Year) VALUES ('$Title','$Artist','$Album','$Year');");
		}
		$result = mysql_query("SELECT * FROM Songs WHERE Title='$Title' AND Artist='$Artist';");
		return mysql_fetch_assoc($result);
	}

	function suggest_song_for_event($SongID, $EventID)
	{
		$result = mysql_query("SELECT * FROM Suggestions WHERE SongID='$SongID' AND EventID='$EventID';");

		if(mysql_num_rows($result) > 0)
			return;

		$result = mysql_query("SELECT * FROM Events,Playlists WHERE EventID='$EventID' AND Playlists.PlaylistID=Events.PlaylistID AND Playlists.SongID='$SongID';");
		if(mysql_num_rows($result) > 0)
			return;
		else
		{
			 mysql_query("INSERT INTO Suggestions(SongID,EventID) VALUES ('$SongID','$EventID');");
		}
	}

	function accept_suggestion($Song,$EventID)
	{
		$EventResult = mysql_query("SELECT * FROM Events WHERE EventID='$EventID';");
		$EventArray = mysql_fetch_assoc($EventResult);

		$PlaylistID = $EventArray["PlaylistID"];
		$PlaylistResult = mysql_query("SELECT * FROM Playlists WHERE PlaylistID='$PlaylistID';");
		$PlaylistArray = mysql_fetch_assoc($PlaylistResult);

		$PlaylistTitle = $PlaylistArray["Title"];
		$UserID =  $_COOKIE["PlayReq"];

		mysql_query("INSERT INTO Playlists(UserID,SongID,PlaylistID,Title) VALUES ('$UserID','$Song','$PlaylistID','$PlaylistTitle');");
		mysql_query("DELETE FROM Suggestions WHERE SongID='$Song' AND EventID='$EventID';");

	}

	function reject_suggestion($Song,$EventID)
	{
		mysql_query("DELETE FROM Suggestions WHERE SongID='$Song' AND EventID='$EventID';");
	}

	function get_host_info_for_email($EventID)
	{
		$result = mysql_query("SELECT * FROM Events,Users WHERE Events.EventID='$EventID' AND Users.UserID = Events.Host;");
		$retval = mysql_fetch_assoc($result);

		return $retval;
	}

	function get_info_for_event($EventID)
	{
		$result = mysql_query("SELECT * FROM Events WHERE Events.EventID='$EventID'");
		$retval = mysql_fetch_assoc($result);

		return $retval;
	}

	function suggested_songs_for_event($EventID)
	{
		$result = mysql_query("SELECT * FROM Songs,Suggestions WHERE Suggestions.EventID='$EventID' AND Songs.SongID=Suggestions.SongID;");

		$retval = array();
		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}
		return $retval;
	}


	function add_song_to_library($UserID,$SongID)
	{
		$result = mysql_query("SELECT * FROM Library WHERE UserID='$UserID' AND SongID='$SongID';");

		if(mysql_num_rows($result) > 0)
			return;
		else
		{
			 mysql_query("INSERT INTO Library(UserID,SongID) VALUES ('$UserID','$SongID');");
		}
	}

	function delete_from_library($Song)
	{
		$UID = $_COOKIE["PlayReq"];

		mysql_query("DELETE From Library WHERE UserID='$UID' AND SongID='$Song';");
	}

	function delete_from_playlist($SongID,$PlaylistID)
	{
		$UID = $_COOKIE["PlayReq"];

		mysql_query("DELETE From Playlists WHERE PlaylistID='$PlaylistID' AND SongID='$SongID';");
	}

	function uninvite_from_event($UserID,$EventID)
	{
		mysql_query("DELETE From Attendees WHERE UserID='$UserID' AND EventID='$EventID';");
	}

	function update_ownership_in_library($Song)
	{
		$result = mysql_query("SELECT * FROM Library WHERE SongID='$Song';");

		$retval = mysql_fetch_assoc($result);

		$UID = $_COOKIE["PlayReq"];

		if($retval["Owns"] == 1)
		{
			mysql_query("UPDATE Library SET Owns=0 WHERE UserID='$UID' AND SongID='$Song';");
		}
		else
		{
			mysql_query("UPDATE Library SET Owns=1 WHERE UserID='$UID' AND SongID='$Song';");
		}
	}

	function update_feelings_in_library($Song)
	{
		$result = mysql_query("SELECT * FROM Library WHERE SongID='$Song';");

		$retval = mysql_fetch_assoc($result);

		$UID = $_COOKIE["PlayReq"];

		if($retval["Feelings"] == 1)
		{
			mysql_query("UPDATE Library SET Feelings=0 WHERE UserID='$UID' AND SongID='$Song';");
		}
		else
		{
			mysql_query("UPDATE Library SET Feelings=1 WHERE UserID='$UID' AND SongID='$Song';");
		}
	}

	function add_playlist($Title,$ID)
	{
		if(!playlist_exists($Title,$ID))
		{
			mysql_query("INSERT INTO Playlists(Title,UserID) VALUES ('$Title','$ID');");
		}

		return;
	}

	function playlist_exists($Title,$ID)
	{
		$result = mysql_query("SELECT * FROM Playlists WHERE Title='$Title' AND UserID='$ID';");

		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;

	}

	function playlist_exists_for_user($PlaylistID,$UserID)
	{
		$result = mysql_query("SELECT * FROM Playlists WHERE PlaylistID='$PlaylistID' AND UserID='$UserID';");

		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;
	}

	function event_exists_for_user($EventID,$UserID)
	{
		$result = mysql_query("SELECT * FROM Events,Attendees WHERE Events.EventID='$EventID' AND Events.EventID=Attendees.EventID;");

		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;
	}

	function my_songs_from_playlist($UserID,$PlaylistID)
	{
		$result = mysql_query("SELECT Songs.Title,Songs.Artist,Songs.Album,Songs.Year,Songs.SongID FROM Songs,Playlists WHERE Playlists.PlaylistID='$PlaylistID' AND Songs.SongID=Playlists.SongID AND Playlists.UserID='$UserID';");
		$retval = array();

		if(mysql_num_rows($result) > 0)
		{
			while($row = mysql_fetch_array($result)){
				array_push($retval, $row);
			}
			return $retval;
		}
		else
		{
			return false;
		}
	}

	function get_songs_for_event($UserID,$EventID)
	{
		$result = mysql_query("SELECT Songs.Title,Songs.Artist,Songs.Album,Songs.Year,Songs.SongID
		FROM Songs,Playlists,Events WHERE Playlists.PlaylistID=Events.PlaylistID AND Songs.SongID=Playlists.SongID AND Events.PlaylistID=Playlists.PlaylistID AND Events.EventID='$EventID';");

		$retval = array();

		if(mysql_num_rows($result) > 0)
		{
			while($row = mysql_fetch_array($result)){
				array_push($retval, $row);
			}
			return $retval;
		}
		else
		{
			return false;
		}
	}

	function attendees_for_event($EventID)
	{
		$result = mysql_query("SELECT Users.UserName,Users.UserID FROM Attendees,Users,Events WHERE Events.EventID='$EventID' AND Attendees.UserID=Users.UserID AND Attendees.EventID=Events.EventID;");
		$retval = array();

		if(mysql_num_rows($result) > 0)
		{
			while($row = mysql_fetch_array($result)){
				array_push($retval, $row);
			}
			return $retval;
		}
		else
		{
			return false;
		}
	}

	function is_user_hosting_event($EventID,$UserID)
	{
		$result = mysql_query("SELECT * FROM Events WHERE EventID='$EventID' AND Host='$UserID';");

		if(mysql_num_rows($result) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function playlists_for_user($UserID)
	{
		$result = mysql_query("SELECT DISTINCT Title,PlaylistID FROM Playlists WHERE Playlists.UserID='$UserID' ORDER BY PlaylistID ASC;");
		$retval = array();


		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}

		return $retval;
	}

	function events_for_user($UserID)
	{
		$result = mysql_query("SELECT DISTINCT Title,Events.EventID FROM Events,Attendees WHERE Attendees.UserID='$UserID' AND Attendees.EventID=Events.EventID ORDER BY Events.EventID ASC;");
		$retval = array();


		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}

		return $retval;
	}

	function first_now_playing()
	{
		$result = mysql_query("SELECT DISTINCT * FROM NowPlaying ORDER BY EventID ASC LIMIT 1;");

		return mysql_fetch_array($result);
	}


	function first_playlist_for_user($UserID)
	{
		$result = mysql_query("SELECT DISTINCT Title,PlaylistID FROM Playlists WHERE Playlists.UserID='$UserID' ORDER BY PlaylistID ASC LIMIT 1;");

		return mysql_fetch_array($result);
	}

	function first_event_for_user($UserID)
	{
		$result = mysql_query("SELECT DISTINCT Title,EventID FROM Events WHERE Events.Host='$UserID' ORDER BY EventID ASC LIMIT 1;");

		return mysql_fetch_array($result);
	}

	function get_nowplaying_for_event($EventID)
	{
		$result = mysql_query("SELECT * FROM NowPlaying WHERE EventID=$EventID LIMIT 1;");

		return mysql_fetch_array($result);
	}

	function update_now_playing_for_event($eventID,$song,$artist)
	{
		$result = mysql_query("SELECT * FROM NowPlaying WHERE EventID='$eventID';");

		if(mysql_num_rows($result) < 1)
			mysql_query("INSERT INTO NowPlaying(EventID,Title,Artist) VALUES ('$eventID','$song','$artist');");
		else
			mysql_query("UPDATE NowPlaying SET Title='$song',Artist='$artist' WHERE EventID='$eventID';");
	}


	function is_event_playing_something($EventID)
	{
		$result = mysql_query("SELECT * FROM NowPlaying WHERE EventID=$EventID LIMIT 1;");

		if(!$result)
			return false;

		if(mysql_num_rows($result) < 1)
			return false;
		else
			return true;
	}

	function add_song_to_playlist($SongID,$PlaylistID,$UserID)
	{
		$result = mysql_query("SELECT * FROM Playlists WHERE SongID='$SongID' AND PlaylistID='$PlaylistID';");
		$TitleQuery = mysql_query("SELECT * FROM Playlists WHERE PlaylistID='$PlaylistID' LIMIT 1;");

		$row = mysql_fetch_assoc($TitleQuery);
		$Title = $row["Title"];

		if($PlaylistID==NULL)
			return;
		echo "The title is: ";
		echo $Title;

		if(mysql_num_rows($result) > 0)
		{
			return;
		}
		else
		{
			 mysql_query("INSERT INTO Playlists(UserID,SongID,PlaylistID,Title) VALUES ('$UserID','$SongID','$PlaylistID','$Title');");
		}
	}

	function event_exists($Title,$UserID)
	{
		$result = mysql_query("SELECT * FROM Events WHERE Title='$Title' AND Host='$UserID';");

		if(mysql_num_rows($result) > 0)
			return true;
		else
			return false;
	}

	function all_events()
	{
		$result = mysql_query("SELECT * from Events;");
		$retval = array();


		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}

		return $retval;
	}

	function add_event($Title,$Location,$Date,$PlaylistID,$UserID,$Alerts)
	{
		if(!event_exists($Title,$UserID))
		{
			mysql_query("INSERT INTO Events(Title,Location,DateTime,Host,PlaylistID,EmailAlerts) VALUES ('$Title','$Location','$Date','$UserID','$PlaylistID','$Alerts');");
			$result = mysql_query("SELECT * FROM Events WHERE Title='$Title' AND Host='$UserID';");

			$row = mysql_fetch_assoc($result);
			$EventID = $row["EventID"];

			mysql_query("INSERT INTO Attendees(EventID,UserID) VALUES ($EventID,$UserID);");
		}

		return;
	}

	function get_all_uninvited_users($EventID)
	{
		$result = mysql_query("SELECT * FROM Users WHERE UserID NOT IN (SELECT UserID FROM Attendees Where EventID='$EventID');");
		$retval = array();
		while($row = mysql_fetch_array($result)){
			array_push($retval, $row);
		}
		return $retval;
	}

	function invite_user_to_event($UserID,$EventID)
	{
		mysql_query("INSERT INTO Attendees(EventID,UserID) VALUES ($EventID,$UserID);");
	}
*/
?>