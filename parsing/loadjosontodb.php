<?php
require_once("connectDbLocalhost.php");

define ("TYPEFILE", array(
	"geojson/freewifigenova.geojson",
	"geojson/Bar.geojson",
	"geojson/caffè.geojson",
	"geojson/Fastfood.geojson",
 	"geojson/Gelateria.geojson",
	"geojson/hotel.geojson",
	"geojson/ristorante.geojson",
	"geojson/Isola.geojson",
	"geojson/Altro.geojson"
));

define ("TYPE", array(
	"freewifi", //0
	"bar",      //1
	"bar",
	"fastfood",
	"gelateria",
	"hotel",
	"ristorante", //6
	"isola",
	"altro"
));

$tableName = 'my_table';
for($i = 0; $i<9; $i++){

	$jsonFile = TYPEFILE[$i];
	$type = TYPE[$i];
	//$type = "test";

	$jsonString = file_get_contents($jsonFile);
	$jsonData = json_decode($jsonString, true);
	$jsonDataElements = $jsonData['features'];  //elements contiene tutti i puntiWIFI
	//echo count($jsonDataelement);

	foreach ($jsonDataElements as $wifiPoint){

		$name = $wifiPoint['properties']['name'];
		//$name = "del'lstorto";
		$name = trim($name);
		$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); //per apici e &
		$coordY = $wifiPoint['geometry']['coordinates'][1];
		$coordX = $wifiPoint['geometry']['coordinates'][0];

		$sql = "INSERT INTO puntiWiFi (id_punto, id_user, nome_punto, tipo_attivita, coordinate, indirizzo, ssid_wifi, tipo_accesso_wifi, password_wifi_bool, descrizione, sito, social, telefono)
			VALUES (NULL, NULL, '" .$name ."', '" .$type . "', GeomFromText('POINT(" .$coordX ." " . $coordY .")',0), NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";

		echo "<br>";
		echo $sql;
		echo "<br>";

		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
			echo "<br>";
		}
		else {
			echo "Error";
		}
	}
}

$conn->close();
?>
