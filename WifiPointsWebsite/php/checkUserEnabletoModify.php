<?php

if(isset($_SESSION['id_punto']) && isset($_SESSION['id_user'])){
	require("connectDbLocalhost.php");

	$preparedQuery = mysqli_prepare($conn,
		"SELECT * FROM puntiWiFi WHERE id_punto=? AND id_user=?");
//
//	echo(mysqli_error($conn));
//	echo("<br>");
//	echo($_SESSION['id_punto']);
//	echo("<br>");
//	echo($_SESSION['id_user']);
	mysqli_stmt_bind_param($preparedQuery, 'ss', $_SESSION['id_punto'], $_SESSION['id_user']);
	mysqli_stmt_execute($preparedQuery);
	mysqli_stmt_store_result($preparedQuery);
	$row = mysqli_stmt_num_rows($preparedQuery);

	mysqli_stmt_free_result($preparedQuery);
	mysqli_stmt_close($preparedQuery);
	mysqli_close($conn);

	//print_r($_SESSION['id_punto']);
	//echo("<br>");
	//print_r($_SESSION['id_user']);
	//echo("<br>");
	//print_r($row);
}

else{
	$row=0;
}
?>
