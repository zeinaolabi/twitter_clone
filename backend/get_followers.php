<?php
//Get connection and headers
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];

//Validate input
if(!isset($userID) || empty($userID)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid ID'
    ]);
    
    return;
}

//Prepare and execute to get followers
$query = $mysqli->prepare("SELECT follows.user_id, users.username
FROM follows 
INNER JOIN users
ON follows.user_id = users.id
WHERE follows.followeduser_id = ?;");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result();

//Save result in followings array
$followings = [];
while($a = $array->fetch_assoc()){
    $followings[] = $a;
}

//If the array was empty, send back an error
if (empty($followings)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'No users'
    ]);
    
    return;
}

$json = json_encode($followings);
echo $json;

$query->close();
$mysqli->close();
?>