<?php
session_start();
if(isset($_SESSION["id_user"])){

	if($_SESSION["plus"] === 0){
		header("location: ../chatAll.php");
		die("");
	}
}
else{
	header("location: ../loginPage.php");
	die;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$messaggio = "";
	if(!empty($_POST["message"])){
		$messaggio = trim($_POST["message"]);
		$messaggio = htmlspecialchars($messaggio);
	} else {
		header("location: ../chatPlus.php");
		die("");
	}
	$id_user = $_SESSION["id_user"];

	require("connectDbLocalhost.php");
	mysqli_real_escape_string($conn,$id_user);
	mysqli_real_escape_string($conn,$messaggio);
	$stmt = $conn->prepare("INSERT INTO chatplus(`id_user`,`messaggio`) VALUES (?,?)");
	$stmt->bind_param("ss",$id_user,$messaggio);
	$stmt->execute();
	$stmt->close();
	header("location: ../chatPlus.php");
	die;
}
else{
	header("location: ../homepage.php");
	die();
}
?>
