
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
            const columnId = $(`#${currentColumn}`)[0].id;

            let task; // use let instead of const since we may reassign

            if (columnId === 'todo') {
                task = $(`
                    <div id="tasks${taskIdCounter++}" class="p-3 bg-gray-100 rounded shadow-md cursor-move" draggable="true">
                    <a href="http://workfyre.local/main/dashboard/templates/tasks.php">
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
                    <a href="http://workfyre.local/main/dashboard/templates/tasks.php">
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
                    <a href="http://workfyre.local/main/dashboard/templates/tasks.php">
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




    /** comment start */
    let savedRange = null;
    let clearedPlaceholder = false;

    $(function () {
        // Formatting helper
        function format(command, value = null) {
            const box = document.getElementById('messageBox');
            box.focus();
            document.execCommand(command, false, value);
            $('a').attr('target', '_blank');
        }

        // Format buttons
        $('#boldBtn').click(() => format('bold'));
        $('#italicBtn').click(() => format('italic'));
        $('#bulletBtn').click(() => format('insertUnorderedList'));

        // Clear placeholder on first focus
        $('#messageBox').on('focus', function () {
            if (!clearedPlaceholder) {
                $(this).html('');
                clearedPlaceholder = true;
            }
        });

        // Save/restore selection for links
        function saveSelection() {
            const sel = window.getSelection();
            if (sel.rangeCount > 0) {
                savedRange = sel.getRangeAt(0);
            }
        }

        function restoreSelection() {
            if (savedRange) {
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(savedRange);
            }
        }

        // Link logic
        $('#linkBtn').click(() => {
            saveSelection();
            $('#linkModal').removeClass('hidden');
            $('#linkInput').val('').focus();
        });

        $('#insertLinkBtn').click(() => {
            const url = $('#linkInput').val().trim();

            // Validate the URL using a simple regex pattern
            const urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;

            if (url && urlPattern.test(url)) {
                restoreSelection();
                console.log(url)
                format('createLink', url);

                $('#linkModal').addClass('hidden');
            } else {
                // Show alert if the URL is not valid
                alert("Please enter a valid URL.");
            }
        });

        $('#cancelLinkBtn').click(() => {
            $('#linkModal').addClass('hidden');
        });

        // File preview
        $('#fileUpload').on('change', function () {
            const files = this.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = `
                            <br>
                            <img src="${e.target.result}" 
                                 class="preview-image inline-block max-w-[150px] max-h-[150px] m-2 rounded shadow cursor-pointer" />
                        `;
                        $('#messageBox').append(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#messageBox').append(
                        `<div class="text-sm text-gray-500 my-2">📎 ${file.name}</div>`
                    );
                }
            }
        });
        // Show modal when clicking an image
        $(document).on('click', '.preview-image', function () {
            const src = $(this).attr('src');
            $('#modalImage').attr('src', src);
            $('#imageModal').removeClass('hidden');
        });

        // Close modal
        $('#closeImageModal, #imageModal').on('click', function (e) {
            if (e.target.id === 'imageModal' || e.target.id === 'closeImageModal') {
                $('#imageModal').addClass('hidden');
            }
        });

        // Send message
        $('#sendBtn').click(() => {
            const message = $('#messageBox').html().trim();
            if (!message) {
                alert("Please write a message or attach a file.");
                return;
            }
            const messageHtml = `
         <div class="p-4 border border-gray-300 bg-white rounded-lg mb-4">
                <div class="flex items-center gap-2 justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span
                            class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-8 h-8 overflow-hidden">
                            <img src="http://workfyre.local/assets/images/default-profile.png"
                                class="w-full h-full object-cover" alt="default profile" />
                        </span>
                        <p class="text-sm font-medium">Ramesh Karki</p>
                    </div>
                    <p class="text-sm font-thin">Apr-22-2025<span class="ml-2">4:41 PM</span></p>
                </div>
                <div class="text-gray-800 text-wrap">${message}</div>
            </div>

          </div>
        `;
            $('#messageList').append(messageHtml);
            $('#messageBox').html('');
            $('#fileUpload').val('');
            clearedPlaceholder = false;
        });
    });
    /**comment ends */

    /**single tabs start*/
    const projectBoard = $('#projectBoard');
    const projectFiles = $('#projectFiles');
    const projectTeam = $('#projectTeam');
    const projectBoardContainer = $('#projectBoardContainer');
    const projectFilesContainer = $('#projectFilesContainer');
    const projectTeamContainer = $('#projectTeamContainer');

    projectBoard.on("click", function () {
        projectBoardContainer.removeClass('hidden');
        projectFilesContainer.addClass('hidden');
        projectTeamContainer.addClass('hidden');

        projectBoard.addClass('text-sky-700 border-b-2 border-sky-700');
        projectFiles.removeClass('text-sky-700 border-b-2 border-sky-700');
        projectTeam.removeClass('text-sky-700 border-b-2 border-sky-700');
    })
    projectFiles.on("click", function () {
        projectBoardContainer.addClass('hidden');
        projectFilesContainer.removeClass('hidden');
        projectTeamContainer.addClass('hidden');

        projectBoard.removeClass('text-sky-700 border-b-2 border-sky-700');
        projectFiles.addClass('text-sky-700 border-b-2 border-sky-700');
        projectTeam.removeClass('text-sky-700 border-b-2 border-sky-700');
    })
    projectTeam.on("click", function () {
        projectBoardContainer.addClass('hidden');
        projectFilesContainer.addClass('hidden');
        projectTeamContainer.removeClass('hidden');

        projectBoard.removeClass('text-sky-700 border-b-2 border-sky-700');
        projectFiles.removeClass('text-sky-700 border-b-2 border-sky-700');
        projectTeam.addClass('text-sky-700 border-b-2 border-sky-700');
    })
    /**single tabs ends*/






    /**createing a project start here */
    $(document).on('click', '#createNewProject', function () {
        currentColumn = $(this).data('target');
        $('#projectModal').removeClass('hidden');
    });

    // Hide modal
    $('#cancelBtn').on('click', function () {
        $('#projectModal').addClass('hidden');
    });
    /**creating a project ends here */

})