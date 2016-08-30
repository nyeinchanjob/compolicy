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

	$survey->id = $data->id;
  	$survey->readOne();

  	$survey_arr[] = array(
    		'id' => $survey->id,
    		'area' => $survey->area,
    		'city_mm' => $survey->city_mm, 'city_en' => $survey->city_en,
		'township_mm' => $survey->township_mm, 'township_en' => $survey->township_en,
		'ward_mm' => $survey->ward_mm, 'ward_en' => $survey->ward_en,
		'phone1' => $survey->phone1, 'phone2' => $survey->phone2, 'phone3' => $survey->phone3,
		'longitude' => $survey->longitude, 'latitude' => $survey->latitude,
		`image_path_1` => $survey->image_path_1, 'image_path_2' => $survey->image_path_2, 'image_path_3' => $survey->image_path_3,
		'user_id' => $survey->user_id,
		'status' => $survey->status
  );

 	print_r(json_encode($survey_arr));
?>
