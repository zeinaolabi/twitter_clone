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
        'message' => 'Invalid user'
    ]);
    
    return;
}

//Prepare and execute SQL query to get tweets and tweets' owner info
$query = $mysqli->prepare("SELECT tweet, images.image, tweets.id, users.username, users.name, users.profile_picture
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
)
ORDER BY tweets.id desc LIMIT 20;");
$query->bind_param("s", $userID);
$query->execute();
$array = $query->get_result();

//If no result was sent back, send an error
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
    //Store info with a dictionary keyed with the tweet id 
    $tweets[$a["id"]] = $a;
    $tweets[$a["id"]]['likes_count'] = 0;
    $tweets[$a["id"]]['tweet_id'] = $a["id"];

    if (isset($a['image'])) {
        //Encode image to base64 before sending
        $tweets[$a["id"]]['image'] = base64_encode(file_get_contents($a['image']));
    }

    if (isset($a['profile_picture'])) {
        //Encode image to base64 before sending
        $tweets[$a["id"]]['profile_picture'] = base64_encode(file_get_contents($a['profile_picture']));
    }
}

//If array was empty, return empty response
if (empty($tweets)) {
    $json = json_encode([]);
    echo $json; 
    return;
}

//Added Tweets' ids in SQL query to get the likes of each tweet
$query = sprintf("SELECT COUNT(*) as count, likes.tweet_id
FROM likes
where likes.tweet_id IN (%s)
GROUP BY likes.tweet_id;", implode(',', array_keys($tweets)));

$query = $mysqli->prepare($query);
$query->execute();
$array = $query->get_result();

while($a = $array->fetch_assoc()){
    //Save likes count into previous array
    $tweets[$a["tweet_id"]]['likes_count'] = $a['count'];
}

$json = json_encode(array_values($tweets));
echo $json;

$query->close();
$mysqli->close();

?>