<!DOCTYPE html>
<html>
<head>
	<title>Actor</title>
	<style>
		p.padding {
		padding-top: 00px;
		padding-right: 50px;
		padding-bottom: 20px;
		padding-left: 50px;
	}
	</style>
</head>	

<body bgcolor="#FFFAF0">

<p class="padding"><h2>Actor Information</h2></p>

<?php
	$db_connection = mysql_connect("localhost", "cs143", "");
	if (!$db_connection)
		die("Connection Failed : ".mysql_error());
		
	$db_selected = mysql_select_db("CS143", $db_connection);
	if (!$db_selected)
		die ('<br>Cannot use CS143 : ' . mysql_error());

	if ($_GET['id']) $actID = $_GET['id'];
	else $actID = 15927;
	
	$actQuery = "SELECT first, last, sex, dob, dod FROM Actor WHERE id = $actID";
	$actPersonalInfo = mysql_query($actQuery, $db_connection);

	if ($actPersonalInfo && mysql_num_rows($actPersonalInfo) > 0) {
		$actor = mysql_fetch_row($actPersonalInfo);

		echo "<h2>$actor[0] $actor[1]</h2>";
		echo "<b>Sex: </b> $actor[2]<br/>";

		$dob = date("M d, Y", strtotime($actor[3]));
		echo "<b>Born:</b> $dob<br/>";
		if ($actor[4]){
			$dod = date("M d, Y", strtotime($actor[4]));
			echo " <b>Died:</b> $dod<br/>";
		}
		else {
			$date1=date_create("$actor[3]");
			$date2=date_create(date());
			$diff=date_diff($date1,$date2);
			$age=$diff->format("%Y years");
			echo " <b>Age:</b>$age<br/>";
		}
	} 

	$actMovieQuery = "SELECT Movie.title, MovieActor.role, Movie.id, Movie.rating FROM MovieActor, Movie WHERE MovieActor.aid = $actID AND Movie.id = MovieActor.mid";
	$actMovieInfo = mysql_query($actMovieQuery, $db_connection);

	if ($actMovieInfo && mysql_num_rows($actMovieInfo) > 0) {
		echo "<h3>Acted as:<br></h3>";
		while ($movie = mysql_fetch_row($actMovieInfo)){	
			echo "\"$movie[1]\" in ";
			echo "<a href = './showMovieInfo.php?id=$movie[2]'> $movie[0]</a> </br>";
		}	
	} 


mysql_close($db_connection);

?>
</body>
</html>