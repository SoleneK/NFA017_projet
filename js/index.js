$(function() {
	var auctions_by_page = 6;
	var first_auction = auctions_by_page;


	// Script ajax pour afficher plus d'annonces lors du clic sur le bouton
	$('#more_auctions').click(function () {
		$.ajax({
			url: 'library/get_more_auctions.php',
			type: 'GET',
			data: 'start=' + first_auction + '&quantity=' + auctions_by_page,
			dataType: 'json',
			success: function (response) {
				if (response['status'] === 'OK') {
					$.each (response['data'], function(index, value) {
						var html = '<article class="container">';
						html += '<div class="row align-items-center border rounded my-1">';
						html += '<div class="col-3">';
						html += '<img src="images/auctions/' + value['image'] + '" title="' + value['title'] + '" class="img-fluid" />';
						html += '</div>';
						html += '<div class="col text-left">';
						html += '<a href="auction.php?id=' + value['id'] + '" >' + value['title'] + '</a><br />';
						html += value['current_bid'] + ' € (' + value['number_bids'] + ' enchères)<br />',
						html += '<span class="countdown_sentence"  data-end-date="' + value['countdown'] + '">Temps restant : ' + value['time_left'] + '</span>';
						html += '</div></div></article>';
						$('#auctions_list').append(html);
						$('.countdown_sentence').each(set_countdown);
					});
					if (response['data'].length < auctions_by_page)
						$('#more_auctions').remove();

					first_auction += auctions_by_page;
				}
			}
		});

	});

});