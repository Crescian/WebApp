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
}
?>

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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it ">Hardware Issuance</span>
            </div>

            <div class="card shadow border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger fw-bold">EMPLOYEE</h4>
                        <div>
                            <button class="btn btn-danger fw-bold" onclick="newHardwareIssuance();"><i class="fa-solid fa-plus"></i> New Entry</button>
                            <button type="button" class="btn btn-dark dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">
                                View By
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" id="filter_all" onclick="loadTableHardwareIssuance('');" href="#">All</a></li>
                                <li><a class="dropdown-item active" id="filter_issued" onclick="loadTableHardwareIssuance('Issued');" href="#">Issued</a></li>
                                <li><a class="dropdown-item" id="filter_retrieved" onclick="loadTableHardwareIssuance('Retrieved');" href="#">Retrieved</a></li>
                                <li><a class="dropdown-item" id="filter_defective" onclick="loadTableHardwareIssuance('Defective');" href="#">Defective</a></li>
                                <li><a class="dropdown-item" id="filter_returned" onclick="loadTableHardwareIssuance('Returned');" href="#">Returned to Warehouse</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table w-100" id="table_hardware_issuance">
                            <thead>
                                <tr>
                                    <th class="text-center">EMPLOYEE</th>
                                    <th class="text-center">CPU NUMBER</th>
                                    <th class="text-center">DESCRIPTION</th>
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
                <button class="btn btn-danger rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
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

<!-- Hardware Issuance Modal -->
<div class="modal fade" id="modalHardwareIssuance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalHardwareIssuanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="position-absolute top-50 start-50 translate-middle bg-light shadow p-3 rounded-pill pre-loading" style="display: none;">
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                </div>
                <h4 class="modal-title text-danger fw-bold mb-4" id="modalHardwareIssuanceLabel"><i class="fa-solid fa-file-circle-plus me-1"></i>New Entry</h4>
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
                        <input type="text" id="barcode_number" class="form-control fw-bold" placeholder="Barcode Number:">
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
                    <button type="button" class="btn btn-danger fw-bold rounded-pill" onclick="saveHardwareIssuance();">Save</button>
                    <button type="button" class="btn btn-light text-danger fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="modalActionHardwareIssuance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalActionHardwareIssuanceLabel" aria-hidden="true">
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
    // * ~ Dropdown Active ~
    $('.dropdown-menu li a').on('click', function() {
        $('.dropdown-menu li a').removeClass('active');
        $(this).addClass('active');
    });

    //* ====================== F U N C T I O N S ======================

    //* ~ Function calls ~
    let tableHardwareIssuance;
    loadTableHardwareIssuance('Issued');

    //* ~ load table through serverside ~
    function loadTableHardwareIssuance(filterValue) {

        tableHardwareIssuance = $('#table_hardware_issuance').DataTable({
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
                url: 'functions/it_hardware_issuance-function.php',
                type: 'POST',
                data: {
                    action: 'loadTableHardwareIssuance',
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
                    targets: 2,
                    width: '30%'
                },
                {
                    targets: 6,
                    orderable: false,
                    className: 'dt-nowrap-center'
                }
            ]
        });


        // // ----- Reload table every 30 seconds. -----
        // setInterval(function() {
        //     tableHardwareIssuance.ajax.reload(null, false);
        // }, 30000);

    }

    //* ~ load input data upon creation ~
    function loadInputData() {
        $.ajax({
            url: 'functions/it_hardware_issuance-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadInputData',
            },
            success: (data) => {
                $('#issuer').html(data.issuer);
                $('#department').html(data.department);
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

    //* ~ New entry of Hardware Issuance ~
    function newHardwareIssuance() {
        clearAttributes();
        loadInputData();
        $('#modalHardwareIssuance').modal('show');
    }

    //* ~ Save new entry of Hardware Issuance ~
    function saveHardwareIssuance() {
        if (formValidation('department', 'employee', 'cpu_control_no', 'barcode_number', 'issuer')) {
            $.ajax({
                url: 'functions/it_hardware_issuance-function.php',
                type: 'POST',
                data: {
                    action: 'newHardwareIssuance',
                    cpuControlNumber: $('#cpu_control_no').val(),
                    barcodeNumber: $('#barcode_number').val(),
                    issuer: $('#issuer').val(),
                    dateIssued: (new Date()).toISOString().split('T')[0]
                },
                beforeSend: function() {
                    $('.pre-loading').fadeIn();
                    $('.modal-btn').children('button').prop('disabled', true);
                },
                success: (res) => {
                    $('.pre-loading').fadeOut();
                    $('.modal-btn').children('button').prop('disabled', false);
                    if (res == true) {
                        $('#modalHardwareIssuance').modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Success',
                            text: 'Item Added Successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tableHardwareIssuance.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: `${res}`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }
    }

    //* ~ Action Hardware Issuance ~
    function actionHardwareIssuance(id, status) {
        $('#modalActionHardwareIssuance').modal('show');
        let btn_status;
        switch (status) {
            case 'Retrieve':
                $('.alert-title').html('<i class="fa-solid fa-arrow-right-arrow-left"></i> Retrieve');
                $('.message').text('Are you sure you want to retrieve this item?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'retrieve_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#retrieve_btn');
                break;

            case 'Defective':
                $('.alert-title').html('<i class="fa-solid fa-triangle-exclamation"></i> Defective');
                $('.message').text('Are you sure that the item is defective?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'defective_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#defective_btn');
                break;

            case 'Return':
                $('.alert-title').html('<i class="fa-solid fa-warehouse"></i> Return to Warehouse');
                $('.message').text('Are you sure you want to return this item to warehouse?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'return_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#return_btn');
                break;

            case 'Print':
                $('.alert-title').html('<i class="fa-solid fa-file-pdf"></i> Print');
                $('.message').text('This feature is not available right now.');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'print_btn',
                    text: 'Yes, submit',
                    disabled: true
                }));
                btn_status = $('#print_btn');
                break;

            case 'Delete':
                $('.alert-title').html('<i class="fa-solid fa-trash"></i> Delete');
                $('.message').text('This data will be deleted permanently. Are you sure?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'delete_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#delete_btn');
                break;
        }
        btn_status.on('click', function() {
            $.ajax({
                url: 'functions/it_hardware_issuance-function.php',
                type: 'POST',
                data: {
                    action: 'actionHardwareIssuance',
                    id: id,
                    status: status
                },
                success: function(res) {
                    // if (res == true) {
                    $('#modalActionHardwareIssuance').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Success',
                        text: `Item ${res}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    tableHardwareIssuance.ajax.reload(null, false);
                    // }
                }

            });
        });
    }

    //* ~ Validate fields  ~
    $(document).on("input change", "input.is-invalid, select.is-invalid", function() {
        $(this).toggleClass('is-valid is-invalid');
    });

    //* ~ Form validation function ~
    function formValidation(...args) {
        let department = $(`#${arguments[0]}`).val();
        let employee = $(`#${arguments[1]}`).val();
        let cpuControlNumber = $(`#${arguments[2]}`).val();
        let barcodeNumber = $(`#${arguments[3]}`).val();
        let issuer = $(`#${arguments[4]}`).val();
        let validated = true;


        if (department.trim() == '') {
            validate(arguments[0], 'Department is required field.');
            validated = false;
        } else {
            clearValidate(arguments[0]);
        }

        if (employee.trim() == '') {
            validate(arguments[1], 'Employee is required field.');
            validated = false;
        } else {
            clearValidate(arguments[1]);
        }

        if (cpuControlNumber.trim() == '') {
            validate(arguments[2], 'CPU Control Number is required field.');
            validated = false;
        } else {
            clearValidate(arguments[2]);
        }

        if (barcodeNumber.trim() == '') {
            validate(arguments[3], 'Barcode Number is required field.');
            validated = false;
        } else {
            clearValidate(arguments[3]);
        }

        if (issuer.trim() == '') {
            validate(arguments[4], 'Issuer is required field.');
            validated = false;
        } else {
            clearValidate(arguments[4]);
        }
        return validated;
    }

    //* ~ Validation Error ~
    function validate(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    //* ~ Validation Success ~
    function clearValidate(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }

    //* ~ Reset ~
    function clearAttributes() {
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid').val('');
        $('#employee').prop('disabled', true);
    }
</script>