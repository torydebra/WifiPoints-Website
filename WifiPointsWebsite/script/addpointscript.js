"use strict"

/* cambia stile css da "rosso" a "verde" o viceversa*/
function changeStyle(check, label) {
	var divName = "#div" + label;
	if (check === 'true') {
		$(divName).removeClass('has-error');
		$(divName).addClass('has-success');

	} else {
		$(divName).removeClass('has-success');
		$(divName).addClass('has-error');
	}
}

function validateName(){
	$(document).ready(function () {

		$("#name").keyup(function () {
			var name = $(this).val();

			if (name.length < 3) {
				changeStyle('false', 'Name');
				$("#messageName").html("Nome deve essere lungo almeno 3 caratteri");

			} else if (name.length > 100) {
				changeStyle('false', 'Name');
				$("#messageName").html("Nome deve essere lungo al massimo 100 caratteri");

			} else { //se supera tutti i controlli guarda nel db se è già presente un nome uguale (case insensitive)

				$("#messageName").html('checking...');

				jQuery.post('php/namePoint-check.php', $("#name").serialize())
					.done(function (data) {
						var data = data.trim(); //echo in namePoint-check.php ci mette uno spazio davanti

						if (data === 'taken') {
							changeStyle('false', 'Name');
							$("#messageName").html('Username già in uso');
						} else {
							changeStyle('true', 'Name');
							$("#messageName").html('');
						}
				});
			}
		});
	});
}

function validateAddress(){
	$(document).ready(function () {

		$("#address").blur(function () {
			var address = $('#address')[0]['value'];
			//console.log(address);

			//non valida indirizzi, solo vede che non ci siano ; e , che mi danno problemi
			//perchè formato è "via,civico;città"
			var regaddress = /^[^,;]+$/;

			if(address.length === 0){
				changeStyle('false', 'Address')
				$("#messageAddress").html("Indirizzo obbligatorio");

			} else if (!regaddress.test(address)){
				changeStyle('false', 'Address');
				$("#messageAddress").html("indirizzo non può contenere , e ;");

			} else {
				changeStyle('true', 'Address');
				$("#messageAddress").html("");
			}

		});
	});
}

function validateTelephone(){
	$(document).ready(function () {

		$("#telefono").blur(function () {
			var tel = $(this).val();
			//solo numeri oppure + seguito da numeri
			var regTel = /^[+]|^[0-9]+$/

			// se cancello tutto numero metto stile neutro visto che tel è facoltativo
			if(tel.length === 0){
				$("#divTel").removeClass("has-error");
				$("#divTel").removeClass("has-success");
				$("#messageTel").html("");

			} else if (tel.length < 7){
				changeStyle('false', 'Tel');
				$("#messageTel").html("Telefono non valido");

			} else if(! regTel.test(tel)){
				changeStyle('false', 'Tel');
				$("#messageTel").html("Telefono non valido");

			} else{
				changeStyle('true', 'Tel');
				$("#messageTel").html("");
			}

		});
	});
}

function getCoords() {

	//ogni volta che uno tra indirizzo civico e città perde il focus si chiama locIq
	//questo perchè se uno ritorna su uno dei tre campi per correggere...
	$('#addressRow').focusout(function(){
		var urlGeo =
		"https://locationiq.org/v1/search.php?key=8b5cb724c5ca786941d0&format=json" +
		"&street=" + $('#addressNumber').val() + "%20" + $('#address').val() +
		"&city=" + $('#city').val() + "&country=italy"; //solo italia per il momento

		jQuery.get(urlGeo).done(function (data) {
			//assumiamo che il primo risultato sia quello giusto
			//console.log(data[0]);

			$('#xCoord').val(data[0]["lon"]);
			$('#yCoord').val(data[0]["lat"]);

		});
	});
}
