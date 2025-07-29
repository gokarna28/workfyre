<?php
include_once('header.php');

// Get current user data for dynamic profile image
$currentUser = getCurrentUser();
$userDetails = getUsersDetailsByUser_id($currentUser['id']);

// Debug: Check what data we're getting
// error_log("User Details: " . print_r($userDetails, true));
?>
<sidebar>
    <div class="bg-white flex flex-col justify-between border-r border-slate-200 md:w-1/5 w-1/6 md:h-screen h-full p-6 fixed z-50 top-0 shadow-lg">
        <!-- Logo Section -->
        <div class="mb-6 border-b border-gray-200 pb-4">
            <h1 class="text-2xl font-bold text-gray-900 text-center">
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/home.php" class="hover:text-blue-600 transition-colors">Workfyre</a>
            </h1>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/home.php"
                        class="<?php echo isset($current_page) && $current_page == 'home.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' ?> flex items-center gap-4 rounded-xl p-4 text-sm font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg <?php echo isset($current_page) && $current_page == 'home.php' ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' ?> transition-colors">
                            <i class="fa-solid fa-house text-lg"></i>
                        </div>
                        <span class="hidden md:block">Dashboard</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/projects.php"
                        class="<?php echo isset($current_page) && $current_page == 'projects.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' ?> flex items-center gap-4 rounded-xl p-4 text-sm font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg <?php echo isset($current_page) && $current_page == 'projects.php' ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' ?> transition-colors">
                            <i class="fa-solid fa-folder text-lg"></i>
                        </div>
                        <span class="hidden md:block">Projects</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/teams.php"
                        class="<?php echo isset($current_page) && $current_page == 'teams.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' ?> flex items-center gap-4 rounded-xl p-4 text-sm font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg <?php echo isset($current_page) && $current_page == 'teams.php' ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' ?> transition-colors">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                        <span class="hidden md:block">Team</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/notifications.php"
                        class="<?php echo isset($current_page) && $current_page == 'notifications.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' ?> flex items-center gap-4 rounded-xl p-4 text-sm font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg <?php echo isset($current_page) && $current_page == 'notifications.php' ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' ?> transition-colors relative">
                            <i class="fa-regular fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                        </div>
                        <span class="hidden md:block">Notifications</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Settings & Logout Section -->
        <div class="space-y-2">
            <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/settings.php"
                class="<?php echo isset($current_page) && $current_page == 'settings.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' ?> flex items-center gap-4 rounded-xl p-4 text-sm font-medium transition-all duration-200 group">
                <div class="p-2 rounded-lg <?php echo isset($current_page) && $current_page == 'settings.php' ? 'bg-blue-100' : 'bg-gray-100 group-hover:bg-blue-100' ?> transition-colors">
                    <i class="fa-solid fa-gear text-lg"></i>
                </div>
                <span class="hidden md:block">Settings</span>
            </a>
            
            <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/logout.php"
                class="text-gray-700 hover:bg-red-50 hover:text-red-600 flex items-center gap-4 rounded-xl p-4 text-sm font-medium transition-all duration-200 group">
                <div class="p-2 rounded-lg bg-gray-100 group-hover:bg-red-100 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                </div>
                <span class="hidden md:block">Logout</span>
            </a>
        </div>

        <!-- Profile Section -->
        <div class="border-t border-gray-200 pt-4 mt-4">
            <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/settings.php"
                class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-all duration-200 group">
                <div class="relative">
                    <img src="<?php echo (!empty($userDetails['profile_image']) ? (strpos($userDetails['profile_image'], 'http') === 0 ? $userDetails['profile_image'] : HOMEPAGE_URL . $userDetails['profile_image']) : 'https://i.pravatar.cc/40'); ?>" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 group-hover:border-blue-300 transition-colors" 
                         alt="profile" />
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                <div class="flex-1 min-w-0 hidden md:block">
                    <p class="font-semibold text-gray-900 truncate"><?php echo htmlspecialchars($userDetails['firstname'] ?? 'User'); ?> <?php echo htmlspecialchars($userDetails['lastname'] ?? ''); ?></p>
                    <p class="text-xs text-gray-500 truncate"><?php echo htmlspecialchars($userDetails['email'] ?? ''); ?></p>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?php echo ucfirst($userDetails['user_role'] ?? 'member'); ?>
                        </span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- project Modal -->
    <div id="projectModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-100">
        <div class="bg-white rounded-xl p-6 w-1/2 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Create Project</h3>
                <button id="closeProjectModal" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="mb-2" id="projectSuccessMessage"></div>
            <form id="createProjectForm" method="post" enctype="multipart/form-data">
                <div>
                    <label>Title:</label>
                    <input type="text" id="project_title" name="project_title"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Project title">
                </div>

                <div class="flex items-center w-full gap-5">
                    <div class="w-full">
                        <label>Priority:</label>
                        <select id="project_priority" name="project_priority"
                            class="w-full border p-2 rounded mb-4 border border-slate-300">
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label>Description:</label>
                    <textarea id="project_description" name="project_description"
                        class="w-full border p-2 rounded mb-4 border border-slate-300"
                        placeholder="Description"></textarea>
                </div>

                <div>
                    <input type="file" id="project_attachments" name="project_attachments[]" multiple
                        accept=".png,.jpg,.jpeg,.pdf,.doc,.docx,.zip" />

                    <label for="project_attachments"
                        class="block w-full cursor-pointer border p-2 rounded mb-4 border-slate-300 bg-white text-gray-600 text-center hover:bg-gray-100">
                        Click to select attachments
                    </label>

                    <div id="previewContainer" class="mb-4"></div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button id="cancelBtn" type="button"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Create Project
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- notification modal  -->
    <div id="notificationModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-end hidden z-100 transform translate-x-full transition-transform duration-700">
        <div class="bg-white p-6 w-[30%] shadow-lg h-screen">
            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                <p class="flex items-center font-medium">Notifications <span
                        class="bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs ml-2">6</span>
                </p>
                <p><a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/notifications.php"
                        class="text-gray-600 text-sm mb-4 text-sky-600">all notifications</a>
                </p>

                <i class="fa-solid fa-xmark cursor-pointer text-2xl" id="closenotificationModalBtn"></i>
            </div>
            <p class="text-gray-600 text-sm my-4">Stay Updated With Your Latest Notifications</p>
            <div class="max-h-[600px] overflow-y-auto hide-scrollbar">
                <div class="flex flex-col gap-4 mb-6">
                    <p class="pb-4 text-gray-500 border-b border-slate-200">Today</p>
                    <div class="flex items-center justify-between bg-stone-100 p-4 rounded-xl">
                        <div class="flex items-center gap-4">
                            <span
                                class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/50'; ?>" class="w-full h-full object-cover"
                                    alt="profile" />
                            </span>
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($userDetails['firstname'] ?? 'User') . ' ' . htmlspecialchars($userDetails['lastname'] ?? ''); ?></p>
                                <p class="text-sm">Create Rest api for data access.</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Now</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between bg-stone-100 p-4 rounded-xl">
                        <div class="flex items-center gap-4">
                            <span
                                class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/30'; ?>" class="w-full h-full object-cover"
                                    alt="profile" />
                            </span>
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($userDetails['firstname'] ?? 'User') . ' ' . htmlspecialchars($userDetails['lastname'] ?? ''); ?></p>
                                <p class="text-sm">Create Rest api for data access.</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>3 min ago</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 mb-6">
                    <p class="mb-4 text-gray-500">Yesterday</p>
                    <div class="flex items-center justify-between bg-stone-100 p-4 rounded-xl">
                        <div class="flex items-center gap-4">
                            <span
                                class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/60'; ?>" class="w-full h-full object-cover"
                                    alt="profile" />
                            </span>
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($userDetails['firstname'] ?? 'User') . ' ' . htmlspecialchars($userDetails['lastname'] ?? ''); ?></p>
                                <p class="text-sm">Create Rest api for data access.</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Feb 12, 2025 2:00 PM.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between bg-stone-100 p-4 rounded-xl">
                        <div class="flex items-center gap-4">
                            <span
                                class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/80'; ?>" class="w-full h-full object-cover"
                                    alt="profile" />
                            </span>
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($userDetails['firstname'] ?? 'User') . ' ' . htmlspecialchars($userDetails['lastname'] ?? ''); ?></p>
                                <p class="text-sm">Create Rest api for data access.</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Feb 13, 2025 2:30 PM.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between bg-stone-100 p-4 rounded-xl">
                        <div class="flex items-center gap-4">
                            <span
                                class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/70'; ?>" class="w-full h-full object-cover"
                                    alt="profile" />
                            </span>
                            <div>
                                <p class="font-medium"><?php echo htmlspecialchars($userDetails['firstname'] ?? 'User') . ' ' . htmlspecialchars($userDetails['lastname'] ?? ''); ?></p>
                                <p class="text-sm">Create Rest api for data access.</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <p>Feb 13, 2025 4:00 PM.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</sidebar>