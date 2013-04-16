<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$PID = $_SESSION['PID'];
$id = $_POST['id'];
$id = sanitize($id);

$now = time();
$diff = $now-180;

//
// Check for inactive players and delete from the game
//
$res = mysql_query("SELECT id FROM players
			WHERE last_action <= $diff
			AND id = $id LIMIT 1")
			or die(mysql_error());
			
if (mysql_num_rows($res) == 1){
	mysql_query("DELETE FROM players
			WHERE id = $id");
	mysql_query("DELETE FROM answers WHERE player = $id");
	mysql_query("UPDATE carddist SET player = 0, played = 0
				 WHERE player = $id");
	echo 1;
}

$nRes = mysql_query("SELECT id FROM players WHERE id = $id LIMIT 1");
if (mysql_num_rows($nRes) < 1){
	echo 1;
}

else{}

?> 