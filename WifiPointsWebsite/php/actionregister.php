<?php
//TODO mettere i php nel server in cartella non pubblica
$username = $email = $password = $plus = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	if (!isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["password"]) ||
		!isset($_POST["password2"]) ) {

		header("location: ../registerPage.php?error=1");
		die;

	} else {
		$username = trim($_POST["username"]);
		$username = htmlspecialchars($username);
		$email = trim($_POST["email"]);
		$email = htmlspecialchars($email);
		$password = trim($_POST["password"]);
		$password = htmlspecialchars($password);
		$password2 = trim($_POST["password2"]);
		$password2 = htmlspecialchars($password2);
		if(isset($_POST["name"])) {
			$name = trim($_POST["name"]);
			$name = htmlspecialchars($name);
		}
		if(isset($_POST["surname"])) {
			$surname = trim($_POST["surname"]);
			$surname = htmlspecialchars($surname);
		}
	}

} else {
	header("location: ../registerPage.php?error=1");
	die;
}

//se viene saltato il controllo javascript e le password sono diverse
if ($password != $password2){
	header("location: ../registerPage.php?error=1");
	die;
}

require("connectDbLocalhost.php");

mysqli_real_escape_string($conn,$username);
mysqli_real_escape_string($conn,$email);
mysqli_real_escape_string($conn,$password);

$passwordCrypted = password_hash ($password, PASSWORD_BCRYPT);

if($passwordCrypted === FALSE){ //errore di hash password
	header("location: ../registerPage.php?error=1");
	die;
}

if(isset($name)){ //user plus
	$plus=1;
	mysqli_real_escape_string($conn,$name);
	mysqli_real_escape_string($conn,$surname);
	$preparedQuery = mysqli_prepare($conn, "INSERT INTO users
	(id_user, username, password, email, image_path, plus, nome, cognome)
	VALUES (NULL, ?, ?, ?, NULL, 1, ?, ?)");

	mysqli_stmt_bind_param($preparedQuery, 'sssss', $username, $passwordCrypted, $email, $name, $surname);

} else {
	$plus=0;
	// NULL per gli autoincrement
	$preparedQuery = mysqli_prepare($conn, "INSERT INTO users
	(id_user, username, password, email, image_path, plus, nome, cognome)
	VALUES (NULL, ?, ?, ?, NULL, 0, NULL, NULL)");

	mysqli_stmt_bind_param($preparedQuery, 'sss', $username, $passwordCrypted, $email);
}

$result = mysqli_stmt_execute($preparedQuery);
$id_user = mysqli_insert_id($conn);
mysqli_stmt_close($preparedQuery);
mysqli_close($conn);

if ($result == 1){

	if(isset($_POST["rememberMe"])){
		require("createCookie.php");
	}
	session_start();
	$_SESSION["id_user"] = $id_user;
	$_SESSION["username"] = $username;
	$_SESSION['plus'] = $plus;
	if(isset($_SESSION['originurl'])){ //se arriva richiesta login ad es da pag plusPoint.php
		$locate = $_SESSION['originurl'];
		unset($_SESSION['originurl']);
		header("location:" . $locate);

	} else {
		header("location: ../homepage.php");
	}
	die;
}

// errori sull'inserimento in db accadono solo se si bypassa il controllo javascript quindi
// non interessa rivelare che tipo di errore è accaduto
else {
	header("location: ../registerPage.php?error=1");
	die;
}

?>
