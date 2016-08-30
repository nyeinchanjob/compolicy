<?php
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

	$data = json_decode(file_get_contents('php://input'));

	$survey->area = $data->area;
	$survey->city_mm = $data->city_mm;
	$survey->city_en = $data->city_en;
	$survey->township_mm = $data->township_mm;
	$survey->township_en = $data->township_en;
	$survey->ward_mm = $data->ward_mm;
	$survery->ward_en = $data->ward_en;
	$survey->outlet_mm = $data->outlet_mm;
	$survey->outlet_en = $data->outlet_en;
	$survey->owner_mm = $data->owner_mm;
	$survey->owner_en = $data->owner_en;
	$survey->phone1 = $data->phone1;
	$survey->phone2 = $data->phone2;
	$survey->phone3 = $data->phone3;
	$survey->longitude = $data->longitude;
	$survey->latitude = $data->latitude;
	$survey->image_path_1 = $data.image_path_1;
	$survey->image_path_2 = $data.image_path_2;
	$survey->image_path_3 = $data.image_path_3;
	$survey->user_id = $data->user_id
	$survey->survey_status = $data->survey_status;

	$survey->questions = $data->answers;


	if ($survey->create()) {
		echo 'Survey was created.';
	} else {
		echo 'Unable to survey product';
	}
?>
