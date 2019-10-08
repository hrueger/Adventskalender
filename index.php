<?php
require_once("./include/lib.inc.php");
require_once("./include/db.inc.php");

?>
<!DOCTYPE html>
<html lang="de">

<head>
	<?php getHead(); ?>
</head>

<body>
	<section class="bg-danger">
		<div class="container py-0">
			<?php echo getNav("index"); ?>
		</div>
	</section>
	<header class="bgimg fullheight text-center text-white d-flex">
		<div class="container my-auto">
			<div class="row">
				<div class="col mx-auto">
					<span class="font-weight-normal display-4 blackborder">Der etwas andere</span><br>
					<span class="font-weight-bold display-3 blackborder">AG-Ventskalender</span><br>
					<span class="font-weight-normal display-4 blackborder">des Allgäu-Gymnasiums</span>

					<?php
					if ($loggedin) {
						$db = connect();
						$userid = $db->real_escape_string($_SESSION["userid"]);
						?>
						<h2 class="blackborder">Willkommen <?php echo $_SESSION["nickname"]; ?></h2><br>

						<p><a class="btn btn-lg btn-primary" href="aufgaben.php#tab1" role="button">Aufgaben &amp; Lösungen</a></p>
						<p><a class="btn btn-lg btn-success" href="./bestenliste.php#tab4" role="button">Bestenliste</a></p>


					<?php } else { ?>

						<h2 class="blackborder">Wie gut kennst du das AG?</h2>
						<p class="lead blackborder">Melde dich jetzt an, spiele mit und gewinne mit etwas Glück einen der vielen Preise!</p>
						<p><a class="btn btn-lg big-btn btn-primary" href="./login.php" role="button">Einloggen</a></p>
						<p><a class="btn btn-lg big-btn btn-success" href="./neuer_benutzer.php" role="button">Heute noch anmelden</a></p>


					<?php } ?>
					<br><br>
					<span class="blackborder display-5">Der rein nicht-kommerzielle AG-Ventskalender des Allgäu-Gymnasiums.</span>
				</div>
			</div>
		</div>
	</header>
	<?php getFooter(); ?>
</body>

</html>