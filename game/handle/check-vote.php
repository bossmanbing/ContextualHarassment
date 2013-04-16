<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$RID = $_SESSION['RID'];
$PID = $_SESSION['PID'];

$qry = "SELECT id FROM players
		 WHERE room = '$RID' AND played >= '2'";
$res = mysql_query($qry) or die(mysql_error());
$count = mysql_num_rows($res);

$pQry = "SELECT id FROM players
		 WHERE room = '$RID'";
$pRes = mysql_query($pQry) or die(mysql_error());
$players = mysql_num_rows($pRes);

if ($count == $players){
	$nQry = mysql_get_var("SELECT played FROM players
							WHERE id = '$PID' AND room = '$RID' LIMIT 1");
	if ($nQry == 2){
		$uQry = "UPDATE players SET played = 3
					WHERE id = '$PID' AND room = '$RID' LIMIT 1";
		mysql_query($uQry) or die(mysql_error());
	}
	else{}
	
	$cQry = "SELECT id FROM players
			 WHERE room = '$RID' AND played = 3";
	$cRes = mysql_query($cQry) or die(mysql_error());
	$updates = mysql_num_rows($cRes);
	if ($updates == $players){
		echo 1;
	}
	else{
		echo 0;
	}
}
else{ echo 'something broke';}

?> 