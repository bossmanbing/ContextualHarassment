<?php

include("../handle/mysql.php");

//
// Create an array of black cards for jQuery access
//
$qry = "SELECT * FROM cards WHERE color = 'black'";
$res = mysql_query($qry) or die(mysql_error());

$var = array();
	while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
		array_push($var, stripslashes($row['cardText']));
	}

echo json_encode($var);
?> 