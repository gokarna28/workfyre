<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<section class="pt-25 pl-85 w-full pr-10">
    <h2>Project Dashboard</h2>

    <div class="flex items-center w-full justify-between mb-5">
        <div class="flex items-center gap-2">
            <h2 class="text-4xl font-medium">All Projects</h2>
            <span class="bg-slate-200 h-8 w-8 rounded-full flex items-center justify-center">
                <span><?php echo getTotalProjects(); ?></span>
            </span>
        </div>
        <!-- <div class="">
            <button id="createNewProject"
                class="flex items-center gap-2 hover:bg-[#1a143b] hover:text-white border cursor-pointer border-slate-300 px-4 py-2 rounded-full">
                <i class="fa-solid fa-plus"></i>
                <p class="text-lg font-medium">New Project</p>
            </button>
        </div> -->
    </div>
    <div class="w-full flex items-center">
        <div class="border flex border-slate-300 mb-5 w-35 p-2 rounded-full items-center">
            <i class="fa-solid fa-filter"></i>
            <select class="outline-none">
                <option value="">Add Filter</option>
                <option value="active">Active</option>
                <option value="in-progress">In progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <div class="w-full mb-10">
        <div class="w-[70%] flex flex-col justify-center">
            <?php
            $limit = 5;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Get total projects
            $totalProjects = getTotalProjects();
            $totalPages = ceil($totalProjects / $limit);

            // Fetch projects for current page
            $projects = getProjectDetails($limit, $offset);

            if (!empty($projects)) {
                foreach ($projects as $project) {
                    ?>
                    <div onclick="window.location='/main/dashboard/templates/single.php?pid=<?php echo $project['id']; ?>';"
                        class="w-full bg-white rounded-xl shadow-md p-4 mb-4">
                        <!-- Category -->
                        <p
                            class="text-sm text-blue-600 font-medium mb-1 rounded-full px-2 w-fit <?php echo getClasses($project['priority']); ?>">
                            <?php echo ucwords(str_replace('_', ' ', subject: $project['priority'])); ?>
                        </p>
                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            <?php echo $shortTitle = mb_strlen($project['title']) > 50 ? mb_substr($project['title'], 0, 50) . '...' : $project['title']; ?>
                        </h3>
                        <!-- Progress Section -->
                        <?php
                        $taskDetails = getTasksDetailsByProject_id($project['id']);

                        if (is_array($taskDetails)) {
                            $totalTask = count($taskDetails);
                            $completedTask = getTotalTaskByStatus($project['id'], 'completed');
                            $progress_percentage = $totalTask > 0 ? ($completedTask / $totalTask) * 100 : 0;
                            $progress_percentage = round($progress_percentage, 2);
                        } else {
                            // fallback if task details retrieval fails
                            $totalTask = 0;
                            $progress_percentage = 0;
                        }
                        //condition for progress bar
                        $progress_class = 'blue';
                        if ($progress_percentage > 25 && $progress_percentage != 100) {
                            $progress_class = 'orange';
                        } elseif ($progress_percentage >= 100) {
                            $progress_class = 'green';
                        }
                        ?>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-3/4">
                                <p class="text-sm text-gray-500 mb-1">Progress</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-<?php echo $progress_class; ?>-400 h-2.5 rounded-full"
                                        style="width: <?php echo $progress_percentage . '%'; ?>"></div>
                                </div>
                            </div>

                            <p class="text-lg font-semibold text-gray-900"><?php echo $progress_percentage . '%'; ?></p>
                        </div>
                        <!-- Avatars and Counters -->
                        <div class="flex items-center justify-between">
                            <div class="flex -space-x-2">
                                <?php
                                $projectMeta = getProjectMeta($project['id']);
                                if (is_array($projectMeta) && isset($projectMeta)) {
                                    foreach ($projectMeta as $projectM) {
                                        ?>
                                        <img src="https://i.pravatar.cc/40?img=4" alt="Avatar 1"
                                            class="w-8 h-8 rounded-full border-2 border-white">
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-1 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5v-2a2 2 0 012-2h10a2 2 0 012 2v2h-4m-6 0h.01M12 16h.01">
                                        </path>
                                    </svg>
                                    <span class="text-sm"><?php echo $totalTask; ?></span>
                                </div>
                                <div class="flex items-center space-x-1 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm"><?php echo isset($completedTask) ? $completedTask : 0; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No Project Found.";
            }
            ?>
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center mt-6 space-x-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>"
                            class="px-3 py-1 rounded-md border <?php echo $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border-blue-600'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
    
</section>