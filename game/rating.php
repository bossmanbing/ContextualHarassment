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

$qry = "SELECT * FROM cards WHERE color='white'";
$res = mysql_query($qry) or die(mysql_error());


while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
?>
<form action="." method="post" class='form-submit'>
	<?php echo $row['cardText']; ?>: 
	<input type='hidden' value='<?php echo $row['id'];?>' name='id'/> --- 
	<input type='text' name='value' value='<?php echo $row['vulgar'];?>' size=1 maxlength=1/> --- 
	<input type='submit' name='update' value='Update' />
	<br />
</form>
<?php
}
?>

</body>
</html>