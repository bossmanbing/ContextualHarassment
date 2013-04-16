<?php

include("../handle/mysql.php");

$qry = "SELECT * FROM cards WHERE color = 'white'";
$res = mysql_query($qry) or die(mysql_error());

$var = array();
	while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
		array_push($var, $row['cardText']);
	}

echo json_encode($var);
?> 