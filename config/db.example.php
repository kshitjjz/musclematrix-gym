<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = '127.0.0.1';
$db   = 'gym_app';
$user = 'postgres';
$pass = 'NewSecurePassword123';  // Change this to your PostgreSQL password
$port = '5432';

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
