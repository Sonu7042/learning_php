<?php

include "configure.php";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Fetch Data</title>
</head>
<script src="https://cdn.tailwindcss.com"></script>
<body>

<div class="relative w-full">
    <button
        class="absolute  right-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-pink-500 hover:to-indigo-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-300"
        onclick="addForm()"
    >
        Add User
    </button>
</div>

<div class="relative w-full">
    <button
        class="absolute  right-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 hover:from-pink-500 hover:to-indigo-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-300"
        onclick="signup()"
    >
        Sign Up
    </button>
</div>



<script>
function addForm() {
    window.location.href = 'addUser.php';
}


function signup() {
    window.location.href = 'signup.php';
}

</script>


<h2 
    class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-center mt-8 mb-6"
>
    Fetched Data
</h2>


    <?php
    $sql = "SELECT * FROM users";  
    $result = mysqli_query($con, $sql);

    // echo "<pre>";
    // print_r ($result);
    // die;

    if (mysqli_num_rows($result) > 0) {
        echo "
<div class='overflow-x-auto'>
    <table class='table-auto w-full border-collapse border border-gray-200'>
        <thead>
            <tr class='bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white'>
                <th class='border border-gray-300 px-4 py-2'>ID</th>
                <th class='border border-gray-300 px-4 py-2'>First Name</th>
                <th class='border border-gray-300 px-4 py-2'>Last Name</th>
                <th class='border border-gray-300 px-4 py-2'>Email</th>
                <th class='border border-gray-300 px-4 py-2'>Password</th>
                <th class='border border-gray-300 px-4 py-2'>Phone No.</th>
                <th class='border border-gray-300 px-4 py-2'>Create Date</th>
                <th class='border border-gray-300 px-4 py-2'>Actions</th>
            </tr>
        </thead>
        <tbody class='bg-white'>";
        
// Example loop to fetch and display data from database
while ($row = mysqli_fetch_assoc($result)) {
    echo "
    <tr class='hover:bg-gray-100'>
        <td class='border border-gray-300 px-4 py-2 text-center'>" . $row["id"] . "</td>
        <td class='border border-gray-300 px-4 py-2'>" . $row["firstName"] . "</td>
        <td class='border border-gray-300 px-4 py-2'>" . $row["lastName"] . "</td>
        <td class='border border-gray-300 px-4 py-2'>" . $row["email"] . "</td>
        <td class='border border-gray-300 px-4 py-2'>" . $row["password"] . "</td>
        <td class='border border-gray-300 px-4 py-2'>" . $row["phoneNo"] . "</td>
        <td class='border border-gray-300 px-4 py-2'>" . $row["create_at"] . "</td>

        <td class='border border-gray-300 px-4 py-2'>
        <button class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600'>Delete</button>
        <button class='bg-blue-500 text-white px-4 py-2 rounded hover:bg-red-600'>Update</button>
        </td>

    </tr>";
}

echo "
        </tbody>
    </table>
</div>";
    } else {
        echo "0 results";
    }
    ?>

</body>
</html>
