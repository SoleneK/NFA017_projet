<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require 'config_mail.php';

function send_validation_mail ($mail_address, $key) {
	$mail = new PHPMailer(true); 

	//Server settings
	//$mail->SMTPDebug = 2;
	$mail->isSMTP();
	$mail->Host = 'ssl0.ovh.net';
	$mail->SMTPAuth = true;
	$mail->Username = MAIL_USERNAME;
	$mail->Password = MAIL_PASSWORD;
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;

	//Recipients
	$mail->setFrom(MAIL_USERNAME, 'Le Bazar des Merveilles');
	$mail->addAddress($mail_address);
	$mail->addReplyTo(MAIL_USERNAME, 'Le Bazar des Merveilles');

	//Content
	$mail->isHTML(true);
	$mail->Subject = utf8_decode('Création de compte sur Le Bazar des Merveilles');

	$host = 'http://'.$_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$link = $host.$uri.'/mail_validation.php?mail='.$mail_address.'&key='.$key;

	$mail->Body = '<p>Bienvenue sur le Bazar des Merveilles !</p>
	<p>Pour valider votre inscription, il vous suffit de cliquer sur le lien suivant : <a href="'.$link.'">'.$link.'</a> ou de le recopier dans votre navigateur.</p>
	<p>&Agrave; bient&ocirc;t !</p>';
	$mail->AltBody = 'Bienvenue sur le Bazar des Merveilles !
	Pour valider votre inscription, il vous suffit de recopier le lien suivant dans votre navigateur : '.$link.'
	&Agrave; bient&ocirc;t ! !';

	$mail->send();
}



