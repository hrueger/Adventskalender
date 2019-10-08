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
			<?php getFooter(); ?>

		</div> <!-- /container -->


	</body>

	</html>