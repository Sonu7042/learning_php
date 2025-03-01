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
  <title>Login</title>
  <script src="./assets/css/tailwind.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">
  <!-- Sign-Up Form -->
  <div class="max-w-md w-full bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Login</h2>
    <p class="text-sm text-gray-600 text-center mb-6">
      Join us today! Fill out the form below to get started.
    </p>
    <form action="./config/server.php" method="POST" id="loginForm" class="space-y-4">
      <!-- Email -->
      <input type="text" name="login" class="hidden">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="Enter your email"
          value="<?php echo $_POST['email'] ?? ''; ?>"
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

      <!-- Sign Up Button -->
      <button
        name="postLogin"
        id="login_btn"
        type="submit"
        class="w-full bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-pink-500 hover:to-indigo-500 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
        Login
      </button>
      <!-- Already have an account -->
      <p class="text-center text-sm text-gray-600 mt-4">
        Don't have an account?
        <a href="signup.php" class="text-indigo-500 font-medium hover:underline">Sign Up</a>
      </p>
    </form>
  </div>

  <script src="./assets/js/jquery-3.6.4.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script>
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

    $(document).on('click', "#login_btn", function(e) {
      e.preventDefault();

      let email = $("#email").val();
      let password = $("#password").val();

      let errorCount = 0;


      if (email == "") {
        $(".email_error").text("email is required");
        $(".email_error").removeClass("hidden");
        $("#email").addClass('border-red-500')
        errorCount++;
      }
      if (password == "") {
        $(".password_error").text("password is required");
        $(".password_error").removeClass("hidden");
        $("#password").addClass('border-red-500')
        errorCount++;
      }


      if (errorCount == 0) {
        let form = $("#loginForm");
        let url = form.attr('action');
        let method = form.attr('method');

        $.ajax({
          type: method,
          url: url,
          data: form.serialize(),
          success: function(response) {
            const res = JSON.parse(response);
            // console.log(res)
            if (res.success == true) {
              toastr.success(res.message)

              setTimeout(() => {
                 window.location.href = "index.php"
              }, 1500);

            } else {
              if (res.notFound) {
                $(".email_error").text(res.notFound);
                $(".email_error").removeClass("hidden");
                $("#email").addClass('border-red-500')
              } else {
                $(".email_error").text("");
                $(".email_error").addClass("hidden");
                $("#email").removeClass('border-red-500')
              }

              if (res.passwardError) {
                $(".password_error").text(res.passwardError);
                $(".password_error").removeClass("hidden");
                $("#password").addClass('border-red-500')
              } else {
                $(".password_error").text("");
                $(".password_error").addClass("hidden");
                $("#password").removeClass('border-red-500')
              }

            }
          },
          error: function(response) {
            alert("Error occurred while submitting the form");
          }
        });
      }
    })


    $(document).on("keyup", "#email", function(e) {
      let email = $(this).val();
      if (email == '') {
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
      let password = $(this).val();
      if (password == '') {
        $(".password_error").text("password is required");
        $(".password_error").removeClass("hidden");
        $("#password").addClass('border-red-500');
      } else {
        $(".password_error").text("");
        $(".password_error").addClass("hidden");
        $("#password").removeClass('border-red-500')
      }
    })
  </script>

</body>

</html>