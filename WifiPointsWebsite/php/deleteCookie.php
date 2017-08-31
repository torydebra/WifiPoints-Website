<?php
	require("connectDbLocalhost.php");
	$preparedQuery = mysqli_prepare($conn, "DELETE FROM cookie WHERE id_user=?");
	mysqli_stmt_bind_param($preparedQuery, 's', $id_user);
	mysqli_stmt_execute($preparedQuery);
	mysqli_stmt_close($preparedQuery);
	mysqli_close($conn);
?>
