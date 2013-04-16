<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

// bring in form values and clean up
$player = $_SESSION['PID'];
$room = $_SESSION['RID'];

$newCard = mysql_get_var("SELECT card FROM blackdist WHERE room = '$room'
							AND played = '0'
							AND active = '0' LIMIT 1");
							
$oldCard = mysql_get_var("SELECT card FROM blackdist WHERE room = '$room'
							AND played = 0
							AND active = 1 LIMIT 1");
							
$qry = "UPDATE blackdist SET played = 1 WHERE card = '$oldCard'
		AND room = $room";
mysql_query($qry) or die(mysql_error());

$nQry = "UPDATE blackdist SET active = 1 WHERE card = '$newCard'";
mysql_query($nQry) or die(mysql_error());



$uQry = "UPDATE players SET played = 0
				WHERE room = '$room'";
mysql_query($uQry) or die(mysql_error());
$dQry = "DELETE FROM answers WHERE room = '$room'";
mysql_query($dQry) or die(mysql_error());

?> 