<?php

session_start();

// Optional: recreate session from cookie (remember me)
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
}

// If user is logged in and tries to access home, redirect to dashboard
if (isset($_SESSION['user_id']) && (!isset($_GET['page']) || $_GET['page'] === 'home')) {
    header('Location: dashboard.php');
    exit;
}

// Determine which page to display. Default to 'home'.
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Define a list of allowed pages to prevent security issues.
$allowed_pages = ['home', 'schedule', 'tour', 'nutrition_advanced', 'videos'];

// If the requested page is not in the allowed list, default to home.
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Include the header partial.
include 'partials/header.php';

// Include the main content for the requested page.
// The main tag is opened in the header and closed in the footer.
include 'pages/' . $page . '.php';

// Include the footer partial.
include 'partials/footer.php';
?>
