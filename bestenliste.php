<?php 
	require_once("./include/lib.inc.php"); 
	require_once("./include/db.inc.php"); 
	
	//updatePoints();
	
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
     <?php getNav("bestenliste"); ?>

      <div class="jumbotron">
        <h1>Bestenliste</h1>
		<br>
			<ul class="menu nav nav-tabs">
				<li><a href="#tab1">Alle Schüler</a></li>
				<li><a href="#tab2">Alle Lehrer</a></li>
				<li><a href="#tab3">Alle Klassen (durchschnittlich)</a></li>
				<li><a href="#tab4">Bestenliste (alle Teilnehmer)</a></li>
			</ul>
		  	
		  	<h5><b><?php echo "Stand: ".strftime("%A").", ".date('d.m.o \u\m H:i:s')." Uhr"; ?></b></h5>
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

    </div> <!-- /container -->


    <!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
