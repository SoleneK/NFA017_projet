<?php

require 'init.php';
require 'vue/header.php';

echo '<h1 class="mb-4">Créer une annonce</h1>';

// Si l'utilisateur n'est pas connecté, redirection
if (!isset($_SESSION['user'])){
	echo '<p class="alert alert-danger">Vous devez être connecté pour faire ça';
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
		<p class="alert alert-success">Votre annonce a bien été créée !</p>
		<p><a href="auction_creation.php">Ajouter une autre annonce</a></p>
		<?php

	else:
		?>

		<form method="post" action="auction_creation.php" enctype="multipart/form-data" class="form-group">
			<?php
			if (isset($auction_creation_status))
				echo '<p class="alert alert-danger">', $auction_creation_status, '</p>';
			?>
			<p>Entrez les informations de l'annonce à créer</p>
			<div class="form-group row">
				<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
				<label for="auction_title" class="col-sm-2 col-form-label text-left">Titre</label>
				<div class="col-sm-10 col-form-label">
					<input type="text" name="auction_title" id="auction_title" class="form-control" value="<?=isset($_POST['auction_title']) ? $_POST['auction_title'] : ''; ?>" required />
				</div>
				<label for="auction_image" class="col-sm-2 col-form-label text-left">Image</label>
				<div class="col-sm-10 col-form-label">
					<input type="file" name="auction_image" id="auction_image" class="form-control-file" required />
				</div>
				<label for="auction_description" class="col-sm-2 col-form-label text-left">Description</label>
				<div class="col-sm-10 col-form-label">
					<textarea name="auction_description" id="auction_description" class="form-control" placeholder="500 caractères maximum" required /><?=isset($_POST['auction_description']) ? $_POST['auction_description'] : ''; ?></textarea>
				</div>
				<label for="auction_date"  class="col-sm-2 col-form-label text-left">Date de fin</label>
				<div class="col-sm-10 col-form-label">
					<input type="date" name="auction_date" id="auction_date" class="form-control" value="<?=isset($_POST['auction_date']) ? $_POST['auction_date'] : ''; ?>" data-toggle="tooltip" data-placement="right" title="Une annonce peut durer entre 5 minutes et un mois" required />
				</div>
				<label for="auction_time"  class="col-sm-2 col-form-label text-left">Heure de fin</label>
				<div class="col-sm-10 col-form-label">
					<input type="time" name="auction_time" id="auction_time" class="form-control" value="<?=isset($_POST['auction_time']) ? $_POST['auction_time'] : ''; ?>" required />
				</div>
				<label for="auction_start_bid" class="col-sm-2 col-form-label text-left">Montant de départ</label>
				<div class="col-sm-10 col-form-label">
					<input type="text" name="auction_start_bid" id="auction_start_bid" class="form-control" value="<?=isset($_POST['auction_start_bid']) ? $_POST['auction_start_bid'] : ''; ?>" required />
				</div>
				<div class="col-sm-12 text-center">
					<input type="submit" class="btn btn-primary" value="Créer" />
				</div>
			</div>
		</form>

		<script src="js/auction_creation.js"></script>

		<?php
	endif;
}

require 'vue/footer.php';
