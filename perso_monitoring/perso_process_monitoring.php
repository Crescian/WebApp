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

    .app-card-wrapper {
        width: 450px;
        top: 25px;
        right: 10px;
    }

    @media only screen and (max-width: 480px) {
        .app-card-wrapper {
            width: 90%;
        }

        .app-circle-bars {
            padding: 10px 8px !important;
            font-size: 18px !important;
        }
    }

    .app-circle-btn-wrapper {
        bottom: 25px;
        right: 10px;
    }

    .app-circle-bars {
        padding: 15px;
        font-size: 24px;
    }
</style>
<div class="container-fluid px-5 py-3">
    <!-- ==================== CONTENT SECTION ==================== -->
    <div class="row">
        <span class="page-title-perso">Process Monitoring</span>
    </div>
    <div class="row">
        <div class="card shadow mt-4">
            <div class="card-body">
                <!-- ========== Nav Tabs ========== -->
                <ul class="nav nav-tabs nav-fill flex-column flex-sm-row" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 active" id="processSection-tab" data-bs-toggle="tab" data-bs-target="#processSection" role="tab" aria-controls="processSection" aria-selected="false">Process</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="materialSection-tab" data-bs-toggle="tab" data-bs-target="#materialSection" role="tab" aria-controls="materialSection" aria-selected="false">Material</button>
                    </li>
                </ul>
                <hr>
                <!-- ======================= Nav tabs Content ======================= -->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="processSection" role="tabpanel" aria-labelledby="processSection-tab">
                        <!-- ======================= Sub Nav tabs ======================= -->
                        <ul class="nav nav-tabs nav-fill flex-column flex-sm-row mt-4" role="tablist">
                            <li class="nav-item dropdown" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 dropdown-toggle active" id="printing_tab_dropdown" data-bs-toggle="dropdown">Printing Section <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><button class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 active dropdown-item" id="inkjetSection-tab" data-bs-toggle="tab" data-bs-target="#inkjetSection" role="tab" aria-controls="inkjetSection" aria-selected="true">Inkjet</button></li>
                                    <li><button class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 dropdown-item" id="persomasterPersolineSection-tab" data-bs-toggle="tab" data-bs-target="#persomasterPersolineSection" role="tab" aria-controls="persomasterPersolineSection" aria-selected="false">Persomaster / Persoline</button></li>
                                </ul>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="embossingSection-tab" data-bs-toggle="tab" data-bs-target="#embossingSection" role="tab" aria-controls="embossingSection" aria-selected="false">Embossing Section</button>
                            </li>
                            <li class="nav-item dropdown" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 dropdown-toggle" id="packaging_tab_dropdown" data-bs-toggle="dropdown">Packaging Section<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="packagingSection-tab" data-bs-toggle="tab" data-bs-target="#packagingSection" role="tab" aria-controls="packagingSection" aria-selected="false">Packaging</button></li>
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="qANonHsaKitting-tab" data-bs-toggle="tab" data-bs-target="#qANonHsaKitting" role="tab" aria-controls="qANonHsaKitting" aria-selected="false">QA / Non HSA Kitting</button></li>
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="hsaKitting-tab" data-bs-toggle="tab" data-bs-target="#hsaKitting" role="tab" aria-controls="hsaKitting" aria-selected="false">HSA Kitting</button></li>
                                </ul>
                            </li>
                        </ul>
                        <!-- ======================= Sub Nav tabs Content ======================= -->
                        <div class="tab-content" id="mySubTabContent">
                            <div class="tab-pane fade active show" id="inkjetSection" role="tabpanel" aria-labelledby="inkjetSection-tab">
                                <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="job-process-section-title">Inkjet Section</h3>
                                    <div class="d-flex justify-content-end">
                                        <div class="dropdown p-r-8">
                                            <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Persomonitoring.php?d=Inkjet Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="inkjetList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th style="text-align:center;"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="inkjet_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="inkjet_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="inkjet_process_machine" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="Persoline">Persoline</option>
                                                    <option value="Persomaster">Persomaster</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="inkjet_process_machine">Machine</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="inkjet_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="inkjet_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="inkjet_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSaveInkjet me-1 mb-2" onclick="savePlanner('Inkjet');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelInkjet me-2 mb-2" onclick="cancelPlanner('Inkjet');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="inkjetListDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= inkjetSection-tab End ======================= -->
                            <div class="tab-pane fade" id="persomasterPersolineSection" role="tabpanel" aria-labelledby="persomasterPersolineSection-tab">
                                <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="job-process-section-title">Persomaster Machine</h3>
                                    <div class="d-flex justify-content-end">
                                        <div class="dropdown p-r-8">
                                            <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="exportExcelFile-Persomonitoring.php?d=Persomaster/Persoline Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="persomasterList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="persomaster_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="persomaster_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="persomaster_process_machine" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="Persoline">Persoline</option>
                                                    <option value="Persomaster">Persomaster</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="persomaster_process_machine">Machine</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="persomaster_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="persomaster_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="persomaster_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSavePersomaster me-1 mb-2" onclick="savePlanner('Persomaster');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelPersomaster me-2 mb-2" onclick="cancelPlanner('Persomaster');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Persoline Machine</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="persolineList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="persoline_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="persoline_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="persoline_process_machine" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="Persoline">Persoline</option>
                                                    <option value="Persomaster">Persomaster</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="persoline_process_machine">Machine</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="persoline_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="persoline_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="persoline_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSavePersoline me-1 mb-2" onclick="savePlanner('Persoline');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelPersoline me-2 mb-2" onclick="cancelPlanner('Persoline');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="persomasterPersolineListDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= persomasterPersolineSection-tab End ======================= -->
                            <div class="tab-pane fade" id="embossingSection" role="tabpanel" aria-labelledby="embossingSection-tab">
                                <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="job-process-section-title">Embossing/Datacard Section</h3>
                                    <div class="d-flex justify-content-end">
                                        <div class="dropdown p-r-8">
                                            <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Persomonitoring.php?d=Embossing/Datacard Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="embossingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="embossing_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="embossing_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="embossing_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="embossing_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="embossing_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSaveEmbossing me-1 mb-2" onclick="savePlanner('Embossing');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelEmbossing me-2 mb-2" onclick="cancelPlanner('Embossing');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">For Packing</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="embossingPackList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="embossing_pack_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="embossing_pack_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="embossing_pack_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="embossing_pack_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="embossing_pack_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSaveEmbossingPack me-1 mb-2" onclick="savePlanner('EmbossingPack');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelEmbossingPack me-2 mb-2" onclick="cancelPlanner('EmbossingPack');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="embossingListDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= embossingSection-tab End ======================= -->
                            <div class="tab-pane fade" id="packagingSection" role="tabpanel" aria-labelledby="packagingSection-tab">
                                <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="job-process-section-title">Packaging Section</h3>
                                    <div class="d-flex justify-content-end">
                                        <div class="dropdown p-r-8">
                                            <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Persomonitoring.php?d=Packaging Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="packaging_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="packaging_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="packaging_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="packaging_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="packaging_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="packaging_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSavePackaging me-1 mb-2" onclick="savePlanner('Packaging');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelPackaging me-2 mb-2" onclick="cancelPlanner('Packaging');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="packaging_listDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= packagingSection-tab End ======================= -->
                            <div class="tab-pane fade" id="qANonHsaKitting" role="tabpanel" aria-labelledby="qANonHsaKitting-tab">
                                <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="job-process-section-title">QA/Non HSA Kitting Section</h3>
                                    <div class="d-flex justify-content-end">
                                        <div class="dropdown p-r-8">
                                            <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Persomonitoring.php?d=QA/Non HSA Kitting Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="qa_non_hsa_kitting_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="nonhsa_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="nonhsa_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="nonhsa_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="nonhsa_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="nonhsa_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSaveNonHSA me-1 mb-2" onclick="savePlanner('NonHSA');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelNonHSA me-2 mb-2" onclick="cancelPlanner('NonHSA');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="qa_non_hsa_kitting_listDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= qANonHsaKitting-tab End ======================= -->
                            <div class="tab-pane fade" id="hsaKitting" role="tabpanel" aria-labelledby="hsaKitting-tab">
                                <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                                    <h3 class="job-process-section-title">HSA Kitting Section</h3>
                                    <div class="d-flex justify-content-end">
                                        <div class="dropdown p-r-8">
                                            <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Persomonitoring.php?d=HSA Kitting Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="hsa_kitting_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="hsa_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="hsa_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="hsa_process_machine" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="Manual Kitting">Manual Kitting</option>
                                                    <option value="Persomail">Persomail</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="hsa_process_machine">Machine</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="hsa_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="hsa_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="hsa_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSaveHSA me-1 mb-2" onclick="savePlanner('HSA');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelHSA me-2 mb-2" onclick="cancelPlanner('HSA');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">For Packing</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="hsa_kitting_forPackinglist_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Receive</th>
                                                        <th style="text-align:center;">Cut-Off</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Remarks</th>
                                                        <th class="text-center"></th>
                                                        <th style="text-align:center;">Jobentry_id</th>
                                                        <th style="text-align:center;">Process_id</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="hsa_pack_process_priority" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="0">Remove Priority</option>
                                                    <option value="1">Priority 1</option>
                                                    <option value="2">Priority 2</option>
                                                    <option value="3">Priority 3</option>
                                                    <option value="4">Priority 4</option>
                                                    <option value="5">Priority 5</option>
                                                    <option value="6">Priority 6</option>
                                                    <option value="7">Priority 7</option>
                                                    <option value="8">Priority 8</option>
                                                    <option value="9">Priority 9</option>
                                                    <option value="10">Priority 10</option>
                                                    <option value="11">Priority 11</option>
                                                    <option value="12">Priority 12</option>
                                                    <option value="13">Priority 13</option>
                                                    <option value="14">Priority 14</option>
                                                    <option value="15">Priority 15</option>
                                                    <option value="16">Priority 16</option>
                                                    <option value="17">Priority 17</option>
                                                    <option value="18">Priority 18</option>
                                                    <option value="19">Priority 19</option>
                                                    <option value="20">Priority 20</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="hsa_pack_process_priority">Priority</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <div class="form-floating">
                                                <select id="hsa_pack_process_machine" class="form-select fw-bold" disabled>
                                                    <option value="">Choose...</option>
                                                    <option value="Manual Kitting">Manual Kitting</option>
                                                    <option value="Persomail">Persomail</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label class="fw-bold" for="hsa_pack_process_machine">Machine</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-3">
                                        <div class="form-floating">
                                            <input type="date" id="hsa_pack_process_release_date" class="form-control fw-bold" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label class="fw-bold" for="hsa_pack_process_release_date">Release Date</label>
                                        </div>
                                    </div>
                                    <div class="col-sm mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control fw-bold" id="hsa_pack_process_instruction" disabled>
                                            <label class="fw-bold">Instruction</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="row mt-2">
                                            <button type="button" class="btn btn-secondary col-sm btnSaveHSAPacking me-1 mb-2" onclick="savePlanner('HSAPacking');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                            <button type="button" class="btn btn-secondary col-sm btnCancelHSAPacking me-2 mb-2" onclick="cancelPlanner('HSAPacking');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="hsa_kitting_listDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">#</th>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th>Process</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Time Start</th>
                                                        <th style="text-align:center;">Time End</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= hsaKitting-tab End ======================= -->
                        </div><!-- ======================= mySubTabContent End ======================= -->
                    </div><!-- ======================= processSection-tab End ======================= -->
                    <div class="tab-pane fade" id="materialSection" role="tabpanel" aria-labelledby="materialSection-tab">
                        <!-- ======================= Sub Nav tabs ======================= -->
                        <ul class="nav nav-tabs nav-fill flex-column flex-sm-row mt-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 active" id="stickerChecklistSection-tab" data-bs-toggle="tab" data-bs-target="#stickerChecklistSection" role="tab" aria-controls="stickerChecklistSection" aria-selected="false">STICKER LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="carrierChecklistSection-tab" data-bs-toggle="tab" data-bs-target="#carrierChecklistSection" role="tab" aria-controls="carrierChecklistSection" aria-selected="false">CARRIER LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="simPairingChecklistSection-tab" data-bs-toggle="tab" data-bs-target="#simPairingChecklistSection" role="tab" aria-controls="simPairingChecklistSection" aria-selected="false">SIM PAIRING LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="waybillChecklistSection-tab" data-bs-toggle="tab" data-bs-target="#waybillChecklistSection" role="tab" aria-controls="waybillChecklistSection" aria-selected="false">WAYBILL LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="logsheetChecklistSection-tab" data-bs-toggle="tab" data-bs-target="#logsheetChecklistSection" role="tab" aria-controls="logsheetChecklistSection" aria-selected="false">LOGSHEET/CHECKLIST LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="dataPrepSection-tab" data-bs-toggle="tab" data-bs-target="#dataPrepSection" role="tab" aria-controls="dataPrepSection" aria-selected="false">DATA PREPARATION LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="cardAndFormSection-tab" data-bs-toggle="tab" data-bs-target="#cardAndFormSection" role="tab" aria-controls="cardAndFormSection" aria-selected="false">CARD AND FORM LIST</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="collateralSection-tab" data-bs-toggle="tab" data-bs-target="#collateralSection" role="tab" aria-controls="collateralSection" aria-selected="false">COLLATERAL FOR REQUEST LIST</button>
                            </li>
                        </ul>
                        <!-- ======================= Sub Nav tabs Content ======================= -->
                        <div class="tab-content" id="mySubTabContent">
                            <div class="tab-pane fade" id="stickerChecklistSection" role="tabpanel" aria-labelledby="stickerChecklistSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">STICKER LIST</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="stickerChecklist_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="stickerChecklistDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= stickerChecklistSection End ======================= -->
                            <div class="tab-pane fade" id="carrierChecklistSection" role="tabpanel" aria-labelledby="carrierChecklistSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">CARRIER LIST</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="carrierChecklist_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="carrierChecklistDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= carrierChecklistSection End ======================= -->
                            <div class="tab-pane fade" id="simPairingChecklistSection" role="tabpanel" aria-labelledby="simPairingChecklistSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">SIM PAIRING LIST</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="simPairingChecklist_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="simPairingChecklistDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= simPairingChecklistSection End ======================= -->
                            <div class="tab-pane fade" id="waybillChecklistSection" role="tabpanel" aria-labelledby="waybillChecklistSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">WAYBILL LIST</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="waybillChecklist_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="waybillChecklistDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= waybillChecklistSection End ======================= -->
                            <div class="tab-pane fade" id="logsheetChecklistSection" role="tabpanel" aria-labelledby="logsheetChecklistSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Logsheet/Checklist List</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="logsheetChecklist_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="logsheetChecklistDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= logsheetChecklistSection End ======================= -->
                            <div class="tab-pane fade" id="dataPrepSection" role="tabpanel" aria-labelledby="dataPrepSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Data Preparation</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="dataPrepList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="dataPrepListDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= dataPrepSection End ======================= -->
                            <div class="tab-pane fade" id="cardAndFormSection" role="tabpanel" aria-labelledby="cardAndFormSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Card and Form List</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="cardAndFormList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="cardAndFormListDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= cardAndFormSection End ======================= -->
                            <div class="tab-pane fade" id="collateralSection" role="tabpanel" aria-labelledby="collateralSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Collateral For Request List</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="collateralList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th style="text-align:center;">Status</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Done</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="collateralListDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O No.</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Material</th>
                                                        <th>Operator Remarks</th>
                                                        <th style="text-align:center;">Prepared By</th>
                                                        <th style="text-align:center;">Date Finished</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= collateralSection End ======================= -->
                        </div><!-- ======================= mySubTabContent End ======================= -->



                    </div> <!-- ======================= materialSection-tab End ======================= -->
                </div><!-- ======================= myTabContent End ======================= -->
            </div><!-- ======================= Card Body End ======================= -->
        </div><!-- ======================= Card End ======================= -->
    </div>
    <!-- =============== Material Process Start Modal =============== -->
    <div class="modal fade" id="materialProcessStartModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">MATERIAL PROCESS START</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="material_jobentryid" disabled>
                    <input type="hidden" class="form-control" id="materialid" disabled>
                    <input type="hidden" class="form-control" id="material_section" disabled>
                    <div class="form-floating mt-2">
                        <select class="form-select fw-bold" id="material_operator"></select>
                        <div class="invalid-feedback"></div>
                        <label for="material_operator" class="fw-bold fs-18">Operator</label>
                    </div>
                    <div class="form-floating mt-2">
                        <textarea id="material_operator_remarks" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                        <div class="invalid-feedback"></div>
                        <label for="material_operator_remarks" class="col-form-label fw-bold">Operator Remarks</label>
                    </div>
                </div>
                <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    <button type="button" class="btn btn-success col-sm btnSaveMaterialProcess" onclick="saveMaterialProcess();"><i class="fa-regular fa-floppy-disk p-r-8"></i> save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Material Process Start Modal End =============== -->
    <!-- ==================== CONTENT SECTION END ==================== -->
    <div class="position-fixed z-3 app-card-wrapper"><!-- ==================== CARD SECTION ==================== -->
        <div class="card card-4 border-0 shadow app-card">
            <div class="d-flex justify-content-between justify-content-md-between mt-1 me-3 align-items-center">
                <button class="btn text-white fs-2" onclick="hideCard();"><i class="fa-solid fa-bars"></i></button>
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
    <!-- ==================== CARD SECTION END ==================== -->
    <!-- ==================== CARD BUTTON SECTION ==================== -->
    <div class="position-fixed app-circle-btn-wrapper">
        <button class="btn btn-primary rounded-circle app-circle-btn" onclick="showCard();"><i class="fa-solid fa-bars app-circle-bars"></i></button>
    </div>
    <!-- ==================== CARD BUTTON SECTION END ==================== -->
</div>
<?php
include './../helper/perso_announcement.php';
include './../includes/footer.php';
include './../helper/input_validation.php';
?>
<script>
    hideCard();

    function showCard() {
        $('.app-card').slideDown();
        $('.app-circle-btn').fadeOut();
    }

    function hideCard() {
        $('.app-card').slideUp();
        $('.app-circle-btn').fadeIn();
    }

    let inkjetList_table;
    let persomasterList_table;
    let persolineList_table;
    let embossingList_table;
    let embossingPackList_table;
    let packaging_list_table;
    let qa_non_hsa_kitting_list_table;
    let hsa_kitting_list_table;
    let hsa_kitting_forPackinglist_table;
    let inTable = [];
    let values = [];
    let materialAction;
    let btnMaterialStatus;

    var access_level = '<?php echo $_SESSION['access_lvl']; ?>';
    var empno = '<?php echo $_SESSION['empno']; ?>';

    loadProcessTable('Inkjet Section');
    loadProcessTable('Persomaster Section');
    loadProcessTable('Persoline Section');
    loadProcessTable('HSA Kitting Section');
    loadProcessTable('HSA Packing Section');
    loadEmbossingPackagingTable('Embossing Section');
    loadEmbossingPackagingTable('EmbossingPacking Section');
    loadEmbossingPackagingTable('Packaging Section');
    loadEmbossingPackagingTable('QA/Non HSA Kitting Section');

    loadProcessDoneTable('inkjetListDone_table', 'Inkjet Section');
    loadProcessDoneTable('persomasterPersolineListDone_table', 'Persomaster/Persoline Section');
    loadProcessDoneTable('embossingListDone_table', 'Embossing Section');
    loadProcessDoneTable('packaging_listDone_table', 'Packaging Section');
    loadProcessDoneTable('qa_non_hsa_kitting_listDone_table', 'QA/Non HSA Kitting Section');
    loadProcessDoneTable('hsa_kitting_listDone_table', 'HSA Kitting Section');

    loadMateriallistTable('stickerChecklist_table', 'Sticker Section');
    loadMateriallistTable('carrierChecklist_table', 'Carrier Section');
    loadMateriallistTable('simPairingChecklist_table', 'Sim Pairing Section');
    loadMateriallistTable('waybillChecklist_table', 'Waybill Section');
    loadMateriallistTable('logsheetChecklist_table', 'Logsheet Checklist Section');
    loadMateriallistTable('dataPrepList_table', 'Data Preparation Section');
    loadMateriallistTable('cardAndFormList_table', 'Card and Form Section');
    loadMateriallistTable('collateralList_table', 'Collateral Section');


    //* ========== Load Page by User Designation ==========
    switch (access_level) {
        case 'PKL':
        case 'KPC':
        case 'JPA':
        case 'KPT':
        case 'JAT':
        case 'SPA':
        case 'PQS':
        case 'SMO':
            $('#processSection-tab').removeClass('active');
            $('#processSection').removeClass('active show');

            $('#materialSection-tab').addClass('active');
            $('#materialSection').addClass('active show');

            $('#stickerChecklistSection-tab').addClass('active');
            $('#stickerChecklistSection').addClass('active show');
            break;

        case 'EVS':
        case 'EVF':
        case 'EFT':
        case 'PLS':
            $('#processSection-tab').removeClass('active');
            $('#processSection').removeClass('active show');

            $('#materialSection-tab').addClass('active');
            $('#materialSection').addClass('active show');

            $('#collateralSection-tab').addClass('active');
            $('#collateralSection').addClass('active show');
            break;

        case 'EDS':
        case 'EDR':
            $('#processSection-tab').removeClass('active');
            $('#processSection').removeClass('active show');

            $('#materialSection-tab').addClass('active');
            $('#materialSection').addClass('active show');

            $('#waybillChecklistSection-tab').addClass('active');
            $('#waybillChecklistSection').addClass('active show');
            break;

        case 'ESP':
            $('#processSection-tab').removeClass('active');
            $('#processSection').removeClass('active show');

            $('#materialSection-tab').addClass('active');
            $('#materialSection').addClass('active show');

            $('#stickerChecklistSection-tab').addClass('active');
            $('#stickerChecklistSection').addClass('active show');
            break;

        case 'PKL':
        case 'JPA':
        case 'KPC':
        case 'KPT':
            $('#waybillChecklistSection-tab').addClass('active');
            $('#waybillChecklistSection').addClass('active show');
            break;
    }
    //* ========== Load Page by User Designation End ==========

    function loadProcessTable(process_section) {
        switch (process_section) {
            case 'Inkjet Section':
                var rows_selected = [];
                inkjetList_table = $('#inkjetList_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: process_section,
                            process_category: 'default'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13, 14],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 15],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 16,
                            className: 'dt-nowrap-center',
                            width: '5%',
                            orderable: false
                        }, {
                            targets: [17, 18],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });

                //* ======== Offset Table ========
                $('#inkjetList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = inkjetList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(inkjetList_table, 'Inkjet'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#inkjetList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', inkjetList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#inkjetList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#inkjetList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                inkjetList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(inkjetList_table, 'Inkjet'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    inkjetList_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Persomaster Section':
                var rows_selected = [];
                persomasterList_table = $('#persomasterList_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'Persomaster/Persoline Section',
                            process_category: 'Persomaster'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13, 14],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 15],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 16,
                            className: 'dt-nowrap-center',
                            width: '5%',
                            orderable: false
                        }, {
                            targets: [17, 18],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });

                //* ======== Offset Table ========
                $('#persomasterList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = persomasterList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(persomasterList_table, 'Persomaster'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#persomasterList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', persomasterList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#persomasterList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#persomasterList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                persomasterList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(persomasterList_table, 'Persomaster'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    persomasterList_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Persoline Section':
                var rows_selected = [];
                persolineList_table = $('#persolineList_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'Persomaster/Persoline Section',
                            process_category: 'Persoline'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13, 14],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 15],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 16,
                            className: 'dt-nowrap-center',
                            width: '5%',
                            orderable: false
                        }, {
                            targets: [17, 18],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });

                //* ======== Offset Table ========
                $('#persolineList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = persolineList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(persolineList_table, 'Persoline'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#persolineList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', persolineList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#persolineList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#persolineList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                persolineList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(persolineList_table, 'Persoline'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    persolineList_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'HSA Kitting Section':
                var rows_selected = [];
                hsa_kitting_list_table = $('#hsa_kitting_list_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'HSA Kitting Section',
                            process_category: 'default'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13, 14],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 15],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 16,
                            className: 'dt-nowrap-center',
                            width: '5%',
                            orderable: false
                        }, {
                            targets: [17, 18],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });
                //* ======== Offset Table ========
                $('#hsa_kitting_list_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = hsa_kitting_list_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(hsa_kitting_list_table, 'HSA'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#hsa_kitting_list_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', hsa_kitting_list_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#hsa_kitting_list_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#hsa_kitting_list_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                hsa_kitting_list_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(hsa_kitting_list_table, 'HSA'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    hsa_kitting_list_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'HSA Packing Section':
                var rows_selected = [];
                hsa_kitting_forPackinglist_table = $('#hsa_kitting_forPackinglist_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'HSA Kitting Section',
                            process_category: 'For Packing'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13, 14],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 15],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 16,
                            className: 'dt-nowrap-center',
                            width: '5%',
                            orderable: false
                        }, {
                            targets: [17, 18],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });
                //* ======== Offset Table ========
                $('#hsa_kitting_forPackinglist_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = hsa_kitting_forPackinglist_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(hsa_kitting_forPackinglist_table, 'HSAPacking'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#hsa_kitting_forPackinglist_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', hsa_kitting_forPackinglist_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#hsa_kitting_forPackinglist_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#hsa_kitting_forPackinglist_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                hsa_kitting_forPackinglist_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(hsa_kitting_forPackinglist_table, 'HSAPacking'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    hsa_kitting_forPackinglist_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;
        }
    }

    function loadEmbossingPackagingTable(process_section) {
        switch (process_section) {
            case 'Embossing Section':
                var rows_selected = [];
                embossingList_table = $('#embossingList_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'Embossing/Datacard Section',
                            process_category: 'default'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 14],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 15,
                            className: 'dt-nowrap-center',
                            orderable: false
                        }, {
                            targets: [16, 17],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback ': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });
                //* ======== Offset Table ========
                $('#embossingList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = embossingList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(embossingList_table, 'Embossing'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#embossingList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', embossingList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#embossingList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#embossingList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                embossingList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(embossingList_table, 'Embossing'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    embossingList_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'EmbossingPacking Section':
                var rows_selected = [];
                embossingPackList_table = $('#embossingPackList_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'Embossing/Datacard Section',
                            process_category: 'For Packing'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 14],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 15,
                            className: 'dt-nowrap-center',
                            orderable: false
                        }, {
                            targets: [16, 17],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });
                //* ======== Offset Table ========
                $('#embossingPackList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = embossingPackList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(embossingPackList_table, 'EmbossingPack'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#embossingPackList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', embossingPackList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#embossingPackList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#embossingPackList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                embossingPackList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(embossingPackList_table, 'EmbossingPack'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    embossingPackList_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Packaging Section':
                var rows_selected = [];
                packaging_list_table = $('#packaging_list_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'Packaging Section',
                            process_category: 'default'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 14],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 15,
                            className: 'dt-nowrap-center',
                            orderable: false
                        }, {
                            targets: [16, 17],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });
                //* ======== Offset Table ========
                $('#packaging_list_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = packaging_list_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(packaging_list_table, 'Packaging'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#packaging_list_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', packaging_list_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#packaging_list_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#packaging_list_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                packaging_list_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(packaging_list_table, 'Packaging'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    packaging_list_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'QA/Non HSA Kitting Section':
                var rows_selected = [];
                qa_non_hsa_kitting_list_table = $('#qa_non_hsa_kitting_list_table').DataTable({
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    // 'searching': false,
                    'ajax': {
                        url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
                            process_section: 'QA/Non HSA Kitting Section',
                            process_category: 'default'
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
                            className: 'dt-body-middle-center',
                            width: '3%',
                            orderable: false
                        }, {
                            targets: [1, 2, 8, 9, 10, 13],
                            className: 'dt-body-middle-center',
                            width: '5%',
                            orderable: false
                        },
                        {
                            targets: [3, 4, 5, 6, 7, 12, 14],
                            className: 'dt-body-middle-left',
                            width: '10%',
                            orderable: false
                        }, {
                            targets: 11,
                            className: 'dt-body-middle-right',
                            width: '6%',
                            orderable: false
                        },
                        {
                            targets: 15,
                            className: 'dt-nowrap-center',
                            orderable: false
                        }, {
                            targets: [16, 17],
                            className: 'hide_column_datable'
                        }
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        var rowId = data[0]; //* Get row ID
                        if ($.inArray(rowId, rows_selected) !== -1) { //* If row ID is in the list of selected row IDs
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }
                    }
                });
                //* ======== Offset Table ========
                $('#qa_non_hsa_kitting_list_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = qa_non_hsa_kitting_list_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(qa_non_hsa_kitting_list_table, 'NonHSA'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#qa_non_hsa_kitting_list_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', qa_non_hsa_kitting_list_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#qa_non_hsa_kitting_list_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#qa_non_hsa_kitting_list_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                qa_non_hsa_kitting_list_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(qa_non_hsa_kitting_list_table, 'NonHSA'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    qa_non_hsa_kitting_list_table.ajax.reload(null, false);
                }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;
        }
    }

    function loadProcessDoneTable(inTable, process_section) {
        inTable = $('#' + inTable).DataTable({
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            // 'searching': false,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_job_process_table_data_done',
                    process_section: process_section
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
                    className: 'dt-body-middle-center',
                    width: '3%'
                }, {
                    targets: [1, 7, 8, 9, 12],
                    className: 'dt-body-middle-center',
                    width: '6%'
                },
                {
                    targets: [2, 3, 4, 5, 6, 11],
                    className: 'dt-body-middle-left',
                    width: '10%'
                }, {
                    targets: 10,
                    className: 'dt-body-middle-right',
                    width: '6%'
                }

            ]
        });
        inTable.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            inTable.ajax.reload(null, false);
        }, 120000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadMateriallistTable(inTable, material_section) {
        inTable = $('#' + inTable).DataTable({
            'lengthMenu': [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_material_table',
                    material_section: material_section,
                    access_level: access_level
                }
            },
            'columnDefs': [{
                    targets: 0,
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: [1, 2, 3, 4, 6],
                    className: 'dt-body-middle-left',
                    width: '13%'
                },
                {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '6%'
                },
                {
                    targets: 7,
                    className: 'dt-nowrap-center',
                    width: '5%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return materialActionUser(data[0], data[1], data[2], data[3], data[4])
                    }
                }
            ],
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }
        });
        setInterval(function() {
            inTable.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function materialActionUser(jobentryid, material_id, material_section, material_status, access_level) {
        if (access_level == 'ESP') {
            if (material_section == 'Sticker Section' || material_section == 'Logsheet Checklist Section') {
                btnMaterialStatus = materialBtnAction(material_status, jobentryid, material_id, material_section); //* ======== Sticker / Logsheet Checklist Section ========
            } else {
                btnMaterialStatus = materialBtnActionDefault(material_status); //* ======== Default ========
            }
        } else if (access_level == 'PKL' || access_level == 'KPC' || access_level == 'JPA' || access_level == 'KPT' || access_level == 'JAT' || access_level == 'SPA' || access_level == 'PQS' || access_level == 'SMO') {
            if (material_section == 'Sticker Section' || material_section == 'Carrier Section' || material_section == 'Sim Pairing Section') {
                btnMaterialStatus = materialBtnAction(material_status, jobentryid, material_id, material_section); //* ======== Sticker / Carrier / Sim Pairing Section ========
            } else {
                btnMaterialStatus = materialBtnActionDefault(material_status); //* ======== Default ========
            }
        } else if (access_level == 'EDS' || access_level == 'EDR') {
            if (material_section == 'Waybill Section') {
                btnMaterialStatus = materialBtnAction(material_status, jobentryid, material_id, material_section); //* ======== Waybill Section ========
            } else {
                btnMaterialStatus = materialBtnActionDefault(material_status); //* ======== Default ========
            }
        } else if (access_level == 'EVS' || access_level == 'EVF' || access_level == 'EFT' || access_level == 'PLS') {
            if (material_section == 'Collateral Section') {
                btnMaterialStatus = materialBtnAction(material_status, jobentryid, material_id, material_section); //* ======== Collateral Section ========
            } else {
                btnMaterialStatus = materialBtnActionDefault(material_status); //* ======== Default ========
            }
        } else {
            btnMaterialStatus = materialBtnActionDefault(material_status); //* ======== Default ========
        }
        return btnMaterialStatus;
    }

    function materialBtnAction(material_status, jobentryid, material_id, material_section) {
        if (material_status == 'Pending') {
            materialAction = `<button type="button" class="btn btn-success col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-title="Start" onclick="materialProcessStart('` + jobentryid + `','` + material_id + `','` + material_section + `')"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>`;
        } else if (material_status == 'Hold') {
            materialAction = `<span class="badge bg-danger col-sm-12">Hold</span>`;
        } else {
            materialAction = `<span class="badge bg-dark col-sm-12">Done</span>`;
        }
        return materialAction;
    }

    function materialBtnActionDefault(material_status) {
        if (material_status == 'Pending') {
            materialAction = '<span class="badge bg-warning col-sm-12">Pending</span>';
        } else if (material_status == 'Hold') {
            materialAction = '<span class="badge bg-danger col-sm-12">Hold</span>';
        } else {
            materialAction = '<span class="badge bg-dark col-sm-12">Done</span>';
        }
        return materialAction;
    }

    function updateDataTableSelectAllCtrl(table, category) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody .rowChkBox', $table);
        var $chkbox_checked = $('tbody .rowChkBox:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        if ($chkbox_checked.length === 0) { //* If none of the checkboxes are checked
            chkbox_select_all.checked = false;

            addInputDisabled(category);
            clearValues();

            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
        } else if ($chkbox_checked.length === $chkbox_all.length) { //* If all of the checkboxes are checked
            chkbox_select_all.checked = true;

            removeInputDisabled(category);

            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
        } else { //* If some of the checkboxes are checked
            chkbox_select_all.checked = true;

            removeInputDisabled(category);

            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = true;
            }
        }
    }

    function savePlanner(category) {
        switch (category) {
            case 'Inkjet':
                $.each($('#inkjetList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(17)').text(), $(data).find('td:eq(18)').text()]
                    ]);
                });
                updateProcess(values, $('#inkjet_process_priority').val(), $('#inkjet_process_machine').val(), $('#inkjet_process_release_date').val(), $('#inkjet_process_instruction').val(), category);
                break;

            case 'Persomaster':
                $.each($('#persomasterList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(17)').text(), $(data).find('td:eq(18)').text()]
                    ]);
                });
                updateProcess(values, $('#persomaster_process_priority').val(), $('#persomaster_process_machine').val(), $('#persomaster_process_release_date').val(), $('#persomaster_process_instruction').val(), category);
                break;

            case 'Persoline':
                $.each($('#persolineList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(17)').text(), $(data).find('td:eq(18)').text()]
                    ]);
                });
                updateProcess(values, $('#persoline_process_priority').val(), $('#persoline_process_machine').val(), $('#persoline_process_release_date').val(), $('#persoline_process_instruction').val(), category);
                break;

            case 'Embossing':
                $.each($('#embossingList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(16)').text(), $(data).find('td:eq(17)').text()]
                    ]);
                });
                updateProcess(values, $('#embossing_process_priority').val(), '', $('#embossing_process_release_date').val(), $('#embossing_process_instruction').val(), category);
                break;

            case 'EmbossingPack':
                $.each($('#embossingPackList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(16)').text(), $(data).find('td:eq(17)').text()]
                    ]);
                });
                updateProcess(values, $('#embossing_pack_process_priority').val(), '', $('#embossing_pack_process_release_date').val(), $('#embossing_pack_process_instruction').val(), category);
                break;

            case 'Packaging':
                $.each($('#packaging_list_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(16)').text(), $(data).find('td:eq(17)').text()]
                    ]);
                });
                updateProcess(values, $('#packaging_process_priority').val(), '', $('#packaging_process_release_date').val(), $('#packaging_process_instruction').val(), category);
                break;

            case 'NonHSA':
                $.each($('#qa_non_hsa_kitting_list_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(16)').text(), $(data).find('td:eq(17)').text()]
                    ]);
                });
                updateProcess(values, $('#nonhsa_process_priority').val(), '', $('#nonhsa_process_release_date').val(), $('#nonhsa_process_instruction').val(), category);
                break;

            case 'HSA':
                $.each($('#hsa_kitting_list_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(17)').text(), $(data).find('td:eq(18)').text()]
                    ]);
                });
                updateProcess(values, $('#hsa_process_priority').val(), $('#hsa_process_machine').val(), $('#hsa_process_release_date').val(), $('#hsa_process_instruction').val(), category);
                break;

            case 'HSAPacking':
                $.each($('#hsa_kitting_forPackinglist_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(17)').text(), $(data).find('td:eq(18)').text()]
                    ]);
                });
                updateProcess(values, $('#hsa_pack_process_priority').val(), $('#hsa_pack_process_machine').val(), $('#hsa_pack_process_release_date').val(), $('#hsa_pack_process_instruction').val(), category);
                break;
        }
    }

    function updateProcess(values, process_priority, process_machine, release_date, process_instructions, category) {
        for (let i = 0; i < values.length; i++) {
            var tblData = values[i];
            var strData = tblData.toString().split(',');
            jobentry_id = strData[0];
            process_id = strData[1];

            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_monitoring_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_process_planner',
                    jobentry_id: jobentry_id,
                    process_id: process_id,
                    process_priority: process_priority,
                    process_machine: process_machine,
                    release_date: release_date,
                    process_instructions: process_instructions
                }
            });
        }
        values = [];

        cancelPlanner(category);

        setTimeout(function() {
            switch (category) {
                case 'Inkjet':
                    inkjetList_table.ajax.reload(null, false);
                    break;

                case 'Persomaster':
                    persomasterList_table.ajax.reload(null, false);
                    break;

                case 'Persoline':
                    persolineList_table.ajax.reload(null, false);
                    break;

                case 'Embossing':
                    embossingList_table.ajax.reload(null, false);
                    break;

                case 'EmbossingPack':
                    embossingPackList_table.ajax.reload(null, false);
                    break;

                case 'Packaging':
                    packaging_list_table.ajax.reload(null, false);
                    break;

                case 'NonHSA':
                    qa_non_hsa_kitting_list_table.ajax.reload(null, false);
                    break;

                case 'HSA':
                    hsa_kitting_list_table.ajax.reload(null, false);
                    break;

                case 'HSAPacking':
                    hsa_kitting_forPackinglist_table.ajax.reload(null, false);
                    break;
            }
        }, 200);
    }

    function materialProcessStart(jobentryid, material_id, material_section) {
        $('#materialProcessStartModal').modal('show');
        $('#material_jobentryid').val(jobentryid);
        $('#materialid').val(material_id);
        $('#material_section').val(material_section);

        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_material_operator',
                empno: empno
            },
            success: function(result) {
                $("#material_operator").empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#material_operator option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#material_operator').append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result.emp_name != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#material_operator option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#material_operator').append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    }

    function saveMaterialProcess() {
        if (inputValidation('material_operator', 'material_operator_remarks')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'material_job_start',
                    jobentryid: $('#material_jobentryid').val(),
                    materialid: $('#materialid').val(),
                    material_section: $('#material_section').val(),
                    material_operator_remarks: $('#material_operator_remarks').val(),
                    emp_name: $('#material_operator').val()
                },
                success: function(result) {
                    $('#materialProcessStartModal').modal('hide');
                    clearValues();
                    refreshMaterialSection();
                }
            });
        }
    }

    function cancelPlanner(category) {
        var $table;
        switch (category) {
            case 'Inkjet':
                $table = $('#inkjetList_table').DataTable().table().node();
                $('#inkjetList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#inkjetList_table').find('tr').removeClass('selected');
                break;

            case 'Persomaster':
                $table = $('#persomasterList_table').DataTable().table().node();
                $('#persomasterList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#persomasterList_table').find('tr').removeClass('selected');
                break;

            case 'Persoline':
                $table = $('#persolineList_table').DataTable().table().node();
                $('#persolineList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#persolineList_table').find('tr').removeClass('selected');
                break;

            case 'Embossing':
                $table = $('#embossingList_table').DataTable().table().node();
                $('#embossingList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#embossingList_table').find('tr').removeClass('selected');
                break;

            case 'EmbossingPack':
                $table = $('#embossingPackList_table').DataTable().table().node();
                $('#embossingPackList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#embossingPackList_table').find('tr').removeClass('selected');
                break;

            case 'Packaging':
                $table = $('#packaging_list_table').DataTable().table().node();
                $('#packaging_list_table').find('input[type="checkbox"]').prop('checked', false);
                $('#packaging_list_table').find('tr').removeClass('selected');
                break;

            case 'NonHSA':
                $table = $('#qa_non_hsa_kitting_list_table').DataTable().table().node();
                $('#qa_non_hsa_kitting_list_table').find('input[type="checkbox"]').prop('checked', false);
                $('#qa_non_hsa_kitting_list_table').find('tr').removeClass('selected');
                break;

            case 'HSA':
                $table = $('#hsa_kitting_list_table').DataTable().table().node();
                $('#hsa_kitting_list_table').find('input[type="checkbox"]').prop('checked', false);
                $('#hsa_kitting_list_table').find('tr').removeClass('selected');
                break;

            case 'HSAPacking':
                $table = $('#hsa_kitting_forPackinglist_table').DataTable().table().node();
                $('#hsa_kitting_forPackinglist_table').find('input[type="checkbox"]').prop('checked', false);
                $('#hsa_kitting_forPackinglist_table').find('tr').removeClass('selected');
                break;
        }

        var $chkbox_checked = $('tbody .rowChkBox:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);
        if ($chkbox_checked.length > 0) { //* If some of the checkboxes are checked
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
                chkbox_select_all.indeterminate = false;
            }
        }
        addInputDisabled(category);
        clearValues();
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('textarea').val('');
        values = [];
        clearAttributes();
    }

    function removeInputDisabled(category) {
        switch (category) {
            case 'Inkjet':
                $('#inkjet_process_priority').prop('disabled', false);
                $('#inkjet_process_machine').prop('disabled', false);
                $('#inkjet_process_release_date').prop('disabled', false);
                $('#inkjet_process_instruction').prop('disabled', false);
                $('.btnSaveInkjet').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelInkjet').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Persomaster':
                $('#persomaster_process_priority').prop('disabled', false);
                $('#persomaster_process_machine').prop('disabled', false);
                $('#persomaster_process_release_date').prop('disabled', false);
                $('#persomaster_process_instruction').prop('disabled', false);
                $('.btnSavePersomaster').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelPersomaster').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Persoline':
                $('#persoline_process_priority').prop('disabled', false);
                $('#persoline_process_machine').prop('disabled', false);
                $('#persoline_process_release_date').prop('disabled', false);
                $('#persoline_process_instruction').prop('disabled', false);
                $('.btnSavePersoline').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelPersoline').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Embossing':
                $('#embossing_process_priority').prop('disabled', false);
                $('#embossing_process_release_date').prop('disabled', false);
                $('#embossing_process_instruction').prop('disabled', false);
                $('.btnSaveEmbossing').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelEmbossing').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'EmbossingPack':
                $('#embossing_pack_process_priority').prop('disabled', false);
                $('#embossing_pack_process_release_date').prop('disabled', false);
                $('#embossing_pack_process_instruction').prop('disabled', false);
                $('.btnSaveEmbossingPack').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelEmbossingPack').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Packaging':
                $('#packaging_process_priority').prop('disabled', false);
                $('#packaging_process_release_date').prop('disabled', false);
                $('#packaging_process_instruction').prop('disabled', false);
                $('.btnSavePackaging').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelPackaging').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'NonHSA':
                $('#nonhsa_process_priority').prop('disabled', false);
                $('#nonhsa_process_release_date').prop('disabled', false);
                $('#nonhsa_process_instruction').prop('disabled', false);
                $('.btnSaveNonHSA').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelNonHSA').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'HSA':
                $('#hsa_process_priority').prop('disabled', false);
                $('#hsa_process_release_date').prop('disabled', false);
                $('#hsa_process_machine').prop('disabled', false);
                $('#hsa_process_instruction').prop('disabled', false);
                $('.btnSaveHSA').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelHSA').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'HSAPacking':
                $('#hsa_pack_process_priority').prop('disabled', false);
                $('#hsa_pack_process_machine').prop('disabled', false);
                $('#hsa_pack_process_release_date').prop('disabled', false);
                $('#hsa_pack_process_instruction').prop('disabled', false);
                $('.btnSaveHSAPacking').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelHSAPacking').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;
        }
    }

    function addInputDisabled(category) {
        switch (category) {
            case 'Inkjet':
                $('#inkjet_process_priority').prop('disabled', true);
                $('#inkjet_process_machine').prop('disabled', true);
                $('#inkjet_process_release_date').prop('disabled', true);
                $('#inkjet_process_instruction').prop('disabled', true);
                $('.btnSaveInkjet').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelInkjet').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'Persomaster':
                $('#persomaster_process_priority').prop('disabled', true);
                $('#persomaster_process_machine').prop('disabled', true);
                $('#persomaster_process_release_date').prop('disabled', true);
                $('#persomaster_process_instruction').prop('disabled', true);
                $('.btnSavePersomaster').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelPersomaster').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'Persoline':
                $('#persoline_process_priority').prop('disabled', true);
                $('#persoline_process_machine').prop('disabled', true);
                $('#persoline_process_release_date').prop('disabled', true);
                $('#persoline_process_instruction').prop('disabled', true);
                $('.btnSavePersoline').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelPersoline').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'Embossing':
                $('#embossing_process_priority').prop('disabled', true);
                $('#embossing_process_release_date').prop('disabled', true);
                $('#embossing_process_instruction').prop('disabled', true);
                $('.btnSaveEmbossing').prop('disabled', true).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelEmbossing').prop('disabled', true).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'EmbossingPack':
                $('#embossing_pack_process_priority').prop('disabled', true);
                $('#embossing_pack_process_release_date').prop('disabled', true);
                $('#embossing_pack_process_instruction').prop('disabled', true);
                $('.btnSaveEmbossingPack').prop('disabled', true).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelEmbossingPack').prop('disabled', true).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Packaging':
                $('#packaging_process_priority').prop('disabled', true);
                $('#packaging_process_release_date').prop('disabled', true);
                $('#packaging_process_instruction').prop('disabled', true);
                $('.btnSavePackaging').prop('disabled', true).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelPackaging').prop('disabled', true).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'NonHSA':
                $('#nonhsa_process_priority').prop('disabled', true);
                $('#nonhsa_process_release_date').prop('disabled', true);
                $('#nonhsa_process_instruction').prop('disabled', true);
                $('.btnSaveNonHSA').prop('disabled', true).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelNonHSA').prop('disabled', true).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'HSA':
                $('#hsa_process_priority').prop('disabled', true);
                $('#hsa_process_release_date').prop('disabled', true);
                $('#hsa_process_instruction').prop('disabled', true);
                $('#hsa_process_machine').prop('disabled', true);
                $('.btnSaveHSA').prop('disabled', true).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelHSA').prop('disabled', true).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'HSAPacking':
                $('#hsa_pack_process_priority').prop('disabled', true);
                $('#hsa_pack_process_machine').prop('disabled', true);
                $('#hsa_pack_process_release_date').prop('disabled', true);
                $('#hsa_pack_process_instruction').prop('disabled', true);
                $('.btnSaveHSAPacking').prop('disabled', true).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelHSAPacking').prop('disabled', true).removeClass('btn-secondary').addClass('btn-danger');
                break;
        }
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
        $('textarea').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>