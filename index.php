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
	<div class="bgimgdiv"></div>
    <div class="container">
      <?php echo getNav("index"); ?>

      <div class="jumbotron text-center">
        
		
        
		<h4>Der etwas andere</h4><h2>AG-Ventskalender</h2><h3>des Allgäu-Gymnasiums</h3>
		<img class="leadImage img-fluid" src="./images/banner.jpg"><br>
		
		<?php
		if ($loggedin) {
			$db = connect();
			$userid = $db->real_escape_string($_SESSION["userid"]);
			/*
			$res = $db->query("SELECT worldchampion FROM users WHERE id=$userid");
			$wm = "noch nicht getippt";
			$wmbanner = true;
			if ($res) {
				$res = $res->fetch_all(MYSQLI_ASSOC);
				if ($res) {
					$res = $res[0];
					if ($res) {
						//$wm = (isset($res["worldchampion"])) ? $res["worldchampion"] : "noch nicht getippt";
						if (isset($res["worldchampion"])) {
							$short = $db->real_escape_string($res["worldchampion"]);
							$res = $db->query("SELECT * FROM `teams` WHERE `short` = '$short'");
							echo $db->error;
							if ($res) {
								$res = $res->fetch_all(MYSQLI_ASSOC);
								if ($res) {
									$res = $res[0];
									if ($res) {
										if (isset($res["name"])) {
											$wm = $res["name"];
											$wmbanner = false;
											
											
											
										}
										
									} 
								}
							}
						}
					} 
				}
			}
		
			if ($wmbanner) {
				
				$res = $db->query("SELECT * FROM matches ORDER BY `date` ASC LIMIT 1");
				echo $db->error;
				if (!$res) {
					alert("danger", "Es wurden keine Spiele gefunden!");
				} else {
					$res = $res->fetch_all(MYSQLI_ASSOC);
					if (!$res) {
						alert("danger", "Es wurden keine Spiele gefunden!");
					} else {

						$start_date = new DateTime("@".strtotime($res[0]["date"]));
						if (new DateTime("NOW") < $start_date) {
							 $wmbanner = true;

						} else {
							$wmbanner = false;
						}
					}
				}
			}
			*/	
		?>
		<h2>Willkommen <?php echo $_SESSION["nickname"]; ?></h2><br>
		<!--<p class="lead">Du hast folgenden Weltmeister getippt: <i><?php echo $wm; ?></i></p>
		  <?php 
			/*if ($wmbanner) {
				alert("warning", "<h3>Du hast noch keinen Welmeister getippt!</h3><br>Klicke jetzt <a href='./weltmeister.php'>HIER</a> um noch auf eine Mannschaft als Weltmeister zu setzen und bei Erfolg noch mal <b>80 Punkte</b> dazu zu erhalten!");
			} */
		  ?>-->
		<!--<p class="lead">Hier kannst du deine Tipps abgeben und die Ergebnisse ansehen:</p>-->
		<p><a class="btn btn-lg btn-primary" href="aufgaben.php#tab1" role="button">Aufgaben &amp; Lösungen</a></p>
		<p><a class="btn btn-lg btn-success" href="./bestenliste.php#tab4" role="button">Bestenliste</a></p>
        
		
		<?php } else { ?>
		
		<h2>Wie gut kennst du das AG?</h2>
		<p class="lead">Melde dich jetzt an, spiele mit und gewinne mit etwas Glück einen der vielen Preise!</p>
        <p><a class="btn btn-lg btn-primary" href="./login.php" role="button">Einloggen</a></p>
        <p><a class="btn btn-lg btn-success" href="./neuer_benutzer.php" role="button">Heute noch anmelden</a></p>
        
		
		<?php } ?>
		
		<br><br>
		<small>Der rein nicht-kommerzielle AG-Ventskalender des Allgäu-Gymnasiums</small>
      </div>
	

      <?php getFooter(); ?>

    </div> <!-- /container -->


  </body>
</html>
