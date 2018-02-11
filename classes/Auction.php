<?php

class Auction {
	private $id;
	private $title;
	private $image;
	private $description;
	private $begin_date;
	private $end_date;
	private $id_seller;
	private $pseudo_seller;
	private $start_bid;
	private $bids_list;
	private $active;

	public function get_id() {
		return $this->id;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_image() {
		return $this->image;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_begin_date() {
		return $this->begin_date;
	}

	public function get_end_date() {
		return $this->end_date;
	}

	public function get_id_seller() {
		return $this->id_seller;
	}

	public function get_pseudo_seller () {
		return $this->pseudo_seller;
	}

	public function get_start_bid() {
		return $this->start_bid;
	}

	public function get_bids_list() {
		return $this->bids_list;
	}

	public function get_active() {
		return $this->active;
	}

	private function set_id($id) {
		$this->id = $id;
	}

	private function set_title($title) {
		$this->title = htmlspecialchars($title);
	}

	private function set_image($image) {
		$this->image = $image;
	}

	private function set_description($description) {
		$this->description = htmlspecialchars($description);
	}

	private function set_begin_date($begin_date) {
		$this->begin_date = $begin_date;
	}

	private function set_end_date($end_date) {
		$this->end_date = $end_date;
	}

	private function set_id_seller($id_seller) {
		$this->id_seller = $id_seller;
	}

	private function set_pseudo_seller($pseudo_seller) {
		$this->pseudo_seller = $pseudo_seller;
	}

	private function set_start_bid($start_bid) {
		$this->start_bid = $start_bid;
	}

	private function set_active($active) {
		$this->active = $active;
	}

	public function get_current_bid() {
		if (is_null($this->get_bids_list()))
			$amount = $this->get_start_bid();
		else
			$amount = $this->get_bids_list()[0]->get_amount();

		return $amount;
	}

	public function get_id_current_buyer() {
		if (is_null($this->get_bids_list()))
			$id = null;
		else
			$id = $this->get_bids_list()[0]->get_id_buyer();

		return $id;
	}

	public function get_pseudo_current_buyer() {
		if (is_null($this->get_bids_list()))
			$pseudo = null;
		else
			$pseudo = $this->get_bids_list()[0]->get_pseudo_buyer();

		return $pseudo;
	}

	// Le constructeur n'est appelé que lors de la création d'une enchère à partir des données de la BDD
	public function __construct($id, $title, $image, $description, $begin_date, $end_date, $id_seller, $start_bid, $active) {
		$this->set_id($id);
		$this->set_title($title);
		$this->set_image($image);
		$this->set_description($description);
		$this->set_begin_date($begin_date);
		$this->set_end_date($end_date);
		$this->set_id_seller($id_seller);
		$this->set_pseudo_seller(db_get_user_pseudo($id_seller));
		$this->set_start_bid($start_bid);
		$this->set_active($active);

		// Récupération de la liste des enchères
		$bids_list = db_get_bids($this->get_id());

		while ($bid = $bids_list->fetch(PDO::FETCH_ASSOC)) {
			$this->add_bid_element($bid['bid_id'], $bid['bid_amount'], $bid['bid_date'], $bid['usr_id']);
		}
	}

	//	Ajoute une enchère à la variable $bids_list
	public function add_bid_element($bid, $amount, $date, $buyer) {
		$this->bids_list[] = new Bid($bid, $amount, $date, $buyer);
	}

	// Ajoute une nouvelle enchère au début du tableau
	public function add_new_bid($bid) {
		array_unshift($this->bids_list, $bid);
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
	public static function create_auction($title, $image, $description, $end_date, $id_seller, $start_bid) {
		// Récupérer l'extention du fichier image
		$extension = strtolower(pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION));
		$authorized_extensions = ['jpg', 'jpeg', 'gif', 'png'];

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
		else if (!in_array($extension, $authorized_extensions))
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
				require 'library/resize_image.php';
				resize_image($_FILES[$image]['tmp_name'], $image_width, $image_height, $extension, $file_name);

				// Supprimer l'image envoyée en upload
				unlink($_FILES[$image]['tmp_name']);
			}
			else {
				// Déplacer l'image envoyée dans le fichier images
				move_uploaded_file($_FILES[$image]['tmp_name'], 'images/'.$file_name);
			}

			// Créer l'enchère dans la base de données
			if (db_create_auction($title, $file_name, $description, $begin_date, $end_date, (float)$start_bid, (int)$id_seller))
				$message = 'OK';
			else
				$message = 'ERROR_CREATION';
		}

		return $message;
	}

	public function create_new_bid($amount) {
		$date_bid = time();

		// Vérifier qu'un utilisateur est conencté
		if (!isset($_SESSION['user']))
			$message = 'NO_USER_CONNECTED';
		else {
			$buyer = $_SESSION['user']->get_id();

			// Vérifier que l'annonce n'est pas terminée
			if ($date_bid > $this->get_end_date())
				$message = 'AUCTION_CLOSED';
			// Vérifier que l'utilisateur qui enchérit n'est pas le vendeur
			else if ($buyer == $this->get_id_seller())
				$message = 'IS_SELLER';
			// Vérifier que le montant est strictement supérieur à celui de l'enchère en cours
			else if ($amount < $this->get_current_bid())
				$message = 'BID_INFERIOR';
			// Vérifier que l'acheteur a un solde suffisant pour enchérir
			else if ($_SESSION['user']->get_checked_balance() < $amount)
				$message = 'INSUFFICIENT_BALANCE';
			else {
				$bid = BID::create($amount, $date_bid, $buyer, $this->get_id());

				// Vérifier que l'insertion en BDD s'est bien déroulée
				if (!$bid) 
					$message = 'ERROR_CREATION';
				else {
					// Enlever le montant du solde de l'utilisateur
					$_SESSION['user']->modify_balance($amount, false);

					// On rend à celui qui avait la plus haute enchère le montant qu'il avait immobilisé
					if (!is_null($this->get_bids_list()))
						db_modify_balance($this->get_bids_list()[0]->get_id_buyer(), $this->get_bids_list()[0]->get_amount(), true);

					// On ajoute la nouvelle enchère à la liste des enchères de l'annonce
					$this->add_new_bid($bid);

					$message = 'OK';
				}
			}
		}		

		return $message;
	}

	// Traiter une enchère terminée pour que l'utilisateur récupère le montant de la vente
	public function close_auction() {
		$seller = $_SESSION['user']->get_id();

		// Vérifier que l'utilisateur est bien le vendeur
		if ($seller != $this->get_id_seller())
			$message = 'NOT_SELLER';
		// Vérifier que l'annonce est terminée
		else if ($this->get_end_date() > time())
			$message = 'RUNNING';
		// Vérifier que l'annonce n'a pas déjà été traitée
		else if (!$this->get_active())
			$message = 'ALREADY_CLOSED';
		else {
			// Vérifier qu'il y a eu au moins une enchère
			if (is_null($this->get_bids_list()))
				$message = 'NO_BID';
			else {
				$_SESSION['user']->modify_balance($this->get_current_bid(), true);
				$message = 'OK';
			}			
			db_close_auction($this->get_id());
		}

		return $message;
	}
}