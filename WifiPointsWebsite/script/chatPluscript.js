"use strict"

function load() {
	$.ajax({
		url: "php/loadchatPlus.php",
		type: "GET",
		success: function (data) {
			$('#messaggi').html(data);
		}
	});
}
load();
