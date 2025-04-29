<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<section class="pt-25 pl-85 w-full pr-10">
    <h2>Project Dashboard</h2>

    <div class="flex items-center w-full justify-between mb-5">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-medium">All Projects</h2>
            <span class="bg-slate-200 rounded-full items-center justify-center">
                <p>12</p>
            </span>
        </div>
        <div class="">
            <button id="createNewProject"
                class="flex items-center gap-2 hover:bg-stone-900 hover:text-white border cursor-pointer border-slate-300 px-4 py-2 rounded-2xl">
                <i class="fa-solid fa-plus"></i>
                <p class="text-lg font-medium">New Project</p>
            </button>
        </div>
    </div>
    <div class="w-full flex items-center">
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
    <div class="w-full">
        <table class="w-full">
            <thead class="bg-slate-100 w-full border-b border-slate-300">
                <th class="text-lg text-start font-normal px-4 py-2">Project Name</th>
                <th class="text-lg text-start font-normal px-4 py-2">Start Date</th>
                <th class="text-lg text-start font-normal px-4 py-2">Deadline</th>
                <th class="text-lg text-start font-normal px-4 py-2">Status</th>
                <th class="text-lg text-start font-normal px-4 py-2">Priority</th>
                <th class="text-lg text-start font-normal px-4 py-2"></th>
            </thead>
            <tbody>
                <?php
                // var_dump(getProjectDetails());
                $projects = getProjectDetails();
                if (!empty($projects)) {
                    foreach ($projects as $project) {
                        ?>
                        <tr onclick="window.location='/main/dashboard/templates/single.php';"
                            class="cursor-pointer hover:bg-slate-100">
                            <td class="p-4"><?php echo  $shortTitle = mb_strlen($project['title']) > 20 ? mb_substr($project['title'], 0, 20) . '...' : $project['title'];?></td>
                            <td class="p-4 text-sm font-thin"><?php echo $project['created_at']; ?></td>
                            <td class="p-4 text-sm font-thin"><?php echo $project['deadline']; ?></td>
                            <td class="p-4">
                                <span
                                    class="<?php echo getClasses($project['status']); ?> px-2 rounded-full items-center justify-center"><?php echo ucwords(str_replace('_', ' ', $project['status'])); ?></span>
                            </td>
                            <td class="p-4">
                                <span
                                    class="<?php echo getClasses($project['priority']); ?> px-2 rounded-full items-center justify-center"><?php echo ucwords(str_replace('_', ' ', subject: $project['priority'])); ?></span>
                            </td>
                            <td class="p-4">:</td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "No Project Found.";
                }
                ?>
               
            </tbody>
        </table>
    </div>
    <!-- project Modal -->
    <div id="projectModal" class="fixed inset-0 bg-gray-500/50 flex items-center justify-center hidden z-50">
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
                    <input type="file" id="project_attachments" name="project_attachments" multiple />
                    <label for="project_attachments"
                        class="block w-full cursor-pointer border p-2 rounded mb-4 border-slate-300 bg-white text-gray-600 text-center hover:bg-gray-100">
                        Click to select attachments
                    </label>

                    <div id="previewContainer" class="mb-4"></div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Add Task
                    </button>
                </div>
            </form>

        </div>
    </div>
</section>