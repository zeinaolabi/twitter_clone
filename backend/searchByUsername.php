<?php
//Get connection and headers
include("connection.php");
require_once("headers.php");

$username = $_GET["username"];

if(!isset($username) || empty($username)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid username'
    ]);
    
    return;
}

?>