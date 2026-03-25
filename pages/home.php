<!-- PAGE: Home -->
<div id="page-home">
    <!-- Hero Section -->
    <section class="relative h-[calc(100vh-5rem)] overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="assets\img\hanuman.png" alt="Gym" class=" w-full h-full object-cover ">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        
        <!-- Clickable PNG Image -->
        <div class="absolute inset-x-50 bottom-5 flex justify-center z-10">
            <img src="assets\img\prarambh.png" alt="Start Now" class="w-75 md:w-105 hover:scale-85 transition-transform cursor-pointer open-login-modal">
        </div>
    </section>

    <!-- Dynamic Stats Section -->
    <section class="glass-card mx-6 my-8 rounded-lg" id="stats-section">
        <div class="container mx-auto px-6 py-12 flex justify-around text-center">
            <div class="stat">
                <h2 id="members-count" class="text-4xl md:text-5xl font-bold text-red-400">0</h2>
                <p class="text-sm md:text-base text-gray-300">Members Trained</p>
            </div>
            <div class="stat">
                <h2 id="hours-count" class="text-4xl md:text-5xl font-bold text-red-400">0</h2>
                <p class="text-sm md:text-base text-gray-300">Hours of Training</p>
            </div>
            <div class="stat">
                <h2 id="calories-count" class="text-4xl md:text-5xl font-bold text-red-400">0</h2>
                <p class="text-sm md:text-base text-gray-300">Calories Burned</p>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="py-20" style="background: rgba(15, 15, 15, 0.9); backdrop-filter: blur(10px);">
            <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-10">Why Muscle<span class="text-red-500">Matrix</span>?</h2>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="flex flex-col items-center">
                    <div class="glass-card rounded-full p-5 mb-4 glow-emerald">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.657 7.343A8 8 0 0117.657 18.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1014.12 11.88l-4.242 4.242z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Expert Trainers</h3>
                    <p class="text-gray-400">Our certified trainers are dedicated to your success, providing personalized guidance and motivation.</p>
                </div>
                    <div class="flex flex-col items-center">
                    <div class="glass-card rounded-full p-5 mb-4 glow-emerald">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Modern Facilities</h3>
                    <p class="text-gray-400">Train with state-of-the-art equipment in a clean, inspiring, and supportive environment.</p>
                </div>
                    <div class="flex flex-col items-center">
                    <div class="glass-card rounded-full p-5 mb-4 glow-emerald">
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Community Focused</h3>
                    <p class="text-gray-400">Join a vibrant community of fitness enthusiasts who support and inspire each other every day.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call-to-Action Banner -->
    <section class="py-16" style="background: rgba(20, 20, 20, 0.85); backdrop-filter: blur(10px);">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Transform?</h2>
            <button class="bg-gradient-to-r from-red-600 to-red-700 text-white font-bold py-3 px-8 rounded-lg text-lg uppercase hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-red-500/50 open-login-modal">Start Your Free Session</button>
        </div>
    </section>

    <!-- Your PowerPulse Pass (Initially Hidden) -->
    <section id="powerpulse-pass-section" class="hidden py-20 bg-gray-900">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-white mb-2">Welcome to the Club!</h2>
            <p class="text-gray-400 mb-10">Here is your personalized pass. Your journey starts now.</p>
            
            <div class="max-w-2xl mx-auto bg-gray-800 rounded-2xl p-8 border border-emerald-400 shadow-2xl shadow-emerald-500/10" style="background: radial-gradient(circle, #1f2937, #111827);">
                <div class="flex justify-between items-start">
                        <h3 class="text-2xl font-bold text-white">Muscle<span class="text-red-500">Matrix</span> Pass</h3>
                        <span class="text-xs font-semibold uppercase bg-red-500/20 text-red-300 px-3 py-1 rounded-full">Strength Builder</span>
                </div>
                <div class="my-8 text-center">
                    <img src="https://placehold.co/150x150/34D399/111827?text=PP" alt="User Avatar" class="w-32 h-32 rounded-full mb-4 border-4 border-gray-700 mx-auto">
                    <h4 class="text-3xl font-bold text-white">Welcome, Member!</h4>
                    <p class="text-gray-400">Your recommended focus is <span class="font-bold text-white">Muscle Gain</span>.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <a href="index.php?page=schedule" class="bg-gray-700 text-white font-bold py-3 px-4 rounded-lg cta-button">Book First Class</a>
                    <a href="index.php?page=tracker" class="bg-emerald-500 text-gray-900 font-bold py-3 px-4 rounded-lg cta-button">Go to My Tracker</a>
                </div>
            </div>
        </div>
    </section>
</div>
