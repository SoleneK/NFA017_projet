$(function() {
	$('[data-toggle="tooltip"]').tooltip();

	$('main form').on('submit', function(event) {
		// Vérifier que le prix est positif
		if ($('#auction_start_bid').val() < 0)
			var error_message = 'Le prix de départ doit être positif';

		// Si une erreur est survenue, affichage d'un message et non-envoi du formulaire
		if (typeof error_message !== 'undefined') {
			$('#js_inscription_form').remove();
			$('main form').after('<p class="alert alert-warning" id="js_inscription_form">' + error_message + '</p>');
			event.preventDefault();
		}	
	});
});