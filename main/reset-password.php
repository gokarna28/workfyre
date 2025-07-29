<?php 
include_once('public-templates/public-header.php');
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/functions.php');

// Get token from URL
$token = $_GET['token'] ?? '';

// Debug: Check if token exists
if (empty($token)) {
    $debugMessage = "No token provided in URL";
} else {
    $debugMessage = "Token received: " . substr($token, 0, 10) . "...";
}

// Verify token
$tokenResult = verifyPasswordResetToken($token);
$isValidToken = $tokenResult['status'] === 'success';

// Debug: Log the result
error_log("Token verification result: " . print_r($tokenResult, true));
?>

<section class="w-full h-full flex items-center justify-center">
    <div class="w-1/2 mt-20 bg-white text-black px-10 py-20 rounded-3xl">
        <div class="items-center justify-center flex flex-col mb-10">
            <h2>Workfyre</h2>
            <h1 class="text-3xl font-medium">Reset your password</h1>
            <p>Enter your new password below</p>
        </div>
        
        <!-- Debug Section - Remove this after fixing the issue -->
        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
            <h3 class="font-bold mb-2">Debug Info:</h3>
            <p><strong>Token:</strong> <?php echo htmlspecialchars($token ? substr($token, 0, 20) . '...' : 'No token'); ?></p>
            <p><strong>Token Status:</strong> <?php echo htmlspecialchars($tokenResult['status']); ?></p>
            <p><strong>Message:</strong> <?php echo htmlspecialchars($tokenResult['message'] ?? 'No message'); ?></p>
            <p><strong>Is Valid:</strong> <?php echo $isValidToken ? 'Yes' : 'No'; ?></p>
        </div>
        
        <div class="mb-2" id="successMessage"></div>
        
        <?php if ($isValidToken): ?>
            <form id="resetPasswordForm" class="items-center justify-center">
                <input type="hidden" id="resetToken" value="<?php echo htmlspecialchars($token); ?>">
                <div>
                    <div class="flex flex-col mb-5">
                        <label class="mb-2">New Password:</label>
                        <div class="relative">
                            <input type="password" id="newPassword" class="border py-2 px-4 text-lg rounded-2xl border-slate-300 w-full pr-12"
                                name="new_password" placeholder="Enter your new password" required />
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 toggle-password" data-target="newPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span id="passwordMessage" class="text-sm font-light text-red-600"></span>
                    </div>
                    <div class="flex flex-col mb-5">
                        <label class="mb-2">Confirm Password:</label>
                        <div class="relative">
                            <input type="password" id="confirmPassword" class="border py-2 px-4 text-lg rounded-2xl border-slate-300 w-full pr-12"
                                name="confirm_password" placeholder="Confirm your new password" required />
                            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 toggle-password" data-target="confirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span id="confirmPasswordMessage" class="text-sm font-light text-red-600"></span>
                    </div>
                </div>
                <div>
                    <div class="flex flex-col justify-center gap-5 mb-5">
                        <button type="submit" id="resetPasswordBtn"
                            class="hover:bg-stone-900 hover:text-white text-xl font-medium border px-4 py-2 rounded-full">
                            Reset Password
                        </button>
                    </div>
                    <div>
                        <p>Remember your password? <a href="<?php echo HOMEPAGE_URL ?>/main/login.php" class="text-blue-400">Login</a></p>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center">
                <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-4 px-6 mb-4">
                    <h3 class="text-lg font-semibold mb-2">Invalid or Expired Link</h3>
                    <p>The password reset link is invalid or has expired. Please request a new password reset link.</p>
                </div>
                <a href="<?php echo HOMEPAGE_URL ?>/main/send-otp.php" 
                   class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Request New Reset Link
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($isValidToken): ?>
<script>
$(document).ready(function() {
    $('#resetPasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        const token = $('#resetToken').val();
        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();
        
        // Clear previous messages
        $('#passwordMessage, #confirmPasswordMessage').html('');
        
        // Basic validation
        if (newPassword === '' || confirmPassword === '') {
            $('#successMessage').html(`
                <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">All fields are required</div>
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
        $('#resetPasswordBtn').prop('disabled', true).text('Resetting...');
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-register-login.php',
            data: {
                action: 'reset_password',
                token: token,
                new_password: newPassword
            },
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    $('#successMessage').html(`
                        <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                    `);
                    $('#resetPasswordForm')[0].reset();
                    
                    // Redirect to login page after 3 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo HOMEPAGE_URL ?>/main/login.php';
                    }, 3000);
                } else {
                    $('#successMessage').html(`
                        <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
                $('#successMessage').html(`
                    <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">An error occurred. Please try again.</div>
                `);
            },
            complete: function() {
                // Re-enable button
                $('#resetPasswordBtn').prop('disabled', false).text('Reset Password');
            }
        });
    });
});
</script>
<?php endif; ?>

<?php include_once('public-templates/public-footer.php'); ?> 