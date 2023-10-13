<?php include './../includes/header.php';
// date_default_timezone_set('Asia/Manila');
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$data_base64 = base64_encode($sqlstring);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
$json_response = curl_exec($curl);
//* ====== Close Connection ======
curl_close($curl);
// * ======== Prepare Array ========
$data_result = json_decode($json_response, true);
foreach ($data_result['data'] as $row) {
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
                <span class="page-title-it">User Access Request Main</span>
            </div>
            <!-- ==================== CONTENT SECTION ==================== -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow active" onclick="loadTableNavigation('Cancelled')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-danger fw-bold">CANCELLED</span>
                                    <div class="fs-2 fw-bold" id="cancelled_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-ban fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow" onclick="loadTableNavigation('Pending')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-warning fw-bold">FOR APPROVAL</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-regular fa-hourglass-half fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Process')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-primary fw-bold">IN PROCESS</span>
                                    <div class="fs-2 fw-bold" id="process_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-spinner fa-spin"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-info shadow" onclick="loadTableNavigation('Received')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-info fw-bold">FOR RECEIVED</span>
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
                                    <div class="fs-2 fw-bold" id="accomplished_count"></div>
                                </div>
                                <div class="fs-1 text-success"><i class="fa-solid fa-circle-check fa-bounce"></i></div>
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
                            <input type="hidden" id="toast_id" class="form-control fw-bold">
                            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ==================== Cancel Request Table ==================== -->
                    <div class="table-responsive" id="cancel_table">
                        <table id="cancel_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== Pending Request Table ==================== -->
                    <div class="table-responsive" id="approval_table">
                        <table id="approval_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== On Going Request Table ==================== -->
                    <div class="table-responsive" id="process_table">
                        <table id="process_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== For Received  Request Table ==================== -->
                    <div class="table-responsive" id="received_table">
                        <table id="received_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== Done Request Table ==================== -->
                    <div class="table-responsive" id="accomplished_table">
                        <table id="accomplished_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">CONTROL NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">ACCESS</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
            <!-- ==================== Alert Modal ==================== -->
            <div class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content shadow">
                        <div class="modal-body py-4 text-center">
                            <h3 class="alert-title mb-4 fw-bold">Title</h3>
                            <p class="alert-message fw-semibold fs-6">This is a sample message.</p>
                        </div>
                        <div class="modal-footer flex-nowrap p-0 alert-modal-btn">
                            <!-- <button type="button" class="btn btn-link text-danger text-decoration-none col-6 m-0 border-end fw-bold alert-submit" id="alert-submit">Yes, submit</button> -->
                            <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
                        </div>
                    </div>
                </div>
            </div><!-- ==================== Alert Modal End ==================== -->
            <!-- ==================== Details Modal ==================== -->
            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="detailsModalLabel">Details</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="control_number" readonly disabled>
                                        <label class="fw-bold">Control Number:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="access" disabled>
                                        <label class="fw-bold">Access:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="priority" disabled>
                                        <label class="fw-bold">Priority:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" style="height: 75px; resize: none;" id="purpose" disabled></textarea>
                                <label class="fw-bold">Purpose:</label>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="mail_account" disabled>
                                        <label class="fw-bold">Mail Account:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="file_storage_access" disabled>
                                        <label class="fw-bold">File Storage Access:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="in_house_access" disabled>
                                        <label class="fw-bold">In House Access:</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" value="" id="user_access_domain" checked disabled>
                                        <label class="form-check-label fw-bold" for="user_access_domain">
                                            Domain
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="prepared_by_date" disabled>
                                        <label class="fw-bold">Date Requested:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="date_needed" disabled>
                                        <label class="fw-bold">Date Needed:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="date_accomplish" disabled>
                                        <label class="fw-bold">Date Accomplished:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="requested_by" disabled>
                                        <label class="fw-bold">Requested By:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="approved_by" disabled>
                                        <label class="fw-bold">Approved By:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="noted_by" disabled>
                                        <label class="fw-bold">Noted By:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-primary col-sm btn-update" onclick="editApprovalDetails(this.value);"><i class="fa-solid fa-pen-to-square p-r-8 animation-trigger"></i> Edit</button>
                            <button type="button" class="btn btn-danger col-sm" onclick="closeModal();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- ==================== Details Modal END ==================== -->
            <!-- ==================== Details Modal ==================== -->
            <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="detailsModalLabel">Remarks</h5>
                        </div>
                        <div class="modal-body">
                            <p class="fw-semibold fs-6 mb-0">Accomplish Request?</p>
                            <textarea class="form-control mt-2" style="height: 120px" name="" id="reasonRemarks" cols="30" rows="10" placeholder="The repair was delayed due to..."></textarea>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-dark col-sm submit-reason" id="submit_reason" onclick="submitReason(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- ==================== Details Modal END ==================== -->
            <!-- ==================== Details Modal ==================== -->
            <div class="modal fade" id="reasonCancelModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="detailsModalLabel">Reason <i class="fa-solid fa-question fa-bounce"></i></h5>
                        </div>
                        <div class="modal-body">
                            <p class="fw-semibold fs-6 mb-0">Accomplish Cancel Request?</p>
                            <textarea class="form-control mt-2" style="height: 120px" name="" id="reasonCancelRemarks" cols="30" rows="10" placeholder="The request is cancelled due to..."></textarea>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-dark col-sm submit-cancel-reason" id="submit_cancel_reason" onclick="submitCancelReason(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                        </div>
                    </div>
                </div>
            </div><!-- ==================== Details Modal END ==================== -->
            <!-- ==================== Details Modal ==================== -->
            <div class="modal fade" id="technicianModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <h5 class="modal-title fw-bold text-light" id="detailsModalLabel">Technician Assign</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <select name="technician" id="technician" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                    <option value="Garlando Hilario">Garlando Hilario</option>
                                    <option value="Jonald Narzabal">Jonald Narzabal</option>
                                </select>
                                <label class="fw-bold">Technician Assign:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-dark col-sm" id="submit_technician"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- ==================== Details Modal END ==================== -->
            <!-- =============== Generate Report Modal =============== -->
            <div class="modal fade" id="generateReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header card-1">
                            <div class="col-sm">
                                <h5 class="modal-title fw-bold text-light" id="generateReportName">Software Dev. Report</h5>
                            </div>
                            <div class="col-sm-7">
                                <select class="form-select fw-bold" id="reportTypeSoftAndGeneral" onchange="reportType(this.value);">
                                    <option value="Software">Software Report</option>
                                    <option value="General">General Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2 hide_staff">
                                <select name="" id="it_staff" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                    <option value="Jonald Narzabal">Jonald Narzabal</option>
                                    <option value="Garlando Hilario">Garlando Hilario</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="staff" class="fw-bolder request_type_soft_hard_hide">Technicians:</label>
                            </div>
                            <div class="form-floating mb-2 hardwareAndSofwateHide">
                                <select name="" id="request_type_soft_hard" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="date_report_month" class="fw-bolder">Request Type:</label>
                            </div>
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
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var access_lvl = '<?php echo $_SESSION['access_lvl']; ?>';
    // Get the current date
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var autoFillmonth = currentDate.getMonth() + 1;
    var formattedDate = year + '-' + (autoFillmonth < 10 ? '0' : '') + autoFillmonth;
    $('.btn-update').hide();
    //* Card header name of active table
    let activeRequestList = {
        'Cancelled': 'Cancelled',
        'Pending': 'Approval',
        'Process': 'Process',
        'Received': 'Received',
        'Done': 'Done',
    }
    loadTableRequest('Cancelled', 'cancel_request_table');
    loadTableRequest('Pending', 'approval_request_table');
    loadTableRequest('Process', 'process_request_table');
    loadTableRequest('Received', 'received_request_table');
    loadTableRequest('Done', 'accomplished_request_table');
    loadTableNavigation('Cancelled');
    loadRequestCount();

    function loadTableNavigation(statusVal) {
        if (statusVal == 'Pending') {
            $('.btn-update').show();
        } else {
            $('.btn-update').hide();
        }
        $('#active_request').text(`${activeRequestList[statusVal] ?? 'Cancelled'} Request`);
        switch (statusVal) {
            case 'Cancelled':
                $('#cancel_table').show();
                $('#approval_table').hide();
                $('#process_table').hide();
                $('#received_table').hide();
                $('#accomplished_table').hide();
                break;

            case 'Pending':
                $('#cancel_table').hide();
                $('#approval_table').show();
                $('#process_table').hide();
                $('#received_table').hide();
                $('#accomplished_table').hide();
                break;

            case 'Process':
                $('#cancel_table').hide();
                $('#approval_table').hide();
                $('#process_table').show();
                $('#received_table').hide();
                $('#accomplished_table').hide();
                break;

            case 'Received':
                $('#cancel_table').hide();
                $('#approval_table').hide();
                $('#process_table').hide();
                $('#received_table').show();
                $('#accomplished_table').hide();
                break;

            case 'Done':
                $('#cancel_table').hide();
                $('#approval_table').hide();
                $('#process_table').hide();
                $('#received_table').hide();
                $('#accomplished_table').show();
                break;
        }
    }

    function loadTableRequest(statusVal, table) {
        //* ======== Load Table ========
        let inTable = $('#' + table).DataTable({
            'responsive': true,
            'autoWidth': false,
            'serverSide': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_table_request',
                    statusVal: statusVal
                }
            },
            'columnDefs': [{
                targets: [0, 1, 2, 4],
                className: 'dt-body-middle-center'
            }, {
                targets: 3,
                className: 'dt-body-middle-left'
            }, {
                targets: 5,
                className: 'dt-body-middle-center',
                render: function(data, type, row, meta) {
                    const buttonMap = {
                        'Cancelled': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Reapprove" onclick="reApprovedRequest(${data.id});" id="btn_approve"><i class="fa-solid fa-thumbs-up"></i></button>
                        <button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="detailsRequest(${data.id});"><i class="fa-solid fa-circle-info"></i></button>`,
                        'Pending': `<button class="btn btn-${data.noted_by_acknowledge == true ? 'warning' : 'secondary'} shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Process" onclick="acknowledgeRequest(${data.id});" ${data.noted_by_acknowledge == true ? '' : 'disabled'}><i class="fa-regular fa-circle-play fa-lg ${data.noted_by_acknowledge == true ? 'fa-fade' : ''}" style="color: #141415;"></i></button>
                        <button class="btn btn-danger shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cancel" onclick="cancelRequest(${data.id});"><i class="fa-solid fa-ban fa-shake"></i></button>
                        <button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="detailsRequest(${data.id});"><i class="fa-solid fa-circle-info"></i></button>`,
                        'Process': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Accomplish" onclick="accomplishRequest(${data.id})" id="btn_accomplish"><i class="fa-solid fa-check"></i></button>
                        <button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="detailsRequest(${data.id});"><i class="fa-solid fa-circle-info"></i></button>`,
                        'Received': `<button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="detailsRequest(${data.id});"><i class="fa-solid fa-circle-info"></i></button>`,
                        'Done': `<button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" onclick="pdfRequest('${data.control_no}');" id="btn_print"><i class="fa-solid fa-file-pdf"></i></button>
                        <button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="detailsRequest(${data.id});"><i class="fa-solid fa-circle-info"></i></button>`,
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
            inTable.ajax.reload(null, false);
        }, 5000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function pdfRequest(control_no) {
        window.open(`it_user_access_request_pdf.php?control_no=${control_no}`, '_blank');
    }

    function accomplishRequest(data) {
        Swal.fire({
            title: 'Do you want to Accomplish the request?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: `Don't Accomplish`,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Accomplish!', '', 'success')
                $.ajax({
                    url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'accomplishRequest',
                        logged_user: logged_user,
                        data: data
                    },
                    success: result => {
                        Swal.fire('Cancel!', '', 'success')
                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
    }

    function reApprovedRequest(data) {
        Swal.fire({
            title: 'Do you want to Reapprove the request?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: `Don't Reapprove`,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Reapprove!', '', 'success')
                $.ajax({
                    url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'reapproveRequest',
                        data: data
                    },
                    success: result => {
                        Swal.fire('Cancel!', '', 'success')
                    }
                })

            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
    }

    function detailsRequest(data, status) {
        $.ajax({
            url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'detailsRequest',
                data: data
            },
            success: result => {
                $('#control_number').val(result[0]['control_no']);
                $('#access').val(result[0]['access']);
                $('#priority').val(result[0]['priority']);
                $('#purpose').val(result[0]['purpose']);
                $('#mail_account').val(result[0]['mail_account']);
                $('#file_storage_access').val(result[0]['file_storage_access']);
                $('#in_house_access').val(result[0]['in_house_access']);
                $('#prepared_by_date').val(result[0]['date_request']);
                $('#date_needed').val(result[0]['date_need']);
                $('#date_accomplish').val(result[0]['date_accomplished']);
                result[0]['domain_account'] == true ? $('#user_access_domain').prop('checked', true) : $('#user_access_domain').prop('checked', false);
                $('#requested_by').val(result[0]['prepared_by']);
                $('#approved_by').val(result[0]['approved_by']);
                $('#noted_by').val(result[0]['noted_by']);
                $('.btn-update').val(data);
            }
        })
        $('#detailsModal').modal('show');
    }

    let btnAssign = 'edit';

    function editApprovalDetails(request_id) {
        if (btnAssign == 'edit') {
            $('#access').prop('disabled', false);
            $('#priority').prop('disabled', false);
            $('#purpose ').prop('disabled', false);
            $('#mail_account').prop('disabled', false);
            $('#file_storage_access').prop('disabled', false);
            $('#in_house_access').prop('disabled', false);
            $('#prepared_by_date').prop('disabled', false);
            $('#date_needed').prop('disabled', false);
            $('#user_access_domain').prop('disabled', false);
            $('.btn-update').removeClass('btn-primary').addClass('btn-success');
            $('.btn-update').html('<i class="fa-solid fa-pen-to-square p-r-8 animation-trigger"></i>Update');
            $('.animation-trigger').addClass('fa-bounce');
            btnAssign = 'update';
        } else {
            let domain = document.querySelector('#user_access_domain').checked;
            let access = $('#access').val();
            let priority = $('#priority').val();
            let purpose = $('#purpose').val();
            let mail_account = $('#mail_account').val();
            let file_storage_access = $('#file_storage_access').val();
            let in_house_access = $('#in_house_access').val();
            let prepared_by_date = $('#prepared_by_date').val();
            let date_needed = $('#date_needed').val();
            $.ajax({
                url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
                type: "POST",
                data: {
                    action: 'update_approval_details',
                    access: access,
                    priority: priority,
                    purpose: purpose,
                    mail_account: mail_account,
                    file_storage_access: file_storage_access,
                    in_house_access: in_house_access,
                    domain: domain,
                    prepared_by_date: prepared_by_date,
                    date_needed: date_needed,
                    request_id: request_id
                },
                success: function(result) {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Update Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    }

    function closeModal() {
        $('#control_number').prop('disabled', true);
        $('#access').prop('disabled', true);
        $('#priority').prop('disabled', true);
        $('#purpose ').prop('disabled', true);
        $('#mail_account').prop('disabled', true);
        $('#file_storage_access').prop('disabled', true);
        $('#in_house_access').prop('disabled', true);
        $('#user_access_domain').prop('disabled', true);
        $('#prepared_by_date').prop('disabled', true);
        $('#date_needed').prop('disabled', true);
        $('#requested_by').prop('disabled', true);
        $('#approved_by').prop('disabled', true);
        $('#noted_by').prop('disabled', true);
        $('#detailsModal').modal('hide');
        $('.btn-update').removeClass('btn-success').addClass('btn-primary');
        $('.animation-trigger').removeClass('fa-bounce');
        $('.btn-update').html('<i class="fa-solid fa-pen-to-square p-r-8 animation-trigger"></i>Edit');
        btnAssign = 'edit';
    }

    function cancelRequest(data) {
        Swal.fire({
            title: 'Do you want to Cancel the request?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: `Don't Cancel`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'cancelRequest',
                        data: data
                    },
                    success: result => {
                        Swal.fire('Cancel!', '', 'success')
                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
    }

    function acknowledgeRequest(data) {
        Swal.fire({
            title: 'Do you want to Acknowledge the request?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Acknowledge',
            denyButtonText: `Don't Acknowledge`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'acknowledgeRequest',
                        data: data
                    },
                    success: result => {
                        Swal.fire('Acknowledge!', '', 'success')
                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
    }

    function loadRequestCount() {
        $.ajax({
            url: '../controller/itasset_controller/it_user_access_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_request_count'
            },
            success: (res) => {
                console.log(res);
                $('#cancelled_count').text(res['Cancelled'] ?? 0); //? Cancelled
                $('#pending_count').text(res['Pending'] ?? 0); //? For Approval
                $('#process_count').text(res['Process'] ?? 0); //? In Process
                $('#received_count').text(res['Received'] ?? 0); //? For Received
                $('#accomplished_count').text(res['Done'] ?? 0); //? Accomplish
                setTimeout(loadRequestCount, 1500);
            }
        });
    }
</script>