<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$RID = $_SESSION['RID'];

$qry = "SELECT id FROM players
		 WHERE room = '$RID' AND played = '1'";

$res = mysql_query($qry) or die(mysql_error());

echo mysql_num_rows($res);

?> 