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
    'code' => $survey->code,
    'name' => $survey->name,
		'brandId' => $survey->brandId,
		'sizeId' => $survey->sizeId,
		'otherSizeDetail' => $survey->otherSizeDetail,
		'otherTypeDetail' => $survey->otherTypeDetail,
    'status' => $survey->status
  );

  print_r(json_encode($survey_arr));
?>
