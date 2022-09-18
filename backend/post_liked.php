<?php
include("connection.php");
require_once("headers.php");

$tweetID = $_GET["tweet_id"];
$userID = $_GET["user_id"];

//Validate input
if(!isset($tweetID) || empty($tweetID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid tweet'
    ]);
    
    return;   
}   

//Validate input
if(!isset($userID) || empty($userID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid user'
    ]);
    
    return;   
} 

//Prepare and execute SQL query to check if the tweet was liked or not
$query = $mysqli->prepare("SELECT id FROM likes WHERE `user_id` = ? and `tweet_id` = ?");
$query->bind_param("ss", $userID, $tweetID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

echo json_encode(! empty($array));
?>