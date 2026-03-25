<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MuscleMatrix Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
</head>
<body class="bg-gray-900">
    <header class="bg-gray-900/80 backdrop-blur-sm sticky top-0 z-40 border-b border-gray-800">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php?page=home" class="text-2xl font-bold text-white">Muscle<span class="text-red-500">Matrix</span></a>
            <div class="hidden md:flex items-center space-x-6">
                <a href="index.php?page=home" class="text-gray-300 hover:text-red-400">Home</a>
                <a href="index.php?page=schedule" class="text-gray-300 hover:text-red-400">Schedule</a>
                <a href="index.php?page=tour" class="text-gray-300 hover:text-red-400">Tour</a>
                <a href="index.php?page=nutrition_advanced" class="text-gray-300 hover:text-red-400">Nutrition AI</a>
                <a href="index.php?page=videos" class="text-gray-300 hover:text-red-400">Videos</a>
            </div>
            

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User is logged in: show profile -->
                <div class="flex items-center space-x-4">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="dashboard.php" class="bg-emerald-500 text-gray-900 px-4 py-2 rounded">Dashboard</a>
                     <a href="auth/logout.php" class="bg-red px-4 py-2 rounded cta-button">Logout</a>
                </div>
            <?php else: ?>
                <!-- User not logged in: show login/register -->
                <button class="open-login-modal bg-emerald-500 text-gray-900 font-bold py-2 px-5 rounded-lg cta-button">Login</button>            
            <?php endif; ?>

            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
            
        </nav>
        <div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
            <a href="index.php?page=home" class="block text-gray-300 hover:text-red-400 py-2">Home</a>
            <a href="index.php?page=schedule" class="block text-gray-300 hover:text-red-400 py-2">Schedule</a>
            <a href="index.php?page=tour" class="block text-gray-300 hover:text-red-400 py-2">Tour</a>
            <a href="index.php?page=nutrition" class="block text-gray-300 hover:text-red-400 py-2">Nutrition</a>
            <a href="index.php?page=videos" class="block text-gray-300 hover:text-red-400 py-2">Videos</a>
            <button class="open-login-modal w-full mt-4 bg-red text-gray-900 font-bold py-2 px-5 rounded-lg cta-button">Login</button>
        </div>
    </header>
    <main>
