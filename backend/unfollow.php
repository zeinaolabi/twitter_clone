<?php
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];
$followedUserID = $_GET["followeduser_id"];

//Validate input
if(!isset($userID) || empty($userID) || !isset($followedUserID) || empty($followedUserID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid users'
    ]);
    
    return;   
}   

//Prepare and execute SQL query to get unfollow id
$query = $mysqli->prepare("SELECT id FROM `follows` WHERE `user_id` = ? and `followeduser_id` = ?");
$query->bind_param("ss", $userID, $followedUserID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

//If the result was empty, return an error
if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'User not followed'
    ]);
    
    return;
}

$follow_id = $array["id"];

//Prepare and execute SQL query to unfollow a user
$query = $mysqli->prepare("DELETE FROM `follows` WHERE `id` = ?"); 
$query->bind_param("s", $follow_id);

if (!$query->execute()) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't unfollow user"
    ]);
    
    return;
}

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>