<?php

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Authorization: Bearer 4567890-");

//include database and object files
include_once '../../../config/Database.php';
include_once '../../../config/passwordHelper.php';
include_once '../../../Models/Users.php';

//instantiate database and user object
$database = new Database();
$db = $database->getConnection();

$helper = new PasswordHelper();
 
$users = new Users($db);
 
$data = json_decode(file_get_contents("php://input"));

//check for null values
if(!empty($data->name) && !empty($data->email) &&
!empty($data->age) && !empty($data->designation) && !empty($data->password)){    

    //strip tags to prevent XSS attacks
    $uname = htmlspecialchars(strip_tags($data->name));
    $uemail = htmlspecialchars(strip_tags($data->email));
    $uage = htmlspecialchars(strip_tags($data->age));
    $udesignation = htmlspecialchars(strip_tags($data->designation));
    $upassword = htmlspecialchars(strip_tags($data->password));

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
    //validate PASSWORD
    if($helper->checkPassword($upassword) === false){
        http_response_code(400);
        echo json_encode([
            "status" => "false",
            "message" => "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."
        ]);
        exit();
    }
    // after validating fields
    // secure password by hashing using the PASSWORD_DEFAULT constant
    $hashpass = $helper->hashPassword($upassword);

    // set product property values
    $users->name = $uname;
    $users->email = $uemail;
    $users->age = $uage;
    $users->designation = $udesignation;
    $users->password = $hashpass;	
    
    $users->createdAt = date('Y-m-d H:i:s'); 
    $users->updatedAt = date('Y-m-d H:i:s'); 
    
    if($users->create()){         
        http_response_code(201);         
        echo json_encode([
            "status" => "true",
            "message" => "User Created Successfully."
        ]);
    } else{         
        http_response_code(503);        
        echo json_encode([
            "status" => "false",
            "message" => "Unable to create User."
        ]);
    }
}else{    
    http_response_code(400);    
    echo json_encode([
        "status" => "false",
        "message" => "Something Went Wrong, Please Try Again."
    ]);
}
