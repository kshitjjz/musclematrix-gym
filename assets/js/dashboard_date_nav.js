// Dashboard Date Navigation
let dashCurrentDate = new Date().toISOString().split('T')[0];

document.getElementById('dash-date-selector').addEventListener('change', function() {
    dashCurrentDate = this.value;
    loadDashboardDataForDate(dashCurrentDate);
});

document.getElementById('dash-prev-day').addEventListener('click', function() {
    const date = new Date(dashCurrentDate);
    date.setDate(date.getDate() - 1);
    dashCurrentDate = date.toISOString().split('T')[0];
    document.getElementById('dash-date-selector').value = dashCurrentDate;
    loadDashboardDataForDate(dashCurrentDate);
});

document.getElementById('dash-next-day').addEventListener('click', function() {
    const date = new Date(dashCurrentDate);
    date.setDate(date.getDate() + 1);
    const today = new Date().toISOString().split('T')[0];
    if (dashCurrentDate < today) {
        dashCurrentDate = date.toISOString().split('T')[0];
        document.getElementById('dash-date-selector').value = dashCurrentDate;
        loadDashboardDataForDate(dashCurrentDate);
    }
});

document.getElementById('dash-today').addEventListener('click', function() {
    dashCurrentDate = new Date().toISOString().split('T')[0];
    document.getElementById('dash-date-selector').value = dashCurrentDate;
    loadDashboardDataForDate(dashCurrentDate);
});

async function loadDashboardDataForDate(date) {
    try {
        const res = await fetch(`api/nutrition_api_v2.php?action=get_summary_by_date&date=${date}`, {
            credentials: 'same-origin'
        });
        
        const data = await res.json();
        
        if (data.success) {
            // Update date display
            const dateObj = new Date(date + 'T00:00:00');
            const isToday = date === new Date().toISOString().split('T')[0];
            const dateDisplay = isToday ? "Today's Overview" : dateObj.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }) + ' Overview';
            document.getElementById('dash-selected-date-display').textContent = dateDisplay;
            
            // Get summary data
            const summary = data.summary || {
                total_calories_consumed: 0,
                total_calories_burned: 0,
                net_calories: 0,
                total_protein_g: 0,
                total_carbs_g: 0,
                total_fat_g: 0,
                status: 'on_track'
            };
            
            const goal = data.goal;
            
            // Update stat cards
            document.getElementById('dash-net-calories').textContent = Math.round(summary.net_calories || 0);
            document.getElementById('dash-goal-calories').textContent = goal ? goal.daily_calories : 2000;
            document.getElementById('dash-food-count').textContent = data.foods.length;
            document.getElementById('dash-consumed').textContent = Math.round(summary.total_calories_consumed || 0);
            document.getElementById('dash-activity-count').textContent = data.activities.length;
            document.getElementById('dash-burned').textContent = Math.round(summary.total_calories_burned || 0);
            
            // Update status
            const status = summary.status || 'on_track';
            const statusIcon = document.getElementById('dash-status-icon');
            const statusText = document.getElementById('dash-status-text');
            
            if (status === 'surplus') {
                statusIcon.textContent = '📈';
                statusText.textContent = 'Surplus';
                statusText.className = 'text-3xl font-bold text-red-400';
            } else if (status === 'deficit') {
                statusIcon.textContent = '📉';
                statusText.textContent = 'Deficit';
                statusText.className = 'text-3xl font-bold text-blue-400';
            } else {
                statusIcon.textContent = '⚖️';
                statusText.textContent = 'On Track';
                statusText.className = 'text-3xl font-bold text-emerald-400';
            }
            
            // Update macros
            document.getElementById('dash-protein').textContent = Math.round(summary.total_protein_g || 0);
            document.getElementById('dash-carbs').textContent = Math.round(summary.total_carbs_g || 0);
            document.getElementById('dash-fat').textContent = Math.round(summary.total_fat_g || 0);
            
            if (goal) {
                document.getElementById('dash-protein-goal').textContent = goal.protein_g || 0;
                document.getElementById('dash-carbs-goal').textContent = goal.carbs_g || 0;
                document.getElementById('dash-fat-goal').textContent = goal.fat_g || 0;
            }
        }
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}
