<?php

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include database and object files
include_once '../../../config/Database.php';
include_once '../../../config/passwordHelper.php';
include_once '../../../Models/Users.php';
include_once '../../../Models/Wallets.php';

//instantiate database and user object
$database = new Database();
$db = $database->getConnection();

$helper = new PasswordHelper();
 
$users = new Users($db);
$wallets = new Wallets($db);
 
$data = json_decode(file_get_contents("php://input"));

//check for null values
if(!empty($data->id) && !empty($data->amount) && !empty($data->password)){    

    //strip tags to prevent XSS attacks
    $uamount = htmlspecialchars(strip_tags($data->amount));
    $upassword = htmlspecialchars(strip_tags($data->password));

    //validate amount
    if(!is_numeric($uamount) || $uamount < 0){
        http_response_code(400);
        echo json_encode([
            "status" => "false",
            "message" => "Unable to create User. Amount is invalid or unauthorized."
        ]);
        exit();
    }
    // verify password
	$users->id = $data->id;
	$oldPass = $users->getPassword();

	if($helper->verifyPassword($upassword, $oldPass) === false){
		http_response_code(400);
		echo json_encode([
			"status" => "false",
			"message" => "Password is incorrect."
		]);
		exit();
	}

    //function to initiate funding via payment gateway
    //if successful, update user's wallet balance
    $response = 'true';


    //response is ok proceed fund wallet
    if($response === 'true'){
        // set product property values
        $wallets->user_id = $data->id;
        $wallets->amount = $uamount;
        $wallets->tr_type = 'Credit';
        $wallets->initiatedAt = date('Y-m-d H:i:s'); 
        
        if($wallets->fundWallet()){         
            http_response_code(201);         
            echo json_encode([
                "status" => "true",
                "message" => "Wallet Fund Successfull."
        ]);
        }else{
            http_response_code(503);
            echo json_encode([
                "status" => "false",
                "message" => "Unable to Fund Wallet. Service Unavailable."
            ]);
        }
    }else{    
        http_response_code(400);    
        echo json_encode([
            "status" => "false",
            "error" => $response,
            "message" => "Unable to Initialize Payment."
        ]);
    }
}else{
    http_response_code(400);
    echo json_encode([
        "status" => "false",
        "message" => "Unable to Process Request"
    ]);
}
