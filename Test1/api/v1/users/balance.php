<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../../../config/Database.php';
include_once '../../../config/passwordHelper.php';
include_once '../../../Models/Wallets.php';
include_once '../../../Models/Users.php';
 
$database = new Database();
$db = $database->getConnection();

$helper = new PasswordHelper();

$wallets = new Wallets($db);
$users = new Users($db);

$data = json_decode(file_get_contents("php://input"));

//check for null values
if(!empty($data->id)){
    $users_id = $data->id;
    $result = $wallets->getBalance($users_id);
    if($result){
        http_response_code(200);
        echo json_encode([
            "status" => "true",
            "message" => "User Balance Retrieved.",
            "balance" => $result
        ]);
    }else{
        http_response_code(404);
        echo json_encode([
                "status" => "false",
                "message" => "Unable to Perform Request."
            ]);
        }

    }else{
        http_response_code(404);     
        echo json_encode([                
                "status" => "false",
                "message" => "Unauthenticated."
            ]);
    }
    
?>