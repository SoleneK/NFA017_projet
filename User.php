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
}