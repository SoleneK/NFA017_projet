<?php

class Auction {
	private $id;
	private $title;
	private $image;
	private $description;
	private $begin_date;
	private $enddate;
	private $id_seller;
	private $start_bid;
	private $bids_list;
	private $active;

	public function __construct () {

	}

	/*	Fonction à appeler lors de la création d'une annonce
	*	Paramètres :
	*		le titre
	*		le nom du champ du fomulaire qui sert à télécharger l'image
	*		la description
	*		la date de fin de l'annonce, en format timestamp
	*		l'id du vendeur
	*		l'enchère de départ
	*	Retour : message d'information de la réussite de l'opération ou du problème rencontré
	*/ 
	public static function create_auction ($title, $image, $description, $end_date, $id_seller, $start_bid) {
		// Récupérer l'extention du fichier image
		$extension = strtolower(pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION));
		$authorized_extension = ['jpg', 'jpeg', 'gif', 'png'];

		$max_image_size = 1024 * 1024;

		$begin_date = time();
		$min_end_date = strtotime('+ 5 minutes'); // 5 minutes
		$max_end_date = strtotime('+ 1 month'); // 1 mois

		// Vérifier que tous les champs du formulaire sont remplis
		if (is_null($title))
			$message = 'NO_TITLE';
		else if ($_FILES[$image]['tmp_name'] == '')
			$message = 'NO_IMAGE';
		else if (is_null($description))
			$message = 'NO_DESCRIPTION';
		else if ($end_date == 0)
			$message = 'NO_END_DATE';
		else if (is_null($start_bid))
			$message = 'NO_START_BID';
		// Vérifier que le fichier image a une extension .jpg, .jpeg, .gif ou .png
		else if (!in_array($extension, $authorized_extension))
			$message = 'NOT_AN_IMAGE';
		// Vérifier que l'image ne pèse pas plus de 5 Mo
		else if (filesize($_FILES[$image]['tmp_name']) > $max_image_size)
			$message = 'IMAGE_TOO_BIG';
		// Vérifier que la durée de l'enchère est comprise entre les bornes fixées
		else if ($end_date < $min_end_date || $end_date > $max_end_date)
			$message = 'INCORRECT_END_DATE';
		// Vérifier que le montant de départ est positif (ou nul)
		else if ($start_bid < 0)
			$message = 'START_BID_NEGATIVE';
		else {
			$file_name = md5(uniqid()).'.'.$extension;
			// Si les dimensions de l'image dépassent 250px x 250px, la redimensionner
			list($image_width, $image_height) = getimagesize($_FILES[$image]['tmp_name']);
			if ($image_width > 250 || $image_height > 250) {
				// Créer une image rendimensionnée et l'enregistre dans le dosser images
				require 'resize_image.php';
				resize_image($_FILES[$image]['tmp_name'], $image_width, $image_height, $extension, $file_name);

				// Supprimer l'image envoyée en upload
				unlink ($_FILES[$image]['tmp_name']);
			}
			else {
				// Déplacer l'image envoyée dans le fichier images
				move_uploaded_file($_FILES[$image]['tmp_name'], 'images/'.$file_name);
			}

			// Créer l'enchère dans la base de données
			if (db_create_auction ($title, $file_name, $description, $begin_date, $end_date, (int)$start_bid, (int)$id_seller))
				$message = 'OK';
			else
				$message = 'ERROR_CREATION';
		}

		return $message;
	}
}