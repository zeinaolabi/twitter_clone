<?php
include("connection.php");
require_once("headers.php");

//Get inputs from user
$userID = $_POST["user_id"];
$name = $_POST["name"];
$profilePicture = $_POST["profile_picture"];
$coverPicture = $_POST["cover_picture"];
$bio = $_POST["bio"];  

//Get user id
$query = $mysqli->prepare("SELECT id FROM users WHERE `id` = ?");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

//If no result was given back(no user), send an error
if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "User doesn't exist"
    ]);
    
    return;
}

//Check if an image was sent
if(isset($profilePicture) && !empty($profilePicture)){   
    //Decode the image from base64 
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
}  


//Prepare and execute SQL query to update users' info
$query = $mysqli->prepare("UPDATE `users` 
SET `name` = ?, `profile_picture` = ?, `cover_picture` = ?, `bio` = ?
WHERE id = ?;");
$query->bind_param("sssss", $name, $file_name, $coverPicture, $bio, $userID);
$query->execute();

$json = json_encode(['status' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>