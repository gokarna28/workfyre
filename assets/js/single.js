
$(document).ready(function () {
    let currentColumn = '';
    let taskIdCounter = 1;

    // Show modal
    $(document).on('click', '.add-task-btn', function () {
        currentColumn = $(this).data('target');
        $('#taskInput').val('');
        $('#taskModal').removeClass('hidden');
        // Clear form and messages when opening modal
        $('#createTaskForm').trigger('reset');
        $('#taskCreateSuccessMessage').html('');
    });

    // Hide modal with cancel button
    $('#cancelBtn').on('click', function () {
        $('#taskModal').addClass('hidden');
        $('#createTaskForm').trigger('reset');
        $('#taskCreateSuccessMessage').html('');
    });

    // Close modal with X button
    $('#closeTaskModal').on('click', function () {
        $('#taskModal').addClass('hidden');
        $('#createTaskForm').trigger('reset');
        $('#taskCreateSuccessMessage').html('');
    });

    // Close modal when clicking outside
    $('#taskModal').on('click', function (e) {
        if (e.target === this) {
            $(this).addClass('hidden');
            $('#createTaskForm').trigger('reset');
            $('#taskCreateSuccessMessage').html('');
        }
    });

    // Close modal with ESC key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && !$('#taskModal').hasClass('hidden')) {
            $('#taskModal').addClass('hidden');
            $('#createTaskForm').trigger('reset');
            $('#taskCreateSuccessMessage').html('');
        }
    });

    // Add task from modal
    $('#createTaskForm').on('submit', function (e) {
        e.preventDefault();
        const projectId = $(this).find('input[name="project_id"]').val().trim();
        const taskTitle = $(this).find('input[name="task_title"]').val().trim();
        const taskDeadline = $(this).find('input[name="task_deadline"]').val().trim();
        const taskAssignto = $(this).find('select[name="task_assign"]').val().trim();
        // const taskDependencies = $(this).find('select[name="task_dependencies"]').val().trim();
        const taskDescription = $(this).find('textarea[name="task_description"]').val().trim();

        let taskDependencies = [];
        $(this).find('.task-dependency-checkbox:checked').each(function () {
            taskDependencies.push($(this).val());
        });

        var fileInput = $(this).find('input[name="task_attachments[]"]')[0];

        // Create FormData object
        var formData = new FormData();
        formData.append('project_id', projectId);
        formData.append('task_title', taskTitle);
        formData.append('task_deadline', taskDeadline);
        formData.append('task_assignto', taskAssignto);
        formData.append('task_dependencies', JSON.stringify(taskDependencies));
        formData.append('task_description', taskDescription);
        formData.append('action', 'create_task');

        if (fileInput && fileInput.files.length > 0) {
            for (var i = 0; i < fileInput.files.length; i++) {
                formData.append('task_attachments[]', fileInput.files[i]);
            }
        }

        if (taskTitle !== '') {
            const columnId = $(`#${currentColumn}`)[0].id;

            let task; // use let instead of const since we may reassign

            $.ajax({
                type: 'POST',
                url: 'http://workfyre.local/main/dashboard/ajax-project.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    // return;
                    if (response.status == 'success') {

                        $('#taskCreateSuccessMessage').html(`
                        <div class="bg-green-100 text-green-700 border border-green-300 rounded-lg py-3 px-4 text-sm">${response.message}</div>
                         `)

                        // Reset form
                        $('#createTaskForm').trigger('reset');

                        // Update Gantt chart with new task
                        if (typeof updateChartWithNewTask === 'function' && response.task_card_details) {
                            // Prepare task data for chart update
                            const chartTaskData = {
                                id: response.task_card_details.task_id,
                                title: response.task_card_details.title,
                                created_at: response.task_card_details.created_at, // Use actual created date from server
                                deadline: response.task_card_details.deadline,
                                priority: response.task_card_details.priority,
                                assigned_to: response.task_card_details.assignto_user,
                                critical: response.task_card_details.critical || 0 // Use critical status from server response
                            };
                            updateChartWithNewTask(chartTaskData);
                        }

                        //push the container
                        // if (columnId === 'todo') {
                        console.log(response.task_card_details);
                        
                        // Check if current user can modify this task (newly created tasks are always modifiable by creator)
                        const canModify = true; // Newly created tasks are always modifiable
                        const dragClass = canModify ? 'cursor-move' : 'cursor-not-allowed';
                        const draggable = canModify ? 'true' : 'false';
                        const permissionIndicator = canModify ? 'text-green-600' : 'text-red-600';
                        const permissionText = canModify ? 'You can modify' : 'Assigned to another user';
                        
                        $('#todo').prepend(`
                                        <div id="tasks${response.task_card_details.task_id}" class="p-3 bg-gray-100 rounded shadow-md ${dragClass}"
                                        draggable="${draggable}" data-task_id="${response.task_card_details.task_id}">
                                        
                                        <!-- Permission Indicator -->
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2 text-xs ${permissionIndicator}">
                                                <i class="fa-solid ${canModify ? 'fa-check-circle' : 'fa-lock'}"></i>
                                                <span>${permissionText}</span>
                                            </div>
                                        </div>
                                        
                                        <a
                                            href="http://workfyre.local/main/dashboard/templates/tasks.php?pid=${response.task_card_details.project_id}&tid=${response.task_card_details.task_id}">
                                            <h2 class="text-xl font-medium">${response.task_card_details.title}</h2>
                                            <P class="text-sm mb-2 w-full">
                                            ${response.task_card_details.description}
                                            </p>
                                            <div
                                                class="flex items-center mb-2 border-b border-slate-300 p-2 justify-between text-sm font-light">
                                                <P>${response.task_card_details.deadline}</p>
                                                <span
                                                    class="${response.task_card_details.priority_class} rounded-full flex px-2 items-center justify-center">${response.task_card_details.priority}
                                                    </spam>
                                            </div>

                                            <div class="flex items-center justify-between text-sm relative">
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-regular fa-comments"></i>
                                                    <p>12 Comments</p>
                                                </div>
                                                <div class="flex items-center gap-1"><span>Assign to:</span>
                                                    
                                                    <span
                                                        class="assignUserProfile rounded-full ml-1 font-medium border border-slate-300 flex items-center justify-center w-6 h-6 overflow-hidden">
                                                        <img src="http://workfyre.local/assets/images/default-profile.png"
                                                            class="w-full h-full object-cover" alt="default profile" />
                                                    </span>
                                                    <div
                                                        class="userNameTooltip bg-slate-900/80 text-white absolute px-4 py-2 rounded top-5 left-8 flex z-50 hidden group-hover:flex">
                                                        <span>${response.task_card_details.assignto_user}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                            `);
                        
                        // Hide modal after successful creation
                        setTimeout(() => {
                            $('#taskModal').addClass('hidden');
                            $('#taskCreateSuccessMessage').html('');
                        }, 2000);

                    } else {
                        $('#taskCreateSuccessMessage').html(`
                        <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-3 px-4 text-sm">${response.message}</div>
                         `)
                    }
                },
                error: function (xhr, status, error) {
                    console.log("An error occurred: " + error);
                }
            });


            // Add drag event
            task.on('dragstart', function (e) {
                e.originalEvent.dataTransfer.setData('text/plain', this.id);
            });



        }
    });
    //date piker dissable past date
    const today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('min', today);

    // Drag & drop logic
    $('.task-column').on('dragover', function (e) {
        e.preventDefault();
        
        // Get the task being dragged
        const draggedTaskId = e.originalEvent.dataTransfer.getData('text/plain');
        if (!draggedTaskId) return;
        
        // Check if the task is draggable (has permission)
        const taskElement = document.getElementById(draggedTaskId);
        if (!taskElement || taskElement.getAttribute('draggable') === 'false') {
            return; // Don't allow drag feedback for non-draggable tasks
        }
        
        // Extract task ID from the element ID
        const taskId = draggedTaskId.replace('tasks', '');
        
        // Determine target status based on current column
        let targetStatus = 'not-started';
        if (this.id === 'inprogress') {
            targetStatus = 'in-progress';
        } else if (this.id === 'done') {
            targetStatus = 'completed';
        }
        
        // Check if the move is allowed (async)
        canMoveTaskToStatus(taskId, targetStatus).then(canMove => {
            // Show visual feedback
            if (taskElement) {
                showDragFeedback(taskElement, canMove, targetStatus);
            }
        });
    });

    $('.task-column').on('dragleave', function (e) {
        // Reset visual feedback when leaving drop zone
        const draggedTaskId = e.originalEvent.dataTransfer.getData('text/plain');
        if (draggedTaskId) {
            const taskElement = document.getElementById(draggedTaskId);
            if (taskElement) {
                resetDragFeedback(taskElement);
            }
        }
    });

    $('.task-column').on('drop', function (e) {
        e.preventDefault();
        const taskId = e.originalEvent.dataTransfer.getData('text/plain');
        const task = document.getElementById(taskId);
        
        // Check if the task is draggable (has permission)
        if (!task || task.getAttribute('draggable') === 'false') {
            showPermissionError();
            return;
        }
        
        // Extract task ID from the element ID
        const taskIdNum = taskId.replace('tasks', '');
        
        // Determine target status based on current column
        let targetStatus = 'not-started';
        if (this.id === 'inprogress') {
            targetStatus = 'in-progress';
        } else if (this.id === 'done') {
            targetStatus = 'completed';
        }
        
        // Check if the move is allowed (async)
        canMoveTaskToStatus(taskIdNum, targetStatus).then(canMove => {
            if (!canMove) {
                // Show error message and prevent drop
                showTaskMoveError(taskIdNum, targetStatus);
                resetDragFeedback(task);
                return;
            }
            
            // Reset visual feedback
            resetDragFeedback(task);
            
            // Allow the drop
            this.appendChild(task);

            // Update task styling based on new status
            if (this.id === 'inprogress') {
                $(task).removeClass('bg-gray-100 bg-sky-200').addClass('bg-yellow-200');
            } else if (this.id === 'done') {
                $(task).removeClass('bg-gray-100 bg-yellow-200').addClass('bg-sky-200');
            } else {
                $(task).removeClass('bg-yellow-200 bg-sky-200').addClass('bg-gray-100');
            }

            // Update task status via AJAX
            var params = {
                task_id: taskIdNum,
                task_status: targetStatus,
                action: 'update_task_status'
            };
            ajaxTaskStatus(params);
        });
    });

    // Function to show error message for invalid task moves
    function showTaskMoveError(taskId, targetStatus) {
        const taskElement = document.getElementById('tasks' + taskId);
        if (!taskElement) return;

        // Get task priority
        const priorityBadge = taskElement.querySelector('.rounded-full');
        const priority = priorityBadge ? priorityBadge.textContent.trim() : 'Low';

        let errorMessage = '';
        if (targetStatus === 'completed') {
            if (priority.toLowerCase() === 'low') {
                errorMessage = 'Low priority tasks must go through "In Progress" first';
            } else {
                errorMessage = 'Task dependencies must be completed first';
            }
        } else if (targetStatus === 'in-progress') {
            errorMessage = 'Task dependencies must be completed first';
        }

        // Show temporary error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50';
        errorDiv.textContent = errorMessage;
        document.body.appendChild(errorDiv);

        // Remove error message after 3 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 3000);
    }

    //task status update function
    function ajaxTaskStatus(taskData) {
        $.ajax({
            type: 'POST',
            url: 'http://workfyre.local/main/dashboard/ajax-project.php',
            data: taskData,
            success: function (response) {
                console.log(response);
                
                if (response.status === 'success') {
                    // Show success message
                    showStatusUpdateMessage(response.message, 'success');
                } else if (response.status === 'error') {
                    // Show error message
                    showStatusUpdateMessage(response.message, 'error');
                    
                    // If it's a permission error, revert the visual change
                    if (response.message.includes('permission')) {
                        // You might want to revert the task to its original position
                        // This would require storing the original position before the drop
                    }
                }
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
                showStatusUpdateMessage('Failed to update task status. Please try again.', 'error');
            }
        });
    }

    // Function to show status update messages
    function showStatusUpdateMessage(message, type) {
        const messageClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const errorDiv = document.createElement('div');
        errorDiv.className = `fixed top-4 right-4 ${messageClass} text-white px-4 py-2 rounded shadow-lg z-50`;
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);

        // Remove message after 3 seconds
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 3000);
    }

    // Function to check if task can be moved to target status
    async function canMoveTaskToStatus(taskId, targetStatus) {
        // Get task details from the DOM
        const taskElement = document.getElementById('tasks' + taskId);
        if (!taskElement) return false;

        // Get task priority from the priority badge
        const priorityBadge = taskElement.querySelector('.rounded-full');
        const priority = priorityBadge ? priorityBadge.textContent.trim().toLowerCase() : 'low';

        // Get current status
        const currentStatus = getCurrentTaskStatus(taskElement);

        // Rules for task movement based on priority and dependencies
        const rules = {
            'not-started': {
                canMoveTo: ['in-progress', 'completed'],
                highPriority: true,  // High priority tasks can move directly to completed
                lowPriority: false   // Low priority tasks should go through in-progress first
            },
            'in-progress': {
                canMoveTo: ['not-started', 'completed'],
                highPriority: true,
                lowPriority: true
            },
            'completed': {
                canMoveTo: ['not-started', 'in-progress'],
                highPriority: true,
                lowPriority: true
            }
        };

        // Check if the move is allowed
        if (!rules[currentStatus] || !rules[currentStatus].canMoveTo.includes(targetStatus)) {
            return false;
        }

        // Special rule: Low priority tasks cannot skip in-progress
        if (priority === 'low' && currentStatus === 'not-started' && targetStatus === 'completed') {
            return false;
        }

        // Check dependencies (if task has dependencies, they should be completed first)
        if (targetStatus === 'in-progress' || targetStatus === 'completed') {
            const dependenciesCompleted = await areDependenciesCompleted(taskId);
            if (!dependenciesCompleted) {
                return false;
            }
        }

        return true;
    }

    // Function to get current task status based on parent container
    function getCurrentTaskStatus(taskElement) {
        const parentContainer = taskElement.closest('.task-column');
        if (!parentContainer) return 'not-started';

        const containerId = parentContainer.id;
        switch (containerId) {
            case 'todo':
                return 'not-started';
            case 'inprogress':
                return 'in-progress';
            case 'done':
                return 'completed';
            default:
                return 'not-started';
        }
    }

    // Function to check if task dependencies are completed
    function areDependenciesCompleted(taskId) {
        // Make AJAX call to check dependencies
        return new Promise((resolve) => {
            $.ajax({
                type: 'POST',
                url: 'http://workfyre.local/main/dashboard/ajax-project.php',
                data: {
                    action: 'check_task_dependencies',
                    task_id: taskId
                },
                success: function (response) {
                    if (response.status === 'success') {
                        resolve(response.dependencies_completed);
                    } else {
                        resolve(false);
                    }
                },
                error: function () {
                    resolve(false);
                }
            });
        });
    }

    // Function to show drag feedback
    function showDragFeedback(taskElement, canMove, targetStatus) {
        if (canMove) {
            taskElement.style.opacity = '0.7';
            taskElement.style.transform = 'scale(1.02)';
        } else {
            taskElement.style.opacity = '0.3';
            taskElement.style.transform = 'scale(0.98)';
            // Add visual feedback for invalid drop
            taskElement.style.border = '2px dashed #ef4444';
        }
    }

    // Function to reset drag feedback
    function resetDragFeedback(taskElement) {
        taskElement.style.opacity = '';
        taskElement.style.transform = '';
        taskElement.style.border = '';
    }

    // Make existing tasks (if any) draggable (optional)
    $(document).on('dragstart', '.task-column > div', function (e) {
        e.originalEvent.dataTransfer.setData('text/plain', this.id);
    });

    $('.assignUserProfile').hover(
        function () {
            $(this).siblings('.userNameTooltip').fadeIn(150); // show on hover in
        },
        function () {
            $(this).siblings('.userNameTooltip').fadeOut(150); // hide on hover out
        }
    );


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


    /***Invite team start */
    //opan the invite team form
    $('#inviteTeamBtn').on('click', function () {
        $('#inviteTeamForm').removeClass('hidden');
    })
    //close the modal
    $('#inviteTeamCancelBtn').on('click', function () {
        $('#inviteTeamForm').addClass('hidden');
    });
    //open the invite tab
    $('#header-invite-team').on('click', function(){
        $('#projectTeam').trigger('click');
        $('#inviteTeamBtn').trigger('click');
    })
    


    $('#inviteTeamForm').on('submit', function (e) {
        e.preventDefault(); // Prevent actual form submission

        // Get all checked user_ids
        const userIds = [];
        $('input[name="user_id[]"]:checked').each(function () {
            userIds.push($(this).val());
        });

        // Get project_id
        const projectId = $('input[name="project_id"]').val();

        var data = {
            user_ids: userIds,
            project_id: projectId,
            action: 'invite_team'
        }
        inviteTeam(data);
    });

    function inviteTeam(data) {
        $.ajax({
            type: 'POST',
            url: 'http://workfyre.local/main/dashboard/ajax-project.php',
            data: data,
            success: function (response) {

                if (response.status == 'success') {

                    $('#inviteTeamSuccessMessage').html(`
                    <div class="bg-green-100 text-green-300 border border-green-300 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                     `)
                    //push the container
                    response.project_meta.forEach(element => {
                        $(`#invitation_container${data.project_id}`).prepend(`
                            <li class="mb-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-5">
                                        <span class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-8 h-8 overflow-hidden">
                                            <img src="http://workfyre.local/assets/images/default-profile.png"
                                                 class="w-full h-full object-cover" alt="default profile" />
                                        </span>
                                        <p class="text-lg font-medium">${element.firstname + ' ' + element.lastname}</p>
                                    </div>
                                    <div class="text-sm">Email:<span class="ml-2 text-sm font-light">${element.email}</span></div>
                                    <div class="text-sm">Status:
                                        <span class="bg-yellow-200 text-yellow-500 ml-2 px-2 rounded-full text-sm">
                                            ${String(element.status).charAt(0).toUpperCase() + String(element.status).slice(1)}
                                        </span>
                                    </div>
                                    <span class="text-sm font-light">${element.created_at}</span>
                                </div>
                            </li>
                        `);
                    });


                    setTimeout(() => {
                        $('#inviteTeamForm').addClass('hidden');
                    }, 2000);
                } else {
                    $('#inviteTeamSuccessMessage').html(`
                    <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                     `)
                }
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    }
    /**Invite team end */

    /**settings tab start */
    $(document).ready(function () {
        $('.tab-btn').click(function () {
            const tabId = $(this).data('tab');

            // Toggle tab content
            $('.tab-content').addClass('hidden');
            $('#' + tabId).removeClass('hidden');

            // Toggle active button style
            $('.tab-btn').removeClass('text-blue-600 border-blue-500 border-b-2').addClass('text-gray-500');
            $(this).addClass('text-blue-600 border-blue-500 border-b-2').removeClass('text-gray-500');
        });
    });
    /**settings tab ends */

    //preview image while select 
    $('#img-upload').on('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#preview-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
})