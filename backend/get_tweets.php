<?php
//Get connection and headers
include("connection.php");
require_once("headers.php");

$userID = $_GET["user_id"];

if(!isset($userID) || empty($userID)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid user'
    ]);
    
    return;
}

$query = $mysqli->prepare("SELECT tweet, image
FROM tweets
LEFT JOIN images
ON tweets.id = images.tweet_id
WHERE tweets.user_id IN (
    SELECT followeduser_id
    FROM users
    INNER JOIN follows
    ON users.id = follows.user_id
    WHERE users.id = ?
)");

$query->bind_param("s", $userID);
$query->execute();
$array = $query->get_result();

if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'No tweets'
    ]);
    
    return;
}

$response = [];

while($a = $array->fetch_assoc()){
    $response[] = $a;
}

$json = json_encode($response);
echo $json;

$query->close();
$mysqli->close();

?>