<?php
require_once("./include/lib.inc.php");
require_once("./include/db.inc.php");
require_once("./include/login.inc.php");


?>
<!DOCTYPE html>
<html lang="de">

<head>
	<?php getHead(); ?>
	<style>
		.taskimg {
			max-width: 70%;
			display: inline-block;
			vertical-align: middle;
			float: none;
			border: 5px solid #4A1C19;
			border-radius: 3px;
		}

		.inputChar {
			text-align: center;
			font-size: 2.3em !important;
			width: 1em !important;
			height: 1em !important;
			padding: 01px !important;
			display: inline;
		}

		.btn {
			white-space: normal;
		}

		.important {
			background-color: rgba(255, 229, 153, 1.00);
		}

		.solution {
			color: rgba(255, 0, 4, 1.00);
			font-weight: bold;
		}

		.solutionHint {
			color: rgba(255, 0, 4, 1.00);
			font-weight: bold;
			font-size: 1.7em;
		}

		.dndletter {
			margin: 5px;
			display: inline-block;
			border: 1px solid rgba(0, 124, 255, 1.00);
			background-color: #d9edf7;
			border-radius: 3px;
			height: 40px;
			width: 40px;
		}

		.dndletter p {
			margin: 2px !important;
			padding: 2px 8px;
		}

		.dndfree {
			background-color: rgba(255, 255, 255, 1.00);
			border: 2px solid rgba(215, 215, 215, 1.00);
			border-radius: 3px;
			display: inline-block;
			width: 40px;
			height: 40px;
		}

		.full {
			/*background-color: rgba(241, 236, 163, 1.00);*/
			box-shadow: 0 0 2px 2px #FFE500;
		}

		.dndletter:hover {
			cursor: pointer;
			background-color: #98CEE9;
		}

		.dndprefilled {
			background-color: rgba(255, 255, 255, 1.00);
			border: 2px solid rgba(215, 215, 215, 1.00);
			border-radius: 3px;
			display: inline-block;
			width: 40px;
			height: 40px;
			font-weight: 200;
			font-size: 21px;
			margin: 5px;
			display: inline-block;
			border: 1px solid rgba(0, 124, 255, 1.00);
			background-color: #d9edf7;
			border-radius: 3px;
		}

		.dndprefilled p {}
	</style>
</head>

<body class="bgimg">

	<div class="container">
		<?php getNav("aufgabe"); ?>

		<div class="jumbotron text-center">
			<?php
			if ((isset($_POST["submit"]) && isset($_POST["dayid"])) || (isset($_POST["solution"]) && isset($_POST["dayid"]))) {


				$db = connect();

				$userid = $db->real_escape_string($_SESSION["userid"]);
				$day = $db->real_escape_string($_POST["dayid"]);

				$res = $db->query("SELECT * FROM tipps WHERE userid=$userid AND day=$day");

				if ($res) {
					$res = $res->fetch_all(MYSQLI_ASSOC);
					if ($res) {
						$res = $res[0];
						if ($res) {
							$existing = true;
						} else {
							$existing = false;
						}
					} else {
						$existing = false;
					}
				} else {
					$existing = false;
				}
				$res = $db->query("SELECT * FROM days WHERE day=$day");
				echo $db->error;
				
				if ($res) {
					$res = $res->fetch_all(MYSQLI_ASSOC);
					
					if ($res) {
						$res = $res[0];
						if ($res) {
							$task = $res;
						} else {
							alert("danger", "Aufgabe nicht gefunden!");
							die();
						}
					} else {
						alert("danger", "Aufgabe nicht gefunden!");
						die();
					}
				} else {
					alert("danger", "Aufgabe nicht gefunden!");
					die();
				}
				$allow = checkForDate($day);
				$word = "";
				if ($task["day"] == WEIHNACHTSTAG) {
					$word = $_POST["solution"];
				} else {
					foreach ($_POST as $key => $entry) {
						if (substr($key, 0, 4) === "char") {
							$word .= $entry;
						}
					}
				}
				$userid = $db->real_escape_string($userid);
				$word = $db->real_escape_string($word);
				$day = $db->real_escape_string($day);

				if ($allow == "today") {
					if ($existing) {
						$db->query("UPDATE tipps SET tipp='$word' WHERE userid=$userid AND day=$day");
					} else {
						$db->query("INSERT INTO tipps (userid, day, tipp) VALUES ('$userid', '$day', '$word')");
					}
				} else {
					if ($allow == "past") {
						alert("warning", "Du bist leider zu spät dran. Beeile dich beim nächsten mal!");
					} else {
						alert("warning", "Du bist leider zu früh dran. Warte noch ein bisschen!");
					}

					die();
				}
				if (!$db->error) {
					echo '<meta http-equiv="refresh" content="0; url=aufgaben.php?s">';
					die();
					alert("success", "Deine Lösung wurde erfolgreich gespeichert!");
				} else {
					alert("danger", "Ein Datenbankfehler. Bitte wende dich an Hannes Rüger, Klasse 10a.");
					echo $db->error;
				}

				die();
			} else if (!isset($_GET["a"])) {
				echo '<meta http-equiv="refresh" content="0; url=aufgaben.php">';
				die();
			}

			$db = connect();
			$id = $db->real_escape_string(intval($_GET["a"]));
			$uid = $db->real_escape_string($_SESSION["userid"]);

			$res = $db->query("SELECT * FROM tipps WHERE userid=$uid AND day=$id");
			if ($res) {
				$tipp = false;
				$res = $res->fetch_all(MYSQLI_ASSOC);
				if (!$res) {
					$tipp = false;
				} else {
					$tipp = $res;
				}
			} else {
				$tipp = false;
			}




			$res = $db->query("SELECT * FROM `days` WHERE day=$id");
			if (!$res) {
				alert("danger", "Es wurde keine Aufgabe gefunden!");
			} else {
				$res = $res->fetch_all(MYSQLI_ASSOC);
				if (!$res) {
					alert("danger", "Es wurde keine Aufgabe gefunden!");
				} else {
					$task = $res[0];
					$allow = checkForDate($task["day"]);
					if (
						$allow == "today"
						or $allow == "past"
					) {

						$wochentage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
						$nummer = (intval($task["day"]) < 10 ? '0' . $task["day"] : $task["day"]);
						setlocale(LC_TIME, "de_DE.utf8");
						$tag = $nummer . ". Dezember 2019";
						$tag = $wochentage[date("w", strtotime($nummer . ".12.2019"))] . ", " . $tag;
						echo "<h4>$tag</h4><br>";
						$dontEchoChristmasForm = false;
						if ($allow == "today") {
							if ($task["day"] != WEIHNACHTSTAG) {
								echo "<img class='img img-fluid taskimg' src='./images/getImage.php?d=$nummer&m=a'/>";
							}
						} else {
							if ($task["day"] != WEIHNACHTSTAG) {
								echo "<h4 class='solution'>LÖSUNG</h4><img class='img img-fluid taskimg' src='./images/getImage.php?d=$nummer&m=l'/>";
							} else {
								$dontEchoChristmasForm = true;


								$points = getUserPoints();

								echo "<br><h2>Herzlichen Glückwunsch!<br>";
								alert("success", "<p class='lead'>Du hast $points von 480 möglichen Punkten erhalten.</p>");
								echo "</h2><strong>Du kennst dich gut im Allgäu-Gymnasium aus.<br>Du wirst benachrichtigt, wenn du einen Preis gewonnen hast.</strong><br><br>";
								echo "<h4 class='solution'>LÖSUNG</h4>";
								echo "<p class='lead'>" . $task["word"] . "</p><br><br>".$task["solutionHint"]."<br>";
							}
						}
						if (!$dontEchoChristmasForm) {

							echo "<p class='lead'>" . $task["text"] . "</p>";

							$solution = preg_split('/(?!^)(?=.)/u', $task["word"]);
							if (empty($tipp)) {
								$buttonname = "Lösung abgeben";

								$tipp = false;
							} else {
								$buttonname = "Lösung ändern";
								$tipp = preg_split('/(?!^)(?=.)/u', $tipp[0]["tipp"]);
							}
							if ($task["day"] != WEIHNACHTSTAG) {
								echo '<form class="form justify-content-center" method="post">
							<input type="hidden" id="dayid" name="dayid" value="' . $_GET["a"] . '">
							<div class="form-group">';
							}



							if ($task["day"] == WEIHNACHTSTAG) {
								?>
								<link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/css/base/jquery.ui.all.css" rel="stylesheet">
								<link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.2/css/lightness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
								<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
								<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js"></script>
								<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-simulate/1.0.1/jquery.simulate.min.js"></script>
								<?php
													echo '<form action="./aufgabe.php" id="submitSolution" class="form-inline" method="post">
										<input type="hidden" id="dayid" name="dayid" value="' . $_GET["a"] . '"><input type="hidden" id="solution" name="solution" value=""></form>';
													$counter = "A";
													$word = preg_split('/(?!^)(?=.)/u', $task["word"]);
													shuffle($word);
													if (($key = array_search(',', $word)) !== false) {
														unset($word[$key]);
													}
													/*if (($key = array_search('N', $word)) !== false) {
														unset($word[$key]);
													}*/

													foreach ($word as $letter) {
														if ($letter != " ") {
															echo '<div class="draggable dndletter" data-id=' . $counter . ' data-letter="' . $letter . '"><p>' . $letter . '</p></div>';
															$counter++;
														}
													}


													?>
								<br>
								<br>
								<br>
								<br>

								<?php
													$letters = preg_split('/(?!^)(?=.)/u', $task["word"]);
													$counter = 0;
													foreach ($letters as $letter) {
														if ($letter == " ") {
															echo '<div class="dndspace" data-letterSubmit=" ">&nbsp</div>';
														} else {
															if ($counter == 7) {
																echo '<div class="dndprefilled" id="8" data-containsid="-1" data-letterSubmit=","><p>,</p></div>';
															/*} else if ($counter == 27) {
																echo '<div class="dndprefilled" id="27" data-containsid="-1" data-letterSubmit="A"><p>A</p></div>';*/
															} else {
																echo '<div class="droppable dndfree" id="box' . $counter . '" data-containsid="" data-letterSubmit=""><p>&nbsp</p></div>';
															}
														}
														$counter++;
													}


													?>


								<script>
									$(function() {
										$(".draggable").draggable({
											snap: ".droppable",

											drag: function(event, ui) {

											}
										});
										$(".droppable").droppable({
											drop: function(event, ui) {
												var id = ui.draggable.data("id");
												$("[data-containsid]").each(function() {
													if ($(this).data("containsid") == id) {
														$(this).removeClass("full");
														$(this).data("containsid", "");
														$(this).data("letterSubmit", "");
													}
												})
												$(this).data("letterSubmit", ui.draggable.data("letter"));
												$(this).data("containsid", id);
												$(this).addClass("full");
											}

										});
										$("#submitBtn").on("click", function() {
											var text = "";
											$("[data-letterSubmit]").each(function() {
												if (typeof $(this).data("letterSubmit") !== "undefined" || $(this).data("letterSubmit") == "") {
													text += $(this).data("letterSubmit");
												} else {
													if ($(this).hasClass("dndprefilled")) {
														if ($(this).attr("id") == "8") {
															text += ",";
														/*} else if ($(this).attr("id") == "27") {
															text += "A";*/
														} else {

														}
													} else {
														text += " ";
													}

												}

											});
											$("#solution").val(text);
											$("#submitSolution").submit();
										});

										var draggable = null;
										var droppable = null;
										var droppableOffset = null;
										var draggableOffset = null;
										var dx = null;
										var dy = null;




										setTimeout(function() {
											<?php
																if ($tipp) {
																	$counter = 0;
																	$usedletters = [];
																	foreach ($tipp as $letter) {
																		if ($letter != " ") {
																			if (isset($usedletters[$letter])) {
																				$usedletters[$letter]++;
																				$lettercounter = $usedletters[$letter];
																			} else {
																				$lettercounter = 1;
																				$usedletters[$letter] = 1;
																			}
																			echo '
																			draggable = null;
																			var counter = 1;
																			$(".draggable:contains(\'' . $letter . '\')").each(function() {
																				if (counter == ' . $lettercounter . ') {
																					draggable = $(this);
																				}
																				counter++;
																			});


																			if (draggable) {
																				droppable = $("#box' . $counter . '");
																				droppableOffset = droppable.offset();
																				draggableOffset = draggable.offset();
																				dx = droppableOffset.left - draggableOffset.left+20;
																				dy = droppableOffset.top - draggableOffset.top+20;

																				draggable.simulate("drag", {
																					dx: dx,
																					dy: dy
																				});
																			}
																			
																			
																			';
																		}
																		$counter++;
																	}
																}

																?>
										}, 10);


									});
								</script>
				<?php
									echo "<br><br>";
									alert("info", "Sortiere die Buchstaben mithilfe von Drag and Drop in die freien Kästchen, sodass ein Lösungssatz herauskommt. Das Komma ist bereits vorgegeben.");
									alert("warning", "Bis Donnerstag, den 26.12.2019 um 23:59 Uhr kannst du eine Lösung abgeben und ändern.");

									echo '<br><br>';
									if ($allow == "today") {
										echo '<button id="submitBtn" name="submit" type="submit" class="btn btn-success">' . $buttonname . '</button>';
									} else {
										echo '<button class="btn btn-success disabled">Zu spät...</button>';
									}
									echo "<br>";
								} else {
									// kein Weihnachten
									$counter = 1;
									if ($allow != "today") {
										echo "<div><h4 class='solutionHint d-block'>" . implode("", $solution) . "</h4></div><b>(".$task["solutionHint"].")</b><div><br><b>Dein Tipp:</b></div>";
									}
									echo "<div>";
									foreach ($solution as $key => $unused) {
										if (!empty($tipp) && isset($tipp[$key])) {
											$val = $tipp[$key];
										} else {
											$val = "";
										}

										if ($task["letter"] == $counter) {
											echo "<input class='form-control inputChar important' maxlength='1' size='1' type='text' value='$val' name='char" . $counter . "'>";
										} else {
											echo "<input class='form-control inputChar' maxlength='1' size='1' type='text' value='$val' name='char" . $counter . "'>";
										}

										$counter++;
									}
									echo "</div>";
									if ($allow == "past") {
										$alternatives = implode(",<br>", explode("____", $task["alternatives"]));
										if ($alternatives) {
											$alternatives = "<div><p>Auch akzeptiert wird/werden: <br>" . $alternatives . "</p></div>";
										}
										echo "<div><h4 class='solutionHint'>$alternatives</h4></div>";
									}

									echo '</div><br><br>';
									alert("info", "Alle Umlaute, Sonderzeichen etc. werden <b>nicht</b> als zwei Zeichen geschrieben. Bsp.: ß wird <b>nicht</b> zu ss, sondern bleibt ß.");

									if ($allow == "today") {
										$tag = $task["day"];
										$bis = $tag;

										$wochentag = date("w", strtotime("$tag.12.2019"));

										switch ($wochentag) {
											case 0:
												$bis += 2;
												break;
											case 1:
												$bis += 1;
												break;
											case 2:
												$bis += 1;
												break;
											case 3:
												$bis += 1;
												break;
											case 4:
												$bis += 1;
												break;
											case 5:
												$bis += 3;
												break;
											case 6:
												$bis += 3;
												break;
										}


										$tagname = $wochentage[date("w", strtotime($bis . ".12.2019"))];
										alert("warning", "Bis <b>$tagname, den $bis.12.2019 um 23:59 Uhr</b> kannst du eine Lösung abgeben und ändern.");
										echo "<br><br>";
										echo '<button name="submit" type="submit" class="btn btn-success">' . $buttonname . '</button>';
									} else {

										echo '<button class="btn btn-success disabled">Zu spät...</button>';
									}
									echo '</form>';
								}
							}
							echo "<br>";


							if ($dontEchoChristmasForm) {
								echo "<a class='btn btn-primary' href='./aufgaben.php#tab1'>Zurück zu den Aufgaben</a>";
							} else {
								echo "<a class='btn btn-primary' href='./aufgaben.php#tab1'>Abbrechen und zurück zu den Aufgaben</a>";
							}
						} else {
							alert("danger", "<b>Zu früh!</b><br>Diese Aufgabe ist noch nicht freigeschaltet. Komme doch ein anderes mal wieder!");
						}
					}
				}

				?>



		</div>


		<?php getFooter(); ?>

		<script>
			$(document).ready(function() {
				$(".inputChar").on("input", function() {
					$('input[type=text]').val(function() {
						var n = [""];
						var s = $(this).val();
						if (s.length > 1) {
							s = s[0];
						}
						if (s == "ß") {
							n = s;
						} else {
							n = s.toUpperCase();
						}
						return n;
					});
					if ($(this).val()) {
						$(this).next('.inputChar').select();
					}
				});
				$(".inputChar").on('keydown', function(event) {
					var key = event.keyCode || event.charCode;
					if (key == 8 || key == 46)
						if (!$(this).val()) {
							$(this).prev('.inputChar').select();
						}
				});
			});
		</script>
	</div>
</body>
</html>