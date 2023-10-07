<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
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
    <link rel="stylesheet" type="text/css" href="vendor/css/custom.landing-page.css" />
    <link rel="stylesheet" type="text/css" href="vendor/css/custom.css" />
    <link rel="stylesheet" type="text/css" href="vendor/css/util.css" />

    <script type="text/javascript" src="vendor/JQuery/jquery.min.js"></script>
    <script type="text/javascript" src="vendor/Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="vendor/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="vendor/SweetAlert/sweetalert2.min.js"></script>
    <script type="text/javascript" src="vendor/js/jquery.mask.js"></script>
    <script type="text/javascript" src="vendor/js/moment.js"></script>

    <title>Banner Web App</title>
    <style>
        /* #animation {
            opacity: 0;
            transition: opacity 2s;
        }

        .end-animation {
            opacity: 1;
        } */
    </style>
</head>
<script>
    window.onbeforeunload = function(e) {
        $('.announcement-section').addClass('slide-out-left');
        $('.applications-section').addClass('slide-out-right');
    }
</script>

<body class="landing-page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 d-flex flex-column p-5 announcement-section" id="generateNews">
                <!-- <div class="row">
                    <a href="#" class="to-top">
                        <i class="fas fa-chevron-up"></i>
                    </a>
                </div> -->
            </div>
            <div class="col-md-6 p-0">
                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-2 vh-100 g-0 applications-section"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="d-flex flex-row-reverse">
                        <button type="button" class="btn-close mt-2" style="margin-right: 15px;" data-bs-dismiss="modal" aria-label="Close" onclick="clearValues();"></button>
                    </div>
                </div>
                <div class="modal-body" id="announcement_layout">
                    <!-- <div class="announce-body mb-3">
                        <div class="row mt-3 mb-3 announcement-container-modal">
                            <span class="fs-35 fw-bold">Header</span>
                            <span class="fs-20 mt-3 fw-bold">The Announcer<span class="text-secondary announce-body-modal fs-15">' . $post_date . '</span></span>
                        </div>
                        <div class="row bg-dark announcement-container-modal">
                            <div class="d-flex justify-content-center image-announcement-container">
                                <img src="vendor/images/announcement.jpg" alt="" id="post_image">
                            </div>
                        </div>
                        <div class="row mt-3 announce-body-modal">
                            <span class="mt-3 mb-5 fs-25 text-secondary">Lorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem Ipsum</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <span class="fs-10 fw-bold text-secondary">� 2022 Banner Plasticard, Inc. All rights reserved.</span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // document.addEventListener("contextmenu", (e) => {
    //     e.preventDefault();
    // }, false);
    // document.addEventListener("keydown", (e) => {
    //     if (e.ctrlKey || e.keyCode == 123) {
    //         e.stopPropagation();
    //         e.preventDefault();
    //     }
    // });
    
    var department = '<?php echo $_SESSION['dept_code'] ?>';

    loadAccessApp();
    loadNewsFeed();
    seeMoreFunction();

    // window.addEventListener('scroll', function() {
    //     if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
    //         // user has scrolled to bottom of the page
    //         alert('bottom');
    //         document.getElementById("animation").classList.add("end-animation");
    //     }
    // });

    function maintenance() {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: '<b>Alert!</b>',
            html: '<h6>This Feature is Undergoing Maintenance.</h6>',
            showConfirmButton: false,
            timer: 3000
        });
    }

    function loadAccessApp() {
        $.ajax({
            url: 'functions/LandingPage.functions.php',
            type: 'POST',
            data: {
                action: 'loadAccessApp',
                department: department
            },
            success: function(result) {
                $('.applications-section').html(result);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }
    // let countSee;

    function loadNewsFeed() {
        $.ajax({
            url: 'functions/LandingPage.functions.php',
            type: 'POST',
            data: {
                action: 'loadNewsFeedFunctions'
            },
            success: function(result) {
                $('#generateNews').html(result);
                const toTop = document.querySelector(".to-top");
                window.addEventListener("scroll", () => {
                    if (window.pageYOffset > 100) {
                        toTop.classList.add("active");
                        console(window.pageYOffset);
                    } else {
                        toTop.classList.remove("active");
                        console(window.pageYOffset);
                    }
                });
            }
        });
    }

    function btnThumb(id) {
        $('#postModal').modal('show');
        $('#generateNews').css('display', 'none');
        $.ajax({
            url: 'functions/LandingPage.functions.php',
            type: 'POST',
            data: {
                action: 'btnThumbFunc',
                id: id
            },
            success: function(result) {
                $('#announcement_layout').html(result);
            }
        });
    }

    // let seeChoice;

    function seeMoreFunction() {
        let seeChoice = 'more';
        let count = 10;
        count += 10;
        $.ajax({
            url: 'functions/LandingPage.functions.php',
            type: 'POST',
            data: {
                action: 'seeMoreAndLessFunction',
                count: count,
                seeChoice: seeChoice
            },
            success: function(result) {
                $('#generateNews').html(result);
            }
        });
    }

    function seeLessFunction() {
        let seeChoice = 'less';
        let count = 10;
        $.ajax({
            url: 'functions/LandingPage.functions.php',
            type: 'POST',
            data: {
                action: 'seeMoreAndLessFunction',
                seeChoice: seeChoice
            },
            success: function(result) {
                $('#generateNews').html(result);
                count = 10;
            }
        });
    }

    //* ======================= Idle Timer =======================
    // function idleLogout() {
    //     var t;
    //     window.onload = resetTimer;
    //     window.onmousemove = resetTimer;
    //     window.onmousedown = resetTimer; //* catches touchscreen presses as well      
    //     window.ontouchstart = resetTimer; //* catches touchscreen swipes as well      
    //     window.ontouchmove = resetTimer; //* required by some devices 
    //     window.onclick = resetTimer; //* catches touchpad clicks as well
    //     window.onkeydown = resetTimer;
    //     window.addEventListener('scroll', resetTimer, true);

    //     function doInactive() {
    //         //* your function for too long inactivity goes here
    //         location.href = 'functions/logout.php';
    //     }

    //     function resetTimer() {
    //         clearTimeout(t);
    //         t = setTimeout(doInactive, 300000); //* time is in milliseconds - 5mins
    //     }
    // }
    // idleLogout();
    //* ======================= Idle Timer End =======================

    // ======================= Check User Logged In =======================
    setInterval(function() {
        isLoggedIn();
    }, 5000);

    function isLoggedIn() {
        $.ajax({
            url: 'functions/login.php',
            type: 'POST',
            data: {
                action: 'isLoggedIn'
            },
            success: function(result) {
                if (result == 0) { // Log Out
                    location.href = 'functions/logout.php';
                }
            }
        });
    }
</script>

</html>