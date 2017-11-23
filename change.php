<!DOCTYPE html>
<html lang="en">

<?php
 
	session_start();
	session_regenerate_id();
 
	if (empty($_SESSION['login'])) {
		header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
	} else {
		$login_status = '
				<p style="color: white;">Sie sind als <strong>' . htmlspecialchars($_SESSION['user']['username']) . '</strong> angemeldet. <a href="../logout.php">Abmelden</a></p>
		';
		$name = htmlspecialchars($_SESSION['user']['username']);
		$users_id = htmlspecialchars($_SESSION['user']['userid']);
		
		date_default_timezone_set('Europe/Berlin');
		$link = mysqli_connect("localhost", "root", "", "eliareut_weight") or die (mysqli_error ());
		
		$userlangselect = mysqli_fetch_array(mysqli_query($link, "SELECT user_lang FROM tbl_user WHERE id = '$users_id';"));
		$language = $userlangselect['user_lang'];
		
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
	
	}
?>

<head>
    
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

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Weight Manager</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>
    <link href="css/ownStyle.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Weight Manager v1.0</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
					<li>
                        <a href="index.php"><?php if($language == "de"){echo "Startseite";} else {echo "Home";} ?></a>
                    </li>
                    <li>
                        <a href="change.php"><?php if($language == "de"){echo "Einstellungen";} else {echo "Settings";} ?></a>
                    </li>
					<li>
						<a href="chart.php"><?php if($language == "de"){echo "Diagramme";} else {echo "Charts";} ?></a>
					</li>
					<li>
						<a href="logout.php"><?php if($language == "de"){echo "Ausloggen";} else {echo "Logout";} ?></a>
					</li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Formulare zur änderung der Preferences -->
        <div class="row">
            <div class="col-lg-12">
                
                <?php
				
				if($language == "de"){
					echo '<h2>Deine Einstellungen</h2>';
				} else {
					echo '<h2>Your Settings</h2>';
				}
							   
                
                $datas = mysqli_query($link, "SELECT * FROM tbl_user WHERE id=$users_id;");
				
				if (isset($_POST["submit_eingabemaske"]))	// Submit-Schaltfläche der Eingabemaske wurde betätigt
				{
					
					// SQL-Kommando: Ändern von Einträgen
					$sql="
					UPDATE tbl_user SET 
					name='$_POST[name]', 
					goal_weight='$_POST[PreferedWeight]', 
					goal_date='$_POST[PreferedDate]',
					height ='$_POST[height]',
					age ='$_POST[FormAge]',
					user_lang = '$_POST[newLang]'
					WHERE ID='$users_id';";
					// SQL-Kommando ausführen
						if(mysqli_query($link, $sql) == TRUE){
							redirect("change.php?state=success");
							header('Location: change.php?state=success');
							if($language == "de"){
								echo("<br/><br/><div style='text-align: center;' class='alert alert-success'><strong><span class='glyphicon glyphicon-floppy-saved'></span> Änderungen gespeichert!</strong></div>");
							} else {
								echo("<br/><br/><div style='text-align: center;' class='alert alert-success'><strong><span class='glyphicon glyphicon-floppy-saved'></span> Changes saved!</strong></div>");
							}
							sleep(1);
						} else {
							echo ("Fehler im SQL-Kommando: $sql");
						}
				}
				if (isset($_GET['state']) && $_GET['state'] == 'success'){
                    if($language == "de"){
						echo("<br/><br/><div style='text-align: center;' class='alert alert-success'><strong><span class='glyphicon glyphicon-floppy-saved'></span> Änderungen gespeichert!</strong></div>");
					} else {
						echo("<br/><br/><div style='text-align: center;' class='alert alert-success'><strong><span class='glyphicon glyphicon-floppy-saved'></span> Changes saved!</strong></div>");
					}
             }
				
				
                while ($row = mysqli_fetch_array($datas)){
					
					if($row['goal_date'] != NULL){
						$date = DateTime::createFromFormat('Y-m-d', $row['goal_date']);
						$output = $date->format('Y-m-d');
					} else {
						$output = "";
					}
					
									
					if(isset($row['current_weight']) && isset($row['height']) && $row['height'] != 0){
						$bmi = round($row['current_weight'] / (($row['height'] / "100") * ($row['height'] /"100")), 2);
					} else {
						$bmi = 0;
					}
									
					if($language == "de"){
						echo '<form method="post" action="change.php">';
						echo '<table class="table">';
						echo '<tr><td>Name: </td><td><input type="text" class="form-control" id="name" name="name" value="'.$row['name'].'"/></td></tr>';
						echo '<tr><td>Benutzername: </td><td><input type="text" class="form-control" id="username" name="username" value="'.$row['username'].'" disabled/></td></tr>';
						echo '<tr><td>Ziel-Gewicht: </td><td><input type="number" step="0.01" class="form-control" id="PreferedWeight" name="PreferedWeight" value="'.$row['goal_weight'].'"/></td></tr>';
						echo '<tr><td>Ziel-Datum: </td><td><input type="date" class="form-control" id="PreferedDate" name="PreferedDate" value="'.$output.'"/></td></tr>';
						echo '<tr><td>Aktuelles Gewicht: </td><td><input type="text" class="form-control" id="CurrentWeight" name="CurrentWeight" value="'.$row['current_weight'].'" disabled/></td></tr>';
						echo '<tr><td>Grösse: </td><td><input type="text" class="form-control" id="height" name="height" value="'.$row['height'].'" /></td></tr>';
						echo '<tr><td>BMI: </td><td><input type="number" step="0.1" class="form-control" id="thebmi" name="thebmi" value="'.$bmi.'" disabled/></td></tr>';
						echo '<tr><td>Alter: </td><td><input type="number" class="form-control" id="FormAge" name="FormAge" value="'.$row['age'].'"/></td></tr>';
						echo '
						<tr>
							<td for="sel1">Sprache:</td>
							<td>
							<select name="newLang" id="newLang" class="form-control" id="sel1">
								<option value="en">Englisch</option>
								<option value="de" selected>Deutsch</option>
							</select>
							</td>
						</tr>
						';
						echo '<tr><td></td><td><input class="btn btn-primary" type="submit" name="submit_eingabemaske" value="Speichern"></td></tr>';
						echo '</table>';
						echo '</form>';
					} else {
						echo '<form method="post" action="change.php">';
						echo '<table class="table">';
						echo '<tr><td>Name: </td><td><input type="text" class="form-control" id="name" name="name" value="'.$row['name'].'"/></td></tr>';
						echo '<tr><td>Username: </td><td><input type="text" class="form-control" id="username" name="username" value="'.$row['username'].'" disabled/></td></tr>';
						echo '<tr><td>Weight Goal: </td><td><input type="number" step="0.01" class="form-control" id="PreferedWeight" name="PreferedWeight" value="'.$row['goal_weight'].'"/></td></tr>';
						echo '<tr><td>Date Goal: </td><td><input type="date" class="form-control" id="PreferedDate" name="PreferedDate" value="'.$output.'"/></td></tr>';
						echo '<tr><td>Current Weight: </td><td><input type="text" class="form-control" id="CurrentWeight" name="CurrentWeight" value="'.$row['current_weight'].'" disabled/></td></tr>';
						echo '<tr><td>Height: </td><td><input type="text" class="form-control" id="height" name="height" value="'.$row['height'].'" /></td></tr>';
						echo '<tr><td>BMI: </td><td><input type="number" step="0.1" class="form-control" id="thebmi" name="thebmi" value="'.$bmi.'" disabled/></td></tr>';
						echo '<tr><td>Age: </td><td><input type="number" class="form-control" id="FormAge" name="FormAge" value="'.$row['age'].'"/></td></tr>';
						echo '
						<tr>
							<td for="sel1">Language:</td>
							<td>
							<select name="newLang" id="newLang" class="form-control" id="sel1">
								<option value="en" selected>English</option>
								<option value="de">German</option>
							</select>
							</td>
						</tr>
						';
						echo '<tr><td></td><td><input class="btn btn-primary" type="submit" name="submit_eingabemaske" value="Save"></td></tr>';
						echo '</table>';
						echo '</form>';
					}
									
					
                }
                
                
                
                ?>
            </div>
        </div>
        <!-- /.row -->
 

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
