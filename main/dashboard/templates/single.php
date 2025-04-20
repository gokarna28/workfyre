<?php include_once('../../sidebar.php'); ?>
<?php include_once('../../header.php'); ?>


<section class="pt-20 pl-80 w-full pr-10">
    <h2>Project Name</h2>
    <p class="mb-5">Short description will be placed here</p>

    <div class="w-full flex items-center justify-between">
        <ul class="flex items-center w-1/2 border-b border-slate-300">
            <li class="mr-10 text-lg text-sky-700 border-b-2 border-sky-700 pb-4"><span><i class="fa-solid fa-chart-bar"></i></span>Board</li>
            <li class="mr-10 text-lg pb-4"><span><i class="fa-regular fa-file"></i></span>Files</li>
            <li class="mr-10 text-lg pb-4"><span><i class="fa-solid fa-users"></i></span>Team</li>
        </ul>
        <div class="">
            <button
                class="flex items-center gap-2 hover:bg-stone-900 hover:text-white border border-slate-300 px-4 py-2 rounded-2xl">
                <i class="fa-solid fa-plus"></i>
                <p class="text-lg font-medium">New Task</p>
            </button>
        </div>
    </div>
</section>