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

$query = $mysqli->prepare("SELECT tweet, image, tweets.id, users.username, users.name, users.profile_picture
FROM tweets
LEFT JOIN images
ON tweets.id = images.tweet_id
INNER JOIN users
on users.id = tweets.user_id
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

$tweets = [];
while($a = $array->fetch_assoc()){
    $tweets[$a["id"]] = $a;
    $tweets[$a["id"]]['likes_count'] = 0;
    $tweets[$a["id"]]['tweet_id'] = $a["id"];
}

$query = sprintf("SELECT COUNT(*) as count, likes.tweet_id
FROM likes
where likes.tweet_id IN (%s)
GROUP BY likes.tweet_id;", implode(',', array_keys($tweets)));

$query = $mysqli->prepare($query);
$query->execute();
$array = $query->get_result();

while($a = $array->fetch_assoc()){
    $tweets[$a["tweet_id"]]['likes_count'] = $a['count'];
}

$json = json_encode(array_values($tweets));
echo $json;

$query->close();
$mysqli->close();

?>