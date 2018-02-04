<?php

require 'db/db_functions.php';

date_default_timezone_set('Europe/Paris');

function autoloader ($classname) {
	require 'classes/' . $classname . '.php';
}

spl_autoload_register ('autoloader');

/*	Si un utilisateur est connecté, l'ouverture de la session rappelle l'objet User
*	Sinon, si un utilisateur est mémorisé dans les cookies, il est connecté
*/
session_start();

if (!isset($_SESSION['user']) && isset($_COOKIE['pseudo']))
	User::connection($_COOKIE['pseudo'], $_COOKIE['password'], true);

// Si l'utilisateur a demandé à se déconnecter
if (isset($_SESSION['user']) && isset($_GET['disconnect']))
	$_SESSION['user']->disconnection();

