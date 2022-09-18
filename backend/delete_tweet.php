<?php
include("connection.php");
require_once("headers.php");

//Get tweet id from user
$tweet_id = $_GET["tweet_id"];

//Validate id
if(!isset($tweet_id) || empty($tweet_id)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid id'
    ]);
    
    return;   
}    

//Prepare and execute query to get the tweet
$query = $mysqli->prepare("SELECT id FROM tweets WHERE id = ?");
$query->bind_param("s", $tweet_id);
$query->execute();

$query->store_result();
$num_rows = $query->num_rows;
    
//If no result was found, send back an error
if($num_rows == 0){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "ID doesn't Exist"]);
    return;
}

//Prepare and execute delete SQL query for the tweet
$query = $mysqli->prepare("DELETE FROM `tweets` WHERE id = ?"); 
$query->bind_param("s", $tweet_id);
$query->execute();

//Prepare and execute delete SQL query for the image
$query = $mysqli->prepare("DELETE FROM `images` WHERE tweet_id = ?"); 
$query->bind_param("s", $tweet_id);
$query->execute();

//Prepare and execute delete SQL query for the likes
$query = $mysqli->prepare("DELETE FROM `likes` WHERE tweet_id = ?"); 
$query->bind_param("s", $tweet_id);
$query->execute();

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>