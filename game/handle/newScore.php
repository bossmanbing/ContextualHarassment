<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$id = $_POST['id'];
$id = sanitize($id);

$score = mysql_get_var("SELECT score FROM players
						WHERE id = '$id' LIMIT 1");
						
echo $score;

?> 