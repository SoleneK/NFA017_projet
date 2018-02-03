<?php

/*	Si un utilisateur est connecté, affichage d'un message d'erreur
*	Sinon, si un formulaire a été envoyé, essai de connexion
*		Si la connexion est réussie, affichage d'un message de réussite
*		Si elle échoue à cause d'une erreur, affichage d'un message d'erreur et affichage du formulaire
*	Sinon, affichage du formulaire
*/

require 'header.php';

if (isset($_SESSION['user'])) {
	include 'vue/header.php';
	echo '<p>Vous êtes déjà connecté !</p>';
	include 'vue/footer.php';
}
else {
	if (isset ($_POST['connexion_pseudo'])) {
		if (isset ($_POST['connection_persists']))
			$stay_connected = true;
		else
			$stay_connected = false;
		$result = User::connection($_POST['connexion_pseudo'], $_POST['connection_password'], false, $stay_connected);
	}

	include 'vue/header.php';
	// En cas de connexion réussie
	if (isset($result) && $result == 'OK')
		echo '<p>Connexion réussie</p>';
	else {
		if (isset($result)) {
			if ($result == 'INCORRECT_INFOS')
				$connexion_error_message = 'Pseudo ou mot de passe incorrect';
			else if ($result == 'NOT_ACTIVE')
				$connexion_error_message = 'Votre compte n\'est pas activé';
		}


		?>

		<form method="post" action="connection.php">
			<p>

		<?php

		if (isset($connexion_error_message))
			echo $connexion_error_message, '<br />';

		?>
				<label for="connexion_pseudo">Pseudo : </label>
				<input type="text" name="connexion_pseudo" id="connexion_pseudo" /><br />
				<label for="connection_password">Mot de passe : </label>
				<input type="password" name="connection_password" id="connection_password" /><br />
				<input type="checkbox" name="connection_persists" id="connection_persists" />
				<label for="connection_persists">Rester connecté</label><br />
				<input type="submit" value="Se connecter" />
			</p>
		</form>

		<?php

	}
	include 'vue/footer.php';
}

