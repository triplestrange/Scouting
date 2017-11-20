<?php

$servername = "localhost";
$username = "sql-user";
$password = "sql-password";
$dbname = "sql-database";

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

// Jewel removal
$jewelMov = $_POST['jewelMov'];

// Autonomous glyph count
$glyphA = $_POST['glyphA'];
// Teleop glyph count
$glyphT = $_POST['glyphT'];
// Glyph in cryptobox key?
$cryptoKey = $_POST['cryptoKey'];

// Parked in the save zone? (yes/no)
$safeZone = $_POST['safeZone'];

// Cryptobox Rows and Columns
$cryptoR = $_POST['cryptoR'];
$cryptoC = $_POST['cryptoC'];

// Completed cipher count
$ciphers = $_POST['ciphers'];

// Relic position and standing
$relicPos = $_POST['relicPos'];
$relicStand = $_POST['relicStand'];

// Balancing stone
$platform = $_POST['platform'];

// Define match number based table creation
$table = "CREATE TABLE IF NOT EXISTS `match_".$matchNum."` (
teamNum INT,
position TEXT,
jewel TEXT,
auto_glyphs INT,
cryptobox_key TEXT,
safe_zone TEXT,
tele_glyphs INT,
crypto_rows INT,
crypto_columns INT,
ciphers INT,
relic_position INT,
relic_standing TEXT,
balance_platform TEXT
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
jewel,
auto_glyphs,
cryptobox_key,
safe_zone,
tele_glyphs,
crypto_rows,
crypto_columns,
ciphers,
relic_position,
relic_standing,
balance_platform
) ". "VALUES(
'$teamNum',
'$startPos',
'$jewelMov',
'$glyphA',
'$cryptoKey',
'$safeZone',
'$glyphT',
'$cryptoR',
'$cryptoC',
'$ciphers',
'$relicPos',
'$relicStand',
'$platform'
)";

// Write data to table
if ($conn->query($data) === TRUE) {
        echo "Data written successfully";
}

?>
