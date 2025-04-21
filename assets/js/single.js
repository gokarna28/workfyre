
$(document).ready(function () {
    let currentColumn = '';
    let taskIdCounter = 1;

    // Show modal
    $(document).on('click', '.add-task-btn', function () {
        currentColumn = $(this).data('target');
        $('#taskInput').val('');
        $('#taskModal').removeClass('hidden');
    });

    // Hide modal
    $('#cancelBtn').on('click', function () {
        $('#taskModal').addClass('hidden');
    });

    // Add task from modal
    $('#addTaskBtn').on('click', function () {
        const taskTitle = $('#task_title').val().trim();
        const taskPriority = $('#task_priority').val().trim();
        const taskDeadline = $('#task_deadline').val().trim();
        const taskAssignto = $('#task_assign').val().trim();
        const taskAttachments = $('#task_attachments').val().trim();
        const taskDependencies = $('#task_dependencies').val().trim();
        const taskDescriptions = $('#task_description').val().trim();

        if (taskTitle !== '') {
            const columnId = $(`#${currentColumn}`)[0].id; // get the column id

            let task; // use let instead of const since we may reassign

            if (columnId === 'todo') {
                task = $(`
                    <div id="tasks${taskIdCounter++}" class="p-3 bg-gray-100 rounded shadow-md cursor-move" draggable="true">
                    <a href="templates/tasks.php">
                        <h2 class="text-xl font-medium">${taskTitle}</h2>
                        <P class="text-sm mb-2">${taskDescriptions}</p>
                        <div
                            class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                            <P>${taskDeadline}</p>
                            <span class="bg-indigo-400 text-white rounded-full px-2 items-center justify-center">${taskPriority}
                                </spam>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-1">
                                <i class="fa-regular fa-comments"></i>
                                <p>12 comments</p>
                            </div>
                            <div class="flex items-center gap-1"><span>Assign to:</span>
                                <span
                                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                    <img src="http://workfyre.local/assets/images/default-profile.png"
                                        class="w-full h-full object-cover" alt="default profile" />
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                `);
            } else if (columnId === 'inprogress') {
                task = $(`
                    <div id="tasks${taskIdCounter++}" class="p-3 bg-yellow-200 rounded shadow-md cursor-move" draggable="true">
                    <a href="templates/tasks.php">
                        <h2 class="text-xl font-medium">${taskTitle}</h2>
                        <P class="text-sm mb-2">${taskDescriptions}</p>
                        <div
                            class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                            <P>${taskDeadline}</p>
                            <span class="bg-stone-400 text-white rounded-full px-2 items-center justify-center">${taskPriority}
                                </spam>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-1">
                                <i class="fa-regular fa-comments"></i>
                                <p>12 comments</p>
                            </div>
                            <div class="flex items-center gap-1"><span>Assign to:</span>
                                <span
                                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                    <img src="http://workfyre.local/assets/images/default-profile.png"
                                        class="w-full h-full object-cover" alt="default profile" />
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                `);
            } else if (columnId === 'done') {
                task = $(`
                    <div id="tasks${taskIdCounter++}" class="p-3 bg-sky-200 rounded shadow-md cursor-move" draggable="true">
                    <a href="templates/tasks.php">
                        <h2 class="text-xl font-medium">${taskTitle}1</h2>
                        <P class="text-sm mb-2">${taskDescriptions}</p>
                        <div
                            class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                            <P>${taskDeadline}</p>
                            <span class="bg-green-400 text-white rounded-full px-2 items-center justify-center">${taskPriority}
                                </spam>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-1">
                                <i class="fa-regular fa-comments"></i>
                                <p>12 comments</p>
                            </div>
                            <div class="flex items-center gap-1"><span>Assign to:</span>
                                <span
                                    class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                    <img src="http://workfyre.local/assets/images/default-profile.png"
                                        class="w-full h-full object-cover" alt="default profile" />
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                `);
            }

            // Add drag event
            task.on('dragstart', function (e) {
                e.originalEvent.dataTransfer.setData('text/plain', this.id);
            });

            // Append task to the correct column
            $(`#${currentColumn}`).append(task);

            // Hide the modal
            $('#taskModal').addClass('hidden');
        }
    });


    // Drag & drop logic
    $('.task-column').on('dragover', function (e) {
        e.preventDefault();
    });

    $('.task-column').on('drop', function (e) {
        e.preventDefault();
        const taskId = e.originalEvent.dataTransfer.getData('text/plain');
        const task = document.getElementById(taskId);
        this.appendChild(task);
        if (this.id === 'inprogress') {
            $(task).removeClass('bg-gray-100');
            $(task).removeClass('bg-sky-200');
            $(task).addClass('bg-yellow-200');
        } else if (this.id === 'done') {
            $(task).removeClass('bg-gray-100');
            $(task).removeClass('bg-yellow-200');
            $(task).addClass('bg-sky-200');
        } else {
            $(task).removeClass('bg-yellow-200');
            $(task).removeClass('bg-sky-200');
            $(task).addClass('bg-gray-100');
        }
    });

    // Make existing tasks (if any) draggable (optional)
    $(document).on('dragstart', '.task-column > div', function (e) {
        e.originalEvent.dataTransfer.setData('text/plain', this.id);
    });
})