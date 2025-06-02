<?php
include_once('main/public-templates/public-header.php');

?>
<section class="w-full h-auto pt-40 bg-[#1a143b]">
    <div class="flex items-center justify-center w-full flex-col mb-20 text-[#181832]">
        <div class="text-center mb-6">
            <h1
                class="text-7xl font-bold bg-gradient-to-r from-purple-500 via-sky-300 to-purple-400 bg-clip-text text-transparent">
                Centralize your Project
            </h1>

            <h1
                class="text-7xl font-bold bg-gradient-to-r from-purple-500 via-sky-300 to-purple-400 bg-clip-text text-transparent">
                Management with AI Features</h1>
        </div>
        <div class="w-full px-50 text-center flex flex-col items-center justify-center gap-6 text-white">
            <p class="px-20 text-slate-400 text-xl">Workfyre is a powerful project management platform built to help teams plan, track, and deliver
                projects
                with precision. Whether you're a solo creator or managing a team, Workfyre keeps everything
                organized,
                on schedule, and in sync.</p>
            <div class="flex items-center gap-10 justify-center">
                <button
                    class="bg-[#6c63ff] px-8 py-3 rounded-full text-lg shadow-md">Get
                    Started</button>
                <!-- <button class="px-8 py-3 rounded-full text-lg bg-[#6c63ff] shadow-md">30-Day
                    Free
                    Trial</button> -->
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

<section class="w-full bg-[#f5f7ff] pt-20 pb-10 flex flex-col items-center justify-center">
    <div class="max-w-6xl flex flex-col md:flex-row items-center justify-between mb-10">
        <!-- Chart Image -->
        <div class="w-full md:w-1/2 flex justify-start">
            <img src="/assets/images/Screenshot 2025-06-02 170854.png" alt="Project Statistics Chart"
                class="max-w-full rounded-xl shadow-md" />
        </div>

        <!-- Text Content -->
        <div class="w-full md:w-1/2">
            <h2 class="text-6xl font-bold text-[#181832] mb-4 leading-tight">
                Project Statistics <br class="hidden md:block" /> & Analytics
            </h2>
            <p class="text-gray-600 mb-6 leading-relaxed text-xl">
                Leverage powerful data-driven insights and detailed analytics to monitor and evaluate your project's
                performance in real-time, empowering informed decision-making and successful project outcomes.
            </p>
            <button
                class="bg-[#6c63ff] hover:bg-[#5a54e4] text-white rounded-full w-18 h-18 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-right transform rotate-315"></i>
            </button>
        </div>
    </div>

    <div class="max-w-6xl flex flex-col md:flex-row items-center justify-between">
        <!-- Text Content -->
        <div class="w-full md:w-1/2">
            <h2 class="text-6xl font-bold text-[#181832] mb-4 leading-tight">
                Project Statistics <br class="hidden md:block" /> & Analytics
            </h2>
            <p class="text-gray-600 text-xl mb-6 leading-relaxed">
                Leverage powerful data-driven insights and detailed analytics to monitor and evaluate your project's
                performance in real-time, empowering informed decision-making and successful project outcomes.
            </p>
            <button
                class="bg-[#6c63ff] hover:bg-[#5a54e4] text-white rounded-full w-18 h-18 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-right transform rotate-225"></i>
            </button>
        </div>
        <!-- Chart Image -->
        <div class="w-full md:w-1/2 flex justify-end">
            <img src="/assets/images/Screenshot 2025-06-02 172130.png" alt="Project Statistics Chart"
                class="max-w-full rounded-xl shadow-md" />
        </div>
    </div>
</section>

<!-- carasoul section -->
<section class="max-w-7xl mx-auto p-20">
    <div class="flex flex-col md:flex-row items-start justify-between">
        <!-- Left Text Section -->
        <div class="md:w-1/2 mb-10 md:mb-0">
            <h2 class="text-6xl font-bold leading-tight mb-6">
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
        <div class="md:w-full overflow-hidden relative">
            <div id="slider" class="flex transition-all duration-500 space-x-6 w-full">
                <!-- Slide 1 -->
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Lorem ipsum dolor sit amet,
                        consectetur adipiscing elit. Donec eget
                        lorem eu arcu iaculis.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=1" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Michel Doncic</p>
                            <p class="text-sm text-gray-500">Project Manager</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Lorem ipsum dolor sit amet,
                        consectetur adipiscing elit. Donec eget
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
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Sed do eiusmod tempor incididunt
                        ut
                        labore et dolore magna aliqua.”
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
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris
                        nisi.”</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Alex Jordan</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm">“Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris
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

<section class="flex flex-col md:flex-row max-w-7xl mx-auto px-20 justify-between items-start gap-20">
    <!-- Running Task Card -->
    <div class="bg-gray-900 text-white p-6 rounded-xl shadow-md w-60">
        <div class="text-sm">Running Task</div>
        <div class="text-4xl font-bold my-2">65</div>
        <div class="relative w-24 h-24">
            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 36 36">
                <path class="text-gray-700" stroke="currentColor" stroke-width="4" fill="none" d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831" />
                <path class="text-indigo-500" stroke="currentColor" stroke-width="4" stroke-dasharray="45, 100"
                    fill="none" d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831" />
            </svg>
            <div class="absolute inset-0 flex items-center justify-center text-sm">
                45%
            </div>
        </div>
        <div class="text-sm mt-2">100 Task</div>
    </div>

    <!-- Main Heading -->
    <div class="flex-1">
        <h1 class="text-6xl font-bold leading-tight">
            Optimize Performance<br />
            Through <span
                class="bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text">Real-Time</span>
            Task
            Analysis
        </h1>
    </div>
</section>

<section class="px-50 mt-10 mb-10">
    <div class="relative p-4 bg-[#f5f7ff] rounded-3xl overflow-hidden">
        <div id="carousel" class="overflow-hidden rounded-3xl p-4">
            <div id="carousel-track" class="flex gap-6 w-max">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[270px] shrink-0">
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">Project in
                        progress</span>
                    <h2 class="mt-3 font-semibold">Working on Task App UI Kit On dribbble</h2>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 56%"></div>
                        </div>
                        <div class="text-sm mt-1 text-right text-gray-500">56%</div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/30?img=1"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <img src="https://i.pravatar.cc/30?img=2"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <div
                                class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                                +2</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>2</span>
                            <span>6</span>
                            <span>4 days</span>
                        </div>
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[270px] shrink-0">
                    <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Project is
                        paused</span>
                    <h2 class="mt-3 font-semibold">Working on Dashboard Design</h2>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-pink-400 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                        <div class="text-sm mt-1 text-right text-gray-500">70%</div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/30?img=3"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <img src="https://i.pravatar.cc/30?img=4"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <div
                                class="w-6 h-6 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                                +3</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>3</span>
                            <span>4</span>
                            <span>2 days</span>
                        </div>
                    </div>
                </div>

                <!-- Repeat Task Cards 3–5 (use same markup) -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[270px] shrink-0">
                    <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Project is
                        paused</span>
                    <h2 class="mt-3 font-semibold">Working on Dashboard Design</h2>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-pink-400 h-2 rounded-full" style="width: 70%"></div>
                        </div>
                        <div class="text-sm mt-1 text-right text-gray-500">70%</div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/30?img=3"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <img src="https://i.pravatar.cc/30?img=4"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <div
                                class="w-6 h-6 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                                +3</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>3</span>
                            <span>4</span>
                            <span>2 days</span>
                        </div>
                    </div>
                </div>

                <!-- Task Card 4 -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[270px] shrink-0">
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">Project in
                        progress</span>
                    <h2 class="mt-3 font-semibold">Working on Task App UI Kit On dribbble</h2>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" style="width: 56%"></div>
                        </div>
                        <div class="text-sm mt-1 text-right text-gray-500">56%</div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/30?img=1"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <img src="https://i.pravatar.cc/30?img=2"
                                class="w-6 h-6 rounded-full border-2 border-white" />
                            <div
                                class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                                +2</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>2</span>
                            <span>6</span>
                            <span>4 days</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<section class="flex flex-col md:flex-row gap-10 bg-[#f5f7ff] py-20 px-50 text-[#111827] p-10">

    <!-- Left Section -->
    <div class="w-full md:w-1/3 space-y-10">
        <div>
            <h1 class="text-4xl font-bold">Your Works</h1>
            <p class="text-sm text-gray-500 mt-1">Get up running, building amazing real time<br>analytics applications
                for a multimedia</p>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text">
                <span class="">Project</span>
                <span class=""> Calendar</span>
            </h2>
            <p class="text-sm text-gray-500 mt-2">Get up running, building amazing real time</p>
        </div>

        <div>
            <h1 class="text-4xl font-bold">Tasks Board</h1>
            <p class="text-sm text-gray-500 mt-1">Get up running, building amazing real time<br>analytics applications
                for a multimedia</p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full md:w-2/3 bg-white rounded-2xl p-10 shadow space-y-6 ">

        <!-- Dates Row -->
        <div class="flex justify-between items-center space-x-2">
            <div class="text-center">
                <p class="text-sm text-gray-400">10</p>
                <p class="text-sm text-gray-500">Sat</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-400">11</p>
                <p class="text-sm text-gray-500">Sun</p>
            </div>
            <div class="text-center bg-indigo-100 text-indigo-700 px-3 py-1 rounded-2xl font-semibold">
                <p class="text-sm">12</p>
                <p class="text-sm">Mon</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-400">13</p>
                <p class="text-sm text-gray-500">Tue</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-400">14</p>
                <p class="text-sm text-gray-500">Wed</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-400">15</p>
                <p class="text-sm text-gray-500">Thu</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-400">16</p>
                <p class="text-sm text-gray-500">Fri</p>
            </div>
        </div>

        <!-- Task: Daily Work -->
        <div class="flex gap-4 items-center">
            <div class="w-20 text-sm text-gray-400">9:00 AM</div>
            <div class="flex-1 bg-pink-200 rounded-xl p-4">
                <h3 class="font-semibold text-sm">Daily Work</h3>
                <p class="text-xs text-gray-700 mt-1">To discuss with team all work<br>processes for the day.</p>
                <p class="text-xs text-gray-600 mt-2">9:00 - 10:20 AM</p>
            </div>
        </div>

        <!-- Task: UI Kit -->
        <div class="flex gap-4 items-center">
            <div class="w-20 text-sm text-gray-400">10:20 AM</div>
            <div class="flex-1 bg-orange-200 rounded-xl p-4">
                <h3 class="font-semibold text-sm">New UI Kit for the app</h3>
                <p class="text-xs text-gray-700 mt-1">To collect all assets that continues a<br>set of design elements
                    such as components.</p>
                <p class="text-xs text-gray-600 mt-2">10:20 - 12:35 AM</p>
            </div>
        </div>

        <!-- Task: Lunch -->
        <div class="flex gap-4 items-center">
            <div class="w-20 text-sm text-gray-400">11:30 AM</div>
            <div class="flex-1 bg-indigo-200 rounded-xl p-4">
                <h3 class="font-semibold text-sm">Lunch Break</h3>
                <p class="text-xs text-gray-600 mt-2">12:25 - 1:25 AM</p>
            </div>
        </div>

    </div>
</section>

<section class="">
    <div
        class="bg-gradient-to-b from-black to-gray-900  flex flex-col items-center justify-center text-center p-10">
        <h1 class="text-4xl md:text-6xl font-medium mb-6 leading-tight text-white">
            Get started with the <br />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500 font-bold">#1 project
                management</span>
        </h1>

        <!-- Email Input + Button -->
        <div class="flex w-full max-w-xl mx-auto mt-4 bg-white p-2 rounded-full">
            <input type="email" placeholder="Enter Your Email" 
                class="flex-1 p-4 rounded-l-full text-black focus:outline-none" />
            <button
                class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-4 rounded-full font-semibold hover:opacity-90 transition-all">
                Get in Touch
            </button>
        </div>
    </div>
</section>

<section class="bg-black py-4 border-t border-gray-800">
    <div class="max-w-6xl mx-auto px-4 flex justify-between items-center text-sm text-white">
        <div class="flex items-center gap-2">
            <span class="text-white font-bold">Workfyre</span>
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
<?php
include_once('main/public-templates/public-footer.php');

?>