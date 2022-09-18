<?php
include("connection.php");
require_once("headers.php");

$username = strtolower(trim($_POST["username"]));
$email = $_POST["email"];
$password = $_POST["password"];
$birthDate = $_POST["birth_date"];

// username condition script
if(isset($username) && !empty($username)){
    $query = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();

    $query->store_result();
    $num_rows = $query->num_rows;
    
    if($num_rows != 0){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => 'Username Exists']);
        return;}
}else{
    if($num_rows != 0){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => 'Invalid username']);
        return;
}};    

if(isset($email) && !empty($email)){
    $query = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();

    $query->store_result();
    $num_rows = $query->num_rows;
    
    if($num_rows != 0){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => 'Email Exists']);
        return;}
}else{
    if($num_rows != 0){
        http_response_code(400);
        echo json_encode([
            'error' => 400,
            'message' => 'Invalid Email']);
        return;
    }
};   

if(!isset($password) || empty($password)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid password']);
        return;
}

if(!isset($birthDate) || empty($birthDate)){
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Invalid date']);
        return;
}      

$password = hash("sha256", $password);

$query = $mysqli->prepare("INSERT INTO `users` ( `username`, `email`, `password`, `birth_date`) VALUES (? , ? , ? , ?) "); 
$query->bind_param("ssss", $username, $email, $password, $birthDate);
$query->execute();

$userID = mysqli_insert_id($mysqli);

if ($userID === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'Incorrect data'
    ]);
    
    return;
}

$json = json_encode(['userID' => $userID]);
echo $json;

$query->close();
$mysqli->close();

?>