function set_countdown() {
	var that = this;
	// Récupérer le temps restant sur l'annonce
	var date_end_date = $(this).attr('data-end-date');
	var end_date = {
		'days': Math.floor(date_end_date / (60 * 60 * 24)),
		'hours': (Math.floor(date_end_date / (60 * 60))) % 24,
		'minutes': (Math.floor(date_end_date / 60)) % 60,
		'seconds': date_end_date % 60
	};
	
	var countdown = setInterval(function () {
		// Calculer le nouveau temps restant
		if (end_date.seconds !== 0)
			end_date.seconds -= 1;
		else {
			end_date.seconds = 59;
			if (end_date.minutes !== 0)
				end_date.minutes -= 1;
			else {
				end_date.minutes = 59;
				if(end_date.hours !== 0)
					end_date.hours -= 1;
				else {
					end_date.hours = 23;
					if(end_date.days !== 0)
						end_date.days -= 1;
				}
			}
		}

		// Si le compteur n'est pas terminé (toutes les valeurs à 0), le mettre à jour
		if (end_date.days !== 0 || end_date.hours !== 0 || end_date.minutes !== 0 || end_date.seconds !== 0) {
			if (end_date.days === 0)
				$(that).find('.countdown_days').text('');
			else
				$(that).find('.countdown_days').text(end_date.days + 'j ');

			if (end_date.hours === 0)
				$(that).find('.countdown_hours').text('');
			else
				$(that).find('.countdown_hours').text(end_date.hours + 'h ');

			if (end_date.minutes === 0)
				$(that).find('.countdown_minutes').text('');
			else
				$(that).find('.countdown_minutes').text(end_date.minutes + 'm ');

			if (end_date.seconds === 0)
				$(that).find('.countdown_seconds').text('');
			else
				$(that).find('.countdown_seconds').text(end_date.seconds + 's ');
		}
		// Lorsque le compteur est terminé, remplacer la phrase et effacer les éléments signalés
		else {
			clearInterval(countdown);
			$(that).text('Enchère terminée');
			$('.countdown_remove').remove();
		}
	}, 1000);
}

$(function() {
	$('.countdown_sentence').each(set_countdown);
});

