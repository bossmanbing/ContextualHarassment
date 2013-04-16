<?php

include("../handle/mysql.php");
include("../handle/functions.php");

$message = $_POST['message'];

$message = sanitize($message);

$qry = "INSERT INTO reports (content) VALUES ('$message')";

if (mysql_query($qry)){
	echo $message;
}
else{
	die(mysql_error());
}

?> 