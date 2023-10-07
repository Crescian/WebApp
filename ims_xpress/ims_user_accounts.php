<?php include './../includes/header.php';
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$data_base64 = base64_encode($sqlstring);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
$json_response = curl_exec($curl);
//* ====== Close Connection ======
curl_close($curl);
// * ======== Prepare Array ========
$data_result = json_decode($json_response, true);
foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #b811da;
        border-radius: 100vw;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <div class="row">
                <span class="page-title-ims">User Accounts Module</span>
            </div>
            <!-- content section -->
            <div class="row mt-5 mb-4"> <!-- =========== User List Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-10 py-3">
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
                            <div class="row" id="user_data_load">
                                <div class="col-sm text-center fw-bold">Currently No Data to Show</div>
                            </div>
                            <div class="user_profile_section" id="user_data_list">
                                <div class="row row-cols-1 row-cols-sm-4" id="profile_container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== User List Section End =========== -->
            <!-- =============== User Profile Modal =============== -->
            <div class="modal fade" id="addUpdateUserModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-10">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="add_user_title"></h4>
                        </div>
                        <div class="modal-body" style="background-color: #eee;">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <div class="ims_user_image_container">
                                                <div class="ims_user_container"></div>
                                                <input type="file" class="ims_upload_user_pic">
                                            </div>
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="employee_department"></select>
                                                <div class="invalid-feedback"></div>
                                                <label for="employee_department" class="col-form-label fw-bold">Department:</label>
                                            </div>
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="employee_fullname">
                                                    <option value="">Choose...</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label for="employee_fullname" class="col-form-label fw-bold">Employee:</label>
                                            </div>
                                            <div class="form-floating mb-2">
                                                <input type="email" class="form-control fw-bold" id="employee_job_title" disabled>
                                                <label for="employee_job_title" class="col-form-label fw-bold">Job Title:</label>
                                            </div>
                                            <div class="form-floating mb-2">
                                                <input type="email" class="form-control fw-bold" id="employee_email">
                                                <div class="invalid-feedback"></div>
                                                <label for="employee_email" class="col-form-label fw-bold">Email: (Optional)</label>
                                            </div>
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="employee_username">
                                                <div class="invalid-feedback"></div>
                                                <label for="employee_username" class="col-form-label fw-bold">Username:</label>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="chkManualPass" onclick="chkManualPassFunction(this.checked);">
                                                        <label class="form-check-label fw-bold" for="chkManualPass">Manual Password</label>
                                                    </div>
                                                    <div class="form-floating mb-2">
                                                        <input type="text" class="form-control fw-bold" id="employee_password" value="Banner1994#" disabled>
                                                        <div class="invalid-feedback"></div>
                                                        <label for="employee_password" class="col-form-label fw-bold">Password:</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_active_user">
                                                        <label class="form-check-label fw-bold" for="switch_active_user">Active User</label>
                                                    </div>
                                                    <div class="form-floating mb-2">
                                                        <input type="file" class="form-control fw-bold" id="employee_signature">
                                                        <div class="invalid-feedback"></div>
                                                        <label for="employee_signature" class="col-form-label fw-bold">E-Signature: (Optional)</label>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="text-uppercase fw-bold text-dark">Permissions</h6>
                                            <hr>
                                            <!-- ========== Nav Tabs ========== -->
                                            <ul class="nav nav-tabs nav-fill flex-column flex-sm-row" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button type="button" class="nav-link nav-link-ims flex-sm-fill text-uppercase active" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" role="tab" aria-controls="system" aria-selected="false">System</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button type="button" class="nav-link nav-link-ims flex-sm-fill text-uppercase" id="iso_modules-tab" data-bs-toggle="tab" data-bs-target="#iso_modules" role="tab" aria-controls="iso_modules" aria-selected="false">ISO Modules</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button type="button" class="nav-link nav-link-ims flex-sm-fill text-uppercase" id="document_viewing-tab" data-bs-toggle="tab" data-bs-target="#document_viewing" role="tab" aria-controls="document_viewing" aria-selected="false">Document Viewing</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button type="button" class="nav-link nav-link-ims flex-sm-fill text-uppercase" id="document_control-tab" data-bs-toggle="tab" data-bs-target="#document_control" role="tab" aria-controls="document_control" aria-selected="false">Document Control</button>
                                                </li>
                                            </ul>
                                            <!-- ======================= Nav tabs Content ======================= -->
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade active show" id="system" role="tabpanel" aria-labelledby="system-tab">
                                                    <div class="row mt-3">
                                                        <div id="system_menu_tree"></div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="iso_modules" role="tabpanel" aria-labelledby="iso_modules-tab">
                                                    <div class="row mt-3">
                                                        <div id="iso_menu_tree"></div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="document_viewing" role="tabpanel" aria-labelledby="document_viewing-tab">
                                                    <div class="row mt-3">
                                                        <div id="document_viewing_menu_tree"></div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="document_control" role="tabpanel" aria-labelledby="document_control-tab">
                                                    <div class="row mt-3">
                                                        <div id="document_control_menu_tree"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col btnUpdateUser" onclick="updateUser(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveUser" onclick="saveUser();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== User Profile Modal End =============== -->




            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-10 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IMS XPRESS</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/input_validation.php';
include './../helper/select_values.php'; ?>
<script>
    loadUserList();

    function loadUserList() {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_user_list'
            },
            beforeSend: function() {
                $('#user_data_load').css('display', 'block');
                $('#user_data_list').css('display', 'none');
            },
            success: function(result) {
                $('#user_data_load').css('display', 'none');
                $('#user_data_list').css('display', 'block');
                let html = '';
                $.each(result, (key, value) => {
                    html += `<div class="col-sm col-md mb-4 d-flex align-items-center justify-content-center">
                                <div class="user_profile_card" style="">
                                    <div class="user_profile_image">
                                        <img class="user_profile_image-image" ` + value.user_image + ` alt="">
                                    </div>
                                    <div class="user_profile_info">
                                        <span class="user_profile_name">` + value.fullname + `</span>
                                        <span class="user_profile_job_title">` + value.pos_name + `</span>
                                        <span class="user_profile_department">` + value.department + `</span>
                                    </div>
                                    <div class="user_profile_actions">`;
                    if (value.is_active == false) {
                        html += `<span class="badge bg-danger col-sm-12">Account Disabled</span>`;
                    }
                    if (value.act_lockedout == true) {
                        html += `<span class="badge bg-danger col-sm-12">Account Locked</span>`;
                    }
                    html += `</div>
                                    <div class="user_profile_buttons">
                                        <button type="button" class="btn btn-primary" onclick="showProfile('` + value.user_id + `');">Profile</button>
                                        <button type="button" class="btn btn-primary" onclick="sendMessage('` + value.empno + `');">Message</button>
                                    </div>
                                </div>
                            </div>`;
                });
                $('#profile_container').append(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function addUserModal() {
        $('#addUpdateUserModal').modal('show');
        $('#add_user_title').html('ADD USER INFORMATION');
        $('.ims_user_container').html('<img src="../vendor/images/blank-profile-picture.png" alt="" class="ims_user_image" id="ims_user_image">');
        $('.btnUpdateUser').prop('disabled', true).css('display', 'none');
        $('.btnSaveUser').prop('disabled', false).css('display', 'block');
        loadDepartment();
        loadSytemMenuTree('load_system_menu_tree', 'system_menu_tree');
        loadSytemMenuTree('load_iso_menu_tree', 'iso_menu_tree');
        loadSytemMenuTree('load_document_viewing_menu_tree', 'document_viewing_menu_tree');
        loadSytemMenuTree('load_document_control_menu_tree', 'document_control_menu_tree');
    }

    function saveUser() {
        // if (inputValidation('employee_department', 'employee_fullname', 'employee_username')) {
        saveUserAccess('system_menu_tree', 'save_sys_mod_user_access')
        saveUserAccess('iso_menu_tree', 'save_iso_mod_user_access')
        saveUserAccess('document_viewing_menu_tree', 'save_doc_view_mod_user_access')
        saveUserAccess('document_control_menu_tree', 'save_doc_control_mod_user_access')
        // }
    }


    function showProfile(userid) {
        $('#addUpdateUserModal').modal('show');
        $('#add_user_title').html('UPDATE USER INFORMATION');
        $('.btnUpdateUser').prop('disabled', false).css('display', 'block').val(userid);
        $('.btnSaveUser').prop('disabled', true).css('display', 'none');
        loadDepartment();
        loadSytemMenuTree('load_system_menu_tree', 'system_menu_tree');
        loadSytemMenuTree('load_iso_menu_tree', 'iso_menu_tree');
        loadSytemMenuTree('load_document_viewing_menu_tree', 'document_viewing_menu_tree');
        loadSytemMenuTree('load_document_control_menu_tree', 'document_control_menu_tree');
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_employee_information',
                userid: userid
            },
            success: result => {
                setTimeout(function() {
                    $('#employee_department').val(result.dept_code).change();
                }, 200);
                setTimeout(function() {
                    $('#employee_fullname').val(result.empno);
                    loadSystemMenuTreeAccess('load_system_menu_access', 'system_menu_tree');
                    loadSystemMenuTreeAccess('load_iso_menu_access', 'iso_menu_tree');
                    loadSystemMenuTreeAccess('load_document_viewing_access', 'document_viewing_menu_tree');
                    loadSystemMenuTreeAccess('load_document_control_access', 'document_control_menu_tree');
                }, 400);
                $('#employee_job_title').val(result.pos_name);
                $('#employee_email').val(result.user_email);
                $('#employee_username').val(result.username);
                $('.ims_user_container').html('<img src="data:image/jpeg;base64,' + result.user_image + '" value="' + result.user_image + '" alt="" class="ims_user_image" id="ims_user_image">');
                if (result.is_active == true) {
                    $('#switch_active_user').prop('checked', true);
                } else {
                    $('#switch_active_user').prop('checked', false);
                }
            }
        });
    }

    function updateUser(userid) {
        // if (inputValidation('employee_department', 'employee_fullname', 'employee_username')) {


        saveUserAccess('system_menu_tree', 'save_sys_mod_user_access', 'delete_sys_mod_user_access')
        saveUserAccess('iso_menu_tree', 'save_iso_mod_user_access', 'delete_iso_mod_user_access')
        saveUserAccess('document_viewing_menu_tree', 'save_doc_view_mod_user_access', 'delete_doc_view_mod_user_access')
        saveUserAccess('document_control_menu_tree', 'save_doc_control_mod_user_access', 'delete_doc_control_mod_user_access')
        // }
    }

    function loadDepartment() {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_department'
            },
            success: result => {
                loadSelectValues('employee_department', result);
            }
        });
    }

    $('#employee_department').change(function() {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_employee',
                dept_code: $(this).val()
            },
            success: result => {
                loadSelectValues('employee_fullname', result);
            }
        });
    });

    $('#employee_fullname').change(function() {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_employee_job_title',
                empno: $(this).val()
            },
            success: result => {
                $('#employee_job_title').val(result.job_title);
            }
        });
    });

    function loadSytemMenuTree(inAction, inSection) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: inAction
            },
            success: function(result) {
                $('#' + inSection).jstree('destroy');
                if (result != 'empty') {
                    $('#' + inSection).jstree({
                        'checkbox': {
                            'three_state': false,
                            'keep_selected_style': false
                        },
                        'plugins': ["wholerow", "checkbox"],
                        'core': {
                            'data': result
                        }
                    }).on('loaded.jstree', function() {
                        $('#' + inSection).jstree('open_all');
                    });
                }
            }
        });
    }

    function loadSystemMenuTreeAccess(inAction, inSection) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: inAction,
                empno: $('#employee_fullname').val()
            },
            success: function(result) {
                $.each(result, (key, value) => {
                    $('#' + inSection).jstree('select_node', value)
                });
            }
        });
    }

    function saveUserAccess(inObject, inAction, inActionDel) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
            type: 'POST',
            data: {
                action: inActionDel,
                empno: $('#employee_fullname').val()
            },
            success: result => {
                let strMenuId = $('#' + inObject).jstree('get_selected', true);
                for (let i = 0; i < strMenuId.length; i++) {
                    selectedNode = strMenuId[i];
                    var access_id = selectedNode.id;
                    $.ajax({
                        url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
                        type: 'POST',
                        data: {
                            action: inAction,
                            access_id: access_id,
                            empno: $('#employee_fullname').val()
                        }
                    });
                }
            }
        });
    }

    $('.ims_upload_user_pic').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            $.ajax({
                url: '../controller/ims_xpress_controller/ims_user_accounts_contr.class.php',
                type: 'POST',
                data: {
                    action: 'load_image_base64',
                    image: e.target.result
                },
                success: function(result) {
                    $('.ims_user_container').html('<img src="' + e.target.result + '" value="' + result + '" class="ims_user_image" id="ims_user_image">');
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });

    function chkManualPassFunction(chkPassVal) {
        if (chkPassVal == true) {
            $('#employee_password').prop('disabled', false).val('');
            realtimePassValidation();
        } else {
            $('#employee_password').prop('disabled', true).val('Banner1994#').removeClass('is-valid is-invalid');
        }
    }

    function realtimePassValidation() {
        let employee_password = $('#employee_password');
        employee_password.on('input', function() {
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
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('#chkManualPass').prop('checked', false);
        $('#switch_active_user').prop('checked', false);
        $('#employee_password').prop('disabled', true).val('Banner1994#').removeClass('is-valid is-invalid');
        $('#user_image_division').html('<img src="../vendor/images/blank-profile-picture.png" id="add_user_image">');

        $('#system-tab').addClass('active');
        $('#system').addClass('active show');
        $('#iso_modules-tab').removeClass('active');
        $('#iso_modules').removeClass('active show');
        $('#document_viewing-tab').removeClass('active');
        $('#document_viewing').removeClass('active show');
        $('#document_control-tab').removeClass('active');
        $('#document_control').removeClass('active show');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>