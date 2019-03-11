<?php
	function connect()  {
		$db = new mysqli("localhost", "allgymadv", "rTBX5QdjKZfiWsU9k4w5LgEL", "allgymadv1");

		if ($db->connect_errno) {
			alert("danger", "Verbindung fehlgeschlagen: " . $db->connect_error);
			die();
		}
		$db->query("SET NAMES utf8");
		return $db;
	}
	//echo "connected to database!";
?>