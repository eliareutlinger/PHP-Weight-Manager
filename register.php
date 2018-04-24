<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

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

    <title>Registrieren</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
		<script src='https://www.google.com/recaptcha/api.js'></script>

</head>

	<?php
		session_start();
		session_regenerate_id();

		if (empty($_SESSION['login'])) {

		} else {
			header('Location: http://' . $_SERVER['HTTP_HOST'] . '/index.php');
		}
	?>

<?php
	$message = array();
	if (!empty($_POST)) {
		if(isset($_POST['g-recaptcha-response'])){
           $captcha=$_POST['g-recaptcha-response'];
    }
		if (empty($_POST['f']['username']) || empty($_POST['f']['password']) || empty($_POST['f']['password_again'])
		) {

			$message['error'] = 'Some fields are empty';

		} else if ($_POST['f']['password'] != $_POST['f']['password_again']) {

			$message['error'] = "Passwords don't match";

		} else if (strstr($_POST['f']['username'], " ")){

			$message['error'] = 'Username should not contain spaces.';

		} else {

			unset($_POST['f']['password_again']);
			$salt = '';
			for ($i = 0; $i < 22; $i++) {
				$salt .= substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1);
			}

			$_POST['f']['password'] = crypt(
				$_POST['f']['password'],
				'$2a$10$' . $salt
			);

			include("database/connect.php");
			if ($mysqli->connect_error) {
				$message['error'] = 'Database Error: ' . $mysqli->connect_error;
			}

			$query = sprintf(
				"INSERT INTO tbl_user (username, password)
				SELECT * FROM (SELECT '%s', '%s') as new_user
				WHERE NOT EXISTS (
					SELECT username FROM tbl_user WHERE username = '%s'
				) LIMIT 1;",
				$mysqli->real_escape_string($_POST['f']['username']),
				$mysqli->real_escape_string($_POST['f']['password']),
				$mysqli->real_escape_string($_POST['f']['username'])
			);

			$mysqli->query($query);

			if ($mysqli->affected_rows == 1) {
				$message['success'] = 'New User (' . htmlspecialchars($_POST['f']['username']) . ') created, <a href="login.php">Login</a>.';
			} else {

				$message['error'] = 'Username already in use.';

			}

			$mysqli->close();
		}
	} else {
		$message['notice'] = 'Fill out the Form below to create a new Account or <a href="login.php">Login</a>.';
	}
?>



<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <div class="container" style="margin-top: 30px; margin-bottom: 100px;">
        <div class="row"  style="margin-left: 2%; margin-right: 2%;">
					<img class="img-responsive center-block" src="images/headersmall.png"/>
            <?php if (isset($message['error'])): ?>
                    <fieldset style="color: red;" class="error"><legend>Error</legend><?php echo $message['error'] ?></fieldset>
            <?php endif;
                if (isset($message['success'])): ?>
                        <fieldset style="color: green;" class="success"><legend>Success</legend><?php echo $message['success'] ?></fieldset>
            <?php endif;
                if (isset($message['notice'])): ?>
                        <fieldset class="notice"><legend>Register</legend><?php echo $message['notice'] ?></fieldset>
            <?php endif; ?>
            <br/>

            <form role="form" action="./register.php" method="post">
								<div class="col-lg-3"></div>
                <div class="col-lg-6" id="formular">
                    <div class="well well-sm"><strong><span class="glyphicon glyphicon-asterisk"></span>Needed Fields</strong></div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="f[username]" id="username" placeholder="Create Username" required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="f[password]" placeholder="Create Password" required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password-again">Repeat Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_again" name="f[password_again]" placeholder="Repeat Password" required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                        </div>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Register!" class="btn btn-info pull-right">
                </div>
								<div class="col-lg-3"></div>
            </form>

        </div>
    </div>
</body>

</html>
