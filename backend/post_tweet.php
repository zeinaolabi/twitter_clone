<?php
include("connection.php");
require_once("headers.php");

$tweet = $_POST["tweet"];
$userID = $_POST["user_id"];
$image = $_POST["image"];

//Validate tweet
if(isset($tweet) && strlen($tweet) > 280){ 
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid tweet'
    ]);
    
    return;   
}    

//Prepare and execute query to add a new tweet
$query = $mysqli->prepare("INSERT INTO `tweets` (`tweet`, `user_id`) VALUE (?, ?) "); 
$query->bind_param("ss", $tweet, $userID);
$query->execute();

$tweet_id = mysqli_insert_id($mysqli);

//If no new id was inserted, return an error
if ($tweet_id === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "Error: Can't add tweet"
    ]);
    
    return;
}


if(isset($image) && !empty($image)){
    //Decode image to base 64
    $img = base64_decode(str_replace('data:image/png;base64,', '', $image));
    $split_image = 'png';
    
    //Save the file by using a random name
    $file_name = sprintf("images/%s.png", bin2hex(random_bytes(10)));

    //If file not saved in folder, send back an error
    if(!file_put_contents($file_name, $img)){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => "Error: Invalid image"
    ]);
    
        return;
    }

    //Prepare and execute SQL query to add image
    $query = $mysqli->prepare("INSERT INTO `images` (`image`, `tweet_id`) VALUE (?, ?) "); 
    $query->bind_param("ss", $file_name, $tweet_id);
    $query->execute();
}  

$json = json_encode(['message' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>