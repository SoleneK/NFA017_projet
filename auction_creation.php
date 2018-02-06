<?php

require 'header.php';

// Si l'utilisateur n'est pas connecté, redirection
if (!isset($_SESSION['user'])){
	$host = 'http://'.$_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header('Location: '.$host.$uri.'/index1.php', TRUE, 307);
}

if (isset($_POST['auction_title'])) {
	if (empty($_POST['auction_date']) || empty($_POST['auction_time']))
		$end_date = 0;
	else
		$end_date = strtotime($_POST['auction_date'].' '.$_POST['auction_time']);

	$message = Auction::create_auction ($_POST['auction_title'], 'auction_image', $_POST['auction_description'], $end_date, $_SESSION['user']->get_id(), $_POST['auction_start_bid']);

	include 'vue/header.php';
	echo '<p>', $message, '</p>';
	include 'vue/footer.php';
}
else {
	include 'vue/header.php';

	?>

	<form method="post" action="auction_creation.php" enctype="multipart/form-data">
		<p>Entrez les informations de l'annonce à créer</p>
		<p>
			<label for="auction_title">Titre : </label><input type="text" name="auction_title" id="auction_title" required /><br />
			<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
			<label for="auction_image">Image : </label><input type="file" name="auction_image" id="auction_image" required /><br />
			<label for="auction_description">Description (500 caractères maximum) : </label><br />
			<textarea name="auction_description" required /></textarea><br />
			Fin de l'annonce le <input type="date" name="auction_date" required /> à <input type="time" name="auction_time" required /><br />
			<label for="auction_start_bid">Montant de départ : </label><input type="number" name="auction_start_bid" id="auction_start_bid" required /><br />
			<input type="submit" value="Créer" />
		</p>
	</form>

	<?php

	include 'vue/footer.php';
}