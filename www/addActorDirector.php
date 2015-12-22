<!DOCTYPE html>
<html>
<head>
	<title>ActorDirector</title>
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

<p class="padding"><h2>Add New Actor/Director Information</h2></p>

<?php 
	$db_connection = mysql_connect("localhost", "cs143", "");
	if (!$db_connection)
		die("Connection Failed : ".mysql_error());
		
	$db_selected = mysql_select_db("CS143", $db_connection);
	if (!$db_selected)
		die ('<br>Cannot use CS143 : ' . mysql_error());
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
	Identity: <select name="identity" required>
		<option value="Actor">Actor</option>
		<option value="Director">Director</option>
		<option value="Both">Both</option>
	</select><br><br>

	First Name: <input type="text" name="first" required><br><br>
	Last Name: <input type="text" name="last" required>	<br><br>
	Sex: 
	<?php
	$allSexQuery = "SELECT DISTINCT sex FROM Actor";
	$allSexInfo = mysql_query($allSexQuery, $db_connection);
	while($row = mysql_fetch_row($allSexInfo))
		echo "<input type='radio' name='sex' value=$row[0] required>$row[0] ";
	?>
	<br><br>
	Date of Birth: <input type="date" name="dob" required><br><br>
	Still Alive?: 
		<input type="radio" name="alive" value="Yes" required>Yes 
		<input type="radio" name="alive" value="No">No <br>
	Date of Death: <input type="date" name="dod"><br><br>

	<input type="submit" value="Add Actor/Director"/>
</form>

<?php 
$set = 0;
if (isset($_GET["identity"]) and isset($_GET["first"]) and isset($_GET["last"]) and isset($_GET["sex"]) and isset($_GET["dob"])) $set = 1;
if ($set==1) {
	$alive = $_GET["alive"];
	$identity = $_GET["identity"];
	$first = mysql_real_escape_string($_GET["first"]);
	$last = mysql_real_escape_string($_GET["last"]);
	$sex = $_GET["sex"];
	$dob = date("Y-m-d", strtotime($_GET["dob"]));
	$dod = date("Y-m-d", strtotime($_GET["dod"]));

	$maxIDQuery = "SELECT id FROM MaxPersonID";
	$maxIDInfo = mysql_query($maxIDQuery, $db_connection);

	$maxID = mysql_fetch_row($maxIDInfo);
	$pid = $maxID[0] + 1;
//	echo $pid;
	
	if ($identity=="Actor"){	
		if ($alive == "Yes")		
			$actorInsert = "INSERT INTO Actor VALUES($pid, '$last', '$first', '$sex', '$dob', NULL)";
		else {
			$actorInsert = "INSERT INTO Actor VALUES($pid, '$last', '$first', '$sex', '$dob', '$dod')";
		}
		mysql_query($actorInsert, $db_connection);
	}
	else if ($identity=="Director"){	
		if ($alive == "Yes")		
			$directorInsert = "INSERT INTO Director VALUES($pid, '$last', '$first', '$dob', NULL)";
		else {
			$directorInsert = "INSERT INTO Director VALUES($pid, '$last', '$first', '$dob', '$dod')";
		}
		mysql_query($directorInsert, $db_connection);
	}
	else {	
		if ($alive == "Yes"){
			$actorInsert = "INSERT INTO Actor VALUES($pid, '$last', '$first', '$sex', '$dob', NULL)";		
			$directorInsert = "INSERT INTO Director VALUES($pid, '$last', '$first', '$dob', NULL)";
		}
		else {
			$actorInsert = "INSERT INTO Actor VALUES($pid, '$last', '$first', '$sex', '$dob', '$dod')";
			$directorInsert = "INSERT INTO Director VALUES($pid, '$last', '$first', '$dob', '$dod')";
		}
		mysql_query($actorInsert, $db_connection);
		mysql_query($directorInsert, $db_connection);
	}

	$maxIDUpdate = "UPDATE MaxPersonID SET id = $pid";
	mysql_query($maxIDUpdate, $db_connection);
	
	echo "Person added successfully!";
}
mysql_close($db_connection);
?>

</body>
</html>