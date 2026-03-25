<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();
require '../config/db.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? (int)$_SESSION['user_id'] : null;

if (!$is_logged_in && $action !== 'get_food_calories') {
    echo json_encode(['error' => 'Login required']);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// INITIALIZATION CHECK
// ═══════════════════════════════════════════════════════════════════
if ($action === 'check_initialization') {
    $stmt = $pdo->prepare("SELECT is_initialized, daily_calories, protein_g, carbs_g, fat_g, goal_type FROM user_nutrition_goals WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $goal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'initialized' => $goal ? (bool)$goal['is_initialized'] : false,
        'goal' => $goal ?: null
    ]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// SAVE INITIAL CALORIE GOAL
// ═══════════════════════════════════════════════════════════════════
if ($action === 'initialize_goal') {
    $calories = (int)($_POST['calories'] ?? 2000);
    $protein = (int)($_POST['protein'] ?? 0);
    $carbs = (int)($_POST['carbs'] ?? 0);
    $fat = (int)($_POST['fat'] ?? 0);
    $goal_type = $_POST['goal_type'] ?? 'maintain';
    
    $stmt = $pdo->prepare("
        INSERT INTO user_nutrition_goals (user_id, daily_calories, protein_g, carbs_g, fat_g, goal_type, is_initialized, last_weight_update, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, TRUE, CURRENT_DATE, NOW())
        ON CONFLICT (user_id) DO UPDATE
        SET daily_calories = EXCLUDED.daily_calories,
            protein_g = EXCLUDED.protein_g,
            carbs_g = EXCLUDED.carbs_g,
            fat_g = EXCLUDED.fat_g,
            goal_type = EXCLUDED.goal_type,
            is_initialized = TRUE,
            last_weight_update = CURRENT_DATE,
            updated_at = NOW()
    ");
    $stmt->execute([$user_id, $calories, $protein, $carbs, $fat, $goal_type]);
    
    echo json_encode(['success' => true, 'message' => 'Goal initialized successfully']);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// SET DAILY GOAL (for specific date)
// ═══════════════════════════════════════════════════════════════════
if ($action === 'set_daily_goal') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $calories = (int)($_POST['calories'] ?? 2000);
    $protein = (int)($_POST['protein'] ?? 0);
    $carbs = (int)($_POST['carbs'] ?? 0);
    $fat = (int)($_POST['fat'] ?? 0);
    $notes = $_POST['notes'] ?? '';
    
    $stmt = $pdo->prepare("
        INSERT INTO user_daily_goals (user_id, goal_date, daily_calories, protein_g, carbs_g, fat_g, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON CONFLICT (user_id, goal_date) DO UPDATE
        SET daily_calories = EXCLUDED.daily_calories,
            protein_g = EXCLUDED.protein_g,
            carbs_g = EXCLUDED.carbs_g,
            fat_g = EXCLUDED.fat_g,
            notes = EXCLUDED.notes
    ");
    $stmt->execute([$user_id, $date, $calories, $protein, $carbs, $fat, $notes]);
    
    // Create or update summary for this date to ensure it exists
    updateDailySummary($pdo, $user_id, $date);
    
    echo json_encode(['success' => true, 'message' => 'Daily goal saved']);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// DELETE DAILY GOAL (revert to default)
// ═══════════════════════════════════════════════════════════════════
if ($action === 'delete_daily_goal') {
    $date = $_POST['date'] ?? date('Y-m-d');
    
    $stmt = $pdo->prepare("DELETE FROM user_daily_goals WHERE user_id = ? AND goal_date = ?");
    $stmt->execute([$user_id, $date]);
    
    echo json_encode(['success' => true, 'message' => 'Custom goal deleted, reverted to default']);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// CHECK IF GOAL UPDATE NEEDED (15 days check)
// ═══════════════════════════════════════════════════════════════════
if ($action === 'check_goal_update_needed') {
    $stmt = $pdo->prepare("SELECT last_weight_update FROM user_nutrition_goals WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $needsUpdate = false;
    $daysSinceUpdate = 0;
    
    if ($result && $result['last_weight_update']) {
        $lastUpdate = new DateTime($result['last_weight_update']);
        $today = new DateTime();
        $daysSinceUpdate = $today->diff($lastUpdate)->days;
        $needsUpdate = $daysSinceUpdate >= 15;
    }
    
    echo json_encode([
        'success' => true,
        'needs_update' => $needsUpdate,
        'days_since_update' => $daysSinceUpdate,
        'last_update' => $result['last_weight_update'] ?? null
    ]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// AI-POWERED FOOD CALORIE LOOKUP
// ═══════════════════════════════════════════════════════════════════
if ($action === 'get_food_calories') {
    $foodName = $_POST['food_name'] ?? '';
    
    if (empty($foodName)) {
        echo json_encode(['error' => 'Food name required']);
        exit;
    }
    
    // Try Open Food Facts API first
    $url = 'https://world.openfoodfacts.org/cgi/search.pl?search_terms=' . urlencode($foodName) . '&search_simple=1&action=process&json=1&page_size=5&fields=product_name,nutriments,brands,serving_size';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    $results = [];
    
    if (isset($data['products']) && count($data['products']) > 0) {
        foreach ($data['products'] as $product) {
            if (!isset($product['nutriments']['energy-kcal_100g'])) continue;
            
            $n = $product['nutriments'];
            $results[] = [
                'name' => $product['product_name'] ?? 'Unknown',
                'brand' => $product['brands'] ?? '',
                'calories' => round($n['energy-kcal_100g'] ?? 0, 1),
                'protein' => round($n['proteins_100g'] ?? 0, 1),
                'carbs' => round($n['carbohydrates_100g'] ?? 0, 1),
                'fat' => round($n['fat_100g'] ?? 0, 1),
                'serving_size' => $product['serving_size'] ?? '100g',
                'source' => 'openfoodfacts'
            ];
        }
    }
    
    // Fallback: Use predefined database
    if (empty($results)) {
        $commonFoods = [
            'banana' => ['calories' => 89, 'protein' => 1.1, 'carbs' => 23, 'fat' => 0.3],
            'apple' => ['calories' => 52, 'protein' => 0.3, 'carbs' => 14, 'fat' => 0.2],
            'chicken breast' => ['calories' => 165, 'protein' => 31, 'carbs' => 0, 'fat' => 3.6],
            'rice' => ['calories' => 130, 'protein' => 2.7, 'carbs' => 28, 'fat' => 0.3],
            'egg' => ['calories' => 155, 'protein' => 13, 'carbs' => 1.1, 'fat' => 11],
            'bread' => ['calories' => 265, 'protein' => 9, 'carbs' => 49, 'fat' => 3.2],
            'milk' => ['calories' => 42, 'protein' => 3.4, 'carbs' => 5, 'fat' => 1],
            'oats' => ['calories' => 389, 'protein' => 17, 'carbs' => 66, 'fat' => 7],
        ];
        
        $searchLower = strtolower($foodName);
        foreach ($commonFoods as $food => $nutrition) {
            if (strpos($searchLower, $food) !== false) {
                $results[] = array_merge([
                    'name' => ucfirst($food),
                    'brand' => '',
                    'serving_size' => '100g',
                    'source' => 'database'
                ], $nutrition);
            }
        }
    }
    
    echo json_encode(['success' => true, 'results' => $results]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// ADD FOOD TO LOG
// ═══════════════════════════════════════════════════════════════════
if ($action === 'add_food') {
    $foodName = $_POST['food_name'] ?? '';
    $calories = (float)($_POST['calories'] ?? 0);
    $protein = (float)($_POST['protein'] ?? 0);
    $carbs = (float)($_POST['carbs'] ?? 0);
    $fat = (float)($_POST['fat'] ?? 0);
    $servingSize = $_POST['serving_size'] ?? '100g';
    $quantity = (float)($_POST['quantity'] ?? 1);
    $mealType = $_POST['meal_type'] ?? 'snack';
    $logDate = $_POST['log_date'] ?? date('Y-m-d');
    
    $stmt = $pdo->prepare("
        INSERT INTO user_food_log (user_id, log_date, food_name, calories, protein_g, carbs_g, fat_g, serving_size, quantity, meal_type, source)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'api')
    ");
    $stmt->execute([$user_id, $logDate, $foodName, $calories, $protein, $carbs, $fat, $servingSize, $quantity, $mealType]);
    
    $foodId = $pdo->lastInsertId();
    
    // Update daily summary for the log date
    updateDailySummary($pdo, $user_id, $logDate);
    
    echo json_encode(['success' => true, 'id' => $foodId]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// ADD ACTIVITY
// ═══════════════════════════════════════════════════════════════════
if ($action === 'add_activity') {
    $activityName = $_POST['activity_name'] ?? '';
    $duration = (int)($_POST['duration'] ?? 0);
    $reps = (int)($_POST['reps'] ?? 0);
    $intensity = $_POST['intensity'] ?? 'moderate';
    $estBurn = (float)($_POST['est_burn'] ?? 0);
    $logDate = $_POST['log_date'] ?? date('Y-m-d');
    
    // If AI provided estimate, use it
    if ($estBurn > 0) {
        $caloriesBurned = $estBurn;
    }
    // Rep-based activities
    elseif ($reps > 0) {
        $repCalories = [
            'pushup' => 0.5,
            'situp' => 0.5,
            'squat' => 0.5,
            'pullup' => 1.0,
            'burpee' => 1.5,
            'jumping jack' => 0.3,
        ];
        
        $activityLower = strtolower($activityName);
        $calPerRep = 0.5; // default
        
        foreach ($repCalories as $activity => $cal) {
            if (strpos($activityLower, $activity) !== false) {
                $calPerRep = $cal;
                break;
            }
        }
        
        $caloriesBurned = round($reps * $calPerRep, 1);
        $duration = 0; // No duration for rep-based
    }
    // Time-based activities
    else {
        $metValues = [
            'walking' => ['light' => 2.5, 'moderate' => 3.5, 'vigorous' => 4.5],
            'running' => ['light' => 6, 'moderate' => 8, 'vigorous' => 11],
            'cycling' => ['light' => 4, 'moderate' => 6, 'vigorous' => 10],
            'swimming' => ['light' => 5, 'moderate' => 7, 'vigorous' => 10],
            'gym' => ['light' => 3, 'moderate' => 5, 'vigorous' => 8],
            'yoga' => ['light' => 2, 'moderate' => 3, 'vigorous' => 4],
            'hiit' => ['light' => 6, 'moderate' => 8, 'vigorous' => 12],
        ];
        
        $activityLower = strtolower($activityName);
        $met = 5; // default
        
        foreach ($metValues as $activity => $intensities) {
            if (strpos($activityLower, $activity) !== false) {
                $met = $intensities[$intensity] ?? $intensities['moderate'];
                break;
            }
        }
        
        // Assume average weight of 70kg
        $caloriesBurned = round($met * 70 * ($duration / 60), 1);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO user_activity_log (user_id, log_date, activity_name, duration_minutes, calories_burned, intensity)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $logDate, $activityName, $duration, $caloriesBurned, $intensity]);
    
    $activityId = $pdo->lastInsertId();
    
    // Update daily summary for the log date
    updateDailySummary($pdo, $user_id, $logDate);
    
    echo json_encode(['success' => true, 'id' => $activityId, 'calories_burned' => $caloriesBurned]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// GET SUMMARY BY DATE
// ═══════════════════════════════════════════════════════════════════
if ($action === 'get_summary_by_date') {
    $date = $_GET['date'] ?? date('Y-m-d');
    
    $stmt = $pdo->prepare("SELECT * FROM user_daily_summary WHERE user_id = ? AND log_date = ?");
    $stmt->execute([$user_id, $date]);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check for daily goal first, fallback to default goal
    $stmt = $pdo->prepare("SELECT * FROM user_daily_goals WHERE user_id = ? AND goal_date = ?");
    $stmt->execute([$user_id, $date]);
    $dailyGoal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$dailyGoal) {
        // Use default goal
        $stmt = $pdo->prepare("SELECT * FROM user_nutrition_goals WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $goal = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Convert daily goal to same format
        $goal = [
            'daily_calories' => $dailyGoal['daily_calories'],
            'protein_g' => $dailyGoal['protein_g'],
            'carbs_g' => $dailyGoal['carbs_g'],
            'fat_g' => $dailyGoal['fat_g'],
            'is_custom' => true,
            'notes' => $dailyGoal['notes'] ?? ''
        ];
    }
    
    // Get food log for this date
    $stmt = $pdo->prepare("SELECT * FROM user_food_log WHERE user_id = ? AND log_date = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id, $date]);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get activity log for this date
    $stmt = $pdo->prepare("SELECT * FROM user_activity_log WHERE user_id = ? AND log_date = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id, $date]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true, 
        'summary' => $summary, 
        'goal' => $goal,
        'foods' => $foods,
        'activities' => $activities,
        'date' => $date
    ]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// GET TODAY'S SUMMARY
// ═══════════════════════════════════════════════════════════════════
if ($action === 'get_today_summary') {
    $stmt = $pdo->prepare("SELECT * FROM user_daily_summary WHERE user_id = ? AND log_date = CURRENT_DATE");
    $stmt->execute([$user_id]);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$summary) {
        updateDailySummary($pdo, $user_id);
        $stmt->execute([$user_id]);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Get goal
    $stmt = $pdo->prepare("SELECT * FROM user_nutrition_goals WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $goal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'summary' => $summary, 'goal' => $goal]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// GET TODAY'S FOOD LOG
// ═══════════════════════════════════════════════════════════════════
if ($action === 'get_food_log') {
    $stmt = $pdo->prepare("
        SELECT * FROM user_food_log 
        WHERE user_id = ? AND log_date = CURRENT_DATE 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user_id]);
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'foods' => $foods]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// GET TODAY'S ACTIVITY LOG
// ═══════════════════════════════════════════════════════════════════
if ($action === 'get_activity_log') {
    $stmt = $pdo->prepare("
        SELECT * FROM user_activity_log 
        WHERE user_id = ? AND log_date = CURRENT_DATE 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user_id]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'activities' => $activities]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// DELETE FOOD
// ═══════════════════════════════════════════════════════════════════
if ($action === 'delete_food') {
    $foodId = (int)($_POST['id'] ?? 0);
    
    // Get the log_date before deleting
    $stmt = $pdo->prepare("SELECT log_date FROM user_food_log WHERE id = ? AND user_id = ?");
    $stmt->execute([$foodId, $user_id]);
    $food = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($food) {
        $stmt = $pdo->prepare("DELETE FROM user_food_log WHERE id = ? AND user_id = ?");
        $stmt->execute([$foodId, $user_id]);
        
        updateDailySummary($pdo, $user_id, $food['log_date']);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// DELETE ACTIVITY
// ═══════════════════════════════════════════════════════════════════
if ($action === 'delete_activity') {
    $activityId = (int)($_POST['id'] ?? 0);
    
    // Get the log_date before deleting
    $stmt = $pdo->prepare("SELECT log_date FROM user_activity_log WHERE id = ? AND user_id = ?");
    $stmt->execute([$activityId, $user_id]);
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($activity) {
        $stmt = $pdo->prepare("DELETE FROM user_activity_log WHERE id = ? AND user_id = ?");
        $stmt->execute([$activityId, $user_id]);
        
        updateDailySummary($pdo, $user_id, $activity['log_date']);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

// ═══════════════════════════════════════════════════════════════════
// HELPER FUNCTION: UPDATE DAILY SUMMARY
// ═══════════════════════════════════════════════════════════════════
function updateDailySummary($pdo, $user_id, $log_date = null) {
    if ($log_date === null) {
        $log_date = date('Y-m-d');
    }
    
    // Calculate food totals for specific date
    $stmt = $pdo->prepare("
        SELECT 
            COALESCE(SUM(calories * quantity), 0) as total_calories,
            COALESCE(SUM(protein_g * quantity), 0) as total_protein,
            COALESCE(SUM(carbs_g * quantity), 0) as total_carbs,
            COALESCE(SUM(fat_g * quantity), 0) as total_fat
        FROM user_food_log
        WHERE user_id = ? AND log_date = ?
    ");
    $stmt->execute([$user_id, $log_date]);
    $foodTotals = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calculate activity totals for specific date
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(calories_burned), 0) as total_burned
        FROM user_activity_log
        WHERE user_id = ? AND log_date = ?
    ");
    $stmt->execute([$user_id, $log_date]);
    $activityTotals = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $netCalories = $foodTotals['total_calories'] - $activityTotals['total_burned'];
    
    // Get goal for this specific date (check daily goal first, then default)
    $stmt = $pdo->prepare("SELECT daily_calories FROM user_daily_goals WHERE user_id = ? AND goal_date = ?");
    $stmt->execute([$user_id, $log_date]);
    $dailyGoal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$dailyGoal) {
        // Use default goal
        $stmt = $pdo->prepare("SELECT daily_calories FROM user_nutrition_goals WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $goal = $stmt->fetch(PDO::FETCH_ASSOC);
        $goalCalories = $goal['daily_calories'] ?? 2000;
    } else {
        $goalCalories = $dailyGoal['daily_calories'];
    }
    
    $status = 'on_track';
    if ($netCalories > $goalCalories + 200) $status = 'surplus';
    elseif ($netCalories < $goalCalories - 200) $status = 'deficit';
    
    // Update or insert summary for specific date
    $stmt = $pdo->prepare("
        INSERT INTO user_daily_summary (user_id, log_date, total_calories_consumed, total_calories_burned, net_calories, total_protein_g, total_carbs_g, total_fat_g, status, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ON CONFLICT (user_id, log_date) DO UPDATE
        SET total_calories_consumed = EXCLUDED.total_calories_consumed,
            total_calories_burned = EXCLUDED.total_calories_burned,
            net_calories = EXCLUDED.net_calories,
            total_protein_g = EXCLUDED.total_protein_g,
            total_carbs_g = EXCLUDED.total_carbs_g,
            total_fat_g = EXCLUDED.total_fat_g,
            status = EXCLUDED.status,
            updated_at = NOW()
    ");
    $stmt->execute([
        $user_id,
        $log_date,
        $foodTotals['total_calories'],
        $activityTotals['total_burned'],
        $netCalories,
        $foodTotals['total_protein'],
        $foodTotals['total_carbs'],
        $foodTotals['total_fat'],
        $status
    ]);
}

echo json_encode(['error' => 'Unknown action']);
?>
