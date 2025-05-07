<?php 
include_once('header.php');
?>
<sidebar>
    <div class="bg-white border-r border-slate-300 md:w-1/5 w-1/6 md:h-screen h-full pt-30 p-6 fixed">
        <ul>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/home.php"
                    class="<?php echo isset($current_page)&& $current_page=='home.php'?'hover:bg-white bg-slate-100 border border-slate-300':'hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300'?> cursor-pointer flex items-center gap-6 rounded-lg p-4 text-xl font-medium mb-6">
                    <i class="fa-solid fa-house md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Home</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/projects.php"
                    class="<?php echo isset($current_page)&& $current_page=='projects.php'?'hover:bg-white bg-slate-100 border border-slate-300':'hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300'?> cursor-pointer flex items-center gap-6 rounded-lg p-4 text-xl font-medium mb-6">
                    <i class="fa-regular fa-gem md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Projects</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/teams.php"
                    class="<?php echo isset($current_page)&& $current_page=='teams.php'?'hover:bg-white bg-slate-100 border border-slate-300':'hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300'?> cursor-pointer flex items-center gap-6 rounded-lg p-4 text-xl font-medium mb-6">
                    <i class="fa-regular fa-user md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Teams</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/settings.php"
                    class="<?php echo isset($current_page)&& $current_page=='settings.php'?'hover:bg-white bg-slate-100 border border-slate-300':'hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300'?> cursor-pointer flex items-center gap-6 rounded-lg p-4 text-xl font-medium mb-6">
                    <i class="fa-solid fa-gear md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Settings</span>
                </a>
            </li>
            <li>
                <a href="<?php echo HOMEPAGE_URL ?>/main/dashboard/logout.php"
                    class="<?php echo isset($current_page)&& $current_page=='logout.php'?'hover:bg-white bg-slate-100 border border-slate-300':'hover:bg-white hover:bg-slate-100 hover:border hover:border-slate-300'?> cursor-pointer flex items-center gap-6 rounded-lg p-4 text-xl font-medium mb-6">
                    <i class="fa-solid fa-arrow-right-from-bracket md:text-2xl text-xl"></i>
                    <span class="hidden md:block">Logout</span>
                </a>
            </li>

        </ul>
    </div>
</sidebar>