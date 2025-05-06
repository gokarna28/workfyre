$(document).ready(function () {
    /**createing a project start here */
    $(document).on('click', '#createNewProject', function () {
        currentColumn = $(this).data('target');
        $('#projectModal').removeClass('hidden');
    });

    // Hide modal
    $('#cancelBtn').on('click', function () {
        $('#projectModal').addClass('hidden');
    });

    let filesToUpload = [];

    $('#project_attachments').on('change', function (e) {
        const newFiles = Array.from(e.target.files);
        filesToUpload = filesToUpload.concat(newFiles);

        // Clear the input so previously selected files don't linger
        // $(this).val('');
        renderPreview();
    });

    function renderPreview() {
        $('#previewContainer').html('');

        filesToUpload.forEach((file, index) => {
            const fileRow = $('<div class="flex items-center gap-2 mb-2"></div>');

            // If it's an image, show preview
            if (file.type.startsWith('image/')) {
                const img = $('<img class="w-12 h-12 object-cover rounded" />');
                img.attr('src', URL.createObjectURL(file));
                fileRow.append(img);
            }

            // Filename and remove
            fileRow.append(`<span class="text-sm">${file.name}</span>`);
            const removeBtn = $('<button type="button" class="text-red-500 text-xs">Remove</button>');
            removeBtn.on('click', function () {
                filesToUpload.splice(index, 1);
                renderPreview();
            });
            fileRow.append(removeBtn);

            $('#previewContainer').append(fileRow);
        });
    }

    $('#createProjectForm').submit(function (e) {
        e.preventDefault();

        var project_title = $(this).find('input[name="project_title"]').val().trim();
        var project_priority = $(this).find('select[name="project_priority"]').val().trim();
        var project_description = $(this).find('textarea[name="project_description"]').val().trim();


        var fileInput = $(this).find('input[name="project_attachments[]"]')[0];

        // Create FormData object
        var formData = new FormData();
        formData.append('project_title', project_title);
        formData.append('project_priority', project_priority);
        formData.append('project_description', project_description);
        formData.append('action', 'create_project');

        if (fileInput && fileInput.files.length > 0) {
            for (var i = 0; i < fileInput.files.length; i++) {
                formData.append('project_attachments[]', fileInput.files[i]);
            }
        }

        ajaxCreateProject(formData);

    });

    function ajaxCreateProject(formData) {
        $.ajax({
            type: 'POST',
            url: 'http://workfyre.local/main/dashboard/ajax-project.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log(response);
                if (response.status == 'success') {
                    $('#projectSuccessMessage').html(`
                        <div class="bg-green-100 text-green-300 border border-green-300 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                    `);
                    $('#createProjectForm').trigger('reset');
                    $('#previewContainer').html('');
                    setTimeout(() => {
                        $('#projectSuccessMessage').html('');
                    }, 2000);
                } else {
                    $('#projectSuccessMessage').html(`
                        <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                    `);
                }
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    }

    /**creating a project ends here */


    //delete project attachment
    $('.deleteProjectAttachment').on('click', function () {
        const attachmentId = $(this).data('attachment_id');

        var data = {
            attachmentId: attachmentId,
            action: 'delete_project_attachment'
        }
        $.ajax({
            type: 'POST',
            url: 'http://workfyre.local/main/dashboard/ajax-project.php',
            data: data,
            success: function (response) {
                if (response.status == 'success') {
                    $('#projectAttachmentConainer' + data.attachmentId).addClass('hidden');
                    $('#deleteProjectAttachmentSuccessMessage').html(`
                        <div class="bg-green-100 text-green-300 border border-green-300 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                    `);

                    setTimeout(() => {
                        $('#deleteProjectAttachmentSuccessMessage').html('');
                    }, 2000);
                } else {
                    $('#successMessage').html(`
                        <div class="bg-red-100 text-red-400 border border-red-400 rounded-lg py-3 px-4 text-xl">${response.message}</div>
                    `);
                }
            },
            error: function (xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });


    });



});