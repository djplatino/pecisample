<?php

$host = "localhost";
$user = "root";
$password = "gil";
$database = "peci";
$mysql = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_errno()) {
    die( '{"status":"failed","message":"Database could not be instantiated", "error":"001"}'); 
}



