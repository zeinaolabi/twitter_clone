<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
include("connection.php");

// username condition script
if(isset($_POST["username"]) && !empty($_POST["username"])){ 
    $username = $_POST["username"];
}else{
    
    $array_response["status"] = "PLEASE ENTER A USERNAME";
    $json_response = json_encode($array_response);
    die($json_response);
};    

// email condition script
if(isset($_POST["email"])){
    $email = $_POST["email"];
    $query = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();

    $query->store_result();
    $num_rows = $query->num_rows;
    if($num_rows != 0){
        $array_response["message"] = "Email already exists! Please use another email.";
        $json_response = json_encode($array_response);
        die($json_response);
    }
    
}else{
    $array_response["message"] = "PLEASE ENTER AN EMAIL ";
        $json_response = json_encode($array_response);
        die($json_response);
};   

// password condition script
if(isset($_POST["password"])){
    $password = $_POST["password"];
    $password = hash("sha256", $password);
}else{
    $array_response["message"] = "PLEASE ENTER A PASSWORD";
        $json_response = json_encode($array_response);
        die($json_response);
}; 

if(isset($_POST["birth_date"]) && !empty($_POST["birth_date"])){ 
    $birth_date = $_POST["birth_date"];
}else{
    
    $array_response["message"] = "PLEASE ENTER YOUR BIRTHDAY";
    $json_response = json_encode($array_response);
    die($json_response);
};      

$query = $mysqli->prepare("INSERT INTO `users` ( `username`, `email`, `password`, `birth_date`) VALUES (? , ? , ? , ?) "); 
$query->bind_param("ssss", $username, $email, $password, $birth_date);
$query->execute();

$query = $mysqli->prepare("SELECT id  FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();

$array = $query->get_result();

if (empty($array)) {
    http_response_code(400);
    echo json_encode([
        'error' => 400,
        'message' => 'incorrect username or password'
    ]);
    
    return;
}

$json = json_encode($array->fetch_assoc());
echo $json;

$query->close();
$mysqli->close();

?>