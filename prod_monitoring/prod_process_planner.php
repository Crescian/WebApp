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
        <span class="page-title-production">Job Process Planner</span>
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
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="offsetList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="offset_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="offset_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="offset_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="offset_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="offset_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="offset_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="offset_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="offset_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="offset_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveOffset me-1 mb-2" onclick="savePlanner('Offset');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelOffset me-2 mb-2" onclick="cancelPlanner('Offset');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="varnishSection" role="tabpanel" aria-labelledby="varnishSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Varnish Printing Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="varnishList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="varnish_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="varnish_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="varnish_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="varnish_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="varnish_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="varnish_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="varnish_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="varnish_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="varnish_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveVarnish me-1 mb-2" onclick="savePlanner('Varnish');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelVarnish me-2 mb-2" onclick="cancelPlanner('Varnish');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="hpSection" role="tabpanel" aria-labelledby="hpSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Digital Printing Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="digitalList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="digital_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="digital_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="digital_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="digital_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="digital_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="digital_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="digital_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="digital_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="digital_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveDigital me-1 mb-2" onclick="savePlanner('Digital');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelDigital me-2 mb-2" onclick="cancelPlanner('Digital');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="silkscreenSection" role="tabpanel" aria-labelledby="silkscreenSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Silkscreen Printing Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="silkscreenList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="silkscreen_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="silkscreen_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="silkscreen_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="silkscreen_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="silkscreen_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="silkscreen_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="silkscreen_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="silkscreen_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="silkscreen_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveSilkscreen me-1 mb-2" onclick="savePlanner('Silkscreen');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelSilkscreen me-2 mb-2" onclick="cancelPlanner('Silkscreen');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tslSection" role="tabpanel" aria-labelledby="tslSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing - TSL Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="tslList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="tsl_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="tsl_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="tsl_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="tsl_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="tsl_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="tsl_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="tsl_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="tsl_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="tsl_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveTsl me-1 mb-2" onclick="savePlanner('TSL');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelTsl me-2 mb-2" onclick="cancelPlanner('TSL');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="punchingSection" role="tabpanel" aria-labelledby="punchingSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing Die-Cutting Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="dieCuttingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="dieCutting_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="dieCutting_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="dieCutting_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="dieCutting_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="dieCutting_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="dieCutting_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="dieCutting_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="dieCutting_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="dieCutting_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveDieCutting me-1 mb-2" onclick="savePlanner('DieCutting');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelDieCutting me-2 mb-2" onclick="cancelPlanner('DieCutting');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="stampingSection" role="tabpanel" aria-labelledby="stampingSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing - Hot Stamping Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="hotStampingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="hotStamping_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="hotStamping_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="hotStamping_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="hotStamping_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="hotStamping_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="hotStamping_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="hotStamping_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="hotStamping_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="hotStamping_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveHotStamping me-1 mb-2" onclick="savePlanner('HotStamping');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelHotStamping me-2 mb-2" onclick="cancelPlanner('HotStamping');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="chipEmbeddingSection" role="tabpanel" aria-labelledby="chipEmbeddingSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Finishing - Chip Embedding Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="chipEmbeddingList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="chipEmbedding_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="chipEmbedding_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="chipEmbedding_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="chipEmbedding_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="chipEmbedding_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="chipEmbedding_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="chipEmbedding_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="chipEmbedding_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="chipEmbedding_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveChipEmbedding me-1 mb-2" onclick="savePlanner('ChipEmbedding');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelChipEmbedding me-2 mb-2" onclick="cancelPlanner('ChipEmbedding');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="qualityControlSection" role="tabpanel" aria-labelledby="qualityControlSection-tab">
                        <div class="mt-4 d-flex flex-row align-items-center justify-content-between">
                            <h3 class="job-process-section-title">Quality Control Section</h3>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown p-r-8">
                                    <button class="btn btn-primary dropdown-toggle fw-bold fs-18" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Export</button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item card-body-hover-pointer" href="exportExcelFile-Prodmonitoring.php?d=Offset Section"><i class="fa-solid fa-file-excel p-r-8"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mb-4">
                            <div class="col">
                                <div class="table-responsive">
                                    <table id="qualityControlList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
                                                <th class="text-center"><input name="select_all" value="1" type="checkbox"></th>
                                                <th style="text-align:center;">Jobentry_id</th>
                                                <th style="text-align:center;">Process_id</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderProd">
                                            <tr>
                                                <th style="text-align:center;">Priority</th>
                                                <th style="text-align:center;">Date Receive</th>
                                                <th>Customer</th>
                                                <th>J.O Number</th>
                                                <th>Description</th>
                                                <th style="text-align:center;">Quantity</th>
                                                <th>Process</th>
                                                <th style="text-align:center;">Machine</th>
                                                <th style="text-align:center;">Start Date</th>
                                                <th style="text-align:center;">End Date</th>
                                                <th>Instruction</th>
                                                <th style="text-align:center;">Transfer Date</th>
                                                <th style="text-align:center;">Status</th>
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
                                        <select id="qualityControl_process_priority" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
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
                                        <label class="fw-bold" for="qualityControl_process_priority">Priority</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <select id="qualityControl_process_machine" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold" for="qualityControl_process_machine">Machine</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="qualityControl_process_start_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="qualityControl_process_start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="qualityControl_process_end_date" class="form-control fw-bold" disabled>
                                    <div class="invalid-feedback"></div>
                                    <label class="fw-bold" for="qualityControl_process_end_date">End Date</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold" id="qualityControl_process_instruction" disabled>
                                    <label class="fw-bold">Instruction</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="row mt-2">
                                    <button type="button" class="btn btn-secondary col-sm btnSaveQualityControl me-1 mb-2" onclick="savePlanner('QualityControl');" disabled><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                                    <button type="button" class="btn btn-secondary col-sm btnCancelQualityControl me-2 mb-2" onclick="cancelPlanner('QualityControl');" disabled><i class="fa-regular fa-circle-xmark p-r-8"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<?php include './../includes/footer.php'; ?>
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

    let offsetList_table;
    let varnishList_table;
    let digitalList_table;
    let silkscreenList_table;
    let tslList_table;
    let dieCuttingList_table;
    let hotStampingList_table;
    let chipEmbeddingList_table;
    let qualityControlList_table;

    let values = [];

    loadProcessTable('Offset Printing');
    loadProcessTable('Varnish Printing');
    loadProcessTable('Digital Printing');
    loadProcessTable('Silkscreen Printing');
    loadProcessTable('Tapelaying/Spotwelding');
    loadProcessTable('Finishing');
    loadProcessTable('Lamination');
    loadProcessTable('Card Punching');
    loadProcessTable('Quality Control');

    function loadProcessTable(process_section) {
        switch (process_section) {
            case 'Offset Printing':
                var rows_selected = [];
                loadSelectValue('Offset Printing', 'offset_process_machine');

                offsetList_table = $('#offsetList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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

                //* ======== Offset Table ========
                $('#offsetList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = offsetList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(offsetList_table, 'Offset'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#offsetList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', offsetList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#offsetList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#offsetList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                offsetList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(offsetList_table, 'Offset'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    offsetList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Varnish Printing':
                var rows_selected = [];
                loadSelectValue('Varnish Printing', 'varnish_process_machine');
                varnishList_table = $('#varnishList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Varnish Table ========
                $('#varnishList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = varnishList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(varnishList_table, 'Varnish'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#varnishList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', varnishList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#varnishList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#varnishList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                varnishList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(varnishList_table, 'Varnish'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    varnishList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Digital Printing':
                var rows_selected = [];
                loadSelectValue('Digital Printing', 'digital_process_machine');
                digitalList_table = $('#digitalList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'processing': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Digital Table ========
                $('#digitalList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = digitalList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(digitalList_table, 'Digital'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#digitalList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', digitalList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#digitalList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#digitalList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                digitalList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(digitalList_table, 'Digital'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    digitalList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Silkscreen Printing':
                var rows_selected = [];
                loadSelectValue('Silkscreen Printing', 'silkscreen_process_machine');
                silkscreenList_table = $('#silkscreenList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Silkscreen Table ========
                $('#silkscreenList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = silkscreenList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(silkscreenList_table, 'Silkscreen'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#silkscreenList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', silkscreenList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#silkscreenList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#silkscreenList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                silkscreenList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(silkscreenList_table, 'Silkscreen'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    silkscreenList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Tapelaying/Spotwelding':
                var rows_selected = [];
                loadSelectValue('Tapelaying/Spotwelding', 'tsl_process_machine');
                tslList_table = $('#tslList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== T/S/L Table ========
                $('#tslList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = tslList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(tslList_table, 'TSL'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#tslList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', tslList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#tslList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#tslList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                tslList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(tslList_table, 'TSL'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    tslList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Finishing':
                var rows_selected = [];
                loadSelectValue('Finishing', 'dieCutting_process_machine');
                dieCuttingList_table = $('#dieCuttingList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Die Cutting Table ========
                $('#dieCuttingList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = dieCuttingList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(dieCuttingList_table, 'DieCutting'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#dieCuttingList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', dieCuttingList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#dieCuttingList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#dieCuttingList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                dieCuttingList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(dieCuttingList_table, 'DieCutting'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    dieCuttingList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Lamination':
                var rows_selected = [];
                loadSelectValue('Lamination', 'hotStamping_process_machine');
                hotStampingList_table = $('#hotStampingList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Hot Stamping Table ========
                $('#hotStampingList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = hotStampingList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(hotStampingList_table, 'HotStamping'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#hotStampingList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', hotStampingList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#hotStampingList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#hotStampingList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                hotStampingList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(hotStampingList_table, 'HotStamping'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    hotStampingList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Card Punching':
                var rows_selected = [];
                loadSelectValue('Card Punching', 'chipEmbedding_process_machine');
                chipEmbeddingList_table = $('#chipEmbeddingList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Chip Embedding Table ========
                $('#chipEmbeddingList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = chipEmbeddingList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(chipEmbeddingList_table, 'ChipEmbedding'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#chipEmbeddingList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', chipEmbeddingList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#chipEmbeddingList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#chipEmbeddingList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                chipEmbeddingList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(chipEmbeddingList_table, 'ChipEmbedding'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    chipEmbeddingList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;

            case 'Quality Control':
                var rows_selected = [];
                loadSelectValue('Quality Control', 'qualityControl_process_machine');
                qualityControlList_table = $('#qualityControlList_table').DataTable({
                    'lengthMenu': [
                        [5, 10, 50, 100, -1],
                        [5, 10, 50, 100, "All"]
                    ],
                    'autoWidth': false,
                    'responsive': true,
                    'searching': false,
                    'ajax': {
                        url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            action: 'load_job_process_data',
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
                        targets: [0, 12],
                        className: 'dt-body-middle-center',
                        width: '5%'
                    }, {
                        targets: [1, 8, 9, 11],
                        className: 'dt-body-middle-center',
                        width: '7%'
                    }, {
                        targets: [2, 3, 4, 10],
                        className: 'dt-body-middle-left',
                    }, {
                        targets: 5,
                        className: 'dt-body-middle-right',
                        width: '6%'
                    }, {
                        targets: [6, 7],
                        className: 'dt-body-middle-left',
                        width: '10%',
                        orderable: false
                    }, {
                        targets: 13,
                        className: 'dt-nowrap-center',
                        width: '5%',
                        orderable: false
                    }, {
                        targets: [14, 15],
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
                //* ======== Quality Control Table ========
                $('#qualityControlList_table tbody').on('click', '.rowChkBox', function(e) {
                    var $row = $(this).closest('tr');
                    var data = qualityControlList_table.row($row).data(); //* Get row data 

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
                    updateDataTableSelectAllCtrl(qualityControlList_table, 'QualityControl'); //* Update state of "Select all" control
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                $('#qualityControlList_table').on('click', 'tbody td, thead th:first-child', function(e) { //* Handle click on table cells with checkboxes
                    $(this).parent().find('input[type="checkbox"]').trigger('click');
                });

                $('thead input[name="select_all"]', qualityControlList_table.table().container()).on('click', function(e) { //* Handle click on "Select all" control
                    if (this.checked) {
                        $('#qualityControlList_table tbody .rowChkBox:not(:checked)').trigger('click');
                    } else {
                        $('#qualityControlList_table tbody .rowChkBox:checked').trigger('click');
                    }
                    e.stopPropagation(); //* Prevent click event from propagating to parent
                });

                qualityControlList_table.on('draw', function() { //* Handle table draw event
                    updateDataTableSelectAllCtrl(qualityControlList_table, 'QualityControl'); //* Update state of "Select all" control
                });

                setInterval(function() {
                    qualityControlList_table.ajax.reload(null, false);
                }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
                break;
        }
    }

    function updateDataTableSelectAllCtrl(table, category) {
        var $table = table.table().node();
        var $chkbox_all = $('tbody .rowChkBox', $table);
        var $chkbox_checked = $('tbody .rowChkBox:checked', $table);
        var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

        if ($chkbox_checked.length === 0) { //* If none of the checkboxes are checked
            chkbox_select_all.checked = false;

            addInputDisabled(category);
            clearAttributes();
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
            case 'Offset':
                $.each($('#offsetList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#offset_process_machine').val(), $('#offset_process_priority').val(), $('#offset_process_instruction').val(), $('#offset_process_start_date').val(), $('#offset_process_end_date').val(), category);
                break;

            case 'Varnish':
                $.each($('#varnishList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#varnish_process_machine').val(), $('#varnish_process_priority').val(), $('#varnish_process_instruction').val(), $('#varnish_process_start_date').val(), $('#varnish_process_end_date').val(), category);
                break;

            case 'Digital':
                $.each($('#digitalList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#digital_process_machine').val(), $('#digital_process_priority').val(), $('#digital_process_instruction').val(), $('#digital_process_start_date').val(), $('#digital_process_end_date').val(), category);
                break;

            case 'Silkscreen':
                $.each($('#silkscreenList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#silkscreen_process_machine').val(), $('#silkscreen_process_priority').val(), $('#silkscreen_process_instruction').val(), $('#silkscreen_process_start_date').val(), $('#silkscreen_process_end_date').val(), category);
                break;

            case 'TSL':
                $.each($('#tslList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#tsl_process_machine').val(), $('#tsl_process_priority').val(), $('#tsl_process_instruction').val(), $('#tsl_process_start_date').val(), $('#tsl_process_end_date').val(), category);
                break;

            case 'DieCutting':
                $.each($('#dieCuttingList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#dieCutting_process_machine').val(), $('#dieCutting_process_priority').val(), $('#dieCutting_process_instruction').val(), $('#dieCutting_process_start_date').val(), $('#dieCutting_process_end_date').val(), category);
                break;

            case 'HotStamping':
                $.each($('#hotStampingList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#hotStamping_process_machine').val(), $('#hotStamping_process_priority').val(), $('#hotStamping_process_instruction').val(), $('#hotStamping_process_start_date').val(), $('#hotStamping_process_end_date').val(), category);
                break;

            case 'ChipEmbedding':
                $.each($('#chipEmbeddingList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#chipEmbedding_process_machine').val(), $('#chipEmbedding_process_priority').val(), $('#chipEmbedding_process_instruction').val(), $('#chipEmbedding_process_start_date').val(), $('#chipEmbedding_process_end_date').val(), category);
                break

            case 'QualityControl':
                $.each($('#qualityControlList_table .rowChkBox:checked'), function() {
                    var data = $(this).parents('tr:eq(0)');
                    values.push([
                        [$(data).find('td:eq(14)').text(), $(data).find('td:eq(15)').text()]
                    ]);
                });
                saveValuesPlanner(values, $('#chipEmbedding_process_machine').val(), $('#chipEmbedding_process_priority').val(), $('#chipEmbedding_process_instruction').val(), $('#chipEmbedding_process_start_date').val(), $('#chipEmbedding_process_end_date').val(), category);
                break;
        }
    }

    function saveValuesPlanner(values, process_machine, process_priority, instructions, start_date, end_date, category) {
        for (let i = 0; i < values.length; i++) {
            var tblData = values[i];
            var strData = tblData.toString().split(',');
            jobentry_id = strData[0];
            process_id = strData[1];

            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_process_planner',
                    process_machine: process_machine,
                    process_priority: process_priority,
                    instructions: instructions,
                    start_date: start_date,
                    end_date: end_date,
                    jobentry_id: jobentry_id,
                    process_id: process_id
                }
            });

        }
        values = [];
        setTimeout(function() {
            switch (category) {
                case 'Offset':
                    offsetList_table.ajax.reload(null, false);
                    break;
                case 'Varnish':
                    varnishList_table.ajax.reload(null, false);
                    break;
                case 'Digital':
                    digitalList_table.ajax.reload(null, false);
                    break;
                case 'Silkscreen':
                    silkscreenList_table.ajax.reload(null, false);
                    break;
                case 'TSL':
                    tslList_table.ajax.reload(null, false);
                    break;
                case 'DieCutting':
                    dieCuttingList_table.ajax.reload(null, false);
                    break;
                case 'HotStamping':
                    hotStampingList_table.ajax.reload(null, false);
                    break;
                case 'ChipEmbedding':
                    chipEmbeddingList_table.ajax.reload(null, false);
                    break;
                case 'QualityControl':
                    qualityControlList_table.ajax.reload(null, false);
                    break;
            }
            cancelPlanner(category);
        }, 200);
    }

    function cancelPlanner(category) {
        var $table;
        switch (category) {
            case 'Offset':
                $table = $('#offsetList_table').DataTable().table().node();
                $('#offsetList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#offsetList_table').find('tr').removeClass('selected');
                break;
            case 'Varnish':
                $table = $('#varnishList_table').DataTable().table().node();
                $('#varnishList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#varnishList_table').find('tr').removeClass('selected');
                break;
            case 'Digital':
                $table = $('#digitalList_table').DataTable().table().node();
                $('#digitalList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#digitalList_table').find('tr').removeClass('selected');
                break;
            case 'Silkscreen':
                $table = $('#silkscreenList_table').DataTable().table().node();
                $('#silkscreenList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#silkscreenList_table').find('tr').removeClass('selected');
                break;
            case 'TSL':
                $table = $('#tslList_table').DataTable().table().node();
                $('#tslList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#tslList_table').find('tr').removeClass('selected');
                break;
            case 'DieCutting':
                $table = $('#dieCuttingList_table').DataTable().table().node();
                $('#dieCuttingList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#dieCuttingList_table').find('tr').removeClass('selected');
                break;
            case 'HotStamping':
                $table = $('#hotStampingList_table').DataTable().table().node();
                $('#hotStampingList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#hotStampingList_table').find('tr').removeClass('selected');
                break;
            case 'ChipEmbedding':
                $table = $('#chipEmbeddingList_table').DataTable().table().node();
                $('#chipEmbeddingList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#chipEmbeddingList_table').find('tr').removeClass('selected');
                break;
            case 'QualityControl':
                $table = $('#qualityControlList_table').DataTable().table().node();
                $('#qualityControlList_table').find('input[type="checkbox"]').prop('checked', false);
                $('#qualityControlList_table').find('tr').removeClass('selected');
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

    function loadSelectValue(process_section, inObject) {
        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_process_planner_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_process_machine',
                process_section: process_section
            },
            success: function(result) {
                $.each(result, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${value}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${value}">${value}</option>`);
                    }
                });
            }
        });
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        values = [];
    }

    function removeInputDisabled(category) {
        switch (category) {
            case 'Offset':
                $('#offset_process_priority').prop('disabled', false);
                $('#offset_process_machine').prop('disabled', false);
                $('#offset_process_start_date').prop('disabled', false);
                $('#offset_process_end_date').prop('disabled', false);
                $('#offset_process_instruction').prop('disabled', false);
                $('.btnSaveOffset').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelOffset').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Varnish':
                $('#varnish_process_priority').prop('disabled', false);
                $('#varnish_process_machine').prop('disabled', false);
                $('#varnish_process_start_date').prop('disabled', false);
                $('#varnish_process_end_date').prop('disabled', false);
                $('#varnish_process_instruction').prop('disabled', false);
                $('.btnSaveVarnish').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelVarnish').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Digital':
                $('#digital_process_priority').prop('disabled', false);
                $('#digital_process_machine').prop('disabled', false);
                $('#digital_process_start_date').prop('disabled', false);
                $('#digital_process_end_date').prop('disabled', false);
                $('#digital_process_instruction').prop('disabled', false);
                $('.btnSaveDigital').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelDigital').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'Silkscreen':
                $('#silkscreen_process_priority').prop('disabled', false);
                $('#silkscreen_process_machine').prop('disabled', false);
                $('#silkscreen_process_start_date').prop('disabled', false);
                $('#silkscreen_process_end_date').prop('disabled', false);
                $('#silkscreen_process_instruction').prop('disabled', false);
                $('.btnSaveSilkscreen').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelSilkscreen').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'TSL':
                $('#tsl_process_priority').prop('disabled', false);
                $('#tsl_process_machine').prop('disabled', false);
                $('#tsl_process_start_date').prop('disabled', false);
                $('#tsl_process_end_date').prop('disabled', false);
                $('#tsl_process_instruction').prop('disabled', false);
                $('.btnSaveTsl').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelTsl').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'DieCutting':
                $('#dieCutting_process_priority').prop('disabled', false);
                $('#dieCutting_process_machine').prop('disabled', false);
                $('#dieCutting_process_start_date').prop('disabled', false);
                $('#dieCutting_process_end_date').prop('disabled', false);
                $('#dieCutting_process_instruction').prop('disabled', false);
                $('.btnSaveDieCutting').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelDieCutting').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'HotStamping':
                $('#hotStamping_process_priority').prop('disabled', false);
                $('#hotStamping_process_machine').prop('disabled', false);
                $('#hotStamping_process_start_date').prop('disabled', false);
                $('#hotStamping_process_end_date').prop('disabled', false);
                $('#hotStamping_process_instruction').prop('disabled', false);
                $('.btnSaveHotStamping').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelHotStamping').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'ChipEmbedding':
                $('#chipEmbedding_process_priority').prop('disabled', false);
                $('#chipEmbedding_process_machine').prop('disabled', false);
                $('#chipEmbedding_process_start_date').prop('disabled', false);
                $('#chipEmbedding_process_end_date').prop('disabled', false);
                $('#chipEmbedding_process_instruction').prop('disabled', false);
                $('.btnSaveChipEmbedding').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelChipEmbedding').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;

            case 'QualityControl':
                $('#qualityControl_process_priority').prop('disabled', false);
                $('#qualityControl_process_machine').prop('disabled', false);
                $('#qualityControl_process_start_date').prop('disabled', false);
                $('#qualityControl_process_end_date').prop('disabled', false);
                $('#qualityControl_process_instruction').prop('disabled', false);
                $('.btnSaveQualityControl').prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
                $('.btnCancelQualityControl').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
                break;
        }
    }

    function addInputDisabled(category) {
        switch (category) {
            case 'Offset':
                $('#offset_process_priority').prop('disabled', true);
                $('#offset_process_machine').prop('disabled', true);
                $('#offset_process_start_date').prop('disabled', true);
                $('#offset_process_end_date').prop('disabled', true);
                $('#offset_process_instruction').prop('disabled', true);
                $('.btnSaveOffset').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelOffset').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'Varnish':
                $('#varnish_process_priority').prop('disabled', true);
                $('#varnish_process_machine').prop('disabled', true);
                $('#varnish_process_start_date').prop('disabled', true);
                $('#varnish_process_end_date').prop('disabled', true);
                $('#varnish_process_instruction').prop('disabled', true);
                $('.btnSaveVarnish').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelVarnish').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'Digital':
                $('#digital_process_priority').prop('disabled', true);
                $('#digital_process_machine').prop('disabled', true);
                $('#digital_process_start_date').prop('disabled', true);
                $('#digital_process_end_date').prop('disabled', true);
                $('#digital_process_instruction').prop('disabled', true);
                $('.btnSaveDigital').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelDigital').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'Silkscreen':
                $('#silkscreen_process_priority').prop('disabled', true);
                $('#silkscreen_process_machine').prop('disabled', true);
                $('#silkscreen_process_start_date').prop('disabled', true);
                $('#silkscreen_process_end_date').prop('disabled', true);
                $('#silkscreen_process_instruction').prop('disabled', true);
                $('.btnSaveSilkscreen').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelSilkscreen').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'TSL':
                $('#tsl_process_priority').prop('disabled', true);
                $('#tsl_process_machine').prop('disabled', true);
                $('#tsl_process_start_date').prop('disabled', true);
                $('#tsl_process_end_date').prop('disabled', true);
                $('#tsl_process_instruction').prop('disabled', true);
                $('.btnSaveTsl').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelTsl').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'DieCutting':
                $('#dieCutting_process_priority').prop('disabled', true);
                $('#dieCutting_process_machine').prop('disabled', true);
                $('#dieCutting_process_start_date').prop('disabled', true);
                $('#dieCutting_process_end_date').prop('disabled', true);
                $('#dieCutting_process_instruction').prop('disabled', true);
                $('.btnSaveDieCutting').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelDieCutting').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'HotStamping':
                $('#hotStamping_process_priority').prop('disabled', true);
                $('#hotStamping_process_machine').prop('disabled', true);
                $('#hotStamping_process_start_date').prop('disabled', true);
                $('#hotStamping_process_end_date').prop('disabled', true);
                $('#hotStamping_process_instruction').prop('disabled', true);
                $('.btnSaveHotStamping').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelHotStamping').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'ChipEmbedding':
                $('#chipEmbedding_process_priority').prop('disabled', true);
                $('#chipEmbedding_process_machine').prop('disabled', true);
                $('#chipEmbedding_process_start_date').prop('disabled', true);
                $('#chipEmbedding_process_end_date').prop('disabled', true);
                $('#chipEmbedding_process_instruction').prop('disabled', true);
                $('.btnSaveChipEmbedding').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelChipEmbedding').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;

            case 'QualityControl':
                $('#qualityControl_process_priority').prop('disabled', true);
                $('#qualityControl_process_machine').prop('disabled', true);
                $('#qualityControl_process_start_date').prop('disabled', true);
                $('#qualityControl_process_end_date').prop('disabled', true);
                $('#qualityControl_process_instruction').prop('disabled', true);
                $('.btnSaveQualityControl').prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
                $('.btnCancelQualityControl').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
                break;
        }
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>