<?php
session_start();
header('Content-type: application/json');
require("connectDbLocalhost.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["id_user"]) && isset($_SESSION["plus"]) &&
   $_SESSION["plus"] === 1) {

	 $wifiPoints = '';

	// X() e ST_X() sinonimi, ma stX c'è da mysql 5.6.1 quindi sul server usabile solo X()
	 $preparedQuery = mysqli_prepare($conn, "SELECT id_punto, id_user, nome_punto, tipo_attivita, X(coordinate), Y(coordinate), indirizzo, ssid_wifi, tipo_accesso_wifi, password_wifi_bool, descrizione, sito, social, telefono FROM puntiWiFi WHERE id_user=?");
	 mysqli_stmt_bind_param($preparedQuery, 's', $_SESSION["id_user"]);

	 mysqli_stmt_execute($preparedQuery);
	 $point = mysqli_stmt_get_result($preparedQuery);

	 $wifiPoints = mysqli_fetch_all($point, MYSQLI_ASSOC);

	 mysqli_stmt_free_result($preparedQuery);
	 mysqli_stmt_close($preparedQuery);
	 mysqli_close($conn);
	 echo json_encode($wifiPoints);
}

?>
