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
    /* =========== Change Scrollbar Style - Justine 02112023 =========== */
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
                <span class="page-title-physical">Surveillance Log Sheet</span>
            </div>
            <div class="row row-cols-1 row-cols-md-2 mt-4">
                <!-- Time Synchronization Monitoring Log Sheet -->
                <div class="col mb-3">
                    <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="timeSynchronizationModal();">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Time Synchronization Monitoring Log Sheet</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-clock fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Surveillance Event Monitoring Log Sheet -->
                <div class="col mb-3">
                    <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="surveillanceEventModal();">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Surveillance Event Monitoring Log Sheet</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-calendar-day fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- ==================== Card End ==================== -->
            <div class="row mt-3 mb-4">
                <div class="col-sm mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light">Time Synchronization Monitoring List</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="timeSyncList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Time Synchronization</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Time Synchronization</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Time Synchronization Monitoring List End ==================== -->
                </div>
                <div class="col-sm">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <h4 class="fw-bold text-light">Surveillance Event Monitoring List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="eventMonitoringList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Event Monitoring</th>
                                            <th style="text-align:center;">Reviewed By</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Event Monitoring</th>
                                            <th style="text-align:center;">Reviewed By</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Surveillance Event Monitoring List End ==================== -->
                </div>
            </div><!-- ==================== Table Section End ==================== -->
            <!-- =============== Time Synchronization Modal =============== -->
            <div class="modal fade" id="timeSynchronizationModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">TIME SYNCHRONIZATION MONITORING LOG SHEET</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control fw-bold" id="timesync_ref_no" disabled>
                            <div class="table-responsive mb-2">
                                <table id="timeSynchronization_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Surveillance No.</th>
                                            <th style="text-align:center;">Real Time</th>
                                            <th style="text-align:center;">Actual Time</th>
                                            <th style="text-align:center;">Time Gap</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timeSynchronization_table_body"></tbody>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Surveillance Name</th>
                                            <th style="text-align:center;">Real Time</th>
                                            <th style="text-align:center;">Actual Time</th>
                                            <th style="text-align:center;">Time Gap</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold" id="surveillance_perform_by" disabled>
                                        <div class="invalid-feedback"></div>
                                        <label for="surveillance_perform_by" class="fw-bold">Prepared By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="perform_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mt-2">
                                        <select class="form-select fw-bold" id="surveillance_checked_by" onchange="loadJobPosition(this.value,'checked_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="surveillance_checked_by" class="fw-bold">Checked By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="checked_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mt-2">
                                        <select class="form-select fw-bold" id="surveillance_noted_by" onchange="loadJobPosition(this.value,'noted_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="surveillance_noted_by" class="fw-bold">Noted By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="noted_job_pos"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success" onclick="saveTimeSynchMonitoring();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Time Synchronization Modal End =============== -->
            <!-- =============== Surveillance Event Monitoring Modal =============== -->
            <div class="modal fade" id="surveillanceEventModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xxl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">SURVEILLANCE EVENT MONITORING LOG SHEET</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control fw-bold" id="event_ref_no" disabled>
                            <div class="table-responsive mb-2">
                                <table id="eventMonitoring_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th style="text-align:center;">Seq#</th>
                                            <th>Surveillance Description</th>
                                            <th style="text-align:center;">Time Start</th>
                                            <th style="text-align:center;">Time End</th>
                                            <th style="text-align:center;">From</th>
                                            <th style="text-align:center;">To</th>
                                            <th style="text-align:center;">Total No. of Days</th>
                                            <th style="text-align:center;">Min. Required No. Days</th>
                                            <th style="text-align:center;">Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody id="eventMonitoring_table_body"></tbody>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th style="text-align:center;">Seq#</th>
                                            <th>Surveillance Description</th>
                                            <th style="text-align:center;">Time Start</th>
                                            <th style="text-align:center;">Time End</th>
                                            <th style="text-align:center;">From</th>
                                            <th style="text-align:center;">To</th>
                                            <th style="text-align:center;">Total No. of Days</th>
                                            <th style="text-align:center;">Min. Required No. Days</th>
                                            <th style="text-align:center;">Comments</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold" id="event_reviewed_by" disabled>
                                        <div class="invalid-feedback"></div>
                                        <label for="event_reviewed_by" class="fw-bold">Reviewed By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="event_reviewed_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mt-2">
                                        <select class="form-select fw-bold" id="event_noted_by" onchange="loadJobPosition(this.value,'event_noted_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="event_noted_by" class="fw-bold">Noted By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="event_noted_job_pos"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success" onclick="saveEventMonitoring();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Surveillance Event Monitoring Modal =============== -->
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
include './../helper/select_values.php'; ?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var logged_user_access = '<?php echo $_SESSION['access_lvl']; ?>';
    loadTimeSyncListTable();
    loadEventMonitoringListTable();

    function surveillanceEventModal() {
        $('#surveillanceEventModal').modal('show');
        $('#event_reviewed_by').val(logged_user);
        loadJobPosition(logged_user, 'event_reviewed_job_pos');
        loadEventMonitoringTable();
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'event_noted_by', 'physical_security');
        generateSurveillanceRefno('phd_event_monitoring_refno', 'event_ref_no', 'event_ref_no');
    }

    function loadTimeSyncListTable() {
        var timeSyncList_table = $('#timeSyncList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_time_sync_list_table'
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
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="previewTimeSync('${data}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="removeTimeSync('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        timeSyncList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            timeSyncList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadEventMonitoringListTable() {
        var eventMonitoringList_table = $('#eventMonitoringList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_event_monitoring_list_table'
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
                width: '5%'
            }]
        });
        eventMonitoringList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            eventMonitoringList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadCms() {
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'surveillance_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'paging_noted_by', 'physical_security');
    }

    function generateSurveillanceRefno(inTable, inField, inObject) {
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'generate_surveillance_refno',
                inTable: inTable,
                inField: inField
            },
            success: function(result) {
                $('#' + inObject).val(result.surveillance_ref_no);
            }
        });
    }

    function timeSynchronizationModal() {
        loadCms();
        $('#timeSynchronizationModal').modal('show');
        $('#surveillance_perform_by').val(logged_user);
        loadJobPosition(logged_user, 'perform_job_pos');
        loadTimeSynchronizationTable();
        // loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'surveillance_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'surveillance_noted_by', 'physical_security');
        generateSurveillanceRefno('phd_time_sync_log_refno', 'timesyncrefno', 'timesync_ref_no');
    }

    function saveTimeSynchMonitoring() {
        if (submitValidation('timeSync')) {
            var perform_by = document.getElementById('surveillance_perform_by').value;
            var checked_by = document.getElementById('surveillance_checked_by').value;
            var noted_by = document.getElementById('surveillance_noted_by').value;
            var timesync_ref_no = document.getElementById('timesync_ref_no').value;
            let arraySurveillanceName = [];
            let arrayRealTime = [];
            let arrayActualTime = [];
            let arrayTimeGap = [];
            let arrayRemarks = [];
            $.ajax({
                url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_timeSync_header',
                    perform_by: perform_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    timesync_ref_no: timesync_ref_no
                },
                success: function(timesyncheader_id) {
                    //* ======== Save Surveillance Details ========
                    $('.surveillance_name').each(function() {
                        var str_surveillance_name = $(this).val();
                        arraySurveillanceName.push([str_surveillance_name]);
                    });
                    $('.surveillance_real_time').each(function() {
                        var realTime = $(this).val();
                        arrayRealTime.push([realTime]);
                    });
                    $('.surveillance_actual_time').each(function() {
                        var actualTime = $(this).val();
                        arrayActualTime.push([actualTime]);
                    });
                    $('.surveillance_time_gap').each(function() {
                        var timeGap = $(this).val();
                        arrayTimeGap.push([timeGap]);
                    });
                    $('.surveillance_remarks').each(function() {
                        var str_remarks = $(this).val();
                        arrayRemarks.push([str_remarks]);
                    });

                    for (let i = 0; i < arraySurveillanceName.length; i++) {
                        var strSurveillanceName = arraySurveillanceName[i];
                        var surveillance_name = strSurveillanceName.toString();

                        var strRealTime = arrayRealTime[i];
                        var real_time = strRealTime.toString();

                        var strActualTime = arrayActualTime[i];
                        var actual_time = strActualTime.toString();

                        var strTimeGap = arrayTimeGap[i];
                        var time_gap = strTimeGap.toString();

                        var strRemarks = arrayRemarks[i];
                        var remarks = strRemarks.toString();

                        $.ajax({
                            url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_timeSync_details',
                                timesyncheader_id: timesyncheader_id,
                                surveillance_name: surveillance_name,
                                real_time: real_time,
                                actual_time: actual_time,
                                time_gap: time_gap,
                                remarks: remarks,
                                timesync_ref_no: timesync_ref_no
                            }
                        });
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
                    $('#timeSynchronizationModal').modal('hide');
                }
            });
        }
    }

    function previewTimeSync(timesyncheaderid) {
        strLink = "time_synchronization_monitoring_log_sheet_pdf.php?d=" + timesyncheaderid;
        window.open(strLink, '_blank');
    }

    function removeTimeSync(timesyncheaderid) {
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
                    url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_timesync',
                        timesyncheaderid: timesyncheaderid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Report deleted.',
                            'success'
                        );
                    }
                });
            }
        });
    }

    function loadTimeSynchronizationTable() {
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_time_synchronization_table_body'
            },
            success: function(result) {
                let html = '';
                var surveillanceCount = 0;
                $.each(result, (key, row) => {
                    surveillanceCount++;
                    html += `<tr>
                        <td style="width:25%;"><input type="text" name="surveillance_name[]" class="form-control fw-bold surveillance_name" value="` + row.surveillance_name + `" disabled></td>
                        <td><input type="time" step="1" name="surveillance_real_time[]" class="form-control fw-bold surveillance_real_time" id="surveillance_real_time` + surveillanceCount + `" onchange="getTimeGap('` + surveillanceCount + `');"></td>
                        <td><input type="time" step="1" name="surveillance_actual_time[]" class="form-control fw-bold surveillance_actual_time" id="surveillance_actual_time` + surveillanceCount + `" onchange="getTimeGap('` + surveillanceCount + `');"></td>
                        <td><input type="text" name="surveillance_time_gap[]" class="form-control fw-bold text-center surveillance_time_gap" id="surveillance_time_gap` + surveillanceCount + `" disabled></td>
                        <td><input type="text" name="surveillance_remarks[]" class="form-control fw-bold surveillance_remarks" placeholder="Remarks..."></td>
                        </tr>`;
                });
                $('#timeSynchronization_table_body').append(html);
            }
        });
    }

    function loadJobPosition(employee, posObject) {
        if (employee == '') {
            $('#' + posObject).html('');
        } else {
            $.ajax({
                url: '../functions/common_functions.php',
                type: 'POST',
                data: {
                    action: 'load_job_pos_name_employee',
                    employee: employee
                },
                success: function(pos_name) {
                    $('#' + posObject).html(pos_name);
                }
            });
        }
    }

    function getTimeGap(count) {
        var timeGap = '';
        var real_time = document.getElementById('surveillance_real_time' + count).value;
        var actual_time = document.getElementById('surveillance_actual_time' + count).value;
        var startTime = moment(real_time, "HH:mm:ss");
        var endTime = moment(actual_time, "HH:mm:ss");
        var duration = moment.duration(endTime.diff(startTime));
        var hours = parseInt(duration.asHours());
        var minutes = parseInt(duration.asMinutes()) - hours * 60;
        var seconds = parseInt(duration.asSeconds()) - minutes * 60;
        // TODO fix error value when theres nothing to compare
        timeGap = seconds + ' sec.';
        $('#surveillance_time_gap' + count).val(timeGap);
    }

    function surveillanceEventModal() {
        $('#surveillanceEventModal').modal('show');
        $('#event_reviewed_by').val(logged_user);
        loadJobPosition(logged_user, 'event_reviewed_job_pos');
        loadEventMonitoringTable();
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'event_noted_by', 'physical_security');
        generateSurveillanceRefno('phd_event_monitoring_refno', 'event_ref_no', 'event_ref_no');
    }

    function loadEventMonitoringTable() {
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_event_monitoring_table_body'
            },
            success: function(result) {
                console.log(result);
                let html = '';
                var eventCount = 0;
                var rowCount = 0;
                $.each(result, (key, row) => {
                    eventCount++;
                    html += `<tr>
                        <td style="width:4%;text-align:center;vertical-align:middle;">` + eventCount + `</td>
                        <td style="display:none;"><input type="text" name="event_surveillance_name[]" class="form-control fw-bold event_surveillance_name" value="` + row.surveillance_name + `" disabled></td>
                        <td style="width:15%;vertical-align:middle;">` + row.surveillance_name + `</td>
                        <td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_start[]" class="form-control fw-bold event_time_start" id="event_time_start` + rowCount + `"></td>
                        <td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_end[]" class="form-control fw-bold event_time_end" id="event_time_end` + rowCount + `"></td>
                        <td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_from[]" class="form-control fw-bold event_date_from" id="event_date_from` + rowCount + `" onchange="getEventDateDiff('` + rowCount + `');"></td>
                        <td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_to[]" class="form-control fw-bold event_date_to" id="event_date_to` + rowCount + `" onchange="getEventDateDiff('` + rowCount + `');"></td>
                        <td style="width:10%;vertical-align:middle;"><input type="number" name="event_no_days[]" class="form-control fw-bold text-center event_no_days" id="event_no_days` + rowCount + `"></td>
                        <td style="display:none;"><input type="text" name="event_min_days[]" class="form-control fw-bold text-center event_min_days" value="90" disabled></td>
                        <td style="width:6%;text-align:center;vertical-align:middle;">90</td>
                        <td style="vertical-align:middle;"><input type="text" name="event_comments[]" class="form-control fw-bold text-center event_comments" id="event_comments` + rowCount + `"></td>
                        </tr>`;
                    rowCount++;
                });
                $('#eventMonitoring_table_body').append(html);
            }
        });
    }

    function getEventDateDiff(eventCount) {
        var event_date_from = moment(document.getElementById('event_date_from' + eventCount).value, "YYYY-MM-DD");
        var event_date_to = moment(document.getElementById('event_date_to' + eventCount).value, "YYYY-MM-DD");
        //* Difference in number of days
        $('#event_no_days' + eventCount).val(event_date_to.diff(event_date_from, 'days') + 1);
    }

    function saveEventMonitoring() {
        if (submitValidation('eventMonitoring')) {
            var reviewed_by = document.getElementById('event_reviewed_by').value;
            var noted_by = document.getElementById('event_noted_by').value;
            var event_ref_no = document.getElementById('event_ref_no').value;
            let arraySurveillanceName = [];
            let arrayStartTime = [];
            let arrayEndTime = [];
            let arrayDateFrom = [];
            let arrayDateTo = [];
            let arrayTotalDays = [];
            let arrayMinDays = [];
            let arrayComments = [];

            $.ajax({
                url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_event_header',
                    reviewed_by: reviewed_by,
                    noted_by: noted_by,
                    event_ref_no: event_ref_no
                },
                success: function(eventheader_id) {
                    //* ======== Save Event Monitoring Details ========
                    $('.event_surveillance_name').each(function() {
                        var str_surveillance_name = $(this).val();
                        arraySurveillanceName.push([str_surveillance_name]);
                    });
                    $('.event_time_start').each(function() {
                        var startTime = $(this).val();
                        arrayStartTime.push([startTime]);
                    });
                    $('.event_time_end').each(function() {
                        var endTime = $(this).val();
                        arrayEndTime.push([endTime]);
                    });
                    $('.event_date_from').each(function() {
                        var dateFrom = $(this).val();
                        arrayDateFrom.push([dateFrom]);
                    });
                    $('.event_date_to').each(function() {
                        var dateTo = $(this).val();
                        arrayDateTo.push([dateTo]);
                    });
                    $('.event_no_days').each(function() {
                        var totalDays = $(this).val();
                        arrayTotalDays.push([totalDays]);
                    });
                    $('.event_min_days').each(function() {
                        var minDays = $(this).val();
                        arrayMinDays.push([minDays]);
                    });
                    $('.event_comments').each(function() {
                        var str_comments = $(this).val();
                        arrayComments.push([str_comments]);
                    });

                    for (let i = 0; i < arraySurveillanceName.length; i++) {
                        var strSurveillanceName = arraySurveillanceName[i];
                        var surveillance_name = strSurveillanceName.toString();

                        var strTimeStart = arrayStartTime[i];
                        var event_time_start = strTimeStart.toString();

                        var strEndTime = arrayEndTime[i];
                        var event_time_end = strEndTime.toString();

                        var strDateFrom = arrayDateFrom[i];
                        var event_date_from = strDateFrom.toString();

                        var strDateTo = arrayDateTo[i];
                        var event_date_to = strDateTo.toString();

                        var strTotalDays = arrayTotalDays[i];
                        var event_total_days = strTotalDays.toString();

                        var strMinDays = arrayMinDays[i];
                        var event_min_days = strMinDays.toString();

                        var strComments = arrayComments[i];
                        var event_comments = strComments.toString();

                        $.ajax({
                            url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_event_details',
                                eventheader_id: eventheader_id,
                                surveillance_name: surveillance_name,
                                event_time_start: event_time_start,
                                event_time_end: event_time_end,
                                event_date_from: event_date_from,
                                event_date_to: event_date_to,
                                event_total_days: event_total_days,
                                event_min_days: event_min_days,
                                event_ref_no: event_ref_no,
                                event_comments: event_comments
                            }
                        });
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
                    $('#surveillanceEventModal').modal('hide');
                }
            });
        }
    }

    function previewEventReport(eventheaderid) {
        strLink = "surveillance_event_monitoring_log_sheet_pdf.php?d=" + eventheaderid;
        window.open(strLink, '_blank');
    }

    function removeEventReport(eventheaderid) {
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
                    url: '../controller/phd_controller/phd_surveillance_log_sheet_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_event_monitoring',
                        eventheaderid: eventheaderid
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

    $('#surveillance_checked_by').change(function() {
        if ($(this).val() == '') {
            showFieldError('surveillance_checked_by', 'Checked by must not be blank');
        } else {
            clearFieldError('surveillance_checked_by');
        }
    });
    $('#surveillance_noted_by').change(function() {
        if ($(this).val() == '') {
            showFieldError('surveillance_noted_by', 'Noted by must not be blank');
        } else {
            clearFieldError('surveillance_noted_by');
        }
    });
    $('#event_noted_by').change(function() {
        if ($(this).val() == '') {
            showFieldError('event_noted_by', 'Noted by must not be blank');
        } else {
            clearFieldError('event_noted_by');
        }
    });

    function refreshProcessTable() {
        $('#timeSyncList_table').DataTable().ajax.reload(null, false);
        $('#eventMonitoringList_table').DataTable().ajax.reload(null, false);
    }

    function submitValidation(category) {
        var isValidated = true;
        if (category == 'timeSync') {
            var surveillance_checked_by = document.getElementById('surveillance_checked_by').value;
            var surveillance_noted_by = document.getElementById('surveillance_noted_by').value;

            if (surveillance_checked_by.length == 0) {
                showFieldError('surveillance_checked_by', 'Checked by must not be blank');
                if (isValidated) {
                    $('#surveillance_checked_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('surveillance_checked_by');
            }

            if (surveillance_noted_by.length == 0) {
                showFieldError('surveillance_noted_by', 'Noted by must not be blank');
                if (isValidated) {
                    $('#surveillance_noted_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('surveillance_noted_by');
            }
            return isValidated;
        } else {
            var event_noted_by = document.getElementById('event_noted_by').value;

            if (event_noted_by.length == 0) {
                showFieldError('event_noted_by', 'Noted by must not be blank');
                if (isValidated) {
                    $('#event_noted_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('event_noted_by');
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

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        $('#perform_job_pos').html('');
        $('#checked_job_pos').html('');
        $('#noted_job_pos').html('');
        $('#event_reviewed_job_pos').html('');
        $('#event_noted_job_pos').html('');
        arraySurveillanceName = [];
        arrayRealTime = [];
        arrayActualTime = [];
        arrayTimeGap = [];
        arrayRemarks = [];
        $('#eventMonitoring_table_body').html('');
        $('#timeSynchronization_table_body').html('');
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>