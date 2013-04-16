<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$id = $_POST['id'];
$message = $_POST['message'];
$RID = $_SESSION['RID'];

$id = sanitize($id);
$message = sanitize($message);

$username = mysql_get_var("SELECT name FROM players WHERE id = '$id' LIMIT 1");

$qry = "INSERT INTO chat (player, message, room)
		VALUES ('$username', '$message', '$RID')";
		
mysql_query($qry) or die(mysql_error());

?> 