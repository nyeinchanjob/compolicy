<?php
class Product {
	private $conn;
	private $table_name = 'product';
	private $table_config = '`config`';
	private $table_product_type = '`product_type`';

	public $id;
	public $code;
	public $name;
	public $brandId;
	public $sizeId;
	public $otherSizeStatus;
	public $otherSizeDetail;
	public $typeId;
	public $otherTypeStatus;
	public $otherTypeDetail;
	public $status;

	public $cid;
	public $config_code;
	public $config_value;
	public $config_type;
	public $config_status;

	public function __construct($db) {
		$this->conn = $db;
	}

	function create() {
		$query = 'INSERT INTO
			'. $this->table_name . '
			SET
				`product_code` =:code,
				`product_name` =:name,
				`brand_id` = :brandId,
				`size_id` = :sizeId,
				`size_other_status` = :sizeOtherStatus,
				`size_other_text` = :sizeOtherDetail,
				`type_other_status` = :typeOtherStatus,
				`type_other_text` = :typeOtherDetail,
				`product_status` =:status';

		$stmt = $this->conn->prepare($query);
		// posted values
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->otherSizeDetail = htmlspecialchars(strip_tags($this->otherSizeDetail));
		$this->otherTypeDetail = htmlspecialchars(strip_tags($this->otherTypeDetail));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":code", $this->code);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":brandId", $this->brandId);
		$stmt->bindParam(":sizeId", $this->sizeId);
		$stmt->bindParam(":otherSizeStatus", $this->otherSizeStatus);
		$stmt->bindParam(":otherSizeDetail", $this->otherSizeDetail);
		$stmt->bindParam(":typeOtherStatus", $this->otherTypeStatus);
		$stmt->bindParam(":typeOtherDetail", $this->otherTypeDetail);
		$stmt->bindParam(":status", $this->status);
		// execute query
		if ($stmt->execute()) {
			if (count($this->typeId)>0) {
					getLastProductId();
					$queryDetail = 'INSERT INTO
						' . $table_product_type . '
							SET
								`product_id` = :productId,
								`typ_id` = :typeId;';
					for($i = 0; $i<count($this->typeId); $i++) {
						$detail = $this->conn->prepare($queryDetail);
						$detail->bindParam(":productId", $this->id);
						$detail->bindParam(":typeId", $this->typeId[$i]);
						$detail->execute();
					}
			}
			return true;
		} else {
			echo '<pre>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function getLastProductId() {
		$query = 'select `id` FROM `' . $table_name . '` ORDER BY `id` DESC LIMIT 0,1;';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
	}

	function readConfig() {
		$query = 'SELECT
						`id`, `config_code`, `config_value`, `config_type`, `config_status`
			FROM
			 ' . $this->table_config . '
			WHERE
				`config_type` = :configType AND
				`config_status` = 1
			ORDER BY
				`config_value`;';
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":configType", $this->config_type);
		if($stmt->execute()) {
			return $stmt;
		} else {
			echo '<pre>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return;
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
			id, product_code, product_name, brand_id, size_id, product_status
			FROM
			' . $this->table_name . '
			WHERE id = ?
			LIMIT
				0, 1';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id = $row['id'];
		$this->code = $row['product_code'];
		$this->name = $row['product_name'];
		$this->brandId = $row['brand_id'];
		$this->sizeId = $row['size_id'];
		$this->status = $row['product_status'];
	}

	function update() {
		$query = 'UPDATE
		' . $this->table_name . ' SET
				`product_code` = :code,
				`product_name` = :name,
				`brand_id` = :brandId,
				`size_id` = :sizeId,
				`product_status` = :status
			WHERE
				`id` = :id';

		$stmt = $this->conn->prepare($query);
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":code", $this->code);
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":brandId", $this->brandId);
		$stmt->bindParam(":sizeId", $this->sizeId);
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
