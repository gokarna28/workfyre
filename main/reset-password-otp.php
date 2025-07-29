<?php 
include_once('public-templates/public-header.php');
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/connection.php');

// Get email and OTP from URL parameters
$email = $_GET['email'] ?? '';
$otp = $_GET['otp'] ?? '';

if (empty($email) || empty($otp)) {
    header('Location: ' . HOMEPAGE_URL . '/main/send-otp.php');
    exit;
}
?>

<section class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="<?php echo HOMEPAGE_URL ?>/assets/images/logo.png" alt="Workfyre" class="h-12 w-auto" />
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h1>
            <p class="text-gray-600">Create a new password for your account</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="mb-6" id="successMessage"></div>
            
            <form id="resetPasswordForm" class="space-y-6">
                <input type="hidden" id="userEmail" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" id="userOtp" value="<?php echo htmlspecialchars($otp); ?>">
                
                <!-- Email Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           value="<?php echo htmlspecialchars($email); ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600" 
                           readonly />
                </div>

                <!-- New Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" 
                               id="newPassword" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               placeholder="Enter your new password" required />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="newPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span id="passwordMessage" class="text-sm text-red-500 mt-1"></span>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" 
                               id="confirmPassword" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                               placeholder="Confirm your new password" required />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="confirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span id="confirmPasswordMessage" class="text-sm text-red-500 mt-1"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="resetPasswordBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    Reset Password
                </button>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-gray-600">
                        Remember your password? 
                        <a href="<?php echo HOMEPAGE_URL ?>/main/login.php" class="text-blue-600 hover:text-blue-700 font-medium">Sign in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#resetPasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#userEmail').val();
        const otp = $('#userOtp').val();
        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();
        
        // Clear previous messages
        $('#passwordMessage, #confirmPasswordMessage').html('');
        
        // Basic validation
        if (newPassword === '' || confirmPassword === '') {
            $('#successMessage').html(`
                <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">All fields are required</div>
            `);
            return;
        }
        
        // Check password length
        if (newPassword.length < 6) {
            $('#passwordMessage').html('Password must be at least 6 characters long.');
            return;
        }
        
        // Check if passwords match
        if (newPassword !== confirmPassword) {
            $('#confirmPasswordMessage').html('Passwords do not match.');
            return;
        }
        
        // Disable button and show loading
        $('#resetPasswordBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Resetting...');
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-register-login.php',
            data: {
                action: 'reset_password_otp',
                email: email,
                otp: otp,
                new_password: newPassword
            },
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    $('#successMessage').html(`
                        <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl py-4 px-6 text-sm font-medium">${response.message}</div>
                    `);
                    $('#resetPasswordForm')[0].reset();
                    
                    // Redirect to login page after 3 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo HOMEPAGE_URL ?>/main/login.php';
                    }, 3000);
                } else {
                    $('#successMessage').html(`
                        <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">${response.message}</div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
                $('#successMessage').html(`
                    <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">An error occurred. Please try again.</div>
                `);
            },
            complete: function() {
                // Re-enable button
                $('#resetPasswordBtn').prop('disabled', false).html('Reset Password');
            }
        });
    });
});
</script>

<?php include_once('public-templates/public-footer.php'); ?> 