<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>


<section class="pt-25 pl-85 w-full pr-10">
   <p class="text-slate-500">Manage and Track Your Projects</p>
   <div class="w-full flex items-center gap-20 p-4 mb-5">
      <div class="border border-slate-300 rounded-lg p-4 bg-[#CABDFF] text-white shadow-md">
         <h2 class="text-xl font-bold mb-2">Total Team Members</h2>
         <div class="flex items-center gap-5">
            <span class="text-2xl font-medium"><i class="fa-regular fa-user"></i></span>
            <span class="text-6xl font-bold">100+</span>
         </div>
      </div>
      <div class="border border-slate-300 rounded-lg p-4 bg-[#FFBC99] text-white shadow-md">
         <h2 class="text-xl font-bold mb-2">Total Projects</h2>
         <div class="flex items-center gap-5">
            <span class="text-2xl font-medium"><i class="fa-solid fa-file-waveform"></i></span>
            <span class="text-6xl font-bold">100+</span>
         </div>
      </div>
      <div class="border border-slate-300 rounded-lg p-4 bg-[#B5E4CA] text-white shadow-md">
         <h2 class="text-xl font-bold mb-2">Completed Projects</h2>
         <div class="flex items-center gap-5">
            <span class="text-2xl font-medium"><i class="fa-regular fa-gem"></i></span>
            <span class="text-6xl font-bold">100+</span>
         </div>
      </div>
   </div>

   <div class="bg-red-400 p-4 rounded-lg bg-slate-100 ">
      <p class="text-slate-500 mb-5">Great things happen when we work together</p>
      <div class="gap-10 flex flex-wrap">
         <div class="flex flex-col bg-white shadow-md items-center gap-2 w-40 rounded-lg p-4">
            <span
               class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-14 h-14 overflow-hidden">
               <img src="http://workfyre.local/assets/images/default-profile.png" class="w-full h-full object-cover"
                  alt="default profile" />
            </span>
            <p class="text-center">Yegosh Thapa</p>
         </div>
         <div class="flex flex-col bg-white shadow-md items-center gap-2 w-40 rounded-lg p-4">
            <span
               class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-14 h-14 overflow-hidden">
               <img src="http://workfyre.local/assets/images/default-profile.png" class="w-full h-full object-cover"
                  alt="default profile" />
            </span>
            <p class="text-center">Yegosh Thapa</p>
         </div>
         <div class="flex flex-col bg-white shadow-md items-center gap-2 w-40 rounded-lg p-4">
            <span
               class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-14 h-14 overflow-hidden">
               <img src="http://workfyre.local/assets/images/default-profile.png" class="w-full h-full object-cover"
                  alt="default profile" />
            </span>
            <p class="text-center">Yegosh Thapa</p>
         </div>
         <div class="flex flex-col bg-white shadow-md items-center gap-2 w-40 rounded-lg p-4">
            <span
               class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-14 h-14 overflow-hidden">
               <img src="http://workfyre.local/assets/images/default-profile.png" class="w-full h-full object-cover"
                  alt="default profile" />
            </span>
            <p class="text-center">Yegosh Thapa</p>
         </div>
         <div class="flex flex-col bg-white shadow-md items-center gap-2 w-40 rounded-lg p-4">
            <span
               class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-14 h-14 overflow-hidden">
               <img src="http://workfyre.local/assets/images/default-profile.png" class="w-full h-full object-cover"
                  alt="default profile" />
            </span>
            <p class="text-center">Yegosh Thapa</p>
         </div>
         <div class=" bg-stone-100 shadow-md w-20 rounded-lg p-4 hover:border hover:border-sky-500 cursor-pointer">
            <a href="teams.php" class="flex flex-col items-center gap-2">
               <span
                  class="rounded-full font-medium border border-slate-300 flex items-center justify-center w-10 h-10 overflow-hidden p-2">
                  <img src="http://workfyre.local/assets/images/back.png" class="w-full h-full object-cover"
                     alt="default profile" />
               </span>
               <p class="text-center">View All</p>
            </a>
         </div>
      </div>


   </div>

   <!-- bar chart  -->
   <div class="w-full p-4">
      <canvas id="myChart"></canvas>
   </div>

</section>
<script>
   const ctx = document.getElementById('myChart');

   new Chart(ctx, {
      type: 'bar',
      data: {
         labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
         datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
         }]
      },
      options: {
         scales: {
            y: {
               beginAtZero: true
            }
         }
      }
   });
</script>