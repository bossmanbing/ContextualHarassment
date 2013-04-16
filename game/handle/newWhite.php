<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

// bring in form values and clean up
$player = $_SESSION['PID'];
$room = $_SESSION['RID'];
$oldCard = $_POST['card'];
$oldCard = sanitize($oldCard);

$newCard = mysql_get_var("SELECT card FROM carddist WHERE room = '$room'
							AND player = '0'
							AND played = '0' LIMIT 1");
							
$qry = "UPDATE carddist SET played = '1' WHERE card = '$oldCard'
		AND room = '$room' LIMIT 1";
mysql_query($qry) or die(mysql_error());

$nQry = "UPDATE carddist SET player = '$player' WHERE card = '$newCard'
		AND room = '$room' LIMIT 1";
mysql_query($nQry) or die(mysql_error());

$newText = mysql_get_var("SELECT cardText FROM cards
							WHERE id = '$newCard' LIMIT 1");
							
if ($newText){
	echo $newText;
}
else{
	echo "Something Broke";
}

?> 