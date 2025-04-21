<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kanban Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .dragging {
            opacity: 0.5;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen p-6 relative">

    <h1 class="text-3xl font-bold mb-6 text-center">Kanban Board</h1>

    <div class="grid grid-cols-3 gap-4">
        <!-- To Do Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">To Do</h2>
                <button class="add-task-btn text-sm px-2 py-1 bg-white-500 text-black border boarder-slate-300  rounded hover:text-white hover:bg-stone-900"
                    data-target="todo">+ Add</button>
            </div>
            <div id="todo" class="task-column space-y-3 min-h-[200px]"></div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">In Progress</h2>
                <button class="add-task-btn text-sm px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                    data-target="inprogress">+ Add</button>
            </div>
            <div id="inprogress" class="task-column space-y-3 min-h-[200px]"></div>
        </div>

        <!-- Done Column -->
        <div class="bg-white rounded shadow p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Done</h2>
                <button class="add-task-btn text-sm px-2 py-1 bg-blue-500 text-white rounded hover:bg-green-600"
                    data-target="done">+ Add</button>
            </div>
            <div id="done" class="task-column space-y-3 min-h-[200px]"></div>
        </div>
    </div>

    <!-- Modal -->
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Add Task</h3>
            <input type="text" id="taskInput"
                class="w-full border p-2 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Task name">
            <div class="flex justify-end space-x-2">
                <button id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button id="addTaskBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                    Task</button>
            </div>
        </div>
    </div>

    <script>
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
            const taskText = $('#taskInput').val().trim();
            if (taskText !== '') {
                const task = $(`
          <div id="task${taskIdCounter++}" class="p-3 bg-blue-100 rounded shadow cursor-move" draggable="true">${taskText}</div>
        `);

                task.on('dragstart', function (e) {
                    e.originalEvent.dataTransfer.setData('text/plain', this.id);
                });

                $(`#${currentColumn}`).append(task);
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
        });

        // Make existing tasks (if any) draggable (optional)
        $(document).on('dragstart', '.task-column > div', function (e) {
            e.originalEvent.dataTransfer.setData('text/plain', this.id);
        });
    </script>

</body>

</html>