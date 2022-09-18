<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include("connection.php");
require_once("headers.php");

$userID = $_POST["user_id"];
$name = $_POST["name"];
$profilePicture = $_POST["profile_picture"];
$coverPicture = $_POST["cover_picture"];
$bio = $_POST["bio"];  

$query = $mysqli->prepare("SELECT id FROM users WHERE `id` = ?");
$query->bind_param("s", $userID);
$query->execute();

$array = $query->get_result()->fetch_assoc();

if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => "User doesn't exist"
    ]);
    
    return;
}

if(isset($profilePicture) && !empty($profilePicture)){    
    $img = base64_decode(str_replace('data:image/png;base64,', '', $image));
    $split_image = 'png';
    
    $file_name = sprintf("images/%s.png", bin2hex(random_bytes(10)));
    if(!file_put_contents($file_name, $img)){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => "Error: Invalid image"
    ]);
    
        return;
    }
}  

$query = $mysqli->prepare("UPDATE `users` 
SET `name` = ?, `profile_picture` = ?, `cover_picture` = ?, `bio` = ?
WHERE id = ?;");
$query->bind_param("sssss", $name, $file_name, $coverPicture, $bio, $userID);
$query->execute();

if ($userID === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Incorrect data'
    ]);
    
    return;
}

$json = json_encode(['status' => "success!"]);
echo $json;

$query->close();
$mysqli->close();

?>