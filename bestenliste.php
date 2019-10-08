<?php
require_once("./include/lib.inc.php");
require_once("./include/db.inc.php");

?>
<!DOCTYPE html>
<html lang="de">

<head>
	<?php getHead(); ?>

	<style>
		.tab-content {
			display: none;
		}

		.tab-content:target {
			display: block;
		}

		th {
			text-align: center;
		}

		@media (min-width: 768px) {
			.container {
				max-width: 730px;
			}
		}
	</style>


</head>

<body class="bgimg">

	<div class="container">
		<?php getNav("bestenliste"); ?>

		<div class="jumbotron text-center">
			<h1>Bestenliste</h1>
			<br>
			<ul class="menu nav nav-tabs">
				<li><a href="#tab1">Alle Schüler</a></li>
				<li><a href="#tab2">Alle Lehrer</a></li>
				<li><a href="#tab3">Alle Klassen (durchschnittlich)</a></li>
				<li><a href="#tab4">Bestenliste (alle Teilnehmer)</a></li>
			</ul>

			<h5><b><?php echo "Stand: " . strftime("%A") . ", " . date('d.m.o \u\m H:i:s') . " Uhr"; ?></b></h5>
			<div class="tab-folder">
				<div id="tab1" class="tab-content">
					<h3>Alle Schüler</h3>
					<?php
					createBestenliste("SELECT * FROM users WHERE grade NOT IN ('Lehrer/in', 'Studienseminar 17/19', 'Studienseminar 18/20') AND `checked`!=-1 AND `hideInScores`!=1  ORDER BY `points` DESC", false);
					?>
				</div>
				<div id="tab2" class="tab-content">
					<h3>Alle Lehrer</h3>
					<?php
					createBestenliste("SELECT * FROM users WHERE grade IN ('Lehrer/in', 'Studienseminar 17/19', 'Studienseminar 18/20') AND `checked`!=-1 AND `hideInScores`!=1 ORDER BY `points` DESC", false);
					?>
				</div>
				<div id="tab3" class="tab-content">
					<h3>Alle Klassen (durchschnittlich)</h3>
					<?php
					createBestenliste("grades", false);
					?>
				</div>
				<div id="tab4" class="tab-content">
					<h3>Bestenliste (alle Teilnehmer)</h3>
					<?php
					if (isset($_SESSION["userid"])) {
						createBestenliste("SELECT * FROM users WHERE `checked`!=-1 AND `hideInScores`!=1 ORDER BY `points` DESC", true);
					} else {
						createBestenliste("SELECT * FROM users  WHERE `checked`!=-1 AND `hideInScores`!=1 ORDER BY `points` DESC", false);
					}
					?>
				</div>
			</div>


			<div class="clearfix">&nbsp;</div>

		</div>


	
		<?php getFooter(); ?>

	</div>
</body>
</html>