<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); 

// Get current user data for dynamic profile image
$currentUser = getCurrentUser();
$userDetails = getUsersDetailsByUser_id($currentUser['id']);
?>

<section class="pt-25 pl-85 w-full pr-10">
    <h2>Notification Dashboard</h2>

    <h2 class="text-4xl font-bold my-4">Notification</h2>
    <p class="text-gray-600 text-sm mb-4">Stay Updated With Your Latest Notifications</p>

    <!-- <div class="flex mb-4 items-center justify-between w-2/3">
        <button class="flex items-center text-sky-600">All <span
                class="bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs ml-2">6</span></button>
        <button class="hover:text-sky-600 cursor-pointer">Mark all as read</button>
    </div> -->
    <div class="flex gap-6 w-full">
        <div class="flex flex-col w-2/3 border border-slate-200 p-4 rounded-xl">
            <div class="flex flex-col gap-4 mb-6">
                <p class="pb-4 text-gray-500 border-b border-slate-200">Today</p>
                <div
                    class="flex items-center justify-between bg-stone-100 hover:bg-stone-50 cursor-pointer shadow-sm p-4 rounded-xl">
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
                <div
                    class="flex items-center justify-between bg-stone-100 hover:bg-stone-50 cursor-pointer shadow-sm p-4 rounded-xl">
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
                <p class="pb-4 text-gray-500 border-b border-slate-200">Yesterday</p>
                <div
                    class="flex items-center justify-between bg-white hover:bg-stone-50 cursor-pointer shadow-sm p-4 rounded-xl">
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
                <div
                    class="flex items-center justify-between bg-stone-100 hover:bg-stone-50 cursor-pointer shadow-sm p-4 rounded-xl">
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
                <div
                    class="flex items-center justify-between bg-stone-100 hover:bg-stone-50 cursor-pointer shadow-sm p-4 rounded-xl">
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

        <div class="flex flex-col w-1/4 bg-stone-50 rounded-xl p-4 h-full border border-slate-200 p-4 rounded-xl">
            <div class="flex items-center mb-6 justify-between">
                <p class="text-gray-600">Filter By</p>
                <p class="text-sky-600 cursor-pointer">Mark all as read</p>
            </div>
            <div class="flex items-center gap-6 bg-white p-4 mb-4 rounded-xl shadow-sm">
                <input type="checkbox" value="seen" class="w-6 h-6" checked />
                <button class="flex items-center">All <span
                        class="bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs ml-2">6</span></button>
            </div>
            <div class="flex items-center gap-6 bg-white p-4 mb-4 rounded-xl shadow-sm">
                <input type="checkbox" value="seen" class="w-6 h-6" />
                <button class="flex items-center">Seen <span
                        class="bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs ml-2">6</span></button>
            </div>
            <div class="flex items-center gap-6 bg-white p-4 mb-4 rounded-xl shadow-sm">
                <input type="checkbox" value="seen" class="w-6 h-6" />
                <button class="flex items-center">Unseen <span
                        class="bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs ml-2">6</span></button>
            </div>

        </div>
    </div>
</section>