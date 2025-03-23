<?php
include './config/config.php';
// print_r($_SESSION);
if (!isset($_SESSION['loginId']) || $_SESSION['loginId'] == '') {
    header("Location: login.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <script src="./assets/css/tailwind.js"></script>
</head>

<body class="bg-gray-50 text-gray-800  ">
    <!-- main -->
    <main class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white h-[90vh]">
        <?php if (isset($_SESSION['loginId']) && $_SESSION['loginId'] != '') { ?>

            <div class="w-full flex justify-between gap-3 py-2 px-4">
                <h1 class="text-4xl font-bold "><?php echo ($_SESSION['loginUserData']['name'] ?? ""); ?></h1>
                <a href="session.php" class="bg-white text-indigo-600 font-semibold py-3 px-6 h-auto rounded-lg shadow-lg hover:bg-gray-100 transition duration-300">
                    Logout
                </a>
            </div>

        <?php } else { ?>
            <div class="w-full flex justify-end gap-3 py-2 px-4">
                <a href="login.php" class="bg-white text-indigo-600 font-semibold py-3 px-6 rounded-lg shadow-lg hover:bg-gray-100 transition duration-300">
                    Login
                </a>
                <a href="signup.php" class="bg  -white text-indigo-600 font-semibold py-3 px-6 rounded-lg shadow-lg hover:bg-gray-100 transition duration-300">
                    SignUp
                </a>    
            </div>

        <?php } ?>


        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-5xl font-extrabold mb-4">
                Welcome to Your Todo
            </h1>
            <p class="text-lg font-medium mb-6">
                Here You can manage your tasks
            </p>
            <div class="flex justify-center space-x-4">
                <a href="todoList.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-300">
                    Get Started
                </a>
            </div>
        </div>
    </main>


    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6 h-[10vh]">
        <div class="max-w-6xl mx-auto text-center">
            <p>&copy; 2025 Your Company Name. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>