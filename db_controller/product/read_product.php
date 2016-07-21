<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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

	$stmt = $product->readAll();
	$num = $stmt->rowCount();

	if($num>0) {
		$data = '';
		$x = 1;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			$data .= '{';
				$data .= '"product_id":"' . $id . '",';
				$data .= '"product_code":"' . $product_code . '",';
				$data .= '"product_name":"' . html_entity_decode($product_name) . '",';
				//$status = $product_status == '1' ? 'Active' : 'Inactive';
				$data .= '"product_status":"' . $product_status . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}
	echo '{"records":[' . $data . ']}';
?>
