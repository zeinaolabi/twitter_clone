<?php
// include headers for responses
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
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
$query = $mysqli->prepare("SELECT id, email, password  FROM users WHERE email = ? AND password = ?");
$query->bind_param("ss", $email, $password);
$query->execute();

$query->store_result();
$num_rows = $query->num_rows;
$query->bind_result($id, $email, $password);
$query->fetch();

// return response
if($num_rows == 0){
    $array_response["status"] = "This account doesn't exist";
}else{
    $array_response["status"] = "Logged In !";
}
echo json_encode($array_response);

$query->close();
$mysqli->close();

?>
