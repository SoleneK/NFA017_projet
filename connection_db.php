<?php

include 'config_db.php';

try {
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_BASE . '; charset=UTF8', DB_USER, DB_PASSWORD);
}
catch (Exception $e) {
	echo '<p>Problème de connexion à la base de données</p>';
	exit();
}