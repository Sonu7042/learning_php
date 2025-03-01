
<?php
include "configure.php";

if (isset($_POST['dataSubmit'])) {
    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $phoneno   = $_POST['phoneno'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailResult = mysqli_query($con, $checkEmailQuery);

    if (mysqli_num_rows($emailResult) > 0) {
       ?>
       <script>alert("Email already exist")</script>
       <?php

    } else {
        // Insert new record if email doesn't exist
        $sql = "INSERT INTO users (firstName, lastName, phoneNo, email, password) 
                VALUES ('$firstname', '$lastname', '$phoneno', '$email', '$password')";

        if (mysqli_query($con, $sql)) {
            header("Location: index.php");
        } else {
            echo "<script>alert('Data not inserted. Please try again.');</script>";
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/css"></style>
</head>
<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full bg-white p-2 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Registration Form</h2>
        <form action="" method="post" class="space-y-6 px-6 py-4">
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="firstname" name="firstname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="lastname" name="lastname" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="phoneno" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phoneno" name="phoneno" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button name="dataSubmit" type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sign up
            </button>
        </form>
    </div>
</body>
</html>
