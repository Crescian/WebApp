<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWebLive->prepare($queryCheckApp);
$stmtCheckApp->execute();
while ($chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC)) {
    $chkAppId = $chkAppIdRow['app_id'];
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
            <!-- content section -->
            <div class="row">
                <span class="page-title-perso">Authorize Courier</span>
            </div>
            <div class="row mt-5">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <h4 class="m-0 fw-bold text-light">Expected Courier List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="expectedCourierList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Expected Date</th>
                                            <th>Company</th>
                                            <th>Courier</th>
                                            <th style="text-align:center;">Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Expected Date</th>
                                            <th>Company</th>
                                            <th>Courier</th>
                                            <th style="text-align:center;">Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Expected Courier List Section End =============== -->
            <div class="row mt-5">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Authorize Courier List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="authorizeCourierEntryModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Authorize Entry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="authorizeCourierList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Company</th>
                                            <th>Courier</th>
                                            <th>Authorize Person</th>
                                            <th style="text-align:center;">Designation</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Company</th>
                                            <th>Courier</th>
                                            <th>Authorize Person</th>
                                            <th style="text-align:center;">Designation</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Authorize Courier List Section End =============== -->
            <!-- =============== Authorize Courier Entry Modal =============== -->
            <div class="modal fade" id="authorizeCourierEntryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">AUTHORIZE COURIER ENTRY</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col" id="authorize_courier_division"></div>
                            </div>
                            <div class="form-floating mb-2">
                                <select id="authorize_company_name" class="form-select fw-bold" onclick="loadCourier();"></select>
                                <div class="invalid-feedback"></div>
                                <label for="authorize_company_name" class="fw-bolder">Company Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select id="authorize_courier" class="form-select fw-bold" onclick="loadEmployee();">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="authorize_courier" class="fw-bolder">Courier</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select id="authorize_employee" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="authorize_employee" class="fw-bolder">Authorize Employee</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select id="authorize_job_position" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                    <option value="Representative">Representative</option>
                                    <option value="Aide">Aide</option>
                                    <option value="Driver">Driver</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="authorize_job_position" class="fw-bolder">Designation</label>
                            </div>
                            <div class="form-floating">
                                <input type="file" id="authorize_courier_image" name="authorize_courier_image" class="form-control fw-bold">
                                <div class="invalid-feedback"></div>
                                <label for="authorize_courier_image" class="fw-bolder">Authorize Picture</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-sm-11 mx-auto mb-2">
                            <button type="button" class="btn btn-success col-sm btnSaveCourier" onclick="saveCourier();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Authorize Courier Entry Modal End =============== -->
            <!-- =============== Authorize Courier Info Modal =============== -->
            <div class="modal fade" id="authorizeCourierInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-header">
                            <h4 class="modal-title text-uppercase fw-bold text-primary">AUTHORIZE COURIER INFORMATION</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearValues('authorizeCourierInfo');"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col" id="authorize_courier_division_info"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="authorize_company_name_info" class="col-form-label fw-bolder">Company Name</label>
                                    <input type="text" id="authorize_company_name_info" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="authorize_courier_info" class="col-form-label fw-bolder">Courier</label>
                                    <input type="text" id="authorize_courier_info" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="authorize_employee_info" class="col-form-label fw-bolder">Authorize Employee</label>
                                    <input type="text" id="authorize_employee_info" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="authorize_job_position_info" class="col-form-label fw-bolder">Designation</label>
                                    <input type="text" id="authorize_job_position_info" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger col col-sm fs-19" data-bs-dismiss="modal" onclick="clearValues('authorizeCourierInfo');"><i class="fa fa-times-circle p-r-8 shake"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Authorize Courier Info Modal End =============== -->
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
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    loadExpectedCourierTable();
    loadAuthorizeCourierTable();
    let prevIndexCompany = '';
    let prevIndexCourier = '';

    function loadExpectedCourierTable() {
        var expectedCourierList_table = $('#expectedCourierList_table').DataTable({
            'serverSide': true,
            // 'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_authorize_courier_functions.php',
                type: 'POST',
                data: {
                    action: 'load_expected_courier_list'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
            },
            'columnDefs': [{
                    targets: 0,
                    className: 'dt-body-middle-center',
                    width: '8%'
                }, {
                    targets: 1,
                    className: 'dt-body-middle-left',
                    width: '30%'
                },
                {
                    targets: 2,
                    className: 'dt-body-middle-left',
                    width: '30%'
                },
                {
                    targets: 3,
                    className: 'dt-body-middle-center',
                    width: '5%',
                    orderable: false
                }
            ]
        });
        setInterval(function() {
            expectedCourierList_table.ajax.reload(null, false);
        }, 10000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAuthorizeCourierTable() {
        var authorizeCourierList_table = $('#authorizeCourierList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_authorize_courier_functions.php',
                type: 'POST',
                data: {
                    action: 'load_authorize_courier_list'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
            },
            'columnDefs': [{
                    targets: 0,
                    className: 'dt-body-middle-left'
                }, {
                    targets: 1,
                    className: 'dt-body-middle-left',
                    width: '30%'
                },
                {
                    targets: 2,
                    className: 'dt-body-middle-left',
                    width: '30%'
                },
                {
                    targets: 3,
                    className: 'dt-body-middle-center',
                    width: '10%'
                },
                {
                    targets: 4,
                    className: 'dt-nowrap-center',
                    width: '10%',
                    orderable: false
                }
            ]
        });
        setInterval(function() {
            authorizeCourierList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function authorizeCourierEntryModal() {
        $('#authorizeCourierEntryModal').modal('show');
        $('#authorize_courier_division').html('<img src="../vendor/images/blank-profile-picture.png" alt="" id="authorize_courier_image_container" class="img-thumbnail rounded">');
        loadCompany();
    }

    function loadCompany() {
        $.ajax({
            url: 'functions/perso_authorize_courier_functions.php',
            type: 'POST',
            data: {
                action: 'load_company_name'
            },
            success: function(result) {
                $('#authorize_company_name').html(result);
            }
        });
    }

    function loadCourier() {
        let currIndex = document.getElementById('authorize_company_name').selectedIndex;
        let currVal = document.getElementById('authorize_company_name').options;

        if (prevIndexCompany != currIndex) { //* ======= Toggle same Selection =======
            let companyname = currVal[currIndex].value;
            $.ajax({
                url: 'functions/perso_authorize_courier_functions.php',
                type: 'POST',
                data: {
                    action: 'load_courier',
                    companyname: companyname
                },
                success: function(result) {
                    $('#authorize_courier').html(result);
                }
            });
            prevIndexCompany = currIndex;
        } else {
            prevIndexCompany = '';
        }
    }

    function loadEmployee() {
        let companyname = document.getElementById('authorize_company_name').value;
        let currIndex = document.getElementById('authorize_courier').selectedIndex;
        let currVal = document.getElementById('authorize_courier').options;

        if (prevIndexCourier != currIndex) { //* ======= Toggle same Selection =======
            var courier = currVal[currIndex].value;
            $.ajax({
                url: 'functions/perso_authorize_courier_functions.php',
                type: 'POST',
                data: {
                    action: 'load_employee',
                    courier: courier,
                    companyname: companyname
                },
                success: function(result) {
                    $('#authorize_employee').html(result);
                }
            });
            prevIndexCourier = currIndex;
        } else {
            prevIndexCourier = '';
        }
    }

    function saveCourier() {
        if (submitValidation()) {
            let authorize_company_name = document.getElementById('authorize_company_name').value;
            let authorize_courier = document.getElementById('authorize_courier').value;
            let authorize_employee = document.getElementById('authorize_employee').value;
            let authorize_job_position = document.getElementById('authorize_job_position').value;
            let authorize_courier_image = $('#authorize_courier_image_container').attr('value'); //* ======= image container =======

            //* ======= Validate if File Uploaded is Image =======
            let image_property = document.getElementById('authorize_courier_image').files[0]; //* ======= input file =======
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
                $('#authorize_courier_image').addClass('is-invalid').removeClass('is-valid');
            } else {
                $.ajax({
                    url: 'functions/perso_authorize_courier_functions.php',
                    type: 'POST',
                    data: {
                        action: 'save_authorize_courier',
                        authorize_company_name: authorize_company_name,
                        authorize_courier: authorize_courier,
                        authorize_employee: authorize_employee,
                        authorize_job_position: authorize_job_position,
                        authorize_courier_image: authorize_courier_image
                    },
                    success: function(result) {
                        if (result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'info',
                                title: 'Courier Already Exist.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Successfully Save.',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            clearValues();
                            $('#authorizeCourierEntryModal').modal('hide');
                            $('#authorizeCourierList_table').DataTable().ajax.reload(null, false);
                        }
                    }
                });
            }
        }
    }

    function authorizeCourierInfo(authorizeid) {
        $('#authorizeCourierInfoModal').modal('show');
        $.ajax({
            url: 'functions/perso_authorize_courier_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'load_authorize_courier_info',
                authorizeid: authorizeid
            },
            success: function(result) {
                $('#authorize_company_name_info').val(result.company_name);
                $('#authorize_courier_division_info').html(result.authorize_image);
                $('#authorize_courier_info').val(result.courier);
                $('#authorize_job_position_info').val(result.authorize_job_position);
                $('#authorize_employee_info').val(result.authorize_fullname);
            }
        });
    }

    function authorizeCourierDelete(authorizeid) {
        $.ajax({
            url: 'functions/perso_authorize_courier_functions.php',
            type: 'POST',
            data: {
                action: 'delete_authorize_courier',
                authorizeid: authorizeid
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Successfully Removed.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#authorizeCourierList_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    $('#authorize_courier_image').on('change', function() {
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
                    $('#authorize_courier_division').html('<img src="' + e.target.result + '" alt="" value="' + result + '" id="authorize_courier_image_container" class="img-thumbnail rounded">');
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
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

    function submitValidation() {
        let isValidated = true;
        let authorize_company_name = document.getElementById('authorize_company_name').value;
        let authorize_courier = document.getElementById('authorize_courier').value;
        let authorize_employee = document.getElementById('authorize_employee').value;
        let authorize_job_position = document.getElementById('authorize_job_position').value;
        let authorize_courier_image = document.getElementById('authorize_courier_image').value;

        if (authorize_company_name.length == 0) {
            showFieldError('authorize_company_name', 'Company Name must not be blank.');
            if (isValidated) {
                $('#authorize_company_name').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('authorize_company_name');
        }

        if (authorize_courier.length == 0) {
            showFieldError('authorize_courier', 'Courier must not be blank.');
            if (isValidated) {
                $('#authorize_courier').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('authorize_courier');
        }

        if (authorize_employee.length == 0) {
            showFieldError('authorize_employee', 'Authorize Employee must not be blank.');
            if (isValidated) {
                $('#authorize_employee').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('authorize_employee');
        }

        if (authorize_job_position.length == 0) {
            showFieldError('authorize_job_position', 'Authorize Designation must not be blank.');
            if (isValidated) {
                $('#authorize_job_position').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('authorize_job_position');
        }

        if (authorize_courier_image.length == 0) {
            showFieldError('authorize_courier_image', 'Please select image to upload.');
            if (isValidated) {
                $('#authorize_courier_image').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('authorize_courier_image');
        }
        return isValidated;
    }

    //* ======= On Change Validation =======
    $('#authorize_company_name').change(function() {
        if ($(this).val() == '') {
            showFieldError('authorize_company_name', 'Company must not be blank.');
        } else {
            clearFieldError('authorize_company_name');
        }
    });
    $('#authorize_courier').change(function() {
        if ($(this).val() == '') {
            showFieldError('authorize_courier', 'Authorize Courier must not be blank.');
        } else {
            clearFieldError('authorize_courier');
        }
    });
    $('#authorize_employee').change(function() {
        if ($(this).val() == '') {
            showFieldError('authorize_employee', 'Authorize Employee must not be blank.');
        } else {
            clearFieldError('authorize_employee');
        }
    });
    $('#authorize_job_position').change(function() {
        if ($(this).val() == '') {
            showFieldError('authorize_job_position', 'Authorize Designation must not be blank.');
        } else {
            clearFieldError('authorize_job_position');
        }
    });
    $('#authorize_courier_image').change(function() {
        if ($(this).val() == '') {
            showFieldError('authorize_courier_image', 'Please select image to upload.');
        } else {
            clearFieldError('authorize_courier_image');
        }
    });

    function clearValues() {
        $('select').find('option:first').prop('selected', 'selected');
        $('#authorize_courier_division').html('<img src="../vendor/images/blank-profile-picture.png" alt="" id="authorize_courier_image_container" class="img-thumbnail rounded">');
        clearAttributes();
    }

    function clearAttributes() {
        $('select').removeClass('is-invalid is-valid');
        $('input').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>