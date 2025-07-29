<?php include_once('../../sidebar.php'); ?>
<?php include_once('../../header.php'); ?>
<?php
$project_id = isset($_GET['pid']) ? $_GET['pid'] : "";
$task_id = isset($_GET['tid']) ? $_GET['tid'] : "";
$project = getProjectDetailsByProjectID($project_id);
$task = getTasksDetailsByTask_id($task_id);
$attachments = getTaskAttachments($task_id);

// Get current user for permission checks
$currentUser = getCurrentUser();
$canModify = canUserModifyTask($task_id);
?>

<section class="py-25 pl-85 w-full pr-10">
    <!-- Enhanced Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="http://workfyre.local/main/dashboard/templates/single.php?pid=<?php echo $project_id;?>"
                class="flex items-center justify-center w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-300 group">
                <i class="fa-solid fa-arrow-left text-lg group-hover:scale-110 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900"><?php echo ucfirst($task['title']); ?></h1>
                <p class="text-gray-600 mt-1">Project: <?php echo ucfirst($project['title']); ?></p>
            </div>
        </div>
    </div>

    <!-- Task Details Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
        <div class="grid grid-cols-1 gap-8">
            <!-- Main Task Info -->
            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Task Details</h2>
                    <p class="text-gray-700 leading-relaxed"><?php echo $task['description']; ?></p>
                </div>
                
                <!-- Task Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-calendar text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Deadline</p>
                                <p class="font-semibold text-gray-900"><?php echo $task['deadline']; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-flag text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Priority</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo getClasses($task['priority']); ?>">
                                    <?php echo ucfirst($task['priority']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-check-circle text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    <?php 
                                    switch($task['status']) {
                                        case 'completed': echo 'bg-green-100 text-green-800'; break;
                                        case 'in-progress': echo 'bg-yellow-100 text-yellow-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800'; break;
                                    }
                                    ?>">
                                    <?php echo ucfirst(str_replace('-', ' ', $task['status'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-user text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Assigned To</p>
                                <?php $assignUser = getUsersDetailsByUser_id($task['assign_to']); ?>
                                <p class="font-semibold text-gray-900">
                                    <?php echo is_array($assignUser) ? $assignUser['firstname'] . ' ' . $assignUser['lastname'] : 'Unassigned'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="w-full flex items-center justify-between mb-8">
        <ul class="flex items-center border-b border-gray-200">
            <li id="projectBoard" class="mr-8 text-lg text-blue-600 border-b-2 border-blue-600 pb-4 cursor-pointer flex gap-2 items-center">
                <i class="fa-regular fa-comments"></i>
                <span>Comments</span>
            </li>
            <li id="projectFiles" class="mr-8 text-lg pb-4 gap-2 flex cursor-pointer items-center text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fa-regular fa-file"></i>
                <span>Files</span>
            </li>
        </ul>
    </div>

    <!-- Comments Container -->
    <div id="projectBoardContainer" class="w-full">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <!-- Message List -->
            <div id="messageList" class="w-full mx-auto mb-8 space-y-6">
                <!-- Sent messages will appear here -->
            </div>
            
            <!-- Comment Input Section -->
            <div class="bg-gray-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add a Comment</h3>
                
                <!-- Toolbar -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button id="boldBtn" class="px-3 py-2 bg-white border border-gray-200 rounded-lg font-bold cursor-pointer hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-bold"></i>
                    </button>
                    <button id="italicBtn" class="px-3 py-2 bg-white border border-gray-200 rounded-lg italic cursor-pointer hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-italic"></i>
                    </button>
                    <button id="bulletBtn" class="px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-list-ul"></i>
                    </button>
                    <button id="linkBtn" class="px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-link"></i>
                    </button>
                    <div class="relative">
                        <input type="file" id="fileUpload" class="hidden" multiple>
                        <label for="fileUpload" class="inline-block cursor-pointer bg-white border border-gray-200 text-sm px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fa-regular fa-images"></i>
                        </label>
                    </div>
                </div>

                <!-- Editable Message Box -->
                <div id="messageBox" contenteditable="true" class="border border-gray-200 rounded-lg p-4 min-h-[120px] outline-none bg-white mb-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Write your comment here..."></div>

                <div class="flex justify-end">
                    <button id="sendBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Post Comment</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Custom Link Popup -->
        <div id="linkModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-xl shadow-2xl max-w-sm w-full mx-4">
                <h2 class="text-lg font-semibold mb-4">Insert a Link</h2>
                <input id="linkInput" type="text" placeholder="https://example.com" class="w-full border border-gray-200 px-4 py-3 rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="flex justify-end gap-3">
                    <button id="cancelLinkBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button id="insertLinkBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Insert</button>
                </div>
            </div>
        </div>

        <!-- Image Preview Popup -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 items-center justify-center p-4 hidden">
            <div class="relative max-w-full max-h-full flex flex-col items-center">
                <button class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition-colors" id="closeImageModal">
                    <i class="fa-regular fa-circle-xmark"></i>
                </button>
                <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain" />
            </div>
        </div>
    </div>

    <!-- Files Container -->
    <div id="projectFilesContainer" class="hidden">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Task Files</h3>
                <?php if ($canModify): ?>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Files</span>
                </button>
                <?php endif; ?>
            </div>
            
            <div class="mb-2" id="deleteProjectAttachmentSuccessMessage"></div>
            
            <?php if (!empty($attachments)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($attachments as $attachment): 
                    $path = $attachment['attachment'];
                    $filename = basename($path);
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    $isImage = in_array($extension, $imageExtensions);
                    $isOpenableFile = in_array($extension, ['pdf', 'zip']);
                    $previewSrc = $isImage ? $path : '/assets/images/default-file-icon.png';
                ?>
                <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <?php if ($isOpenableFile): ?>
                    <a href="<?php echo $path; ?>" target="_blank" class="block">
                    <?php endif; ?>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden">
                            <img src="<?php echo $previewSrc; ?>" class="w-full h-full object-cover" alt="<?php echo $filename; ?>" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo $filename; ?></p>
                            <p class="text-xs text-gray-500"><?php echo strtoupper($extension); ?> file</p>
                        </div>
                    </div>
                    
                    <?php if ($isOpenableFile): ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($canModify): ?>
                    <div class="mt-3 flex justify-end">
                        <button class="deleteTaskAttachment text-red-600 hover:text-red-700 text-sm font-medium transition-colors" data-attachment_id="<?php echo $attachment['id']; ?>">
                            <i class="fa-solid fa-trash mr-1"></i>Delete
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <i class="fa-regular fa-file text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">No files attached to this task</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>