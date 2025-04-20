<?php include_once('public-templates/public-header.php');

?>
<section class="w-full  h-full flex items-center justify-center">
<div class="w-1/2 mt-20 bg-white text-black px-10 py-20 rounded-3xl">
    <div class="items-center justify-center flex flex-col mb-10">
        <h2>Workfyre</h2>
        <h1 class="text-3xl font-medium">Welcome to the workfyre</h1>
        <p>Manage and Track Your Projects</p>
    </div>
    <form class="items-center justify-center">
        <div>
            <div class="flex flex-col mb-5">
                <label class="mb-2">Email:</label>
                <input type="email" class="border py-2 px-4 text-lg rounded-2xl border-slate-300" name="email"
                    placeholder="Enter your email" required />
            </div>
            <div class="flex flex-col mb-5">
                <label class="mb-2">Password:</label>
                <input type="password" class="border py-2 px-4 text-lg rounded-2xl border-slate-300" name="password"
                    placeholder="Enter your password" required />
            </div>
        </div>
        <div>
            <div class="flex flex-col justify-center gap-5 mb-5">
                <a href="forgot-password.php">Forgot password?</a>
                <button type="submit" class="hover:bg-stone-900 hover:text-white text-xl font-medium border px-4 py-2 rounded-full">Login</button>
            </div>
            <div>
                <p>Don't have account? <a href="register.php" class="text-blue-400">Register</a></p>
            </div>
        </div>
    </form>
</div>
</section>