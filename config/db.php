<?php

$host = getenv("mysql.railway.internal");
$user = getenv("root");
$password = getenv("uABBMhnmvXRFNhmZDDKAinCUeOEuizXN");
$db = getenv("railway");
$port = getenv("3306");

$conn = new mysqli($host, $user, $password, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
