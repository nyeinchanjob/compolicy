<?php
class Survey {
	private $conn;
	private $table_name = 'survey';
	private $table_question = 'question';
	private $table_config = 'config';

	public $id;
	public $area;
	public $city_mm;
	public $city_en;
	public $township_mm;
	public $township_en;
	public $ward_mm;
	public $ward_en;
	public $outlet_type;
	public $outlet_mm;
	public $outlet_en;
	public $owner_mm;
	public $owner_en;
	public $phone1;
	public $phone2;
	public $phone3;
	public $longitude;
	public $latitude;
	public $image_path_1;
	public $image_path_2;
	public $image_path_3;
	public $survey_status;
	public $user_id;
	public $created_date;
	public $updated_date;

	public $qid;
	public $question_id;
	public $answer;

	public $questions;

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

	function createQuestion() {
		$query = 'INSERT INTO ' . $this->table_question . ' (
				`survey_id`,
				`question_id`,
				`answer`
			 ) VALUES ';
		for ($i = 0; $i<count($this->questions); $i++) {
			$query .= '(';
			$query .= ':survey_id, :question_id' . $i . ', :answer' . $i;
			$query .= ')';
			$query .= ($i<count($this->questions) - 1) ? ',' : ';';
		}

		$stmt = $this->conn->prepare($query);

		for ($j = 0; $j<count($this->questions); $j++) {
			$stmt->bindParam(":survey_id", $this->id, PDO::PARAM_INT);
			$stmt->bindParam(":question_id". $j, $this->questions[$j]->question_id, PDO::PARAM_STR);
			$stmt->bindParam(":answer".$j, $this->questions[$j]->answer, PDO::PARAM_STR);
		}


    	if ($stmt->execute()) {
    		return true;
    	} else {
    		echo '<pre> <br/>Create Question Error<br/>';
    			print_r($stmt->errorInfo());
    		echo '</pre>';
			return false;
		}

	}

	function create() {
		$query = 'INSERT INTO '. $this->table_name . '(
				`area`,
				`city_mm`, `city_en`,
				`township_mm`, `township_en`,
				`ward_mm`, `ward_en`,
				`outlet_type`,
				`outlet_mm`, `outlet_en`,
				`owner_mm`, `owner_en`,
				`phone1`, `phone2`, `phone3`,
				`longitude`, `latitude`,
				`image1` , `image2`, `image3`,
				`user_id`,
				`survey_status`
			) VALUES (
				:area,
			 	:city_mm, :city_en,
			 	:township_mm, :township_en,
				:ward_mm, :ward_en,
				:outlet_type,
				:outlet_mm, :outlet_en,
				:owner_mm, :owner_en,
				:phone1, :phone2, :phone3,
				:longitude, :latitude,
				:image_path_1, :image_path_2, :image_path_3,
				:user_id,
				:survey_status);';

		$stmt = $this->conn->prepare($query);
		// posted values
		$this->area = htmlspecialchars(strip_tags($this->area));
		$this->city_mm = htmlspecialchars(strip_tags($this->city_mm));
		$this->city_en = htmlspecialchars(strip_tags($this->city_en));
		$this->township_mm = htmlspecialchars(strip_tags($this->township_mm));
		$this->township_en = htmlspecialchars(strip_tags($this->township_en));
		$this->ward_mm = htmlspecialchars(strip_tags($this->ward_mm));
		$this->ward_en = htmlspecialchars(strip_tags($this->ward_en));
		$this->outlet_mm = htmlspecialchars(strip_tags($this->outlet_mm));
		$this->outlet_en = htmlspecialchars(strip_tags($this->outlet_en));
		$this->owner_mm = htmlspecialchars(strip_tags($this->owner_mm));
		$this->owner_en = htmlspecialchars(strip_tags($this->owner_en));
		$this->phone1 = htmlspecialchars(strip_tags($this->phone1));
		$this->phone2 = htmlspecialchars(strip_tags($this->phone2));
		$this->phone3 = htmlspecialchars(strip_tags($this->phone3));
		$this->image_path_1 = htmlspecialchars(strip_tags($this->image_path_1));
		$this->image_path_2 = htmlspecialchars(strip_tags($this->image_path_2));
		$this->image_path_3 = htmlspecialchars(strip_tags($this->image_path_3));


	// bind values
		$stmt->bindParam(":area", $this->area, PDO::PARAM_STR);
		$stmt->bindParam(":city_mm", $this->city_mm, PDO::PARAM_STR);
		$stmt->bindParam(":city_en", $this->city_en, PDO::PARAM_STR);
		$stmt->bindParam(":township_mm", $this->township_mm, PDO::PARAM_STR);
		$stmt->bindParam(":township_en", $this->township_en, PDO::PARAM_STR);
		$stmt->bindParam(":ward_mm", $this->ward_mm, PDO::PARAM_STR);
		$stmt->bindParam(":ward_en", $this->ward_en, PDO::PARAM_STR);
		$stmt->bindParam(":outlet_type", $this->outlet_type, PDO::PARAM_INT);
		$stmt->bindParam(":outlet_mm", $this->outlet_mm, PDO::PARAM_STR);
		$stmt->bindParam(":outlet_en", $this->outlet_en, PDO::PARAM_STR);
		$stmt->bindParam(":owner_mm", $this->owner_mm, PDO::PARAM_STR);
		$stmt->bindParam(":owner_en", $this->owner_en, PDO::PARAM_STR);
		$stmt->bindParam(":phone1", $this->phone1, PDO::PARAM_STR);
		$stmt->bindParam(":phone2", $this->phone2, PDO::PARAM_STR);
		$stmt->bindParam(":phone3", $this->phone3, PDO::PARAM_STR);
		$stmt->bindParam(":longitude", $this->longitude, PDO::PARAM_STR);
		$stmt->bindParam(":latitude", $this->latitude, PDO::PARAM_STR);
		$stmt->bindParam(":image_path_1", $this->image_path_1, PDO::PARAM_STR);
		$stmt->bindParam(":image_path_2", $this->image_path_2, PDO::PARAM_STR);
		$stmt->bindParam(":image_path_3", $this->image_path_3, PDO::PARAM_STR);
		$stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
		$stmt->bindParam(":survey_status", $this->survey_status, PDO::PARAM_BOOL);

	// execute query

		if ($stmt->execute()) {
			$this->id = $this->getLastSurveyId();
			if ($this->createQuestion()) {
				return true;
			} else {
				$this->id = array($this->id);
				$this->delete();
				echo '<pre><br/> Unable to Create Survey.';
				return false;
			}

		} else {
			echo '<pre> <br/>Create Survey Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function readConfig() {
		$query = 'SELECT
				`id`,`config_value`, `config_type`, `config_status`
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
				`id`,
				`area`, `outlet_mm`, `township_mm`, `city_mm`, `survey_status`
			FROM
			' . $this->table_name . '
			WHERE
				survey_status = 1';
			if ($this->issysadmin == false) {
				$query .= ' AND user_id = :user_id';
			}

			$query .=' ORDER BY
				id DESC';
		$stmt = $this->conn->prepare($query);
		if ($this->issysadmin==false) {
			$stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
		}

		$stmt->execute();
		return $stmt;
	}

	function readOne() {
		$query = 'SELECT
				`id`,
				`area`,
				`city_mm`, `city_en`,
				`township_mm`, `township_en`,
				`ward_mm`, `ward_en`,
				`outlet_type`,
				`outlet_mm`, `outlet_en`,
				`owner_mm`, `owner_en`,
				`phone1`, `phone2`, `phone3`,
				`longitude`, `latitude`,
				`image1`, `image2`, `image3`,
				`user_id`,
			 	`survey_status`
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
		$this->area = $row['area'];
		$this->city_mm = $row['city_mm'];
		$this->city_en = $row['city_en'];
		$this->township_mm = $row['township_mm'];
		$this->township_en = $row['township_en'];
		$this->ward_mm = $row['ward_mm'];
		$this->ward_en = $row['ward_en'];
		$this->outlet_type = $row['outlet_type'];
		$this->outlet_mm = $row['outlet_mm'];
		$this->outlet_en = $row['outlet_en'];
		$this->owner_mm = $row['owner_mm'];
		$this->owner_en = $row['owner_en'];
		$this->phone1 = $row['phone1'];
		$this->phone2 = $row['phone2'];
		$this->phone3 = $row['phone3'];
		$this->longitude = $row['longitude'];
		$this->latitude = $row['latitude'];
		$this->image_path_1 = $row['image1'];
		$this->image_path_2 = $row['image2'];
		$this->image_path_3 = $row['image3'];
		$this->user_id = $row['user_id'];
		$this->survey_status = $row['survey_status'];

	}

	function readQuestion() {
		$query = 'SELECT
				`id`, `survey_id`, `question_id`, `answer`
			FROM
				`' . $this->table_question . '`
			WHERE
				`survey_id` = ?
			ORDER BY
				`question_id`;';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$qas_arr = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$qas = array(
				'question_id' => $question_id,
				'answer' => $answer
				);
			$qas_arr[] = $qas;
		}
		$this->questions = $qas_arr;
	}

	function update() {
		$query = 'UPDATE `' . $this->table_name . '` SET
				`area` = :area,
				`city_mm` = :city_mm, `city_en` = :city_en,
				`township_mm` = :township_mm, `township_en` = :township_en,
				`ward_mm` = :ward_mm, `ward_en` = :ward_en,
				`outlet_type` = :outlet_type,
				`outlet_mm` = :outlet_mm, `outlet_en` = :outlet_en,
				`owner_mm` = :owner_mm, `owner_en` = :owner_en,
				`phone1` = :phone1, `phone2` = :phone2, `phone3` = :phone3,
				`longitude` = :longitude, `latitude` = :latitude,
				`image1` = :image_path_1, `image2` = :image2, `image3` = :image_path_3,
				`updated_by` = :user_id, update_date = :updated_date
				`survey_status` = :status
			WHERE
				`id` = :id';

		$stmt = $this->conn->prepare($query);
		// bind values
        	$this->area = htmlspecialchars(strip_tags($this->area));
        	$this->city_mm = htmlspecialchars(strip_tags($this->city_mm));
        	$this->city_en = htmlspecialchars(strip_tags($this->city_en));
        	$this->township_mm = htmlspecialchars(strip_tags($this->township_mm));
        	$this->township_en = htmlspecialchars(strip_tags($this->township_en));
        	$this->ward_mm = htmlspecialchars(strip_tags($this->ward_mm));
        	$this->ward_en = htmlspecialchars(strip_tags($this->ward_en));
        	$this->outlet_mm = htmlspecialchars(strip_tags($this->outlet_mm));
        	$this->outlet_en = htmlspecialchars(strip_tags($this->outlet_en));
        	$this->owner_mm = htmlspecialchars(strip_tags($this->owner_mm));
        	$this->owner_en = htmlspecialchars(strip_tags($this->owner_en));
        	$this->phone1 = htmlspecialchars(strip_tags($this->phone1));
        	$this->phone2 = htmlspecialchars(strip_tags($this->phone2));
        	$this->phone3 = htmlspecialchars(strip_tags($this->phone3));
        	$this->image_path_1 = htmlspecialchars(strip_tags($this->image_path_1));
        	$this->image_path_2 = htmlspecialchars(strip_tags($this->image_path_2));
        	$this->image_path_3 = htmlspecialchars(strip_tags($this->image_path_3));


        	// bind values
        	$stmt->bindParam(":area", $this->area, PDO::PARAM_STR);
        	$stmt->bindParam(":city_mm", $this->city_mm, PDO::PARAM_STR);
        	$stmt->bindParam(":city_en", $this->city_en, PDO::PARAM_STR);
        	$stmt->bindParam(":township_mm", $this->township_mm, PDO::PARAM_STR);
        	$stmt->bindParam(":township_en", $this->township_en, PDO::PARAM_STR);
        	$stmt->bindParam(":ward_mm", $this->ward_mm, PDO::PARAM_STR);
        	$stmt->bindParam(":ward_en", $this->ward_en, PDO::PARAM_STR);
        	$stmt->bindParam(":outlet_type", $this->outlet_type, PDO::PARAM_INT);
        	$stmt->bindParam(":outlet_mm", $this->outlet_mm, PDO::PARAM_STR);
        	$stmt->bindParam(":outlet_en", $this->outlet_en, PDO::PARAM_STR);
        	$stmt->bindParam(":owner_mm", $this->owner_mm, PDO::PARAM_STR);
        	$stmt->bindParam(":owner_en", $this->owner_en, PDO::PARAM_STR);
        	$stmt->bindParam(":phone1", $this->phone1, PDO::PARAM_STR);
        	$stmt->bindParam(":phone2", $this->phone2, PDO::PARAM_STR);
        	$stmt->bindParam(":phone3", $this->phone3, PDO::PARAM_STR);
        	$stmt->bindParam(":longitude", $this->longitude, PDO::PARAM_STR);
        	$stmt->bindParam(":latitude", $this->latitude, PDO::PARAM_STR);
        	$stmt->bindParam(":image_path_1", $this->image_path_1, PDO::PARAM_STR);
        	$stmt->bindParam(":image_path_2", $this->image_path_2, PDO::PARAM_STR);
        	$stmt->bindParam(":image_path_3", $this->image_path_3, PDO::PARAM_STR);
        	$stmt->bindParam(":user_id", $this->user_id, PDO::PARAM_STR);
        	$stmt->bindParam(":survey_status", $this->survey_status, PDO::PARAM_BOOL);
		$stmt->bindParam(":id", $this->id);

		if ($stmt->execute()) {
			if ($this->deleteQuestion()) {
				if ($this->createQuestion()) {
					return true;
				} else {
					echo '<pre> <br/> Update Question Error <br/>';
					return false;
				}
			}
		} else {
			echo '<pre> <br/>Update Survey Error<br/>';
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
			$this->deleteQuestion();
			return true;
		} else {
			echo '<pre> <br/> Delete Survey Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

	function deleteQuestion() {
		$query = 'DELETE FROM
			' . $this->table_question . '
			WHERE FIND_IN_SET(survey_id, :array)
		';

		$stmt = $this->conn->prepare($query);
		$ids_string = implode(',', $this->id);
		$stmt->bindParam(':array', $ids_string);

		if ($stmt->execute()) {
			return true;
		} else {
			echo '<pre> <br/> Delete Question Error<br/>';
				print_r($stmt->errorInfo());
			echo '</pre>';
			return false;
		}
	}

}
?>
