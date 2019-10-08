<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Heutigen Tag setzen</title>
</head>

<body class="bgimg">
	
	
<?php 
	if (isset($_POST["tag"])) {
		file_put_contents("heutigerTag.txt", $_POST["tag"]);
		echo "erfolgreich gespeichert!<br>";
		
	}
	$day = file_get_contents("heutigerTag.txt");
	?>
	
<form method="post" id="">
  
<select name="tag">
  
 <?php for ($i=1;$i<31;$i++) {
			if ($i <10) {
				$j = "0".$i;
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