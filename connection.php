<?php

/*	A mettre sur une autre page :
*	démarrage de la session
*	vérification qu'il existe déjà un utilisateur dans la session
*	si non, vérification qu'il existe un cookie
*/

require 'autoload.php';
require 'db/db_functions.php';

session_start();

if (!isset($_SESSION['user'])) {
	if (isset($_COOKIE['pseudo'])) {
		$result = User::connection($_COOKIE['pseudo'], $_COOKIE['password'], true);
		var_dump($result);
		var_dump($_COOKIE);
		var_dump($_SESSION);
	}
	else {
		// Si un formulaire a été envoyé
		if (isset ($_POST['connexion_pseudo'])) {
			if (isset ($_POST['connection_persists']))
				$stay_connected = true;
			else
				$stay_connected = false;
			$result = User::connection($_POST['connexion_pseudo'], $_POST['connection_password'], false, $stay_connected);
		}

		else {
			include 'header.php';

			?>

			<form method="post" action="connection.php">
				<p>
					<label for="connexion_pseudo">Pseudo : </label>
					<input type="text" name="connexion_pseudo" id="connexion_pseudo" /><br />
					<label for="connection_password">Mot de passe : </label>
					<input type="password" name="connection_password" id="connection_password" /><br />
					<input type="checkbox" name="connection_persists" id="connection_persists" />
					<label for="connection_persists">Rester connecté</label><br />
					<input type="submit" value="Se connecter" />
				</p>

			<?php

			include 'footer.php';
		}
	}
}
else {
	echo 'Vous êtes connecté';
}

// TODO : quand la connexion échoue, reproposer le formulaire