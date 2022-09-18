<?php 
// DataBase connection
include("connection.php");
require_once("headers.php");

$email = $_POST["email"];
$password = $_POST["password"];

//Validate input
if(!isset($email) || empty($email)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid Email']);
    return;
}

//Validate input
if(!isset($password)  || empty($password)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid Password']);
    return;   
}

//Hash password
$password = hash("sha256", $password);

//Prepare and execute SQL query to log in
$query = $mysqli->prepare("SELECT id  FROM users WHERE email = ? AND password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$array = $query->get_result()->fetch_assoc();

//If no result was given, send an error
if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'incorrect username or password'
    ]);

    return;
}

$json = json_encode($array);
echo $json;

$query->close();
$mysqli->close();

?>