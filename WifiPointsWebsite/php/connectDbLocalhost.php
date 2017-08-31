<?php
$servername = "localhost";
$usernameserver = "S4119809";
//$usernameserver = "phpmyadmin";
$passwordserver = "gruppodev";
//$passwordserver = "admindb";
$dbname ="S4119809";
//$dbname ="phpmyadmin";
$charset = "utf8";

// Create connection
$conn = mysqli_connect($servername, $usernameserver, $passwordserver, $dbname);

if (!$conn) {
	echo("Connection failed: " . mysqli_connect_error());
	die();

}
mysqli_set_charset ($conn , $charset);

// Check connection
//
//var_dump("Connected successfully<br>");
//echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
//echo"<br>";

?>
