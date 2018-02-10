<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/temp.css">
	<title>Le Bazar des Merveilles</title>
</head>

<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<div id="title"><a href="<?=$_SESSION['server_url']; ?>"><img src="<?=$_SESSION['server_url']; ?>/images/title.png" alt="Le Bazar des Merveilles" /></a></div>

	<nav>
		<?php
		// Afficher le menu de connection et le lien d'inscription
		if (!isset($_SESSION['user'])) :
			?>

			<form method="post" id="connexion_form" action="">
				<?php
				if (isset($connexion_result) && $connexion_result != 'OK') {
					switch ($connexion_result) {
						case 'INCORRECT_INFOS':
							$error_message = 'Informations incorrectes';
							break;
						case 'NOT_ACTIVE':
							$error_message = 'Compte inactif';
							break;
					}
					echo $error_message, '<br />';
				}
					
				?>

				<label for="connection_pseudo">Pseudo :</label><br />
				<input type="text" name="connection_pseudo" id="connection_pseudo" /><br />
				<label for="connection_password">Mot de passe :</label><br />
				<input type="password" name="connection_password" id="connection_password" /><br />
				<input type="checkbox" name="connection_persists" id="connection_persists" /> <label for="connection_persists">Rester connecté</label><br />
				<input type="submit" value="Se connecter" />
			</form>

			<hr />

			<a href="<?=$_SESSION['server_url']; ?>inscription.php">Créer un compte</a>

			<?php
		// Afficher la liste des liens si connecté
		else :
			?>

			<p>
				<?=$_SESSION['user']->get_pseudo(); ?><br />
				Solde : <?=$_SESSION['user']->get_balance(); ?> (<a href="<?=$_SESSION['server_url']; ?>account.php">recharger</a>)
			</p>
			<p>
				<a href="index.php?disconnect">Se déconnecter</a><br />
				<a href="<?=$_SESSION['server_url']; ?>account.php">Mes infos</a>
			</p>
			<hr />
				<p><a href="<?=$_SESSION['server_url']; ?>index.html">Toutes les annonces</a></p>
			<hr />
			<p>
				<a href="<?=$_SESSION['server_url']; ?>create_auction.php">Mettre un objet en vente</a><br />
				<a href="<?=$_SESSION['server_url']; ?>active_auctions.php">Mes annonces en cours</a><br />
				<a href="<?=$_SESSION['server_url']; ?>closed_auctions.php">Mes annonces terminées</a>
			</p>
			<hr />
			<p>
				<a href="<?=$_SESSION['server_url']; ?>running_bids.php">Mes enchères en cours</a><br />
				<a href="<?=$_SESSION['server_url']; ?>closed_bids.php">Mes enchères terminées</a>
			</p>

			<?php
		endif;
		?>

	</nav>

	<main>
