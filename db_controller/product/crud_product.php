<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

  if(file_exists('../../config/database.php') && include_once('../../config/database.php') ){
    global $database = new Database();
    global $db = $database->getConn();
  } else {
    echo 'Unable to include database file.';
  }

  if(file_exists("product.php") && include_once("product.php") ){
    global $product = new Product($db);
  } else {
    echo 'Unable to include product file.';
  }

  $data = json_decode(file_get_contents('php://input'));

   function createRecord() {
     $product->code = $data->code;
   	$product->name	= $data->name;
   	$product->status = $data->status;

   	if ($product->create()) {
   		return 'Product was created.';
   	} else {
   		return 'Unable to create product';
   	}
   }

   function readRecord() {
     $stmt = $product->readAll();
   	$num = $stmt->rowCount();

   	if($num>0) {
   		$res = '';
   		$x = 1;
   		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   			extract($row);
   			$res .= '{';
   				$res .= '"product_id":"' . $id . '",';
   				$res .= '"product_code":"' . $product_code . '",';
   				$res .= '"product_name":"' . html_entity_decode($product_name) . '",';
   				//$status = $product_status == '1' ? 'Active' : 'Inactive';
   				$res .= '"product_status":"' . $product_status . '"';
   			$res .= '}';

   			$res .= $x < $num ? ',' : '';
   			$x++;
   		}
   	}
   	return '{"records":[' . $res . ']}';
   }

   function readDetail() {
     $product->id = $data->id;
     $product->readOne();

     $product_arr[] = array(
       'id' => $product->id,
       'code' => $product->code,
       'name' => $product->name,
       'status' => $product->status
     );

     return json_encode($product_arr);
   }

   function updateRecord() {
     $product->id = $data->id;
   	$product->code = $data->code;
   	$product->name	= $data->name;
   	$product->status = $data->status;

   	if ($product->update()) {
   		return 'Product was updated.';
   	} else {
   		return 'Unable to update product';
   	}
   }

   function deleteRecord() {
     $product->id = $data->id;

   	if ($product->delete()) {
   		return 'Product was deleted.';
   	} else {
   		return 'Unable to delete product';
   	}
   }
   public $result;
   function doAction($type) {
     $reuslt = undefined;
     switch($type) {
       case "create":
         $result = createRecord();
         break;
       case "read":
         $result = readRecord();
         break;
       case "readOne":
         $result = readDetail();
         break;
       case "update":
         $result = updateRecord();
         break;
       case "delete":
         $result = deleteRecord();
         break;
       default:
         $result = readRecord();
     }
     return $result;
  }
print_r(doAction($data->action));
?>
