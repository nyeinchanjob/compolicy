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

	if ($product->delete()) {
		echo 'Product was deleted.';
	} else {
		echo 'Unable to delete product';
	}
?>
