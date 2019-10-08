<?php
require_once( "./include/lib.inc.php" );
require_once( "./include/db.inc.php" );
require_once( "./include/login.inc.php" );


?>
<!DOCTYPE html>
<html lang="de">
<head>
	<?php getHead(); ?>
	<style>
		.day {
			cursor: pointer;
			display: inline-block;
			position: relative;
			padding: 0;
			margin: 0;
			text-align: center;
			float: left;
		}
		
		.present {
			width: 145px;
		}
		
		.number {
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			font-size: 50px !important;
			color: #FFFFFF;
			text-align: center;
			vertical-align: middle;
			line-height: 200px;
			text-shadow: 0 0 5px black;
		}
		
		.overlay {
			position: absolute;
			right: 2px;
			bottom: 2px;
			height: 40px;
			width: 40px;
			z-index: 4;
			
		}
		
		.overview {
			width: 100%;
		}
		.overview td {
			border: 1px solid black;
		}
		
		
		
		.green {
			background-color: #B2DBA1;
		}
		.red {
			background-color: #DBA1A1;
		}
		.yellow {
			background-color: #E9D44E;
		}
		.white {
			background-color: #FFFFFF;
		}
		.blue {
			background-color: #A1C2DB;
		}
		
		table {
 
		  width: 100%;
		  text-align:center;
		}

td {
  border: solid 1px;
}

		
		

@media screen and (max-width: 600px) {
  * {
    box-sizing: border-box;
  }
  /* break table */
  table:not(.colorcodes) tr {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
  }
  table:not(.colorcodes) td {
    display: block;
    width: 10%;
    text-align: center;
  }
  table:not(.colorcodes) td[colspan] {
    width: 100%;
  }
}
		
	</style>
</head>

<body>

	<div class="container">
		<?php getNav("aufgaben"); ?>

		<div class="jumbotron text-center">
			<h1>Aufgaben</h1>
			<br>

			<?php
			$db = connect();
			if ( isset( $_GET[ "s" ] ) ) {
				alert( "success", "Deine Eingabe wurde erfolgreich gespeichert!" );
			}

			date_default_timezone_set( "Europe/Berlin" );
			
			if (checkForDate(WEIHNACHTSTAG) == "past") {
				$points = getUserPoints();
				echo "<h2>Herzlichen Glückwunsch!<br>";
								alert("success","<p class='lead'>Du hast $points von 480 möglichen Punkten erhalten.</p>");
								echo "</h2><strong>Du kennst dich gut im Allgäu-Gymnasium aus.<br>Du wirst benachrichtigt, wenn du einen Preis gewonnen hast.</strong><br><br>";
			}
			
			?>


			<!--<ul class="menu nav nav-tabs">
				<li><a href="#tab1">Im Bild</a></li>
				<li><a href="#tab2">Als Liste</a></li>
				
			</ul>-->
			<br>

			<!--<div id="tab1" class="tab-content">-->
			<?php alert("info","<h3>Farbcodes:</h3><table class='colorcodes'>
						<tr><td class='green'>Grün:</td><td>Richtig, volle Punkzahl</td></tr>
						<tr><td class='red'>Rot:</td><td>Falsch, keine Punkte</td></tr>
						<tr><td class='blue'>Blau:</td><td>Richtige Alternative, halbe Punkzahl</td></tr>
						<tr><td class='yellow'>Gelb:</td><td>Momentan zur Bearbeitung</td></tr>
						<tr><td class='white'>Weiß:</td><td>Noch nicht freigeschaltet</td></tr>
						</table>
						"); 
			alert("warning", "<h3>Achtung:</h3>Die Lösungen, die auch akzeptiert werden und die halbe Punkzahl geben, können sich im Nachhinein noch ändern und somit deine Punktzahl erhöhen, da die Alternativen erst diskutiert werden müssen.");
			?>
			<table class='overview'>
				<tr>
					<?php
					$db = connect();
					$today = intval(date('d'));
					$tipps = [];
					$res = $db->query( "SELECT * FROM days")->fetch_all(MYSQLI_ASSOC);
					$userid = $db->real_escape_string($_SESSION["userid"]);
					$res2 = $db->query( "SELECT * FROM tipps WHERE userid=$userid" )->fetch_all(MYSQLI_ASSOC);
					foreach ($res2 as $tipp) {
						$tipps[$tipp["day"]] = $tipp["tipp"];
					}
					foreach ($res as $day){
						$i = $day["day"];
						if (checkForDate($i) == "past") {
							$alternatives = array_map("strtoupper", explode("-",$day[ "alternatives" ]));
							if (isset($tipps[$day["day"]]) AND strtoupper($day["word"]) == strtoupper($tipps[$day["day"]])) {
								$add = " class='green'";
							} else if (isset($tipps[$day["day"]]) AND in_array(strtoupper($tipps[$day["day"]]), $alternatives) AND !empty($tipps[$day["day"]])){
								$add = " class='blue'";
								
							} else {
								$add = " class='red'";
							}
							
						} else {
							if (checkForDate($i) == "today") {
								$add = " class='yellow'";
							} else {
								$add = "";
							}
						}
						if ($i < 10) {
							echo "<td$add>0$i</td>";
						} else {
							echo "<td$add>$i</td>";
						}
					}
					?>
				</tr>
				<tr>
						<?php
					
					
					foreach ($res as $day){
						$day["letter"] -= 1;
						$i = $day["day"];
						if (checkForDate($i) == "past") {
							$alternatives = array_map("strtoupper", explode("-",$day[ "alternatives" ]));
							if (isset($tipps[$day["day"]]) AND strtoupper($day["word"]) == strtoupper($tipps[$day["day"]])) {
								$add = " class='green'";
							} else if (isset($tipps[$day["day"]]) AND in_array(strtoupper($tipps[$day["day"]]), $alternatives) AND !empty($tipps[$day["day"]])){
								$add = " class='blue'";
								
							} else {
								$add = " class='red'";
							}
							$splitted = preg_split('/(?!^)(?=.)/u', strtoupper($day["word"]));
							if ($day["letter"] != -1 && isset($splitted[$day["letter"]])) {
								$letter = $splitted[$day["letter"]];
							}
							
						} else {
							if (checkForDate($i) == "today") {
								$add = " class='yellow'";
							} else {
								$add = "";
							}
							
							$letter = "";
						}
						
						echo "<td$add>$letter</td>";
						
						
					}
					?>
				</tr>
			</table>
			<?php getTasks($db, "list"); ?>

			<div class="clearfix">&nbsp;</div>
			<!--</div>
			<div id="tab2" class="tab-content">
				<?php /*getTasks($db, "img");*/ ?>


				<div class="clearfix">&nbsp;</div>
			</div>-->
		</div>
		<!--
      <div class="row marketing">
        <div class="col-lg-6">
          <h4>Unter-Überschrift</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Unter-Überschrift</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Unter-Überschrift</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>

        <div class="col-lg-6">
          <h4>Unter-Überschrift</h4>
          <p>Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum.</p>

          <h4>Unter-Überschrift</h4>
          <p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cras mattis consectetur purus sit amet fermentum.</p>

          <h4>Unter-Überschrift</h4>
          <p>Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
        </div>
      </div>-->

		<?php getFooter(); ?>

	</div>
	<!-- /container -->
</body>
</html>