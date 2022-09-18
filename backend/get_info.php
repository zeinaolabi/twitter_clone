<?php
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];  

if(!isset($userID) || empty($userID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid user'
    ]);
    
    return;   
} 

$query = $mysqli->prepare("SELECT * FROM users WHERE `id` = ?");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

echo json_encode($array);
?>