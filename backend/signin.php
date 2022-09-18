<?php 
// DataBase connection
include("connection.php");
require_once("headers.php");

$email = $_POST["email"];
$password = $_POST["password"];
$password = hash("sha256", $password);

$query = $mysqli->prepare("SELECT id  FROM users WHERE email = ? AND password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$array = $query->get_result()->fetch_assoc();

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