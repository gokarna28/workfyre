<?php include_once('../../sidebar.php'); ?>
<?php include_once('../../header.php'); ?>
<?php
$project_id = isset($_GET['pid']) ? $_GET['pid'] : "";
$task_id = isset($_GET['tid']) ? $_GET['tid'] : "";
$project = getProjectDetailsByProjectID($project_id);
$task = getTasksDetailsByTask_id($task_id);


$attachments = getTaskAttachments($task_id);

?>

<section class="pt-25 pl-85 w-full pr-10">
    <div class="mb-5">
        <div class="flex items-center gap-5">
            <a href="http://workfyre.local/main/dashboard/templates/single.php?pid=<?php echo $project_id;?>"
                class="hover:bg-slate-100 p-2 rounded-lg"><i class="fa-solid fa-arrow-left"></i></a>
            <h2 class="text-xl font-medium"><?php echo ucfirst($project['title']); ?></h2>
        </div>
        <p class="mb-5 text-sm pl-10">
            <?php echo strlen($project['description']) > 20 ? substr($project['description'], 0, 40) . '...' : $project['description']; ?>
        </p>
        <h2 class="text-lg font-medium"><?php echo ucfirst($task['title']) ?></h2>
        <p class="text-sm"><?php echo $task['description']; ?></p>
    </div>

    <div class="w-full flex items-center justify-between mb-10">
        <ul class="flex items-center w-1/2 border-b border-slate-300">
            <li id="projectBoard" class="mr-10 text-lg text-sky-700 border-b-2 border-sky-700 pb-4 cursor-pointer flex gap-2">
                <span><i class="fa-regular fa-comments"></i></span>Comments
            </li>
            <li id="projectFiles" class="mr-10 text-lg pb-4 gap-2 flex cursor-pointer"><span><i
                        class="fa-regular fa-file"></i></span>Files</li>
           
        </ul>
    </div>
    <!-- commnents container -->
    <div id="projectBoardContainer" class="w-full">

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
            <div id="linkModal"
                class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                    <h2 class="text-lg font-semibold mb-2">Insert a Link</h2>
                    <input id="linkInput" type="text" placeholder="https://example.com"
                        class="w-full border px-3 py-2 rounded mb-4 focus:outline-none focus:ring focus:ring-blue-300">
                    <div class="flex justify-end space-x-2">
                        <button id="cancelLinkBtn"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button id="insertLinkBtn"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Insert</button>
                    </div>
                </div>
            </div>

            <!-- image preview popup   -->
            <div id="imageModal"
                class="fixed inset-0 bg-gray-400 bg-opacity-80 z-50 items-center justify-center p-4 hidden">
                <div class="relative max-w-full max-h-full flex flex-col items-center">
                    <span class="flex w-full text-4xl items-center justify-end"><i
                            class="fa-regular fa-circle-xmark cursor-pointer" id="closeImageModal"></i></span>
                    <img id="modalImage" src="" alt="Preview"
                        class="max-w-full max-h-[90vh] rounded shadow-lg object-contain" />
                </div>
            </div>

        </div>
    </div>

    <!-- files container -->
    <div id="projectFilesContainer" class="hidden">
        <div class="mb-2" id="deleteProjectAttachmentSuccessMessage"></div>
        <ul>
            <?php
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $path = $attachment['attachment'];
                    $filename = basename($path);
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                    $isImage = in_array($extension, $imageExtensions);
                    $isOpenableFile = in_array($extension, ['pdf', 'zip']);

                    $previewSrc = $isImage ? $path : '/assets/images/default-file-icon.png';

                    // Start <a> tag if it's a PDF or ZIP
                    if ($isOpenableFile) {
                        echo '<a href="' . $path . '" target="_blank">';
                    }
                    ?>
                    <li id="projectAttachmentConainer<?php echo $attachment['id'] ?>"
                        class="shadow-sm p-2 rounded-lg flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <span
                                class="rounded-lg font-medium border border-slate-300 flex items-center justify-center w-20 h-20 overflow-hidden bg-white">
                                <img src="<?php echo $previewSrc; ?>"
                                    class="preview-image cursor-pointer w-full h-full object-cover"
                                    alt="<?php echo $filename; ?>" />
                            </span>
                            <p class="text-sm"><?php echo $filename; ?></p>
                        </div>
                        <?php
                        // Close <a> tag if started
                        if ($isOpenableFile) {
                            echo '</a>';
                        }
                        ?>
                        <div class="flex items-center gap-2">

                            <button
                                class="deleteTaskAttachment border border-slate-300 px-4 py-2 rounded-lg hover:bg-red-800 hover:text-white"
                                data-attachment_id="<?php echo $attachment['id'] ?>">
                                Delete
                            </button>
                        </div>
                    </li>
                    <?php

                }
            }else{
                echo "No Attachments Found.";
            }

            ?>

        </ul>
    </div>
   

</section>