document.addEventListener('DOMContentLoaded', function() {
    console.log('Nutrition Advanced JS loaded');
    const nuUser = window.nutritionUser;
    console.log('nutritionUser:', nuUser);
    
    if (!nuUser || !nuUser.loggedIn) {
        console.log('User not logged in, exiting');
        return;
    }
    
    console.log('User is logged in, initializing...');
    
    let currentGoal = null;
    let currentSummary = null;
    let calcResult = null;
    
    async function checkInitialization() {
        console.log('Checking initialization...');
        try {
            const res = await fetch('api/nutrition_api_v2.php?action=check_initialization', {credentials: 'same-origin'});
            console.log('Response status:', res.status);
            const text = await res.text();
            console.log('Response text:', text);
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            
            if (!data.initialized) {
                console.log('Not initialized, showing modal');
                document.getElementById('init-modal').style.display = 'flex';
            } else {
                console.log('Already initialized, loading dashboard');
                currentGoal = data.goal;
                document.getElementById('main-dashboard').style.display = 'block';
                loadDashboard();
            }
        } catch(err) {
            console.error('Initialization check failed:', err);
        }
    }
    
    document.getElementById('init-calculate-btn').addEventListener('click', function() {
        const age = parseFloat(document.getElementById('init-age').value);
        const gender = document.getElementById('init-gender').value;
        const weight = parseFloat(document.getElementById('init-weight').value);
        const height = parseFloat(document.getElementById('init-height').value);
        const activity = parseFloat(document.getElementById('init-activity').value);
        const goal = document.getElementById('init-goal').value;
        
        if (!age || !weight || !height) {
            showToast('Please fill all fields', 'error');
            return;
        }
        
        const bmr = Math.round((10 * weight) + (6.25 * height) - (5 * age) + (gender === 'male' ? 5 : -161));
        const tdee = Math.round(bmr * activity);
        let goalCalories = tdee;
        
        if (goal === 'lose') goalCalories = tdee - 400;
        else if (goal === 'gain') goalCalories = tdee + 400;
        
        const proteinGrams = Math.round(weight * (goal === 'lose' ? 2.2 : goal === 'gain' ? 2.0 : 1.8));
        const fatGrams = Math.round((goalCalories * 0.25) / 9);
        const carbGrams = Math.round((goalCalories - (proteinGrams * 4) - (fatGrams * 9)) / 4);
        
        calcResult = {
            calories: goalCalories,
            protein: proteinGrams,
            carbs: carbGrams,
            fat: fatGrams,
            goal_type: goal
        };
        
        document.getElementById('init-result-cal').textContent = goalCalories;
        document.getElementById('init-result-protein').textContent = proteinGrams + 'g';
        document.getElementById('init-result-carbs').textContent = carbGrams + 'g';
        document.getElementById('init-result-fat').textContent = fatGrams + 'g';
        document.getElementById('init-results').classList.remove('opacity-0');
        document.getElementById('init-save-btn').classList.remove('hidden');
    });
    
    document.getElementById('init-save-btn').addEventListener('click', async function() {
        if (!calcResult) return;
        
        const fd = new FormData();
        fd.append('action', 'initialize_goal');
        fd.append('calories', calcResult.calories);
        fd.append('protein', calcResult.protein);
        fd.append('carbs', calcResult.carbs);
        fd.append('fat', calcResult.fat);
        fd.append('goal_type', calcResult.goal_type);
        
        const res = await fetch('api/nutrition_api_v2.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        
        if (data.success) {
            document.getElementById('init-modal').style.display = 'none';
            document.getElementById('main-dashboard').style.display = 'block';
            currentGoal = calcResult;
            showToast('Goal saved! Start tracking your nutrition', 'success');
            loadDashboard();
        }
    });
    
    async function loadDashboard() {
        await Promise.all([
            loadTodaySummary(),
            loadFoodLog(),
            loadActivityLog()
        ]);
    }
    
    async function loadTodaySummary() {
        const res = await fetch('api/nutrition_api_v2.php?action=get_today_summary', {credentials: 'same-origin'});
        const data = await res.json();
        
        if (data.success) {
            currentSummary = data.summary;
            currentGoal = data.goal;
            updateDashboard();
        }
    }
    
    function updateDashboard() {
        if (!currentGoal) return;
        
        // Initialize empty summary if null
        if (!currentSummary) {
            currentSummary = {
                total_calories_consumed: 0,
                total_calories_burned: 0,
                net_calories: 0,
                total_protein_g: 0,
                total_carbs_g: 0,
                total_fat_g: 0,
                status: 'on_track'
            };
        }
        
        const consumed = parseFloat(currentSummary.total_calories_consumed || 0);
        const burned = parseFloat(currentSummary.total_calories_burned || 0);
        const net = parseFloat(currentSummary.net_calories || 0);
        const goal = parseInt(currentGoal.daily_calories || 2000);
        
        document.getElementById('net-calories').textContent = Math.round(net);
        document.getElementById('goal-calories').textContent = goal;
        document.getElementById('total-consumed-main').textContent = Math.round(consumed);
        document.getElementById('total-burned-main').textContent = Math.round(burned);
        
        const remaining = goal - net;
        document.getElementById('total-remaining-main').textContent = Math.abs(Math.round(remaining));
        
        if (remaining < 0) {
            document.getElementById('remaining-text').innerHTML = 'Over by: <span class="text-red-400 font-bold" id="total-remaining-main">' + Math.abs(Math.round(remaining)) + '</span> kcal';
        } else {
            document.getElementById('remaining-text').innerHTML = 'Remaining: <span class="text-white font-bold" id="total-remaining-main">' + Math.round(remaining) + '</span> kcal';
        }
        
        const pct = Math.min((net / goal) * 100, 100);
        document.getElementById('main-progress-bar').style.width = pct + '%';
        document.getElementById('main-progress-pct').textContent = Math.round(pct) + '%';
        
        if (pct >= 100) {
            document.getElementById('main-progress-bar').className = 'bg-red-500 h-4 rounded-full transition-all duration-500';
        } else if (pct >= 80) {
            document.getElementById('main-progress-bar').className = 'bg-yellow-500 h-4 rounded-full transition-all duration-500';
        } else {
            document.getElementById('main-progress-bar').className = 'bg-emerald-500 h-4 rounded-full transition-all duration-500';
        }
        
        const status = currentSummary.status || 'on_track';
        if (status === 'surplus') {
            document.getElementById('status-icon').textContent = '📈';
            document.getElementById('status-text').textContent = 'Surplus';
            document.getElementById('status-text').className = 'text-lg font-bold text-red-400';
        } else if (status === 'deficit') {
            document.getElementById('status-icon').textContent = '📉';
            document.getElementById('status-text').textContent = 'Deficit';
            document.getElementById('status-text').className = 'text-lg font-bold text-blue-400';
        } else {
            document.getElementById('status-icon').textContent = '⚖️';
            document.getElementById('status-text').textContent = 'On Track';
            document.getElementById('status-text').className = 'text-lg font-bold text-emerald-400';
        }
        
        document.getElementById('macro-protein').textContent = Math.round(currentSummary.total_protein_g || 0);
        document.getElementById('macro-carbs').textContent = Math.round(currentSummary.total_carbs_g || 0);
        document.getElementById('macro-fat').textContent = Math.round(currentSummary.total_fat_g || 0);
        
        document.getElementById('goal-protein').textContent = currentGoal.protein_g || 0;
        document.getElementById('goal-carbs').textContent = currentGoal.carbs_g || 0;
        document.getElementById('goal-fat').textContent = currentGoal.fat_g || 0;
    }
    
    // ═══════════════════════════════════════════════════════════════════
    // AI SMART LOG
    // ═══════════════════════════════════════════════════════════════════
    document.getElementById('ai-log-btn').addEventListener('click', async function() {
        const input = document.getElementById('casual-input').value.trim();
        const statusDiv = document.getElementById('ai-status');
        
        if (!input) {
            statusDiv.textContent = '⚠️ Please enter something to log';
            statusDiv.className = 'text-sm text-yellow-400 text-center min-h-6';
            return;
        }
        
        this.disabled = true;
        this.textContent = '🤖 AI Processing...';
        statusDiv.textContent = 'Analyzing your input with AI...';
        statusDiv.className = 'text-sm text-blue-400 text-center min-h-6';
        
        try {
            console.log('Calling Gemini API with input:', input);
            
            // Call local PHP endpoint
            const response = await fetch('api/ai_nutrition_parse.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                credentials: 'same-origin',
                body: JSON.stringify({ text: input })
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('API Error Response:', errorText);
                throw new Error(`API returned status ${response.status}: ${errorText}`);
            }
            
            const data = await response.json();
            console.log('API Response:', data);
            
            if (!data.success) {
                console.error('API error details:', JSON.stringify(data, null, 2));
                
                // Show user-friendly message if available
                if (data.user_message) {
                    statusDiv.textContent = data.user_message;
                    statusDiv.className = 'text-sm text-yellow-400 text-center min-h-6';
                    throw new Error(data.user_message);
                }
                
                const errorMsg = data.error || 'AI processing failed';
                const details = data.http_code ? ` (HTTP ${data.http_code})` : '';
                throw new Error(errorMsg + details);
            }
            
            const parsed = data.data;
            console.log('Parsed JSON:', parsed);
            
            // Check for error in parsed data (missing quantity)
            if (parsed && parsed.error) {
                statusDiv.textContent = '⚠️ ' + parsed.error;
                statusDiv.className = 'text-sm text-yellow-400 text-center min-h-6';
                this.disabled = false;
                this.textContent = '🚀 Log with AI';
                return;
            }
            
            statusDiv.textContent = '✓ AI parsed your input!';
            statusDiv.className = 'text-sm text-emerald-400 text-center min-h-6';
            
            // Process foods
            if (parsed.foods && parsed.foods.length > 0) {
                console.log('Processing foods:', parsed.foods);
                for (const food of parsed.foods) {
                    console.log('Searching for food:', food.name, 'quantity:', food.quantity);
                    await searchAndAddFood(
                        food.name, 
                        food.quantity || 1, 
                        food.est_calories,
                        food.est_protein,
                        food.est_carbs,
                        food.est_fat
                    );
                }
            }
            
            // Process activities
            if (parsed.activities && parsed.activities.length > 0) {
                console.log('Processing activities:', parsed.activities);
                for (const activity of parsed.activities) {
                    console.log('Logging activity:', activity.name, 'duration:', activity.duration, 'reps:', activity.reps);
                    await logPhysicalActivity(
                        activity.name, 
                        activity.duration || 0, 
                        activity.reps || 0,
                        activity.est_calories
                    );
                }
            }
            
            document.getElementById('casual-input').value = '';
            showToast('✓ Logged successfully!', 'success');
            
        } catch (error) {
            console.error('AI Log error:', error);
            statusDiv.textContent = '❌ AI processing failed. Please try again.';
            statusDiv.className = 'text-sm text-red-400 text-center min-h-6';
        } finally {
            this.disabled = false;
            this.textContent = '🚀 Log with AI';
        }
    });
    
    // Helper function to search and add food
    async function searchAndAddFood(foodName, quantity, estCalories, estProtein, estCarbs, estFat) {
        console.log('searchAndAddFood called with:', foodName, quantity, estCalories, estProtein, estCarbs, estFat);
        const fd = new FormData();
        fd.append('action', 'get_food_calories');
        fd.append('food_name', foodName);
        
        const res = await fetch('api/nutrition_api_v2.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        console.log('Food search result:', data);
        
        if (data.success && data.results.length > 0) {
            const food = data.results[0];
            console.log('Adding food from database:', food);
            await addFood({
                name: food.name,
                calories: food.calories,
                protein: food.protein,
                carbs: food.carbs,
                fat: food.fat,
                serving_size: food.serving_size,
                quantity: quantity
            });
        } else if (estCalories) {
            console.log('Using AI fallback - calories:', estCalories, 'protein:', estProtein, 'carbs:', estCarbs, 'fat:', estFat);
            await addFood({
                name: foodName,
                calories: estCalories,
                protein: estProtein || Math.round(estCalories * 0.25 / 4),
                carbs: estCarbs || Math.round(estCalories * 0.50 / 4),
                fat: estFat || Math.round(estCalories * 0.25 / 9),
                serving_size: quantity > 1 ? quantity + ' servings' : '1 serving',
                quantity: 1
            });
        } else {
            console.warn('No food found for:', foodName);
            showToast('⚠️ Could not find: ' + foodName, 'error');
        }
    }
    

    
    // Helper function to log activity (supports both time-based and rep-based)
    async function logPhysicalActivity(activityName, duration, reps, estCalories) {
        const fd = new FormData();
        fd.append('action', 'add_activity');
        fd.append('activity_name', activityName);
        fd.append('duration', duration || 0);
        fd.append('reps', reps || 0);
        fd.append('intensity', 'moderate');
        if (estCalories) fd.append('est_burn', estCalories);
        
        const res = await fetch('api/nutrition_api_v2.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        
        if (data.success) {
            loadDashboard();
        }
    }
    
    
    async function addFood(food) {
        const fd = new FormData();
        fd.append('action', 'add_food');
        fd.append('food_name', food.name);
        fd.append('calories', food.calories);
        fd.append('protein', food.protein);
        fd.append('carbs', food.carbs);
        fd.append('fat', food.fat);
        fd.append('serving_size', food.serving_size);
        fd.append('quantity', food.quantity || 1);
        fd.append('meal_type', 'snack');
        
        const res = await fetch('api/nutrition_api_v2.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        
        if (data.success) {
            loadDashboard();
        }
    }
    
    async function loadFoodLog() {
        const res = await fetch('api/nutrition_api_v2.php?action=get_food_log', {credentials: 'same-origin'});
        const data = await res.json();
        
        if (data.success) {
            const container = document.getElementById('food-log-list');
            if (data.foods.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No foods logged yet</p>';
            } else {
                container.innerHTML = data.foods.map(food => `
                    <div class="bg-gray-700/50 rounded-lg p-3 flex justify-between items-center">
                        <div>
                            <p class="text-white font-semibold text-sm">${food.food_name}</p>
                            <p class="text-xs text-gray-400">${Math.round(food.calories * food.quantity)} kcal · ${food.serving_size}</p>
                        </div>
                        <button class="text-red-400 hover:text-red-300 text-lg delete-food-btn" data-id="${food.id}">×</button>
                    </div>
                `).join('');
                
                document.querySelectorAll('.delete-food-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        deleteFood(this.dataset.id);
                    });
                });
            }
        }
    }
    
    async function deleteFood(id) {
        const fd = new FormData();
        fd.append('action', 'delete_food');
        fd.append('id', id);
        
        const res = await fetch('api/nutrition_api_v2.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        
        if (data.success) {
            showToast('Food removed', 'success');
            loadDashboard();
        }
    }
    
    
    async function loadActivityLog() {
        const res = await fetch('api/nutrition_api_v2.php?action=get_activity_log', {credentials: 'same-origin'});
        const data = await res.json();
        
        if (data.success) {
            const container = document.getElementById('activity-log-list');
            if (data.activities.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No activities logged yet</p>';
            } else {
                container.innerHTML = data.activities.map(activity => {
                    const durationText = activity.duration_minutes > 0 
                        ? `${activity.duration_minutes} min` 
                        : 'reps';
                    return `
                        <div class="bg-gray-700/50 rounded-lg p-3 flex justify-between items-center">
                            <div>
                                <p class="text-white font-semibold text-sm">${activity.activity_name}</p>
                                <p class="text-xs text-gray-400">${durationText} · ${Math.round(activity.calories_burned)} kcal burned</p>
                            </div>
                            <button class="text-red-400 hover:text-red-300 text-lg delete-activity-btn" data-id="${activity.id}">×</button>
                        </div>
                    `;
                }).join('');
                
                document.querySelectorAll('.delete-activity-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        deleteActivity(this.dataset.id);
                    });
                });
            }
        }
    }
    
    async function deleteActivity(id) {
        const fd = new FormData();
        fd.append('action', 'delete_activity');
        fd.append('id', id);
        
        const res = await fetch('api/nutrition_api_v2.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        
        if (data.success) {
            showToast('Activity removed', 'success');
            loadDashboard();
        }
    }
    
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');
        
        toastMsg.textContent = message;
        toast.className = 'fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ' + 
            (type === 'error' ? 'bg-red-500' : 'bg-emerald-500') + ' text-white';
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }
    
    // Export functions to window for date selector
    window.nutritionAdvanced = {
        updateDashboard: updateDashboard,
        showToast: showToast,
        currentGoal: currentGoal,
        currentSummary: currentSummary,
        deleteFood: deleteFood,
        deleteActivity: deleteActivity
    };
    
    checkInitialization();
});
