<?php
include("connection.php");
require_once("headers.php");

$tweet = $_POST["tweet"];
$userID = $_POST["user_id"];
$image = $_POST["image"];

// username condition script
if(isset($tweet) && strlen($tweet) > 280){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid tweet'
    ]);
    
    return;   
}    

$query = $mysqli->prepare("INSERT INTO `tweets` (`tweet`, `user_id`) VALUE (?, ?) "); 
$query->bind_param("ss", $tweet, $userID);
$query->execute();

$tweet_id = mysqli_insert_id($mysqli);

if ($tweet_id === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't add tweet"
    ]);
    
    return;
}

if(isset($image) && !empty($image)){
    $img = base64_decode($image);
    $split_image = pathinfo($img);

    $file_name = "images/".$split_image['filename'].".".$split_image['extension'];
    if(!file_put_contents($file_name, $img)){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => "Error: Invalid image"
    ]);
    
    return;
    }

    $query = $mysqli->prepare("INSERT INTO `images` (`image`, `tweet_id`) VALUE (?, ?) "); 
    $query->bind_param("ss", $file_name, $tweet_id);
    $query->execute();
}  

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>