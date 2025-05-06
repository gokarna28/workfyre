<?php include_once('../sidebar.php'); ?>
<?php include_once('../header.php'); ?>

<section class="pt-25 pl-85 w-full pr-10">
   <p>Manage and Track Your Projects</p>
   <div class="w-full flex items-center gap-20 p-4">
      <div class="border border-slate-300 rounded p-4 bg-[#CABDFF] text-white">
         <h2 class="text-xl font-bold mb-2">Total Team Members</h2>
         <div class="flex items-center gap-5">
            <span class="text-2xl font-medium"><i class="fa-regular fa-user"></i></span>
            <span class="text-6xl font-bold">100+</span>
         </div>
      </div>
      <div class="border border-slate-300 rounded p-4 bg-[#FFBC99] text-white">
         <h2 class="text-xl font-bold mb-2">Total Projects</h2>
         <div class="flex items-center gap-5">
            <span class="text-2xl font-medium"><i class="fa-solid fa-file-waveform"></i></span>
            <span class="text-6xl font-bold">100+</span>
         </div>
      </div>
      <div class="border border-slate-300 rounded p-4 bg-[#B5E4CA] text-white">
         <h2 class="text-xl font-bold mb-2">Completed Projects</h2>
         <div class="flex items-center gap-5">
            <span class="text-2xl font-medium"><i class="fa-regular fa-gem"></i></span>
            <span class="text-6xl font-bold">100+</span>
         </div>
      </div>
   </div>
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