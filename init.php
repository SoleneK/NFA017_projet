<?php

require 'library/db_functions.php';

date_default_timezone_set('Europe/Paris');

function autoloader ($classname) {
	require 'classes/'.$classname.'.php';
}

spl_autoload_register ('autoloader');

$server_url = 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/';

session_start();

if (!isset($_SESSION['user']))
	// Connexion de l'utilisateur par cookie
	if (isset($_COOKIE['pseudo']))
		User::connection($_COOKIE['pseudo'], $_COOKIE['password'], true);
	// Connexion de l'utilisateur par formulaire
	else if (isset($_POST['connection_pseudo'])){
		if (isset ($_POST['connection_persists']))
			$stay_connected = true;
		else
			$stay_connected = false;
		$connexion_result = User::connection($_POST['connection_pseudo'], $_POST['connection_password'], false, $stay_connected);
	}

if (isset($_SESSION['user']) && isset($_GET['disconnect']))
	$_SESSION['user']->disconnection();

