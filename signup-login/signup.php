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
        $array_response["status"] = "Email already exists! Please use another email.";
        $json_response = json_encode($array_response);
        die($json_response);
    }
    
}else{
    $array_response["status"] = "PLEASE ENTER AN EMAIL ";
        $json_response = json_encode($array_response);
        die($json_response);
};   

// password condition script
if(isset($_POST["password"])){
    $password = $_POST["password"];
    $password = hash("sha256", $password);
}else{
    $array_response["status"] = "PLEASE ENTER A PASSWORD";
        $json_response = json_encode($array_response);
        die($json_response);
}; 

// first-name condition script
if(isset($_POST["first_name"])){ 
    $first_name = $_POST["first_name"];
}else{
    $array_response["status"] = "PLEASE ENTER YOUR FIRST NAME";
        $json_response = json_encode($array_response);
        echo($json_response);
};   

// last-name condition script
if(isset($_POST["last_name"])){ 
    $last_name = $_POST["last_name"];
}else{
    $array_response["status"] = "PLEASE ENTER YOUR LAST NAME";
        $json_response = json_encode($array_response);
        echo($json_response);
};   

$query = $mysqli->prepare("INSERT INTO `users` ( `username`, `email`, `password`, `first_name`, `last_name`) VALUES (? , ? , ? , ? , ?) "); 
$query->bind_param("sssss", $username, $email, $password, $first_name, $last_name);
$query->execute();

$array_response = [];
$array_response["status"] = "SIGNED UP SUCCESSFULLY";
$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();

?>