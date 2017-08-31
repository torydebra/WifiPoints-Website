<?php
header('Content-type: application/json');

$testo_commento = $id_punto = $id_user = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

	$comments = '';
	//$id_punto esiste perchè ciò è già stato verificato in loadSinglePoint

	$id_punto = trim($_POST["id_punto"]);

	require("connectDbLocalhost.php");

	$id_punto = htmlspecialchars($id_punto);
	mysqli_real_escape_string($conn,$id_punto);

	$preparedQuery = mysqli_prepare($conn, "
		SELECT commenti.data_commento, commenti.testo_commento, users.username
		FROM commenti
		INNER JOIN users ON commenti.id_user=users.id_user
		WHERE id_punto=?
		ORDER BY commenti.data_commento");

	//id è stringa per la real escape string
	mysqli_stmt_bind_param($preparedQuery, 's', $id_punto);
	mysqli_stmt_execute($preparedQuery);

	$result = mysqli_stmt_get_result($preparedQuery);

	$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

	mysqli_stmt_free_result($preparedQuery);
	mysqli_stmt_close($preparedQuery);

	mysqli_close($conn);
	echo json_encode($comments);

}
?>
