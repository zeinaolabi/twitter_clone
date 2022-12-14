<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include("connection.php");
require_once("headers.php");

$tweet_id = $_GET["tweet_id"];

// username condition script
if(!isset($tweet_id) || empty($tweet_id)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid id'
    ]);
    
    return;   
}    

$query = $mysqli->prepare("SELECT id FROM tweets WHERE id = ?");
$query->bind_param("s", $tweet_id);
$query->execute();

$query->store_result();
$num_rows = $query->num_rows;
    
if($num_rows == 0){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "ID doesn't Exist"]);
    return;
}

$query = $mysqli->prepare("DELETE FROM `tweets` WHERE id = ?"); 
$query->bind_param("s", $tweet_id);
$query->execute();

$query = $mysqli->prepare("DELETE FROM `images` WHERE tweet_id = ?"); 
$query->bind_param("s", $tweet_id);
$query->execute();

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>