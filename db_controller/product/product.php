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

	function getLastProductId() {
		$query = 'select `id` FROM `' . $this->table_name . '` ORDER BY `id` DESC LIMIT 0,1;';
		$stmt = $this->conn->prepare($query);
		if ($stmt->execute()) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['id'];
		} else {
			echo '<pre> <br/>Get Last Product ID Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return;
		}
	}

	function create() {
		$query = 'INSERT INTO
			'. $this->table_name . '(
						`product_code`,
						`product_name`,
						`brand_id`,';
		if (count($this->sizeId)>0) { $query .='`size_id`,'; }
		$query .= '`size_other_status`,
							 `size_other_text`,
							 `type_other_status`,
							 `type_other_text`,
							 `product_status`) VALUES (
								:code,
			 				 	:name,
			 				 	:brandId,';
		if (count($this->sizeId)>0) { $query .=':sizeId,'; }
				$query .=':sizeOtherStatus,
				 					:sizeOtherDetail,
				 					:typeOtherStatus,
				 					:typeOtherDetail,
				 					:status);';

		$stmt = $this->conn->prepare($query);
		// posted values
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->otherSizeDetail = htmlspecialchars(strip_tags($this->otherSizeDetail));
		$this->otherTypeDetail = htmlspecialchars(strip_tags($this->otherTypeDetail));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":code", $this->code, PDO::PARAM_STR);
		$stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
		$stmt->bindParam(":brandId", $this->brandId, PDO::PARAM_INT);
		if (count($this->sizeId)>0) { $stmt->bindParam(":sizeId", $this->sizeId, PDO::PARAM_INT); }
		$stmt->bindParam(":sizeOtherStatus", $this->otherSizeStatus, PDO::PARAM_BOOL);
		$stmt->bindParam(":sizeOtherDetail", $this->otherSizeDetail, PDO::PARAM_STR);
		$stmt->bindParam(":typeOtherStatus", $this->otherTypeStatus, PDO::PARAM_BOOL);
		$stmt->bindParam(":typeOtherDetail", $this->otherTypeDetail, PDO::PARAM_STR);
		$stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);
		// execute query

		if ($stmt->execute()) {
			print_r(count($this->typeId) . 'count');
			if (count($this->typeId)>0) {
					$this->id = $this->getLastProductId();
					$queryDetail = 'INSERT INTO
						' . $this->table_product_type . '(
								`product_id`,	`type_id`) VALUES (
								:productId, :typeId);';
					for($i = 0; $i<count($this->typeId); $i++) {
						$detail = $this->conn->prepare($queryDetail);
						$detail->bindParam(":productId", $this->id, PDO::PARAM_INT);
						$detail->bindParam(":typeId", $this->typeId[$i], PDO::PARAM_INT);
						if($detail->execute() == false) {
							$this->delete();
							echo '<pre> <br/>Product Type Error<br/>';
								print_r($detail->errorInfo());
							echo '</pre>';
						}
					}
			}
			return true;
		} else {
			echo '<pre> <br/>Product Type Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
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
			id, product_code, product_name, brand_id, size_id, size_other_status,
			size_other_text, type_other_status, type_other_text, product_status
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
		$this->otherSizeStatus = $row['size_other_status'];
		$this->otherSizeDetail = $row['size_other_text'];
		$this->otherTypeStatus = $row['type_other_status'];
		$this->otherTypeDetail = $row['type_other_text'];
		$this->status = $row['product_status'];
	}

	function readType() {
		$query = 'SELECT
			id, product_id, type_id
			FROM
			' . $this->table_product_type . '
			WHERE product_id = ?
			ORDER BY type_id;';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$type_arr = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$type_arr[] = $type_id;
		}
		$this->typeId = $type_arr;
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
