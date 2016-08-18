<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){
		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("survey.php") && include_once("survey.php") ){
		$survey = new Survey($db);
	} else {
		echo 'Unable to include survey file.';
	}

	$stmt = $survey->readAll();
	$num = $stmt->rowCount();

	if($num>0) {
		$data = '';
		$x = 1;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$data .= '{';
				$data .= '"survey_id":"' . $id . '",';
				$data .= '"survey_code":"' . $survey_code . '",';
				$data .= '"survey_name":"' . html_entity_decode($survey_name) . '",';
				//$status = $survey_status == '1' ? 'Active' : 'Inactive';
				$data .= '"survey_status":"' . $survey_status . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}
	echo '{"records":[' . $data . ']}';
?>
