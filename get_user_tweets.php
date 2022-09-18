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

$query = $mysqli->prepare("SELECT tweet, image, tweets.id
FROM tweets
LEFT JOIN images
ON tweets.id = images.tweet_id");

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

$tweets = [];
while($a = $array->fetch_assoc()){
    $tweets[$a["id"]] = $a;
    $tweets[$a["id"]]['likes_count'] = 0;
}

$query = sprintf("SELECT COUNT(*) as count, likes.tweet_id
FROM likes
where likes.tweet_id IN (%s)
GROUP BY likes.tweet_id;", implode(',', array_keys($tweets)));

$query = $mysqli->prepare($query);
$query->execute();
$array = $query->get_result();

while($a = $array->fetch_assoc()){
    $tweets[$a["tweet_id"]]['likes_counts'] = $a['count'];
}

$json = json_encode($tweets);
echo $json;

$query->close();
$mysqli->close();

?>