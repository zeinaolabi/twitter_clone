<?php
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];  

//Validate input
if(!isset($userID) || empty($userID)){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid user'
    ]);
    
    return;   
} 

//Prepare and execute query to get users' info
$query = $mysqli->prepare("SELECT * FROM users WHERE `id` = ?");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

if (isset($array['profile_picture'])) {
    //Encode image to base64 before sending
    $array['profile_picture'] = base64_encode(file_get_contents($array['profile_picture']));
}

if (isset($array['cover_picture'])) {
    //Encode image to base64 before sending
    $array['cover_picture'] = base64_encode(file_get_contents($array['cover_picture']));
}

echo json_encode($array);
?>