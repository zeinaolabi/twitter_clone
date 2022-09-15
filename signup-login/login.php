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

if(isset($_POST["password"])){
    $password = ($_POST["password"]);
    $password = hash("sha256", $password);
}else{
    $array_response["status"] = "Please enter a password.";
    $json_response= json_encode($array_response);
    die($json_response);
}
?>