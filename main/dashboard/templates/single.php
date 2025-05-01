<?php include_once('../../sidebar.php'); ?>
<?php include_once('../../header.php'); ?>

<section class="pt-25 pl-85 w-full pr-10">

    <?php
    $project_id = isset($_GET['pid']) ? $_GET['pid'] : "";
    $project = getProjectDetailsByProjectID($project_id);

    $attachments = getProjectAttachments($project_id);
    ?>

    <div class="mb-5">
        <div class="flex items-center gap-5 mb-2">
            <a href="http://workfyre.local/main/dashboard/projects.php" class="hover:bg-slate-100 p-2 rounded-lg"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <h2 class="text-xl font-medium"><?php echo $project['title']; ?></h2>
        </div>

        <p class="text-sm pl-15"><?php echo $project['description'] ?></p>
    </div>

    <div class="w-full flex items-center justify-between mb-10">
        <ul class="flex items-center w-1/2 border-b border-slate-300">
            <li id="projectBoard" class="mr-10 text-lg text-sky-700 border-b-2 border-sky-700 pb-4 cursor-pointer">
                <span><i class="fa-solid fa-chart-bar"></i></span>Board
            </li>
            <li id="projectFiles" class="mr-10 text-lg pb-4 cursor-pointer"><span><i
                        class="fa-regular fa-file"></i></span>Files</li>
            <li id="projectTeam" class="mr-10 text-lg pb-4 cursor-pointer"><span><i
                        class="fa-solid fa-users"></i></span>Team</li>
        </ul>

    </div>
    <!-- board container -->
    <div id="projectBoardContainer" class="grid grid-cols-3 gap-4 mb-5">
        <!-- To Do Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">To Do</h2>
                <button
                    class="add-task-btn text-sm px-2 py-1 bg-gray-100 hover:bg-gray-200 border border-slate-300 rounded hover:bg-blue-600"
                    data-target="todo">+ Add</button>
            </div>
            <div id="todo" class="task-column space-y-3 min-h-[200px] max-h-[700px] snap-y overflow-y-auto">
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">In Progress</h2>
                <button class="add-task-btn text-sm px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                    data-target="inprogress">+ Add</button>
            </div>
            <div id="inprogress" class="task-column space-y-3 min-h-[200px]">

            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Done</h2>
                <button class="add-task-btn text-sm px-2 py-1 bg-sky-500 text-white rounded hover:bg-sky-600"
                    data-target="done">+ Add</button>
            </div>
            <div id="done" class="task-column space-y-3 min-h-[200px]">

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
                                class="deleteProjectAttachment border border-slate-300 px-4 py-2 rounded-lg hover:bg-red-800 hover:text-white"
                                data-attachment_id="<?php echo $attachment['id'] ?>">
                                Delete
                            </button>
                        </div>
                    </li>
                    <?php

                }
            }

            ?>

        </ul>
    </div>
    <!-- team container -->
    <div id="projectTeamContainer" class="hidden">
        <div class="items-center justify-end flex mb-5">
            <button id="inviteTeamBtn"
                class="border border-slate-300 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white">
                <i class="fa-solid fa-user-plus"></i>
                Invite Team
            </button>
        </div>
        <div class="">
            <ul>
                <li>user1</li>
                <li>user1</li>
                <li>user1</li>
                <li>user1</li>
                <li>user1</li>
                <li>user1</li>
                <li>user1</li>
            </ul>
        </div>


        <!-- invite team modal -->
        <div id="inviteTeamForm" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Invite Team to the <span
                        class="font-bold"><?php echo $project['title'] ?></span></h3>
                <form id="inviteTeamForm" method="POST">
                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">

                    <div
                        class="flex flex-col items-start justify-center w-full gap-5 border-t border-slate-300 p-4 w-full">
                        <ul class="w-full max-h-[500px] snap-y overflow-y-auto">
                            <?php
                            // var_dump(getUsersDetails());
                            $users = getUsersDetails();
                            if ($users) {
                                foreach ($users as $user) {
                                    ?>
                                    <li class="mb-5 w-full">
                                        <label class="flex items-center gap-5 hover:bg-slate-100 p-3 rounded-lg">
                                            <input class="w-6 h-6" type="checkbox" name="user_id[]"
                                                value="<?php echo $user['id']; ?>">
                                            <div class="flex items-center justify-between w-full">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-8 h-8 overflow-hidden">
                                                        <img src="http://workfyre.local/assets/images/default-profile.png"
                                                            class="w-full h-full object-cover" alt="default profile" />
                                                    </span>
                                                    <p class="text-lg">
                                                        <?php echo ucfirst($user['firstname'] . ' ' . $user['lastname']) ?>
                                                    </p>
                                                </div>
                                                <p>Email:<span
                                                        class="ml-2 text-sm font-light"><?php echo $user['email'] ?></span></p>
                                            </div>
                                        </label>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button id="inviteTeamCancelBtn"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                            Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Add Task</h3>
            <div>
                <label>Title:</label>
                <input type="text" id="task_title" class="w-full border p-2 rounded mb-4 border border-slate-300"
                    placeholder="Task title">
            </div>
            <div class="flex items-center w-full gap-5">
                <div class="w-1/2">
                    <label>priority:</label>
                    <select id="task_priority" class="w-full border p-2 rounded mb-4 border border-slate-300">
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label>Deadline:</label>
                    <input type="date" id="task_deadline" class="w-full border p-2 rounded mb-4 border border-slate-300"
                        placeholder="Task title">
                </div>
            </div>
            <div class="flex items-center w-full gap-5">
                <div class="w-1/2">
                    <label>Dependencies:</label>
                    <select id="task_dependencies" class="w-full border p-2 rounded mb-4 border border-slate-300">
                        <option value="task1">task1</option>
                        <option value="task2">task2</option>
                        <option value="task3">task3</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label>Assign to:</label>
                    <select id="task_assign" class="w-full border p-2 rounded mb-4 border border-slate-300">
                        <option value="user1">user1</option>
                        <option value="user2">user2</option>
                        <option value="user3">user3</option>
                    </select>
                </div>
            </div>
            <div>
                <label>Description:</label>
                <textarea id="task_description" class="w-full border p-2 rounded mb-4 border border-slate-300"
                    placeholder="description"></textarea>
            </div>

            <div>
                <label>Attachments:</label>
                <input type="file" id="task_attachments" class="w-full border p-2 rounded mb-4 border border-slate-300"
                    placeholder="Task title">
            </div>
            <div class="flex justify-end space-x-2">
                <button id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button id="addTaskBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                    Task</button>
            </div>
        </div>
    </div>
    <!-- image preview popup   -->
    <div id="imageModal" class="fixed inset-0 bg-gray-400 bg-opacity-80 z-50 items-center justify-center p-4 hidden">
        <div class="relative max-w-full max-h-full flex flex-col items-center">
            <span class="flex w-full text-4xl items-center justify-end"><i
                    class="fa-regular fa-circle-xmark cursor-pointer" id="closeImageModal"></i></span>
            <img id="modalImage" src="" alt="Preview"
                class="max-w-full max-h-[90vh] rounded shadow-lg object-contain" />
        </div>
    </div>
</section>