<?php

// Prend en paramètre une idée et renvoie un objet Auction contenant l'annonce correspondante ou false si elle n'existe pas
function get_auction_by_id($id) {
	$response = db_get_auction_by_id($id);

	// Si aucune annonce ne correspond
	if (!$response)
		$auction = false;
	else
		$auction = new Auction($id, $response['auc_title'], $response['auc_image'], $response['auc_description'], $response['auc_begindate'], $response['auc_enddate'], $response['usr_id'], $response['auc_startbid'], $response['auc_active']);

	return $auction;
}

// Prend en paramètre l'id d'un utilisateur et renvoie la liste des annonces (actives ou inactives) qu'il a publiées ou false sinon
function get_auctions_by_seller($id_seller, $active) {
	$response = db_get_auctions_by_seller($id_seller, $active);

	if (is_null($response))
		$auctions_list = false;
	else {
		while ($auction = $response->fetch(PDO::FETCH_ASSOC)) {
			$auctions_list[] = new Auction($auction['auc_id'], $auction['auc_title'], $auction['auc_image'], $auction['auc_description'], $auction['auc_begindate'], $auction['auc_enddate'], $id_seller, $auction['auc_startbid'], $active);
		}
	}

	return $auctions_list;
}

// Prend en paramètre l'id d'un utilisateur et renvoie la liste des annonces (en cours ou terminées) sur les quelles il a enchéries, et false sinon
function get_auctions_by_buyer($id_buyer, $running) {
	$response = db_get_auctions_by_buyer($id_buyer, $running);

	if (is_null($response))
		$auctions_list = false;
	else {
		while ($auction = $response->fetch(PDO::FETCH_ASSOC)) {
			$auctions_list[] = new Auction($auction['auc_id'], $auction['auc_title'], $auction['auc_image'], $auction['auc_description'], $auction['auc_begindate'], $auction['auc_enddate'], $auction['usr_id'], $auction['auc_startbid'], $auction['auc_active']);
		}
	}

	return $auctions_list;
}

function get_auctions($start, $quantity) {
	$response = db_get_auctions((int)$start, (int)$quantity);

	if (is_null($response))
		$auctions_list = false;
	else {
		while ($auction = $response->fetch(PDO::FETCH_ASSOC)) {
			$auctions_list[] = new Auction($auction['auc_id'], $auction['auc_title'], $auction['auc_image'], $auction['auc_description'], $auction['auc_begindate'], $auction['auc_enddate'], $auction['usr_id'], $auction['auc_startbid'], $auction['auc_active']);
		}
	}

	return $auctions_list;
}
