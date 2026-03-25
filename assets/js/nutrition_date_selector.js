// Date Selector Functionality
let currentDate = new Date().toISOString().split('T')[0];
let currentGoal = null;
let currentSummary = null;

// Helper function for toast notifications
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-message');
    
    if (toast && toastMsg) {
        toastMsg.textContent = message;
        toast.className = 'fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ' + 
            (type === 'error' ? 'bg-red-500' : 'bg-emerald-500') + ' text-white';
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }
}

// Helper function to update dashboard
function updateDashboard() {
    if (typeof window.updateDashboard === 'function') {
        window.updateDashboard();
    } else if (window.nutritionAdvanced && typeof window.nutritionAdvanced.updateDashboard === 'function') {
        window.nutritionAdvanced.updateDashboard();
    }
}

// Check if goal update is needed (15 days)
async function checkGoalUpdateNeeded() {
    try {
        const res = await fetch('api/nutrition_api_v2.php?action=check_goal_update_needed', {
            credentials: 'same-origin'
        });
        const data = await res.json();
        
        if (data.success && data.needs_update) {
            document.getElementById('days-since-update').textContent = data.days_since_update;
            document.getElementById('goal-update-reminder').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error checking goal update:', error);
    }
}

// Update goals button
document.getElementById('update-goals-btn')?.addEventListener('click', function() {
    document.getElementById('goal-update-reminder').classList.add('hidden');
    document.getElementById('init-modal').style.display = 'flex';
});

// Set daily goal button
document.getElementById('set-daily-goal-btn')?.addEventListener('click', function() {
    openDailyGoalModal(false);
});

// Edit goal button (in status banner)
document.getElementById('edit-goal-btn')?.addEventListener('click', function() {
    openDailyGoalModal(true);
});

function openDailyGoalModal(isEdit) {
    const dateObj = new Date(currentDate + 'T00:00:00');
    const dateDisplay = dateObj.toLocaleDateString('en-US', { 
        weekday: 'long', 
        month: 'long', 
        day: 'numeric',
        year: 'numeric'
    });
    document.getElementById('modal-date-display').textContent = dateDisplay;
    document.getElementById('modal-action-text').textContent = isEdit ? 'Edit' : 'Set';
    
    // Pre-fill with current goal if available
    if (currentGoal) {
        document.getElementById('daily-goal-calories').value = currentGoal.daily_calories || '';
        document.getElementById('daily-goal-protein').value = currentGoal.protein_g || '';
        document.getElementById('daily-goal-carbs').value = currentGoal.carbs_g || '';
        document.getElementById('daily-goal-fat').value = currentGoal.fat_g || '';
        document.getElementById('daily-goal-notes').value = currentGoal.notes || '';
        
        // Show delete button only if it's a custom goal
        if (currentGoal.is_custom) {
            document.getElementById('delete-daily-goal-btn').classList.remove('hidden');
        } else {
            document.getElementById('delete-daily-goal-btn').classList.add('hidden');
        }
    }
    
    document.getElementById('daily-goal-modal').classList.remove('hidden');
    document.getElementById('daily-goal-modal').classList.add('flex');
}

// Cancel daily goal
document.getElementById('cancel-daily-goal-btn')?.addEventListener('click', function() {
    document.getElementById('daily-goal-modal').classList.add('hidden');
    document.getElementById('daily-goal-modal').classList.remove('flex');
});

// Save daily goal
document.getElementById('save-daily-goal-btn')?.addEventListener('click', async function() {
    const calories = document.getElementById('daily-goal-calories').value;
    const protein = document.getElementById('daily-goal-protein').value;
    const carbs = document.getElementById('daily-goal-carbs').value;
    const fat = document.getElementById('daily-goal-fat').value;
    const notes = document.getElementById('daily-goal-notes').value;
    
    if (!calories) {
        showToast('Please enter calories', 'error');
        return;
    }
    
    const fd = new FormData();
    fd.append('action', 'set_daily_goal');
    fd.append('date', currentDate);
    fd.append('calories', calories);
    fd.append('protein', protein || 0);
    fd.append('carbs', carbs || 0);
    fd.append('fat', fat || 0);
    fd.append('notes', notes);
    
    try {
        const res = await fetch('api/nutrition_api_v2.php', {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
        });
        const data = await res.json();
        
        if (data.success) {
            showToast('✓ Daily goal saved!', 'success');
            document.getElementById('daily-goal-modal').classList.add('hidden');
            document.getElementById('daily-goal-modal').classList.remove('flex');
            loadDataForDate(currentDate);
        }
    } catch (error) {
        console.error('Error saving daily goal:', error);
        showToast('Failed to save goal', 'error');
    }
});

// Delete daily goal
document.getElementById('delete-daily-goal-btn')?.addEventListener('click', async function() {
    if (!confirm('Delete custom goal for this day? It will revert to your default goal.')) {
        return;
    }
    
    try {
        const res = await fetch('api/nutrition_api_v2.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=delete_daily_goal&date=${currentDate}`,
            credentials: 'same-origin'
        });
        const data = await res.json();
        
        if (data.success) {
            showToast('✓ Custom goal deleted', 'success');
            document.getElementById('daily-goal-modal').classList.add('hidden');
            document.getElementById('daily-goal-modal').classList.remove('flex');
            loadDataForDate(currentDate);
        }
    } catch (error) {
        console.error('Error deleting daily goal:', error);
        showToast('Failed to delete goal', 'error');
    }
});

// Check for goal update on load
if (typeof checkInitialization === 'function') {
    const originalCheck = checkInitialization;
    checkInitialization = async function() {
        await originalCheck();
        await checkGoalUpdateNeeded();
    };
}

document.getElementById('date-selector').addEventListener('change', function() {
    currentDate = this.value;
    loadDataForDate(currentDate);
});

document.getElementById('prev-day-btn').addEventListener('click', function() {
    const date = new Date(currentDate);
    date.setDate(date.getDate() - 1);
    currentDate = date.toISOString().split('T')[0];
    document.getElementById('date-selector').value = currentDate;
    loadDataForDate(currentDate);
});

document.getElementById('next-day-btn').addEventListener('click', function() {
    const date = new Date(currentDate);
    date.setDate(date.getDate() + 1);
    const today = new Date().toISOString().split('T')[0];
    if (currentDate < today) {
        currentDate = date.toISOString().split('T')[0];
        document.getElementById('date-selector').value = currentDate;
        loadDataForDate(currentDate);
    }
});

document.getElementById('today-btn').addEventListener('click', function() {
    currentDate = new Date().toISOString().split('T')[0];
    document.getElementById('date-selector').value = currentDate;
    loadDataForDate(currentDate);
});

async function loadDataForDate(date) {
    try {
        const res = await fetch(`api/nutrition_api_v2.php?action=get_summary_by_date&date=${date}`, {
            credentials: 'same-origin'
        });
        
        const text = await res.text();
        console.log('Raw response:', text);
        
        const data = JSON.parse(text);
        
        if (data.success) {
            // Update date display
            const dateObj = new Date(date + 'T00:00:00');
            const dateDisplay = dateObj.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            document.getElementById('selected-date-display').textContent = dateDisplay;
            
            // Update title labels
            const isToday = date === new Date().toISOString().split('T')[0];
            const titleText = isToday ? "Today's" : dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            document.getElementById('food-log-title').textContent = titleText;
            document.getElementById('activity-log-title').textContent = titleText;
            document.getElementById('macros-title').textContent = titleText;
            
            // Update summary
            currentSummary = data.summary || {
                total_calories_consumed: 0,
                total_calories_burned: 0,
                net_calories: 0,
                total_protein_g: 0,
                total_carbs_g: 0,
                total_fat_g: 0,
                status: 'on_track'
            };
            currentGoal = data.goal;
            
            // Show badge if custom goal
            const goalBadge = document.getElementById('goal-badge');
            if (data.goal && data.goal.is_custom) {
                goalBadge.textContent = '(Custom)';
                goalBadge.className = 'text-xs bg-blue-500 px-2 py-1 rounded-full';
            } else {
                goalBadge.textContent = '(Default)';
                goalBadge.className = 'text-xs bg-gray-600 px-2 py-1 rounded-full';
            }
            
            // Update dashboard with current date's data
            if (typeof window.nutritionAdvanced !== 'undefined') {
                window.nutritionAdvanced.currentGoal = currentGoal;
                window.nutritionAdvanced.currentSummary = currentSummary;
                if (window.nutritionAdvanced.updateDashboard) {
                    window.nutritionAdvanced.updateDashboard();
                }
            }
            
            // Manually update dashboard elements
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
            
            // Update food log
            const foodContainer = document.getElementById('food-log-list');
            if (data.foods.length === 0) {
                foodContainer.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No foods logged</p>';
            } else {
                foodContainer.innerHTML = data.foods.map(food => `
                    <div class="bg-gray-700/50 rounded-lg p-3 flex justify-between items-center">
                        <div>
                            <p class="text-white font-semibold text-sm">${food.food_name}</p>
                            <p class="text-xs text-gray-400">${Math.round(food.calories * food.quantity)} kcal · ${food.serving_size}</p>
                        </div>
                        ${isToday ? `<button class="text-red-400 hover:text-red-300 text-lg delete-food-btn" data-id="${food.id}">×</button>` : ''}
                    </div>
                `).join('');
                
                if (isToday && window.nutritionAdvanced && window.nutritionAdvanced.deleteFood) {
                    document.querySelectorAll('.delete-food-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            window.nutritionAdvanced.deleteFood(this.dataset.id);
                        });
                    });
                }
            }
            
            // Update activity log
            const activityContainer = document.getElementById('activity-log-list');
            if (data.activities.length === 0) {
                activityContainer.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No activities logged</p>';
            } else {
                activityContainer.innerHTML = data.activities.map(activity => {
                    const durationText = activity.duration_minutes > 0 
                        ? `${activity.duration_minutes} min` 
                        : 'reps';
                    return `
                        <div class="bg-gray-700/50 rounded-lg p-3 flex justify-between items-center">
                            <div>
                                <p class="text-white font-semibold text-sm">${activity.activity_name}</p>
                                <p class="text-xs text-gray-400">${durationText} · ${Math.round(activity.calories_burned)} kcal burned</p>
                            </div>
                            ${isToday ? `<button class="text-red-400 hover:text-red-300 text-lg delete-activity-btn" data-id="${activity.id}">×</button>` : ''}
                        </div>
                    `;
                }).join('');
                
                if (isToday && window.nutritionAdvanced && window.nutritionAdvanced.deleteActivity) {
                    document.querySelectorAll('.delete-activity-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            window.nutritionAdvanced.deleteActivity(this.dataset.id);
                        });
                    });
                }
            }
        }
    } catch (error) {
        console.error('Error loading date data:', error);
        showToast('Failed to load data for selected date', 'error');
    }
}
