<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        exit("❌ Email and password are required.");
    }

    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE LOWER(email) = LOWER(?)");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "❌ Email not found.";
    } elseif (!password_verify($password, $user['password_hash'])) {
        echo "❌ Incorrect password.";
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Handle remember me
        if (isset($_POST['remember'])) {
            setcookie("user_id", $user['id'], time() + (30*24*60*60), "/");
            setcookie("username", $user['username'], time() + (30*24*60*60), "/");
        }
        
        echo "✅ Login successful! Redirecting...";
        exit;
    }
}
?>
