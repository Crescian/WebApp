<?php include './../includes/header.php';
session_start();
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
// * Check if module is within the application
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
                <span class="page-title-perso">Job Entry</span>
            </div>
            <div class="row mt-5 mb-4"> <!-- =========== Job Entry Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light" id="process_division_title">Job List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addEntryModal();"><i class="fa-solid fa-square-plus p-r-8"></i> New Job Entry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="jobEntry_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Target Date</th>
                                            <th style="text-align:center;">Release Date</th>
                                            <th>Template Name</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Cut-Off</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Target Date</th>
                                            <th style="text-align:center;">Release Date</th>
                                            <th>Template Name</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Cut-Off</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== Job Entry Section End =========== -->
            <div class="row mt-5 mb-4"><!-- =========== Job Entry Done Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light" id="process_division_title">Job Done</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="jobentryarchive();"><i class="fa-solid fa-file-zipper p-r-8"></i> Archive</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="jobDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Target Date</th>
                                            <th style="text-align:center;">Release Date</th>
                                            <th>Template Name</th>
                                            <th style="text-align:center;">Info</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Target Date</th>
                                            <th style="text-align:center;">Release Date</th>
                                            <th>Template Name</th>
                                            <th style="text-align:center;">Info</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =========== Job Entry Done Section End =========== -->
            <!-- =============== Add Job Entry Modal =============== -->
            <div class="modal fade" id="addJobEntryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4 d-flex justify-content-between align-items-center">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="job_entry_title"></h4>
                            <div>
                                <button class="btn btn-dark fw-bold" id="scan_barcode" onclick="scanBarcodeAutoFill();"><i class="fa-solid fa-barcode me-2"></i>Scan Barcode</button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="company" id="company"></select>
                                        <div class="invalid-feedback"></div>
                                        <label for="company" class="col-form-label fw-bold">Company</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="jonumber">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="jonumber" class="col-form-label fw-bold">Job Order</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="job_description" disabled>
                                        <input type="hidden" class="form-control fw-bold" id="orderid" disabled>
                                        <label for="job_description" class="col-form-label fw-bold">Description</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="job_template">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="job_template" class="col-form-label fw-bold">Template Name</label>
                                    </div>
                                    <div id="filename_section"></div>
                                    <!-- <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="job_filename"></select>
                                        <input type="text" class="form-control fw-bold" id="job_filename" placeholder="Filename">
                                        <div class="invalid-feedback"></div>
                                        <label for="job_filename" class="col-form-label fw-bold">Filename</label>
                                    </div> -->
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="dateEntry" disabled>
                                                <div class="invalid-feedback"></div>
                                                <label for="dateEntry" class="col-form-label fw-bold">Date Entry</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="date" class="form-control fw-bold" id="releaseDate">
                                                <div class="invalid-feedback"></div>
                                                <label for="releaseDate" class="col-form-label fw-bold">Release Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="date" class="form-control fw-bold" id="dateReceive">
                                                <div class="invalid-feedback"></div>
                                                <label for="dateReceive" class="col-form-label fw-bold">Date Receive</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="job_cutoff">
                                                    <option value="Within Cut-Off">Within Cut-Off</option>
                                                    <option value="Beyond Cut-Off">Beyond Cut-Off</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label for="job_cutoff" class="col-form-label fw-bold">Cut-Off</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold text-center" id="job_quantity" placeholder="Quantity">
                                                <div class="invalid-feedback"></div>
                                                <label for="job_quantity" class="col-form-label fw-bold">Quantity</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="mode_delivery">
                                                    <option value="">Choose...</option>
                                                    <option value="Pick up">Pick up</option>
                                                    <option value="Delivery">Delivery</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label for="mode_delivery" class="col-form-label fw-bold">Mode of Delivery</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="pickup_courier">
                                                    <option value="">Choose...</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label for="pickup_courier" class="col-sm-3 col-form-label fw-bold">Courier</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" name="flexChkJob" id="job_chk_hold"><label class="form-check-label fw-bold fs-15 p-l-8" for="job_chk_hold">Hold Job</label>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="hidden" class="form-control fw-bold text-center" id="job_status" disabled>
                                    </div>
                                </div>
                            </div><!-- =============== Add Job Entry Row End =============== -->
                            <hr>
                            <div class="row"><!-- =============== Process and Material List Row =============== -->
                                <div class="col-sm-6 mb-3">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bolder text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="processList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead class="customHeaderAdmin">
                                                        <tr>
                                                            <th width="10%">SEQ#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Process List End =============== -->
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bolder text-light">Material List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="materialList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead class="customHeaderAdmin">
                                                        <tr>
                                                            <th width="10%" class="text-center">#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Material List End =============== -->
                            </div><!-- =============== Process and Material List Row End =============== -->
                            <div class="process_timeline"><!-- =============== Process Timeline =============== -->
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="printing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Printing Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="printing_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="embossing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Embossing Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="embossing_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="packaging_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Packaging Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="packaging_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="vault_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Vault Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="vault_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="dispatching_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Dispatching Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="dispatching_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="sticker_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Sticker List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="sticker_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="carrier_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Carrier List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="carrier_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="sim_pairing_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Sim Pairing List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="sim_pairing_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="waybill_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Waybill List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="waybill_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="logsheet_checklist_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Logsheet/Checklist List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="logsheet_checklist_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="data_preparation_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Data Preparation List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data_preparation_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="card_and_form_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Card and Form List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="card_and_form_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="collateral_for_request_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Collateral for Request List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="collateral_for_request_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- =============== Process Timeline End =============== -->
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 btnUpdateJobEntry" onclick="updateJobEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col-sm-12 btnSaveJobEntry" onclick="saveJobEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col-sm-12" data-bs-dismiss="modal" onclick="clearValues('jobEntry');"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Job Entry Modal End =============== -->
            <!-- =============== Job Done Modal =============== -->
            <div class="modal fade" id="jobDoneInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Job Done</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="done_company" disabled></input>
                                        <label for="done_company" class="col-form-label fw-bold">Company</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="done_jonumber" disabled></input>
                                        <label for="done_jonumber" class="col-form-label fw-bold">Job Order</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="done_job_description" disabled>
                                        <label for="done_job_description" class="col-form-label fw-bold">Description</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-control fw-bold" id="done_job_template" disabled></select>
                                        <label for="done_job_template" class="col-form-label fw-bold">Template Name</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="done_job_filename" disabled>
                                        <label for="done_job_filename" class="col-form-label fw-bold">Filename</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="done_dateEntry" disabled>
                                                <label for="done_dateEntry" class="col-form-label fw-bold">Date Entry</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="done_releaseDate" disabled>
                                                <label for="done_releaseDate" class="col-form-label fw-bold">Release Date</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="done_dateReceive" disabled>
                                                <label for="done_dateReceive" class="col-form-label fw-bold">Date Receive</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="done_job_cutoff" disabled>
                                                <label for="done_job_cutoff" class="col-form-label fw-bold">Cut-Off</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold text-center" id="done_job_quantity" disabled>
                                                <label for="done_job_quantity" class="col-form-label fw-bold">Quantity</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="done_mode_delivery" disabled></input>
                                                <label for="done_mode_delivery" class="col-form-label fw-bold">Mode of Delivery</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold" id="done_pickup_courier" disabled></input>
                                                <label for="done_pickup_courier" class="col-sm-3 col-form-label fw-bold">Courier</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" name="flexDoneChkJob" id="done_job_chk_hold" disabled><label class="form-check-label fw-bold fs-15 p-l-8" for="done_job_chk_hold">Hold Job</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row"><!-- =============== Process and Material List Row =============== -->
                                <div class="col-sm-6 mb-3">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bolder text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="done_processList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead class="customHeaderAdmin">
                                                        <tr>
                                                            <th width="10%">SEQ#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Process List End =============== -->
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bolder text-light">Material List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="done_materialList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead class="customHeaderAdmin">
                                                        <tr>
                                                            <th width="10%" class="text-center">#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Material List End =============== -->
                            </div><!-- =============== Process and Material List Row End =============== -->
                            <div class="done_process_timeline d-none">
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_printing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Printing Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_printing_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_embossing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Embossing Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_embossing_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_packaging_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Packaging Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_packaging_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_vault_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Vault Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_vault_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_dispatching_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Dispatching Division</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_dispatching_division_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_sticker_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Sticker List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_sticker_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_carrier_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Carrier List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_carrier_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_sim_pairing_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Sim Pairing List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_sim_pairing_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_waybill_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Waybill List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_waybill_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_logsheet_checklist_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Logsheet/Checklist List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_logsheet_checklist_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_data_preparation_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Data Preparation List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_data_preparation_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_card_and_form_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Card and Form List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_card_and_form_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <div class="table-responsive">
                                                    <table id="done_collateral_for_request_list_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                        <thead class="customHeaderAdmin">
                                                            <tr>
                                                                <th width="40%">Collateral for Request List</th>
                                                                <th width="12%" style="text-align:center;">Quantity</th>
                                                                <th width="10%" style="text-align:center;">Status</th>
                                                                <th width="30%" style="text-align:center;">Remarks</th>
                                                                <th width="5%" style="text-align:center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="done_collateral_for_request_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues('jobDone');"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Job Done Modal End =============== -->
            <!-- =============== Process Timeline Info Modal =============== -->
            <div class="modal fade" id="processTimelineInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Process Information</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" id="process_info_time_start" disabled>
                                        <label for="process_info_time_start" class="col-form-label fw-bold">Time Start</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" id="process_info_time_end" disabled>
                                        <label for="process_info_time_end" class="col-form-label fw-bold">Time End</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div id="operator_container"></div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" id="process_info_status" disabled>
                                        <label for="process_info_status" class="col-form-label fw-bold">Status</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mt-3 mb-2">
                                <textarea id="process_info_instruction" class="form-control fw-bold" style="resize:none;height: 120px" disabled></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="process_info_instruction" class="col-form-label fw-bold">Instructions</label>
                            </div>
                            <div class="form-floating mt-3 mb-2">
                                <textarea id="process_info_operator_remarks" class="form-control fw-bold" style="resize:none;height: 120px" disabled></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="process_info_operator_remarks" class="col-form-label fw-bold">Operator Remarks</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Process Timeline Info Modal End =============== -->
            <!-- =============== Material Timeline Info Modal =============== -->
            <div class="modal fade" id="materialTimelineInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Material Information</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" id="material_info_time_start" disabled>
                                        <label for="material_info_time_start" class="col-form-label fw-bold">Time Start</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" id="material_info_time_end" disabled>
                                        <label for="material_info_time_end" class="col-form-label fw-bold">Time End</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div id="material_operator_container"></div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" id="material_info_status" disabled>
                                        <label for="material_info_status" class="col-form-label fw-bold">Status</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mt-3 mb-2">
                                <textarea id="material_info_operator_remarks" class="form-control fw-bold" style="resize:none;height: 120px" disabled></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="material_info_operator_remarks" class="col-form-label fw-bold">Operator Remarks</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Material Timeline Info Modal End =============== -->
            <!-- =============== Job Archive Modal =============== -->
            <div class="modal fade" id="jobArchiveModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="archive_title_modal"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="month" class="form-control fw-bold" name="archive_month_from" id="archive_month_from">
                                        <div class="invalid-feedback"></div>
                                        <label for="archive_month_from" class="col-form-label fw-bold">From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="month" class="form-control fw-bold" name="archive_month_to" id="archive_month_to">
                                        <div class="invalid-feedback"></div>
                                        <label for="archive_month_to" class="col-form-label fw-bold">To</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success btnArchiveJobEntry" onclick="jobArchive();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Archive</button>
                            <button type="button" class="btn btn-danger btnArchiveClose" data-bs-dismiss="modal" onclick="clearValues('archive');"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Job Archive Modal End =============== -->
            <!-- =============== Scan Barcode Modal =============== -->
            <div class="modal fade" id="scanBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">SCAN BARCODE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold text-center" name="scanned_barcode" id="scanned_barcode">
                                <label for="scanned_barcode" class="col-form-label fw-bold">Barcode:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-danger btnArchiveClose" data-bs-dismiss="modal" onclick="clearValues('scan_barcode');"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
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
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;"></div>
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
    let dateToday = new Date().toISOString().slice(0, 10);

    loadJobEntryTable();
    loadJobDoneTable();
    loadCompanyName();

    function loadJobEntryTable() {
        var jobEntry_table = $('#jobEntry_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'deferRender': true,
            'responsive': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'load_job_entry_table_data'
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
                    targets: [0, 1, 7, 8],
                    className: 'dt-body-middle-center'
                }, {
                    targets: [2, 3, 4, 5, 9],
                    className: 'dt-body-middle-left'
                },
                {
                    targets: 6,
                    className: 'dt-body-middle-right'
                }, {
                    targets: [10, 11],
                    className: 'dt-body-middle-center',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: 12,
                    className: 'dt-nowrap-center',
                    width: '5%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col btnViewJobEntry" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="infoJobEntryModal('${data[0]}');"><i class="fa-solid fa-circle-info fa-bounce"></i></button>
                    <button type="button" class="btn btn-danger col btnDeleteJobEntry" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete Job" onclick="deleteJobEntry('${data[0]}','${data[1]}');"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                    }
                }
            ]
        });
        jobEntry_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            jobEntry_table.ajax.reload(null, false);
        }, 30000); //* ======== Reload Table Data Every X seconds with pagination retained ========
    }

    function loadJobDoneTable() {
        var jobDone_table = $('#jobDone_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'deferRender': true,
            'responsive': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'load_job_done_table_data'
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
                    targets: [0, 1, 7, 8],
                    className: 'dt-body-middle-center'
                }, {
                    targets: [2, 3, 4, 5, 9],
                    className: 'dt-body-middle-left'
                },
                {
                    targets: 6,
                    className: 'dt-body-middle-right'
                }, {
                    targets: 10,
                    className: 'dt-nowrap-center',
                    width: '5%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col btnViewJobEntry" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobDoneInfo('${data}');"><i class="fa-solid fa-circle-info fa-bounce"></i></button>`
                    }

                }
            ]
        });
        jobDone_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            jobDone_table.ajax.reload(null, false);
        }, 30000); //* ======== Reload Table Data Every X seconds with pagination retained ========
    }

    function addEntryModal() {
        $('#addJobEntryModal').modal('show');
        $('#job_entry_title').html('JOB INFORMATION ENTRY');
        $('#dateEntry').val(dateToday);
        $('#releaseDate').attr('min', dateToday);
        $('.btnUpdateJobEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveJobEntry').prop('disabled', false).css('display', 'block');
        $('.process_timeline').addClass('d-none');
        $('#job_status').val('Pending');
        $('#filename_section').html(`<div class="form-floating mb-2">
        <input type="text" class="form-control fw-bold filename_single" id="job_filename" placeholder="Filename">
        <div class="invalid-feedback"></div>
        <label for="job_filename" class="col-form-label fw-bold">Filename</label></div>`);
    }

    function saveJobEntry() {
        if (inputValidation('company', 'jonumber', 'job_filename', 'job_template', 'dateReceive', 'job_quantity', 'mode_delivery')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_job_entry',
                    company: $('#company').val(),
                    jonumber: $('#jonumber').val(),
                    job_description: $('#job_description').val(),
                    orderid: $('#orderid').val(),
                    job_filename: $('#job_filename').val(),
                    job_template: $('#job_template').val(),
                    releaseDate: $('#releaseDate').val(),
                    dateReceive: $('#dateReceive').val(),
                    job_cutoff: $('#job_cutoff').val(),
                    job_quantity: $('#job_quantity').val(),
                    mode_delivery: $('#mode_delivery').val(),
                    pickup_courier: $('#pickup_courier').val(),
                    job_chk_hold: $('#job_chk_hold').is(':checked')
                },
                success: function(result) {
                    if (result.jobentryid == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Filename Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        $('#job_filename').focus();
                        clearAttributes();
                    } else {
                        $.ajax({
                            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_tempprocess_temp_material',
                                jobentryid: result.jobentryid,
                                templateid: $('#job_template').val(),
                                job_chk_hold: $('#job_chk_hold').is(':checked')
                            },
                            success: function(result) {
                                Swal.fire({
                                    position: 'top',
                                    icon: 'success',
                                    title: 'Save Successfully.',
                                    text: '',
                                    showConfirmButton: false,
                                    timer: 800
                                });
                                clearValues('multipleEntry');
                                $('#jobEntry_table').DataTable().ajax.reload(null, false);
                            }
                        });
                    }
                }
            });
        }
    }

    function infoJobEntryModal(jobentryid) {
        $('#addJobEntryModal').modal('show');
        $('#job_entry_title').html('JOB INFORMATION UPDATE');
        $('.btnUpdateJobEntry').prop('disabled', false).css('display', 'block');
        $('.btnSaveJobEntry').prop('disabled', true).css('display', 'none');
        $('.process_timeline').removeClass('d-none');
        $('#filename_section').html(`<div class="form-floating mb-2">
        <input type="text" class="form-control fw-bold filename_single" id="job_filename" placeholder="Filename">
        <div class="invalid-feedback"></div>
        <label for="job_filename" class="col-form-label fw-bold">Filename</label></div>`);
        addInputDisabled();

        loadJobentryProcessDivision('Printing Division', jobentryid, 'update');
        loadJobentryProcessDivision('Embossing Division', jobentryid, 'update');
        loadJobentryProcessDivision('Packaging Division', jobentryid, 'update');
        loadJobentryProcessDivision('Vault Division', jobentryid, 'update');
        loadJobentryProcessDivision('Dispatching Division', jobentryid, 'update');

        loadMaterialListDivisions('Sticker Section', jobentryid, 'update');
        loadMaterialListDivisions('Data Preparation Section', jobentryid, 'update');
        loadMaterialListDivisions('Logsheet Checklist Section', jobentryid, 'update');
        loadMaterialListDivisions('Collateral Section', jobentryid, 'update');
        loadMaterialListDivisions('Waybill Section', jobentryid, 'update');
        loadMaterialListDivisions('Sim Pairing Section', jobentryid, 'update');
        loadMaterialListDivisions('Card and Form Section', jobentryid, 'update');
        loadMaterialListDivisions('Carrier Section', jobentryid, 'update');

        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_entry_info',
                jobentryid: jobentryid
            },
            success: function(result) {
                $('#company').val(result.customer_name).change();
                setTimeout(function() {
                    $('#jonumber').val(result.jonumber).change();
                }, 200);
                $('#job_description').val(result.job_description);
                $('#orderid').val(result.orderid);
                setTimeout(function() {
                    $('#job_template').val(result.template_id).change();
                }, 450);
                $('#job_filename').val(result.job_filename);
                $('#dateEntry').val(result.date_entry);
                $('#releaseDate').val(result.release_date);
                $('#dateReceive').val(result.date_receive);
                $('#job_cutoff').val(result.job_cutoff);
                $('#job_quantity').val(result.job_quantity).each(function() { //* ======== Format quantity with Commas ========
                    $(this).val(function(index, value) {
                        return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    });
                });
                $('#mode_delivery').val(result.mode_delivery).change();
                setTimeout(function() {
                    $('#pickup_courier').val(result.pickup_courier);
                }, 200);
                if (result.job_status == 'Hold') {
                    $('#job_chk_hold').prop('checked', true);
                    $('#releaseDate').val('');
                    $('#releaseDate').prop('disabled', true);
                } else {
                    $('#job_chk_hold').prop('checked', false);
                    $('#releaseDate').val(result.release_date);
                }
                if (result.job_status == 'On-Going' || result.job_status == 'Process Hold') {
                    $('#releaseDate').prop('disabled', false);
                    $('#dateReceive').prop('disabled', false);
                    $('#job_cutoff').prop('disabled', false);
                    $('#mode_delivery').prop('disabled', false);
                    $('#pickup_courier').prop('disabled', false);
                    $('#job_quantity').prop('disabled', false);
                    $('#job_filename').prop('disabled', false);
                } else {
                    removeInputDisabled();
                }
                $('#job_status').val(result.job_status);
                $('.btnUpdateJobEntry').val(jobentryid);
            }
        });
    }

    function updateJobEntry(jobentryid) {
        if (inputValidation('company', 'jonumber', 'job_filename', 'job_template', 'dateReceive', 'job_quantity', 'mode_delivery')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_job_entry',
                    jobentryid: jobentryid,
                    company: $('#company').val(),
                    jonumber: $('#jonumber').val(),
                    job_description: $('#job_description').val(),
                    orderid: $('#orderid').val(),
                    job_filename: $('#job_filename').val(),
                    job_template: $('#job_template').val(),
                    releaseDate: $('#releaseDate').val(),
                    dateReceive: $('#dateReceive').val(),
                    date_entry: $('#dateEntry').val(),
                    job_cutoff: $('#job_cutoff').val(),
                    job_quantity: $('#job_quantity').val(),
                    mode_delivery: $('#mode_delivery').val(),
                    pickup_courier: $('#pickup_courier').val(),
                    job_status: $('#job_status').val()
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Filename Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        $('#job_filename').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        $('#jobEntry_table').DataTable().ajax.reload(null, false);
                        clearProcessTableMaterialTable();
                        setTimeout(function() {
                            loadJobentryProcessDivision('Printing Division', jobentryid, 'update');
                            loadJobentryProcessDivision('Embossing Division', jobentryid, 'update');
                            loadJobentryProcessDivision('Packaging Division', jobentryid, 'update');
                            loadJobentryProcessDivision('Vault Division', jobentryid, 'update');
                            loadJobentryProcessDivision('Dispatching Division', jobentryid, 'update');

                            loadMaterialListDivisions('Sticker Section', jobentryid, 'update');
                            loadMaterialListDivisions('Data Preparation Section', jobentryid, 'update');
                            loadMaterialListDivisions('Logsheet Checklist Section', jobentryid, 'update');
                            loadMaterialListDivisions('Collateral Section', jobentryid, 'update');
                            loadMaterialListDivisions('Waybill Section', jobentryid, 'update');
                            loadMaterialListDivisions('Sim Pairing Section', jobentryid, 'update');
                            loadMaterialListDivisions('Card and Form Section', jobentryid, 'update');
                            loadMaterialListDivisions('Carrier Section', jobentryid, 'update');
                        }, 250);
                    }
                    clearAttributes();
                }
            });
        }
    }

    function deleteJobEntry(jobentryid, jobstatus) {
        if (jobstatus == 'On-Going' || jobstatus == 'Process Hold') {
            Swal.fire({
                position: 'top',
                icon: 'info',
                title: 'Job already in process, cannot make any changes.',
                text: '',
                showConfirmButton: false,
                timer: 800
            });
        } else {
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
                        url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'delete_job_entry',
                            jobentryid: jobentryid
                        },
                        success: function(result) {
                            $('#jobEntry_table').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                'Deleted!',
                                'Job Entry deleted.',
                                'success'
                            )
                        }
                    });
                }
            });
        }
    }

    function jobDoneInfo(jobentryid) {
        $('#jobDoneInfoModal').modal('show');
        $('.done_process_timeline').removeClass('d-none');
        loadJobentryProcessDivision('Printing Division', jobentryid, 'done');
        loadJobentryProcessDivision('Embossing Division', jobentryid, 'done');
        loadJobentryProcessDivision('Packaging Division', jobentryid, 'done');
        loadJobentryProcessDivision('Vault Division', jobentryid, 'done');
        loadJobentryProcessDivision('Dispatching Division', jobentryid, 'done');

        loadMaterialListDivisions('Sticker Section', jobentryid, 'done');
        loadMaterialListDivisions('Data Preparation Section', jobentryid, 'done');
        loadMaterialListDivisions('Logsheet Checklist Section', jobentryid, 'done');
        loadMaterialListDivisions('Collateral Section', jobentryid, 'done');
        loadMaterialListDivisions('Waybill Section', jobentryid, 'done');
        loadMaterialListDivisions('Sim Pairing Section', jobentryid, 'done');
        loadMaterialListDivisions('Card and Form Section', jobentryid, 'done');
        loadMaterialListDivisions('Carrier Section', jobentryid, 'done');

        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_entry_info',
                jobentryid: jobentryid
            },
            success: function(result) {
                $('#done_company').val(result.customer_name);
                $('#done_jonumber').val(result.jonumber);
                $('#done_job_description').val(result.job_description);
                $('#done_job_filename').val(result.job_filename);
                loadTemplate(result.jonumber, result.orderid, result.customer_name, 'doneJobEntry')
                setTimeout(function() {
                    $('#done_job_template').val(result.template_id).change();
                }, 200);
                $('#done_dateEntry').val(result.date_entry);
                $('#done_releaseDate').val(result.release_date);
                $('#done_dateReceive').val(result.date_receive);
                $('#done_job_cutoff').val(result.job_cutoff);
                $('#done_job_quantity').val(result.job_quantity).each(function() { //* ======== Format quantity with Commas ========
                    $(this).val(function(index, value) {
                        return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    });
                });
                $('#done_mode_delivery').val(result.mode_delivery);
                $('#done_pickup_courier').val(result.pickup_courier);
                if (result.job_status == 'Hold') {
                    $('#done_job_chk_hold').prop('checked', true);
                } else {
                    $('#done_job_chk_hold').prop('checked', false);
                }
            }
        });
    }

    function loadCompanyName() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_company_name'
            },
            success: function(result) {
                $("#company").empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#company option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#company').append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result.customer_name != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#company option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#company').append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    }

    $('#company').change(function() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_order_number',
                companyname: $(this).val()
            },
            success: function(result) {
                $("#jonumber").empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#jonumber option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#jonumber').append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result.customer_name != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#jonumber option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#jonumber').append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    });

    $('#jonumber').change(function() {
        var jonumber = $(this).val();
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_description',
                jonumber: jonumber
            },
            success: function(result) {
                $('#job_description').val(result.descriptions);
                $('#orderid').val(result.orderid);
                loadTemplate(jonumber, result.orderid, $('#company').val(), 'jobEntry');
            }
        });
    });

    function loadTemplate(jonumber, orderid, company, category) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_template_name',
                company: company,
                jonumber: jonumber,
                orderid: orderid
            },
            success: function(result) {
                var inObject;
                if (category == 'jobEntry') {
                    inObject = 'job_template';
                } else {
                    inObject = 'done_job_template';
                }
                $('#' + inObject).empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#` + inObject + ` option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $("#" + inObject).append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result.templateid != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#` + inObject + ` option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $("#" + inObject).append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    }

    $('#job_template').change(function() {
        loadTemplateDetailsInfo($(this).val(), 'jobEntry');
    });

    $('#done_job_template').change(function() {
        loadTemplateDetailsInfo($(this).val(), 'donejobEntry');
    });

    function loadTemplateDetailsInfo(templateid, category) {
        if (templateid == '') {
            $("#processList_table").find("tr:gt(0)").remove();
            $("#done_processList_table").find("tr:gt(0)").remove();
            $("#materialList_table").find("tr:gt(0)").remove();
            $("#done_materialList_table").find("tr:gt(0)").remove();
        } else {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_template_process',
                    templateid: templateid
                },
                success: function(result) {
                    if (category == 'jobEntry') {
                        $("#processList_table").find("tr:gt(0)").remove();
                        setTimeout(function() {
                            let tableRow = '';
                            $.each(result, (key, value) => {
                                tableRow += '<tr><td style="vertical-align:middle;text-align:center;font-weight: bold;" disabled>' + value.process_sequence + '</td>';
                                tableRow += '<td style="vertical-align:middle;font-weight: bold;" disabled>' + value.process_name + '</td></tr>';
                            });
                            $('#processList_table').append(tableRow);
                        }, 200);
                    } else {
                        $("#done_processList_table").find("tr:gt(0)").remove();
                        setTimeout(function() {
                            let tableRow = '';
                            $.each(result, (key, value) => {
                                tableRow += '<tr><td style="vertical-align:middle;text-align:center;font-weight: bold;" disabled>' + value.process_sequence + '</td>';
                                tableRow += '<td style="vertical-align:middle;font-weight: bold;" disabled>' + value.process_name + '</td></tr>';
                            });
                            $('#done_processList_table').append(tableRow);
                        }, 100);
                    }
                }
            });

            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_template_material',
                    templateid: templateid
                },
                success: function(result) {
                    if (category == 'jobEntry') {
                        $("#materialList_table").find("tr:gt(0)").remove();
                        setTimeout(function() {
                            let tableRow = '';
                            let materialCount = 0;
                            $.each(result, (key, value) => {
                                materialCount++;
                                tableRow += '<tr><td style="vertical-align:middle;text-align:center;font-weight: bold;" disabled>' + materialCount + '</td>';
                                tableRow += '<td style="vertical-align:middle;font-weight: bold;" disabled>' + value.material_name + '</td></tr>';
                            });
                            $('#materialList_table').append(tableRow);
                        }, 200);
                    } else {
                        $("#done_materialList_table").find("tr:gt(0)").remove();
                        setTimeout(function() {
                            let tableRow = '';
                            let materialCount = 0;
                            $.each(result, (key, value) => {
                                materialCount++;
                                tableRow += '<tr><td style="vertical-align:middle;text-align:center;font-weight: bold;" disabled>' + materialCount + '</td>';
                                tableRow += '<td style="vertical-align:middle;font-weight: bold;" disabled>' + value.material_name + '</td></tr>';
                            });
                            $('#done_materialList_table').append(tableRow);
                        }, 100);
                    }
                }
            });
        }
    }

    $('#mode_delivery').change(function() {
        if ($(this).val() == 'Pick up') {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_job_courier',
                    company_name: $('#company').val()
                },
                success: function(result) {
                    $("#pickup_courier").empty();
                    setTimeout(function() {
                        optionText = "Choose...";
                        optionValue = "";
                        let optionExists = ($(`#pickup_courier option[value="${optionValue}"]`).length > 0);
                        if (!optionExists) {
                            $("#pickup_courier").append(`<option value="${optionValue}"> ${optionText}</option>`);
                        }
                        if (result.courier != 'empty') {
                            $.each(result, (key, value) => {
                                var optionExists = ($(`#pickup_courier option[value="${key}"]`).length > 0);
                                if (!optionExists) {
                                    $("#pickup_courier").append(`<option value="${key}">${value}</option>`);
                                }
                            });
                        }
                    }, 100);
                }
            });
        } else {
            $("#pickup_courier").empty();
            setTimeout(function() {
                optionText = "Choose...";
                optionValue = "";
                let optionExists = ($(`#pickup_courier option[value="${optionValue}"]`).length > 0);
                if (!optionExists) {
                    $("#pickup_courier").append(`<option value="${optionValue}"> ${optionText}</option>`);
                }
            }, 100);
        }
    });

    function loadJobentryProcessDivision(processDivision, jobentryid, category) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_jobentry_process_data',
                processDivision: processDivision,
                jobentryid: jobentryid,
                category: category
            },
            success: function(result) {
                let inTableBody;
                if (category == 'update') {
                    switch (processDivision) {
                        case 'Printing Division':
                            inTableBody = 'printing_division_body';
                            break;
                        case 'Embossing Division':
                            inTableBody = 'embossing_division_body';
                            break;
                        case 'Packaging Division':
                            inTableBody = 'packaging_division_body';
                            break;
                        case 'Vault Division':
                            inTableBody = 'vault_division_body';
                            break;
                        case 'Dispatching Division':
                            inTableBody = 'dispatching_division_body';
                            break;
                    }
                } else {
                    switch (processDivision) {
                        case 'Printing Division':
                            inTableBody = 'done_printing_division_body';
                            break;
                        case 'Embossing Division':
                            inTableBody = 'done_embossing_division_body';
                            break;
                        case 'Packaging Division':
                            inTableBody = 'done_packaging_division_body';
                            break;
                        case 'Vault Division':
                            inTableBody = 'done_vault_division_body';
                            break;
                        case 'Dispatching Division':
                            inTableBody = 'done_dispatching_division_body';
                            break;
                    }
                }
                let tableRow = '';
                $.each(result, (key, value) => {
                    tableRow += '<tr><td style="vertical-align: middle;">' + value.process_name + '</td>';
                    tableRow += '<td style="text-align:center; vertical-align: middle;">' + value.job_quantity + '</td>';
                    tableRow += '<td style="text-align:center; vertical-align: middle;">' + value.processStatus + '</td>';
                    tableRow += '<td style="vertical-align: middle;">' + value.operator_remarks + '</td>';
                    if (category == 'update') {
                        if (value.process_status == 'Pending' || value.process_status == 'Hold' || value.process_status == 'Process Hold') {
                            tableRow += '<td style="vertical-align: middle;text-align: center;"><button type="button" class="btn btn-secondary col-sm-12" disabled><i class="fa-solid fa-arrows-rotate"></i></button></td>';
                        } else {
                            tableRow += '<td style="vertical-align: middle;text-align: center;"><button type="button" class="btn btn-info col-sm-12 btnResetProcess" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Reset Process" onclick="resetProcessStatus(\'' + value.jobentryid + '\',\'' + value.processid + '\');"><i class="fa-solid fa-arrows-rotate fa-spin"></i></button></td>';
                        }
                    } else {
                        tableRow += '<td style="vertical-align: middle;text-align: center;"><button type="button" class="btn btn-info col-sm-12 btnViewProcessInfo" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="viewProcessDoneInfo(\'' + value.jobentryid + '\',\'' + value.processid + '\',\'' + processDivision + '\');"><i class="fa-solid fa-circle-info fa-bounce"></i></button></td>';
                    }
                    tableRow += '</tr>';
                });
                $('#' + inTableBody).append(tableRow);
            }
        });
    }

    function loadMaterialListDivisions(materialSection, jobentryid, category) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_jobentry_material_data',
                materialSection: materialSection,
                jobentryid: jobentryid,
                category: category
            },
            success: function(result) {
                let inTableBody;
                if (category == 'update') {
                    switch (materialSection) {
                        case 'Sticker Section':
                            inTableBody = 'sticker_list_body';
                            break;
                        case 'Carrier Section':
                            inTableBody = 'carrier_list_body';
                            break;
                        case 'Sim Pairing Section':
                            inTableBody = 'sim_pairing_list_body';
                            break;
                        case 'Waybill Section':
                            inTableBody = 'waybill_list_body';
                            break;
                        case 'Logsheet Checklist Section':
                            inTableBody = 'logsheet_checklist_body';
                            break;
                        case 'Data Preparation Section':
                            inTableBody = 'data_preparation_body';
                            break;
                        case 'Card and Form Section':
                            inTableBody = 'card_and_form_body';
                            break;
                        case 'Collateral Section':
                            inTableBody = 'collateral_for_request_body';
                            break;
                    }
                } else {
                    switch (materialSection) {
                        case 'Sticker Section':
                            inTableBody = 'done_sticker_list_body';
                            break;
                        case 'Carrier Section':
                            inTableBody = 'done_carrier_list_body';
                            break;
                        case 'Sim Pairing Section':
                            inTableBody = 'done_sim_pairing_list_body';
                            break;
                        case 'Waybill Section':
                            inTableBody = 'done_waybill_list_body';
                            break;
                        case 'Logsheet Checklist Section':
                            inTableBody = 'done_logsheet_checklist_body';
                            break;
                        case 'Data Preparation Section':
                            inTableBody = 'done_data_preparation_body';
                            break;
                        case 'Card and Form Section':
                            inTableBody = 'done_card_and_form_body';
                            break;
                        case 'Collateral Section':
                            inTableBody = 'done_collateral_for_request_body';
                            break;
                    }
                }
                let tableRow = '';
                $.each(result, (key, value) => {
                    tableRow += '<tr><td style="vertical-align: middle;">' + value.material_name + '</td>';
                    tableRow += '<td style="text-align:center; vertical-align: middle;">' + value.job_quantity + '</td>';
                    tableRow += '<td style="text-align:center; vertical-align: middle;">' + value.materialStatus + '</td>';
                    tableRow += '<td style="vertical-align: middle;">' + value.operator_remarks + '</td>';
                    if (category == 'update') {
                        if (value.material_status == 'Pending' || value.material_status == 'Hold' || value.material_status == 'Process Hold') {
                            tableRow += '<td style="vertical-align: middle;text-align: center;"><button type="button" class="btn btn-secondary col-sm-12" disabled><i class="fa-solid fa-arrows-rotate"></i></button></td>';
                        } else {
                            tableRow += '<td style="vertical-align: middle;text-align: center;"><button type="button" class="btn btn-info col-sm-12 btnResetProcess" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Reset Material" onclick="resetMaterialStatus(\'' + value.jobentryid + '\',\'' + value.materialid + '\');"><i class="fa-solid fa-arrows-rotate fa-spin"></i></button></td>';
                        }
                    } else {
                        tableRow += '<td style="vertical-align: middle;text-align: center;"><button type="button" class="btn btn-info col-sm-12 btnViewProcessInfo" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="viewMaterialDoneInfo(\'' + value.jobentryid + '\',\'' + value.materialid + '\');"><i class="fa-solid fa-circle-info fa-bounce"></i></button></td>';
                    }
                    tableRow += '</tr>';
                });
                $('#' + inTableBody).append(tableRow);
            }
        });
    }

    function resetProcessStatus(jobentryid, processid) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            data: {
                action: 'reset_process_status',
                jobentryid: jobentryid,
                processid: processid
            },
            success: function(result) {
                loadJobentryProcessDivision('Printing Division', jobentryid, 'update');
                loadJobentryProcessDivision('Embossing Division', jobentryid, 'update');
                loadJobentryProcessDivision('Packaging Division', jobentryid, 'update');
                loadJobentryProcessDivision('Vault Division', jobentryid, 'update');
                loadJobentryProcessDivision('Dispatching Division', jobentryid, 'update');
            }
        });
    }

    function resetMaterialStatus(jobentryid, materialid) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            data: {
                action: 'reset_material_status',
                jobentryid: jobentryid,
                materialid: materialid
            },
            success: function(result) {
                loadMaterialListDivisions('Sticker Section', jobentryid, 'update');
                loadMaterialListDivisions('Data Preparation Section', jobentryid, 'update');
                loadMaterialListDivisions('Logsheet Checklist Section', jobentryid, 'update');
                loadMaterialListDivisions('Collateral Section', jobentryid, 'update');
                loadMaterialListDivisions('Waybill Section', jobentryid, 'update');
                loadMaterialListDivisions('Sim Pairing Section', jobentryid, 'update');
                loadMaterialListDivisions('Card and Form Section', jobentryid, 'update');
                loadMaterialListDivisions('Carrier Section', jobentryid, 'update');
            }
        });
    }

    function viewProcessDoneInfo(jobentryid, processid, processDivision) {
        $('#processTimelineInfoModal').modal('show');
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_process_info',
                jobentryid: jobentryid,
                processid: processid,
                processDivision: processDivision
            },
            success: function(result) {
                $('#process_info_time_start').val(result.date_time_start);
                $('#process_info_time_end').val(result.date_time_end);
                $('#process_info_status').val(result.process_status);
                $('#process_info_instruction').val(result.process_instructions);
                $('#process_info_operator_remarks').val(result.operator_remarks);
            }
        });
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_process_operator',
                jobentryid: jobentryid,
                processid: processid
            },
            success: function(result) {
                let tableRow = '';
                $.each(result, (key, value) => {
                    tableRow += `
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control fw-bold text-center" id="process_info_operator" value="` + value.process_operator + `" disabled>
                        <label for="process_info_operator" class="col-form-label fw-bold">Operator</label>
                    </div>`;
                });
                $('#operator_container').html(tableRow);
            }
        });
    }

    function viewMaterialDoneInfo(jobentryid, materialid) {
        $('#materialTimelineInfoModal').modal('show');
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_material_info',
                jobentryid: jobentryid,
                materialid: materialid
            },
            success: function(result) {
                $('#material_info_time_start').val(result.date_time_end);
                $('#material_info_time_end').val(result.date_time_end);
                $('#material_info_status').val(result.material_status);
                $('#material_info_operator_remarks').val(result.operator_remarks);
            }
        });
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_material_operator',
                jobentryid: jobentryid,
                materialid: materialid
            },
            success: function(result) {
                let tableRow = '';
                $.each(result, (key, value) => {
                    tableRow += `
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control fw-bold text-center" id="material_info_operator" value="` + value.material_operator + `" disabled>
                        <label for="material_info_operator" class="col-form-label fw-bold">Operator</label>
                    </div>`;
                });
                $('#material_operator_container').html(tableRow);
            }
        });
    }

    $('#job_chk_hold').click(function(event) {
        if (this.checked) {
            $('#releaseDate').prop('disabled', true);
            $('#releaseDate').prop('disabled', true);
            $('#job_status').val('Hold');
        } else {
            $('#releaseDate').prop('disabled', false);
            $('#job_remarks').val('');
            $('#job_status').val('Pending');
        }
    });

    $('#job_quantity').keyup(function() {
        if (event.which >= 37 && event.which <= 40) { //* =========== skip for arrow keys ===========
            event.preventDefault();
        }
        $(this).val(function(index, value) {
            return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });

    function jobentryarchive() {
        $('#jobArchiveModal').modal('show');
        $('#archive_title_modal').html('Archive');
    }

    function jobArchive() {
        if (inputValidation('archive_month_from', 'archive_month_to')) {
            var date_from = new Date(document.getElementById('archive_month_from').value);
            var date_to = new Date(document.getElementById('archive_month_to').value);
            var converted_date_from = date_from.getFullYear() + ' ' + ("0" + (date_from.getMonth() + 1)).slice(-2);
            var converted_date_to = date_to.getFullYear() + ' ' + ("0" + (date_to.getMonth() + 1)).slice(-2);
            var ajaxTime = new Date().getTime();
            clearAttributes();
            $('#archive_month_from').prop('disabled', true);
            $('#archive_month_to').prop('disabled', true);
            $('.btnArchiveClose').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
            $('.btnArchiveJobEntry').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');

            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'archive_job_entry',
                    month_from: converted_date_from,
                    month_to: converted_date_to
                },
                beforeSend: function() {
                    $('#archive_title_modal').html('Archiving 80 data... <i class="fa-solid fa-download p-l-8 fa-beat"></i>');
                },
                success: result => {
                    if (result == '') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'No data to show',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Job Successfully Archive',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        $('#jobDone_table').DataTable().ajax.reload(null, false);
                        $('#archive_title_modal').html('Archive');
                    }
                    $('#archive_month_from').prop('disabled', false).val('');
                    $('#archive_month_to').prop('disabled', false).val('');
                    $('.btnArchiveClose').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                    $('.btnArchiveJobEntry').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');

                }
            });
        }
    }

    function scanBarcodeAutoFill() {
        $('#scanBarcodeModal').modal('show').on('shown.bs.modal', function() {
            $('#scanned_barcode').focus();
        });
    }

    $('#scanned_barcode').on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_input_val',
                    scan_code: $(this).val()
                },
                success: result => {
                    $('#company').val(result.companyname).change();
                    setTimeout(function() {
                        $('#jonumber').val(result.jonumber).change();
                    }, 200);
                    $('#job_quantity').val(result.jo_quantity).each(function() { //* ======== Format quantity with Commas ========
                        $(this).val(function(index, value) {
                            return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        });
                    });
                    let file_count = 0;
                    $.each(result.filename, (key, value) => {
                        file_count++;
                    });
                    if (file_count > 1) {
                        $('#filename_section').html(`<div class="form-floating mb-2">
                        <select class="form-select fw-bold job_filename_multiple" id="job_filename"></select>
                        <div class="invalid-feedback"></div>
                        <label for="job_filename" class="col-form-label fw-bold">Filename</label></div>`);
                        setTimeout(function() {
                            loadSelectValues('job_filename', result.filename)
                            $('.job_filename_multiple').change(function() {
                                $.ajax({
                                    url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {
                                        action: 'load_filename_quantity',
                                        childid: result.childid,
                                        filename: $(this).val()
                                    },
                                    success: result => {
                                        $('#job_quantity').val(result.filequantity).each(function() { //* ======== Format quantity with Commas ========
                                            $(this).val(function(index, value) {
                                                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            });
                                        });
                                    }
                                });
                            });
                        }, 200);
                    } else {
                        $('#filename_section').html(`<div class="form-floating mb-2">
                        <input type="text" class="form-control fw-bold" id="job_filename" placeholder="Filename">
                        <div class="invalid-feedback"></div>
                        <label for="job_filename" class="col-form-label fw-bold">Filename</label></div>`);
                        setTimeout(function() {
                            $.each(result.filename, (key, value) => {
                                $('#job_filename').val(value);
                            });
                            setTimeout(function() {
                                $.ajax({
                                    url: '../controller/perso_monitoring_controller/perso_job_entry_contr.class.php',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {
                                        action: 'load_filename_quantity',
                                        childid: result.childid,
                                        filename: $('#job_filename').val()
                                    },
                                    success: result => {
                                        $('#job_quantity').val(result.filequantity).each(function() { //* ======== Format quantity with Commas ========
                                            $(this).val(function(index, value) {
                                                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            });
                                        });
                                    }
                                });
                            }, 200);
                        }, 200);
                    }
                }
            });
            $('#scanBarcodeModal').modal('hide')
            clearValues('scan_barcode');
        }
    });


    function addInputDisabled() {
        $('#company').prop('disabled', true);
        $('#jonumber').prop('disabled', true);
        $('#job_filename').prop('disabled', true);
        $('#job_template').prop('disabled', true);
        $('#releaseDate').prop('disabled', true);
        $('#dateReceive').prop('disabled', true);
        $('#job_cutoff').prop('disabled', true);
        $('#job_quantity').prop('disabled', true);
        $('#mode_delivery').prop('disabled', true);
        $('#pickup_courier').prop('disabled', true);
        $('#job_chk_hold').prop('disabled', true);
    }

    function removeInputDisabled() {
        let job_chk_hold = document.getElementById('job_chk_hold').checked;
        $('#company').prop('disabled', false);
        $('#jonumber').prop('disabled', false);
        $('#job_filename').prop('disabled', false);
        $('#job_template').prop('disabled', false);
        $('#job_quantity').prop('disabled', false);
        $('#mode_delivery').prop('disabled', false);
        $('#pickup_courier').prop('disabled', false);
        $('#job_chk_hold').prop('disabled', false);
        $('#job_remarks').prop('disabled', false);
        $('#dateReceive').prop('disabled', false);
        $('#job_cutoff').prop('disabled', false);
        if (job_chk_hold == true) {
            $('#releaseDate').prop('disabled', true);
        } else {
            $('#releaseDate').prop('disabled', false);
        }
    }

    function clearValues(val) {
        if (val == 'jobEntry') {
            prevIndexCourier = '';
            $('select').find('option:first').prop('selected', 'selected');
            $('input[type=text]').val('');
            $('input[type=checkbox]').prop('checked', false);
            $('#dateEntry').attr('min', dateToday).val(dateToday);
            $('#releaseDate').val('');
            $('#dateReceive').val('');
            $('#pickup_courier').prop('disabled', true);

            $("#jonumber").empty();
            setTimeout(function() {
                optionText = "Choose...";
                optionValue = "";
                let optionExists = ($(`#jonumber option[value="${optionValue}"]`).length > 0);
                if (!optionExists) {
                    $('#jonumber').append(`<option value="${optionValue}">${optionText}</option>`);
                }
            }, 100);
            $("#job_template").empty();
            setTimeout(function() {
                optionText = "Choose...";
                optionValue = "";
                let optionExists = ($(`#job_template option[value="${optionValue}"]`).length > 0);
                if (!optionExists) {
                    $('#job_template').append(`<option value="${optionValue}">${optionText}</option>`);
                }
            }, 100);
            $("#processList_table").find("tr:gt(0)").remove();
            $("#materialList_table").find("tr:gt(0)").remove();
            $('#filename_section').html(`<div class="form-floating mb-2">
            <input type="text" class="form-control fw-bold" id="job_filename" placeholder="Filename">
            <div class="invalid-feedback"></div>
            <label for="job_filename" class="col-form-label fw-bold">Filename</label></div>`);

            clearProcessTableMaterialTable();
        } else if (val == 'multipleEntry') {
            prevIndexCourier = '';
            $('#job_filename').val('');
            $('#job_filename').find('option:first').prop('selected', 'selected');
            $('#job_quantity').val('');
            $('#dateEntry').attr('min', dateToday).val(dateToday);
            $('#releaseDate').val('');
            $('#dateReceive').val('');
            $('textarea').val('');
            $('input[type=checkbox]').prop('checked', false);
            $('#job_cutoff').find('option:first').prop('selected', 'selected');
            $('#mode_delivery').find('option:first').prop('selected', 'selected');
            $('#pickup_courier').find('option:first').prop('selected', 'selected');
            $('#job_template').find('option:first').prop('selected', 'selected');
            $('#pickup_courier').prop('disabled', true);

            $("#processList_table").find("tr:gt(0)").remove();
            $("#materialList_table").find("tr:gt(0)").remove();
        } else if (val == 'archive') {
            $('#archive_month_from').val('');
            $('#archive_month_to').val('');
        } else if (val == 'scan_barcode') {
            $('#scanned_barcode').val('');
        } else {
            $("#done_processList_table").find("tr:gt(0)").remove();
            $("#done_materialList_table").find("tr:gt(0)").remove();

            $("#done_printing_division_table").find("tr:gt(0)").remove();
            $("#done_embossing_division_table").find("tr:gt(0)").remove();
            $("#done_packaging_division_table").find("tr:gt(0)").remove();
            $("#done_vault_division_table").find("tr:gt(0)").remove();
            $("#done_dispatching_division_table").find("tr:gt(0)").remove();

            $("#done_sticker_list_table").find("tr:gt(0)").remove();
            $("#done_carrier_list_table").find("tr:gt(0)").remove();
            $("#done_sim_pairing_list_table").find("tr:gt(0)").remove();
            $("#done_waybill_list_table").find("tr:gt(0)").remove();
            $("#done_logsheet_checklist_list_table").find("tr:gt(0)").remove();
            $("#done_data_preparation_list_table").find("tr:gt(0)").remove();
            $("#done_card_and_form_list_table").find("tr:gt(0)").remove();
            $("#done_collateral_for_request_list_table").find("tr:gt(0)").remove();
        }
        clearAttributes();
        removeInputDisabled();
    }

    function clearProcessTableMaterialTable() {
        $("#printing_division_table").find("tr:gt(0)").remove();
        $("#embossing_division_table").find("tr:gt(0)").remove();
        $("#packaging_division_table").find("tr:gt(0)").remove();
        $("#vault_division_table").find("tr:gt(0)").remove();
        $("#dispatching_division_table").find("tr:gt(0)").remove();

        $("#sticker_list_table").find("tr:gt(0)").remove();
        $("#carrier_list_table").find("tr:gt(0)").remove();
        $("#sim_pairing_list_table").find("tr:gt(0)").remove();
        $("#waybill_list_table").find("tr:gt(0)").remove();
        $("#logsheet_checklist_list_table").find("tr:gt(0)").remove();
        $("#data_preparation_list_table").find("tr:gt(0)").remove();
        $("#card_and_form_list_table").find("tr:gt(0)").remove();
        $("#collateral_for_request_list_table").find("tr:gt(0)").remove();
    }

    function clearAttributes() {
        $('textarea').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
        $('input').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>