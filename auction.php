<?php

require 'header.php';
require 'auction_functions.php';

// Si une id a été envoyé par URL, on récupère l'annonce correspondante
if (isset($_GET['id']))
	$auction = get_auction_by_id((int)$_GET['id']);

include 'vue/header.php';

// S'il n'y a pas d'id ou que l'id fournie ne correspond pas à une annonce
if (!isset($_GET['id']) || !$auction)
	echo 'Aucune annonce n\'a été trouvée :(';

else {
	?>

	<h3><?=$auction->get_title(); ?></h3>
	<p>
		<img src="images/<?=$auction->get_image(); ?>" alt="<?=$auction->get_title(); ?>" /><br />
		<strong>Description</strong> : <?=$auction->get_description(); ?><br />
		Annonce publiée par <?=$auction->get_pseudo_seller(); ?> le <?=date('d-m-Y', $auction->get_begin_date()); ?> à <?=date('H:i', $auction->get_begin_date()); ?><br />
		Les enchères se terminent le <?=date('d-m-Y', $auction->get_end_date()); ?> à <?=date('H:i', $auction->get_end_date()); ?>.<br />
		Prix de départ : <?=$auction->get_start_bid(); ?><br />
		Enchère actuelle : <?=$auction->get_current_bid(); ?>

		<form method="post" action="auction.php?id=<?=$auction->get_id(); ?>">
			<input type="number" name="set_bid_amount" /> <input type="submit" value="Enchérir" />
		</form>
	</p>


	<?php
}


include 'vue/footer.php';