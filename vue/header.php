<!doctype html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="css/css.css">

	<title>Le Bazar des Merveilles</title>
</head>

<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<div id="title" class="container text-center">
		<a href="index.php"><img src="images/title.png" alt="Le Bazar des Merveilles" /></a>
	</div>

	<div class="container">
		<div class="row">
			<nav class=o"col-md-auto">
				<div class="card">
					<div class="card-body">

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
							echo '<p class="alert alert-danger">', $error_message, '</p>';
						}
							
						?>

						<label for="connection_pseudo">Pseudo :</label><br />
						<input type="text" name="connection_pseudo" id="connection_pseudo" /><br />
						<label for="connection_password">Mot de passe :</label><br />
						<input type="password" name="connection_password" id="connection_password" /><br />
						<input type="checkbox" name="connection_persists" id="connection_persists" /> <label for="connection_persists">Rester connecté</label><br />
						<div class="text-center"><input type="submit" class="btn btn-primary" value="Se connecter" /></div>
					</form>

					<hr />

					<p><a href="inscription.php">Créer un compte</a></p>

					<?php
				// Afficher la liste des liens si connecté
				else :
					?>

					<p>
						<?=$_SESSION['user']->get_pseudo(); ?><br />
						Solde : <?=$_SESSION['user']->get_balance(); ?> € (<a href="account.php">recharger</a>)<br />
						<a href="index.php?disconnect">Se déconnecter</a><br />
					</p>
					<p>
						<a href="account.php">Mes infos</a>
					</p>
					<hr />
						<p><a href="index.php">Toutes les annonces</a></p>
					<hr />
					<p>
						<a href="auction_creation.php">Mettre un objet en vente</a><br />
						<a href="running_auctions.php">Mes annonces en cours</a><br />
						<a href="closed_auctions.php">Mes annonces terminées</a>
					</p>
					<hr />
					<p>
						<a href="running_bids.php">Mes enchères en cours</a><br />
						<a href="closed_bids.php">Mes enchères terminées</a>
					</p>

					<?php
				endif;
				?>

					</div>
				</div>
			</nav>

			<main class="col text-center">
