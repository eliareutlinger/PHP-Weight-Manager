<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<link rel="apple-touch-icon" sizes="57x57" href="/allicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/allicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/allicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/allicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/allicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/allicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/allicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/allicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/allicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/allicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/allicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/allicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/allicons/favicon-16x16.png">
    <link rel="manifest" href="/allicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/allicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="css/ownStyle.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

	<?php

		function redirect($url){
			if (!headers_sent()){
				header('Location: '.$url);
				exit;
			} else {
				echo '<script type="text/javascript">';
				echo 'window.location.href="'.$url.'";';
				echo '</script>';
				echo '<noscript>';
				echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
				echo '</noscript>'; exit;
			}
		}

		ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
        ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);

		session_start();
		session_regenerate_id();

		if (empty($_SESSION['login'])) {

		} else {
			redirect("index.php");
		}
	?>

  <body>

    <?php
if (isset($_SESSION['login'])) {
	redirect("index.php");
} else {
	if (!empty($_POST)) {
		if (
			empty($_POST['f']['username']) ||
			empty($_POST['f']['password'])
		) {
			$message['error'] = 'Es wurden nicht alle Felder ausgefüllt.';
		} else {
			include("database/connect.php");
			if ($mysqli->connect_error) {
				$message['error'] = 'Datenbankverbindung fehlgeschlagen: ' . $mysqli->connect_error;
			} else {
				$query = sprintf(
					"SELECT username, password, ID FROM tbl_user WHERE username = '%s'",
					$mysqli->real_escape_string($_POST['f']['username'])
				);
				$result = $mysqli->query($query);
				if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					if (crypt($_POST['f']['password'], $row['password']) == $row['password']) {

						ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
						ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);

						session_start();

						$_SESSION = array(
							'login' => true,
							'user'  => array(
								'username'  => $row['username'],
								'userid' => $row['ID']

							)
						);
						$message['success'] = 'Anmeldung erfolgreich.';
						redirect("index.php");
					} else {
						$message['error'] = 'Das Kennwort ist nicht korrekt.';
					}
				} else {
					$message['error'] = 'Der Benutzer wurde nicht gefunden.';
				}
				$mysqli->close();
			}
		}
	} else {
		$message['notice'] = '';
	}
}
?>

    <div class="container">
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<br/>
				<img class="img-responsive center-block" src="images/headersmall.png"/>

      <form class="form-signin" action="./login.php" method="post">


        <?php if (isset($message['error'])): ?>
              <fieldset style="color: red;" class="error"><legend>Fehler</legend><?php echo $message['error'] ?></fieldset>
        <?php endif;
          if (isset($message['success'])): ?>
              <fieldset style="color: green;" class="success"><legend>Erfolg</legend><?php echo $message['success'] ?></fieldset>
        <?php endif;
          if (isset($message['notice'])): ?>
              <fieldset class="notice"><h2 class="form-signin-heading">Login</h2><?php echo $message['notice'] ?></fieldset>
        <?php endif; ?>
        <br/>
        <label for="inputEmail" class="sr-only">Benutzername</label>
        <input type="text" id="username" name="f[username]" class="form-control" placeholder="Benutzername" required autofocus><br/>
        <label for="inputPassword" class="sr-only">Passwort</label>
        <input type="password" id="password" name="f[password]" class="form-control" placeholder="Passwort" required><br/>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      </form>
			<p><br/>Need an Account? <a href="register.php">Register</a></p>
		</div>
		<div class="col-lg-4"></div>
    </div> <!-- /container -->


  </body>
</html>
