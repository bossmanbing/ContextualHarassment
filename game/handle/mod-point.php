<?php

include("../handle/mysql.php");
include("../handle/functions.php");

$oper = $_POST['oper'];
$id = $_POST['id'];

$oper = sanitize($oper);

$qry = '';
if ($oper == 'plus'){
	$qry = "UPDATE reports SET points = points + 1 WHERE id = '$id' LIMIT 1";
}
if ($oper == 'minus'){
	$qry = "UPDATE reports SET points = points - 1 WHERE id = '$id' LIMIT 1";
} else{}

if (mysql_query($qry)){
	echo mysql_get_var("SELECT points FROM reports WHERE id = '$id' LIMIT 1");
}
else{
	mysql_error();
}

?> 