<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
// * Check if module is within the application
session_start();
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
                <span class="page-title-perso">Assign DR#</span>
            </div>
            <div class="row mt-5">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Assigned DR# List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" data-bs-toggle="modal" data-bs-target="#drAddModal"><i class="fa-solid fa-square-plus p-r-8"></i> Add DR#</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="assigned_dr_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th style="text-align:center;">D.R Number</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th style="text-align:center;">Signed</th>
                                            <th style="text-align:center;">Received By</th>
                                            <th style="text-align:center;">Date Received</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th style="text-align:center;">D.R Number</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th style="text-align:center;">Signed</th>
                                            <th style="text-align:center;">Received By</th>
                                            <th style="text-align:center;">Date Received</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Assigned DR List Section End =============== -->

            <!-- =============== Add DR Number Modal =============== -->
            <div class="modal fade" id="drAddModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">ADD DR NUMBER</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type='text' class="form-control fw-bold text-center" id="dr_add_total_no" placeholder="Quantity">
                                <div class="invalid-feedback"></div>
                                <label for="dr_add_total_no" class="fw-bold">Quantity</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-2 px-3">
                            <button type="button" class="btn btn-success btnSaveAddDr" onclick="saveAddDr();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
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
<?php
include './../helper/perso_announcement.php';
include './../includes/footer.php';
?>
<script>
    var access_level = '<?php echo $_SESSION['access_lvl']; ?>';
    var empno = '<?php echo $_SESSION['empno']; ?>';

    loadAssignedDrListTable();
    fetchUpdateDr();

    function loadAssignedDrListTable() {
        var assigned_dr_table = $('#assigned_dr_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_assigned_dr_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_assigned_dr_table_data',
                    empno: empno
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
            },
            'columnDefs': [{
                    targets: [0, 1, 7],
                    className: 'dt-body-middle-center',
                    width: '8%'
                }, {
                    targets: [2, 3, 4],
                    className: 'dt-body-middle-left',
                    width: '12%'
                },
                {
                    targets: 5,
                    className: 'dt-body-middle-center',
                    width: '4%'
                }, {
                    targets: 6,
                    className: 'dt-body-middle-center',
                    width: '8%'
                }
            ]
        });
        setInterval(function() {
            assigned_dr_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    $('#dr_add_total_no').keyup(function() {
        if (event.which >= 37 && event.which <= 40) { // =========== skip for arrow keys ===========
            event.preventDefault();
        }
        $(this).val(function(index, value) {
            return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });

    function fetchUpdateDr() {
        $.ajax({
            url: 'functions/perso_assigned_dr_module_functions.php',
            type: 'POST',
            data: {
                action: 'fetch_dr_update_from_live'
            }
        });
    }

    function saveAddDr() {
        if (submitValidation()) {
            let dr_add_total_no = document.getElementById('dr_add_total_no').value;

            $.ajax({
                url: 'functions/perso_assigned_dr_module_functions.php',
                type: 'POST',
                data: {
                    action: 'add-dr-number',
                    drnumberqty: dr_add_total_no
                },
                success: function(result) {
                    clearValues();
                    $('#drAddModal').modal('hide');
                }
            });
        }
    }

    function signedDr(drassignid) {
        $.ajax({
            url: 'functions/perso_assigned_dr_module_functions.php',
            type: 'POST',
            data: {
                action: 'signed_dr',
                drassignid: drassignid
            },
            success: function(result) {
                $('#assigned_dr_table').DataTable().ajax.reload(null, false);
            }
        });
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

    function submitValidation() {
        let isValidated = true;
        let dr_add_total_no = document.getElementById('dr_add_total_no').value;

        if (dr_add_total_no.length == 0) {
            showFieldError('dr_add_total_no', 'DR Quantity must not be blank');
            if (isValidated) {
                $('#dr_add_total_no').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('dr_add_total_no');
        }
        return isValidated;
    }

    function clearValues() {
        $('#dr_add_total_no').val('');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>