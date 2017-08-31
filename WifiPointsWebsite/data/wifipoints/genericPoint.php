<?php
session_start();
if(!isset($_SESSION['id_user'])){ //se è loggato è inutile andare a vedere i cookie
	require("../../php/checkCookie.php");
	if(!isset($_SESSION['id_user'])){ //per reindirizzare qui se faccio il login/reg da qui
		$_SESSION["originurl"] = $_SERVER['REQUEST_URI'];
	}
}
?>
<!DOCTYPE html>
	<html lang=it>

	<head>
		<title>Wifi Point</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
		<link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="../../css/general.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/genericPointcss.css"/>
		<link rel="stylesheet" type="text/css" href="../../css/commentBoxcss.css" />
		<script type="text/javascript" src="../../libraries/JQuery/JQuery.js"></script>
		<script type="text/javascript" src="../../libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../script/pointinfoscript.js"></script>
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
					<a href="../../homepage.php"><span><img src="../images/ServerImg/home.png" height="20" width="20" alt="homeIcon"></span> Home</a>
				</li>
				<li>
					<a href="../../map.php"><span><img src="../images/ServerImg/maps.jpg" height="20" width="20" alt="mapIcon"></span> Mappa</a>
				</li>
				<?php if (isset($_SESSION['id_user'])) {echo('
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span>
						<img src="../images/ServerImg/chat.png" height="20" width="20" alt="chatIcon"></span> Chat
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="../../chatAll.php">Chat</a></li>');
						if ($_SESSION['plus'] === 1) {
							echo ('<li><a href="../../chatPlus.php">Chat Plus</a></li>');
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
					<li><a href="../../gestionePuntiPlus.php"><span>
						<img src="../images/ServerImg/gestisci.png" height="20" width="20" alt="gestisciIcon"></span> I miei punti wifi</a></li>
					<li><a href="../../addPointForm.php"><span>
						<img src="../images/ServerImg/plus.jpg" height="20" width="20" alt="plusicon"></span> Aggiungi Punto</a></li>
					<li><div class="divider"></div></li>
					<li><a href="../../php/logout.php"><span>
						<img src="../images/ServerImg/signup.png" height="20" width="20" alt="chatIcon"></span> Logout</a></li>

					');} else { echo('
					<li><a href="../../php/logout.php"><span>
						<img src="../images/ServerImg/signup.png" height="20" width="20" alt="chatIcon"></span> Logout</a></li>

					');}echo('
					</ul>
				</li>

			');} else {echo('
				<li><a href="../../loginPage.php"><img src="../images/ServerImg/login1.png" height="20" width="20" alt="loginIcon"> Login</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span>
						<img src="../images/ServerImg/signup.png" height="20" width="20" alt="chatIcon"></span>  Registrati
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="../../registerPage.php"><span>
						<img src="../images/ServerImg/utente.png" height="20" width="20" alt="utenteIcon"></span> Come utente base</a></li>
						<li><a href="../../registerPagePlus.php"><span>
						<img src="../images/ServerImg/utente.png" height="20" width="20" alt="utenteIcon"></span> Come utente plus</a></li>
					</ul>
				</li>
			');} ?>

			</ul>
		</div>
		</div>
	</nav>

	<div class="col-md-10 col-md-offset-1 container-fluid bigContainer">
			<h1 id='nome_punto'></h1>
			<h2 id='address'></h2>
			<h4 id='tipo_attivita'>Tipo attività: </h4>

			<div id="commentsContainer">
				<h3>Commenti</h3>
				<ul id="comments">

			<!--<li class="cmmnt">
						<div class="cmmnt-content">
							<div class="comment-header">username<span class="pubdate"> - datacommento</span></div>
							<p>testo commento</p>
						</div>
				</li>   -->

				</ul>
			</div>


			<?php if (isset($_SESSION["id_user"])) {?>

			<form action="../../php/actionSendComment.php" method="post" name="commentForm" id="commentForm">
				<div class="form-group">

					<!-- non importa se utente può modificare questo value, andrà a commentare un altro puntoWifi invece di quello della pagina attuale ma tanto potrebbe farlo comunque andando sulla pagina dell'altro punto		-->
					<!-- serve per inviare anche id_punto a actionSendComment -->
					<input type="hidden" name="id_punto" id="id_punto" value="" />
					<label for="comment">Commento:</label>
					<textarea class="form-control" rows="10" name="testo_commento" id="testo_commento" placeholder="10-5000 caratteri" maxlength="5000" minlength="10" required></textarea>
				</div>
				<div class="buttonContainer">
					<button id=commentButton type="submit" class="btn btn-primary btn-lg " type="submit">Invia</button>
				</div>
			</form>

			<?php } else {?>

			<h4>Loggati o Registrati per scrivere commenti<br>
				<a href="../../loginPage.php">Login </a>&nbsp;&nbsp;&nbsp;
				<a href="../../registerPage.php">Registrati</a>
			</h4>

			<?php } ?>

		</div>
		<script>
			loadInfoPoint();
			loadComments();
		</script>
	</body>
</html>
