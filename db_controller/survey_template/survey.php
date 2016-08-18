<?php
class Survey {
	private $conn;
	private $table_name = 'survey';
	private $table_config = '`config`';
	private $table_survey_type = '`survey_type`';

	public $id;
	public $code;
	public $name;
	public $brandId;
	public $sizeId;
	public $otherSizeDetail;
	public $typeId;
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

	function getLastSurveyId() {
		$query = 'select `id` FROM `' . $this->table_name . '` ORDER BY `id` DESC LIMIT 0,1;';
		$stmt = $this->conn->prepare($query);
		if ($stmt->execute()) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['id'];
		} else {
			echo '<pre> <br/>Get Last Survey ID Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return;
		}
	}

	function create() {
		$query = 'INSERT INTO
			'. $this->table_name . '(
						`survey_code`,
						`survey_name`,
						`brand_id`,
						`size_id`,
						`size_other_text`,
						`type_other_text`,
						`survey_status`) VALUES (
								:code,
			 				 	:name,
			 				 	:brandId,
								:sizeId,
								:sizeOtherDetail,
				 				:typeOtherDetail,
				 				:status);';

		$stmt = $this->conn->prepare($query);
		// posted values
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->otherSizeDetail = htmlspecialchars(strip_tags($this->otherSizeDetail));
		$this->otherTypeDetail = htmlspecialchars(strip_tags($this->otherTypeDetail));
		// bind values
		$stmt->bindParam(":code", $this->code, PDO::PARAM_STR);
		$stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
		$stmt->bindParam(":brandId", $this->brandId, PDO::PARAM_INT);
		$stmt->bindParam(":sizeId", $this->sizeId, PDO::PARAM_INT);
		$stmt->bindParam(":sizeOtherDetail", $this->otherSizeDetail, PDO::PARAM_STR);
		$stmt->bindParam(":typeOtherDetail", $this->otherTypeDetail, PDO::PARAM_STR);
		$stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);
		// execute query

		if ($stmt->execute()) {
			print_r(count($this->typeId) . 'count');
			if (count($this->typeId)>0) {
					$this->id = $this->getLastSurveyId();
					$queryDetail = 'INSERT INTO
						' . $this->table_survey_type . '(
								`survey_id`,	`type_id`) VALUES (
								:surveyId, :typeId);';
					for($i = 0; $i<count($this->typeId); $i++) {
						$detail = $this->conn->prepare($queryDetail);
						$detail->bindParam(":surveyId", $this->id, PDO::PARAM_INT);
						$detail->bindParam(":typeId", $this->typeId[$i], PDO::PARAM_INT);
						if($detail->execute() == false) {
							$this->delete();
							echo '<pre> <br/>Survey Type Error<br/>';
								print_r($detail->errorInfo());
							echo '</pre>';
						}
					}
			}
			return true;
		} else {
			echo '<pre> <br/>Survey Type Error<br/>';
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
				id, survey_code, survey_name, survey_status
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
			id, survey_code, survey_name, brand_id, size_id,
			size_other_text,  type_other_text, survey_status
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
		$this->code = $row['survey_code'];
		$this->name = $row['survey_name'];
		$this->brandId = $row['brand_id'];
		$this->sizeId = $row['size_id'];
		$this->otherSizeDetail = $row['size_other_text'];
		$this->otherTypeDetail = $row['type_other_text'];
		$this->status = $row['survey_status'];
	}

	function readType() {
		$query = 'SELECT
			id, survey_id, type_id
			FROM
			' . $this->table_survey_type . '
			WHERE survey_id = ?
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
				`survey_code` = :code,
				`survey_name` = :name,
				`brand_id` = :brandId,
				`size_id` = :sizeId,
				`size_other_text` = :sizeOtherDetail,
				`type_other_text` = :typeOtherDetail,
				`survey_status` = :status
			WHERE
				`id` = :id';

		$stmt = $this->conn->prepare($query);
		// bind values
		$this->code = htmlspecialchars(strip_tags($this->code));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->otherSizeDetail = htmlspecialchars(strip_tags($this->otherSizeDetail));
		$this->otherTypeDetail = htmlspecialchars(strip_tags($this->otherTypeDetail));
		$this->status = htmlspecialchars(strip_tags($this->status));
		// bind values
		$stmt->bindParam(":code", $this->code, PDO::PARAM_STR);
		$stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
		$stmt->bindParam(":brandId", $this->brandId, PDO::PARAM_INT);
		$stmt->bindParam(":sizeId", $this->sizeId, PDO::PARAM_INT);
		$stmt->bindParam(":sizeOtherDetail", $this->otherSizeDetail, PDO::PARAM_STR);
		$stmt->bindParam(":typeOtherDetail", $this->otherTypeDetail, PDO::PARAM_STR);
		$stmt->bindParam(":status", $this->status, PDO::PARAM_BOOL);
		$stmt->bindParam(":id", $this->id);

		if ($stmt->execute()) {
			if (count($this->typeId)>0) {
				$survey_id = $this->id;
				$id_arr = array($this->id);
				print_r($id_arr);
				$this->id = $id_arr;
				if($this->deleteSurveyType()) {
					$queryDetail = 'INSERT INTO
						' . $this->table_survey_type . '(
								`survey_id`,	`type_id`) VALUES (
								:surveyId, :typeId);';
					for($i = 0; $i<count($this->typeId); $i++) {
						$detail = $this->conn->prepare($queryDetail);
						$detail->bindParam(":surveyId", $survey_id, PDO::PARAM_INT);
						$detail->bindParam(":typeId", $this->typeId[$i], PDO::PARAM_INT);
						if($detail->execute() == false) {
							//$this->delete();
							echo '<pre> <br/>Survey Type Error<br/>';
								print_r($detail->errorInfo());
							echo '</pre>';
						}
					}
				}
			}
			return true;
		} else {
			echo '<pre> <br/>Survey Type Error<br/>';
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
			$this->deleteSurveyType();
			return true;
		} else {
			echo '<pre>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function deleteSurveyType() {
		$query = 'DELETE FROM
			' . $this->table_survey_type . '
			WHERE FIND_IN_SET(survey_id, :array)
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
