<?php

class Bid {
	private $id;
	private $amount;
	private $date;
	private $id_buyer;

	public function get_id() {
		return $this->id;
	}

	public function get_amount() {
		return $this->amount;
	}

	public function get_date() {
		return $this->date;
	}

	public function get_id_buyer() {
		return $this->id_buyer;
	}

	private function set_id($id) {
		$this->id = $id;
	}

	private function set_amount($amount) {
		$this->amount = $amount;
	}

	private function set_date($date) {
		$this->date = $date;
	}

	private function set_id_buyer($id_buyer) {
		$this->id_buyer = $id_buyer;
	}

	public function __construct($id, $amount, $date, $buyer) {
		$this->set_id($id);
		$this->set_amount($amount);
		$this->set_date($date);
		$this->set_id_buyer($buyer);
	}
	
	public static function create($amount, $date, $buyer, $id_auction) {
		if (db_create_bid($buyer, $amount, $date, $id_auction)) {
			global $db;
			$id = $db->lastInsertId();
			$bid = new Bid($id, $amount, $date, $buyer);
		}
		else
			$bid = false;

		return $bid;
	}
}