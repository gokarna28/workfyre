<?php
session_start();
include_once(__DIR__ . '/../config/config.php');
include_once(__DIR__ . '/../config/functions.php');

if (!isUserLoggedIn()) {
    header('Location: ' . HOMEPAGE_URL . '/main/login.php');
    exit();
}

$current_page = getCurrentPageName();

// Get current user data for dynamic profile image
$currentUser = getCurrentUser();
$userDetails = getUsersDetailsByUser_id($currentUser['id']);

// Check if current user is admin
$isAdmin = isUserAdmin($currentUser['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workfyre</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="http://workfyre.local/assets/js/team.js"></script>
    <script src="http://workfyre.local/assets/js/single.js"></script>
    <script src="http://workfyre.local/assets/js/project-js.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="http://workfyre.local/assets/js/script.js"></script>

<style>
    .hide-scrollbar {
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* Internet Explorer 10+ */
}

.hide-scrollbar::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Opera */
}

</style>
</head>

<body>
    <header class="bg-white px-10 py-4 flex items-center justify-between fixed pl-85 w-full top-0 z-40 shadow-lg border-b border-gray-200">

        <div class="flex items-center w-full gap-5 justify-between">
            <div class="bg-slate-100 border border-gray-100 rounded-full py-2 px-4 w-full">
                <i class="fa-solid fa-magnifying-glass mr-2"></i>
                <input type="text" name="search" placeholder="Search here" class="outline-none" />
            </div>
            <div class="w-full">

                <ul class="flex items-center justify-end gap-5">
                    <li>
                        <span id="notifiction_icon_header"
                            class="rounded-full cursor-pointer font-medium border border-slate-300 flex items-center justify-center w-10 h-10  p-2 relative">
                            <i class="fa-solid fa-bell"></i>
                            <!-- Notification count badge -->
                            <span
                                class="absolute -top-1 -right-3 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center border border-white p-0.5">+99</span>
                        </span>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="flex items-center gap-4 font-medium">
                        <button id="createNewProject"
                            class="flex items-center gap-2 hover:bg-[#1a143b] hover:text-white border cursor-pointer border-slate-300 px-4 py-2 rounded-full">
                            <i class="fa-solid fa-plus"></i>
                            <p class="text-lg font-medium">New Project</p>
                        </button>
                    </li>
                    <?php endif; ?>
                    <!-- <li class="flex items-center gap-4 font-medium">
                        <span
                            class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden p-2">
                            <i class="fa-solid fa-gear md:text-xl text-xl"></i>
                        </span>
                    </li> -->
                    <!-- <li class="flex items-center gap-4 font-medium">
                        <span
                            class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden">
                            <img src="<?php echo $userDetails['profile_image'] ?? 'https://i.pravatar.cc/40'; ?>"
                                class="w-full h-full object-cover" alt="profile" />
                        </span>

                    </li> -->
                </ul>

            </div>
        </div>
    </header>