<?php
//Get connection and headers
include("connection.php");
require_once("headers.php");

$username = $_GET["username"];


//Validate input
if(!isset($username) || empty($username)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid username'
    ]);
    
    return;
}

//Prepare and execute SQL query to get id and username of user
$query = $mysqli->prepare("SELECT id, username FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();

$array = $query->get_result()->fetch_assoc();

//If no result, send an error
if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'No such username'
    ]);
    
    return;
}

$json = json_encode($array);
echo $json;

$query->close();
$mysqli->close();

?>