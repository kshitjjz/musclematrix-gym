<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$port = '3306';
$host = getenv("mysql-ngp.railway.internal");
$user = getenv("root");
$password = getenv("uTrOIpJusWAcaIaRutUhegNASYwffjKJ");
$db = getenv("railway");

$conn = new mysqli($host, $user, $password, $db);

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    if (isset($_GET['action']) || isset($_POST['action'])) {
        header('Content-Type: application/json');
        die(json_encode(["error" => "Database connection failed"]));
    } else {
        die("Database connection failed");
    }
}
?>
