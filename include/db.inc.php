<?php
function connect()
{
	$db = new mysqli("localhost", "root", "", "advent");

	if ($db->connect_errno) {
		alert("danger", "Verbindung fehlgeschlagen: " . $db->connect_error);
		die();
	}
	$db->query("SET NAMES utf8");
	return $db;
}
