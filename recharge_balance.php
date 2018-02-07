<?php

require 'header.php';

// Si l'utilisateur n'est pas connecté, redirection
if (!isset($_SESSION['user'])){
	$host = 'http://'.$_SERVER['HTTP_HOST']; //TODO : retirer http ?
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header('Location: '.$host.$uri.'/index1.php', TRUE, 307);
}

include 'vue/header.php';

// Si l'utilisateur a demandé à recharger le compte
if (isset($_POST['form_recharge_balance'])) {
	$response = $_SESSION['user']->modify_balance($_POST['form_recharge_balance'], true);
	switch ($response) {
		case 'OK':
			$message = 'Votre solde a bien été augmenté';
			break;
		case 'ERROR_DB':
			$message = 'Erreur lors de la mise à jour de votre solde, veuillez réessayer';
			break;
		case 'ERROR_AMOUNT':
			$message = 'Veuillez entrer un nombre positif';
			break;
		default:
			$message = 'Erreur inconnue';
	}
		
	echo '<p>', $message, '</p>';
}

?>
<p>Solde : <?=$_SESSION['user']->get_balance(); ?></p>
<form method="post" action="recharge_balance.php">
	<p>
		Ajouter de l'argent à votre compte<br />
		<label for="form_recharge_balance">Montant à ajouter :</label>
		<input type="number" name="form_recharge_balance" id="form_recharge_balance" /><br />
		<input type="submit" value="Ajouter" />
	</p>
</form>

<?php

include 'vue/footer.php';

