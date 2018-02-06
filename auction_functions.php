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