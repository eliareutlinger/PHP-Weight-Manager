<!DOCTYPE html>
<html lang="en">

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

	session_start();
	session_regenerate_id();

	if (empty($_SESSION['login'])) {
		redirect("login.php");
	} else {

    $name = htmlspecialchars($_SESSION['user']['username']);
    $users_id = htmlspecialchars($_SESSION['user']['userid']);

	date_default_timezone_set('Europe/Berlin');
	include("database/connect.php");

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

        <!-- Formular zur Übermittlung neuer Werte -->
        <div class="row">
            <div class="col-lg-12">

                <form role="form" action="index.php" method="post" >
                        <?php

                            $rowGoal = mysqli_fetch_array(mysqli_query($link, "SELECT goal_weight, current_weight FROM tbl_user WHERE id = '$users_id';"));
                            $differencetogoal = $rowGoal['current_weight'] - $rowGoal['goal_weight'];
                            $goal_weightForm = $rowGoal['goal_weight'];


                            if (isset($_GET['state']) && $_GET['state'] == 'success'){
								if($language == "de"){
									echo '<div class="alert alert-success" style="text-align: center;"><strong><span class="glyphicon glyphicon-ok"></span> Eintrag gespeichert! <a href="index.php">Weitere hinzufügen</a></strong></div>';
								} else {
									echo '<div class="alert alert-success" style="text-align: center;"><strong><span class="glyphicon glyphicon-ok"></span> Success! Weight Added. <a href="index.php">Add another one</a></strong></div>';
								}
                            } else if (isset($_GET['state']) && $_GET['state'] == 'successdeleted'){
								if($language == "de"){
									echo '<div class="alert alert-info" style="text-align: center;"><strong><span class="glyphicon glyphicon-trash"></span> Eintrag gelöscht. <a href="index.php">Neue hinzufügen</a></strong></div>';
								} else {
									echo '<div class="alert alert-info" style="text-align: center;"><strong><span class="glyphicon glyphicon-trash"></span> Success! Entry Deleted. <a href="index.php">Add a new One</a></strong></div>';
								}
                            } else {

								if($language == "de"){
									echo '<h2>Neuen Eintrag erfassen</h2>';
								} else {
									echo '<h2>Add new Entry</h2>';
								}

								$date = "";
								$weight = "";

								if(isset($_POST["InputDate"])){
									$date = $_POST["InputDate"];
								}
								if(isset($_POST["InputWeight"])){
									$weight = $_POST["InputWeight"];
								}

                                $now = date('Y-m-d H:i:s');

                                $sql = "INSERT INTO tbl_weight_data (time, weight, tbl_user_ID) VALUES ('$date', '$weight', '$users_id');";
                                $othersql = "UPDATE tbl_user SET current_weight='$weight' WHERE id='$users_id';";

                                if (isset($_POST['submit'])) {
                                       if(mysqli_query($link, $sql) == TRUE)
                                        {
											mysqli_query($link, $othersql);
											redirect("index.php?state=success");
											if($language == "de"){
												echo '<div class="alert alert-success" style="text-align: center;"><strong><span class="glyphicon glyphicon-ok"></span> Eintrag gespeichert! <a href="index.php">Weitere hinzufügen</a></strong></div>';
											} else {
												echo '<div class="alert alert-success" style="text-align: center;"><strong><span class="glyphicon glyphicon-ok"></span> Success! Weight Added. <a href="index.php">Add another one</a></strong></div>';
											}
                                        }else{
											echo '<div class="alert alert-danger" style="text-align: center;"><span class="glyphicon glyphicon-alert"></span><strong> Error!</strong></div>';
                                        }
                                } else {

									if($language == "de"){
										echo '

                                        <div class="col-lg-6" style="float: none !important; margin-left: auto !important; margin-right: auto !important;">
                                        <div class="form-group">
                                          <label for="InputDate">Datum & Zeit</label>
                                          <div class="input-group">
                                            <input type="text" class="form-control" name="InputDate" id="InputDate" value="'.$now.'" required>
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
                                        </div>
                                        <div class="form-group">
                                          <label for="InputWeight">Gewicht</label>
                                          <div class="input-group">
                                            <input type="number" step="0.01" class="form-control" id="InputWeight" name="InputWeight" placeholder="Ziel: '.$goal_weightForm.'" required>
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
                                        </div>
                                        <input type="submit" name="submit" id="submit" value="Speichern" class="btn btn-info pull-right">
                                      </div>

                                        ';
									} else {
										echo '

                                        <div class="col-lg-6" style="float: none !important; margin-left: auto !important; margin-right: auto !important;">
                                        <div class="form-group">
                                          <label for="InputDate">Date & Time</label>
                                          <div class="input-group">
                                            <input type="text" class="form-control" name="InputDate" id="InputDate" value="'.$now.'" required>
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
                                        </div>
                                        <div class="form-group">
                                          <label for="InputWeight">Weight</label>
                                          <div class="input-group">
                                            <input type="number" step="0.01" class="form-control" id="InputWeight" name="InputWeight" placeholder="Goal: '.$goal_weightForm.'" required>
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
                                        </div>
                                        <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
                                      </div>

                                        ';
									}


                                }
                            }


                        ?>

              </form>
            </div>
        </div>
        <!-- /.row -->



        <!-- Auflistung aller Werte -->
        <div class="row">
            <div class="col-lg-12 ">


                        <?php

							if($language == "de"){
								echo '<h2>Alle Einträge</h2>';
							} else {
								echo '<h2>All Entries</h2>';
							}

                            $sql2 = "SELECT * FROM tbl_weight_data WHERE tbl_user_ID = '$users_id' ORDER BY time DESC;";
							$sql2_3 = $sql2;
                            $alldata = mysqli_query($link, $sql2);
							$countTheData = mysqli_query($link, $sql2_3);
                            $i = "1";
                            $difference = "0";
                            $biggest_weight = "0";
                            $lowest_weight = "200";

							if($language == "de"){
								echo '  <div class="table-responsive"><table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Datum | Zeit</th>
                                            <th>Gewicht</th>
																						<th></th>
                                        </tr>
                                    </thead>
                                    <tbody>';
							} else {
								echo '  <div class="table-responsive"><table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date | Time</th>
                                            <th>Weight</th>
																						<th></th>
                                        </tr>
                                    </thead>
                                    <tbody>';
							}



									$thecountid = "0";

									while($rowxyz = mysqli_fetch_array($countTheData)) {
										$thecountid = $thecountid + "1";
									}

                                    while($row = mysqli_fetch_array($alldata)) {

										$date = DateTime::createFromFormat('Y-m-d H:i:s', $row['time']);
										$output = $date->format('d.m.Y | H:i');

                                        echo '<tr><td>'.$thecountid.'</td><td>'.$output.'</td><td>'.$row['weight'].' Kg</td><td><a href="deleteentry.php?id='.$row['ID'].'"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
                                        if ($i == "1"){
                                          $difference = $row['weight'];
                                          $oldest_weight = $row['weight'];
                                        } else if ($i == "2"){
                                          $difference = $difference - $row['weight'];
                                        }
                                        $i = $i + "1";
                                        $thecountid = $thecountid - "1";
                                        $newest_weight = $row['weight'];

                                    }



                            echo '  </tbody>
                                    </table></div>';
                            if (isset($oldest_weight) && isset($newest_weight)){
								$bigdifference = $oldest_weight - $newest_weight;
							} else {
								$bigdifference = "";
							}


                        ?>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
				<?php
				if($language == "de"){
					echo '<h2>Fakten</h2>';
				} else {
					echo '<h2>Facts</h2>';
				}
				?>

                <div class="col-lg-3 well factdivs">
					<?php
					if($language == "de"){
						echo '<p>Unterschied zwischen den letzten 2 Einträgen:</p>';
					} else {
						echo '<p>Difference between the last 2 entries:</p>';
					}

                        if ($difference < 0){
                                   echo "<h3 style='color: green;'>".round($difference, 4)." Kg</h3>";
                                } else if ($difference > 0){
                                   echo "<h3 style='color: red;'> +".round($difference, 4)." Kg</h3>";
                                } else {
                                   echo "<h3 style='color: grey;'>".round($difference, 4)." Kg</h3>";
                                }

                    ?>
                </div>
                <div class="col-lg-3 well factdivs">

                    <?php

						if($language == "de"){
							echo '<p>Unterschied zwischen letztem und ersten Eintrag:</p>';
						} else {
							echo '<p>Difference between the first and last entry:</p>';
						}

                        if ($bigdifference > 0){
                            echo "<h3 style='color: red;'> +".round($bigdifference, 2)." Kg</h3>";
                        } else if ($bigdifference < 0){
                            echo "<h3 style='color: green;'>".round($bigdifference, 2)." Kg</h3>";
                        } else {
                            echo "<h3 style='color: grey;'>".round($bigdifference, 2)." Kg</h3>";
                        }

                    ?>
                </div>

                <div class="col-lg-3 well factdivs">
					<?php
					if($language == "de"){
						echo '<p>Unterschied zwischem aktuellem und Ziel-Gewicht:</p>';
					} else {
						echo '<p>Difference between current Weight and Goal:</p>';
					}

                        if($differencetogoal >= "10" || $differencetogoal <= "-10"){
                          echo "<h3 style='color: red;'>". round($differencetogoal, 2) ." Kg </h3>";
                        } else if ($differencetogoal < "10" && $differencetogoal > "5" || $differencetogoal > "-10" && $differencetogoal < "-5"){
                          echo "<h3 style='color: grey;'>". round($differencetogoal, 2) ." Kg </h3>";
                        } else if ($differencetogoal < "5" || $differencetogoal > "-5"){
                          echo "<h3 style='color: green;'>". round($differencetogoal, 2) ." Kg </h3>";
                        } else {

                        }

                    ?>
                </div>

				<div class="col-lg-3 well factdivs">
					<?php
					if($language == "de"){
						echo '<p>Dein BMI:</p>';
					} else {
						echo '<p>Your BMI:</p>';
					}


                        $datas = mysqli_query($link, "SELECT current_weight, height, age FROM tbl_user WHERE id=$users_id;");

									 			while ($row = mysqli_fetch_array($datas)){
													if(isset($row['current_weight']) && isset($row['height']) && $row['height'] != 0){
														$bmi = round($row['current_weight'] / (($row['height'] / "100") * ($row['height'] /"100")), 2);
													} else {
														$bmi = 0;
													}
													if(isset($row['age'])){
														$age = $row['age'];
													} else {
														$age = 0;
													}

												}

									 			if($age <= 24 && $age > 10){
													if($bmi < 19){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													} else if ($bmi >= 19 && $bmi <=24){
														echo "<h3 style='color:green;'>".$bmi."</h3>";
													} else if ($bmi > 24){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													}
													if($language == "de"){
														echo '<p>Dein BMI sollte zwischen 19-24 sein.</p>';
													} else {
														echo '<p>Your BMI should be between 19-24</p>';
													}
												} else if ($age > 24 && $age <=34){
													if($bmi < 20){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													} else if ($bmi >= 20 && $bmi <=25){
														echo "<h3 style='color:green;'>".$bmi."</h3>";
													} else if ($bmi > 25){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													}
													if($language == "de"){
														echo "<p>Dein BMI sollte zwischen 20-25 sein</p>";
													} else {
														echo "<p>Your BMI should be between 20-25</p>";
													}
												} else if ($age > 34 && $age <=44){
													if($bmi < 21){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													} else if ($bmi >= 21 && $bmi <=26){
														echo "<h3 style='color:green;'>".$bmi."</h3>";
													} else if ($bmi > 26){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													}
													if($language == "de"){
														echo "<p>Dein BMI sollte zwischen 21-26 sein</p>";
													} else {
														echo "<p>Your BMI should be between 21-26</p>";
													}
												} else if ($age > 44 && $age <=54){
													if($bmi < 22){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													} else if ($bmi >= 22 && $bmi <=27){
														echo "<h3 style='color:green;'>".$bmi."</h3>";
													} else if ($bmi > 27){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													}
													if($language == "de"){
														echo "<p>Dein BMI sollte zwischen 22-27 sein</p>";
													} else {
														echo "<p>Your BMI should be between 22-27</p>";
													}
												} else if ($age > 54 && $age <=64){
													if($bmi < 23){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													} else if ($bmi >= 23 && $bmi <=28){
														echo "<h3 style='color:green;'>".$bmi."</h3>";
													} else if ($bmi > 28){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													}
													if($language == "de"){
														echo "<p>Dein BMI sollte zwischen 23-28 sein</p>";
													} else {
														echo "<p>Your BMI should be between 23-28</p>";
													}
												} else if ($age > 64) {
													if($bmi < 24){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													} else if ($bmi >= 24 && $bmi <=29){
														echo "<h3 style='color:green;'>".$bmi."</h3>";
													} else if ($bmi > 29){
														echo "<h3 style='color:red;'>".$bmi."</h3>";
													}
													if($language == "de"){
														echo "<p>Dein BMI sollte zwischen 24-29 sein</p>";
													} else {
														echo "<p>Your BMI should be between 24-29</p>";
													}
												} else {
													echo "<h3 style='color:grey;'>".$bmi."</h3>";
													if($language == "de"){
														echo "Füge dein <a href='change.php'>Alter</a> hinzu für den genauen BMI";
													} else {
														echo "<a href='change.php'>Add</a> your Age for exact BMI";
													}
												}





                    ?>
                </div>

								<div class="col-lg-3 well factdivs">
										<?php

										if($language == "de"){
											echo "<p>Tage bis zum Ziel-Datum:</p>";
										} else {
											echo "<p>Days left to Goal Date:</p>";
										}

										$datas = mysqli_query($link, "SELECT goal_date, current_weight, goal_weight FROM tbl_user WHERE id=$users_id;");

										while ($row = mysqli_fetch_array($datas)){
												$goal_date = $row['goal_date'];
												$now = date("Y-m-d");
												$weightleft = $row['current_weight'] - $row['goal_weight'];
										}


										$now = time(); // or your date as well
										$your_date = strtotime("$goal_date");
										$datediff = $your_date - $now;

										$daysleft = floor($datediff / (60 * 60 * 24));

										if($language == "de"){
											if($daysleft > 28){
												echo "<h3 style='color:green;'>". $daysleft ." Tage</h3>";
											} else if($daysleft <= 28 && $daysleft >=10){
												echo "<h3 style='color:grey;'>". $daysleft ." Tage</h3>";
											} else if($daysleft > 10){
												echo "<h3 style='color:red;'>". $daysleft ." Tage</h3>";
											}
										} else {
											if($daysleft > 28){
												echo "<h3 style='color:green;'>". $daysleft ." Days</h3>";
											} else if($daysleft <= 28 && $daysleft >=10){
												echo "<h3 style='color:grey;'>". $daysleft ." Days</h3>";
											} else if($daysleft > 10){
												echo "<h3 style='color:red;'>". $daysleft ." Days</h3>";
											}
										}


										?>
								</div>

								<div class="col-lg-3 well factdivs">

										<?php

										if($language == "de"){
											echo "<p>Täglich nötiger Verlust um Ziel-Gewicht auf Ziel-Datum zu erreichen:</p>";
										} else {
											echo "<p>Daily loss needed to achive Weight Goal until Date Goal:</p>";
										}

										$lossneeded = round($weightleft/$daysleft,4);

										if($lossneeded >0.30){
											echo "<h3 style='color:red;'>". $lossneeded ." Kg</h3>";
										} if($lossneeded <= 0.3 && $lossneeded > 0.14){
											echo "<h3 style='color:grey;'>". $lossneeded ." Kg</h3>";
										} if($lossneeded <=0.14){
											echo "<h3 style='color:green;'>". $lossneeded ." Kg</h3>";
										}
										?>
								</div>

								<div class="col-lg-3 well factdivs">

										<?php

											if($language == "de"){
												echo "<p>Täglich mögliche Einnahme anhand Durchschnittsverbrauch (2400):</p>	";
											} else {
												echo "<p>Daily possible Calories intake based on averge consumption (2400):</p>	";
											}

											$onekilo = "7300";
											$lossneededkilo = ($onekilo)*($lossneeded);

											$endresult = round("2400" - $lossneededkilo);

											if($language == "de"){
												if($endresult < "1000"){
												echo "<h3 style='color:red;'>". $endresult ." Kalorien</h3>";
											} else if($endresult > "1000") {
												echo "<h3 style='color:green;'>". $endresult ." Kalorien</h3>";
											}
											} else {
												if($endresult < "1000"){
												echo "<h3 style='color:red;'>". $endresult ." Calories</h3>";
											} else if($endresult > "1000") {
												echo "<h3 style='color:green;'>". $endresult ." Calories</h3>";
											}
											}



										?>
								</div>
            </div>
        </div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
