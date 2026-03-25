console.log('🚀 main.js loaded');
document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ DOMContentLoaded fired');
    console.log('🔍 Checking for login modal elements...');
    
    // IMMEDIATE CHECK - before any other code
    const testLoginModal = document.getElementById('login-modal');
    const testLoginButtons = document.querySelectorAll('.open-login-modal');
    console.log('Login modal exists:', !!testLoginModal);
    console.log('Login buttons found:', testLoginButtons.length);
    
    // --- DATA (This can be loaded on all pages) ---
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('show-login-modal') === '1') {
        const modal = document.getElementById('login-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // or whatever flex classes you use
        }
    }

    // Close button
    const closeBtn = document.getElementById('close-login-modal');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }
    
    const classData = {
        monday: [
            { time: '6:00 AM', name: 'Morning HIIT', instructor: 'Sarah Johnson', spots: '12/15', duration: 45, type: 'hiit', difficulty: 'Intermediate' },
            { time: '9:00 AM', name: 'Vinyasa Flow', instructor: 'Mike Chen', spots: '8/12', duration: 60, type: 'yoga', difficulty: 'Beginner' },
            { time: '6:00 PM', name: 'Strength Training', instructor: 'David Rodriguez', spots: '10/20', duration: 75, type: 'strength', difficulty: 'Advanced' },
        ],
        tuesday: [
            { time: '7:00 AM', name: 'Spin Revolution', instructor: 'Emma Wilson', spots: '15/20', duration: 50, type: 'cardio', difficulty: 'Intermediate' },
            { time: '5:00 PM', name: 'Boxing Basics', instructor: 'David Rodriguez', spots: '7/10', duration: 60, type: 'cardio', difficulty: 'Beginner' },
            { time: '7:00 PM', name: 'Powerlifting 101', instructor: 'Alex Stone', spots: '5/8', duration: 90, type: 'strength', difficulty: 'Advanced' },
        ],
        wednesday: [
            { time: '6:00 AM', name: 'Sunrise Yoga', instructor: 'Mike Chen', spots: '10/15', duration: 60, type: 'yoga', difficulty: 'Beginner' },
            { time: '6:00 PM', name: 'Full Body Burn', instructor: 'Sarah Johnson', spots: '18/20', duration: 60, type: 'hiit', difficulty: 'Intermediate' },
        ],
        thursday: [
             { time: '7:00 AM', name: 'Endurance Run', instructor: 'Emma Wilson', spots: '11/20', duration: 50, type: 'cardio', difficulty: 'Advanced' },
             { time: '5:00 PM', name: 'Restorative Yoga', instructor: 'Mike Chen', spots: '9/12', duration: 60, type: 'yoga', difficulty: 'Beginner' },
        ],
        friday: [
            { time: '6:00 AM', name: 'Friday Finisher', instructor: 'Sarah Johnson', spots: '12/15', duration: 45, type: 'hiit', difficulty: 'Advanced' },
            { time: '6:00 PM', name: 'Olympic Weightlifting', instructor: 'Alex Stone', spots: '4/8', duration: 90, type: 'strength', difficulty: 'Expert' },
        ],
        saturday: [
             { time: '9:00 AM', name: 'Weekend Warrior Bootcamp', instructor: 'David Rodriguez', spots: '25/30', duration: 75, type: 'hiit', difficulty: 'Intermediate' },
        ],
        sunday: []
    };
    const tourData = [
        { id: 'cardio', title: 'Cardio Zone', description: 'State-of-the-art treadmills, ellipticals, and cycling stations.', features: ['30+ Cardio Machines', 'Heart Rate Monitoring'], image: 'https://placehold.co/800x600/1F2937/34D399?text=Cardio+Zone' },
        { id: 'strength', title: 'Strength Training', description: 'A wide range of free weights, squat racks, and resistance machines.', features: ['Olympic Barbells', 'Dumbbells up to 150lbs'], image: 'https://placehold.co/800x600/1F2937/34D399?text=Strength+Training' },
        { id: 'group', title: 'Group Fitness Studio', description: 'Spacious studio for HIIT, yoga, and other group classes.', features: ['Mirrored Walls', 'Premium Sound System'], image: 'https://placehold.co/800x600/1F2937/34D399?text=Group+Fitness' },
        { id: 'recovery', title: 'Recovery Lounge', description: 'Relax and recover with our foam rollers and massage chairs.', features: ['Massage Chairs', 'Stretching Area'], image: 'https://placehold.co/800x600/1F2937/34D399?text=Recovery+Lounge' }
    ];
    const leaderboardData = [
        { rank: 1, name: 'Sarah Johnson', streak: '21 day streak', workouts: 68 },
        { rank: 2, name: 'Mike Chen', streak: '18 day streak', workouts: 64 },
        { rank: 3, name: 'Emma Wilson', streak: '15 day streak', workouts: 59 },
        { rank: 4, name: 'You', isUser: true, streak: '12 day streak', workouts: 47 },
        { rank: 5, name: 'David Kim', streak: '9 day streak', workouts: 43 },
    ];
    const quizData = [
        { question: "What's your primary fitness goal?", options: ["Weight Loss", "Muscle Gain", "Endurance", "General Fitness"] },
        { question: "How much time can you dedicate to workouts?", options: ["30 minutes", "45 minutes", "60 minutes", "90+ minutes"] },
        { question: "What's your current fitness level?", options: ["Beginner", "Intermediate", "Advanced", "Expert"] },
        { question: "How many days per week can you workout?", options: ["2-3 days", "4-5 days", "6-7 days", "Daily"] }
    ];
    const activityData = { "2025-10-02": 1, "2025-10-04": 3, "2025-10-05": 2, "2025-10-08": 2, "2025-10-10": 3, "2025-10-11": 1, "2025-10-14": 2, "2025-10-16": 3, "2025-10-17": 2, };
    const recentWorkoutsData = [ { type: 'Strength', details: 'Upper Body', date: '2025-10-17', duration: '60 min' }, { type: 'HIIT', details: 'Full Body Blast', date: '2025-10-16', duration: '45 min' }, { type: 'Yoga', details: 'Vinyasa Flow', date: '2025-10-14', duration: '50 min' }, ];
    const badgeData = [ { name: 'First Step', icon: '&#x1F463;', earned: true, description: 'Complete your first workout.' }, { name: '7 Day Streak', icon: '&#x1F525;', earned: true, description: 'Workout 7 days in a row.' }, { name: 'Cardio King', icon: '&#x1F451;', earned: true, description: 'Log 10 cardio sessions.' }, { name: 'Month Warrior', icon: '&#x1F31F;', earned: false, description: 'Complete 20 workouts in a month.', progress: 12, total: 20 }, { name: 'Strength Master', icon: '&#x1F4AA;', earned: false, description: 'Lift a total of 10,000 lbs.', progress: 6500, total: 10000 }, { name: 'Early Bird', icon: '&#x1F424;', earned: false, description: 'Workout before 7 AM 5 times.', progress: 2, total: 5 }, ];
    const videoData = [ { id: 1, title: 'Perfect Squat Form', trainer: 'Sarah Johnson', duration: 12, muscleGroup: 'Legs', image: 'https://placehold.co/600x400/1F2937/34D399?text=Squat+Form', isSaved: false }, { id: 2, title: 'Morning Motivation Mindset', trainer: 'Mike Chen', duration: 8, muscleGroup: 'Motivation', image: 'https://placehold.co/600x400/1F2937/34D399?text=Motivation', isSaved: true }, { id: 3, title: 'Post-Workout Recovery', trainer: 'Emma Wilson', duration: 18, muscleGroup: 'Recovery', image: 'https://placehold.co/600x400/1F2937/34D399?text=Recovery+Tips', isSaved: false }, { id: 4, title: 'Deadlift Mastery Guide', trainer: 'Alex Stone', duration: 25, muscleGroup: 'Back', image: 'https://placehold.co/600x400/1F2937/34D399?text=Deadlift+Mastery', isSaved: false }, { id: 5, title: 'Pre-Workout Nutrition', trainer: 'Emma Wilson', duration: 10, muscleGroup: 'Nutrition', image: 'https://placehold.co/600x400/1F2937/34D399?text=Pre-Workout+Nutrition', isSaved: true }, { id: 6, title: 'Breaking Through Plateaus', trainer: 'David Rodriguez', duration: 32, muscleGroup: 'Motivation', image: 'https://placehold.co/600x400/1F2937/34D399?text=Plateaus', isSaved: false }, { id: 7, title: 'Advanced Core Techniques', trainer: 'Sarah Johnson', duration: 22, muscleGroup: 'Core', image: 'https://placehold.co/600x400/1F2937/34D399?text=Core+Techniques', isSaved: false }, ];
    let macroChartInstance = null;

    // --- GLOBAL SCRIPTS (Run on every page) ---

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    }

    // Login & Register Modals - Simple and Direct
    document.body.addEventListener('click', function(e) {
        console.log('Body clicked:', e.target);
        
        // Open login modal
        if (e.target.classList.contains('open-login-modal') || e.target.closest('.open-login-modal')) {
            e.preventDefault();
            console.log('Login button clicked!');
            const loginModal = document.getElementById('login-modal');
            const registerModal = document.getElementById('register-modal');
            console.log('Login modal element:', loginModal);
            if (loginModal) {
                if (registerModal) {
                    registerModal.classList.add('hidden');
                    registerModal.classList.remove('flex');
                }
                loginModal.classList.remove('hidden');
                loginModal.classList.add('flex');
                console.log('✅ Login modal opened');
            } else {
                console.error('❌ Login modal not found!');
            }
        }
        
        // Close login modal
        if (e.target.id === 'close-login-modal') {
            const loginModal = document.getElementById('login-modal');
            if (loginModal) {
                loginModal.classList.add('hidden');
                loginModal.classList.remove('flex');
            }
        }
        
        // Close register modal
        if (e.target.id === 'close-register-modal') {
            const registerModal = document.getElementById('register-modal');
            if (registerModal) {
                registerModal.classList.add('hidden');
                registerModal.classList.remove('flex');
            }
        }
        
        // Show register modal from login
        if (e.target.id === 'show-register-modal') {
            e.preventDefault();
            const loginModal = document.getElementById('login-modal');
            const registerModal = document.getElementById('register-modal');
            if (loginModal) {
                loginModal.classList.add('hidden');
                loginModal.classList.remove('flex');
            }
            if (registerModal) {
                registerModal.classList.remove('hidden');
                registerModal.classList.add('flex');
            }
        }
        
        // Show login modal from register
        if (e.target.id === 'show-login-modal') {
            e.preventDefault();
            const loginModal = document.getElementById('login-modal');
            const registerModal = document.getElementById('register-modal');
            if (registerModal) {
                registerModal.classList.add('hidden');
                registerModal.classList.remove('flex');
            }
            if (loginModal) {
                loginModal.classList.remove('hidden');
                loginModal.classList.add('flex');
            }
        }
    });

    // Handle login form submission (GLOBAL)
    const loginForm = document.getElementById('login-form');
    console.log('🔍 Login form found:', loginForm);
    if (loginForm) {
        console.log('✅ Attaching login form handler');
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('🚀 Login form submitted');
            
            const submitBtn = document.getElementById('login-submit-btn');
            const errorDiv = document.getElementById('login-error');
            const successDiv = document.getElementById('login-success');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Logging in...';
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
            const formData = new FormData(loginForm);
            console.log('📤 Sending login request...');
            
            try {
                const response = await fetch('auth/login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const text = await response.text();
                console.log('📥 Response:', text);
                
                if (text.includes('✅')) {
                    successDiv.textContent = 'Login successful! Redirecting...';
                    successDiv.classList.remove('hidden');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    errorDiv.textContent = text.replace(/❌/g, '').trim();
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Login';
                }
            } catch (error) {
                console.error('❌ Login error:', error);
                errorDiv.textContent = 'Login failed. Please try again.';
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login';
            }
        });
    }


    // --- PAGE-SPECIFIC SCRIPTS ---

    // HOME PAGE LOGIC
    if (document.getElementById('page-home')) {
        // HERO SLIDER
        const before = document.querySelector('.split.before');
        const slider = document.querySelector('.slider-handle');
        const hero = document.querySelector('.split-hero');
        if (hero) {
            let isDragging = false;
            const startDrag = () => isDragging = true;
            const stopDrag = () => isDragging = false;
            const onDrag = (e) => {
                if (!isDragging) return;
                let rect = hero.getBoundingClientRect();
                let x = (e.clientX || e.touches[0].clientX) - rect.left;
                x = Math.max(0, Math.min(x, rect.width));
                before.style.width = `${x}px`;
                slider.style.left = `${x}px`;
            };

            slider.addEventListener('mousedown', startDrag);
            window.addEventListener('mouseup', stopDrag);
            window.addEventListener('mousemove', onDrag);
            slider.addEventListener('touchstart', startDrag, { passive: true });
            window.addEventListener('touchend', stopDrag);
            window.addEventListener('touchmove', onDrag, { passive: true });
        }

        // DYNAMIC STATS COUNTER
        function countUp(el, target) {
            let count = 0;
            let step = Math.ceil(target / 100);
            let interval = setInterval(() => {
                count += step;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                el.innerText = count.toLocaleString();
            }, 20);
        }
        const statsSection = document.getElementById('stats-section');
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                countUp(document.getElementById('members-count'), 1200);
                countUp(document.getElementById('hours-count'), 5000);
                countUp(document.getElementById('calories-count'), 35000);
                observer.disconnect();
            }
        }, { threshold: 0.5 });
        if (statsSection) {
            observer.observe(statsSection);
        }

        // AI WORKOUT FINDER
        const workoutFinderModal = document.getElementById('workout-finder-modal');
        const quizContainer = document.getElementById('quiz-container');
        const workoutFinderBtn = document.getElementById('workout-finder-btn');
        const workoutFinderBtnMobile = document.getElementById('workout-finder-btn-mobile');
        let currentQuestion = 0;
        
        const openQuiz = () => {
            workoutFinderModal.classList.remove('hidden');
            renderQuiz();
        };

        if (workoutFinderBtn) workoutFinderBtn.addEventListener('click', openQuiz);
        if (workoutFinderBtnMobile) workoutFinderBtnMobile.addEventListener('click', openQuiz);

        function renderQuiz() {
            if (currentQuestion >= quizData.length) {
                quizContainer.innerHTML = `
                    <h2 class="text-2xl font-bold text-center text-white mb-4">We've found your perfect plan!</h2>
                    <p class="text-gray-400 text-center mb-6">Based on your answers, we recommend the "Progressive Strength" plan.</p>
                    <button id="view-plan-btn" class="w-full bg-emerald-500 text-gray-900 font-bold py-3 rounded-lg cta-button">View My Plan</button>
                    <button id="close-quiz-btn" class="w-full text-gray-400 text-center mt-4">Close</button>
                `;
                document.getElementById('close-quiz-btn').addEventListener('click', () => {
                    workoutFinderModal.classList.add('hidden');
                    currentQuestion = 0;
                });
                document.getElementById('view-plan-btn').addEventListener('click', () => {
                    workoutFinderModal.classList.add('hidden');
                    const recommendedPlanSection = document.getElementById('powerpulse-pass-section');
                    recommendedPlanSection.classList.remove('hidden');
                    recommendedPlanSection.scrollIntoView({ behavior: 'smooth' });
                    currentQuestion = 0;
                });
                return;
            }

            const q = quizData[currentQuestion];
            const progress = ((currentQuestion + 1) / quizData.length) * 100;
            quizContainer.innerHTML = `
                <button id="close-quiz-btn-x" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">&times;</button>
                <p class="text-sm text-gray-400 mb-2">Question ${currentQuestion + 1} of ${quizData.length}</p>
                <div class="w-full bg-gray-700 rounded-full h-2 mb-4"><div class="bg-emerald-500 h-2 rounded-full" style="width: ${progress}%"></div></div>
                <h2 class="text-2xl font-bold text-white mb-6">${q.question}</h2>
                <div class="grid grid-cols-2 gap-4">
                    ${q.options.map(opt => `<button class="quiz-option p-4 bg-gray-700 rounded-lg hover:bg-emerald-500/20 hover:ring-1 hover:ring-emerald-400">${opt}</button>`).join('')}
                </div>
            `;
            document.querySelectorAll('.quiz-option').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentQuestion++;
                    renderQuiz();
                });
            });
            document.getElementById('close-quiz-btn-x').addEventListener('click', () => {
                workoutFinderModal.classList.add('hidden');
                currentQuestion = 0;
            });
        }
    }

    // SCHEDULE PAGE LOGIC
    if (document.getElementById('page-schedule')) {
        const dayButtons = document.querySelectorAll('.schedule-day-btn');
        const filterButtons = document.querySelectorAll('.class-filter-btn');
        const classGrid = document.getElementById('class-grid');
        const classDetailsModal = document.getElementById('class-details-modal');
        const classModalContent = document.getElementById('class-modal-content');

        let currentDay = 'monday';
        let currentFilter = 'all';

        function renderClasses() {
            classGrid.innerHTML = '';
            const classes = classData[currentDay] || [];
            const filteredClasses = currentFilter === 'all' ? classes : classes.filter(c => c.type === currentFilter);

            if (filteredClasses.length === 0) {
                classGrid.innerHTML = `<p class="text-gray-400 col-span-full text-center">No classes scheduled for this day.</p>`;
                return;
            }

            filteredClasses.forEach(c => {
                const classEl = document.createElement('div');
                classEl.className = 'bg-gray-800 p-6 rounded-lg border border-gray-700 hover:border-emerald-400 transition-colors duration-300 cursor-pointer';
                classEl.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-emerald-400 font-bold">${c.time}</p>
                            <h3 class="text-xl font-bold text-white">${c.name}</h3>
                            <p class="text-gray-400 text-sm">with ${c.instructor}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold uppercase bg-gray-700 px-2 py-1 rounded-full">${c.type}</span>
                            <p class="text-sm text-gray-300 mt-2">${c.duration} min</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-400">Spots Left: <span class="font-bold text-white">${c.spots.split('/')[1] - c.spots.split('/')[0]}</span></p>
                        <button class="bg-emerald-500 text-gray-900 font-bold py-2 px-4 rounded-lg cta-button text-sm">Book Class</button>
                    </div>
                `;
                classEl.addEventListener('click', () => showClassDetails(c));
                classGrid.appendChild(classEl);
            });
        }

        function showClassDetails(classInfo) {
            classModalContent.innerHTML = `
                <span class="text-xs font-semibold uppercase bg-gray-700 px-2 py-1 rounded-full">${classInfo.type}</span>
                <h2 class="text-3xl font-bold text-white mt-2">${classInfo.name}</h2>
                <p class="text-lg text-gray-300 mt-1">with ${classInfo.instructor}</p>
                <div class="my-4 border-t border-gray-700"></div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div><p class="text-sm text-gray-400">Time</p><p class="font-bold text-white">${classInfo.time}</p></div>
                    <div><p class="text-sm text-gray-400">Duration</p><p class="font-bold text-white">${classInfo.duration} min</p></div>
                    <div><p class="text-sm text-gray-400">Difficulty</p><p class="font-bold text-white">${classInfo.difficulty}</p></div>
                </div>
                <div class="my-4 border-t border-gray-700"></div>
                <p class="text-gray-400 text-sm mb-6">A high-energy class designed to push your limits. Bring a towel and a water bottle!</p>
                <button class="w-full bg-emerald-500 text-gray-900 font-bold py-3 px-8 rounded-lg text-lg uppercase cta-button">Confirm Booking (${classInfo.spots})</button>
            `;
            classDetailsModal.classList.remove('hidden');
        }

        document.getElementById('close-class-modal').addEventListener('click', () => classDetailsModal.classList.add('hidden'));

        dayButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                dayButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentDay = btn.dataset.day;
                renderClasses();
            });
        });

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active', 'bg-emerald-500', 'text-gray-900'));
                btn.classList.add('active', 'bg-emerald-500', 'text-gray-900');
                currentFilter = btn.dataset.filter;
                renderClasses();
            });
        });

        renderClasses();
    }

    // TOUR PAGE LOGIC
    if (document.getElementById('page-tour')) {
        const mainImage = document.getElementById('tour-main-image');
        const mainTitle = document.getElementById('tour-main-title');
        const mainDescription = document.getElementById('tour-main-description');
        const mainFeatures = document.getElementById('tour-main-features');
        const thumbnailsContainer = document.getElementById('tour-thumbnails');

        function renderTour(tourId) {
            const tour = tourData.find(t => t.id === tourId);
            mainImage.src = tour.image;
            mainTitle.textContent = tour.title;
            mainDescription.textContent = tour.description;
            mainFeatures.innerHTML = tour.features.map(f => `<li>- ${f}</li>`).join('');
        }
        
        tourData.forEach(tour => {
            const thumb = document.createElement('div');
            thumb.className = 'cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-emerald-400';
            thumb.innerHTML = `<img src="${tour.image}" alt="${tour.title}" class="w-full h-full object-cover">`;
            thumb.addEventListener('click', () => renderTour(tour.id));
            thumbnailsContainer.appendChild(thumb);
        });
        renderTour('cardio');
    }

    // TRACKER PAGE LOGIC
    if (document.getElementById('page-tracker')) {
        const trackerTabButtons = document.querySelectorAll('.tracker-tab-btn');
        const trackerTabs = document.querySelectorAll('.tracker-tab-content');
        
        trackerTabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                trackerTabButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                trackerTabs.forEach(tab => tab.classList.add('hidden'));
                document.getElementById(`tracker-${btn.dataset.tab}`).classList.remove('hidden');
            });
        });

        // Dashboard Rendering
        const muscleCtx = document.getElementById('muscleFocusChart').getContext('2d');
        new Chart(muscleCtx, { type: 'doughnut', data: { labels: ['Legs', 'Chest', 'Back', 'Arms', 'Core'], datasets: [{ data: [3, 4, 3, 5, 4], backgroundColor: ['#10B981', '#34D399', '#6EE7B7', '#A7F3D0', '#D1FAE5'], borderColor: '#1F2937', borderWidth: 4, }] }, options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#D1D5DB' } } } } });

        function renderActivityCalendar() {
            const calendarEl = document.getElementById('activity-calendar');
            calendarEl.innerHTML = '';
            const days = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
            days.forEach(day => calendarEl.innerHTML += `<div class="font-bold text-gray-500">${day}</div>`);
            const firstDayOffset = 3; 
            for(let i=0; i<firstDayOffset; i++) { calendarEl.innerHTML += `<div></div>`; }
            for(let i=1; i<=31; i++) {
                const date = `2025-10-${i.toString().padStart(2,'0')}`;
                const intensity = activityData[date];
                let intensityClass = 'bg-gray-700/50';
                if (intensity === 1) intensityClass = 'bg-emerald-900';
                if (intensity === 2) intensityClass = 'bg-emerald-600';
                if (intensity === 3) intensityClass = 'bg-emerald-400';
                calendarEl.innerHTML += `<div class="w-full h-8 flex items-center justify-center rounded ${intensityClass}">${i}</div>`;
            }
        }
        renderActivityCalendar();

        const recentWorkoutsEl = document.getElementById('recent-workouts');
        recentWorkoutsData.forEach(workout => { recentWorkoutsEl.innerHTML += `<div class="flex items-center p-3 bg-gray-700/50 rounded-lg"><div class="flex-grow"><p class="font-bold text-white">${workout.type}: <span class="font-normal">${workout.details}</span></p><p class="text-xs text-gray-400">${workout.date}</p></div><div class="font-semibold text-white">${workout.duration}</div></div>`; });

        const leaderboardContainer = document.querySelector('#tracker-leaderboard > div');
        leaderboardData.forEach(user => { const userEl = document.createElement('div'); userEl.className = `flex items-center p-4 rounded-lg ${user.isUser ? 'bg-emerald-500/20 ring-1 ring-emerald-400' : 'bg-gray-700'}`; userEl.innerHTML = `<div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center font-bold mr-4">${user.rank}</div><div class="flex-grow text-left"><p class="font-bold text-white">${user.name}</p><p class="text-xs text-gray-400">${user.streak}</p></div><div class="font-semibold text-white">${user.workouts} workouts</div>`; leaderboardContainer.appendChild(userEl); });

        const badgeContainer = document.querySelector('#tracker-badges > div');
        badgeData.forEach(badge => {
            let badgeHTML = '';
            if (badge.earned) { badgeHTML = `<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 text-center"><div class="text-5xl mb-3">${badge.icon}</div><h4 class="font-bold text-white text-lg">${badge.name}</h4><p class="text-xs text-gray-400 mb-3">${badge.description}</p><span class="text-sm bg-emerald-500/20 text-emerald-300 px-3 py-1 rounded-full font-semibold">Earned</span></div>`; } else { const progressPercent = (badge.progress / badge.total) * 100; badgeHTML = `<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 text-center opacity-60"><div class="text-5xl mb-3">${badge.icon}</div><h4 class="font-bold text-white text-lg">${badge.name}</h4><p class="text-xs text-gray-400 mb-3">${badge.description}</p><div class="w-full bg-gray-700 rounded-full h-2.5 mb-1"><div class="bg-emerald-600 h-2.5 rounded-full" style="width: ${progressPercent}%"></div></div><p class="text-xs text-gray-400">${badge.progress} / ${badge.total}</p></div>`; }
            badgeContainer.innerHTML += badgeHTML;
        });
    }

    // NUTRITION PAGE LOGIC
    if (document.getElementById('page-nutrition')) {
        console.log('🍎 Nutrition page JS loaded');
        const nuUser = window.nutritionUser || { loggedIn: false };
        console.log('User logged in:', nuUser.loggedIn);

        // --- TAB SWITCHING ---
        console.log('Setting up tab buttons...');
        const nutritionTabBtns = document.querySelectorAll('.nutrition-tab-btn');
        const nutritionTabContents = document.querySelectorAll('.nutrition-tab-content');
        console.log('Found', nutritionTabBtns.length, 'tab buttons');
        console.log('Found', nutritionTabContents.length, 'tab contents');
        nutritionTabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                console.log('Tab clicked:', btn.dataset.tab);
                nutritionTabBtns.forEach(b => {
                    b.classList.remove('text-emerald-400', 'border-emerald-400');
                    b.classList.add('text-gray-400', 'border-transparent');
                });
                btn.classList.add('text-emerald-400', 'border-emerald-400');
                btn.classList.remove('text-gray-400', 'border-transparent');
                nutritionTabContents.forEach(t => t.style.display = 'none');
                document.getElementById('nutrition-tab-' + btn.dataset.tab).style.display = 'block';
            });
        });

        // --- CALORIE CALCULATOR ---
        let lastCalcResult = null;
        document.getElementById('calculate-nutrition-btn').addEventListener('click', () => {
            const age      = parseFloat(document.getElementById('age').value);
            const gender   = document.getElementById('gender').value;
            const weight   = parseFloat(document.getElementById('weight').value);
            const height   = parseFloat(document.getElementById('height').value);
            const activity = parseFloat(document.getElementById('activity').value);
            const goal     = document.getElementById('goal').value;
            if (!age || !weight || !height) return;

            const bmr  = Math.round((10 * weight) + (6.25 * height) - (5 * age) + (gender === 'male' ? 5 : -161));
            const tdee = Math.round(bmr * activity);
            let goalCalories = tdee;
            let adjustmentText = 'No adjustment';
            if (goal === 'lose') { goalCalories = tdee - 400; adjustmentText = '-400 kcal (Cut)'; }
            else if (goal === 'gain') { goalCalories = tdee + 400; adjustmentText = '+400 kcal (Bulk)'; }

            const proteinGrams   = Math.round(weight * (goal === 'lose' ? 2.2 : goal === 'gain' ? 2.0 : 1.8));
            const proteinCalories = proteinGrams * 4;
            const fatCalories    = goalCalories * 0.25;
            const fatGrams       = Math.round(fatCalories / 9);
            const carbCalories   = goalCalories - proteinCalories - fatCalories;
            const carbGrams      = Math.round(carbCalories / 4);

            lastCalcResult = { calories: Math.round(goalCalories), protein: proteinGrams, carbs: carbGrams, fat: fatGrams, goal_type: goal };

            document.getElementById('result-calories').textContent   = Math.round(goalCalories) + ' kcal';
            document.getElementById('result-protein').textContent    = proteinGrams + 'g';
            document.getElementById('result-carbs').textContent      = carbGrams + 'g';
            document.getElementById('result-fat').textContent        = fatGrams + 'g';
            document.getElementById('result-bmr').textContent        = bmr + ' kcal';
            document.getElementById('result-tdee').textContent       = tdee + ' kcal';
            document.getElementById('result-adjustment').textContent = adjustmentText;

            const macroCtx = document.getElementById('macroChart').getContext('2d');
            if (macroChartInstance) macroChartInstance.destroy();
            macroChartInstance = new Chart(macroCtx, {
                type: 'doughnut',
                data: { labels: ['Protein', 'Carbs', 'Fat'], datasets: [{ data: [proteinCalories, carbCalories, fatCalories], backgroundColor: ['#34D399', '#A7F3D0', '#6EE7B7'], borderColor: '#1F2937', borderWidth: 4 }] },
                options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#9CA3AF', font: { size: 11 } } } } }
            });
            document.getElementById('nutrition-results-container').classList.remove('opacity-0');

            // Meal timing
            const mealGrid = document.getElementById('meal-timing-grid');
            const meals = [
                { time: 'Breakfast (7-8 AM)',     cal: Math.round(goalCalories * 0.25), note: 'Oats, eggs, fruit' },
                { time: 'Pre-Workout (11 AM)',    cal: Math.round(goalCalories * 0.20), note: 'Rice, chicken, banana' },
                { time: 'Post-Workout (2 PM)',    cal: Math.round(goalCalories * 0.30), note: 'Protein shake, rice, veggies' },
                { time: 'Dinner (7 PM)',          cal: Math.round(goalCalories * 0.25), note: 'Salmon, sweet potato, salad' },
            ];
            mealGrid.innerHTML = meals.map(m =>
                '<div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-center">'
                + '<p class="text-emerald-400 font-semibold text-sm">' + m.time + '</p>'
                + '<p class="text-2xl font-bold text-white my-1">' + m.cal + '</p>'
                + '<p class="text-xs text-gray-400">kcal</p>'
                + '<p class="text-xs text-gray-500 mt-2">' + m.note + '</p></div>'
            ).join('');
            document.getElementById('meal-timing-tips').classList.remove('hidden');
        });

        // Save goal button (only rendered when logged in)
        const saveGoalBtn = document.getElementById('save-goal-btn');
        const saveGoalMsg = document.getElementById('save-goal-msg');
        if (saveGoalBtn) {
            saveGoalBtn.addEventListener('click', async () => {
                if (!lastCalcResult) { saveGoalMsg.textContent = 'Calculate first!'; saveGoalMsg.className = 'text-xs mt-2 text-yellow-400'; saveGoalMsg.classList.remove('hidden'); return; }
                saveGoalBtn.disabled = true;
                saveGoalBtn.textContent = 'Saving...';
                const fd = new FormData();
                fd.append('action', 'save_goal');
                fd.append('calories',  lastCalcResult.calories);
                fd.append('protein',   lastCalcResult.protein);
                fd.append('carbs',     lastCalcResult.carbs);
                fd.append('fat',       lastCalcResult.fat);
                fd.append('goal_type', lastCalcResult.goal_type);
                const res  = await fetch('nutrition_api.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) {
                    saveGoalMsg.textContent = '✓ Goal saved! Switch to Food Tracker to start logging.';
                    saveGoalMsg.className = 'text-xs mt-2 text-emerald-400';
                    // update tracker display if already loaded
                    const disp = document.getElementById('daily-goal-display');
                    if (disp) { disp.textContent = lastCalcResult.calories; userGoal = lastCalcResult; updateTotals(); }
                } else {
                    saveGoalMsg.textContent = 'Failed to save. Try again.';
                    saveGoalMsg.className = 'text-xs mt-2 text-red-400';
                }
                saveGoalMsg.classList.remove('hidden');
                saveGoalBtn.disabled = false;
                saveGoalBtn.textContent = '\uD83D\uDCBE Save as My Daily Goal';
            });
        }

        // --- FOOD TRACKER (DB for logged-in, localStorage for guests) ---
        let foodLog  = [];
        let userGoal = { calories: 2000, protein: 0, carbs: 0, fat: 0 };

        function updateTotals() {
            const goal = userGoal.calories || 2000;
            const totals = foodLog.reduce((acc, item) => {
                acc.cal     += item.calories * item.qty;
                acc.protein += (item.protein || 0) * item.qty;
                acc.carbs   += (item.carbs   || 0) * item.qty;
                acc.fat     += (item.fat     || 0) * item.qty;
                return acc;
            }, { cal: 0, protein: 0, carbs: 0, fat: 0 });

            const consumed  = Math.round(totals.cal);
            const remaining = goal - consumed;
            const pct       = Math.min((consumed / goal) * 100, 100);
            const bar       = document.getElementById('calorie-progress-bar');
            if (!bar) return;
            bar.style.width = pct + '%';
            bar.className   = 'h-5 rounded-full transition-all duration-500 ' + (pct >= 100 ? 'bg-red-500' : pct >= 80 ? 'bg-yellow-500' : 'bg-emerald-500');

            document.getElementById('calorie-progress-pct').textContent  = Math.round(pct) + '%';
            document.getElementById('total-consumed').textContent         = consumed;
            document.getElementById('total-remaining').textContent        = Math.abs(remaining);
            document.getElementById('remaining-label').innerHTML          = remaining < 0
                ? 'Over by: <span class="text-red-400 font-bold" id="total-remaining">' + Math.abs(remaining) + '</span> kcal'
                : 'Remaining: <span class="text-white font-bold" id="total-remaining">' + remaining + '</span> kcal';
            document.getElementById('total-protein-consumed').textContent = Math.round(totals.protein) + 'g';
            document.getElementById('total-carbs-consumed').textContent   = Math.round(totals.carbs)   + 'g';
            document.getElementById('total-fat-consumed').textContent     = Math.round(totals.fat)     + 'g';
            if (userGoal.protein) document.getElementById('goal-protein').textContent = userGoal.protein;
            if (userGoal.carbs)   document.getElementById('goal-carbs').textContent   = userGoal.carbs;
            if (userGoal.fat)     document.getElementById('goal-fat').textContent     = userGoal.fat;
        }

        function renderFoodLog() {
            const logEl    = document.getElementById('food-log-list');
            const emptyMsg = document.getElementById('empty-log-msg');
            if (!logEl) return;
            if (foodLog.length === 0) { logEl.innerHTML = ''; emptyMsg.classList.remove('hidden'); updateTotals(); return; }
            emptyMsg.classList.add('hidden');
            logEl.innerHTML = foodLog.map((item, i) =>
                '<div class="flex items-center justify-between bg-gray-700/50 rounded-lg px-4 py-3">'
                + '<div class="flex-1">'
                + '<p class="text-white font-semibold text-sm">' + item.name + '</p>'
                + '<p class="text-xs text-gray-400">' + item.serving + 'g &times; ' + item.qty + ' &nbsp;&middot;&nbsp; '
                + '<span class="text-emerald-400 font-semibold">' + Math.round(item.calories * item.qty) + ' kcal</span>'
                + ' &nbsp;&middot;&nbsp; P:' + Math.round((item.protein||0)*item.qty) + 'g'
                + ' C:' + Math.round((item.carbs||0)*item.qty) + 'g'
                + ' F:' + Math.round((item.fat||0)*item.qty) + 'g</p></div>'
                + '<div class="flex items-center gap-2 ml-4">'
                + '<button class="log-qty-btn w-7 h-7 bg-gray-600 hover:bg-gray-500 rounded text-white text-sm font-bold" data-index="' + i + '" data-id="' + (item.id||'') + '" data-action="dec">&minus;</button>'
                + '<span class="text-white text-sm w-5 text-center">' + item.qty + '</span>'
                + '<button class="log-qty-btn w-7 h-7 bg-gray-600 hover:bg-gray-500 rounded text-white text-sm font-bold" data-index="' + i + '" data-id="' + (item.id||'') + '" data-action="inc">+</button>'
                + '<button class="log-remove-btn text-red-400 hover:text-red-300 ml-2 text-xl leading-none" data-index="' + i + '" data-id="' + (item.id||'') + '">&times;</button>'
                + '</div></div>'
            ).join('');

            document.querySelectorAll('.log-qty-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const idx = parseInt(btn.dataset.index);
                    const dbId = btn.dataset.id;
                    if (btn.dataset.action === 'inc') foodLog[idx].qty++;
                    else if (foodLog[idx].qty > 1) foodLog[idx].qty--;
                    else { foodLog.splice(idx, 1); }

                    if (nuUser.loggedIn && dbId) {
                        const fd = new FormData(); fd.append('action','update_qty'); fd.append('id', dbId); fd.append('qty', foodLog[idx] ? foodLog[idx].qty : 0);
                        await fetch('nutrition_api.php', { method:'POST', body:fd });
                    } else { localStorage.setItem('gymFoodLog', JSON.stringify(foodLog)); }
                    renderFoodLog();
                });
            });
            document.querySelectorAll('.log-remove-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const idx  = parseInt(btn.dataset.index);
                    const dbId = btn.dataset.id;
                    foodLog.splice(idx, 1);
                    if (nuUser.loggedIn && dbId) {
                        const fd = new FormData(); fd.append('action','remove_food'); fd.append('id', dbId);
                        await fetch('nutrition_api.php', { method:'POST', body:fd });
                    } else { localStorage.setItem('gymFoodLog', JSON.stringify(foodLog)); }
                    renderFoodLog();
                });
            });
            updateTotals();
        }

        // Load goal + log on page open
        async function initTracker() {
            if (nuUser.loggedIn) {
                const [goalRes, logRes] = await Promise.all([
                    fetch('nutrition_api.php?action=get_goal'),
                    fetch('nutrition_api.php?action=get_log')
                ]);
                const goalData = await goalRes.json();
                const logData  = await logRes.json();
                if (!goalData.error) {
                    userGoal = { calories: goalData.daily_calories, protein: goalData.protein_g, carbs: goalData.carbs_g, fat: goalData.fat_g };
                    const disp = document.getElementById('daily-goal-display');
                    if (disp) disp.textContent = userGoal.calories;
                    const lbl = document.getElementById('goal-source-label');
                    if (lbl) lbl.textContent = goalData.goal_type ? '(' + goalData.goal_type + ')' : '';
                }
                if (Array.isArray(logData)) {
                    foodLog = logData.map(r => ({
                        id: r.id, name: r.food_name,
                        calories: parseFloat(r.calories), protein: parseFloat(r.protein),
                        carbs: parseFloat(r.carbs), fat: parseFloat(r.fat),
                        serving: parseInt(r.serving), qty: parseInt(r.qty)
                    }));
                }
            } else {
                foodLog = JSON.parse(localStorage.getItem('gymFoodLog') || '[]');
            }
            renderFoodLog();
        }
        initTracker();

        // Clear log
        const clearBtn = document.getElementById('clear-log-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', async () => {
                if (!confirm('Clear all food entries for today?')) return;
                foodLog = [];
                if (nuUser.loggedIn) {
                    const fd = new FormData(); fd.append('action','clear_log');
                    await fetch('nutrition_api.php', { method:'POST', body:fd });
                } else { localStorage.setItem('gymFoodLog', '[]'); }
                renderFoodLog();
            });
        }

        // Food Search via Open Food Facts API
        const searchBtn    = document.getElementById('food-search-btn');
        const searchInput  = document.getElementById('food-search-input');
        const searchResults = document.getElementById('food-search-results');
        const searchStatus  = document.getElementById('food-search-status');

        async function searchFood(query) {
            if (!query.trim()) return;
            searchResults.innerHTML = '';
            searchStatus.textContent = 'Searching...';
            searchStatus.classList.remove('hidden');
            searchBtn.disabled = true; searchBtn.textContent = '...';
            try {
                const url = 'https://world.openfoodfacts.org/cgi/search.pl?search_terms=' + encodeURIComponent(query) + '&search_simple=1&action=process&json=1&page_size=8&fields=product_name,nutriments,brands';
                const res  = await fetch(url);
                const data = await res.json();
                const products = (data.products || []).filter(p => p.product_name && p.nutriments && p.nutriments['energy-kcal_100g']);
                if (products.length === 0) { searchStatus.textContent = 'No results found. Try a different term.'; return; }
                searchStatus.textContent = 'Found ' + products.length + ' results:';
                searchResults.innerHTML = products.map((p, i) => {
                    const n       = p.nutriments;
                    const cal     = Math.round(n['energy-kcal_100g']     || 0);
                    const protein = Math.round(n['proteins_100g']         || 0);
                    const carbs   = Math.round(n['carbohydrates_100g']    || 0);
                    const fat     = Math.round(n['fat_100g']              || 0);
                    const brand   = p.brands ? ' &middot; ' + p.brands.split(',')[0] : '';
                    const safeName = p.product_name.replace(/"/g, '&quot;');
                    return '<div class="flex items-center justify-between bg-gray-700/50 hover:bg-gray-700 rounded-lg px-4 py-3 transition-colors">'
                        + '<div><p class="text-white text-sm font-semibold">' + p.product_name + ' <span class="text-gray-500 font-normal text-xs">' + brand + '</span></p>'
                        + '<p class="text-xs text-gray-400">' + cal + ' kcal &middot; P:' + protein + 'g &middot; C:' + carbs + 'g &middot; F:' + fat + 'g <span class="text-gray-500">(per 100g)</span></p></div>'
                        + '<button class="add-food-btn ml-4 bg-emerald-500 text-gray-900 font-bold text-xs px-3 py-1.5 rounded-lg cta-button flex-shrink-0"'
                        + ' data-name="' + safeName + '" data-cal="' + cal + '" data-protein="' + protein + '" data-carbs="' + carbs + '" data-fat="' + fat + '">+ Add</button>'
                        + '</div>';
                }).join('');

                document.querySelectorAll('.add-food-btn').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        e.stopPropagation();
                        const foodName = btn.dataset.name;
                        const cal      = parseFloat(btn.dataset.cal);
                        const protein  = parseFloat(btn.dataset.protein);
                        const carbs    = parseFloat(btn.dataset.carbs);
                        const fat      = parseFloat(btn.dataset.fat);

                        if (nuUser.loggedIn) {
                            const fd = new FormData();
                            fd.append('action',    'add_food');
                            fd.append('food_name', foodName);
                            fd.append('calories',  cal);
                            fd.append('protein',   protein);
                            fd.append('carbs',     carbs);
                            fd.append('fat',       fat);
                            fd.append('serving',   100);
                            fd.append('qty',       1);
                            const res  = await fetch('nutrition_api.php', { method:'POST', body:fd });
                            const data = await res.json();
                            if (data.success) {
                                const existing = foodLog.find(f => f.name === foodName);
                                if (existing) existing.qty++;
                                else foodLog.push({ id: data.id, name: foodName, calories: cal, protein, carbs, fat, serving: 100, qty: 1 });
                            }
                        } else {
                            const existing = foodLog.find(f => f.name === foodName);
                            if (existing) existing.qty++;
                            else foodLog.push({ name: foodName, calories: cal, protein, carbs, fat, serving: 100, qty: 1 });
                            localStorage.setItem('gymFoodLog', JSON.stringify(foodLog));
                        }
                        renderFoodLog();
                        btn.textContent = '\u2713 Added';
                        btn.classList.replace('bg-emerald-500','bg-emerald-700');
                        setTimeout(() => { btn.textContent = '+ Add'; btn.classList.replace('bg-emerald-700','bg-emerald-500'); }, 1500);
                    });
                });
            } catch(err) {
                searchStatus.textContent = 'Search failed. Check your internet connection.';
            } finally {
                searchBtn.disabled = false; searchBtn.textContent = 'Search';
            }
        }
        if (searchBtn) {
            searchBtn.addEventListener('click', () => searchFood(searchInput.value));
            searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') searchFood(searchInput.value); });
        }

        // --- GYM CALORIE GUIDE ---
        const workoutCalories = [
            { name: 'Heavy Weightlifting', cal: '300-450', icon: '\uD83C\uDFCB\uFE0F' },
            { name: 'HIIT Training',        cal: '500-700', icon: '\u26A1' },
            { name: 'Running (moderate)',   cal: '450-600', icon: '\uD83C\uDFC3' },
            { name: 'Cycling (moderate)',   cal: '400-550', icon: '\uD83D\uDEB4' },
            { name: 'Swimming',             cal: '400-500', icon: '\uD83C\uDFCA' },
            { name: 'Boxing / Kickboxing',  cal: '500-700', icon: '\uD83E\uDD4A' },
            { name: 'Yoga / Stretching',    cal: '150-250', icon: '\uD83E\uDDD8' },
            { name: 'CrossFit',             cal: '500-800', icon: '\uD83D\uDD25' },
        ];
        const gymFoods = [
            { name: 'Chicken Breast',       cal: 165, p: 31,   c: 0,    f: 3.6, best: 'Protein source' },
            { name: 'Eggs (whole)',          cal: 155, p: 13,   c: 1.1,  f: 11,  best: 'All-round nutrition' },
            { name: 'Oats',                  cal: 389, p: 17,   c: 66,   f: 7,   best: 'Pre-workout carbs' },
            { name: 'Brown Rice',            cal: 216, p: 5,    c: 45,   f: 1.8, best: 'Sustained energy' },
            { name: 'Sweet Potato',          cal: 86,  p: 1.6,  c: 20,   f: 0.1, best: 'Clean carbs' },
            { name: 'Salmon',                cal: 208, p: 20,   c: 0,    f: 13,  best: 'Omega-3 + protein' },
            { name: 'Greek Yogurt',          cal: 59,  p: 10,   c: 3.6,  f: 0.4, best: 'Post-workout snack' },
            { name: 'Banana',                cal: 89,  p: 1.1,  c: 23,   f: 0.3, best: 'Quick energy' },
            { name: 'Almonds',               cal: 579, p: 21,   c: 22,   f: 50,  best: 'Healthy fats' },
            { name: 'Whey Protein (scoop)',  cal: 120, p: 25,   c: 3,    f: 2,   best: 'Post-workout protein' },
        ];
        const workoutGrid = document.getElementById('workout-calories-grid');
        if (workoutGrid) workoutGrid.innerHTML = workoutCalories.map(w =>
            '<div class="flex items-center justify-between bg-gray-700/50 rounded-lg px-4 py-3">'
            + '<span class="text-white text-sm">' + w.icon + ' ' + w.name + '</span>'
            + '<span class="text-emerald-400 font-bold text-sm">' + w.cal + ' kcal</span></div>'
        ).join('');
        const gymFoodsTable = document.getElementById('gym-foods-table');
        if (gymFoodsTable) gymFoodsTable.innerHTML = gymFoods.map(f =>
            '<tr class="hover:bg-gray-700/30 transition-colors">'
            + '<td class="py-2 pr-4 text-white font-medium">' + f.name + '</td>'
            + '<td class="py-2 px-2 text-center text-emerald-400 font-semibold">' + f.cal + '</td>'
            + '<td class="py-2 px-2 text-center text-gray-300">' + f.p + 'g</td>'
            + '<td class="py-2 px-2 text-center text-gray-300">' + f.c + 'g</td>'
            + '<td class="py-2 px-2 text-center text-gray-300">' + f.f + 'g</td>'
            + '<td class="py-2 pl-4 text-gray-400 text-xs">' + f.best + '</td></tr>'
        ).join('');
    }

    // VIDEOS PAGE LOGIC
    if (document.getElementById('page-videos')) {
        const videoGrid = document.getElementById('video-grid');
        const videoTabs = document.querySelectorAll('.video-tab-btn');
        const videoFiltersContainer = document.getElementById('video-filters');
        const filterTrainer = document.getElementById('filter-trainer');
        const filterDuration = document.getElementById('filter-duration');
        const filterMuscle = document.getElementById('filter-muscle');
        
        let currentVideoTab = 'all';

        function populateFilters() {
            const trainers = [...new Set(videoData.map(v => v.trainer))];
            const muscleGroups = [...new Set(videoData.map(v => v.muscleGroup))];
            trainers.forEach(t => filterTrainer.innerHTML += `<option value="${t}">${t}</option>`);
            muscleGroups.forEach(m => filterMuscle.innerHTML += `<option value="${m}">${m}</option>`);
        }

        function renderVideos() {
            videoGrid.innerHTML = '';
            const trainer = filterTrainer.value;
            const duration = filterDuration.value;
            const muscle = filterMuscle.value;
            let filteredVideos = videoData;

            if (currentVideoTab === 'watchlist') {
                filteredVideos = filteredVideos.filter(v => v.isSaved);
                videoFiltersContainer.classList.add('hidden');
            } else {
                videoFiltersContainer.classList.remove('hidden');
            }

            if (trainer !== 'all') { filteredVideos = filteredVideos.filter(v => v.trainer === trainer); }
            if (muscle !== 'all') { filteredVideos = filteredVideos.filter(v => v.muscleGroup === muscle); }
            if (duration !== 'all') { filteredVideos = filteredVideos.filter(v => { if (duration === 'short') return v.duration < 15; if (duration === 'medium') return v.duration >= 15 && v.duration <= 30; if (duration === 'long') return v.duration > 30; }); }

            if(filteredVideos.length === 0) {
                videoGrid.innerHTML = `<p class="text-gray-400 col-span-full text-center">No videos match your criteria.</p>`;
                return;
            }

            filteredVideos.forEach(video => {
                const videoEl = document.createElement('div');
                videoEl.className = 'bg-gray-800 rounded-lg overflow-hidden group';
                videoEl.innerHTML = `<div class="relative"><img src="${video.image}" alt="${video.title}"><div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"><svg class="w-16 h-16 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg></div><button class="save-video-btn absolute top-3 right-3 w-8 h-8 rounded-full bg-black/50 flex items-center justify-center" data-id="${video.id}"><svg class="w-5 h-5 ${video.isSaved ? 'text-emerald-400' : 'text-white'}" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-3.13L5 18V4z"/></svg></button></div><div class="p-6"><h4 class="font-bold text-xl text-white">${video.title}</h4><p class="text-gray-400 text-sm mt-2">By ${video.trainer}</p></div>`;
                videoGrid.appendChild(videoEl);
            });

            document.querySelectorAll('.save-video-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const videoId = parseInt(btn.dataset.id);
                    const video = videoData.find(v => v.id === videoId);
                    video.isSaved = !video.isSaved;
                    renderVideos();
                });
            });
        }

        videoTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                videoTabs.forEach(t => { t.classList.remove('border-emerald-400', 'text-emerald-400'); t.classList.add('border-transparent', 'text-gray-400'); });
                tab.classList.add('border-emerald-400', 'text-emerald-400');
                tab.classList.remove('border-transparent', 'text-gray-400');
                currentVideoTab = tab.dataset.tab;
                renderVideos();
            });
        });

        [filterTrainer, filterDuration, filterMuscle].forEach(filter => { filter.addEventListener('change', renderVideos); });
        
        populateFilters();
        renderVideos();
    }
});


