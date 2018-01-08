<?php
// SQL query script
// Original base script at:
// https://github.com/PenguinSnail/mysql-slack

// Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];

// MySQL vars
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "scouting";

// Check the token and make sure the request is from our team
if($token != 'XXXXXXXXXX'){
    $msg = "The token for the slash command doesn't match. Check your script.";
    die($msg);
}

// Extract operation from passed text
$oper = strtok($text, " ");
$oper = strtolower($oper);

// Extract remaining arguments from passed text
$words = explode(' ', $text);
$words = array_slice($words, 1);
$args = implode(' ', $words);

// Determine operation and set query
if ($oper == "query") {
	if (strtok($args, " ") == "drop") {
		die("I'm afraid I can't let you do that...");
	} else {
		$query = $args;
	}
} elseif ($oper == "match") {
	$args = preg_replace("/[^0-9,.]/", "", strtok($args, " "));
	$query = "select * from match_" . $args;
} elseif ($oper == "team") {
	$args = preg_replace("/[^0-9,.]/", "", strtok($args, " "));
	$query = "select * from team_" . $args;
} elseif ($oper == "notes") {
	$args = preg_replace("/[^0-9,.]/", "", strtok($args, " "));
	$query = "select * from notes_" . $args;
} elseif ($oper == "list") {
	$args = strtok($args, " ");
	if (strpos($args, "team") !== false) {
		$query = "show tables like 'team\_%'";
	} elseif (strpos($args, "match") !== false) {
		$query = "show tables like 'match\_%'";
	} elseif (strpos($args, "note") !==false) {
		$query = "show tables like 'notes\_%'";
	} else {
		$query = "show tables";
	}
} else {
	die("List/Match/Team/Notes/Query - List tables/Match data/Team data/Team notes/Query database");
}

// Check connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Run query
if (!$result = $conn->query($query)) {
	die("ERROR: " . $conn->error);
}

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
