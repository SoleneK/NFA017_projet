<?php

require 'init.php';

require 'vue/header.php';
echo '<h1 class="mb-4">Créer un compte</h1>';

// Vérifier qu'il n'y a pas d'utilisateur connecté
if (isset($_SESSION['user'])) {
	echo '<p class="alert alert-danger">Vous êtes déjà connecté, vous n\'avez pas besoin de vous créer un compte.</p>';
}
else {
	// Si un formulaire d'inscription a été envoyé, procéder à l'inscription
	if (isset($_POST['inscription_pseudo'])) {
		$inscription_status = User::inscription($_POST['inscription_pseudo'], $_POST['inscription_password_1'], $_POST['inscription_password_2'], $_POST['inscription_mail']);
	}

	// Si l'inscription est réussie
	if (isset($inscription_status) && $inscription_status == 'OK') {
		echo '<p>Votre compte a été créé. Avant de pouvoir vous connecter, vous devez confirmer cette inscription en cliquant ou recopiant le lien qui vous a été envoyé à l\'adresse mail que vous avez saisie.</p>';
	}
	else {
		?>

		<form method="post" action="inscription.php" class="form-group">

		<?php

		if (isset($inscription_status)) {
			switch ($inscription_status) {
				case 'NO_PSEUDO':
					$error_message = 'Vous devez entrer un pseudo';
					break;
				case 'NO_PASSWORD':
					$error_message = 'Vous devez entrer un mot de passe';
					break;
				case 'NO_MAIL':
					$error_message = 'Vous devez entrer une adresse mail';
					break;
				case 'INCORRECT_PSEUDO':
					$error_message = 'Le pseudo ne peut contenir que des lettres, des nombres et le caractère _';
					break;
				case 'UNAVAILABLE_PSEUDO';
					$error_message = 'Ce pseudo n\'est pas disponible';
					break;
				case 'UNAVAILABLE_MAIL':
					$error_message = 'Un compte avec cette adresse mail existe déjà';
					break;
				case 'PASSWORD_UNMATCH':
					$error_message = 'Les mots de passe que vous avez saisis ne sont pas identiques';
					break;
				case 'ERROR_CREATION':
					$error_message = 'Une erreur est survenue lors de la création. Veuillez réessayer.';
					break;
			}
			echo '<p class="alert alert-danger">', $error_message, '</p>';
		}

		?>
			<div class="form-group row">
				<label for="inscription_pseudo" class="col-sm-2 col-form-label text-left">Pseudo</label>
				<div class="col-sm-10 col-form-label">
					<input type="text" name="inscription_pseudo" id="inscription_pseudo" class="form-control" value="<?=isset($_POST['inscription_pseudo']) ? $_POST['inscription_pseudo'] : ''; ?>" data-toggle="tooltip" data-placement="right" title="Doit contenir uniquement des lettres, des chiffres et des _" required />
				</div>
				<label for="inscription_password_1" class="col-sm-2 col-form-label text-left">Mot de passe</label>
				<div class="col-sm-10 col-form-label">
					<input type="password" name="inscription_password_1" id="inscription_password_1" class="form-control" required />
				</div>
				<label for="inscription_password_2" class="col-sm-2 col-form-label text-left">Confirmer le mot de passe</label>
				<div class="col-sm-10 col-form-label">
					<input type="password" name="inscription_password_2" id="inscription_password_2" class="form-control" required />
				</div>
				<label for="inscription_mail" class="col-sm-2 col-form-label text-left">Mail</label>
				<div class="col-sm-10 col-form-label">
					<input type="mail" name="inscription_mail" id="inscription_mail" class="form-control"  value="<?=isset($_POST['inscription_mail']) ? $_POST['inscription_mail'] : ''; ?>" data-toggle="tooltip" data-placement="right" title="Attention à entrer une adresse valide, elle sera utilisée pour activer le compte" required />
				</div>
				<div class="col-sm-12 text-center">
					<input type="submit" class="btn btn-primary" value="Créer le compte" />
				</div>
			</div>
		</form>

		<script src="js/inscription.js"></script>

		<?php
	}

}

require 'vue/footer.php';
