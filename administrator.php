<?php
require_once("./include/lib.inc.php");
require_once("./include/db.inc.php");
if (!isset($_SESSION["adminLoggedin"]) or $_SESSION["adminLoggedin"] != true) {
	$loggedin = false;
} else {
	$loggedin = true;
}

if (isset($_POST["submit"])) {

	if (
		isset($_POST["username"]) &&
		isset($_POST["password"]) &&
		!empty(trim($_POST["username"])) &&
		!empty(trim($_POST["password"]))
	) {
		$db = connect();

		$username = $db->real_escape_string($_POST["username"]);
		$res = $db->query("SELECT * FROM admins WHERE username='$username'");

		if (!$res) {
			alert("danger", "Bitte überprüfe deinen Benutzernamen!");
		} else {
			$res = $res->fetch_all(MYSQLI_ASSOC);
			if (!$res) {
				alert("danger", "Bitte überprüfe deinen Benutzernamen!");
			} else {
				$res = $res[0];
				if (!$res) {
					alert("danger", "Bitte überprüfe deinen Benutzernamen!");
				} else {
					$password = $res["password"];
					$status = password_verify($_POST["password"], $password);

					if ($status) {
						$_SESSION["adminLoggedin"] = true;
						$_SESSION["adminusername"] = $res["username"];
						$_SESSION["adminuserid"] = $res["id"];
						$loggedin = true;
					} else {
						alert("danger", "Bitte überprüfe dein Passwort!");
					}
				}
			}
		}
	}



	$username = (isset($_POST["username"])) ? $_POST["username"] : "";
	$password = (isset($_POST["password"])) ? $_POST["password"] : "";

	if (empty(trim($password)) xor empty(trim($username))) {
		alert("danger", "Bitte überprüfe deine Zugangsdaten!");
	}
} else if (isset($_GET["v"])) {
	$db = connect();
	$user = $db->real_escape_string($_GET["v"]);

	$res = $db->query("UPDATE users SET checked=1 WHERE id=$user");
} else if (isset($_GET["b"])) {
	$db = connect();
	$user = $db->real_escape_string($_GET["b"]);

	$res = $db->query("UPDATE users SET checked=-1 WHERE id=$user");
} else if (isset($_POST["saveAlternatives"])) {
	$db = connect();
	$res = $db->query("UPDATE days SET `alternatives`=''");
	foreach ($_POST as $key => $value) {
		if (strpos($key, 'day_') === 0) {
			$day = str_replace("day_", "", $key);
			$day = $db->real_escape_string($day);
			$alternatives = implode("____", $value);
			$alternatives = $db->real_escape_string($alternatives);
			$res = $db->query("UPDATE days SET `alternatives`='$alternatives' WHERE day=$day");
		 }
	}
	updatePoints();
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">

	<title>Administrator - AG-AG-Ventskalender</title>

	<link href="./include/lib/bootstrap/bootstrap.min.css" rel="stylesheet">

	<link href="./styles/administrator.css" rel="stylesheet">

	<style>
		.header {
			margin-top: 4px;
			height: 45px;
		}
	</style>
</head>

<body>
	<?php if ($loggedin) { ?>
		<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow navbar-expand-lg">
		
			
			<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="./" target="_blank">
			<button class="navbar-toggler" type="button">
				<span class="navbar-toggler-icon"></span>
			</button>
			AG-Ventskalender</a>
			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap">
					<a class="nav-link" href="./administrator.php?a=logout">Ausloggen</a>
				</li>
			</ul>
		</nav>

		
<div class="container-fluid">
  <div class="row">
    <nav id="sidebar" class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link <?php if (!isset($_GET["a"]) || $_GET["a"] == "dashboard") echo "active";?> " href="./administrator.php?a=dashboard">
              Anleitung
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET["a"]) && $_GET["a"] == "suggestions") echo "active";?> " href="./administrator.php?a=suggestions">
              Vorschläge
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET["a"]) && $_GET["a"] == "users") echo "active";?> " href="./administrator.php?a=users">
              Benutzer
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET["a"]) && $_GET["a"] == "points") echo "active";?> " href="./administrator.php?a=points">
              Punkte aktualisieren
            </a>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Sicherung</span>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="./include/exportdb.php">
              Exportieren
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET["a"]) && $_GET["a"] == "importData") echo "active";?> " href="./administrator.php?a=importData">
              Imporieren
            </a>
          </li>
        </ul>
      </div>
    </nav>
				<div class="col-md-9 ml-sm-auto col-lg-10 px-4">

					<?php $action = (isset($_GET["a"])) ? $_GET["a"] : "dashboard";

						if ($action == "dashboard") {
							?>


						<h1 class="page-header">Bedienungsanleitung</h1>
						Eigentlich ist alles selbsterklärend ;-)<br>
						<br>
						Viel Spaß mit dem AG-Ventskalender!


					<?php } else if (isset($_POST["updatePoints"])) {
							updatePoints();
							alert("success", "Die Punkte aller Spieler wurden aktualisiert!");
						} else if ($action == "points") { ?>
						<h1 class="page-header">Punkte der Spieler aktualisieren</h1>
						<form class="form-horizontal" method="post">

							<input class="btn btn-primary" name="updatePoints" type="submit" value="Punkte aktualisieren">
						</form>

					<?php } else if ($action == "suggestions") { ?>
						<h1 class="page-header">Vorschläge</h1>
						<div class="table-responsive">
						<form method="POST">
							
							<button type="submit" class="btn btn-primary" name="saveAlternatives">Speichern</button>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Tag</th>
										<th>Lösung</th>
										<th>Vorschläge</th>
									</tr>
								</thead>
								<?php
									$db = connect();
									for ($i = 1; $i < 24; $i++) {

										$res = $db->query("SELECT DISTINCT tipp FROM tipps WHERE day=$i")->fetch_all(MYSQLI_ASSOC);
										echo $db->error;
										$alternatives = $db->query("SELECT word, alternatives FROM `days` WHERE `day`=$i")->fetch_all(MYSQLI_ASSOC);
										echo $db->error;
										$alternatives = $alternatives[0];									
										$word = $alternatives["word"];
										$suggestions = "";
										$counter = 0;
										if (!isset($alternatives) || !isset($alternatives["alternatives"])) {
											$alternatives = [];
											echo "SELECT word, alternatives FROM `days` WHERE `day`=$i";
										} else {
											$alternatives = explode("____", $alternatives["alternatives"]);
										}
										foreach ($res as $suggestion) {
											$counter += 1;
											$ids = $i. $counter.random_int(0,1000);
											$s =  (in_array($suggestion["tipp"], $alternatives) ? "checked" : "");
											$suggestions .= "<input class='p-2' type='checkbox' id='$ids' ".$s." name='day_".$i."[]' value='".$suggestion["tipp"]. "'><label for='$ids'>".$suggestion["tipp"]. "</label><br>";
										}


										echo "<tr><td>$i</td><td>$word</td><td>$suggestions</td></tr>";
									}
									?>
								</table>
							</form>
						</div>

					<?php } else if ($action == "users") { 
							?>
						<h1 class="page-header">Benutzer</h1>

						<?php
								$db = connect();
								$res = $db->query("SELECT * FROM users ORDER BY grade");
								if (!$res) {
									alert("danger", "Keine Benutzer gefunden!");
								} else {
									$res = $res->fetch_all(MYSQLI_ASSOC);
									if (!$res) {
										alert("danger", "Keine Benutzer gefunden!");
									} else {
										$blockedUsers = array();
										$checkedUsers = array();
										$uncheckedUsers = array();

										foreach ($res as $user) {
											if ($user["checked"] == -1) {
												$blockedUsers[] = $user;
											} else if ($user["checked"] == 0) {
												$uncheckedUsers[] = $user;
											} else {
												$checkedUsers[] = $user;
											}
										}
										alert("success", "Es gibt " . count($uncheckedUsers) . " neue Benutzer!");
										alert("info", "Es gibt " . count($checkedUsers) . " verifizierte Benutzer!");
										alert("warning", "Es gibt " . count($blockedUsers) . " blockierte Benutzer!");
										echo "<h3>Neue Benutzer</h3>
										<div class='table-responsive'>
									<table class='table table-striped'>
									  <thead>
										<tr>
										  <th>Klasse</th>
										  <th>Nickname</th>
										  <th>Name</th>
										  <th>Verifizieren</th>
										  <th>Blockieren</th>
										</tr>
									  </thead>
									  <tbody>";
										foreach ($uncheckedUsers as $user) {
											echo "<tr>
										<td>" . $user["grade"] . "</td>
										<td>" . $user["nickname"] . "</td>
										<td>" . $user["name"] . "</td>
										<td><a href='./administrator.php?a=users&v=" . $user["id"] . "'>Verifizieren</a></td>
										<td><a href='./administrator.php?a=users&b=" . $user["id"] . "'>Blockieren</a></td>
									</tr>";
										}
										echo "</tbody></table></div>";
										echo "<h3>Verifizierte Benutzer</h3>
										
										<div class='table-responsive'>
									<table class='table table-striped'>
									  <thead>
										<tr>
										  <th>Klasse</th>
										  <th>Nickname</th>
										  <th>Name</th>
										  
										</tr>
									  </thead>
									  <tbody>";
										foreach ($checkedUsers as $user) {
											echo "<tr>
										<td>" . $user["grade"] . "</td>
										<td>" . $user["nickname"] . "</td>
										<td>" . $user["name"] . "</td>
									</tr>";
										}
										echo "</tbody></table></div>";
										echo "<h3>Blockierte Benutzer</h3>
										
										<div class='table-responsive'>
									<table class='table table-striped'>
									  <thead>
										<tr>
										  <th>Klasse</th>
										  <th>Nickname</th>
										  <th>Name</th>
										  
										</tr>
									  </thead>
									  <tbody>";
										foreach ($blockedUsers as $user) {
											echo "<tr>
										<td>" . $user["grade"] . "</td>
										<td>" . $user["nickname"] . "</td>
										<td>" . $user["name"] . "</td>
									</tr>";
										}
										echo "</tbody></table></div>";
									}
								}



								?>




				</div>
			<?php } else if ($action == "results") {
					?>
				<div class="jumbotron text-center"><?php
															if (isset($_POST["submit"]) && isset($_POST["matchid"])) {
																if (
																	isset($_POST["goalsTeam1"]) &&
																	isset($_POST["goalsTeam2"]) &&
																	trim($_POST["goalsTeam1"]) != "" &&
																	trim($_POST["goalsTeam2"]) != ""
																) {
																	$tippTeam1 = intval($_POST["goalsTeam1"]);
																	$tippTeam2 = intval($_POST["goalsTeam2"]);
																	if (
																		is_numeric($tippTeam1) &&
																		is_numeric($tippTeam2) &&
																		$tippTeam1 > -2 &&
																		$tippTeam2 > -2 &&
																		$tippTeam1 < 100 &&
																		$tippTeam2 < 100
																	) {
																		$db = connect();

																		$userid = $db->real_escape_string($_SESSION["adminuserid"]);
																		$matchid = $db->real_escape_string($_POST["matchid"]);


																		$db->query("UPDATE matches SET goalsTeam1=$tippTeam1, goalsTeam2=$tippTeam2 WHERE id=$matchid");

																		alert("success", "Das Ergebnis wurde erfolgreich gespeichert!");
																		updatePoints();
																		alert("success", "Die Punkte aller Spieler wurden aktualisiert!");
																		echo "<br><a class='btn btn-primary' href='./administrator.php?a=matches'>Zurück zum Spielplan</a>";
																		die();
																	} else {
																		alert("danger", "Du hast leider nicht alle Felder korrekt ausgefüllt.0002");
																	}
																} else {
																	alert("danger", "Du hast leider nicht alle Felder ausgefüllt.0001");
																}
															} else if (!isset($_GET["s"])) {
																header("Location: administrator.php");
															}

															$db = connect();
															$id = $db->real_escape_string($_GET["s"]);

															$res = $db->query("SELECT * FROM `matches` WHERE id=$id");
															if (!$res) {
																alert("danger", "Es wurde kein Spiel gefunden!");
															} else {
																$res = $res->fetch_all(MYSQLI_ASSOC);
																if (!$res) {
																	alert("danger", "Es wurde kein Spiel gefunden!");
																} else {
																	$match = $res[0];

																	$short1 = $db->real_escape_string($match["team1"]);
																	$short2 = $db->real_escape_string($match["team2"]);
																	$res = $db->query("SELECT * FROM teams WHERE short='$short1' or short='$short2'");
																	if (!$res) {
																		alert("danger", "Es wurden keine Teams gefunden!");
																	} else {
																		if (strlen($short1) != 3 or strlen($short1) != 3) {
																			$team1 = $short1;
																			$team2 = $short2;
																		} else {
																			$res = $res->fetch_all(MYSQLI_ASSOC);
																			if ($res[0]["short"] == $match["team1"]) {
																				$team1 = $res[0]["name"];
																				$team2 = $res[1]["name"];
																			} else {
																				$team1 = $res[1]["name"];
																				$team2 = $res[0]["name"];
																			}
																		}
																		$monate = array(
																			1 => "Januar",
																			2 => "Februar",
																			3 => "M&auml;rz",
																			4 => "April",
																			5 => "Mai",
																			6 => "Juni",
																			7 => "Juli",
																			8 => "August",
																			9 => "September",
																			10 => "Oktober",
																			11 => "November",
																			12 => "Dezember"
																		);
																		$tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
																		$date = strtotime($match["date"]);
																		$tag = date("j", $date);
																		$monat = date("m", $date);
																		$jahr = date("Y", $date);
																		$uhr =  date("H:i", $date);
																		$stadt = $match["place"];
																		$wochentag = $tage[date("w", $date)];
																		$goalsTeam1 = $match["goalsTeam1"];
																		$goalsTeam2 = $match["goalsTeam2"];
																		if ($goalsTeam1 < 0 and $goalsTeam2 < 0) {
																			$goalsTeam1 = "--";
																			$goalsTeam2 = "--";
																		}
																		$id = $match["id"];

																		$kuerzel1 = ($match["team1"] == "?" or strlen($match["team1"]) != 3) ? "unknown" : $match["team1"];
																		$kuerzel2 = ($match["team2"] == "?" or strlen($match["team2"]) != 3) ? "unknown" : $match["team2"];

																		echo "<h2><img class='teamIcon' src='./images/teams/$kuerzel1.jpg'>&nbsp;$team1 vs. $team2&nbsp;<img class='teamIcon' src='./images/teams/$kuerzel2.jpg'></h2>";

																		echo "<div class='infoblock'>";

																		echo $wochentag . ", $tag.$monat.$jahr um $uhr Uhr";
																		echo "<br>in $stadt";
																		if ($match["goalsTeam1"] < 0 and $match["goalsTeam2"] < 0) {
																			$buttonname = "Ergebnis speichern";
																			$val1 = "";
																			$val2 = "";
																		} else {
																			$buttonname = "Ergebnis ändern";
																			$val1 = $match["goalsTeam1"];
																			$val2 = $match["goalsTeam2"];
																		}
																		echo '</div><br><br><form class="form-inline" method="post">
						<input type="hidden" id="matchid" name="matchid" value="' . $_GET["s"] . '">
						<div class="form-group">
							
							<input type="number" class="form-control" id="goalsTeam1" name="goalsTeam1" value=' . $val1 . ' placeholder="' . $team1 . '">
						</div>
						<b>&nbsp;:&nbsp;</b>
						<div class="form-group">
							
							<input type="number" class="form-control" id="goalsTeam2" name="goalsTeam2" value=' . $val2 . ' placeholder="' . $team2 . '">
						</div><br><br><br><br>
				<button name="submit" type="submit" class="btn btn-success">' . $buttonname . '</button>
					</form><br>';



																		echo "<a class='btn btn-primary' href='./administrator.php?a=matches'>Abbrechen und zurück zum Spielplan</a>";
																	}
																}
															}

															?>



				</div>
			<?php } else if ($action == "logout") {
					?>
				<h1>AGventskalender <?php echo date("Y"); ?></h1>
				<br>
				<?php

						if (isset($_POST["logout"])) {

							if (ini_get("session.use_cookies")) {
								$params = session_get_cookie_params();
								setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
							}

							session_destroy();
							alert("success", "Du wurdest erfolgreich ausgeloggt!");
							header("Location: ./administrator.php");
						} else if (isset($_POST["stay"])) {

							header("Location: ./administrator.php");
						} else {

							?>


					<br>
					<form class="form-horizontal" method="post">



						<h3>Bist du sicher, dass du dich ausloggen möchtest?</h3>
						<div class="form-group">
							<div class="">
								<input type="submit" name="logout" value="Ja, ausloggen" class="btn btn-danger">
								<input type="submit" name="stay" value="Nein, hier bleiben" class="btn btn-success">
							</div>
						</div>

					</form>


				<?php  } ?>
			<?php } else if ($action == "importData") {
					?>
				<h1>Daten importieren</h1>
				<br>
				<?php alert("warning", "<b>Achtung:</b> Alle Daten, die sich seit dem Download der sql Datei verändert haben, gehen bei einem Import verloren!!!. Deshalb sollten die Daten nur bei einem kompletten Verlust der Datenbank oder bei einem schwerwiegendem Fehler neu importiert werden. "); ?>
				Bitte lade die Datei hoch:
				<form enctype="multipart/form-data" method="post">
					<label class="btn btn-primary btn-file">
						Auswählen... <input type="file" name="dbfile" style="display: none;">
					</label><br><br>
					<input class="btn btn-success" type="submit" name="submitFile" value="Importieren"><br><br>
				</form>
			<?php }
				if (isset($_POST["submitFile"])) {
					$target_dir = "./";
					$target_file = $target_dir . "database.sql";
					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


					if (file_exists($target_file)) {
						alert("danger", "Entschuldigung, die Datei existiert bereits. <a href='./include/importdb.php?del' class='btn btn-warning'>Hochgeladene Datei löschen</a>");
						$uploadOk = 0;
					}
					if ($imageFileType != "sql") {
						alert("danger", "Entschuldigung, es sind nur *.sql Dateien erlaubt.");
						$uploadOk = 0;
					}
					if ($uploadOk == 0) {
						alert("danger", "Die Datei wurde nicht hochgeladen.");
					} else {
						if (move_uploaded_file($_FILES["dbfile"]["tmp_name"], $target_file)) {
							alert("success", "Die Datei " . basename($_FILES["dbfile"]["name"]) . " wurde erfolgreich hochgeladen.<a href='./include/importdb.php' class='btn btn-primary'>Gleich importieren</a>");
						} else {
							alert("danger", "Entschuldigung, die Datei wurde aufgrund eines Fehlers nicht hochgeladen.");
						}
					}
				}
			} else {
				?>
			<div class="container">

				<form class="form-signin" method="post">
					<h2 class="form-signin-heading">Bitte melde dich an</h2>
					<label for="username" class="sr-only">Email-Adresse</label>
					<input type="text" id="username" name="username" class="form-control" placeholder="Benutzername" required autofocus>
					<label for="password" class="sr-only">Passwort</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Passwort" required>

					<button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Anmelden</button>
				</form>

			</div>
		<?php } ?>

		<script src="./include/lib/bootstrap/jquery.min.js"></script>
		<script src="./include/lib/bootstrap/bootstrap.min.js"></script>
		<script>
			$(document).ready(() => {
				$(".navbar-toggler").click((event) => {
					console.log("toggle");
					$("#sidebar").toggleClass("d-none");
					$("#sidebar").toggleClass("mt-5");
					event.preventDefault();
					event.stopPropagation();
				});
			});
		</script>
</body>
</html>