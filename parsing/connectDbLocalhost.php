<?php
$servername = "localhost";
$usernameserver = "S4119809";
$passwordserver = "gruppodev";
$dbname ="S4119809";
$charset = "utf8";


// Create connection
$conn = mysqli_connect($servername, $usernameserver, $passwordserver, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset ($conn , $charset);  //per parsare correttamente accenti e vari

// Check connection

//echo "Connected successfully<br>";
//echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
//echo"<br>";


?>
