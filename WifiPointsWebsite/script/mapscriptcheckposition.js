"use strict"
//funzioni chiamate da checkPositionAddedPoint e da plusPoint

var marker; //serve global così la setnewcoords può leggere le coordinate

//draggable true:  funzione chiamata da checkPosition, marker movibile
//draggable false: funzione chiamata da plusPoint, marker non movibile
function loadMapCheckPosition(draggable) {
	var mymap = generateMap();
	putPointonMap(mymap, draggable);
}

function generateMap() {

	//set view dopo per centrare sul punto caricato
	var mymap = L.map('mapid');

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox.streets', //cambiare questo per cambiare tiles
		accessToken: 'pk.eyJ1IjoidG9yeWRlYnJhIiwiYSI6ImNqMnVuamx1NzAwZ2gycW1yZnB6MXd2ZmMifQ.xAZkzt5avaLRQ5GBXuufCQ'
	}).addTo(mymap);

	//	var marker = L.marker([44.406277, 8.932543]).addTo(mymap);
	//	var marker2 = L.marker([44.407062, 8.932161]).addTo(mymap);
	//	marker.bindPopup("<b>Bar Berto</b><br>bar<br>unknown manager for this point");
	//	marker2.bindPopup("<b>Capitan Baliano</b><br>bar<br>unknown manager for this point");

	return mymap;
}

function putPointonMap(mymap, draggable) {

	//se draggrable allora richeista arriva dal check position
	//altrimenti arriva da pag plusPoint che è in sottocartelle

	if(draggable === "true"){
		var url = "php/"
	}else{
		var url = "../../php/"
	}

	jQuery.post(url+"loadPointcheckposition.php").done(function (wifiPoint) {
		var freewifi = L.layerGroup();
		var bar = L.layerGroup();
		var fastfood = L.layerGroup();
		var gelaterie = L.layerGroup();
		var hotel = L.layerGroup();
		var restaurants = L.layerGroup();
		var isole = L.layerGroup();
		var other = L.layerGroup();

		var options;
		//console.log(wifiPoint);

		//coordinate per il tipo Point sono al contrario
		var yCoord = wifiPoint['X(coordinate)'];
		var xCoord = wifiPoint['Y(coordinate)'];
		var tipo_attivita = wifiPoint['tipo_attivita'];

		//servono due switch perchè prima bisogna settare l'icona, poi creare il marker e poi aggiungere il marker
		//al layer
		switch (tipo_attivita) {
			case 'freewifi':
				options = {
					prefix: 'fa',
					icon: 'wifi',
					iconShape: 'marker',
					borderColor: '#FF0000',
					textColor: '#FF0000'
				};
				break;
			case 'bar':
				options = {
					prefix: 'fa',
					icon: 'coffee',
					iconShape: 'marker',
					borderColor: '#00CC00',
					textColor: '#00CC00'

				};
				break;
			case 'fastfood':
				options = {
					prefix: 'fa',
					icon: 'wifi',
					iconShape: 'marker',
					borderColor: '#0000FF',
					textColor: '#0000FF'
				};
				break;
			case 'gelateria':
				options = {
					prefix: 'fa',
					icon: 'wifi',
					iconShape: 'marker',
					borderColor: '#FFFF66',
					textColor: '#FFFF66'
				};
				break;
			case 'hotel':
				options = {
					prefix: 'fa',
					icon: 'bed',
					iconShape: 'marker',
					borderColor: '#4286f4',
					textColor: '#4286f4'
				};
				break;
			case 'ristorante':
				options = {
					prefix: 'fa',
					icon: 'cutlery',
					iconShape: 'marker',
					borderColor: '#FF6600',
					textColor: '#FF6600'
				};
				break;
			case 'isola':
				options = {
					prefix: 'fa',
					icon: 'wifi',
					iconShape: 'marker',
					borderColor: '#FFCCFF',
					textColor: '#FFCCFF'
				};
				break;
			default: //tipologia altro o test
				options = {
					prefix: 'fa',
					icon: 'eercast',
					iconShape: 'marker',
					borderColor: '#000000',
					textColor: '#000000'
				};
		}

		//formato indirizzo in db: "via, civico; città"
		var addressTot = wifiPoint['indirizzo'];
		var address = addressTot.substring(0, addressTot.lastIndexOf(","));
		var addressNumber =
			addressTot.substring((addressTot.lastIndexOf(",")+1), addressTot.lastIndexOf(";"));
		var addressFormatted = address + addressNumber;

		if (draggable === 'true'){
			marker = new L.marker([xCoord, yCoord], {
				draggable: true,
				icon: L.BeautifyIcon.icon(options)
			}).bindPopup("<a href='#'>" +
				wifiPoint['nome_punto'] + "</a><br><p>" +
				addressFormatted + "</p>");
		} else {
			marker = new L.marker([xCoord, yCoord], {
				draggable: false,
				icon: L.BeautifyIcon.icon(options)
			}).bindPopup("<a href='#'>" +
				wifiPoint['nome_punto'] + "</a><br><p>" +
				addressFormatted + "</p>");
		}

		switch (tipo_attivita) {
			case 'freewifi':
				freewifi.addLayer(marker).addTo(mymap);
				break;
			case 'bar':
				bar.addLayer(marker).addTo(mymap);
				break;
			case 'fastfood':
				fastfood.addLayer(marker).addTo(mymap);
				break;
			case 'gelateria':
				gelaterie.addLayer(marker).addTo(mymap);
				break;
			case 'hotel':
				hotel.addLayer(marker).addTo(mymap);
				break;
			case 'ristorante':
				restaurants.addLayer(marker).addTo(mymap);
				break;
			case 'isola':
				isole.addLayer(marker).addTo(mymap);
				break;
			default:
				other.addLayer(marker).addTo(mymap);
		}

		var overlayMaps = {
			"Freewifi": freewifi,
			"Bar": bar,
			"Fastfood": fastfood,
			"Gelaterie": gelaterie,
			"Hotel": hotel,
			"Restaurants": restaurants,
			"Isola": isole,
			"Altro": other
		};

		//primo argo per cambiare tiles
		L.control.layers(null, overlayMaps).addTo(mymap);
		mymap.setView([xCoord, yCoord], 16);

	});
//		.fail( function (request, status, error) {
//	        alert(request.responseText);
//	    });
}


//prende nuova posizione del marker e salva le coord negli input hidden.
//se vengono modificati a mano per injection, ci pensa il php action a validare.
function setNewCoords(){
	$('#xCoord').val(marker.getLatLng()['lng']);
	$('#yCoord').val(marker.getLatLng()['lat']);

	return true;
}
