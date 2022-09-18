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

?>