<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();
session_start();

require_once __DIR__ . '/services/AIService.php';

ob_clean();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['text']) || empty(trim($input['text']))) {
    echo json_encode(['success' => false, 'error' => 'No input text provided']);
    exit;
}

try {
    $aiService = new AIService();
    $result = $aiService->parseNutritionInput($input['text']);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
