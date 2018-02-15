<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';

echo '<h1 class="mb-4">Mes enchères terminées</h1>';

if (!isset($_SESSION['user'])){
	echo '<p class="alert alert-danger">Vous n\'êtes pas connecté';
}
else {
	$auctions_list = get_auctions_by_buyer($_SESSION['user']->get_id(), false);

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
					L'objet a été vendu pour <?=$auction->get_current_bid() ?> € à <?=$auction->get_pseudo_current_buyer(); ?>

		<?php

		if ($auction->get_id_current_buyer() == $_SESSION['user']->get_id())
			echo '<br /><span class="text-success">Vous avez remporté cette enchère</span>';
		?>
		
				</div>
			</div>
		</article>

		<?php

	}
}
