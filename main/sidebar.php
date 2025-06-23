<?php
include_once('header.php');
?>
<sidebar>
    <div
        class="bg-white flex flex-col justify-between border-r border-slate-300 md:w-1/5 w-1/6 md:h-screen h-full p-6 fixed z-3 top-0">
        <ul>
            <li>
                <div class="mb-6 p-2 pb-10 border-b border-slate-300">
                    <h1 class="text-3xl font-bold"><a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/home.php"></a>
                    </h1>
                </div>
            </li>

            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/home.php"
                    class="<?php echo isset($current_page) && $current_page == 'home.php' ? 'hover:bg-slate-100 bg-slate-100 ' : 'hover:bg-slate-100 hover:bg-slate-100 ' ?> cursor-pointer flex items-center gap-6 rounded-xl p-3 text-lg font-medium mb-6">
                    <i class="fa-solid fa-house  text-lg"></i>
                    <span class="hidden md:block">Home</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/projects.php"
                    class="<?php echo isset($current_page) && $current_page == 'projects.php' ? 'hover:bg-slate-100 bg-slate-100 ' : 'hover:bg-slate-100 hover:bg-slate-100 ' ?> cursor-pointer flex items-center gap-6 rounded-xl p-3 text-lg font-medium mb-6">
                    <i class="fa-regular fa-gem  text-lg"></i>
                    <span class="hidden md:block">Projects</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/teams.php"
                    class="<?php echo isset($current_page) && $current_page == 'teams.php' ? 'hover:bg-slate-100 bg-slate-100 ' : 'hover:bg-slate-100 hover:bg-slate-100 ' ?> cursor-pointer flex items-center gap-6 rounded-xl p-3 text-lg font-medium mb-6">
                    <i class="fa-regular fa-user  text-lg"></i>
                    <span class="hidden md:block">Teams</span>
                </a>
            </li>
            <li>
                <p class="mb-6 text-gray-500 text-sm">Accounts</p>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/notifications.php"
                    class="<?php echo isset($current_page) && $current_page == 'notifications.php' ? 'hover:bg-slate-100 bg-slate-100 ' : 'hover:bg-slate-100 hover:bg-slate-100 ' ?> cursor-pointer flex items-center gap-6 rounded-xl p-3 text-lg font-medium mb-6">
                    <i class="fa-regular fa-bell  text-lg"></i>
                    <span class="hidden md:block">Notifications</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/settings.php"
                    class="<?php echo isset($current_page) && $current_page == 'settings.php' ? 'hover:bg-slate-100 bg-slate-100 ' : 'hover:bg-slate-100 hover:bg-slate-100 ' ?> cursor-pointer flex items-center gap-6 rounded-xl p-3 text-lg font-medium mb-6">
                    <i class="fa-solid fa-gear  text-lg"></i>
                    <span class="hidden md:block">Settings</span>
                </a>
            </li>

            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/logout.php"
                    class="<?php echo isset($current_page) && $current_page == 'logout.php' ? 'hover:bg-slate-100 bg-slate-100 ' : 'hover:bg-slate-100 hover:bg-slate-100 ' ?> cursor-pointer flex items-center gap-6 rounded-xl p-4 text-lg font-medium mb-6">
                    <i class="fa-solid fa-arrow-right-from-bracket  text-lg"></i>
                    <span class="hidden md:block">Logout</span>
                </a>
            </li>
        </ul>
        <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/settings.php"
            class="border-t border-slate-300 p-3 flex items-center gap-6">
            <span
                class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-14 h-14 overflow-hidden">
                <img src="https://i.pravatar.cc/40" class="w-full h-full object-cover" alt="default profile" />
            </span>
            <span class="">
                <p class="font-medium">Gokarna</p>
                <p class="text-sm text-gray-500">gokarnachy</p>
            </span>
        </a>
    </div>


    <!-- project Modal -->
    <div id="projectModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-100">
        <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Create Project</h3>
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
                        Add Task
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
                                <img src="https://i.pravatar.cc/50" class="w-full h-full object-cover"
                                    alt="default profile" />
                            </span>
                            <div>
                                <p class="font-medium">Gokarna Chaudhary</p>
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
                                <img src="https://i.pravatar.cc/30" class="w-full h-full object-cover"
                                    alt="default profile" />
                            </span>
                            <div>
                                <p class="font-medium">Gokarna Chaudhary</p>
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
                                <img src="https://i.pravatar.cc/60" class="w-full h-full object-cover"
                                    alt="default profile" />
                            </span>
                            <div>
                                <p class="font-medium">Gokarna Chaudhary</p>
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
                                <img src="https://i.pravatar.cc/80" class="w-full h-full object-cover"
                                    alt="default profile" />
                            </span>
                            <div>
                                <p class="font-medium">Gokarna Chaudhary</p>
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
                                <img src="https://i.pravatar.cc/70" class="w-full h-full object-cover"
                                    alt="default profile" />
                            </span>
                            <div>
                                <p class="font-medium">Gokarna Chaudhary</p>
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