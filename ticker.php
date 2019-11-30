<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>AG-Ventskalender</title>
	<style>
		* {
			margin: 0px;
			padding: 0px;
		}
		
		td {
			/*height: 100%;
			width: 100%;*/
			padding: 5px;
			margin: 5px;
		}
		.header {
			margin-right: 10px;
		}
		img {
			padding: 3px;
		}
		
	</style>
	<link rel="stylesheet" href="include/lib/bootstrap/bootstrap.min.css">
	<script src="./include/lib/bootstrap/jquery.min.js" crossorigin="anonymous"></script>
</head>

<body>
	<?php require_once("./include/lib.inc.php");
	if (checkForDate(1) == "future") {
		?>
	<img class="header img img-fluid" src="images/header_lang.png">
	<h1>Der AG-Ventskalender</h1>

	<?php
	alert( "info", "<h3>Meldet euch jetzt an und gewinnt tolle Preise!</h3>" );
	}
	else {
		?>
	<?php 
	
	$images = array();
	for ($i=1;$i<25;$i++) {
		if (checkForDate($i)=="today") {
			if ($i<10) {
				$i = "0".$i;
			}
			$images[] = $i;
		}
	}
		
	?>
	<table>
	<tr>
		<td><?php echo (isset($images[0]))?'<img class="img img-fluid image" src="images/getImage.php?d='.$images[0].'&m=a">':"";?></td>
		<td><?php echo (isset($images[1]))?'<img class="img img-fluid image" src="images/getImage.php?d='.$images[1].'&m=a">':"";?></td>
		<td rowspan="2"><img class="header" src="images/header_gedreht.jpg"></td>
	</tr>
	<tr>
		<td><?php echo (isset($images[2]))?'<img class="img img-fluid image" src="images/getImage.php?d='.$images[2].'&m=a">':"";?></td>
		<td><?php echo (isset($images[3]))?'<img class="img img-fluid image" src="images/getImage.php?d='.$images[3].'&m=a">':"";?></td>
	</tr>
	</table>
	
	<script>
		$( document ).ready(function() {
			resized();
			
		
			
		});
		$( window ).resize(function() {
			 resized();
			});
		function resized() {
			var height = $( window ).height();
			$(".image").css("width", height/2);
			$(".image").css("height", height/2);
			$(".header").css("height", height);
		}
	</script>

	<?php } ?>
</body>
</html>