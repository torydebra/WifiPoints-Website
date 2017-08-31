<?php

if(isset($_COOKIE["logRemember"])){

	$val = $id_user = $valDb = $username = $plus = "";
	require("connectDbLocalhost.php");

	$val = $_COOKIE["logRemember"];

	$preparedQuery = mysqli_prepare($conn, "
		SELECT cookie.id_user, cookie.value_hash, users.username, users.plus
		FROM cookie
		INNER JOIN users ON cookie.id_user = users.id_user");
	mysqli_stmt_bind_result($preparedQuery, $id_user, $valDb, $username, $plus);
	mysqli_stmt_execute($preparedQuery);
	while(mysqli_stmt_fetch($preparedQuery)){
//		echo($val);
//		echo("<br>");
//		echo($valDb);
//		echo("<br>");
		if (password_verify($val, $valDb)){
			$_SESSION['id_user'] = $id_user;
			$_SESSION['username'] = $username;
			$_SESSION['username'] = $username;
			$_SESSION['plus'] = $plus;
			break;
		}
	}
	mysqli_stmt_close($preparedQuery);
	mysqli_close($conn);
}

?>
