<?php
session_start();
include("../handle/mysql.php");
include("../handle/functions.php");

$PID = $_SESSION['PID'];
$RID = $_SESSION['RID'];

//
// Check to see if current player is still in the database
//
$res = mysql_query("SELECT id FROM players WHERE id = '$PID' LIMIT 1");

$checkPlayer = '';

if (mysql_num_rows($res) < 1){
	$checkPlayer = 1;
}

else{
	$checkPlayer = 0;
}

///
///   CHECK FOR PLAYER VOTES
///

$qry = "SELECT id FROM players
		 WHERE room = '$RID' AND played >= '2'";
$res = mysql_query($qry) or die(mysql_error());
$count = mysql_num_rows($res);

$pQry = "SELECT id FROM players
		 WHERE room = '$RID'";
$pRes = mysql_query($pQry) or die(mysql_error());
$players = mysql_num_rows($pRes);

$checkVote = '';
if ($count == $players){
	$nQry = mysql_get_var("SELECT played FROM players
							WHERE id = '$PID' AND room = '$RID' LIMIT 1");
	if ($nQry == 2){
		$uQry = "UPDATE players SET played = 3
					WHERE id = '$PID' AND room = '$RID' LIMIT 1";
		mysql_query($uQry) or die(mysql_error());
	}
	else{}
	
	$cQry = "SELECT id FROM players
			 WHERE room = '$RID' AND played = 3";
	$cRes = mysql_query($cQry) or die(mysql_error());
	$updates = mysql_num_rows($cRes);
	if ($updates == $players){
		$checkVote = 1;
		
		///
		/// SET A NEW BLACK CARD IF ALL OF THE PLAYERS HAVE VOTED
		///
		
		$newCard = mysql_get_var("SELECT card FROM blackdist WHERE room = '$RID'
							AND played = '0'
							AND active = '0' LIMIT 1");
							
		$oldCard = mysql_get_var("SELECT card FROM blackdist WHERE room = '$RID'
									AND played = 0
									AND active = 1 LIMIT 1");
									
		$qry = "UPDATE blackdist SET played = 1 WHERE card = '$oldCard'
				AND room = $RID";
		mysql_query($qry) or die(mysql_error());

		$nQry = "UPDATE blackdist SET active = 1 WHERE card = '$newCard'";
		mysql_query($nQry) or die(mysql_error());



		$uQry = "UPDATE players SET played = 0
						WHERE room = '$RID'";
		mysql_query($uQry) or die(mysql_error());
		$dQry = "DELETE FROM answers WHERE room = '$RID'";
		mysql_query($dQry) or die(mysql_error());
	}
	else{
		$checkVote = 0;
	}
}
else{}

$array = array('checkPlayer'=>$checkPlayer, 'checkVote'=>$checkVote);
echo json_encode($array);
?>