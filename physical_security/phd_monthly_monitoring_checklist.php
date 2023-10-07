<?php
include './../includes/header.php';
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
    /* =========== Change Scrollbar Style - Justine 02162023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #FF7A00;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-physical">Monthly Monitoring Checklist</span>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 mt-4">
                <!-- Interlocking RUD Card -->
                <div class="col mb-3">
                    <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="loadInterlockingModal();">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Interlocking RUD</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="printing_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-door-open fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Electric Fence Card -->
                <div class="col mb-3">
                    <div class="card border-left-info shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="loadElectricFenceModal();">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Electric Fence</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="embossing_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-xmarks-lines fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Emergency Evaluation Switch Card -->
                <div class="col mb-3">
                    <div class="card border-left-warning shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="loadEmergencyEvalSwitchModal();">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Emergency Evaluation Switch</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="packaging_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-land-mine-on fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Room Temperature Card -->
                <div class="col mb-3">
                    <div class="card border-left-dark shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="loadRoomTempModal();">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Room Temperature</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="vault_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-temperature-low fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3 mb-4">
                <div class="col-sm mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light">Monthly Monitoring List</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="monthlyMonitoringList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Monitoring Header</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Interlocking RUD</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Electric Fence</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Emergency Evacuation Switch</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Room Temp</th>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Noted by</th>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Action</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Monitoring Header</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th style="text-align: center;vertical-align:middle;">Performed By</th>
                                            <th style="text-align: center;vertical-align:middle;">Checked By</th>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Noted by</th>
                                            <th rowspan="2" style="text-align: center;vertical-align:middle;">Action</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Interlocking RUD</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Electric Fence</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Emergency Evacuation Switch</th>
                                            <th colspan="2" style="text-align: center;vertical-align:middle;">Room Temp</th>

                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Template List End ==================== -->
                </div>
            </div>
            <!-- =============== Interlocking RUD Modal =============== -->
            <div class="modal fade" id="loadInterlockingModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Interlocking RUD</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="interlockingRudList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Area</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="interlockingRud_table_body"></tbody>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Area</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="interlock_performed_by" disabled></input>
                                <label for="interlock_performed_by" class="fw-bold">Prepared By</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="interlock_performed_job_pos"></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="interlock_checked_by" onchange="loadJobPosition(this.value,'interlock_checked_job_pos');">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="interlock_checked_by" class="fw-bold">Checked by</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="interlock_checked_job_pos"></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="interlock_noted_by" onchange="loadJobPosition(this.value,'interlock_noted_job_pos');">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="interlock_noted_by" class="fw-bold">Noted by</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="interlock_noted_job_pos"></label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveInterlock" onclick="saveInterlock();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Interlocking RUD Modal End ===============-->
            <!-- =============== Electric Fence Modal =============== -->
            <div class="modal fade" id="loadElectricFenceModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Electric Fence</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="electricFenceList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Area</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="electricFence_table_body"></tbody>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Area</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="electric_performed_by" disabled></input>
                                <label for="electric_performed_by" class="fw-bold">Prepared By</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="electric_performed_job_pos"></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="electric_checked_by" onchange="loadJobPosition(this.value,'electric_checked_job_pos');">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="electric_checked_by" class="fw-bold">Checked by</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="electric_checked_job_pos"></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="electric_noted_by" onchange="loadJobPosition(this.value,'electric_noted_job_pos');">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="electric_noted_by" class="fw-bold">Noted by</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="electric_noted_job_pos"></label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveElectric" onclick="saveElectric();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Electric Fence Modal End ===============-->
            <!-- =============== Emergency Evaluation Switch Modal =============== -->
            <div class="modal fade" id="loadEmergencyEvalSwitchModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Emergency Evaluation Switch</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="emergencyEvalSwitchList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Area</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="emergencyEvalSwitch_table_body"></tbody>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Area</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="emergency_performed_by" disabled></input>
                                <label for="emergency_performed_by" class="fw-bold">Prepared By</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="emergency_performed_by_pos"></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="emergency_checked_by" onchange="loadJobPosition(this.value,'emergency_checked_by_job_pos');">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="emergency_checked_by" class="fw-bold">Checked by</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="emergency_checked_by_job_pos"></label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="emergency_noted_by" onchange="loadJobPosition(this.value,'emergency_noted_job_pos');">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="emergency_noted_by" class="fw-bold">Noted by</label>
                            </div>
                            <div class="row mb-3">
                                <label class="fw-bold fs-13 ps-4" id="emergency_noted_job_pos"></label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveEmergency" onclick="saveEmergency();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Emergency Evaluation Switch Modal End ===============-->
            <!-- =============== Room Temperature Modal =============== -->
            <div class="modal fade" id="loadRoomTempModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Room Temperature</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-2">
                                <div class="table-responsive">
                                    <table id="emergencyEvalSwitchList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Room</th>
                                                <th style="text-align: center;">Reading1</th>
                                                <th style="text-align: center;">Reading2</th>
                                                <th style="text-align: center;">Temperature Alarm</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="roomTemp_table_body"></tbody>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align: center;">QR</th>
                                                <th style="text-align: center;">Room</th>
                                                <th style="text-align: center;">Reading1</th>
                                                <th style="text-align: center;">Reading2</th>
                                                <th style="text-align: center;">Temperature Alarm</th>
                                                <th style="text-align: center;">Remarks</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="roomTemp_performed_by" disabled></input>
                                        <label for="roomTemp_performed_by" class="fw-bold">Prepared By</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="roomTemp_performed_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="roomTemp_checked_by" onchange="loadJobPosition(this.value,'roomTemp_checked_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="roomTemp_checked_by" class="fw-bold">Checked by</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="roomTemp_checked_job_pos"></label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="roomTemp_noted_by" onchange="loadJobPosition(this.value,'roomTemp_noted_job_pos');">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="roomTemp_noted_by" class="fw-bold">Noted by</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="fw-bold fs-13 ps-4" id="roomTemp_noted_job_pos"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveRoomTemp" onclick="saveRoomTemp();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Room Temperature Modal End ===============-->
            <!-- =============== Paging System Modal =============== -->
            <div class="modal fade" id="qr_scannerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-black justify-content-center">
                            <h4 class="modal-title text-uppercase fw-bold text-light">SCAN QR-CODE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3" style="padding: 0px; width: 100%; max-height: 300px; overflow:hidden; border: 1px solid gray">
                                <video id="video" style="width: 100%;"></video>
                            </div>
                            <div id="sourceSelectPanel" style="display:none">
                                <div class="form-floating mb-2">
                                    <select class="form-select fw-bold" id="sourceSelect"></select>
                                    <label for="paging_noted_by" class="fw-bold">Change video source:</label>
                                </div>
                            </div>
                            <!-- <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="result">
                                <label for="paging_noted_by" class="fw-bold">Result:</label>
                            </div> -->
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-secondary col" data-bs-dismiss="modal" id="closeModal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
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
            <div class="card card-2 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">PHYSICAL SECURITY</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/select_values.php';
include './../helper/phd_scan_qr.php'; ?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var logged_user_access = '<?php echo $_SESSION['access_lvl']; ?>';
    loadMonthlyMonitoringTable();
    let arrayInterlockCategoryName = [];
    let arrayInterlockLocationName = [];
    let arrayInterlockStatus = [];
    let arrayInterlockRemarks = [];

    let arrayElectricLocationName = [];
    let arrayElectricStatus = [];
    let arrayElectricRemarks = [];

    let arrayEmergencySwitchName = [];
    let arrayEmergencyStatus = [];
    let arrayEmergencyRemarks = [];

    let arrayRoomTempLocationName = [];
    let arrayRoomTempReadingOne = [];
    let arrayRoomTempReadingTwo = [];
    let arrayRoomTempAlarm = [];
    let arrayRoomTempRemarks = [];

    function loadMonthlyMonitoringTable() {
        var monthlyMonitoringList_table = $('#monthlyMonitoringList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_monthly_monitoring_list_table'
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
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                className: 'dt-body-middle-left',
            }, {
                targets: 10,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="previewMonthlyReport('${data}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="previewMonthlyReport('${data}');"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="removeMonthlyReport('${data}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        monthlyMonitoringList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            // $('[data-bs-toggle="tooltip"]').tooltip('hide'); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            monthlyMonitoringList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadInterlockingModal() {
        $('#loadInterlockingModal').modal('show');
        $('#interlock_performed_by').val(logged_user);
        loadJobPosition(logged_user, 'interlock_performed_job_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'interlock_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'interlock_noted_by', 'physical_security');
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_interlocking_rud_table'
            },
            success: function(result) {
                let html = ``;
                var cat_count = 0;
                $.each(result, function(tablecategory, category_name) {
                    html += `<tr>`;
                    html += `<td style="text-align:center;" colspan="4"><input type="text" name="interlock_category_name[]" class="form-control fw-bold interlock_category_name" id="interlock_category_name" value="` + tablecategory + `" disabled></td>`;
                    $.each(category_name, function(category_index_value, locationname) {
                        cat_count++;
                        html += `<tr>
                        <td><input type="hidden" class="btnActivate` + cat_count + ` form-control" value="" id="" disabled><input type="hidden" class="categoryRud" value="` + tablecategory + `"><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate` + cat_count + `" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + cat_count + `','` + locationname.location_name + `')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                        <td style="vertical-align:middle;"><input type="text" name="interlock_location_name` + cat_count + `[]" class="form-control fw-bold interlock_location_name" id="interlock_location_name` + cat_count + `" value="` + locationname.location_name + `" disabled></td>
                        <td style="width:30%;"><input type="text" name="interlock_status` + cat_count + `[]" class="form-control text-center fw-bold interlock_status" id="interlock_status` + cat_count + `" disabled></td>
                        <td style="width:30%;"><input type="text" name="interlock_remarks` + cat_count + `[]" class="form-control text-center fw-bold interlock_remarks" id="interlock_remarks` + cat_count + `" disabled></td>
                        </tr>`;
                    });
                    html += `</tr>`;
                });
                $('#interlockingRud_table_body').append(html);
            }
        });
    }

    function loadElectricFenceModal() {
        $('#loadElectricFenceModal').modal('show');
        $('#electric_performed_by').val(logged_user);
        loadJobPosition(logged_user, 'electric_performed_job_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'electric_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'electric_noted_by', 'physical_security');
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_electric_fence_table'
            },
            success: function(result) {
                let html = ``;
                var count = 0;
                $.each(result, (key, row) => {
                    count++;
                    html += `<tr>
                    <td><input type="hidden" class="btnActivate` + count + ` form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate` + count + `" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + count + `','` + row.location_name + `')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                    <td style="vertical-align:middle;">` + row.location_name + `</td>
                    <td style="vertical-align:middle;display:none;"><input type="text" name="electric_location_name[]" class="form-control fw-bold electric_location_name" id="electric_location_name` + count + `" value="` + row.location_name + `" disabled></td>
                    <td style="width:30%;"><input type="text" name="electric_fence_status[]" class="form-control text-center fw-bold electric_fence_status" id="electric_fence_status` + count + `" disabled></td>
                    <td style="width:30%;"><input type="text" name="electric_fence_remarks[]" class="form-control text-center fw-bold electric_fence_remarks" id="electric_fence_remarks` + count + `" disabled></td>
                    </tr>`;
                });
                $('#electricFence_table_body').append(html);

                // $count = 0;
                // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                //     $count++;
                //     echo '<tr>';
                //     echo '<td><input type="hidden" class="btnActivate' . $count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + $count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + $count + ',\'' + $row['location_name'] + '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                //     echo '<td style="vertical-align:middle;">' . $row['location_name'] . '</td>';
                //     echo '<td style="vertical-align:middle;display:none;"><input type="text" name="electric_location_name[]" class="form-control fw-bold electric_location_name" id="electric_location_name' . $count . '" value="' . $row['location_name']  . '" disabled></td>';
                //     echo '<td style="width:30%;"><input type="text" name="electric_fence_status[]" class="form-control text-center fw-bold electric_fence_status" id="electric_fence_status' . $count . '" disabled></td>';
                //     echo '<td style="width:30%;"><input type="text" name="electric_fence_remarks[]" class="form-control text-center fw-bold electric_fence_remarks" id="electric_fence_remarks' . $count . '" disabled></td>';
                //     echo '</tr>';
                // }
            }
        });
    }

    function loadEmergencyEvalSwitchModal() {
        $('#loadEmergencyEvalSwitchModal').modal('show');
        $('#emergency_performed_by').val(logged_user);
        loadJobPosition(logged_user, 'emergency_performed_by_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'emergency_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'emergency_noted_by', 'physical_security');
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_emergency_eval_switch_table'
            },
            success: function(result) {
                let html = ``;
                var count = 0;
                $.each(result, (key, row) => {
                    count++;
                    html += `<tr>
                    <td><input type="hidden" class="btnActivate` + count + ` form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate` + count + `" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + count + `','` + row.location_name + `')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                    <td style="vertical-align:middle;">` + row.location_name + `</td>
                    <td style="vertical-align:middle; display:none;"><input type="text" name="emergency_switch[]" class="form-control fw-bold emergency_switch" id="emergency_switch` + count + `" value="` + row.location_name + `" disabled></td>
                    <td style="vertical-align:middle;width:32%;"><input type="text" name="emergency_eval_status[]" class="form-control text-center fw-bold emergency_eval_status" id="emergency_eval_status` + count + `" disabled></td>
                    <td style="vertical-align:middle;width:32%;"><input type="text" name="emergency_eval_remarks[]" class="form-control text-center fw-bold emergency_eval_remarks" id="emergency_eval_remarks` + count + `" disabled></td>
                    </tr>`;
                });

                $('#emergencyEvalSwitch_table_body').append(html);
                // $count = 0;
                // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                //     $count++;
                //     echo '<tr>';
                //     echo '<td><input type="hidden" class="btnActivate' . $count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' . $count . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' . $count . ',\'' . $row['location_name'] . '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                //     echo '<td style="vertical-align:middle;">' . $row['location_name'] . '</td>';
                //     echo '<td style="vertical-align:middle; display:none;"><input type="text" name="emergency_switch[]" class="form-control fw-bold emergency_switch" id="emergency_switch' . $count . '" value="' . $row['location_name']  . '" disabled></td>';
                //     echo '<td style="vertical-align:middle;width:32%;"><input type="text" name="emergency_eval_status[]" class="form-control text-center fw-bold emergency_eval_status" id="emergency_eval_status' . $count . '" disabled></td>';
                //     echo '<td style="vertical-align:middle;width:32%;"><input type="text" name="emergency_eval_remarks[]" class="form-control text-center fw-bold emergency_eval_remarks" id="emergency_eval_remarks' . $count . '" disabled></td>';
                //     echo '</tr>';
                // }
            }
        });
    }

    function loadRoomTempModal() {
        $('#loadRoomTempModal').modal('show');
        $('#roomTemp_performed_by').val(logged_user);
        loadJobPosition(logged_user, 'roomTemp_performed_job_pos');
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'roomTemp_checked_by', 'physical_security');
        loadSelectValue('phd_authorized_noted_by', 'noted_by_name', 'roomTemp_noted_by', 'physical_security');
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_room_temp_table'
            },
            success: function(result) {
                let html = ``;
                var count = 0;
                $.each(result, (key, row) => {
                    count++;
                    html += `<tr>
                        <td><input type="hidden" class="btnActivate` + count + ` form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate` + count + `" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode('` + count + `','` + row.location_name + `')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>
                        <td style="vertical-align:middle;">` + row.location_name + `</td>
                        <td style="vertical-align:middle;display:none;"><input type="text" name="roomtemp_location_name[]" class="form-control fw-bold roomtemp_location_name" id="roomtemp_location_name` + count + `" value="` + row.location_name + `" disabled></td>
                        <td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_reading_one[]" class="form-control text-center fw-bold roomtemp_reading_one" id="roomtemp_reading_one` + count + `" disabled></td>
                        <td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_reading_two[]" class="form-control text-center fw-bold roomtemp_reading_two" id="roomtemp_reading_two` + count + `" disabled></td>
                        <td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_temp_alarm[]" class="form-control text-center fw-bold roomtemp_temp_alarm" id="roomtemp_temp_alarm` + count + `" disabled></td>
                        <td style="vertical-align:middle;width:25%;"><input type="text" name="roomtemp_remarks[]" class="form-control text-center fw-bold roomtemp_remarks" id="roomtemp_remarks` + count + `" disabled></td>
                        </tr>`;
                });
                $('#roomTemp_table_body').append(html);

                // while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
                //     $count++;
                //     echo '<tr>';
                //     echo '<td><input type="hidden" class="btnActivate' . $count . ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' . $count . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' . $count . ',\'' . $row['location_name'] . '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                //     echo '<td style="vertical-align:middle;">' . $row['location_name'] . '</td>';
                //     echo '<td style="vertical-align:middle;display:none;"><input type="text" name="roomtemp_location_name[]" class="form-control fw-bold roomtemp_location_name" id="roomtemp_location_name' . $count . '" value="' . $row['location_name'] . '" disabled></td>';
                //     echo '<td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_reading_one[]" class="form-control text-center fw-bold roomtemp_reading_one" id="roomtemp_reading_one' . $count . '" disabled></td>';
                //     echo '<td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_reading_two[]" class="form-control text-center fw-bold roomtemp_reading_two" id="roomtemp_reading_two' . $count . '" disabled></td>';
                //     echo '<td style="vertical-align:middle;width:15%;"><input type="text" name="roomtemp_temp_alarm[]" class="form-control text-center fw-bold roomtemp_temp_alarm" id="roomtemp_temp_alarm' . $count . '" disabled></td>';
                //     echo '<td style="vertical-align:middle;width:25%;"><input type="text" name="roomtemp_remarks[]" class="form-control text-center fw-bold roomtemp_remarks" id="roomtemp_remarks' . $count . '" disabled></td>';
                //     echo '</tr>';
                // }
            }
        });
    }

    function saveInterlock() {
        if (submitValidation('interlocking')) {
            var performed_by = document.getElementById('interlock_performed_by').value;
            var checked_by = document.getElementById('interlock_checked_by').value;
            var noted_by = document.getElementById('interlock_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_monthly_monitoring_header',
                    performed_by: performed_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    category: 'interlock'
                },
                success: function(result) {

                    $('.categoryRud').each(function() {
                        var categ = $(this).val();
                        arrayInterlockCategoryName.push([categ]);
                    });
                    $('.interlock_location_name').each(function() {
                        var str_interlock_location_name = $(this).val();
                        arrayInterlockLocationName.push([str_interlock_location_name]);
                    });
                    $('.interlock_status').each(function() {
                        var str_interlock_status = $(this).val();
                        arrayInterlockStatus.push([str_interlock_status]);
                    });
                    $('.interlock_remarks').each(function() {
                        var str_interlock_remarks = $(this).val();
                        arrayInterlockRemarks.push([str_interlock_remarks]);
                    });
                    console.log(arrayInterlockCategoryName);
                    console.log(arrayInterlockLocationName);
                    console.log(arrayInterlockStatus);
                    console.log(arrayInterlockRemarks);
                    for (let j = 0; j < arrayInterlockLocationName.length; j++) {
                        var strInterlockCategnName = arrayInterlockCategoryName[j];
                        var interlock_category_name = strInterlockCategnName.toString();

                        var strInterlockLocationName = arrayInterlockLocationName[j];
                        var interlock_location_name = strInterlockLocationName.toString();

                        var strInterlockStatus = arrayInterlockStatus[j];
                        var interlock_status = strInterlockStatus.toString();

                        var strInterlockRemarks = arrayInterlockRemarks[j];
                        var interlock_remarks = strInterlockRemarks.toString();

                        $.ajax({
                            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_interlocking_rud',
                                interlock_category_name: interlock_category_name,
                                interlock_location_name: interlock_location_name,
                                interlock_status: interlock_status,
                                interlock_remarks: interlock_remarks,
                                monthlymonitoringid: result.monthlymonitoring_id,
                                monitoring_ref_no: result.monitoring_ref_no
                            }
                        });
                    }
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Save.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    $('#monthlyMonitoringList_table').DataTable().ajax.reload(null, false);
                    $('#loadInterlockingModal').modal('hide');
                }
            });
        }
    }

    function saveElectric() {
        if (submitValidation('electric')) {
            var performed_by = document.getElementById('electric_performed_by').value;
            var checked_by = document.getElementById('electric_checked_by').value;
            var noted_by = document.getElementById('electric_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_monthly_monitoring_header',
                    performed_by: performed_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    category: 'electric'
                },
                success: function(result) {
                    //* ======== Save Electric Fence Details ========
                    $('.electric_location_name').each(function() {
                        var str_location_name = $(this).val();
                        arrayElectricLocationName.push([str_location_name]);
                    });
                    $('.electric_fence_status').each(function() {
                        var str_electric_status = $(this).val();
                        arrayElectricStatus.push([str_electric_status]);
                    });
                    $('.electric_fence_remarks').each(function() {
                        var str_electric_remarks = $(this).val();
                        arrayElectricRemarks.push([str_electric_remarks]);
                    });

                    for (let i = 0; i < arrayElectricLocationName.length; i++) {
                        var strElectricLocationName = arrayElectricLocationName[i];
                        var electric_location_name = strElectricLocationName.toString();

                        var strElectricStatus = arrayElectricStatus[i];
                        var electric_status = strElectricStatus.toString();

                        var strElectricRemarks = arrayElectricRemarks[i];
                        var electric_remarks = strElectricRemarks.toString();

                        $.ajax({
                            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_electric_fence_details',
                                electric_location_name: electric_location_name,
                                electric_status: electric_status,
                                electric_remarks: electric_remarks,
                                monthlymonitoringid: result.monthlymonitoring_id,
                                monitoring_ref_no: result.monitoring_ref_no
                            }
                        });

                    }
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Save.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    $('#monthlyMonitoringList_table').DataTable().ajax.reload(null, false);
                    $('#loadElectricFenceModal').modal('hide');
                }
            });
        }
    }

    function saveEmergency() {
        if (submitValidation('emergency')) {
            var performed_by = document.getElementById('emergency_performed_by').value;
            var checked_by = document.getElementById('emergency_checked_by').value;
            var noted_by = document.getElementById('emergency_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_monthly_monitoring_header',
                    performed_by: performed_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    category: 'emergency'
                },
                success: function(result) {
                    //* ======== Save Emergency Details ========
                    $('.emergency_switch').each(function() {
                        var str_switch_name = $(this).val();
                        arrayEmergencySwitchName.push([str_switch_name]);
                    });
                    $('.emergency_eval_status').each(function() {
                        var str_emergency_status = $(this).val();
                        arrayEmergencyStatus.push([str_emergency_status]);
                    });
                    $('.emergency_eval_remarks').each(function() {
                        var str_emergency_remarks = $(this).val();
                        arrayEmergencyRemarks.push([str_emergency_remarks]);
                    });

                    for (let i = 0; i < arrayEmergencySwitchName.length; i++) {
                        var strEmergencySwitchName = arrayEmergencySwitchName[i];
                        var emergency_switch_name = strEmergencySwitchName.toString();

                        var strEmergencyStatus = arrayEmergencyStatus[i];
                        var emergency_status = strEmergencyStatus.toString();

                        var strEmergencyRemarks = arrayEmergencyRemarks[i];
                        var emergency_remarks = strEmergencyRemarks.toString();

                        $.ajax({
                            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_emergency_eval_details',
                                emergency_switch: emergency_switch_name,
                                emergency_status: emergency_status,
                                emergency_remarks: emergency_remarks,
                                monthlymonitoringid: result.monthlymonitoring_id,
                                monitoring_ref_no: result.monitoring_ref_no
                            }
                        });

                    }
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Save.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    $('#monthlyMonitoringList_table').DataTable().ajax.reload(null, false);
                    $('#loadEmergencyEvalSwitchModal').modal('hide');
                }
            });
        }
    }

    function saveRoomTemp() {
        if (submitValidation('roomTemp')) {
            var performed_by = document.getElementById('roomTemp_performed_by').value;
            var checked_by = document.getElementById('roomTemp_checked_by').value;
            var noted_by = document.getElementById('roomTemp_noted_by').value;

            $.ajax({
                url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_monthly_monitoring_header',
                    performed_by: performed_by,
                    checked_by: checked_by,
                    noted_by: noted_by,
                    category: 'roomTemp'
                },
                success: function(result) {
                    //* ======== Save Emergency Details ========
                    $('.roomtemp_location_name').each(function() {
                        var str_location_name = $(this).val();
                        arrayRoomTempLocationName.push([str_location_name]);
                    });
                    $('.roomtemp_reading_one').each(function() {
                        var str_reading_one = $(this).val();
                        arrayRoomTempReadingOne.push([str_reading_one]);
                    });
                    $('.roomtemp_reading_two').each(function() {
                        var str_reading_two = $(this).val();
                        arrayRoomTempReadingTwo.push([str_reading_two]);
                    });
                    $('.roomtemp_temp_alarm').each(function() {
                        var str_temp_alarm = $(this).val();
                        arrayRoomTempAlarm.push([str_temp_alarm]);
                    });
                    $('.roomtemp_remarks').each(function() {
                        var str_temp_remarks = $(this).val();
                        arrayRoomTempRemarks.push([str_temp_remarks]);
                    });

                    for (let i = 0; i < arrayRoomTempLocationName.length; i++) {
                        var str_location_name = arrayRoomTempLocationName[i];
                        var roomtemp_location_name = str_location_name.toString();

                        var str_reading_one = arrayRoomTempReadingOne[i];
                        var roomtemp_reading1 = str_reading_one.toString();

                        var str_reading_two = arrayRoomTempReadingTwo[i];
                        var roomtemp_reading2 = str_reading_two.toString();

                        var str_temp_alarm = arrayRoomTempAlarm[i];
                        var roomtemp_temperature_alarm = str_temp_alarm.toString();

                        var str_temp_remarks = arrayRoomTempRemarks[i];
                        var roomtemp_remarks = str_temp_remarks.toString();

                        $.ajax({
                            url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_roomtemp_details',
                                roomtemp_location_name: roomtemp_location_name,
                                roomtemp_reading1: roomtemp_reading1,
                                roomtemp_reading2: roomtemp_reading2,
                                roomtemp_temperature_alarm: roomtemp_temperature_alarm,
                                roomtemp_remarks: roomtemp_remarks,
                                monthlymonitoringid: result.monthlymonitoring_id,
                                monitoring_ref_no: result.monitoring_ref_no
                            }
                        });
                    }
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Save.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    $('#monthlyMonitoringList_table').DataTable().ajax.reload(null, false);
                    $('#loadRoomTempModal').modal('hide');
                }
            });
        }
    }

    function removeMonthlyReport(monthlymonitoringid) {
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
                    url: '../controller/phd_controller/phd_monthly_monitoring_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_monthly_monitoring',
                        monthlymonitoringid: monthlymonitoringid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Report deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function validationQrScanner(count) {
        $('#roomtemp_reading_one' + count).prop('disabled', false);
        $('#roomtemp_reading_two' + count).prop('disabled', false);
        $('#roomtemp_temp_alarm' + count).prop('disabled', false);
        $('#roomtemp_remarks' + count).prop('disabled', false);
        $('#emergency_eval_status' + count).prop('disabled', false);
        $('#emergency_eval_remarks' + count).prop('disabled', false);
        $('#electric_fence_status' + count).prop('disabled', false);
        $('#electric_fence_remarks' + count).prop('disabled', false);
        $('#interlock_status' + count).prop('disabled', false);
        $('#interlock_remarks' + count).prop('disabled', false);
    }

    function refreshProcessTable() {
        $('#monthlyMonitoringList_table').DataTable().ajax.reload(null, false);
    }

    function previewMonthlyReport(monthlymonitoringid) {
        strLink = "monthly_monitoring_checklist_pdf.php?d=" + monthlymonitoringid;
        window.open(strLink, '_blank');
    }

    function submitValidation(category) {
        var isValidated = true;
        if (category == 'interlocking') {
            var interlock_checked_by = document.getElementById('interlock_checked_by').value;
            var interlock_noted_by = document.getElementById('interlock_noted_by').value;

            if (interlock_checked_by.length == 0) {
                showFieldError('interlock_checked_by', 'Checked by must not be blank');
                if (isValidated) {
                    $('#interlock_checked_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('interlock_checked_by');
            }

            if (interlock_noted_by.length == 0) {
                showFieldError('interlock_noted_by', 'Noted by must not be blank');
                if (isValidated) {
                    $('#interlock_noted_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('interlock_noted_by');
            }
            return isValidated;
        } else if (category == 'electric') {
            var electric_checked_by = document.getElementById('electric_checked_by').value;
            var electric_noted_by = document.getElementById('electric_noted_by').value;

            if (electric_checked_by.length == 0) {
                showFieldError('electric_checked_by', 'Checked by must not be blank');
                if (isValidated) {
                    $('#electric_checked_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('electric_checked_by');
            }

            if (electric_noted_by.length == 0) {
                showFieldError('electric_noted_by', 'Noted by must not be blank');
                if (isValidated) {
                    $('#electric_noted_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('electric_noted_by');
            }
            return isValidated;
        } else if (category == 'emergency') {
            var emergency_checked_by = document.getElementById('emergency_checked_by').value;
            var emergency_noted_by = document.getElementById('emergency_noted_by').value;

            if (emergency_checked_by.length == 0) {
                showFieldError('emergency_checked_by', 'Checked by must not be blank');
                if (isValidated) {
                    $('#emergency_checked_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('emergency_checked_by');
            }

            if (emergency_noted_by.length == 0) {
                showFieldError('emergency_noted_by', 'Noted by must not be blank');
                if (isValidated) {
                    $('#emergency_noted_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('emergency_noted_by');
            }
            return isValidated;
        } else {
            var roomTemp_checked_by = document.getElementById('roomTemp_checked_by').value;
            var roomTemp_noted_by = document.getElementById('roomTemp_noted_by').value;

            if (roomTemp_checked_by.length == 0) {
                showFieldError('roomTemp_checked_by', 'Checked by must not be blank');
                if (isValidated) {
                    $('#roomTemp_checked_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('roomTemp_checked_by');
            }

            if (roomTemp_noted_by.length == 0) {
                showFieldError('roomTemp_noted_by', 'Noted by must not be blank');
                if (isValidated) {
                    $('#roomTemp_noted_by').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('roomTemp_noted_by');
            }
            return isValidated;
        }
    }

    function showFieldError(element, message) {
        $('#' + element).addClass('is-invalid').removeClass('is-valid');
        $('#' + element).next().html(message);
        $('#' + element).next().show();
    }

    function clearFieldError(element) {
        $('#' + element).removeClass('is-invalid').addClass('is-valid');
        $('#' + element).attr('required');
        $('#' + element).next().html('');
    }

    function clearValues() {
        $('#interlockingRud_table_body').html('');
        $('#electricFence_table_body').html('');
        $('#emergencyEvalSwitch_table_body').html('');
        $('#roomTemp_table_body').html('');
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        $('#roomTemp_performed_job_pos').html('');
        $('#roomTemp_checked_job_pos').html('');
        $('#roomTemp_noted_job_pos').html('');
        $('#interlock_performed_job_pos').html('');
        $('#interlock_checked_job_pos').html('');
        $('#interlock_noted_job_pos').html('');
        $('#electric_performed_job_pos').html('');
        $('#electric_checked_job_pos').html('');
        $('#electric_noted_job_pos').html('');
        $('#emergency_performed_by_pos').html('');
        $('#emergency_checked_by_job_pos').html('');
        $('#emergency_noted_job_pos').html('');
        arrayInterlockCategoryName = [];
        arrayInterlockLocationName = [];
        arrayInterlockStatus = [];
        arrayInterlockRemarks = [];

        arrayElectricLocationName = [];
        arrayElectricStatus = [];
        arrayElectricRemarks = [];

        arrayEmergencySwitchName = [];
        arrayEmergencyStatus = [];
        arrayEmergencyRemarks = [];

        arrayRoomTempLocationName = [];
        arrayRoomTempReadingOne = [];
        arrayRoomTempReadingTwo = [];
        arrayRoomTempAlarm = [];
        arrayRoomTempRemarks = [];
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>