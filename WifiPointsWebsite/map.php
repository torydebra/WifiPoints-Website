<?php
session_start();
if(!isset($_SESSION['id_user'])){ //se è loggato è inutile andare a vedere i cookie
	require("php/checkCookie.php");
	if(!isset($_SESSION['id_user'])){ //per reindirizzare qui se faccio il login/reg da qui
		$_SESSION["originurl"] = $_SERVER['REQUEST_URI'];
	}
}

?>


<!DOCTYPE html>
<html lang=it>

<head>
	<title>WiFi Map</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8" />
	<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="libraries/leaflet/leaflet.css" />
	<link rel="stylesheet" type="text/css" href="libraries/BeautifyMarker/leaflet-beautify-marker-icon.css" />
	<link rel="stylesheet" type="text/css" href="css/general.css" />
	<link rel="stylesheet" type="text/css" href="css/mapcss.css" />
	<script type="text/javascript" src="libraries/JQuery/JQuery.js"></script>
	<script type="text/javascript" src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="libraries/leaflet/leaflet.js"></script>
	<script type="text/javascript" src="libraries/BeautifyMarker/leaflet-beautify-marker-icon.js"></script>
	<script type="text/javascript" src="script/mapscript.js"></script>

</head>


<body>

	<nav class="col-md-10 col-md-offset-1 navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="homepage.php">We Connect</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li>
					<a href="homepage.php"><span><img src="data/images/ServerImg/home.png" height="20" width="20" alt="homeIcon"></span> Home</a>
				</li>
				<li class="active">
					<a href="map.php"><span><img src="data/images/ServerImg/maps.jpg" height="20" width="20" alt="mapIcon"></span> Mappa</a>
				</li>
				<?php if (isset($_SESSION['id_user'])) {echo('
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span>
						<img src="data/images/ServerImg/chat.png" height="20" width="20" alt="chatIcon"></span> Chat
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="chatAll.php">Chat all_users</a></li>');
						if ($_SESSION['plus'] === 1) {
							echo ('<li><a href="chatPlus.php">Chat Plus</a></li>');
						}
					echo('
					</ul>
				</li>
				');} ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
			<?php if (isset($_SESSION['id_user'])) {echo('
				<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.
						$_SESSION['username'] .
					'<span class="caret"></span></a>
					<ul class="dropdown-menu">');
					if($_SESSION['plus'] === 1){ echo('
					<li><a href="gestionePuntiPlus.php"><span>
						<img src="data/images/ServerImg/gestisci.png" height="20" width="20" alt="gestisciIcon"></span> I miei punti wifi</a></li>
					<li><a href="addPointForm.php"><span>
						<img src="data/images/ServerImg/plus.jpg" height="20" width="20" alt="plusicon"></span>Aggiungi Punto</a></li>
					<li><div class="divider"></div></li>
					<li><a href="php/logout.php"><span>
					<img src="data/images/ServerImg/logout.jpg" height="20" width="20" alt="logouticon"></span> Logout</a></li>

					');} else { echo('
					<li><a href="php/logout.php"><span>
					<img src="data/images/ServerImg/logout.jpg" height="20" width="20" alt="logouticon"></span> Logout</a></li>

					');}echo('
					</ul>
				</li>

			');} else {echo('
				<li><a href="loginPage.php"><img src="data/images/ServerImg/login1.png" height="20" width="20" alt="loginIcon"> Login</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span>
						<img src="data/images/ServerImg/signup.png" height="20" width="20" alt="chatIcon"></span>  Registrati
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="registerPage.php"><span>
						<img src="data/images/ServerImg/utente.png" height="20" width="20" alt="utenteIcon"></span> Come utente base</a></li>
						<li><a href="registerPagePlus.php"><span>
						<img src="data/images/ServerImg/utente.png" height="20" width="20" alt="utenteIcon"></span> Come utente plus</a></li>
					</ul>
				</li>
			');} ?>

			</ul>
		</div>
		</div>
	</nav>

	<div class="col-md-10 col-md-offset-1 container-fluid bigContainer">
		<div id="mapid"></div>
	</div>

	<script>
		loadMap();

	</script>

</body>

</html>
