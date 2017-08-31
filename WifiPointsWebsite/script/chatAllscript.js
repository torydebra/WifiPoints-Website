"use strict"

function load(){

		$.ajax({
		url : "php/loadchatAll.php",
		type : "GET",
		success : function(data){
			$('#messaggi').html(data);
	 	}
	});
}
load();
