<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$RID = $_SESSION['RID'];
$PID = $_SESSION['PID'];

$qry = "SELECT cardText, player FROM answers
		 WHERE room = '$RID' ORDER BY RAND()";

$res = mysql_query($qry) or die(mysql_error());

$answers = array();
$count = 0;
while ($row = mysql_fetch_array($res, MYSQL_ASSOC))
		{
			if ($PID != $row['player']){
				$answers[] = "<div class ='blackAnswer votable' id='a".$count."' user-id='".$row['player']."'>".$row['cardText']."</div>";
				$count ++;
			}
		}

echo json_encode($answers);

?> 