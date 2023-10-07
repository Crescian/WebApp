<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
// * Check if module is within the application and if module is granted to user
session_start();
$firstPage = "SELECT appmenuid, app_menu_title, app_menu_parent_id, app_menu_link FROM bpi_app_menu_module 
    INNER JOIN bpi_access_module ON bpi_access_module.appmenu_id = bpi_app_menu_module.appmenuid
    WHERE bpi_app_menu_module.app_id = '{$_GET['app_id']}' AND access_user = '{$_SESSION['empno']}'AND app_menu_link <> '#'
    ORDER BY appmenuid LIMIT 1";
$stmtfirstPage = $BannerWebLive->prepare($firstPage);
$stmtfirstPage->execute();
$firstPageResult = $stmtfirstPage->fetch(PDO::FETCH_ASSOC);

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
} else if ($firstPageResult['app_menu_link'] != $currentPage) {
    header("location: {$firstPageResult['app_menu_link']}?app_id={$_GET['app_id']}");
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
                <span class="page-title-perso">Dashboard</span>
            </div>


            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="row row-cols-1 row-cols-sm-1 g-4">
                        <div class="col">
                            <div class="card shadow h-100" style="max-height:20rem;">
                                <div class="card-header custom-header-color text-center">
                                    <span class="fw-bold text-primary fs-18">On-Going Dispatch</span>
                                </div>
                                <div class="card-body dispatch_ongoing_list">
                                    <ul class="dispatch_ongoing_ul"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-12">
                    <div class="row row-cols-1 row-cols-sm-3">
                        <!-- Printing Division Card -->
                        <div class="col mb-3">
                            <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="loadProcessTimeline('Printing Division','Yes');">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-primary text-uppercase mb-1">Printing Division</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="printing_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-print fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Embossing Division Card -->
                        <div class="col mb-3">
                            <div class="card border-left-info shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="loadProcessTimeline('Embossing Division','Yes');">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-info text-uppercase mb-1">Embossing Division</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="embossing_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-brands fa-elementor fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Packaging Division Card -->
                        <div class="col mb-3">
                            <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="loadProcessTimeline('Packaging Division','Yes');">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-warning text-uppercase mb-1">Packaging Division</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="packaging_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-boxes-stacked fa-flip fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Vault Division Card -->
                        <div class="col mb-3">
                            <div class="card border-left-dark shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="loadProcessTimeline('Vault Division','Yes');">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Vault Division</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="vault_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-vault fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dispatching Division Card -->
                        <div class="col mb-3">
                            <div class="card border-left-danger shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="loadProcessTimeline('Dispatching Division','Yes');">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-danger text-uppercase mb-1">Dispatching Division</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="dispatch_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-people-carry fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Released Card -->
                        <div class="col mb-3">
                            <div class="card border-left-success shadow h-100 py-2 card-body-hover-pointer">
                                <div class="card-body" onclick="loadRelease();">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="fs-20 fw-bold text-success text-uppercase mb-1">Released</div>
                                            <div class="h4 mb-0 fw-bold text-gray-800" id="released_count"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa-solid fa-shipping-fast fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5" id="process_timeline_section">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-4">
                            <div class="row">
                                <div class="col-sm-11">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Process Timeline</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="filterModal('processTimeline');"><i class="fa-solid fa-filter p-r-8"></i> Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="process_timeline_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">#</th>
                                            <th style="text-align:center;">Date Received</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th>Process</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">DR#</th>
                                            <th style="text-align:center;">Delivery Date</th>
                                            <th style="text-align:center;">Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">#</th>
                                            <th style="text-align:center;">Date Received</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th>Process</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">DR#</th>
                                            <th style="text-align:center;">Delivery Date</th>
                                            <th style="text-align:center;">Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-4">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light">Job Entry Timeline</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="dropdown">
                                                <button class="btn btn-light dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-JobEntryTimeline.php"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                                    <li><a class="dropdown-item card-body-hover-pointer" onclick="exportCsvFile();"><i class="fa-solid fa-file-csv p-r-8"></i>CSV</a></li>
                                                    <li><a class="dropdown-item card-body-hover-pointer" onclick="exportPdfFile();"><i class="fa-solid fa-file-pdf p-r-8"></i>PDF</a></li>
                                                    <li><a class="dropdown-item card-body-hover-pointer" onclick="exportDocFile();"><i class="fa-solid fa-file p-r-8"></i>DOC</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="row">
                                                <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="filterModal('jobEntry');"><i class="fa-solid fa-filter p-r-8"></i> Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="job_entry_timeline_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Received</th>
                                            <th style="text-align:center;">Cut-Off</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th>Instruction</th>
                                            <th style="text-align:center;">Delivery Date</th>
                                            <th style="text-align:center;">DR#</th>
                                            <th style="text-align:center;">Courier</th>
                                            <th style="text-align:center;">Delivery Mode</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Received</th>
                                            <th style="text-align:center;">Cut-Off</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th>Instruction</th>
                                            <th style="text-align:center;">Delivery Date</th>
                                            <th style="text-align:center;">DR#</th>
                                            <th style="text-align:center;">Courier</th>
                                            <th style="text-align:center;">Delivery Mode</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4" id="process_released_section">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-4">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light">Released</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="dropdown">
                                                <button class="btn btn-light dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Released.php"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                                    <li><a class="dropdown-item card-body-hover-pointer" onclick="exportCsvFile();"><i class="fa-solid fa-file-csv p-r-8"></i>CSV</a></li>
                                                    <li><a class="dropdown-item card-body-hover-pointer" onclick="exportPdfFile();"><i class="fa-solid fa-file-pdf p-r-8"></i>PDF</a></li>
                                                    <li><a class="dropdown-item card-body-hover-pointer" onclick="exportDocFile();"><i class="fa-solid fa-file p-r-8"></i>DOC</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="row">
                                                <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="filterModal('jobReleased');"><i class="fa-solid fa-filter p-r-8"></i> Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="process_released_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th style="text-align:center;">Cut-Off</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Released Date</th>
                                            <th style="text-align:center;">DR. No.</th>
                                            <th>Remarks</th>
                                            <th>Mode of Delivery</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th style="text-align:center;">Cut-Off</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Released Date</th>
                                            <th style="text-align:center;">DR. No.</th>
                                            <th>Remarks</th>
                                            <th>Mode of Delivery</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Process Timeline Filter Modal =============== -->
            <div class="modal fade" id="filterTimelineModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="filter-title"></h4>
                        </div>
                        <div class="modal-body">
                            <input type="text" class="form-control" id="jobDivision" disabled>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control fw-bold" id="filter_date_entry_from" aria-label="Date Entry From">
                                        <div class="invalid-feedback"></div>
                                        <label for="filter_date_entry_from" class="fw-bold">Date Entry From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control fw-bold" id="filter_date_entry_to" aria-label="Date Entry To">
                                        <div class="invalid-feedback"></div>
                                        <label for="filter_date_entry_to" class="fw-bold">Date Entry To</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control fw-bold" id="filter_date_received_from" aria-label="Date Entry From">
                                        <div class="invalid-feedback"></div>
                                        <label for="filter_date_received_from" class="fw-bold">Date Received From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control fw-bold" id="filter_date_received_to" aria-label="Date Entry To">
                                        <div class="invalid-feedback"></div>
                                        <label for="filter_date_received_to" class="fw-bold">Date Received To</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="filter_customer"></select>
                                <div class="invalid-feedback"></div>
                                <label for="filter_customer" class="fw-bold">Customer</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="filter_jonumber"></select>
                                <div class="invalid-feedback"></div>
                                <label for="filter_customer" class="fw-bold">J.O Number</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="filter_filename"></select>
                                <div class="invalid-feedback"></div>
                                <label for="filter_customer" class="fw-bold">Filename</label>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control fw-bold" id="filter_date_delivery_from" aria-label="Delivery Date From">
                                        <div class="invalid-feedback"></div>
                                        <label for="filter_date_delivery_from" class="fw-bold">Delivery Date From</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control fw-bold" id="filter_date_delivery_to" aria-label="Delivery Date To">
                                        <div class="invalid-feedback"></div>
                                        <label for="filter_date_delivery_to" class="fw-bold">Delivery Date To</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating">
                                <select class="form-select fw-bolder" id="filter_status">
                                    <option value="">Choose...</option>
                                    <option value="Hold">Hold</option>
                                    <option value="Process Hold">Process Hold</option>
                                    <option value="On-Going">On-Going</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Done">Done</option>
                                    <option value="Process Done">Process Done</option>
                                </select>
                                <label for="filter_status" class="fw-bold">Status</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-primary btnFilterProcess" onclick="processFilter(this);"><i class="fa-regular fa-circle-check"></i> Apply</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-solid fa-xmark"></i> Close</button>
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
?>
<script>
    loadProcessCount('Printing Division');
    loadProcessCount('Embossing Division');
    loadProcessCount('Packaging Division');
    loadProcessCount('Vault Division');
    loadProcessCount('Dispatching Division');
    loadReleasedCount();
    loadDispatchOngoing();
    loadProcessTimeline('Printing Division', 'No');
    loadJobEntryTimelineTable('No', '', '', '', '', '', '', '', '');
    loadReleasedTimelineTable();

    function loadProcessCount(processDivision) {
        $.ajax({
            url: 'functions/perso_dashboard_functions.php',
            type: 'POST',
            data: {
                action: 'load_job_process_count',
                processDivision: processDivision
            },
            success: function(result) {
                switch (processDivision) {
                    case 'Printing Division':
                        $('#printing_count').html(result);
                        break;
                    case 'Embossing Division':
                        $('#embossing_count').html(result);
                        break;
                    case 'Packaging Division':
                        $('#packaging_count').html(result);
                        break;
                    case 'Vault Division':
                        $('#vault_count').html(result);
                        break;
                    case 'Dispatching Division':
                        $('#dispatch_count').html(result);
                        break;
                }
            }
        });
        setInterval(function() {
            loadProcessCount(processDivision);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadReleasedCount() {
        $.ajax({
            url: 'functions/perso_dashboard_functions.php',
            type: 'POST',
            data: {
                action: 'load_released_count'
            },
            success: function(result) {
                $('#released_count').html(result);
            }
        });
        setInterval(function() {
            loadReleasedCount();
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadDispatchOngoing() {
        $.ajax({
            url: 'functions/perso_dashboard_functions.php',
            type: 'POST',
            data: {
                action: 'load_dispatch_ongoing_list'
            },
            success: function(result) {
                $('.dispatch_ongoing_ul').html(result);
            }
        });

        setInterval(function() {
            loadDispatchOngoing();
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadProcessTimeline(processDivision, smoothScroll) {
        $('#process_division_title').html('Process Timeline' + ' - ' + processDivision); //* ======= Change Process Timeline Caption per Division =======
        $('#jobDivision').val(processDivision);
        if (smoothScroll == 'Yes') {
            document.querySelector('#process_timeline_section').scrollIntoView({
                behavior: 'smooth'
            }); //* ======= Smooth Scroll to process_timeline_section Division =======
        }
        loadProcessData(processDivision);
    }

    function loadProcessData(processDivision) {
        var process_timeline_table = $('#process_timeline_table').dataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'destroy': true,
            'serverSide': true,
            'processing': true,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_dashboard_functions.php',
                type: 'POST',
                data: {
                    action: 'load_process_data_timeline',
                    processDivision: processDivision
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '5%'
            }, {
                targets: [1, 9],
                className: 'dt-body-middle-center',
                width: '8%'
            }, {
                targets: [2, 3, 4],
                className: 'dt-body-middle-left',
                width: '10%'
            }, {
                targets: 5,
                className: 'dt-body-middle-left',
                width: '13%'
            }, {
                targets: 6,
                className: 'dt-body-middle-left'
            }, {
                targets: 7,
                className: 'dt-body-middle-right',
                width: '6%'
            }, {
                targets: 8,
                className: 'dt-body-middle-center',
                width: '8%'
            }, {
                targets: [9, 10],
                className: 'dt-body-middle-center',
                width: '7%'
            }]
        });
        // setInterval(function() {
        //     process_timeline_table.ajax.reload(null, false);
        // }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadJobEntryTimelineTable(filtered, dateentryfrom, dateentryto, customername, jonumber, jobfilename, deliverydatefrom, deliverydateto, filter_status) {
        var job_entry_timeline_table = $('#job_entry_timeline_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'ajax': {
                url: 'functions/perso_dashboard_functions.php',
                type: 'POST',
                data: {
                    action: 'load_job_entry_timeline_data',
                    filtered: filtered,
                    dateentryfrom: dateentryfrom,
                    dateentryto: dateentryto,
                    customername: customername,
                    jonumber: jonumber,
                    jobfilename: jobfilename,
                    deliverydatefrom: deliverydatefrom,
                    deliverydateto: deliverydateto,
                    filter_status: filter_status
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); //* ======== Hide tooltip every table draw ========
            },
            'columnDefs': [{
                targets: [0, 1, 8, 11, 12],
                className: 'dt-body-middle-center',
                width: '6%'
            }, {
                targets: [2, 3, 4, 5, 7],
                className: 'dt-body-middle-left',
                width: '10%'
            }, {
                targets: 6,
                className: 'dt-body-middle-right',
                width: '6%'
            }, {
                targets: [9, 10],
                className: 'dt-body-middle-center',
                width: '7%'
            }, {
                targets: 13,
                className: 'dt-nowrap-center',
                orderable: false
            }]
        });
        setInterval(function() {
            job_entry_timeline_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadReleasedTimelineTable() {
        var process_released_table = $('#process_released_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'serverSide': true,
            'destroy': true,
            'ajax': {
                url: 'functions/perso_dashboard_functions.php',
                type: 'POST',
                data: {
                    action: 'load_release_table_data'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); //* ======== Hide tooltip every table draw ========
            },
            'columnDefs': [{
                targets: [0, 7],
                className: 'dt-body-middle-center',
                width: '15%'
            }, {
                targets: [1, 9],
                className: 'dt-body-middle-center',
                width: '8%'
            }, {
                targets: [2, 3, 4],
                className: 'dt-body-middle-left',
                width: '20%'
            }, {
                targets: 5,
                className: 'dt-body-middle-left'
            }, {
                targets: 6,
                className: 'dt-body-middle-right',
                width: '10%'
            }, {
                targets: 8,
                className: 'dt-body-middle-center',
                width: '10%'
            }]
        });
        setInterval(function() {
            process_released_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadRelease() {
        document.querySelector('#process_released_section').scrollIntoView({
            behavior: 'smooth'
        }); //* ======= Smooth Scroll to process_released_section Division =======
    }

    function filterModal(val) {
        if (val == 'jobEntry') {
            $('#filter-title').html('Job Entry Timeline Filter');
        } else if (val == 'processTimeline') {
            $('#filter-title').html('Process Timeline Filter');
        } else {
            $('#filter-title').html('Released Timeline Filter');
        }
        $('.btnFilterProcess').val(val);
        $('#filterTimelineModal').modal('show');
        loadDropDownFilter('customer_name', 'bpi_perso_job_entry', 'filter_customer');
        loadDropDownFilter('jonumber', 'bpi_perso_job_entry', 'filter_jonumber');
        loadDropDownFilter('job_filename', 'bpi_perso_job_entry', 'filter_filename');
    }

    function loadDropDownFilter(inField, inTable, inObject) {
        $.ajax({
            url: 'functions/perso_dashboard_functions.php',
            type: 'POST',
            data: {
                action: 'load_dropdown_filter',
                inField: inField,
                inTable: inTable
            },
            success: function(result) {
                $('#' + inObject).html(result);
            }
        });
    }

    function processFilter(val) {
        var filterCategory = val.value;
        var processDivision = document.getElementById('jobDivision').value;
        var dateentryfrom = document.getElementById('filter_date_from').value;
        var dateentryto = document.getElementById('filter_date_to').value;
        var customername = document.getElementById('filter_customer').value;
        var jonumber = document.getElementById('filter_jonumber').value;
        var jobfilename = document.getElementById('filter_filename').value;
        var deliverydatefrom = document.getElementById('filter_delivery_date_from').value;
        var deliverydateto = document.getElementById('filter_delivery_date_to').value;
        var filter_status = document.getElementById('filter_status').value;
        var filtered = 'Yes';
        alert('hello');

        // $('#filterTimelineModal').modal('hide');
        // clearValues()
        // if (filterCategory == 'jobEntry') {
        //     loadJobEntryTimelineTable(filtered, dateentryfrom, dateentryto, customername, jonumber, jobfilename, deliverydatefrom, deliverydateto, filter_status);
        // }
        // else if (filterCategory == 'processTimeline') {
        //     loadProcessTimeLineData(processDivision, filtered, dateentryfrom, dateentryto, customername, jonumber, jobfilename, deliverydatefrom, deliverydateto, filter_status);
        // } else {
        //     loadReleaseTableData(filtered, dateentryfrom, dateentryto, customername, jonumber, jobfilename, deliverydatefrom, deliverydateto);
        // }
    }

    function exportCsvFile() {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: 'Under Maintenance!',
            text: '',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function exportPdfFile() {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: 'Under Maintenance!',
            text: '',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function exportDocFile() {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: 'Under Maintenance!',
            text: '',
            showConfirmButton: false,
            timer: 1500
        });
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
    }
</script>
</body>
<html>