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
                <span class="page-title-it">Software & Hardware Request Main</span>
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
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Ongoing')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-primary fw-bold">IN PROCESS</span>
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
                                    <div class="fs-2 fw-bold" id="accomplish_count"></div>
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
                            <!-- <button type="button" class="btn btn-primary" onclick="test();">Test</button> -->

                            <!-- <div class="toast-container position-fixed bottom-0 end-0 p-3">
                                <div id="liveToast" class="toast" role="alert" data-bs-autohide="false" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-header">
                                        <img src="..." class="rounded me-2" alt="...">
                                        <strong class="me-auto">Bootstrap</strong>
                                        <small>11 mins ago</small>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                </div>
                            </div> -->
                            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            </div>
                            <button class="btn btn-primary fw-bold" id="generate_report"><i class="fa-solid fa-calendar-days me-2"></i>General Report</button>
                            <!-- <button class="btn btn-success fw-bold" id="ta_report"><i class="fa-solid fa-calendar-days me-2"></i>Technical Accomplishment Report</button> -->
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ==================== Cancel Request Table ==================== -->
                    <div class="table-responsive" id="cancel_table">
                        <table id="cancel_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== Pending Request Table ==================== -->
                    <div class="table-responsive" id="pending_table">
                        <table id="pending_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== On Going Request Table ==================== -->
                    <div class="table-responsive" id="ongoing_table">
                        <table id="ongoing_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== For Received  Request Table ==================== -->
                    <div class="table-responsive" id="received_table">
                        <table id="for_received_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== Done Request Table ==================== -->
                    <div class="table-responsive" id="done_table">
                        <table id="done_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
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
                                        <input type="text" class="form-control fw-bold" id="queue_number" readonly disabled>
                                        <label class="fw-bold">Reference Number:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="request_type" disabled>
                                        <label class="fw-bold">Request Type:</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="item" disabled>
                                        <label class="fw-bold">Requested Item:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" style="height: 125px; resize: none;" id="description" disabled></textarea>
                                <label class="fw-bold">Description:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" style="height: 125px; resize: none;" id="purpose" disabled></textarea>
                                <label class="fw-bold">Purpose:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" style="height: 125px; resize: none;" id="remarks" disabled></textarea>
                                <label class="fw-bold">Remarks:</label>
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
    switch (access_lvl) {
        case 'SPG':
            $('#generate_report').removeClass('btn-primary').addClass('btn-warning');
            $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>My Report');
            break;
        case 'SDS':
            $('#generate_report').removeClass('btn-primary').addClass('btn-light');
            $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>Software Development Report');
            break;
    }
    var empno = '<?php echo $_SESSION['empno']; ?>';
    var username = '<?php echo $_SESSION['username']; ?>';

    let toastCounter = 1; // Initialize a counter for generating unique IDs
    function getCurrentTime12Hour() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const period = hours >= 12 ? 'PM' : 'AM';
        const hours12 = (hours % 12) || 12;

        return `${hours12}:${minutes.toString().padStart(2, '0')} ${period}`;
    }
    const currentTime = getCurrentTime12Hour();

    function test() {
        let html = '';
        html += '<div id="toast' + toastCounter + '" class="toast" role="alert" data-bs-autohide="false" aria-live="assertive" aria-atomic="true">';
        html += '<div class="toast-header">';
        html += '<i class="fa-regular fa-bell fa-shake" style="color: #ec0958;"></i>&nbsp;&nbsp;<span class="text-danger fw-bold">Notification</span>';
        html += '<strong class="me-auto"></strong>';
        html += '<small class="text-muted">' + currentTime + '</small>';
        html += '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';
        html += '</div>';
        html += '<div class="toast-body">';
        html += '<span class="text-primary fw-bold"><span class="text-danger fw-bold">Request by:</span> Crescian Lloyd Lanoy</span><br><hr>';
        html += '<span class="text-danger fw-bold">Approval cancelled the acknowledgement.</span>';
        html += '</div>';
        html += '</div>';
        $('.toast-container').append(html);
        // setTimeout(function() {
        const toastLiveExample = document.getElementById('toast' + toastCounter);
        const toast = new bootstrap.Toast(toastLiveExample);
        toast.show();
        toastCounter++; // Increment the counter for the next toast
        // }, 500);
    }

    //* following makes an AJAX call to PHP to get notification every 20 secs
    pushNotify();
    setInterval(function() {
        pushNotify();
    }, 20000);

    let loadOnce = true;
    setInterval(function() {
        loadToastNotification();
    }, 1000);

    function loadToastNotification() {
        $.ajax({
            url: '../controller/itasset_controller/it_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'toastNotification'
            },
            success: value => {
                console.log(loadOnce);
                if (loadOnce) {
                    $('#toast_id').val(value.request_id);
                    loadOnce = false;
                }
                let toastVal = $('#toast_id').val();
                console.log(toastVal + ' ' + value.request_id);
                if (toastVal != value.request_id) {
                    let html = '';
                    html += '<div id="toast' + value.request_id + '" class="toast" role="alert" data-bs-autohide="false" aria-live="assertive" aria-atomic="true">';
                    html += '<div class="toast-header">';
                    html += '<i class="fa-regular fa-bell fa-shake" style="color: #ec0958;"></i>&nbsp;&nbsp;<span class="text-danger fw-bold">Notification</span>';
                    html += '<strong class="me-auto"></strong>';
                    html += '<small class="text-muted">' + currentTime + '</small>';
                    html += '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';
                    html += '</div>';
                    html += '<div class="toast-body">';
                    html += '<span class="text-primary fw-bold"><span class="text-danger fw-bold">Request by:</span> ' + value.prepared_by + '</span><br><hr>';
                    html += '<span class="text-danger fw-bold">Approval ' + value.status + ' the acknowledgement.</span>';
                    html += '</div>';
                    html += '</div>';
                    $('.toast-container').append(html);
                    // setTimeout(function() {
                    const toastLiveExample = document.getElementById('toast' + value.request_id);
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                    // }, 500);
                }
                $('#toast_id').val(value.request_id);
            }
        });
    }
    // Get the current date
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var autoFillmonth = currentDate.getMonth() + 1;
    var formattedDate = year + '-' + (autoFillmonth < 10 ? '0' : '') + autoFillmonth;

    let btnAssign = 'edit';

    function closeModal() {
        $('#prepared_by_date').prop('disabled', true);
        $('#date_needed').prop('disabled', true);
        $('#request_type').prop('disabled', true);
        $('#item ').prop('disabled', true);
        $('#description').prop('disabled', true);
        $('#purpose').prop('disabled', true);
        $('#detailsModal').modal('hide');
        $('.btn-update').removeClass('btn-success').addClass('btn-primary');
        $('.animation-trigger').removeClass('fa-bounce');
        btnAssign = 'edit';
    }

    function editApprovalDetails(request_id) {
        if (btnAssign == 'edit') {
            $('#prepared_by_date').prop('disabled', false);
            $('#date_needed').prop('disabled', false);
            $('#request_type').prop('disabled', false);
            $('#item ').prop('disabled', false);
            $('#description').prop('disabled', false);
            $('#purpose').prop('disabled', false);
            $('.btn-update').removeClass('btn-primary').addClass('btn-success');
            $('.animation-trigger').addClass('fa-bounce');
            btnAssign = 'update';
        } else {
            let prepared_by_date = $('#prepared_by_date').val();
            let date_needed = $('#date_needed').val();
            let request_type = $('#request_type').val();
            let item = $('#item').val();
            let description = $('#description').val();
            let purpose = $('#purpose').val();
            $.ajax({
                url: '../controller/itasset_controller/it_request_main_contr.class.php',
                type: "POST",
                data: {
                    action: 'update_approval_details',
                    date_requested: prepared_by_date,
                    date_needed: date_needed,
                    request_type: request_type,
                    item: item,
                    description: description,
                    purpose: purpose,
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
                    //* if PHP call returns data process it and show notification
                    //* if nothing returns then it means no notification available for now
                    if ($.trim(data)) {
                        $.each(data, (key, value) => {
                            notification = createNotification(value.title, value.icon, value.body, value.url);
                            //* closes the web browser notification automatically after 10 secs
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
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            });
        }
    }

    //* Card header name of active table
    let activeRequestList = {
        'Cancelled': 'Cancelled',
        'Pending': 'Pending',
        'Ongoing': 'Ongoing',
        'For Received': 'For Received',
        'Done': 'Accomplished',
    }

    loadTableRequest('Cancelled', 'cancel_request_table');
    loadTableRequest('Pending', 'pending_request_table');
    loadTableRequest('Ongoing', 'ongoing_request_table');
    loadTableRequest('For Received', 'for_received_table');
    loadTableRequest('Done', 'done_request_table');
    loadTableNavigation('Cancelled')
    loadRequestCount();

    function loadTableNavigation(statusVal) {
        $('#active_request').text(`${activeRequestList[statusVal] ?? 'Cancelled'} Request`);
        switch (statusVal) {
            case 'Cancelled':
                $('#cancel_table').show();
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'Pending':
                $('#cancel_table').hide();
                $('#pending_table').show();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'Ongoing':
                $('#cancel_table').hide();
                $('#pending_table').hide();
                $('#ongoing_table').show();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'For Received':
                $('#cancel_table').hide();
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').show();
                $('#done_table').hide();
                break;

            default:
                $('#cancel_table').hide();
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').show();
                break;
        }
    }


    function loadTableRequest(statusVal, table) {
        //* ======== Toggle Table Buttons ========
        $('#' + table).on('click', '#btn_cancel', (event) => handleAction('cancel', $(event.currentTarget).data('id'), table));
        $('#' + table).on('click', '#btn_request', (event) => handleAction('process', $(event.currentTarget).data('id'), table, $(event.currentTarget).data('sender'), $(event.currentTarget).data('request')));
        $('#' + table).on('click', '#btn_accomplish', (event) => handleAction('accomplish', $(event.currentTarget).data('id'), table, $(event.currentTarget).data('sender'), $(event.currentTarget).data('request'), $(event.currentTarget).data('technician')));
        $('#' + table).on('click', '#btn_approve', (event) => handleAction('reapprove', $(event.currentTarget).data('id'), table));
        $('#' + table).on('click', '#btn_print', (event) => window.open(`it_request_main_pdf.php?id=${$(event.currentTarget).data('id')}`, '_blank'));
        $('#' + table).on('click', '#btn_details', (event) => {
            let id = $(event.currentTarget).data("id");
            let status = $(event.currentTarget).data('status');
            if (status == 'Pending') {
                $('.btn-update').css('display', 'block');
            } else {
                $('.btn-update').css('display', 'none');
            }
            $('#detailsModal').modal('show');
            $.ajax({
                url: '../controller/itasset_controller/it_request_main_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_details',
                    id: id
                },
                success: (res) => {
                    $('#queue_number').val(res.queue_number);
                    $('#item').val(res.item);
                    $('#request_type').val(res.request_type);
                    $('#description').val(res.description);
                    $('#purpose').val(res.purpose);
                    $('#requested_by').val(res.prepared_by);
                    $('#approved_by').val(res.approved_by);
                    $('#noted_by').val(res.noted_by);
                    $('#prepared_by_date').val(res.prepared_by_date);
                    $('#date_needed').val(res.date_needed);
                    $('.btn-update').val(res.request_id);
                    $('#date_accomplish').val(res.repaired_by_date);
                    $('#remarks').val(res.status);
                }
            });
        });

        //* ======== Load Table ========
        let inTable = $('#' + table).DataTable({
            'responsive': true,
            'autoWidth': false,
            'serverSide': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/itasset_controller/it_request_main_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_table_request',
                    statusVal: statusVal
                }
            },
            'columnDefs': [{
                targets: [0, 1, 2, 3, 4],
                className: 'dt-body-middle-center'
            }, {
                targets: [5, 6],
                className: 'dt-body-middle-left'
            }, {
                targets: 7,
                orderable: false,
                className: 'dt-nowrap-center',
                render: function(data, type, row, meta) {
                    const buttonMap = {
                        'Cancelled': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Reapprove" data-id="${data.id}" id="btn_approve"><i class="fa-solid fa-thumbs-up"></i></button>`,
                        'Pending': `<button class="btn btn-${data.noted_by_acknowledge == true ? 'primary':'secondary'} shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Process" data-id="${data.id}" data-sender="${data.sender}" data-request="${data.request}" id="btn_request" ${data.noted_by_acknowledge == true ? '':'disabled'}><i class="fa-regular fa-circle-play ${data.noted_by_acknowledge == true ? 'fa-bounce':''}" style="color: #e7e008;"></i></button>
                            <button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" data-id="${data.id}" id="btn_print"><i class="fa-solid fa-file-pdf"></i></button>
                            <button class="btn btn-danger shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Cancel" data-id="${data.id}" id="btn_cancel"><i class="fa-solid fa-ban"></i></button>
                            <button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" data-status="${data.status}" id="btn_details"><i class="fa-solid fa-circle-info"></i></button>`,
                        'Ongoing': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Accomplish" data-id="${data.id}" data-sender="${data.sender}" data-request="${data.request}" data-technician="${data.repaired_by}" id="btn_accomplish"><i class="fa-solid fa-check"></i></button>
                            <button class="btn btn-dark shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info"></i></button>`,
                        'For Received': `<button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" data-id="${data.id}" id="btn_print"><i class="fa-solid fa-file-pdf"></i></button>
                        <button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info fa-beat"></i></button>`,
                        'Done': `<button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" data-id="${data.id}" id="btn_print"><i class="fa-solid fa-file-pdf"></i></button>
                            <button class="btn btn-dark shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" data-id="${data.id}" id="btn_details"><i class="fa-solid fa-circle-info"></i></button>`
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

    function loadRequestCount() {
        $.ajax({
            url: '../controller/itasset_controller/it_request_main_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_request_count'
            },
            success: (res) => {
                $('#cancelled_count').text(res['Cancelled'] ?? 0); //? Cancelled
                $('#pending_count').text(res['Pending'] ?? 0); //? For Approval
                $('#ongoing_count').text(res['Ongoing'] ?? 0); //? In Process
                $('#received_count').text(res['For Received'] ?? 0); //? For Received
                $('#accomplish_count').text(res['Done'] ?? 0); //? Accomplish
                setTimeout(loadRequestCount, 1500);
            }
        });
    }

    function reportType(value) {
        if (value == 'Software') {
            $('#generateReportName').html('Software Dev. Report');
            $('#generateReportName').addClass('fade-in');
            setTimeout(function() {
                $('#generateReportName').removeClass('fade-in');
            }, 800);
            $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>Software Development Report');
            $('.hide_staff').css('display', 'block');
            $('.hardwareAndSofwateHide').css('display', 'none');
            $('#reportTypeSoftAndGeneral').val('Software');
        } else {
            $('#generateReportName').addClass('fade-in');
            setTimeout(function() {
                $('#generateReportName').removeClass('fade-in');
            }, 800);
            $('#generateReportName').html('General Report');
            $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>General Report');
            $('.hardwareAndSofwateHide').css('display', 'block');
            $('.hide_staff').css('display', 'none');
            $('#reportTypeSoftAndGeneral').val('General');
        }
    }
    $('#generate_report').click(() => {
        document.getElementById('date_report_month').value = formattedDate;
        $('#generateReportModal').modal('show');
        let month = $('#date_report_month');
        let request_type = $('#request_type_soft_hard');
        let senior = $('#it_staff');
        switch (access_lvl) {
            case 'SPG':
                $('#reportTypeSoftAndGeneral').css('display', 'none');
                $('.hardwareAndSofwateHide').css('display', 'none');
                $('.hide_staff').css('display', 'none');
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
                        window.open(`it_request_main_general_report_senior_pdf.php?month=${month.val()}&requestType=Software&user=${logged_user}`, '_blank');
                    }
                });
                break;
            case 'SDS':
                $('.hide_staff').css('display', 'block');
                $('.hardwareAndSofwateHide').css('display', 'none');
                $('#reportTypeSoftAndGeneral').css('display', 'block');
                $('#generate_report_submit').click(() => {
                    let typePdf = $('#reportTypeSoftAndGeneral').val();
                    if (typePdf == 'Software') {
                        if (month.val() == '') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Error',
                                text: 'Please fill out all required fields.',
                                showConfirmButton: false,
                                timer: 800
                            });
                        } else if (senior.val() == '') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Error',
                                text: 'Please fill out all required fields.',
                                showConfirmButton: false,
                                timer: 800
                            });
                        } else {
                            window.open(`it_request_main_general_report_senior_pdf.php?month=${month.val()}&requestType=Software&user=${senior.val()}`, '_blank');
                        }
                    } else {
                        if (month.val() == '') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Error',
                                text: 'Please fill out all required fields.',
                                showConfirmButton: false,
                                timer: 800
                            });
                        } else if (request_type.val() == '') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Error',
                                text: 'Please fill out all required fields.',
                                showConfirmButton: false,
                                timer: 800
                            });
                        } else {
                            // $('#generateReportModal').modal('hide');
                            window.open(`it_request_main_general_report_pdf.php?month=${month.val()}&requestType=${request_type.val()}`, '_blank');
                            // clearValues();
                        }
                    }
                });
                break;
            default:
                $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>General Report');
                $('#reportTypeSoftAndGeneral').css('display', 'none');
                $('.hardwareAndSofwateHide').css('display', 'block');
                $('.hide_staff').css('display', 'none');

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
                    } else if (request_type.val() == '') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Please fill out all required fields.',
                            showConfirmButton: false,
                            timer: 800
                        });
                    } else {
                        // $('#generateReportModal').modal('hide');
                        window.open(`it_request_main_general_report_pdf.php?month=${month.val()}&requestType=${request_type.val()}`, '_blank');
                        // clearValues();
                    }
                });
                break;
        }
        // if (access_lvl == 'SPG') {} else {}
    });

    function clearValues() {
        if (access_lvl == 'SDS') {
            $('#generateReportName').html('Software Dev. Report');
            $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>Software Development Report');
            $('.hide_staff').css('display', 'block');
            $('.hardwareAndSofwateHide').css('display', 'none');
            $('#reportTypeSoftAndGeneral').val('Software');
        } else {
            $('#generateReportName').html('General Report');
            $('#generate_report').html('<i class="fa-solid fa-calendar-days me-2"></i>General Report');
        }
    }

    function alertModal(title, message, type) {
        $('#alertModal').modal('show');
        $('.alert-title').text(title);
        $('.alert-message').text(message);
        $('.alert-submit').remove();
        $('.alert-modal-btn').prepend($('<button>', {
            type: 'button',
            class: 'btn btn-link alert-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
            id: `alert_${type}_btn`,
            text: 'Yes, submit'
        }));
    }


    function submitReason(id) {
        $.ajax({
            url: '../controller/itasset_controller/it_request_main_contr.class.php',
            type: 'POST',
            data: {
                action: 'accomplishWithReason',
                id: id,
                logged_user: logged_user,
                reasonRemarks: $('#reasonRemarks').val()
            }
        });
        $('#reasonModal').modal('hide');
        $('#alertModal').modal('hide')
        $('#reasonRemarks').val('');
    }

    function submitCancelReason(id) {
        if ($('#reasonCancelRemarks').val() != '') {
            $.ajax({
                url: '../controller/itasset_controller/it_request_main_contr.class.php',
                type: 'POST',
                data: {
                    action: 'cancelhWithReason',
                    id: id,
                    logged_user: logged_user,
                    reasonCancelRemarks: $('#reasonCancelRemarks').val()
                }
            });
            $('#reasonCancelModal').modal('hide');
            $('#alertModal').modal('hide')
            $('#reasonCancelRemarks').val('');
        }
    }

    function handleAction(action, id, table, sender, request, technician) {
        tech = technician == null ? null : technician;
        if (action == 'cancel') {
            $('.submit-cancel-reason').val(id);
            $('#reasonCancelModal').modal('show');
        }
        if (action != 'cancel') {
            alertModal(action[0].toUpperCase() + action.slice(1), `Are you sure you want to ${action} this request?`, action);
        }
        $(`#alert_${action}_btn`).click(() => {
            let objData = action == 'accomplish' ? {
                action: action,
                id: id,
                sender: sender,
                request: request,
                logged_user: tech
            } : {
                action: action,
                id: id,
                logged_user: logged_user
            };
            if (action == 'accomplish') {
                $.ajax({
                    url: '../controller/itasset_controller/it_request_main_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_details',
                        id: id
                    },
                    success: (res) => {
                        const date = new Date();
                        let day = date.getDate();
                        let month = date.getMonth() + 1;
                        let year = date.getFullYear();
                        let curDate = `${year}-${month}-${day}`;
                        let date1 = new Date(res.date_needed);
                        let date2 = new Date(curDate);
                        if (date2 >= date1) {
                            $('.submit-reason').val(id);
                            $('#reasonModal').modal('show');
                        } else {
                            $.ajax({
                                url: '../controller/itasset_controller/it_request_main_contr.class.php',
                                type: 'POST',
                                data: objData,
                                success: () => refreshTable(),
                                complete: () => $('#alertModal').modal('hide')
                            });
                        }
                    }
                });
            } else if (action == 'reapprove') {
                $.ajax({
                    url: '../controller/itasset_controller/it_request_main_contr.class.php',
                    type: 'POST',
                    data: objData,
                    success: () => refreshTable(),
                    complete: () => $('#alertModal').modal('hide')
                });
            } else if (action == 'cancel') {
                $.ajax({
                    url: '../controller/itasset_controller/it_request_main_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'cancel',
                        id: id
                    },
                    success: () => refreshTable(),
                    complete: () => $('#alertModal').modal('hide')
                });
            } else {
                if (access_lvl == 'SPG') {
                    $.ajax({
                        url: '../controller/itasset_controller/it_request_main_contr.class.php',
                        type: 'POST',
                        data: objData,
                        success: () => refreshTable(),
                        complete: () => $('#alertModal').modal('hide')
                    });
                } else {
                    if (request == 'Hardware') {
                        $.ajax({
                            url: '../controller/itasset_controller/it_request_main_contr.class.php',
                            type: 'POST',
                            data: objData,
                            success: () => refreshTable(),
                            complete: () => $('#alertModal').modal('hide')
                        });
                    } else {
                        // $.ajax({
                        //     url: '../controller/itasset_controller/it_request_main_contr.class.php',
                        //     type: 'POST',
                        //     data: {
                        //         action: 'process',
                        //         id: id,
                        //         sender: sender,
                        //         logged_user: logged_user
                        //     },
                        //     success: () => refreshTable(),
                        //     complete: () => $('#alertModal').modal('hide')
                        // });
                        $('#technicianModal').modal('show');
                        $('#submit_technician').click(() => {
                            let technician = $('#technician').val();
                            $.ajax({
                                url: '../controller/itasset_controller/it_request_main_contr.class.php',
                                type: 'POST',
                                data: {
                                    action: 'process',
                                    id: id,
                                    sender: sender,
                                    logged_user: technician
                                },
                                success: result => {
                                    refreshTable()
                                },
                                complete: () => $('#alertModal').modal('hide')
                            });
                            $('#technicianModal').modal('hide');
                            $('#technician').val('');
                        });
                    }
                }
            }
        });
    }


    // function handleAction(action, id, table, sender, request) {
    //     alertModal(action[0].toUpperCase() + action.slice(1), `Are you sure you want to ${action} this request?`, action);
    //     $(`#alert_${action}_btn`).click(() => {
    //         let objData = action == 'accomplish' ? {
    //             action: action,
    //             id: id,
    //             sender: sender,
    //             logged_user: logged_user
    //         } : {
    //             action: action,
    //             id: id,
    //             logged_user: null
    //         };
    //         if (action == 'accomplish') {
    //             if (access_lvl == 'SPG') {
    //                 $.ajax({
    //                     url: '../controller/itasset_controller/it_request_main_contr.class.php',
    //                     type: 'POST',
    //                     data: objData,
    //                     success: () => refreshTable(),
    //                     complete: () => $('#alertModal').modal('hide')
    //                 });
    //             } else {
    //                 if (request == 'Hardware') {
    //                     $.ajax({
    //                         url: '../controller/itasset_controller/it_request_main_contr.class.php',
    //                         type: 'POST',
    //                         data: objData,
    //                         success: () => refreshTable(),
    //                         complete: () => $('#alertModal').modal('hide')
    //                     });
    //                 } else {
    //                     $('#technicianModal').modal('show');
    //                     $('#submit_technician').click(() => {
    //                         let technician = $('#technician').val();
    //                         $.ajax({
    //                             url: '../controller/itasset_controller/it_request_main_contr.class.php',
    //                             type: 'POST',
    //                             data: {
    //                                 action: 'accomplish',
    //                                 id: id,
    //                                 sender: sender,
    //                                 logged_user: technician
    //                             },
    //                             success: () => refreshTable(),
    //                             complete: () => $('#alertModal').modal('hide')
    //                         });
    //                         $('#technicianModal').modal('hide');
    //                         $('#technician').val('');
    //                     });
    //                 }
    //             }
    //         } else {
    //             $.ajax({
    //                 url: '../controller/itasset_controller/it_request_main_contr.class.php',
    //                 type: 'POST',
    //                 data: objData,
    //                 success: () => refreshTable(),
    //                 complete: () => $('#alertModal').modal('hide')
    //             });
    //         }
    //     });
    // }


    function refreshTable() {
        $('#cancel_request_table').DataTable().ajax.reload(null, false);
        $('#pending_request_table').DataTable().ajax.reload(null, false);
        $('#ongoing_request_table').DataTable().ajax.reload(null, false);
        $('#done_request_table').DataTable().ajax.reload(null, false);
    }
</script>