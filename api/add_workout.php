<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

// If user not logged in, block access
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

// Read and decode POST JSON data
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["error" => "Invalid JSON input"]);
    exit;
}

// Extract workout details
$workout_name = trim($input['workout_name'] ?? '');
$workout_type = trim($input['workout_type'] ?? '');
$duration = intval($input['duration_minutes'] ?? 0);
$intensity = trim($input['intensity'] ?? '');

// Basic validation
if ($workout_name === '' || $duration <= 0) {
    echo json_encode(["error" => "Workout name and duration are required"]);
    exit;
}

try {
    // Insert new workout into DB
    $stmt = $pdo->prepare("
        INSERT INTO workouts (user_id, workout_name, workout_type, duration_minutes, intensity, date_performed)
        VALUES (:user_id, :workout_name, :workout_type, :duration, :intensity, CURRENT_DATE)
    ");

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':workout_name' => $workout_name,
        ':workout_type' => $workout_type,
        ':duration' => $duration,
        ':intensity' => $intensity,
    ]);

    echo json_encode(["success" => true, "message" => "Workout added successfully"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}

?>
