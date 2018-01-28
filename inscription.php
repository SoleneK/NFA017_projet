<?php

// Si un formulaire d'inscription a été envoyé, procéder à l'inscription et afficher le résultat
if (isset($_POST['inscription_pseudo'])) {
	include 'header.php';
	echo '<p>Formulaire reçu</p>';
	include 'footer.php';
}

// Sinon, afficher le formulaire d'inscription
else {
	include 'header.php';

	?>

	<h1>Inscription</h1>

	<form method="post" action="inscription.php">
		<p>
			<label for="inscription_pseudo">Pseudo : </label>
			<input type="text" name="inscription_pseudo" id="inscription_pseudo" /><br />
			<label for="inscription_password_1">Mot de passe : </label>
			<input type="password" name="inscription_password_1" id="inscription_password_1" /><br />
			<label for="inscription_password_2">Confirmer le mot de passe : </label>
			<input type="password" name="inscription_password_2" id="inscription_password_2" /><br />
			<label for="inscription_mail">Mail : </label>
			<input type="mail" name="inscription_mail" id="inscription_mail" /><br />
			<input type="submit" value="Créer le compte" />
		</p>
	</form>

	<?php

	include 'footer.php';
}