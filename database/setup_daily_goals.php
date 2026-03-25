<?php
require '../config/db.php';

try {
    // Add daily goals table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_daily_goals (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            goal_date DATE NOT NULL,
            daily_calories INTEGER NOT NULL,
            protein_g INTEGER DEFAULT 0,
            carbs_g INTEGER DEFAULT 0,
            fat_g INTEGER DEFAULT 0,
            notes TEXT,
            created_at TIMESTAMP DEFAULT NOW(),
            UNIQUE(user_id, goal_date)
        );
    ");
    
    // Add last_weight_update column to user_nutrition_goals
    $pdo->exec("
        ALTER TABLE user_nutrition_goals 
        ADD COLUMN IF NOT EXISTS last_weight_update DATE DEFAULT CURRENT_DATE;
    ");
    
    // Create index
    $pdo->exec("
        CREATE INDEX IF NOT EXISTS idx_daily_goals_user_date ON user_daily_goals(user_id, goal_date);
    ");
    
    echo "✅ Daily goals table created successfully!<br>";
    echo "✅ last_weight_update column added to user_nutrition_goals<br>";
    echo "✅ Index created for performance<br>";
    echo "<br><a href='../index.php?page=nutrition_advanced'>Go to Nutrition Tracker</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
