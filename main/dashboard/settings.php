<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<section class="py-25 pl-85 w-full pr-10">

    <!-- Header -->
    <div class="h-40 bg-gradient-to-r from-purple-100 to-blue-100 relative rounded-tl-[50px]">
        <div class="absolute -bottom-18 left-6">
            <img src="https://i.pravatar.cc/80"
                class="w-28 h-28 rounded-full border-4 border-white shadow-md object-cover" />
        </div>
    </div>
    <div class="py-4 pl-40">
        <h2 class="text-3xl font-semibold text-gray-900">Profile</h2>
        <p class="text-sm text-gray-500 mb-6">Update your photo and personal details.</p>
    </div>


    <div class="w-full bg-white rounded-lg">
        <!-- Tab Buttons -->
        <div class="flex border-b border-gray-200 mb-6" id="tabs">
            <button data-tab="tab1"
                class="tab-btn px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-500">
                Profile
            </button>
            <!-- <button data-tab="tab2"
                class="tab-btn px-4 py-2 ml-4 text-sm font-medium text-gray-500 hover:text-blue-600">
                Notification
            </button> -->
            <button data-tab="tab3"
                class="tab-btn px-4 py-2 ml-4 text-sm font-medium text-gray-500 hover:text-blue-600">
                Security
            </button>
        </div>

        <!-- Tab Contents -->
        <div id="tab-contents">
            <div id="tab1" class="tab-content">
                <div class="pb-6">
                    <form class="space-y-6">
                        <!-- Display name -->
                        <div class="flex items-center w-full gap-10">
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <div class="flex rounded-md border border-slate-300">

                                    <input type="text" value="olivia"
                                        class="flex-1 block w-full  text-sm px-4 py-3 outline-none">
                                </div>
                            </div>
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <div class="flex rounded-md border border-slate-300">
                                    <input type="text" value="olivia"
                                        class="flex-1 block w-full text-sm px-4 py-3 outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- email -->
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="flex rounded-md border border-slate-300">
                                <input type="text" value="example@example.com" readonly
                                    class="flex-1 block w-full text-sm px-4 py-3 outline-none text-gray-500">
                            </div>
                        </div>

                        <!-- Photo -->


                        <!-- Bio -->
                        <div class="w-full flex gap-10">
                            <div class="w-2/5 border border-slate-300 p-4 rounded-lg bg-white">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your photo</label>
                                <p class="text-sm text-gray-500 mb-2">This will be displayed on your profile.</p>

                                <div class="flex items-center space-x-4">
                                    <img id="preview-img" src="https://i.pravatar.cc/40"
                                        class="w-10 h-10 rounded-full object-cover" />

                                    <button type="button"
                                        class="text-sm font-medium hover:text-white border border-slate-300 hover:bg-red-500 px-4 py-2 rounded-full cursor-pointer">
                                        Delete
                                    </button>

                                    <label
                                        class="text-sm font-medium hover:text-black text-white hover:border border-slate-300 hover:bg-transparent bg-blue-500 px-4 py-2 rounded-full cursor-pointer inline-block">
                                        Update
                                        <input type="file" class="hidden" id="img-upload">
                                    </label>
                                </div>
                            </div>
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your bio</label>
                                <p class="text-sm text-gray-500 mb-2">Write a short introduction.</p>
                                <div class="border border-gray-300 rounded-md">
                                    <textarea rows="3" placeholder="Add a short bio..."
                                        class="w-full px-3 py-2 text-sm focus:outline-none resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-100">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Save</button>
                        </div>

                    </form>
                </div>
            </div>
            <!-- <div id="tab2" class="tab-content hidden">
                <h3 class="text-lg font-semibold">Settings</h3>
                <p class="text-gray-600">Configure your notification preferences and theme.</p>
            </div> -->
            <div id="tab3" class="tab-content hidden">
                <h3 class="text-lg font-semibold">Change Password</h3>
                <p class="text-gray-600">Change your password and manage sessions securely.</p>
                <div class="flex flex-col gap-10 py-10 w-1/2">
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Old Password</label>
                        <div class="flex rounded-md border border-slate-300">

                            <input type="password" class="flex-1 block w-full  text-sm px-4 py-3 outline-none">
                        </div>
                        <span class="text-sm text-red-400">Old Password not matched</span>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="flex rounded-md border border-slate-300">
                            <input type="password" class="flex-1 block w-full text-sm px-4 py-3 outline-none">
                        </div>

                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="flex rounded-md border border-slate-300">
                            <input type="password" class="flex-1 block w-full text-sm px-4 py-3 outline-none">
                        </div>
                        <span class="text-sm text-red-400">Password not matched</span>
                    </div>
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-100">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>