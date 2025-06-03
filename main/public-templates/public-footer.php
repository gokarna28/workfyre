<?php
include_once(__DIR__ . '/../../config/config.php');
?>


<section class="bg-black py-4 border-t border-gray-800">
    <div class="max-w-6xl mx-auto px-4 flex justify-between items-center text-sm text-white">
        <div class="flex items-center gap-2">
            <span class="font-medium flex items-center justify-center w-10 h-10 overflow-hidden p-2">
                <img src="http://workfyre.local/assets/images/logo.png" class="w-full h-full object-cover"
                    alt="default profile" />
            </span>
            <h1 class="text-xl font-bold font-['Josefin Sans">Workfyre</h1>
        </div>
        <nav class="space-x-6">
            <a href="#" class="hover:text-sky-500">About</a>
            <a href="#" class="hover:text-sky-500">Testimonial</a>
            <a href="#" class="hover:text-sky-500">Features</a>
            <a href="#" class="hover:text-sky-500">Demo</a>
            <a href="#" class="hover:text-sky-500">Blog</a>
            <a href="#" class="hover:text-sky-500">Contact</a>
        </nav>
    </div>
</section>

<!-- scroll to top   -->
<button id="scrollToTopBtn"
    class="fixed bottom-6 right-6 bg-gradient-to-t from-blue-400 to-purple-500 text-3xl font-bold hover:bg-cyan-700 text-white px-4 py-2 shadow-lg hidden">
    <i class="fa-solid fa-arrow-up"></i>
</button>
<script src="<?php echo PUBLIC_PATH ?>/js/public-js.js"></script>

</body>

</html>