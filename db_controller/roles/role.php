<?php
class Role {
  	private $conn;
  	private $table_name = 'role';
  	private $table_config = 'config';
  	private $table_control = 'control';

  	private $sysadmin = 'sysadmin';

  	public $id;
  	public $name;
  	public $status;

 	public $type;

  	public $role_id;
	public $permission;
	public $menu_arr;

	public function __construct($db) {
    		$this->conn = $db;
  	}

  	function checkRoleName() {
    		$query = 'SELECT
              		`id`, `role_name`, `role_status` FROM
              		`' . $this->table_name . '`
              		WHERE `role_name` = :name';
    		$stmt = $this->conn->prepare($query);
    		$stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
    		$stmt->execute();
    		return $stmt;
  	}

  	function create() {
    		$query = 'INSERT INTO ' . $this->table_name . '(
        		`role_name`,
        		`role_status`) VALUES (
        			:name,
        			:status
      			);';

    		$stmt = $this->conn->prepare($query);

    		$this->name = htmlspecialchars(strip_tags($this->name));

    		$stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    		$stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);
		
    		if ($stmt->execute()){
			$last_role = $this->checkRoleName();
      			$role = $last_role->fetch(PDO::FETCH_ASSOC);
        		$this->role_id = array($role['id']);

      			if ($this->createControl()) {
        			return true;
      			} else {
        			echo '<pre> <br/>Control Error<br/>';
  				echo '</pre>';
  				return false;
      			}

    		} else {
      			echo '<pre> <br/>Role Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
    		}
  	}

  	function readAll() {
    		$query = 'SELECT `id`, `role_name`, `role_status` FROM `' . $this->table_name .'`
     			WHERE `role_name` <> :name ORDER BY `id` DESC;';
		$stmt = $this->conn->prepare($query);
    		$stmt->bindParam(":name", $this->sysadmin, PDO::PARAM_STR);
    		$stmt->execute();
    		return $stmt;
  	}

  	function readOne() {
    		$query = 'SELECT
      			`id`, `role_name`, `role_status` FROM
      			' . $this->table_name . '
      			 WHERE id = ?
      			LIMIT
        		0, 1;';

    		$stmt = $this->conn->prepare($query);
    		$stmt->bindParam(1, $this->id);
    		$stmt->execute();

    		$row = $stmt->fetch(PDO::FETCH_ASSOC);
    		$this->id = $row['id'];
    		$this->name = $row['role_name'];
    		$this->status = $row['role_status'];
  	}

  	function readAllType() {
    		$query = 'SELECT DISTINCT `config_type`
    			FROM `' . $this->table_config .'`
     			WHERE NOT FIND_IN_SET(`config_type`, :array)
      			AND  `config_status` = 1
      			ORDER BY `config_type`;';
    		$stmt = $this->conn->prepare($query);
    		$sys_string = implode(',', $this->sys);
    		$stmt->bindParam(":array", $sys_string);
    		if($stmt->execute()) {
        		return $stmt;
    		} else {
      			echo 'Yae';
    		}

  	}

  	function readMenuConfig() {
    		$query = 'SELECT `id`, `config_value`, `config_type`,
    			`config_status` FROM `' . $this->table_config .'`
     			WHERE `config_type` = :type
      			AND `config_status` = 1
       			ORDER BY `id`;';
    		$stmt = $this->conn->prepare($query);
    		$stmt->bindParam(":type", $this->type, PDO::PARAM_STR);
    		if($stmt->execute()) {
        		return $stmt;
    		} else {
      			echo 'Yae';
    		}
  	}

  	function update() {
      		$query = 'UPDATE
       		 ' . $this->table_name . ' SET
          	`role_name` = :name,
          	`role_status` = :status
          	 WHERE id = :id;';
      		$stmt = $this->conn->prepare($query);
      		$this->name = htmlspecialchars(strip_tags($this->name));

      		$stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
      		$stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);
      		$stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

      		if ($stmt->execute()) {
			$this->role_id = array($this->id);
        		if ($this->deleteControl()) {
          			if ($this->createControl()) {
            				return true;
          			} else {
            				echo '<pre> <br/>Control Error<br/>';
      					echo '</pre>';
      					return false;
          			}
        		}
      		} else {
        		echo '<pre> <br/>Update Role Error<br/>';
  				print_r($stmt->errorInfo());
  			echo '</pre>';
  			return false;
      		}
  	}

  	function delete() {
    		$query = ' DELETE FROM
     			 ' . $this->table_name . '
     			  WHERE FIND_IN_SET(id, :array);';

    		$stmt = $this->conn->prepare($query);
    		$ids_string = implode(',', $this->id);
    		$stmt->bindParam(':array', $ids_string);

		if ($stmt->execute()) {
      			$this->role_id = $this->id;
      			if($this->deleteControl()) {
        			return true;
      			} else {
        			echo '<pre> <br/>Control Error<br/>';
        			echo '</pre>';
        			return false;
      			}
    		} else {
      			echo '<pre> <br/>Delete Role Error<br/>';
        			print_r($stmt->errorInfo());
      			echo '</pre>';
      			return false;
    		}
  	}


  	function createControl() {
		$query = 'INSERT INTO ' . $this->table_control . ' (
			`role_id`,`menu_id`, `config_id`) VALUES';
		$this->permission = array($this->permission);
		$menuCount = 0;
		print_r(count($this->menu_arr));
		for ($h = 0; $h < count($this->menu_arr); $h++) {
			if (count($this->permission[0]->{$this->menu_arr[$h]}->data) > 0) {
				$menuCount++;
			}
		}
		print_r($menuCount);
		for ($i = 0; $i < count($this->menu_arr); $i++) {

			$menu = $this->permission[0]->{$this->menu_arr[$i]}->data;
			if (count($menu) > 0) {
				for ($j = 0; $j < count($menu); $j++) {
					$query .= '(:role_id' . $i . $j .', :menu_id' . $i . $j .', :config_id' . $i . $j .')';
					$query .= $j < count($menu) - 1 ? ',' : '';
				}
			$query .= $i < ($menuCount - 1) ? ',' : '';
			}

		}
		$stmt = $this->conn->prepare($query);
		for ($p = 0; $p < count($this->menu_arr); $p++) {
			$menu = $this->permission[0]->{$this->menu_arr[$p]}->data;
			if(count($menu) > 0) {
				for ($m = 0; $m < count($menu); $m++) {
					$stmt->bindParam(':role_id' . $p . $m, $this->role_id[0]);
					$stmt->bindParam(':menu_id' . $p . $m, $this->permission[0]->{$this->menu_arr[$p]}->id);
					$stmt->bindParam(':config_id'. $p . $m, $menu[$m]);
				}
			}
		}
		if ($stmt->execute()) {
			return true;
		} else {
			echo '<pre> Create Control Error.';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function readForRole() {
		$query = 'SELECT ctrl.`id`, `role_id`, `menu_id`, `config_id`, mnu.config_value as menu_value  FROM  ' . $this->table_control . ' as ctrl 
			INNER JOIN `config` as mnu ON ctrl.menu_id = mnu.id 
			WHERE `role_id` = :role_id';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':role_id', $this->role_id, PDO::PARAM_INT);
		if ($stmt->execute()) {
			return $stmt;
		} else {
			echo '<pre> read control for Role Error <br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return;
		}
	}

	function deleteControl() {
		$query = 'DELETE FROM ' . $this->table_control . '
				WHERE FIND_IN_SET(`role_id`, :array)';
		$stmt = $this->conn->prepare($query);
		$id_arr = implode(',', $this->role_id);
		$stmt->bindParam(":array", $id_arr);
		if ($stmt->execute()) {
        		return true;
        	} else {
        		echo '<pre> Delete Control<br/>';
        			print_r($stmt->errorInfo());
        		echo '</pre>';
        		return false;
        	}
	}

}
?>
