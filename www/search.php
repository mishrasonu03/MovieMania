<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
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

<p class="padding"><h2>Search Actor/Movie</h2></p>

<?php 
	$db_connection = mysql_connect("localhost", "cs143", "");
	if (!$db_connection)
		die("Connection Failed : ".mysql_error());
		
	$db_selected = mysql_select_db("CS143", $db_connection);
	if (!$db_selected)
		die ('<br>Cannot use CS143 : ' . mysql_error());
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
	Name of the Actor or Movie: <input type="text" name="search" required><br><br>
<input type="submit" value="Search"/>
</form>

<?php 
$set = 0;
if (isset($_GET["search"])) $set = 1;

if ($set==1) {

	$search = mysql_real_escape_string($_GET["search"]);
	
	echo "<h3>Please wait...</h3>";
	echo "<h4>Searching among Actors...</h4>"; 

	$searchActor = "SELECT id, first, last, dob FROM Actor WHERE concat(first,' ',last) like '%$search%' ORDER BY first, last";
	$actorSearched = mysql_query($searchActor, $db_connection);

	if ($actorSearched && mysql_num_rows($actorSearched)>0){
		echo mysql_num_rows($actorSearched). " Actors Found<br>";
		while ($row = mysql_fetch_row($actorSearched)){
			$dob = date("M d, Y", strtotime($row[3]));
			echo "<a href = './showActorInfo.php?id=$row[0]'> $row[1] $row[2]</a>  ($dob) <br/>";
		}
	}
	else echo "No Actors Found";

	echo "<h4>Searching among Movies...</h4>"; 

	$searchMovie = "SELECT id, title, year FROM Movie WHERE title like '%$search%' ORDER BY title";
	$movieSearched = mysql_query($searchMovie, $db_connection);

	if ($movieSearched && mysql_num_rows($movieSearched)>0){
		echo mysql_num_rows($movieSearched). " Movies Found<br>";
		while ($row = mysql_fetch_row($movieSearched)){
			$year = date("M d, Y", strtotime($row[2]));
			echo "<a href = './showMovieInfo.php?id=$row[0]'> $row[1]</a>  ($year) <br/>";
		}
	}
	else echo "No Movies Found";


}
mysql_close($db_connection);
?>

</body>
</html>