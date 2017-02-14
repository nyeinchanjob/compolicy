<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("question_type.php") && include_once("question_type.php") ){
		$question_type = new QuestionType($db);
	} else {
		echo 'Unable to include product file.';
	}

	$data = json_decode(file_get_contents('php://input'));

  	$question_type->id = $data->id;
	$question_type->name	= $data->name;
	$question_type->is_active = $data->is_active;

	if ($question_type->update()) {
		echo 'QuestionType was updated.';
	} else {
		echo 'Unable to update question_type';
	}
?>
