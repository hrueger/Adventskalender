<?php
require_once("./include/lib.inc.php");
require_once("./include/db.inc.php");

?>
<!DOCTYPE html>
<html lang="de">

<head>
	<?php getHead(); ?>
	<style>
		.text-normal {
			font-size: 14px !important;
			font-weight: normal !important;
			text-align: left;
		}

		.bigger {
			font-size: 22px !important;
			font-weight: normal !important;
			text-align: left;
		}
	</style>
</head>

<body class="bgimg">

	<div class="container">
		<div class="header clearfix">
			<?php getNav("regeln"); ?>
			<div class="jumbotron text-normal">
				<h2>Der etwas andere AGventskalender</h2>
				<h4>Das Schulhaus-Tippspiel des Allgäu-Gymnasiums Kempten</h4>
				<br>	
				<p class="text-left regeln text-normal">
					<br>
					<p class="bigger">Liebe Schülerinnen und Schüler, Lehrerinnen und Lehrer des Allgäu-Gymnasiums,</p>

						<i>willkommen beim AGventskalender, dem Schulhaus-Tippspiel unseres Gymnasiums zur Adventszeit! <br>
						<p class="text-justify">
						<br> Wie gut kennst du deine Schule? Jeden Tag kannst du ein Geschenk öffnen und dein Wissen testen. Du wirst Bilder von Orten und Objekten in unserem Schulhaus sehen. Was wurde fotografiert? Gib die Buchstaben des entsprechenden Worts in die dafür vorgesehenen Kästchen ein. Solltest du Probleme mit den Objekten oder den Begriffen haben, dann mache dich auf ins und ums Schulgebäude und suche die Stellen. Schau dich dort genau um. Manchmal findest du Hilfe vor Ort. Du hast jeweils mindestens zwei Tage Zeit. <br> Jedes Lösungswort enthält einen Buchstaben, den du brauchst, um den Lösungssatz am Ende des AGventskalenders herauszufinden.<br> Falls du an einem Tag die Lösung nicht findest, sei nicht traurig, sondern spiele am nächsten Tag einfach weiter. Jede Woche gibt es mehr Punkte auf eine richtige Lösung. <br> Die Teilnahme an diesem Tippspiel ist natürlich kostenlos.<br> Neben der Freude, zu den besten AG-Experten der Schulfamilie zu gehören, warten auf die Erstplatzierten kleine Preise.<br></p>
						<br> Viel Spaß beim Schulhaus-Erforschen, Knobeln, Rätseln und Gewinnen!<br>
						<br> Die Arbeitsgemeinschaft Multimedia des Allgäu-Gymnasiums<br></i>

					<br>
					<br>
					<h4>Noch ein paar Regeln:</h4>
					<div class="card panel">
						<b>Wer kann am AGventskalender teilnehmen?</b><br>
						<p class="text-left regeln text-normal">Alle Schülerinnen und Schüler sowie alle Lehrkräfte des Allgäu-Gymnasiums dürfen am Tippspiel teilnehmen.</p>
					</div>
					<div class="card panel">
						<b>Wie melde ich mich an?</b>
						<p class="text-left regeln text-normal">Bitte melde dich zunächst an (<a href="./neuer_benutzer.php">Registrieren</a>). Außer deinem Namen und deiner Klasse musst du keine personenbezogenen Daten angeben. Diese werden absolut vertraulich behandelt. Wenn du im Sommer am WM-Tippspiel teilgenommen hast, kannst du einfach deine Anmeldung von damals per Klick übernehmen. Bitte notiere dir deine angegebenen Zugangsdaten, deinen Nicknamen und dein Passwort. (Bei Verlust dieser Daten müsstest du dich neu anmelden und würdest so bereits erreichte Punkte verlieren.) Bitte melde dich mit deinem richtigen Namen und deiner richtigen Klasse an. Jede Anmeldung wird von uns genau überprüft. Existiert kein Schüler oder Lehrer mit dem angegeben Namen an unserer Schule, wird die Anmeldung gelöscht.</p>
					</div>
					<div class="card panel">
						<b>Wann kann ich die Lösungen eintragen?</b>
						<p class="text-left regeln text-normal">Die Tabelle sagt dir, bis wann du die Lösung eingeben kannst.</p>

						<table class="table table-striped table-responsive">
							<thead>
								<tr>
									<th>Tag des Rätsels</th>
									<th colspan="2">Abgabe möglich bis</th>

								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Montag</td>
									<td>Dienstag</td>
									<td>23:59 Uhr</td>
								</tr>
								<tr>
									<td>Dienstag</td>
									<td>Mittwoch</td>
									<td>23:59 Uhr</td>
								</tr>
								<tr>
									<td>Mittwoch</td>
									<td>Donnerstag</td>
									<td>23:59 Uhr</td>
								</tr>
								<tr>
									<td>Donnerstag</td>
									<td>Freitag</td>
									<td>23:59 Uhr</td>
								</tr>
								<tr>
									<td>Freitag, Samstag, Sonntag</td>
									<td>Dienstag</td>
									<td>23:00 Uhr</td>
								</tr>
							</tbody>
						</table>
						<br> Nach dieser Frist ist die Eingabe nicht mehr möglich und du kannst die Lösung des jeweiligen Bilderrätsels ansehen.<br>
						<br> Die aktuellen Bilderrätsel kannst du auch in der Schule an den Info-Screens sehen. <br>
					</div>
					<div class="card panel"><b>Wie viele Punkte kann ich erzielen?</b><br>
						<p class="text-left regeln text-normal">Für jedes richtige Lösung eines Bilder-Rätsels der
							<ul>
								<li><strong>ersten</strong> Adventswoche (01. – 08.12.) bekommst du <strong>10 Punkte</strong></li>
								<li><strong>zweiten</strong> Adventswoche (09. – 15.12.) bekommst du <strong>20 Punkte</strong></li>
								<li><strong>dritten</strong> Adventswoche (16. – 23.12.) bekommst du <strong>30 Punkte</strong></li>
								<li>Für die richtige Lösung am <strong>Heiligabend</strong> bekommst du <strong>60 Punkte</strong></li>
							</ul>
							<p>Unter <a class="d-inline" href="./bestenliste.php">Bestenliste</a> kannst du jederzeit nachsehen, wie viele Punkte du bisher erhalten hast und auf welchem Platz du momentan stehst.<br> <br>Auch kannst du sehen, wie gut deine Klasse bisher abgeschnitten hat. <br> Die Preisträger des Tippspiels werden dann benachrichtigt.<br></p>
						</p>
					</div>
					<hr>
					<br><strong> Technische Realisierung:</strong><br> Hannes Rüger (10a) <br>
					<br><strong> Spielleitung:</strong>
					<br> Andreas Herz, StD<br>


				</p>
			</div>


			<?php getFooter(); ?>

		</div>
		

</body>

</html>