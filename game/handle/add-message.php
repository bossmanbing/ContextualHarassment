<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$room = $_SESSION['RID'];
$id = $_POST['id'];
$id = sanitize($id);


// check for new players
$res = mysql_query("SELECT id, player, message FROM chat
			WHERE room = $room AND id > '$id'")
			or die(mysql_error());

// if there are new players
if (mysql_num_rows($res) > 0){
		while ($row = mysql_fetch_array($res, MYSQL_ASSOC))
		{
			echo "<span id='mess".$row['id']."' class='viewed chatMessage' message-id='".$row['id']."'>
						<strong>".$row['player'].":</strong> ".$row['message']."</br>
					</span>";
		}
}

else{}

?> 