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
}

$link = mysqli_connect("localhost", "root", "", "eliareut_weight") or die (mysqli_error ());

$id = $_GET['id'];

$sql = "DELETE FROM `tbl_weight_data` WHERE `ID` = $id";
$sql2 = "SELECT weight FROM tbl_weight_data WHERE tbl_user_ID = $users_id ORDER BY time DESC";


mysqli_query($link, $sql);

$row = mysqli_fetch_array(mysqli_query($link, $sql2));
$lastweight = $row['weight'];

$sql3 = "UPDATE `tbl_user` SET `current_weight` = '$lastweight' WHERE `ID` = $users_id;";

mysqli_query($link, $sql3);

header('Location: index.php?state=successdeleted');

?>