<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$PID = $_SESSION['PID'];

$res = mysql_query("SELECT id FROM players WHERE id = '$PID' LIMIT 1");

if (mysql_num_rows($res) < 1){
	echo 1;
}

else{}


?> 