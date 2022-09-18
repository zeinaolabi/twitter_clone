<?php
include("connection.php");
require_once("headers.php");

$userID = $_POST["user_id"];
$followedUserID = $_POST["followeduser_id"];

// username condition script
if(!isset($userID) || empty($userID) || !isset($followedUserID) || empty($followedUserID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid users'
    ]);
    
    return;   
}    

$query = $mysqli->prepare("SELECT id FROM follow WHERE `user_id` = ? and `followeduser_id` = ?");
$query->bind_param("ss", $userID, $followedUserID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

if (!empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Post already liked'
    ]);
    
    return;
}

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>