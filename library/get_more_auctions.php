<?php

require 'db_functions.php';
require 'auction_functions.php';
require 'time_functions.php';

require '../classes/Auction.php';
require '../classes/Bid.php';

// Vérifier que des données ont bien été envoyées
if (!isset($_GET['start']) || !isset($_GET['quantity'])) {
	$response['status'] = 'ERROR';
}
else {
	$start = (int)$_GET['start'];
	$quantity = (int)$_GET['quantity'];

	if ($start < 0 || $quantity <= 0)
		$response['status'] = 'ERROR';
	else {
		// Récupérer les annonces
		$request = get_auctions($start, $quantity);
		$auctions_list = [];
		foreach ($request as $auction) {
			$auctions_list[] = array(
				'id' => $auction->get_id(),
				'title' => $auction->get_title(),
				'image' => $auction->get_image(),
				'current_bid' => $auction->get_current_bid(),
				'countdown' =>$auction->get_end_date() - time(),
				'time_left' => get_time_left($auction->get_end_date()),
				'number_bids' => $auction->get_number_bids()
				);
		}

		// Créer le tableau à renvoyer
		$response['status'] = 'OK';
		$response['data'] = $auctions_list;
	}
}

header("Content-type: application/json;charset=UTF-8");
echo json_encode($response);