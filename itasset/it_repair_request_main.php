<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach($result_res as $row){
// $data_base64 = base64_encode($sqlstring);
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
// curl_setopt($curl, CURLOPT_HEADER, false);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
// $json_response = curl_exec($curl);
// //* ====== Close Connection ======
// curl_close($curl);
// // * ======== Prepare Array ========
// $data_result = json_decode($json_response, true);
// foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom right, #fa3c3c, #aa0000);
        border-radius: 100vw;
    }
</style>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it">Repair Request Main</span>
            </div>
            <!-- ==================== CONTENT SECTION ==================== -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow active" onclick="loadTableNavigation('On Hold')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-danger fw-bold">RECORDER</span>
                                    <div class="fs-2 fw-bold" id="on_hold_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-hand fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow" onclick="loadTableNavigation('Pending')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-warning fw-bold">ACKNOWLEDGE</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-regular fa-hourglass-half fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Ongoing')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-primary fw-bold">ONGOING</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-spinner fa-spin"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-info shadow" onclick="loadTableNavigation('For Received')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-info fw-bold">FOR VERIFICATION</span>
                                    <div class="fs-2 fw-bold" id="received_count"></div>
                                </div>
                                <div class="fs-1 text-info"><i class="fa-solid fa-envelope fa-bounce"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-success shadow" onclick="loadTableNavigation('Done')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-success fw-bold">ACCOMPLISHED</span>
                                    <div class="fs-2 fw-bold" id="accomplish_count"></div>
                                </div>
                                <div class="fs-1 text-success"><i class="fa-solid fa-circle-check fa-fade " style="--fa-animation-duration: 2s; --fa-fade-opacity: 0.6;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header card-1 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-light" id="active_request"></h4>
                        <div>
                            <button class="btn btn-primary fw-bold" id="generate_report"><i class="fa-solid fa-calendar-days me-2"></i>General Report</button>
                            <button class="btn btn-success fw-bold" id="ta_report"><i class="fa-solid fa-calendar-days me-2"></i>Technical Accomplishment Report</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive" id="onhold_table">
                        <table id="onhold_repair_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive" id="pending_table">
                        <table id="pending_repair_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive" id="ongoing_table">
                        <table id="ongoing_repair_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="table-responsive" id="received_table">
                        <table id="received_repair_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="table-responsive" id="done_table">
                        <table id="done_repair_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE REPAIR</th>
                                    <th class="text-center">DATE ACCOMPLISHED</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- =============== Alert Modal =============== -->
            <div class="modal fade" id="accomplishedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content shadow">
                        <div class="modal-header card-1 d-flex justify-content-center">
                            <h3 class="fw-bold text-light">Proceed</h3>
                        </div>
                        <div class="modal-body pt-4 text-center">
                            <p class="fw-semibold fs-6 mb-0">Accomplish Repair?</p>
                        </div>
                        <div class="modal-footer flex-nowrap p-0">
                            <button type="button" class="btn btn-link text-danger text-decoration-none border-end col-6 m-0 fw-bold btn-accomplish" onclick="btnAccomplish(this.value);" data-bs-dismiss="modal">Yes</button>
                            <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Alert Modal =============== -->
            <div class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content shadow">
                        <div class="modal-header card-1 d-flex justify-content-center">
                            <h3 class="alert-title fw-bold text-light">Title</h3>
                        </div>
                        <div class="modal-body pt-4 text-center">
                            <p class="alert-message fw-semibold fs-6 mb-0">This is a sample message.</p>
                        </div>
                        <div class="modal-footer flex-nowrap p-0 alert-modal-btn">
                            <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Details Modal =============== -->
            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="detailsModalLabel">Details</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="queue_number" readonly disabled>
                                            <label class="fw-bold">Queue Number:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="date_requested" disabled>
                                            <label class="fw-bold">Date Requested:</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="area" disabled>
                                            <label class="fw-bold">Area:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="location" disabled>
                                            <label class="fw-bold">Location:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="ip_address" disabled>
                                            <label class="fw-bold">IP Address:</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="item" disabled>
                                            <label class="fw-bold">Item:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="prepared_by" disabled>
                                            <label class="fw-bold">Requested by:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="repaired_by" disabled>
                                            <label class="fw-bold">Repaired by:</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" style="height: 100px; resize: none;" id="remarks" disabled></textarea>
                                <label class="fw-bold">Remarks:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" style="height: 100px; resize: none;" id="action_taken" disabled></textarea>
                                <label class="fw-bold">Action Taken:</label>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="datetime_repair" disabled>
                                        <label class="fw-bold">Datetime Repair:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="datetime_accomplish" disabled>
                                        <label class="fw-bold">Datetime Acknowledge:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="datetime_acknowledge" disabled>
                                        <label class="fw-bold">Datetime Accomplish:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Generate Report Modal =============== -->
            <div class="modal fade" id="generateReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="generateReportModalLabel">Generate Report</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="month" class="form-control fw-bold" id="date_report_month" name="date_report_month">
                                <div class="invalid-feedback"></div>
                                <label for="date_report_month" class="fw-bolder">Date:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-dark col-sm" id="generate_report_submit"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Total Accomplishment Report Modal =============== -->
            <div class="modal fade" id="taReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="taReportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="taReportModalLabel">Technical Accomplishment Report</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2 hide_staff">
                                <select name="" id="it_staff" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="staff" class="fw-bolder request_type_soft_hard_hide">Technicians:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="month" class="form-control fw-bold" id="date_ta_month" name="date_ta_month">
                                <div class="invalid-feedback"></div>
                                <label for="date_ta_month" class="fw-bolder">Date:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-dark w-100" id="ta_report_submit"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ==================== CONTENT SECTION END ==================== -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-danger rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <!-- ==================== CARD SECTION ==================== -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-1 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IT ASSET</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
        <!-- ==================== CARD SECTION END ==================== -->
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    var logged_name = '<?php echo $_SESSION['fullname']; ?>';
    var access_lvl = '<?php echo $_SESSION['access_lvl']; ?>';
    switch (access_lvl) {
        case 'SDS':
            $('#ta_report').removeClass('btn-primary').addClass('btn-light');
            break;
    }

    //* following makes an AJAX call to PHP to get notification every 20 secs
    pushNotify();
    // setInterval(function() {
    //     pushNotify();
    // }, 20000);
    // Get the current date
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var autoFillmonth = currentDate.getMonth() + 1;
    var formattedDate = year + '-' + (autoFillmonth < 10 ? '0' : '') + autoFillmonth;

    function loadItDept() {
        $.ajax({
            url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadDept'
            },
            success: function(result) {
                $.each(result, (key, value) => {
                    $('#it_staff').append(`<option value="${key}">${value}</option>`);
                })
            }
        });
    }

    function pushNotify() {
        if (!("Notification" in window)) {
            //* checking if the user's browser supports web push Notification
            alert("Web browser does not support desktop notification");
        }
        if (Notification.permission !== "granted")
            Notification.requestPermission();
        else {
            $.ajax({
                url: "../controller/itasset_controller/it_push_notification_contr.class.php",
                type: "POST",
                dataType: 'JSON',
                data: {
                    action: 'fetch_new_repair_request',
                    request_type: 'repair'
                },
                success: function(data, textStatus, jqXHR) {
                    console.log(data);

                    //* if PHP call returns data process it and show notification
                    //* if nothing returns then it means no notification available for now
                    if ($.trim(data)) {
                        $.each(data, (key, value) => {
                            notification = createNotification(value.title, value.icon, value.body, value.url);
                            //* closes the web browser notification automatically after 10 secs
                            // setTimeout(function() {
                            // notification.close();
                            // }, 100);
                            // notification = null;
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            });

            $.ajax({
                url: "../controller/itasset_controller/it_push_notification_contr.class.php",
                type: "POST",
                dataType: 'JSON',
                data: {
                    action: 'fetch_new_repair_request',
                    request_type: 'request'
                },
                success: function(data, textStatus, jqXHR) {
                    console.log(data);

                    //* if PHP call returns data process it and show notification
                    //* if nothing returns then it means no notification available for now
                    if ($.trim(data)) {
                        $.each(data, (key, value) => {
                            notification = createNotification(value.title, value.icon, value.body, value.url);
                            //* closes the web browser notification automatically after 10 secs
                            // setTimeout(function() {
                            // notification.close();
                            // }, 100);
                            // notification = null;
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            });
        }
    };

    //* Card header name of active table
    let activeRequestList = {
        'On Hold': 'On Hold',
        'Pending': 'Pending',
        'Ongoing': 'Ongoing',
        'For Received': 'For Received',
        'Done': 'Accomplished',
    }

    loadTableRepair('On Hold', 'onhold_repair_table');
    loadTableRepair('Pending', 'pending_repair_table');
    loadTableRepair('Ongoing', 'ongoing_repair_table');
    loadTableRepair('For Received', 'received_repair_table');
    loadTableRepair('Done', 'done_repair_table');
    loadTableNavigation('On Hold')
    loadRepairCount();

    function loadTableNavigation(statusVal) {
        $('#active_request').text(`${activeRequestList[statusVal] ?? 'On Hold'} Request`);
        switch (statusVal) {
            case 'On Hold':
                $('#onhold_table').show();
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'Pending':
                $('#onhold_table').hide();
                $('#pending_table').show();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'Ongoing':
                $('#onhold_table').hide();
                $('#pending_table').hide();
                $('#ongoing_table').show();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'For Received':
                $('#onhold_table').hide();
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').show();
                $('#done_table').hide();
                break;

            default:
                $('#onhold_table').hide();
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').show();
                break;
        }
    }

    function loadTableRepair(statusVal, table) {
        //* Action Button Events
        $('#' + table).on('click', '#btn_acknowledge', (event) => handleAction('acknowledge', $(event.currentTarget).data('id'), table));
        $('#' + table).on('click', '#btn_repair', (event) => handleAction('repair', $(event.currentTarget).data('id'), table));
        // $('#' + table).on('click', '#btn_received', (event) => handleAction('For Received', $(event.currentTarget).data('id'), table));
        $('#' + table).on('click', '#btn_accomplish', (event) => handleAction('accomplish', $(event.currentTarget).data('id'), table, $(event.currentTarget).data('sender'), $(event.currentTarget).data('requested')));
        $('#' + table).on('click', '#btn_cancel', (event) => handleAction('cancel', $(event.currentTarget).data('id'), table));
        $('#' + table).on('click', '#btn_print', (event) => window.open(`it_repair_request_main_pdf.php?id=${$(event.currentTarget).data('id')}`, '_blank'));
        $('#' + table).on('click', '#btn_details', (event) => {
            let id = $(event.currentTarget).data("id");
            $('#detailsModal').modal('show');
            $.ajax({
                url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_details',
                    id: id
                },
                success: (res) => {
                    $('#queue_number').val(res.queue_number);
                    $('#area').val(res.area);
                    $('#location').val(res.location);
                    $('#ip_address').val(res.ip_address);
                    $('#item').val(res.item);
                    $('#prepared_by').val(res.prepared_by);
                    $('#repaired_by').val(res.repaired_by);
                    $('#date_requested').val(res.date_requested);
                    $('#remarks').val(res.remarks);
                    $('#action_taken').val(res.action_taken);
                    $('#datetime_acknowledge').val(res.prepared_by_acknowlege_date);
                    $('#datetime_repair').val(res.datetime_repair);
                    $('#datetime_accomplish').val(res.datetime_accomplish);
                }
            });
        });

        let inTable = $('#' + table).DataTable({
            'responsive': true,
            'autoWidth': false,
            'serverSide': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_table_repair',
                    statusVal: statusVal
                }
            },
            'columnDefs': [{
                targets: [0, 1, 3, 4, 5, 6, 7],
                className: 'dt-body-middle-center'
            }, {
                targets: 2,
                className: 'dt-body-middle-left'
            }, {
                targets: 8,
                orderable: false,
                className: 'dt-nowrap-center',
                render: function(data, type, row, meta) {
                    const buttonMap = {
                        'On Hold': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Acknowledge" data-id="${data.id}" id="btn_acknowledge"><i class="fa-solid fa-handshake fa-beat"></i></button>
                            <button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info fa-bounce"></i></button>
                            <button class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cancel" data-id="${data.id}" id="btn_cancel"><i class="fa-solid fa-ban fa-shake"></i></button>`,
                        'Pending': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Repair" data-id="${data.id}" id="btn_repair"><i class="fa-solid fa-hammer fa-shake"></i></button>
                            <button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" data-id="${data.id}" id="btn_print"><i class="fa-solid fa-file-pdf fa-flip"></i></button>
                            <button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info fa-bounce"></i></button>`,
                        'Ongoing': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Accomplish" data-id="${data.id}" data-sender="${data.sender}" data-requested="${data.requested}" id="btn_accomplish"><i class="fa-solid fa-check fa-bounce"></i></button>
                            <button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info fa-beat"></i></button>`,
                        'For Received': `<button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" data-id="${data.id}" id="btn_print"><i class="fa-solid fa-file-pdf fa-flip"></i></button>
                                <button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info fa-beat"></i></button>`,
                        'Done': `<button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" data-id="${data.id}" id="btn_print"><i class="fa-solid fa-file-pdf fa-flip"></i></button>
                                <button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info fa-beat"></i></button>`
                    };
                    return buttonMap[data.status] || '';
                }
            }]
        });
        inTable.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 1000);
        });

        setInterval(function() {
            inTable.ajax.reload(null, false); //* ======= Reload Table Data Every X seconds with pagination retained =======
        }, 5000);
    }

    function loadRepairCount() {
        $.ajax({
            url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_repair_count'
            },
            success: (res) => {
                $('#on_hold_count').text(res['On Hold'] ?? 0);
                $('#pending_count').text(res['Pending'] ?? 0);
                $('#ongoing_count').text(res['Ongoing'] ?? 0);
                $('#received_count').text(res['For Received'] ?? 0);
                $('#accomplish_count').text(res['Done'] ?? 0);
                setTimeout(loadRepairCount, 1500);
            }
        });
    }

    $('#generate_report').click(() => {
        $('#generateReportModal').modal('show');
        document.getElementById('date_report_month').value = formattedDate;

        let month = $('#date_report_month');

        month.change(() => {
            end.attr('min', month.val());
            end.prop('disabled', !month.val());
        });

        $('#generate_report_submit').click(() => {
            if (month.val() == '') {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill out all required fields.',
                    showConfirmButton: false,
                    timer: 800
                });
            } else {
                $('#generateReportModal').modal('hide');
                window.open(`it_repair_main_report_pdf.php?month=${month.val()}`, '_blank')
                clearValues();
            }
        });
    });
    
    $('#ta_report').click(() => {
        $('#taReportModal').modal('show');
        document.getElementById('date_ta_month').value = formattedDate;
        let month = $('#date_ta_month');
        let it_staff = $('#it_staff');
        switch (access_lvl) {
            case 'SDS':
                loadItDept();
                $('.hide_staff').css('display', 'block');
                $('#ta_report_submit').click(() => {
                    if (month.val() == '') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Please fill out all required fields.',
                            showConfirmButton: false,
                            timer: 800
                        });
                    } else if (it_staff.val() == '') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Please fill out all required fields.',
                            showConfirmButton: false,
                            timer: 800
                        });
                    } else {
                        $('#taReportModal').modal('hide');
                        window.open(`it_repair_main_ta_report_pdf.php?month=${month.val()}&user=${it_staff.val()}`, '_blank')
                        clearValues();
                    }
                });
                break;
            default:
                $('.hide_staff').css('display', 'none');
                $('#ta_report_submit').click(() => {
                    if (month.val() == '') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Please fill out all required fields.',
                            showConfirmButton: false,
                            timer: 800
                        });
                    } else {
                        $('#taReportModal').modal('hide');
                        window.open(`it_repair_main_ta_report_pdf.php?month=${month.val()}&user=${logged_name}`, '_blank')
                        clearValues();
                    }
                });
                break;
        }
    });

    function btnAccomplish(id) {
        $.ajax({
            url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
            type: 'POST',
            data: {
                action: 'proceedAccomplish',
                id: id,
                logged_name: logged_name
            }
        })
    }

    function handleAction(action, id, table, sender, requested) {
        if (action == 'accomplish') {
            $('.btn-accomplish').val(id);
            $('#accomplishedModal').modal('show');
        }

        alertModal(action[0].toUpperCase() + action.slice(1), `Are you sure you want to ${action} this request?`, action);
        $(`#alert_${action}_btn`).click(() => {
            let objData = "";
            switch (action) {
                case 'accomplish':
                    objData = {
                        action: action,
                        id: id,
                        sender: sender,
                        requested: requested,
                        action_taken: $('#action_taken').val(),
                        logged_name: logged_name
                    }
                    break;

                case 'acknowledge':
                    objData = {
                        action: action,
                        id: id,
                        priority: $('#priority').val(),
                        logged_name: logged_name
                    }
                    break;

                default:
                    objData = {
                        action: action,
                        id: id,
                    }
                    break;
            }
            $.ajax({
                url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
                type: 'POST',
                data: objData,
                success: () => refreshTable(),
                complete: () => $('#alertModal').modal('hide')
            });
        });
    }

    getDoneStatus();

    function getDoneStatus() {
        $.ajax({
            url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'getDoneStatus'
            },
            success: function(result) {
                console.log(result.datetime_repair);
                $.each(result, (key, value) => {
                    var dateStart = value.datetime_repair;
                    var dateEnd = value.datetime_accomplish;
                    var start_date = new Date(dateStart);
                    var since_start = new Date(dateEnd);
                    var diff = Math.abs(since_start.getTime() - start_date.getTime());
                    var minutes = Math.floor(diff / (1000 * 60));
                    var totalMinutes = minutes + " Min.";
                    $.ajax({
                        url: '../controller/itasset_controller/it_repair_request_main_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'setDuration',
                            id: value.repair_id,
                            totalMinutes: totalMinutes
                        }
                    })
                })
            }
        });
    }

    function refreshTable() {
        $('#onhold_repair_table').DataTable().ajax.reload(null, false);
        $('#pending_repair_table').DataTable().ajax.reload(null, false);
        $('#ongoing_repair_table').DataTable().ajax.reload(null, false);
        $('#done_repair_table').DataTable().ajax.reload(null, false);
    }

    //* Dynamic Alert Modal
    function alertModal(title, message, type) {
        $('#alertModal').modal('show');
        $('.alert-title').text(title);
        $('.alert-message').text(message)

        $('.alert-submit').remove();
        $('.alert-modal-btn').prepend($('<button>', {
            type: 'button',
            class: 'btn btn-link alert-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
            id: `alert_${type}_btn`,
            text: 'Yes, submit'
        }));

        if (type == 'accomplish') {
            $(`#alert_${type}_btn`).prop('disabled', 1)
            $('.alert-message').append($('<textarea>', {
                class: 'form-control mt-4',
                id: 'action_taken',
                placeholder: 'Action Taken:',
                height: '120px',
            })).on('input', '#action_taken', (e) => {
                $(`#alert_${type}_btn`).prop('disabled', $('#action_taken').val().trim() == '' ? 1 : 0)
            });
        }

        if (type == 'acknowledge') {
            const options = [{
                value: '',
                label: 'Priority :'
            }, {
                value: 'Critical',
                label: 'Critical'
            }, {
                value: 'High',
                label: 'High'
            }, {
                value: 'Medium',
                label: 'Medium'
            }, {
                value: 'Low',
                label: 'Low'
            }];

            const select = $('<select>', {
                class: 'form-select mt-4',
                id: 'priority',
            }).append(options.map(option => $('<option>', {
                value: option.value,
                text: option.label,
                hidden: option.value == '' ? true : false
            })));

            $(`#alert_${type}_btn`).prop('disabled', 1);
            $('.alert-message').append(select).on('change', '#priority', (e) => {
                $(`#alert_${type}_btn`).prop('disabled', $('#priority').val() == '' ? 1 : 0);
            });
        }
    }

    function clearValues() {
        $('input').val('');
        $('textarea').val('');
        $('#it_staff').html('<option value="">Choose...</option>');
    }
</script>