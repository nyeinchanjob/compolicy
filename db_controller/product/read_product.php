<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

	if(file_exists("../../config/database.php") && include_once('../../config/database.php') ){
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
			$data .= '"id":"'   . $id . '",';
			$data .= '"code":"' . $code . '",';
			$data .= '"name":"' . html_entity_decode($name) . '",';
			$data .= '"status":"' . $status . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}
	
	echo '{"records":[' . $data . ']}';

?>
