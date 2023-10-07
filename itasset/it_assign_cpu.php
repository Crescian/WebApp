<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
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
                                    <th class="text-center">DATE UPDATED</th>
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
<!-- Assign CPU Modal -->
<div class="modal fade" id="modalAssignCPU" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAssignCPULabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal-title text-danger fw-bold mb-4" id="modalAssignCPULabel"><i class="fa-solid fa-file-circle-plus me-1"></i>New Entry</h4>

                <div class="row mb-2 hide-radio">
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input typeAssign" type="radio" name="typeAssign" id="typeAssign0" value="PC">
                            <label class="form-check-label fw-bold" for="typeAssign1">
                                PC
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input typeAssign" type="radio" name="typeAssign" id="typeAssign1" value="LT">
                            <label class="form-check-label fw-bold" for="typeAssign3">
                                LT
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="cpu_control_number" class="form-control fw-bold" placeholder=" " readonly>
                    <div class="invalid-feedback"></div>
                    <label for="cpu_control_number" class="form-label fw-bold">CPU Control Number:</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select fw-bold" id="department" onchange="loadEmployee(this.value);">
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="department" class="fw-bold">Department:</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select fw-bold" id="employee" disabled>
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

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="activePc" checked>
                    <label class="form-check-label fw-bold" for="activePc">
                        Active
                    </label>
                </div>

                <div class="d-grid gap-2 modal-btn">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    let tableAssignCpu = "";
    loadTableAssignCPU();
    loadInputData();

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
                url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
                type: 'POST',
                data: {
                    action: 'loadTableAssignCPU'
                }
            },
            drawCallback: function(settings, json) {},
            columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5],
                    className: 'dt-body-middle-center'
                },
                {
                    targets: 6,
                    orderable: false,
                    className: 'dt-nowrap-center'
                }
            ]
        });
        setInterval(function() {
            tableAssignCpu.ajax.reload(null, false);
        }, 5000);
    }

    //* ~ load input data upon creation ~
    function loadInputData() {
        $.ajax({
            url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadInputData',
            },
            success: (data) => {
                $('#department').html(data.department);
            }
        });
    }

    //* ~ load input data of employee ~
    function loadEmployee(deptCode) {
        $('#employee').prop('disabled', deptCode == "" ? true : false);
        $.ajax({
            url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
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

    $('.typeAssign').on('change', function() {
        let radioButtons = document.getElementsByName("typeAssign");
        let type;
        for (let i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                type = radioButtons[i].value;
                break;
            }
        }
        $.ajax({
            url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
            type: 'POST',
            data: {
                action: 'setTypeControlNumber',
                type
            },
            success: result => {
                console.log(result);
                $('#cpu_control_number').val(result);
            }
        })
    });
    //* ~ New entry of assign CPU ~
    function newAssignCPU() {
        loadInputData();
        $('#modalAssignCPU').modal('show');
        $('.hide-radio').show();
        $('.modal-title').html('<i class="fa-solid fa-file-circle-plus me-1"></i>New Entry');
        $('.modal-btn').html(`<button type="button" class="btn btn-danger btn-new fw-bold rounded-pill" onclick="saveFunction();">Save</button>
            <button type="button" class="btn btn-light btn-cancel text-danger fw-bold rounded-pill" onclick="cancelFunction();" data-bs-dismiss="modal">Cancel</button>`);
        $('#cpu_control_number').val('');
    }

    function cancelFunction() {
        clearValues();
    }

    function deleteCPU(id) {
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
                    url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
                    type: 'POST',
                    data: {
                        action: 'deleteCPU',
                        id
                    },
                    success: (res) => {
                        loadTableAssignCPU();
                    }
                })
            }
        });
    }

    function saveFunction() {
        if (formValidation('employee', 'description', 'location', 'department', 'cpu_control_number')) {
            let activePc = document.getElementById("activePc");
            let radioButtons = document.getElementsByName("typeAssign");
            let access;
            for (let i = 0; i < radioButtons.length; i++) {
                if (radioButtons[i].checked) {
                    access = radioButtons[i].value;
                    break;
                }
            }
            $.ajax({
                url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
                type: 'POST',
                data: {
                    action: 'newAssignCPU',
                    cpuControlNumber: $('#cpu_control_number').val(),
                    employee: $('#employee').val(),
                    description: $('#description').val(),
                    location: $('#location').val(),
                    dateToday: (new Date()).toISOString().split('T')[0],
                    activePc: activePc.checked
                },
                success: (res) => {
                    console.log(res);
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
                    clearValues();
                    clearAttributes();
                }
            });
        }
    }

    //* ~ Edit entry of assign CPU ~
    function editAssignCPU(cpuID) {
        clearAttributes();
        $('.hide-radio').hide();
        $('#modalAssignCPU').modal('show');
        $('.modal-title').html('<i class="fa-solid fa-file-edit me-1"></i>Edit Entry');
        $.ajax({
            url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
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
            }
        });
        $('.modal-btn').html(
            `<button type="button" class="btn btn-danger btn-edit fw-bold rounded-pill">Update</button>
            <button type="button" class="btn btn-light btn-cancel text-danger fw-bold rounded-pill" onclick="cancelFunction();" data-bs-dismiss="modal">Cancel</button>`);
        $('.btn-edit').click(() => {
            if (formValidation('employee', 'description', 'location', 'department', 'cpu_control_number')) {
                $.ajax({
                    url: "../controller/itasset_controller/it_assign_cpu_contr.class.php",
                    type: 'POST',
                    data: {
                        action: 'editAssignCPU',
                        cpuControlNumber: $('#cpu_control_number').val(),
                        employee: $('#employee').val(),
                        description: $('#description').val(),
                        location: $('#location').val(),
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

    function clearValues() {
        $('#cpu_control_number').val('');
        $('#description').val('');
        $('#location').val('');
        $('#department').find('option:first').prop('selected', 'selected');
        $('#employee').find('option:first').prop('selected', 'selected');
        $(".typeAssign").prop('checked', false);
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
        let department = $('#' + arguments[3]).val();
        let control_number = $('#' + arguments[4]).val();
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
        if (department.trim() == '') {
            validate(arguments[3], 'Department is required field.');
            validated = false;
        } else {
            clearValidate(arguments[3]);
        }
        if (control_number.trim() == '') {
            validate(arguments[4], 'Control Number is required field.');
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
        $('input, select, textarea').removeClass('is-invalid is-valid');
        $('#employee').prop('disabled', true);
    }
</script>