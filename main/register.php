<?php

include_once('public-templates/public-header.php');

?>
<section class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="<?php echo HOMEPAGE_URL ?>/assets/images/logo.png" alt="Workfyre" class="h-12 w-auto" />
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h1>
            <p class="text-gray-600">Join Workfyre and manage your projects efficiently</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="mb-6" id="successMessage"></div>
            
            <form id="userRegisterForm" class="space-y-6">
                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               name="firstname" placeholder="John" required />
                        <span id="firstnameMessage" class="text-sm text-red-500 mt-1"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               name="lastname" placeholder="Doe" required />
                        <span id="lastnameMessage" class="text-sm text-red-500 mt-1"></span>
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                           name="email" placeholder="john@example.com" required />
                    <span id="emailMessage" class="text-sm text-red-500 mt-1"></span>
                </div>

                <!-- Password Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               name="password" placeholder="Create a strong password" required />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               name="confirm_password" placeholder="Confirm your password" required />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="confirm_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span id="confirmPasswordMessage" class="text-sm text-red-500 mt-1"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    Create Account
                </button>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-gray-600">
                        Already have an account? 
                        <a href="<?php echo HOMEPAGE_URL ?>/main/login.php" class="text-blue-600 hover:text-blue-700 font-medium">Sign in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
<?php include_once('public-templates/public-footer.php'); ?>