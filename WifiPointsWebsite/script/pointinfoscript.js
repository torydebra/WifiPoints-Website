"use_strict"
//funzioni sotto usate anche dai plus point (loadComments)

//diverso da load per il plus point perchè per il generic non sappiamo indirizzo
//quindi serve locationIq per reverse geocoding
function loadInfoPoint() {

	// prende quello che c'è dopo ? nell'url (passatogli tramite get)
	var id_punto = window.location.search.substr(1);
	//console.log(id_punto);
	$('#id_punto').val(id_punto);

	if (id_punto != '') {// se stringa non vuota

		jQuery.post("../../php/loadSinglePoint.php", {id_punto: id_punto})
			.done(function (wifiPoint) {

				$('#nome_punto').html(wifiPoint['nome_punto']);
				$('#tipo_attivita').append(wifiPoint['tipo_attivita']);
				//$('#coordY').html(wifiPoint['X(coordinate)']);
				//$('#coordX').html(wifiPoint['Y(coordinate)']);

				var urlGeo = "https://locationiq.org/v1/reverse.php?format=json&key=8b5cb724c5ca786941d0&lat=" +
					wifiPoint['Y(coordinate)'] +
					"&lon=" + wifiPoint['X(coordinate)'] +
					"&zoom=18&addressdetails=1";

				// ajax innestate perchè prima devono essere disponibile le coord dal nostro db
				jQuery.get(urlGeo).done(function (data) {
					console.log(data);
					if (data["address"]["road"] != null) {
						$('#address').html(data["address"]["road"]);
					}
					//a volte la via è in "pedestrian"
					else if (data["address"]["pedestrian"] != null) {
						$('#address').html(data["address"]["pedestrian"]);
					}
					$('#address').append(" ");
					$('#address').append(data["address"]["house_number"]);
				});

			});
	} else {
		window.location.replace("../../map.php");
	}
}


function loadComments() {
	var id_punto = window.location.search.substr(1);

	if (id_punto != '') {
		jQuery.post("../../php/loadComments.php", {id_punto: id_punto})
			.done(function (comments) {

				//commments sono in ordine dal più vecchio al più nuovo
				// metto il primo in cima e gli altri sotto
				for ($i = comments.length - 1; $i >= 0; $i--) {

					$('#comments').append(
						'<li class="cmmnt"> <div class="cmmnt-content"> <div class="comment-header">' +
						comments[$i]["username"] +
						'<span class="pubdate">  -  ' +
						timeSince(comments[$i]["data_commento"]) +
						'</span></div><p>' +
						comments[$i]["testo_commento"] +
						'</p></div></li>');
				}
			});

	} else {
		window.location.replace("../../map.php");
	}
}

/* commentDate: un tipo timestamp (dal db)*/
function timeSince(commentDate) {

	var then =  new Date(commentDate+"+02:00"); //UTC+2: timezone del server

	//now è salvata con timezone del browser, ma con la getTime js converte tutto in utc
	var now = new Date();
	var secondsPast = (now.getTime() - then.getTime()) / 1000;

	if (secondsPast < 60) {
		secondsInt = parseInt(secondsPast);
		if (secondsInt === 1){
			return secondsInt + ' secondo fa'
		}
		else {
			return secondsInt + ' secondi fa'
		}
	}
	//oltre un minuto
	if (secondsPast < 3600) {
		secondsInt = parseInt(secondsPast/60);
		if (secondsInt === 1){
			return secondsInt + ' minuto fa'
		}
		else {
			return secondsInt + ' minuti fa'
		}
	}
	//oltre un'ora
	if (secondsPast <= 86400) {
		secondsInt = parseInt(secondsPast/3600);
		if (secondsInt === 1){
			return secondsInt + ' ora fa'
		}
		else {
			return secondsInt + ' ore fa'
		}
	}
	//oltre un giorno
	if (secondsPast <= 2592000) {
		secondsInt = parseInt(secondsPast/86400);
		if (secondsInt === 1){
			return secondsInt + ' giorno fa'
		}
		else {
			return secondsInt + ' giorni fa'
		}
	}
	//oltre un mese (30 giorni)
	if (secondsPast <= 31536000){
		secondsInt = parseInt(secondsPast/2592000);
		if (secondsInt === 1){
			return secondsInt + ' mese fa'
		}
		else {
			return secondsInt + ' mesi fa'
		}
	}
	//oltre un anno
	else {
		secondsInt = parseInt(secondsPast/31536000);
		if (secondsInt === 1){
			return secondsInt + ' anno fa'
		}
		else {
			return secondsInt + ' anni fa'
		}
	}
}
