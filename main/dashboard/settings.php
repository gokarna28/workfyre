<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); 

// Get current user data
$currentUser = getCurrentUser();
$userDetails = getUsersDetailsByUser_id($currentUser['id']);
?>

<section class="py-25 pl-85 w-full pr-10 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Account Settings</h1>
            <p class="text-gray-600">Manage your profile information and account security</p>
        </div>

        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 via-gray-600 to-gray-800 h-32 relative">
                <div class="absolute -bottom-12 left-8">
                    <div class="relative">
                        <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/80'; ?>"
                            class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-contain bg-gray-100" />
                        <div class="absolute -bottom-1 -right-1 bg-green-500 w-6 h-6 rounded-full border-2 border-white"></div>
                    </div>
                </div>
            </div>
            <div class="pt-16 pb-6 px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-1">
                    <?php echo htmlspecialchars($userDetails['firstname'] ?? '') . ' ' . htmlspecialchars($userDetails['lastname'] ?? ''); ?>
                </h2>
                <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($userDetails['email'] ?? ''); ?></p>
                <p class="text-sm text-gray-500">Member since <?php echo date('F j, Y', strtotime($userDetails['created_at'] ?? 'now')); ?></p>
            </div>
        </div>

        <!-- Settings Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-8" aria-label="Tabs">
                    <button data-tab="tab1" class="tab-btn py-4 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm">
                        <i class="fa-solid fa-user mr-2"></i>Profile Information
                    </button>
                    <button data-tab="tab3" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                        <i class="fa-solid fa-shield-alt mr-2"></i>Security
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div id="tab-contents" class="p-8">
                <!-- Profile Tab -->
                <div id="tab1" class="tab-content">
                    <form id="profileForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Profile Picture Section -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h3>
                                    <div class="flex items-center space-x-6">
                                        <div class="relative">
                                            <img id="preview-img" src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/40'; ?>"
                                                class="w-20 h-20 rounded-full object-contain bg-gray-100 border-4 border-white shadow-md" />
                                            <div class="absolute -bottom-1 -right-1 bg-green-500 w-5 h-5 rounded-full border-2 border-white"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex space-x-3">
                                                <label class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-pointer hover:bg-blue-700 transition-colors">
                                                    <i class="fa-solid fa-upload mr-2"></i>Upload Photo
                                                    <input type="file" class="hidden" id="img-upload" name="profile_picture" accept="image/*">
                                                </label>
                                                <button type="button" id="deletePhoto" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                                                    <i class="fa-solid fa-trash mr-2"></i>Remove
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF, WebP up to 5MB</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                            <input type="text" name="firstname" value="<?php echo htmlspecialchars($userDetails['firstname'] ?? ''); ?>"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                            <input type="text" name="lastname" value="<?php echo htmlspecialchars($userDetails['lastname'] ?? ''); ?>"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" value="<?php echo htmlspecialchars($userDetails['email'] ?? ''); ?>" readonly
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                                        <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                        <input type="text" value="<?php echo ucfirst($userDetails['user_role'] ?? 'user'); ?>" readonly
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Bio Section -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">About You</h3>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                        <textarea name="bio" rows="6" placeholder="Tell us about yourself..."
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"><?php echo htmlspecialchars($userDetails['bio'] ?? ''); ?></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Share a brief description about yourself</p>
                                    </div>
                                </div>

                                <!-- Account Information -->
                                <div class="bg-blue-50 rounded-xl p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Member Since</span>
                                            <span class="text-sm font-medium text-gray-900"><?php echo date('F j, Y', strtotime($userDetails['created_at'] ?? 'now')); ?></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Account Status</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fa-solid fa-check-circle mr-1"></i>Active
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Last Login</span>
                                            <span class="text-sm font-medium text-gray-900">Today</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200 mt-8">
                            <button type="button" id="cancelProfile" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="saveProfile" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <i class="fa-solid fa-save mr-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Security Tab -->
                <div id="tab3" class="tab-content hidden">
                    <div class="max-w-2xl">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Security Settings</h3>
                        <p class="text-gray-600 mb-8">Change your password and manage your account security.</p>
                        
                        <div class="mb-6" id="passwordChangeMessage"></div>
                        
                        <form id="changePasswordForm" class="space-y-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                                <div class="flex">
                                    <i class="fa-solid fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                                    <div>
                                        <h4 class="text-sm font-medium text-yellow-800">Password Security</h4>
                                        <p class="text-sm text-yellow-700 mt-1">Choose a strong password that you haven't used elsewhere.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                    <div class="relative">
                                        <input type="password" id="currentPassword" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required />
                                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePassword('currentPassword')">
                                            <i class="fa-solid fa-eye" id="currentPasswordIcon"></i>
                                        </button>
                                    </div>
                                    <span id="currentPasswordMessage" class="text-sm text-red-500"></span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <div class="relative">
                                        <input type="password" id="newPassword" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required />
                                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePassword('newPassword')">
                                            <i class="fa-solid fa-eye" id="newPasswordIcon"></i>
                                        </button>
                                    </div>
                                    <span id="newPasswordMessage" class="text-sm text-red-500"></span>
                                    <p class="text-xs text-gray-500 mt-1">Must be at least 6 characters long</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" id="confirmNewPassword" class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required />
                                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePassword('confirmNewPassword')">
                                            <i class="fa-solid fa-eye" id="confirmNewPasswordIcon"></i>
                                        </button>
                                    </div>
                                    <span id="confirmNewPasswordMessage" class="text-sm text-red-500"></span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="button" id="cancelPasswordChange" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" id="savePasswordChange" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    <i class="fa-solid fa-key mr-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Tab functionality
    $('.tab-btn').click(function() {
        const tabId = $(this).data('tab');
        
        // Hide all tab contents
        $('.tab-content').addClass('hidden');
        $('#' + tabId).removeClass('hidden');
        
        // Update active tab button
        $('.tab-btn').removeClass('border-blue-500 text-blue-600').addClass('border-transparent text-gray-500');
        $(this).removeClass('border-transparent text-gray-500').addClass('border-blue-500 text-blue-600');
    });
    
    // Profile picture preview
    $('#img-upload').change(function() {
        const file = this.files[0];
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                this.value = '';
                return;
            }
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, GIF, WebP)');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Delete profile picture
    $('#deletePhoto').click(function() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
            $('#preview-img').attr('src', 'https://i.pravatar.cc/40');
            $('#img-upload').val('');
        }
    });
    
    // Profile form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        // Disable button and show loading
        $('#saveProfile').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Saving...');
        
        // Create FormData object to handle file uploads
        const formData = new FormData(this);
        formData.append('action', 'update_profile');
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-settings.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                
                // Parse response if it's a string
                let responseData = response;
                if (typeof response === 'string') {
                    try {
                        responseData = JSON.parse(response);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        responseData = { status: 'error', message: 'Invalid response format' };
                    }
                }
                
                if (responseData.status === 'success') {
                    // Show success message
                    const successMessage = `
                        <div id="messageAlert" class="mb-6 bg-green-50 text-green-800 border border-green-200 rounded-xl py-4 px-6 text-sm font-medium">
                            ${responseData.message}
                        </div>
                    `;
                    
                    // Remove existing message and add new one
                    $('.mb-6[id="messageAlert"]').remove();
                    $('#profileForm').before(successMessage);
                    
                    // Update profile picture if new one was uploaded
                    if (responseData.user_data && responseData.user_data.profile_image) {
                        $('#preview-img').attr('src', responseData.user_data.profile_image);
                        $('.bg-gradient-to-r img').attr('src', responseData.user_data.profile_image);
                    }
                    
                    // Update user name in header if changed
                    if (responseData.user_data) {
                        const fullName = (responseData.user_data.firstname || '') + ' ' + (responseData.user_data.lastname || '');
                        $('.bg-gradient-to-r + div h2').text(fullName.trim());
                    }
                    
                    // Auto-hide message after 5 seconds
                    setTimeout(function() {
                        $('#messageAlert').fadeOut('slow');
                    }, 5000);
                    
                } else {
                    // Show error message
                    const errorMessage = `
                        <div id="messageAlert" class="mb-6 bg-red-50 text-red-800 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">
                            ${responseData.message || 'An error occurred'}
                        </div>
                    `;
                    
                    // Remove existing message and add new one
                    $('.mb-6[id="messageAlert"]').remove();
                    $('#profileForm').before(errorMessage);
                    
                    // Auto-hide message after 5 seconds
                    setTimeout(function() {
                        $('#messageAlert').fadeOut('slow');
                    }, 5000);
                }
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
                const errorMessage = `
                    <div id="messageAlert" class="mb-6 bg-red-50 text-red-800 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">
                        An error occurred. Please try again.
                    </div>
                `;
                
                // Remove existing message and add new one
                $('.mb-6[id="messageAlert"]').remove();
                $('#profileForm').before(errorMessage);
                
                // Auto-hide message after 5 seconds
                setTimeout(function() {
                    $('#messageAlert').fadeOut('slow');
                }, 5000);
            },
            complete: function() {
                // Re-enable button
                $('#saveProfile').prop('disabled', false).html('<i class="fa-solid fa-save mr-2"></i>Save Changes');
            }
        });
    });
    
    // Cancel profile changes
    $('#cancelProfile').click(function() {
        if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
            location.reload();
        }
    });
    
    // Change password form
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        const currentPassword = $('#currentPassword').val();
        const newPassword = $('#newPassword').val();
        const confirmNewPassword = $('#confirmNewPassword').val();
        
        // Clear previous messages
        $('#currentPasswordMessage, #newPasswordMessage, #confirmNewPasswordMessage').html('');
        
        // Validation
        if (!currentPassword || !newPassword || !confirmNewPassword) {
            $('#passwordChangeMessage').html(`
                <div class="bg-red-50 text-red-800 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">All fields are required</div>
            `);
            return;
        }
        
        if (newPassword.length < 6) {
            $('#newPasswordMessage').html('New password must be at least 6 characters long.');
            return;
        }
        
        if (newPassword !== confirmNewPassword) {
            $('#confirmNewPasswordMessage').html('New passwords do not match.');
            return;
        }
        
        // Disable button and show loading
        $('#savePasswordChange').prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Changing Password...');
        
        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: '<?php echo HOMEPAGE_URL ?>/main/dashboard/ajax-settings.php',
            data: {
                action: 'change_password',
                current_password: currentPassword,
                new_password: newPassword
            },
            success: function(response) {
                console.log(response);
                
                // Parse response if it's a string
                let responseData = response;
                if (typeof response === 'string') {
                    try {
                        responseData = JSON.parse(response);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        responseData = { status: 'error', message: 'Invalid response format' };
                    }
                }
                
                if (responseData.status === 'success') {
                    $('#passwordChangeMessage').html(`
                        <div class="bg-green-50 text-green-800 border border-green-200 rounded-xl py-4 px-6 text-sm font-medium">${responseData.message}</div>
                    `);
                    $('#changePasswordForm')[0].reset();
                    
                    // Auto-hide message after 5 seconds
                    setTimeout(function() {
                        $('#passwordChangeMessage').fadeOut('slow');
                    }, 5000);
                } else {
                    $('#passwordChangeMessage').html(`
                        <div class="bg-red-50 text-red-800 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">${responseData.message || 'An error occurred'}</div>
                    `);
                    
                    // Auto-hide message after 5 seconds
                    setTimeout(function() {
                        $('#passwordChangeMessage').fadeOut('slow');
                    }, 5000);
                }
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
                $('#passwordChangeMessage').html(`
                    <div class="bg-red-50 text-red-800 border border-red-200 rounded-xl py-4 px-6 text-sm font-medium">An error occurred. Please try again.</div>
                `);
                
                // Auto-hide message after 5 seconds
                setTimeout(function() {
                    $('#passwordChangeMessage').fadeOut('slow');
                }, 5000);
            },
            complete: function() {
                // Re-enable button
                $('#savePasswordChange').prop('disabled', false).html('<i class="fa-solid fa-key mr-2"></i>Change Password');
            }
        });
    });
    
    // Cancel button
    $('#cancelPasswordChange').click(function() {
        $('#changePasswordForm')[0].reset();
        $('#passwordChangeMessage').html('');
        $('#currentPasswordMessage, #newPasswordMessage, #confirmNewPasswordMessage').html('');
    });
});

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + 'Icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>