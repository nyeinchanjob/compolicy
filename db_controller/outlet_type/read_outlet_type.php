<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

	if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){
		$database = new Database();
		$db = $database->getConn();
	} else {
		echo 'Unable to include database file.';
	}

	if(file_exists("outlet_type.php") && include_once("outlet_type.php") ){
		$outlet_type = new OutletType($db);
	} else {
		echo 'Unable to include outlet_type file.';
	}

	 $confType = $outlet_type->readAll();
	 $num = $confType->rowCount();
	 $data = '';
	 if($num>0) {
	 	$x = 1;
		while($row = $confType->fetch(PDO::FETCH_ASSOC)){
			extract($row);
			$data .= '{';
				$data .= '"id":"' . $id . '",';
				$data .= '"name":"' . html_entity_decode($name) . '",';
				$data .= '"is_active":"' . $is_active . '"';
			$data .= '}';

			$data .= $x < $num ? ',' : '';
			$x++;
		}
	}
	echo '{"records":[' . $data . ']}';
?>
