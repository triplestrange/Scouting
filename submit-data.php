<?php

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
$teamNum = $_POST['teamNum'];
$matchNum = $_POST['matchNum'];
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
$autoline = $_POST['autoline'];

// Autonomous switch
$autoSwitch = $_POST['autoSwitch'];

// Autonomous scale
$autoScale = $_POST['autoScale'];

// Teleop switch
$teleSwitch = $_POST['teleSwitch'];

// Teleop scale
$teleScale = $_POST['teleScale'];

// Cubes returned
$returnCubes = $_POST['returnCubes'];

// Was levitate used
$levitate = $_POST['levitate'];

// Parked or climbed
$endPos = $_POST['endPos'];

// Define match number based table creation
$table = "CREATE TABLE IF NOT EXISTS `match_".$matchNum."` (
teamNum INT,
position TEXT,
autoline TEXT,
autoSwitch INT,
autoScale INT,
teleSwitch INT,
teleScale INT,
returnCubes INT,
levitate TEXT,
endPos TEXT
)";

// Create table for match if necessary
if ($conn->query($table) === TRUE) {
	echo "Table: OK";
}

echo nl2br ("\n");

// Define data write function
$data = "INSERT INTO `match_".$matchNum."` ". "(
teamNum,
position,
autoLine,
autoSwitch,
autoScale,
teleSwitch,
teleScale,
returnCubes,
levitate,
endPos
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
'$endPos'
)";

// Write data to table
if ($conn->query($data) === TRUE) {
        echo "Data written successfully";
}

?>
