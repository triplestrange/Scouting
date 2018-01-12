<?php
// SQL query script
// Original base script at:
// https://github.com/PenguinSnail/mysql-slack

// Domain the database and scouting app are running on
// Must include the path to the scouting app scripts and a trailing slash
$domain = "http://domain.site/scouting/";

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
} elseif ($oper == "average") {
	$args = preg_replace("/[^0-9,.]/", "", strtok($args, " "));
	$query = "select (select avg(autoSwitch) from team_" . $args . "), (select avg(autoScale) from team_" . $args . "), (select avg(teleSwitch) from team_" . $args . "), (select avg(teleScale) from team_" . $args . "), (select avg(returnCubes) from team_" . $args . ")";
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

if ($result == "") {
	die("No data found :(");
}

// create csv contents
$i = 0;
while($row = $result->fetch_assoc()) {
	if ($i == 0) {
		$i++;
   		foreach ($row as $key => $value) {
			if ($oper == "average") {
				$key = substr($key, 1, -1);
				preg_match( '!\(([^\)]+)\)!', $key, $match);
				$key = $match[1];
			}
       		$csvcont = $csvcont . $key;
			$csvcont = $csvcont . ",";
		}
	}
	$csvcont = $csvcont . "\n";
	foreach ($row as $value) {
		$csvcont = $csvcont . $value;
		$csvcont . ",";
	}
}

//Write to csv file
if ($args == "" ) {
	$file = $oper . ".csv";
} else {
	$file = $oper . "_" . $args . ".csv";
}
file_put_contents("./csv/" . $file, $csvcont);

// build $output string
$i = 0;
while($row = $result->fetch_assoc()) {
	if ($i == 0) {
		$i++;
   		foreach ($row as $key => $value) {
			if ($oper == "average") {
				$key = substr($key, 1, -1);
				preg_match( '!\(([^\)]+)\)!', $key, $match);
				$key = $match[1];
			}
       		$output = $output . str_pad($key,11," ");
			$output = $output . " | ";
		}
	}
	$output = $output . "\n";
	foreach ($row as $value) {
		$output = $output . str_pad($value,15," ");
		$output . " | ";
	}
}

// JSON Formatted output
echo	"{\"attachments\": [{";
echo 		"\"pretext\": \"" . $output . "\"";
echo	"},{";
echo		"\"fallback\": \"HTML Table\",";
echo		"\"color\": \"#0066ff\",";
echo 		"\"title\": \"Webpage Table\",";
echo 		"\"title_link\": \"" . $domain . "html-table.php?oper=" . $oper . "&args=" . $args . "\"";
echo 	"}, {";
echo 		"\"fallback\": \"CSV File\",";
echo 		"\"color\": \"#36a64f\",";
echo 		"\"title\": \"CSV File\",";
echo 		"\"title_link\": \"" . $domain . "csv/" . $file . "\"";
echo 	"}]}";

die();

?>
