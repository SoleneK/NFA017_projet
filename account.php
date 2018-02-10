<?php

require 'init.php';
require 'vue/header.php';

echo '<h1>Mon compte</h1>';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user']))
	echo '<p>Vous n\'êtes pas connecté</p>';
else {
	// Si l'utilisateur a demandé à recharger son compte
	if (isset($_POST['form_recharge_balance'])) {
		$response = $_SESSION['user']->modify_balance(round($_POST['form_recharge_balance'], 2), true);
		switch ($response) {
			case 'OK':
				$recharge_balance_status = 'Votre solde a bien été augmenté';
				break;
			case 'ERROR_DB':
				$recharge_balance_status = 'Erreur lors de la mise à jour de votre solde, veuillez réessayer';
				break;
			case 'ERROR_AMOUNT':
				$recharge_balance_status = 'Veuillez entrer un montant positif';
				break;
		}
	}

	?>

	<h2>Mes infos</h2>
	<p>
		Pseudo : <?=$_SESSION['user']->get_pseudo(); ?><br />
		Mail : <?=$_SESSION['user']->get_mail(); ?>
	</p>

	<h2>Mon solde</h2>
	<p>
		Solde disponible : <?=$_SESSION['user']->get_balance(); ?> €
	</p>
	<form method="post" action="account.php">
		<label for="form_recharge_balance">Ajouter de l'argent</label> : 
		<input type="text" name="form_recharge_balance" id="form_recharge_balance" />
		<input type="submit" value="Recharger" />
		<?php
		if (isset($recharge_balance_status))
			echo '<br />', $recharge_balance_status;
		?>
	</form>
	<?php
}
