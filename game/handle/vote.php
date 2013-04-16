<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$user = $_POST['user'];
$user = sanitize($user);

$id = $_SESSION['PID'];

$uQry = "UPDATE players SET played = 2 WHERE id = '$id' LIMIT 1";
mysql_query($uQry) or die(mysql_error());

$qry = "UPDATE players SET score = score+1 WHERE id = '$user' LIMIT 1";

if (mysql_query($qry)){
	echo $user;
}
else{
	echo mysql_error();
}



?> 