<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';
require 'library/time_functions.php';

echo '<h1 class="mb-4">Les annonces en cours</h1>';

$auctions_list = get_auctions(0, 6);

echo '<div class="container"><div id="auctions_list" class="row">';
foreach ($auctions_list as $auction) {
	?>

	<article class="container">
		<div class="row align-items-center border rounded my-1">
			<div class="col-3">
				<img src="images/auctions/<?=$auction->get_image(); ?>" title="<?=$auction->get_title(); ?> " class="img-fluid" />
			</div>
			<div class="col text-left">
				<a href="auction.php?id=<?=$auction->get_id(); ?>"><?=$auction->get_title(); ?></a><br />
				<?=$auction->get_current_bid(); ?> € (<?=$auction->get_number_bids(); ?> enchères)<br />
				Temps restant : <?=get_time_left($auction->get_end_date()); ?>
			</div>
		</div>
	</article>

	<?php
}

?>	
	</div>
	</div>
	<div id="more_auctions"><button class="btn btn-primary">Afficher plus</button></div>
	<script src="js/index.js"></script>

<?php
require 'vue/footer.php';
