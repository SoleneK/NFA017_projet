<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';
require 'library/time_functions.php';

// Si une id a été envoyé par URL, on récupère l'annonce correspondante
if (isset($_GET['id']))
	$auction = get_auction_by_id((int)$_GET['id']);

// S'il n'y a pas d'id ou que l'id fournie ne correspond pas à une annonce
if (!isset($_GET['id']) || !$auction)
	echo '<p class="alert alert-danger">Aucune annonce n\'a été trouvée</p>';
else {
	// Si l'utilisateur envoie une enchère
	if (isset($_POST['set_bid_amount'])) {
		$response = $auction->create_new_bid((float)$_POST['set_bid_amount']);

		switch ($response) {
			case 'NO_USER_CONNECTED':
				$bid_status = 'Vous devez être connecté pour enchérir';
				break;
			case 'AUCTION_CLOSED':
				$bid_status = 'Cette annonce est terminée';
				break;
			case 'IS_SELLER':
				$bid_status = 'Vous ne pouvez pas enchérir sur une de vos annonces';
				break;
			case 'BID_INFERIOR':
				$bid_status = 'Vous devez entrer un montant supérieur à l\'enchère en cours';
				break;
			case 'INSUFFICIENT_BALANCE':
				$bid_status = 'Votre solde est insuffisant';
				break;
			case 'ERROR_CREATION':
				$bid_status = 'Une erreur est survenue pendant l\'enregistrement de votre enchère. Veuillez réessayer.';
				break;
		}
	}

	?>

	<h1 class="mb-4"><?=$auction->get_title(); ?></h1>
	<p>
		<img src="images/auctions/<?=$auction->get_image(); ?>" alt="<?=$auction->get_title(); ?>" /><br />
		<em><?=$auction->get_description(); ?></em><br />
		Annonce publiée par <?=$auction->get_pseudo_seller(); ?> le <?=date('d-m-Y', $auction->get_begin_date()); ?> à <?=date('H:i', $auction->get_begin_date()); ?><br />
		
		<?php
		if ($auction->get_end_date() < time())
			echo 'Enchère terminée le ', date('d-m-Y', $auction->get_end_date()), ' à ', date('H:i', $auction->get_end_date());
		else {
			echo '<span class="countdown_sentence"  data-end-date="', $auction->get_end_date() - time(), '">Les enchères se terminent dans ', get_time_left($auction->get_end_date()), ' (', date('d-m-Y H:i', $auction->get_end_date()), ')</span>';
			echo '<script src="js/countdown.js"></script>';
		}
		?>
		<br />
		Prix de départ : <?=$auction->get_start_bid(); ?> €<br />
		Enchère actuelle : <?=$auction->get_current_bid(); ?> €
	</p>
		<?php
		if (isset($_SESSION['user'])) {
			?>

			<form method="post" action="auction.php?id=<?=$auction->get_id(); ?>" class="countdown_remove">
				<input type="text" name="set_bid_amount" required /> <input type="submit" class="btn btn-primary" value="Enchérir" />
			</form>

			<?php

			if (isset($bid_status)) {
				echo '<p class="alert alert-danger">', $bid_status, '</p>';
			}
		}
		else {
			echo '<p class="alert alert-danger countdown_remove">Vous devez être connecté pour enchérir</p>';
		}
}


require 'vue/footer.php';