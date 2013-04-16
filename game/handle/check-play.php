<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$id = $_POST['id'];
$id = sanitize($id);

$qry = "SELECT id FROM players
		 WHERE id = '$id' AND played = '1' LIMIT 1";

$res = mysql_query($qry) or die(mysql_error());

if (mysql_num_rows($res) == 1){
	$now = time();
	mysql_query("UPDATE players 
				SET last_action = '$now' 
				WHERE id = '$id' LIMIT 1") 
				or die(mysql_error());
	echo 1;
}

else{}

?> 