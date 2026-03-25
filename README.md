# PowerPulse Fitness - Gym Management System

## Project Structure

```
GYM!/
├── api/                          # API Endpoints
│   ├── add_workout.php          # Workout logging API
│   ├── ai_nutrition_parse.php   # AI-powered nutrition parser (Gemini API)
│   └── nutrition_api_v2.php     # Nutrition tracking API
│
├── assets/                       # Static Assets
│   ├── css/
│   │   └── style.css            # Glassmorphism theme styles
│   └── js/
│       ├── main.js              # Global JavaScript (modals, navigation)
│       └── nutrition_advanced.js # Nutrition AI page logic
│
├── auth/                         # Authentication
│   ├── login.php                # Login handler
│   ├── logout.php               # Logout handler
│   ├── register.php             # Registration handler
│   ├── login_form.php           # Login form (unused)
│   └── register_form.php        # Register form (unused)
│
├── config/                       # Configuration
│   └── db.php                   # PostgreSQL database connection
│
├── database/                     # Database Scripts
│   ├── setup_nutrition_db.php   # Create nutrition tables
│   └── reset_nutrition_db.php   # Reset nutrition data
│
├── gym-website-backend/          # Backend Services
│   ├── src/services/
│   │   └── AIService.php        # Gemini AI integration
│   └── .env                     # Environment variables (API keys)
│
├── pages/                        # Page Content
│   ├── home.php                 # Homepage
│   ├── schedule.php             # Class schedule
│   ├── tour.php                 # Gym tour
│   ├── nutrition_advanced.php   # AI Nutrition Tracker
│   └── videos.php               # Workout videos
│
├── partials/                     # Reusable Components
│   ├── header.php               # Navigation header
│   └── footer.php               # Footer with modals
│
├── dashboard.php                 # User dashboard
├── index.php                     # Main entry point
└── package.json                  # NPM dependencies

```

## Features

### 1. AI-Powered Nutrition Tracking
- **Natural Language Input**: "I ate 2 eggs and ran for 20 mins"
- **Global Food Recognition**: Supports all cuisines (Indian, Chinese, Italian, etc.)
- **Activity Tracking**: Time-based (running 30min) and rep-based (30 pushups)
- **Calorie Calculator**: BMR/TDEE with macro breakdown
- **Real-time Dashboard**: Net calories, macros, progress bars

### 2. Authentication System
- Session-based login with "Remember Me" option
- PostgreSQL user storage with password hashing
- Modal-based login/register UI

### 3. Class Schedule
- Weekly class calendar with filters
- Instructor profiles and class details
- Booking system with spot availability

### 4. Gym Tour
- Interactive facility showcase
- Image gallery with thumbnails

### 5. Workout Videos
- Filterable video library
- Watchlist feature
- Trainer and muscle group filters

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    level INT DEFAULT 1,
    xp INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT NOW()
);
```

### Nutrition Tables
- `user_nutrition_goals`: Daily calorie/macro targets
- `user_food_log`: Food entries with macros
- `user_activity_log`: Exercise entries with calories burned
- `user_daily_summary`: Aggregated daily stats

## API Endpoints

### Nutrition API (`api/nutrition_api_v2.php`)
- `check_initialization`: Check if user has set goals
- `initialize_goal`: Save daily calorie/macro targets
- `get_food_calories`: Search Open Food Facts API
- `add_food`: Log food entry
- `add_activity`: Log activity (time or rep-based)
- `get_today_summary`: Get daily totals
- `get_food_log`: Get today's food entries
- `get_activity_log`: Get today's activities
- `delete_food`: Remove food entry
- `delete_activity`: Remove activity entry

### AI Nutrition Parser (`api/ai_nutrition_parse.php`)
- Uses Google Gemini API for natural language processing
- Recognizes foods with quantities and activities with duration/reps
- Returns structured JSON with calories and macros

## Configuration

### Database Connection (`config/db.php`)
```php
$host = 'localhost';
$dbname = 'gym_app';
$user = 'postgres';
$password = 'your_password';
```

### Environment Variables (`gym-website-backend/.env`)
```
GEMINI_API_KEY=your_api_key_here
```

## Tech Stack

- **Frontend**: HTML, Tailwind CSS, Vanilla JavaScript
- **Backend**: PHP 7.4+
- **Database**: PostgreSQL 12+
- **AI**: Google Gemini API
- **External APIs**: Open Food Facts
- **Charts**: Chart.js

## Design Theme

**Liquid Glass / Glassmorphism**
- Backdrop blur effects
- Transparent cards with rgba backgrounds
- Emerald-500 accent color (#10B981)
- Dark gray-900 base (#111827)

## Installation

1. Import database schema from `database/setup_nutrition_db.php`
2. Configure database credentials in `config/db.php`
3. Add Gemini API key to `gym-website-backend/.env`
4. Place project in `c:\xampp\htdocs\GYM!`
5. Access via `http://localhost/GYM!/`

## Key Files to Modify

- **Styling**: `assets/css/style.css`
- **Navigation**: `partials/header.php`
- **Database**: `config/db.php`
- **AI Prompts**: `gym-website-backend/src/services/AIService.php`
- **API Logic**: `api/nutrition_api_v2.php`

## Notes

- All API calls use `credentials: 'same-origin'` for session management
- Gemini API has multiple model fallback for quota limits
- Food search uses Open Food Facts API with local fallback database
- Activity calories calculated using MET values (time-based) or per-rep values (rep-based)
