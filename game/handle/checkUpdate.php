<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

// bring in form values and clean up
$player = $_SESSION['PID'];
$room = $_SESSION['RID'];

$qry = "SELECT id FROM players WHERE played = 0 AND id = '$player' LIMIT 1";
$res = mysql_query($qry) or die(mysql_error());

$num = mysql_num_rows($res);
							
echo $num;

?> 