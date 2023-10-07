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
                <span class="page-title-production">Machine Module</span>
            </div>
            <div class="row mt-5">
                <div class="col-sm-6 mx-auto">
                    <div class="card shadow">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Machine List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addMachineModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Add Machine</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="machineList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th style="text-align:center;">Machine Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
                                        <tr>
                                            <th style="text-align:center;">Machine Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Machine List Section End =============== -->
            <!-- =============== Add Machine Modal =============== -->
            <div class="modal fade" id="addUpdateMachineModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="machine_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="machine_name">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Machine Name</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveMachine" onclick="saveMachine();"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Save</button>
                            <button type="button" class="btn btn-success btnUpdateMachine" onclick="updateMachine(this.value);"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Update</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark fa-beat p-r-8" style="--fa-animation-duration: 2s;"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Machine Modal End =============== -->

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
    loadMachineListTable();

    function loadMachineListTable() {
        var machineList_table = $('#machineList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/prod_machine_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_machine_list_table'
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
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false
            }]
        });
        setInterval(function() {
            machineList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addMachineModal() {
        $('#addUpdateMachineModal').modal('show');
        $('.btnSaveMachine').prop('disabled', false).css('display', 'block');
        $('.btnUpdateMachine').prop('disabled', true).css('display', 'none');
        $('#machine_modal_title').html('MACHINE ENTRY');
    }

    function saveMachine() {
        if (submitValidation()) {
            var machine_name = document.getElementById('machine_name').value;

            $.ajax({
                url: 'functions/prod_machine_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_machine',
                    machine_name: machine_name
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Machine Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearAttributes();
                        $('#machine_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Machine Successfully Save.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearValues();
                        $('#machineList_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function modifyMachine(machineid) {
        $('#addUpdateMachineModal').modal('show');
        $('.btnSaveMachine').prop('disabled', true).css('display', 'none');
        $('.btnUpdateMachine').prop('disabled', false).css('display', 'block').val(machineid);
        $('#machine_modal_title').html('MACHINE UPDATE');

        $.ajax({
            url: 'functions/prod_machine_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_machine_information',
                machineid: machineid
            },
            success: function(result) {
                $('#machine_name').val(result.machine_name);
            }
        });
    }

    function updateMachine(machineid) {
        if (submitValidation()) {
            var machine_name = document.getElementById('machine_name').value;

            $.ajax({
                url: 'functions/prod_machine_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_machine',
                    machine_name: machine_name,
                    machineid: machineid
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Machine Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#machine_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Machine Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#machineList_table').DataTable().ajax.reload(null, false);
                    }
                    clearAttributes();
                }
            });
        }
    }

    function deleteMachine(machineid) {
        $.ajax({
            url: 'functions/prod_machine_module_functions.php',
            type: 'POST',
            data: {
                action: 'delete_machine_name',
                machineid: machineid
            },
            success: function(result) {
                $('#machineList_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function submitValidation() {
        var isValidated = true;
        var machine_name = document.getElementById('machine_name').value;

        if (machine_name.length == 0) {
            showFieldError('machine_name', 'Machine Name must not be blank');
            if (isValidated) {
                $('#machine_name').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('machine_name');
        }
        return isValidated;

    }

    function clearValues() {
        $('input').val('');
        clearAttributes();
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
        $('input[type=text]').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>