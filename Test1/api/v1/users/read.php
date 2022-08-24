<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../../../config/Database.php';
include_once '../../../Models/Users.php';

$database = new Database();
$db = $database->getConnection();
 
$users = new Users($db);

$users->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $users->read();

if($result->num_rows > 0){    
	while ($user = $result->fetch_assoc()) { 
            $userRecords[] = [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "age" => $user['age'],
                "designation" => $user['designation'],
                "createdAt" => $user['createdAt'],
                "updatedAt" => $user['updatedAt']
            ];
    }    
    http_response_code(200);     
    echo json_encode([
        "status" => "true",
        "message" => "User Retrieved Successfully.",
        "Users" => $userRecords
    ]);
}else{     
    http_response_code(404);     
    echo json_encode(
        [
            "status" => "false",
            "message" => "No user found."
        ]);
}