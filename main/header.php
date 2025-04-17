<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"
        integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+"
        crossorigin="anonymous"></script>
</head>

<body>
    <header
        class="bg-transparent px-10 py-4 flex items-center justify-between border-b border-slate-300 z-2 absolute w-full top-0">
        <div class="w-1/2">
            <h1 class="text-3xl font-bold">Workfyre</h1>
        </div>
        <div class="flex items-center w-1/2 pl-70 gap-5 justify-right">
            <div class="md:gap-4 gap-0 w-full">
                <ul>
                    <li class="flex items-center gap-0 md:gap-4 font-medium">
                        <span
                            class="rounded-full font-medium border border-slate-300 items-center justify-center">
                            <i class="fa-solid fa-plus text-xl text-blue-500 p-4"></i>
                        </span>
                        <h2 class="text-xl hidden md:block">Create Projects</h2>
                    </li>
                </ul>
            </div>
            <div class="w-full">
                <ul class="flex items-center justify-center gap-5">
                    <li class="flex items-center gap-4 font-medium">
                        <span
                            class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden p-2">
                            <img src="http://workfyre.local/assets/images/notification-bell.png"
                                class="w-full h-full object-cover" alt="default profile" />
                        </span>
                    </li>
                    <li class="flex items-center gap-4 font-medium">
                        <span
                            class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden p-2">
                            <i class="fa-solid fa-gear md:text-xl text-xl"></i>
                        </span>
                    </li>
                    <li class="flex items-center gap-4 font-medium">
                        <span
                            class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden p-2">
                            <img src="http://workfyre.local/assets/images/default-profile.png"
                                class="w-full h-full object-cover" alt="default profile" />
                        </span>

                    </li>
                </ul>

            </div>
        </div>
    </header>