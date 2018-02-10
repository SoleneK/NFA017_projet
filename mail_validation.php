<?php

require 'init.php';

// Si un utilisateur est connecté ou que l'URl ne contient pas d'email ou de clé, le rediriger vers la page d'accueil
if (isset($_SESSION['user']) || !isset($_GET['mail']) || !isset($_GET['key']))
	header('Location: '.$_SESSION['server_url'].'index.php', TRUE, 307);


require 'vue/header.php';

// Vérification que la clé correspond au mail
if (db_check_validation_key($_GET['mail'], $_GET['key'])) {
	$response = db_activate_account ($_GET['mail']);
	if ($response)
		echo '<p>Votre compte a été activé, vous pouvez maintenant vous connecter</p>';
	else
		echo '<p>Erreur pendant l\'activation, veuillez réessayer</p>';
}
else {
	echo '<p>Les informations fournies ne sont pas valides</p>';
}

require 'vue/footer.php';
