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

	$para = json_decode(file_get_contents('php://input'));
	$survey->user_id = $para->user_id;
	$survey->issysadmin = $para->issysadmin;
	$stmt = $survey->readAll();
	$num = $stmt->rowCount();

	if($num>0) {
		$data = '';
		$x = 1;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$data .= '{';
				$data .= '"id":"' . $id . '",';
				$data .= '"area":"' . $area . '",';
				$data .= '"outlet_mm":"' . html_entity_decode($outlet_mm) . '",';
				$data .= '"township_mm":"' . html_entity_decode($township_mm) . '",';
				$data .= '"city_mm":"' . html_entity_decode($city_mm) . '",';
				//$status = $survey_status == '1' ? 'Active' : 'Inactive';
				$data .= '"survey_status":"' . $survey_status . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}
	echo '{"records":[' . $data . ']}';
?>
