<?php
class Role {
  private $conn;
  private $table_name = 'role';
  private $sysadmin = 'sysadmin';
  public $id;
  public $name;
  public $status;


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
        `role_status`
      ) VALUES (
        :name,
        :status
      );';

    $stmt = $this->conn->prepare($query);

    $this->name = htmlspecialchars(strip_tags($this->name));

    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);

    if ($stmt->execute()){
      return true;
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
        return true;
      } else {
        echo '<pre> <br/>Product Type Error<br/>';
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
      return true;
    } else {
      echo '<pre> <br/>Product Type Error<br/>';
        print_r($stmt->errorInfo());
      echo '</pre>';
      return false;
    }
  }

}
?>
