<?php

class User {
	private $id;
	private $pseudo;
	private $password;
	private $mail;
	private $balance;

	public function __construct() {
		echo '<p>Utilisateur créé</p>';
	}

	public function getId () {
		return $this->id;
	}

	public function getPseudo () {
		return $this->pseudo;
	}

	public function getPassword () {
		return $this->password;
	}

	public function getMail () {
		return $this->mail;
	}

	public function getBalance () {
		return $this->balance;
	}

	public function setId ($id) {
		$this->id = $id;
	}

	public function setPseudo ($pseudo) {
		$this->pseudo = $pseudo;
	}

	public function setPassword ($password) {
		$this->password = $password;
	}

	public function setMail ($mail) {
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
		else if (preg_match('/\W/', $pseudo)) // Si le pseudo contient un caractère autre que chiffre, lettre, un derscore
			$message = 'INCORRECT_PSEUDO';
		else if (user_exists($pseudo))
			$message = 'UNAVAILABLE_PSEUDO';
		else if ($password1 != $password2)
			$message = 'PASSWORD_UNMATCH';
		else if (!preg_match('/^[\w\.]+@[\w\.]+\.[\w\.]+$/', $mail)) // Cherche bidule@truc.chose
			$message = 'INCORRECT_MAIL';
		else if (create_user($pseudo, $password1, $mail)) // Tout est bon, création du compte dans la BDD
			$message = 'OK';
		else 
			$message = 'ERROR_CREATION';

		return $message;
	}


}