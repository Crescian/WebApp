<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-physical">Authorized Module</span>
            </div>
            <div class="row mt-4">
                <div class="col mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Checked By List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addCheckedNotedByModal('checkedby');"><i class="fa-solid fa-square-plus p-r-8"></i> Add Employee</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="checkedByList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Employee Name</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Employee Name</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Time Synchronization Monitoring List End ==================== -->
                </div>
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Noted By List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addCheckedNotedByModal('notedby');"><i class="fa-solid fa-square-plus p-r-8"></i> Add Employee</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="notedByList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Employee Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Employee Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Surveillance Event Monitoring List End ==================== -->
                </div>
            </div><!-- ==================== Table Section End ==================== -->
            <!-- =============== Checked/Noted By Entry Modal =============== -->
            <div class="modal fade" id="addCheckedNotedByModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="checked_noted_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2">
                                <select class="form-select fw-bold" id="employee_name"></select>
                                <div class="invalid-feedback"></div>
                                <label for="employee_name" class="fw-bold">Employee Name</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveCheckedNotedEntry" onclick="saveCheckedNotedEntry(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--===============Checked/Noted By Entry Modal===============-->

            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-2 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">PHYSICAL SECURITY</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    loadCheckedByTable();
    loadNotedByTable();

    function loadCheckedByTable() {
        var checkedByList_table = $('#checkedByList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_authorization_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_checked_by_table'
                },
                dataSrc: function(data) {
                    if (data == "") {
                        return [];
                    } else {
                        return data.data;
                    }
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '95%',
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-12 btn-danger btnRemoveCheckedBy" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove Employee" onclick="removeCheckedBy('${data}');"><i class="fa-solid fa-trash-can fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        checkedByList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            checkedByList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadNotedByTable() {
        var notedByList_table = $('#notedByList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_authorization_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_noted_by_table'
                },
                dataSrc: function(data) {
                    if (data == "") {
                        return [];
                    } else {
                        return data.data;
                    }
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '95%',
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-12 btn-danger btnRemoveNotedBy" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove Employee" onclick="removeNotedBy('${data}');"><i class="fa-solid fa-trash-can fa-shake"></i>`
                }
            }]
        });
        notedByList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            notedByList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addCheckedNotedByModal(category) {
        $('#addCheckedNotedByModal').modal('show');
        if (category == 'checkedby') {
            $('#checked_noted_title').html('Employee - Checked By');
        } else {
            $('#checked_noted_title').html('Employee - Noted By');
        }
        $('.btnSaveCheckedNotedEntry').val(category);
        load_employee();
    }

    function load_employee() {
        $.ajax({
            url: '../functions/common_functions.php',
            type: 'POST',
            data: {
                action: 'load_employee_per_department',
                dept_code: 'PHD'
            },
            success: function(result) {
                $('#employee_name').html(result);
            }
        });
    }

    function saveCheckedNotedEntry(category) {
        if (submitValidation()) {
            var employee_name = document.getElementById('employee_name').value;
            var inTable, inField;
            if (category == 'checkedby') {
                inTable = 'phd_authorized_checked_by';
                inField = 'checked_by_name';
            } else {
                inTable = 'phd_authorized_noted_by';
                inField = 'noted_by_name';
            }
            $.ajax({
                url: '../controller/phd_controller/phd_authorization_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_checked_noted_by_employee',
                    employee_name: employee_name,
                    inTable: inTable,
                    inField: inField
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Employee Already Exist.',
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
                        refreshProcessTable();
                    }
                }
            });
        }
    }

    function removeCheckedBy(phdcheckedbyid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to regress this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/phd_controller/phd_authorization_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'remove_checked_by_employee',
                        phdcheckedbyid: phdcheckedbyid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    function removeNotedBy(phdnotedbyid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to regress this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/phd_controller/phd_authorization_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'remove_noted_by_employee',
                        phdnotedbyid: phdnotedbyid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    function refreshProcessTable() {
        $('#checkedByList_table').DataTable().ajax.reload(null, false);
        $('#notedByList_table').DataTable().ajax.reload(null, false);
    }

    function submitValidation() {
        var isValidated = true;
        var employee_name = document.getElementById('employee_name').value;

        if (employee_name.length == 0) {
            showFieldError('employee_name', 'Employee Name must not be blank');
            if (isValidated) {
                $('#employee_name').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('employee_name');
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

    function clearValues() {
        $('select').val('');
        clearAttributes();
    }

    function clearAttributes() {
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>