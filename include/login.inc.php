<?php 
	if (!isset($_SESSION["loggedin"]) OR $_SESSION["loggedin"] != true) {
		header("Location: login.php");
	}


?>