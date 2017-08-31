<?php
	session_start();

	if(!isset($_SESSION["id_user"])){
		require("php/checkCookie.php");
	}

	if(isset($_SESSION["id_user"])){
		if($_SESSION["plus"] === 0){
			header("location: ../chatAll.php");
			die("");
		}
	}
	else{
		header("location: ../loginPage.php");
		die("");
	}

	require("connectDbLocalhost.php");
	$stmt = $conn ->prepare("SELECT users.username,chatplus.messaggio,chatplus.data_messaggio
		FROM users
		INNER JOIN chatplus ON users.id_user = chatplus.id_user
		ORDER BY chatplus.data_messaggio DESC LIMIT 0,10");
	$stmt->execute();
	$stmt->bind_result($username, $messaggio,$datamessaggio);
	while ($stmt->fetch()) {
        echo "<b>".$username." : ".$messaggio."</b>";
        echo "<br>";
    }
?>
