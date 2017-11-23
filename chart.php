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
	
	<?php
	
	$sql2 = "SELECT * FROM tbl_weight_data WHERE tbl_user_ID = '$users_id' ORDER BY time DESC;";
	$alldata = mysqli_query($link, $sql2);
	
	while($row = mysqli_fetch_array($alldata)) {
		
		
		
	}
	
	?>
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
			<?php
			
			if($language == "de"){
				echo "['Datum',  'Gewicht'],";
			} else {
				echo "['Date',  'Weight'],";
			}
	
			$sql2 = "SELECT * FROM tbl_weight_data WHERE tbl_user_ID = '$users_id' ORDER BY time ASC;";
			$alldata = mysqli_query($link, $sql2);
			
			while($row = mysqli_fetch_array($alldata)) {
				
				$date = DateTime::createFromFormat('Y-m-d H:i:s', $row['time']);
				$output = $date->format('d.m.Y');
				
				echo "    ['".$output."', ".$row['weight']."],    ";
				
			}
			

			
			?>

        ]);

        var options = {
          title: '<?php if($language == "de"){ echo 'Gewichtsverlust Diagramm'; } else { echo 'Weight Loss Chart'; } ?>',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

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
					echo '<h2>Diagramme</h2>';
				} else {
					echo '<h2>Charts</h2>';
				}
				
				?>
                <div id="curve_chart" style="width: 900px; height: 500px"></div>
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
