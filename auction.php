<?php

require 'init.php';
require 'library/auction_functions.php';

require 'vue/header.php';
echo '<h1>Afficher une annonce</h1>';

// Si une id a été envoyé par URL, on récupère l'annonce correspondante
if (isset($_GET['id']))
	$auction = get_auction_by_id((int)$_GET['id']);

// S'il n'y a pas d'id ou que l'id fournie ne correspond pas à une annonce
if (!isset($_GET['id']) || !$auction)
	echo '<p>Aucune annonce n\'a été trouvée</p>';
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

	<h3><?=$auction->get_title(); ?></h3>
	<p>
		<img src="images/auctions/<?=$auction->get_image(); ?>" alt="<?=$auction->get_title(); ?>" /><br />
		<strong>Description</strong> : <?=$auction->get_description(); ?><br />
		Annonce publiée par <?=$auction->get_pseudo_seller(); ?> le <?=date('d-m-Y', $auction->get_begin_date()); ?> à <?=date('H:i', $auction->get_begin_date()); ?><br />
		Les enchères se terminent le <?=date('d-m-Y', $auction->get_end_date()); ?> à <?=date('H:i', $auction->get_end_date()); ?>.<br />
		Prix de départ : <?=$auction->get_start_bid(); ?><br />
		Enchère actuelle : <?=$auction->get_current_bid(); ?>
	</p>
		<?php
		if (isset($_SESSION['user'])):
			?>

			<form method="post" action="auction.php?id=<?=$auction->get_id(); ?>">
				<?php
				if (isset($bid_status))
					echo $bid_status, '<br />';
				?>
				<input type="text" name="set_bid_amount" required /> <input type="submit" value="Enchérir" />
			</form>

			<?php
		else:
			?>
			<p>Vous devez être connecté pour enchérir</p>
			<?php
		endif;
}


require 'vue/footer.php';