<?php
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

//Prepare and execute SQL query to get followings
$query = $mysqli->prepare("SELECT follows.followeduser_id, users.username
FROM follows 
INNER JOIN users
ON follows.followeduser_id = users.id
WHERE follows.user_id = ?;");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result();

//Save result in followings array
$followers = [];
while($a = $array->fetch_assoc()){
    $followers[] = $a;
}

//If the array was empty, send back an error
if (empty($followers)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'No users'
    ]);
    
    return;
}

$json = json_encode($followers);
echo $json;

$query->close();
$mysqli->close();
?>