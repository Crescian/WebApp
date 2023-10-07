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
                <span class="page-title-physical">Time Synchronization Monitoring Log Sheet</span>
            </div>
            <div class="row mt-5 mb-4">
                <div class="col-sm mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light">Time Synchronization Monitoring List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="timeSynchronizationModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Synchronization Log Sheet</button>
                                    </div>
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
            </div>
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
                                            <th style="text-align:center;">ACS Time</th>
                                            <th style="text-align:center;">Surveillance Time</th>
                                            <th style="text-align:center;">Time Gap</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="timeSynchronization_table_body"></tbody>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Surveillance Name</th>
                                            <th style="text-align:center;">ACS Time</th>
                                            <th style="text-align:center;">Surveillance Time</th>
                                            <th style="text-align:center;">Time Gap</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <input type="hidden" class="form-control fw-bold" id="pagingcount">
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
                                        <input type="text" id="surveillance_checked_by_preview" class="form-control fw-bold" disabled>
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
                                        <input type="text" id="surveillance_checked_by_input" class="form-control fw-bold" disabled>
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
                            <button type="button" class="btn btn-warning btn-sm fw-bold btn-update" style="border-radius: 20px;" onclick="updateFunc(this.value);"><i class="fa-solid fa-pen-to-square fa-bounce p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success btn-save" onclick="saveTimeSynchMonitoring();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Time Synchronization Modal End =============== -->
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
include '../helper/select_values.php'; ?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var logged_user_access = '<?php echo $_SESSION['access_lvl']; ?>';
    let arraySurveillanceName = [];
    let arrayRealTime = [];
    let arrayActualTime = [];
    let arrayTimeGap = [];
    let arrayRemarks = [];
    let timeid = [];
    let prepared = [];
    loadTimeSyncListTable();
    var pagingcount = 0;

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
                url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
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
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="previewTimeSync('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="removeTimeSync('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
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

    function saveTimeSynchMonitoring() {
        if (submitValidation('save')) {
            var perform_by = document.getElementById('surveillance_perform_by').value;
            var checked_by = document.getElementById('surveillance_checked_by').value;
            var noted_by = document.getElementById('surveillance_noted_by').value;
            var timesync_ref_no = document.getElementById('timesync_ref_no').value;
            $.ajax({
                url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_timeSync_header',
                    perform_by: perform_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    timesync_ref_no: timesync_ref_no
                },
                success: function(timesyncheader_id) {
                    alert(timesyncheader_id);
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
                        if (timeGap == '') {
                            arrayTimeGap.push([null]);
                        } else {
                            arrayTimeGap.push([timeGap]);
                        }
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
                            url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_timeSync_details',
                                timesyncheader_id: timesyncheader_id,
                                surveillance_name: surveillance_name,
                                real_time: real_time,
                                actual_time: actual_time,
                                time_gap: time_gap,
                                remarks: remarks,
                                timesync_ref_no: timesync_ref_no,
                                logged_user: logged_user
                            },
                            success: function(result) {
                                console.log(result);
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

    function updateFunc(timesyncheaderid) {
        if (submitValidation('update')) {
            var timesync_ref_no = document.getElementById('timesync_ref_no').value;
            //* ======== Save Surveillance Details ========
            $('.prepare').each(function() {
                var str_prepare = $(this).val();
                prepared.push([str_prepare]);
            });
            $('.timesyncid').each(function() {
                var str_timesyncid = $(this).val();
                timeid.push([str_timesyncid]);
            });
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
                if (timeGap == '') {
                    arrayTimeGap.push([null]);
                } else {
                    arrayTimeGap.push([timeGap]);
                }
            });
            $('.surveillance_remarks').each(function() {
                var str_remarks = $(this).val();
                arrayRemarks.push([str_remarks]);
            });
            console.log(prepared);
            console.log(timeid);
            console.log(arraySurveillanceName);
            console.log(arrayRealTime);
            console.log(arrayActualTime);
            console.log(arrayTimeGap);
            console.log(arrayRemarks);
            for (let i = 0; i < arraySurveillanceName.length; i++) {

                var strPrepare = prepared[i];
                var prepare = strPrepare.toString();

                var strTimeiId = timeid[i];
                var time_id = strTimeiId.toString();

                var strRealTime = arrayRealTime[i];
                var real_time = strRealTime.toString();

                var strActualTime = arrayActualTime[i];
                var actual_time = strActualTime.toString();

                var strTimeGap = arrayTimeGap[i];
                var time_gap = strTimeGap.toString();

                var strRemarks = arrayRemarks[i];
                var remarks = strRemarks.toString();
                $.ajax({
                    url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'update_timeSync_details',
                        prepare: prepare,
                        logged_user: logged_user,
                        time_id: time_id,
                        real_time: real_time,
                        actual_time: actual_time,
                        time_gap: time_gap,
                        remarks: remarks,
                        timesync_ref_no: timesync_ref_no
                    },
                    success: function(result) {
                        // alert(result);
                        console.log(result);
                    }
                });
            }
            Swal.fire({
                position: 'top',
                icon: 'success',
                title: 'Successfully Update.',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            clearValues();
            refreshProcessTable();
            $('#timeSynchronizationModal').modal('hide');
        }
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
                    url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
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
                        )
                    }
                });
            }
        })
    }

    function refreshProcessTable() {
        $('#timeSyncList_table').DataTable().ajax.reload(null, false);
    }

    function timeSynchronizationModal() {
        $('#pagingcount').val('0');
        $('#surveillance_checked_by').css('display', 'block');
        $('#surveillance_checked_by_preview').css('display', 'none');
        $('.btn-update').css('display', 'none');
        $('.btn-save').css('display', 'block');
        $('#timeSynchronizationModal').modal('show');
        $('#surveillance_perform_by').val(logged_user);
        $('#surveillance_checked_by_input').css('display', 'none');
        $('#surveillance_noted_by').css('display', 'block');
        loadJobPosition(logged_user, 'perform_job_pos');
        loadTimeSynchronizationTable();
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'surveillance_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'surveillance_noted_by', 'physical_security');
        generateSurveillanceRefno('phd_time_sync_log_refno', 'timesyncrefno', 'timesync_ref_no');
    }

    function loadNotedBy(timesyncheaderid) {
        $.ajax({
            url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load-noted-by',
                timesyncheaderid: timesyncheaderid
            },
            success: function(result) {
                $('#surveillance_noted_by').css('display', 'none');
                $('#surveillance_checked_by_input').val(result.result);
                loadJobPosition(result.result, 'noted_job_pos')
            }
        })
    }

    function btnPreview(timesyncheaderid) {
        $('#pagingcount').val('0');
        $('#surveillance_checked_by').css('display', 'none');
        $('#surveillance_checked_by_preview').css('display', 'block');
        $('.btn-update').val(timesyncheaderid);
        $('#timeSynchronizationModal').modal('show');
        $('.btn-update').css('display', 'block');
        $('.btn-save').css('display', 'none');
        $('#surveillance_checked_by_input').css('display', 'block');
        $('#surveillance_perform_by').val(logged_user);
        loadNotedBy(timesyncheaderid);
        loadJobPosition(logged_user, 'perform_job_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'surveillance_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'surveillance_noted_by', 'physical_security');
        generateSurveillanceRefno('phd_time_sync_log_refno', 'timesyncrefno', 'timesync_ref_no');
        $.ajax({
            url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_checked_by',
                timesyncheaderid: timesyncheaderid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#surveillance_checked_by_preview').val(result.result);
                    loadJobPosition(result.result, 'checked_job_pos');
                }, 300);
            }
        });
        $.ajax({
            url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview',
                timesyncheaderid: timesyncheaderid
            },
            success: function(result) {
                let html = '';
                var surveillanceCount = 0;
                $.each(result, function(index, row) {
                    console.log(row.surveillance_no);
                    surveillanceCount++;
                    html += '<tr>';
                    html += '<td style="width:25%;"><input type="hidden" class="timesyncid" value="' + row.timesyncdetailsid + '"><input type="hidden" class="prepare" value="' + row.prepared_by + '"><input type="text" name="surveillance_name" class="form-control fw-bold surveillance_name" value="' + row.surveillance_no + '" disabled></td>';
                    if (row.real_time == null) {
                        html += '<td><input type="time" step="1" name="surveillance_real_time" class="form-control fw-bold surveillance_real_time" value="' + row.real_time + '" id="surveillance_real_time' + surveillanceCount + '" onchange="getTimeGap(' + surveillanceCount + ');"></td>';
                        html += '<td><input type="time" step="1" name="surveillance_actual_time" class="form-control fw-bold surveillance_actual_time" value="' + row.actual_time + '" id="surveillance_actual_time' + surveillanceCount + '" onchange="getTimeGap(' + surveillanceCount + ');" disabled></td>';
                    } else {
                        html += '<td><input class="form-control fw-bold surveillance_real_time" name="surveillance_real_time" value="' + row.real_time + '" disabled></td>';
                        html += '<td><input class="form-control fw-bold surveillance_actual_time" name="surveillance_actual_time" value="' + row.actual_time + '" disabled></td>';
                    }
                    html += '<td><input type="text" name="surveillance_time_gap" class="form-control fw-bold text-center surveillance_time_gap" value="' + row.time_gap + '" id="surveillance_time_gap' + surveillanceCount + '" disabled></td>';
                    if (row.remarks == '') {
                        html += '<td><input type="text" name="surveillance_remarks" id="surveillance_remarks' + surveillanceCount + '" class="form-control fw-bold surveillance_remarks" placeholder="Remarks..." onchange="status_change(this.value);" disabled></td>';
                    } else {
                        html += '<td><input type="text" name="surveillance_remarks" class="form-control fw-bold surveillance_remarks" value="' + row.remarks + '" placeholder="Remarks..." disabled></td>';
                    }
                    html += '</tr>';
                });
                $('#timeSynchronization_table_body').append(html);
            }
        });
    }

    function status_change(selectValue) {
        if (selectValue == 0 && selectValue.length == 0) {
            pagingcount--;
        } else {
            pagingcount++;
        }
        $('#pagingcount').val(pagingcount);
    }

    function loadTimeSynchronizationTable() {
        $.ajax({
            url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_time_synchronization_table_body'
            },
            success: function(result) {
                let html = '';
                var surveillanceCount = 0;
                $.each(result, function(index, row) {
                    surveillanceCount++;
                    html += '<tr>';
                    html += '<td style="width:25%;"><input type="text" name="surveillance_name" class="form-control fw-bold surveillance_name" value="' + row.surveillance_name + '" disabled></td>';
                    html += '<td><input type="time" step="1" name="surveillance_real_time" class="form-control fw-bold surveillance_real_time" id="surveillance_real_time' + surveillanceCount + '" onchange="getTimeGap(' + surveillanceCount + ');"></td>';
                    html += '<td><input type="time" step="1" name="surveillance_actual_time" class="form-control fw-bold surveillance_actual_time" id="surveillance_actual_time' + surveillanceCount + '" onchange="getTimeGap(' + surveillanceCount + ');" disabled></td>';
                    html += '<td><input type="text" name="surveillance_time_gap" class="form-control fw-bold text-center surveillance_time_gap" id="surveillance_time_gap' + surveillanceCount + '" disabled></td>';
                    html += '<td><input type="text" name="surveillance_remarks" id="surveillance_remarks' + surveillanceCount + '" class="form-control fw-bold surveillance_remarks" placeholder="Remarks..." onchange="status_change(this.value);" disabled>';
                    html += '<div class="invalid-feedback"></div>';
                    html += '</td>';
                    html += '</tr>';
                });
                $('#timeSynchronization_table_body').append(html);
            }
        });
    }

    function generateSurveillanceRefno(inTable, inField, inObject) {
        $.ajax({
            url: '../controller/phd_controller/phd_time_synchronization_log_sheet_contr.class.php',
            type: 'POST',
            data: {
                action: 'generate_surveillance_refno',
                inTable: inTable,
                inField: inField
            },
            success: function(result) {
                $('#' + inObject).val(result);
            }
        });
    }

    function previewTimeSync(timesyncheaderid) {
        strLink = "time_synchronization_monitoring_log_sheet_pdf.php?d=" + timesyncheaderid;
        window.open(strLink, '_blank');
    }

    function getTimeGap(count, value) {
        // alert(trueValue);
        // $('#surveillance_remarks' + count).prop('disabled', false);
        // $('#surveillance_actual_time' + count).prop('disabled', false);
        // var timeGap = '';
        // var real_time = document.getElementById('surveillance_real_time' + count).value;
        // var actual_time = document.getElementById('surveillance_actual_time' + count).value;
        // var startTime = moment(real_time, "HH:mm:ss");
        // var endTime = moment(actual_time, "HH:mm:ss");
        // var duration = moment.duration(endTime.diff(startTime));
        // var hours = parseInt(duration.asHours());
        // var minutes = parseInt(duration.asMinutes()) - hours * 60;
        // var seconds = parseInt(duration.asSeconds()) - minutes * 60;
        // // TODO fix error value when theres nothing to compare
        // timeGap = seconds + ' sec.';
        $('#surveillance_actual_time' + count).prop('disabled', false);
        $('#surveillance_real_time' + count).prop('disabled', true);
        $('#surveillance_remarks' + count).prop('disabled', false);
        var timeGap = '';
        var real_time = document.getElementById('surveillance_real_time' + count).value;
        var actual_time = document.getElementById('surveillance_actual_time' + count).value;
        var startTime = moment(real_time, "HH:mm:ss");
        var endTime = moment(actual_time, "HH:mm:ss");
        var duration = moment.duration(endTime.diff(startTime));
        var hours = parseInt(duration.asHours());
        var minutes = parseInt(duration.asMinutes()) - hours * 60;
        var seconds = parseInt(duration.asSeconds()) - minutes * 60;

        // if (startTime.isAfter(endTime)) {
        //     $('#surveillance_actual_time' + count).val('');
        //     Swal.fire({
        //         position: 'top',
        //         icon: 'error',
        //         title: 'ACS Time must be greater in Surveillance Time.',
        //         showConfirmButton: false,
        //         timer: 2000
        //     });
        // } else {
        //     timeGap = seconds + ' sec.';
        //     $('#surveillance_time_gap' + count).val(timeGap);
        // }

        timeGap = seconds + ' sec.';
        $('#surveillance_time_gap' + count).val(timeGap);
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

    function submitValidation(val) {
        var isValidated = true;
        var surveillance_checked_by = document.getElementById('surveillance_checked_by').value;
        var surveillance_noted_by = document.getElementById('surveillance_noted_by').value;
        if (val == 'save') {
            $('.surveillance_remarks').each(function() {
                var str_remarks = $(this).val();
                arrayRemarks.push([str_remarks]);
            });
            $('.surveillance_real_time').each(function() {
                var realTime = $(this).val();
                arrayRealTime.push([realTime]);
            });
            console.log(arrayRemarks);
            console.log(arrayRealTime);
            for (let i = 0; i < arrayRemarks.length; i++) {
                var strRemarksArray = arrayRemarks[i];
                var remarks = strRemarksArray.toString();
                var strRealTime = arrayRealTime[i];
                var real_time = strRealTime.toString();
                if (real_time != '') {
                    if (remarks == '') {
                        i++;
                        $('#surveillance_remarks' + i).addClass('is-invalid').removeClass('is-valid');
                        isValidated = false;
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
            arrayRemarks = [];
            arrayRealTime = [];
            return isValidated;
        } else {
            $('.surveillance_remarks').each(function() {
                var str_remarks = $(this).val();
                arrayRemarks.push([str_remarks]);
            });
            $('.surveillance_real_time').each(function() {
                var realTime = $(this).val();
                arrayRealTime.push([realTime]);
            });
            console.log(arrayRemarks);
            console.log(arrayRealTime);
            for (let i = 0; i < arrayRemarks.length; i++) {
                var strRemarksArray = arrayRemarks[i];
                var remarks = strRemarksArray.toString();
                var strRealTime = arrayRealTime[i];
                var real_time = strRealTime.toString();
                if (real_time != '') {
                    if (remarks == '') {
                        i++;
                        $('#surveillance_remarks' + i).addClass('is-invalid').removeClass('is-valid');
                        isValidated = false;
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
            arrayRemarks = [];
            arrayRealTime = [];
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
        $('#timeSynchronization_table_body').html('');
        $("input[name=surveillance_real_time]").val('');
        $("input[name=surveillance_actual_time]").val('');
        $("input[name=surveillance_time_gap]").val('');
        $("input[name=surveillance_remarks]").val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        $('#perform_job_pos').html('');
        $('#checked_job_pos').html('');
        $('#noted_job_pos').html('');
        arraySurveillanceName = [];
        arrayRealTime = [];
        arrayActualTime = [];
        arrayTimeGap = [];
        arrayRemarks = [];
        timeid = [];
        prepared = [];
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>