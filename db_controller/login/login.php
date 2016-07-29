<?php
class Login {
	private $conn;
	private $table_name = 'login';

	public $id;
	public $username;
	public $password;
	public $user_id;
	public $status;

	public $fname;
	public $lname;
	public $role_id;
	public $position;
	public $rname;

	public function __construct($db) {
		$this->conn = $db;
	}

	function create() {
		$query = 'INSERT INTO
			'. $this->table_name . '
			SET
				username =:username,
				password =:password,
				user_id =:user_id,
				login_status =:status';

		$stmt = $this->conn->prepare($query);
		// posted values
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->password = htmlspecialchars(strip_tags($this->password));
		$this->user_id = htmlspecialchars(strip_tags($this->user_id));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":password", $this->password);
		$stmt->bindParam(":user_id", $this->user_id);
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

	function readOne() {
		$query = 'SELECT
			id, username, password, user_id, login_status
			FROM
			' . $this->table_name . '
			WHERE id = ? and login_status = 1
			LIMIT
				0, 1';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC	);

		$this->id = $row['id'];
		$this->code = $row['username'];
		$this->name = $row['password'];
		$this->user_id = $row['user_id'];
		$this->status = $row['login_status'];
	}

	function checkLogin() {
		$query = 'SELECT
			lg.id, lg.username, lg.password, lg.user_id, lg.login_status,
			ur.first_name, ur.last_name, ur.position, ur.role_id,
			rl.role_name FROM
			' . $this->table_name . ' as lg
			INNER JOIN user AS ur ON lg.user_id = ur.id
			INNER JOIN role AS rl ON ur.role_id = rl.id
			WHERE
				lg.username = :username AND
				lg.password = :password AND
				lg.login_status = 1
			LIMIT
				0,1;';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':password', $this->password);
		if ($stmt->execute()) {
			return $stmt;
		} else {
			echo '<pre>';
				echo($stmt->errorInfo());
			echo '</pre>';
			return;
		}
	}

	function update() {
		$query = 'UPDATE
		' . $this->table_name . ' SET
				username = :username,
				password = :password,
				login_status = :status
			WHERE
				id = :id
		';

		$stmt = $this->conn->prepare($query);
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->password = htmlspecialchars(strip_tags($this->password));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":password", $this->password);
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
