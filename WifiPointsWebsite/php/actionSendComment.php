<?php
session_start();

$testo_commento = $id_punto = $id_user = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	if (!isset($_POST["testo_commento"]) || !isset($_SESSION["id_user"]) || !isset($_POST["id_punto"])) {
		header("location: ../map.php");
    	die();
	}

	$testo_commento = trim($_POST["testo_commento"]);
	$id_user = trim($_SESSION["id_user"]);
	$id_punto = trim($_POST["id_punto"]);


	//id user non può essere null visto che è una var di sessione, se viene cancellata tutta viene visto
	// dall'isset sopra, che vede anche se viene modificata?
	if($id_punto === null){
		header("location: ../map.php");

		die($id_punto);
	}
	if($testo_commento === ''){
		$locate = "location: ../data/wifipoints/genericPoint.php?" . $id_punto;
		header($locate);
		die();
	}
//	echo($testo_commento);
//	echo("<br>");
//	echo(gettype($testo_commento));
//	echo("<br>");
//	echo($id_punto);

	require("connectDbLocalhost.php");

	$id_user = htmlspecialchars($id_user);
	$testo_commento = htmlspecialchars($testo_commento);
	$id_punto = htmlspecialchars($id_punto);

	//escape caratteri html come i < > dei tag
	mysqli_real_escape_string($conn,$id_user);
	mysqli_real_escape_string($conn,$testo_commento);
	mysqli_real_escape_string($conn,$id_punto);

	$preparedQuery = mysqli_prepare($conn, "INSERT INTO commenti VALUES (NULL, ?, ?, NULL, ?)");
	//gli id sono di tipo stringa per la real escape string
	mysqli_stmt_bind_param($preparedQuery, 'sss', $id_user, $id_punto, $testo_commento);
	$result = mysqli_stmt_execute($preparedQuery);

	mysqli_stmt_close($preparedQuery);

	//per sapere se punto plus o no per reindirizzare correttamente
	$plus = '';
	$preparedQuery = mysqli_prepare($conn, "SELECT id_user FROM puntiWiFi WHERE id_punto=?");
	mysqli_stmt_bind_param($preparedQuery, 's', $id_punto);
	mysqli_stmt_bind_result($preparedQuery, $plus);
	$result = mysqli_stmt_execute($preparedQuery);
	mysqli_stmt_fetch($preparedQuery);


	mysqli_stmt_close($preparedQuery);
	mysqli_close($conn);

	if($plus === null){
		$locate = "location: ../data/wifipoints/genericPoint.php?" . $id_punto;

	} else {
		$locate = "location: ../data/wifipoints/plusPoint.php?" . $id_punto;
	}

	header($locate);

}
?>
