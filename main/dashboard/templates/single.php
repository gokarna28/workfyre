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

        <p class="text-sm pl-10"><?php echo $project['description'] ?></p>
    </div>

    <div class="w-full flex items-center justify-between mb-10">
        <ul class="flex items-center w-1/2 border-b border-slate-300">
            <li id="projectBoard" class="mr-10 text-lg text-sky-700 border-b-2 border-sky-700 pb-4 cursor-pointer flex gap-2">
                <span><i class="fa-solid fa-chart-bar"></i></span>Board
            </li>
            <li id="projectFiles" class="mr-10 text-lg pb-4 cursor-pointer flex gap-2"><span><i
                        class="fa-regular fa-file"></i></span>Files</li>
            <li id="projectTeam" class="mr-10 text-lg pb-4 cursor-pointer flex gap-2"><span><i
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
                <?php
                $taskDetails = getTasksDetailsByStatus($project_id, 'not-started');
                if (isset($taskDetails) && is_array($taskDetails)) {
                    foreach ($taskDetails as $taskCard) {
                        ?>
                        <div id="tasks<?php echo $taskCard['id']; ?>" class="p-3 bg-gray-100 rounded shadow-md cursor-move"
                            draggable="true" data-task_id="<?php echo $taskCard['id'];?>">
                            <a
                                href="http://workfyre.local/main/dashboard/templates/tasks.php?pid=<?php echo $_GET['pid']; ?>&tid=<?php echo $taskCard['id']; ?>">
                                <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                <P class="text-sm mb-2 w-full">
                                    <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                </p>
                                <div
                                    class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                    <P><?php echo $taskCard['deadline']; ?></p>
                                    <span
                                        class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                        </spam>
                                </div>

                                <div class="flex items-center justify-between text-sm relative">
                                    <div class="flex items-center gap-1">
                                        <i class="fa-regular fa-comments"></i>
                                        <p>12 Comments</p>
                                    </div>
                                    <div class="flex items-center gap-1"><span>Assign to:</span>
                                        <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                        <span
                                            class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                            <img src="http://workfyre.local/assets/images/default-profile.png"
                                                class="w-full h-full object-cover" alt="default profile" />
                                        </span>
                                        <div
                                            class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                            <span><?php echo $assignUser['firstname'] . ' ' . $assignUser['lastname']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>

            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">In Progress</h2>
                <!-- <button class="add-task-btn text-sm px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                    data-target="inprogress">+ Add</button> -->
            </div>
            <div id="inprogress" class="task-column space-y-3 min-h-[200px]">
                <?php
                $taskDetails = getTasksDetailsByStatus($project_id, 'in-progress');
                if (isset($taskDetails) && is_array($taskDetails)) {
                    foreach ($taskDetails as $taskCard) {
                        ?>
                        <div id="tasks<?php echo $taskCard['id']; ?>" class="p-3 bg-yellow-200 rounded shadow-md cursor-move"
                            draggable="true" data-task_id="<?php echo $taskCard['id'];?>">
                            <a
                                href="http://workfyre.local/main/dashboard/templates/tasks.php?pid=<?php echo $_GET['pid']; ?>&tid=<?php echo $taskCard['id']; ?>">
                                <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                <P class="text-sm mb-2 w-full">
                                    <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                </p>
                                <div
                                    class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                    <P><?php echo $taskCard['deadline']; ?></p>
                                    <span
                                        class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                        </spam>
                                </div>

                                <div class="flex items-center justify-between text-sm relative">
                                    <div class="flex items-center gap-1">
                                        <i class="fa-regular fa-comments"></i>
                                        <p>12 Comments</p>
                                    </div>
                                    <div class="flex items-center gap-1"><span>Assign to:</span>
                                        <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                        <span
                                            class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                            <img src="http://workfyre.local/assets/images/default-profile.png"
                                                class="w-full h-full object-cover" alt="default profile" />
                                        </span>
                                        <div
                                            class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                            <span><?php echo $assignUser['firstname'] . ' ' . $assignUser['lastname']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Done</h2>
                <!-- <button class="add-task-btn text-sm px-2 py-1 bg-sky-500 text-white rounded hover:bg-sky-600"
                    data-target="done">+ Add</button> -->
            </div>
            <div id="done" class="task-column space-y-3 min-h-[200px]">
                <?php
                $taskDetails = getTasksDetailsByStatus($project_id,'completed');
                if (isset($taskDetails) && is_array($taskDetails)) {
                    foreach ($taskDetails as $taskCard) {
                        ?>
                        <div id="tasks<?php echo $taskCard['id']; ?>" class="p-3 bg-sky-200 rounded shadow-md cursor-move"
                            draggable="true" data-task_id="<?php echo $taskCard['id'];?>">
                            <a
                                href="http://workfyre.local/main/dashboard/templates/tasks.php?pid=<?php echo $_GET['pid']; ?>&tid=<?php echo $taskCard['id']; ?>">
                                <h2 class="text-xl font-medium"><?php echo $taskCard['title']; ?></h2>
                                <P class="text-sm mb-2 w-full">
                                    <?php echo strlen($taskCard['description']) > 20 ? substr($taskCard['description'], 0, 40) . '...' : $taskCard['description']; ?>
                                </p>
                                <div
                                    class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                    <P><?php echo $taskCard['deadline']; ?></p>
                                    <span
                                        class="<?php echo getClasses($taskCard['priority']); ?> rounded-full flex px-2 items-center justify-center"><?php echo $taskCard['priority']; ?>
                                        </spam>
                                </div>

                                <div class="flex items-center justify-between text-sm relative">
                                    <div class="flex items-center gap-1">
                                        <i class="fa-regular fa-comments"></i>
                                        <p>12 Comments</p>
                                    </div>
                                    <div class="flex items-center gap-1"><span>Assign to:</span>
                                        <?php $assignUser = getUsersDetailsByUser_id($taskCard['assign_to']); ?>
                                        <span
                                            class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                            <img src="http://workfyre.local/assets/images/default-profile.png"
                                                class="w-full h-full object-cover" alt="default profile" />
                                        </span>
                                        <div
                                            class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                            <span><?php echo $assignUser['firstname'] . ' ' . $assignUser['lastname']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
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
            }else{
                echo "No Attachments Found.";
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
        <div class="border-t border-slate-300 p-4">
            <ul id="invitation_container<?php echo $project_id; ?>">
                <?php
                $projectMeta = getProjectMeta($project_id);
                if (is_array($projectMeta) && isset($projectMeta)) {
                    foreach ($projectMeta as $projectM) {
                        ?>
                        <li class="mb-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-5">
                                    <span
                                        class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-8 h-8 overflow-hidden">
                                        <img src="http://workfyre.local/assets/images/default-profile.png"
                                            class="w-full h-full object-cover" alt="default profile" />
                                    </span>
                                    <p class="text-lg font-medium">
                                        <?php echo $projectM['firstname'] . ' ' . $projectM['lastname']; ?>
                                    </p>
                                </div>
                                <div class="text-sm">Email:<span class="ml-2 text-sm font-light">gokarnachy28@gmail.com</span>
                                </div>
                                <div class="text-sm">status:<span
                                        class="<?php echo getClasses($projectM['status']) ?> ml-2 px-2 rounded-full text-sm"><?php echo ucfirst($projectM['status']); ?></span>
                                </div>
                                <span class="text-sm font-light"><?php echo $projectM['created_at'] ?></span>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    echo "No User Found.";
                }
                ?>

            </ul>
        </div>


        <!-- invite team modal -->
        <div id="inviteTeamForm" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
                <h3 class="text-xl font-semibold mb-4">Invite Team to the <span
                        class="font-bold"><?php echo $project['title'] ?></span></h3>
                <div class="mb-2" id="inviteTeamSuccessMessage"></div>
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
                            Team</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- carate task modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-1/2 shadow-lg">
            <form id="createTaskForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                <h3 class="text-xl font-semibold mb-4">Add Task</h3>
                <div class="mb-2" id="taskCreateSuccessMessage"></div>
                <div>
                    <label>Title:</label>
                    <input type="text" id="task_title" name="task_title"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Task title"
                        required>
                </div>
                <div class="flex items-center w-full gap-5">
                    <div class="w-1/2">
                        <label>priority:</label>
                        <select id="task_priority" name="task_priority"
                            class="w-full border p-2 rounded mb-4 border border-slate-300">
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="w-1/2">
                        <label>Deadline:</label>
                        <input type="date" id="task_deadline" name="task_deadline"
                            class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Task title"
                            required>
                    </div>
                </div>
                <div class="flex items-center w-full gap-5">
                    <div class="w-full">
                        <label class="block mb-2 font-medium text-slate-700">Dependencies:</label>
                        <div id="task_dependencies_wrapper"
                            class="mb-4 space-y-1 border border-slate-300 rounded p-4 max-h-30 snap-y overflow-y-auto">
                            <?php
                            $tasks = getTasksDetailsByProject_id($project['id']);
                            if (is_array($tasks)) {
                                foreach ($tasks as $task) {
                                    ?>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="task_dependencies[]" value="<?php echo $task['id']; ?>"
                                            class="task-dependency-checkbox">
                                        <span><?php echo ucfirst($task['title']); ?></span>
                                    </label>
                                    <?php
                                }
                            } else {
                                echo "No Task Yet.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="flex items-center w-full gap-5">

                    <div class="w-1/2">
                        <label>Assign to:</label>
                        <select id="task_assign" name="task_assign"
                            class="w-full border p-2 rounded mb-4 border border-slate-300" required>
                            <option value="">assign to</option>
                            <?php $users = getProjectMetaByStatus($project['id'], 'inrolled');
                            if ($users) {
                                foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['id'] ?>">
                                        <?php echo ucfirst($user['firstname'] . ' ' . $user['lastname']) ?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label>Description:</label>
                    <textarea id="task_description" name="task_description"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="description"
                        required></textarea>
                </div>

                <div>
                    <label>Attachments:</label>
                    <input type="file" id="task_attachments" name="task_attachments[]"
                        class="w-full border p-2 rounded mb-4 border border-slate-300" placeholder="Task title"
                        multiple>
                </div>
                <div class="flex justify-end space-x-2">
                    <button id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" id="addTaskBtn" name="addTaskBtn"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                        Task</button>
                </div>
            </form>

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