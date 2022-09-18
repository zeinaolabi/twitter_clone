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