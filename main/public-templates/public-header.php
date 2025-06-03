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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

</head>

<body class="">

    <header
        class="bg-[#1a143b] text-white px-10 py-4 flex items-center justify-between w-full top-0 border-b border-slate-700 border-dashed z-20">

        <div class="w-1/2">
            <a href="<?php echo HOMEPAGE_URL ?>" class="flex items-center" >
                <span
                    class="font-medium flex items-center justify-center w-14 h-14 overflow-hidden p-2">
                    <img src="http://workfyre.local/assets/images/logo.png"
                        class="w-full h-full object-cover" alt="default profile" />
                </span>
                <h1 class="text-3xl font-bold font-['Josefin Sans">Workfyre</h1>
            </a>
        </div>
        <div class="w-full px-4 flex justify-between items-center text-sm text-white">
            <nav class="space-x-6">
                <a href="#" class="hover:text-sky-500">About</a>
                <a href="#testimonial" class="hover:text-sky-500">Testimonial</a>
                <a href="#" class="hover:text-sky-500">Features</a>
                <a href="#your-work" class="hover:text-sky-500">Your Work</a>
                <a href="#contact" class="hover:text-sky-500">Contact</a>
            </nav>
        </div>
        <div class="gap-4 flex items-center">
            <a href="<?php echo HOMEPAGE_URL ?>/main/login.php"
                class="rounded-full py-2 px-6 text-lg bg-[#6c63ff]">Login</a>
            <!-- <a href="<?php //echo HOMEPAGE_URL ?>/main/register.php"
                class="border border-slate-300 hover:bg-stone-300 rounded-lg py-2 px-6 text-lg">Register</a> -->
        </div>

    </header>