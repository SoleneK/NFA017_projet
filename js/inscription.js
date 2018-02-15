$(function() {
	console.log('bip');
	$('[data-toggle="tooltip"]').tooltip();

	$('main form').on('submit', function(event) {
		// Vérifier que le pseudo ne contient que des lettres, chiffres et _
		if ($('#inscription_pseudo').val().search(/\W./) !== -1)
			var error_message = 'Le pseudo ne doit contenir que des lettres, des chiffres et des _';
		// Vérifier que les deux mots de passe saisis sont identiques
		else if ($('#inscription_password_1').val() !== $('#inscription_password_2').val())
			var error_message = 'Les deux mots de passe ne sont pas identiques';

		// Si une erreur est survenue, affichage d'un message et non-envoi du formulaire
		if (typeof error_message !== 'undefined') {
			$('#js_inscription_form').remove();
			$('main form').after('<p class="alert alert-warning" id="js_inscription_form">' + error_message + '</p>');
			event.preventDefault();
		}	
	});
});