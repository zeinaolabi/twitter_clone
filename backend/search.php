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

// prepares an SQL statement for execution
$query = $mysqli->prepare("SELECT id, username FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();

$array = $query->get_result()->fetch_assoc();

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