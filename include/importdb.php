<?php
require_once("./db.inc.php");
require_once("./lib.inc.php");
$filename = '../database.sql';

if (isset($_GET["del"])) {
    @unlink($filename);
    alert("success", "Die Datei wurde erfolgreich gelöscht. <a href='../administrator.php?a=importData' class='btn btn-warning'>Zurück</a>");
    die();
}
$db = connect() or die('Error connecting to MySQL server: ' . $db->error);
$db->query('SET foreign_key_checks = 0');
if ($result = $db->query("SHOW TABLES")) {
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $db->query('DROP TABLE IF EXISTS ' . $row[0]);
    }
}

$db->query('SET foreign_key_checks = 1');
$templine = '';
$lines = file($filename);
foreach ($lines as $line) {
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;
    $templine .= $line;
    if (substr(trim($line), -1, 1) == ';') {
        $db->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $db->error . '<br /><br />');
        $templine = '';
    }
}
unlink($filename);
alert("success", "Die Datei wurde erfolgreich importiert und alle Daten wiederhergestellt. <a href='../administrator.php?a=importData' class='btn btn-warning'>Zurück</a>");
