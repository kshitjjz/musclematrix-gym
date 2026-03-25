<?php
require '../config/db.php';

try {
    // Enhanced user nutrition goals table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_nutrition_goals (
            id SERIAL PRIMARY KEY,
            user_id INTEGER UNIQUE NOT NULL,
            daily_calories INTEGER NOT NULL DEFAULT 2000,
            protein_g INTEGER DEFAULT 0,
            carbs_g INTEGER DEFAULT 0,
            fat_g INTEGER DEFAULT 0,
            goal_type VARCHAR(20) DEFAULT 'maintain',
            is_initialized BOOLEAN DEFAULT FALSE,
            updated_at TIMESTAMP DEFAULT NOW(),
            created_at TIMESTAMP DEFAULT NOW()
        );
    ");

    // Enhanced food log with AI data
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_food_log (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            log_date DATE NOT NULL DEFAULT CURRENT_DATE,
            food_name VARCHAR(255) NOT NULL,
            calories NUMERIC(8,2) DEFAULT 0,
            protein_g NUMERIC(8,2) DEFAULT 0,
            carbs_g NUMERIC(8,2) DEFAULT 0,
            fat_g NUMERIC(8,2) DEFAULT 0,
            serving_size VARCHAR(50) DEFAULT '100g',
            quantity NUMERIC(8,2) DEFAULT 1,
            meal_type VARCHAR(20),
            source VARCHAR(50) DEFAULT 'manual',
            ai_suggestion TEXT,
            created_at TIMESTAMP DEFAULT NOW()
        );
    ");

    // Activity tracking table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_activity_log (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            log_date DATE NOT NULL DEFAULT CURRENT_DATE,
            activity_name VARCHAR(255) NOT NULL,
            duration_minutes INTEGER NOT NULL,
            calories_burned NUMERIC(8,2) DEFAULT 0,
            intensity VARCHAR(20) DEFAULT 'moderate',
            notes TEXT,
            created_at TIMESTAMP DEFAULT NOW()
        );
    ");

    // Daily summary table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_daily_summary (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            log_date DATE NOT NULL DEFAULT CURRENT_DATE,
            total_calories_consumed NUMERIC(8,2) DEFAULT 0,
            total_calories_burned NUMERIC(8,2) DEFAULT 0,
            net_calories NUMERIC(8,2) DEFAULT 0,
            total_protein_g NUMERIC(8,2) DEFAULT 0,
            total_carbs_g NUMERIC(8,2) DEFAULT 0,
            total_fat_g NUMERIC(8,2) DEFAULT 0,
            status VARCHAR(20) DEFAULT 'on_track',
            ai_insights TEXT,
            updated_at TIMESTAMP DEFAULT NOW(),
            UNIQUE(user_id, log_date)
        );
    ");

    // AI suggestions history
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS ai_suggestions (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            suggestion_type VARCHAR(50) NOT NULL,
            food_name VARCHAR(255),
            suggestion_text TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT NOW()
        );
    ");

    // Create indexes for performance
    $pdo->exec("
        CREATE INDEX IF NOT EXISTS idx_food_log_user_date ON user_food_log(user_id, log_date);
        CREATE INDEX IF NOT EXISTS idx_activity_log_user_date ON user_activity_log(user_id, log_date);
        CREATE INDEX IF NOT EXISTS idx_daily_summary_user_date ON user_daily_summary(user_id, log_date);
    ");

    echo "✅ Database schema created successfully!<br>";
    echo "✅ All tables and indexes are ready.<br>";
    echo "<br><a href='index.php'>Go to Dashboard</a>";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
