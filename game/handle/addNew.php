<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$id = $_POST['id'];
$id = sanitize($id);

//
// get information for the new player and send information back to the room
//
$res = mysql_query("SELECT id, name, score FROM players
			WHERE id = $id LIMIT 1")
			or die(mysql_error());
while ($row = mysql_fetch_array($res, MYSQL_ASSOC))
		{
$response = "<div class='player' player-id='".$row['id']."' id='player".$row['id']."'>
				<strong>".$row['name']."</strong>
				<span class='score' id='score".$row['id']."'>
					".$row['score']."
				</span>
			</div>";
}

echo $response;
?> 