<?php
//TODO mettere i php nel server in cartella non pubblica

session_start();


$id_user = $id_punto = $xCoord = $yCoord = null;

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
	header("location: ../addPointForm.php?error=1");
	die;
}

if (!isset($_SESSION["id_punto"]) || !isset($_SESSION["id_user"]) ){
	echo("non hai i permessi per modificare questo punto");
	die;

}

//info obbligatorie
if (!isset($_POST["xCoord"]) || !isset($_POST["yCoord"])
	||
	 (empty($_SESSION["id_user"]) || empty($_SESSION["id_punto"]) || empty($_POST["xCoord"]) ||
	 empty($_POST["yCoord"]))
   ){
	header("location: ../addPointForm.php?error=1");
	die;

}

$id_user = $_SESSION["id_user"];
$id_punto = $_SESSION["id_punto"];
// perchè coord sono hidden nel form quindi modificabili per injection
$xCoord = trim($_POST["xCoord"]);
$xCoord = htmlspecialchars($xCoord);
$yCoord = trim($_POST["yCoord"]);
$yCoord = htmlspecialchars($yCoord);

require("connectDbLocalhost.php");

mysqli_real_escape_string($conn,$xCoord);
mysqli_real_escape_string($conn,$yCoord);

$preparedQuery = mysqli_prepare($conn,
	"UPDATE puntiWiFi SET coordinate=GeomFromText(?,0) WHERE id_punto =? AND id_user= ?");

//if (!$boh){
//	echo ("error prepared<br>");
//	echo (mysqli_error($conn));
//	die;
//}
$point = "POINT(" .$xCoord ." " . $yCoord .")";

mysqli_stmt_bind_param($preparedQuery, 'sss', $point, $id_punto, $id_user);
//if (!$boh){
//	echo ("error bindparam<br>");
//	echo (mysqli_error($preparedQuery));
//	die;
//}

$result = mysqli_stmt_execute($preparedQuery);
mysqli_stmt_close($preparedQuery);
mysqli_close($conn);

if ($result){

	echo ("success");
	echo ('
		<p><a href="../data/wifipoints/plusPoint.php?' . $id_punto . '">Return back
		<i class="fa fa-undo" aria-hidden="true"></i></a>
		</p>');
	die;
}

else {
	//echo ("error execute<br>");
	//echo (mysqli_stmt_error($preparedQuery));
	echo ('
		<p><a href="../map.php">Return Back
		<i class="fa fa-undo" aria-hidden="true"></i></a>
		</p>');
	die;

}
?>
