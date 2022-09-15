<?php

header("Access-Control-Allow-Origin: *");

$db_host = "localhost";
$db_user = "root";
$db_pass = null;
$db_name = "twitterdb";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    printf("connect failed: %s\n", $mysqli->connect_error);
    exit();
}
if ($mysqli->ping()) {
    printf ("Our connection is ok!\n");
} else {
    printf("Error: %s\n", $mysqli->error);
}

?>
