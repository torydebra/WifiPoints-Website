"use_strict"

//per sapere da chi è gestito il punto
function loadInfoUserPlus() {
	var id_punto = window.location.search.substr(1);
	$('#id_punto').val(id_punto);

	if (id_punto != '') {
		jQuery.post("../../php/loadUserPlus.php", {id_punto: id_punto})
			.done(function (user) {
				//console.log(user);
				if(user === null){ //allora è un punto non gestito bisogna andare in genericPoint.php
					window.location.replace("genericPoint.php?" + id_punto);
				}
				$('#userPlus').append(" " + user['username']);
			});
	} else {
		window.location.replace("../../map.php");
	}
}

function loadInfoPlusPoint() {

	// prende quello che c'è dopo ? nell'url (passatogli tramite get)
	var id_punto = window.location.search.substr(1);
	//console.log(id_punto);

	//se in url è presente info su quale punto visualizzare (dopo il ?);
	if (id_punto != '') {

		//url immagine non c'è in db tanto ogni punto ha la sua cartella
		//chiamata <id_punto> con dentro la foto chiamata <id_punto>.png
		var urlImage = "../images/wifipoints/" + id_punto + "/" + id_punto + ".png";

		$.ajax({
    		url: urlImage,
    		type:'HEAD', //richiesta veloce legge solo header tanto
					 // ci serve sapere solo se file esiste
    		success: function()
    		{
        		$('#imgPoint').attr("src", urlImage);
				$('#imgPoint').attr("src", urlImage);
				$('#imgPoint').removeAttr('hidden');
    		}
		});

		$('#id_punto').val(id_punto);

		//chiamata a stesso php usato da pointinfoscript: post restituisce tutte le info
		//che siano null o meno
		jQuery.post("../../php/loadSinglePoint.php", {id_punto: id_punto})
			.done(function (wifiPoint) {

				$('#nome_punto').html(wifiPoint['nome_punto']);
				$('#tipo_attivita').append(wifiPoint['tipo_attivita']);
				//$('#coordY').html(wifiPoint['X(coordinate)']);
				//$('#coordX').html(wifiPoint['Y(coordinate)']);

				var addressTot = wifiPoint['indirizzo'];
				var address = addressTot.substring(0, addressTot.lastIndexOf(","));
				var addressNumber =
					addressTot.substring((addressTot.lastIndexOf(",") + 2), addressTot.lastIndexOf(";"));
				var addressFormatted = address + " " + addressNumber;
				var city = addressTot.substring((addressTot.lastIndexOf(";") + 2), addressTot.length);

				$('#address').html(addressFormatted);
				$('#city').html(city);

				if (wifiPoint['tipo_accesso_wifi'] !== null) { //teoricamente mai null: info obbligatoria
					switch (wifiPoint['tipo_accesso_wifi']) {
						case "free":
							$('#tipoWifi').append(" libero");
							break;
						case "consume":
							$('#tipoWifi').append(" solo per clienti");
							break;
						case "pay":
							$('#tipoWifi').append(" a pagamento");
							break;
					}
					$('#tipoWifi').removeAttr('hidden');
				}
				if (wifiPoint['password_wifi_bool'] !== null) { //teoricamente mai null

					if(wifiPoint['password_wifi_bool'] === 0){
						$('#passwordBool').html("Senza Password");
					} else {
						$('#passwordBool').html("Con Password");
					}
					$('#passwordBool').removeAttr('hidden');
				}

				//se info non è presente non visualizzerà nulla
				if (wifiPoint['ssid_wifi'] !== null) {
					$('#ssid').append(wifiPoint['ssid_wifi']);
					$('#ssid').removeAttr('hidden');
				}
				if (wifiPoint['descrizione'] !== null) {
					$('#description').html(wifiPoint['descrizione']);
					$('#descrTitle').removeAttr('hidden');
					$('#description').removeAttr('hidden');
				}
				if (wifiPoint['telefono'] !== null) {
					$('#telephone').append(wifiPoint['telefono']);
					$('#telephone').removeAttr('hidden');
				}
				if (wifiPoint['sito'] !== null) {
					$('#site').append(wifiPoint['sito']);
					$('#site').removeAttr('hidden');
				}
				if (wifiPoint['social'] !== null) {
					$('#social').append(wifiPoint['social']);
					$('#social').removeAttr('hidden');
				}
			});
	} else {
		window.location.replace("../../map.php");
	}
}
