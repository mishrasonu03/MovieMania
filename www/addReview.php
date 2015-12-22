<!DOCTYPE html>
<html>
<head>
	<title>Review</title>
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

<p class="padding"><h2>Add Review</h2></p>

<?php 
	$db_connection = mysql_connect("localhost", "cs143", "");
	if (!$db_connection)
		die("Connection Failed : ".mysql_error());
		
	$db_selected = mysql_select_db("CS143", $db_connection);
	if (!$db_selected)
		die ('<br>Cannot use CS143 : ' . mysql_error());
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
	Your Full Name: <input type="text" name="reviewer"><p>

	Movie:
	<select id="movie" name="movie">
	<?php
		$allMovieQuery = "SELECT title FROM Movie ORDER BY title";
		$allMovieInfo = mysql_query($allMovieQuery, $db_connection);
		while($row = mysql_fetch_assoc($allMovieInfo)){
			$mov = $row['title'];	
			echo "<option value='$mov' required>$mov</option>";
		}
	?>
	</select>

	Rating: 
		<input type="radio" name="rating" value="1">1
		<input type="radio" name="rating" value="2">2
		<input type="radio" name="rating" value="3">3
		<input type="radio" name="rating" value="4">4
		<input type="radio" name="rating" value="5">5 <p>
	Comment:<br>  
		<textarea name="comment" cols="75" rows="10"></textarea><p>

	<input type="submit" value="Add Comment"/>
</form>

<?php
	$set=0;
	if (isset($_GET["movie"]) and isset($_GET["rating"]) and isset($_GET["comment"]) and isset($_GET["reviewer"])) $set=1; 
	if ($set==1){
		$rating = $_GET['rating'];
		$movie = $_GET['movie'];
		$reviewer = mysql_real_escape_string($_GET['reviewer']);
		$comment = mysql_real_escape_string($_GET['comment']);

		$movieIDQuery = "SELECT id FROM Movie WHERE title = '$movie'";
		$movieIDInfo = mysql_query($movieIDQuery, $db_connection);
		$row = mysql_fetch_assoc($movieIDInfo);
		$movieID = $row['id'];

		$insertReview = "INSERT INTO Review VALUES('$reviewer', NOW(), '$movieID', '$rating', '$comment')";
		mysql_query($insertReview, $db_connection);
		echo "Thank you for your review";		
	}
mysql_close($db_connection);

?>
</body>
</html>