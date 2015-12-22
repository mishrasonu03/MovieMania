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
	Actor:
	<select id="actor" name="actor">
	<?php
		$allActorQuery = "SELECT concat(first, ' ', last) as name FROM Actor ORDER BY name";
		$allActorInfo = mysql_query($allActorQuery, $db_connection);
		while($row = mysql_fetch_assoc($allActorInfo)){
			$act = $row['name'];
			echo "<option value='$act' required>$act</option>";
		}
	?>
	</select>

	<p>
	Role: <input type="text" name="role" required><br><br>

	<input type="submit" value="Add Movie-Actor"/>
</form>

<?php 
$set = 0;
if (isset($_GET["movie"]) and isset($_GET["actor"]) and isset($_GET["role"])) $set = 1;

if ($set==1) {
	$actor = $_GET["actor"];
	$movie = $_GET["movie"];
	$role = mysql_real_escape_string($_GET["role"]);
	
	$actorIDQuery = "SELECT id FROM Actor WHERE concat(first, ' ', last) = '$actor'";
	$actorIDInfo = mysql_query($actorIDQuery, $db_connection);
	$row = mysql_fetch_assoc($actorIDInfo);
	$actorID = $row['id'];

	$movieIDQuery = "SELECT id FROM Movie WHERE title = '$movie'";
	$movieIDInfo = mysql_query($movieIDQuery, $db_connection);
	$row = mysql_fetch_assoc($movieIDInfo);
	$movieID = $row['id'];

	$insertActorMovie = "INSERT INTO MovieActor VALUES ('$movieID','$actorID','$role')";
	mysql_query($insertActorMovie);

	echo "Movie-Actor added successfully!";
}
mysql_close($db_connection);
?>

</body>
</html>