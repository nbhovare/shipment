<?php

    session_start();
    if(isset($_SESSION['isSession'])){        
        if($_SESSION["isSession"]=="true"){            
            header("location:./index.php");
        }
        else{            
            // Unset all session variables
                //$_SESSION = array();

                // Unset the session data
                unset($_SESSION['isSession']);
                unset($_SESSION['user_id']);
                unset($_SESSION['type']);
                unset($_SESSION['status']);

                // Destroy the session
                session_destroy();
        }
    }

?>


<!-- 
=========================================================
 Light Bootstrap Dashboard - v2.0.1
=========================================================

 Product Page: https://www.creative-tim.com/product/light-bootstrap-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com)
 Licensed under MIT (https://github.com/creativetimofficial/light-bootstrap-dashboard/blob/master/LICENSE)

 Coded by Creative Tim

=========================================================

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.  -->
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Login</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/css/demo.css" rel="stylesheet" />
    <script src="../assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.2.0/bcrypt.min.js" integrity="sha512-BJZhA/ftU3DVJvbBMWZwp7hXc49RJHq0xH81tTgLlG16/OkDq7VbNX6nUnx+QY4bBZkXtJoG0b0qihuia64X0w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bcryptjs/2.2.0/bcrypt.js" integrity="sha512-tFbGxu83rXLUBv8rUnccoJqzpYG3SiZnycAZph1kmsxcwoPnWBhL2ILP6on/6jm7dgQDDGOuNAwNRRmAzDo5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        .center-div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }        
    </style>

        <script>
            $(document).ready(function () {
                $("#passCheck").click(function () {
                    if ($(this).is(":checked")) {
                        $("#password").attr("type", "text");
                    } else {
                        $("#password").attr("type", "password");
                    }
                });

                $("#loginForm").submit(function(e){
                    e.preventDefault();

                    // some other

                    var username = $('#username').val(); // Assuming your username field has an ID of 'usernameField'
    var password = $('#password').val(); // Assuming your password field has an ID of 'passwordField'

    // Hash the password using SHA-256
    async function hashPassword(password) {
        const encoder = new TextEncoder();
        const data = encoder.encode(password);
        const hash = await crypto.subtle.digest('SHA-256', data);
        return hash;
    }

    hashPassword(password)
        .then(hashedPassword => {
            // Convert the hashed password to a hexadecimal string
            const hashedPasswordHex = Array.from(new Uint8Array(hashedPassword))
                .map(byte => byte.toString(16).padStart(2, '0'))
                .join('');

            // Create an object with username and hashed password
            var formData = {
                username: username,
                password: hashedPasswordHex
            };

            // Convert the object to a JSON string
            var formDataJSON = JSON.stringify(formData);
            // some other


                    /*// Prepare Data                                                            
                    var username = $('#usernameField').val();
                    var password = $('#passwordField').val();
                    var encryptedPassword = CryptoJS.AES.encrypt(password, 'your_secret_key').toString();
                    var formData = {
                        username: username,
                        password: encryptedPassword
                    };
                    var formDataJSON = JSON.stringify(formData);*/
                    // Prepare Data  

                    // old 
                   /* var formData = $('#loginForm').serializeArray();
                    // Convert the serialized form data to a JSON object
                    var formDataObject = {};
                    $.each(formData, function(index, field) {
                        formDataObject[field.name] = field.value;
                    });

                    // Convert the JSON object to a JSON string
                    var formDataJSON = JSON.stringify(formDataObject);           
                    // old*/

                    $.ajax({
                        type: "POST",
                        url: './queries/auth.php',                        
                        data: {data : formDataJSON},
                        success: function(response)
                        {                                        
                            if(response[0].msg==="1"){
                                window.location.href="./index.php";
                            }
                            else{
                                alert(response[0].msg);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Ajax request failed with status: " + status + " and error: " + error);
                            // You can provide a more user-friendly error message or handle errors as needed.
                        }
                    });
                    
                    })
                    .catch(error => {
            console.error('Error hashing password:', error);
        });

                })

            });
        </script>
</head>

<body style='background: linear-gradient(to right, 
  rgba(0, 0, 255, 0.5), /* Blue with 50% transparency */
  rgba(255, 0, 0, 0.5)   /* Red with 50% transparency */
);'>
                     

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 offset-md-3 center-div">
                            <div class="card">
                                <div class="card-header ">
                                    <h4 class="card-title">Blue Express Login</h4>
                                    <p class="card-category">Login to your account</p>
                                </div>
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form id="loginForm" method="POST">
                                                <div class="form-group">
                                                    <label><b>Email Address</b></label>
                                                    <input type="email" id="username" name="username" class="form-control" placeholder="Enter Rrgistsred email address" required>
                                                </div>
                                                <div class="form-group">
                                                    <label><b>Password</b></label>
                                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" required>
                                                </div>
                                                <table>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" id="passCheck" type="checkbox" value="">
                                                                <span class="form-check-sign"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>Show Password</td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <div class="form-group">                                                    
                                                    <button type="submit" class="btn btn-warning" id="loginBtn" name="loginBtn" class="form-control">Login</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>                  
                </div>
            </div>
            
      

</body>
<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="../assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="../assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="../assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="../assets/js/demo.js"></script>
</html>
