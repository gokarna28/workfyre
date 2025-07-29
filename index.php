<?php
include_once('main/public-templates/public-header.php');

?>
<section class="w-full h-auto bg-[#1a143b]">
    <div class="flex items-center pt-10 md:pt-20 pb-20 md:pb-40 w-full flex-col text-[#181832] relative h-full z-2 px-4 md:px-0">
        <div class="text-center mb-6">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">
                <span class="bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text">Ignite</span>
                Your Workflow
            </h1>

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white leading-tight">
                Master Every <span
                    class="bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text">Deadlines
                </span></h1>
        </div>
        <div class="w-full px-4 md:px-50 text-center flex flex-col items-center justify-center gap-6 text-white">
            <p class="px-4 md:px-20 text-slate-400 text-lg md:text-xl">Workfyre is a powerful project management platform built to help
                teams plan, track, and deliver
                projects
                with precision. Whether you're a solo creator or managing a team, Workfyre keeps everything
                organized,
                on schedule, and in sync.</p>
            <div class="flex items-center gap-4 md:gap-10 justify-center flex-col sm:flex-row">
                <button class="bg-[#6c63ff] px-6 md:px-8 py-3 rounded-full text-base md:text-lg shadow-md w-full sm:w-auto"><a
                        href="<?php echo HOMEPAGE_URL ?>/main/register.php">Get
                        Started</a></button>
                <!-- <button class="px-8 py-3 rounded-full text-lg bg-[#6c63ff] shadow-md">30-Day
                    Free
                    Trial</button> -->
            </div>
        </div>
    </div>

    <div class="absolute left-1/2 transform -translate-x-1/2 flex items-center justify-center top-[80%] gap-2 md:gap-6 px-4 md:px-0 overflow-x-auto w-full">

        <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
            <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Project is
                paused</span>
            <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Dashboard Design</h2>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-400 h-2 rounded-full" style="width: 70%"></div>
                </div>
                <div class="text-sm mt-1 text-right text-gray-500">70%</div>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/30?img=3" class="w-6 h-6 rounded-full border-2 border-white" />
                    <img src="https://i.pravatar.cc/30?img=4" class="w-6 h-6 rounded-full border-2 border-white" />
                    <div
                        class="w-6 h-6 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                        +3</div>
                </div>
                <div class="flex items-center gap-2">
                    <span>3</span>
                    <span>4</span>
                    <span class="hidden sm:inline">2 days</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
            <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">Project in
                progress</span>
            <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Task App UI Kit On dribbble</h2>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-cyan-500 h-2 rounded-full" style="width: 56%"></div>
                </div>
                <div class="text-sm mt-1 text-right text-gray-500">56%</div>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/30?img=1" class="w-6 h-6 rounded-full border-2 border-white" />
                    <img src="https://i.pravatar.cc/30?img=2" class="w-6 h-6 rounded-full border-2 border-white" />
                    <div
                        class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                        +2</div>
                </div>
                <div class="flex items-center gap-2">
                    <span>2</span>
                    <span>6</span>
                    <span class="hidden sm:inline">4 days</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
            <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Project is
                paused</span>
            <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Dashboard Design</h2>
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-400 h-2 rounded-full" style="width: 70%"></div>
                </div>
                <div class="text-sm mt-1 text-right text-gray-500">70%</div>
            </div>
            <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                <div class="flex -space-x-2">
                    <img src="https://i.pravatar.cc/30?img=3" class="w-6 h-6 rounded-full border-2 border-white" />
                    <img src="https://i.pravatar.cc/30?img=4" class="w-6 h-6 rounded-full border-2 border-white" />
                    <div
                        class="w-6 h-6 rounded-full bg-pink-100 text-pink-700 flex items-center justify-center text-xs font-bold border-2 border-white">
                        +3</div>
                </div>
                <div class="flex items-center gap-2">
                    <span>3</span>
                    <span>4</span>
                    <span class="hidden sm:inline">2 days</span>
                </div>
            </div>
        </div>
    </div>

</section>

<section class="w-full bg-[#f5f7ff] pt-20 md:pt-50 pb-10 flex flex-col items-center justify-center px-4 md:px-0">
    <div class="max-w-6xl flex flex-col lg:flex-row items-center justify-between mb-20 gap-8">
        <!-- Chart Image -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-start">
            <img src="/assets/images/Screenshot 2025-06-02 170854.png" alt="Project Statistics Chart"
                class="max-w-full rounded-xl shadow-md" />
        </div>

        <!-- Text Content -->
        <div class="w-full lg:w-1/2 text-center lg:text-left">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#181832] mb-4 leading-tight">
                Critical Path Management (CPM)
            </h2>
            <p class="text-gray-600 mb-6 leading-relaxed text-lg md:text-xl">
                Easily map out task dependencies and identify the longest sequence of critical activities.
                Spot potential delays early and keep your project timeline on track.
            </p>
            <button
                class="bg-[#6c63ff] hover:bg-[#5a54e4] text-white rounded-full w-16 h-16 md:w-18 md:h-18 flex items-center justify-center transition text-xl md:text-2xl font-medium">
                <a href="<?php echo HOMEPAGE_URL ?>/main/login.php">
                    <i class="fa-solid fa-arrow-right transform rotate-315"></i>
                </a>
            </button>
        </div>
    </div>

    <div class="max-w-6xl flex flex-col lg:flex-row items-center justify-between gap-8">
        <!-- Text Content -->
        <div class="w-full lg:w-1/2 text-center lg:text-left order-2 lg:order-1">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#181832] mb-4 leading-tight">
                Dynamic Project Timelines
            </h2>
            <p class="text-gray-600 text-lg md:text-xl mb-6 leading-relaxed">
                Plan, assign, and manage tasks with interactive Gantt charts.
                See how every task connects and adjust timelines in real-time as your project evolves.
            </p>
            <button
                class="bg-[#6c63ff] hover:bg-[#5a54e4] text-white rounded-full w-16 h-16 md:w-18 md:h-18 flex items-center justify-center transition text-xl md:text-2xl font-medium">
                <a href="<?php echo HOMEPAGE_URL ?>/main/login.php">
                    <i class="fa-solid fa-arrow-right transform rotate-225"></i>
                </a>
            </button>
        </div>
        <!-- Chart Image -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end order-1 lg:order-2">
            <img src="/assets/images/Screenshot 2025-06-02 172130.png" alt="Project Statistics Chart"
                class="max-w-full rounded-xl shadow-md" />
        </div>
    </div>
</section>

<!-- carasoul section -->
<section class="max-w-7xl mx-auto p-4 md:p-20" id="testimonial">
    <div class="flex flex-col lg:flex-row items-start justify-between gap-8">
        <!-- Left Text Section -->
        <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-violet-500">Why Teams
                </span>
                Rely on <br />Workfyre
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
        <div class="w-full lg:w-1/2 overflow-hidden relative">
            <div id="slider" class="flex transition-all duration-500 space-x-4 md:space-x-6 w-full overflow-x-auto">
                <!-- Slide 1 -->
                <div class="min-w-[280px] md:min-w-[100px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">"Workfyre
                        transformed the way we
                        deliver projects. The CPM visualization is a game-changer!"
                        — Maya R., Senior Project Manager</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=1" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Michel Doncic</p>
                            <p class="text-sm text-gray-500">Project Manager</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">Our team
                        communicates better and finishes faster—Workfyre gave us a system that scales."
                        — Daniel L., Startup Co-Founder</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=1" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Emily W., Product Manager</p>
                            <p class="text-sm text-gray-500">Project Manager</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">Workfyre gave our
                        team crystal-clear visibility into project timelines. The CPM feature helped us avoid costly
                        delays.
                    </p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=2" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Samantha K., Project Manager</p>
                            <p class="text-sm text-gray-500">Web Developer</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">I love how easy it
                        is to assign tasks and track progress. The real-time updates keep everyone accountable.</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Raj P., Freelancer</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">As a startup
                        founder, Workfyre lets me focus on priorities by highlighting the most critical tasks
                        automatically.</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Lena M., CEO</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">The collaborative
                        features keep our remote team connected. File sharing and chat in one place save us hours every
                        week.</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Carlos D., Marketing Director</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">The Gantt charts
                        are intuitive and flexible. We can quickly adjust plans as client needs change.</p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Nina S., Creative Agency Lead</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
                <div class="min-w-[280px] md:min-w-[150px] max-h-[200px] bg-[#f5f8ff] p-3 rounded-xl shadow-md">
                    <p class="text-gray-700 mb-4 bg-white p-2 rounded-2xl text-sm max-w-[500px] wrap">Workfyre's
                        reporting tools make stakeholder updates effortless. I can export progress reports in minutes.
                    </p>
                    <div class="flex items-center space-x-3">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-full" />
                        <div>
                            <p class="font-semibold">Michael B., Construction Project Lead</p>
                            <p class="text-sm text-gray-500">UI Designer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="flex flex-col lg:flex-row max-w-7xl mx-auto px-4 md:px-20 justify-between items-start gap-10">
    <!-- Running Task Card -->
    <div class="bg-gray-900 text-white p-6 rounded-xl shadow-md w-full lg:w-60">
        <div class="text-sm">Running Task</div>
        <div class="text-3xl md:text-4xl font-bold my-2">65</div>
        <div class="relative w-20 h-20 md:w-24 md:h-24">
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
    <div class="flex-1 text-center lg:text-left">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
            Optimize Performance<br />
            Through <span
                class="bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text">Real-Time</span>
            Task
            Analysis
        </h1>
    </div>
</section>

<section class="px-4 md:px-50 mt-10 mb-10">
    <div class="relative p-4 bg-[#f5f7ff] rounded-3xl overflow-hidden">
        <div id="carousel" class="overflow-hidden rounded-3xl p-4">
            <div id="carousel-track" class="flex gap-4 md:gap-6 w-max">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">Project in
                        progress</span>
                    <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Task App UI Kit On dribbble</h2>
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
                            <span class="hidden sm:inline">4 days</span>
                        </div>
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
                    <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Project is
                        paused</span>
                    <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Dashboard Design</h2>
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
                            <span class="hidden sm:inline">2 days</span>
                        </div>
                    </div>
                </div>

                <!-- Repeat Task Cards 3–5 (use same markup) -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
                    <span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">Project is
                        paused</span>
                    <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Dashboard Design</h2>
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
                            <span class="hidden sm:inline">2 days</span>
                        </div>
                    </div>
                </div>

                <!-- Task Card 4 -->
                <div class="bg-white rounded-xl p-4 shadow-md w-[250px] md:w-[270px] shrink-0">
                    <span class="bg-cyan-100 text-cyan-700 px-3 py-1 rounded-full text-xs font-semibold">Project in
                        progress</span>
                    <h2 class="mt-3 font-semibold text-sm md:text-base">Working on Task App UI Kit On dribbble</h2>
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
                            <span class="hidden sm:inline">4 days</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<section class="flex flex-col lg:flex-row gap-10 bg-[#f5f7ff] py-10 md:py-20 px-4 md:px-50 text-[#111827] p-4 md:p-10" id="your-work">

    <!-- Left Section -->
    <div class="w-full lg:w-1/3 space-y-6 md:space-y-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold">Your Works</h1>
            <p class="text-sm text-gray-500 mt-1">Get up running, building amazing real time<br>analytics applications
                for a multimedia</p>
        </div>

        <div class="bg-white rounded-xl shadow p-4 md:p-6">
            <h2 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 text-transparent bg-clip-text">
                <span class="">Project</span>
                <span class=""> Calendar</span>
            </h2>
            <p class="text-sm text-gray-500 mt-2">Get up running, building amazing real time</p>
        </div>

        <div>
            <h1 class="text-3xl md:text-4xl font-bold">Tasks Board</h1>
            <p class="text-sm text-gray-500 mt-1">Get up running, building amazing real time<br>analytics applications
                for a multimedia</p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-2/3 bg-white rounded-2xl p-4 md:p-10 shadow space-y-4 md:space-y-6">

        <!-- Dates Row -->
        <div class="flex justify-between items-center space-x-1 md:space-x-2 overflow-x-auto">
            <div class="text-center min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm text-gray-400">10</p>
                <p class="text-xs md:text-sm text-gray-500">Sat</p>
            </div>
            <div class="text-center min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm text-gray-400">11</p>
                <p class="text-xs md:text-sm text-gray-500">Sun</p>
            </div>
            <div class="text-center bg-indigo-100 text-indigo-700 px-2 md:px-3 py-1 rounded-2xl font-semibold min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm">12</p>
                <p class="text-xs md:text-sm">Mon</p>
            </div>
            <div class="text-center min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm text-gray-400">13</p>
                <p class="text-xs md:text-sm text-gray-500">Tue</p>
            </div>
            <div class="text-center min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm text-gray-400">14</p>
                <p class="text-xs md:text-sm text-gray-500">Wed</p>
            </div>
            <div class="text-center min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm text-gray-400">15</p>
                <p class="text-xs md:text-sm text-gray-500">Thu</p>
            </div>
            <div class="text-center min-w-[40px] md:min-w-0">
                <p class="text-xs md:text-sm text-gray-400">16</p>
                <p class="text-xs md:text-sm text-gray-500">Fri</p>
            </div>
        </div>

        <!-- Task: Daily Work -->
        <div class="flex gap-2 md:gap-4 items-center">
            <div class="w-16 md:w-20 text-xs md:text-sm text-gray-400">9:00 AM</div>
            <div class="flex-1 bg-pink-200 rounded-xl p-3 md:p-4">
                <h3 class="font-semibold text-xs md:text-sm">Daily Work</h3>
                <p class="text-xs text-gray-700 mt-1">To discuss with team all work<br>processes for the day.</p>
                <p class="text-xs text-gray-600 mt-2">9:00 - 10:20 AM</p>
            </div>
        </div>

        <!-- Task: UI Kit -->
        <div class="flex gap-2 md:gap-4 items-center">
            <div class="w-16 md:w-20 text-xs md:text-sm text-gray-400">10:20 AM</div>
            <div class="flex-1 bg-orange-200 rounded-xl p-3 md:p-4">
                <h3 class="font-semibold text-xs md:text-sm">New UI Kit for the app</h3>
                <p class="text-xs text-gray-700 mt-1">To collect all assets that continues a<br>set of design elements
                    such as components.</p>
                <p class="text-xs text-gray-600 mt-2">10:20 - 12:35 AM</p>
            </div>
        </div>

        <!-- Task: Lunch -->
        <div class="flex gap-2 md:gap-4 items-center">
            <div class="w-16 md:w-20 text-xs md:text-sm text-gray-400">11:30 AM</div>
            <div class="flex-1 bg-indigo-200 rounded-xl p-3 md:p-4">
                <h3 class="font-semibold text-xs md:text-sm">Lunch Break</h3>
                <p class="text-xs text-gray-600 mt-2">12:25 - 1:25 AM</p>
            </div>
        </div>

    </div>
</section>

<section class="" id="contact">
    <div class="bg-gradient-to-b from-black to-gray-900 flex flex-col items-center justify-center text-center p-4 md:p-10">
        <h1 class="text-3xl md:text-4xl lg:text-6xl font-medium mb-6 leading-tight text-white">
            Get started with the <br />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500 font-bold">#1
                project
                management</span>
        </h1>

        <!-- Email Input + Button -->
        <div class="flex flex-col sm:flex-row w-full max-w-xl mx-auto mt-4 bg-white p-2 rounded-full">
            <input type="email" placeholder="Enter Your Email"
                class="flex-1 p-3 md:p-4 rounded-l-full sm:rounded-l-full rounded-r-full sm:rounded-r-none text-black focus:outline-none" />
            <button
                class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 md:px-6 py-3 md:py-4 rounded-full sm:rounded-l-none font-semibold hover:opacity-90 transition-all mt-2 sm:mt-0">
                Get in Touch
            </button>
        </div>
    </div>
</section>


<section class="bg-black py-4 border-t border-gray-800">
    <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center text-sm text-white gap-4">
        <div class="flex items-center gap-2">
            <span class="font-medium flex items-center justify-center w-8 h-8 md:w-10 md:h-10 overflow-hidden p-2">
                <img src="http://workfyre.local/assets/images/logo.png" class="w-full h-full object-cover"
                    alt="default profile" />
            </span>
            <h1 class="text-lg md:text-xl font-bold font-['Josefin Sans">Workfyre</h1>
        </div>
        <nav class="flex space-x-4 md:space-x-6">
            <a href="#" class="hover:text-sky-500">About</a>
            <a href="#" class="hover:text-sky-500">Demo</a>
            <a href="#contact" class="hover:text-sky-500">Contact</a>
        </nav>
    </div>
</section>

<!-- scroll to top   -->
<button id="scrollToTopBtn"
    class="fixed bottom-6 right-6 bg-gradient-to-t from-blue-400 to-purple-500 text-2xl md:text-3xl font-bold hover:bg-cyan-700 text-white px-3 py-2 md:px-4 md:py-2 shadow-lg hidden">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<?php
include_once('main/public-templates/public-footer.php');

?>