<?php

// Values
$oper = $_GET['oper'];
$args = $_GET['args'];

// MySQL vars
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "scouting";

// Determine operation and set query
if ($oper == "query") {
	$query = $args;    
} elseif ($oper == "match") {
	$query = "select * from match_" . $args;
} elseif ($oper == "team") {
	$query = "select * from team_" . $args;
} elseif ($oper == "notes") {
	$query = "select * from notes_" . $args;
} elseif ($oper == "list") {
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
	$query = "select (select avg(autoSwitch) from team_" . $args . "), (select avg(autoScale) from team_" . $args . "), (select avg(teleSwitch) from team_" . $args . "), (select avg(teleScale) from team_" . $args . "), (select avg(returnCubes) from team_" . $args . ")";
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

echo "Result of:";
echo "<br>\n";
echo $oper . " " . $args;

// Found at:
// https://stackoverflow.com/a/37574368
echo "<table border=1>";
$i = 0;
while($row = $result->fetch_assoc())
{
    if ($i == 0) {
      $i++;
      echo "<tr>";
      foreach ($row as $key => $value) {
        echo "<th>" . $key . "</th>";
      }
      echo "</tr>";
    }
    echo "<tr>";
    foreach ($row as $value) {
      echo "<td>" . $value . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

?>