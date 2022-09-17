<?php
// DataBase connection
include("connection.php");

// isset functions to determine whether a variable is set or not.
$array_response = [];

if(isset($_POST["email"])  &&  !empty($_POST["email"] )){
    $email = ($_POST["email"]);
}else{
    $array_response["status"] = "Please enter an email.";
    $json_response= json_encode($array_response);
    die($json_response); 
}

if(isset($_POST["password"])  && !empty($_POST["password"] )){
    $password = ($_POST["password"]);
    $password = hash("sha256", $password);
}else{
    $array_response["status"] = "Please enter a password.";
    $json_response= json_encode($array_response);
    die($json_response);
}
// prepares an SQL statement for execution
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