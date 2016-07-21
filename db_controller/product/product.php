<?php
class Product {
	private $conn;
	private $table_name = 'product';

	public $id;
	public $code;
	public $name;
	public $status;

	public function __construct($db) {
		$this->conn = $db;
	}

	function create() {
		$query = 'INSERT INTO
			'. $this->table_name . '
			SET
				product_code =:code,
				product_name =:name,
				product_status =:status';

		$stmt = $this->conn->prepare($query);
		// posted values
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":code", $this->code);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":status", $this->status);
		// execute query
		if ($stmt->execute()) {
			return true;
		} else {
			echo '<pre>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function readAll() {
		$query = 'SELECT
				id, product_code, product_name, product_status
			FROM
			' . $this->table_name . '
			ORDER BY
				id DESC';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

}
?>
