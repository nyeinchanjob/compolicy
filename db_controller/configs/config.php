<?php
class Config {
  private $conn;
  private $table_name = 'config';
  private $sys = ['menus', 'controls'];

  public $id;
  public $value;
  public $type;
  public $status;


  public function __construct($db) {
    $this->conn = $db;
  }

  function checkConfigName() {
    $query = 'SELECT
              `id`, `config_name`, `config_status` FROM
              `' . $this->table_name . '`
              WHERE `config_name` = :name';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt;
  }

  function create() {
    $query = 'INSERT INTO ' . $this->table_name . '(
        `config_value`,
        `config_type`,
        `config_status`
      ) VALUES (
        :value,
        :type,
        :status
      );';

    $stmt = $this->conn->prepare($query);

    $this->value = htmlspecialchars(strip_tags($this->value));
    $this->type = htmlspecialchars(strip_tags($this->type));

    $stmt->bindParam(":value", $this->value, PDO::PARAM_STR);
    $stmt->bindParam(":type", $this->type, PDO::PARAM_STR);
    $stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);

    if ($stmt->execute()){
      return true;
    } else {
      echo '<pre> <br/>Config Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
    }
  }

  function readAllType() {
    $query = 'SELECT DISTINCT `config_type`
    FROM `' . $this->table_name .'`
     WHERE NOT FIND_IN_SET(`config_type`, :array) ORDER BY `config_type`;';
    $stmt = $this->conn->prepare($query);
    $sys_string = implode(',', $this->sys);
    $stmt->bindParam(":array", $sys_string);
    if($stmt->execute()) {
        return $stmt;
    } else {
      echo 'Yae';
    }

  }

  function readAll() {
    $query = 'SELECT `id`, `config_value`, `config_type`,
    `config_status` FROM `' . $this->table_name .'`
     WHERE `config_type` = :type ORDER BY `id` DESC;';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":type", $this->type, PDO::PARAM_STR);
    if($stmt->execute()) {
        return $stmt;
    } else {
      echo 'Yae';
    }
  }

  function readOne() {
    $query = 'SELECT
      `id`, `config_value`, `config_type`, `config_status` FROM
      ' . $this->table_name . '
      WHERE id = ?
      LIMIT
        0, 1;';

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $row['id'];
    $this->value = $row['config_value'];
    $this->type = $row['config_type'];
    $this->status = $row['config_status'];
  }

  function update() {
      $query = 'UPDATE
        ' . $this->table_name . ' SET
          `config_value` = :value,
          `config_type` = :type,
          `config_status` = :status
          WHERE id = :id;';
      $stmt = $this->conn->prepare($query);
      $this->value = htmlspecialchars(strip_tags($this->value));

      $stmt->bindParam(":value", $this->value, PDO::PARAM_STR);
      $stmt->bindParam(":type", $this->type, PDO::PARAM_STR);
      $stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);
      $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        return true;
      } else {
        echo '<pre> <br/>Config Type Error<br/>';
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
      echo '<pre> <br/>Config Type Error<br/>';
        print_r($stmt->errorInfo());
      echo '</pre>';
      return false;
    }
  }

}
?>
