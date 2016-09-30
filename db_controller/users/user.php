<?php
class User {
    private $conn;
	private $table_name = 'user';
    private $table_role = 'role';
    private $surveyor = 'surveyor';

	public $id;
	public $name;
	public $department;
	public $position;
    public $role_id;
    public $role_name;
    public $username;
    public $password;
    public $user_status;

    public $issysadmin;

    public function __construct($db) {
    	$this->conn = $db;
	}

	function checkLoginName() {
    	$query = 'SELECT
                `id`, `name`, `department`, `position`, `role_id`, `username`, `password`, `user_status`
            FROM
			         `' . $this->table_name . '`
            WHERE
			         `username` = :username;';
    	$stmt = $this->conn->prepare($query);
    	$stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
    	$stmt->execute();
    	return $stmt;
	}

	function checkUserName() {
		$query = 'SELECT
			     `id`, `name`, `department`, `positoin`, `role_id`, `username`, `password`, `user_status`
			FROM
				`' . $this->table_name . '`
			WHERE
				`name` = :name;';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt;
	}

	function login() {
		$query = 'SELECT
				`'. $this->table_name .'`.`id`, `name`, `department`, `position`, `' . $this->table_name .  '`.`role_id`, `' . $this->table_role . '`.`role_name`, `username`, `password`, `user_status`
			FROM
			    `' . $this->table_name . '`
            INNER JOIN `' . $this->table_role . '` ON `' . $this->table_name . '`.`role_id` = `' . $this->table_role . '`.`id`
			WHERE
				`username` = :username AND
				`password` = :password AND
				`user_status` = 1 AND `delete_status` = 0;';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
		if ($stmt->execute()) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->department = $row['department'];
			$this->position = $row["position"];
			$this->role_id = $row['role_id'];
			$this->role_name = $row['role_name'];
			$this->username = $row['username'];
			$this->password = $row['password'];
			$this->user_status = $row['user_status'];
		} else {
			echo '<pre> <br/> Login Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
		}
	}

    function create() {
    	$query = 'INSERT INTO `' . $this->table_name . '` (
        			`name`,
        			`department`,
					`position`,
					`role_id`,
					`username`,
					`password`,
        			`user_status`
        		) VALUES (
        			:name,
        			:department,
					:position,
					:role_id,
					:username,
					:password,
                    :user_status
                );';

		$stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->department = htmlspecialchars(strip_tags($this->department));
		$this->position = htmlspecialchars(strip_tags($this->position));
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":department", $this->department, PDO::PARAM_STR);
		$stmt->bindParam(":position", $this->position, PDO::PARAM_STR);
		$stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_INT);
		$stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
		$stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
	    $stmt->bindParam(":user_status", $this->user_status, PDO::PARAM_BOOL);

        if ($stmt->execute()){
        	return true;
        } else {
        	echo '<pre> <br/>User Create Error<br/>';
                print_r($stmt->errorInfo());
    		echo '</pre>';
    		return false;
        }
    }

    function readAllRole() {
    	$query = 'SELECT
                `id`, `role_name`, `role_status`
            FROM
                `' . $this->table_role .'`
            WHERE
				`role_status` = 1 ';

		if ($this->issysadmin == false) {
			$query .= ' AND `role_name`= :surveyor';
		}
        $query .=' ORDER BY
		    `role_name`;';
		$stmt = $this->conn->prepare($query);
		if ($this->issysadmin==false) {
			$stmt->bindParam(":surveyor", $this->surveyor, PDO::PARAM_STR);
		}
		$stmt->execute();
		return $stmt;
    }


    function readAll() {
    	$query = 'SELECT
				`id`,`name`, `department`, `position`, `role_id`, `username`, `password`, `user_status`
            FROM `' . $this->table_name .'`
            WHERE `delete_status` = 0 AND `role_id` =:role_id
            ORDER BY
                `id` Desc';
		$stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_STR);
		$stmt->execute();
        return $stmt;
    }

    function readOne() {
    	$query = 'SELECT
            	`id`, `name`, `department`, `position`, `role_id`, `username`, `password`, `user_status`
	        FROM
				`' . $this->table_name . '`
            WHERE
			    `id` = ?
            LIMIT
                0, 1;';

		$stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->name = $row['name'];
		$this->department = $row['department'];
        $this->position = $row["position"];
		$this->role_id = $row['role_id'];
		$this->username = $row['username'];
		$this->password = $row['password'];
		$this->user_status = $row['user_status'];
    }

    function update() {
    	$query = 'UPDATE `' . $this->table_name . '` SET
                `name` = :name,
                `department` = :department,
			    `position` = :position,
			    `role_id` = :role_id,
			    `username` = :username,
			    `password` = :password,
                `user_status` = :user_status
            WHERE
				`id` = :id;';
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->department = htmlspecialchars(strip_tags($this->department));
		$this->position = htmlspecialchars(strip_tags($this->position));
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":department", $this->department, PDO::PARAM_STR);
		$stmt->bindParam(":position", $this->position, PDO::PARAM_STR);
		$stmt->bindParam(":role_id", $this->role_id, PDO::PARAM_INT);
		$stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
		$stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
	    $stmt->bindParam(":user_status", $this->user_status, PDO::PARAM_BOOL);

		if ($stmt->execute()) {
        	return true;
        } else {
        	echo '<pre> <br/>User Update Error<br/>';
        		print_r($stmt->errorInfo());
        	echo '</pre>';
        	return false;
        }
    }

    function delete() {
    	$query = 'UPDATE `' . $this->table_name . '` SET
                `delete_status` = 1
            WHERE
			FIND_IN_SET(`id`, :array);';

        	$stmt = $this->conn->prepare($query);
        	$ids_string = implode(',', $this->id);
        	$stmt->bindParam(':array', $ids_string);

        	if ($stmt->execute()) {
            	return true;
        	} else {
            	echo '<pre> <br/>User Delete Error<br/>';
            		print_r($stmt->errorInfo());
            	echo '</pre>';
            	return false;
        	}
    }
}
?>
