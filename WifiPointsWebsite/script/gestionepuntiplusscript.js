"use strict"

function loadElencoPoints() {

	$(document).ready(function () {

		//php legge id user dalla sessione
		jQuery.post("php/loadElencoPointsPlus.php")
			.done(function (elencoPoints) {
				//console.log(elencoPoints.length);

				for (var i = 0; i < elencoPoints.length; i++) {

					//addres da db è: "via,civico;città"
					var addressTot = elencoPoints[i]['indirizzo'];
					var address = addressTot.substring(0, addressTot.lastIndexOf(","));
					var addressNumber =
						addressTot.substring((addressTot.lastIndexOf(",") + 2), addressTot.lastIndexOf(";"));
					var city = addressTot.substring((addressTot.lastIndexOf(";") + 2), addressTot.length);
					var addressFormatted = address + " " + addressNumber + " " + city;

					$('#pointsContainer').append(
						'<li class="liPoints"><span class="nomePunto"><a href="data/wifipoints/plusPoint.php?'+
						elencoPoints[i]["id_punto"]
						+'">' +
						elencoPoints[i]["nome_punto"]
						+ '</a></span><span class="indirizzoPunto">'+
						addressFormatted
						+'</span></li>');

				}
			});
//		.fail( function (request, status, error) {
//	        console.log(error);
//	    });
	});
}
