<?php 
	require_once("../include/lib.inc.php");
	if (isset($_GET["d"])&&isset($_GET["m"])&&($_GET["m"]=="a"||$_GET["m"]=="l")) {
		$allow = checkForDate($_GET["d"]);
		$day = $_GET["d"];
		if ($allow == "past" && $_GET["m"]=="l") {
			header('Content-Type: image/jpeg');
			$f = "./tasks/kalender_".$day."_loesung.jpg";
			if (is_file($f)) {
				readfile($f);
			}
			
			die();
		} else if ($allow=="today" && $_GET["m"]=="a") {
			$f = "./tasks/kalender_".$day."_aufgabe.jpg";
			header('Content-Type: image/jpeg');
			if (is_file($f)) {
				readfile($f);
			}
			die();
		}
	}
		

?>