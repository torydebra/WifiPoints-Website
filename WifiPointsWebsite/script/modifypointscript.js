"use strict"
//modifica informazioni per punto gestito
function loadInfoPointToModify() {

	// prende quello che c'è dopo ? nell'url (passatogli tramite get)
	var id_punto = window.location.search.substr(1);
	$('#id_punto').val(id_punto);

	if (id_punto != '') {// se stringa non vuota
		jQuery.post("php/loadSinglePoint.php", { id_punto: id_punto })
			.done(function (wifiPoint) {

				//console.log(wifiPoint);

				$('#nome_punto_title').html(wifiPoint['nome_punto']);
				$('#name').val(wifiPoint['nome_punto']);
				$('#tipo_attivita').val((wifiPoint['tipo_attivita']));

				// formato indirizzo:  "via, civico; città"
				var addressTot = wifiPoint['indirizzo'];
			 	var address = addressTot.substring(0, addressTot.lastIndexOf(","));
			 	var addressNumber =
					addressTot.substring((addressTot.lastIndexOf(",")+2), addressTot.lastIndexOf(";"));
				var city = addressTot.substring((addressTot.lastIndexOf(";")+2), addressTot.length);

				$('#address').val(address);
				$('#addressNumber').val(addressNumber);
				$('#city').val(city);

				$('#xCoord').val(wifiPoint["X(coordinate)"]);
				$('#yCoord').val(wifiPoint["Y(coordinate)"]);

				// seleziona uno dei tre radio tipowifi<>
				$('#tipowifi'+wifiPoint['tipo_accesso_wifi']).attr("checked", "checked");

				$('#passwordBool'+wifiPoint['password_wifi_bool']).attr("checked", "checked");

				if(wifiPoint['ssid_wifi'] !== null){
					$('#ssid').val(wifiPoint['ssid_wifi']);
				}
				if(wifiPoint['descrizione'] !== null){
					$('#description').val(wifiPoint['descrizione']);
				}
				if(wifiPoint['telefono'] !== null){
					$('#telefono').val(wifiPoint['telefono']);
				}
				if(wifiPoint['sito'] !== null){
					$('#sito').val(wifiPoint['site']);
				}
				if(wifiPoint['social'] !== null){
					$('#social').val(wifiPoint['social']);
				}
			});
	} else {// se cancello da barra indirizzi l'id dopo ? reindirizza visto che non si sa quale punto caricare
		window.location.replace("../../map.php");
	}
}


function validateNameModify(){

	$(document).ready(function () {

		var oldName = $('#nome_punto_title').html();

		$("#name").keyup(function () {

			var name = $(this).val();

			if (name.length < 3) {
				changeStyle('false', 'Name');
				$("#messageName").html("Nome deve essere lungo almeno 3 caratteri");

			} else if (name.length > 100) {
				changeStyle('false', 'Name');
				$("#messageName").html("Nome deve essere lungo al massimo 100 caratteri");

			} else { //se supera tutti i controlli guarda nel db se è già presente un nome uguale (case insensitive per il collation del db)

				$("#messageName").html('checking...');

				jQuery.post('php/namePoint-check.php', $("#name").serialize())
					.done(function (data) {
						var data = data.trim(); //echo in namePoint-check.php ci mette uno spazio davanti

						//da controllare anche che nome non sia lo stesso di prima (oldname)
					    // se è lo stesso ovviamente è valido
						if (data === 'taken' && name !== oldName) {
							changeStyle('false', 'Name');
							$("#messageName").html('Nome già in uso');
						} else {
							changeStyle('true', 'Name');
							$("#messageName").html('');
						}
				});
			}
		});
	});
}
