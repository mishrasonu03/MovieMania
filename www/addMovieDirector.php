<!DOCTYPE html>
<html>
<head>
	<title>MovieActor</title>
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

<p class="padding"><h2>Add an Actor to a Movie</h2></p>

<?php 
	$db_connection = mysql_connect("localhost", "cs143", "");
	if (!$db_connection)
		die("Connection Failed : ".mysql_error());
		
	$db_selected = mysql_select_db("CS143", $db_connection);
	if (!$db_selected)
		die ('<br>Cannot use CS143 : ' . mysql_error());
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">

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
	
	<p>
	Director:
	<select id="director" name="director">
	<?php
		$allDirectorQuery = "SELECT concat(first, ' ', last) as name FROM Director ORDER BY name";
		$allDirectorInfo = mysql_query($allDirectorQuery, $db_connection);
		while($row = mysql_fetch_assoc($allDirectorInfo)){
			$dir = $row['name'];
			echo "<option value='$dir' required>$dir</option>";
		}
	?>
	</select>
	<p>
	<input type="submit" value="Add Movie-Director"/>
</form>

<?php 
$set = 0;
if (isset($_GET["movie"]) and isset($_GET["director"])) $set = 1;

if ($set==1) {
	$director = $_GET["director"];
	$movie = $_GET["movie"];
	
	$directorIDQuery = "SELECT id FROM Director WHERE concat(first, ' ', last) = '$director'";
	$directorIDInfo = mysql_query($directorIDQuery, $db_connection);
	$row = mysql_fetch_assoc($directorIDInfo);
	$directorID = $row['id'];

	$movieIDQuery = "SELECT id FROM Movie WHERE title = '$movie'";
	$movieIDInfo = mysql_query($movieIDQuery, $db_connection);
	$row = mysql_fetch_assoc($movieIDInfo);
	$movieID = $row['id'];

	$insertDirector = "INSERT INTO MovieDirector VALUES ('$movieID','$directorID')";
	mysql_query($insertDirector);

	echo "Movie-Actor added successfully!";
}
mysql_close($db_connection);
?>

</body>
</html>