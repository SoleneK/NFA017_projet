<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';

echo '<h1 class="mb-4">Mes annonces terminées</h1>';

if (!isset($_SESSION['user'])){
	echo '<p class="alert alert-danger">Vous n\'êtes pas connecté';
}
else {
	// Si une demande pour clore une action a été envoyée
	if (isset($_GET['id'])) {
		$auction = get_auction_by_id((int)$_GET['id']);
		if ($auction)
			$response = $auction->close_auction();
		else
			$response = 'NO_AUCTION';

		switch ($response) {
			case 'NO_AUCTION':
				$close_auction_status = 'Cette annonce n\'existe pas.';
				break;
			case 'NOT_SELLER':
				$close_auction_status = 'Vous n\'avez pas mis cet objet en vente.';
				break;
			case 'RUNNING':
				$close_auction_status = 'Cette annonce est encore en cours.';
				break;
			case 'ALREADY_CLOSED':
				$close_auction_status = 'Cette annonce a déjà été validée.';
				break;
			case 'NO_BID':
				$close_auction_status = 'Il n\'y a pas eu d\'enchère sur '.$auction->get_title().'.';
				break;
			case 'OK':
				$close_auction_status = 'La vente de '.$auction->get_title().' pour '.$auction->get_current_bid().' € est validée. Le montant a été ajouté à votre solde.';
				break;
		}

		unset ($auction);
	}

	$auctions_list = get_auctions_by_seller($_SESSION['user']->get_id(), false);

	if (isset($close_auction_status))
		if ($response == 'OK')
			echo '<p class="alert alert-success">', $close_auction_status, '</p>';
		else
			echo '<p class="alert alert-danger">', $close_auction_status, '</p>';

	foreach($auctions_list as $auction) {
		?>

		<article class="container">
			<div class="row align-items-center border rounded my-1">
				<div class="col-3">
					<img src="images/auctions/<?=$auction->get_image(); ?>" title="<?=$auction->get_title(); ?>" class="img-fluid" />
				</div>
				<div class="col text-left">
					<a href="auction.php?id=<?=$auction->get_id(); ?>"><?=$auction->get_title(); ?></a><br />
					Annonce terminée le <?=date('d-m-Y', $auction->get_end_date()); ?> à <?=date('H:i', $auction->get_end_date()); ?><br />
					L'objet a été vendu pour <?=$auction->get_current_bid() ?> €
					<?php
					if (!is_null($auction->get_bids_list()))
						echo ' à ', $auction->get_pseudo_current_buyer();

					if ($auction->get_active())
						echo '<br /><a href="closed_auctions.php?id=', $auction->get_id(), '">Valider la vente</a>';
					?>
				</div>
			</div>
		</article>

		<?php
	}
}

