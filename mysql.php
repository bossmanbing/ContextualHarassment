<?php

$mysql_host = ""; // the name or address of your mysql server
$mysql_database = "ch_cards";
$mysql_user = ""; // your mysql username
$mysql_password = ""; // the password for the above mysql user

mysql_connect("$mysql_host", "$mysql_user", "$mysql_password")or die("cannot connect");
mysql_select_db("$mysql_database")or die("cannot select DB");
?>