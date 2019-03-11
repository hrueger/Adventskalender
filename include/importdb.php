<?php
require_once("./db.inc.php");
require_once("./lib.inc.php");
// Name of the file
$filename = '../database.sql';

// Database name



if (isset($_GET["del"])) {
	@unlink($filename);
	alert("success", "Die Datei wurde erfolgreich gelöscht. <a href='../administrator.php?a=importData' class='btn btn-warning'>Zurück</a>");
	die();
}
// Connect to MySQL server
$db = connect() or die('Error connecting to MySQL server: ' . $db->error);
// Select database

$db->query('SET foreign_key_checks = 0');
if ($result = $db->query("SHOW TABLES"))
{
    while($row = $result->fetch_array(MYSQLI_NUM))
    {
        $db->query('DROP TABLE IF EXISTS '.$row[0]);
    }
}

$db->query('SET foreign_key_checks = 1');
// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
{
// Skip it if it's a comment
if (substr($line, 0, 2) == '--' || $line == '')
    continue;

// Add this line to the current segment
$templine .= $line;
// If it has a semicolon at the end, it's the end of the query
if (substr(trim($line), -1, 1) == ';')
{
    // Perform the query
    $db->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $db->error . '<br /><br />');
    // Reset temp variable to empty
    $templine = '';
}
}
unlink($filename);
 alert("success", "Die Datei wurde erfolgreich importiert und alle Daten wiederhergestellt. <a href='../administrator.php?a=importData' class='btn btn-warning'>Zurück</a>");



?>