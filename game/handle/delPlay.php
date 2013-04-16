<?php
session_start();
include("../handle/mysql.php");

mysql_query("DELETE FROM players WHERE 1");
?>