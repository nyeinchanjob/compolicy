<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charaset=UTF-8");
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

    $config = json_decode(file_get_contents('php://input'));
    for($i = 0; $i<count($config->cType); $i++) {
        $j = 1;
        $product->config_type = $config->cType[$i];
        $stmt = $product->readConfig();
        $num = $stmt->rowCount();
        if($num>0) {
            $data .= '{"' . $config->cType[$i] . '":[' ;
            $x = 1;
            while($row=$stmt->fetch(PDO::FETCH_ASOC)) {
                extract($row);
                $data .= '{';
                    $data .= '"config_id":"' . $cid . '",';
                    $data .= '"config_code":"' . $config_code . '",';
                    $data .= '"config_value":"' . $config_value . '",';
                    $data .= '"config_type":"' . $config_type . '",';
                    $data .= '"config_status":"' . $config_status . '"';
                $data .= '}';
                $data .= $x < $num ? ',' : '';
                $x++;
            }
            $data .= ']}';
            $data .= $j < $i ? ',' : '';
        }
         $j++;
    }

    echo '{"records":['. $data .']}';
?>
