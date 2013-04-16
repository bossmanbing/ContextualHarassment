<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$PID = $_SESSION['PID'];
$RID = $_SESSION['RID'];
$text = $_POST['text'];
$oldCard = $_POST['card'];

$oldCard = sanitize($oldCard);
$text = mysql_real_escape_string($text);

$qry = "INSERT INTO answers (cardText, room, player)
		VALUES ('$text', '$RID', '$PID')";
		
if (mysql_query($qry)){
	$uQry = "UPDATE players SET played = 1
			WHERE id = '$PID'";
	mysql_query($uQry) or die(mysql_error());
}
else{
	echo mysql_error();
}

?> 