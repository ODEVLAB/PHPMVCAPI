<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../../../config/Database.php';
include_once '../../../Models/Products.php';
 
$database = new Database();
$db = $database->getConnection();
 
$products = new Products($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->name) && 
!empty($data->description) && !empty($data->price) && 
!empty($data->category_id)){
	
	//strip tags to prevent XSS attacks
	$pname = htmlspecialchars(strip_tags($data->name));
	$pdescription = htmlspecialchars(strip_tags($data->description));
	$pprice = htmlspecialchars(strip_tags($data->price));
	$pcategory_id = htmlspecialchars(strip_tags($data->category_id));
	
	//validate price
    if(!is_numeric($pprice) || $pprice < 0){
        http_response_code(400);
        echo json_encode([
            "status" => "false",
            "message" => "Unable to create Product. Price must be numeric."
        ]);
        exit();
    }
    //validate category_id
    if(!is_numeric($pcategory_id) || $pcategory_id < 1){
        http_response_code(400);
        echo json_encode([
            "status" => "false",
            "message" => "Unable to create Product. Category ID must be numeric."
        ]);
        exit();
    }


	// set product property values
	$products->id = $data->id; 
	$products->name = $pname;
    $products->description = $pdescription;
    $products->price = $pprice;
    $products->category_id = $pcategory_id;	
    $products->updatedAt = date('Y-m-d H:i:s'); 
	
	
	if($products->update()){     
		http_response_code(200);   
		echo json_encode([
			"status" => "true",
			"message" => "Product Updated Successfully."
		]);
	}else{    
		http_response_code(503);     
		echo json_encode([
			"status" => "false",
			"message" => "Unable to update Product."
		]);
	}
	
} else {
	http_response_code(400);    
    echo json_encode([
		"status" => "false",
		"message" => "Unable to update Product. Data is incomplete."
	]);
}
?>