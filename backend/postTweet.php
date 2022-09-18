<?php
include("connection.php");
require_once("headers.php");

$tweet = $_POST["tweet"];
$userID = $_POST["userID"];
$image = $_POST["image"];

// username condition script
if(!isset($tweet) || empty($tweet) || strlen($tweet) > 280){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid tweet'
    ]);
    
    return;   
}    

$query = $mysqli->prepare("INSERT INTO `tweets` (`tweet`, `user_id`) VALUE (?, ?) "); 
$query->bind_param("ss", $tweet, $userID);
$query->execute();

$tweet_id = mysqli_insert_id($mysqli);

if ($tweet_id === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't add tweet"
    ]);
    
    return;
}

$json = json_encode($array["status"] = "success!");
echo $json;

$query->close();
$mysqli->close();

?>