<?php

include("handle/mysql.php");

$qry = "SELECT * FROM reports WHERE 1 ORDER BY points DESC";
$res = mysql_query($qry) or die(mysql_error());

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/report.js"></script>
	<title>Contextual Harassment</title>
</head>
<body>

<div id='introWrap'>
	<div id='nav'>
		<a href='index.html'>Home</a>
		 -- 
		<a href='report.php'>Bugs/Ideas</a>
	</div>
	<h1>Bugs and Suggestions</h1>
	<p>
	To report a bug or to make a suggestion, please fill out the form below. This isn't a part of the game, so please try to separate the kind of comments that might win you points from the report. This part should be taken seriously. (That's the goal, anyway)
	</p>
	<p>
		Please read through these reports before posting your own report. Duplicate or similar entries will be deleted. You can help decide which reports get the most attention through the voting process. Next to each report is a <strong>+</strong> sign and a <strong>-</strong> sign. Upvoting pushes a report closer to the top, and downvoting buries it near the bottom. This will help decide the priority of a given report, as well as how much a suggestion might help create a better game and experience.
	</p>
	<hr />
	<div class='column' id='left-side'>
		<p>
			Thanks for using the reports page.
		</p>
		<p>
			Before you file your report, please look to the list on the right and see if someone has already filed a similar report. If so, the instructions on the next half of the page should help you further.Please leave a detailed message about your problem or suggestion. The clearer your report, the easier it will be to craft a solution. You get 1500 characters, so there should be plenty of space.
		</p>
		<hr />
		<form action='.' method='post' id='report-form'>
			<label for='message'style='position:relative;bottom: 150px;'>Your message: </label>
			<textarea maxlength=1500 cols=33 rows=10 name='message' id='report-message' required></textarea>
			<input type='submit' value='submit' name='report-submit' style='float:right;'/>
		</form>	
	</div>
	<div class='column' id='right-side'>
		<h3>User Reports</h3>
		<p>
			<strong>Be cool.</strong> Try to only vote for one time per report. This page is to try to help create a better game so people can have fun. 
			<br /><br />
			<em>Don't be a jerk</em>.
		</p>
		<hr />
		<div id='report-comments'>
		<?php
			$pointClass = '';
			$counter = 0;
			while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
				if ($row['points'] > 0){
					$pointClass = "pos";
				}
				elseif ($row['points'] < 0){
					$pointClass = "neg";
				}
				else{
					$pointClass = "zero";
				}
				
				echo "<div class='comment' id='com".$counter."' point-data='".$row['points']."'>
						<div class='mod' com-data='".$counter."'>
							<a href='.' class='plus mod-point' plus-attr='".$row['id']."'>+</a>
							<br />
							<br />
							<a href='.' class='minus mod-point' min-attr='".$row['id']."'>-</a>
						</div>
						<div class='message'>
							<span class='points'>Points: <span id='".$row['id']."' class='$pointClass'> ".$row['points']."</span></span>
							<hr />
							<p>
							".stripslashes($row['content'])."
							</p>
						</div>
					</div>";
					$counter ++;
			}
		?>
		</div>
	</div>
	<div class='clear'></div>
</div>

</body>
</html>
