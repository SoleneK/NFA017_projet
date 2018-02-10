<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';
require 'library/time_functions.php';

echo '<h1>Les annonces en cours</h1>';

$auctions_list = get_auctions(0, 5);

foreach ($auctions_list as $auction) {
	?>

	<article>
		<img src="images/auctions/<?=$auction->get_image(); ?>" title="<?=$auction->get_title(); ?>" /><br />
		<a href="auction.php?id=<?=$auction->get_id(); ?>"><?=$auction->get_title(); ?></a><br />
		<?=$auction->get_current_bid(); ?> €<br />
		Temps restant : <?=get_time_left($auction->get_end_date()); ?>
	</article>

	<?php
}

require 'vue/footer.php';
