<?php
//cerca il gestore del punto wifi

//cosi non serve il json parse per chiamante jquery
header('Content-type: application/json');

require("connectDbLocalhost.php");

 if ($_SERVER["REQUEST_METHOD"] === "POST") {

	if (!isset($_POST["id_punto"]) || $_POST["id_punto"] === '') {
		//questo php sta venendo eseguito da una chiamata ajax e non dalla pagina del bro attuale
		//quindi non può cambiare gli header della pagina attuale ma cmq si interrompe
		header("Location: ../map.php");
    	die();
	}
	 $username='';

	 $preparedQuery = mysqli_prepare($conn, "
	 	SELECT users.username
		FROM puntiWiFi
		INNER JOIN users ON puntiWiFi.id_user=users.id_user
		WHERE id_punto=?");

	 mysqli_stmt_bind_param($preparedQuery, 's', $_POST['id_punto']);
	 mysqli_stmt_execute($preparedQuery);
	 $result = mysqli_stmt_get_result($preparedQuery);

	 $user = mysqli_fetch_assoc($result);

	 mysqli_stmt_free_result($preparedQuery);
	 mysqli_stmt_close($preparedQuery);
	 mysqli_close($conn);
	 echo json_encode($user);
}
?>
