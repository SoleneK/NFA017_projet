<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';
require 'library/time_functions.php';

echo '<h1 class="mb-4">Mes enchères en cours </h1>';

if (!isset($_SESSION['user'])){
	echo '<p class="alert alert-danger">Vous n\'êtes pas connecté';
}
else {
	$auctions_list = get_auctions_by_buyer($_SESSION['user']->get_id(), true);

	foreach($auctions_list as $auction) {
		?>

		<article class="container">
			<div class="row align-items-center border rounded my-1">
				<div class="col-3">
					<img src="images/auctions/<?=$auction->get_image(); ?>" title="<?=$auction->get_title(); ?>" class="img-fluid" />
				</div>
				<div class="col text-left">
					<a href="auction.php?id=<?=$auction->get_id(); ?>"><?=$auction->get_title(); ?></a><br />
					Prix de vente actuel : <?=$auction->get_current_bid() ?> € par <?=$auction->get_pseudo_current_buyer(); ?><br />
					Cette annonce se termine dans <?=get_time_left($auction->get_end_date()); ?> (<?=date('d-m-Y H:i', $auction->get_end_date()); ?>)<br />

		<?php

		if ($auction->get_id_current_buyer() == $_SESSION['user']->get_id())
			echo '<span class="text-success">Votre enchère est la plus élevée</span>';
		else
			echo '<span class="text-danger">Votre enchère n\'est pas la plus élevée</span>';
		?>

				</div>
			</div>
		</article>

		<?php

	}
}
