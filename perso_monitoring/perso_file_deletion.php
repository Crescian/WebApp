<?php include './../includes/header.php';
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
            <div class="row">
                <span class="page-title-perso">File Deletion</span>
            </div>
            <!-- content section -->
            <!-- Card Row -->
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="row row-cols-1 row-cols-md-4">
                        <!-- Certificate of Deletion Card -->
                        <div class="col mb-3">
                            <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#certificateDeletionModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Certificate of Deletion</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-regular fa-file-pdf fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s; --fa-flip-x: 1; --fa-flip-y: 0;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Certificate of Deletion Weekly Card -->
                        <div class="col mb-3">
                            <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#certificateWeeklyModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Certificate of Deletion Weekly</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-regular fa-file-pdf fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Certificate of Deletion Monthly Card -->
                        <div class="col mb-3">
                            <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#certificateMonthlyModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Certificate of Deletion Monthly</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-regular fa-file-pdf fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s; --fa-flip-x: 1; --fa-flip-y: 0;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Certificate of Deletion Quarterly Card -->
                        <div class="col mb-3">
                            <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#certificateQuarterlyModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Certificate of Deletion Quarterly</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-regular fa-file-pdf fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-4 justify-content-md-center">
                        <!-- File for Deletion Card -->
                        <div class="col mb-2">
                            <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#forCertificationModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">FILE FOR CERTIFICATION</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="for_certified_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-file-signature fa-3x text-gray-300 fa-bounce"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- File Received Card -->
                        <div class="col mb-2">
                            <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="fileChecklist();">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">FILE RECEIVED</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="for_received_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-file-circle-check fa-3x text-gray-300 fa-beat"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Manual Entry Card -->
                        <div class="col mb-2">
                            <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#manualFileEntryDeleteModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Manual Entry</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-file-circle-plus fa-3x text-gray-300 fa-bounce"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Holiday Entry Card -->
                        <div class="col mb-2">
                            <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" data-bs-toggle="modal" data-bs-target="#holidayEntryModal">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Holiday Entry</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-regular fa-calendar-days fa-3x text-gray-300 fa-beat"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mt-4">
                <div class="card-header card-4 py-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="fw-bold text-light">File List</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file_deleted_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderAdmin">
                                <tr>
                                    <th style="text-align:center;">Received Date</th>
                                    <th>Company</th>
                                    <th>Filename</th>
                                    <th style="text-align:center;">File Size</th>
                                    <th style="text-align:center;">Delivery Date</th>
                                    <th style="text-align:center;">Deleted Date</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderAdmin">
                                <tr>
                                    <th style="text-align:center;">Received Date</th>
                                    <th>Company</th>
                                    <th>Filename</th>
                                    <th style="text-align:center;">File Size</th>
                                    <th style="text-align:center;">Delivery Date</th>
                                    <th style="text-align:center;">Deleted Date</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- =============== Certificate Of Deletion Modal =============== -->
            <div class="modal fade" id="certificateDeletionModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">CERTIFICATE OF DELETION</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2 mb-3">
                                <select class="form-select fw-bold" id="deletion_company"></select>
                                <div class="invalid-feedback"></div>
                                <label for="deletion_company" class="fw-bold">Company</label>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rdoCertDeletion" id="chkReceivedDate" checked>
                                        <label class="form-check-label fw-bold" for="chkReceivedDate">Received Date</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rdoCertDeletion" id="chkDeletionDate">
                                        <label class="form-check-label fw-bold" for="chkDeletionDate">Deletion Date</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rdoCertDeletion" id="chkDeliveryDate">
                                        <label class="form-check-label fw-bold" for="chkDeliveryDate">Delivery Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm mb-3">
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="deletion_date_from" onchange="loadReferrenceNo();">
                                        <div class="invalid-feedback"></div>
                                        <label for="deletion_date_from" class="fw-bold">Date From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="deletion_date_to" onchange="loadReferrenceNo();">
                                        <div class="invalid-feedback"></div>
                                        <label for="deletion_date_to" class="fw-bold">Date To</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-control fw-bold" id="deletion_reference_no">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="deletion_reference_no" class="fw-bold">Reference No.</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill" onclick="previewFileDeletion();">Preview</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Certificate Of Deletion Weekly Modal =============== -->
            <div class="modal fade" id="certificateWeeklyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">CERTIFICATE OF DELETION WEEKLY</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2">
                                <select class="form-select fw-bold" id="weekly_company"></select>
                                <div class="invalid-feedback"></div>
                                <label for="weekly_company" class="fw-bold">Company</label>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rdoCertWeekly" id="chkWeeklyReceivedDate" checked>
                                        <label class="form-check-label fw-bold" for="chkWeeklyReceivedDate">Received Date</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rdoCertWeekly" id="chkWeeklyDeletionDate">
                                        <label class="form-check-label fw-bold" for="chkWeeklyDeletionDate">Deletion Date</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rdoCertWeekly" id="chkWeeklyDeliveryDate">
                                        <label class="form-check-label fw-bold" for="chkWeeklyDeliveryDate">Delivery Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm mb-3">
                                    <div class="form-floating">
                                        <input type="date" class="form-control fw-bold" id="weekly_deletion_date_from">
                                        <div class="invalid-feedback"></div>
                                        <label for="weekly_deletion_date_from" class="fw-bold">Date From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating">
                                        <input type="date" class="form-control fw-bold" id="weekly_deletion_date_to">
                                        <div class="invalid-feedback"></div>
                                        <label for="weekly_deletion_date_to" class="fw-bold">Date To</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill" onclick="previewWeekly();">Preview</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Certificate Of Deletion Monthly Modal =============== -->
            <div class="modal fade" id="certificateMonthlyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">CERTIFICATE OF DELETION MONTHLY</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2 mb-3">
                                <select class="form-select fw-bold" id="monthly_company"></select>
                                <div class="invalid-feedback"></div>
                                <label for="monthly_company" class="fw-bold">Company</label>
                            </div>
                            <div class="form-floating">
                                <input type="month" id="monthly_date" class="form-control fw-bold">
                                <div class="invalid-feedback"></div>
                                <label for="monthly_date" class="fw-bold">Month</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill" onclick="previewMonthly();">Preview</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Certificate Of Deletion Quarterly Modal =============== -->
            <div class="modal fade" id="certificateQuarterlyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">CERTIFICATE OF DELETION QUARTERLY</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2 mb-3">
                                <select class="form-select fw-bold" id="quarterly_company"></select>
                                <div class="invalid-feedback"></div>
                                <label for="quarterly_company" class="fw-bold">Company</label>
                            </div>
                            <div class="row">
                                <div class="col-sm mb-3">
                                    <div class="form-floating">
                                        <input type="month" class="form-control fw-bold" id="quarterly_month_from" onchange="checkRecordQuarterly();">
                                        <div class="invalid-feedback"></div>
                                        <label for="quarterly_month_from" class="fw-bold">Month From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating">
                                        <input type="month" class="form-control fw-bold" id="quarterly_month_to" onchange="checkRecordQuarterly();">
                                        <div class="invalid-feedback"></div>
                                        <label for="quarterly_month_to" class="fw-bold">Month To</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnQuarterlyPreview" onclick="quarterlyMonthly();">Preview</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== For Certification Modal =============== -->
            <div class="modal fade" id="forCertificationModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">FILE FOR CERTIFICATION</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <label class="form-check-label fw-bold"></label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="for_certification_company"></select>
                                        <div class="invalid-feedback"></div>
                                        <label for="for_certification_company" class="fw-bold">Company</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="chk_delivery_date">
                                        <label class="form-check-label fw-bold" for="chk_delivery_date">Delivery Date</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="date" class="form-control fw-bold" id="for_certification_date_from" onchange="fetch_for_certification();">
                                        <div class=" invalid-feedback"></div>
                                        <label for="for_certification_date_from" class="fw-bold">Date From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-check">
                                        <label class="form-check-label fw-bold"></label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="date" class="form-control fw-bold" id="for_certification_date_to" onchange="fetch_for_certification();">
                                        <div class="invalid-feedback"></div>
                                        <label for="for_certification_date_to" class="fw-bold">Date To</label>
                                    </div>
                                </div>
                            </div>

                            <div class="inputSection d-none">
                                <div class="table-responsive">
                                    <table id="for_certification_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th>File Name</th>
                                                <th style="text-align:center;">File Size</th>
                                                <th style="text-align:center;">Date Received</th>
                                                <th style="text-align:center;">Delivery Date</th>
                                                <th style="text-align:center;">Deleted Date</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">filedeletionid</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderAdmin">
                                            <tr>
                                                <th>File Name</th>
                                                <th style="text-align:center;">File Size</th>
                                                <th style="text-align:center;">Date Received</th>
                                                <th style="text-align:center;">Delivery Date</th>
                                                <th style="text-align:center;">Deleted Date</th>
                                                <th class="text-center"></th>
                                                <th style="text-align:center;">filedeletionid</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <hr>
                                <div class="row">
                                    <input type="hidden" id="gen_referrence_no" class="form-control fw-bold">
                                    <div class="col-sm">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="file_prepared_by" disabled>
                                            <label for="file_prepared_by" class="col-form-label fw-bold">Prepared By:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-floating mb-2">
                                            <select class="form-select fw-bold" id="file_checked_by" onchange="matchValidation();" disabled></select>
                                            <div class="invalid-feedback"></div>
                                            <label for="file_checked_by" class="col-form-label fw-bold">Checked By:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-floating mb-2">
                                            <input type="text" class="form-control fw-bold" id="file_noted_by" value="Mary Jane Ang" disabled>
                                            <label for="file_noted_by" class="col-form-label fw-bold">Noted By:</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-secondary col-sm-12 text-light fw-bold rounded-pill btnSaveFileCertification" onclick="saveFileCertification();" disabled>Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- =============== Manual File Entry Modal =============== -->
            <div class="modal fade" id="manualFileEntryDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">MANUAL FILE ENTRY/DELETE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2 mb-3">
                                <select class="form-select fw-bold" id="manual_file_company"></select>
                                <div class="invalid-feedback"></div>
                                <label for="manual_file_company" class="col-form-label fw-bold">Company:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="date" class="form-control fw-bold" id="manual_file_received_date">
                                <div class="invalid-feedback"></div>
                                <label for="manual_file_received_date" class="col-form-label fw-bold">Received Date:</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="manual_file_for_deletion_chk" onclick="toggleDeletionDate(this.checked);">
                                <label class="form-check-label fw-bold" for="manual_file_for_deletion_chk">Deletion:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="date" class="form-control fw-bold" id="manual_file_deletion_date" disabled>
                                <div class="invalid-feedback"></div>
                                <label for="manual_file_deletion_date" class="col-form-label fw-bold">Deleted Date:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="manual_file_filename">
                                <div class="invalid-feedback"></div>
                                <label for="manual_file_filename" class="col-form-label fw-bold">Filename:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="manual_file_file_size">
                                <div class="invalid-feedback"></div>
                                <label for="manual_file_file_size" class="col-form-label fw-bold">File Size:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveManualFile" onclick="saveManualFile();">Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Holiday Entry Modal =============== -->
            <div class="modal fade" id="holidayEntryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">HOLIDAY ENTRY</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="date" class="form-control fw-bold" id="holiday_date">
                                <div class="invalid-feedback"></div>
                                <label for="holiday_date" class="fw-bold">Holiday Date</label>
                            </div>
                            <hr>
                            <div class="table-responsive mb-3">
                                <table id="holiday_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Month</th>
                                            <th style="text-align:center;">Day</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Month</th>
                                            <th style="text-align:center;">Day</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveHoliday" onclick="saveHoliday();">Save</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== File Checklist Modal =============== -->
            <div class="modal fade" id="forFileChecklistModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">GENERATE CHECKLIST</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="date" class="form-control fw-bold" id="recieve_date_checklist" onchange="generateChecklist();">
                                <div class="invalid-feedback"></div>
                                <label for="recieve_date_checklist" class="fw-bold">Recieve Date</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnPreviewChecklist" onclick="previewChecklist();">Preview</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
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
include './../helper/input_validation.php';
include './../helper/select_values.php';
?>
<script>
    loadFileReceivedCertifiedCount();
    loadCompanySelect();
    loadFileDeletedTableData();
    loadSignatorySelect();
    loadHolidayTable();

    let for_certification_table;
    var dateToday = new Date().toISOString().slice(0, 10);
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    $('#for_certification_date_from').attr('max', dateToday);
    $('#deletion_date_from').attr('max', dateToday);
    $('#weekly_deletion_date_from').attr('max', dateToday);
    $('#recieve_date_checklist').attr('max', dateToday);
    $('#file_prepared_by').val(logged_user);

    // syncData();

    // function syncData() {
    //     $.ajax({
    //         url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
    //         type: 'POST',
    //         data: {
    //             action: 'sync_data'
    //         }
    //     });
    // }

    function loadFileDeletedTableData() {
        var file_deleted_list_table = $('#file_deleted_list_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_file_deleted_list_table'
                }
            },
            order: [
                [0, 'desc']
            ],
            'columnDefs': [{
                targets: [0, 4, 5],
                className: 'dt-body-middle-center',
                width: '11%'
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '17%'
            }, {
                targets: 2,
                className: 'dt-body-middle-left'
            }, {
                targets: 3,
                className: 'dt-body-middle-right',
                width: '10%',
            }, {
                targets: 6,
                className: 'dt-nowrap-center',
                width: '6%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return btnAction = `<button type="button" class="btn btn-info col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="View Information" onclick="viewFileInfo(${data});"><i class="fa-solid fa-circle-info fa-beat"></i></button>`;
                }
            }]
        });
        file_deleted_list_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 800);
        });
        setInterval(function() {
            file_deleted_list_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadHolidayTable() {
        var holiday_table = $('#holiday_table').DataTable({
            'lengthMenu': [
                [3, 25, 50, 100],
                [3, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_holiday_table_data'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-body-middle-center'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '10%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return btnAction = `<button type="button" class="btn btn-danger col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Remove Holiday" onclick="removeHoliday(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`;
                }
            }]
        });
        holiday_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 800);
        });
        setInterval(function() {
            holiday_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function previewFileDeletion() {
        if (inputValidation('deletion_company', 'deletion_date_from', 'deletion_date_to', 'deletion_reference_no')) {
            var strLink;
            var dcat;
            var file_company = document.getElementById('deletion_company').value;
            var date_from = document.getElementById('deletion_date_from').value;
            var date_to = document.getElementById('deletion_date_to').value;
            var file_reference_no = document.getElementById('deletion_reference_no').value;
            var chkReceivedDate = document.getElementById('chkReceivedDate').checked;
            var chkDeletionDate = document.getElementById('chkDeletionDate').checked;
            if (chkReceivedDate == true) {
                dcat = 'tf1';
            } else if (chkDeletionDate == true) {
                dcat = 'tf2';
            } else {
                dcat = 'ff1';
            }
            strLink = `certificate_of_file_deletion_pdf.php?dfrom=${date_from}&dto=${date_to}&dcat=${dcat}&ref=${file_reference_no}&comp=${file_company}`;
            window.open(strLink, '_blank');
            clearAttributes();
        }
    }

    function previewWeekly() {
        if (inputValidation('weekly_company', 'weekly_deletion_date_from', 'weekly_deletion_date_to')) {
            var strLink;
            var date;
            var company = document.getElementById('weekly_company').value;
            var datefrom = document.getElementById('weekly_deletion_date_from').value;
            var dateto = document.getElementById('weekly_deletion_date_to').value;
            var chkWeeklyReceivedDate = document.getElementById('chkWeeklyReceivedDate').checked;
            var chkWeeklyDeletionDate = document.getElementById('chkWeeklyDeletionDate').checked;
            if (chkWeeklyReceivedDate == true) {
                date = 'tf1';
            } else if (chkWeeklyDeletionDate == true) {
                date = 'tf2';
            } else {
                date = 'ff1';
            }
            strLink = `certificate_of_file_deletion_week_month_quart_pdf.php?d=wee&df=${datefrom}&dt=${dateto}&f=${date}&com=${company}`;
            window.open(strLink, '_blank');
            clearAttributes();
        }
    }

    function previewMonthly() {
        if (inputValidation('monthly_company', 'monthly_date')) {
            var strLink;
            var company = document.getElementById('monthly_company').value;
            var month_date = new Date(document.getElementById('monthly_date').value);
            var converted_month_date = ("0" + (month_date.getMonth() + 1)).slice(-2) + ' ' + month_date.getFullYear();
            //* =========== Add value for pdf viewer ===========
            strLink = `certificate_of_file_deletion_week_month_quart_pdf.php?d=mon&m=${converted_month_date}&com=${company}`;
            window.open(strLink, '_blank');
            clearAttributes();
        }
    }

    function checkRecordQuarterly() {
        var file_company = document.getElementById('quarterly_company').value;
        var date_from = new Date(document.getElementById('quarterly_month_from').value);
        var date_to = new Date(document.getElementById('quarterly_month_to').value);
        var converted_date_from = ("0" + (date_from.getMonth() + 1)).slice(-2) + ' ' + date_from.getFullYear();
        var converted_date_to = ("0" + (date_to.getMonth() + 1)).slice(-2) + ' ' + date_to.getFullYear();
        var diff_date_from = moment(date_from.getFullYear() + '-' + ("0" + (date_from.getMonth() + 1)).slice(-2) + '-01');
        var diff_date_to = moment(date_to.getFullYear() + '-' + ("0" + (date_to.getMonth() + 1)).slice(-2) + '-01');
        var dateDiff = diff_date_to.diff(diff_date_from, 'months');
        if (dateDiff > 2) {
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Invalid Date Range',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            $('.btnQuarterlyPreview').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
        } else {
            if (inputValidation('quarterly_month_from', 'quarterly_month_to')) {
                $.ajax({
                    url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'quarterly_record_check',
                        file_company: file_company,
                        date_from: converted_date_from,
                        date_to: converted_date_to
                    },
                    success: result => {
                        if (result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Record Found',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('.btnQuarterlyPreview').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'No Record Found',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            clearValue();
                            $('.btnQuarterlyPreview').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                        }
                    }
                });

            }
        }
    }

    function quarterlyMonthly() {
        if (inputValidation('quarterly_company', 'quarterly_month_from', 'quarterly_month_to')) {
            var strLink;
            var company = document.getElementById('quarterly_company').value;
            var date_from = new Date(document.getElementById('quarterly_month_from').value);
            var date_to = new Date(document.getElementById('quarterly_month_to').value);
            var converted_date_from = date_from.getFullYear() + ' ' + ("0" + (date_from.getMonth() + 1)).slice(-2);
            var converted_date_to = date_to.getFullYear() + ' ' + ("0" + (date_to.getMonth() + 1)).slice(-2);
            //* =========== Add value for pdf viewer ===========
            strLink = `certificate_of_file_deletion_week_month_quart_pdf.php?d=qua&df=${converted_date_from}&dt=${converted_date_to}&com=${company}`;
            window.open(strLink, '_blank');
            clearAttributes();
        }
    }

    function loadReferrenceNo() {
        $('#deletion_date_to').attr('min', $('#deletion_date_from').val());
        $('#deletion_date_from').attr('max', $('#deletion_date_to').val());
        var chkReceivedDate = document.getElementById('chkReceivedDate').checked;
        var chkDeletionDate = document.getElementById('chkDeletionDate').checked;

        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_referrence_no',
                file_company: $('#deletion_company').val(),
                date_from: $('#deletion_date_from').val(),
                date_to: $('#deletion_date_to').val(),
                chkReceivedDate: chkReceivedDate,
                chkDeletionDate: chkDeletionDate
            },
            success: result => {
                loadSelectValues('deletion_reference_no', result);
            }
        });
    }

    function fetch_for_certification() {
        $('#for_certification_date_to').attr('min', $('#for_certification_date_from').val());
        $('#for_certification_date_from').attr('max', $('#for_certification_date_to').val());
        generateReferrenceNo();

        if (inputValidation('for_certification_company', 'for_certification_date_from', 'for_certification_date_to')) {
            var chk_delivery_date = document.getElementById('chk_delivery_date').checked;
            $('.inputSection').removeClass('d-none');
            var rows_selected = [];

            for_certification_table = $('#for_certification_table').DataTable({
                'autoWidth': false,
                'responsive': true,
                'destroy': true,
                'searching': false,
                'deferRender': true,
                'processing': true,
                'serverSide': true,
                'ajax': {
                    url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_for_certification_data',
                        file_company: $('#for_certification_company').val(),
                        file_date_from: $('#for_certification_date_from').val(),
                        file_date_to: $('#for_certification_date_to').val(),
                        file_chk_delivery: chk_delivery_date
                    }
                },
                'columnDefs': [{
                    targets: 5,
                    className: 'dt-body-middle-center',
                    width: '5%',
                    orderable: false
                }, {
                    targets: 0,
                    className: 'dt-body-middle-left'
                }, {
                    targets: 1,
                    className: 'dt-body-middle-right',
                    width: '10%'
                }, {
                    targets: [2, 3, 4],
                    className: 'dt-body-middle-center',
                    width: '12%'
                }, {
                    targets: 6,
                    className: 'hide_column_datable'
                }],
                'rowCallback': function(row, data, dataIndex) {
                    var rowId = data[0]; //* Get row ID
                    if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                        $(row).find('input[type="checkbox"]').prop('checked', true);
                        $(row).addClass('selected');
                    }
                }
            });

            $('#for_certification_table tbody').on('click', '.rowChkBox', function(e) {
                var $row = $(this).closest('tr');
                var data = for_certification_table.row($row).data(); //* Get row data 

                var rowId = data[0]; //* Get row ID
                var index = $.inArray(rowId, rows_selected); //* Determine whether row ID is in the list of selected row IDs 

                if (this.checked && index === -1) { //* If checkbox is checked and row ID is not in list of selected row IDs
                    rows_selected.push(rowId);
                } else if (!this.checked && index !== -1) { //* Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                    rows_selected.splice(index, 1);
                }

                if (this.checked) {
                    $row.addClass('selected');
                } else {
                    $row.removeClass('selected');
                }

                updateDataTableSelectAllCtrl(for_certification_table); //* Update state of "Select all" control
                e.stopPropagation(); //* Prevent click event from propagating to parent
            });

            $('thead input[name="select_all"]', for_certification_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                if (this.checked) {
                    $('#for_certification_table tbody .rowChkBox:not(:checked)').trigger('click');
                } else {
                    $('#for_certification_table tbody .rowChkBox:checked').trigger('click');
                }
                e.stopPropagation(); //* Prevent click event from propagating to parent
            });

            for_certification_table.on('draw', function() { //* Handle table draw event
                updateDataTableSelectAllCtrl(for_certification_table); //* Update state of "Select all" control
            });
        }
    }

    $('#for_certification_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
        $(this).parent().find('input[type="checkbox"]').trigger('click');
    });

    function updateDataTableSelectAllCtrl(table) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody .rowChkBox', $table);
        var $chkbox_checked = $('tbody .rowChkBox:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);


        if ($chkbox_checked.length === 0) { //* If none of the checkboxes are checked
            chkbox_select_all.checked = false;
            $('.btnSaveFileCertification').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
            $('#file_checked_by').prop('disabled', true);
            // clearAttributes();

            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
        } else if ($chkbox_checked.length === $chkbox_all.length) { //* If all of the checkboxes are checked
            chkbox_select_all.checked = true;
            $('.btnSaveFileCertification').removeClass('btn-secondary').addClass('btn-success').prop('disabled', false);
            $('#file_checked_by').prop('disabled', false);

            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
        } else { //* If some of the checkboxes are checked
            chkbox_select_all.checked = true;
            $('.btnSaveFileCertification').removeClass('btn-secondary').addClass('btn-success').prop('disabled', false);
            $('#file_checked_by').prop('disabled', false);

            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }

    function saveFileCertification() {
        if (inputValidation('file_checked_by')) {
            var chk_delivery_date = document.getElementById('chk_delivery_date').checked;
            let data_values = [];
            let i;
            $.each($('.rowChkBox:checked'), function() {
                var data = $(this).parents('tr:eq(0)');
                data_values.push([
                    [$(data).find('td:eq(6)').text()]
                ]);
            });
            for (i = 0; i < data_values.length; i++) {
                var tblData = data_values[i];
                var strData = tblData.toString();
                var filedeletionid = strData[0];

                $.ajax({
                    url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                    type: 'POST',
                    data: {
                        action: 'save_file_certification',
                        filedeletionid: filedeletionid,
                        prepared_by: $('#file_prepared_by').val(),
                        checked_by: $('#file_checked_by').val(),
                        noted_by: $('#file_noted_by').val(),
                        reference_no: $('#gen_referrence_no').val()
                    }
                });
            }
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                type: 'POST',
                data: {
                    action: 'update_reference_no',
                    reference_no: $('#gen_referrence_no').val()
                }
            });
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'File/s have been successfully certified.',
                text: '',
                showConfirmButton: false,
                timer: 2000
            });

            if (i == data_values.length) {
                fetch_for_certification();
                $('#file_checked_by').find('option:first').prop('selected', 'selected');
                $('.btnSaveFileCertification').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
                clearAttributes();
            }
        }
    }

    function toggleDeletionDate(toggleVal) {
        if (toggleVal == true) {
            $('#manual_file_deletion_date').prop('disabled', false);
        } else {
            $('#manual_file_deletion_date').prop('disabled', true).removeClass('is-valid is-invalid').val('');
        }
    }

    function saveManualFile() {
        let isValidated = false;
        var manual_file_for_deletion_chk = document.getElementById('manual_file_for_deletion_chk').checked;

        if (inputValidation('manual_file_company', 'manual_file_received_date', 'manual_file_filename', 'manual_file_file_size')) {
            if (manual_file_for_deletion_chk == true) {
                if (inputValidation('manual_file_deletion_date')) {
                    isValidated = true;
                } else {
                    isValidated = false;
                }
            } else {
                isValidated = true;
            }
        } else {
            isValidated = false;
        }

        if (isValidated == true) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_manual_file_entry',
                    manual_file_company: $('#manual_file_company').val(),
                    manual_file_received_date: $('#manual_file_received_date').val(),
                    manual_file_filename: $('#manual_file_filename').val(),
                    manual_file_file_size: $('#manual_file_file_size').val(),
                    manual_file_for_deletion_chk: manual_file_for_deletion_chk,
                    manual_file_deletion_date: $('#manual_file_deletion_date').val()
                },
                success: result => {
                    console.log(result);
                    if (result == 'no record') {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'No record found!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Record already exist!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Record Successfully Updated!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#manualFileEntryDeleteModal').modal('hide');
                        clearValues();
                    }
                }
            });
        }
    }

    function fileChecklist() {
        $('#forFileChecklistModal').modal('show');
    }

    function generateChecklist() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'check_record',
                dateFilter: $('#recieve_date_checklist').val()
            },
            success: result => {
                if (result == 'no record') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'No record found!',
                        text: '',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('.btnPreviewChecklist').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Record found!',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('.btnPreviewChecklist').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                }
            }
        });
    }

    function previewChecklist() {
        if (inputValidation('recieve_date_checklist')) {
            var strLink;
            var dateFilter = document.getElementById('recieve_date_checklist').value;
            strLink = `file_received_checklist_pdf.php?df=${dateFilter}`;
            window.open(strLink, '_blank');
            clearAttributes();
        }
    }

    function viewFileInfo(filedeletionid) {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Under Maintenance!',
            text: '',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function saveHoliday() {
        if (inputValidation('holiday_date')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_holiday_date',
                    holiday_date: $('#holiday_date').val()
                },
                success: result => {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Holiday already exist!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#holiday_date').focus();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Holiday successfully added!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }
    }

    function removeHoliday(holidayid) {
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
                    url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
                    type: 'POST',
                    data: {
                        action: 'remove_holiday_date',
                        holidayid: holidayid
                    },
                    success: result => {
                        $('#holiday_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Holiday deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    function loadFileReceivedCertifiedCount() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_file_received_count'
            },
            success: result => {
                $('#for_received_count').html(result);
            }
        });
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_file_certified_count'
            },
            success: result => {
                $('#for_certified_count').html(result);
            }
        });
        setTimeout(function() {
            loadFileReceivedCertifiedCount();
        }, 1000);
    }

    function loadCompanySelect() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_company_select_values'
            },
            success: result => {
                loadSelectValues('for_certification_company', result);
                loadSelectValues('deletion_company', result);
                loadSelectValues('weekly_company', result);
                loadSelectValues('monthly_company', result);
                loadSelectValues('quarterly_company', result);
                loadSelectValues('manual_file_company', result);
            }
        });
    }

    function loadSignatorySelect() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_signatory_select_values'
            },
            success: result => {
                loadSelectValues('file_checked_by', result);
            }
        });
    }

    function generateReferrenceNo() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_file_deletion_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'gen_referrence_no'
            },
            success: result => {
                $('#gen_referrence_no').val(result);
            }
        });
    }

    function matchValidation() {
        if ($('#file_prepared_by').val() === $('#file_checked_by').val()) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'File Certification',
                text: 'Prepared By and Checked By Matched, can not proceed.',
                showConfirmButton: false,
                timer: 3000
            });
            $('.btnSaveFileCertification').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
            $('#file_checked_by').focus();
        } else {
            $('.btnSaveFileCertification').removeClass('btn-secondary').addClass('btn-success').prop('disabled', false);
        }
    }

    function clearValues() {
        clearAttributes();
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('input[type=checkbox]').prop('checked', false);
        $('input[type=radio]').prop('checked', false);
        $('.inputSection').addClass('d-none');
        $('.btnSaveFileCertification').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
        $("#deletion_reference_no").empty();
        setTimeout(function() {
            optionText = "Choose...";
            optionValue = "";
            let optionExists = ($(`#deletion_reference_no option[value="${optionValue}"]`).length > 0);
            if (!optionExists) {
                $('#deletion_reference_no').append(`<option value="${optionValue}">${optionText}</option>`);
            }
        }, 100);
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>