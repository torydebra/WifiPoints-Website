<?php

session_start();

if(!isset($_SESSION["id_user"])){
	require("php/checkCookie.php");
	if(!isset($_SESSION["id_user"])){
		header("location: ../homepage.php");
		die("");
	}
}

require("connectDbLocalhost.php");
$stmt = $conn ->prepare("SELECT users.username,chat.messaggio,chat.data_messaggio
	FROM users
	INNER JOIN chat ON users.id_user = chat.id_user
	ORDER BY chat.data_messaggio DESC LIMIT 0,20");
$stmt->execute();
$stmt->bind_result($username, $messaggio, $data);
while ($stmt->fetch()) {
	echo "<b>".$username." : ".$messaggio."</b>";
	echo "<br>";
}

?>
