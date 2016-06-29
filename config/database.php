<?php
class Database {
	private $host = 'localhost';
	private $db_name = 'compolicy';
	private $user_name = 'super';
	private $password = 'suPer@123';

	public $conn;

	public function getConn() {
		$this->conn = null;
		try {
			$this->conn = new PDO('mysql:host='. $this->host .
			'; dbname=' . $this->db_name, $this->user_name, $this->password);
		} catch (PDOException $ex) {
			echo 'Connection Error: ' . $ex->getMessage();
		}
		return $this->conn;
	}
}
?>
