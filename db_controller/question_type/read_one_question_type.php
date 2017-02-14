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
  	$question_type->readOne();

  $question_type_arr[] = array(
    'id' => $question_type->id,
    'name' => $question_type->name,
    'is_active' => $question_type->is_active
  );

  print_r(json_encode($question_type_arr));
?>
