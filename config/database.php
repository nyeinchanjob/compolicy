<?php
class Database {
	//private $host = '10.72.35.178';
	//private $db_name = 'crud';
	private $host = 'localhost';
	private $db_name = 'outletsurvey';
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
