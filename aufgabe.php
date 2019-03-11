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

<body>

	<div class="container">
		<?php getNav("aufgabe"); ?>
		
		<div class="jumbotron">
			<?php

			//var_dump($_POST);
			//die();
			if ( ( isset( $_POST[ "submit" ] ) && isset( $_POST[ "dayid" ] ) ) || ( isset( $_POST[ "solution" ] ) && isset( $_POST[ "dayid" ] ) ) ) {


				$db = connect();

				$userid = $db->real_escape_string( $_SESSION[ "userid" ] );
				$day = $db->real_escape_string( $_POST[ "dayid" ] );

				$res = $db->query( "SELECT * FROM tipps WHERE userid=$userid AND day=$day" );

				if ( $res ) {
					$res = $res->fetch_all( MYSQLI_ASSOC );
					//var_dump($res);
					if ( $res ) {
						$res = $res[ 0 ];
						if ( $res ) {
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
				$res = $db->query( "SELECT * FROM days WHERE day=$day" );
				echo $db->error;
				//echo $db->error;
				if ( $res ) {
					$res = $res->fetch_all( MYSQLI_ASSOC );
					//var_dump($res);
					if ( $res ) {
						$res = $res[ 0 ];
						if ( $res ) {
							$task = $res;
						} else {
							alert( "danger", "Aufgabe nicht gefunden!" );
							die();
						}
					} else {
						alert( "danger", "Aufgabe nicht gefunden!" );
						die();
					}
				} else {
					alert( "danger", "Aufgabe nicht gefunden!" );
					die();
				}
				$allow = checkForDate( $day );
				//echo "///////////////////////////////////////////////////////////////////////////";
				//var_dump($existing);
				$word = "";
				if ( $task[ "day" ] == WEIHNACHTSTAG ) {
					$word = $_POST[ "solution" ];
				} else {
					foreach ( $_POST as $key => $entry ) {
						if ( substr( $key, 0, 4 ) === "char" ) {
							$word .= $entry;
						}
						/* else {
												//echo "Übersprungen $entry<br>";
											}*/
					}



				}
				//echo $word;
				$userid = $db->real_escape_string( $userid );
				$word = $db->real_escape_string( $word );
				$day = $db->real_escape_string( $day );

				if ( $allow == "today" ) {
					if ( $existing ) {
						$db->query( "UPDATE tipps SET tipp='$word' WHERE userid=$userid AND day=$day" );
					} else {
						$db->query( "INSERT INTO tipps (userid, day, tipp) VALUES ('$userid', '$day', '$word')" );
					}
				} else {
					if ( $allow == "past" ) {
						alert( "warning", "Du bist leider zu spät dran. Beeile dich beim nächsten mal!" );
					} else {
						alert( "warning", "Du bist leider zu früh dran. Warte nich ein bisschen!" );
					}

					die();
				}
				//echo $word;
				if ( !$db->error ) {
					echo '<meta http-equiv="refresh" content="0; url=aufgaben.php?s">';
					die();
					alert( "success", "Deine Lösung wurde erfolgreich gespeichert!" );
				} else {
					alert( "danger", "Ein Datenbankfehler. Bitte wende dich an Hannes Rüger, Klasse 9a." );
					echo $db->error;
				}

				die();



			} else if ( !isset( $_GET[ "a" ] ) ) {
				//var_dump($_POST);
				//die();
				echo '<meta http-equiv="refresh" content="0; url=aufgaben.php">';
				die();
			}

			$db = connect();
			$id = $db->real_escape_string( $_GET[ "a" ] );
			$uid = $db->real_escape_string( $_SESSION[ "userid" ] );

			$res = $db->query( "SELECT * FROM tipps WHERE userid=$uid AND day=$id" );
			if ( $res ) {
				$tipp = false;
				$res = $res->fetch_all( MYSQLI_ASSOC );
				if ( !$res ) {
					$tipp = false;
				} else {
					$tipp = $res;
				}
			} else {
				$tipp = false;
			}




			$res = $db->query( "SELECT * FROM `days` WHERE day=$id" );
			if ( !$res ) {
				alert( "danger", "Es wurde keine Aufgabe gefunden!" );
			} else {
				$res = $res->fetch_all( MYSQLI_ASSOC );
				if ( !$res ) {
					alert( "danger", "Es wurde keine Aufgabe gefunden!" );
				} else {
					$task = $res[ 0 ];
					$allow = checkForDate( $task[ "day" ] );
					//echo $allow;
					if ( $allow == "today"
						OR $allow == "past" ) {

						$wochentage = array( "Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag" );
						//var_dump( $allow );
						$nummer = ( intval( $task[ "day" ] ) < 10 ? '0' . $task[ "day" ] : $task[ "day" ] );
						setlocale( LC_TIME, "de_DE.utf8" );
						$tag = $nummer . ". Dezember 2018";
						$tag = $wochentage[ date( "w", strtotime( $nummer . ".12.2018" ) ) ] . ", " . $tag;
						//$id = $match["id"];
						echo "<h4>$tag</h4><br>";
						$dontEchoChristmasForm = false;
						if ( $allow == "today" ) {
							if ( $task[ "day" ] != WEIHNACHTSTAG ) {
								//echo "<img class='img img-responsive taskimg' src='./images/getImage.php?d=$nummer&m=a'/>";
								echo "<img class='img img-responsive taskimg' src='./images/getImage.php?d=$nummer&m=a'/>";
									
							}
									

						} else {
							if ( $task[ "day" ] != WEIHNACHTSTAG ) {
								echo "<h4 class='solution'>LÖSUNG</h4><img class='img img-responsive taskimg' src='./images/getImage.php?d=$nummer&m=l'/>";
								//echo "<h4 class='solution'>LÖSUNG</h4><img class='img img-responsive taskimg' src='./images/getImage.php?d=$nummer&m=l'/>";
							} else {
								$dontEchoChristmasForm = true;


								$points = getUserPoints();

								echo "<br><h2>Herzlichen Glückwunsch!<br>";
								alert( "success", "<p class='lead'>Du hast $points von 480 möglichen Punkten erhalten.</p>" );
								echo "</h2><strong>Du kennst dich gut im Allgäu-Gymnasium aus.<br>Du wirst benachrichtigt, wenn du einen Preis gewonnen hast.</strong><br><br>";
								echo "<h4 class='solution'>LÖSUNG</h4>";
								echo "<p class='lead'>" . $task[ "word" ] . "</p><br><br><br>";
							}

						}
						if ( !$dontEchoChristmasForm ) {
							
							echo "<br><br><p class='lead'>" . $task[ "text" ] . "</p>";
							//var_dump($tipp);

							$solution = preg_split( '/(?!^)(?=.)/u', $task[ "word" ] );
							if ( empty( $tipp ) ) {
								$buttonname = "Lösung abgeben";

								$tipp = false;

							} else {
								$buttonname = "Lösung ändern";
								$tipp = preg_split( '/(?!^)(?=.)/u', $tipp[ 0 ][ "tipp" ] );

							}
							//var_dump( $solution );
							if ( $task[ "day" ] != WEIHNACHTSTAG ) {
								echo '<form class="form-inline" method="post">
							<input type="hidden" id="dayid" name="dayid" value="' . $_GET[ "a" ] . '">
							<div class="form-group">';
							}



							if ( $task[ "day" ] == WEIHNACHTSTAG ) {
								///////////////////////////////   Weihnachten      //////////////////////////////////
								?>
			<link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/css/base/jquery.ui.all.css" rel="stylesheet">
			<link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.2/css/lightness/jquery-ui-1.10.2.custom.min.css" rel="stylesheet">
			<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js"></script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-simulate/1.0.1/jquery.simulate.min.js"></script>
			<?php 
								echo '<form action="./aufgabe.php" id="submitSolution" class="form-inline" method="post">
							<input type="hidden" id="dayid" name="dayid" value="' . $_GET[ "a" ] . '"><input type="hidden" id="solution" name="solution" value=""></form>';
								$counter = "A";
								//$res = $db->query("SELECT word, letter FROM days")->fetch_all(MYSQLI_ASSOC);
								//foreach ($res as $day) {
								$word = preg_split( '/(?!^)(?=.)/u', $task[ "word" ] );
								//var_dump($word);
								shuffle($word);
								//var_dump($word);
								if (($key = array_search('A', $word)) !== false) {
									unset($word[$key]);
								}
								if (($key = array_search('N', $word)) !== false) {
									unset($word[$key]);
								}
								
								foreach ($word as $letter) {
									//var_dump($day);
									if ($letter != " ") {
										//echo $day[ "word" ]."<br><br><br>";
										//$letter = ;
										echo '<div class="draggable dndletter" data-id='.$counter.' data-letter="'.$letter.'"><p>'.$letter.'</p></div>';
										$counter++;
									}

								}


							?>
			<br>
			<br>
			<br>
			<br>

			<?php 
								$letters = preg_split( '/(?!^)(?=.)/u', $task[ "word" ]);
								$counter = 0;
								foreach ($letters as $letter) {
									//var_dump($day);
									if ($letter == " ") {
										echo '<div class="dndspace" data-letterSubmit=" ">&nbsp</div>';
									} else {
										if ($counter == 8) {
											echo '<div class="dndprefilled" id="8" data-containsid="-1" data-letterSubmit="N"><p>N</p></div>';
										} else if ($counter == 27) {
											echo '<div class="dndprefilled" id="27" data-containsid="-1" data-letterSubmit="A"><p>A</p></div>';
										} else {
											echo '<div class="droppable dndfree" id="box'.$counter.'" data-containsid="" data-letterSubmit=""><p>&nbsp</p></div>';
										}


									}
									$counter++;
								}


							?>


			<script>
				$( function () {
					$( ".draggable" ).draggable( {
						snap: ".droppable",

						drag: function ( event, ui ) {
							//var el = document.elementFromPoint(ui.originalPosition.top, ui.originalPosition.left);
							//console.log(el);
							//$(el).removeClass("full");

						}
					} );
					$( ".droppable" ).droppable( {
						drop: function ( event, ui ) {
							//console.log(event);
							//console.log(ui);
							//var draggableId = ;
							//var droppableId = $(this).attr("id");
							//ui.draggable.removeClass("draggable");
							//ui.draggable.detach().appendTo($(this));
							//ui.draggable.css("position", "absolute");
							//ui.draggable.css("top", $(this).offset().top);
							//ui.draggable.css("left", $(this).offset().left);
							var id = ui.draggable.data( "id" );
							//console.log("*[data-containsid='"+id+"']");
							//console.log($("*[data-containsid='"+id+"']"));
							//console.log($("*[data-test]"));
							//$("[data-containsid='"+id+"']").removeClass("full").data("containsid", "");
							$( "[data-containsid]" ).each( function () {
								if ( $( this ).data( "containsid" ) == id ) {
									$( this ).removeClass( "full" );
									$( this ).data( "containsid", "" );
									$( this ).data( "letterSubmit", "" );
								}
							} )
							$( this ).data( "letterSubmit", ui.draggable.data( "letter" ) );
							$( this ).data( "containsid", id );
							$( this ).addClass( "full" );
						}

					} );
					$( "#submitBtn" ).on( "click", function () {
						var text = "";
						$( "[data-letterSubmit]" ).each( function () {
							//console.log("iterating through "+$(this));
							if ( typeof $( this ).data( "letterSubmit" ) !== "undefined" || $( this ).data( "letterSubmit" ) == "" ) {
								text += $( this ).data( "letterSubmit" );
							} else {
								//console.log("hat kein letterSubmit");
								if ( $( this ).hasClass( "dndprefilled" ) ) {
									//console.log("hasClass prefilled!");
									if ( $( this ).attr( "id" ) == "8" ) {
										text += "N";
									} else if ( $( this ).attr( "id" ) == "27" ) {
										text += "A";
									} else {

									}
								} else {
									//console.log("doesnt have class prefilled!");
									text += " ";
								}

							}

						} );
						//alert("go..");
						$( "#solution" ).val( text );
						$( "#submitSolution" ).submit();
					} );

					var draggable = null;
					var droppable = null;
					var droppableOffset = null;
					var draggableOffset = null;
					var dx = null;
					var dy = null;




					setTimeout( function () {
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

							//draggable = $(".draggable:contains(\''.$letter.'\'):eq('.$lettercounter.')");


							
							draggable = null;
							var counter = 1;
							$(".draggable:contains(\''.$letter.'\')").each(function() {
								if (counter == '.$lettercounter.') {
									draggable = $(this);
								}
								counter++;
							});


							if (draggable) {
								//console.log(draggable);
								droppable = $("#box'.$counter.'");
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
					}, 10 );


				} );
			</script>
			<?php
			//var_dump($usedletters);	
			alert("info", "Sortiere dieBuchstaben mithilfe von Drag and Drop in die freien Kästchen, sodass ein Lösungssatz herauskommt. Zwei Buchstaben sind bereits vorgegeben.");
			alert("warning", "Bis Mittwoch, den 26.12.2018 um 23:59 Uhr kannst du eine Lösung abgeben und ändern.");
								
			echo '<br><br><br>';
			if ( $allow == "today" ) {
				echo '<button id="submitBtn" name="submit" type="submit" class="btn btn-success">' . $buttonname . '</button>';
			} else {
				echo '<button class="btn btn-success disabled">Zu spät...</button>';
			}
			echo "<br>";
			echo "<br>";
			}
			else {
				///////////////////////////////    normale Aufgabe //////////////////////////////////
				$counter = 1;
				if ( $allow != "today" ) {
					echo "<br><h4 class='solutionHint'>Lösung: ".implode("", $solution)."</h4><br><b>Dein Tipp:</b><br>";
				}
				foreach ( $solution as $key => $unused ) {
					//if ( $allow == "today" ) {
						if ( !empty( $tipp ) && isset($tipp[ $key ])) {
							$val = $tipp[ $key ];
						} else {
							$val = "";
						}
					/// Nicht mehr die Lösung, sondern den Tipp anzeigen
					//} else {
					//	$val = $solution[ $key ];
					//}
					
					if ( $task[ "letter" ] == $counter ) {
						echo "<input class='form-control inputChar important' maxlength='1' size='1' type='text' value='$val' name='char" . $counter . "'>";
					} else {
						echo "<input class='form-control inputChar' maxlength='1' size='1' type='text' value='$val' name='char" . $counter . "'>";
					}

					$counter++;
				}
				if ($allow == "past") {
					$alternatives = implode(",<br>", explode("-", $task["alternatives"]));
					if ($alternatives) {
						$alternatives = "<br><br>Auch akzeptiert wird/werden: <br>".$alternatives;
					}
					echo "<br><h4 class='solutionHint'>".$task["solutionHint"]."$alternatives</h4><br>";
				}
				
				echo '</div><br><br>';
				alert( "info", "Alle Umlaute, Sonderzeichen, etc werden <b>nicht</b> als zwei Zeichen geschrieben. Bsp.: ß wird <b>nicht</b> zu ss, sondern bleibt ß." );
				
				if ( $allow == "today" ) {
					$tag = $task["day"];
					$bis = $tag;
										
					$wochentag = date("w", strtotime("$tag.12.2018"));
					
					switch ($wochentag) {
						case 0: // Sonntag
							$bis += 2;
							break;
						case 1: // Montag
							$bis += 1;
							break;
						case 2: // Dienstag
							$bis += 1;
							break;
						case 3: // Mittwoch
							$bis += 1;
							break;
						case 4: // Donnerstag
							$bis += 1;
							break;
						case 5: // Freitag
							$bis += 3;
							break;
						case 6: // Samstag
							$bis += 3;
							break;
					}
					
					
					$tagname = $wochentage[ date( "w", strtotime( $bis . ".12.2018" ) ) ];
					alert("warning", "Bis <b>$tagname, den $bis.12.2018 um 23:59 Uhr</b> kannst du eine Lösung abgeben und ändern.");echo "<br><br>";
					echo '<button name="submit" type="submit" class="btn btn-success">' . $buttonname . '</button>';
				} else {
					
					echo '<button class="btn btn-success disabled">Zu spät...</button>';
				}
				echo '</form>';
			}
			}
						echo "<br>";


			if ( $dontEchoChristmasForm ) {
				echo "<a class='btn btn-primary' href='./aufgaben.php#tab1'>Zurück zu den Aufgaben</a>";
			} else {
				echo "<a class='btn btn-primary' href='./aufgaben.php#tab1'>Abbrechen und zurück zu den Aufgaben</a>";
			}

			} else {
				alert( "danger", "<b>Zu früh!</b><br>Diese Aufgabe ist noch nicht freigeschaltet. Komme doch ein anderes mal wieder!" );
			}

			}
			}

			?>



		</div>


		<?php getFooter(); ?>

		<script>
			$( document ).ready( function () {
				$( ".inputChar" ).on( "input", function () {
					if ( $( this ).val() ) {
						$( this ).next( '.inputChar' ).select();
					}
					$( 'input[type=text]' ).val( function () {
						var n = [ "" ];
						var s = this.value;
						for ( var i = 0; i < s.length; i++ ) {
							if (s[i] == "ß") {
								n[i] = s[i];
							} else {
								n[i] = s[i].toUpperCase();
							}

						}
						return n.join( "" );
					} );
				} );
				$( ".inputChar" ).on( 'keydown', function ( event ) {
					var key = event.keyCode || event.charCode;
					if ( key == 8 || key == 46 )
						if ( !$( this ).val() ) {
							$( this ).prev( '.inputChar' ).select();
						}
				} );


			} );
			
			
			
		</script>
	</div>
	<!-- /container -->


	<!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
	<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>