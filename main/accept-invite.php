<?php
include_once('public-templates/public-header.php');

$invite_id = isset($_GET['invite_id']) ? $_GET['invite_id'] : "";
$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : "";

$project = getProjectDetailsByProjectID($project_id);
?>
<section class="w-full  h-full flex items-center justify-center">
    <div class="w-1/2 mt-20 bg-white text-black px-10 py-20 rounded-xl mb-10">
        <div class="items-center justify-center flex flex-col mb-10">
            <h2>Workfyre</h2>
            <h1 class="text-3xl font-medium">Welcome to the workfyre</h1>
            <p>Manage and Track Your Projects</p>
        </div>
        <div class="mb-2" id="successMessage"></div>

        <form id="acceptTeamToProject" method="POST">
            <input type="hidden" name="invite_id" value="<?php echo $invite_id; ?>" />
            <p class="mb-5 text-xl">You're invited to join the project <?php echo ucfirst($project['title']) ?>. We’re
                excited to have you on board—let’s create something amazing together!
                Please accept the invitation to begin contributing.</p>
            <div class="flex flex-col justify-center gap-5 mb-5">
                <button type="submit"
                    class="bg-[#181832] text-white hover:bg-transparent hover:text-[#181832] cursor-pointer text-xl font-bold hover:border p-4 rounded-lg">Accept</button>
            </div>
        </form>
    </div>
</section>