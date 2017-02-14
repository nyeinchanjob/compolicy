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
		echo 'Unable to include question_type file.';
	}

	$data = json_decode(file_get_contents('php://input'));

  $question_type->id = $data->id;

	if ($question_type->delete()) {
		echo 'Question  Type was deleted.';
	} else {
		echo 'Unable to delete question Type';
	}
?>
