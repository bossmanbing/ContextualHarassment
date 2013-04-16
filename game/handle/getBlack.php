<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

// bring in form values and clean up
$player = $_SESSION['PID'];
$room = $_SESSION['RID'];

							
$card = mysql_get_var("SELECT card FROM blackdist WHERE room = '$room'
							AND played = 0
							AND active = 1 LIMIT 1");

$newText = mysql_get_var("SELECT cardText FROM cards
							WHERE id = '$card' LIMIT 1");
							
if ($newText){
	echo $newText;
}
else{
}

?> 