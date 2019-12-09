<?php
header('Content-Type: text/html; charset=UTF-8');
define("WEIHNACHTSTAG", 24);
session_start();
date_default_timezone_set("Europe/Berlin");
setlocale(LC_TIME, "de_DE.utf8");
$loggedin = (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) ? true : false;

function alert($type, $message)
{
	echo "	<div class='alert alert-$type'>
					$message
				</div>";
}

function getTimeObjBetween($startDate, $endDate)
{
	$ONE_SECOND = 1000;
	$ONE_MINUTE = 60 * $ONE_SECOND;
	$ONE_HOUR = 60 * $ONE_MINUTE;
	$ONE_DAY = 24 * $ONE_HOUR;

	$resultObject = new stdClass();
	$resultObject->totalDays = 0;
	$resultObject->hours = 0;
	$resultObject->minutes = 0;
	$resultObject->seconds = 0;


	$timespan = $endDate - $startDate;

	$dayCount = $timespan / $ONE_DAY;
	$resultObject->totalDays = floor($dayCount);

	$hours = ($dayCount - $resultObject->totalDays) * 24;
	$resultObject->hours = floor($hours);

	$minutes = ($hours - $resultObject->hours) * 60;
	$resultObject->minutes = floor($minutes);

	$seconds = ($minutes - $resultObject->minutes) * 60;
	$resultObject->seconds = floor($seconds);

	return $resultObject;
}

function getTasks($db, $mode)
{
	
	$query = 'SELECT * FROM `days` ORDER BY `day` ASC';
	$res = $db->query($query);
	$userid = $_SESSION["userid"];
	$res2 = $db->query("SELECT * FROM tipps WHERE userid=$userid")->fetch_all(MYSQLI_ASSOC);
	$tipps = [];
	foreach ($res2 as $tipp) {
		$tipps[$tipp["day"]] = $tipp["tipp"];
	}
	if (!$res) {
		alert("danger", "Es wurden keine Aufgaben gefunden!");
	} else {
		$res = $res->fetch_all(MYSQLI_ASSOC);
		if (!$res) {
			alert("danger", "Es wurden keine Aufgaben gefunden!");
		} else {
			if ($mode == "list") {
				$tasks = array();

				$keys = array_keys($res);
				shuffle($keys);

				foreach ($keys as $key) {
					$tasks[$key] = $res[$key];
				}
				foreach ($tasks as $day) {
					echo "<div class='day'><a href='./aufgabe.php?a=" . $day["day"] . "'>";
					$allow = checkForDate($day["day"]);
					if ($allow == "today") {
						echo "<img class='present' src='./images/presentToday.png'>";
					} else {
						if ($allow == "past") {
							$alternatives = array_map("strtoupper", explode("____", $day["alternatives"]));
							if (isset($tipps[$day["day"]]) and strtoupper($day["word"]) == strtoupper($tipps[$day["day"]])) {
								echo "<img class='overlay' src='./images/right.png'>";
							} else if (isset($tipps[$day["day"]]) and in_array(strtoupper($tipps[$day["day"]]), $alternatives) and !empty($tipps[$day["day"]])) {
								echo "<img class='overlay' src='./images/half.png'>";
							} else {
								echo "<img class='overlay' src='./images/wrong.png'>";
							}
						}
						echo "<img class='present' src='./images/present.png'>";
					}


					echo "<p class='number'>" . $day["day"] . "</p>";
					echo "</a></div>";
				}
			} else if ($mode == "list") {
				echo "bild";
			}
		}
	}
}

function updatePoints()
{
	$db = connect();
	$res = $db->query("SELECT * FROM users");
	if (!$res) {
		alert("danger", "Es wurden keine Benutzer gefunden!");
		die();
	} else {
		$res = $res->fetch_all(MYSQLI_ASSOC);
		if (!$res) {
			alert("danger", "Es wurden keine Benutzer gefunden!");
			die();
		} else {
			$users = $res;
		}
	}

	$res = $db->query("SELECT * FROM days");
	if (!$res) {
		alert("danger", "Es wurden keine Aufgaben gefunden!");
		die();
	} else {
		$res = $res->fetch_all(MYSQLI_ASSOC);
		if (!$res) {
			alert("danger", "Es wurden keine Aufgaben gefunden!");
			die();
		}
	}
	$days = [];
	foreach ($res as $day) {
		$days[$day["day"]] = $day;
	}

	$res = $db->query("SELECT * FROM tipps");
	if (!$res) {
		alert("danger", "Es wurden keine Tipps gefunden!");
		die();
	} else {
		$res = $res->fetch_all(MYSQLI_ASSOC);
		if (!$res) {
			alert("danger", "Es wurden keine Tipps gefunden!");
			die();
		} else {
			$tipps = $res;
		}
	}

	
	$userPoints = [];
	$champions = [];
	foreach ($users as $user) {
		$userpoints[$user["id"]] = 0;
		
	}
	echo "<br>";
	foreach ($tipps as $tipp) {
		$userid = $tipp["userid"];
		$day = $tipp["day"];
		if (checkForDate($day) == "past") {
			$tipp = strtoupper($tipp["tipp"]);
			$solution = strtoupper($days[$day]["word"]);
			$alternatives = array_map("strtoupper", explode("____", $days[$day]["alternatives"]));

			if ($tipp == $solution) {

				if ($day == WEIHNACHTSTAG) {
					$userpoints[$userid] += 60;
				} else {
					if (intval($day) <= 8) {
						$userpoints[$userid] += 10;
					} else if (intval($day) <= 15) {
						$userpoints[$userid] += 20;
					} else if (intval($day) <= 23) {
						$userpoints[$userid] += 30;
					}
				}
			} else if (in_array($tipp, $alternatives) and !empty($tipp) and $tipp != "" && trim($tipp) != "") {
				if (intval($day) <= 8) {
					$userpoints[$userid] += 10;
				} else if (intval($day) <= 15) {
					$userpoints[$userid] += 20;
				} else if (intval($day) <= 23) {
					$userpoints[$userid] += 30;
				}
			}
		}



	}
	foreach ($userpoints as $id => $points) {
		$id = $db->real_escape_string($id);
		$points = $db->real_escape_string($points);
		$db->query("UPDATE users SET points=$points WHERE id=$id");
		echo $db->error;
	}
}

function createBestenliste($query, $whereami)
{
	$html = "";
	$db = connect();



	if ($query != "grades") {
		$res = $db->query($query);
		if (!$res) {
			alert("danger", "Es wurden keine Benutzer gefunden!<br>Fehler: " . $db->error);
		} else {
			$res = $res->fetch_all(MYSQLI_ASSOC);
			if (!$res) {
				alert("warning", "Es haben noch keine Spieler am AG-Ventskalender teilgenommen!");
			} else {
				$counter = 0;
				$html .= "<table class='table table-striped table-hover'><thead><th>Rang</th><th>Punkte</th><th>Klasse</th><th>Nickname</th></thead><tbody>";
				$letztePunkte = -1;
				$gesamtcounter = 0;
				foreach ($res as $user) {
					$nickname = htmlspecialchars($user["nickname"]);
					$grade = htmlspecialchars($user["grade"]);
					$points = htmlspecialchars($user["points"]);

					if ($letztePunkte != (int) $user["points"]) {
						$counter++;
						$letztePunkte = (int) $user["points"];
					}

					$html .= "<tr";
					if ($whereami == true and $user["id"] == $_SESSION["userid"]) {

						$meinplatz = $counter;
						$html .= " class='success' id='me'";
					}
					$html .= "><td>$counter</td><td>$points</td><td>$grade</td><td>$nickname</td></tr>";
					$gesamtcounter++;
				}
				$html .= "</tbody></table>";
			}
		}
	} else {
		$res = $db->query("SELECT * FROM users  WHERE `hideInScores`!=1");
		if (!$res) {
			alert("danger", "Es wurden keine Benutzer gefunden!<br>Fehler: " . $db->error);
		} else {
			$res = $res->fetch_all(MYSQLI_ASSOC);
			if (!$res) {
				alert("warning", "Es haben noch keine Spieler am AG-Ventskalender teilgenommen!");
			} else {
				$grades = [];
				$letztePunkte = -1;
				foreach ($res as $user) {
					if (!isset($grades[$user["grade"]])) {

						$grades[$user["grade"]] = array();
						$grades[$user["grade"]]["count"] = 1;
						$grades[$user["grade"]]["points"] = intval($user["points"]);
					} else {
						$grades[$user["grade"]]["count"] += 1;
						$grades[$user["grade"]]["points"] += intval($user["points"]);
					}
				}

				foreach ($grades as $key => $grade) {
					$grades[$key] = round($grade["points"] / $grade["count"], 1);
				}

				arsort($grades);


				$counter = 0;
				$html .= "<table class='table table-striped table-hover'><thead><th>Rang</th><th>Durchschnittliche Punktzahl</th><th>Klasse</th></thead><tbody>";
				foreach ($grades as $name => $grade) {
					$gradename = ($name == "Lehrer/in") ? "Lehrer" : htmlspecialchars($name);
					$points = htmlspecialchars($grade);
					if ($letztePunkte != (int) $points) {
						$counter++;
						$letztePunkte = (int) $points;
					}
					$html .= "<tr><td>$counter</td><td>$points</td><td>$gradename</td></tr>";

				}
				$html .= "</tbody></table>";
			}
		}
	}
	if ($whereami and isset($meinplatz)) {
		echo "<br><br>";
		alert("info", "Du bist aktuell auf dem $meinplatz. Platz von $gesamtcounter Teilnehmern!");
	}
	echo $html;
}

function getHead()
{
	echo '<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
	
	
	
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./images/favicon.png">
	
    <title>AG - AG-Ventskalender</title>

    <link href="./include/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="./include/lib/schnee.js"></script>
    <link href="./styles/main.css" rel="stylesheet">
	
   <style>
	@media (min-width: 768px) {
		.container {
		max-width: 730px;
		}
	} 
	</style>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="./include/lib/bootstrap/jquery.min.js"></script>
	<script src="./include/lib/bootstrap/bootstrap.min.js"></script>
	';
}

function getNav($current)
{
	global $loggedin;
	echo '<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
	<a class="navbar-brand" href="index.php"><img style="height: 46px" src="./images/header.png"></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse " id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
		  <li ' . ($current == "index" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./index.php">Start</a></li>';
	if (!$loggedin) {
		echo '<li ' . ($current == "login" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./login.php">Einloggen</a></li>
            <li ' . ($current == "neuer_benutzer" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./neuer_benutzer.php">Registrieren</a></li>';
	} else {
		echo '<li ' . ($current == "logout" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./logout.php">Abmelden</a></li>
			<li ' . ($current == "aufgaben" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./aufgaben.php#tab1">Aufgaben</a></li>';
	}
	echo '<li ' . ($current == "bestenliste" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./bestenliste.php#tab4">Bestenliste</a></li>
            <li ' . ($current == "regeln" ? 'class="nav-item active"' : "class='nav-item'") . '><a class="nav-link" href="./regeln.php">Regeln</a></li>
		  
		</ul>
		
	  </div>
</nav>';
}

function checkForDate($dayid)
{
	date_default_timezone_set('Europe/Berlin');
	$year = date("Y");
	if (isset($_SESSION["heutigerTag"])) {
		$now = new DateTime($_SESSION["heutigerTag"] . ".12.$year");
		$todayday = $_SESSION["heutigerTag"];
		$current = $now->getTimestamp();
		$date = strtotime("$year-12-$dayid");
		$month = 12;
	} else {
		$now = new DateTime("TODAY");
		$todayday = strftime("%e");
		$current = $now->getTimestamp();
		$date = strtotime("$year-12-$dayid");
		$month = strftime("%m");
	}

	$wochentag = date("w", $current);
	$datediff = $date - $current;
	$differance = floor($datediff / (60 * 60 * 24));

	if ((($todayday == 25 || $todayday == 26 || $todayday == 27) && $dayid == WEIHNACHTSTAG) && $month == 12) {
		return "today";
	}


	$zurueckliegend = null;
	switch ($wochentag) {
		case 0:
			$zurueckliegend = 3;
			break;
		case 1:
			$zurueckliegend = 4;
			break;
		case 2:
			$zurueckliegend = 4;
			break;
		case 3:
			$zurueckliegend = 2;
			break;
		case 4:
			$zurueckliegend = 2;
			break;
		case 5:
			$zurueckliegend = 2;
			break;
		case 6:
			$zurueckliegend = 2;
			break;
	}
	if (-$zurueckliegend < $differance and $differance <= 0) {
		return "today";
	} else if ($differance > 0) {
		return "future";
	} else if ($differance <= -$zurueckliegend) {
		return "past";
	}
}

function getUserPoints()
{
	$db = connect();
	$uid = $db->real_escape_string($_SESSION["userid"]);
	$res = $db->query("SELECT points FROM users WHERE id = $uid");
	if (!$res) {
		alert("danger", "Dein Benutzer wurde nicht gefunden!");
		die();
	} else {
		$res = $res->fetch_all(MYSQLI_ASSOC);
		if (!$res) {
			alert("danger", "Dein Benutzer wurde nicht gefunden!");
			die();
		} else {
			if (isset($res[0]) && isset($res[0]["points"])) {
				return $res[0]["points"];
			} else {
				alert("danger", "Irgendwas ist schief gelaufen!");
				die();
			}
		}
	}
}

function getFooter()
{
	echo '
	<section class="bg-dark">
		<div class="container">

		<footer class="footer">
				<p>&copy; ' . date("Y") . ' AG-Multimedia des Allgäu-Gymnasiums Kempten. Alle Rechte vorbehalten.</p>
			</footer>
		</div>
	</section>';
}
