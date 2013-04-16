<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

// bring in form values and clean up
$username = $_POST['username'];
$username = sanitize($username);
$safe = $_POST['safe'];
$safe = sanitize($safe);


$safeType = 0;
if ($safe){
	$safeType = 1;
}
else{
	$safeType = 0;
}


// // select the id for the most recently created room
$room = mysql_get_var("SELECT id FROM room WHERE type = $safeType LIMIT 1");

if (empty($room)){
	mysql_query("INSERT INTO room (type) VALUES ($safeType)");
	$room = mysql_insert_id();
	echo "room = $room";
}

else{}

// time to determine last click
$now = time();

// delete all inactive players
mysql_query("DELETE FROM players
			WHERE last_action <= ('$now'- 600)")
			or die(mysql_error());

// check to see if the selected room is already in use
$chkQry = "SELECT id FROM players WHERE room = '$room'";
$chkRes = mysql_query($chkQry) or die(mysql_error());

// if nobody is playing in the room, delete all of the data associated with it
if (mysql_num_rows($chkRes) == 0){
	mysql_query("DELETE FROM blackdist WHERE room = '$room'");
	mysql_query("DELETE FROM carddist WHERE room = '$room'");
	mysql_query("DELETE FROM answers WHERE room = '$room'");
	mysql_query("DELETE FROM chat WHERE room = '$room'");
}
else{}

// add player to the room
$qry = "INSERT INTO players (name, room) VALUES ('$username', '$room')";
mysql_query($qry) or die(mysql_error());
$PID = mysql_insert_id();

// make sure the room has not passed capacity
$rmQry = "SELECT id FROM players WHERE room='$room'";
$res = mysql_query($rmQry) or die(mysql_error());
// create a new room for the player if the last room is full
// also update the player info to reflect the new room, and change the value of room variable to new room
if (mysql_num_rows($res) > 7){
	$newQry = "INSERT INTO room (type) VALUES ($safeType)";
	mysql_query($newQry) or die(mysql_error());
	$room = mysql_insert_id();
	
	$upQry = "UPDATE players SET room=$room WHERE name='$username'";
	mysql_query($upQry) or die(mysql_error());
}

else{}

// set session ID to user DB ID
$_SESSION['PID'] = $PID;
// set last click for user to NOW
mysql_query("UPDATE players 
			SET last_action = '$now' 
			WHERE id = '$PID' LIMIT 1") 
			or die(mysql_error());
			
$countQry = "SELECT room FROM carddist WHERE room = '$room' LIMIT 1";
$countRes = mysql_query($countQry) or die(mysql_error());
// Check to see if the room has cards distributed
// if not, create a random list of cards
if (mysql_num_rows($countRes) == 0){
	if ($safe){
		$cQry = "SELECT id FROM cards WHERE color = 'white' 
				AND vulgar = '0' ORDER BY RAND()";
	}
	else{
		$cQry = "SELECT id FROM cards WHERE color = 'white' ORDER BY RAND()";
	}
	$cRes = mysql_query($cQry) or die(mysql_error());
	
	// add random cards to distribution list associated with this room
	while ($row = mysql_fetch_array($cRes, MYSQL_ASSOC))
		{
			$card = $row['id'];
			$in = "INSERT INTO carddist (card, player, room, played)
			VALUES ('$card', '0', '$room', '0')";
			mysql_query($in) or die(mysql_error());
		}
}

$blackQry = "SELECT card FROM blackdist
			WHERE active = '1' AND room = $room
			LIMIT 1";
$blackRes = mysql_query($blackQry) or die(mysql_error());
// check if room has any distributed black cards
// if not, assign cards to room in random order
if (mysql_num_rows($blackRes) == 0){
	if ($safe){
		$bQry = "SELECT id FROM cards WHERE color = 'black' 
				AND vulgar = '0' ORDER BY RAND()";
	}
	else{
		$bQry = "SELECT id FROM cards WHERE color = 'black' 
				AND vulgar != 'multi' ORDER BY RAND()";
	}
	$bRes = mysql_query($bQry) or die(mysql_error());
	// insert cards into black distribution list for this room
	while ($bRow = mysql_fetch_array($bRes, MYSQL_ASSOC))
		{
			$bCard = $bRow['id'];
			$in = "INSERT INTO blackdist (card, room)
			VALUES ('$bCard', '$room' )";
			mysql_query($in) or die(mysql_error());
		}
	// set the first black card for the room
	$upQry = "UPDATE blackdist SET active = '1'
			  WHERE room = '$room' LIMIT 1";
	mysql_query($upQry) or die(mysql_error());
}
// assign 10 unused cards to this player
mysql_query("UPDATE carddist SET player = '$PID'
			 WHERE room = '$room'
			 AND played = '0'
			 AND player = '0'
			 LIMIT 10")
			 or die(mysql_error());
			 
// set room session ID to this room
$_SESSION['RID'] = $room;
// send player to the room
header("Location:../room.php");


?> 