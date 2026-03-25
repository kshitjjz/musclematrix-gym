<!-- PAGE: Interactive Class Schedule -->
<div id="page-schedule">
        <section class="py-20 bg-gray-900">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold text-white mb-2" style="position: relative; z-index: 20; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Interactive <span class="text-red-500">Class Schedule</span></h2>
            <p class="text-gray-300 mb-10 font-medium" style="position: relative; z-index: 20; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Book your favorite classes with our real-time scheduling system.</p>
            <div class="mb-4 flex flex-wrap justify-center gap-2">
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold active" data-day="monday">Monday</button>
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold" data-day="tuesday">Tuesday</button>
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold" data-day="wednesday">Wednesday</button>
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold" data-day="thursday">Thursday</button>
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold" data-day="friday">Friday</button>
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold" data-day="saturday">Saturday</button>
                <button class="schedule-day-btn px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white font-semibold" data-day="sunday">Sunday</button>
            </div>
                <div class="mb-8 flex flex-wrap justify-center gap-2" id="class-filter-tags">
                <button class="class-filter-btn px-3 py-1 text-sm rounded-full bg-gray-700 text-white font-semibold active" data-filter="all">All</button>
                <button class="class-filter-btn px-3 py-1 text-sm rounded-full bg-gray-700 text-white font-semibold" data-filter="hiit">HIIT</button>
                <button class="class-filter-btn px-3 py-1 text-sm rounded-full bg-gray-700 text-white font-semibold" data-filter="yoga">Yoga</button>
                <button class="class-filter-btn px-3 py-1 text-sm rounded-full bg-gray-700 text-white font-semibold" data-filter="strength">Strength</button>
                <button class="class-filter-btn px-3 py-1 text-sm rounded-full bg-gray-700 text-white font-semibold" data-filter="cardio">Cardio</button>
                </div>
            <div id="class-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                <!-- Classes will be dynamically inserted here -->
            </div>
            <div class="mt-12 bg-gray-800/50 border border-gray-700 max-w-lg mx-auto p-6 rounded-lg">
                <h3 class="text-xl font-bold text-white">Sync Your Schedule</h3>
                <p class="text-gray-400 text-sm mt-1 mb-4">Add booked classes to your personal calendar.</p>
                <div class="flex justify-center gap-4">
                    <button class="bg-red-600 text-white font-semibold py-2 px-5 rounded-lg cta-button">Google Calendar</button>
                    <button class="bg-red text-white font-semibold py-2 px-5 rounded-lg cta-button">Apple Calendar</button>
                </div>
            </div>
        </div>
    </section>
</div>
