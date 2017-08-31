<?php

require("connectDbLocalhost.php");

$preparedQuery = mysqli_prepare($conn,
	"SELECT * FROM users WHERE id_user=? AND plus=1");

mysqli_stmt_bind_param($preparedQuery, 's', $_SESSION['id_user']);
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

?>
