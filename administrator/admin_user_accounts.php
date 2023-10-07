<?php include './../includes/header.php';
//* Banner Web Database connection
$BannerWebLive = $conn->db_conn_bannerweb();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWebLive->prepare($queryCheckApp);
$stmtCheckApp->execute();
$chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC);
$chkAppId = $chkAppIdRow['app_id'];

if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    /* =========== Change Scrollbar Style - Justine 02012023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #eb0b95;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-admin">User Module</span>
            </div>
            <div class="row mt-5 mb-4"> <!-- =========== User List Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-7 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light">User List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addUserModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add User</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="user_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_admin">
                                        <tr>
                                            <th style="text-align: center;">Emp No</th>
                                            <th>Fullname</th>
                                            <th>Username</th>
                                            <th style="text-align: center;">Access Level</th>
                                            <th style="text-align: center;">Logged</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Forgot Password</th>
                                            <th style="text-align: center;">Locked Out</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_admin">
                                        <tr>
                                            <th style="text-align: center;">Emp No</th>
                                            <th>Fullname</th>
                                            <th>Username</th>
                                            <th style="text-align: center;">Access Level</th>
                                            <th style="text-align: center;">Logged</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Forgot Password</th>
                                            <th style="text-align: center;">Locked Out</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== User List Section End =========== -->
            <!-- =============== Add User Modal =============== -->
            <div class="modal fade" id="addUpdateUserModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light">ADD USER</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col" id="user_image_division"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <label for="user_department" class="col-form-label fw-bold">Department</label>
                                            <select name="" id="user_department" class="form-select fw-bold" onclick="loadEmployee();">
                                                <option value="">Choose...</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-sm">
                                            <label for="user_employee" class="col-form-label fw-bold emplo">Employee</label>
                                            <select name="" id="user_employee" class="form-select fw-bold" onclick="loadEmployeeDetails();">
                                                <option value="">Choose...</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="co-sm">
                                            <label for="user_username" class="col-form-label fw-bold">Username</label>
                                            <input type="text" id="user_username" class="form-control fw-bold" placeholder="Username...." onpaste="return false" oncut="return false" oncopy="return false">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-sm">
                                            <label for="user_status" class="col-form-label fw-bold">Status</label>
                                            <select class="form-select fw-bold" id="user_status">
                                                <option value="">Choose...</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <label for="user_empno" class="col-form-label fw-bold">Employee Number</label>
                                            <input type="text" id="user_empno" class="form-control fw-bold" placeholder="Employee No." disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-sm">
                                            <label for="user_access" class="col-form-label fw-bold">Access Level</label>
                                            <select name="" id="user_access" class="form-select fw-bold" disabled></select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="pass_section">
                                        <div class="col-sm">
                                            <input class="form-check-input" type="checkbox" name="flexChkPass" id="chkPassword" onclick="chkPasswordFunction();"><label class="form-check-label fs-15 ms-2" for="chkPassword"><strong> Manual Password</strong></label>
                                            <input type="text" id="user_password" class="form-control fw-bold" value="Banner1994#" disabled>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col">
                                            <label for="user_image_file" class="col-form-label fw-bold">User Image</label>
                                            <input type="file" id="user_image_file" name="user_image_file" class="form-control fw-bold">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- =============== Add User Details End =============== -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary col btnEditUser" onclick="editUser();"><i class="fa-solid fa-pen-to-square p-r-8"></i>Edit</button>
                            <button type="button" class="btn btn-success col btnUpdateUser" onclick="updateUser(this);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveUser" onclick="saveUser();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add User Modal End =============== -->



            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-7 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">ADMINISTRATOR</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    let prevIndexDepartment = '';
    let prevIndexEmployee = '';
    loadUserListTable();

    function loadUserListTable() {
        var user_list_table = $('#user_list_table').DataTable({
            'lengthMenu': [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/user_account_functions.php',
                type: 'POST',
                data: {
                    action: 'load_user_list_table'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '8%',
            }, {
                targets: [1, 2, 3],
                className: 'dt-body-middle-left',
                width: '10%',
            }, {
                targets: [4, 5, 6, 7],
                className: 'dt-body-middle-center',
                width: '7%',
                orderable: false
            }, {
                targets: 8,
                className: 'dt-nowrap-center',
                width: '15%',
                orderable: false
            }]
        });
        setInterval(function() {
            user_list_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addUserModal() {
        $('#addUpdateUserModal').modal('show');
        $('.btnEditUser').prop('disabled', true).css('display', 'none');
        $('.btnUpdateUser').prop('disabled', true).css('display', 'none');
        $('.btnSaveUser').prop('disabled', false).css('display', 'block');
        $('#user_image_division').html('<img src="../vendor/images/blank-profile-picture.png" id="add_user_image">');
        $('#user_password').val('Banner1994#');
        loadDepartment();
        loadAccessLevel();
    }

    function loadDepartment() {
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'load_department'
            },
            success: function(result) {
                $('#user_department').html(result);
            }
        });
    }

    function loadAccessLevel() {
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'load_access_level'
            },
            success: function(result) {
                $('#user_access').html(result);
            }
        });
    }

    function loadEmployee() {
        let currIndex = document.getElementById('user_department').selectedIndex;
        let currVal = document.getElementById('user_department').options;

        if (currIndex > 0) {
            if (prevIndexDepartment != currIndex) { //* ======= Toggle same Selection =======
                let dept_code = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/user_account_functions.php',
                    type: 'POST',
                    data: {
                        action: 'load_employee',
                        dept_code: dept_code
                    },
                    success: function(result) {
                        $('#user_employee').html(result);
                    }
                });
                prevIndexDepartment = currIndex;
            } else {
                prevIndexDepartment = '';
                // $('#user_username').val('');
                // $('#user_empno').val('');
                // $('#user_employee').find('option:first').prop('selected', 'selected');
                // $('#user_access').find('option:first').prop('selected', 'selected');
                // $('#user_status').find('option:first').prop('selected', 'selected');
                // $('#chkPassword').prop('checked', false);
                // $('#user_password').val('Banner1994#');
            }
        } else {
            // prevIndexDepartment = '';
            // $('#user_username').val('');
            // $('#user_empno').val('');
            // $('#user_department').find('option:first').prop('selected', 'selected');
            // $('#user_employee').find('option:first').prop('selected', 'selected');
            // $('#user_access').find('option:first').prop('selected', 'selected');
            // $('#user_status').find('option:first').prop('selected', 'selected');
            // $('#chkPassword').prop('checked', false);
            // $('#user_password').val('Banner1994#');

        }
    }

    function loadEmployeeDetails() {
        let currIndex = document.getElementById('user_employee').selectedIndex;
        let currVal = document.getElementById('user_employee').options;

        if (currIndex > 0) {
            if (prevIndexEmployee != currIndex) { //* ======= Toggle same Selection =======
                let empno = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/user_account_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    cache: false,
                    data: {
                        action: 'load_employee_details',
                        empno: empno
                    },
                    success: function(result) {
                        $('#user_empno').val(empno);
                        $('#user_access').val(result.pos_code);
                    }
                });
                prevIndexEmployee = currIndex;
            } else {
                prevIndexEmployee = '';
                // $('#user_username').val('');
                // $('#user_status').find('option:first').prop('selected', 'selected');
                // $('#chkPassword').prop('checked', false);
                // $('#user_password').val('Banner1994#');
            }
        } else {
            // prevIndexEmployee = '';
            // $('#user_username').val('');
            // $('#user_empno').val('');
            // $('#user_access').find('option:first').prop('selected', 'selected');
            // $('#user_status').find('option:first').prop('selected', 'selected');
            // $('#chkPassword').prop('checked', false);
            // $('#user_password').val('Banner1994#');
        }
    }

    function saveUser() {
        if (submitValidation()) {
            var user_department = document.getElementById('user_department').value;
            var user_username = document.getElementById('user_username').value;
            var user_status = document.getElementById('user_status').value;
            var user_empno = document.getElementById('user_empno').value;
            var user_access = document.getElementById('user_access').value;
            var user_password = document.getElementById('user_password').value;
            let authorize_courier_image = $('#add_user_image').attr('value'); //* ======= image container =======
            //* ======= Validate if File Uploaded is Image =======
            let image_property = document.getElementById('user_image_file').files[0]; //* ======= input file =======
            let image_name = image_property.name;
            let image_size = Math.round(image_property.size / 1024) + " MB";
            let image_extension = image_name.split('.').pop().toLowerCase();

            if (jQuery.inArray(image_extension, ['gif', 'jpg', 'jpeg', '']) == -1) {
                Swal.fire({
                    position: 'top',
                    icon: 'info',
                    title: 'Invalid Image File.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#user_image_file').focus();
            } else {
                $.ajax({
                    url: 'functions/user_account_functions.php',
                    type: 'POST',
                    data: {
                        action: 'save_user_account',
                        user_department: user_department,
                        user_username: user_username,
                        user_status: user_status,
                        user_empno: user_empno,
                        user_access: user_access,
                        user_password: user_password,
                        authorize_courier_image: authorize_courier_image
                    },
                    success: function(result) {
                        if (result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'info',
                                title: 'User Already Exist.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else if (result == 'existing username') {
                            Swal.fire({
                                position: 'top',
                                icon: 'info',
                                title: 'Username Already Taken.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('#user_username').focus();
                            $('#user_username').removeClass('is-valid').addClass('is-invalid');
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'User Added.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('#user_list_table').DataTable().ajax.reload(null, false);
                            clearValues();
                        }
                    }
                });
            }
        }
    }

    function btnUserPreview(userid) {
        $('#addUpdateUserModal').modal('show');
        $('.btnEditUser').prop('disabled', false).css('display', 'block');
        $('.btnUpdateUser').prop('disabled', true).css('display', 'none');
        $('.btnSaveUser').prop('disabled', true).css('display', 'none');
        $('.btnUpdateUser').val(userid);
        loadDepartment();
        loadAccessLevel();
        addInputDisabled();
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_user',
                userid: userid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#user_department').val(result.dept_code);
                }, 200);
                setTimeout(function() {
                    loadEmployee();
                }, 400);
                setTimeout(function() {
                    $('#user_employee').val(result.empno);
                }, 800);
                setTimeout(function() {
                    loadEmployeeDetails();
                }, 1000);
                if (result.reset_pass == true) {
                    $('#pass_section').css('display', 'block');
                    $('#user_password').val('Banner1994#');
                } else {
                    $('#pass_section').css('display', 'none');
                }
                $('#user_username').val(result.username);
                $('#user_status').val(result.is_active);
                $('#user_image_division').html(result.user_image)
            }
        });
    }

    function editUser() {
        $('.btnEditUser').prop('disabled', true).css('display', 'none');
        $('.btnUpdateUser').prop('disabled', false).css('display', 'block');
        $('.btnSaveUser').prop('disabled', true).css('display', 'none');
        removeInputDisabled();
    }

    function updateUser(val) {
        if (submitValidation()) {
            var userid = val.value;
            let user_image_file = document.getElementById('user_image_file').value;
            if (user_image_file.length > 0) {
                //* ======= Validate if File Uploaded is Image =======
                let image_property = document.getElementById('user_image_file').files[0]; //* ======= input file =======
                let image_name = image_property.name;
                let image_size = Math.round(image_property.size / 1024) + " MB";
                let image_extension = image_name.split('.').pop().toLowerCase();

                if (jQuery.inArray(image_extension, ['gif', 'jpg', 'jpeg', '']) == -1) {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'Invalid Image File.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#user_image_file').focus();
                } else {
                    updateUserFunction(userid);
                }
            } else {
                updateUserFunction(userid);
            }
        }
    }

    function updateUserFunction(userid) {
        // TODO fix saving of password if you want to change the default password while password for reset is still true.
        var user_department = document.getElementById('user_department').value;
        var user_username = document.getElementById('user_username').value;
        var user_status = document.getElementById('user_status').value;
        var user_empno = document.getElementById('user_empno').value;
        var user_access = document.getElementById('user_access').value;
        let add_user_image = $('#add_user_image').attr('value'); //* ======= image container =======
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'update_user_account',
                userid: userid,
                user_department: user_department,
                user_username: user_username,
                user_status: user_status,
                user_empno: user_empno,
                user_access: user_access,
                add_user_image: add_user_image
            },
            success: function(result) {
                if (result == 'existing') {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'User Already Exist.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else if (result == 'existing username') {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'Username Already Taken.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#user_username').focus();
                    $('#user_username').removeClass('is-valid').addClass('is-invalid');
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'User Successfully Updated.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearAttributes();
                    addInputDisabled();
                    $('#user_list_table').DataTable().ajax.reload(null, false);
                    $('.btnEditUser').prop('disabled', false).css('display', 'block');
                    $('.btnUpdateUser').prop('disabled', true).css('display', 'none');
                    $('.btnSaveUser').prop('disabled', true).css('display', 'none');
                }
            }
        });
    }

    function btnUserReset(userid, fullname) {
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'reset_user_password',
                userid: userid
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: fullname,
                    text: 'Password Reset Successfull.',
                    showConfirmButton: false,
                    timer: 3000
                });
                $('#user_list_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function btnDisable(userid, isactive, fullname) {
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'disable_enable_user',
                userid: userid,
                isactive: isactive
            },
            success: function(result) {
                if (isactive == 1) {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: fullname,
                        text: 'Account Deactivated.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: fullname,
                        text: 'Account Activated.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                $('#user_list_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function btnLockout(userid, lockedout, fullname) {
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'lock_unlock_user',
                userid: userid,
                lockedout: lockedout
            },
            success: function(result) {
                if (lockedout == 1) {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: fullname,
                        text: 'Account Locked.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: fullname,
                        text: 'Account Unlocked.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                $('#user_list_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function btnUserDelete(userid, fullname) {
        $.ajax({
            url: 'functions/user_account_functions.php',
            type: 'POST',
            data: {
                action: 'delete_user_account',
                userid: userid
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: fullname,
                    text: 'Account Deleted.',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#user_list_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function btnUserInfo(userid) {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: '',
            text: 'Under Maintenance.',
            showConfirmButton: false,
            timer: 1500
        });
    }


    function chkPasswordFunction() {
        var chkPassVal = document.getElementById('chkPassword').checked;
        if (chkPassVal == true) {
            $('#user_password').prop('disabled', false);
            $('#user_password').val('');
            realtimePassValidation();
        } else {
            $('#user_password').prop('disabled', true);
            $('#user_password').val('Banner1994#');
            $('#user_password').removeClass('is-valid is-invalid');
        }
    }

    $('#user_image_file').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            $.ajax({
                url: '../functions/common_functions.php',
                type: 'POST',
                data: {
                    action: 'load_image_base64',
                    image: e.target.result
                },
                success: function(result) {
                    $('#user_image_division').html('<img src="' + e.target.result + '" value="' + result + '" id="add_user_image">');
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('#user_username').keypress(function() {
        if ($(this).val() == '') {
            showFieldError('user_username', 'Username must not be blank.');
        } else {
            clearFieldError('user_username');
        }
    });

    function realtimePassValidation() {
        let user_password = $('#user_password');
        user_password.on('input', function() {
            var pass = $(this).val();
            let message = "";
            if (pass.length < 8) {
                message = "Password must be at least 8 characters long.";
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
    }

    function addInputDisabled() {
        $('#user_department').prop('disabled', true);
        $('#user_employee').prop('disabled', true);
        $('#user_username').prop('disabled', true);
        $('#user_status').prop('disabled', true);
        $('#user_image_file').prop('disabled', true);
    }

    function removeInputDisabled() {
        $('#user_department').prop('disabled', false);
        $('#user_employee').prop('disabled', false);
        $('#user_username').prop('disabled', false);
        $('#user_status').prop('disabled', false);
        $('#user_image_file').prop('disabled', false);
    }

    function submitValidation() {
        let isValidated = true;
        var user_department = document.getElementById('user_department').value;
        var user_employee = document.getElementById('user_employee').value;
        var user_username = document.getElementById('user_username').value;
        var user_status = document.getElementById('user_status').value;
        var user_password = document.getElementById('user_password').value;
        var chkPassVal = document.getElementById('chkPassword').checked;

        if (user_department.length == 0) {
            showFieldError('user_department', 'Department must not be blank.');
            if (isValidated) {
                $('#user_department').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('user_department');
        }

        if (user_employee.length == 0) {
            showFieldError('user_employee', 'Employee must not be blank.');
            if (isValidated) {
                $('#user_employee').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('user_employee');
        }

        if (user_username.length == 0) {
            showFieldError('user_username', 'Username must not be blank.');
            if (isValidated) {
                $('#user_username').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('user_username');
        }

        if (user_status.length == 0) {
            showFieldError('user_status', 'Status must not be blank.');
            if (isValidated) {
                $('#user_status').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('user_status');
        }

        if (chkPassVal == true) {
            if (user_password.length == 0) {
                showFieldError('user_password', 'Password must not be blank.');
                if (isValidated) {
                    $('#user_password').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('user_password');
            }
        }
        return isValidated;
    }

    $('#user_department').change(function() {
        if ($(this).val() == '') {
            showFieldError('user_department', 'Department must not be blank.');
        } else {
            clearFieldError('user_department');
        }
    });
    $('#user_employee').change(function() {
        if ($(this).val() == '') {
            showFieldError('user_employee', 'Employee must not be blank.');
        } else {
            clearFieldError('user_employee');
        }
    });
    $('#user_username').change(function() {
        if ($(this).val() == '') {
            showFieldError('user_username', 'Username must not be blank.');
        } else {
            clearFieldError('user_username');
        }
    });
    $('#user_status').change(function() {
        if ($(this).val() == '') {
            showFieldError('user_status', 'Status must not be blank.');
        } else {
            clearFieldError('user_status');
        }
    });

    function showFieldError(element, message) {
        $('#' + element).addClass('is-invalid').removeClass('is-valid');
        $('#' + element).next().html(message);
        $('#' + element).next().show();
    }

    function clearFieldError(element) {
        $('#' + element).removeClass('is-invalid').addClass('is-valid');
        $('#' + element).attr('required');
        $('#' + element).next().html('');
    }


    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('#chkPassword').prop('checked', false);
        $('#user_password').prop('disabled', true);
        $('#user_password').val('Banner1994#');
        $('#pass_section').css('display', 'block');
        $('#user_password').removeClass('is-valid is-invalid');
        $('.btnEditUser').prop('disabled', true).css('display', 'none');
        $('.btnUpdateUser').prop('disabled', true).css('display', 'none');
        $('.btnSaveUser').prop('disabled', false).css('display', 'block');
        $('#user_image_division').html('<img src="../vendor/images/blank-profile-picture.png" id="add_user_image">');
        clearAttributes();
        removeInputDisabled();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>

</html>