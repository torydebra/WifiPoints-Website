<?php
session_start();
if(!isset($_SESSION['id_user'])){ //se è loggato è inutile andare a vedere i cookie
	require("php/checkCookie.php");
}
?>
<!DOCTYPE html>
<html lang=it>
	<head>
		<title>We connect Genova</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="utf-8" />
		<link rel="stylesheet" href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/general.css">
		<link rel="stylesheet" href="css/home1.css">
		<script src="libraries/JQuery/JQuery.js"></script>
		<script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
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
				<li class="active">
					<a href="homepage.php"><span><img src="data/images/ServerImg/home.png" height="20" width="20" alt="homeIcon"></span> Home</a>
				</li>
				<li>
					<a href="map.php"><span><img src="data/images/ServerImg/maps.jpg" height="20" width="20" alt="mapIcon"></span> Mappa</a>
				</li>
				<?php if (isset($_SESSION['id_user'])) {echo('
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span>
						<img src="data/images/ServerImg/chat.png" height="20" width="20" alt="chatIcon"></span> Chat
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="chatAll.php"><span>
						<img src="data/images/ServerImg/live.jpg" height="20" width="20" alt="gestisciIcon"></span> Chat all_users</a></li>');
						if ($_SESSION['plus'] === 1) {
							echo ('<li><a href="chatPlus.php"><span>
						<img src="data/images/ServerImg/live.jpg" height="20" width="20" alt="gestisciIcon"></span> Chat users-plus</a></li>');
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
						<img src="data/images/ServerImg/plus.jpg" height="20" width="20" alt="plusicon"></span> Aggiungi Punto</a></li>
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
		<div class="container homePhoto">
			<img src="data/images/ServerImg/HomefotoGenova1.jpg">
		</div>
		<div class="container mainDiv">
			<h1>ABOUT US</h1>
			<h4>WE CONNECT ti consente di stare sempre e ovunque connesso. Offriamo un servizio per cercare i punti wifi di Genova e dintorni (e anche altre città) a cui connetterti anche essendo fuori casa. I punti sono categorizzati in base alla attività (bar, ristoranti, fastfood ecc...) in modo da migliorare l'esperienza dell'utente. In più gestiamo un sistema di commenti per valutare ogni punto wifi</h4>
			<div class="container userbase">
				<h4>Come utente base puoi:</h4>
				<p>Cercare i punti Wifi sulla mappa</p>
				<p>Commentare i punti wifi</p>
				<p>Usufruire della chat</p>
				<p> </p>
				<h5><a href="registerPage.php">Registrati come utente base</a></h5>
			</div>
			<div class="container userplus">
				<h4>Come utente plus in più puoi:</h4>
				<p>Usufruire di una chat solo per user plus</p>
				<p>Inserire il tuo punto Wifi</p>
				<p>Gestire i tuoi punti wifi</p>

				<h5><a href="registerPagePlus.php">Registrati come utente plus</a></h5>
			</div>
			<p class="bottomCopyright"><span>
			<img src="data/images/ServerImg/copyright.png" height="30" width="30" alt="chatIcon" align="center"></span> Copyrights Cyrus &amp; Davide, Luglio 2017</p>
		</div>
	</div>
	</body>

</html>
