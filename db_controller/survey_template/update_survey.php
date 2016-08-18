<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("survery.php") && include_once("survery.php") ){
		$survery = new Survery($db);
	} else {
		echo 'Unable to include survery file.';
	}

	$data = json_decode(file_get_contents('php://input'));

  $survery->id = $data->id;
	$survery->code = $data->code;
	$survery->name	= $data->name;
	$survery->brandId	= $data->brandId;
	$survery->sizeId	= $data->sizeId;
	$survery->typeId	= $data->typeId;
	$survery->otherSizeDetail	= $data->sizeOtherDetail;
	$survery->otherTypeDetail	= $data->typeOtherDetail;
	$survery->status = $data->status;

	if ($survery->update()) {
		echo 'Survery was updated.';
	} else {
		echo 'Unable to update survery';
	}
?>
