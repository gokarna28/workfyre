<?php 
include_once('public-templates/public-header.php');
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/connection.php');

// Get email from URL parameter
$email = $_GET['email'] ?? '';
if (empty($email)) {
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verify OTP</h1>
            <p class="text-gray-600">Enter the 6-digit OTP sent to your email</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="mb-6" id="successMessage"></div>
            
            <form id="verifyOtpForm" class="space-y-6">
                <input type="hidden" id="userEmail" value="<?php echo htmlspecialchars($email); ?>">
                
                <!-- Email Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           value="<?php echo htmlspecialchars($email); ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 text-gray-600" 
                           readonly />
                </div>

                <!-- OTP Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OTP Code</label>
                    <input type="text" 
                           id="otpInput" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-center text-2xl font-mono tracking-widest" 
                           name="otp" placeholder="000000" maxlength="6" required />
                    <span id="otpMessage" class="text-sm text-red-500 mt-1"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="verifyOtpBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    Verify OTP
                </button>

                <!-- Resend Link -->
                <div class="text-center">
                    <p class="text-gray-600">
                        Didn't receive OTP? 
                        <a href="<?php echo HOMEPAGE_URL ?>/main/send-otp.php" class="text-blue-600 hover:text-blue-700 font-medium">Resend</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Auto-focus on OTP input
    $('#otpInput').focus();
    
    // Allow only numbers in OTP input
    $('#otpInput').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    $('#verifyOtpForm').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#userEmail').val();
        const otp = $('#otpInput').val().trim();
        
        // Basic validation
        if (otp === '') {
            $('#successMessage').html(`
                <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">OTP is required</div>
            `);
            return;
        }
        
        if (otp.length !== 6) {
            $('#successMessage').html(`
                <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">Please enter a 6-digit OTP</div>
            `);
            return;
        }
        
        // Disable button and show loading
        $('#verifyOtpBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...');
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-register-login.php',
            data: {
                action: 'verify_otp_reset',
                email: email,
                otp: otp
            },
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    $('#successMessage').html(`
                        <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl py-4 px-6 text-sm font-medium">${response.message}</div>
                    `);
                    
                    // Redirect to password reset page after 2 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo HOMEPAGE_URL ?>/main/reset-password-otp.php?email=' + encodeURIComponent(email) + '&otp=' + otp;
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
                $('#verifyOtpBtn').prop('disabled', false).html('Verify OTP');
            }
        });
    });
});
</script>

<?php include_once('public-templates/public-footer.php'); ?>