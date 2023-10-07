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
    /* =========== Change Scrollbar Style - Justine 01122023 =========== */
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
                <span class="page-title-admin">Employee Signature</span>
            </div>
            <div class="row mt-3 mb-4">
                <div class="col-sm-6 mx-auto">
                    <div class="card shadow mt-4">
                        <div class="card-header card-7 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light" id="process_division_title">Signature List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="addSignatureModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Signature</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="employee_signature_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_admin">
                                        <tr>
                                            <th>Employee Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_admin">
                                        <tr>
                                            <th>Employee Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Add Section End ==================== -->
                </div>
            </div>
            <!-- =============== Add Section Modal =============== -->
            <div class="modal fade" id="addSignatureModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light">ADD SIGNATURE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1">
                                <div class="col" id="employee_sign_division"></div>
                            </div>
                            <div class="form-floating mt-3">
                                <select class="form-select fw-bold" id="sign_department" onclick="loadEmployee();">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder fs-15" for="sign_department">Department</label>
                            </div>
                            <div class="form-floating mt-3">
                                <select class="form-select fw-bold" id="sign_employee">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder fs-15" for="sign_employee">Employee</label>
                            </div>
                            <div class="form-floating mt-3">
                                <input type="file" class="form-control fw-bold" id="sign_signature">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder fs-15 mb-1" for="sign_signature">Employee Signature</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary col btnEditSignature" onclick="editSignature();"><i class="fa-solid fa-pen-to-square p-r-8"></i>Edit</button>
                            <button type="button" class="btn btn-success col btnUpdateSignature" onclick="updateSignature(this);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveSignature" onclick="saveSignature();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Section Modal End =============== -->
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
    let prevIndexDepartment = '';

    loadSignatureTable();

    function loadSignatureTable() {
        var employee_signature_table = $('#employee_signature_table').DataTable({
            'lengthMenu': [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/employee_signature_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_employee_signature_table'
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
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '25%',
                orderable: false
            }]
        });
        setInterval(function() {
            employee_signature_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadDepartment() {
        $.ajax({
            url: 'functions/employee_signature_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_department'
            },
            success: function(result) {
                $('#sign_department').html(result);
            }
        });
    }

    function loadEmployee() {
        let currIndex = document.getElementById('sign_department').selectedIndex;
        let currVal = document.getElementById('sign_department').options;

        if (currIndex > 0) {
            if (prevIndexDepartment != currIndex) { //* ======= Toggle same Selection =======
                let dept_code = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/employee_signature_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'load_employee',
                        dept_code: dept_code
                    },
                    success: function(result) {
                        $('#sign_employee').html(result);
                    }
                });
                prevIndexDepartment = currIndex;
            } else {
                prevIndexDepartment = '';
            }
        }
    }

    function addSignatureModal() {
        $('#addSignatureModal').modal('show');
        $('.btnEditSignature').prop('disabled', true).css('display', 'none');
        $('.btnUpdateSignature').prop('disabled', true).css('display', 'none');
        $('.btnSaveSignature').prop('disabled', false).css('display', 'block');
        $('#employee_sign_division').html('<img src="../vendor/images/blank-profile-picture.png" id="employee_signature_image">');
        loadDepartment();
    }

    function saveSignature() {
        if (submitValidation()) {
            var sign_employee = document.getElementById('sign_employee').value;
            var dept_code = document.getElementById('sign_department').value;
            let employee_signature_image = $('#employee_signature_image').attr('value'); //* ======= image container =======
            //* ======= Validate if File Uploaded is Image =======
            let image_property = document.getElementById('sign_signature').files[0]; //* ======= input file =======
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
                $('#sign_signature').focus();
            } else {
                $.ajax({
                    url: 'functions/employee_signature_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'save_employee_signature',
                        sign_employee: sign_employee,
                        dept_code: dept_code,
                        employee_signature_image: employee_signature_image
                    },
                    success: function(result) {
                        if (result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'info',
                                title: 'Employee Signature Already Exist.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Employee Signature Added.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('#employee_signature_table').DataTable().ajax.reload(null, false);
                            clearValues();
                        }
                    }
                });
            }

        }
    }

    function btnPreviewSignature(empsignatureid) {
        $('#addSignatureModal').modal('show');
        $('.btnEditSignature').prop('disabled', false).css('display', 'block');
        $('.btnUpdateSignature').prop('disabled', true).css('display', 'none');
        $('.btnSaveSignature').prop('disabled', true).css('display', 'none');
        $('.btnUpdateSignature').val(empsignatureid);
        loadDepartment();
        addInputDisabled();
        $.ajax({
            url: 'functions/employee_signature_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_employee_signature',
                empsignatureid: empsignatureid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#sign_department').val(result.dept_code);
                }, 200);
                setTimeout(function() {
                    loadEmployee();
                }, 400);
                setTimeout(function() {
                    $('#sign_employee').val(result.emp_name);
                }, 800);
                $('#employee_sign_division').html(result.employee_signature)
            }
        });
    }

    function editSignature() {
        $('.btnEditSignature').prop('disabled', true).css('display', 'none');
        $('.btnUpdateSignature').prop('disabled', false).css('display', 'block');
        $('.btnSaveSignature').prop('disabled', true).css('display', 'none');
        removeInputDisabled();
    }

    function updateSignature(val) {
        if (submitValidation()) {
            var empsignatureid = val.value;
            var sign_employee = document.getElementById('sign_employee').value;
            var dept_code = document.getElementById('sign_department').value;
            var employee_signature_image = $('#employee_signature_image').attr('value'); //* ======= image container =======
            //* ======= Validate if File Uploaded is Image =======
            let image_property = document.getElementById('sign_signature').files[0]; //* ======= input file =======
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
                $('#sign_signature').focus();
            } else {
                $.ajax({
                    url: 'functions/employee_signature_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'update_employee_signature',
                        sign_employee: sign_employee,
                        dept_code: dept_code,
                        employee_signature_image: employee_signature_image,
                        empsignatureid: empsignatureid
                    },
                    success: function(result) {
                        if (result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'info',
                                title: 'Employee Signature Already Exist.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Employee Signature Updated.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('#employee_signature_table').DataTable().ajax.reload(null, false);
                            addInputDisabled();
                        }
                    }
                });
            }
        }
    }

    function btnDeleteSignature(empsignatureid) {
        $.ajax({
            url: 'functions/employee_signature_module_functions.php',
            type: 'POST',
            data: {
                action: 'delete_employee_signature',
                empsignatureid: empsignatureid
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Employee Signature Deleted.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#employee_signature_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    $('#sign_signature').on('change', function() {
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
                    $('#employee_sign_division').html('<img src="' + e.target.result + '" value="' + result + '" id="employee_signature_image">');
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('#sign_department').change(function() {
        if ($(this).val() == '') {
            showFieldError('sign_department', 'Department must not be blank.');
        } else {
            clearFieldError('sign_department');
        }
    });
    $('#sign_employee').change(function() {
        if ($(this).val() == '') {
            showFieldError('sign_employee', 'Employee must not be blank.');
        } else {
            clearFieldError('sign_employee');
        }
    });
    $('#sign_signature').change(function() {
        if ($(this).val() == '') {
            showFieldError('sign_signature', 'Signature must not be blank.');
        } else {
            clearFieldError('sign_signature');
        }
    });

    function submitValidation() {
        let isValidated = true;
        var sign_department = document.getElementById('sign_department').value;
        var sign_employee = document.getElementById('sign_employee').value;
        var sign_signature = document.getElementById('sign_signature').value;

        if (sign_department.length == 0) {
            showFieldError('sign_department', 'Department must not be blank.');
            if (isValidated) {
                $('#sign_department').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('sign_department');
        }

        if (sign_employee.length == 0) {
            showFieldError('sign_employee', 'Employee must not be blank.');
            if (isValidated) {
                $('#sign_employee').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('sign_employee');
        }

        if (sign_signature.length == 0) {
            showFieldError('sign_signature', 'Signature must not be blank.');
            if (isValidated) {
                $('#sign_signature').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('sign_signature');
        }
        return isValidated;
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

    function addInputDisabled() {
        $('#sign_department').prop('disabled', true);
        $('#sign_employee').prop('disabled', true);
        $('#sign_signature').prop('disabled', true);
    }

    function removeInputDisabled() {
        $('#sign_department').prop('disabled', false);
        $('#sign_employee').prop('disabled', false);
        $('#sign_signature').prop('disabled', false);
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('.btnEditSignature').prop('disabled', true).css('display', 'none');
        $('.btnUpdateSignature').prop('disabled', true).css('display', 'none');
        $('.btnSaveSignature').prop('disabled', false).css('display', 'block');
        $('#employee_sign_division').html('<img src="../vendor/images/blank-profile-picture.png" id="employee_signature_image">');
        clearAttributes();
        removeInputDisabled();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>