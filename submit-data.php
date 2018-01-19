<?php

header( "refresh:3;url=index.html" );

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "scouting";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Read POST values
// Initial numbers
// Only allow numeric characters
$teamNum = preg_replace("/[^0-9,.]/", "", $_POST['teamNum']);
$matchNum = preg_replace("/[^0-9,.]/", "", $_POST['matchNum']);
$startPos = $_POST['startPos'];

// End if no match number
if ($matchNum === "") {
	die("ERROR: No match number");
}

// End if no team number
if ($teamNum === "") {
        die("ERROR: No team number");
}

// Autonomous movement
$autoLine = $_POST['autoLine'];

// Autonomous switch
$autoSwitch = preg_replace("/[^0-9,.]/", "", $_POST['autoSwitch']);

// Autonomous scale
$autoScale = preg_replace("/[^0-9,.]/", "", $_POST['autoScale']);

// Teleop switch
$teleSwitch = preg_replace("/[^0-9,.]/", "", $_POST['teleSwitch']);

// Teleop scale
$teleScale = preg_replace("/[^0-9,.]/", "", $_POST['teleScale']);

// Cubes returned
$returnCubes = preg_replace("/[^0-9,.]/", "", $_POST['returnCubes']);

// Yellow/Red cards
$yellowCards = preg_replace("/[^0-9,.]/", "", $_POST['yellowCards']);
$redCards = preg_replace("/[^0-9,.]/", "", $_POST['redCards']);

// Was levitate used
$levitate = $_POST['levitate'];

// Parked or climbed
$endPos = $_POST['endPos'];

// Additional notes
$notes = $_POST['notes'];

// Define match number based table creation
$match_table = "CREATE TABLE IF NOT EXISTS `match_".$matchNum."` (
teamNum INT,
position TEXT,
autoLine TEXT,
autoSwitch INT,
autoScale INT,
teleSwitch INT,
teleScale INT,
returnCubes INT,
levitate TEXT,
endPos TEXT,
yellowCards INT,
redCards INT
)";

// Create table for match if necessary
if ($conn->query($match_table) === TRUE) {
	echo "Match Table: OK";
}

echo nl2br ("\n");

// Define data write function
$match_data = "INSERT INTO `match_".$matchNum."` ". "(
teamNum,
position,
autoLine,
autoSwitch,
autoScale,
teleSwitch,
teleScale,
returnCubes,
levitate,
endPos,
yellowCards,
redCards
) ". "VALUES(
'$teamNum',
'$startPos',
'$autoLine',
'$autoSwitch',
'$autoScale',
'$teleSwitch',
'$teleScale',
'$returnCubes',
'$levitate',
'$endPos',
'$yellowCards',
'$redCards'
)";

// Write data to table
if ($conn->query($match_data) === TRUE) {
        echo "Match data written successfully";
}

echo nl2br ("\n");
echo nl2br ("\n");

// Define match number based table creation
$team_table = "CREATE TABLE IF NOT EXISTS `team_".$teamNum."` (
	matchNum INT,
	position TEXT,
	autoLine TEXT,
	autoSwitch INT,
	autoScale INT,
	teleSwitch INT,
	teleScale INT,
	returnCubes INT,
	levitate TEXT,
	endPos TEXT,
	yellowCards INT,
	redCards INT
	)";
	
	// Create table for match if necessary
	if ($conn->query($team_table) === TRUE) {
		echo "Team Table: OK";
	}
	
	echo nl2br ("\n");
	
	// Define data write function
	$team_data = "INSERT INTO `team_".$teamNum."` ". "(
	matchNum,
	position,
	autoLine,
	autoSwitch,
	autoScale,
	teleSwitch,
	teleScale,
	returnCubes,
	levitate,
	endPos,
	yellowCards,
	redCards
	) ". "VALUES(
	'$matchNum',
	'$startPos',
	'$autoLine',
	'$autoSwitch',
	'$autoScale',
	'$teleSwitch',
	'$teleScale',
	'$returnCubes',
	'$levitate',
	'$endPos',
	'$yellowCards',
	'$redCards'
	)";
	
	// Write data to table
	if ($conn->query($team_data) === TRUE) {
			echo "team data written successfully";
	}	

	echo nl2br ("\n");
	echo nl2br ("\n");
	
// Define match number based table creation
$notes_table = "CREATE TABLE IF NOT EXISTS `notes_".$teamNum."` (
	matchNum INT,
	notes TEXT
	)";
	
	// Create table for match if necessary
	if ($conn->query($notes_table) === TRUE) {
		echo "Team Notes Table: OK";
	}

	echo nl2br ("\n");
	
	// Define data write function
	$notes_data = "INSERT INTO `notes_".$teamNum."` ". "(
	matchNum,
	notes
	) ". "VALUES(
	'$matchNum',
	'$notes'
	)";
	
	// Write data to table
	if ($conn->query($notes_data) === TRUE) {
		echo "team notes written successfully";
	}

?>
