<?php
// SQL query script
// Original base script at:
// https://github.com/PenguinSnail/mysql-slack

// Domain the database and scouting app are running on
// Must include the path to the scouting app scripts and a trailing slash
$domain = "http://domain.site/scouting/";

// URL to your Slack Incoming Webhook
$webhook = "https://hooks.slack.com/XXXXXX";

// Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$username = $_POST['user_name'];
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
$csvcont = "";
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
$filewrite = "./csv/" . $file;
file_put_contents($filewrite, $csvcont);

// build $output string
$i = 0;
$output = "";
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

// Build JSON from arrays
$data = array(
	"username" => "Scouting Database",
	"channel" => "@" . $username,
	"text" => $output,
	"attachments" => array(
		array(
			"fallback" => "HTML Table",
			"color" => "#0066ff",
			"title" => "Webpage Table",
			"title_link" => $domain . "html-table.php?oper=" . $oper . "&args=" . $args
		), array(
			"fallback" => "CSV Table",
			"color" => "#36a64f",
			"title" => "CSV File",
			"title_link" => $domain . "csv/" . $file
		)
	)
);
$payload = json_encode($data);

/*
// JSON Formatted output
$payload = "{\"channel\":\"@" . $username . "\", \"username\":\"Scouting Database\", \"text\": \"" . $output . "\", \"attachments\": [{";
$payload = $payload . "\"fallback\": \"HTML Table\",";
$payload = $payload . "\"color\": \"#0066ff\",";
$payload = $payload . "\"title\": \"Webpage Table\",";
$payload = $payload . "\"title_link\": \"" . $domain . "html-table.php?oper=" . $oper . "&args=" . $args . "\"";
$payload = $payload . "}, {";
$payload = $payload . "\"fallback\": \"CSV File\",";
$payload = $payload . "\"color\": \"#36a64f\",";
$payload = $payload . "\"title\": \"CSV File\",";
$payload = $payload . "\"title_link\": \"" . $domain . "csv/" . $file . "\"";
$payload = $payload . "}]}";
*/

// Issue cURL command
$slack_call = curl_init($webhook);
curl_setopt($slack_call, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($slack_call, CURLOPT_POSTFIELDS, $payload);
curl_setopt($slack_call, CURLOPT_CRLF, true);
curl_setopt($slack_call, CURLOPT_RETURNTRANSFER, true);
curl_setopt($slack_call, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Content-Length: " . strlen($payload))
);
curl_exec($slack_call);
curl_close($slack_call);

die();

?>
