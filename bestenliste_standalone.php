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

<body>

	<div class="container">


		<div class="jumbotron text-center">
			<img src="images/header_lang.png" class="img img-fluid">
			<h1>Bestenliste<br><small>des AG-ventskalenders</small></h1>

			<br>

			<?php if (isset($_GET["s"])) { ?>
				<h3>Die besten 10 Schüler</h3>
			<?php
				createBestenliste("SELECT * FROM users WHERE grade NOT IN ('Lehrer/in', 'Studienseminar 17/19', 'Studienseminar 18/20')  AND `checked`!=-1 AND `hideInScores`!=1 ORDER BY `points` DESC LIMIT 10", false);
			} else if (isset($_GET["l"])) { ?>
				<h3>Die besten 10 Lehrer</h3>
				<?php
					createBestenliste("SELECT * FROM users WHERE grade IN ('Lehrer/in', 'Studienseminar 17/19', 'Studienseminar 18/20')  AND `checked`!=-1 AND `hideInScores`!=1 ORDER BY `points` DESC LIMIT 10", false);
					?>
			<?php } ?>





			<div class="clearfix">&nbsp;</div>

		</div>
		<br>
		<div class="card">
			<h4><?php echo "Stand: " . strftime("%A") . ", " . date('d.m.o \u\m H:i:s') . " Uhr"; ?></h4>
		</div>


		<?php echo getFooter(); ?>

	</div> <!-- /container -->


</body>

</html>