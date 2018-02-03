<?php

/*	Page temporaire pour tester le script de déconnexion
*	Déconnexion effectuée lorsque la variable disconnect est passée dans l'URL
*/

require 'header.php';

if (isset($_GET['disconnect'])) {
	$_SESSION['user']->disconnection();
}
else {
	?>

	<form method="get" action="disconnection.php">
		<input type="hidden" name="disconnect" />
		<input type="submit" value="Se déconnecter" />
	</form>

	<?php
}
