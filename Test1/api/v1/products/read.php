<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../../config/Database.php';
include_once '../../../Models/Products.php';

$database = new Database();
$db = $database->getConnection();
 
$products = new Products($db);

$products->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $products->read();

if($result->num_rows > 0){    
	while ($product = $result->fetch_assoc()) { 
            $productRecords[] = [
                "id" => $product['id'],
                "name" => $product['name'],
                "description" => $product['description'],
                "price" => $product['price'],
                "category_id" => $product['category_id'],
                "createdAt" => $product['createdAt'],
                "updatedAt" => $product['updatedAt']
            ];
    }    
    http_response_code(200);     
    echo json_encode([
        "status" => "true",
        "message" => "Product Retrieved Successfully.",
        "products" => $productRecords
    ]);
}else{     
    http_response_code(404);     
    echo json_encode(
        [
            "status" => "false",
            "message" => "No Product found."
        ]);
}