<?php
//TODO mettere i php nel server in cartella non pubblica
session_start();

$id_user = $name = $tipo_attivita = $address = $addressNumber = $city = $ssid = $wifiType = $passWifiBool = $description = $telephone = $site = $social = $xCoord = $yCoord = null;
$allowedTipoAttivita = array("freewifi", "bar", "fastfood", "hotel", "ristorante", "gelateria", "isola", "altro");
$allowedwifiType = array("free", "consume", "pay");
$allowedPassBool = array ("0", "1");

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
	header("location: ../addPointForm.php?error=1");
	die;
}

//info obbligatorie
if ((!isset($_SESSION["id_user"]) || !isset($_POST["name"]) || !isset($_POST["tipo_attivita"]) ||
	 !isset($_POST["address"]) || !isset($_POST["addressNumber"]) || !isset($_POST["city"]) ||
	 !isset($_POST["tipowifi"]) || !isset($_POST["passwordBool"]) ||
	 !isset($_POST["xCoord"]) || !isset($_POST["yCoord"]))
	 ||
	 (empty($_SESSION["id_user"]) || empty($_POST["name"]) || empty($_POST["tipo_attivita"]) ||
	 empty($_POST["address"]) || empty($_POST["addressNumber"]) || empty($_POST["city"]) || empty($_POST["tipowifi"]) || empty($_POST["xCoord"]) || empty($_POST["yCoord"]))
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

// NULL per gli autoincrement
$preparedQuery = mysqli_prepare($conn,
	"INSERT INTO puntiWiFi (id_punto, id_user, nome_punto, tipo_attivita, coordinate, indirizzo, ssid_wifi, tipo_accesso_wifi, password_wifi_bool, descrizione, sito, social, telefono)
	VALUES (NULL, ?, ?, ?, GeomFromText(?,0), ?, ?, ?, ?, ?, ?, ?, ?)");

//if (!$boh){
//	echo ("error prepared<br>");
//	echo (mysqli_error($conn));
//	die;
//}
$point = "POINT(" .$xCoord ." " . $yCoord .")";
$address = $address . ", " . $addressNumber . "; " . $city;

mysqli_stmt_bind_param($preparedQuery, 'ssssssssssss', $id_user, $name, $tipo_attivita, $point, $address, $ssid, $wifiType, $passWifiBool, $description, $site, $social, $telephone);

$result = mysqli_stmt_execute($preparedQuery);
$id_inserted = mysqli_insert_id($conn);

if ($result){

	//da mettere qui perchè serve il id_inserted
	if(isset($_FILES["photo"]) && $_FILES["photo"]["name"] !== ''){
		require_once("functionImgtopng.php");

		$target = "../data/images/wifipoints/" . $id_inserted;

		//se falso qualche errore strano perchè id è unico
		//nell'entualità di una cancellazione punto, alua sarà necessario rimuovere anche la cartella
		//della sua immagine per non incorrere in errore qua
		if (!file_exists($target)) {
			mkdir($target);

			$photoName = $id_inserted .'.png';
			$target = $target . "/". $photoName;

//			echo $target;
//			echo("<br>");
//			echo $_FILES["photo"]['name']."<br>";
//			echo $_FILES["photo"]['tmp_name']."<br>";
//			echo $_FILES["photo"]['size']."<br>";
//			echo $_FILES['photo']['error']."<br>";

			imageUploaded($_FILES["photo"]["tmp_name"], $target);
		}
	}

	//salva id nella sessione così posso andare in pagina di checkpositionaddedpoint che verifica che
	//il punto che si sta modificando appartenda all'user loggato
	$_SESSION['id_punto'] = $id_inserted;
	mysqli_stmt_close($preparedQuery);
	mysqli_close($conn);

	header("location: ../checkPositionAddedPoint.php");
	die;
}


else {
	mysqli_stmt_close($preparedQuery);
	mysqli_close($conn);
	//echo ("error execute<br>");
	//echo (mysqli_stmt_error($preparedQuery)); solo se chiusura è dopo
	header("location: ../addPointForm.php?error=1");
	die;

}
?>
