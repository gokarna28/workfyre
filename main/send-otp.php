<?php 
include_once('public-templates/public-header.php');
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/connection.php');
?>

<section class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="<?php echo HOMEPAGE_URL ?>/assets/images/logo.png" alt="Workfyre" class="h-12 w-auto" />
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h1>
            <p class="text-gray-600">Enter your email to receive a 6-digit OTP</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="mb-6" id="successMessage"></div>
            
            <form id="sendOtpForm" class="space-y-6">
                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           id="resetEmail" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                           name="email" placeholder="john@example.com" required />
                    <span id="emailMessage" class="text-sm text-red-500 mt-1"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="sendOtpBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    Send OTP
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
    $('#sendOtpForm').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#resetEmail').val().trim();
        
        // Basic validation
        if (email === '') {
            $('#successMessage').html(`
                <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">Email is required</div>
            `);
            return;
        }
        
        // Validate email format
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            $('#emailMessage').html('Please enter a valid email address.');
            return;
        }
        
        // Disable button and show loading
        $('#sendOtpBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Sending...');
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-register-login.php',
            data: {
                action: 'send_otp_reset',
                email: email
            },
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    $('#successMessage').html(`
                        <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl py-4 px-6 text-sm font-medium">${response.message}</div>
                    `);
                    $('#sendOtpForm')[0].reset();
                    
                    // Redirect to verify OTP page after 2 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo HOMEPAGE_URL ?>/main/verify-otp.php?email=' + encodeURIComponent(email);
                    }, 2000);
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
                $('#sendOtpBtn').prop('disabled', false).html('Send OTP');
            }
        });
    });
});
</script>

<?php include_once('public-templates/public-footer.php'); ?>