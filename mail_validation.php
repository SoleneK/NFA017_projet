<?php

include 'header.php';

// Vérifier si compte connecté ?

// Vérification que la clé correspond au mail
if (db_check_validation_key($_GET['mail'], $_GET['key'])) {
	$response = db_activate_account ($_GET['mail']);
	if ($response)
		echo '<p>Compte activé</p>';
	else
		echo '<p>Erreur pendant l\'activation';
}
else {
	echo '<p>Les informations fournies ne sont pas valides';
}
