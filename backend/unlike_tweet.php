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

//Prepare and execute SQL query to get like id
$query = $mysqli->prepare("SELECT id FROM likes WHERE `user_id` = ? and `tweet_id` = ?");
$query->bind_param("ss", $userID, $tweetID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

//If not result was sent back, send an error
if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Post not liked'
    ]);
    
    return;
}

$like_id = $array["id"];

//Prepare and execute SQL query to delete like
$query = $mysqli->prepare("DELETE FROM `likes` WHERE `id` = ?"); 
$query->bind_param("s", $like_id);

//If the query didn't execute, send an error
if (!$query->execute()) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't unlike tweet"
    ]);
    
    return;
}

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>