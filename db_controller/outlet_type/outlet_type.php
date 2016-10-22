<?php
class OutletType {
  private $conn;
  private $table_name = 'outlet_type';
  private $issysadmin = 'sysadmin';

  public $id;
  public $name;
  public $is_active;


  public function __construct($db) {
    $this->conn = $db;
  }

  function checkOutletTypeName() {
    $query = 'SELECT
              `id`, `name`, `is_active` FROM
              `' . $this->table_name . '`
              WHERE `name` = :name';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt;
  }

  function create() {
    $query = 'INSERT INTO ' . $this->table_name . '(
        `name`,
        `is_active`
      ) VALUES (
        :name,
        :is_active
      );';

    $stmt = $this->conn->prepare($query);

    $this->value = htmlspecialchars(strip_tags($this->name));

    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":is_active", $this->is_active, PDO::PARAM_BOOL);

    if ($stmt->execute()){
      return true;
    } else {
      echo '<pre> <br/>Outlet Type Create Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
    }
  }

  function readAll() {
    $query = 'SELECT `id`, `name`, `is_active`
     FROM `' . $this->table_name .'`
     ORDER BY `id` DESC;';
    $stmt = $this->conn->prepare($query);
    if($stmt->execute()) {
        return $stmt;
    }
  }

  function readOne() {
    $query = 'SELECT
      `id`, `name`, `is_active` FROM
      ' . $this->table_name . '
      WHERE id = ?
      LIMIT
        0, 1;';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $row['id'];
    $this->name = $row['name'];
    $this->is_active = $row['is_active'];
  }

  function update() {
      $query = 'UPDATE
        ' . $this->table_name . ' SET
          `name` = :name,
          `is_active` = :is_active
          WHERE id = :id;';
      $stmt = $this->conn->prepare($query);
      $this->value = htmlspecialchars(strip_tags($this->name));

      $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
      $stmt->bindParam(":is_active", $this->is_active, PDO::PARAM_BOOL);
      $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        return true;
      } else {
        echo '<pre> <br/>Outlet Type Update Error<br/>';
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
      echo '<pre> <br/>OutletType Delete Error<br/>';
        print_r($stmt->errorInfo());
      echo '</pre>';
      return false;
    }
  }

}
?>
