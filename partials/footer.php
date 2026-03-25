    </main>

    <!-- Login Modal -->
    <div id="login-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4">
        <div class="modal-glass w-full max-w-md p-8 rounded-lg relative">
            <button id="close-login-modal" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">&times;</button>
            <h2 class="text-2xl font-bold text-white text-center mb-6">Member Login</h2>
            <div id="login-error" class="hidden mb-4 p-3 bg-red-500/20 border border-red-500 rounded text-red-400 text-sm text-center"></div>
            <div id="login-success" class="hidden mb-4 p-3 bg-emerald-500/20 border border-emerald-500 rounded text-emerald-400 text-sm text-center"></div>
            <form id="login-form" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-400">Email</label>
                    <input type="email" name="email" placeholder="Enter your email" id="email" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-400">Password</label>
                    <input type="password" name="password" placeholder="Enter your password" id="password" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600 text-white" required>
                </div>
                <button type="submit" id="login-submit-btn" class="w-full bg-emerald-500 text-gray-900 font-bold py-3 rounded-lg cta-button">Login</button>
                <div>
                    <label class="text-gray-400 text-sm">
                        <br>
                     <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>
                <p class="text-center text-sm text-gray-400 mt-4">
                    Don't have an account? <a id="show-register-modal" class="font-semibold text-emerald-400 hover:underline cursor-pointer">Register here</a>
                </p>
            </form>
        </div>
    </div>

    <!-- Register Modal -->
        <div id="register-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4">
            <div class="modal-glass w-full max-w-md p-8 rounded-lg relative">
                <button id="close-register-modal" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">&times;</button>
                <h2 class="text-2xl font-bold text-white text-center mb-6">Create an Account</h2>
                <form action="auth/register.php" method="POST">
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-400">Username</label>
                        <input type="text" name="username" placeholder="Username" id="username" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600">
                    </div>
                    <div class="mb-4">
                        <label for="register-email" class="block text-sm font-medium text-gray-400">Email</label>
                        <input type="email" name="email" placeholder="Enter your email" id="register-email" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600">
                    </div>
                    <div class="mb-4">
                        <label for="register-password" class="block text-sm font-medium text-gray-400">Password</label>
                        <input type="password" name="password" placeholder="Enter a strong password"" id="register-password" class="w-full bg-gray-700 p-2 rounded mt-1 border border-gray-600">
                    </div>
                    <button type="submit" class="w-full bg-emerald-500 text-gray-900 font-bold py-3 rounded-lg cta-button">Create Account</button>
                    <p class="text-center text-sm text-gray-400 mt-4">
                        Already have an account? <a href="#" id="show-login-modal" class="font-semibold text-emerald-400 hover:underline">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    
    <!-- AI Workout Finder Modal -->
    <div id="workout-finder-modal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
        <div id="quiz-container" class="bg-gray-800 w-full max-w-2xl p-8 rounded-lg relative transition-all duration-300">
             <!-- Quiz content will be injected here -->
        </div>
    </div>

    <!-- Class Details Modal -->
    <div id="class-details-modal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 w-full max-w-lg p-8 rounded-lg relative border border-gray-700">
            <button id="close-class-modal" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">&times;</button>
            <div id="class-modal-content">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>
    
    <footer class="bg-gray-900 py-8 border-t border-gray-800">
        <div class="container mx-auto px-6 text-center text-gray-400">
            &copy; 2025 PowerPulse Fitness. All Rights Reserved.
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>

