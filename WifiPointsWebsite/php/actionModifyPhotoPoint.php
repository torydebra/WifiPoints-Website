<?php
require_once("functionImgtopng.php");

session_start();

if(isset($_FILES["photo"]) && $_FILES["photo"]["name"] !== '' && isset($_SESSION["id_punto"]) ){

		$target = "../data/images/wifipoints/" . $_SESSION['id_punto'];

		//nell'entualità di una cancellazione punto, alua sarà necessario rimuovere anche la cartella

		//suppress warning: sappiamo che stiamo sovrascrivendo
		@mkdir($target);

		$photoName = $_SESSION['id_punto'] .'.png';
		$target = $target . "/". $photoName;

//		echo $target;
//		echo("<br>");
//		echo $_FILES["photo"]['name']."<br>";
//		echo $_FILES["photo"]['tmp_name']."<br>";
//		echo $_FILES["photo"]['size']."<br>";
//		echo $_FILES['photo']['error']."<br>";

		imageUploadedfromModify($_FILES["photo"]["tmp_name"], $target);

}
//con location serviva poi aggiornare a mano pag per vedere la foto
//cambiata.
header('Refresh: 1; url=../data/wifipoints/plusPoint.php?' . $_SESSION["id_punto"]);
die;
?>
