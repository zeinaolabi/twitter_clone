<?php
include("connection.php");

$array_response = [];

if(isset($_POST["id"])){
    $id = $_POST["id"];
    $id=decrypt($id);
}else{
    $array_response["message"] = "Its not available";
    $json_response = json_encode($array_response);
    return $json_response;
}


$json_response = json_encode($array_response);
echo $json_response;

$query->close();
$mysqli->close();
?>