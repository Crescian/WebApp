<?php include './../includes/header.php';
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

// * Check if module is within the application
session_start();
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
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #291af5;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-production">Process Module</span>
            </div>
            <div class="row mt-5">
                <div class="col-sm-7 mx-auto">
                    <div class="card shadow">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Process List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addProcessModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Process</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="processList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th style="text-align:center;">Process Name</th>
                                            <th style="text-align:center;">Section Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
                                        <tr>
                                            <th style="text-align:center;">Process Name</th>
                                            <th style="text-align:center;">Section Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Process List Section End =============== -->
            <!-- =============== AddUpdate Process Modal =============== -->
            <div class="modal fade" id="addUpdateProcessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="process_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="process_name">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Process Name</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select fw-bold" id="process_section">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="process_section" class="fw-bold">Process Section</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnUpdateProcess" onclick="updateProcess(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveProcess" onclick="saveProcess();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div> <!-- =============== AddUpdate Process Modal End =============== -->


            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-8 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">MANUFACTURING</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    loadProcessListTable();

    function loadProcessListTable() {
        var processList_table = $('#processList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/prod_process_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_process_list_table'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* =========== Hide tooltip every table draw ===========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            },
            'columnDefs': [{
                targets: [0, 1],
                className: 'dt-body-middle-left'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '15%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditProcess" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Process" onclick="modifyProcess(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteProcess" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Process" onclick="deleteProcess(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        setInterval(function() {
            processList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addProcessModal() {
        $('#addUpdateProcessModal').modal('show');
        loadSelectValueWithId('prod_section_name', 'sectionid', 'section_name', 'process_section', 'production');
        $('#process_modal_title').html('PROCESS ENTRY');
        $('.btnSaveProcess').prop('disabled', false).css('display', 'block');
        $('.btnUpdateProcess').prop('disabled', true).css('display', 'none');
    }

    function saveProcess() {
        if (submitValidation()) {
            var process_name = document.getElementById('process_name').value;
            var process_section = document.getElementById('process_section').value;

            $.ajax({
                url: 'functions/prod_process_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_process',
                    process_name: process_name,
                    section_id: process_section,
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Process Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#process_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Process Name Successfully Saved.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#processList_table').DataTable().ajax.reload(null, false);
                        $('input').val('');
                        clearAttributes();
                    }
                }
            });
        }
    }

    function modifyProcess(processid) {
        $('#addUpdateProcessModal').modal('show');
        loadSelectValueWithId('prod_section_name', 'sectionid', 'section_name', 'process_section', 'production');
        $('#process_modal_title').html('PROCESS UPDATE');
        $('.btnSaveProcess').prop('disabled', true).css('display', 'none');
        $('.btnUpdateProcess').prop('disabled', false).css('display', 'block').val(processid);

        $.ajax({
            url: 'functions/prod_process_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_process_info',
                processid: processid
            },
            success: function(result) {
                $('#process_name').val(result.process_name);
                setTimeout(function() {
                    $('#process_section').val(result.process_section);
                }, 300);
            }
        });
    }

    function updateProcess(processid) {
        if (submitValidation()) {
            var process_name = document.getElementById('process_name').value;
            var process_section = document.getElementById('process_section').value;

            $.ajax({
                url: 'functions/prod_process_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_process',
                    process_name: process_name,
                    section_id: process_section,
                    processid: processid
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Process Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#process_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Process Name Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#processList_table').DataTable().ajax.reload(null, false);
                        clearAttributes();
                    }
                }
            });

        }
    }

    function deleteProcess(processid) {
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
                    url: 'functions/prod_process_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'remove_process',
                        processid: processid
                    },
                    success: function(result) {
                        $('#processList_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Process deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function submitValidation() {
        var isValidated = true;
        var process_name = document.getElementById('process_name').value;
        var process_section = document.getElementById('process_section').value;

        if (process_name.length == 0) {
            showFieldError('process_name', 'Process Name must not be blank');
            if (isValidated) {
                $('#process_name').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('process_name');
        }

        if (process_section.length == 0) {
            showFieldError('process_section', 'Process Section must not be blank');
            if (isValidated) {
                $('#process_section').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('process_section');
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
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function clearAttributes() {
        $('input[type=text]').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>