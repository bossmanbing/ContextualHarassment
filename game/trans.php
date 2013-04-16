<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/update.js"></script>
</head>
<body>

<?php

include("handle/mysql.php");

mysql_query("BEGIN");
$q1 = mysql_query("SELECT @var:=cardText FROM cards WHERE id = 587 LIMIT 1");
$q2 = mysql_query("UPDATE cards SET vulgar='0' WHERE cardText=@var");

if ($q1 && $q2){
	mysql_query("COMMIT") or die(mysql_error());
}
else{
	mysql_query("ROLLBACK");
}
?>
</body>
</html>