<?php
	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){

		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("product.php") && include_once("product.php") ){
		$product = new Product($db);
	} else {
		echo 'Unable to include product file.';
	}

	$data = json_decode(file_get_contents('php://input'));

	$product->code = $data->code;
	$product->name	= $data->name;
	$product->brandId	= $data->brandId;
	$product->sizeId	= $data->sizeId;
	$product->typeId	= $data->typeId;
	$product->otherSizeDetail	= $data->sizeOtherDetail;
	$product->otherTypeDetail	= $data->typeOtherDetail;
	$product->status = $data->status;

	if ($product->create()) {
		echo 'Product was created.';
	} else {
		echo 'Unable to create product';
	}
?>
