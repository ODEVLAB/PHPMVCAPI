<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../../../config/Database.php';
include_once '../../../Models/Users.php';
 
$database = new Database();
$db = $database->getConnection();
 
$users = new Users($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
	$users->id = $data->id;
	if($users->delete()){    
		http_response_code(200); 
		echo json_encode([
			"status" => "true",
			"message" => "User was deleted."
		]);
	} else {    
		http_response_code(503);   
		echo json_encode([
			"status" => "false",
			"message" => "Unable to delete User."
		]);
	}
} else {
	http_response_code(400);    
    echo json_encode([
		"status" => "false",
		"message" => "Unable to delete User. Something Went wrong."
	]);
}
?>