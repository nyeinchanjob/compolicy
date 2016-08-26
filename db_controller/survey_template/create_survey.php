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

	$survey->code = $data->code;
	$survey->name	= $data->name;
	$survey->brandId	= $data->brandId;
	$survey->sizeId	= $data->sizeId;
	$survey->typeId	= $data->typeId;
	$survey->otherSizeDetail	= $data->sizeOtherDetail;
	$survey->otherTypeDetail	= $data->typeOtherDetail;
	$survey->status = $data->status;

	if ($product->create()) {
		echo 'Product was created.';
	} else {
		echo 'Unable to create product';
	}
?>
