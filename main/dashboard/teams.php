<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>


<section class="pt-25 pl-85 w-full pr-10">
    <h2 class="mb-6">Teams Dashboard</h2>
    <div class="flex gap-4">

        <div id="teamListsContainer" class="w-full border border-slate-300 p-4 rounded-xl md:w-2/3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 mb-6">
                    <button class="py-2 px-3 border border-slate-300 rounded-lg bg-blue-400 text-white">All</button>
                    <button class="py-2 px-3 border border-slate-300 rounded-lg">Active</button>
                    <button class="py-2 px-3 border border-slate-300 rounded-lg">Inactive</button>
                </div>
                <div class="border flex border-slate-300 mb-5 w-35 p-2 rounded-xl items-center">
                    <i class="fa-solid fa-filter"></i>
                    <select class="outline-none">
                        <option value="">Add Filter</option>
                        <option value="active">Active</option>
                        <option value="in-progress">In progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div>
                <ul>
                    <li class="teamCard relative  mb-4 bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <input type="checkbox" class="w-6 h-6" value="" />
                                <span
                                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                    <img src="https://i.pravatar.cc/40" class="w-full h-full object-cover"
                                        alt="default profile" />
                                </span>
                                <span>
                                    <p class="font-medium">Gokarna Chaudhary</p>
                                    <p class="text-sm text-gray-500">gokarnachy28@gmail.com</p>
                                </span>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="text-sm text-green-600 bg-green-100 rounded-full px-2">Available</span>
                                <div class="relative group">
                                    <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>

                                    <!-- Dropdown appears only when hovering on the icon -->
                                    <div
                                        class="absolute right-0 top-6 hidden group-hover:block bg-white border border-gray-200 rounded-md shadow-md w-[200px] z-10">
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Assign to</p>
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer">View Profile</p>
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-red-600">Delete</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="teamCard relative  mb-4 bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <input type="checkbox" class="w-6 h-6" value="" />
                                <span
                                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                    <img src="https://i.pravatar.cc/40" class="w-full h-full object-cover"
                                        alt="default profile" />
                                </span>
                                <span>
                                    <p class="font-medium">Gokarna Chaudhary</p>
                                    <p class="text-sm text-gray-500">gokarnachy28@gmail.com</p>
                                </span>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="text-sm text-red-600 bg-red-100 rounded-full px-2">Unavailable</span>
                                <div class="relative group">
                                    <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>

                                    <!-- Dropdown appears only when hovering on the icon -->
                                    <div
                                        class="absolute right-0 top-6 hidden group-hover:block bg-white border border-gray-200 rounded-md shadow-md w-[200px] z-10">
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Assign to</p>
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer">View Profile</p>
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-red-600">Delete</p>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    </li>
                    <li class="teamCard relative  mb-4 bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <input type="checkbox" class="w-6 h-6" value="" />
                                <span
                                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                                    <img src="https://i.pravatar.cc/40" class="w-full h-full object-cover"
                                        alt="default profile" />
                                </span>
                                <span>
                                    <p class="font-medium">Gokarna Chaudhary</p>
                                    <p class="text-sm text-gray-500">gokarnachy28@gmail.com</p>
                                </span>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="text-sm text-red-600 bg-red-100 rounded-full px-2">Unavailable</span>
                                <div class="relative group">
                                    <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>

                                    <!-- Dropdown appears only when hovering on the icon -->
                                    <div
                                        class="absolute right-0 top-6 hidden group-hover:block bg-white border border-gray-200 rounded-md shadow-md w-[200px] z-10">
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Assign to</p>
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer">View Profile</p>
                                        <p class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-red-600">Delete</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex items-center justify-between border-t border-slate-200 pt-2">
                <div class="flex items-center gap-4">
                    <input type="checkbox" value="" />
                    <button class="py-2 px-3 border border-slate-300 rounded-lg">Delete</button>
                </div>
                <!-- Pagination -->
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border rounded">&lt;</button>
                        <button class="px-3 py-1 border rounded">1</button>
                        <button class="px-3 py-1 border rounded">2</button>
                        <button class="px-3 py-1 border rounded">3</button>
                        <button class="px-3 py-1 border rounded">...</button>
                        <button class="px-3 py-1 border rounded">12</button>
                        <button class="px-3 py-1 border rounded">&gt;</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- team analytics modal  -->
        <div id="teamAnalyticsModal" class="w-full border border-slate-300 p-4 rounded-xl md:w-1/4 h-full bg-white">
            <div class="flex items-center justify-between pb-2">
                <p>Analytics</p>
            </div>
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-6 grid grid-cols-2 gap-6">
                <!-- Users Card -->
                <div class="text-center">
                    <div class="flex justify-center mb-2">
                        <span
                            class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Available</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">5</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 60%"></div>
                    </div>
                </div>

                <!-- Clicks Card -->
                <div class="text-center">
                    <div class="flex justify-center mb-2">
                        <span
                            class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Unavailable</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">6</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-600 h-2.5 rounded-full" style="width: 80%"></div>
                    </div>
                </div>

                <!-- Sales Card -->
                <div class="text-center">
                    <div class="flex justify-center mb-2">
                        <span
                            class="inline-block bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Blocked</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">0</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: 40%"></div>
                    </div>
                </div>

                <!-- Items Card -->
                <div class="text-center">
                    <div class="flex justify-center mb-2">
                        <span
                            class="inline-block bg-teal-100 text-teal-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Total</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">74</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-teal-600 h-2.5 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- team profile modal  -->
        <div id="teamProfileModal" class="w-full border border-slate-300 p-4 rounded-xl md:w-1/4 bg-stone-50 hidden">
            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                <p>Profile</p>
                <i class="fa-solid fa-xmark" id="closeTeamProfileBtn"></i>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span
                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-20 h-20 overflow-hidden">
                    <img src="https://i.pravatar.cc/40" class="w-full h-full object-cover" alt="default profile" />
                </span>
                <div class="flex items-center gap-4 pb-6 border-b border-slate-200">
                    <p class="font-medium">Gokarna Chaudhary</p>
                    <a href="mailto:gokarnachy28@gmail.com"
                        class="relative group bg-orange-200 px-2 rounded cursor-pointer">
                        <i class="fa-regular fa-envelope"></i>
                        <!-- Tooltip -->
                        <span
                            class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 z-10 whitespace-nowrap">
                            Send Email
                        </span>
                    </a>
                </div>
                <div class="w-full">
                    <p class="text-sm text-gray-500 mb-4">Enrolled Projects</p>
                    <div>
                        <ul>
                            <li class="flex items-center justify-between p-2 rounded-xl bg-stone-100 shadow-sm mb-2">
                                <p class="font-medium">Furniture Nepal</p>
                                <span class="text-sm text-red-600 bg-red-100 rounded-full px-2">Not Started</span>
                            </li>
                            <li class="flex items-center justify-between p-2 rounded-xl bg-stone-100 shadow-sm mb-2">
                                <p class="font-medium">Logo Design</p>
                                <span class="text-sm text-sky-600 bg-sky-200 rounded-full px-2">Completed</span>
                            </li>
                            <li class="flex items-center justify-between p-2 rounded-xl bg-stone-100 shadow-sm mb-2">
                                <p class="font-medium">Logo Design</p>
                                <span class="text-sm text-orange-600 bg-orange-200 rounded-full px-2">In Progress</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>