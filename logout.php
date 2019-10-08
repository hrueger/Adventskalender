<?php 
	require_once("./include/lib.inc.php"); 
	require_once("./include/db.inc.php"); 
	require_once("./include/login.inc.php"); 

	if (isset($_POST["logout"])) {
				
				//if (ini_get("session.use_cookies")) {
				//	$params = session_get_cookie_params();
				//	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
				//}

				// Zum Schluß, löschen der Session.
				//session_destroy();
				$_SESSION["loggedin"] = false;
				$_SESSION["userid"] = null;
				
				header("Location: index.php");
				
			} else if (isset($_POST["stay"])) {
				
				header("Location: index.php");
				
			} else {
			$message = false;
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <?php getHead(); ?>
  </head>

  <body>

    <div class="container">
     <?php getNav("logout"); ?>

      <div class="jumbotron text-center">
        <h1>Der AGventskalender</h1> 
		<br>
		<?php
			
			
			
			
			
			
			
			
			
			//echo "<pre>";
			//var_dump($_REQUEST);
			//echo "</pre>";
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


  </body>
</html>
