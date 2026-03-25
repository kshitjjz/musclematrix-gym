<?php
$is_logged_in = isset($_SESSION['user_id']);
$session_user_id = $is_logged_in ? (int)$_SESSION['user_id'] : 0;
$session_username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';
?>
<!-- PAGE: Advanced Nutrition Corner -->
<div id="page-nutrition-advanced">
    <script>
        window.nutritionUser = {
            loggedIn: <?= $is_logged_in ? 'true' : 'false' ?>,
            userId: <?= $session_user_id ?>,
            username: "<?= $session_username ?>"
        };
    </script>

    <section class="py-20 bg-gray-900">
        <div class="container mx-auto px-6">

            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-2" style="position: relative; z-index: 20; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">AI-Powered <span class="text-red-500">Nutrition Tracker</span></h2>
                <p class="text-gray-300 font-medium" style="position: relative; z-index: 20; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Smart calorie tracking with activity monitoring</p>
            </div>

            <?php if (!$is_logged_in): ?>
            <!-- Not Logged In -->
            <div class="glass-card rounded-lg p-12 text-center max-w-2xl mx-auto">
                <div class="text-5xl mb-4">🔒</div>
                <h3 class="text-xl font-bold text-white mb-2">Login Required</h3>
                <p class="text-gray-400 mb-6">Track your nutrition and activities with AI-powered insights</p>
                <button class="open-login-modal bg-emerald-500 text-gray-900 font-bold py-3 px-8 rounded-lg cta-button">Login / Register</button>
            </div>
            <?php else: ?>

            <!-- Initialization Check Modal -->
            <div id="init-modal" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center p-4">
                <div class="modal-glass w-full max-w-2xl p-8 rounded-lg">
                    <h2 class="text-3xl font-bold text-white text-center mb-4">Welcome! Let's Set Your Goals</h2>
                    <p class="text-gray-400 text-center mb-8">Calculate your daily calorie target to get started</p>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-400">Age</label>
                                    <input type="number" id="init-age" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white" value="25">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Gender</label>
                                    <select id="init-gender" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Weight (kg)</label>
                                <input type="number" id="init-weight" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white" value="70">
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Height (cm)</label>
                                <input type="number" id="init-height" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white" value="175">
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Activity Level</label>
                                <select id="init-activity" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white">
                                    <option value="1.2">Sedentary</option>
                                    <option value="1.375">Lightly Active</option>
                                    <option value="1.55" selected>Moderately Active</option>
                                    <option value="1.725">Very Active</option>
                                    <option value="1.9">Extra Active</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm text-gray-400">Goal</label>
                                <select id="init-goal" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white">
                                    <option value="lose">Weight Loss</option>
                                    <option value="maintain" selected>Maintain</option>
                                    <option value="gain">Muscle Gain</option>
                                </select>
                            </div>
                            <button id="init-calculate-btn" class="w-full bg-emerald-500 text-gray-900 font-bold py-3 rounded-lg cta-button">Calculate & Save</button>
                        </div>
                        
                        <div id="init-results" class="bg-gray-700/50 p-6 rounded-lg flex flex-col justify-center items-center text-center opacity-0 transition-opacity">
                            <h4 class="font-bold text-white text-xl mb-4">Your Daily Target</h4>
                                    <p class="text-4xl font-bold text-red-400 mb-2" id="init-result-cal">—</p>
                            <p class="text-sm text-gray-400 mb-4">calories/day</p>
                            <div class="grid grid-cols-3 gap-3 w-full">
                                <div class="bg-gray-800 p-3 rounded">
                                    <p class="text-xs text-gray-400">Protein</p>
                                    <p class="font-bold text-white" id="init-result-protein">—</p>
                                </div>
                                <div class="bg-gray-800 p-3 rounded">
                                    <p class="text-xs text-gray-400">Carbs</p>
                                    <p class="font-bold text-white" id="init-result-carbs">—</p>
                                </div>
                                <div class="bg-gray-800 p-3 rounded">
                                    <p class="text-xs text-gray-400">Fat</p>
                                    <p class="font-bold text-white" id="init-result-fat">—</p>
                                </div>
                            </div>
                            <button id="init-save-btn" class="w-full mt-4 bg-emerald-600 text-white font-bold py-2 rounded-lg cta-button hidden">Start Tracking</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard -->
            <div id="main-dashboard" class="hidden">
                
                <!-- Date Selector -->
                <div class="glass-card rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <button id="prev-day-btn" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                                ← Previous
                            </button>
                            <input type="date" id="date-selector" class="bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-600" value="<?= date('Y-m-d') ?>">
                            <button id="next-day-btn" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                                Next →
                            </button>
                            <button id="today-btn" class="bg-emerald-500 hover:bg-emerald-600 text-gray-900 font-bold px-4 py-2 rounded-lg cta-button">
                                Today
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <button id="set-daily-goal-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                                ⚙️ Set Goal for This Day
                            </button>
                            <div class="text-white font-semibold">
                                <span id="selected-date-display"><?= date('l, F j, Y') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Goal Update Reminder Banner -->
                <div id="goal-update-reminder" class="hidden glass-card rounded-lg p-4 mb-6 bg-yellow-500/20 border border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">⚠️</span>
                            <div>
                                <p class="text-white font-bold">Time to Update Your Goals!</p>
                                <p class="text-gray-300 text-sm">It's been <span id="days-since-update">15</span> days since your last update. Recalculate based on your progress.</p>
                            </div>
                        </div>
                        <button id="update-goals-btn" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold px-6 py-2 rounded-lg">
                            Update Goals
                        </button>
                    </div>
                </div>
                
                <!-- Status Banner -->
                <div id="status-banner" class="glass-card rounded-lg p-6 mb-6 text-center">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-center gap-4 flex-wrap">
                                <div>
                                    <p class="text-sm text-gray-400">Net Calories</p>
                                    <p class="text-3xl font-bold text-white" id="net-calories">0</p>
                                </div>
                                <div class="text-4xl" id="status-icon">⚖️</div>
                                <div>
                                    <p class="text-sm text-gray-400">Status</p>
                                    <p class="text-lg font-bold" id="status-text">On Track</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Goal <span id="goal-badge" class="text-xs"></span></p>
                                    <p class="text-lg font-bold text-red-400" id="goal-calories">2000</p>
                                </div>
                            </div>
                        </div>
                        <button id="edit-goal-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            ✏️ Edit Goal
                        </button>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-4 w-full bg-gray-700 rounded-full h-4 relative overflow-hidden">
                        <div id="main-progress-bar" class="bg-emerald-500 h-4 rounded-full transition-all duration-500" style="width:0%"></div>
                        <span id="main-progress-pct" class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">0%</span>
                    </div>
                    
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-400">Consumed: <span class="text-red-400 font-bold" id="total-consumed-main">0</span> kcal</span>
                        <span class="text-gray-400">Burned: <span class="text-yellow-400 font-bold" id="total-burned-main">0</span> kcal</span>
                        <span class="text-gray-400" id="remaining-text">Remaining: <span class="text-white font-bold" id="total-remaining-main">2000</span> kcal</span>
                    </div>
                </div>

                <!-- Smart Log (AI-Powered) -->
                <div class="glass-card rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-white mb-4 text-center">🤖 AI Smart Log</h3>
                    
                    <input type="text" id="casual-input" class="w-full bg-gray-700 p-4 rounded-lg border border-gray-600 text-white placeholder-gray-400 mb-4" placeholder="I ate 2 eggs and ran for 20 mins">
                    
                    <button id="ai-log-btn" class="w-full bg-gradient-to-r from-emerald-500 to-blue-500 text-white font-bold py-3 rounded-lg cta-button mb-3">🚀 Log with AI</button>
                    
                    <div id="ai-status" class="text-sm text-gray-400 text-center min-h-6"></div>
                </div>

                <!-- Food & Activity Logs -->
                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Food Log -->
                    <div class="glass-card rounded-lg p-6">
                        <h4 class="text-lg font-bold text-white mb-3">🍽️ <span id="food-log-title">Today's</span> Food Log</h4>
                        <div id="food-log-list" class="space-y-2">
                            <p class="text-gray-500 text-sm text-center py-4">No foods logged yet</p>
                        </div>
                    </div>
                    
                    <!-- Activity Log -->
                    <div class="glass-card rounded-lg p-6">
                        <h4 class="text-lg font-bold text-white mb-3">🏃 <span id="activity-log-title">Today's</span> Activities</h4>
                        <div id="activity-log-list" class="space-y-2">
                            <p class="text-gray-500 text-sm text-center py-4">No activities logged yet</p>
                        </div>
                    </div>
                </div>

                <!-- Macros Breakdown -->
                <div class="glass-card rounded-lg p-6 mt-6">
                    <h3 class="text-xl font-bold text-white mb-4">📊 <span id="macros-title">Today's</span> Macros</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-400">Protein</p>
                            <p class="text-2xl font-bold text-red-400"><span id="macro-protein">0</span>g</p>
                            <p class="text-xs text-gray-500">/ <span id="goal-protein">0</span>g</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-400">Carbs</p>
                            <p class="text-2xl font-bold text-blue-400"><span id="macro-carbs">0</span>g</p>
                            <p class="text-xs text-gray-500">/ <span id="goal-carbs">0</span>g</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-400">Fat</p>
                            <p class="text-2xl font-bold text-yellow-400"><span id="macro-fat">0</span>g</p>
                            <p class="text-xs text-gray-500">/ <span id="goal-fat">0</span>g</p>
                        </div>
                    </div>
                </div>

            </div>

            <?php endif; ?>

        </div>
    </section>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg hidden transform transition-all duration-300">
        <p id="toast-message"></p>
    </div>

    <!-- Set Daily Goal Modal -->
    <div id="daily-goal-modal" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center p-4">
        <div class="modal-glass w-full max-w-md p-8 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-white">⚙️ <span id="modal-action-text">Set</span> Goal</h2>
                <button id="delete-daily-goal-btn" class="hidden text-red-400 hover:text-red-300 text-sm font-semibold">
                    🗑️ Delete Custom Goal
                </button>
            </div>
            <p class="text-gray-400 text-center mb-2 text-sm">For <span id="modal-date-display" class="font-bold"></span></p>
            <p class="text-gray-500 text-center mb-6 text-xs">Customize calories for workout days, rest days, etc.</p>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-400">Calories</label>
                    <input type="number" id="daily-goal-calories" class="w-full bg-gray-700 p-3 rounded mt-1 border border-gray-600 text-white" placeholder="2000">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm text-gray-400">Protein (g)</label>
                        <input type="number" id="daily-goal-protein" class="w-full bg-gray-700 p-3 rounded mt-1 border border-gray-600 text-white" placeholder="150">
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Carbs (g)</label>
                        <input type="number" id="daily-goal-carbs" class="w-full bg-gray-700 p-3 rounded mt-1 border border-gray-600 text-white" placeholder="200">
                    </div>
                    <div>
                        <label class="text-sm text-gray-400">Fat (g)</label>
                        <input type="number" id="daily-goal-fat" class="w-full bg-gray-700 p-3 rounded mt-1 border border-gray-600 text-white" placeholder="60">
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-400">Notes (optional)</label>
                    <input type="text" id="daily-goal-notes" class="w-full bg-gray-700 p-3 rounded mt-1 border border-gray-600 text-white" placeholder="e.g., Leg day, Rest day">
                </div>
                <div class="flex gap-3">
                    <button id="save-daily-goal-btn" class="flex-1 bg-emerald-500 text-gray-900 font-bold py-3 rounded-lg cta-button">
                        Save Goal
                    </button>
                    <button id="cancel-daily-goal-btn" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 rounded-lg">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/nutrition_advanced.js?v=<?= time() ?>"></script>
    <script src="assets/js/nutrition_date_selector.js?v=<?= time() ?>"></script>
</div>
