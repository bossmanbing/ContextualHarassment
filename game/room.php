<?php
session_start();
if (empty($_SESSION['PID'])){
	header("Location:index.html");
}

include("handle/mysql.php");
include("handle/functions.php");

$PID = $_SESSION['PID'];
$RID = $_SESSION['RID'];
$now = time();
mysql_query("UPDATE players 
			SET last_action = '$now' 
			WHERE id = '$PID' LIMIT 1") 
			or die(mysql_error());

			
$qry = "SELECT name, score, id FROM players
			WHERE id = '$PID' LIMIT 1";
$res = mysql_query($qry) or die(mysql_error());
$player = mysql_fetch_assoc($res);
mysql_free_result($res);

$uQry = "SELECT name, score, id FROM players
			WHERE room = '$RID' AND id != '$PID'";
$uRes = mysql_query($uQry) or die(mysql_error());

$cQry = "SELECT cardText, id FROM cards as c
			JOIN carddist as d
			ON c.id = d.card
			WHERE player = '$PID' AND played = '0' LIMIT 10";

$cRes = mysql_query($cQry) or die(mysql_error());

$cQry = "SELECT cardText FROM cards as c
			JOIN blackdist as b
			ON c.id = b.card
			WHERE b.room = '$RID' AND b.active = '1' AND b.played = '0' LIMIT 1";

$blackCard = mysql_get_var($cQry);

$mQry = "SELECT id, message, player FROM chat
		WHERE room = '$RID'";
		
$mRes = mysql_query($mQry) or die(mysql_error());
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/logic.js"></script>
	<script type="text/javascript" src="js/heartbeat.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
</head>
<body>

<div id='board'>

	<div id='gameArea'>
		<div id='voteArea'>
		</div>
		<div id='blackArea'>
			<div class='gameCard' id='blackCard' class='droppable'><?php echo $blackCard; ?></div>
		</div>
		
		<a href="." id='reset'>Reset Cards</a>
		<a href="." id='commit'>Submit Card</a>

		<div id='voting' class='bottomArea'>
		</div>
		<div id='answers' class='bottomArea'>
			<?php
			$place = 10;
			// display 10 answer cards for player
			while ($cRow = mysql_fetch_array($cRes, MYSQL_ASSOC))
			{
			?>
			<div class='answerCard playable' card-id='<?php echo $cRow['id']; ?>' id='c<?php echo $place; ?>'><?php echo $cRow['cardText']; ?></div>
			<?php
			$place --;
			}		// end while loop for card placement
			?>
			
		</div>
		<a href='report.php' target='_blank' id='reportLink'>
			Report a bug
			<br />
			or
			<br />
			Submit an idea
		</a>
		<a href='./handle/logout.php' id='logout'>
			Leave the room
		</a>
	</div>
	<div class='clear'></div>
	<div id='gameInfo'>
		<div id='roomPlayers'>
			<div class='player midPlay' id='player<?php echo $player['id']; ?>' player-id='<?php echo $player['id'];?>'>
				<strong><?php echo $player['name']; ?></strong>
				<span class='score' id='score<?php echo $player['id']; ?>'><?php echo $player['score'];?></span>			
			</div>
			<?php
				// PRINTS THE INFO CARDS FOR THE OTHER USERS IN THE ROOM
				while ($row = mysql_fetch_array($uRes, MYSQL_ASSOC))
					{
			?>
			<div class='player' player-id='<?php echo $row['id']; ?>' id='player<?php echo $row['id']; ?>'>
				<strong><?php echo $row['name']; ?></strong>
				<span class='score' id='score<?php echo $row['id']; ?>'>
					<?php echo $row['score'];?>
				</span>
			</div>
			<?php
				}	// ends while loop to place users
			?>
		</div>
		<div id='chatArea'>
			<div id='messages'>
				<?php
				// post chat messages for this room
				while ($mRow = mysql_fetch_array($mRes, MYSQL_ASSOC))
				{
				?>
					<span id='mess<?php echo $mRow['id']; ?>' class='viewed chatMessage' message-id='<?php echo $mRow['id']; ?>'>
						<strong><?php echo $mRow['player']; ?>:</strong> <?php echo $mRow['message']; ?> </br>
					</span>
				<?php
				} // ends message posting while loop
				?>
			</div>
			<div id='form'>
				<form action='.' method='post' id='chat-form'>
					<input type='text' id='chat-message' name='message' maxlength='100' size='16' placeholder='Say something!' required />
					<input type='hidden' id='form-user' name='user' value='<?php echo $player['id']; ?>' />
					<input type='submit' id='chat-submit' value='Submit' />
			</div>
		</div>
	</div>
</div>

</body>
</html>
