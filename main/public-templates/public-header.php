<?php
include_once(__DIR__ . '/../../config/config.php');
include_once(__DIR__ . '/../../config/functions.php');
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
    <script src="<?php echo PUBLIC_PATH ?>/js/public-js.js"></script>
</head>

<body class="bg-[#181832] text-white">

    <header class="bg-transparent px-10 py-4 flex items-center justify-between z-2 w-full top-0">
        <div class="w-1/2">
            <a href="<?php echo HOMEPAGE_URL ?>">
                <h1 class="text-3xl font-bold">Workfyre</h1>
            </a>
        </div>
        <div>
            nav bar
        </div>
        <div class="gap-4 flex items-center">
            <a href="<?php echo HOMEPAGE_URL ?>/main/login.php"
                class="border border-slate-300 rounded-lg py-2 px-6 text-lg bg-stone-300">Login</a>
            <a href="<?php echo HOMEPAGE_URL ?>/main/register.php"
                class="border border-slate-300 hover:bg-stone-300 rounded-lg py-2 px-6 text-lg">Register</a>
        </div>

    </header>