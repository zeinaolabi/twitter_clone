<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include("connection.php");
require_once("headers.php");

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$birthDate = $_POST["birth_date"];
$password = hash("sha256", $password);

$query = $mysqli->prepare("INSERT INTO `users` ( `username`, `email`, `password`, `birth_date`) VALUES (? , ? , ? , ?) "); 
$query->bind_param("ssss", $username, $email, $password, $birthDate);
$query->execute();

$query = $mysqli->prepare("SELECT id  FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();

$array = $query->get_result();

if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Incorrect data'
    ]);
    
    return;
}

$json = json_encode($array->fetch_assoc());
echo $json;

$query->close();
$mysqli->close();

?>