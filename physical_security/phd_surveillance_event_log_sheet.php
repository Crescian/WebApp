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
                <span class="page-title-physical">Surveillance Event Monitoring Log Sheet</span>
            </div>
            <div class="row mt-5 mb-4">
                <div class="col-sm">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light" id="process_division_title">Surveillance Event Monitoring List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="surveillanceEventModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Event Log Sheet</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="eventMonitoringList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Event Monitoring</th>
                                            <th style="text-align:center;">Prepared by</th>
                                            <th style="text-align:center;">Prepared by</th>
                                            <th style="text-align:center;">Prepared by</th>
                                            <th style="text-align:center;">Noted by</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Event Monitoring</th>
                                            <th style="text-align:center;">Prepared by</th>
                                            <th style="text-align:center;">Prepared by</th>
                                            <th style="text-align:center;">Prepared by</th>
                                            <th style="text-align:center;">Noted by</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Surveillance Event Monitoring List End ==================== -->
                </div>
            </div><!-- ==================== Table Section End ==================== -->
            <!-- =============== Surveillance Event Monitoring Modal =============== -->
            <div class="modal fade" id="surveillanceEventModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xxl modal-fullscreen-xl-down modal-dialog-scrollable" role="document">
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
                                            <th colspan="2" rowspan="2" style="text-align:center;vertical-align:middle;">Surveillance Description</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Video Event Reviewed</th>
                                            <th colspan="4" style="text-align: center;vertical-align:middle;">Video Event Recorded</th>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Comments</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center;vertical-align:middle;">Time Start</th>
                                            <th style="text-align:center;vertical-align:middle;">Time End</th>
                                            <th style="text-align:center;vertical-align:middle;">From</th>
                                            <th style="text-align:center;vertical-align:middle;">To</th>
                                            <th style="text-align:center;vertical-align:middle;">Total No. of Days</th>
                                            <th style="text-align:center;vertical-align:middle;">Min. Required No. Days</th>
                                        </tr>
                                    </thead>
                                    <tbody id="eventMonitoring_table_body"></tbody>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th colspan="2" rowspan="2" style="text-align:center;vertical-align:middle;">Surveillance Description</th>
                                            <th style="text-align:center;vertical-align:middle;">Time Start</th>
                                            <th style="text-align:center;vertical-align:middle;">Time End</th>
                                            <th style="text-align:center;vertical-align:middle;">From</th>
                                            <th style="text-align:center;vertical-align:middle;">To</th>
                                            <th style="text-align:center;vertical-align:middle;">Total No. of Days</th>
                                            <th style="text-align:center;vertical-align:middle;">Min. Required No. Days</th>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Comments</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Video Event Reviewed</th>
                                            <th colspan="4" style="text-align: center;vertical-align:middle;">Video Event Recorded</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" class="form-control fw-bold" id="eventcountvalidation">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold" id="event_prepared_by" disabled>
                                        <div class="invalid-feedback"></div>
                                        <label for="event_prepared_by" class="fw-bold">Prepared By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="event_prepared_job_pos"></label>
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
                            <button type="button" class="btn btn-success btnUpdateEvent" onclick="updateEventMonitoring(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveEvent" onclick="saveEventMonitoring();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
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
include '../helper/select_values.php'; ?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var logged_user_access = '<?php echo $_SESSION['access_lvl']; ?>';
    var eventcountvalidation = 0;
    let arraySurveillanceName = [];
    let arrayStartTime = [];
    let arrayEndTime = [];
    let arrayDateFrom = [];
    let arrayDateTo = [];
    let arrayTotalDays = [];
    let arrayMinDays = [];
    let arrayComments = [];

    loadEventMonitoringListTable();

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
                url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
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
                className: 'dt-body-middle-left'
            }, {
                targets: [1, 2, 3, 4],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 5,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="previewEventReport('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="editEventReport('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="removeEventReport('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
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

    function surveillanceEventModal() {
        $('#surveillanceEventModal').modal('show');
        $('#event_prepared_by').val(logged_user);
        loadJobPosition(logged_user, 'event_prepared_job_pos');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'event_noted_by', 'physical_security');
        generateSurveillanceRefno('phd_event_monitoring_refno', 'event_ref_no', 'event_ref_no');
        $('.btnUpdateEvent').prop('disabled', true);
        $('.btnUpdateEvent').css('display', 'none');
        $('.btnSaveEvent').prop('disabled', false);
        $('.btnSaveEvent').css('display', 'block');
        $('#eventcountvalidation').val('0');
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        const formattedDate = `${year}-${month < 10 ? '0' + month : month}-${day < 10 ? '0' + day : day}`;
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_event_monitoring_table_body'
            },
            success: function(result) {
                let html = '';
                var eventCount = 0;
                var rowCount = 0;
                $.each(result, (key, row) => {
                    eventCount++;
                    rowCount++;
                    html += '<tr>';
                    html += '<td style="width:4%;text-align:center;vertical-align:middle;">' + eventCount + '</td>';
                    html += '<td style="display:none;"><input type="text" name="event_surveillance_name[]" class="form-control fw-bold event_surveillance_name" value="' + row.surveillance_name + '" disabled></td>';
                    html += '<td style="width:15%;vertical-align:middle;">' + row.surveillance_name + '</td>';
                    html += '<td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_start[]" class="form-control fw-bold event_time_start" id="event_time_start' + rowCount + '" onchange="enableFrom(' + rowCount + ');"></td>';
                    html += '<td style="width:10%;vertical-align:middle;"><input type="text" name="event_time_end[]" class="form-control fw-bold event_time_end text-center" value="2359H" id="event_time_end' + rowCount + '" disabled></td>';
                    html += '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_from[]" class="form-control fw-bold event_date_from" id="event_date_from' + rowCount + '" min="' + formattedDate + '" onchange="getEventDateDiff(' + rowCount + ');"disabled></td>';
                    html += '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_to[]" class="form-control fw-bold event_date_to" id="event_date_to' + rowCount + '" min="' + formattedDate + '" onchange="getEventDateDiff(' + rowCount + ');"disabled></td>';
                    html += '<td style="width:10%;vertical-align:middle;"><input type="number" name="event_no_days[]" class="form-control fw-bold text-center event_no_days" id="event_no_days' + rowCount + '"disabled></td>';
                    html += '<td style="display:none;"><input type="text" name="event_min_days[]" class="form-control fw-bold text-center event_min_days" value="90" disabled></td>';
                    html += '<td style="width:6%;text-align:center;vertical-align:middle;">90</td>';
                    html += '<td style="vertical-align:middle;"><input type="text" name="event_comments[]" class="form-control fw-bold text-center event_comments" id="event_comments' + rowCount + '"disabled></td>';
                    html += '</tr>';
                });
                $('#eventMonitoring_table_body').append(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }


    function saveEventMonitoring() {
        if (submitValidation()) {
            var prepared_by = document.getElementById('event_prepared_by').value;
            var noted_by = document.getElementById('event_noted_by').value;
            var event_ref_no = document.getElementById('event_ref_no').value;

            $.ajax({
                url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_event_header',
                    prepared_by: prepared_by,
                    noted_by: noted_by,
                    event_ref_no: event_ref_no
                },
                success: function(result) {
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
                            url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_event_details',
                                eventheader_id: result.eventheaderid,
                                surveillance_name: surveillance_name,
                                event_time_start: event_time_start,
                                event_time_end: event_time_end,
                                event_date_from: event_date_from,
                                event_date_to: event_date_to,
                                event_total_days: event_total_days,
                                event_min_days: event_min_days,
                                event_ref_no: event_ref_no,
                                event_comments: event_comments,
                                prepared_by: prepared_by
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
                    $('#eventMonitoringList_table').DataTable().ajax.reload(null, false);
                    $('#surveillanceEventModal').modal('hide');
                }
            });
        }
    }

    function editEventReport(eventheaderid) {
        $('#surveillanceEventModal').modal('show');
        $('#event_prepared_by').val(logged_user);
        loadJobPosition(logged_user, 'event_prepared_job_pos');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'event_noted_by', 'physical_security');
        $('.btnUpdateEvent').val(eventheaderid);
        $('.btnUpdateEvent').prop('disabled', false);
        $('.btnUpdateEvent').css('display', 'block');
        $('.btnSaveEvent').prop('disabled', true);
        $('.btnSaveEvent').css('display', 'none');
        $('#eventcountvalidation').val('0');
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        const formattedDate = `${year}-${month < 10 ? '0' + month : month}-${day < 10 ? '0' + day : day}`;

        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_employee_header',
                eventheaderid: eventheaderid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#event_noted_by').val(result.noted_by);
                    loadJobPosition(result.noted_by, 'event_noted_job_pos');
                }, 300);
            }
        });

        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_event_monitoring_table_body',
                eventheaderid: eventheaderid
            },
            success: function(result) {
                let html = '';
                var eventCount = 0;
                var rowCount = 0;
                $.each(result, (key, row) => {
                    console.log(row.prepared_by);
                    eventCount++;
                    rowCount++;
                    html += '<tr>';
                    html += '<td style="width:4%;text-align:center;vertical-align:middle;">' + eventCount + '</td>';
                    html += '<td style="display:none;"><input type="text" name="event_surveillance_name[]" class="form-control fw-bold event_surveillance_name" value="' + row.surveillance_name + '" disabled></td>';
                    html += '<td style="width:15%;vertical-align:middle;">' + row.surveillance_name + '</td>';
                    if (row.prepared_by != null) {
                        html += '<td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_start[]" class="form-control fw-bold event_time_start" id="event_time_start' + rowCount + '" value="' + row.event_time_start + '" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="text" name="event_time_end[]" class="form-control fw-bold event_time_end text-center" value="2359H" id="event_time_end' + rowCount + '" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_from[]" class="form-control fw-bold event_date_from" id="event_date_from' + rowCount + '" value="' + row.event_date_from + '" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_to[]" class="form-control fw-bold event_date_to" id="event_date_to' + rowCount + '" value="' + row.event_date_to + '" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="number" name="event_no_days[]" class="form-control fw-bold text-center event_no_days" id="event_no_days' + rowCount + '" value="' + row.event_total_days + '" disabled></td>';
                        html += '<td style="display:none;"><input type="text" name="event_min_days[]" class="form-control fw-bold text-center event_min_days" value="90" disabled></td>';
                        html += '<td style="width:6%;text-align:center;vertical-align:middle;">90</td>';
                        html += '<td style="vertical-align:middle;"><input type="text" name="event_comments[]" class="form-control fw-bold text-center event_comments" id="event_comments' + rowCount + '" value="' + row.event_comments + '" disabled></td>';
                    } else {
                        html += '<td style="width:10%;vertical-align:middle;"><input type="time" name="event_time_start[]" class="form-control fw-bold event_time_start" id="event_time_start' + rowCount + '" onchange="enableFrom(' + rowCount + ');"></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="text" name="event_time_end[]" class="form-control fw-bold event_time_end text-center" value="2359H" id="event_time_end' + rowCount + '" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_from[]" class="form-control fw-bold event_date_from" id="event_date_from' + rowCount + '" min="' + formattedDate + '" onchange="getEventDateDiff(' + rowCount + ');" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="date" name="event_date_to[]" class="form-control fw-bold event_date_to" id="event_date_to' + rowCount + '" min="' + formattedDate + '" onchange="getEventDateDiff(' + rowCount + ');" disabled></td>';
                        html += '<td style="width:10%;vertical-align:middle;"><input type="number" name="event_no_days[]" class="form-control fw-bold text-center event_no_days" id="event_no_days' + rowCount + '" disabled></td>';
                        html += '<td style="display:none;"><input type="text" name="event_min_days[]" class="form-control fw-bold text-center event_min_days" value="90" disabled></td>';
                        html += '<td style="width:6%;text-align:center;vertical-align:middle;">90</td>';
                        html += '<td style="vertical-align:middle;"><input type="text" name="event_comments[]" class="form-control fw-bold text-center event_comments" id="event_comments' + rowCount + '" disabled></td>';
                    }
                    html += '</tr>';
                    rowCount++;
                });
                $('#eventMonitoring_table_body').append(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function enableFrom(rowCount) {
        $('#event_date_from' + rowCount).prop('disabled', false);
    }

    function updateEventMonitoring(eventheaderid) {
        if (submitValidation()) {
            var prepared_by = document.getElementById('event_prepared_by').value;
            var noted_by = document.getElementById('event_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_event_header',
                    prepared_by: prepared_by,
                    noted_by: noted_by,
                    eventheaderid: eventheaderid
                },
                success: function(result) {
                    //* ======== Update Event Monitoring Details ========
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
                            url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'update_event_details',
                                eventheader_id: eventheaderid,
                                surveillance_name: surveillance_name,
                                event_time_start: event_time_start,
                                event_time_end: event_time_end,
                                event_date_from: event_date_from,
                                event_date_to: event_date_to,
                                event_total_days: event_total_days,
                                event_comments: event_comments,
                                prepared_by: prepared_by
                            }
                        });
                    }
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Updated.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    $('#eventMonitoringList_table').DataTable().ajax.reload(null, false);
                    $('#surveillanceEventModal').modal('hide');
                }
            });
        }
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
                    url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_event_monitoring',
                        eventheaderid: eventheaderid
                    },
                    success: function(result) {
                        $('#eventMonitoringList_table').DataTable().ajax.reload(null, false);
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

    function previewEventReport(eventheaderid) {
        strLink = "surveillance_event_monitoring_log_sheet_pdf.php?d=" + eventheaderid;
        window.open(strLink, '_blank');
    }

    $('#event_noted_by').change(function() {
        if ($(this).val() == '') {
            showFieldError('event_noted_by', 'Noted by must not be blank');
        } else {
            clearFieldError('event_noted_by');
        }
    });

    function generateSurveillanceRefno(inTable, inField, inObject) {
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_event_log_sheet_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
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

    function getEventDateDiff(eventCount) {
        $('#event_date_to' + eventCount).prop('disabled', false);
        $('#event_comments' + eventCount).prop('disabled', false);
        var event_date_from = moment(document.getElementById('event_date_from' + eventCount).value, "YYYY-MM-DD");
        var event_date_to = moment(document.getElementById('event_date_to' + eventCount).value, "YYYY-MM-DD");
        var diff_date;
        if (isNaN(event_date_to.diff(event_date_from, 'days') + 1) == true) {
            diff_date = 0;
        } else {
            diff_date = event_date_to.diff(event_date_from, 'days') + 1;
        }
        $('#event_no_days' + eventCount).val(diff_date); //* Difference in number of days
        $('#eventcountvalidation').val('1');
    }

    function submitValidation() {
        var isValidated = true;
        var event_noted_by = document.getElementById('event_noted_by').value;
        var eventcountvalidation = document.getElementById('eventcountvalidation').value;

        if (eventcountvalidation == 0) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Please Fill Inputs',
                showConfirmButton: false,
                timer: 1000
            });
            isValidated = false;
        }

        $('.event_surveillance_name').each(function() {
            var str_surveillance_name = $(this).val();
            arraySurveillanceName.push([str_surveillance_name]);
        });
        $('.event_no_days').each(function() {
            var totalDays = $(this).val();
            arrayTotalDays.push([totalDays]);
        });
        $('.event_comments').each(function() {
            var str_comments = $(this).val();
            arrayComments.push([str_comments]);
        });

        for (let i = 0; i < arraySurveillanceName.length; i++) {
            var strTotalDays = arrayTotalDays[i];
            var event_total_days = strTotalDays.toString();
            var strComments = arrayComments[i];
            var event_comments = strComments.toString();

            if (event_total_days != '') {
                if (event_comments == '') {
                    i++;
                    console.log(i);
                    $('#event_comments' + i).addClass('is-invalid').removeClass('is-valid');
                    isValidated = false;
                }
            }
        }

        arraySurveillanceName = [];
        arrayTotalDays = [];
        arrayComments = [];

        if (event_noted_by.length == 0) {
            showFieldError('event_noted_by', 'Noted by must not be blank');
            isValidated = false;
        } else {
            clearFieldError('event_noted_by');
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
        $('#event_prepared_job_pos').html('');
        $('#event_noted_job_pos').html('');
        $('#eventMonitoring_table_body').html('');
        arraySurveillanceName = [];
        arrayStartTime = [];
        arrayEndTime = [];
        arrayDateFrom = [];
        arrayDateTo = [];
        arrayTotalDays = [];
        arrayMinDays = [];
        arrayComments = [];
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>