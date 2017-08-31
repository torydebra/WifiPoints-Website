<?php
	session_start();
	require("deleteCookie.php"); //cancella cookie dal db
	unset($_SESSION);
	session_destroy();
	session_write_close();
	if(isset($_COOKIE["logRemember"])){
		setcookie("logRemember", "", time()-3600, '/');
	}

	header("location: ../homepage.php");
	die("");
?>
