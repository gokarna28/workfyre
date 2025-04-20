<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<section class="pt-20 pl-80 w-full pr-10">
    <h2>Project Dashboard</h2>

    <div class="flex items-center w-full justify-between mb-5">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-medium">All Projects</h2>
            <span class="bg-slate-200 rounded-full items-center justify-center">
                <p>12</p>
            </span>
        </div>
        <div class="">
            <button
                class="flex items-center gap-2 hover:bg-stone-900 hover:text-white border border-slate-300 px-4 py-2 rounded-2xl">
                <i class="fa-solid fa-plus"></i>
                <p class="text-lg font-medium">New Project</p>
            </button>
        </div>
    </div>
    <div class="w-full flex items-center">
        <div class="border flex border-slate-300 mb-5 w-35 p-2 rounded-xl items-center">
            <i class="fa-solid fa-filter"></i>
            <select class="outline-none">
                <option value="">Add Filter</option>
                <option value="active">Active</option>
                <option value="in-progress">In progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>
    <div class="w-full">
        <table class="w-full">
            <thead class="bg-slate-200 w-full">
                <th class="text-lg text-start font-normal px-4 py-2">Project Name</th>
                <th class="text-lg text-start font-normal px-4 py-2">Start Date</th>
                <th class="text-lg text-start font-normal px-4 py-2">Deadline</th>
                <th class="text-lg text-start font-normal px-4 py-2">category</th>
                <th class="text-lg text-start font-normal px-4 py-2">Status</th>
                <th class="text-lg text-start font-normal px-4 py-2">Priority</th>
            </thead>
            <tbody>
                <tr class="cursor-pointer">
                    <a href="/templates/single.php" class="border-b border-slate-200">
                        <td class="p-4">Logo Design for cafe</td>
                        <td class="p-4">april-20-2025</td>
                        <td class="p-4">april-30-2025</td>
                        <td class="p-4">Logo Design</td>
                        <td class="p-4"><span
                                class="bg-green-200 text-green-500 px-2 rounded-full items-center justify-center">active</span>
                        </td>
                        <td class="p-4"><span
                                class="bg-red-200 text-red-500 px-2 rounded-full items-center justify-center">High</span>
                        </td>
                        <td class="p-4">:</td>
                    </a>
                </tr>
                <tr class="cursor-pointer">
                    <a href="/templates/single.php" class="border-b border-slate-200">
                        <td class="p-4">Logo Design for cafe</td>
                        <td class="p-4">april-20-2025</td>
                        <td class="p-4">april-30-2025</td>
                        <td class="p-4">Logo Design</td>
                        <td class="p-4"><span
                                class="bg-yellow-200 text-yellow-500 px-2 rounded-full items-center justify-center">In
                                Progress</span></td>
                        <td class="p-4"><span
                                class="bg-stone-200 text-stone-500 px-2 rounded-full items-center justify-center">Medium</span>
                        </td>
                        <td class="p-4">:</td>
                    </a>
                </tr>
                <tr class="cursor-pointer">
                    <a href="/templates/single.php" class="border-b border-slate-200">
                        <td class="p-4">Logo Design for cafe</td>
                        <td class="p-4">april-20-2025</td>
                        <td class="p-4">april-30-2025</td>
                        <td class="p-4">Logo Design</td>
                        <td class="p-4"><span
                                class="bg-sky-200 text-sky-500 text-sm px-2 rounded-full items-center justify-center">Completed</span>
                        </td>
                        <td class="p-4"><span
                                class="bg-orange-200 text-orange-500 px-2 rounded-full items-center justify-center">Low</span>
                        </td>
                        <td class="p-4">:</td>
                    </a>
                </tr>
            </tbody>
        </table>
    </div>
</section>