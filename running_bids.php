<?php

require 'init.php';
require 'vue/header.php';
require 'library/auction_functions.php';
require 'library/time_functions.php';

echo '<h1>Mes enchères en cours </h1>';

$auctions_list = get_auctions_by_buyer($_SESSION['user']->get_id(), true);

foreach($auctions_list as $auction) {
	?>

	<article>
		<img src="images/auctions/<?=$auction->get_image(); ?>" title="<?=$auction->get_title(); ?>" /><br />
		<a href="auction.php?id=<?=$auction->get_id(); ?>"><?=$auction->get_title(); ?></a><br />
		Prix de vente actuel : <?=$auction->get_current_bid() ?> € par <?=$auction->get_pseudo_current_buyer(); ?><br />
		Cette annonce se termine dans <?=get_time_left($auction->get_end_date()); ?> (<?=date('d-m-Y H:i', $auction->get_end_date()); ?>)<br />

	<?php

	if ($auction->get_id_current_buyer() == $_SESSION['user']->get_id())
		echo 'Votre enchère est la plus élevée';
	else
		echo 'Votre enchère n\'est pas la plus élevée';
	?>
		
	</article>

	<?php

}