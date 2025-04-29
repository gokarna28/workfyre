<?php include_once('public-templates/public-header.php');

?>
<section class="w-full  h-full flex items-center justify-center">
    <div class="w-1/2 mt-20 bg-white text-black px-10 py-20 rounded-xl mb-10">
        <div class="items-center justify-center flex flex-col mb-10">
            <h2>Workfyre</h2>
            <h1 class="text-3xl font-medium">Welcome to the workfyre</h1>
            <p>Manage and Track Your Projects</p>
        </div>
        <div class="mb-2" id="successMessage"></div>

        <form id="userLoginForm" class="items-center justify-center" method="POST">
            <div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">Email:</label>
                    <input type="email" class="border py-2 px-4 text-lg rounded-lg border-slate-300" name="email"
                        placeholder="Enter your email" required />
                    <span id="emailMessage" class="text-sm font-light text-red-600"></span>
                </div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">Password:</label>
                    <input type="password" class="border py-2 px-4 text-lg rounded-lg border-slate-300" name="password"
                        placeholder="Enter your password" required />
                </div>
            </div>
            <div>
                <div class="flex flex-col justify-center gap-5 mb-5">
                    <a href="send-otp.php">Forgot password?</a>
                    <button type="submit"
                        class="bg-[#181832] text-white hover:bg-transparent hover:text-[#181832] cursor-pointer text-xl font-bold hover:border p-4 rounded-lg">Login</button>
                </div>
                <div>
                    <p>Don't have account? <a href="<?php echo HOMEPAGE_URL ?>/main/register.php"
                            class="text-blue-400">Register</a></p>
                </div>
            </div>
        </form>
    </div>
</section>