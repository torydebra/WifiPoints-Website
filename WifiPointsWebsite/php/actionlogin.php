<?php

$username = $id_user = $password = $passwordDB = $plus = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
}

$username = trim($username);
$username = htmlspecialchars($username);
$password = trim($password);
$password = htmlspecialchars($password);

require("connectDbLocalhost.php");

mysqli_real_escape_string($conn,$username);
mysqli_real_escape_string($conn,$password);

$preparedQuery = mysqli_prepare($conn, "SELECT id_user, password, plus FROM users WHERE username=? OR email=?");
mysqli_stmt_bind_param($preparedQuery, 'ss', $username, $username);
mysqli_stmt_bind_result($preparedQuery, $id_user, $passwordDB, $plus);
$result = mysqli_stmt_execute($preparedQuery);
mysqli_stmt_fetch($preparedQuery);

if($result){

	if (password_verify($password, $passwordDB)){
		//se è presente cancello cookie vecchio dal db
		require("deleteCookie.php");

		if(isset($_POST["rememberMe"])){
			//creo nuovo cookie
			require("createCookie.php");
		}
		session_start();
		$_SESSION["id_user"] = $id_user;
		$_SESSION["username"] = $username;
		$_SESSION['plus'] = $plus;
		if(isset($_SESSION['originurl'])){ //se arriva richiesta login ad es da pag plusPoint.php
			$locate = $_SESSION['originurl'];
			unset($_SESSION['originurl']);
			header("location:" . $locate);

		} else {
			header("location: ../homepage.php");
		}
		die;
	}
	else{
		header("location: ../loginPage.php?error=1");
		die;
	}
} else{
	//echo("fail result");
	header("location: ../loginPage.php?error=1");
	die;
}
?>
