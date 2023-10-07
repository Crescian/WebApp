<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
// date_default_timezone_set('Asia/Manila');
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    // $data_base64 = base64_encode($sqlstring);
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    // $json_response = curl_exec($curl);
    // //* ====== Close Connection ======
    // curl_close($curl);
    // // * ======== Prepare Array ========
    // $data_result = json_decode($json_response, true);
    // foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
} ?>

<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom right, #fa3c3c, #aa0000);
        border-radius: 100vw;
    }
</style>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<!-- Insert your code here -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it ">Software Issuance</span>
            </div>

            <div class="card shadow border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger fw-bold">Software</h4>
                        <div>
                            <button class="btn btn-danger fw-bold" onclick="newSoftwareIssuance();"><i class="fa-solid fa-plus"></i> New Entry</button>
                            <button type="button" class="btn btn-dark dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">
                                View By
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" id="filter_all" onclick="loadTableSoftwareIssuance('');" href="#">All</a></li>
                                <li><a class="dropdown-item active" id="filter_issued" onclick="loadTableSoftwareIssuance('Issued');" href="#">Issued</a></li>
                                <li><a class="dropdown-item" id="filter_retrieved" onclick="loadTableSoftwareIssuance('Retrieved');" href="#">Retrieved</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table w-100" id="table_software_issuance">
                            <thead>
                                <tr>
                                    <th class="text-center">EMPLOYEE</th>
                                    <th class="text-center">CPU NUMBER</th>
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center">SOFTWARE</th>
                                    <th class="text-center">SERIAL</th>
                                    <th class="text-center">ISSUER</th>
                                    <th class="text-center">DATE ISSUED</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn card-1 text-light rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <!-- ==================== CARD SECTION ==================== -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-1 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IT ASSET</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
        <!-- ==================== CARD SECTION END ==================== -->
    </div>
</div>

<!-- Software Issuance Modal -->
<div class="modal fade" id="modalSoftwareIssuance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSoftwareIssuanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="position-absolute top-50 start-50 translate-middle bg-light shadow p-3 rounded-pill pre-loading" style="display: none;">
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                </div>
                <h4 class="modal-title text-danger fw-bold mb-4" id="modalSoftwareIssuanceLabel"><i class="fa-solid fa-file-circle-plus me-1"></i>New Entry</h4>
                <div class="row">
                    <div class="form-group col-md-12 mb-3">
                        <select class="form-select fw-bold" id="department" onchange="loadEmployee(this.value);">
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-12 mb-3">
                        <select class="form-select fw-bold" id="employee" onchange="loadCpuControlNo(this.value);" disabled>
                            <option value="" selected>Select an Employee:</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-12 mb-3">
                        <select class="form-select fw-bold" id="cpu_control_no" disabled>
                            <option value="" selected>Select a CPU Control No. :</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-12 mb-3">
                        <select class="form-select fw-bold" id="software_type" onchange="loadSoftwareAndSerial(this.value);">
                            <option value="" selected>Software Type:</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-12 mb-3">
                        <select class="form-select fw-bold" id="software_and_serial" disabled>
                            <option value="" selected>Software Name and Serial Number:</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group col-md-12 mb-4">
                        <select class="form-select fw-bold" id="issuer">
                            <option value="" selected>Select an Issuer:</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="d-grid gap-2 modal-btn">
                    <button type="button" class="btn btn-danger fw-bold rounded-pill" onclick="saveSoftwareIssuance();">Save</button>
                    <button type="button" class="btn btn-light text-danger fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="modalActionSoftwareIssuance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalActionSoftwareIssuanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-body py-4 text-center">
                <h3 class="alert-title mb-4 fw-bold">Title</h3>
                <p class="message fw-semibold fs-6">This is a sample message.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0 alert-modal-btn">
                <!-- <button type="button" class="btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold" id="">Yes, submit</button> -->
                <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    //* ====================== F U N C T I O N S ======================

    //* ~ Function calls ~
    let tableSoftwareIssuance;
    loadTableSoftwareIssuance('Issued');

    //* ~ load table through serverside ~
    function loadTableSoftwareIssuance(filterValue) {

        tableSoftwareIssuance = $('#table_software_issuance').DataTable({
            responsive: true,
            autoWidth: false,
            destroy: true,
            lengthChange: false,
            order: [
                [1, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: 'functions/it_software_issuance-function.php',
                type: 'POST',
                data: {
                    action: 'loadTableSoftwareIssuance',
                    filterValue: filterValue
                }
            },
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); // -- --- Hide tooltip every table draw -----
            },
            columnDefs: [{
                    targets: '_all',
                    className: 'dt-body-middle-center'
                },
                {
                    targets: 8,
                    orderable: false,
                    className: 'dt-nowrap-center'
                }
            ]
        });
        // // ----- Reload table every 30 seconds. -----
        // setInterval(function() {
        //     tableSoftwareIssuance.ajax.reload(null, false);
        // }, 30000);

    }

    //* ~ load input data upon creation ~
    function loadInputData() {
        $.ajax({
            url: 'functions/it_software_issuance-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadInputData',
            },
            success: (data) => {
                $('#issuer').html(data.issuer);
                $('#department').html(data.department);
                $('#software_type').html(data.softwareType);
            }
        });
    }

    //* ~ load input data of employee ~
    function loadEmployee(deptCode) {
        $('#employee').prop('disabled', deptCode == "" ? true : false);
        $.ajax({
            url: 'functions/it_hardware_issuance-function.php',
            type: 'POST',
            data: {
                action: 'loadEmployee',
                deptCode: deptCode,
            },
            success: (data) => {
                $('#employee').html(data);
            }
        });
    }

    //* ~ load input data of cpu control number ~
    function loadCpuControlNo(employeeName) {
        $('#cpu_control_no').prop('disabled', employeeName == "" ? true : false);
        $.ajax({
            url: 'functions/it_hardware_issuance-function.php',
            type: 'POST',
            data: {
                action: 'loadCpuControlNo',
                employeeName: employeeName,
            },
            success: (data) => {
                $('#cpu_control_no').html(data);
            }
        });
    }

    function loadSoftwareAndSerial(softwareType) {
        $('#software_and_serial').prop('disabled', softwareType == "" ? true : false);
        $.ajax({
            url: 'functions/it_software_issuance-function.php',
            type: 'POST',
            data: {
                action: 'loadSoftwareAndSerial',
                softwareType: softwareType,
            },
            success: (data) => {
                $('#software_and_serial').html(data);
            }
        });
    }

    //* ~ New entry of Software Issuance ~
    function newSoftwareIssuance() {
        // clearAttributes();
        loadInputData();
        $('#modalSoftwareIssuance').modal('show');
    }

    function saveSoftwareIssuance() {
        if (formValidation('department', 'employee', 'cpu_control_no', 'software_type', 'software_and_serial', 'issuer')) {
            $.ajax({
                url: 'functions/it_software_available-function.php',
                type: 'POST',
                data: {
                    action: 'newSoftwareAvailable',
                    softwareType: $('#cpu_control_no').val(),
                    software: $('#software_type').val(),
                    description: $('#software_and_serial').val(),
                    serial: $('#issuer').val(),
                },
                success: function(res) {
                    if (res == true) {
                        $('#modalSoftwareAvailable').modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Success',
                            text: `Item Added Successful`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tableSoftwareAvailable.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Failed',
                            text: res,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }
    }


    //* ~ Form validation function ~
    function formValidation(...args) {
        let validated = true;
        $.each(args, function(i, e) {
            let element = $(`#${e}`);
            if (element.val().trim() == '') {
                invalidField(e, 'Field is required.');
                validated = false;
            } else {
                validField(e);
            }
        });
        return validated;
    }

    //* ~ Validation Error ~
    function invalidField(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    //* ~ Validation Success ~
    function validField(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }

    //* ~ Reset ~
    function clearAttributes() {
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid').val('');
        $('#employee').prop('disabled', true);
    }
</script>