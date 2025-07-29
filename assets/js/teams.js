/**
 * Teams Page JavaScript
 * Handles all teams page functionality including dropdowns, modals, notifications, and user management
 */

$(document).ready(function() {
    // Initialize all teams functionality
    initializeTeamsPage();
});

function initializeTeamsPage() {
    // Initialize filter functionality
    initializeFilters();
    
    // Initialize dropdown functionality
    initializeDropdowns();
    
    // Initialize modals
    initializeModals();
    
    // Initialize notification system
    initializeNotifications();
    
    // Initialize select all functionality
    initializeSelectAll();
    
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
}

// Filter functionality
function initializeFilters() {
    function applyFilters() {
        const search = $('#searchUser').val();
        const status = $('#statusFilter').val();
        const sortBy = $('#sortBy').val();
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (sortBy) params.append('sortBy', sortBy);
        
        window.location.href = 'teams.php?' + params.toString();
    }
    
    // Event listeners
    $('#searchUser').on('input', function() {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(applyFilters, 500);
    });
    
    $('#statusFilter, #sortBy').on('change', applyFilters);
    
    $('#clearFilters').on('click', function() {
        window.location.href = 'teams.php';
    });
}

// Dropdown functionality
function initializeDropdowns() {
    $(document).on('click', '.dropdown-toggle', function(e) {
        e.stopPropagation();
        const $dropdown = $(this).siblings('.dropdown-menu');
        
        // Close all other dropdowns
        $('.dropdown-menu').not($dropdown).removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
        
        // Toggle current dropdown
        if ($dropdown.hasClass('opacity-100 visible')) {
            $dropdown.removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
        } else {
            $dropdown.removeClass('opacity-0 invisible scale-95').addClass('opacity-100 visible scale-100');
        }
    });
    
    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.relative').length) {
            $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
        }
    });
}

// Modal functionality
function initializeModals() {
    // Project assignment modal functionality
    let selectedProjectId = null;
    let selectedUserId = null;
    
    // Close modal functions
    $('#closeAssignmentModal, #cancelAssignment').on('click', function() {
        hideProjectAssignmentModal();
        resetAssignmentModal();
    });
    
    // Close manage projects modal
    $('#closeManageProjectsModal, #cancelManageProjects').on('click', function() {
        hideManageUserProjectsModal();
        resetManageProjectsModal();
    });

    // Close user profile popup modal
    $('#closeUserProfilePopup').on('click', function() {
        $('#userProfilePopupModal').addClass('hidden');
        $('#userProfilePopupContent').empty(); // Clear content
    });

    // Close profile popup
    $('#closeProfilePopup').on('click', function() {
        closeProfilePopup();
    });
    
    // Close profile popup when clicking outside
    $(document).on('click', function(e) {
        // Only close if clicking outside the popup AND not on dropdown elements
        if (!$(e.target).closest('#userProfilePopup').length && 
            !$(e.target).closest('.dropdown-toggle').length && 
            !$(e.target).closest('.dropdown-menu').length && 
            !$('#userProfilePopup').hasClass('hidden')) {
            
            // Add a small delay to prevent immediate closing
            setTimeout(() => {
                if (!$('#userProfilePopup').hasClass('hidden')) {
                    closeProfilePopup();
                }
            }, 100);
        }
    });
    
    function closeProfilePopup() {
        $('#userProfilePopup').addClass('hidden').removeClass('show').hide();
        setTimeout(() => {
            $('#profilePopupContent').empty(); // Clear content after animation
        }, 300);
    }

    // Close modal when clicking outside
    $('#projectAssignmentModal, #manageUserProjectsModal, #userProfilePopupModal').on('click', function(e) {
        if (e.target === this) {
            if ($(this).attr('id') === 'projectAssignmentModal') {
                hideProjectAssignmentModal();
                resetAssignmentModal();
            } else if ($(this).attr('id') === 'manageUserProjectsModal') {
                hideManageUserProjectsModal();
                resetManageProjectsModal();
            } else {
                $(this).addClass('hidden');
                $('#userProfilePopupContent').empty(); // Clear content
            }
        }
    });
    
    function resetAssignmentModal() {
        selectedProjectId = null;
        selectedUserId = null;
        $('#availableProjectsList').empty();
        $('#assignmentMessage').addClass('hidden').empty();
        $('#confirmAssignment').addClass('hidden');
    }
    
    function resetManageProjectsModal() {
        $('#userCurrentProjectsList').empty();
        $('#manageProjectsMessage').addClass('hidden').empty();
    }
    
    // Project option click
    $(document).on('click', '.project-option', function() {
        $('.project-option').removeClass('ring-2 ring-blue-500 bg-blue-50');
        $(this).addClass('ring-2 ring-blue-500 bg-blue-50');
        selectedProjectId = $(this).data('project-id');
        $('#confirmAssignment').removeClass('hidden');
    });
    
    // Confirm assignment
    $('#confirmAssignment').on('click', function() {
        if (!selectedProjectId || !selectedUserId) {
            showAssignmentMessage('Please select a project first.', 'error');
            return;
        }
        
        $.ajax({
            url: 'ajax-teams.php',
            method: 'POST',
            data: {
                action: 'assign_user_to_project',
                user_id: selectedUserId,
                project_id: selectedProjectId
            },
            success: function(response) {
                if (response.status === 'success') {
                    showAssignmentMessage(response.message, 'success');
                    setTimeout(function() {
                        $('#projectAssignmentModal').addClass('hidden');
                        resetAssignmentModal();
                        // Reload the page to show updated data
                        location.reload();
                    }, 1500);
                } else {
                    showAssignmentMessage(response.message, 'error');
                }
            },
            error: function() {
                showAssignmentMessage('An error occurred while assigning user to project.', 'error');
            }
        });
    });
    
    function showAssignmentMessage(message, type) {
        const messageDiv = $('#assignmentMessage');
        messageDiv.removeClass('hidden bg-green-100 text-green-800 bg-red-100 text-red-800');
        
        if (type === 'success') {
            messageDiv.addClass('bg-green-100 text-green-800');
        } else {
            messageDiv.addClass('bg-red-100 text-red-800');
        }
        
        messageDiv.text(message);
    }
}

// Show project assignment modal
function showProjectAssignmentModal() {
    console.log('showProjectAssignmentModal called');
    $('#projectAssignmentModal').removeClass('hidden');
    console.log('Modal hidden class removed');
    setTimeout(() => {
        $('#projectAssignmentModal .bg-white').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        console.log('Modal animation classes applied');
    }, 10);
}

// Hide project assignment modal
function hideProjectAssignmentModal() {
    $('#projectAssignmentModal .bg-white').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
    setTimeout(() => {
        $('#projectAssignmentModal').addClass('hidden');
    }, 300);
}

// Show manage user projects modal
function showManageUserProjectsModal() {
    console.log('showManageUserProjectsModal called');
    $('#manageUserProjectsModal').removeClass('hidden');
    console.log('Manage projects modal hidden class removed');
    setTimeout(() => {
        $('#manageUserProjectsModal .bg-white').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        console.log('Manage projects modal animation classes applied');
    }, 10);
}

// Hide manage user projects modal
function hideManageUserProjectsModal() {
    $('#manageUserProjectsModal .bg-white').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
    setTimeout(() => {
        $('#manageUserProjectsModal').addClass('hidden');
    }, 300);
}

// Select all functionality
function initializeSelectAll() {
    $('#selectAll').on('change', function() {
        $('.user-checkbox').prop('checked', $(this).is(':checked'));
    });
    
    // Individual checkbox change
    $(document).on('change', '.user-checkbox', function() {
        const totalCheckboxes = $('.user-checkbox').length;
        const checkedCheckboxes = $('.user-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
}

// Keyboard shortcuts
function initializeKeyboardShortcuts() {
    // Close dropdowns when pressing Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
        }
    });
}

// User profile functions
function viewUserProfile(userId) {
    // Close any open dropdowns
    $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
    
    console.log('viewUserProfile called for user ID:', userId);
    
    // Show loading state
    $('#profilePopupContent').html(`
        <div class="flex items-center justify-center py-8">
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                <p class="text-gray-600">Loading user profile...</p>
            </div>
        </div>
    `);
    
    // Force show the popup
    $('#userProfilePopup').removeClass('hidden').addClass('show').show();
    console.log('Popup should be visible now');
    
    // Prevent any interference
    setTimeout(() => {
        $('#userProfilePopup').removeClass('hidden').addClass('show').show();
    }, 50);
    
    // Load user profile via AJAX
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'get_user_profile',
            user_id: userId
        },
        success: function(response) {
            console.log('AJAX response received:', response);
            if (response.status === 'success') {
                $('#profilePopupContent').html(response.html);
                
                // Ensure popup is visible
                $('#userProfilePopup').removeClass('hidden').addClass('show').show();
                console.log('Profile content loaded, popup should be visible');
                
                // Ensure popup stays visible after a short delay
                setTimeout(() => {
                    $('#userProfilePopup').removeClass('hidden').addClass('show').show();
                }, 100);
                
                // Don't scroll - let it stay in place
                // $('#userProfilePopup')[0].scrollIntoView({ 
                //     behavior: 'smooth', 
                //     block: 'nearest' 
                // });
            } else {
                $('#profilePopupContent').html(`
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500 mb-2"></i>
                        <p class="text-red-600">Error loading user profile</p>
                        <p class="text-sm text-gray-500 mt-1">${response.message || 'Unknown error'}</p>
                    </div>
                `);
                $('#userProfilePopup').removeClass('hidden').addClass('show').show();
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX error:', error);
            $('#profilePopupContent').html(`
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500 mb-2"></i>
                    <p class="text-red-600">Error loading user profile</p>
                    <p class="text-sm text-gray-500 mt-1">Please try again later</p>
                </div>
            `);
            $('#userProfilePopup').removeClass('hidden').addClass('show').show();
        }
    });
}

function assignToProject(userId) {
    // Close any open dropdowns
    $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
    
    selectedUserId = userId;
    console.log('assignToProject called for user ID:', userId);
    
    // Load available projects
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'get_available_projects',
            user_id: userId
        },
        success: function(response) {
            console.log('AJAX response received:', response);
            if (response.status === 'success') {
                $('#availableProjectsList').html(response.html);
                console.log('Projects loaded, showing modal...');
                showProjectAssignmentModal();
                console.log('Modal should be visible now');
            } else {
                console.error('Error loading available projects:', response.message);
                alert('Error loading available projects: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            alert('An error occurred while loading available projects.');
        }
    });
}

function manageUserProjects(userId) {
    // Close any open dropdowns
    $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');

    selectedUserId = userId;
    console.log('manageUserProjects called for user ID:', userId);

    // Load user's current projects
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'get_user_projects',
            user_id: userId
        },
        success: function(response) {
            console.log('AJAX response received for user projects:', response);
            if (response.status === 'success') {
                $('#userCurrentProjectsList').html(response.html);
                console.log('User projects loaded, showing modal...');
                showManageUserProjectsModal();
                console.log('Manage projects modal should be visible now');
            } else {
                console.error('Error loading user projects:', response.message);
                alert('Error loading user projects: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error loading user projects:', error);
            alert('An error occurred while loading user projects.');
        }
    });
}

function removeUserFromProject(userId, projectId) {
    if (confirm('Are you sure you want to remove this user from the project?')) {
        $.ajax({
            url: 'ajax-teams.php',
            method: 'POST',
            data: {
                action: 'remove_user_from_project',
                user_id: userId,
                project_id: projectId
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    showManageProjectsMessage(response.message, 'success');
                    
                    // Reload the user projects list
                    setTimeout(function() {
                        manageUserProjects(userId);
                    }, 1500);
                    
                    // Reload the page to update the main list
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showManageProjectsMessage(response.message, 'error');
                }
            },
            error: function() {
                showManageProjectsMessage('An error occurred while removing user from project.', 'error');
            }
        });
    }
}

function showManageProjectsMessage(message, type) {
    const messageDiv = $('#manageProjectsMessage');
    messageDiv.removeClass('hidden bg-green-100 text-green-800 bg-red-100 text-red-800');
    
    if (type === 'success') {
        messageDiv.addClass('bg-green-100 text-green-800');
    } else {
        messageDiv.addClass('bg-red-100 text-red-800');
    }
    
    messageDiv.text(message);
}

function editUser(userId) {
    // Close any open dropdowns
    $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
    
    // Show a modal to edit user details (simple prompt for demo, replace with a modal form for production)
    const newName = prompt('Enter new name for the user:');
    if (newName && newName.trim() !== '') {
        $.ajax({
            url: 'ajax-teams.php',
            method: 'POST',
            data: {
                action: 'edit_user',
                user_id: userId,
                data: { firstname: newName }
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('User updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating user: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating user.');
            }
        });
    }
}

function deleteUser(userId) {
    // Close any open dropdowns
    $('.dropdown-menu').removeClass('opacity-100 visible scale-100').addClass('opacity-0 invisible scale-95');
    
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        $.ajax({
            url: 'ajax-teams.php',
            method: 'POST',
            data: {
                action: 'delete_user',
                user_id: userId
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting user: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting user.');
            }
        });
    }
}

function deleteUserFromPopup(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        $.ajax({
            url: 'ajax-teams.php',
            method: 'POST',
            data: {
                action: 'delete_user',
                user_id: userId
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Close the popup modal
                    $('#userProfilePopupModal').addClass('hidden');
                    $('#userProfilePopupContent').empty();
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting user: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting user.');
            }
        });
    }
}

// Notification System Functions
let notificationCheckInterval;
let currentFilter = 'all';
let allNotifications = [];

function initializeNotifications() {
    // Load initial notifications
    loadNotifications();
    loadUnreadCount();
    
    // Request notification permission on first load
    if ('Notification' in window && Notification.permission === 'default') {
        setTimeout(() => {
            requestNotificationPermission();
        }, 2000);
    }
    
    // Set up notification bell click
    $('#notificationBell').on('click', function(e) {
        e.stopPropagation();
        toggleNotificationDropdown();
    });
    
    // Mark all as read button
    $('#markAllRead').on('click', function(e) {
        e.preventDefault();
        markAllNotificationsAsRead();
    });
    
    // Filter buttons
    $('.filter-btn').on('click', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        setActiveFilter(filter);
        filterNotifications(filter);
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#notificationBell, #notificationDropdown').length) {
            hideNotificationDropdown();
        }
    });
    
    // Start periodic check for new notifications (every 30 seconds)
    notificationCheckInterval = setInterval(function() {
        checkForNewNotifications();
    }, 30000);
    
    // Also check when page becomes visible (user returns to tab)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            checkForNewNotifications();
        }
    });
}

function setActiveFilter(filter) {
    currentFilter = filter;
    $('.filter-btn').removeClass('active bg-blue-100 text-blue-700').addClass('bg-gray-100 text-gray-600');
    $(`.filter-btn[data-filter="${filter}"]`).removeClass('bg-gray-100 text-gray-600').addClass('active bg-blue-100 text-blue-700');
}

function filterNotifications(filter) {
    const $notifications = $('.notification-item');
    
    if (filter === 'all') {
        $notifications.show();
    } else if (filter === 'unread') {
        $notifications.each(function() {
            const isRead = $(this).data('is-read') == 1;
            $(this).toggle(!isRead);
        });
    } else if (filter === 'read') {
        $notifications.each(function() {
            const isRead = $(this).data('is-read') == 1;
            $(this).toggle(isRead);
        });
    }
    
    updateNotificationCount();
}

function updateNotificationCount() {
    const visibleCount = $('.notification-item:visible').length;
    const totalCount = $('.notification-item').length;
    const unreadCount = $('.notification-item[data-is-read="0"]').length;
    
    let countText = '';
    if (currentFilter === 'all') {
        countText = `${totalCount} notification${totalCount !== 1 ? 's' : ''}`;
    } else if (currentFilter === 'unread') {
        countText = `${unreadCount} unread`;
    } else if (currentFilter === 'read') {
        countText = `${totalCount - unreadCount} read`;
    }
    
    $('#notificationCount').text(countText);
}

function toggleNotificationDropdown() {
    const dropdown = $('#notificationDropdown');
    if (dropdown.hasClass('opacity-100 visible')) {
        hideNotificationDropdown();
    } else {
        showNotificationDropdown();
    }
}

function showNotificationDropdown() {
    $('#notificationDropdown').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
    loadNotifications();
}

function hideNotificationDropdown() {
    $('#notificationDropdown').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
}

function loadNotifications() {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'get_notifications',
            limit: 20,
            offset: 0
        },
        success: function(response) {
            if (response.status === 'success') {
                allNotifications = response.notifications;
                $('#notificationList').html(response.html);
                updateNotificationCount();
                
                // Add hover effects for delete buttons
                $('.notification-item').addClass('group');
            } else {
                $('#notificationList').html('<div class="p-4 text-center text-gray-500">Error loading notifications</div>');
            }
        },
        error: function() {
            $('#notificationList').html('<div class="p-4 text-center text-gray-500">Error loading notifications</div>');
        }
    });
}

function loadUnreadCount() {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'get_unread_count'
        },
        success: function(response) {
            if (response.status === 'success') {
                const count = response.count;
                const badge = $('#notificationBadge');
                
                if (count > 0) {
                    badge.text(count > 99 ? '99+' : count).removeClass('hidden');
                    // Add pulse animation for new notifications
                    badge.addClass('animate-pulse');
                } else {
                    badge.addClass('hidden').removeClass('animate-pulse');
                }
            }
        },
        error: function() {
            // Silently fail for unread count
        }
    });
}

function markNotificationAsRead(notificationId) {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'mark_notification_read',
            notification_id: notificationId
        },
        success: function(response) {
            if (response.status === 'success') {
                // Update the notification item to show as read
                const $notification = $(`.notification-item[data-notification-id="${notificationId}"]`);
                $notification
                    .removeClass('bg-white border-l-4 border-blue-500')
                    .addClass('opacity-75 bg-gray-50')
                    .attr('data-is-read', '1');
                
                // Remove unread indicator
                $notification.find('.w-2.h-2.bg-blue-500').remove();
                
                // Update text colors
                $notification.find('h4').removeClass('text-gray-900').addClass('text-gray-600');
                $notification.find('p').removeClass('text-gray-700').addClass('text-gray-500');
                
                loadUnreadCount();
                updateNotificationCount();
                
                // Show success feedback
                showToast('Notification marked as read', 'success');
            }
        }
    });
}

function markAllNotificationsAsRead() {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'mark_all_notifications_read'
        },
        success: function(response) {
            if (response.status === 'success') {
                // Update all notification items to show as read
                $('.notification-item').each(function() {
                    $(this)
                        .removeClass('bg-white border-l-4 border-blue-500')
                        .addClass('opacity-75 bg-gray-50')
                        .attr('data-is-read', '1');
                    
                    // Remove unread indicators
                    $(this).find('.w-2.h-2.bg-blue-500').remove();
                    
                    // Update text colors
                    $(this).find('h4').removeClass('text-gray-900').addClass('text-gray-600');
                    $(this).find('p').removeClass('text-gray-700').addClass('text-gray-500');
                });
                
                loadUnreadCount();
                updateNotificationCount();
                showToast('All notifications marked as read', 'success');
            } else {
                showToast('Error marking notifications as read', 'error');
            }
        },
        error: function() {
            showToast('Error marking notifications as read', 'error');
        }
    });
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        $.ajax({
            url: 'ajax-teams.php',
            method: 'POST',
            data: {
                action: 'delete_notification',
                notification_id: notificationId
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Remove the notification item from the DOM
                    $(`.notification-item[data-notification-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateNotificationCount();
                    });
                    loadUnreadCount();
                    showToast('Notification deleted', 'success');
                } else {
                    showToast('Error deleting notification', 'error');
                }
            },
            error: function() {
                showToast('Error deleting notification', 'error');
            }
        });
    }
}

function showToast(message, type = 'info') {
    // Create toast notification
    const toast = $(`
        <div class="fixed top-4 right-4 z-50 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm transform transition-all duration-300 translate-x-full">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <i class="fas ${type === 'success' ? 'fa-check-circle text-green-500' : type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-info-circle text-blue-500'}"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">${message}</p>
                </div>
                <button class="text-gray-400 hover:text-gray-600" onclick="$(this).parent().parent().remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    
    // Animate in
    setTimeout(() => {
        toast.removeClass('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.addClass('translate-x-full');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Real-time notification features
function checkForNewNotifications() {
    const previousCount = parseInt($('#notificationBadge').text()) || 0;
    
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'get_unread_count'
        },
        success: function(response) {
            if (response.status === 'success') {
                const currentCount = response.count;
                
                // If there are new notifications
                if (currentCount > previousCount) {
                    const newCount = currentCount - previousCount;
                    
                    // Update badge
                    $('#notificationBadge').text(currentCount > 99 ? '99+' : currentCount).removeClass('hidden');
                    
                    // Show desktop notification if permission granted
                    if (Notification.permission === 'granted') {
                        new Notification('New Notifications', {
                            body: `You have ${newCount} new notification${newCount > 1 ? 's' : ''}`,
                            icon: '/favicon.ico',
                            badge: '/favicon.ico'
                        });
                    }
                    
                    // Play notification sound (if supported)
                    playNotificationSound();
                    
                    // Show toast notification
                    showToast(`You have ${newCount} new notification${newCount > 1 ? 's' : ''}`, 'info');
                    
                    // Add bounce animation to bell
                    $('#notificationBell').addClass('animate-bounce');
                    setTimeout(() => {
                        $('#notificationBell').removeClass('animate-bounce');
                    }, 1000);
                }
            }
        }
    });
}

function playNotificationSound() {
    // Create a simple notification sound using Web Audio API
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {
        // Fallback: silent failure
        console.log('Audio not supported');
    }
}

function requestNotificationPermission() {
    if ('Notification' in window) {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                showToast('Desktop notifications enabled!', 'success');
            }
        });
    }
}

// Enhanced notification click handler
function handleNotificationClick(notificationId, notificationData) {
    // Mark as read
    markNotificationAsRead(notificationId);
    
    // Handle different notification types
    if (notificationData && notificationData.type === 'assignment') {
        // Navigate to project or show project details
        if (notificationData.data && notificationData.data.project_id) {
            // You can add navigation logic here
            showToast('Navigating to project...', 'info');
        }
    } else if (notificationData && notificationData.type === 'profile_update') {
        // Navigate to profile or refresh user list
        showToast('Profile updated, refreshing...', 'info');
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
}

// Add keyboard shortcuts for notifications
$(document).on('keydown', function(e) {
    // Ctrl/Cmd + N to open notifications
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        if ($('#notificationDropdown').hasClass('opacity-100 visible')) {
            hideNotificationDropdown();
        } else {
            showNotificationDropdown();
        }
    }
    
    // Escape to close notifications
    if (e.key === 'Escape') {
        hideNotificationDropdown();
    }
});

// Task Status Change Notification Function
function notifyTaskStatusChange(taskId, oldStatus, newStatus) {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'task_status_change',
            task_id: taskId,
            old_status: oldStatus,
            new_status: newStatus
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log('Task status change notification sent:', response.message);
                // Optionally show a toast notification
                showToast('Task status updated and notifications sent', 'success');
            } else {
                console.error('Error sending task status change notification:', response.message);
                showToast('Error sending notifications', 'error');
            }
        },
        error: function() {
            console.error('Error sending task status change notification');
            showToast('Error sending notifications', 'error');
        }
    });
}

// Function to be called when task status is changed from any part of the application
function handleTaskStatusUpdate(taskId, oldStatus, newStatus) {
    // First update the task status in the database (this will be handled by the existing updateTaskStatus function)
    // Then send notifications
    notifyTaskStatusChange(taskId, oldStatus, newStatus);
}

// Enhanced notification click handler for task notifications
function handleTaskNotificationClick(notificationId, notificationData) {
    // Mark as read
    markNotificationAsRead(notificationId);
    
    // Handle task-related notifications
    if (notificationData && (notificationData.type === 'task_status_change' || notificationData.type === 'admin_task_update')) {
        if (notificationData.data && notificationData.data.task_id) {
            // Navigate to the specific task or project
            showToast('Navigating to task...', 'info');
            // You can add navigation logic here, for example:
            // window.location.href = `project-details.php?project_id=${notificationData.data.project_id}&task_id=${notificationData.data.task_id}`;
        }
    } else if (notificationData && notificationData.type === 'task_created') {
        if (notificationData.data && notificationData.data.task_id) {
            showToast('Navigating to new task...', 'info');
            // Navigate to the newly created task
            // window.location.href = `task-details.php?task_id=${notificationData.data.task_id}`;
        }
    } else if (notificationData && notificationData.type === 'task_assigned') {
        if (notificationData.data && notificationData.data.task_id) {
            showToast('Navigating to assigned task...', 'info');
            // Navigate to the assigned task
            // window.location.href = `task-details.php?task_id=${notificationData.data.task_id}`;
        }
    }
}

// Task Creation Notification Function
function notifyTaskCreated(taskId) {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'task_created',
            task_id: taskId
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log('Task creation notification sent:', response.message);
                showToast('Task created and notifications sent', 'success');
            } else {
                console.error('Error sending task creation notification:', response.message);
                showToast('Error sending notifications', 'error');
            }
        },
        error: function() {
            console.error('Error sending task creation notification');
            showToast('Error sending notifications', 'error');
        }
    });
}

// Task Assignment Notification Function
function notifyTaskAssigned(taskId, assignedUserId) {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'task_assigned',
            task_id: taskId,
            assigned_user_id: assignedUserId
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log('Task assignment notification sent:', response.message);
                showToast('Task assigned and notification sent', 'success');
            } else {
                console.error('Error sending task assignment notification:', response.message);
                showToast('Error sending notifications', 'error');
            }
        },
        error: function() {
            console.error('Error sending task assignment notification');
            showToast('Error sending notifications', 'error');
        }
    });
}

// Function to handle task creation with notifications
function handleTaskCreation(taskData) {
    // First create the task using your existing function
    $.ajax({
        url: 'your-create-task-endpoint.php', // Replace with your actual endpoint
        method: 'POST',
        data: taskData,
        success: function(response) {
            if (response.status === 'success') {
                // Send notification about task creation
                notifyTaskCreated(response.task_id);
                
                // Show success message
                showToast('Task created successfully!', 'success');
                
                // Refresh task list or update UI
                refreshTaskList();
            } else {
                showToast('Error creating task', 'error');
            }
        },
        error: function() {
            showToast('Error creating task', 'error');
        }
    });
}

// Function to handle task assignment with notifications
function handleTaskAssignment(taskId, assignedUserId) {
    // First assign the task using your existing function
    $.ajax({
        url: 'your-assign-task-endpoint.php', // Replace with your actual endpoint
        method: 'POST',
        data: {
            task_id: taskId,
            assigned_user_id: assignedUserId
        },
        success: function(response) {
            if (response.status === 'success') {
                // Send notification about task assignment
                notifyTaskAssigned(taskId, assignedUserId);
                
                // Show success message
                showToast('Task assigned successfully!', 'success');
                
                // Refresh task list or update UI
                refreshTaskList();
            } else {
                showToast('Error assigning task', 'error');
            }
        },
        error: function() {
            showToast('Error assigning task', 'error');
        }
    });
}

// Function to handle comprehensive task creation with notifications
function handleComprehensiveTaskCreation(taskData) {
    // First create the task using your existing function
    $.ajax({
        url: 'your-create-task-endpoint.php', // Replace with your actual endpoint
        method: 'POST',
        data: taskData,
        success: function(response) {
            if (response.status === 'success') {
                // Send comprehensive notification about task creation
                notifyComprehensiveTaskCreation(response.task_id, taskData.task_assignto);
                
                // Show success message
                showToast('Task created and notifications sent!', 'success');
                
                // Refresh task list or update UI
                refreshTaskList();
            } else {
                showToast('Error creating task', 'error');
            }
        },
        error: function() {
            showToast('Error creating task', 'error');
        }
    });
}

// Function to send comprehensive task creation notification
function notifyComprehensiveTaskCreation(taskId, assignedUserId) {
    $.ajax({
        url: 'ajax-teams.php',
        method: 'POST',
        data: {
            action: 'task_creation_comprehensive',
            task_id: taskId,
            assigned_to: assignedUserId
        },
        success: function(response) {
            if (response.status === 'success') {
                console.log('Comprehensive task creation notification sent:', response);
                // Optionally refresh notifications
                loadNotifications();
                loadUnreadCount();
            } else {
                console.error('Failed to send comprehensive task creation notification:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error sending comprehensive task creation notification:', error);
        }
    });
} 