<?php include './../includes/header.php';
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
        $output .= '<option value="' . $row["empno"] . '">' . $row["emp_name"] . '</option>';
    }
    return $output;
    $BannerWebLive = null; //* ======== Close Connection ========
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #291af5;
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
    <div class="row">
        <span class="page-title-production">Operations</span>
    </div>
    <!-- ==================== CONTENT SECTION ==================== -->
    <div class="row">
        <div class="card shadow mt-4">
            <div class="card-body">
                <!-- ========== Nav Tabs ========== -->
                <ul class="nav nav-tabs nav-fill flex-column flex-sm-row" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5 active" id="offsetSection-tab" data-bs-toggle="tab" data-bs-target="#offsetSection" role="tab" aria-controls="offsetSection" aria-selected="false">Offset Printing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="varnishSection-tab" data-bs-toggle="tab" data-bs-target="#varnishSection" role="tab" aria-controls="varnishSection" aria-selected="false">Varnish Printing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="hpSection-tab" data-bs-toggle="tab" data-bs-target="#hpSection" role="tab" aria-controls="hpSection" aria-selected="false">Digital Printing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="silkscreenSection-tab" data-bs-toggle="tab" data-bs-target="#silkscreenSection" role="tab" aria-controls="silkscreenSection" aria-selected="false">Silkscreen Printing</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="tslSection-tab" data-bs-toggle="tab" data-bs-target="#tslSection" role="tab" aria-controls="tslSection" aria-selected="false">Finishing - TSL</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="punchingSection-tab" data-bs-toggle="tab" data-bs-target="#punchingSection" role="tab" aria-controls="punchingSection" aria-selected="false">Finishing - Die Cutting</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="stampingSection-tab" data-bs-toggle="tab" data-bs-target="#stampingSection" role="tab" aria-controls="stampingSection" aria-selected="false">Finishing - Hot Stamping</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="chipEmbeddingSection-tab" data-bs-toggle="tab" data-bs-target="#chipEmbeddingSection" role="tab" aria-controls="chipEmbeddingSection" aria-selected="false">Finishing - Chip Embedding</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link nav-link-prod flex-sm-fill text-uppercase fs-5" id="qualityControlSection-tab" data-bs-toggle="tab" data-bs-target="#qualityControlSection" role="tab" aria-controls="qualityControlSection" aria-selected="false">Quality Control</button>
                    </li>
                </ul>
                <!-- ======================= Nav tabs Content ======================= -->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="offsetSection" role="tabpanel" aria-labelledby="offsetSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Offset Printing Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="offsetList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="varnishSection" role="tabpanel" aria-labelledby="varnishSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Varnish Printing Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="varnishList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="hpSection" role="tabpanel" aria-labelledby="hpSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Digital Printing Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="digitalList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="silkscreenSection" role="tabpanel" aria-labelledby="silkscreenSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Silkscreen Printing Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="silkscreenList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tslSection" role="tabpanel" aria-labelledby="tslSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing - TSL Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="tslList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="punchingSection" role="tabpanel" aria-labelledby="punchingSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing Die-Cutting Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="dieCuttingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="stampingSection" role="tabpanel" aria-labelledby="stampingSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing - Hot Stamping Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="hotStampingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="chipEmbeddingSection" role="tabpanel" aria-labelledby="chipEmbeddingSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing - Chip Embedding Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="chipEmbeddingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="qualityControlSection" role="tabpanel" aria-labelledby="qualityControlSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Quality Control Section</h3>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="qualityControlList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th>Filename</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div><!-- ==================== Card Body End ==================== -->
        </div><!-- ==================== Card End ==================== -->
    </div>
    <!-- =============== Job Process Hold Modal =============== -->
    <div class="modal fade" id="jobProcessHoldModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-8">
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
                <div class="d-grid gap-2 col-sm-11 mx-auto mb-2">
                    <button type="button" class="btn btn-success col-sm btnProcessHold" onclick="saveProcessHold();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Hold Modal End =============== -->
    <!-- =============== Job Process Partial Modal =============== -->
    <div class="modal fade" id="jobProcessPartialModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header card-8">
                    <h4 class="modal-title text-uppercase fw-bold text-light">JOB PROCESS PARTIAL</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="jobentryid_partial" disabled>
                    <input type="text" class="form-control" id="processid_partial" disabled>
                    <input type="text" class="form-control" id="process_section_partial" disabled>
                    <div class="row mt-2">
                        <div class="col">
                            <div id="operator_error_partial"></div>
                            <div class="table-responsive">
                                <table id="operator_table_partial" class="table table-bordered table-striped table-hover" width="100%">
                                    <thead class="customHeaderProd">
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
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control fw-bold" id="partial_quantity">
                        <div class="invalid-feedback"></div>
                        <label for="partial_quantity" class="col-form-label fw-bold">Partial Quantity</label>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea id="operator_remarks_partial" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                        <div class="invalid-feedback"></div>
                        <label for="operator_remarks_partial" class="col-form-label fw-bold">Operator Remarks</label>
                    </div>
                </div>
                <div class="d-grid gap-2 col-sm-11 mx-auto mb-3">
                    <button type="button" class="btn btn-success col-sm btnProcessPartial" onclick="saveProcessPartial();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                </div>
            </div>
        </div>
    </div><!-- =============== Job Process Partial Modal End =============== -->


    <!-- ==================== CONTENT SECTION END ==================== -->
    <div class="position-fixed z-3 app-card-wrapper"><!-- ==================== CARD SECTION ==================== -->
        <div class="card card-8 border-0 shadow app-card">
            <div class="d-flex justify-content-between justify-content-md-between mt-1 me-3 align-items-center">
                <button class="btn text-white fs-2" onclick="hideCard();"><i class="fa-solid fa-bars"></i></button>
                <a href="../Landing_Page.php" class="text-white fs-2">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
            <div class="position-absolute app-title-wrapper">
                <span class="fw-bold app-title text-nowrap">MANUFACTURING</span>
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
<?php include './../includes/footer.php';
include './../helper/input_validation.php'; ?>
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

    var access_level = '<?php echo $_SESSION['access_lvl']; ?>';
    var empno = '<?php echo $_SESSION['empno']; ?>';
    let inTable;
    let operatorCount = 0;
    let processAction;

    loadProcessTable('offsetList_table', 'Offset Printing', access_level);
    loadProcessTable('varnishList_table', 'Varnish Printing', access_level);
    loadProcessTable('digitalList_table', 'Digital Printing', access_level);
    loadProcessTable('silkscreenList_table', 'Silkscreen Printing', access_level);
    loadProcessTable('tslList_table', 'Tapelaying/Spotwelding', access_level);
    loadProcessTable('dieCuttingList_table', 'Finishing', access_level);
    loadProcessTable('hotStampingList_table', 'Lamination', access_level);
    loadProcessTable('chipEmbeddingList_table', 'Card Punching', access_level);
    loadProcessTable('qualityControlList_table', 'Quality Control', access_level);

    function loadProcessTable(inTable, process_section, access_level) {
        inTable = $('#' + inTable).DataTable({
            'lengthMenu': [
                [10, 50, 100, -1],
                [10, 50, 100, "All"]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'ajax': {
                url: '../controller/prod_monitoring_controller/prod_process_operations_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_job_process_data',
                    process_section: process_section,
                    access_level: access_level
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
                targets: [0, 12],
                className: 'dt-body-middle-center',
                width: '5%'
            }, {
                targets: [8, 9, 11],
                className: 'dt-body-middle-center',
                width: '7%'
            }, {
                targets: [1, 2, 3, 4, 10],
                className: 'dt-body-middle-left',
            }, {
                targets: 5,
                className: 'dt-body-middle-right',
                width: '6%'
            }, {
                targets: [6, 7],
                className: 'dt-body-middle-left',
                width: '10%'
            }, {
                targets: 13,
                className: 'dt-nowrap-center',
                width: '12%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btnStatus;
                    switch (data[3]) {
                        case 'JRM':
                        case 'JKT':
                            btnStatus = processBtnAction(data[4], data[5], data[6], data[7], data[8], data[0], data[1], data[2]); //* ======== Offset Printing ========
                            break;
                        default:
                            btnStatus = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-brands fa-google-play"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>
                            <button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Information" onclick="jobProcessInfo('${data[0]}','${data[1]}','${data[2]}');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
                            break;
                    }
                    return btnStatus
                }
            }]
        });
        inTable.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            // $('[data-bs-toggle="tooltip"]').tooltip('hide'); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            inTable.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function processBtnAction(row_processStatus, row_processSequence, processSequence, row_job_filename, jobFilename, row_jobentry_id, row_process_id, process_section) {
        if (row_processStatus == 'Pending' && row_processSequence == processSequence && row_job_filename == jobFilename) {
            processAction = `<button type="button" class="btn btn-success col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Start Job Process" onclick="jobProcessStart('` + row_jobentry_id + `','` + row_process_id + `');"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`;
        } else if (row_processStatus == 'Process Hold') {
            processAction = `<button type="button" class="btn btn-success col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Resume Job Process" onclick="jobProcessResume('` + row_jobentry_id + `','` + row_process_id + `');"><i class="fa-brands fa-google-play fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`;
        } else if (row_processStatus == 'On-Going') {
            processAction = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-brands fa-google-play"></i></button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hold Job Process" onclick="jobProcessHold('` + row_jobentry_id + `','` + row_process_id + `');"><i class="fa-solid fa-circle-pause fa-bounce" style="--fa-animation-duration: 2.5s;"></i></button>
                            <button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Partial Job Process" onclick="jobProcessPartial('` + row_jobentry_id + `','` + row_process_id + `','` + process_section + `');"><i class="fa-regular fa-star-half-stroke fa-flip" style="--fa-animation-duration: 2.5s;"></i></button>
                            <button type="button" class="btn btn-dark col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Done Job Process" onclick="jobProcessDone('` + row_jobentry_id + `','` + row_process_id + `','` + row_processSequence + `','` + process_section + `');"><i class="fa-solid fa-clipboard-check fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>`;
        } else {
            processAction = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-brands fa-google-play"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-circle-pause"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-regular fa-star-half-stroke"></i></button>
                            <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-clipboard-check"></i></button>`;
        }
        processAction += ` <button type="button" class="btn btn-info col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Information" onclick="jobProcessInfo('` + row_jobentry_id + `','` + row_process_id + `','` + process_section + `');"><i class="fa-solid fa-circle-info fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
        return processAction;
    }

    function refreshProcessTable() {
        $('#offsetList_table').DataTable().ajax.reload(null, false);
        $('#varnishList_table').DataTable().ajax.reload(null, false);
        $('#digitalList_table').DataTable().ajax.reload(null, false);
        $('#silkscreenList_table').DataTable().ajax.reload(null, false);
        $('#tslList_table').DataTable().ajax.reload(null, false);
        $('#dieCuttingList_table').DataTable().ajax.reload(null, false);
        $('#hotStampingList_table').DataTable().ajax.reload(null, false);
        $('#chipEmbeddingList_table').DataTable().ajax.reload(null, false);
        $('#qualityControlList_table').DataTable().ajax.reload(null, false);
    }

    function jobProcessStart(jobentry_id, process_id) {
        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_process_operations_contr.class.php',
            type: 'POST',
            data: {
                action: 'process_job_start',
                jobentry_id: jobentry_id,
                process_id: process_id
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Process Started.',
                    text: '',
                    showConfirmButton: false,
                    timer: 800
                });
                refreshProcessTable();
            }
        });
    }

    function jobProcessResume(jobentry_id, process_id) {
        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_process_operations_contr.class.php',
            type: 'POST',
            data: {
                action: 'process_job_resume',
                jobentry_id: jobentry_id,
                process_id: process_id
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Process Resumed.',
                    text: '',
                    showConfirmButton: false,
                    timer: 800
                });
                refreshProcessTable();
            }
        });
    }

    function jobProcessHold(jobentry_id, process_id) {
        $('#jobProcessHoldModal').modal('show');
        $('#jobentryid_hold').val(jobentry_id);
        $('#processid_hold').val(process_id);
    }

    function saveProcessHold() {
        if (inputValidation('operator_remarks_hold')) {
            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_process_operations_contr.class.php',
                type: 'POST',
                data: {
                    action: 'process_job_hold',
                    jobentry_id: $('#jobentryid_hold').val(),
                    process_id: $('#processid_hold').val(),
                    operator_remarks: $('#operator_remarks_hold').val()
                },
                success: function(result) {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Process Hold.',
                        text: '',
                        showConfirmButton: false,
                        timer: 800
                    });
                    refreshProcessTable();
                    $('#jobProcessHoldModal').modal('hide');
                    clearValues();
                }
            });
        }
    }

    function jobProcessPartial(jobentry_id, process_id, process_section) {
        $('#jobProcessPartialModal').modal('show');
        $('#jobentryid_partial').val(jobentry_id);
        $('#processid_partial').val(process_id);
        $('#process_section_partial').val(process_section);
    }

    function saveProcessPartial() {

    }

    function jobProcessDone(jobentry_id, process_id, process_section) {
        alert(jobentry_id + ' - ' + process_id + ' - ' + process_section);
    }

    function jobProcessInfo(jobentry_id, process_id, process_section) {
        alert(jobentry_id + ' - ' + process_id + ' - ' + process_section);
    }

    function addOperator() {
        operatorCount++;
        var process_section = document.getElementById('process_section_partial').value;
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
            switch (process_section) {
                case 'Offset Printing':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Offset Printing'); ?> < /select></td > ';
                    break;
                case 'Varnish Printing':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Varnish Printing'); ?> < /select></td > ';
                    break;
                case 'Digital Printing':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Digital Printing'); ?> < /select></td > ';
                    break;
                case 'Silkscreen Printing':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Silkscreen Printing'); ?> < /select></td > ';
                    break;
                case 'Tapelaying/Spotwelding':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Tapelaying/Spotwelding'); ?> < /select></td > ';
                    break;
                case 'Finishing':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Finishing'); ?> < /select></td > ';
                    break;
                case 'Lamination':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Lamination'); ?> < /select></td > ';
                    break;
                case 'Card Punching':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Card Punching'); ?> < /select></td > ';
                    break;
                case 'Quality Control':
                    html += '<td><select name="operator_name[]" class="form-select fw-bold operator_name"><option value="">Choose...</option><?php echo fill_operator_select_box($BannerWebLive, 'Quality Control'); ?> < /select></td > ';
                    break;
            }
            html += '<td style="text-align:center;"><button type="button" name="removeOperator" class="btn btn-danger btn-sm btnRemoveOperator"><i class="fa fa-minus"></i></button></td>';
            html += '</tr>';
            $('#operator_table_partial').append(html);
        }
    }
    //* =======  REMOVE OPERATOR =======
    $('#operator_table_partial').on('click', '.btnRemoveOperator', function() {
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

    function clearValues() {
        $('textarea').val('');
        $('input').val('');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('textarea').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>