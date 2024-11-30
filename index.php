<?php
    require_once('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" 
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="logregStyle.css">
    <style>
        .fas.fa-eye, .fas.fa-eye-slash {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 30%;
            transform: translateY(-50%);
        }


    </style>
    <title>PlanWise Login</title>
</head>
<body>
    <!-- register -->
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form action="register.php" method="post">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="Username">
                <label for="usernameAcc">Username</label>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <i class="fas fa-eye" id="togglePassword"></i>
                <input type="password" name="password" id="loginPassword" placeholder="Password">
                <label for="accPass">Password</label>
            </div>
            <p class="recover">
                <a href="">Forgot Password</a>
            </p>
            <input type="submit" class="btn" id="loginAcc" value="Sign In" name="signIn">
        </form>
        <p class="or">
            --------or--------
        </p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Don't have an account yet?</p>
            <a href="register.php"><button id="signUpButton">Sign Up</button></a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="logregscript.js"></script>

    <script>
        $(function(){
            $('#loginAcc').click(function(e){
                e.preventDefault();

                var valid = this.form.checkValidity();

                if(valid){
                    var username = $('#username').val();
                    var password = $('#loginPassword').val();
                }

                $.ajax({
                    type: 'POST',
                    url: 'jslogin.php',
                    data: {username: username, password: password},
                    success: function(response){
                        console.log(response);
                        var result = JSON.parse(response);
                            if (result.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Login Successful!',
                                    text: result.message,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3085d6',
                                    timer: 3000
                                }).then(()=> {
                                    window.location.href = result.redirect_url || "default.php";
                                })
                            } else if (result.status === 'error') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Failed!',
                                    text: result.message,
                                    confirmButtonText: 'Try Again',
                                    confirmButtonColor: '#d33'
                                });
                            }
                    }
                })
            });
        });
    </script>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const password = document.getElementById("loginPassword");

            if (togglePassword && password) { // Ensure both elements exist before adding the event listener
                togglePassword.addEventListener("click", function () {
                    // Toggle the type of the password input between 'password' and 'text'
                    const type = password.type === "password" ? "text" : "password";
                    password.type = type;
                    // Toggle the eye icon class
                    this.classList.toggle("fa-eye-slash");
                    this.classList.toggle("fa-eye");
                });
            }
    </script>
</body>