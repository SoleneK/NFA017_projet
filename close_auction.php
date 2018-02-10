<?php

require 'init.php';
require 'auction_functions.php';

// Si l'utilisateur n'est pas connecté, redirection
if (!isset($_SESSION['user'])){
	$host = 'http://'.$_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header('Location: '.$host.$uri.'/index1.php', TRUE, 307);
}

include 'vue/header.php';

if (!isset($_GET['id']))
	echo 'Pas d\'annonce';
else {
	$auction = get_auction_by_id((int)$_GET['id']);
	echo $auction->close_auction();
}

include 'vue/footer.php';
