<?php
//TODO mettere i php nel server in cartella non pubblica

session_start();

$id_user = $id_punto = $name = $tipo_attivita = $address = $addressNumber = $city = $ssid = $wifiType = $passWifiBool = $description = $telephone = $site = $social = $xCoord = $yCoord = null;
$allowedTipoAttivita = array("freewifi", "bar", "fastfood", "hotel", "ristorante", "gelateria", "isola", "altro");
$allowedwifiType = array("free", "consume", "pay");
$allowedPassBool = array ("0", "1");

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
	header("location: ../addPointForm.php?error=1");
	die;
}

//info obbligatorie
if (( !isset($_SESSION["id_user"]) || !isset($_SESSION["id_punto"]) || !isset($_POST["name"]) ||
	  !isset($_POST["tipo_attivita"]) || !isset($_POST["address"]) || !isset($_POST["addressNumber"]) ||
	  !isset($_POST["city"]) || !isset($_POST["tipowifi"]) || !isset($_POST["passwordBool"]) ||
	  !isset($_POST["xCoord"]) || !isset($_POST["yCoord"]))
	 ||
	 (empty($_SESSION["id_user"]) || empty($_SESSION["id_punto"]) || empty($_POST["name"]) ||
	  empty($_POST["tipo_attivita"]) || empty($_POST["address"]) || empty($_POST["addressNumber"]) ||
	  empty($_POST["city"]) || empty($_POST["tipowifi"]) || empty($_POST["xCoord"]) ||
	  empty($_POST["yCoord"]))
	 ||
	 (!in_array($_POST["tipo_attivita"], $allowedTipoAttivita, true) || //mod strict
	 !in_array($_POST["tipowifi"], $allowedwifiType, true) || !is_numeric($_POST["addressNumber"]))
	 ||
	 ($_POST["passwordBool"] != 0 && $_POST["passwordBool"] != 1)
    ){

	header("location: ../addPointForm.php?error=1");
	die;

}

$id_user = $_SESSION["id_user"];
$id_punto = $_SESSION["id_punto"];
$name = trim($_POST["name"]);
$name = htmlspecialchars($name);
$address = trim($_POST["address"]);
$address = htmlspecialchars($address);
$city = trim($_POST["city"]);
$city = htmlspecialchars($city);
$xCoord = trim($_POST["xCoord"]);
$xCoord = htmlspecialchars($xCoord);
$yCoord = trim($_POST["yCoord"]);
$yCoord = htmlspecialchars($yCoord);

//già validati in if sopra
$tipo_attivita = $_POST["tipo_attivita"];
$wifiType = $_POST["tipowifi"];
$passWifiBool = $_POST["passwordBool"];
$addressNumber = $_POST["addressNumber"];

require("connectDbLocalhost.php");

mysqli_real_escape_string($conn,$name);
mysqli_real_escape_string($conn,$address);
mysqli_real_escape_string($conn,$city);
mysqli_real_escape_string($conn,$xCoord);
mysqli_real_escape_string($conn,$yCoord);

if(isset($_POST["ssid"]) && !empty($_POST["ssid"])) {
	$ssid = trim($_POST["ssid"]);
	$ssid = htmlspecialchars($ssid);
	mysqli_real_escape_string($conn,$ssid);
}
if(isset($_POST["description"]) && !empty($_POST["description"])) {
	$description = trim($_POST["description"]);
	$description = htmlspecialchars($description);
	mysqli_real_escape_string($conn,$description);
}
if(isset($_POST["telefono"]) && !empty($_POST["telefono"])) {
	$telephone = trim($_POST["telefono"]);
	$telephone = htmlspecialchars($telephone);
	mysqli_real_escape_string($conn,$telephone);
}
if(isset($_POST["sito"]) && !empty($_POST["sito"])) {
	$site = trim($_POST["sito"]);
	$site = htmlspecialchars($site);
	mysqli_real_escape_string($conn,$site);
}
if(isset($_POST["social"]) && !empty($_POST["social"])) {
	$social = trim($_POST["social"]);
	$social = htmlspecialchars($social);
	mysqli_real_escape_string($conn,$social);
}

// update del punto "id_punto" e verifica anche che user che sta modificando sia il vero gestore
// del punto
$preparedQuery = mysqli_prepare($conn,
	"UPDATE puntiWiFi SET nome_punto=?, tipo_attivita=?,
	coordinate=GeomFromText(?,0), indirizzo=?, ssid_wifi=?, tipo_accesso_wifi=?,
	password_wifi_bool=?, descrizione=?, sito=?, social=?, telefono=? WHERE id_punto =? AND id_user=?");



//if (!$boh){
//	echo ("error prepared<br>");
//	echo (mysqli_error($conn));
//	die;
//}
$point = "POINT(" .$xCoord ." " . $yCoord .")";
$address = $address . ", " . $addressNumber . "; " . $city;

mysqli_stmt_bind_param($preparedQuery, 'sssssssssssss', $name, $tipo_attivita, $point, $address, $ssid, $wifiType, $passWifiBool, $description, $site, $social, $telephone, $id_punto, $id_user);

$result = mysqli_stmt_execute($preparedQuery);
mysqli_stmt_close($preparedQuery);
mysqli_close($conn);

if ($result){

	// se tutto ok, va nella pagina di verifica posizione marker
	header("location: ../checkPositionAddedPoint.php");
	die;
}

else {
	echo ("error execute<br>");
	//echo (mysqli_stmt_error($preparedQuery)); solo se chiusure dopo
	header("location: ../addPointForm.php?error=1");
	die;

}


?>
