<?php

require 'init.php';
require 'vue/header.php';

echo '<h1>Créer une annonce</h1>';

// Si l'utilisateur n'est pas connecté, redirection
if (!isset($_SESSION['user'])){
	echo '<p>Vous devez être connecté pour faire ça';
}
else {
	// Si un formulaire a été envoyé, on essaie de créer l'annonce
	if (isset($_POST['auction_title'])) {
		if (empty($_POST['auction_date']) || empty($_POST['auction_time']))
			$end_date = 0;
		else
			$end_date = strtotime($_POST['auction_date'].' '.$_POST['auction_time']);

		$message = Auction::create_auction ($_POST['auction_title'], 'auction_image', $_POST['auction_description'], $end_date, $_SESSION['user']->get_id(), $_POST['auction_start_bid']);

		switch ($message) {
			case 'NO_TITLE':
				$auction_creation_status = 'Vous devez mettre un titre';
				break;
			case 'NO_IMAGE':
				$auction_creation_status = 'Vous devez ajouter une image';
				break;
			case 'NO_DESCRIPTION':
				$auction_creation_status = 'Vous devez ajouter une description';
				break;
			case 'NO_END_DATE':
				$auction_creation_status = 'Vous devez indiquer une date de fin';
				break;
			case 'NO_START_BID':
				$auction_creation_status = 'Vous devez indiquer un prix de départ';
				break;
			case 'NOT_AN_IMAGE':
				$auction_creation_status = 'Le fichier envoyé n\'est pas une image';
				break;
			case 'IMAGE_TOO_BIG':
				$auction_creation_status = 'La taille de l\'image est trop grande';
				break;
			case 'INCORRECT_END_DATE':
				$auction_creation_status = 'La date de fin est incorrecte';
				break;
			case 'START_BID_NEGATIVE':
				$auction_creation_status = 'Le prix de départ ne peut pas être négatif';
				break;
			case 'ERROR_CREATION':
				$auction_creation_status = 'Une erreur est arrivée pendant la création de l\'annonce. Veuillez réessayer.';
				break;
		}
	}
	
	// Si l'annonce a été créée
	if (isset($message) && $message == 'OK'):
		?>
		<p>Votre annonce a bien été créée !</p>
		<p><a href="auction_creation.php">Ajouter une autre annonce</a></p>
		<?php

	else:
		?>

		<form method="post" action="auction_creation.php" enctype="multipart/form-data">
			<?php
			if (isset($auction_creation_status))
				echo '<p>', $auction_creation_status, '</p>';
			?>
			<p>Entrez les informations de l'annonce à créer</p>
			<p>
				<label for="auction_title">Titre : </label><input type="text" name="auction_title" id="auction_title" required /><br />
				<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
				<label for="auction_image">Image : </label><input type="file" name="auction_image" id="auction_image" required /><br />
				<label for="auction_description">Description (500 caractères maximum) : </label><br />
				<textarea name="auction_description" required /></textarea><br />
				Fin de l'annonce le <input type="date" name="auction_date" required /> à <input type="time" name="auction_time" required /><br />
				<label for="auction_start_bid">Montant de départ : </label><input type="text" name="auction_start_bid" id="auction_start_bid" required /><br />
				<input type="submit" value="Créer" />
			</p>
		</form>

		<?php
	endif;
}

require 'vue/footer.php';
