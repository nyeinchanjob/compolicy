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

  $product->id = $data->id;
	$product->code = $data->code;
	$product->name	= $data->name;
	$product->status = $data->status;

	if ($product->update()) {
		echo 'Product was updated.';
	} else {
		echo 'Unable to update product';
	}
?>
