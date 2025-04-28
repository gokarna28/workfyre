<?php 
include_once('header.php');
?>
<sidebar>
    <div class="bg-white border-r border-slate-300 md:w-1/5 w-1/6 md:h-screen h-full pt-30 p-6 fixed">
        <ul>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/home.php"
                    class="hover:bg-white bg-slate-100 border border-slate-300 cursor-pointer flex items-center gap-6 rounded-lg p-2 text-xl font-medium mb-6">
                    <i class="fa-solid fa-gauge md:text-2xl text-xl hover"></i>
                    <span class="hidden md:block">Home</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/projects.php"
                    class="hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300 cursor-pointer flex items-center gap-6 rounded-lg p-2 text-xl font-medium mb-6">
                    <i class="fa-solid fa-clipboard md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Projects</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/teams.php"
                    class="hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300 cursor-pointer flex items-center gap-6 rounded-lg p-2 text-xl font-medium mb-6">
                    <i class="fa-solid fa-chart-simple md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Teams</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/settings.php"
                    class="hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300 cursor-pointer flex items-center gap-6 rounded-lg p-2 text-xl font-medium mb-6">
                    <i class="fa-solid fa-gear md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Settings</span>
                </a>
            </li>

        </ul>
    </div>
</sidebar>