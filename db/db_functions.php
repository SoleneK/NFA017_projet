<?php

include 'config_db.php';

try {
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_BASE . '; charset=UTF8', DB_USER, DB_PASSWORD);
}
catch (Exception $e) {
	echo '<p>Problème de connexion à la base de données</p>';
	exit();
}

// Vérifie si un utilisateur identifié par son pseudo existe
function db_user_exists ($pseudo) {
	global $db;
	$query = 'SELECT COUNT(usr_pseudo) FROM users WHERE usr_pseudo = :pseudo';
	$statement = $db->prepare($query);
	$statement->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_NUM);
	
	if ($response[0] == '0')
		$user_exists = false;
	else
		$user_exists = true;

	return $user_exists;
}

// Création d'un utilisateur
function db_create_user ($pseudo, $password, $mail) {
	global $db;
	$query = 'INSERT INTO users (usr_pseudo, usr_password, usr_mail) VALUES (:pseudo, :password, :mail)';
	$statement = $db->prepare($query);
	$statement->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
	$statement->bindValue('password', $password, PDO::PARAM_STR);
	$statement->bindValue('mail', $mail, PDO::PARAM_STR);
	$status = $statement->execute();
	return $status;
}

// Cherche les infos d'un utilisateur, les renvoie s'il existe, renvoie false sinon
function db_connect_user ($pseudo) {
	global $db;
	$query = 'SELECT usr_id, usr_password, usr_mail, usr_balance FROM users WHERE usr_pseudo = :pseudo';
	$statement = $db->prepare($query);
	$statement->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_ASSOC);
	return $response;
}

// Mise à jour du mail ou du solde de l'utilisateur identifié par son id
function update_user ($id, $mail, $balance) {
	global $db;
	$query = 'UPDATE users SET usr_mail = :mail, usr_balance = :balance WHERE usr_id = :id';
	$statement = $db->prepare ($query);
	$statement->bindValue('mail', $mail, PDO::PARAM_STR);
	$statement->bindValue('balance', $balance, PDO::PARAM_INT);
	$statement->bindValue('id', $id, PDO::PARAM_INT);
	$status = $statement->execute();
	return $status;	
}