<?php include './../includes/header.php'; ?>
<?php session_start(); ?>
<style>
    html::-webkit-scrollbar {
        display: none;
    }

    .page {
        display: none;
    }

    .profile-nav {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 200px;
        font-weight: bold;
        position: relative;
    }

    .profile-nav a {
        display: block;
        color: #212529;
        padding: 8px 16px;
        text-decoration: none;
        margin-bottom: 5px;
    }

    .profile-nav a.active {
        border-radius: 5px;
        background-image: linear-gradient(to right, #4adede -22.01%, #1f2f98 125.61%);
        color: white;
    }

    .profile-nav a:hover:not(.active) {
        border-left: 5px solid #1f2f98;
        color: #1f2f98;
        transition: all .05s;
    }

    .profile-card {
        border: none;
        overflow: hidden;
        width: 100%;
        max-height: 100vh;
    }

    .profile-header {
        background: linear-gradient(to bottom right, #4adede -22.01%, #1f2f98 125.61%);
        height: 200px;
    }

    .profile-img {
        border-radius: 50%;
        border: 4px solid #ffffff;
        height: 150px;
        width: 150px;
        position: relative;
        z-index: 1;
        margin-top: -100px;
    }

    .profile-img:hover {
        border-color: #cccccc;
        cursor: pointer;
    }

    .form-text {
        font-size: 16px;
    }

    label.form-text,
    #user_name {
        font-weight: bold;
        color: #212529;
    }

    input.form-text {
        font-style: italic;
    }

    .btn-back {
        text-decoration: none;
        color: #212529;
        font-weight: bold;
        padding: 0 5px;
    }

    /* .btn-back h4::after {
        content: 'Back';
        display: none;
        transition: display 1s;
    }

    .btn-back:hover h4::after {
        display: inline-block;
    }

    .btn-back:hover {
        color: #1f2f98;
    }

    .btn-back:hover i {
        transform: rotate(360deg) !important;
        transition: transform .25s ease-in-out;
    } */

    /* Messages CSS */

    .messages-nav-btn {
        color: #cccccc;
        background: none;
        border: none;
    }

    .profile-chat-avatar {
        width: 75px;
        height: 75px;
    }

    .online {
        border: 3px solid greenyellow;
    }

    .overflow-auto::-webkit-scrollbar {
        height: 10px;
    }

    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.2);
        border-radius: 5px;

    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background-color: darkgrey;
        border-radius: 5px;
    }

    .friend,
    .you {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fa-solid fa-user-gear me-2"></i>Settings</h1>
        <a class="btn-back" href="../index.php">
            <h4><i class="fa-solid fa-arrow-left me-1"></i>Back</h4>
        </a>
    </div>
    <hr>
    <div class="row">
        <!-- ==================== Navbar Section ==================== -->
        <div class="col-md-3 d-flex justify-content-center d-none d-md-block">
            <ul class="profile-nav mt-md-5 mt-0">
                <li><a href="#profile-section" class="profile-nav-link active">Profile</a></li>
                <li><a href="#messages-section" class="profile-nav-link">Messages</a></li>
                <!-- <li><a href="#" class="profile-nav-link">Notifications</a></li> -->
                <li><a href="../functions/logout.php" class="profile-nav-link">Logout</a></li>
            </ul>
        </div>
        <!-- ==================== Navbar Section End ==================== -->

        <div class="col-md-9 d-flex justify-content-center align-items-center">
            <!-- ==================== Profile Card Section ==================== -->
            <div id="profile-section" class="card profile-card shadow-lg g-0 page">
                <div class="profile-header"></div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6 d-flex flex-row mb-3 mb-md-0">
                            <img class="profile-img" src="data:image/jpeg;base64,<?= $_SESSION['user_image']; ?>">
                            <h3 class="text-nowrap" id="user_name"></h3>
                        </div>
                        <!-- <div class="col-md-6">
                            <div type="button" id="edit_user" class="btn btn-outline-primary d-grid float-md-end fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#editUserProfile">
                                <span><i class="fa-solid fa-user-pen me-1"></i>Edit</span>
                            </div>
                        </div> -->
                    </div>
                    <div class="row border-bottom mx-1 mx-md-5 mb-3">
                        <label for="full_name" class="col-sm-4 col-form-label form-text">Full Name:</label>
                        <div class="col">
                            <input type="text" class="form-control-plaintext form-text" id="full_name" value="" readonly>
                        </div>
                    </div>
                    <div class="row border-bottom mx-1 mx-md-5 mb-3">
                        <label for="birthday" class="col-sm-4 col-form-label form-text">Birthday:</label>
                        <div class="col">
                            <input type="text" class="form-control-plaintext form-text" id="birthday" value="" readonly>
                        </div>
                    </div>
                    <div class="row border-bottom mx-1 mx-md-5 mb-3">
                        <label for="employee_number" class="col-sm-4 col-form-label form-text">Employee Number:</label>
                        <div class="col">
                            <input type="text" class="form-control-plaintext form-text" id="employee_number" value="" readonly>
                        </div>
                    </div>
                    <div class="row border-bottom mx-1 mx-md-5 mb-3">
                        <label for="department" class="col-sm-4 col-form-label form-text">Department:</label>
                        <div class="col">
                            <input type="text" class="form-control-plaintext form-text" id="department" value="" readonly>
                        </div>
                    </div>
                    <div class="row border-bottom mx-1 mx-md-5 mb-3">
                        <label for="job_position" class="col-sm-4 col-form-label form-text">Job Position:</label>
                        <div class="col">
                            <input type="text" class="form-control-plaintext form-text" id="job_position" value="" readonly>
                        </div>
                    </div>
                    <div class="row border-bottom mx-1 mx-md-5 mb-3">
                        <label for="employment_date" class="col-sm-4 col-form-label form-text">Employment Date:</label>
                        <div class="col">
                            <input type="text" class="form-control-plaintext form-text" id="employment_date" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ==================== Profile Card Section End ==================== -->

            <!-- ==================== Messages Card Section ==================== -->
            <div id="messages-section" class="card profile-card shadow-lg g-0 page">
                <div class="card-body px-5 py-4">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="Search">
                        <div class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <!-- <div class="d-flex flex-row my-5 gap-5 overflow-auto">
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                        <div class="profile-chat-avatar-wrapper">
                            <img src="../vendor/images/user.jpg" alt="asd" class="rounded-circle profile-chat-avatar online">
                            <h6 class="text-center">Smurf</h6>
                        </div>
                    </div> -->

                    <div class="d-flex justify-content-evenly py-2">
                        <button class="messages-nav-btn">
                            <i class="fa-solid fa-comments"></i>
                        </button>
                        <button class="messages-nav-btn">
                            <i class="fa-solid fa-user-group"></i>
                        </button>
                        <button class="messages-nav-btn">
                            <i class="fa-solid fa-user-plus"></i>
                        </button>
                    </div>
                    <div class="card border-0 shadow">
                        <div class="card-header">
                            <h4 class="text-primary">Girl Bestfriend</h4>
                        </div>
                        <div class="card-body">
                            <div class="friend justify-content-start">
                                <img src="../vendor/images/friend.jpg" alt="asd" class="rounded-circle profile-chat-avatar me-2">
                                <div class="alert alert-light rounded-pill">Hiiiii</div>
                            </div>
                            <div class="you justify-content-end">
                                <div class="alert alert-primary rounded-pill">Hello</div>
                                <img class="rounded-circle profile-chat-avatar" src="data:image/jpeg;base64,<?= $_SESSION['user_image']; ?>">
                            </div>
                            <div class="friend justify-content-start">
                                <img src="../vendor/images/friend.jpg" alt="asd" class="rounded-circle profile-chat-avatar me-2">
                                <div class="alert alert-light rounded-pill">Nasan ka?</div>
                            </div>
                            <div class="you justify-content-end">
                                <div class="alert alert-primary rounded-pill">Nasayo</div>
                                <img class="rounded-circle profile-chat-avatar" src="data:image/jpeg;base64,<?= $_SESSION['user_image']; ?>">
                            </div>
                            <div class="friend justify-content-start">
                                <img src="../vendor/images/friend.jpg" alt="asd" class="rounded-circle profile-chat-avatar me-2">
                                <div class="alert alert-light rounded-pill">😘</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control rounded-pill me-2" placeholder="Type your message here...">
                                <button class="btn btn-primary rounded-pill"><i class="fa-solid fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ==================== Messages Card Section End ==================== -->
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editUserProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUserProfileLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="text-center fw-bold mb-4" id="editUserProfileLabel">Edit Profile</h3>

                <div class="form-group mb-3">
                    <label for="username" class="form-text text-muted mb-2">Username:</label>
                    <input type="text" id="username" class="form-control rounded-pill">
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-text text-muted mb-2">Current Password:</label>
                    <input type="password" id="current_password" class="form-control rounded-pill">
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-text text-muted mb-2">New Password:</label>
                    <input type="password" id="new_password" class="form-control rounded-pill">
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="form-text text-muted mb-2">Confirm Password:</label>
                    <input type="password" id="confirm_password" class="form-control rounded-pill">
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary fw-bold rounded-pill">Update</button>
                    <button type="button" class="btn btn-light text-primary fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    $('#profile-section').show();
    $('.profile-nav-link').click(function(e) {
        e.preventDefault();
        $('.profile-nav-link').removeClass('active');
        $(this).addClass('active');
        // Get the page associated with the clicked link
        var page = $(this).attr('href');
        // Hide all pages
        $('.page').fadeOut('fast');
        // Show the associated page
        $(page).delay('fast').fadeIn();
    });

    // var current = $(location).attr('href').split("/").pop();
    // $('.profile-nav a').each(function() {
    //     var $this = $(this);
    //     if ($this.attr('href') == current) {
    //         $this.addClass('active');
    //     }
    // });

    loadUserInformation();

    function loadUserInformation() {

        $.ajax({
            url: 'functions/userDashboard.functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadUserInformation',
                employee_number: '<?= $_SESSION['empno']; ?>'
            },
            success: (data) => {
                $('#user_name').text('@' + data.userName);
                $('#full_name').val(data.fullName);
                $('#birthday').val(data.birthDay);
                $('#employee_number').val(data.employeeNumber);
                $('#department').val(data.department);
                $('#job_position').val(data.jobPosition);
                $('#employment_date').val(data.employmentDate);

                $('#edit_user').click(() => {
                    $('#username').val(data.userName);
                });
            }
        });
    };
</script>
</body>
<html>