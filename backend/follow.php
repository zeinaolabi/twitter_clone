<?php
include("connection.php");
require_once("headers.php");

//Get input from user
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

//Prepare and execute query to get follow id
$query = $mysqli->prepare("SELECT id FROM `follows` WHERE `user_id` = ? and `followeduser_id` = ?");
$query->bind_param("ss", $userID, $followedUserID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

//If a result was given, send back an error
if (!empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Already followed user'
    ]);
    
    return;
}

//Prepare and execute SQL query to follow user
$query = $mysqli->prepare("INSERT INTO `follows` (`user_id`, `followeduser_id`) VALUE (?, ?) "); 
$query->bind_param("ss", $userID, $followedUserID);
$query->execute();

$tweet_id = mysqli_insert_id($mysqli); 

//If a new id wasn't inserted, send back an error
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