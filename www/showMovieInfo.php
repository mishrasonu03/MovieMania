<!DOCTYPE html>
<html>
<head>
	<title>Movie</title>
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

<p class="padding"><h2>Movie Information</h2></p>

<?php
	$db_connection = mysql_connect("localhost", "cs143", "");
	if (!$db_connection)
		die("Connection Failed : ".mysql_error());
		
	$db_selected = mysql_select_db("CS143", $db_connection);
	if (!$db_selected)
		die ('<br>Cannot use CS143 : ' . mysql_error());

	if ($_GET['id']) $movID = $_GET['id'];
	else $movID = 1995;
	
	$movQuery = "SELECT * FROM Movie WHERE id = $movID";
	$movInfo = mysql_query($movQuery, $db_connection);

	if ($movInfo && mysql_num_rows($movInfo) > 0) {
		$movie = mysql_fetch_row($movInfo);
		echo "<h2>$movie[1]</h2>";
		echo "<b>Year: </b> $movie[2]<br/>";
		echo "<b>MPAA Rating: </b> $movie[3]<br/>";
		echo "<b>Company: </b> $movie[4]<br/>";
	}

	$movDirQuery = "SELECT Director.* FROM Director, MovieDirector WHERE MovieDirector.did = Director.id AND MovieDirector.mid = $movID";
	$movDirInfo = mysql_query($movDirQuery, $db_connection);

	if ($movDirInfo && mysql_num_rows($movDirInfo) > 0) {
		echo "<b>Directed By: </b>";
		$count = 0;
		while ($director = mysql_fetch_row($movDirInfo)){
			if ($count >0) echo ", ";
			echo "$director[2] $director[1]";
			$dob = date("M d, Y", strtotime($director[3]));
			echo " (".$dob;
			if ($director[4]){
				$dod = date("M d, Y", strtotime($director[4]));
				echo "-$dod)";
			}
			else {
				$date1=date_create("$director[3]");
				$date2=date_create(date());
				$diff=date_diff($date1,$date2);
				$age=$diff->format("%Y years");
				echo ", Age: ".$age.")";
			}
			$count = $count+1;
		}
	}

	$movGenQuery = "SELECT * FROM MovieGenre WHERE mid = $movID";
	$movGenInfo = mysql_query($movGenQuery, $db_connection);

	if ($movGenInfo && mysql_num_rows($movGenInfo) > 0) {
		echo "<br><b>Genre: </b>";
		$count = 0;
		while ($genre = mysql_fetch_row($movGenInfo)){
			if ($count >0) echo ", ";
			echo "$genre[1]";
			$count = $count+1;			
		}
	}

	$movRatingQuery = "SELECT AVG(rating) as AvgRating FROM Review WHERE mid = $movID HAVING AvgRating>0";
	$movRatingInfo = mysql_query($movRatingQuery, $db_connection); 
	
	if ($movRatingInfo && mysql_num_rows($movRatingInfo) > 0) {
		$rating = mysql_fetch_row($movRatingInfo);
		echo "<p><b>Average Rating: </b> $rating[0] (out of 5)";
	}
	else	echo "No Rating";

	$movActQuery = "SELECT Actor.*, MovieActor.role FROM Actor, MovieActor WHERE mid = $movID AND aid = Actor.id ORDER BY first, last";
	$movActInfo = mysql_query($movActQuery, $db_connection);
	
	if ($movActInfo && mysql_num_rows($movActInfo) > 0) {
		echo "<br>";
		echo "<br><b>Actors: </b></br>";
		while ($actor = mysql_fetch_row($movActInfo)){
			echo "<a href = './showActorInfo.php?id=$actor[0]'> $actor[2] $actor[1]</a>";
			echo " as \"$actor[6]\" </br>";
		}
	}
	
	$movReviewQuery = "SELECT * FROM Review WHERE mid = $movID";
	$movReviewInfo = mysql_query($movReviewQuery, $db_connection);
	
	echo "<br><b>Reviews: </br></b>";

	echo "<a href = './addReview.php?id=$movID'> Add your review</a>";
	echo "<br><br>";	

	if ($movReviewInfo && mysql_num_rows($movReviewInfo)>0){
		while($review = mysql_fetch_row($movReviewInfo)){
			echo "<p> On $review[1], $review[0] rated this movie $review[3] stars<br>";
			echo "Comment: $review[4]</p>";	
		}
	} 
	else { 
		echo "<p> No reviews yet </p>"; 
	}
	
mysql_close($db_connection);

?>
</body>
</html>
