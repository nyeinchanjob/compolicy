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
  $product->readOne();

  $product_arr[] = array(
    'id' => $product->id,
    'code' => $product->code,
    'name' => $product->name,
		'brandId' => $product->brandId,
		'sizeId' => $product->sizeId,
		'otherSizeDetail' => $product->otherSizeDetail,
		'otherTypeDetail' => $product->otherTypeDetail,
    'status' => $product->status
  );

  print_r(json_encode($product_arr));
?>
