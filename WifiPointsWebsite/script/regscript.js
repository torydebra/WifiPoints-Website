"use strict"

// var globale, tutti i check devono essere true per far si che il bottone submit si attivi
var enableSubmitButton = ['false', 'false', 'false', 'false'];


function enableButton(enableSubmitButton) {

	if (enableSubmitButton[0] === 'true' &&
		enableSubmitButton[1] === 'true' &&
		enableSubmitButton[2] === 'true' &&
		enableSubmitButton[3] === 'true') {

		$("#submitButton").removeClass('disabled');
		$("#submitButton").prop("disabled", false);

	} else {
		$("#submitButton").addClass('disabled');
		$("#submitButton").prop("disabled", true);
	}
}


// check = true : input valido,     check = false : input non valido
// label : nome dell'input (per le classi css dei div) sono: Username, Email, Password, Password2
function changeStyle(check, label) {
	var divName = "#div" + label;
	var iconName = "#icon" + label;
	if (check === 'true') {
		$(divName).removeClass('has-error');
		$(divName).addClass('has-success');
		$(iconName).removeClass('glyphicon-remove');
		$(iconName).addClass('glyphicon-ok');

	} else {
		$(divName).removeClass('has-success');
		$(divName).addClass('has-error');
		$(iconName).removeClass('glyphicon-ok');
		$(iconName).addClass('glyphicon-remove');
	}
}


function validateReg() {

	//username solo lettere, numeri, underscore o trattino
	var reusername = /^[A-Za-z0-9_-]+$/;

	//valida il 99.99% email reali
	//var remail = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-			]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

	var remail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]				{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	var repassword = /[a-z]/;
	var re2password = /[A-Z]/;
	var re3password = /[0-9]/;

	// se pagina caricata completamente
	$(document).ready(function () {

		//check input username
		$("#username").keyup(function () {
			var username = $(this).val();

			if (username.length < 3) {
				changeStyle('false', 'Username');
				$("#messageUsername").html("L'username deve essere lungo almeno 3 caratteri");
				enableSubmitButton[0] = 'false';
				enableButton(enableSubmitButton);

			} else if (username.length > 25) {
				changeStyle('false', 'Username');
				$("#messageUsername").html("L'username deve essere lungo al massimo 25 caratteri");
				enableSubmitButton[0] = 'false';
				enableButton(enableSubmitButton);

			} else if (!reusername.test(username)) {
				changeStyle('false', 'Username');
				$("#messageUsername").html("Username può contenere solo lettere non accentate, numeri, underscore(_) o trattino (-)");
				enableSubmitButton[0] = 'false';
				enableButton(enableSubmitButton);

			} else { //se supera tutti i controlli guarda nel db se è già presente un nome uguale (case 				   insensitive)

				$("#messageUsername").html('checking...');

				jQuery.ajax({
					type: 'POST',
					url: 'php/username-check.php',
					data: $("#username").serialize(), //econde as string
					success: function (data) {

						var data = data.trim(); //echo in usernamecheck.php ci mette uno spazio davanti

						if (data === 'taken') {
							changeStyle('false', 'Username');
							$("#messageUsername").html('Username già in uso');
							enableSubmitButton[0] = 'false';
							enableButton(enableSubmitButton);
						} else {
							changeStyle('true', 'Username');
							$("#messageUsername").html('');
							enableSubmitButton[0] = 'true';
							enableButton(enableSubmitButton);
						}
					}
				});
			}
		});

		//check input email
		$("#email").keyup(function () {
			var email = $(this).val();

			if (remail.test(email)) {
				$("#messageEmail").html('checking...');

				jQuery.ajax({
					type: 'POST',
					url: 'php/email-check.php',
					data: $("#email").serialize(),
					success: function (data) {
						var data = data.trim(); //echo in usernamecheck.php ci mette uno spazio davanti

						if (data === 'taken') {
							changeStyle('false', 'Email');
							$("#messageEmail").html('Email già in uso');
							enableSubmitButton[1] = 'false';
							enableButton(enableSubmitButton);

						} else {
							changeStyle('true', 'Email');
							$("#messageEmail").html('');
							enableSubmitButton[1] = 'true';
							enableButton(enableSubmitButton);
						}
					}
				});

			} else {
				changeStyle('false', 'Email');
				$("#messageEmail").html('Email non valida');
				enableSubmitButton[1] = 'false';
				enableButton(enableSubmitButton);
			}
		});

		//check input password
		$("#password").keyup(function () {

			var password = $(this).val();

			if (password.length < 8) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve essere lunga almeno 8 caratteri");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (!repassword.test(password)) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve contenere almeno una lettera minuscola");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (!re2password.test(password)) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve contenere almeno una lettera maiuscola");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (!re3password.test(password)) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve contenere almeno un numero");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (password != $("#password2").val()) {
				changeStyle('false', 'Password2');
				$("#messagePassword").html("");
				$("#messagePassword2").html("Password non coincidono");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else {
				changeStyle('true', 'Password');
				$("#messagePassword").html('');
				enableSubmitButton[2] = 'true';
				enableButton(enableSubmitButton);
			}
		});

		//check input repeat password
		$("#password2").keyup(function () {
			var password2 = $(this).val();
			var password = $("#password").val();

			if (password != password2) {
				changeStyle('false', 'Password2');
				$('#messagePassword2').html("Password non coincidono");
				enableSubmitButton[3] = 'false';
				enableButton(enableSubmitButton);
			}

			//se non vado nel primo if alua pass sono uguali, quindi errori li restituisco al primo
			//input della pass non alla repeat
			else if (password.length < 8) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve essere lunga almeno 8 caratteri");
				$('#messagePassword2').html("");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (!repassword.test(password)) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve contenere almeno una lettera minuscola");
				$('#messagePassword2').html("");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (!re2password.test(password)) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve contenere almeno una lettera maiuscola");
				$('#messagePassword2').html("");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);

			} else if (!re3password.test(password)) {
				changeStyle('false', 'Password');
				$("#messagePassword").html("Password deve contenere almeno un numero");
				$('#messagePassword2').html("");
				enableSubmitButton[2] = 'false';
				enableButton(enableSubmitButton);
			} else {
				changeStyle('true', 'Password2');
				$('#messagePassword2').html("");
				enableSubmitButton[3] = 'true';
				changeStyle('true', 'Password');
				$("#messagePassword").html('');
				enableSubmitButton[2] = 'true';
				enableButton(enableSubmitButton);
			}
		});

		//solo per registerPlus
		$("#name").keyup(function () {
			var name = $(this).val();
			if(name.length>0){
				changeStyle('true','Name');
				$('#messaggeName').html("");
			} else {
				changeStyle('false','Name');
				$('#messaggeName').html("Inserire un nome");
			}

		});

		$("#surname").keyup(function () {
			var surname = $(this).val();
			if(surname.length>0){
				changeStyle('true','Surname');
				$('#messaggeName').html("");
			} else {
				changeStyle('false','Surname');
				$('#messaggeName').html("Inserire un cognome");
			}
		});
	});
}
