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
$auto-switch = $_POST['auto-switch'];

// Autonomous scale
$auto-scale = $_POST['auto-scale'];

// Teleop switch
$tele-switch = $_POST['tele-switch'];

// Teleop scale
$tele-scale = $_POST['tele-scale'];

// Cubes returned
$return-cubes = $_POST['return-cubes'];

// Was levitate used
$levitate = $_POST['levitate'];

// Parked or climbed
$end-pos = $_POST['end-pos'];

// Define match number based table creation
$table = "CREATE TABLE IF NOT EXISTS `match_".$matchNum."` (
teamNum INT,
position TEXT,
auto-line TEXT,
auto-switch INT,
auto-scale INT,
tele-switch INT,
tele-scale INT,
return-cubes INT,
levitate TEXT,
end-pos TEXT
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
auto-line,
auto-switch,
auto-scale,
tele-switch,
tele-scale,
return-cubes,
levitate,
end-pos
) ". "VALUES(
'$teamNum',
'$startPos',
'$auto-line',
'$auto-switch',
'$auto-scale',
'$tele-switch',
'$tele-scale',
'$return-cubes',
'$levitate',
'$end-pos'
)";

// Write data to table
if ($conn->query($data) === TRUE) {
        echo "Data written successfully";
}

?>
