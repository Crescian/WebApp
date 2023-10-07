<?php include './../includes/header.php';
session_start();
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
    /* =========== Change Scrollbar Style - Justine 01122023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #6b6bf0;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <div class="row">
                <span class="page-title-perso">LTO Embossing</span>
            </div>
            <!-- content section -->
            <div id="maintenance_container">
                <div class="row mt-5">
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-warning shadow active" onclick="loadUserAccountsModal();">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate">
                                        <span class="fs-18 text-warning fw-bold">USER ACCOUNTS</span>
                                        <div class="fs-2 fw-bold" id="pending_count"></div>
                                    </div>
                                    <div class="fs-1 text-warning"><i class="fa-solid fa-users fa-bounce"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-primary shadow" onclick="uploadSerialModal();">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate">
                                        <span class="fs-18 text-primary fw-bold">SERIAL UPLOAD</span>
                                        <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                    </div>
                                    <div class="fs-1 text-primary"><i class="fa-solid fa-file-arrow-up fa-beat"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">

                            <div class="row">
                                <div class="col-sm-10">
                                    <span class="fw-bold fs-27 text-light" id="serial_table_title"></span>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="filterModal();"><i class="fa-solid fa-filter p-r-8"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="serial_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Process</th>
                                            <th style="text-align:center;">Serial No.</th>
                                            <th>Process By</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Process</th>
                                            <th style="text-align:center;">Serial No.</th>
                                            <th>Process By</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Serial Filter Modal =============== -->
            <div class="modal fade" id="filterSerialModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">FILTER SERIAL TABLE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control fw-bold" id="filter_serial_start">
                                <div class="invalid-feedback"></div>
                                <label for="filter_serial_start" class="fw-bold">Serial Start:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control fw-bold" id="filter_serial_end">
                                <div class="invalid-feedback"></div>
                                <label for="filter_serial_end" class="fw-bold">Serial End:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" name="filter_serial_status" id="filter_serial_status">
                                    <option value="">Choose...</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="filter_serial_status" class="fw-bold">Serial Status:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill" onclick="applyFilter();">Apply</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== User Account Modal =============== -->
            <div class="modal fade" id="userAccountModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">USER ACCOUNTS</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="user_name">
                                <div class="invalid-feedback"></div>
                                <label for="user_name" class="fw-bold">Username:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="user_pass">
                                <div class="invalid-feedback"></div>
                                <label for="user_pass" class="fw-bold">Password:</label>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="user_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Username</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Username</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill" onclick="saveUser();">Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Reset User Password Modal =============== -->
            <div class="modal fade" id="resetUserPassModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">RESET PASSWORD</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold text-center" id="new_user_name" disabled>
                                <label for="new_user_name" class="fw-bold">Username:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="new_user_pass">
                                <div class="invalid-feedback"></div>
                                <label for="new_user_pass" class="fw-bold">New Password:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdatePassword" onclick="updatePassword(this.value);">Update Password</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Upload Serial Modal =============== -->
            <div class="modal fade" id="uploadSerialModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">UPLOAD SERIAL</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="file" class="form-control fw-bold" id="serial_file">
                                <div class="invalid-feedback"></div>
                                <label for="serial_file" class="fw-bold">Upload File Path Location:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill" onclick="uploadSerial();">Upload</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Manual Update Modal =============== -->
            <div class="modal fade" id="manualUpdateModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">MANUAL UPDATE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" id="reason_for_manual_update" style="resize:none;height: 150px"></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="reason_for_manual_update" class="col-form-label fw-bold">Reason for Manual Update:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdateStatus" onclick="updateSerialStatus(this.value);">Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Manual Update Info Modal =============== -->
            <div class="modal fade" id="manualInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">MANUAL INFO</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="info_manual_by" disabled>
                                <label for="info_manual_by" class="fw-bold">Manual By:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="info_date_process" disabled>
                                <label for="info_date_process" class="fw-bold">Date Process:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="info_serialno" disabled>
                                <label for="info_serialno" class="fw-bold">Serial Number:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" id="info_reason_for_manual_update" style="resize:none;height: 150px" disabled></textarea>
                                <label for="info_reason_for_manual_update" class="col-form-label fw-bold">Reason for Manual Update:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Close</button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-4 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">PERSONALIZATION</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>
<?php
include './../helper/perso_announcement.php';
include './../includes/footer.php';
include './../helper/input_validation.php';
?>
<script>
    var access_lvl = '<?php echo $_SESSION['access_lvl']; ?>';
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';

    if (access_lvl == 'JRM' || access_lvl == 'EAM') {
        $('#maintenance_container').css('display', 'block');
    } else {
        $('#maintenance_container').css('display', 'none');
    }

    loadSerialTable('No', '', '', '');
    loadUserTable();

    function loadSerialTable(inFilter, startSerial, endSerial, inStatus) {
        if (inFilter == 'No') {
            $('#serial_table_title').html('Serial History');
        } else {
            $('#serial_table_title').html('Serial History (Filtered: ' + startSerial + ' to ' + endSerial + ')');
        }
        var serial_table = $('#serial_table').DataTable({
            'destroy': true,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_serial_table_data',
                    inFilter: inFilter,
                    startSerial: startSerial,
                    endSerial: endSerial,
                    inStatus: inStatus
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '20%'
            }, {
                targets: 1,
                className: 'dt-body-middle-center',
                width: '15%'
            }, {
                targets: 2,
                className: 'dt-body-middle-left'
            }, {
                targets: 3,
                className: 'dt-body-middle-center',
                width: '12%'
            }, {
                targets: 4,
                className: 'dt-nowrap-center',
                width: '10%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btnAction = '';
                    if (data[1] == 0) {
                        btnAction = `<button class="btn btn-primary col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Manual Update" onclick="manualUpdate('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-shake"></i></button>`;
                    } else {
                        if (data[2] == 1) {
                            if (access_lvl == 'JRM' || access_lvl == 'EAM') {
                                btnAction = `<button class="btn btn-warning col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Manual Info" onclick="manualInfo('${data[0]}');"><i class="fa-solid fa-circle-info fa-beat"></i></button>`;
                            } else {
                                btnAction = `<span class="badge bg-warning col-sm-12">Manual</span>`;
                            }
                        } else {
                            btnAction = `<span class="badge bg-info col-sm-12"><i class="fa-solid fa-thumbs-up fa-beat"></i></span>`;
                        }
                    }
                    return btnAction;
                }
            }]
        });
        serial_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 800);
        });
        // setInterval(function() {
        //     serial_table.ajax.reload(null, false);
        // }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadUserTable() {
        var user_table = $('#user_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autowidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_user_table_data'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '80%'
            }, {
                targets: 1,
                orderable: false,
                className: 'dt-nowrap-center',
                width: '20%',
                render: function(data, type, row, meta) {
                    return `<button class="btn btn-danger col-sm-6" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Remove User" onclick="removeUser('${data[0]}');"><i class="fa-solid fa-trash-can fa-shake"></i></button>
                    <button class="btn btn-warning col-sm-6" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Reset Password" onclick="resetPassword('${data[0]}','${data[1]}');"><i class="fa-solid fa-repeat fa-spin"></i></button>`;
                }
            }]
        });
        user_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 1000);
        });
        setInterval(function() {
            user_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function uploadSerialModal() {
        // $('#uploadSerialModal').modal('show');
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: 'Under Maintenance.',
            text: '',
            showConfirmButton: false,
            timer: 1000
        });
    }

    function uploadSerial() {
        if (inputValidation('serial_file')) {
            // //* ======= Validate if File Uploaded is Txt File =======
            let serial_file = document.getElementById('serial_file').files[0];
            let serial_extension = serial_file.name.split('.').pop().toLowerCase();

            // if (jQuery.inArray(serial_extension, ['txt']) == -1) {
            //     Swal.fire({
            //         position: 'top',
            //         icon: 'info',
            //         title: 'Invalid Text File.',
            //         text: '',
            //         showConfirmButton: false,
            //         timer: 1000
            //     });
            //     $('#serial_file').focus();
            // } else {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
                type: 'POST',
                data: {
                    action: 'read_text_file',
                    filename: serial_file
                },
                success: result => {
                    alert(result);
                    // Swal.fire({
                    //     position: 'top',
                    //     icon: 'success',
                    //     title: 'Serial Uploaded Successfully.',
                    //     text: '',
                    //     showConfirmButton: false,
                    //     timer: 1000
                    // });
                    // $('#uploadSerialModal').modal('hide');
                    // clearValues();
                }
            });
            // }
        }
    }


    function filterModal() {
        $('#filterSerialModal').modal('show');
        $('#filter_serial_start').val('');
        $('#filter_serial_end').val('');
    }

    function applyFilter() {
        if (inputValidation('filter_serial_start', 'filter_serial_end', 'filter_serial_status')) {
            loadSerialTable('Yes', $('#filter_serial_start').val(), $('#filter_serial_end').val(), $('#filter_serial_status').val());
            $('#filterSerialModal').modal('hide');
            clearAttributes();
        }
    }

    function loadUserAccountsModal() {
        $('#userAccountModal').modal('show');
    }

    function saveUser() {
        if (inputValidation('user_name', 'user_pass')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_user',
                    user_name: $('#user_name').val(),
                    user_pass: $('#user_pass').val()
                },
                success: result => {
                    if (result == 'exist') {
                        Swal.fire({
                            position: 'top',
                            icon: 'warning',
                            title: 'User Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#user_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'User Save Successfully.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#user_table').DataTable().ajax.reload(null, false);
                        clearValues();
                    }
                }
            });
        }
    }

    function removeUser(userid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'remove_user',
                        userid: userid
                    },
                    success: function(result) {
                        $('#user_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Remove!',
                            'User Removed.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    function resetPassword(userid, username) {
        $('#resetUserPassModal').modal('show');
        $('#new_user_name').val(username);
        $('.btnUpdatePassword').val(userid);
    }

    function updatePassword(userid) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
            type: 'POST',
            data: {
                action: 'update_user_password',
                userid: userid,
                new_pass: $('#new_user_pass').val()
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'User Password Updated.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                clearValues();
                $('#resetUserPassModal').modal('hide');
            }
        });
    }

    function manualUpdate(serialid) {
        $('#manualUpdateModal').modal('show');
        $('.btnUpdateStatus').val(serialid);
    }

    function updateSerialStatus(serialid) {
        if (inputValidation('reason_for_manual_update')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_serial_status',
                    serialid: serialid,
                    logged_user: logged_user,
                    manual_remarks: $('#reason_for_manual_update').val()
                },
                success: result => {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Serial Successfully Updated.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    $('#manualUpdateModal').modal('hide');
                    $('#serial_table').DataTable().ajax.reload(null, false);
                }
            });
        }
    }

    function manualInfo(serialid) {
        $('#manualInfoModal').modal('show');
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_lto_embossing_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_manual_info',
                serialid: serialid
            },
            success: result => {
                $('#info_manual_by').val(result.manual_by);
                $('#info_date_process').val(result.manual_datetime);
                $('#info_reason_for_manual_update').val(result.manual_remarks);
                $('#info_serialno').val(result.serialno);
            }
        });

    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>