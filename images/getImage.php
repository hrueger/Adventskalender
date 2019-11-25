<?php 
	require_once("../include/lib.inc.php");
	if (isset($_GET["d"])&&isset($_GET["m"])&&($_GET["m"]=="a"||$_GET["m"]=="l")) {
		$allow = checkForDate($_GET["d"]);
		$day = $_GET["d"];
		$day = sprintf('%02d', $day);
		if ($allow == "past" && $_GET["m"]=="l") {
			header('Content-Type: image/jpeg');
			$f = "./tasks/ding_".$day."_loes.jpg";
			if (is_file($f)) {
				readfile($f);
			}
			
			die();
		} else if ($allow=="today" && $_GET["m"]=="a") {
			$f = "./tasks/ding_".$day.".jpg";
			header('Content-Type: image/jpeg');
			if (is_file($f)) {
				readfile($f);
			}
			die();
		}
	}
		

?>