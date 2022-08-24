<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../../../config/Database.php';
include_once '../../../config/passwordHelper.php';
include_once '../../../Models/Users.php';
 
$database = new Database();
$db = $database->getConnection();

$helper = new PasswordHelper();

$users = new Users($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id) && !empty($data->oldPassword) && !empty($data->password)){
	
	//strip tags to prevent XSS attacks
	$uOldPassword = htmlspecialchars(strip_tags($data->oldPassword));
	$upassword = htmlspecialchars(strip_tags($data->password));

	//validate PASSWORD
	if($helper->checkPassword($upassword) === false){
		http_response_code(400);
		echo json_encode([
			"status" => "false",
			"message" => "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."
		]);
		exit();
	}
	// verify old password
	$users->id = $data->id;
	$oldPass = $users->getPassword();

	if($helper->verifyPassword($uOldPassword, $oldPass) === false){
		http_response_code(400);
		echo json_encode([
			"status" => "false",
			"message" => "Old password is incorrect."
		]);
		exit();
	}

	//hash password before saving
	$hashpass = $helper->hashPassword($upassword);

	$users->id = $data->id;
	$users->password = $hashpass;
    $users->updatedAt = date('Y-m-d H:i:s'); 
	
	
	if($users->updatePassword()){     
		http_response_code(200);   
		echo json_encode([
			"status" => "true",
			"message" => "Password Updated Successfully."
		]);
	}else{    
		http_response_code(503);     
		echo json_encode([
			"status" => "false",
			"message" => "Unable to Update Password."
		]);
	}
	
} else {
	http_response_code(400);    
    echo json_encode([
		"status" => "false",
		"message" => "Unable to Update Password. Something Went Wrong."
	]);
}
?>