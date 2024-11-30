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
        .secondpass {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 30%;
            transform: translateY(-50%);
        }
        .firstpass {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 30%;
            transform: translateY(-80%);
        }
        
    </style>
    <title>PlanWise Registration</title>
</head>
<body>
    <!-- register -->
    <div class="container" id="signUp">
        <h1 class="form-title">REGISTER</h1>
        <form action="register.php" method="post">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input required type="text" name="fname" id="fname" placeholder="First Name">
                <label for="first">First Name <span style="color:darkblue;">*</span></label>
            </div>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="mname" id="mname" placeholder="Middle Name">
                <label for="midname">Middle Name</label>
            </div>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input required type="text" name="lname" id="lname" placeholder="Last Name"
                ><label for="lastname">Last Name <span style="color:darkblue;">*</span></label>
            </div>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input required type="text" name="username" id="username" placeholder="Username">
                <label for="usernameAcc">Username <span style="color:darkblue;">*</span></label>
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <i class="fas fa-eye firstpass" id="togglePassword"></i>
                <input required type="password" name="password" id="password" minlength="8" maxlength="20" placeholder="Password">
                <label for="accPass">Password <span style="color:darkblue;">*</span></label>
                <div id="passwordStrength" style="margin-top: 5px;"></div>
                 <!-- Eye icon for password visibility -->
            </div>
            <div class="form-group" style="margin-top: 20px;">
                <i class="fas fa-lock"></i>
                <i class="fas fa-eye secondpass" id="toggleRepeatPassword"></i>
                <input required type="password" name="repeat_password" id="repeat_password" placeholder="Repeat Password">
                <label for="repeatPass">Repeat Password <span style="color:darkblue;">*</span></label>
                 <!-- Eye icon for repeat password visibility -->
            </div>
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input required type="email" name="email" id="email" placeholder="example123@gmail.com">
                <label for="emailAdd">Email <span style="color:darkblue;">*</span></label>
            </div>
            <div class="form-group">
                <i class="fas fa-phone"></i>
                <input required type="text" name="contact_number" id="contact_number" placeholder="09123456789">
                <label for="cnum">Contact Number <span style="color:darkblue;">*</span></label>
            </div>
            <div class="form-group">
                <i class="fas fa-calendar"></i> 
                <input required type="date" name="birthday" id="birthday">
                <h3>Birthday <span style="color:darkblue;">*</span></h3>
            </div>
            <div class="radiogroup">
                <select reqiored class="form-control" id="gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <h3>Gender <span style="color:darkblue;">*</span></h3>
            </div>
            <p class="recover">
                <a href="">Forgot Password</a>
            </p>
            <input type="submit" class="btn" id="registerAcc" value="Sign Up" name="signUp">
        </form>
        <p class="or">
            --------or--------
        </p>
        <div class="icons">
            <i class="fab fa-google"></i>
            <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
            <p>Already have an account?</p>
            <a href="index.php"><button id="signInButton">Sign In</button></a>
        </div>
    </div>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="logregscript.js"></script>


<script>
$(function () {
    $('#registerAcc').click(function (e) {
        e.preventDefault();

        var valid = this.form.checkValidity();
        if (valid) {
            var fname = $('#fname').val();
            var mname = $('#mname').val();
            var lname = $('#lname').val();
            var username = $('#username').val();
            var password = $('#password').val();
            var repeat_password = $('#repeat_password').val();
            var email = $('#email').val();
            var contact_number = $('#contact_number').val();
            var birthday = $('#birthday').val();
            var gender = $('#gender').val();

            if (password === repeat_password) {
                $.ajax({
                    type: 'POST',
                    url: 'process.php',
                    data: {
                        fname: fname,
                        mname: mname,
                        lname: lname,
                        username: username,
                        password: password,
                        email: email,
                        contact_number: contact_number,
                        birthday: birthday,
                        gender: gender
                    },
                    success: function (response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful!',
                                text: result.message,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                window.location.href = 'index.php'; // Redirect after success
                            });
                        } else if (result.status === 'error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed!',
                                text: result.message,
                                confirmButtonText: 'Try Again',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `An unexpected error occurred: ${xhr.responseText || error}`,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Mismatch',
                    text: 'The passwords do not match. Please re-enter.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f39c12'
                });
            }
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Invalid Form',
                text: 'Please complete all required fields correctly.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3498db'
            });
        }
    });
});

</script>


<script>
    const passwordField = document.getElementById('password');
    const strengthMeter = document.getElementById('passwordStrength');

    passwordField.addEventListener('input', () => {
        const password = passwordField.value;
        const strength = getPasswordStrength(password);
        strengthMeter.textContent = `Strength: ${strength.label}`;
        strengthMeter.style.color = strength.color;
    });

    function getPasswordStrength(password) {
        let score = 0;

        if (password.length >= 8) score++; // Minimum length
        if (password.length > 12) score++; // Extra length
        if (/[A-Z]/.test(password)) score++; // Uppercase letters
        if (/[a-z]/.test(password)) score++; // Lowercase letters
        if (/[0-9]/.test(password)) score++; // Numbers
        if (/[@$!%*?&#]/.test(password)) score++; // Special characters

        // Map score to strength level
        const levels = [
            { label: "Very Weak", color: "red" },
            { label: "Weak", color: "orange" },
            { label: "Fair", color: "yellow" },
            { label: "Good", color: "blue" },
            { label: "Strong", color: "green" },
        ];

        return levels[Math.min(score, levels.length - 1)];
    }
</script>


<script>

    // Add this to your form submission script
const form = document.querySelector('form');
form.addEventListener('submit', function (e) {
    const password = document.getElementById("password").value;
    const repeatPassword = document.getElementById("repeat_password").value;

    if (password !== repeatPassword) {
        e.preventDefault();  // Prevent form submission
        alert("Passwords do not match!");
    }
});

// Password visibility toggle for the main password field
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

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

// Password visibility toggle for the repeat password field
const toggleRepeatPassword = document.getElementById("toggleRepeatPassword");
const repeatPassword = document.getElementById("repeat_password");

if (toggleRepeatPassword && repeatPassword) { // Ensure both elements exist before adding the event listener
    toggleRepeatPassword.addEventListener("click", function () {
        // Toggle the type of the repeat password input between 'password' and 'text'
        const type = repeatPassword.type === "password" ? "text" : "password";
        repeatPassword.type = type;
        // Toggle the eye icon class
        this.classList.toggle("fa-eye-slash");
        this.classList.toggle("fa-eye");
    });
}


</script>
</html>