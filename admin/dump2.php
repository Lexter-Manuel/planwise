<?php
require_once('config.php')
?>

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
    <title>PlanWise Registration</title>

<body>
    <!-- register -->
    <div id="content">
    <div class="container" id="signUp">
        <h1 class="form-title">REGISTER</h1>
        <form action="index.php" method="post">
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
                <i class="fas fa-eye" id="togglePassword"></i>
                <input required type="password" name="password" id="password" placeholder="Password">
                <label for="accPass">Password <span style="color:darkblue;">*</span></label>
                 <!-- Eye icon for password visibility -->
            </div>
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <i class="fas fa-eye" id="toggleRepeatPassword"></i>
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
                <select class="form-control" id="gender" name="gender" style="width: 100%; height: 30px;margin-bottom: 10px;">
                    <option value="" disabled selected>Select A Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select><br>
                <h3>Gender <span style="color:darkblue;">*</span></h3>
            </div>
            <p class="recover">
                <a href="">Forgot Password</a>
            </p>
            <input type="submit" class="btn" id="signUpAcc" value="Sign Up" name="signUp">
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
            <button id="signInButton" onclick="loadPage('loginForm.php', 'content')">Sign In</button>
        </div>
    </div>
    </div>



    <!-- sign in -->

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="logregscript.js"></script>

<script>
    function loadPage(url, elementId) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(elementId).innerHTML = xmlhttp.responseText;

                // Ensure DataTable reinitializes after content load
            }
        };
        xmlhttp.open('GET', url, true);
        xmlhttp.send();
    }

</script>

<script>
    $(function(){
        $('#signUpAcc').click(function(e){
            e.preventDefault();
            var valid = this.form.checkValidity();

            if(valid){
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
                

                $.ajax({
                    type: 'POST',
                    url: 'register.php',
                    data: {fname: fname,mname: mname,lname: lname,username: username,password: password,
                            email: email,contact_number: contact_number,
                            birthday: birthday, gender: gender},
                    success: function(data){
                        swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: 'You have successfully registered',
                            type: 'success'
                        })
                    },
                    error: function(data){
                        swal.fire({
                            icon: 'error',
                            title: 'Registration Error',
                            text: 'Error occur while Registering',
                            type: 'error'
                        })
                    }
                });
            }else{
                alert('false');
            }

        })
    })
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

togglePassword.addEventListener("click", function () {
    // Toggle the type of the password input between 'password' and 'text'
    const type = password.type === "password" ? "text" : "password";
    password.type = type;
    // Toggle the eye icon class
    this.classList.toggle("fa-eye-slash");
    this.classList.toggle("fa-eye");
});

// Password visibility toggle for the repeat password field
const toggleRepeatPassword = document.getElementById("toggleRepeatPassword");
const repeatPassword = document.getElementById("repeat_password");

toggleRepeatPassword.addEventListener("click", function () {
    // Toggle the type of the repeat password input between 'password' and 'text'
    const type = repeatPassword.type === "password" ? "text" : "password";
    repeatPassword.type = type;
    // Toggle the eye icon class
    this.classList.toggle("fa-eye-slash");
    this.classList.toggle("fa-eye");
});

const toggleLoginPassword = document.getElementById("toggleLoginPassword");
const loginPassword = document.getElementById("loginPassword");

toggleLoginPassword.addEventListener("click", function () {
    // Toggle the type of the repeat password input between 'password' and 'text'
    const type = loginPassword.type === "password" ? "text" : "password";
    loginPassword.type = type;
    // Toggle the eye icon class
    this.classList.toggle("fa-eye-slash");
    this.classList.toggle("fa-eye");
});

</script>
