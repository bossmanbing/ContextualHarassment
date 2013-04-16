<?php
session_start();
include('../handle/mysql.php');
include('../handle/functions.php');

$PID = $_SESSION['PID'];
$RID = $_SESSION['RID'];

mysql_query("DELETE FROM answers WHERE player = '$PID' AND room = '$RID'");
mysql_query("UPDATE carddist SET player = 0 WHERE player = '$PID' 
			AND room = '$RID' AND played = 0");
mysql_query("DELETE FROM players WHERE id = '$PID' AND room = '$RID'");

session_destroy();
header('Location:../');
?> 