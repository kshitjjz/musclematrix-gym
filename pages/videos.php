<!-- PAGE: Trainer Tips & Video Library -->
<div id="page-videos">
        <section class="py-20 bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h2 class="text-4xl font-bold text-white mb-2" style="position: relative; z-index: 20; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Trainer Tips & <span class="text-red-500">Video Library</span></h2>
                <p class="text-gray-300 mb-12 font-medium" style="position: relative; z-index: 20; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Learn from our expert trainers with technique videos and motivational content.</p>
            </div>

            <!-- Filters & Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-700 mb-6">
                    <nav class="flex space-x-6" id="video-tabs">
                        <button class="video-tab-btn py-2 px-1 border-b-2 font-semibold border-emerald-400 text-emerald-400" data-tab="all">All Videos</button>
                        <button class="video-tab-btn py-2 px-1 border-b-2 font-semibold border-transparent text-gray-400 hover:text-white" data-tab="watchlist">My Watchlist</button>
                    </nav>
                </div>
                <div id="video-filters" class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <select id="filter-trainer" class="w-full bg-gray-800 p-3 rounded border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="all">All Trainers</option>
                        <!-- Options populated by JS -->
                    </select>
                    <select id="filter-duration" class="w-full bg-gray-800 p-3 rounded border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="all">All Durations</option>
                        <option value="short">Under 15 min</option>
                        <option value="medium">15-30 min</option>
                        <option value="long">Over 30 min</option>
                    </select>
                    <select id="filter-muscle" class="w-full bg-gray-800 p-3 rounded border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="all">All Muscle Groups</option>
                        <!-- Options populated by JS -->
                    </select>
                </div>
            </div>

                <div id="video-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Videos will be populated by JS -->
            </div>
        </div>
    </section>
</div>
