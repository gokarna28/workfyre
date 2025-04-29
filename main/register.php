<?php

include_once('public-templates/public-header.php');

?>
<section class="w-full  h-full flex items-center justify-center">
    <div class="w-1/2 mt-20 bg-white text-black px-10 py-20 rounded-xl mb-10">
        <div class="items-center justify-center flex flex-col mb-10">
            <h2>Workfyre</h2>
            <h1 class="text-3xl font-medium">Welcome to the workfyre</h1>
            <p>Manage and Track Your Projects</p>
        </div>
        <div class="mb-2" id="successMessage"></div>
        
        <form id="userRegisterForm" class="items-center justify-center" method="POST">
            <div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">First Name:</label>
                    <input type="text" class="border py-2 px-4 text-lg rounded-lg border-slate-300 mb-1" name="firstname"
                        placeholder="Enter your first name" required />
                        <span id="firstnameMessage" class="text-sm font-light text-red-600"></span>
                </div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">Last Name:</label>
                    <input type="text" class="border py-2 px-4 text-lg rounded-lg border-slate-300 mb-1" name="lastname"
                        placeholder="Enter your last name" required />
                    <span id="lastnameMessage" class="text-sm font-light text-red-600"></span>
                </div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">Email:</label>
                    <input type="email" class="border py-2 px-4 text-lg rounded-lg border-slate-300 mb-1" name="email"
                        placeholder="Enter your email" required />
                    <span id="emailMessage" class="text-sm font-light text-red-600"></span>
                </div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">Password:</label>
                    <input type="password" class="border py-2 px-4 text-lg rounded-lg border-slate-300 mb-1" name="password"
                        placeholder="Enter your password" required />
                </div>
                <div class="flex flex-col mb-5">
                    <label class="mb-2">Confirm Password:</label>
                    <input type="password" class="border py-2 px-4 text-lg rounded-lg border-slate-300 mb-1"
                        name="confirm_password" placeholder="Retype your password" required />
                    <span id="confirmPasswordMessage" class="text-sm font-light text-red-600"></span>
                </div>
            </div>
            <div>
                <div class="flex flex-col justify-center gap-5 mb-5">
                    <button type="submit"
                        class="bg-[#181832] text-white hover:bg-transparent hover:text-[#181832] cursor-pointer text-xl font-bold hover:border p-4 rounded-lg">Register</button>
                </div>
                <div>
                    <p>Already have account? <a href="<?php echo HOMEPAGE_URL ?>/main/login.php"
                            class="text-blue-400">Login</a></p>
                </div>
            </div>
        </form>
    </div>
</section>
<?php include_once('public-templates/public-footer.php');

?>