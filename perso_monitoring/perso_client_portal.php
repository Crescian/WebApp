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
            <div class="row mb-4">
                <span class="page-title-perso">Client Portal</span>
            </div>
            <!-- content section -->
            <div class="row">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow active" data-bs-toggle="modal" data-bs-target="#userAccountModal">
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
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="updateClientPortalData();">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-18 text-primary fw-bold">UPDATE CLIENT PORTAL DATA</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-file-arrow-up fa-beat"></i></div>
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
                                <div class="col-sm">
                                    <h4 class="fw-bold text-light">Client Job Status List</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="client_job_status_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date</th>
                                            <th>Customer Name</th>
                                            <th>J.O Number</th>
                                            <th>P.O Number</th>
                                            <th>Descriptions</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Total Quantity</th>
                                            <th style="text-align:center;">Vault Good Card</th>
                                            <th style="text-align:center;">Delivered Card</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date</th>
                                            <th>Customer Name</th>
                                            <th>J.O Number</th>
                                            <th>P.O Number</th>
                                            <th>Descriptions</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Total Quantity</th>
                                            <th style="text-align:center;">Vault Good Card</th>
                                            <th style="text-align:center;">Delivered Card</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== User Account Modal =============== -->
            <div class="modal fade" id="userAccountModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4 d-flex justify-content-between align-items-center">
                            <h4 class="modal-title text-uppercase fw-bold text-light">USER ACCOUNTS</h4>
                            <div>
                                <button class="btn btn-light fw-bold" id="scan_barcode" onclick="addNewUser();"><i class="fa-solid fa-user-plus me-2"></i>New User</button>
                            </div>
                        </div>
                        <div class="modal-body">

                            <div class="table-responsive">
                                <table id="user_account_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Email</th>
                                            <th>Company</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Email</th>
                                            <th>Company</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== User Account Modal =============== -->
            <div class="modal fade" id="addUserAccountModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">NEW USER</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="client_fullname">
                                <div class="invalid-feedback"></div>
                                <label for="client_fullname" class="fw-bold">Full name:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="email" class="form-control fw-bold" id="client_username">
                                <div class="invalid-feedback"></div>
                                <label for="client_username" class="fw-bold">User email:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold text-center" id="client_password" disabled>
                                <div class="invalid-feedback"></div>
                                <label for="client_password" class="fw-bold">Password:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="client_customer_name"></select>
                                <div class="invalid-feedback"></div>
                                <label for="client_customer_name" class="fw-bold">Customer Name:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveUser" onclick="saveUser();">Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
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
include './../helper/select_values.php'; ?>
<script>
    loadClientJobStatusTable();
    loadUserAccountTable();

    function loadClientJobStatusTable() {
        var client_job_status_table = $('#client_job_status_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_client_portal_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_client_order_table'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '10%'
            }, {
                targets: [1, 2, 3, 4],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: [5, 6, 7, 8],
                className: 'dt-body-middle-center',
                width: '5%'
            }]
        });
    }

    function loadUserAccountTable() {
        var user_account_table = $('#user_account_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_client_portal_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_user_accounts_table'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '25%'
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '20%'
            }, {
                targets: 2,
                className: 'dt-body-middle-left'
            }]
        });
        setInterval(function() {
            user_account_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addNewUser() {
        $('#addUserAccountModal').modal('show');
        randomPasswordGenerator();
        loadCustomerName();
        removeInputDisabled();
    }

    function saveUser() {
        let isValidated = false;
        if (inputValidation('client_fullname', 'client_password', 'client_customer_name', 'client_username')) {
            if (!validateEmail($('#client_username').val())) {
                $('#client_username').addClass('is-invalid');
                $('#client_username').next().html('Invalid email address');
                isValidated = false
            } else {
                $('#client_username').addClass('is-valid');
                $('#client_username').next().html();
                isValidated = true
            }
        } else {
            isValidated = false;
        }

        if (isValidated == true) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_client_portal_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_client_user',
                    client_fullname: $('#client_fullname').val(),
                    client_username: $('#client_username').val(),
                    client_password: $('#client_password').val(),
                    client_customer_name: $('#client_customer_name').val()
                },
                success: result => {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Username already exist!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#client_username').focus().removeClass('is-valid').addClass('is-invalid');
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'User successfully addded.',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        addInputDisabled();
                        clearAttributes();
                        $('#user_account_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function updateClientPortalData() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_client_portal_contr.php',
            type: 'POST',
            data: {
                action: 'delete_client_portal_data'
            },
            beforeSend: function() {
                Swal.fire({
                    position: 'center',
                    html: '<div class="mb-3"><img src="../vendor/images/loading_gif.gif"/></div><div><span class="fw-bold">Please wait while record is updating.</span></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: result => {
                $.ajax({
                    url: '../controller/perso_monitoring_controller/perso_client_portal_contr.php',
                    type: 'POST',
                    data: {
                        action: 'update_client_portal_data'
                    },
                    success: result => {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Client Portal Data Updated!',
                            text: '',
                            showConfirmButton: true,
                        });
                        $('#client_job_status_table').DataTable().ajax.reload(null, false);
                    }
                });
            }
        });
    }

    function loadCustomerName() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_client_portal_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_customer_name'
            },
            success: result => {
                loadSelectValues('client_customer_name', result);
            }
        });
    }

    function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test(email);
    }

    function randomPasswordGenerator() {
        var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var passwordLength = 12;
        var passGenerated = "";
        for (var i = 1; i <= passwordLength; i++) {
            var randomNumber = Math.floor(Math.random() * chars.length);
            passGenerated += chars.substring(randomNumber, randomNumber + 1);
        }
        $('#client_password').val(passGenerated);
    }

    function addInputDisabled() {
        $('#client_fullname').prop('disabled', true);
        $('#client_username').prop('disabled', true);
        $('#client_customer_name').prop('disabled', true);
        $('.btnSaveUser').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
    }

    function removeInputDisabled() {
        $('#client_fullname').prop('disabled', false);
        $('#client_username').prop('disabled', false);
        $('#client_customer_name').prop('disabled', false);
        $('.btnSaveUser').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
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