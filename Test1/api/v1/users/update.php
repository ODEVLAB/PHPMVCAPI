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

if(!empty($data->id) && !empty($data->name) && 
!empty($data->email) && !empty($data->age) && 
!empty($data->designation)){
	
	//strip tags to prevent XSS attacks
	$uname = htmlspecialchars(strip_tags($data->name));
	$uemail = htmlspecialchars(strip_tags($data->email));
	$uage = htmlspecialchars(strip_tags($data->age));
	$udesignation = htmlspecialchars(strip_tags($data->designation));
	
	 //validate email
	 if(!filter_var($uemail, FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode([
            "status" => "false",
            "message" => "Unable to create User. Email is invalid."
        ]);
        exit();
    }
    //validate age
    if(!is_numeric($uage) || $uage < 18){
        http_response_code(400);
        echo json_encode([
            "status" => "false",
            "message" => "Unable to create User. Age is invalid or unauthorized."
        ]);
        exit();
    }

	// set product property values
	$users->id = $data->id; 
	$users->name = $uname;
    $users->email = $uemail;
    $users->age = $uage;
    $users->designation = $udesignation;
    $users->updatedAt = date('Y-m-d H:i:s'); 
	
	
	if($users->update()){     
		http_response_code(200);   
		echo json_encode([
			"status" => "true",
			"message" => "User Account Updated."
		]);
	}else{    
		http_response_code(503);     
		echo json_encode([
			"status" => "false",
			"message" => "Unable to update User."
		]);
	}
	
} else {
	http_response_code(400);    
    echo json_encode([
		"status" => "false",
		"message" => "Unable to update User. Data is incomplete."
	]);
}
?>