<?php

/*	A mettre sur une autre page :
*	démarrage de la session
*	vérification qu'il existe déjà un utilisateur dans la session
*	si non, vérification qu'il existe un cookie
*/

require 'autoload.php';
require 'db/db_functions.php';

/*	Si un utilisateur est connecté, l'ouverture de la session rappelle l'objet User
*	Sinon, si un utilisateur est mémorisé dans les cookies, il est connecté
*/
session_start();

if (!isset($_SESSION['user']) && isset($_COOKIE['pseudo']))
	User::connection($_COOKIE['pseudo'], $_COOKIE['password'], true);

