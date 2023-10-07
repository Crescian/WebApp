<?php
include './../includes/header.php';
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
    /* =========== Change Scrollbar Style - Justine 02162023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #FF7A00;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-physical">Paging System Monitoring Checklist</span>
            </div>
            <div class="row mt-5 mb-4">
                <div class="col-sm mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-10 col-md-10">
                                    <h4 class="fw-bold text-light">Paging Monitoring List</h4>
                                </div>
                                <div class="col-sm col-md">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="loadPagingMonitoring();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Monitoring</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="pagingMonitoringList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th style="vertical-align:middle;">Monitoring Header</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Noted By</th>
                                            <th style="text-align: center;vertical-align:middle;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th style="vertical-align:middle;">Monitoring Header</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Noted By</th>
                                            <th style="text-align: center;vertical-align:middle;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Paging System Monitoring List End ==================== -->
                </div>
            </div><!-- ==================== Paging System Monitoring Table End ==================== -->
            <!-- =============== Paging System Modal =============== -->
            <div class="modal fade" id="loadPagingSystemModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-xl-down" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">PAGING SYSTEM MONITORING CHECKLIST</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="pagingCheckList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th rowspan="2" style="text-align: center;vertical-align:middle;">QR</th>
                                                <th rowspan="2" style="text-align: center;vertical-align:middle;">Paging Components/Location</th>
                                                <th colspan="2" style="width:30%;text-align: center;vertical-align:middle;">Status</th>
                                                <th rowspan="2" style="width:32%;text-align: center;vertical-align:middle;">Remarks</th>
                                            </tr>
                                            <tr>
                                                <th style="width:15%;text-align: center;vertical-align:middle;">Ok</th>
                                                <th style="width:15%;text-align: center;vertical-align:middle;">Defective</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pagingChecklist_table_body"></tbody>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th rowspan="2" style="text-align: center;vertical-align:middle;">QR</th>
                                                <th rowspan="2" style="text-align: center;vertical-align:middle;">Paging Components/Location</th>
                                                <th style="width:15%;text-align: center;vertical-align:middle;">Ok</th>
                                                <th style="width:15%;text-align: center;vertical-align:middle;">Defective</th>
                                                <th rowspan="2" style="width:32%;text-align: center;vertical-align:middle;">Remarks</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="width:30%;text-align: center;vertical-align:middle;">Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" class="form-control fw-bold" id="pagingcount">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="paging_performed_by" disabled></input>
                                        <label for="paging_performed_by" class="fw-bold">Prepared By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="paging_performed_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="paging_checked_by" onchange="loadJobPosition(this.value,'paging_checked_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="paging_checked_by" class="fw-bold">Checked by</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="paging_checked_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="paging_noted_by" onchange="loadJobPosition(this.value,'paging_noted_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="paging_noted_by" class="fw-bold">Noted by</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="paging_noted_job_pos"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnUpdatePaging" onclick="updatePaging(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success btnSavePaging" onclick="savePaging();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Paging System Modal End ===============-->
            <!-- =============== Paging System Modal =============== -->
            <div class="modal fade" id="qr_scannerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-black justify-content-center">
                            <h4 class="modal-title text-uppercase fw-bold text-light">SCAN QR-CODE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3" style="padding: 0px; width: 100%; max-height: 300px; overflow:hidden; border: 1px solid gray">
                                <video id="video" style="width: 100%;"></video>
                            </div>
                            <div id="sourceSelectPanel" style="display:none">
                                <div class="form-floating mb-2">
                                    <select class="form-select fw-bold" id="sourceSelect"></select>
                                    <label for="paging_noted_by" class="fw-bold">Change video source:</label>
                                </div>
                            </div>
                            <!-- <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="result">
                                <label for="paging_noted_by" class="fw-bold">Result:</label>
                            </div> -->
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-secondary col" data-bs-dismiss="modal" id="closeModal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
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
<?php include './../includes/footer.php';
include './../helper/select_values.php';
include './../helper/phd_scan_qr.php'; ?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var logged_user_access = '<?php echo $_SESSION['access_lvl']; ?>';
    var pagingcount = 0;
    let arrayPagingCategoryName = [];
    let arrayPagingLocationName = [];
    let arrayPagingStatusOk = [];
    let arrayPagingStatusDefective = [];
    let arrayPagingRemarks = [];

    loadPagingMonitoringTable();

    function loadPagingMonitoringTable() {
        var pagingMonitoringList_table = $('#pagingMonitoringList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_paging_system_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_paging_monitoring_list_table'
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
                className: 'dt-body-middle-left'
            }, {
                targets: [1, 2, 3, 4, 5],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 6,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="previewPagingReport('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="editPagingReport('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deletePagingReport('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        pagingMonitoringList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            pagingMonitoringList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadPagingMonitoring() {
        $('#loadPagingSystemModal').modal('show');
        $('#paging_checked_by').prop('disabled', false);
        $('#paging_noted_by').prop('disabled', false);
        $('#paging_performed_by').val(logged_user);
        loadJobPosition(logged_user, 'paging_performed_job_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'paging_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'paging_noted_by', 'physical_security');
        $('#pagingcount').val('0');
        $('.btnSavePaging').prop('disabled', false);
        $('.btnSavePaging').css('display', 'block');
        $('.btnUpdatePaging').prop('disabled', true);
        $('.btnUpdatePaging').css('display', 'none');

        $.ajax({
            url: '../controller/phd_controller/phd_paging_system_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_paging_checklist'
            },
            success: function(result) {
                let html = '';
                var cat_count = 0;
                var row_count = 0;
                $.each(result, function(categoryname, category_value) {
                    html += `<tr>
                        <td colspan="5" style="vertical-align:middle;background-color: #D6EEEE;">` + categoryname + `</td>
                        <td style="text-align:center; display:none;" colspan="4"><input type="text" name="paging_category_name[]" class="form-control fw-bold paging_category_name" id="paging_category_name" value="` + categoryname + `" disabled></td>`;
                    $.each(category_value, function(category_value_index, category_details) {
                        html += `<tr>
                                    <td style="width:5%;vertical-align:middle;"><button type="button" class="btn col-sm-12 btn-dark btnScanQr" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + row_count + `','` + category_details.location_name + `');"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                                    <td style="vertical-align:middle;">` + category_details.location_name + `</td>
                                    <td style="vertical-align:middle; display:none;"><input type="text" name="paging_location_name` + cat_count + `[]" class="form-control fw-bold paging_location_name` + cat_count + `" id="paging_location_name` + row_count + `" value="` + category_details.location_name + `" disabled></td>
                                    <td style="width:15%;vertical-align:middle;"><select name="paging_status_ok` + cat_count + `[]" class="form-select fw-bold paging_status_ok` + cat_count + `" id="paging_status_ok` + row_count + `" onchange="status_change(` + row_count + `,'status_ok',this.value);" disabled><option value="0"></option><option value="1">✔</option><option value="2">✖</option><option value="3">N/A</option></select></td>
                                    <td style="width:15%;vertical-align:middle;"><select name="paging_status_defective` + cat_count + `[]" class="form-select fw-bold paging_status_defective` + cat_count + `" id="paging_status_defective` + row_count + `" onchange="status_change(` + row_count + `,'status_defect',this.value);" disabled><option value="0"></option><option value="1">✔</option><option value="2">✖</option><option value="3">N/A</option></select></td>
                                    <td style="width:32%;vertical-align:middle;"><input type="text" name="paging_remarks` + cat_count + `[]" class="form-control text-center fw-bold paging_remarks` + cat_count + `" id="paging_remarks` + row_count + `" disabled></td>
                                </tr>`;
                        row_count++;
                    });
                    html += `</tr>`;
                    cat_count++;
                });
                $('#pagingChecklist_table_body').append(html);
            }
        });
    }

    function savePaging() {
        if (submitValidation()) {
            var prepared_by = document.getElementById('paging_performed_by').value;
            var checked_by = document.getElementById('paging_checked_by').value;
            var noted_by = document.getElementById('paging_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_paging_system_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_paging_monitoring_header',
                    prepared_by: prepared_by,
                    checked_by: checked_by,
                    noted_by: noted_by
                },
                success: function(result) {
                    //* ======== Save Paging Details ========
                    $('.paging_category_name').each(function() {
                        var str_paging_category_name = $(this).val();
                        arrayPagingCategoryName.push([str_paging_category_name]);
                    });

                    for (let i = 0; i < arrayPagingCategoryName.length; i++) {
                        var strPagingCategoryName = arrayPagingCategoryName[i];
                        var paging_category_name = strPagingCategoryName.toString();

                        $('.paging_location_name' + i).each(function() {
                            var str_paging_location_name = $(this).val();
                            arrayPagingLocationName.push([str_paging_location_name]);
                        });
                        $('.paging_status_ok' + i).each(function() {
                            var str_paging_status_ok = $(this).val();
                            arrayPagingStatusOk.push([str_paging_status_ok]);
                        });
                        $('.paging_status_defective' + i).each(function() {
                            var str_paging_status_defective = $(this).val();
                            arrayPagingStatusDefective.push([str_paging_status_defective]);
                        });
                        $('.paging_remarks' + i).each(function() {
                            var str_paging_remarks = $(this).val();
                            arrayPagingRemarks.push([str_paging_remarks]);
                        });
                        for (let j = 0; j < arrayPagingLocationName.length; j++) {
                            var strPagingLocationName = arrayPagingLocationName[j];
                            var paging_location_name = strPagingLocationName.toString();

                            var strPagingStatusOk = arrayPagingStatusOk[j];
                            var paging_status_ok = strPagingStatusOk.toString();

                            var strPagingStatusDefective = arrayPagingStatusDefective[j];
                            var paging_status_defective = strPagingStatusDefective.toString();

                            var strPagingRemarks = arrayPagingRemarks[j];
                            var paging_remarks = strPagingRemarks.toString();

                            $.ajax({
                                url: '../controller/phd_controller/phd_paging_system_contr.class.php',
                                type: 'POST',
                                data: {
                                    action: 'save_paging_details',
                                    paging_category_name: paging_category_name,
                                    paging_location_name: paging_location_name,
                                    paging_status_ok: paging_status_ok,
                                    paging_status_defective: paging_status_defective,
                                    paging_remarks: paging_remarks,
                                    pagingheader_id: result.pagingheader_id,
                                    paging_ref_no: result.paging_ref_no,
                                    prepared_by: prepared_by
                                }
                            });
                        }

                        arrayPagingLocationName = [];
                        arrayPagingStatusOk = [];
                        arrayPagingStatusDefective = [];
                        arrayPagingRemarks = [];
                    }
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
                    $('#loadPagingSystemModal').modal('hide');
                }
            });
        }
    }

    function updatePaging(pagingheaderid) {
        if (submitValidation()) {
            var prepared_by = document.getElementById('paging_performed_by').value;
            var checked_by = document.getElementById('paging_checked_by').value;
            var noted_by = document.getElementById('paging_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_paging_system_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_paging_monitoring_header',
                    prepared_by: prepared_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    pagingheaderid: pagingheaderid
                },
                success: function(result) {
                    //* ======== Update Paging Details ========
                    $('.paging_category_name').each(function() {
                        var str_paging_category_name = $(this).val();
                        arrayPagingCategoryName.push([str_paging_category_name]);
                    });

                    for (let i = 0; i < arrayPagingCategoryName.length; i++) {
                        var strPagingCategoryName = arrayPagingCategoryName[i];
                        var paging_category_name = strPagingCategoryName.toString();

                        $('.paging_location_name' + i).each(function() {
                            var str_paging_location_name = $(this).val();
                            arrayPagingLocationName.push([str_paging_location_name]);
                        });
                        $('.paging_status_ok' + i).each(function() {
                            var str_paging_status_ok = $(this).val();
                            arrayPagingStatusOk.push([str_paging_status_ok]);
                        });
                        $('.paging_status_defective' + i).each(function() {
                            var str_paging_status_defective = $(this).val();
                            arrayPagingStatusDefective.push([str_paging_status_defective]);
                        });
                        $('.paging_remarks' + i).each(function() {
                            var str_paging_remarks = $(this).val();
                            arrayPagingRemarks.push([str_paging_remarks]);
                        });
                        for (let j = 0; j < arrayPagingLocationName.length; j++) {
                            var strPagingLocationName = arrayPagingLocationName[j];
                            var paging_location_name = strPagingLocationName.toString();

                            var strPagingStatusOk = arrayPagingStatusOk[j];
                            var paging_status_ok = strPagingStatusOk.toString();

                            var strPagingStatusDefective = arrayPagingStatusDefective[j];
                            var paging_status_defective = strPagingStatusDefective.toString();

                            var strPagingRemarks = arrayPagingRemarks[j];
                            var paging_remarks = strPagingRemarks.toString();

                            $.ajax({
                                url: '../controller/phd_controller/phd_paging_system_contr.class.php',
                                type: 'POST',
                                data: {
                                    action: 'update_paging_details',
                                    paging_location_name: paging_location_name,
                                    paging_category_name: paging_category_name,
                                    paging_status_ok: paging_status_ok,
                                    paging_status_defective: paging_status_defective,
                                    paging_remarks: paging_remarks,
                                    prepared_by: prepared_by,
                                    pagingheaderid: pagingheaderid
                                }
                            });
                        }
                        arrayPagingLocationName = [];
                        arrayPagingStatusOk = [];
                        arrayPagingStatusDefective = [];
                        arrayPagingRemarks = [];
                    }
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
                    $('#loadPagingSystemModal').modal('hide');
                }
            });
        }
    }

    function setSelected(selectValue, rowValue) {
        let optionSelected = '';
        if (selectValue == rowValue) {
            optionSelected = 'selected';
        }
        return optionSelected;
    }

    function editPagingReport(pagingheaderid) {
        $('#paging_checked_by').prop('disabled', true);
        $('#paging_noted_by').prop('disabled', true);
        $('#loadPagingSystemModal').modal('show');
        $('#paging_performed_by').val(logged_user);
        loadJobPosition(logged_user, 'paging_performed_job_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'paging_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'paging_noted_by', 'physical_security');
        $('.btnUpdatePaging').val(pagingheaderid);
        $('.btnUpdatePaging').prop('disabled', false);
        $('.btnUpdatePaging').css('display', 'block');
        $('.btnSavePaging').prop('disabled', true);
        $('.btnSavePaging').css('display', 'none');
        $('#pagingcount').val('0');

        $.ajax({
            url: '../controller/phd_controller/phd_paging_system_contr.class.php',
            type: 'POST',
            dataType: ' JSON',
            cache: false,
            data: {
                action: 'preview_employee_header',
                pagingheaderid: pagingheaderid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#paging_checked_by').val(result.checked_by);
                    $('#paging_noted_by').val(result.noted_by);
                    loadJobPosition(result.checked_by, 'paging_checked_job_pos');
                    loadJobPosition(result.noted_by, 'paging_noted_job_pos');
                }, 300);
            }
        });

        $.ajax({
            url: '../controller/phd_controller/phd_paging_system_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_paging_details',
                pagingheaderid: pagingheaderid
            },
            success: function(result) {
                let html = ``;
                var cat_count = 0;
                var row_count = 0;
                $.each(result, function(tablecategory, category_name) {
                    html += `
                            <tr>
                            <td colspan="5" style="vertical-align:middle;background-color: #D6EEEE;">` + tablecategory + `</td>
                            <td style="text-align:center; display:none;" colspan="4"><input type="text" name="paging_category_name[]" class="form-control fw-bold paging_category_name" id="paging_category_name" value="` + tablecategory + `" disabled></td>`;
                    $.each(category_name, function(category_name, row) {
                        html += `<tr>`;
                        if (row.prepared_by != null) {
                            html += `<tr>`;
                            html += `<td style="width:5%;vertical-align:middle;"><button type="button" class="btn col-sm-12 btn-dark btnScanQr" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + row_count + `','` + row.paging_location_name + `');" disabled><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                                    <td style="vertical-align:middle;">` + row.paging_location_name + `</td>
                                    <td style="vertical-align:middle; display:none;"><input type="text" name="paging_location_name` + cat_count + `[]" class="form-control fw-bold paging_location_name` + cat_count + `" id="paging_location_name` + row_count + `" value="` + row.paging_location_name + `" disabled></td>
                                    <td style="width:15%;vertical-align:middle;">
                                    <select name="paging_status_ok` + cat_count + `[]" class="form-select fw-bold paging_status_ok` + cat_count + `" id="paging_status_ok` + row_count + `" onchange="status_change(` + row_count + `,'status_ok',this.value);" disabled>
                                    <option value="0" ${setSelected('0', row.paging_status_ok)}></option>
                                    <option value="1" ${setSelected('1', row.paging_status_ok)}>✔</option>
                                    <option value="2" ${setSelected('2', row.paging_status_ok)}>✖</option>
                                    <option value="3" ${setSelected('3', row.paging_status_ok)}>N/A</option>
                                    </select>
                                    </td>
                                    <td style="width:15%;vertical-align:middle;">
                                    <select name="paging_status_defective` + cat_count + `[]" class="form-select fw-bold paging_status_defective` + cat_count + `" id="paging_status_defective` + row_count + `" onchange="status_change(` + row_count + `,'status_defect',this.value);" disabled>                                   
                                    <option value="0" ${setSelected('0', row.paging_status_defective)}></option>
                                    <option value="1" ${setSelected('1', row.paging_status_defective)}>✔</option>
                                    <option value="2" ${setSelected('2', row.paging_status_defective)}>✖</option>
                                    <option value="3" ${setSelected('3', row.paging_status_defective)}>N/A</option>
                                    </select>
                                    </td>
                                    <td style="width:32%;vertical-align:middle;"><input type="text" name="paging_remarks` + cat_count + `[]" class="form-control text-center fw-bold paging_remarks` + cat_count + `" id="paging_remarks` + row_count + `" value="` + row.paging_remarks + `" disabled></td>
                                    `;
                        } else {
                            html += `<td style="width:5%;vertical-align:middle;"><button type="button" class="btn col-sm-12 btn-dark btnScanQr" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + row_count + `','` + row.paging_location_name + `');"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                                    <td style="vertical-align:middle;">` + row.paging_location_name + `</td>
                                    <td style="vertical-align:middle; display:none;"><input type="text" name="paging_location_name` + cat_count + `[]" class="form-control fw-bold paging_location_name` + cat_count + `" id="paging_location_name` + row_count + `" value="` + row.paging_location_name + `" disabled></td>
                                    <td style="width:15%;vertical-align:middle;"><select name="paging_status_ok` + cat_count + `[]" class="form-select fw-bold paging_status_ok` + cat_count + `" id="paging_status_ok` + row_count + `" onchange="status_change(` + row_count + `,'status_ok');" disabled><option value="0"></option><option value="1">✔</option><option value="2">✖</option><option value="3">N/A</option></select></td>
                                    <td style="width:15%;vertical-align:middle;"><select name="paging_status_defective` + cat_count + `[]" class="form-select fw-bold paging_status_defective` + cat_count + `" id="paging_status_defective` + row_count + `" onchange="status_change(` + row_count + `,'status_defect');" disabled><option value="0"></option><option value="1">✔</option><option value="2">✖</option><option value="3">N/A</option></select></td>
                                    <td style="width:32%;vertical-align:middle;"><input type="text" name="paging_remarks` + cat_count + `[]" class="form-control text-center fw-bold paging_remarks` + cat_count + `" id="paging_remarks` + row_count + `" disabled></td>
                                    `;
                        }
                        html += `</tr>`;
                        row_count++;
                    });
                    html += ` </tr>`;
                    cat_count++;
                });
                $('#pagingChecklist_table_body').html(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }


    function deletePagingReport(pagingheaderid) {
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
                    url: '../controller/phd_controller/phd_paging_system_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_paging_monitoring',
                        pagingheaderid: pagingheaderid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Report deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function validationQrScanner(count) {
        $('#paging_status_ok' + count).prop('disabled', false);
        $('#paging_status_defective' + count).prop('disabled', false);
        $('#paging_remarks' + count).prop('disabled', false);
    }

    function refreshProcessTable() {
        $('#pagingMonitoringList_table').DataTable().ajax.reload(null, false);
    }

    function previewPagingReport(pagingheaderid) {
        strLink = "paging_system_monitoring_checklist_pdf.php?d=" + pagingheaderid;
        window.open(strLink, '_blank');
    }

    function status_change(count, category, selectValue) {
        //TODO Fix Validation if all inputs have no selection
        if (category == "status_defect") {
            $('#paging_status_ok' + count).find('option:first').prop('selected', 'selected');
        } else {
            $('#paging_status_defective' + count).find('option:first').prop('selected', 'selected');
        }
        if (document.getElementById('paging_status_ok' + count).value == 0 && document.getElementById('paging_status_defective' + count).value == 0) {
            $('#paging_remarks' + count).removeClass('is-invalid is-valid');
            pagingcount--;
        }

        if (selectValue == 0) {
            pagingcount--;
        } else {
            pagingcount++;
        }
        $('#pagingcount').val(pagingcount);
    }

    function submitValidation() {
        var isValidated = true;
        var paging_checked_by = document.getElementById('paging_checked_by').value;
        var paging_noted_by = document.getElementById('paging_noted_by').value;
        var pagingcount = document.getElementById('pagingcount').value;
        let arrayPagingCategoryName = [];
        let arrayPagingLocationName = [];
        let arrayPagingStatusOk = [];
        let arrayPagingStatusDefective = [];
        let arrayPagingRemarks = [];

        $('.paging_category_name').each(function() {
            var str_paging_category_name = $(this).val();
            arrayPagingCategoryName.push([str_paging_category_name]);
        });

        for (let i = 0; i < arrayPagingCategoryName.length; i++) {
            var strPagingCategoryName = arrayPagingCategoryName[i];
            var paging_category_name = strPagingCategoryName.toString();

            $('.paging_location_name' + i).each(function() {
                var str_paging_location_name = $(this).val();
                arrayPagingLocationName.push([str_paging_location_name]);
            });
            $('.paging_status_ok' + i).each(function() {
                var str_paging_status_ok = $(this).val();
                arrayPagingStatusOk.push([str_paging_status_ok]);
            });
            $('.paging_status_defective' + i).each(function() {
                var str_paging_status_defective = $(this).val();
                arrayPagingStatusDefective.push([str_paging_status_defective]);
            });
            $('.paging_remarks' + i).each(function() {
                var str_paging_remarks = $(this).val();
                arrayPagingRemarks.push([str_paging_remarks]);
            });

            for (let j = 0; j < arrayPagingLocationName.length; j++) {
                var strPagingLocationName = arrayPagingLocationName[j];
                var paging_location_name = strPagingLocationName.toString();

                var strPagingStatusOk = arrayPagingStatusOk[j];
                var paging_status_ok = strPagingStatusOk.toString();

                var strPagingStatusDefective = arrayPagingStatusDefective[j];
                var paging_status_defective = strPagingStatusDefective.toString();

                var strPagingRemarks = arrayPagingRemarks[j];
                var paging_remarks = strPagingRemarks.toString();

                if (paging_status_ok > 0) {
                    if (paging_remarks.length == 0) {
                        $('#paging_remarks' + j).addClass('is-invalid').removeClass('is-valid');
                        isValidated = false;
                    }

                } else if (paging_status_defective > 0) {
                    if (paging_remarks.length == 0) {
                        $('#paging_remarks' + j).addClass('is-invalid').removeClass('is-valid');
                        isValidated = false;
                    }
                }
            }
        }
        if (pagingcount <= 0) {
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Please Fill Inputs',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            isValidated = false;
        }

        if (paging_checked_by.length == 0) {
            showFieldError('paging_checked_by', 'Checked by must not be blank');
            isValidated = false;
        } else {
            clearFieldError('paging_checked_by');
        }

        if (paging_noted_by.length == 0) {
            showFieldError('paging_noted_by', 'Noted by must not be blank');
            isValidated = false;
        } else {
            clearFieldError('paging_noted_by');
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
        // $('input').prop('disabled', true);
        // $('select').prop('disabled', true);
        clearAttributes();
        $('#paging_performed_job_pos').html('');
        $('#paging_checked_job_pos').html('');
        $('#paging_noted_job_pos').html('');
        arrayPagingCategoryName = [];
        arrayPagingLocationName = [];
        arrayPagingStatusOk = [];
        arrayPagingStatusDefective = [];
        arrayPagingRemarks = [];
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>