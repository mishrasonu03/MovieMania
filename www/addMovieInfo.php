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
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
	Title: <input type="text" name="title" required><br><br>
	Year: <input type="text" name="year" required>	<br><br>
	Company: <input type="text" name="company"><br><br>
	
MPAA Rating: 
	<?php
	$allRatingQuery = "SELECT DISTINCT rating FROM Movie ORDER BY rating";
	$allRatingInfo = mysql_query($allRatingQuery, $db_connection);
	while($row = mysql_fetch_row($allRatingInfo))
		echo "<input type='radio' name='rating' value=$row[0] required>$row[0] ";
	?>
	<br><br>
	Genre: 
	<select id="genre" name="genre" multiple>
	<?php
		$allGenreQuery = "SELECT DISTINCT genre FROM MovieGenre ORDER BY genre";
		$allGenreInfo = mysql_query($allGenreQuery, $db_connection);
		while($row = mysql_fetch_row($allGenreInfo))
			echo "<option value=$row[0] required>$row[0]</option>";
	?>
	</select>
<br><br>
<input type="submit" value="Add Movie"/>
</form>

<?php 

$set = 0;
if (isset($_GET["title"]) and isset($_GET["year"]) and isset($_GET["genre"]) and isset($_GET["rating"])) $set = 1;

if ($set==1) {

	$title = mysql_real_escape_string($_GET["title"]);
	$year = (int) $_GET["year"];
	$company = mysql_real_escape_string($_GET["company"]);
	$genre = mysql_real_escape_string($_GET["genre"]);
	$rating = mysql_real_escape_string($_GET["rating"]);
	
	$maxIDQuery = "SELECT id FROM MaxMovieID";
	$maxIDInfo = mysql_query($maxIDQuery, $db_connection);

	$maxID = mysql_fetch_row($maxIDInfo);
	$mid = $maxID[0] + 1;

	$movieInsert = "INSERT INTO Movie VALUES($mid, '$title', $year, '$rating', '$company');";
	mysql_query($movieInsert, $db_connection);
	
	$maxIDUpdate = "UPDATE MaxMovieID SET id = $mid";
	mysql_query($maxIDUpdate, $db_connection);
	
	$genreInsert = "INSERT INTO MovieGenre VALUES($mid, '$genre')";
	mysql_query($genreInsert, $db_connection);

	echo "Movie added successfully!";
}
mysql_close($db_connection);
?>

</body>
</html>