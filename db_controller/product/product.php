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

	function readOne() {
		$query = 'SELECT
			id, product_code, product_name, product_status
			FROM
			' . $this->table_name . '
			WHERE id = ?
			LIMIT
				0, 1';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC	);

		$this->id = $row['id'];
		$this->code = $row['product_code'];
		$this->name = $row['product_name'];
		$this->status = $row['product_status'];
	}

	function update() {
		$query = 'UPDATE
		' . $this->table_name . ' SET
				product_code = :code,
				product_name = :name,
				product_status = :status
			WHERE
				id = :id
		';

		$stmt = $this->conn->prepare($query);
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":code", $this->code);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":status", $this->status);
		$stmt->bindParam(":id", $this->id);

		if ($stmt->execute()) {
			return true;
		} else {
			echo '<pre>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function delete() {
		$query = 'DELETE FROM
			' . $this->table_name . '
			WHERE FIND_IN_SET(id, :array)
		';

		$stmt = $this->conn->prepare($query);
		$ids_string = implode(',', $this->id);
		$stmt->bindParam(':array', $ids_string);

		if ($stmt->execute()) {
			return true;
		} else {
			echo '<pre>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

}
?>
