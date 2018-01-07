// SQL query script
// Original base script at:
// https://github.com/PenguinSnail/mysql-slack

<?php

// Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];

// Check the token and make sure the request is from our team
if($token != 'XXXXXXXXXX'){
    $msg = "The token for the slash command doesn't match. Check your script.";
    die($msg);
    echo $msg;
}

// Extract operation from passed text
$oper = strtok($text, " ");
$oper = strtolower($oper);

// Extract remaining arguments from passed text
$words = explode(' ', $text);
$words = array_slice($words, 1);
$args = implode(' ', $words);

// If operation is "query" set query to $args
if ($oper == "query") {
    $query = $args;
} elseif ($oper == "match") {
    $query = "select * from match_" . $args;
} elseif ($oper == "team") {
    $query = "select * from team_" . $args;
} else {
    die("ERROR: Operation not recognized");
}

// MySQL vars
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Run query
$result = $conn->query($query);

// Return data
$i = 0;
while($row = $result->fetch_assoc())
{
    if ($i == 0) {
      $i++;
      foreach ($row as $key => $value) {
        echo str_pad($key,11," ");
	echo " | ";
      }
    }
echo ("\n");
	foreach ($row as $value) {
		echo str_pad($value,15," ");
		echo " | ";
	}
}

?>