<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<?php
// Get current user details to determine if admin
$currentUser = getCurrentUser();
$isAdmin = $currentUser && $currentUser['user_role'] === 'admin';

// Get project statistics for sidebar
$projectStats = getProjectStatistics();
?>

<section class="pt-25 pl-85 w-full pr-10">
    <h2>Project Dashboard</h2>

    <div class="flex items-center w-full justify-between mb-5">
        <div class="flex items-center gap-2">
            <h2 class="text-4xl font-medium"><?php echo $isAdmin ? 'All Projects' : 'My Projects'; ?></h2>
            <span class="bg-slate-200 h-8 w-8 rounded-full flex items-center justify-center">
                <span id="projectCount"><?php echo $projectStats['total']; ?></span>
            </span>
        </div>
        
    </div>
    
    <!-- Enhanced Filter Section -->
    <div class="w-full mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Filter Projects</h3>
                <button id="clearFilters" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Clear All Filters</button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search Filter -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">Search Projects</label>
                    <input type="text" id="searchProject" placeholder="Search by title..." 
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Status Filter -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">Project Status</label>
                    <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="on-hold">On Hold</option>
                    </select>
                </div>
                
                <!-- Priority Filter -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="priorityFilter" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                
                <!-- Sort By -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select id="sortBy" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="latest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="title">Title A-Z</option>
                        <option value="priority">Priority</option>
                        <option value="progress">Progress</option>
                    </select>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            <div id="activeFilters" class="mt-4 flex flex-wrap gap-2 hidden">
                <span class="text-sm text-gray-600">Active filters:</span>
            </div>
        </div>
    </div>
    
    <div class="flex gap-6 w-full">
        <div class="w-2/3 mb-10">
            <div id="projectsContainer" class="w-full flex flex-col justify-center">
                <!-- Loading indicator -->
                <div id="loadingIndicator" class="text-center py-8 hidden">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="text-gray-500 mt-2">Loading projects...</p>
                </div>
                
                <!-- Projects will be loaded here via AJAX -->
            </div>
        </div>
        
        <!-- Enhanced Sidebar -->
        <div class="flex flex-col w-1/4 bg-gray-50 rounded-xl p-4 h-full border border-slate-200">
            <div class="flex items-center mb-6 justify-between">
                <p class="text-gray-600 font-medium">Quick Stats</p>
            </div>
            
            <!-- Project Status Summary -->
            <div class="space-y-4">
                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Total Projects</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full" id="totalProjects"><?php echo $projectStats['total']; ?></span>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Active Projects</span>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full" id="activeProjects"><?php echo $projectStats['active']; ?></span>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">In Progress</span>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full" id="inProgressProjects"><?php echo $projectStats['in_progress']; ?></span>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Completed</span>
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded-full" id="completedProjects"><?php echo $projectStats['completed']; ?></span>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">On Hold</span>
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded-full" id="onHoldProjects"><?php echo $projectStats['on_hold']; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h4>
                <div class="space-y-2">
                    <button onclick="window.location='/main/dashboard/projects.php'" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                        Create New Project
                    </button>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    // Filter functionality
    let currentFilters = {
        search: '',
        status: '',
        priority: '',
        sortBy: 'latest'
    };
    
    let currentPage = 1;
    let filterTimeout;
    
    // Load initial projects
    loadProjects();
    
    // Update active filters display
    function updateActiveFilters() {
        const activeFiltersDiv = $('#activeFilters');
        activeFiltersDiv.empty();
        
        let hasActiveFilters = false;
        
        if (currentFilters.search) {
            activeFiltersDiv.append(`<span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Search: ${currentFilters.search}</span>`);
            hasActiveFilters = true;
        }
        
        if (currentFilters.status) {
            activeFiltersDiv.append(`<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Status: ${currentFilters.status}</span>`);
            hasActiveFilters = true;
        }
        
        if (currentFilters.priority) {
            activeFiltersDiv.append(`<span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Priority: ${currentFilters.priority}</span>`);
            hasActiveFilters = true;
        }
        
        if (currentFilters.sortBy !== 'latest') {
            activeFiltersDiv.append(`<span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Sort: ${currentFilters.sortBy}</span>`);
            hasActiveFilters = true;
        }
        
        if (hasActiveFilters) {
            activeFiltersDiv.removeClass('hidden');
        } else {
            activeFiltersDiv.addClass('hidden');
        }
    }
    
    // Load projects via AJAX
    function loadProjects() {
        $('#loadingIndicator').removeClass('hidden');
        $('#projectsContainer').find('> div:not(#loadingIndicator)').remove();
        
        $.ajax({
            url: 'ajax-projects.php',
            method: 'POST',
            data: {
                action: 'filter_projects',
                filters: currentFilters,
                page: currentPage
            },
            success: function(response) {
                $('#loadingIndicator').addClass('hidden');
                
                if (response.status === 'success') {
                    $('#projectsContainer').html(response.html);
                    if (response.pagination) {
                        $('#projectsContainer').append(response.pagination);
                    }
                    $('#projectCount').text(response.count);
                } else {
                    $('#projectsContainer').html(`
                        <div class='text-center py-8'>
                            <p class='text-red-500 text-lg mb-4'>Error loading projects</p>
                            <p class='text-gray-400'>${response.message}</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $('#loadingIndicator').addClass('hidden');
                $('#projectsContainer').html(`
                    <div class='text-center py-8'>
                        <p class='text-red-500 text-lg mb-4'>Network Error</p>
                        <p class='text-gray-400'>Unable to load projects. Please try again.</p>
                    </div>
                `);
            }
        });
    }
    
    // Load specific page
    window.loadPage = function(page) {
        currentPage = page;
        loadProjects();
    };
    
    // Apply filters with debouncing
    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            currentPage = 1; // Reset to first page when filtering
            loadProjects();
        }, 300); // 300ms delay for search input
    }
    
    // Event listeners
    $('#searchProject').on('input', function() {
        currentFilters.search = $(this).val();
        updateActiveFilters();
        applyFilters();
    });
    
    $('#statusFilter').on('change', function() {
        currentFilters.status = $(this).val();
        updateActiveFilters();
        applyFilters();
    });
    
    $('#priorityFilter').on('change', function() {
        currentFilters.priority = $(this).val();
        updateActiveFilters();
        applyFilters();
    });
    
    $('#sortBy').on('change', function() {
        currentFilters.sortBy = $(this).val();
        updateActiveFilters();
        applyFilters();
    });
    
    $('#clearFilters').on('click', function() {
        currentFilters = {
            search: '',
            status: '',
            priority: '',
            sortBy: 'latest'
        };
        
        $('#searchProject').val('');
        $('#statusFilter').val('');
        $('#priorityFilter').val('');
        $('#sortBy').val('latest');
        
        updateActiveFilters();
        applyFilters();
    });
    
    // Initialize
    updateActiveFilters();
});
</script>