<?php

class User {
	private $id;
	private $pseudo;
	private $mail;
	private $balance;

	public function __construct($id, $pseudo, $mail, $balance) {
		$this->setId($id);
		$this->setPseudo($pseudo);
		$this->setMail($mail);
		$this->setBalance($balance);
	}

	public function getId () {
		return $this->id;
	}

	public function getPseudo () {
		return $this->pseudo;
	}

	public function getMail () {
		return $this->mail;
	}

	public function getBalance () {
		return $this->balance;
	}

	private function setId ($id) {
		$this->id = $id;
	}

	private function setPseudo ($pseudo) {
		$this->pseudo = $pseudo;
	}

	private function setMail ($mail) {
		$this->mail = $mail;
	}

	public function setBalance ($balance) {
		$this->balance = $balance;
	}

	/*	Fonction à appeler lors de l'inscription d'un utilisateur
	*	Paramètres : le pseudo, les deux mots de passe et le mail
	*	La fonction vérifie que les données sont correctes pour la création de l'utilisateur
	*	Si oui, elle l'enregistre en BDD et renvoie "OK"
	*	Si non, renvoie le message d'erreur correspondant
	*/
	public static function inscription ($pseudo, $password1, $password2, $mail) {
		if (empty($pseudo))
			$message = 'NO_PSEUDO';
		else if (empty($password1))
			$message = 'NO_PASSWORD';
		else if (empty($mail))
			$message = 'NO_MAIL';
		// Si le pseudo contient un caractère autre que chiffre, lettre, underscore
		else if (preg_match('/\W/', $pseudo))
			$message = 'INCORRECT_PSEUDO';
		else if (db_user_exists($pseudo))
			$message = 'UNAVAILABLE_PSEUDO';
		else if ($password1 != $password2)
			$message = 'PASSWORD_UNMATCH';
		// Toutes les données ont été validées, création du compte
		else {
			// Génération de la clé pour la validation par mail
			$key = md5(uniqid());
			if (db_create_user($pseudo, password_hash($password1, PASSWORD_DEFAULT), $mail, $key)) {
				include 'mail_validation_send.php';
				send_validation_mail ($mail, $key);
				$message = 'OK';
			}
			else 
				$message = 'ERROR_CREATION';
		}

		return $message;
	}

	/*	Fonction à appeler lors de la connexion de l'utilisateur
	*	Paramètres :
	*		le pseudo
	*		le mot de passe (non haché si vient d'un formilaure, hashé si vient d'un cookie)
	*		l'information de si l'identification est faite par un cookie
	*		l'indication que l'utilisateur veut une identification permanente
	*	La fonction vérifie si un utilisateur correspondant à la combinaison pseudo + mot de passe
	*	Si oui, elle crée un objet utilisateur stocké dans $_SESSION et renvoie true
	*	Si non, elle renvoie false
	*/
	public static function connection ($pseudo, $password, $cookie, $stay_connected = false) {
		$infos = db_connect_user($pseudo);

		// Vérification que l'utilisatur existe
		if ($infos == false)
			$status = 'INCORRECT_INFOS';
		// Connexion par cookie : vérification que le mot de passe est identique à celui de la bdd
		else if ($cookie && $password != $infos['usr_password'])
			$status = 'INCORRECT_INFOS';
		// COnnexion par formulaire : vérification que le mot de passe saisi est le bon
		else if (!$cookie && !password_verify($password, $infos['usr_password']))
			$status = 'INCORRECT_INFOS';
		// Vérification que l'utilisateur est actif
		else if (!$infos['usr_active'])
			$status = 'NOT_ACTIVE';
		// Identification réussie
		else {
			$status = 'OK';
			$_SESSION['user'] = new User($infos['usr_id'], $pseudo, $infos['usr_mail'], $infos['usr_balance']);

			if ($stay_connected) { // Durée de vie des cookies : 20 jours
				setcookie ('pseudo', $pseudo, time() + 60*60*24*30);
				setcookie ('password', $infos['usr_password'], time() + 60*60*24*30);
			}
		}

		return $status;
	}

	// Fonction à appeler pour la déconnexion de l'utilisateur
	public function disconnection () {
		unset($_SESSION['user']);
	}

	/*	Fonction à appeler lorsque l'utilisateur ajoute de l'argent à son solde
	*	Paramètre : la somme
	*	Sortie : code d'état de l'opération
	*/
	public function recharge_balance($amount) {
		$amount = (int)$amount;
		if ($amount > 0) {
			$response = db_update_user($this->getId(), $this->getMail(), $this->getBalance());
			if ($response) {
				$this->setBalance($this->getBalance() + $amount);
				$status = 'OK';
			}
			else
				$status = 'ERROR_DB';	
		}
		else {
			$status = 'ERROR_AMOUNT';
		}

		return $status;
	}
}

