<?php
session_start();
require("php/checkCookie.php");

//$_SESSION['id_user']= 1;// margiovanni

$_SESSION['id_punto'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY); //legge url dopo ?

// require qui impedisce la visualizzazione della pagina se non si è gestori del punto,
// essendo file pubblico, questa riga è cancellabile. In sto caso la pagina di modifica sarà visualizzabile
// ma la modifica verrà bloccata dall'actionUpdatePoint.php che è file non visibile.
require("php/checkUserEnabletoModify.php");
if($row !== 1){
	echo("non hai i permessi per modificare questo punto");
	echo('
		<p><a href="map.php">Return back
		<i class="fa fa-undo" aria-hidden="true"></i></a>
		</p> ');
	die;
}

?>

<!DOCTYPE html>
	<html lang=it>

	<head>
		<title>Aggiunta punto wifi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="libraries/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/general.css" />
		<link rel="stylesheet" type="text/css" href="css/formscss.css" />
		<link rel="stylesheet" type="text/css" href="css/addPointFormcss.css" />
		<script type="text/javascript" src="libraries/JQuery/JQuery.js"></script>
		<script type="text/javascript" src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="script/addpointscript.js"></script>
		<script type="text/javascript" src="script/modifypointscript.js"></script>
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
			<h1>Modifica Punto Wifi</h1>
			<h2 id="nome_punto_title"></h2>

			<div class="formContainer">
			<form action="php/actionUpdatePoint.php" method="post" name="pointForm" id="pointForm">

				<div class="sectionContainer">
				<div class="labelSection">Info Punto</div>
				<div class="row">
				<div id=divName class="col-sm-8 input-group has-feedback">
					<span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
					<label for="name" hidden>name</label>
					<!--label per accesibilità -->
					<input name="name" id="name" type="text" class="form-control" placeholder="Nome punto" maxlength="100" autocomplete="off" required>
				</div>
				<div class="col-sm-1"></div>
				<div class="col-sm-3 noPadding">
					<label for="tipo_attivita" hidden>Tipo Attivit&agrave;</label>
					<select class="form-control" name="tipo_attivita" id="tipo_attivita" required>
    					<option value="" disabled selected hidden>Tipo Attivit&agrave;</option>
    					<option value="freewifi">Freewifi</option>
   						<option value="bar">Bar</option>
    					<option value="fastfood">Fastfood</option>
    					<option value="gelateria">Gelateria</option>
    					<option value="hotel">Hotel</option>
    					<option value="ristorante">Ristorante</option>
    					<option value="isola">Isola</option>
    					<option value="altro">Altro</option>
 				 	</select>
				</div>
				</div>

				<div class="messageError" id="messageName"></div>

				<div class="blankDiv"></div>

				<div class="row" id="addressRow">
					<div id="divAddress" class="col-sm-7 input-group">
						<span class="input-group-addon"><i class="fa fa-location-arrow " aria-hidden="true"></i></span>
						<label for="address" hidden>Indirizzo</label>
						<!--label per accesibilità -->
						<input name="address" id="address" type="text" class="form-control" placeholder="Indirizzo (via)" maxlenght="100" autocomplete="off" required>
					</div>
					<div class="col-sm-2 input-group">
					<span class="input-group-addon"><i class="fa fa-location-arrow " aria-hidden="true"></i></span>
						<label for="addressNumber" hidden>Civico</label>
						<!--label per accesibilità -->
						<input name="addressNumber" id="addressNumber" type="number" class="form-control" placeholder="Civico" maxlenght="4" min="0" required>
					</div>
					<div class="col-sm-3 input-group">
						<span class="input-group-addon"><i class="fa fa-location-arrow " aria-hidden="true"></i></span>
						<label for="city" hidden>Citt&agrave;</label>
						<!--label per accesibilità -->
						<input name="city" id="city" type="text" class="form-control" placeholder="Città" maxlenght="100" required>

					</div>
					<input type="hidden" name="xCoord" id="xCoord" value="" />
					<input type="hidden" name="yCoord" id="yCoord" value="" />
				</div>
				<div class="messageError" id="messageAddress"></div>
				</div>

				<div class="sectionContainer">
				<div class="labelSection">Info WiFi</div>
				<div class="row input-group">
					<span class="input-group-addon"><i class="fa fa-wifi" aria-hidden="true"></i></span>
					<label for="ssid" hidden>SSID wifi</label>
					<!--label per accesibilità -->
					<input name="ssid" id="ssid" type="text" class="form-control" placeholder="SSID wifi (facoltativo)" maxlenght="32">
				</div>
				<p class="form-text text-muted"> Ssid sarà visibile sulla pagina del punto Wifi. Se non desideri ciò, lascia vuoto</p>

				<div class="blankDiv"></div>

				<div class="form-group row">
					<div class="col-sm-6">Tipo Wifi
						<div>
							<!--div per far rimanere sopra tipo wifi e sotto i radio-->
							<label class="radio-inline">
							<!--required nei radio basta solo in uno del gruppo-->
							<input type="radio" name="tipowifi" id="tipowififree" value="free" required>Libero
						</label>
							<label class="radio-inline">
							<input type="radio" name="tipowifi" id="tipowificonsume" value="consume">Solo per clienti
						</label>
							<label class="radio-inline">
							<input type="radio" name="tipowifi" id="tipowifipay" value="pay">A Pagamento
						</label>
						</div>
					</div>
					<div class="col-sm-2"></div>
					<div class="col-sm-4">Serve una password?
						<div>
							<label class="radio-inline"><input type="radio" name="passwordBool" id="passwordBool1" value=1 required>SI</label>
							<label class="radio-inline"><input type="radio" name="passwordBool" id="passwordBool0" value=0>NO</label>
						</div>
					</div>
				</div>
				</div>


				<div class="sectionContainer">
				<div class="labelSection">Info aggiuntive (facoltative)</div>

				<label for="description" hidden>Descrizione</label>
				<textarea class="form-control" rows="5" name="description" id="description" placeholder="Descrizione (10-5000 caratteri)" maxlength="5000" minlength="10"></textarea>


				<div id="divTel" class="input-group">
					<span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
					<label for="telefono" hidden>telefono</label>
					<!--label per accesibilità -->
					<input name="telefono" id="telefono" type="text" class="form-control" placeholder="Telefono" maxlength="15" autocomplete="off">
				</div>

				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-bookmark" aria-hidden="true"></i></span>
					<label for="sito" hidden>sito</label>
					<!--label per accesibilità -->
					<input name="sito" id="sito" type="text" class="form-control" placeholder="sito web" maxlenght="100">
				</div>

				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-info-circle " aria-hidden="true"></i></span>
					<label for="social" hidden>social</label><!--label per accesibilità -->
					<input name="social" id="social" type="text" class="form-control" placeholder="contatto social (facebook, twitter...)" maxlenght="100">
				</div>
				</div>

				<?php   if(isset($_GET['error']) && $_GET['error'] == 1){
						echo ('
							<h5 class="messageError" id="messageErrorPhp"> Errore: riprova</h5>
							'); }
				?>

				<button id=submitButton class="btn btn-success btn-lg" type="submit"><i class="fa fa-share-square-o" aria-hidden="true"></i>Invia</button>
				<button class="btn btn-danger btn-lg" type="reset"><i class="fa fa-times" aria-hidden="true"></i>Reset</button>
			</form>

			<p><a href="homepage.php">Return back
			<i class="fa fa-undo" aria-hidden="true"></i></a>
			</p>
			</div>
		</div>
		<script>
			loadInfoPointToModify();
			validateNameModify();
			validateAddress(); //in addpointScript
			validateTelephone();
			getCoords();
		</script>
	</body>
</html>
