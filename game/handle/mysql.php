<?php

$mysql_host = "localhost";
$mysql_database = "cards";
$mysql_user = "root";
$mysql_password = "";

mysql_connect("$mysql_host", "$mysql_user", "$mysql_password")or die("cannot connect");
mysql_select_db("$mysql_database")or die("cannot select DB");
?>