<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Message Box</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        ul {
            list-style: disc;
            margin-left: 1.5rem;
        }

        /* Lightbox styles */
        #imageModal {
            display: none;
        }

        #imageModal.show {
            display: flex;
        }
    </style>
</head>

<body class="bg-gray-100 p-6 font-sans relative">

    <!-- Message List -->
    <div id="messageList" class="max-w-2xl mx-auto mt-6 space-y-4">
        <!-- Sent messages will appear here -->
    </div>

    <div class="max-w-2xl mx-auto bg-white p-4 rounded-xl shadow-md">
        <!-- Toolbar -->
        <div class="flex space-x-2 mb-4">
            <button id="boldBtn" class="px-2 py-1 border border-slate-300 rounded font-bold">B</button>
            <button id="italicBtn" class="px-2 py-1 border border-slate-300 rounded italic">I</button>
            <button id="bulletBtn" class="px-2 py-1 border border-slate-300 rounded">• List</button>
            <button id="linkBtn" class="px-2 py-1 border border-slate-300 rounded">🔗 Link</button>
            <div>
                <input type="file" id="fileUpload" class="hidden" multiple>
                <label for="fileUpload"
                    class="inline-block cursor-pointer bg-transparent border border-slate-300 text-sm px-4 py-2 rounded">
                    <i class="fa-regular fa-images"></i> Media
                </label>
            </div>
        </div>

        <!-- Editable Message Box -->
        <div id="messageBox" contenteditable="true"
            class="border border-gray-300 rounded p-3 min-h-[150px] focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white mb-4">
        </div>

        <div class="w-full items-center flex justify-end">
            <button id="sendBtn" class="bg-stone-900 text-white px-4 py-2 rounded hover:bg-stone-600 transition">
                Comment
            </button>
        </div>
    </div>

    <!-- Link Modal -->
    <div id="linkModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-2">Insert a Link</h2>
            <input id="linkInput" type="text" placeholder="https://example.com"
                class="w-full border px-3 py-2 rounded mb-4 focus:outline-none focus:ring focus:ring-blue-300">
            <div class="flex justify-end space-x-2">
                <button id="cancelLinkBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button id="insertLinkBtn"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Insert</button>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imageModal"
        class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-full max-h-full">
            <button id="closeImageModal"
                class="absolute top-0 right-0 text-white text-3xl font-bold bg-black bg-opacity-50 px-2">✖</button>
            <img id="modalImage" src="" alt="Preview"
                class="max-w-full max-h-[90vh] rounded shadow-lg object-contain" />
        </div>
    </div>

    <script>
        let savedRange = null;
        let clearedPlaceholder = false;

        $(function () {
            function format(command, value = null) {
                const box = document.getElementById('messageBox');
                box.focus();
                document.execCommand(command, false, value);
                $('a').attr('target', '_blank');
            }

            $('#boldBtn').click(() => format('bold'));
            $('#italicBtn').click(() => format('italic'));
            $('#bulletBtn').click(() => format('insertUnorderedList'));

            $('#messageBox').on('focus', function () {
                if (!clearedPlaceholder) {
                    $(this).html('');
                    clearedPlaceholder = true;
                }
            });

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

            $('#linkBtn').click(() => {
                saveSelection();
                $('#linkModal').removeClass('hidden');
                $('#linkInput').val('').focus();
            });

            $('#insertLinkBtn').click(() => {
                const url = $('#linkInput').val().trim();
                const urlPattern = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
                if (url && urlPattern.test(url)) {
                    restoreSelection();
                    format('createLink', url);
                    $('#linkModal').addClass('hidden');
                } else {
                    alert("Please enter a valid URL.");
                }
            });

            $('#cancelLinkBtn').click(() => {
                $('#linkModal').addClass('hidden');
            });

            // Upload & Preview with Popup
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
                $('#imageModal').addClass('show');
            });

            // Close modal
            $('#closeImageModal, #imageModal').on('click', function (e) {
                if (e.target.id === 'imageModal' || e.target.id === 'closeImageModal') {
                    $('#imageModal').removeClass('show');
                }
            });

            // Send Message
            $('#sendBtn').click(() => {
                const message = $('#messageBox').html().trim();
                if (!message) {
                    alert("Please write a message or attach a file.");
                    return;
                }

                const messageHtml = `
                    <div class="p-4 border border-gray-300 bg-white rounded shadow-sm mb-4">
                        <div class="text-gray-800">${message}</div>
                    </div>
                `;
                $('#messageList').append(messageHtml);
                $('#messageBox').html('');
                $('#fileUpload').val('');
                clearedPlaceholder = false;
            });
        });
    </script>

</body>

</html>
