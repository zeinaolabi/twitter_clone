<?php
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];
$followedUserID = $_GET["followeduser_id"];

if(!isset($userID) || empty($userID) || !isset($followedUserID) || empty($followedUserID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid users'
    ]);
    
    return;   
}    

$query = $mysqli->prepare("SELECT id FROM `follows` WHERE `user_id` = ? and `followeduser_id` = ?");
$query->bind_param("ss", $userID, $followedUserID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

if (!empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Already followed user'
    ]);
    
    return;
}

$query = $mysqli->prepare("INSERT INTO `follows` (`user_id`, `followeduser_id`) VALUE (?, ?) "); 
$query->bind_param("ss", $userID, $followedUserID);
$query->execute();

$tweet_id = mysqli_insert_id($mysqli); 

if ($tweet_id === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't follow user"
    ]);
    
    return;
}

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>