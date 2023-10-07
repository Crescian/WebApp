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

function fill_operator_select_box($BannerWebLive, $section_name)
{
    $output = '';
    $result_sql = "SELECT empno,(emp_fn || ' ' || emp_sn) AS emp_name FROM prl_employee
        INNER JOIN bpi_assigned_section ON bpi_assigned_section.sec_job_title = prl_employee.pos_code
        INNER JOIN bpi_section_perso ON bpi_section_perso.sectionpersoid = bpi_assigned_section.sectionperso_id
        WHERE section_name = :section_name ORDER BY emp_name ASC";
    $result_stmt = $BannerWebLive->prepare($result_sql);
    $result_stmt->bindParam(':section_name', $section_name);
    $result_stmt->execute();
    $result_row = $result_stmt->fetchAll();
    foreach ($result_row as $row) {
        $output .= '<option value="' . $row["emp_name"] . '">' . $row["emp_name"] . '</option>';
    }
    return $output;
    $BannerWebLive = null; //* ======== Close Connection ========
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

    /* @media only screen and (max-width: 480px) { */
    @media only screen and (max-width: 1200px) {
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
<div class="container-fluid px-5 py-3"><!-- ==================== CONTENT SECTION ==================== -->
    <div class="row">
        <span class="page-title-perso">Personalization Operation</span>
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
                </ul><!-- ========== Nav Tabs End ========== -->
                <hr>
                <div class="tab-content" id="myTabContent"><!-- ======================= Nav tabs Content ======================= -->

                    <div class="tab-pane fade active show" id="processSection" role="tabpanel" aria-labelledby="processSection-tab">
                        <!-- ======================= Sub Nav tabs ======================= -->
                        <ul class="nav nav-tabs nav-fill flex-column flex-sm-row mt-4" role="tablist">
                            <li class="nav-item dropdown" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 dropdown-toggle" id="printing_tab_dropdown" data-bs-toggle="dropdown">Printing Section <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><button class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="inkjetSection-tab" data-bs-toggle="tab" data-bs-target="#inkjetSection" role="tab" aria-controls="inkjetSection" aria-selected="true">Inkjet</button></li>
                                    <li><button class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="persomasterPersolineSection-tab" data-bs-toggle="tab" data-bs-target="#persomasterPersolineSection" role="tab" aria-controls="persomasterPersolineSection" aria-selected="false">Persomaster / Persoline</button></li>
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
                            <li class="nav-item dropdown" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 dropdown-toggle" id="vault_tab_dropdown" data-bs-toggle="dropdown">Vault Section<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="vaultSection-tab" data-bs-toggle="tab" data-bs-target="#vaultSection" role="tab" aria-controls="vaultSection" aria-selected="false">Vault Section</button></li>
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="withDrPickupSection-tab" data-bs-toggle="tab" data-bs-target="#withDrPickupSection" role="tab" aria-controls="withDrPickupSection" aria-selected="false">With DR - Pickup</button></li>
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="withDrDeliverySection-tab" data-bs-toggle="tab" data-bs-target="#withDrDeliverySection" role="tab" aria-controls="withDrDeliverySection" aria-selected="false">With DR - Delivery</button></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5 dropdown-toggle" id="dispatching_tab_dropdown" data-bs-toggle="dropdown">Dispatching Section<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="dispatchingSection-tab" data-bs-toggle="tab" data-bs-target="#dispatchingSection" role="tab" aria-controls="dispatchingSection" aria-selected="false">Dispatching Section</button></li>
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="dispatchReceivedDrSection-tab" data-bs-toggle="tab" data-bs-target="#dispatchReceivedDrSection" role="tab" aria-controls="dispatchReceivedDrSection" aria-selected="false">Received DR#</button></li>
                                    <li><button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="dispatchDoneSection-tab" data-bs-toggle="tab" data-bs-target="#dispatchDoneSection" role="tab" aria-controls="dispatchDoneSection" aria-selected="false">Dispatch Done</button></li>
                                </ul>
                            </li>
                        </ul>
                        <hr>
                        <!-- ======================= Sub Nav tabs Content ======================= -->
                        <div class="tab-content" id="mySubTabContent">
                            <div class="tab-pane fade" id="inkjetSection" role="tabpanel" aria-labelledby="inkjetSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Inkjet Section</h3>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="inkjetList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Inkjet Section Tab End ======================= -->
                            <div class="tab-pane fade" id="persomasterPersolineSection" role="tabpanel" aria-labelledby="persomasterPersolineSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Persomaster Machine</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="persomasterPersolineList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
                                            <table id="persomasterMachineList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Persomaster/Persoline Machine Section Tab End ======================= -->
                            <div class="tab-pane fade" id="embossingSection" role="tabpanel" aria-labelledby="embossingSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Embossing/Datacard Section</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive" id="embossing_table">
                                            <table id="embossingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">For Packing</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive" id="embossing_table">
                                            <table id="embossing_packingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Embossing Section Tab End ======================= -->
                            <div class="tab-pane fade" id="packagingSection" role="tabpanel" aria-labelledby="packagingSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Packaging Section</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="packaging_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Packaging Section Tab End ======================= -->
                            <div class="tab-pane fade" id="qANonHsaKitting" role="tabpanel" aria-labelledby="qANonHsaKitting-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">QA/Non HSA Kitting Section</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="qa_non_hsa_kitting_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= QA/Non HSA Kitting Section Tab End ======================= -->
                            <div class="tab-pane fade" id="hsaKitting" role="tabpanel" aria-labelledby="hsaKitting-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">HSA Kitting Section</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="hsa_kitting_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Priority</th>
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
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Machine</th>
                                                        <th style="text-align:center;">Action</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
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
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= HSA Kitting Section Tab End ======================= -->
                            <div class="tab-pane fade" id="vaultSection" role="tabpanel" aria-labelledby="vaultSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Vault Section</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="vault_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                        <th style="text-align:center;">Delivery</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">Action</th>
                                                        <th style="text-align:center;">Delivery</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Vault Section Tab End ======================= -->
                            <!-- ======================= Vault Sub Tab ======================= -->
                            <div class="tab-pane fade" id="withDrPickupSection" role="tabpanel" aria-labelledby="withDrPickupSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">With DR - Pickup</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="vault_withDrPickup_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">D.R #</th>
                                                        <th style="text-align:center;">Delivery</th>
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">D.R #</th>
                                                        <th style="text-align:center;">Delivery</th>
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Vault with DR pickup Section Tab End ======================= -->
                            <div class="tab-pane fade" id="withDrDeliverySection" role="tabpanel" aria-labelledby="withDrDeliverySection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">With DR - Delivery</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="vault_withDrDelivery_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">D.R #</th>
                                                        <th style="text-align:center;">Delivery</th>
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Service No.</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Released Date</th>
                                                        <th style="text-align:center;">D.R #</th>
                                                        <th style="text-align:center;">Delivery</th>
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Service No.</th>
                                                        <th style="text-align:center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Vault with DR Delivery Section Tab End ======================= -->
                            <!-- ======================= Vault Sub Tab End ======================= -->
                            <div class="tab-pane fade" id="dispatchingSection" role="tabpanel" aria-labelledby="dispatchingSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Dispatching Section</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="dispatching_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th style="text-align:center;">Courier</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">DR #</th>
                                                        <th style="text-align:center;">Action</th>
                                                        <th style="text-align:center;">Mode of Delivery</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th style="text-align:center;">Courier</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">DR #</th>
                                                        <th style="text-align:center;">Action</th>
                                                        <th style="text-align:center;">Mode of Delivery</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">For Release</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="dispatching_forRelease_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th style="text-align:center;">Courier</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">DR #</th>
                                                        <th style="text-align:center;">Action</th>
                                                        <th style="text-align:center;">Mode of Delivery</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th style="text-align:center;">Courier</th>
                                                        <th style="text-align:center;">Status</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">DR #</th>
                                                        <th style="text-align:center;">Action</th>
                                                        <th style="text-align:center;">Mode of Delivery</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Dispatching Section Tab End ======================= -->
                            <div class="tab-pane fade" id="dispatchReceivedDrSection" role="tabpanel" aria-labelledby="dispatchReceivedDrSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Receive D.R#</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="dispatching_dr_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Time Entry</th>
                                                        <th style="text-align:center;">D.R Number</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th style="text-align:center;">Sign</th>
                                                        <th style="text-align:center;">Received By</th>
                                                        <th style="text-align:center;">Date Received</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Time Entry</th>
                                                        <th style="text-align:center;">D.R Number</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th style="text-align:center;">Sign</th>
                                                        <th style="text-align:center;">Received By</th>
                                                        <th style="text-align:center;">Date Received</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ======================= Dispatching Received DR Section Tab End ======================= -->
                            <div class="tab-pane fade" id="dispatchDoneSection" role="tabpanel" aria-labelledby="dispatchDoneSection-tab">
                                <div class="row mt-4">
                                    <h3 class="job-process-section-title">Dispatch Done</h3>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="table-responsive">
                                            <table id="dispatching_listDone_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Mode of Delivery</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">DR #</th>
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="customHeaderAdmin">
                                                    <tr>
                                                        <th style="text-align:center;">Date Entry</th>
                                                        <th>Customer</th>
                                                        <th>J.O Number</th>
                                                        <th>Description</th>
                                                        <th>Filename</th>
                                                        <th style="text-align:center;">Quantity</th>
                                                        <th>Instruction</th>
                                                        <th style="text-align:center;">Mode of Delivery</th>
                                                        <th style="text-align:center;">Release Date</th>
                                                        <th style="text-align:center;">DR #</th>
                                                        <th>Remarks</th>
                                                        <th style="text-align:center;">Info</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- ======================= Dispatching Done Section Tab End ======================= -->
                            </div><!-- ======================= Dispatching Done Tab End ======================= -->
                        </div><!-- ======================= MysubContent Tab End ======================= -->
                    </div><!-- ======================= Process Section Content End ======================= -->
                    <div class="tab-pane fade" id="materialSection" role="tabpanel" aria-labelledby="materialSection-tab">
                        <!-- ======================= Sub Nav tabs ======================= -->
                        <ul class="nav nav-tabs nav-fill flex-column flex-sm-row mt-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link nav-link-custom flex-sm-fill text-uppercase fs-5" id="stickerChecklistSection-tab" data-bs-toggle="tab" data-bs-target="#stickerChecklistSection" role="tab" aria-controls="stickerChecklistSection" aria-selected="false">STICKER LIST</button>
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
                        <hr>
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
                    </div><!-- ======================= Material Section Content End ======================= -->
                </div>
            </div>
        </div>
    </div>
    <!-- =============== Job Process Hold Modal =============== -->
    <div class="modal fade" id="jobProcessHoldModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">JOB PROCESS HOLD</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="jobentryid_hold" disabled>
                    <input type="hidden" class="form-control" id="processid_hold" disabled>
                    <div class="form-floating mb-2">
                        <textarea id="operator_remarks_hold" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                        <div class="invalid-feedback"></div>
                        <label for="operator_remarks_hold" class="col-form-label fw-bold">Operator Remarks</label>
                    </div>
                </div>
                <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    <button type="button" class="btn btn-success col-sm btnProcessHold" onclick="saveProcessHold(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Hold Modal End =============== -->
    <!-- =============== Job Process Done Modal =============== -->
    <div class="modal fade" id="jobProcessDoneModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">JOB PROCESS DONE</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="jobentryid_done" disabled>
                    <input type="hidden" class="form-control" id="processid_done" disabled>
                    <input type="hidden" class="form-control" id="sequenceNumber" disabled>
                    <input type="hidden" class="form-control" id="jobProcessDivision_done" disabled>
                    <input type="hidden" class="form-control" id="jobProcessSection" disabled>
                    <input type="hidden" class="form-control" id="operatorCount" disabled>
                    <div class="row mt-2 mb-2">
                        <div class="col">
                            <div id="operator_error"></div>
                            <div class="table-responsive">
                                <table id="operator_table" class="table table-bordered table-striped table-hover" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th width="15%" style="text-align:center;">#</th>
                                            <th>Operator</th>
                                            <th width="10%" style="text-align:center;"><button type="button" class="btn btn-primary btn-sm btnAddOperator" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Operator" onclick="addOperator();"><i class="fa-regular fa-square-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea id="operator_remarks_done" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                        <div class="invalid-feedback"></div>
                        <label for="operator_remarks_done" class="col-form-label fw-bold">Operator Remarks</label>
                    </div>
                </div>
                <div class="d-grid gap-2 col-sm-11 mx-auto mb-2">
                    <button type="button" class="btn btn-success col-sm btnSaveJobProcessDone" onclick="saveJobProcessDone();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Done Modal End =============== -->

    <!-- =============== Job Process Info Modal =============== -->
    <div class="modal fade" id="jobProcessInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">JOB PROCESS INFORMATION</h4>
                </div>
                <div class="modal-body">
                    <div class="form-floating mt-2 mb-2">
                        <input type="text" class="form-control fw-bold text-center" id="filename_info" disabled>
                        <div class="invalid-feedback"></div>
                        <label for="filename_info" class="col-form-label col-sm-2 fw-bolder">Filename</label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm">
                            <div class="row mt-2 mb-2">
                                <div class="table-responsive">
                                    <table id="printing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Printing Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="table-responsive">
                                    <table id="embossing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Embossing Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="table-responsive">
                                    <table id="packaging_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Packaging Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="table-responsive">
                                    <table id="vault_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Vault Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="table-responsive">
                                    <table id="dispatching_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Dispatching Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm">
                            <div class="table-responsive">
                                <table id="operator_table_info" class="table table-bordered" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th width="15%" style="text-align:center;">#</th>
                                            <th>Operator</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="form-floating mb-2">
                                <textarea id="operator_remarks_info" class="form-control fw-bold" style="resize:none;height: 120px" disabled></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="operator_remarks_info" class="col-form-label fw-bold">Operator Remarks</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Info Modal End =============== -->
    <!-- =============== Job Process DR Modal =============== -->
    <div class="modal fade" id="jobProcessDrModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">JOB PROCESS VAULT</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="jobentryid_dr" disabled>
                    <input type="hidden" class="form-control" id="processid_dr" disabled>
                    <input type="hidden" class="form-control" id="jobProcessSection_dr" disabled>
                    <input type="hidden" class="form-control" id="jobProcessSequence_dr" disabled>
                    <input type="hidden" class="form-control" id="dr_customerName" disabled>
                    <input type="hidden" class="form-control" id="dr_jonumber" disabled>
                    <input type="hidden" class="form-control" id="dr_jobDescription" disabled>
                    <div class="form-floating mt-2">
                        <select class="form-select fw-bold" id="dr_operator">
                            <option value="">Choose...</option>
                            <?php echo fill_operator_select_box($BannerWebLive, 'Vault Division'); ?>
                        </select>
                        <div class="invalid-feedback"></div>
                        <label for="dr_operator" class="fw-bolder">DR by:</label>
                    </div>
                    <div class="form-floating mt-3">
                        <select id="dr_number" class="form-select fw-bold"></select>
                        <div class="invalid-feedback"></div>
                        <label for="dr_number" class="fw-bolder">DR Number:</label>
                    </div>
                    <div class="form-floating mt-3 mb-2">
                        <textarea id="vault_operator_remarks" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                        <div class="invalid-feedback"></div>
                        <label for="vault_operator_remarks" class="col-form-label fw-bold">Operator Remarks</label>
                    </div>
                </div>
                <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    <button type="button" class="btn btn-success col-sm btnSaveJobProcessDr" onclick="saveJobProcessDr();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- =============== Job Process Service Report Modal =============== -->
    <div class="modal fade" id="jobVaultServiceReportModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">SERVICE REPORT</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="jobentryid_sr" disabled>
                    <div class="form-floating mt-2">
                        <select class="form-select fw-bold" id="vaultServiceReport_operator">
                            <option value="">Choose...</option>
                            <?php echo fill_operator_select_box($BannerWebLive, 'Vault Division'); ?>
                        </select>
                        <div class="invalid-feedback"></div>
                        <label for="vaultServiceReport_operator" class="fw-bolder fs-18">Prepared by :</label>
                    </div>
                    <div class="form-floating mt-3">
                        <select id="serviceReport_number" class="form-control fw-bold">
                            <option value="">Choose...</option>
                        </select>
                        <div class="invalid-feedback"></div>
                        <label for="serviceReport_number" class="fw-bolder fs-18">Service Report Number :</label>
                    </div>
                </div>
                <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    <button type="button" class="btn btn-success col-sm btnSaveVaultServiceReport" onclick="saveVaultServiceReport();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Service Report Modal End =============== -->
    <!-- =============== Dispatcher Verify Courier Modal =============== -->
    <div class="modal fade" id="jobProcessDispatchVerifyCourierModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">VERIFY COURIER</h4>
                </div>
                <div class="modal-body">
                    <div class="section_courier">
                        <div class="swiper slide-container container_courier">
                            <div class="swiper-wrapper content_courier"></div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <input type="hidden" class="form-control" id="verify_jobentryid" disabled>
                    <input type="hidden" class="form-control" id="verify_processid" disabled>
                </div>
                <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    <button type="button" class="btn btn-success btnStartDispatch col-sm-12" onclick="startDispatch();"><i class="fa-brands fa-google-play p-r-8"></i> Start Dispatch</button>
                    <button type="button" class="btn btn-danger btnCancelDispatch col-sm-12" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-solid fa-ban p-r-8"></i> Cancel Dispatch</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Dispatcher Verify Courier Modal End =============== -->
    <!-- =============== DR Assign Modal =============== -->
    <div class="modal fade" id="drAssignModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">DR# RECEIVED BY</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="drassignid" disabled>
                    <div class="form-floating">
                        <select class="form-select fw-bold" id="dr_assign_received_by">
                            <option value="">Choose...</option>
                            <option value="Jazel Mae D. Gaoat">Jazel Mae D. Gaoat</option>
                            <option value="Jefferson J. Eclavea">Jefferson J. Eclavea</option>
                            <option value="Louiezel M. Malabanan">Louiezel M. Malabanan</option>
                            <option value="Ryan P. Tabing">Ryan P. Tabing</option>
                        </select>
                        <div class="invalid-feedback"></div>
                        <label for="dr_assign_received_by" class="fw-bolder fs-18">Received By</label>
                    </div>
                </div>
                <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    <button type="button" class="btn btn-success col-sm btnSaveDrAssign" onclick="saveDrAssign();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== DR Assign Modal End =============== -->
    <!-- =============== Job Process Dispatch Info Modal =============== -->
    <div class="modal fade" id="jobProcessDispatchInfoModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header card-4">
                    <h4 class="modal-title text-uppercase fw-bold text-light">JOB PROCESS DISPATCH INFORMATION</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="dispatch_printing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Printing Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- =============== Printing Division End =============== -->
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="dispatch_embossing_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Embossing Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- =============== Embossing Division End =============== -->
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="dispatch_packaging_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Packaging Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- =============== Packaging Division End =============== -->
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="dispatch_vault_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Vault Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- =============== Vault Division End =============== -->
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="dispatch_dispatching_division_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                        <thead class="customHeaderAdmin">
                                            <tr>
                                                <th width="50%">Dispatching Division</th>
                                                <th width="20%" style="text-align:center;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div><!-- =============== Dispatching Division End =============== -->
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold text-center" id="dispatch_time_start" disabled>
                                        <label for="dispatch_time_start" class="col-form-label fw-bolder">Time Start</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold text-center" id="dispatch_time_end" disabled>
                                        <label for="dispatch_time_end" class="col-form-label fw-bolder">Time End</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control fw-bold" id="dispatch_customer" disabled>
                                <label for="dispatch_customer" class="col-form-label fw-bolder">Customer</label>
                            </div>
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control fw-bold" id="dispatch_jonumber" disabled>
                                <label for="dispatch_jonumber" class="col-form-label fw-bolder">J.O Number</label>
                            </div>
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control fw-bold" id="dispatch_description" disabled>
                                <label for="dispatch_description" class="col-form-label fw-bolder">Description</label>
                            </div>
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control fw-bold text-center" id="dispatch_courier" disabled>
                                <label for="dispatch_courier" class="col-form-label fw-bolder">Courier</label>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold text-center" id="dispatch_date_entry" disabled>
                                        <label for="dispatch_date_entry" class="col-form-label fw-bolder">Date Entry</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold text-center" id="dispatch_release_date" disabled>
                                        <label for="dispatch_release_date" class="col-form-label fw-bolder">Release Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold text-center" id="dispatch_process_status" disabled>
                                        <label for="dispatch_process_status" class="col-form-label fw-bolder">Status</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating mt-2">
                                        <input type="text" class="form-control fw-bold text-center" id="dispatch_dr_number" disabled>
                                        <label for="dispatch_dr_number" class="col-form-label fw-bolder">DR Number</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mt-2">
                                <textarea id="operator_dispatch_remarks" class="form-control fw-bold" style="resize:none;height: 120px" disabled></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="operator_dispatch_remarks" class="col-form-label fw-bold">Operator Remarks</label>
                            </div>
                            <input type="hidden" class="form-control" id="operatorDispatchCountInfo" disabled>
                            <div class="row mt-2">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table id="dispatch_operator_table_info" class="table table-bordered" width="100%">
                                            <thead class="customHeaderAdmin">
                                                <tr>
                                                    <th width="15%" style="text-align:center;">#</th>
                                                    <th>Operator</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Dispatch Info Modal End =============== -->
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

    const swiper = new Swiper('.swiper', {
        slidesPerView: 2,
        spaceBetween: 1,
        centerInsufficientSlides: true,
        freeMode: true,
        grabCursor: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            500: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1280: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
        }
    });

    var access_level = '<?php echo $_SESSION['access_lvl']; ?>';
    var empno = '<?php echo $_SESSION['empno']; ?>';
    let inTable;
    let processAction;
    let materialAction;
    let btnStatus;
    let btnMaterialStatus;
    let operatorCount = 0;

    //* ========== Load Page by User Designation ==========
    switch (access_level) {
        case 'INK':
        case 'JKT':
        case 'JIO':
        case 'SIO':
        case 'SIK':
        case 'FLO':
        case 'SFG':
        case 'JFG':
            $('#printing_tab_dropdown').addClass('active');
            $('#inkjetSection-tab').addClass('active');
            $('#inkjetSection').addClass('active show');
            break;

        case 'ETH':
        case 'DPR':
            $('#embossingSection-tab').addClass('active');
            $('#embossingSection').addClass('active show');
            break;

        case 'PKL':
        case 'KPC':
        case 'JPA':
        case 'KPT':
        case 'JAT':
        case 'SPA':
        case 'PQS':
        case 'SMO':
            $('#packaging_tab_dropdown').addClass('active');
            $('#packagingSection-tab').addClass('active');
            $('#packagingSection').addClass('active show');

            $('#stickerChecklistSection-tab').addClass('active');
            $('#stickerChecklistSection').addClass('active show');
            break;

        case 'EVS':
        case 'EVF':
        case 'EFT':
        case 'PLS':
            $('#vault_tab_dropdown').addClass('active');
            $('#vaultSection-tab').addClass('active');
            $('#vaultSection').addClass('active show');

            $('#collateralSection-tab').addClass('active');
            $('#collateralSection').addClass('active show');
            break;

        case 'EDS':
        case 'EDR':
        case 'PLS':
            $('#dispatching_tab_dropdown').addClass('active');
            $('#dispatchingSection-tab').addClass('active');
            $('#dispatchingSection').addClass('active show');

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

    loadProcessTable('inkjetList_table', 'Inkjet Section', 'default', access_level);
    loadProcessTable('persomasterPersolineList_table', 'Persomaster/Persoline Section', 'Persomaster', access_level);
    loadProcessTable('persomasterMachineList_table', 'Persomaster/Persoline Section', 'Persoline', access_level);
    loadProcessTable('hsa_kitting_list_table', 'HSA Kitting Section', 'default', access_level);
    loadProcessTable('hsa_kitting_forPackinglist_table', 'HSA Kitting Section', 'For Packing', access_level);
    loadProcessDoneTable('inkjetListDone_table', 'Inkjet Section');
    loadProcessDoneTable('persomasterPersolineListDone_table', 'Persomaster/Persoline Section');
    loadProcessDoneTable('hsa_kitting_listDone_table', 'HSA Kitting Section');

    loadEmbossingPackagingTable('embossingList_table', 'Embossing/Datacard Section', 'default', access_level);
    loadEmbossingPackagingTable('embossing_packingList_table', 'Embossing/Datacard Section', 'For Packing', access_level);
    loadEmbossingPackagingTable('packaging_list_table', 'Packaging Section', 'default', access_level);
    loadEmbossingPackagingTable('qa_non_hsa_kitting_list_table', 'QA/Non HSA Kitting Section', 'default', access_level);
    loadEmbossingPackagingDoneTable('embossingListDone_table', 'Embossing/Datacard Section');
    loadEmbossingPackagingDoneTable('packaging_listDone_table', 'Packaging Section');
    loadEmbossingPackagingDoneTable('qa_non_hsa_kitting_listDone_table', 'QA/Non HSA Kitting Section');

    loadVaultTable();
    loadVaultWithDrPickupTable();
    loadVaultWithDrDeliveryTable();

    loadDispatchingTable('dispatching_list_table', 'Dispatching Section', 'default', access_level);
    loadDispatchingTable('dispatching_forRelease_list_table', 'Dispatching Section', 'for_Released', access_level);
    loadDispatchingDrListTable();
    loadDispatchingDoneTable();

    loadMateriallistTable('stickerChecklist_table', 'Sticker Section');
    loadMateriallistTable('carrierChecklist_table', 'Carrier Section');
    loadMateriallistTable('simPairingChecklist_table', 'Sim Pairing Section');
    loadMateriallistTable('waybillChecklist_table', 'Waybill Section');
    loadMateriallistTable('logsheetChecklist_table', 'Logsheet Checklist Section');
    loadMateriallistTable('dataPrepList_table', 'Data Preparation Section');
    loadMateriallistTable('cardAndFormList_table', 'Card and Form Section');
    loadMateriallistTable('collateralList_table', 'Collateral Section');

    loadMateriallistDoneTable('stickerChecklistDone_table', 'Sticker Section');
    loadMateriallistDoneTable('carrierChecklistDone_table', 'Carrier Section');
    loadMateriallistDoneTable('simPairingChecklistDone_table', 'Sim Pairing Section');
    loadMateriallistDoneTable('waybillChecklistDone_table', 'Waybill Section');
    loadMateriallistDoneTable('logsheetChecklistDone_table', 'Logsheet Checklist Section');
    loadMateriallistDoneTable('dataPrepListDone_table', 'Data Preparation Section');
    loadMateriallistDoneTable('cardAndFormListDone_table', 'Card and Form Section');
    loadMateriallistDoneTable('collateralListDone_table', 'Collateral Section');

    function loadProcessTable(inTable, process_section, job_category, access_level) {
        inTable = $('#' + inTable).DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_job_process_data',
                    process_section: process_section,
                    job_category: job_category,
                    access_level: access_level
                }
            },
            'columnDefs': [{
                    targets: 0,
                    className: 'dt-body-middle-center',
                    width: '3%',
                    orderable: false
                },
                {
                    targets: [1, 7, 8, 9, 12, 13],
                    className: 'dt-body-middle-center',
                    width: '5%',
                    orderable: false
                },
                {
                    targets: [2, 3, 4, 5, 6, 11],
                    className: 'dt-body-middle-left',
                    width: '10%',
                    orderable: false
                },
                {
                    targets: 10,
                    className: 'dt-body-middle-right',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: 14,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return processActionUser(data[9], data[3], data[4], data[5], data[6], data[7], data[8], data[0], data[1], data[2])
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

    function loadProcessDoneTable(inTable, process_section) {
        inTable = $('#' + inTable).DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
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
                    width: '3%',
                    orderable: false
                }, {
                    targets: [1, 7, 8, 9, 12, 13],
                    className: 'dt-body-middle-center',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: [2, 3, 4, 5, 6, 11, 14],
                    className: 'dt-body-middle-left',
                    width: '10%',
                    orderable: false
                }, {
                    targets: 10,
                    className: 'dt-body-middle-right',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: 15,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobProcessInfo('${data[0]}','${data[1]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                    }
                }
            ]
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
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadEmbossingPackagingTable(inTable, process_section, job_category, access_level) {
        inTable = $('#' + inTable).DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_job_process_data',
                    process_section: process_section,
                    job_category: job_category,
                    access_level: access_level
                }
            },
            'columnDefs': [{
                    targets: 0,
                    className: 'dt-body-middle-center',
                    width: '3%',
                    orderable: false
                }, {
                    targets: [1, 7, 8, 9, 12],
                    className: 'dt-body-middle-center',
                    width: '5%',
                    orderable: false
                },
                {
                    targets: [2, 3, 4, 5, 6, 11],
                    className: 'dt-body-middle-left',
                    width: '10%',
                    orderable: false
                }, {
                    targets: 10,
                    className: 'dt-body-middle-right',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: 13,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return processActionUser(data[9], data[3], data[4], data[5], data[6], data[7], data[8], data[0], data[1], data[2])
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

    function loadEmbossingPackagingDoneTable(inTable, process_section) {
        inTable = $('#' + inTable).DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
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
                    width: '3%',
                    orderable: false
                }, {
                    targets: [1, 7, 8, 9, 12],
                    className: 'dt-body-middle-center',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: [2, 3, 4, 5, 6, 11, 13],
                    className: 'dt-body-middle-left',
                    width: '10%',
                    orderable: false
                }, {
                    targets: 10,
                    className: 'dt-body-middle-right',
                    width: '6%',
                    orderable: false
                },
                {
                    targets: 14,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobProcessInfo('${data[0]}','${data[1]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                    }
                }
            ]
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
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }


    function loadVaultTable() {
        var vault_list_table = $('#vault_list_table').DataTable({
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
                    action: 'load_vault_table_data',
                    process_section: 'Vault Section',
                    access_level: access_level
                }
            },
            'columnDefs': [{
                    targets: [0, 6],
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: [2, 4],
                    className: 'dt-body-middle-left',
                    width: '13%'
                }, {
                    targets: [1, 3],
                    className: 'dt-body-middle-left',
                    width: '14%'
                }, {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '6%'
                }, {
                    targets: 7,
                    className: 'dt-body-middle-center',
                    width: '5%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        let btnStatus;
                        if (data[3] == 'Pending' && data[5] == data[6] && data[7] == data[8]) {
                            if (data[12] == 'EVS' || data[12] == 'EVF' || data[12] == 'EFT' || data[12] == 'PLS') {
                                btnStatus = `<button type="button" class="btn btn-success col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="DR Job Process" onclick="jobProcessDr('${data[0]}','${data[1]}','${data[4]}','${data[2]}','${data[6]}','${data[9].replace("'", "\\'")}','${data[10]}','${data[11]}');"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>`
                            } else {
                                if (data[3] == 'Pending') {
                                    btnStatus = `<span class="badge bg-warning col-sm-12">Pending</span>`
                                } else if (data[3] == 'Hold') {
                                    btnStatus = `<span class="badge bg-danger col-sm-12">On Hold</span>`
                                } else {
                                    btnStatus = `<span class="badge bg-warning col-sm-12">Pending</span>`
                                }
                            }
                        } else if (data[3] == 'Hold') {
                            btnStatus = `<span class="badge bg-danger col-sm-12">On Hold</span>`
                        } else {
                            btnStatus = `<span class="badge bg-warning col-sm-12">Pending</span>`
                        }
                        return btnStatus
                    }
                },
                {
                    targets: 8,
                    className: 'dt-body-middle-center',
                    width: '6%'
                }, {
                    targets: 9,
                    className: 'dt-nowrap-center',
                    width: '3%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement= "top" data-bs-original-title="View Information" onclick="jobProcessInfo('${data[0]}','${data[1]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
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
            vault_list_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadVaultWithDrPickupTable() {
        var vault_withDrPickup_table = $('#vault_withDrPickup_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_vault_table_data_done',
                    mode_delivery: 'Pick up'
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
                    targets: [0, 7],
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: [1, 2, 3, 4, 10],
                    className: 'dt-body-middle-left',
                    width: '13%'
                },
                {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '6%'
                }, {
                    targets: 6,
                    className: 'dt-body-middle-left'
                },
                {
                    targets: 8,
                    className: 'dt-body-middle-center',
                    width: '5%'
                }, {
                    targets: 9,
                    className: 'dt-body-middle-center',
                    width: '6%'
                },
                {
                    targets: 11,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement= "top" data-bs-original-title="View Information" onclick="jobProcessInfo('${data[0]}','${data[1]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                    }
                }
            ]
        });
        setTimeout(function() {
            vault_withDrPickup_table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            });
        }, 300);
        setInterval(function() {
            vault_withDrPickup_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadVaultWithDrDeliveryTable() {
        var vault_withDrDelivery_table = $('#vault_withDrDelivery_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_vault_table_data_done',
                    mode_delivery: 'Delivery'
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
                    targets: [0, 7],
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: [1, 2, 3, 4, 10],
                    className: 'dt-body-middle-left',
                    width: '13%'
                },
                {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '6%'
                }, {
                    targets: 6,
                    className: 'dt-body-middle-left'
                },
                {
                    targets: [8, 11],
                    className: 'dt-body-middle-center',
                    width: '5%'
                }, {
                    targets: 9,
                    className: 'dt-body-middle-center',
                    width: '6%'
                },
                {
                    targets: 12,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        let btnAction;
                        btnAction = `<button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobProcessInfo('${data[0]}','${data[1]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                        if (data[2] == '-') {
                            btnAction += ` <button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Add Service Report" onclick="jobVaultServiceReport('${data[0]}','${data[3]}');"><i class="fa-solid fa-flag fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                        }
                        return btnAction
                    }
                }
            ]
        });
        setTimeout(function() {
            vault_withDrDelivery_table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            });
        }, 300);
        setInterval(function() {
            vault_withDrDelivery_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadDispatchingTable(inTable, process_section, job_category, access_level) {
        inTable = $('#' + inTable).DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_dispatching_table_data',
                    process_section: process_section,
                    job_category: job_category,
                    access_level: access_level
                }
            },
            'columnDefs': [{
                    targets: [0, 6, 8],
                    className: 'dt-body-middle-center',
                    width: '6%'
                }, {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '5%'
                },
                {
                    targets: [1, 2, 3, 4],
                    className: 'dt-body-middle-left'
                }, {
                    targets: 7,
                    className: 'dt-body-middle-center',
                    width: '5%',
                    orderable: false
                }, {
                    targets: 9,
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: 10,
                    className: 'dt-nowrap-center',
                    width: '2%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        let btnAction;
                        if (data[8] == 'EDR' || data[8] == 'EDS' || data[8] == 'PLS') {
                            if (data[3] == 'Pending' && data[4] == data[5] && data[6] == data[7]) {
                                btnAction = `<button type="button" class="btn btn-success col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Dispatch Start" onclick="jobProcessDispatchStart('${data[0]}','${data[1]}');"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`
                            } else if (data[3] == 'On-Going') {
                                btnAction = `<button type="button" class="btn btn-secondary col-sm"disabled><i class="fa-brands fa-google-play"></i></button>
                                <button type="button" class="btn btn-dark col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Dispatch Done" onclick="jobProcessDispatchDone('${data[0]}','${data[1]}','${data[2]}','${data[5]}','${data[9]}');"><i class="fa-solid fa-clipboard-check fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>`
                            } else {
                                btnAction = `<button type="button" class="btn btn-secondary col-sm"disabled><i class="fa-brands fa-google-play"></i></button>
                                <button type="button" class="btn btn-secondary col-sm"disabled><i class="fa-solid fa-clipboard-check"></i></button>`
                            }
                        } else {
                            btnAction = `<button type="button" class="btn btn-secondary col-sm"disabled><i class="fa-brands fa-google-play"></i></button>
                            <button type="button" class="btn btn-secondary col-sm"disabled><i class="fa-solid fa-clipboard-check"></i></button>`
                        }
                        btnAction += ` <button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Dispatch Info" onclick="jobProcessDispatchInfo('${data[0]}','${data[1]}','${data[2]}','${data[5]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                        return btnAction
                    }
                }, {
                    targets: 11,
                    className: 'dt-body-middle-center',
                    width: '5%'
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

    function loadDispatchingDrListTable() {
        var dispatching_dr_list_table = $('#dispatching_dr_list_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_dispatching_dr_list_table_data'
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
                    targets: [0, 1, 7],
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: [2, 3, 4],
                    className: 'dt-body-middle-left',
                    width: '12%'
                },
                {
                    targets: 5,
                    className: 'dt-body-middle-center',
                    width: '4%'
                }, {
                    targets: 6,
                    className: 'dt-body-middle-center',
                    width: '8%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        let btnAction;
                        if (data[0] == '-') {
                            if (data[1] == '-') {
                                btnAction = `<button type="button" class="btn btn-secondary col-sm-9" disabled><i class="fa-solid fa-user-plus"></i></button>`
                            } else {
                                btnAction = `<button type="button" class="btn btn-primary col-sm-9" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Assign Received By" onclick="assignDrReceivedBy('${data[2]}');"><i class="fa-solid fa-user-plus fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>`
                            }
                        } else {
                            btnAction = data[0]
                        }
                        return btnAction
                    }
                }
            ]
        });
        setTimeout(function() {
            dispatching_dr_list_table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            });
        }, 300);
        setInterval(function() {
            dispatching_dr_list_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadDispatchingDoneTable() {
        var dispatching_listDone_table = $('#dispatching_listDone_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_dispatching_done_table_data',
                    process_section: 'Dispatching Section'
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
                    targets: [0, 8],
                    className: 'dt-body-middle-center',
                    width: '6%'
                }, {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '5%'
                },
                {
                    targets: [1, 2, 3, 4, 6, 10],
                    className: 'dt-body-middle-left'
                }, {
                    targets: 7,
                    className: 'dt-body-middle-center',
                    width: '6%'
                },
                {
                    targets: 9,
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: 11,
                    className: 'dt-nowrap-center',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobProcessDispatchInfo('${data[0]}','${data[1]}','${data[2]}','${data[3]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                    }
                }
            ]
        });
        setTimeout(function() {
            dispatching_listDone_table.on('draw', function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            });
        }, 300);
        setInterval(function() {
            dispatching_listDone_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadMateriallistTable(inTable, material_section) {
        inTable = $('#' + inTable).DataTable({
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

    function loadMateriallistDoneTable(inTable, material_section) {
        inTable = $('#' + inTable).DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_material_done_table',
                    material_section: material_section
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
                    targets: [0, 9],
                    className: 'dt-body-middle-center',
                    width: '7%'
                }, {
                    targets: [1, 2, 3, 4],
                    className: 'dt-body-middle-left',
                    width: '13%'
                },
                {
                    targets: 5,
                    className: 'dt-body-middle-right',
                    width: '6%'
                }, {
                    targets: [6, 7],
                    className: 'dt-body-middle-left',
                    width: '10%'
                },
                {
                    targets: 8,
                    className: 'dt-body-middle-center'
                }
            ]
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
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function processActionUser(process_division, access_level, row_processStatus, row_processSequence, processSequence, row_job_filename, jobFilename, row_jobentry_id, row_process_id, process_section) {
        if (process_division == 'Printing Division' && (access_level == 'INK' || access_level == 'JKT' || access_level == 'JIO' || access_level == 'SIO' || access_level == 'SIK' || access_level == 'FLO' || access_level == 'SFG' || access_level == 'JFG' || access_level == 'PLS')) {
            btnStatus = processBtnAction(row_processStatus, row_processSequence, processSequence, row_job_filename, jobFilename, row_jobentry_id, row_process_id, process_section, process_division); //* ======== Printing Section ========
        } else if (process_division == 'Embossing Division' && (access_level == 'ETH' || access_level == 'ESP' || access_level == 'DPR' || access_level == 'PLS')) {
            btnStatus = processBtnAction(row_processStatus, row_processSequence, processSequence, row_job_filename, jobFilename, row_jobentry_id, row_process_id, process_section, process_division); //* ======== Embossing Section ========
        } else if (process_division == 'Packaging Division' && (access_level == 'PKL' || access_level == 'KPC' || access_level == 'JPA' || access_level == 'KPT' || access_level == 'JAT' || access_level == 'SPA' || access_level == 'PQS' || access_level == 'SMO' || access_level == 'PLS')) {
            btnStatus = processBtnAction(row_processStatus, row_processSequence, processSequence, row_job_filename, jobFilename, row_jobentry_id, row_process_id, process_section, process_division); //* ======== Packaging Section ========
        } else {
            btnStatus = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-brands fa-google-play"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>
                <button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobProcessInfo('` + row_jobentry_id + `','` + row_process_id + `');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
        }
        return btnStatus;
    }

    function processBtnAction(row_processStatus, row_processSequence, processSequence, row_job_filename, jobFilename, row_jobentry_id, row_process_id, process_section, process_division) {
        if (row_processStatus == 'Pending' && row_processSequence == processSequence && row_job_filename == jobFilename) {
            processAction = `<button type="button" class="btn btn-success col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Start Job Process" onclick="jobProcessStart('` + row_jobentry_id + `','` + row_process_id + `','` + process_section + `');"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`;
        } else if (row_processStatus == 'Process Hold') {
            processAction = `<button type="button" class="btn btn-success col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Resume Job Process" onclick="jobProcessResume('` + row_jobentry_id + `','` + row_process_id + `','` + process_section + `');"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`;
        } else if (row_processStatus == 'On-Going') {
            processAction = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-brands fa-google-play"></i></button>
                <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Hold Job Process" onclick="jobProcessHold('` + row_jobentry_id + `','` + row_process_id + `','` + process_section + `');"><i class="fa-solid fa-circle-pause fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                <button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Partial Job Process" onclick="jobProcessPartial('` + row_jobentry_id + `','` + row_process_id + `','` + process_section + `');"><i class="fa-regular fa-star-half-stroke fa-flip" style="--fa-animation-duration: 2.5s;"></i></button>
                <button type="button" class="btn btn-dark col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Done Job Process" onclick="jobProcessDone('` + row_jobentry_id + `','` + row_process_id + `','` + row_processSequence + `','` + process_section + `','` + process_division + `');"><i class="fa-solid fa-clipboard-check fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>`;
        } else {
            processAction = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-brands fa-google-play"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`;
        }
        processAction += ` <button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View Information" onclick="jobProcessInfo('` + row_jobentry_id + `','` + row_process_id + `');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
        return processAction;
    }

    function materialActionUser(jobentryid, material_id, material_section, material_status, access_level) {
        if (access_level == 'ESP' || access_level == 'PQS') {
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
        } else if (access_level == 'DPR') {
            if (material_section == 'Data Preparation Section' || material_section == 'Card and Form Section') {
                btnMaterialStatus = materialBtnAction(material_status, jobentryid, material_id, material_section); //* ======== Data Preparation List / Card and Form List Section ========
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
            materialAction = `<button type="button" class="btn btn-success col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-original-title="Start" onclick="materialProcessStart('` + jobentryid + `','` + material_id + `','` + material_section + `')"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>`;
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

    function refreshProcessTable(section) {
        switch (section) {
            case 'Inkjet Section':
                $('#inkjetList_table').DataTable().ajax.reload(null, false);
                // $('#inkjetListDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Persomaster/Persoline Section':
                $('#persomasterPersolineList_table').DataTable().ajax.reload(null, false);
                // $('#persomasterPersolineListDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Embossing/Datacard Section':
                $('#embossingList_table').DataTable().ajax.reload(null, false);
                $('#embossing_packingList_table').DataTable().ajax.reload(null, false);
                // $('#embossingListDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Packaging Section':
                $('#packaging_list_table').DataTable().ajax.reload(null, false);
                // $('#packaging_listDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'QA/Non HSA Kitting Section':
                $('#qa_non_hsa_kitting_list_table').DataTable().ajax.reload(null, false);
                // $('#qa_non_hsa_kitting_listDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'HSA Kitting Section':
                $('#hsa_kitting_list_table').DataTable().ajax.reload(null, false);
                $('#hsa_kitting_forPackinglist_table').DataTable().ajax.reload(null, false);
                // $('#hsa_kitting_listDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Vault Section':
                $('#vault_list_table').DataTable().ajax.reload(null, false);
                $('#vault_withDrPickup_table').DataTable().ajax.reload(null, false);
                $('#vault_withDrDelivery_table').DataTable().ajax.reload(null, false);
                break;

            case 'Dispatching Section':
                $('#dispatching_list_table').DataTable().ajax.reload(null, false);
                $('#dispatching_forRelease_list_table').DataTable().ajax.reload(null, false);
                $('#dispatching_dr_list_table').DataTable().ajax.reload(null, false);
                // $('#dispatching_listDone_table').DataTable().ajax.reload(null, false);
                break;
        }
    }

    function refreshMaterialSection(section) {
        switch (section) {
            case 'Sticker Section':
                $('#stickerChecklist_table').DataTable().ajax.reload(null, false);
                // $('#stickerChecklistDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Carrier Section':
                $('#carrierChecklist_table').DataTable().ajax.reload(null, false);
                // $('#carrierChecklistDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Sim Pairing Section':
                $('#simPairingChecklist_table').DataTable().ajax.reload(null, false);
                // $('#simPairingChecklistDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Waybill Section':
                $('#waybillChecklist_table').DataTable().ajax.reload(null, false);
                // $('#waybillChecklistDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Logsheet Checklist Section':
                $('#logsheetChecklist_table').DataTable().ajax.reload(null, false);
                // $('#logsheetChecklistDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Data Preparation Section':
                $('#dataPrepList_table').DataTable().ajax.reload(null, false);
                // $('#dataPrepListDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Card and Form Section':
                $('#cardAndFormList_table').DataTable().ajax.reload(null, false);
                // $('#cardAndFormListDone_table').DataTable().ajax.reload(null, false);
                break;

            case 'Collateral Section':
                $('#collateralList_table').DataTable().ajax.reload(null, false);
                // $('#collateralListDone_table').DataTable().ajax.reload(null, false);
                break;
        }
    }

    function jobProcessStart(jobentry_id, process_id, process_section) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            data: {
                action: 'process_job_start',
                jobentry_id: jobentry_id,
                process_id: process_id,
                category: 'start'
            },
            success: function(result) {
                // Swal.fire({
                //     position: 'top',
                //     icon: 'success',
                //     title: 'Process Started.',
                //     text: '',
                //     showConfirmButton: false,
                //     timer: 500
                // });
                refreshProcessTable(process_section);
            }
        });
    }

    function jobProcessResume(jobentry_id, process_id, process_section) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            data: {
                action: 'process_job_start',
                jobentry_id: jobentry_id,
                process_id: process_id,
                category: 'resume'
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Process Started.',
                    text: '',
                    showConfirmButton: false,
                    timer: 500
                });
                refreshProcessTable(process_section);
            }
        });
    }

    function jobProcessHold(jobentry_id, process_id, process_section) {
        $('#jobProcessHoldModal').modal('show');
        $('#jobentryid_hold').val(jobentry_id);
        $('#processid_hold').val(process_id);
        $('.btnProcessHold').val(process_section);
    }

    function saveProcessHold(process_section) {
        if (inputValidation('operator_remarks_hold')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'process_job_hold',
                    jobentry_id: $('#jobentryid_hold').val(),
                    process_id: $('#processid_hold').val(),
                    operator_remarks: $('#operator_remarks_hold').val()
                },
                success: function(result) {
                    // Swal.fire({
                    //     position: 'top',
                    //     icon: 'success',
                    //     title: 'Process Hold.',
                    //     text: '',
                    //     showConfirmButton: false,
                    //     timer: 500
                    // });
                    refreshProcessTable(process_section);
                    $('#jobProcessHoldModal').modal('hide');
                    clearValues();
                }
            });
        }
    }

    function jobProcessPartial(jobentry_id, process_id, process_section) {
        Swal.fire({
            position: 'top',
            icon: 'info',
            title: 'Under Maintenance.',
            text: '',
            showConfirmButton: false,
            timer: 500
        });
    }

    function jobProcessDone(jobentry_id, process_id, processSequence, process_section, process_division) {
        $('#jobentryid_done').val(jobentry_id);
        $('#processid_done').val(process_id);
        $('#sequenceNumber').val(processSequence);
        $('#jobProcessDivision_done').val(process_division);
        $('#jobProcessSection').val(process_section);
        $('#operatorCount').val(0);
        $('#jobProcessDoneModal').modal('show');
        operatorCount = 0;
    }

    function saveJobProcessDone() {
        if (inputValidation('operator_remarks_done')) {
            var section = $('#jobProcessSection').val();
            if ($('#operatorCount').val() == 0) {
                $('#operator_error').removeClass('alert alert-success').addClass('alert alert-danger');
                $('#operator_error').html('<i class="fa-solid fa-circle-exclamation"></i><b> Please Select Operator</b>');
                $('#operator_error').fadeIn(300);
                setTimeout(function() {
                    $('#operator_error').fadeOut(1000);
                }, 1000);
            } else {
                let arrayOperatorId = [];
                //* ======= Save Process Operator =======
                $('.operator_name').each(function() {
                    let operatorId = $(this).val();
                    arrayOperatorId.push([operatorId]);
                });
                for (let i = 0; i < arrayOperatorId.length; i++) {
                    let strData = arrayOperatorId[i];
                    let process_operator = strData.toString();
                    $.ajax({
                        url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'save_job_process_operator',
                            jobentry_id: $('#jobentryid_done').val(),
                            process_id: $('#processid_done').val(),
                            process_section: $('#jobProcessSection').val(),
                            process_operator: process_operator
                        }
                    });
                }
                //* ======= Save Process Done =======
                $.ajax({
                    url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'process_job_done',
                        jobentry_id: $('#jobentryid_done').val(),
                        process_id: $('#processid_done').val(),
                        sequence_number: $('#sequenceNumber').val(),
                        operator_remarks: $('#operator_remarks_done').val()
                    },
                    success: function(result) {
                        // Swal.fire({
                        //     position: 'top',
                        //     icon: 'success',
                        //     title: 'Process Done.',
                        //     text: '',
                        //     showConfirmButton: false,
                        //     timer: 500
                        // });
                        $('#jobProcessDoneModal').modal('hide');
                        refreshProcessTable(section);
                        clearValues();
                    }
                });
            }
        }
    }

    function jobProcessInfo(jobentry_id, process_id) {
        $('#jobProcessInfoModal').modal('show');
        //* ======= Load Process Timeline ======
        loadJobProcessTimeline('Printing Division', jobentry_id, 'other');
        loadJobProcessTimeline('Embossing Division', jobentry_id, 'other');
        loadJobProcessTimeline('Packaging Division', jobentry_id, 'other');
        loadJobProcessTimeline('Vault Division', jobentry_id, 'other');
        loadJobProcessTimeline('Dispatching Division', jobentry_id, 'other');

        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_process_info',
                jobentry_id: jobentry_id,
                process_id: process_id
            },
            success: function(result) {
                $('#filename_info').val(result.job_filename);
                $('#operator_remarks_info').val(result.operator_remarks);

                //* ======= Load Process Info Operator =======
                $.ajax({
                    url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_job_process_operator',
                        jobentry_id: jobentry_id,
                        process_id: process_id
                    },
                    success: function(result) {
                        let html = '';
                        let operatorCount = 0;
                        $.each(result, (key, value) => {
                            operatorCount++;
                            html += '<tr>';
                            html += '<td><input type="text" class="form-control fw-bold" style="text-align:center;" value="' + operatorCount + '" disabled></td>';
                            html += '<td><input type="text" class="form-control fw-bold" value="' + value['process_operator'] + '" disabled></td>';
                            html += '</tr>';
                        });
                        $('#operator_table_info').append(html);
                    }
                });
            }
        });
    }

    function jobProcessDr(jobentryid, processid, jobProcessSection, orderid, processSequence, customerName, jonumber, job_description) {
        $('#jobProcessDrModal').modal('show');
        $('#jobentryid_dr').val(jobentryid);
        $('#processid_dr').val(processid);
        $('#jobProcessSection_dr').val(jobProcessSection);
        $('#jobProcessSequence_dr').val(processSequence);
        $('#dr_customerName').val(customerName);
        $('#dr_jonumber').val(jonumber);
        $('#dr_jobDescription').val(job_description);
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_dr_number',
                orderid: orderid,
                jonumber: jonumber
            },
            success: function(result) {
                $("#dr_number").empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#dr_number option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#dr_number').append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result.serviceno != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#dr_number option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#dr_number').append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    }

    function saveJobProcessDr() {
        if (inputValidation('dr_operator', 'dr_number', 'vault_operator_remarks')) {
            //* ======= Save Assigned DR =======
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_dr_assigned',
                    drnumber: $('#dr_number').val(),
                    customerName: $('#dr_customerName').val(),
                    jonumber: $('#dr_jonumber').val(),
                    jobDescription: $('#dr_jobDescription').val()
                }
            });
            //* ======= Save Operator =======
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_job_process_operator',
                    jobentry_id: $('#jobentryid_dr').val(),
                    process_id: $('#processid_dr').val(),
                    process_section: $('#jobProcessSection_dr').val(),
                    process_operator: $('#dr_operator').val()
                }
            });
            //* ======= Save DR Number =======
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_job_process_dr',
                    jobentryid: $('#jobentryid_dr').val(),
                    remarks: $('#vault_operator_remarks').val(),
                    drnumber: $('#dr_number').val(),
                    processid: $('#processid_dr').val(),
                    processSequence: $('#jobProcessSequence_dr').val()
                },
                success: function(result) {
                    $('#jobProcessDrModal').modal('hide');
                    clearValues();
                    refreshProcessTable('Vault Section');
                }
            });
        }
    }

    function jobVaultServiceReport(jobentryid, customer_name) {
        $('#jobVaultServiceReportModal').modal('show');
        $('#jobentryid_sr').val(jobentryid);
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_service_report_number',
                customer_name: customer_name
            },
            success: function(result) {
                $("#serviceReport_number").empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#serviceReport_number option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#serviceReport_number').append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result.serviceno != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#serviceReport_number option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#serviceReport_number').append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    }

    function saveVaultServiceReport() {
        if (inputValidation('vaultServiceReport_operator', 'serviceReport_number')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_service_report_number',
                    preparedby: $('#vaultServiceReport_operator').val(),
                    serviceno: $('#serviceReport_number').val(),
                    jobentry_id: $('#jobentryid_sr').val()
                },
                success: function(result) {
                    $('#jobVaultServiceReportModal').modal('hide');
                    clearValues();
                    refreshProcessTable('Vault Section');
                }
            });
        }
    }

    function jobProcessDispatchStart(jobentryid, processid) {
        $('#jobProcessDispatchVerifyCourierModal').modal('show');
        let html = '';
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_verify_courier_info',
                jobentryid: jobentryid
            },
            success: function(result) {
                $.each(result, (courier_name, courier_details) => {
                    html += '<div class="swiper-slide card_courier">';
                    html += '<div class="card_content_courier">';
                    $.each(courier_details, (courier_details_index, courier_details_value) => {
                        html += '<div class="image_courier">';
                        if (courier_details_value[3] == 'Pick up') {
                            html += '<img ' + courier_details_value[6] + ' alt="">';
                            // if (courier_details_value[5] == 'Arrived') {
                            //     $('.btnStartDispatch').prop('disabled', false);
                            //     $('.btnStartDispatch').removeClass('btn-secondary').addClass('btn-success');
                            // } else {
                            //     $('.btnStartDispatch').prop('disabled', true);
                            //     $('.btnStartDispatch').removeClass('btn-success').addClass('btn-secondary');
                            // }
                        } else {
                            html += '<img src="../vendor/images/Banner-Logo.png" alt="">';
                            // $('.btnStartDispatch').prop('disabled', false);
                            // $('.btnStartDispatch').removeClass('btn-secondary').addClass('btn-success');
                        }
                        html += '</div>';
                        html += '<div class="authorize_courier">';
                        html += '<span class="authorize_name">' + courier_name + '</span>';
                        html += '<span class="authorize_dr_number">' + courier_details_value[4] + '</span>';
                        html += '<span class="authorize_file_pickup">' + courier_details_value[2] + '</span>';
                        html += '<span class="authorize_company">' + courier_details_value[0] + '</span>';
                        html += '<span class="authorize_courier_type">' + courier_details_value[1] + '</span>';
                        html += '</div>';
                    });
                    html += '</div>';
                    html += '</div>';
                });
                $('.content_courier').html(html);
                $('#verify_jobentryid').val(jobentryid);
                $('#verify_processid').val(processid);
            }
        });
    }

    function startDispatch() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            data: {
                action: 'process_job_start',
                jobentry_id: $('#verify_jobentryid').val(),
                process_id: $('#verify_processid').val(),
                category: 'start'
            },
            success: function(result) {
                // Swal.fire({
                //     position: 'top',
                //     icon: 'success',
                //     title: 'Dispatch Started.',
                //     text: '',
                //     showConfirmButton: false,
                //     timer: 800
                // });
                refreshProcessTable('Dispatching Section');
                $('#jobProcessDispatchVerifyCourierModal').modal('hide');
            }
        });
    }

    function jobProcessDispatchDone(jobentryid, processid, process_section, process_sequence, process_division) {
        $('#jobentryid_done').val(jobentryid);
        $('#processid_done').val(processid);
        $('#sequenceNumber').val(process_sequence);
        $('#jobProcessDivision_done').val(process_division);
        $('#jobProcessSection').val(process_section);
        $('#operatorCount').val(0);
        $('#jobProcessDoneModal').modal('show');
    }

    function jobProcessDispatchInfo(jobentryid, processid, process_section, processSequence) {
        $('#jobProcessDispatchInfoModal').modal('show');
        //* ======= Load Process Timeline ======
        loadJobProcessTimeline('Printing Division', jobentryid, 'dispatch');
        loadJobProcessTimeline('Embossing Division', jobentryid, 'dispatch');
        loadJobProcessTimeline('Packaging Division', jobentryid, 'dispatch');
        loadJobProcessTimeline('Vault Division', jobentryid, 'dispatch');
        loadJobProcessTimeline('Dispatching Division', jobentryid, 'dispatch');
        //* ======= Load Process Info ======
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_process_dispatch_info',
                jobentryid: jobentryid,
                processid: processid,
                process_section: process_section,
                processSequence: processSequence
            },
            success: function(result) {
                $('#dispatch_customer').val(result.customer_name);
                $('#dispatch_jonumber').val(result.jonumber);
                $('#dispatch_description').val(result.job_description);
                $('#dispatch_courier').val(result.pickup_courier);
                $('#dispatch_date_entry').val(result.date_entry);
                $('#dispatch_release_date').val(result.release_date);
                $('#dispatch_time_start').val(result.date_time_start);
                $('#dispatch_time_end').val(result.date_time_end);
                $('#dispatch_dr_number').val(result.dr_number);
                $('#dispatch_process_status').val(result.process_status);
                $('#operator_dispatch_remarks').val(result.operator_remarks);
            }
        });
        //* ======= Load Process Info Operator =======
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_process_operator',
                jobentry_id: jobentryid,
                process_id: processid
            },
            success: function(result) {
                let html = '';
                let operatorCount = 0;
                $.each(result, (key, value) => {
                    operatorCount++;
                    html += '<tr>';
                    html += '<td><input type="text" class="form-control fw-bold" style="text-align:center;" value="' + operatorCount + '" disabled></td>';
                    html += '<td><input type="text" class="form-control fw-bold" value="' + value['process_operator'] + '" disabled></td>';
                    html += '</tr>';
                });
                $('#dispatch_operator_table_info').append(html);
            }
        });
    }

    function assignDrReceivedBy(drassignid) {
        $('#drAssignModal').modal('show');
        $('#drassignid').val(drassignid);
    }

    function saveDrAssign() {
        if (inputValidation('dr_assign_received_by')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_dr_assign_received_by',
                    drassignid: $('#drassignid').val(),
                    dr_assign_received_by: $('#dr_assign_received_by').val()
                },
                success: function(result) {
                    // Swal.fire({
                    //     position: 'top',
                    //     icon: 'success',
                    //     title: 'Successfully Updated.',
                    //     text: '',
                    //     showConfirmButton: false,
                    //     timer: 800
                    // });
                    $('#drAssignModal').modal('hide');
                    refreshProcessTable('Vault Section');
                    clearValues();
                }
            });
        }
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
            var section = $('#material_section').val();
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
                    refreshMaterialSection(section);
                }
            });
        }
    }

    function loadJobProcessTimeline(process_division, jobentry_id, category) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_process_operations_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_process_timeline',
                process_division: process_division,
                jobentry_id: jobentry_id
            },
            success: function(result) {
                let html = '';
                $.each(result, (key, value) => {
                    html += '<tr>';
                    html += '<td>' + value['process_name'] + '</td>';
                    html += '<td style="text-align:center;">' + processStatus(value['process_status']) + '</td>';
                    html += '</tr>';
                });
                switch (process_division) {
                    case 'Printing Division':
                        if (category == 'other') {
                            $('#printing_division_table').append(html);
                        } else {
                            $('#dispatch_printing_division_table').append(html);
                        }
                        break;
                    case 'Embossing Division':
                        if (category == 'other') {
                            $('#embossing_division_table').append(html);
                        } else {
                            $('#dispatch_embossing_division_table').append(html);
                        }
                        break;
                    case 'Packaging Division':
                        if (category == 'other') {
                            $('#packaging_division_table').append(html);
                        } else {
                            $('#dispatch_packaging_division_table').append(html);
                        }
                        break;
                    case 'Vault Division':
                        if (category == 'other') {
                            $('#vault_division_table').append(html);
                        } else {
                            $('#dispatch_vault_division_table').append(html);
                        }
                        break;
                    case 'Dispatching Division':
                        if (category == 'other') {
                            $('#dispatching_division_table').append(html);
                        } else {
                            $('#dispatch_dispatching_division_table').append(html);
                        }
                        break;
                }
            }
        });
    }

    function addOperator() {
        operatorCount++;
        var jobProcessDivision = document.getElementById('jobProcessDivision_done').value;
        $('#operatorCount').val(operatorCount);
        if (operatorCount > 6) {
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Operator Limit Reached.',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            operatorCount--;
        } else {
            let html = '';
            html += '<tr>';
            html += '<td><input type="text" name="sequence_number[]" class="form-control fw-bold sequence_number" style="text-align:center;" value="' + operatorCount + '" disabled></td>';
            switch (jobProcessDivision) {
                case 'Printing Division':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Printing Division'); ?> < /select></td > ';
                    break;
                case 'Embossing Division':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Embossing Division'); ?> < /select></td > ';
                    break;
                case 'Packaging Division':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Packaging Division'); ?> < /select></td > ';
                    break;
                case 'Vault Division':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Vault Division'); ?> < /select></td > ';
                    break;
                case 'Dispatching Division':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Dispatching Division'); ?> < /select></td > ';
                    break;
            }
            html += '<td style="text-align:center;"><button type="button" name="removeOperator" class="btn btn-danger btn-sm btnRemoveOperator"><i class="fa fa-minus"></i></button></td>';
            html += '</tr>';
            $('#operator_table').append(html);
        }
    }
    //* =======  REMOVE OPERATOR =======
    $('#operator_table').on('click', '.btnRemoveOperator', function() {
        $(this).closest('tr').remove();
        reCalcSeqNo();
        operatorCount--;
        $('#operatorCount').val(operatorCount);
    });

    function reCalcSeqNo() {
        $('.sequence_number').each(function(i) {
            $(this).val(i + 1);
        });
    }

    function processStatus($processStats) {
        switch ($processStats) {
            case 'Pending':
                $processStatus = '<span class="badge bg-warning col-sm-12">Pending</span>';
                break;
            case 'On-Going':
                $processStatus = '<span class="badge bg-success col-sm-12">On-Going</span>';
                break;
            case 'Process Done':
                $processStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Done':
                $processStatus = '<span class="badge bg-dark col-sm-12">Done</span>';
                break;
            case 'Hold':
                $processStatus = '<span class="badge bg-danger col-sm-12">On Hold</span>';
                break;
            case 'Process Hold':
                $processStatus = '<span class="badge bg-danger col-sm-12">Process Hold</span>';
                break;
        }
        return $processStatus;
    }

    function clearValues() {
        $('select').find('option:first').prop('selected', 'selected');
        $('textarea').val('');
        $('input').val('');

        $("#operator_table").find("tr:gt(0)").remove();
        $("#operator_table_info").find("tr:gt(0)").remove();

        $("#printing_division_table").find("tr:gt(0)").remove();
        $("#embossing_division_table").find("tr:gt(0)").remove();
        $("#packaging_division_table").find("tr:gt(0)").remove();
        $("#vault_division_table").find("tr:gt(0)").remove();
        $("#dispatching_division_table").find("tr:gt(0)").remove();

        $("#dispatch_operator_table_info").find("tr:gt(0)").remove();
        $("#dispatch_printing_division_table").find("tr:gt(0)").remove();
        $("#dispatch_embossing_division_table").find("tr:gt(0)").remove();
        $("#dispatch_packaging_division_table").find("tr:gt(0)").remove();
        $("#dispatch_vault_division_table").find("tr:gt(0)").remove();
        $("#dispatch_dispatching_division_table").find("tr:gt(0)").remove();

        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
        $('textarea').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>