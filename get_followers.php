<?php
//Get connection and headers
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];

if(!isset($userID) || empty($userID)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid ID'
    ]);
    
    return;
}

// prepares an SQL statement for execution
$query = $mysqli->prepare("SELECT follows.user_id, users.username
FROM follows 
INNER JOIN users
ON follows.user_id = users.id
WHERE follows.followeduser_id = ?;");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result();

$followings = [];

while($a = $array->fetch_assoc()){
    $followings[] = $a;
}

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