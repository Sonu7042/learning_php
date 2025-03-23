<?php
include './config/config.php';
if(isset($_SESSION['loginId']) && $_SESSION['loginId'] != '') {
  header("Location: index.php" );
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Form</title>
  <script src="./assets/css/tailwind.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">
  <!-- Sign-Up Form -->
  <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Create Your Account</h2>
    <p class="text-sm text-gray-600 text-center mb-6">
      Join us today! Fill out the form below to get started.
    </p>
    <form action="./config/server.php" method="POST" id="signupForm" class="space-y-4">
      <input type="text" class="hidden" name="signup">
      <!-- Full Name -->
      <div>
        <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input
          type="text"
          id="fullname"
          name="fullname"
          value="<?php echo $_POST['fullname'] ?? ''; ?>"
          placeholder="Enter your full name"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
        <span class="fullName_error text-red-500 text-sm  hidden "></span>
      </div>
      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          value="<?php echo $_POST['email'] ?? ''; ?>"
          placeholder="Enter your email"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
        <span class="email_error text-red-500 text-sm  hidden "></span>
      </div>
      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          value="<?php echo $_POST['password'] ?? ''; ?>"
          placeholder="Create a password"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
        <span class="password_error text-red-500 text-sm  hidden "></span>
      </div>
      <!-- Confirm Password -->
      <div>
        <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
          type="password"
          id="confirm-password"
          name="confirm-password"
          value="<?php echo $_POST['confirm-password'] ?? ''; ?>"
          placeholder="Confirm your password"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
        <span class="confirmPassword_error text-red-500 text-sm  hidden "></span>
      </div>
      <!-- Sign Up Button -->
      <button
        name="signUp"
        id="singn_btn"
        type="submit"
        class="w-full bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-pink-500 hover:to-indigo-500 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
        Sign Up
      </button>
      <!-- Already have an account -->
      <div id="result"></div>
      <p class="text-center text-sm text-gray-600 mt-4">
        Already have an account?
        <a href="login.php" class="text-indigo-500 font-medium hover:underline">Log In</a>
      </p>
    </form>
  </div>

  <script src="./assets/js/jquery-3.6.4.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script>
    $(document).on('click', "#singn_btn", function(e) {
      toastr.options = {
        'closeButton': true,
        'debug': false,
        'newestOnTop': true,
        'progressBar': true,
        'positionClass': 'toast-top-right',
        'preventDuplicates': true,
        'showDuration': '1000',
        'hideDuration': '1000',
        'timeOut': '1500',
        'extendedTimeOut': '1000',
        'showEasing': 'swing',
        'hideEasing': 'linear',
        'showMethod': 'fadeIn',
        'hideMethod': 'fadeOut',
      }

      e.preventDefault()

      let val = true
      let fullName = $("#fullname").val();
      let email = $("#email").val();
      let password = $("#password").val();
      let confirmPassword = $("#confirm-password").val();

      let error_count = 0

      if (fullName == "") {
        $(".fullName_error").text("name is required");
        $(".fullName_error").removeClass("hidden");
        $("#fullname").addClass('border-red-500')
        val = false;
        error_count++
      }

      if (email == "") {
        $(".email_error").text("email is required");
        $(".email_error").removeClass("hidden");
        $("#email").addClass('border-red-500')
        val = false;
        error_count++
      }

      if (password == "") {
        $(".password_error").text("password is required");
        $(".password_error").removeClass("hidden");
        $("#password").addClass('border-red-500')
        val = false;
        error_count++
      }

      if (confirmPassword == "") {
        $(".confirmPassword_error").text("confirmPassword is required");
        $(".confirmPassword_error").removeClass("hidden");
        $("#confirm-password").addClass('border-red-500')
        val = false;
        error_count++

      } else if (confirmPassword !== password) {
        $(".confirmPassword_error").text("Passwords do not match").removeClass("hidden");
        $("#confirm-password").addClass('border-red-500');
        val = false;
        error_count++
      }

      if (error_count == 0) {
        let form = $('#signupForm');
        let url = form.attr('action');
        let method = form.attr('method');


        $.ajax({
          type: method, 
          url: url,
          data: form.serialize(),
          success: function(response) {
            const res = JSON.parse(response)
            console.log(res)
            if (res.success == true) {
              toastr.success(res.message)
              setTimeout(() => {
                window.location.href = "index.php";
              }, 1500)

            } else {
              if (res.emailError) {
                $(".email_error").text(res.emailError);
                $(".email_error").removeClass("hidden");
                $("#email").addClass('border-red-500')
              } else {
                $(".email_error").text("");
                $(".email_error").addClass("hidden");
                $("#email").removeClass('border-red-500')
              }
              if (res.confirmPasswordError) {
                $(".confirmPassword_error").text(res.confirmPasswordError);
                $(".confirmPassword_error").removeClass("hidden");
                $("#confirm-password").addClass('border-red-500')
              } else {
                $(".confirmPassword_error").text("");
                $(".confirmPassword_error").addClass("hidden");
                $("#confirm-password").removeClass('border-red-500')
              }

              if (res.message) {
                toastr.error(res.message);
              }
            }
          },
          error: function(response) {
            alert("Error occurred while submitting the form");
          }
        });
      }
    })



    $(document).on("keyup", "#fullname", function(e) {
      let fullName = $(this).val();
      if (fullName == '') {
        $(".fullName_error").text("name is required");
        $(".fullName_error").removeClass("hidden");
        $("#fullname").addClass('border-red-500');
      } else {
        $(".fullName_error").text("");
        $(".fullName_error").addClass("hidden");
        $("#fullname").removeClass('border-red-500');
      }
    })


    $(document).on("keyup", "#email", function(e) {
      let fullName = $(this).val();
      if (fullName == '') {
        $(".email_error").text("email is required");
        $(".email_error").removeClass("hidden");
        $("#email").addClass('border-red-500');
      } else {
        $(".email_error").text("");
        $(".email_error").addClass("hidden");
        $("#email").removeClass('border-red-500')

      }
    })


    $(document).on("keyup", "#password", function(e) {
      let fullName = $(this).val();
      if (fullName == '') {
        $(".password_error").text("password is required");
        $(".password_error").removeClass("hidden");
        $("#password").addClass('border-red-500');
      } else {
        $(".password_error").text("");
        $(".password_error").addClass("hidden");
        $("#password").removeClass('border-red-500')

      }
    })


    $(document).on("keyup", "#confirm-password", function(e) {
      let fullName = $(this).val();
      if (fullName == '') {
        $(".confirmPassword_error").text("confirmPassword is required");
        $(".confirmPassword_error").removeClass("hidden");
        $("#confirm-password").addClass('border-red-500');
      } else {
        $(".confirmPassword_error").text("");
        $(".confirmPassword_error").addClass("hidden");
        $("#confirm-password").removeClass('border-red-500')

      }
    })
  </script>
  </>
</body>

</html>