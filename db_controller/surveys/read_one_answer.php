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
  	$survey->readQuestion();

  	$answer_arr[] = array(
    		'answers' => $survey->questions
  	);

 	print_r(json_encode($answer_arr));
?>
