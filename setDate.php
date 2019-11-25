<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Heutigen Tag setzen</title>
</head>

<body>
	<?php
	session_start();
	if (isset($_POST["tag"])) {
		$_SESSION["heutigerTag"] = $_POST["tag"];
		echo "erfolgreich gespeichert!<br><br>";
	}
	if (isset($_SESSION["heutigerTag"])) {
		$day = $_SESSION["heutigerTag"];
	} else {
		$day = null;
	}
	?>

	<form method="post" id="">

		<select name="tag">

			<?php for ($i = 1; $i < 31; $i++) {
				if ($i < 10) {
					$j = "0" . $i;
				} else {
					$j = $i;
				}
				if ($day == $j) {
					echo "<option value='$j' selected>$j</option>";
				} else {
					echo "<option value='$j'>$j</option>";
				}
			}
			?>
		</select> <br>
		<button name="submit">Speichern</button>
	</form>
</body>
</html>