<?php

require 'init.php';

require 'vue/header.php';
echo '<h1>Créer un compte</h1>';

// Vérifier qu'il n'y a pas d'utilisateur connecté
if (isset($_SESSION['user'])) {
	echo '<p>Vous êtes déjà connecté, vous n\'avez pas besoin de vous créer un compte.</p>';
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

		<form method="post" action="inscription.php">

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
			echo '<p>', $error_message, '</p>';
		}

		?>

			<p>
				<label for="inscription_pseudo">Pseudo : </label>
				<input type="text" name="inscription_pseudo" id="inscription_pseudo" required /><br />
				<label for="inscription_password_1">Mot de passe : </label>
				<input type="password" name="inscription_password_1" id="inscription_password_1" required /><br />
				<label for="inscription_password_2">Confirmer le mot de passe : </label>
				<input type="password" name="inscription_password_2" id="inscription_password_2" required /><br />
				<label for="inscription_mail">Mail : </label>
				<input type="mail" name="inscription_mail" id="inscription_mail" required /><br />
				<input type="submit" value="Créer le compte" />
			</p>
		</form>

		<script src="<?=$server_url; ?>/js/inscription.js"></script>

		<?php
	}

}

require 'vue/footer.php';
