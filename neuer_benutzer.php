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

    <div class="container">
      <?php getNav("neuer_benutzer"); ?>

      <div class="jumbotron">
        <h1>AG AG-Ventskalender</h1>
		<br>
		<h3>Jetzt anmelden!</h3>
		<?php
			$ok = false;
		  	/*echo "<pre>";
		  	var_dump($_POST);
		  	echo "</pre>";*/
		  	if (isset($_POST["submit"])) {
				if (isset($_POST["name"]) &&
				isset($_POST["firstname"]) &&
				isset($_POST["grade"]) &&
				isset($_POST["nickname"]) &&
				isset($_POST["password"]) &&
				isset($_POST["password2"]) &&
				!empty(trim($_POST["name"])) &&
				!empty(trim($_POST["grade"])) &&
				!empty(trim($_POST["nickname"])) &&
				!empty(trim($_POST["password"])) &&
				!empty(trim($_POST["password2"]))) {
					$ok = true;
					if ($_POST["password"] == $_POST["password2"]) {
						$db = connect();
						$name = $db->real_escape_string(trim($_POST["firstname"]))." ".$db->real_escape_string(trim($_POST["name"]));
						$nickname = $db->real_escape_string($_POST["nickname"]);
						
						$grade = $db->real_escape_string($_POST["grade"]);
						
						$password = $_POST["password"];
						$res = $db->query("SELECT * FROM `users` WHERE `name` = '$name' OR `nickname` = '$nickname' AND `checked` != -1");
						//var_dump($res);
						//echo $db->error;
						////////////////////////////////////////////
						if ($res) {
							$res = $res->fetch_all(MYSQLI_ASSOC);

							if ($res) {
								$res = $res[0];
								if ($res) {
									if ($res["nickname"] == $nickname) {
										alert("warning", "Dieser Nickname existiert bereits. Bitte verwenden einen anderen!");
										$ok = false;
									}
									if ($res["name"] == $name) {
										alert("warning", "Dieser Spieler existiert bereits. Jede Person kann nur einmal spielen!");
										$ok = false;
									}
								} else {

								}

							}

						}

						if (!preg_match("/^\p{Lu}[\p{L} '&-]*[\p{L}]$/u", $name)) {
							alert("warning", "Bite gib deinen richtigen Namen an!");
							$ok = false;
						}

						if (strlen($password) < 5) {
							alert("warning", "Dein Passwort muss mindestens 5 Zeichen enthalten!");
							$ok = false;
						}
						elseif(!preg_match("#[0-9]+#",$password)) {
							alert("warning", "Dein Passwort muss mindestens 1 Zahl enthalten!");
							$ok = false;
						}
						elseif(!preg_match("#[A-Z]+#",$password)) {
							alert("warning", "Dein Passwort muss mindestens 1 Großbuchstaben enthalten!");
							$ok = false;
						}
						elseif(!preg_match("#[a-z]+#",$password)) {
							alert("warning", "Dein Passwort muss mindestens 1 Kleinbuchstaben enthalten!");
							$ok = false;
						}elseif (!preg_match("#^[a-zA-Z0-9äöüÄÖÜß \.\_]+$#", $nickname)) {
   							alert("warning", "Der Nickname darf nur Buchstaben, Umlaute, Zahlen, Leerzeichen, Punkte und Unterstriche enthalten!");
							$ok = false;
						}


						//alert("info", "Test bestanden!");
						////////////////////////////////////////////
						if ($ok) {
							$password = password_hash($db->real_escape_string($_POST["password"]), PASSWORD_DEFAULT);
							$res = $db->query("INSERT INTO `users` (`name`, `nickname`, `grade`, `password`, `checked`, `points`) VALUES ('$name', '$nickname', '$grade', '$password', '0', '0')");
							if (!$res) {
								die("Fehler: ".$db->error);
							}

							alert("success", "Du bist nun erfolgreich angemeldet!<br><br><a href='./login.php' class='btn btn-primary'>Gleich einloggen</a>");
							$ok = true;
						}

					} else {

						alert("danger", "Die Passwörter stimmen nicht überein");
						$ok = false;
					}
				} else {
					alert("danger", "Du hast nicht alle Felder ausgefüllt!");
					$ok = false;
				}
			} else if (isset($_POST["submitReuse"])) {
				if (isset($_POST["username"]) &&
				isset($_POST["password"]) &&
				!empty(trim($_POST["username"])) &&
				!empty(trim($_POST["password"]))) {
					$ok = true;
					$db = connect();
				
					$nickname = $db->real_escape_string($_POST["username"]);
					$res = $db->query("SELECT * FROM wmusers WHERE nickname='$nickname'");

					if (!$res) {
						alert("danger", "Bitte überprüfe deinen Benutzernamen!");
						$ok = false;
					} else {
						$res = $res->fetch_all(MYSQLI_ASSOC);
						if (!$res) {
							alert("danger", "Bitte überprüfe deinen Benutzernamen!");
							$ok = false;
						} else {
							$res = $res[0];
							if (!$res) {
								alert("danger", "Bitte überprüfe deinen Benutzernamen!");
								$ok = false;
							} else {
								$password = $res["password"];
								$status = password_verify($_POST["password"], $password);

								if ($status) {
									// erfolgreich eingeloggt!!!
									if ($res["checked"] == -1) {
										alert("warning", "<b>Dein Account wurde schon beim Tippspiel blockiert.</b><a href=''./neuer_benutzer.php?mode=new'>Erstelle einfach einen neuen Account</a><br>");
										die();
									}

									
									$_SESSION["username"] = $res["nickname"];
									$_SESSION["name"] = $res["name"];
									$_SESSION["grade"] = $res["grade"];
									$_SESSION["userid"] = $res["id"];
									$_SESSION["password"] = $password;

									header("Location: neuer_benutzer.php?step2");
								} else {
									alert("danger", "Bitte überprüfe dein Passwort!");
									$ok = false;
								}
							}

						}

					}
					

					
				} else {
					alert("danger", "Du hast nicht alle Felder ausgefüllt!");
					$ok = false;
				}
			} else if (isset($_POST["submitReuse2"])) {
				if (isset($_POST["username"]) &&
				!empty(trim($_POST["username"]))) {
					$_SESSION["username"] = $_POST["username"];
					header("Location: neuer_benutzer.php?step3");
				}
					
			} else if (isset($_POST["submitReuse3"])) {
				if (isset($_POST["grade"]) &&
				!empty(trim($_POST["grade"]))) {
					$db = connect();
					$password = $db->real_escape_string($_SESSION["password"]);
					$name = $db->real_escape_string($_SESSION["name"]);
					$nickname = $db->real_escape_string($_SESSION["username"]);
					$grade = $db->real_escape_string($_POST["grade"]);
					$res = $db->query("INSERT INTO `users` (`name`, `nickname`, `grade`, `password`, `checked`, `points`) VALUES ('$name', '$nickname', '$grade', '$password', '0', '0')");
					if (!$res) {
						die("Fehler: ".$db->error);
					}
					
					
					header("Location: login.php?success");
				}
			}
					
			
			
			if (!$ok) {
				$firstname = (isset($_POST["firstname"])) ? $_POST["firstname"] : "";
				$name = (isset($_POST["name"])) ? $_POST["name"] : "";
				$nickname = (isset($_POST["nickname"])) ? $_POST["nickname"] : "";
				$grade = (isset($_POST["grade"])) ? $_POST["grade"] : "";
				
				$username = (isset($_POST["username"])) ? $_POST["username"] : "";
			//echo "<pre>";
			//var_dump($_REQUEST);
			//echo "</pre>";
		?>
		<br>
		  
		<?php if (isset($_GET["step2"])) {
			$reuseusername = $_SESSION["username"];
		  alert("info", "Wenn du magst, kannst du hier deinen Benutzernamen anpassen");?>
		  
		  <form class="form-horizontal" method="post">
			<div class="form-group">
				
				
					
					<input type="text" name="username" required autofocus class="form-control" id="username" value="<?php echo $reuseusername; ?>" placeholder="Vorname"><div class="alert alert-info text-left" role="alert">Bitte gib hier deinen Benutzernamen vom Tippspiel an.</div>
				
			</div>
			
			
			<div class="form-group">
				<div class="pull-right">
					<input type="submit" name="submitReuse2" value="Weiter" class="btn btn-primary">
				</div>
			</div>
			
		</form>
		  <?php } else if (isset($_GET["step3"])) {
			$grade = $_SESSION["grade"];
		  alert("info", "Bitte passe deine Klasse an");?>
		  
		  <form class="form-horizontal" method="post">
			<div class="form-group">
				
				
				<select name="grade" required class="form-control" id="grade">
					<optgroup label="Lehrer/in">
							<option name="grade" <?php if ($grade == "Lehrer/in") echo " selected "; ?>value="Lehrer/in">Lehrer/in</option>
							<option name="grade" <?php if ($grade == "Studienseminar 17/19") echo " selected "; ?>value="Studienseminar 17/19">Studienseminar 17/19</option>
							<option name="grade" <?php if ($grade == "Studienseminar 20/18") echo " selected "; ?>value="Studienseminar 18/20">Studienseminar 18/20</option>
							
						</optgroup>
						<optgroup label="Jahrgangsstufe 5">
							<option name="grade" <?php if ($grade == "5a" OR !$grade) echo " selected "; ?>value="5a">5a</option>
							<option name="grade" <?php if ($grade == "5b") echo " selected "; ?>value="5b">5b</option>
							<option name="grade" <?php if ($grade == "5c") echo " selected "; ?>value="5c">5c</option>
							<option name="grade" <?php if ($grade == "5d") echo " selected "; ?>value="5d">5d</option>
							<option name="grade" <?php if ($grade == "5e") echo " selected "; ?>value="5e">5e</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 6">
							<option name="grade" <?php if ($grade == "6a") echo " selected "; ?>value="6a">6a</option>
							<option name="grade" <?php if ($grade == "6b") echo " selected "; ?>value="6b">6b</option>
							<option name="grade" <?php if ($grade == "6c") echo " selected "; ?>value="6c">6c</option>
							<option name="grade" <?php if ($grade == "6d") echo " selected "; ?>value="6d">6d</option>
							<option name="grade" <?php if ($grade == "6e") echo " selected "; ?>value="6e">6e</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 7">
							<option name="grade" <?php if ($grade == "7a") echo " selected "; ?>value="7a">7a</option>
							<option name="grade" <?php if ($grade == "7b") echo " selected "; ?>value="7b">7b</option>
							<option name="grade" <?php if ($grade == "7c") echo " selected "; ?>value="7c">7c</option>
							<option name="grade" <?php if ($grade == "7d") echo " selected "; ?>value="7d">7d</option>
							<option name="grade" <?php if ($grade == "7e") echo " selected "; ?>value="7e">7e</option>
						</optgroup>

						<optgroup label="Jahrgangsstufe 8">
							<option name="grade" <?php if ($grade == "8a") echo " selected "; ?>value="8a">8a</option>
							<option name="grade" <?php if ($grade == "8b") echo " selected "; ?>value="8b">8b</option>
							<option name="grade" <?php if ($grade == "8c") echo " selected "; ?>value="8c">8c</option>
							<option name="grade" <?php if ($grade == "8d") echo " selected "; ?>value="8d">8d</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 9">
							<option name="grade" <?php if ($grade == "9a") echo " selected "; ?>value="9a">9a</option>
							<option name="grade" <?php if ($grade == "9b") echo " selected "; ?>value="9b">9b</option>
							<option name="grade" <?php if ($grade == "9c") echo " selected "; ?>value="9c">9c</option>
							<option name="grade" <?php if ($grade == "9d") echo " selected "; ?>value="9d">9d</option>
						<optgroup label="Jahrgangsstufe 10">
							<option name="grade" <?php if ($grade == "10a") echo " selected "; ?>value="10a">10a</option>
							<option name="grade" <?php if ($grade == "10b") echo " selected "; ?>value="10b">10b</option>
							<option name="grade" <?php if ($grade == "10c") echo " selected "; ?>value="10c">10c</option>
							<option name="grade" <?php if ($grade == "10d") echo " selected "; ?>value="10d">10d</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 11">
							<option name="grade" <?php if ($grade == "Q11") echo " selected "; ?>value="Q11">Q11</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 12">
							<option name="grade" <?php if ($grade == "Q12") echo " selected "; ?>value="Q12">Q12</option>
						</optgroup>
					</select>
				
			</div>
			
			
			<div class="form-group">
				<div class="pull-right">
					<input type="submit" name="submitReuse3" value="Fertig" class="btn btn-primary">
				</div>
			</div>
			
		</form>
		<?php } else if (!isset($_GET["mode"])) { ?>
		<a href="./neuer_benutzer.php?mode=reuse" type="button" class="btn btn-primary btn-lg btn-block">Account vom WM-Tippspiel weiterverwenden</a>
		<a href="./neuer_benutzer.php?mode=new" type="button" class="btn btn-success btn-lg btn-block">Neuen Account erstellen</a>
		<?php } else if ($_GET["mode"] == "reuse") {?>
		<div class="alert alert-success" role="alert">Wenn du deinen Benutzernamen und dein Passwort vom Tippspiel noch weißt, kannst deinen Account wiederverwenden.</div>
		<br>
		<form class="form-horizontal" method="post">
			<div class="form-group">
				<label for="firstname" class="col-sm-2 control-label">Benutzername</label>
				<div class="col-sm-10">
					
					<input type="text" name="username" required autofocus class="form-control" id="username" value="<?php echo $username; ?>" placeholder="Nickname"><div class="alert alert-info text-left" role="alert">Bitte gib hier deinen Benutzernamen vom Tippspiel an.</div>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Passwort wiederholen</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" required id="password" name="password" placeholder="Passwort eingeben">
					<div class="alert alert-info text-left" role="alert">Bitte gib hier dein Passwort vom Tippspiel an. Du weißt es nicht mehr? Kein Problem: <a href="./neuer_benutzer.php?mode=new">Erstelle einfach einen neuen Account</a></div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="pull-right">
					<input type="submit" name="submitReuse" value="Weiter" class="btn btn-primary">
				</div>
			</div>
			
		</form>
		  <?php } else if ($_GET["mode"] == "new") {?>
		
		  <div class="alert alert-success" role="alert">Beim AG-Ventskalender können alle Schülerinnen und Schüler sowie alle Lehrerinnen und Lehrer des Allgäu-Gymnasiums teilnehmen.</div>
		<br>
		<form class="form-horizontal" method="post">
			<div class="form-group">
				<label for="firstname" class="col-sm-2 control-label">Vorname</label>
				<div class="col-sm-10">
					
					<input type="text" name="firstname" required autofocus class="form-control" id="firstname" value="<?php echo $firstname; ?>" placeholder="Vorname"><div class="alert alert-info text-left" role="alert">Bitte gib hier deinen echten Vornamen an, da dein Benutzer sonst blockiert wird und du nicht mehr mitspielen kannst.</div>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
					
					<input type="text" name="name" required autofocus class="form-control" id="name" value="<?php echo $name; ?>" placeholder="Nachname"><div class="alert alert-info text-left" role="alert">Bitte gib hier deinen echten Nachnamen an, da dein Benutzer sonst blockiert wird und du nicht mehr mitspielen kannst.</div>
				</div>
			</div>
			<div class="form-group">
				<label for="grade" class="col-sm-2 control-label">Klasse</label>
				<div class="col-sm-10">
					<select name="grade" required class="form-control" id="grade">
						<optgroup label="Lehrer/in">
							<option name="grade" <?php if ($grade == "Lehrer/in") echo " selected "; ?>value="Lehrer/in">Lehrer/in</option>
							<option name="grade" <?php if ($grade == "Studienseminar 17/19") echo " selected "; ?>value="Studienseminar 17/19">Studienseminar 17/19</option>
							<option name="grade" <?php if ($grade == "Studienseminar 20/18") echo " selected "; ?>value="Studienseminar 18/20">Studienseminar 18/20</option>
							
						</optgroup>
						<optgroup label="Jahrgangsstufe 5">
							<option name="grade" <?php if ($grade == "5a" OR !$grade) echo " selected "; ?>value="5a">5a</option>
							<option name="grade" <?php if ($grade == "5b") echo " selected "; ?>value="5b">5b</option>
							<option name="grade" <?php if ($grade == "5c") echo " selected "; ?>value="5c">5c</option>
							<option name="grade" <?php if ($grade == "5d") echo " selected "; ?>value="5d">5d</option>
							<option name="grade" <?php if ($grade == "5e") echo " selected "; ?>value="5e">5e</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 6">
							<option name="grade" <?php if ($grade == "6a") echo " selected "; ?>value="6a">6a</option>
							<option name="grade" <?php if ($grade == "6b") echo " selected "; ?>value="6b">6b</option>
							<option name="grade" <?php if ($grade == "6c") echo " selected "; ?>value="6c">6c</option>
							<option name="grade" <?php if ($grade == "6d") echo " selected "; ?>value="6d">6d</option>
							<option name="grade" <?php if ($grade == "6e") echo " selected "; ?>value="6e">6e</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 7">
							<option name="grade" <?php if ($grade == "7a") echo " selected "; ?>value="7a">7a</option>
							<option name="grade" <?php if ($grade == "7b") echo " selected "; ?>value="7b">7b</option>
							<option name="grade" <?php if ($grade == "7c") echo " selected "; ?>value="7c">7c</option>
							<option name="grade" <?php if ($grade == "7d") echo " selected "; ?>value="7d">7d</option>
							<option name="grade" <?php if ($grade == "7e") echo " selected "; ?>value="7e">7e</option>
						</optgroup>

						<optgroup label="Jahrgangsstufe 8">
							<option name="grade" <?php if ($grade == "8a") echo " selected "; ?>value="8a">8a</option>
							<option name="grade" <?php if ($grade == "8b") echo " selected "; ?>value="8b">8b</option>
							<option name="grade" <?php if ($grade == "8c") echo " selected "; ?>value="8c">8c</option>
							<option name="grade" <?php if ($grade == "8d") echo " selected "; ?>value="8d">8d</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 9">
							<option name="grade" <?php if ($grade == "9a") echo " selected "; ?>value="9a">9a</option>
							<option name="grade" <?php if ($grade == "9b") echo " selected "; ?>value="9b">9b</option>
							<option name="grade" <?php if ($grade == "9c") echo " selected "; ?>value="9c">9c</option>
							<option name="grade" <?php if ($grade == "9d") echo " selected "; ?>value="9d">9d</option>
						<optgroup label="Jahrgangsstufe 10">
							<option name="grade" <?php if ($grade == "10a") echo " selected "; ?>value="10a">10a</option>
							<option name="grade" <?php if ($grade == "10b") echo " selected "; ?>value="10b">10b</option>
							<option name="grade" <?php if ($grade == "10c") echo " selected "; ?>value="10c">10c</option>
							<option name="grade" <?php if ($grade == "10d") echo " selected "; ?>value="10d">10d</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 11">
							<option name="grade" <?php if ($grade == "Q11") echo " selected "; ?>value="Q11">Q11</option>
						</optgroup>
						<optgroup label="Jahrgangsstufe 12">
							<option name="grade" <?php if ($grade == "Q12") echo " selected "; ?>value="Q12">Q12</option>
						</optgroup>
					</select>
					<div class="alert alert-info text-left" role="alert">Bitte gib hier deine richtige Klasse bzw. deinen Oberstufenjahrgang an, da dein Benutzer sonst blockiert wird und du nicht mehr mitspielen kannst.
						<br>Wenn Sie eine Lehrkraft sind, wählen Sie bitte <i>Lehrer/in</i> oder das entsprechende Studienseminar aus.</div>
						
				</div>
			</div>
			
			<div class="form-group">
				<label for="nickname" class="col-sm-2 control-label">Nickname</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" required id="nickname" value="<?php echo $nickname; ?>" name="nickname" placeholder="Nickname">
					<div class="alert alert-info text-left" role="alert">Dein Nickname soll ein erfundener Name sein, mit dem du in der Bestenliste auf der Schulhomepage angezeigt wirst, z.B. <i>Özil2</i>. Es sind nur Buchstaben, Umlaute, Zahlen, Lehrzeichen, Punkte und Unterstriche erlaubt!<br>Benutzer, deren Nicknamen eine Beleidigung enthalten oder gegen die Regeln der guten Sitten verstoßen, werden gelöscht.</div>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Passwort</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" required id="password" name="password" placeholder="Passwort">
					<div class="alert alert-info text-left" role="alert">Dein Passwort muss folgende Kriterien erfüllen:
						<ol>
							<li>mindestens 5 Zeichen</li>
							<li>mindestens 1 Kleinbuchstabe</li>
							<li>mindestens 1 Großbuchstabe</li>
							<li>mindestens 1 Zahl</li>
						</ol>
						Merke es dir gut und gib es nicht weiter!
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="password2" class="col-sm-2 control-label">Passwort wiederholen</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" required id="password2" name="password2" placeholder="Passwort wiederholen">
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" name="submit" value="Registrieren" class="btn btn-primary">
				</div>
			</div>
			
		</form>
		  
		  
		   <?php } else { 
			alert("danger", "Unbekannter Fehler. Bitte wende dich an Hannes Rüger, Klasse 9a.");
		
			} ?>
		
			
		<?php } ?>
		
		
		
      </div>
	

      <?php getFooter(); ?>

    </div> <!-- /container -->


    <!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
