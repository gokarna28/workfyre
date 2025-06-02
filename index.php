<?php
include_once('main/public-templates/public-header.php');

?>
<section class="w-full h-auto pt-40 bg-[#060026]">
    <div class="flex items-center justify-center w-full flex-col mb-20 text-[#181832]">
        <div class="text-center mb-6">
            <h1
                class="text-6xl font-bold bg-gradient-to-r from-purple-500 via-sky-300 to-purple-400 bg-clip-text text-transparent">
                Centralize your Project
            </h1>

            <h1
                class="text-6xl font-bold bg-gradient-to-r from-purple-500 via-sky-300 to-purple-400 bg-clip-text text-transparent">
                Management with AI Features</h1>
        </div>
        <div class="w-1/2 flex flex-col items-center justify-center gap-6 text-white">
            <p>Workfyre is a powerful project management platform built to help teams plan, track, and deliver projects
                with precision. Whether you're a solo creator or managing a team, Workfyre keeps everything organized,
                on schedule, and in sync.</p>
            <div class="flex items-center gap-10">
                <button
                    class="bg-sky-400 px-8 py-3 rounded-lg hover:bg-transparent text-lg border border-slate-400 shadow-md">Get
                    Started</button>
                <button class="px-8 py-3 rounded-lg hover:bg-sky-500 text-lg border border-slate-400 shadow-md">30-Day
                    Free
                    Trial</button>
            </div>
        </div>
    </div>
    <div class="px-50 pb-20">
        <span class="flex items-center justify-center w-full overflow-hidden rounded-3xl shadow-md">
            <img src="http://workfyre.local/assets/images/landing-image.png" class="w-full h-full object-cover"
                alt="default profile" />
        </span>
    </div>

</section>
<section class="w-full bg-[#f5f7ff] pt-20 pb-10 px-6">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-10">

        <!-- Chart Image -->
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="/assets/images/Screenshot 2025-06-02 170854.png" alt="Project Statistics Chart"
                class="max-w-full rounded-xl shadow-md" />
        </div>

        <!-- Text Content -->
        <div class="w-full md:w-1/2">
            <h2 class="text-4xl font-bold text-[#181832] mb-4 leading-tight">
                Project Statistics <br class="hidden md:block" /> & Analytics
            </h2>
            <p class="text-gray-600 text-base mb-6 leading-relaxed">
                Leverage powerful data-driven insights and detailed analytics to monitor and evaluate your project's
                performance in real-time, empowering informed decision-making and successful project outcomes.
            </p>
            <button
                class="bg-[#6c63ff] hover:bg-[#5a54e4] text-white rounded-full w-12 h-12 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-right transform rotate-315"></i>
            </button>

        </div>

    </div>
</section>
<section class="w-full bg-[#f5f7ff] pb-10 px-6">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-10">
        <!-- Text Content -->
        <div class="w-full md:w-1/2">
            <h2 class="text-4xl font-bold text-[#181832] mb-4 leading-tight">
                Project Statistics <br class="hidden md:block" /> & Analytics
            </h2>
            <p class="text-gray-600 text-base mb-6 leading-relaxed">
                Leverage powerful data-driven insights and detailed analytics to monitor and evaluate your project's
                performance in real-time, empowering informed decision-making and successful project outcomes.
            </p>
            <button
                class="bg-[#6c63ff] hover:bg-[#5a54e4] text-white rounded-full w-12 h-12 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-right transform rotate-225"></i>
            </button>

        </div>
        <!-- Chart Image -->
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="/assets/images/Screenshot 2025-06-02 172130.png" alt="Project Statistics Chart"
                class="max-w-full rounded-xl shadow-md" />
        </div>
    </div>
</section>

<!-- carasoul section -->
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex flex-col md:flex-row items-start justify-between">

        <!-- Left Text Section -->
        <div class="md:w-1/3 mb-10 md:mb-0">
            <h2 class="text-4xl font-bold leading-tight mb-6">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-violet-500">Trusted</span>
                by <br />Market Leaders
            </h2>

            <!-- Nav Buttons -->
            <div class="flex space-x-4 mt-4">
                <button id="prev"
                    class="w-10 h-10 rounded-full border flex items-center justify-center text-purple-500 hover:bg-purple-100 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button id="next"
                    class="w-10 h-10 rounded-full border flex items-center justify-center text-purple-500 hover:bg-purple-100 transition">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Carousel Section -->
        <div class="md:w-2/3 overflow-hidden relative">
            <div id="slider" class="flex transition-all duration-500 space-x-6 w-full">
                <!-- Slide 1 -->
                <div class="min-w-[300px] bg-[#f5f8ff] p-6 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4">“Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget
                        lorem eu arcu iaculis.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=1" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Michel Doncic</p>
                            <p class="text-sm text-gray-500">Project Manager</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="min-w-[300px] bg-[#f5f8ff] p-6 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4">“Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”
                    </p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=2" class="rounded-full" />
                        <div>
                            <p class="font-semibold">July Kerr</p>
                            <p class="text-sm text-gray-500">Web Developer</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="min-w-[300px] bg-[#f5f8ff] p-6 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4">“Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[300px] bg-[#f5f8ff] p-6 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4">“Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[300px] bg-[#f5f8ff] p-6 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4">“Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[300px] bg-[#f5f8ff] p-6 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4">“Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?php
include_once('main/public-templates/public-footer.php');

?>