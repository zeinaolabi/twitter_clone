<?php
include("connection.php");
require_once("headers.php");

$tweetID = $_GET["tweet_id"];
$userID = $_GET["user_id"];

if(!isset($tweetID) || empty($tweetID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid tweet'
    ]);
    
    return;   
}   

if(!isset($userID) || empty($userID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid user'
    ]);
    
    return;   
} 

$query = $mysqli->prepare("SELECT id FROM likes WHERE `user_id` = ? and `tweet_id` = ?");
$query->bind_param("ss", $userID, $tweetID);
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

$query = $mysqli->prepare("INSERT INTO `likes` (`user_id`, `tweet_id`) VALUE (?, ?) "); 
$query->bind_param("ss", $userID, $tweetID);
$query->execute();

$tweet_id = mysqli_insert_id($mysqli);

if ($tweet_id === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't like tweet"
    ]);
    
    return;
}

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>