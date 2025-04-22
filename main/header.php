<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="http://workfyre.local/assets/js/single.js"></script>
</head>

<body>
    <header
        class="bg-white px-10 py-4 flex items-center justify-between border-b border-slate-300 z-2 fixed w-full top-0">
        <div class="w-1/2">
            <h1 class="text-3xl font-bold">Workfyre</h1>
        </div>
        <div class="flex items-center w-full gap-5 justify-between">
            <div class="bg-slate-100 rounded-full py-2 px-4 w-full">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>
                <input type="text" name="search" placeholder="Search here" class="outline-none" />
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