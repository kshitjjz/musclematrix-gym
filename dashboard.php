<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get user data
$stmt = $pdo->prepare("SELECT username, email, level, xp, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get nutrition summary for today
$stmt = $pdo->prepare("SELECT * FROM user_daily_summary WHERE user_id = ? AND log_date = CURRENT_DATE");
$stmt->execute([$user_id]);
$nutritionSummary = $stmt->fetch(PDO::FETCH_ASSOC);

// Get nutrition goal
$stmt = $pdo->prepare("SELECT daily_calories, protein_g, carbs_g, fat_g FROM user_nutrition_goals WHERE user_id = ?");
$stmt->execute([$user_id]);
$nutritionGoal = $stmt->fetch(PDO::FETCH_ASSOC);

// Get food log count for today
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_food_log WHERE user_id = ? AND log_date = CURRENT_DATE");
$stmt->execute([$user_id]);
$foodCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Get activity log count for today
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_activity_log WHERE user_id = ? AND log_date = CURRENT_DATE");
$stmt->execute([$user_id]);
$activityCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Get last 7 days nutrition data for chart
$stmt = $pdo->prepare("
    SELECT log_date, net_calories, total_calories_consumed, total_calories_burned, total_protein_g, total_carbs_g, total_fat_g
    FROM user_daily_summary 
    WHERE user_id = ? AND log_date >= CURRENT_DATE - INTERVAL '6 days'
    ORDER BY log_date ASC
");
$stmt->execute([$user_id]);
$weeklyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent food logs (last 7 days)
$stmt = $pdo->prepare("
    SELECT food_name, calories, protein_g, carbs_g, fat_g, quantity, log_date, created_at
    FROM user_food_log 
    WHERE user_id = ? AND log_date >= CURRENT_DATE - INTERVAL '6 days'
    ORDER BY created_at DESC
    LIMIT 10
");
$stmt->execute([$user_id]);
$recentFoods = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent activities (last 7 days)
$stmt = $pdo->prepare("
    SELECT activity_name, duration_minutes, calories_burned, log_date, created_at
    FROM user_activity_log 
    WHERE user_id = ? AND log_date >= CURRENT_DATE - INTERVAL '6 days'
    ORDER BY created_at DESC
    LIMIT 10
");
$stmt->execute([$user_id]);
$recentActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'partials/header.php';
?>

<div class="min-h-screen bg-gray-900 py-8">
    <div class="container mx-auto px-6">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center flex-wrap gap-4">
            <div style="position: relative; z-index: 20;">
                <h1 class="text-4xl font-bold text-white mb-2" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Welcome back, <?= htmlspecialchars($username) ?>! 👋</h1>
                <p class="text-gray-300 font-medium" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Here's your fitness overview</p>
            </div>
            
            <!-- Date Selector -->
            <div class="flex items-center gap-3 bg-gray-800/50 backdrop-blur-sm rounded-lg p-3">
                <button id="dash-prev-day" class="text-white hover:text-emerald-400 transition-colors px-3 py-2 rounded-lg hover:bg-gray-700">
                    ◀
                </button>
                <input type="date" id="dash-date-selector" class="bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600 focus:border-emerald-500 focus:outline-none" value="<?= date('Y-m-d') ?>">
                <button id="dash-today" class="bg-emerald-500 text-gray-900 font-bold px-4 py-2 rounded-lg hover:bg-emerald-400 transition-colors">
                    Today
                </button>
                <button id="dash-next-day" class="text-white hover:text-emerald-400 transition-colors px-3 py-2 rounded-lg hover:bg-gray-700">
                    ▶
                </button>
            </div>
        </div>

        <!-- Selected Date Display -->
        <div class="mb-6 text-center" style="position: relative; z-index: 20;">
            <h2 class="text-2xl font-bold text-white" id="dash-selected-date-display" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Today's Overview</h2>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Net Calories -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Net Calories</span>
                    <span class="text-2xl">🔥</span>
                </div>
                <p class="text-3xl font-bold text-white" id="dash-net-calories"><?= $nutritionSummary ? round($nutritionSummary['net_calories']) : 0 ?></p>
                <p class="text-xs text-gray-500 mt-1">Goal: <span id="dash-goal-calories"><?= $nutritionGoal ? $nutritionGoal['daily_calories'] : 2000 ?></span> kcal</p>
            </div>

            <!-- Foods Logged -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Foods Logged</span>
                    <span class="text-2xl">🍽️</span>
                </div>
                <p class="text-3xl font-bold text-white" id="dash-food-count"><?= $foodCount ?></p>
                <p class="text-xs text-gray-500 mt-1">Consumed: <span id="dash-consumed"><?= $nutritionSummary ? round($nutritionSummary['total_calories_consumed']) : 0 ?></span> kcal</p>
            </div>

            <!-- Activities -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Activities</span>
                    <span class="text-2xl">💪</span>
                </div>
                <p class="text-3xl font-bold text-white" id="dash-activity-count"><?= $activityCount ?></p>
                <p class="text-xs text-gray-500 mt-1">Burned: <span id="dash-burned"><?= $nutritionSummary ? round($nutritionSummary['total_calories_burned']) : 0 ?></span> kcal</p>
            </div>

            <!-- Calories Burned -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Status</span>
                    <span class="text-2xl" id="dash-status-icon">⚖️</span>
                </div>
                <p class="text-3xl font-bold" id="dash-status-text"><?= $nutritionSummary ? ucfirst(str_replace('_', ' ', $nutritionSummary['status'])) : 'On Track' ?></p>
                <p class="text-xs text-gray-500 mt-1">Daily Progress</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Quick Actions -->
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white mb-4">⚡ Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <a href="index.php?page=nutrition_advanced" class="block w-full bg-emerald-500 text-gray-900 font-bold py-3 px-4 rounded-lg text-center cta-button">
                        🍎 Track Nutrition
                    </a>
                    <a href="index.php?page=schedule" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        📅 View Schedule
                    </a>
                    <a href="index.php?page=videos" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        🎥 Workout Videos
                    </a>
                    <a href="index.php?page=tour" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        🏋️ Gym Tour
                    </a>
                </div>
            </div>
        </div>

        <!-- Macros Overview -->
        <?php if ($nutritionSummary): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-white mb-4">🥩 Protein (Today)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-bold text-emerald-400"><?= round($nutritionSummary['total_protein_g']) ?>g</p>
                        <p class="text-sm text-gray-400">of <?= $nutritionGoal ? $nutritionGoal['protein_g'] : 0 ?>g</p>
                    </div>
                    <div class="w-24 h-24">
                        <canvas id="proteinChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-white mb-4">🍚 Carbs (Today)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-bold text-blue-400"><?= round($nutritionSummary['total_carbs_g']) ?>g</p>
                        <p class="text-sm text-gray-400">of <?= $nutritionGoal ? $nutritionGoal['carbs_g'] : 0 ?>g</p>
                    </div>
                    <div class="w-24 h-24">
                        <canvas id="carbsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-white mb-4">🥑 Fats (Today)</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-3xl font-bold text-yellow-400"><?= round($nutritionSummary['total_fat_g']) ?>g</p>
                        <p class="text-sm text-gray-400">of <?= $nutritionGoal ? $nutritionGoal['fat_g'] : 0 ?>g</p>
                    </div>
                    <div class="w-24 h-24">
                        <canvas id="fatChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Recent Foods -->
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white mb-4">🍽️ Recent Foods (Last 7 Days)</h2>
                <?php if (count($recentFoods) > 0): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($recentFoods as $food): ?>
                            <div class="bg-gray-700/50 rounded-lg p-3">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-white font-semibold text-sm"><?= htmlspecialchars($food['food_name']) ?></p>
                                    <span class="text-emerald-400 font-bold text-sm"><?= round($food['calories'] * $food['quantity']) ?> kcal</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-gray-400">
                                        P: <?= round($food['protein_g'] * $food['quantity']) ?>g · 
                                        C: <?= round($food['carbs_g'] * $food['quantity']) ?>g · 
                                        F: <?= round($food['fat_g'] * $food['quantity']) ?>g
                                    </p>
                                    <p class="text-xs text-gray-500"><?= date('M d', strtotime($food['log_date'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 text-center py-8">No food logs yet. Start tracking!</p>
                <?php endif; ?>
            </div>

            <!-- Recent Activities -->
            <div class="glass-card p-6">
                <h2 class="text-xl font-bold text-white mb-4">💪 Recent Activities (Last 7 Days)</h2>
                <?php if (count($recentActivities) > 0): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="bg-gray-700/50 rounded-lg p-3">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-white font-semibold text-sm"><?= htmlspecialchars($activity['activity_name']) ?></p>
                                    <span class="text-red-400 font-bold text-sm"><?= round($activity['calories_burned']) ?> kcal</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-gray-400">
                                        <?= $activity['duration_minutes'] > 0 ? $activity['duration_minutes'] . ' min' : 'Reps' ?>
                                    </p>
                                    <p class="text-xs text-gray-500"><?= date('M d', strtotime($activity['log_date'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 text-center py-8">No activities logged yet. Get moving!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Account Info -->
        <div class="glass-card p-6 mt-6">
            <h2 class="text-xl font-bold text-white mb-4">👤 Account Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-400">Username</p>
                    <p class="text-white font-semibold"><?= htmlspecialchars($user['username']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Email</p>
                    <p class="text-white font-semibold"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Member Since</p>
                    <p class="text-white font-semibold"><?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
<?php if ($nutritionSummary && $nutritionGoal): ?>
// Macro Doughnut Charts
function createMacroChart(id, current, goal, color) {
    const ctx = document.getElementById(id).getContext('2d');
    const percentage = Math.min((current / goal) * 100, 100);
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [current, Math.max(0, goal - current)],
                backgroundColor: [color, 'rgba(75, 85, 99, 0.3)'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
}

createMacroChart('proteinChart', <?= round($nutritionSummary['total_protein_g']) ?>, <?= $nutritionGoal['protein_g'] ?>, '#10B981');
createMacroChart('carbsChart', <?= round($nutritionSummary['total_carbs_g']) ?>, <?= $nutritionGoal['carbs_g'] ?>, '#3B82F6');
createMacroChart('fatChart', <?= round($nutritionSummary['total_fat_g']) ?>, <?= $nutritionGoal['fat_g'] ?>, '#FBBF24');
<?php endif; ?>
</script>

<script src="assets/js/dashboard_date_nav.js"></script>

<?php include 'partials/footer.php'; ?>
