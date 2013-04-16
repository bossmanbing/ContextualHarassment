<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$room = $_SESSION['RID'];

// current time, create 5 minute lifetime
$now = time();
$diff = $now-3000;

// check for new players
$res = mysql_query("SELECT id, last_action, played FROM players
			WHERE room = $room")
			or die(mysql_error());
			
$response = false;
$users = array();

// if there are new players
if (mysql_num_rows($res) > 1){
		while ($row = mysql_fetch_array($res, MYSQL_ASSOC))
		{
			// delete inactive players
			if ($row['last_action'] < $diff && $row['played'] == 0){
				$id = $row['id'];
				mysql_query("DELETE FROM players
							WHERE id = $id");
						
			}
			// add the new player, make sure it is not the current player
			if ($row['id'] != $_SESSION['PID']){
				$users[] = $row['id'];
			}
			else{}
		// wrap up for jQuery to handle
		$response = json_encode($users);
		}
}

else{}

echo $response;
?> 