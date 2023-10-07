<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: Landing_Page.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="vendor/images/Banner-Logo.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="vendor/Bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="vendor/Fontawesome/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="vendor/DataTables/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="vendor/SweetAlert/bootstrap-4.css" />
    <link rel="stylesheet" type="text/css" href="vendor/css/custom.index.css" />

    <script type="text/javascript" src="vendor/JQuery/jquery.min.js"></script>
    <script type="text/javascript" src="vendor/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="vendor/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="vendor/SweetAlert/sweetalert2.min.js"></script>
    <script type="text/javascript" src="vendor/js/jquery.mask.js"></script>
    <script type="text/javascript" src="vendor/js/moment.js"></script>

    <title>Banner Web App</title>
</head>

<body class="fade-page">
    <main class="d-flex justify-content-center align-items-center login-main">
        <section class="row login-container shadow-lg rounded">
            <div class="col-lg-6 col-md-12 d-none d-md-block text-description">
                <div class="p-5 my-5">
                    <h1 class="fw-bold mb-5">Banner
                        <span class="text-shadow">
                            Plasticard Inc.
                        </span>
                    </h1>
                    <p class="fs-5">
                        Banner Plasticard's true commitment to quality, excellence and innovation has long highlighted
                        the company's business sense since its inception.
                    </p>
                    <p class="fs-5">
                        True to its consistent exceptional performance, in a span of just four years from 2004 to 2008,
                        Banner Plasticard received international recognition for its achievements and positioned it
                        among the ranks of world-class quality management firms.
                    </p>
                </div>
            </div>
            <div class="col col-lg-6 col-md-12 login-form px-4 py-5">
                <div class="mb-5 text-center">
                    <img class="img-logo" src="vendor/images/Banner-Logo.png" alt="logo">
                </div>

                <div class="mb-5 text-center">
                    <h2 class="text-danger">Account Login</h2>
                    <!-- <p class="text-muted">Please enter your username and password.</p> -->
                </div>

                <div class="wrap-input100 validate-input mb-4">
                    <input class="input100 login-input" id="username" type="text" placeholder="Username">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100"><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                </div>
                <div class="wrap-input100 validate-input mb-4">
                    <input class="input100 login-input" id="password" type="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
                </div>
                <div class="form-check show-password-wrapper mb-4">
                    <input class="form-check-input border-secondary show-password" type="checkbox" id="show_password">
                    <label class="form-check-label text-secondary" for="show_password">
                        Show Password
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" onclick="login();" class="btn btn-danger mb-4 p-2 login-btn rounded-pill">
                        Login
                    </button>
                </div>
                <!-- <div class="d-flex justify-content-center align-items-center">
                    <a href="#!" onclick="forgotPass();" class="text-forgot login-forgotpass">Forgot password?</a>
                </div> -->
            </div>
        </section>
        <footer class="d-flex align-items-center justify-content-md-end justify-content-center login-footer">
            <p class="text-center text-white">&copy; 2022 Banner Plasticard, Inc. All rights reserved.</p>
        </footer>
    </main>
    <div class="square-1"></div>
    <div class="square-2"></div>

    <!-- Modal Change Password-->
    <div class="modal fade" id="modal_reset_password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="modal-title mb-4 text-center text-danger fw-bold">Change Password</h3>
                        <div class="alert alert-secondary reset-alert" role="alert">
                        </div>
                        <input type="hidden" id="user_id">

                        <div class="mb-2">
                            <label class="form-label" for="new_password">New Password:</label>
                            <input type="password" class="form-control fw-bold disabled" name="new_password" id="new_password">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control fw-bold disabled" name="confirm_password" id="confirm_password">
                            <div class="invalid-feedback"></div>
                        </div>


                        <div class="d-grid gap-2">
                            <button type="button" onclick="resetPassword();" class="btn btn-danger fw-bold">Save</button>
                            <button type="button" class="btn btn-light text-danger fw-bold" data-bs-dismiss="modal">Cancel</button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Forgot Password -->
    <div class="modal fade" id="modal_forgot_password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    <h4 class="modal-title mb-4 text-center text-danger fw-bold">Forgot Password</h3>
                        <div class="mb-4">
                            <label class="form-label fw-bold" for="username_forgot_password">Enter your
                                Username:</label>
                            <input type="text" class="form-control" id="username_forgot_password" name="username_forgot_password" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" id="forgot_password" class="btn btn-danger fw-bold">Submit</button>
                            <button type="button" class="btn btn-light text-danger fw-bold" data-bs-dismiss="modal">Cancel</button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal One Time Password -->
    <div class="modal fade" id="modal_otp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">

                    <h6 class="text-danger text-center"><i class="fa-solid fa-circle-info"></i>Administrator Has Been
                        Notified Please Wait.</h6>

                    <!-- <h4 class="modal-title mb-4 text-center text-danger fw-bold">One Time Password</h3>
                        <div class="mb-4">
                            <label class="form-label" for="one_time_password">A one time password has been sent.</label>
                            <input type="number" class="form-control" id="one_time_password" name="one_time_password" required>
                            <div class="invalid-feedback"></div>
                        </div> -->
                    <div class="d-grid gap-2">
                        <!-- <button type="button" id="otp_password" class="btn btn-danger fw-bold">Validate</button>
                        <button type="button" class="btn btn-light text-danger fw-bold" data-bs-dismiss="modal">Cancel</button> -->
                        <button type="button" class="btn btn-danger fw-bold" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $('document').ready(() => {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    $('#password').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            login();
        }
    });

    function login() {
        let username = $('#username').val();
        let password = $('#password').val();
        $.ajax({
            url: 'functions/login.php',
            type: 'POST',
            data: {
                action: 'login',
                username: username,
                password: password,
            },
            success: function(result) {
                // ==================== Reset Password ====================
                if (result.substring(0, 5) == 'reset') {
                    if (result.substring(6, 8) == '01') {
                        $('.reset-alert').html(
                            '<i class="fa-solid fa-circle-info pe-2 "></i>You must change password before logging on the first time. Please update your password or contact your system admin or technical support.'
                        );
                    } else {
                        $('.reset-alert').html(
                            '<i class="fa-solid fa-circle-info pe-2 "></i>Your Password has Expired. You have to change your password before you can login again.'
                        );
                    }
                    let user_id = result.substring(9);
                    $('#modal_reset_password').modal('show');
                    $('#user_id').val(user_id);
                }
                // ==================== Already login ====================
                else if (result == 'Account Already Logged In.') {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Account Already Logged In.',
                        text: 'To force your account to sign out, click logout.',
                        focusConfirm: false,
                        confirmButtonText: 'Logout',
                        confirmButtonColor: '#dc3545',
                        showCancelButton: true,
                    }).then((result) => { // ==================== Force Logout ====================
                        if (result.isConfirmed) {
                            let username = $('#username').val();
                            $.ajax({
                                url: 'functions/login.php',
                                type: 'POST',
                                data: {
                                    action: 'forceLogOut',
                                    username: username
                                },
                                success: function(result) {
                                    Swal.fire({
                                        position: 'top',
                                        icon: 'success',
                                        title: 'Sign Out',
                                        text: 'You have been signed out of your account.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            });
                        }
                    })
                }
                // ==================== Login ====================
                else if (result == "Login") {
                    location.href = 'Landing_Page.php';
                }
                // ==================== Error ====================
                else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Error!',
                        text: result,
                        showConfirmButton: false,
                        timer: 1500
                    });

                }

            }
        });
    }

    function resetPassword() {
        let user_id = $('#user_id').val();
        let new_password = $('#new_password').val();
        let confirm_password = $('#confirm_password').val();

        $.ajax({
            url: 'functions/login.php',
            type: 'POST',
            data: {
                action: 'resetPassword',
                user_id: user_id,
                new_password: new_password,
                confirm_password: confirm_password
            },
            success: function(result) {
                if (result == 'Password Changed!') {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: result,
                        text: 'Your password has been changed successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#modal_reset_password').modal('hide');
                    $('#password').val('').focus();
                } else if (result == 'Old Password') {
                    // $('.reset-alert').removeClass('alert-secondary').addClass('alert-danger').html(
                    //     '<i class="fa-solid fa-circle-exclamation pe-2 "></i>Sorry! You entered an old password, please try again.'
                    // );
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Error!',
                        text: 'Sorry! You entered an old password, please try again.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#new_password').val('');
                    $('#confirm_password').val('');
                    clearValidation();
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Error!',
                        text: result,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }

            }
        });
    }

    function forgotPass() {

        $('#modal_forgot_password').modal('show');

        // if (username_forgot_password == "") {
        //     $('#username_forgot_password').addClass('is-invalid').next().text('Field is required.');
        // } else {

        // }

        $('#forgot_password').click(() => {

            let username_forgot_password = $('#username_forgot_password').val().trim();
            $.ajax({
                url: 'functions/login.php',
                type: 'POST',
                data: {
                    action: 'forgotPassword',
                    username_forgot_password: username_forgot_password,

                },
                success: function(result) {
                    if (result != false) {

                        $('#modal_forgot_password').modal('hide');
                        $('#modal_otp').modal('show');
                        // $('#one_time_password').val(result);
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error!',
                            text: 'No Record Found.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                }
            });
        });

    }

    realtimeValidatePassword();

    function realtimeValidatePassword() {
        let newPassword = $('#new_password');
        let confirmPassword = $('#confirm_password');
        newPassword.on('input', function() {
            var pass = $(this).val();
            let message = "";
            if (pass.length < 12) {
                message = "Password must be at least 12 characters long.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else if (pass.length > 24) {
                message = "Password must be at most 24 characters long.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else if (!/[a-z]/.test(pass)) {
                message = "Password must contain at least one lowercase letter.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else if (!/[A-Z]/.test(pass)) {
                message = "Password must contain at least one uppercase letter.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else if (!/[0-9]/.test(pass)) {
                message = "Password must contain at least one digit.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else if (!/[-+_!@#$%^&*., ?]/.test(pass)) {
                message = "Password must contain at least one special character.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else {
                $(this).addClass('is-valid').removeClass('is-invalid');
            }
            $(this).siblings('.invalid-feedback').text(message);
        });

        confirmPassword.on('input', function() {
            if ($(this).val() != newPassword.val()) {
                message = "Passwords do not match.";
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else {
                $(this).addClass('is-valid').removeClass('is-invalid');
            }
            $(this).siblings('.invalid-feedback').text(message);
        });
    }

    function clearValidation() {
        $('input').removeClass('is-valid is-invalid');
        $('.invalid-feedback').text('');
    }

    $('#show_password').click(function() {
        if ($('#show_password').is(':checked')) {
            $('#password').attr('type', 'text');
        } else {
            $('#password').attr('type', 'password');
        }
    });

    // $(".eye").click(function() {
    //     togglePassword();
    // });

    // function togglePassword() {
    //     var eyeIcon = $(".eye[data-bs-title='Show Password']");
    //     var eyeSlashIcon = $(".eye[data-bs-title='Hide Password']");
    //     var passwordInput = $("input[type='password'], input[type='text']");

    //     eyeIcon.toggle();
    //     eyeSlashIcon.toggle();

    //     passwordInput.attr("type", function(index, attr) {
    //         return attr === "password" ? "text" : "password";
    //     });
    // }

    // // Update the count down every 1 second
    // var x = setInterval(function() {

    //     // Set the date we're counting down to
    //     var lockout_timestart = new Date(result).getTime();
    //     var lockout_expired = new Date(lockout_timestart + 120 * 60 * 1000);

    //     // Get today's date and time
    //     var time_now = new Date().getTime();

    //     // Find the distance between now and the count down date
    //     var interval = lockout_expired - time_now;

    //     // Time calculations for days, hours, minutes and seconds
    //     var hours = Math.floor((interval % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    //     var minutes = Math.floor((interval % (1000 * 60 * 60)) / (1000 * 60));
    //     var seconds = Math.floor((interval % (1000 * 60)) / 1000);

    //     // Display the result
    //     document.getElementById("countdown").innerHTML = hours + "h " +
    //         minutes + "m " + seconds + "s ";

    //     // If the count down is finished, write some text
    //     if (interval < 0) {
    //         clearInterval(x);
    //         document.getElementById("countdown").innerHTML = "EXPIRED";
    //     }
    // }, 1000);
</script>

</html>