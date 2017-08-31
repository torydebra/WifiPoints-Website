<?php
//carica punto avendo id da invio in post

//cosi non serve il json parse per jquery in pointinfoscript
header('Content-type: application/json');

require("connectDbLocalhost.php");

 if ($_SERVER["REQUEST_METHOD"] === "POST") {

	if (!isset($_POST["id_punto"]) || $_POST["id_punto"] === '') {
		//questo php sta venendo eseguito da una chiamata ajax e non dalla pagina del bro attuale
		//quindi non può cambiare gli header della pagina attuale ma cmq si interrompe
		header("Location: ../map.php");
    	die();
	}
	 $wifiPoint = '';

	// $preparedQuery = mysqli_prepare($conn, "SELECT * FROM puntiWiFi");
	 $preparedQuery = mysqli_prepare($conn, "SELECT id_punto, id_user, nome_punto, tipo_attivita, X(coordinate), Y(coordinate), indirizzo, ssid_wifi, tipo_accesso_wifi, password_wifi_bool, descrizione, sito, social, telefono FROM puntiWiFi WHERE id_punto=?");

	 mysqli_stmt_bind_param($preparedQuery, 's', $_POST['id_punto']);
	 mysqli_stmt_execute($preparedQuery);
	 $point = mysqli_stmt_get_result($preparedQuery);

	 $wifiPoint = mysqli_fetch_assoc($point);

	 mysqli_stmt_free_result($preparedQuery);
	 mysqli_stmt_close($preparedQuery);
	 mysqli_close($conn);
	 echo json_encode($wifiPoint);
	// echo $_POST['id_punto'];


}
