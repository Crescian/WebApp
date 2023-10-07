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
<div class="container-fluid">
    <div class="row">
        <div class="col content scroll_color_admin overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-admin">Department And Access Module</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-6 mb-3">
                    <div class="card shadow">
                        <div class="card-header card-7 py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="h4 m-0 fw-bold text-light">Department</h6>
                            <button class="btn btn-light fw-bold fs-18" onclick="departmentModal();"><i class="fa-solid fa-plus p-r-8"></i> Add Department</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="dept_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_admin">
                                            <tr>
                                                <th style="text-align: center;">Department</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_admin">
                                            <tr>
                                                <th style="text-align: center;">Department</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card shadow">
                        <div class="card-header card-7 py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="h4 m-0 fw-bold text-light">Job Title</h6>
                            <button class="btn btn-light fw-bold fs-18" onclick="AccessModal();"><i class="fa-solid fa-plus p-r-8"></i> Add Job Title</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="access_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_admin">
                                            <tr>
                                                <th style="text-align: center;">Job Title</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_admin">
                                            <tr>
                                                <th style="text-align: center;">Job Title</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light headModal"> Add Department</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3 mt-3">
                                <label for="" class="col-form-label col-sm-3 fw-bold">Department:</label>
                                <div class="col">
                                    <input type="text" id="department_input" class="form-control fw-bold">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <label for="dept_code" class="col-form-label col-sm-3 fw-bold">Dept Code:</label>
                                <div class="col">
                                    <input type="text" id="dept_code" class="form-control fw-bold">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success col-sm fw-bold btn-save" onclick="btnDeptSave();"><i class="fa-solid fa-file p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger col-sm fw-bold btn-cancel" onclick="btnDeptAccesClose();"><i class="fa-solid fa-ban p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light headModal"> Add Job Title</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3 mt-3">
                                <label for="dept" class="col-form-label col-sm-3 fw-bold">Department:</label>
                                <div class="col">
                                    <select name="" id="dept" class="form-select fw-bold">
                                        <option value=""></option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <label for="access_input" class="col-form-label col-sm-3 fw-bold">Job Title:</label>
                                <div class="col">
                                    <input type="text" id="access_input" class="form-control fw-bold">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success col-sm fw-bold btn-save" onclick="btnAccessSave();"><i class="fa-solid fa-file p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger col-sm fw-bold btn-cancel" onclick="btnDeptAccesClose();"><i class="fa-solid fa-ban p-r-8"></i> Close</button>
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
            <div class="card card-7 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">Administrator</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    // ======================= Enable Tooltip =======================
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="tooltip"]').on('click', function() { // =========== Hide tooltip upon click ===========
            $(this).tooltip('hide');
        });
    });
    // ======================= Enable Tooltip End =======================
    let dept_tabled;
    let access_table;
    loadDeptTable();
    loadAccessTable();

    function loadDropDown() {
        $.ajax({
            url: 'functions/dept_and_access_module_function.php',
            type: 'POST',
            data: {
                action: 'loadDept'
            },
            success: function(result) {
                $('#dept').html(result);
            }
        });
    }

    function loadDeptTable() {
        dept_tabled = $('#dept_table').DataTable({
            'serverSide': true,
            'paging': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/dept_and_access_module_function.php',
                type: 'POST',
                data: {
                    action: 'loadDeptTable'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false
            }],
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); // =========== Hide tooltip every table draw ===========
            }
        });
        setInterval(function() {
            dept_tabled.ajax.reload(null, false);
        }, 30000); // ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAccessTable() {
        access_table = $('#access_table').DataTable({
            'serverSide': true,
            'paging': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/dept_and_access_module_function.php',
                type: 'POST',
                data: {
                    action: 'loadJobTitleTable'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false
            }],
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); // =========== Hide tooltip every table draw ===========
            }
        });
        setInterval(function() {
            access_table.ajax.reload(null, false);
        }, 30000); // ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function btnDeptSave() {
        if (submitValidation('dept')) {
            let department_input = document.getElementById('department_input').value;
            let dept_code = document.getElementById('dept_code').value;
            $.ajax({
                url: 'functions/dept_and_access_module_function.php',
                type: 'POST',
                data: {
                    action: 'btnDeptSaveFunction',
                    department_input: department_input,
                    dept_code: dept_code
                },
                success: function(result) {
                    if (result == 'Department Exist') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Data is Exist',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Saved',
                            text: 'Succesfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    clearValues();
                    dept_tabled.ajax.reload(null, false);
                }
            });
        }
    }

    function btnAccessSave() {
        if (submitValidation('access')) {
            let dept = document.getElementById('dept').value;
            let access_input = document.getElementById('access_input').value;
            $.ajax({
                url: 'functions/dept_and_access_module_function.php',
                type: 'POST',
                data: {
                    action: 'btnDeptAccessFunction',
                    dept: dept,
                    access_input: access_input
                },
                success: function(result) {
                    if (result == 'Access Level Exist') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Data is Exist',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Saved',
                            text: 'Succesfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    clearValues();
                }
            });
            access_table.ajax.reload(null, false);
        }
    }

    function btnDeptAccesClose() {
        $('#departmentModal').modal('hide');
        $('#accessModal').modal('hide');
        clearValues();
    }

    function btnDeptDelete(id) {
        let btnDeptDelete = 'btnDeptDelete';
        $.ajax({
            url: 'functions/dept_and_access_module_function.php',
            type: 'POST',
            data: {
                action: 'deleteFunction',
                id: id,
                btnDeptAccessDelete: btnDeptDelete
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Deleted',
                    text: 'Succesfully',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
        dept_tabled.ajax.reload(null, false);
    }

    function btnAccessDelete(id) {
        let btnAccessDelete = 'btnAccessDelete';
        $.ajax({
            url: 'functions/dept_and_access_module_function.php',
            type: 'POST',
            data: {
                action: 'deleteFunction',
                id: id,
                btnDeptAccessDelete: btnAccessDelete
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Deleted',
                    text: 'Succesfully',
                    showConfirmButton: false,
                    timer: 1500
                });
                access_table.ajax.reload(null, false);
            }
        });
    }

    function departmentModal() {
        $('#departmentModal').modal('show');
    }

    function AccessModal() {
        $('#accessModal').modal('show');
        loadDropDown();
    }

    function clearValues() {
        $('input').val('');
        $('textarea').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function submitValidation(val) {
        let isValidated = true;
        if (val == 'dept') {
            let department_input = document.getElementById('department_input').value;
            if (department_input.length == 0) {
                showFieldError('department_input', 'Department must not be blank');
                if (isValidated) {
                    $('#department_input').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('department_input');
            }
            return isValidated;
        } else if (val == 'access') {
            let access_input = document.getElementById('access_input').value;
            if (access_input.length == 0) {
                showFieldError('access_input', 'Job Title must not be blank');
                if (isValidated) {
                    $('#access_input').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('access_input');
            }
            return isValidated;
        }
    }

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

    function clearAttributes() {
        $('input').removeClass('is-invalid');
        $('input').removeClass('is-valid');
        $('select').removeClass('is-invalid');
        $('select').removeClass('is-valid');
        $('textarea').removeClass('is-invalid');
        $('textarea').removeClass('is-valid');
    }
</script>
</body>
<html>