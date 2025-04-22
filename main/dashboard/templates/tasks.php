<?php include_once('../../sidebar.php'); ?>
<?php include_once('../../header.php'); ?>


<section class="pt-25 pl-85 w-full pr-10">
    <div class="mb-5">
        <div class="flex items-center gap-5">
            <a href="http://workfyre.local/main/dashboard/templates/single.php" class="hover:bg-slate-100 p-2 rounded-lg"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <h2 class="text-xl font-medium">Project Name</h2>
        </div>
        <p class="mb-5 text-sm">Short description will be placed here...</p>
        <h2 class="text-lg font-medium">Create the header and footer section. And
            remove settings page form the site.</h2>
        <p class="text-sm">Short description will be placed here</p>
    </div>
    <div class="border border-slate-300 w-full rounded-lg p-4 relative">
        <!-- Message List -->
        <div id="messageList" class="w-full mx-auto mt-6 space-y-4 text-wrap">
            <!-- Sent messages will appear here -->

        </div>
        <div class="w-full mx-auto bg-white p-4 rounded-xl border border-slate-300">
            <!-- Toolbar -->
            <div class="flex space-x-2 mb-4">
                <button id="boldBtn"
                    class="px-2 py-1 border border-slate-300 rounded font-bold cursor-pointer">B</button>
                <button id="italicBtn"
                    class="px-2 py-1 border border-slate-300 rounded italic cursor-pointer">I</button>
                <button id="bulletBtn" class="px-2 py-1 border border-slate-300 rounded cursor-pointer">•
                    List</button>
                <button id="linkBtn" class="px-2 py-1 border border-slate-300 rounded cursor-pointer">🔗
                    Link</button>
                <div class="">
                    <input type="file" id="fileUpload" class="hidden" multiple>
                    <label for="fileUpload"
                        class="inline-block cursor-pointer bg-transparent border border-slate-300 text-sm px-4 py-2 rounded">
                        <i class="fa-regular fa-images"></i> Media
                    </label>
                </div>
            </div>

            <!-- Editable Message Box -->
            <div id="messageBox" contenteditable="true"
                class="border border-gray-300 rounded-lg p-3 min-h-[150px] outline-none bg-white mb-4">
            </div>

            <div class="w-full items-center flex justify-end">
                <button id="sendBtn"
                    class="bg-stone-900 text-white px-4 py-2 rounded-lg hover:bg-stone-600 transition cursor-pointer">
                    Comment
                </button>
            </div>
        </div>



        <!-- Custom Link Popup -->
        <div id="linkModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                <h2 class="text-lg font-semibold mb-2">Insert a Link</h2>
                <input id="linkInput" type="text" placeholder="https://example.com"
                    class="w-full border px-3 py-2 rounded mb-4 focus:outline-none focus:ring focus:ring-blue-300">
                <div class="flex justify-end space-x-2">
                    <button id="cancelLinkBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button id="insertLinkBtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Insert</button>
                </div>
            </div>
        </div>

    </div>
</section>