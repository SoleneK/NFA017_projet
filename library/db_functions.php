<?php

include 'config_db.php';

try {
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_BASE . '; charset=UTF8', DB_USER, DB_PASSWORD);
}
catch (Exception $e) {
	echo '<p>Problème de connexion à la base de données</p>';
	exit();
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérifie si un pseudo est déjà utilisé
function db_pseudo_exists($pseudo) {
	global $db;
	$query = 'SELECT COUNT(usr_id) FROM users WHERE usr_pseudo = :pseudo';
	$statement = $db->prepare($query);
	$statement->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_NUM);
	return $response[0];
}

// Vérifie si un mail est déjà utilisé
function db_mail_exists($mail) {
	global $db;
	$query = 'SELECT COUNT(usr_id) FROM users WHERE usr_mail = :mail';
	$statement = $db->prepare($query);
	$statement->bindValue('mail', $mail, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_NUM);
	return $response[0];
}

// Création d'un utilisateur
function db_create_user($pseudo, $password, $mail, $key) {
	global $db;
	$query = 'INSERT INTO users (usr_pseudo, usr_password, usr_mail, usr_key) VALUES (:pseudo, :password, :mail, :key)';
	$statement = $db->prepare($query);
	$statement->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
	$statement->bindValue('password', $password, PDO::PARAM_STR);
	$statement->bindValue('mail', $mail, PDO::PARAM_STR);
	$statement->bindValue('key', $key, PDo::PARAM_STR);
	$status = $statement->execute();
	return $status;
}

// Cherche les infos d'un utilisateur, les renvoie s'il existe, renvoie false sinon
function db_connect_user($pseudo) {
	global $db;
	$query = 'SELECT usr_id, usr_password, usr_mail, usr_balance, usr_active FROM users WHERE usr_pseudo = :pseudo';
	$statement = $db->prepare($query);
	$statement->bindValue('pseudo', $pseudo, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_ASSOC);
	return $response;
}

function db_get_balance($id) {
	global $db;
	$query = 'SELECT usr_balance FROM users WHERE usr_id = :id';
	$statement = $db->prepare($query);
	$statement->bindValue('id', $id, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_NUM);
	return $response[0];
}

// Ajout ($add = true) ou retrait ($add = false) de $montant à l'utilisateur n° $id
function db_modify_balance($id, $amount, $add) {
	global $db;
	$query = 'UPDATE users SET usr_balance = usr_balance ';
	if ($add)
		$query .= '+';
	else
		$query .= '-';
	$query .= ' :amount WHERE usr_id = :id';

	$statement = $db->prepare ($query);
	$statement->bindValue('amount', $amount, PDO::PARAM_STR);
	$statement->bindValue('id', $id, PDO::PARAM_INT);
	$status = $statement->execute();
	return $status;	
}

// Obtenir le pseudo d'un utilsiateur à partir de son id
function db_get_user_pseudo($id) {
	global $db;
	$query = 'SELECT usr_pseudo FROM users WHERE usr_id = :id';
	$statement = $db->prepare($query);
	$statement->bindValue('id', $id, PDO::PARAM_INT);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_NUM);
	return $response[0];
}

// Vérifie que la clé correspond au mail renseigné en comptant le nombre de lignes qui correspondent
function db_check_validation_key($mail, $key) {
	global $db;
	$query = 'SELECT COUNT(usr_id) FROM users WHERE usr_mail = :mail AND usr_key = :key';
	$statement = $db->prepare($query);
	$statement->bindValue('mail', $mail, PDO::PARAM_STR);
	$statement->bindValue('key', $key, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_NUM);
	return $response[0];
}

function db_activate_account($mail) {
	global $db;
	$query = 'UPDATE users SET usr_active = 1 WHERE usr_mail = :mail';
	$statement = $db->prepare($query);
	$statement->bindValue('mail', $mail, PDO::PARAM_STR);
	$status = $statement->execute();
	return $status;
}

function db_create_auction($title, $image, $description, $begin_date, $end_date, $start_bid, $seller) {
	global $db;
	$query = 'INSERT INTO auctions(auc_title, auc_image, auc_description, auc_begindate, auc_enddate, auc_startbid, usr_id) VALUES (:title, :image, :description, :begindate, :enddate, :startbid, :seller)';
	$statement = $db->prepare($query);
	$statement->bindValue('title', $title, PDO::PARAM_STR);
	$statement->bindValue('image', $image, PDO::PARAM_STR);
	$statement->bindValue('description', $description, PDO::PARAM_STR);
	$statement->bindValue('begindate', $begin_date, PDO::PARAM_INT);
	$statement->bindValue('enddate', $end_date, PDO::PARAM_INT);
	$statement->bindValue('startbid', $start_bid, PDO::PARAM_STR);
	$statement->bindValue('seller', $seller, PDO::PARAM_INT);
	$status = $statement->execute();
	return $status;
}

// Retourne la liste des enchères d'une annonce, classées de la plus récente à la plus ancienne
function db_get_bids($id_auction, $last_only = false) {
	global $db;
	$query = 'SELECT bid_id, usr_id, bid_amount, bid_date FROM bids WHERE auc_id = :id_auction ORDER BY bid_date DESC';
	if ($last_only)
		$query .= ' LIMIT 1';

	$statement = $db->prepare($query);
	$statement->bindValue('id_auction', $id_auction, PDO::PARAM_INT);
	$statement->execute();
	return $statement;
}

function db_create_bid($id_buyer, $amount, $date, $id_auction) {
	global $db;
	$query = 'INSERT INTO bids(usr_id, bid_amount, bid_date, auc_id) VALUES (:usr_id, :amount, :bid_date, :auc_id)';
	$statement = $db->prepare($query);
	$statement->bindValue('usr_id', $id_buyer);
	$statement->bindValue('amount', $amount);
	$statement->bindValue('bid_date', $date);
	$statement->bindValue('auc_id', $id_auction);
	$status = $statement->execute();
	return $status;
}

function db_get_auction_by_id($id) {
	global $db;
	$query = 'SELECT auc_title, auc_image, auc_description, auc_begindate, auc_enddate, usr_id, auc_startbid, auc_active FROM auctions WHERE auc_id = :id';
	$statement = $db->prepare($query);
	$statement->bindValue('id', $id, PDO::PARAM_STR);
	$statement->execute();
	$response = $statement->fetch(PDO::FETCH_ASSOC);
	return $response;
}

function db_get_auctions_by_seller($id, $running) {
	global $db;
	$query = 'SELECT auc_id, auc_title, auc_image, auc_description, auc_begindate, auc_enddate, auc_startbid, auc_active
	FROM auctions
	WHERE usr_id = :id
	AND auc_enddate';
	if ($running)
		$query .= ' > '.time();
	else
		$query .= ' < '.time();

	$statement = $db->prepare($query);
	$statement->bindValue('id', $id, PDO::PARAM_INT);
	$statement->execute();
	return $statement;
}

function db_get_auctions_by_buyer($id, $running) {
	global $db;
	$query = 'SELECT auc_id, auc_title, auc_image, auc_description, auc_begindate, auc_enddate, usr_id, auc_startbid, auc_active
		FROM auctions
		WHERE auc_id IN (SELECT auc_id FROM bids WHERE usr_id = :id)
		AND auc_enddate';
	if ($running)
		$query .= ' > '.time();
	else
		$query .= ' < '.time();

	$statement = $db->prepare($query);
	$statement->bindValue('id', $id, PDO::PARAM_INT);
	$statement->execute();
	return $statement;
}

// Renvoie $quantity annonces en cours à partir du n° $start, classées par date de fin décroissante
function db_get_auctions($start, $quantity) {
	global $db;
	$query = 'SELECT auc_id, auc_title, auc_image, auc_description, auc_begindate, auc_enddate, usr_id, auc_startbid, auc_active
		FROM auctions
		WHERE auc_enddate > :current_time
		ORDER BY auc_enddate DESC
		LIMIT :quantity OFFSET :start';
	$statement = $db->prepare($query);
	$statement->bindValue('current_time', time(), PDO::PARAM_INT);
	$statement->bindValue('quantity', $quantity, PDO::PARAM_INT);
	$statement->bindValue('start', $start, PDO::PARAM_INT);
	$statement->execute();
	return $statement;
}

function db_close_auction($id) {
	global $db;
	$query = 'UPDATE auctions SET auc_active = false WHERE auc_id = :id';
	$statement = $db->prepare($query);
	$statement->bindValue('id', $id, PDO::PARAM_INT);
	$status = $statement->execute();
	return $status;
}