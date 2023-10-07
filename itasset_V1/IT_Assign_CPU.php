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
foreach($result_res as $row){
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
                <span class="page-title-it ">Assign CPU</span>
            </div>

            <div class="card shadow border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger">Assign CPU</h4>
                        <button class="btn btn-danger fw-bold" onclick="newAssignCPU();"><i class="fa-solid fa-plus"></i> New Entry</button>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table w-100" id="table_assign_cpu">
                            <thead>
                                <tr>
                                    <th class="text-center">EMPLOYEE</th>
                                    <th class="text-center">CPU NUMBER</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">SWITCH</th>
                                    <th class="text-center">LAN CABLE</th>
                                    <th class="text-center">IP ADDRESS</th>
                                    <th class="text-center">DATE UPDATED</th>
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

<!-- Assign CPU Modal -->
<div class="modal fade" id="modalAssignCPU" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAssignCPULabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal-title text-danger fw-bold mb-4" id="modalAssignCPULabel"><i class="fa-solid fa-file-circle-plus me-1"></i>New Entry</h4>

                <div class="form-floating mb-3">
                    <input type="text" id="cpu_control_number" class="form-control-plaintext text-end fs-3 fw-bold border border-1 rounded" placeholder=" " readonly disabled>
                    <label for="cpu_control_number" class="form-label fw-bold">CPU Control Number:</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="department" onchange="loadEmployee(this.value);">
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="department" class="fw-bold">Department:</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="employee" disabled>
                        <option value="" selected>Select an Employee:</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="employee" class="fw-bold">Employee:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="description" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="description" class="fw-bold">Description:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="location" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="location" class="fw-bold">Location:</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" name="switch_tag" id="switch_tag">
                        <option value="" selected>Select a Switch Tag:</option>
                        <option value="OE-SW1">OE-SW1</option>
                        <option value="OE-SW2">OE-SW2</option>
                        <option value="OE-SW3">OE-SW3</option>
                        <option value="PG-SW1">PG-SW1</option>
                        <option value="PG-SW2">PG-SW2</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="switch_tag" class="fw-bold">Switch Tag:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="lan_cable_tag" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="lan_cable_tag" class="fw-bold">Lan Cable Tag:</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="text" id="ip_address" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="ip_address" class="fw-bold">IP Address:</label>
                </div>
                <div class="d-grid gap-2 modal-btn">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './../includes/footer.php'; ?>

<script>
    //* ====================== IP Address Masking ======================
    $('#ip_address').mask('Z99.Z99.Z99.Z99', {
        translation: {
            'Z': {
                pattern: /[0-2]/,
                optional: true
            }
        },
        onKeyPress: function(val, e, el, options) {
            var match = val.match(/[0-9]+$/);
            if (match) {
                var v = parseInt(match[0]) || 0;
                $(el).val(val.substr(0, match.index) + ((v > 255) ? 255 : v));
            }
        }
    });

    //* ====================== F U N C T I O N S ======================

    //* ~ Function calls ~
    let tableAssignCpu = "";
    loadTableAssignCPU();
    loadInputData();

    //* ~ load table through serverside ~
    function loadTableAssignCPU() {
        tableAssignCpu = $('#table_assign_cpu').DataTable({
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
                url: 'functions/it_assign_cpu-function.php',
                type: 'POST',
                data: {
                    action: 'loadTableAssignCPU'
                }
            },
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); // ----- Hide tooltip every table draw -----
            },
            columnDefs: [{
                    targets: [1, 3, 4, 5, 6, 7],
                    className: 'dt-body-middle-center'
                },
                {
                    targets: [0, 2],
                    className: 'dt-body-middle-left'
                },
                {
                    targets: 8,
                    orderable: false,
                    className: 'dt-nowrap-center'
                }
            ]
        });

        // ----- Reload table every 30 seconds. -----
        setInterval(function() {
            tableAssignCpu.ajax.reload(null, false);
        }, 5000);

    }

    //* ~ load input data upon creation ~
    function loadInputData() {
        $.ajax({
            url: 'functions/it_assign_cpu-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadInputData',
            },
            success: (data) => {
                $('#cpu_control_number').val(data.cpuControlNumber);
                $('#department').html(data.department);
            }
        });
    }

    //* ~ load input data of employee ~
    function loadEmployee(deptCode) {
        $('#employee').prop('disabled', deptCode == "" ? true : false);
        $.ajax({
            url: 'functions/it_assign_cpu-function.php',
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

    //* ~ New entry of assign CPU ~
    function newAssignCPU() {
        clearAttributes();
        loadInputData();
        $('#modalAssignCPU').modal('show');
        $('.modal-title').html('<i class="fa-solid fa-file-circle-plus me-1"></i>New Entry');
        $('.modal-btn').html(
            `<button type="button" class="btn btn-danger btn-new fw-bold rounded-pill">Save</button>
            <button type="button" class="btn btn-light btn-cancel text-danger fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>`);
        $('.btn-new').click(() => {
            if (formValidation('employee', 'description', 'location', 'switch_tag', 'lan_cable_tag', 'ip_address', 'department')) {
                $.ajax({
                    url: 'functions/it_assign_cpu-function.php',
                    type: 'POST',
                    data: {
                        action: 'newAssignCPU',
                        // activeComputer: $('#active_computer').is(':checked'),
                        cpuControlNumber: $('#cpu_control_number').val(),
                        employee: $('#employee').val(),
                        description: $('#description').val(),
                        location: $('#location').val(),
                        switchTag: $('#switch_tag').val(),
                        lanCableTag: $('#lan_cable_tag').val(),
                        ipAddress: $('#ip_address').val(),
                        dateToday: (new Date()).toISOString().split('T')[0]
                    },
                    success: (res) => {
                        if (res) {
                            $('#modalAssignCPU').modal('hide');
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Success',
                                text: 'New Entry Added.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            tableAssignCpu.ajax.reload(null, false);
                        }
                    }
                });
            }
        });
    }

    //* ~ Edit entry of assign CPU ~
    function editAssignCPU(cpuID) {
        clearAttributes();
        $('#modalAssignCPU').modal('show');
        $('.modal-title').html('<i class="fa-solid fa-file-edit me-1"></i>Edit Entry');
        $.ajax({
            url: 'functions/it_assign_cpu-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'fetchByIdAssignCPU',
                cpuID: cpuID
            },
            success: (data) => {
                // $('#active_computer').prop('checked', data.active)
                $('#cpu_control_number').val(data.cpuControlNumber);
                $('#department').val(data.deptCode);
                loadEmployee(data.deptCode);
                setTimeout(() => {
                    $('#employee').val(data.employee);
                }, 500);
                $('#description').val(data.description);
                $('#location').val(data.location);
                $('#switch_tag').val(data.switchTag);
                $('#lan_cable_tag').val(data.lanCableTag);
                $('#ip_address').val(data.ipAddress);
            }
        });
        $('.modal-btn').html(
            `<button type="button" class="btn btn-danger btn-edit fw-bold rounded-pill">Update</button>
            <button type="button" class="btn btn-light btn-cancel text-danger fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>`);
        $('.btn-edit').click(() => {
            if (formValidation('employee', 'description', 'location', 'switch_tag', 'lan_cable_tag', 'ip_address', 'department')) {
                $.ajax({
                    url: 'functions/it_assign_cpu-function.php',
                    type: 'POST',
                    data: {
                        action: 'editAssignCPU',
                        // activeComputer: $('#active_computer').is(':checked'),
                        cpuControlNumber: $('#cpu_control_number').val(),
                        employee: $('#employee').val(),
                        description: $('#description').val(),
                        location: $('#location').val(),
                        switchTag: $('#switch_tag').val(),
                        lanCableTag: $('#lan_cable_tag').val(),
                        ipAddress: $('#ip_address').val(),
                        dateToday: (new Date()).toISOString().split('T')[0]
                    },
                    success: (res) => {
                        if (res) {
                            $('#modalAssignCPU').modal('hide');
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Success',
                                text: 'Data Updated.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            tableAssignCpu.ajax.reload(null, false);
                        }
                    }
                });
            }
        });
    }

    //* ~ Print assign CPU ~
    function pdfAssignCPU(cpuID) {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: '<b>Alert!</b>',
            html: '<h6>This Feature is Undergoing Maintenance.</h6>',
            showConfirmButton: false,
            timer: 2000
        });
    }

    //* ~ Validate fields on keypress ~
    $(document).on("keypress", "input.is-invalid", function() {
        $(this).toggleClass('is-valid is-invalid');
    });

    //* ~ Validate fields on change ~
    $(document).on("change", "select.is-invalid", function() {
        $(this).toggleClass('is-valid is-invalid');
    });

    //* ~ Form validation function ~
    function formValidation(...args) {
        let employee = $('#' + arguments[0]).val();
        let description = $('#' + arguments[1]).val();
        let location = $('#' + arguments[2]).val();
        let switchTag = $('#' + arguments[3]).val();
        let lanCableTag = $('#' + arguments[4]).val();
        let ipAddress = $('#' + arguments[5]).val();
        let department = $('#' + arguments[6]).val();
        let validated = true;

        if (employee.trim() == '') {
            validate(arguments[0], 'Employee is required field.');
            validated = false;
        } else {
            clearValidate(arguments[0]);
        }

        if (description.trim() == '') {
            validate(arguments[1], 'Description is required field.');
            validated = false;
        } else {
            clearValidate(arguments[1]);
        }

        if (location.trim() == '') {
            validate(arguments[2], 'Location is required field.');
            validated = false;
        } else {
            clearValidate(arguments[2]);
        }

        if (switchTag.trim() == '') {
            validate(arguments[3], 'Switch Tag is required field.');
            validated = false;
        } else {
            clearValidate(arguments[3]);
        }

        if (lanCableTag.trim() == '') {
            validate(arguments[4], 'Lan Cable Tag is required field.');
            validated = false;
        } else {
            clearValidate(arguments[4]);
        }

        let ipAddressRegex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;

        if (ipAddress.trim() == '') {
            validate(arguments[5], 'IP Address is required field.');
            validated = false;
        } else if (!ipAddressRegex.test(ipAddress)) {
            validate(arguments[5], 'Invalid IP address format.');
            validated = false;
        } else {
            clearValidate(arguments[5]);
        }

        if (department.trim() == '') {
            validate(arguments[6], 'Department is required field.');
            validated = false;
        } else {
            clearValidate(arguments[6]);
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