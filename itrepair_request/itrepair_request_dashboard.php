<?php include '../includes/header.php';
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
<link rel="stylesheet" type="text/css" href="../vendor/css/style.css" />
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #291af5;
        border-radius: 90vw;
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
    <!-- <div class="row">
        <span class="page-title-itrepair">It Repair And Request</span>
    </div> -->
    <!-- ==================== CONTENT SECTION ==================== -->

    <div id="loading">
        <div class="spinner"></div>
    </div>

    <div class="container-fluid my-3 my-md-3">
        <div class="d-flex justify-content-between bg-light align-items-baseline px-3 py-2 mb-3 rounded shadow">
            <h3 class="fw-bold">
                <i class="fa-solid fa-solid fa-screwdriver-wrench text-bg-danger rounded p-3 me-2"></i>
                <span class="d-none d-md-inline-block">I T&nbsp;&nbsp; R E P A I R&nbsp;&nbsp; A N D&nbsp;&nbsp; R E Q U E S T</span>
            </h3>
            <p class="fw-bold" id="datetime"></p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card custom-card-border shadow">
                    <div class="card-body">
                        <!-- ## ========== B U T T O N S  O N  S W I T C H I N G  T A B S ========== ## -->
                        <ul class="nav nav-pills nav-fill float-md-end mb-3 gap-2" id="pills-tab">
                            <li class="nav-item flex-sm-fill">
                                <button class="nav-link fw-bold active" id="pills-repair-tab" data-bs-toggle="pill" data-bs-target="#pills-repair" type="button">R E P A I R</button>
                            </li>
                            <li class="nav-item flex-sm-fill">
                                <button class="nav-link fw-bold" id="pills-request-tab" data-bs-toggle="pill" data-bs-target="#pills-request" type="button">R E Q U E S T</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <!-- ## ==================== R E P A I R  F O R M ==================== ## -->
                            <div class="tab-pane fade show active" id="pills-repair">
                                <div class="custom-card-title mb-4">
                                    <h3 class="fw-bold">REPAIR FORM</h3>
                                </div>
                                <div class="remainder-header-repair fw-bold mb-3">
                                    R E M I N D E R S &nbsp; <i class="fa-solid fa-bullhorn fa-bounce"></i>
                                    <div class="remainder-details">
                                        This repair request is only for troubleshooting eg. printer troubleshoot, refill ink, telephone and network connection.
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="department" class="form-label fw-bold">Department:</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text text-bg-danger"><i class="fa-solid fa-people-roof"></i></span>
                                        <input type="hidden" name="deptCode" id="deptCode" class="form-control fw-bold" disabled>
                                        <input type="text" name="department" id="department" class="form-control fw-bold" disabled>
                                        <!-- <select name="department" id="department" class="form-select fw-bold"></select> -->
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="requested_by" class="form-label fw-bold">Requested By:</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text text-bg-danger"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="requested_by" id="requested_by" class="form-control fw-bold" disabled>
                                        <!-- <select name="requested_by" id="requested_by" class="form-select fw-bold" disabled>
                                            <option value="">Choose...</option>
                                        </select> -->
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="area" class="form-label fw-bold">Area:</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text text-bg-danger"><i class="fa-solid fa-map-location-dot"></i></span>
                                        <select name="area" id="area" class="form-select fw-bold"></select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="location" class="form-label fw-bold">Location:</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text text-bg-danger"><i class="fa-solid fa-location-dot"></i></span>
                                        <select name="location" id="location" class="form-select fw-bold" disabled>
                                            <option value="" selected>Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="item" class="form-label fw-bold">Item:</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text text-bg-danger"><i class="fa-solid fa-window-restore"></i></span>
                                        <select name="item" id="item" class="form-select fw-bold"></select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="remarks" class="form-label fw-bold">Remarks:</label>
                                    <textarea class="form-control fw-bold" placeholder="Input your remarks here..." id="remarks" style="height: 125px"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary" onclick="clearAttributes();">Cancel</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#submit_repair_modal">Submit</button>
                                </div>
                            </div>
                            <!-- ## ==================== R E P A I R  F O R M  E N D ==================== ## -->

                            <!-- ## ==================== R E Q U E S T  F O R M ==================== ## -->
                            <div class="tab-pane fade" id="pills-request">
                                <div class="custom-card-title mb-4">
                                    <h3 class="fw-bold text-break">REQUEST FORM</h3>
                                </div>
                                <div class="remainder-header fw-bold mb-3">
                                    R E M I N D E R S &nbsp; <i class="fa-solid fa-bullhorn fa-bounce"></i>
                                    <div class="remainder-details">
                                        Software requests are for application development and additional updates to existing application and debugging systems.<br>
                                        Hardware requests are for the replacement of defective items and requests to purchase items.<br> (e.g., printers, monitors, barcodes, and other devices.)
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="request_type" class="form-label fw-bold">Request Type:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-tag"></i></span>
                                            <select name="request_type" id="request_type" class="form-select fw-bold">
                                                <option value="Hardware">Hardware</option>
                                                <option value="Software">Software</option>
                                                <option value="Server">Server Access</option>
                                                <option value="UserAccess">User Access</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3" id="server_ip_section">
                                        <label for="server_ip" class="form-label fw-bold">Server:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-tag"></i></span>
                                            <select name="server_ip" id="server_ip" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                                <option value="192.107.17.49">Banner New Database</option>
                                                <option value="192.107.17.161">Packaging Database</option>
                                                <option value="192.107.17.220">Bannerdata Database</option>
                                                <option value="192.107.16.41">Payroll Database</option>
                                                <option value="192.107.16.248">CMS/Canteen Database</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3" id="user_section">
                                        <label for="user_name" class="form-label fw-bold">User:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-pen"></i></span>
                                            <input type="text" class="form-control fw-bold" name="user_name" id="user_name">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3" id="software_type_section">
                                        <label for="request_software_type" class="form-label fw-bold">Software Type:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-tag"></i></span>
                                            <select name="request_software_type" id="request_software_type" class="form-select fw-bold">
                                                <option value="-" hidden>Choose...</option>
                                                <option value="" hidden>Choose...</option>
                                                <option value="App">App</option>
                                                <option value="Subscription">Subscription</option>
                                                <option value="Website">Website</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <!-- Control Number User Access -->
                                    <div class="col-md-4 mb-3" id="access_control_number">
                                        <label for="control_no" class="form-label fw-bold">Control Number:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-tag"></i></span>
                                            <select name="" id="control_no" class="form-select fw-bold" onchange="previewUserAccess();"></select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3" id="date_needed">
                                        <label for="request_date_needed" class="form-label fw-bold">Date Needed:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-regular fa-calendar-check"></i></span>
                                            <input type="date" max="2999-12-31" class="form-control fw-bold" id="request_date_needed">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="location_section">
                                    <div class="col-md-4 mb-3">
                                        <label for="revoke" class="form-label fw-bold">Access/Revoke:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-tag"></i></span>
                                            <select name="revoke" id="revoke" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                                <option value="false">Add Access</option>
                                                <option value="true">Revoke Access</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="ip_address" class="form-label fw-bold">I.P Address:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-window-restore"></i></span>
                                            <input type="text" class="form-control fw-bold" id="ip_address">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="mac_address" class="form-label fw-bold">Mac Address:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-window-restore"></i></span>
                                            <input type="text" class="form-control fw-bold" id="mac_address">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="app_subs_section">
                                    <div class="col-md-12 mb-3" id="location_server_section">
                                        <label for="location_server" class="form-label fw-bold">Location:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-window-restore"></i></span>
                                            <input type="text" class="form-control fw-bold" id="location_server">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3" id="item_app_subs_section">
                                        <label for="request_item" class="form-label fw-bold">Item:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-window-restore"></i></span>
                                            <input type="text" class="form-control fw-bold" id="request_item">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3" id="purpose_section">
                                        <label for="request_purpose" class="form-label fw-bold">Purpose:</label>
                                        <textarea class="form-control fw-bold" placeholder="Input your purpose here..." id="request_purpose" style="resize:none;height: 100px" maxlength="50" autofocus></textarea>
                                        <div class="invalid-feedback"></div>
                                        <div id="the-count">
                                            <span id="current" class="">0</span>
                                            <span id="maximum" class="">/ 50</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3" id="description_app_subs_section">
                                        <label for="request_description" class="form-label fw-bold">Description:</label>
                                        <textarea class="form-control fw-bold" placeholder="Input your description here..." id="request_description" style="height: 100px"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row" id="website_section">
                                    <div class="col-md-4 mb-3">
                                        <label for="web_priority" class="form-label fw-bold">Priority:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-list-check"></i></span>
                                            <select name="web_priority" id="web_priority" class="form-select fw-bold">
                                                <option value="For Scheduling">For Scheduling</option>
                                                <option value="Urgent">Urgent</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="service_type_web" class="form-label fw-bold">Service Type:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-rectangle-list"></i></span>
                                            <select name="service_type_web" id="service_type_web" class="form-select fw-bold">
                                                <option value="New Application">New Application</option>
                                                <option value="Enhancement to existing application">Enhancement to existing application</option>
                                                <option value="Replace an existing application">Replace an existing application</option>
                                                <option value="New Module">New Module</option>
                                                <option value="New Report">New Report</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="web_app_name" class="form-label fw-bold">Application Name:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-font"></i></span>
                                            <input type="text" class="form-control fw-bold" id="web_app_name">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="web_description" class="form-label fw-bold">Description:</label>
                                        <textarea class="form-control fw-bold" placeholder="Input your purpose here..." id="web_description" style="height: 100px"></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 mb-3" id="department_section">
                                        <label for="request_department" class="form-label fw-bold">Department:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-people-roof"></i></span>
                                            <input type="text" name="request_department" id="request_department" class="form-control fw-bold" disabled>
                                            <!-- <select name="request_department" id="request_department" class="form-select fw-bold"> -->
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // !THIS IS USER ACCESS SIDE =========================================================================================
                                ?>

                                <!-- requested user access here -->
                                <div class="request_section">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="date_request" class="form-label fw-bold">Date Request:</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text text-bg-danger"><i class="fa-solid fa-calendar-check"></i></span>
                                                <input type="date" id="date_request" value="<?php echo date('Y-m-d'); ?>" class="form-control fw-bold" disabled>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm d-flex justify-content-center">
                                        <hr color="red" size="2" width="15%" align="center">
                                        <span class="fw-bold text-danger">ACCESS</span>
                                        <hr color="red" size="2" width="85%" align="center">
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="access" id="access1" value="New" checked>
                                                <label class="form-check-label fw-bold" for="access1">
                                                    New
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="access" id="access2" value="Additional">
                                                <label class="form-check-label fw-bold" for="access2">
                                                    Additional
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="access" id="access3" value="Change">
                                                <label class="form-check-label fw-bold" for="access3">
                                                    Change
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm d-flex justify-content-center">
                                        <hr color="red" size="2" width="15%" align="center">
                                        <span class="fw-bold text-danger">PRIORITY</span>
                                        <hr color="red" size="2" width="85%" align="center">
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="priority" id="priority1" value="Urgent" checked>
                                                <label class="form-check-label fw-bold" for="priority1">
                                                    Urgent
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="priority" id="priority2" value="For Scheduling">
                                                <label class="form-check-label fw-bold" for="priority2">
                                                    For Scheduling
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="domainAccount">
                                                <label class="form-check-label fw-bold" for="domainAccount">
                                                    Domain Account
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox" id="mail_account" value="">
                                            </div>
                                            <input type="text" class="form-control" id="mail_account_input" placeholder="Mail Account" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox" id="file_storage_access" value="">
                                            </div>
                                            <input type="text" class="form-control" id="file_storage_access_input" placeholder="File Storage Access" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="checkbox" id="in_house_access" value="">
                                            </div>
                                            <input type="text" class="form-control" id="in_house_access_input" placeholder="In House Access" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text fw-bold">Purpose:</span>
                                            <input type="text" class="form-control" id="purpose">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // !THIS IS USER ACCESS SIDE =========================================================================================
                                ?>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="request_requested_by" class="form-label fw-bold">Requested By:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" name="request_requested_by" id="request_requested_by" class="form-control fw-bold" disabled>
                                            <!-- <select name="request_requested_by" id="request_requested_by" class="form-select fw-bold" disabled>
                                                <option value="">Choose...</option>
                                            </select> -->
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="request_approved_by" class="form-label fw-bold">Approved By:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-user-check"></i></span>
                                            <select name="request_approved_by" id="request_approved_by" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="requested_by" class="form-label fw-bold">Noted By:</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-bg-danger"><i class="fa-solid fa-user-pen"></i></span>
                                            <select name="request_noted_by" id="request_noted_by" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-secondary" onclick="clearAttributes();">Cancel</button>
                                    <button class="btn btn-danger fw-bold" id="generatePdf" onclick="generatePdf();"><i class="fa-solid fa-file-pdf"></i> Generate PDF</button>
                                    <button type="button" class="btn btn-danger btn-submit" data-bs-toggle="modal" data-bs-target="#submit_request_modal">Submit</button>
                                    <button type="button" class="btn btn-danger btn-save" onclick="saveUserAccess();">Save</button>
                                    <button type="button" class="btn btn-warning btn-update" onclick="updateUserAccess(this.value);"><i class="fa-solid fa-pen-to-square animation-trigger"></i> Update</button>
                                </div>
                            </div>
                            <!-- ## ==================== R E Q U E S T  F O R M  E N D ==================== ## -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card custom-card-border shadow">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="text-nowrap">
                                        <div class="fs-6 fw-bold text-danger">NOW REPAIRING</div>
                                        <div class="fs-3 fw-bold fa-fade" id="now_repairing"></div>
                                    </div>
                                    <div class="fs-2 text-secondary">
                                        <i class="fa-solid fa-hammer fa-shake"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card custom-card-border shadow">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="text-nowrap">
                                        <div class="fs-6 fw-bold text-danger">ONGOING REQUEST</div>
                                        <div class="fs-3 fw-bold fa-fade" id="ongoing_request"></div>
                                    </div>
                                    <div class="fs-2 text-secondary">
                                        <i class="fa-solid fa-file-export fa-beat-fade"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3 ">
                        <div class="card custom-card-border shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-grid d-md-flex flex-wrap justify-content-md-between gap-3">
                                            <div class="search-bar">
                                                <input class="search-input" type="search" id="search" placeholder="Search: Track Number or Requestor">
                                                <button type="button" class="btn btn-danger rounded-pill search-icon" id="search_btn"><i class="fas fa-search"></i></button>
                                                <div class="search-result-list">
                                                    <div class="text-center gap-1 search-loading">
                                                        <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                                                        <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                                                        <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-evenly align-items-center gap-3 fw-bold">
                                                <span class="fs-6">
                                                    <i class="fa-solid fa-square text-info me-2"></i>For Received
                                                </span>
                                                <span class="fs-6">
                                                    <i class="fa-solid fa-square text-success me-2"></i>Ongoing
                                                </span>
                                                <span class="fs-6">
                                                    <i class="fa-solid fa-square text-warning me-2"></i>Pending
                                                </span>
                                                <span class="fs-6">
                                                    <i class="fa-solid fa-square text-danger me-2"></i>On Hold
                                                </span>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="custom-card-title mb-2">
                                            <div class="fw-bold fs-5">REPAIR</div>
                                        </div>
                                        <ul class="list-group list-group-flush list fw-bold" id="repair_list"></ul>
                                    </div>
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="custom-card-title mb-2">
                                            <div class="fw-bold fs-5">REQUEST</div>
                                        </div>
                                        <ul class="list-group list-group-flush fw-bold" id="request_list"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="message-section">
            <button class="btn btn-danger rounded-circle floating-message-btn"><i class="fa-solid fa-message"></i></button>
            <div class="card shadow message-card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="chat-head"><i class="fa-solid fa-robot fa-bounce"></i></div>
                        <div class="chat-name">Chat with
                            <div class="fw-bold fs-5">I.T. Team</div>
                        </div>
                    </div>
                    <button class="message-close">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
                <div class="form-floating">
                    <!-- <select class="form-select rounded-0" id="message_sender">
                        <option value="">Choose...</option>
                    </select>
                    <label for="message_sender">Reference Number:</label> -->
                </div>
                <div class="card-body">
                    <div class="message-body" id="message_body">
                        <div class="message-bubble" id="receiver">First, select your reference number so you may contact the IT team.</div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <textarea type="text" class="message-input" id="message_input" placeholder="Enter your message here..."></textarea>
                    <button class="btn btn-danger rounded-pill" id="message_send"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================== CONTENT SECTION END ==================== -->
    <div class="position-fixed z-3 app-card-wrapper"><!-- ==================== CARD SECTION ==================== -->
        <div class="card card-1 border-0 shadow app-card">
            <div class="d-flex justify-content-between justify-content-md-between mt-1 me-3 align-items-center">
                <button class="btn text-white fs-2" onclick="hideCard();"><i class="fa-solid fa-bars"></i></button>
                <a href="../Landing_Page.php" class="text-white fs-2">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
            <div class="position-absolute app-title-wrapper">
                <span class="fw-bold app-title text-nowrap">IT REPAIR AND REQUEST</span>
            </div>
            <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
            </div>
        </div>
    </div>
    <!-- ==================== CARD SECTION END ==================== -->
    <!-- ==================== CARD BUTTON SECTION ==================== -->
    <div class="position-fixed app-circle-btn-wrapper">
        <button class="btn btn-danger rounded-circle app-circle-btn" onclick="showCard();"><i class="fa-solid fa-bars app-circle-bars"></i></button>
    </div>
    <!-- ==================== CARD BUTTON SECTION END ==================== -->
</div>




<!-- ## ==================== S U B M I T  R E P A I R  M O D A L ==================== ## -->
<div class="modal fade" id="submit_repair_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body p-4 text-center">
                <h3 class="fw-semibold">Are you sure?</h3>
                <p class="mt-2 mb-3">Do you want to submit a repair request?</p>
                <img class="" height="135" width="180" src="../vendor/images/send-request.gif" alt="">
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-link col-6 m-0 text-decoration-none text-muted border-end rounded-0" data-bs-dismiss="modal">No thanks</button>
                <button type="button" class="btn btn-link col-6 m-0 text-decoration-none text-danger fw-bold" onclick="newRepair();">Yes, submit</button>
            </div>
        </div>
    </div>
</div>

<!-- ## ==================== S U B M I T  R E Q U E S T  M O D A L ==================== ## -->
<div class="modal fade" id="submit_request_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body p-4 text-center">
                <h3 class="fw-semibold">Are you sure?</h3>
                <p class="mt-2 mb-3">Do you want to submit a request?</p>
                <img class="" height="135" width="180" src="../vendor/images/send-request.gif" alt="">
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-link col-6 m-0 text-decoration-none text-muted border-end rounded-0" data-bs-dismiss="modal">No thanks</button>
                <button type="button" class="btn btn-link col-6 m-0 text-decoration-none text-danger fw-bold" onclick="newRequest();">Yes, submit</button>
            </div>
        </div>
    </div>
</div>

<!-- ## ==================== Q U E U E  N U M B E R  M O D A L ==================== ## -->
<div class="modal fade" id="queue_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="fw-bold">Reference Number</h3>
                <p class="fs-1 my-2 fw-bold" id="queue_number">23-0001</p>
                <p class="text-muted mb-0 fw-bold fst-italic">Please take note your reference number.</p>
            </div>
            <div class="modal-footer p-0">
                <button type="button" class="btn btn-link text-decoration-none text-danger fs-5 fw-bold w-100" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<!-- ## ==================== T R A C K  R E P A I R  R E Q U E S T  M O D A L ==================== ## -->
<div class="modal fade" id="search_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold">Tracking Number</h4>
                    <div class="badge text-white w-25 px-3 py-2 rounded-pill" id="track_status"></div>
                </div>
                <hr>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Reference Number:</div>
                        <span id="track_queue_number"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Requested by:</div>
                        <span id="track_requested_by"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Area:</div>
                        <span id="track_area"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Location:</div>
                        <span id="track_location"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Item:</div>
                        <span id="track_item"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Date Requested:</div>
                        <span id="track_date_requested"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Remarks:</div>
                        <span id="track_remarks"></span>
                    </li>
                </ul>

                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="search_shr_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold">Tracking Number</h4>
                    <div class="badge text-white w-25 px-3 py-2 rounded-pill" id="track_shr_status"></div>
                </div>
                <hr>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Reference Number:</div>
                        <span id="track_shr_queue_number"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Requested by:</div>
                        <span id="track_shr_requested_by"></span>
                    </li>

                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Request Type:</div>
                        <span id="track_shr_request_type"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Item:</div>
                        <span id="track_shr_item"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Description:</div>
                        <span id="track_shr_description"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Purpose:</div>
                        <span id="track_shr_purpose"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Date Requested:</div>
                        <span id="track_shr_date_requested"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Date Needed:</div>
                        <span id="track_shr_date_needed"></span>
                    </li>
                </ul>
                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="search_wfr_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold">Tracking Number</h4>
                    <div class="badge text-white w-25 px-3 py-2 rounded-pill" id="track_wfr_status"></div>
                </div>
                <hr>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Reference Number:</div>
                        <span id="track_wrf_queue_number"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Requested by:</div>
                        <span id="track_wrf_requested_by"></span>
                    </li>

                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Request Type:</div>
                        <span id="track_wrf_request_type"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Priority:</div>
                        <span id="track_wrf_priority"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Service Type:</div>
                        <span id="track_wrf_service_type"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Description:</div>
                        <span id="track_wrf_description"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Application Name:</div>
                        <span id="track_wrf_application_name"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Date Requested:</div>
                        <span id="track_wrf_date_requested"></span>
                    </li>
                    <li class="list-queue list-group-item-action">
                        <div class="fw-bold">Date Needed:</div>
                        <span id="track_wrf_date_needed"></span>
                    </li>
                </ul>
                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<!-- ## ==================== E D I T  R E P A I R  R E Q U E S T  M O D A L ==================== ## -->
<div class="modal fade" id="edit_repair_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="fw-bold mb-4">Edit Repair Request</h3>
                <div class="form-floating mb-3">
                    <input type="text" id="repair_queue_number_edit" class="form-control-plaintext text-end fs-3 fw-bold border border-1 rounded" placeholder=" " readonly="" disabled="">
                    <div class="invalid-feedback"></div>
                    <label for="repair_queue_number_edit" class="form-label fw-bold">Reference Number:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="repair_department_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="repair_department_edit" class="fw-bold">Department:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="repair_requested_by_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="repair_requested_by_edit" class="fw-bold">Requested By:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="repair_area_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="repair_area_edit" class="fw-bold">Area:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="repair_location_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="repair_location_edit" class="fw-bold">Location:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="repair_item_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="repair_item_edit" class="fw-bold">Item:</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control fw-bold" placeholder="Leave a comment here" id="repair_remarks_edit" style="height: 150px"></textarea>
                    <div class="invalid-feedback"></div>
                    <label for="repair_remarks_edit" class="fw-bold">Remarks:</label>
                </div>
                <div class="d-grid gap-2 w-100 mt-4">
                    <button type="button" class="btn btn-danger" id="repair_request_edit">Update</button>
                    <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ## ==================== E D I T  R E Q U E S T  M O D A L ==================== ## -->
<div class="modal fade" id="edit_request_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="fw-bold mb-4">Edit Request</h3>
                <div class="form-floating mb-3">
                    <input type="text" id="request_queue_number_edit" class="form-control-plaintext text-end fs-3 fw-bold border border-1 rounded" placeholder=" " readonly="" disabled="">
                    <div class="invalid-feedback"></div>
                    <label for="request_queue_number_edit" class="form-label fw-bold">Reference Number:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="request_request_type_edit" class="form-select fw-bold">
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Server">Server Access</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="request_request_type_edit" class="fw-bold">Requested Type:</label>
                </div>
                <div class="form-floating mb-3" id="software_section">
                    <select id="request_software_type_edit" class="form-select fw-bold">
                        <option value="App">App</option>
                        <option value="Subscription">Subscription</option>
                        <option value="Website">Website</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="request_software_type_edit" class="fw-bold">Software Type:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" class="form-control fw-bold" id="request_date_needed_edit">
                    <div class="invalid-feedback"></div>
                    <label for="request_date_needed_edit" class="fw-bold">Date Needed:</label>
                </div>


                <div class="form-floating mb-3">
                    <select id="server_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                        <option value="192.107.17.49">Banner New Database</option>
                        <option value="192.107.17.161">Packaging Database</option>
                        <option value="192.107.17.220">Bannerdata Database</option>
                        <option value="192.107.16.41">Payroll Database</option>
                        <option value="192.107.16.248">CMS/Canteen Database</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="server_edit" class="fw-bold">Server:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control fw-bold" id="user_edit">
                    <div class="invalid-feedback"></div>
                    <label for="user_edit" class="fw-bold">User:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="access_revoke_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                        <option value="false">Add Access</option>
                        <option value="true">Revoke Access</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="access_revoke_edit" class="fw-bold">Access/Revoke:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control fw-bold" id="server_ip_edit">
                    <div class="invalid-feedback"></div>
                    <label for="server_ip_edit" class="fw-bold">I.P Address:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control fw-bold" id="mac_address_edit">
                    <div class="invalid-feedback"></div>
                    <label for="mac_address_edit" class="fw-bold">Mac Address:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control fw-bold" id="location_server_edit">
                    <div class="invalid-feedback"></div>
                    <label for="location_server_edit" class="fw-bold">Location:</label>
                </div>

                <div class="form-floating mb-3" id="priority_section">
                    <select id="priority" class="form-select fw-bold">
                        <option value="For Scheduling">For Scheduling</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="priority" class="fw-bold">Priority:</label>
                </div>
                <div class="form-floating mb-3" id="service_section">
                    <select id="service_type" class="form-select fw-bold">
                        <option value="New Application">New Application</option>
                        <option value="Enhancement to existing application">Enhancement to existing application</option>
                        <option value="Replace an existing application">Replace an existing application</option>
                        <option value="New Module">New Module</option>
                        <option value="New Report">New Report</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="service_type" class="fw-bold">Service Type:</label>
                </div>
                <div class="form-floating mb-3" id="application_name_section">
                    <input type="text" class="form-control fw-bold" id="application_name">
                    <div class="invalid-feedback"></div>
                    <label for="application_name" class="fw-bold">Application Name:</label>
                </div>


                <div class="form-floating mb-2 hide_purpose">
                    <textarea id="request_purpose_edit" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                    <div class="invalid-feedback"></div>
                    <label for="request_purpose_edit" class="col-form-label fw-bold">Purpose</label>
                </div>
                <div class="form-floating mb-2">
                    <textarea id="request_description_edit" class="form-control fw-bold" style="resize:none;height: 120px"></textarea>
                    <div class="invalid-feedback"></div>
                    <label for=request_description_edit" class="col-form-label fw-bold">Description</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="request_department_edit" class="form-select fw-bold" onchange="loadDepartmentEmployee(this.value);">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="request_department_edit" class="fw-bold">Department:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="request_requested_by_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="request_requested_by_edit" class="fw-bold">Requested By:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="request_approved_by_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="request_approved_by_edit" class="fw-bold">Approved By:</label>
                </div>
                <div class="form-floating mb-3">
                    <select id="request_noted_by_edit" class="form-select fw-bold">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="request_noted_by_edit" class="fw-bold">Noted By:</label>
                </div>
                <div class="d-grid gap-2 w-100 mt-4">
                    <button type="button" class="btn btn-danger repair_request_edit" id="repair_request_edit" onclick="updateEditRequest(this.value);">Update</button>
                    <button type="button" class="btn btn-light text-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    let appId = '<?php echo $_GET['app_id'] ?>';
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var user_department = '<?php echo $_SESSION['dept_code']; ?>';
    loadControlNo();
    // ! USER ACCESS FUNCTION
    $('.btn-update').hide();
    $('.btn-save').hide();
    $('#generatePdf').hide();

    // * ~ Date needed value is current date ~
    let currentDate = (new Date()).toISOString().split('T')[0];
    $('#request_date_needed').val(currentDate).prop('min', currentDate);
    $('#date_request').val(currentDate).prop('min', currentDate);

    function generatePdf() {
        let control_no = $('#control_no').val();
        window.open(`../itasset/it_user_access_request_pdf.php?control_no=${control_no}`, '_blank');
    }

    function previewUserAccess() {
        var control_no = document.getElementById("control_no");
        var firstOption = control_no.options[0];
        if ($('#control_no').val() == firstOption.textContent.replace("UAF-", "")) {
            clearAttributes();
            userDetails();
        } else {
            $.ajax({
                url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'previewControlPreview',
                    control_no: $('#control_no').val()
                },
                success: result => {
                    $('#request_date_needed').val(result.date_need)
                    $('#access1').val() == result.access ? $('#access1').prop('checked', true) : $('#access1').prop('checked', false);
                    $('#access2').val() == result.access ? $('#access2').prop('checked', true) : $('#access2').prop('checked', false);
                    $('#access3').val() == result.access ? $('#access3').prop('checked', true) : $('#access3').prop('checked', false);
                    $('#priority1').val() == result.priority ? $('#priority1').prop('checked', true) : $('#priority1').prop('checked', false);
                    $('#priority2').val() == result.priority ? $('#priority2').prop('checked', true) : $('#priority2').prop('checked', false);
                    result.domain_account == true ? $('#domainAccount').prop('checked', true) : $('#domainAccount').prop('checked', false);

                    result.mail_account == '' ? $('#mail_account').prop('checked', false) : $('#mail_account').prop('checked', true);
                    result.file_storage_access == '' ? $('#file_storage_access').prop('checked', false) : $('#file_storage_access').prop('checked', true);
                    result.in_house_access == '' ? $('#in_house_access').prop('checked', false) : $('#in_house_access').prop('checked', true);

                    result.mail_account == '' ? $('#mail_account_input').prop('disabled', true) : $('#mail_account_input').prop('disabled', false);
                    result.file_storage_access == '' ? $('#file_storage_access_input').prop('disabled', true) : $('#file_storage_access_input').prop('disabled', false);
                    result.in_house_access == '' ? $('#in_house_access_input').prop('disabled', true) : $('#in_house_access_input').prop('disabled', false);
                    $('#purpose').val(result.purpose);

                    $('#request_requested_by').val(result.prepared_by);
                    $('#request_approved_by').val(result.approved_by);
                    $('#request_noted_by').val(result.noted_by);
                    $('#mail_account_input').val(result.mail_account);
                    $('#file_storage_access_input').val(result.file_storage_access);
                    $('#in_house_access_input').val(result.in_house_access);
                }
            })
            $('.btn-update').show();
            $('.btn-save').hide();
            $('#generatePdf').show();
        }
    }

    function updateUserAccess() {
        var radioButtons = document.getElementsByName("access");
        var access;
        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                access = radioButtons[i].value;
                break;
            }
        }
        var radioButtons2 = document.getElementsByName("priority");
        var priority;
        for (var i = 0; i < radioButtons2.length; i++) {
            if (radioButtons2[i].checked) {
                priority = radioButtons2[i].value;
                break;
            }
        }
        var domainAccount = document.getElementById("domainAccount").checked;
        Swal.fire({
            title: 'Do you want to update the changes?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Update',
            denyButtonText: `Don't update`,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Update!', '', 'success')
                $.ajax({
                    url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
                    type: 'POST',
                    data: {
                        action: 'update',
                        control_no: $('#control_no').val(),
                        date_needed: $('#request_date_needed').val(),
                        access: access,
                        priority: priority,
                        domainAccount: domainAccount, // Pass the checkbox value, not the DOM element
                        mail_account: $('#mail_account_input').val(),
                        file_storage_access: $('#file_storage_access_input').val(),
                        in_house_access: $('#in_house_access_input').val(),
                        purpose: $('#purpose').val(),
                        preparedBy: $('#request_requested_by').val(),
                        approvedBy: $('#request_approved_by').val(),
                        notedBy: $('#request_noted_by').val()
                    },
                    success: result => {
                        // cancelBtn();
                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not update', '', 'info')
            }
        })
    }

    function toggleInputState(checkboxId, inputSelector) {
        var checkbox = document.getElementById(checkboxId);
        var inputField = document.querySelector(inputSelector);
        inputField.disabled = !checkbox.checked;
    }
    document.getElementById("mail_account").addEventListener("change", function() {
        toggleInputState("mail_account", ".form-control[placeholder='Mail Account']");
    });
    document.getElementById("file_storage_access").addEventListener("change", function() {
        toggleInputState("file_storage_access", ".form-control[placeholder='File Storage Access']");
    });
    document.getElementById("in_house_access").addEventListener("change", function() {
        toggleInputState("in_house_access", ".form-control[placeholder='In House Access']");
    });
    generateDefectiveRefno('tblit_control_no', 'user_access_control_no', 'control_no');
    loadNotedBy();

    function generateDefectiveRefno(inTable, inField, inObject) {
        $('#' + inObject).html('');
        $.ajax({
            url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
            type: 'POST',
            data: {
                action: 'generate_defective_refno',
                inTable: inTable,
                inField: inField
            },
            success: function(result) {
                $('#' + inObject).prepend(`<option value="${result}" class="text-primary fw-bold" selected>UAF-${result}</option>`);
            }
        });
    }

    function saveUserAccess() {
        if (formValidation('purpose')) {
            var radioButtons = document.getElementsByName("access");
            var access;
            for (var i = 0; i < radioButtons.length; i++) {
                if (radioButtons[i].checked) {
                    access = radioButtons[i].value;
                    break;
                }
            }
            var radioButtons2 = document.getElementsByName("priority");
            var priority;
            for (var i = 0; i < radioButtons2.length; i++) {
                if (radioButtons2[i].checked) {
                    priority = radioButtons2[i].value;
                    break;
                }
            }
            var domainAccount = document.getElementById("domainAccount").checked;
            Swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Saved!', '', 'success')
                    $.ajax({
                        url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                        type: 'POST',
                        data: {
                            action: 'saveUserAccess',
                            control_no: $('#control_no').val(),
                            date_request: $('#date_request').val(),
                            date_needed: $('#request_date_needed').val(),
                            access: access,
                            priority: priority,
                            domainAccount: domainAccount, // Pass the checkbox value, not the DOM element
                            mail_account: $('#mail_account_input').val(),
                            file_storage_access: $('#file_storage_access_input').val(),
                            in_house_access: $('#in_house_access_input').val(),
                            purpose: $('#purpose').val(),
                            preparedBy: $('#request_requested_by').val(),
                            approvedBy: $('#request_approved_by').val(),
                            notedBy: $('#request_noted_by').val()
                        },
                        success: result => {
                            if (result == 'Exist') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Control Number Already Exist!',
                                });
                            } else {
                                generateDefectiveRefno('tblit_control_no', 'user_access_control_no', 'control_no');
                                loadControlNo();
                                clearAttributes();
                                userDetails();
                            }
                        }
                    });
                    // loadNotedBy();
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }
    }

    function loadControlNo() {
        $('#control_no').html('');
        $.ajax({
            url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadControlNo',
                logged_user: logged_user
            },
            success: result => {
                $.each(result, function(key, value) {
                    $('#control_no').append(`<option value="${value}" class="text-danger fw-bold">UAF-${value}</option>`);
                })
            }
        });
    }

    // function cancelBtn() {
    //     $('#date_needed').val('mm/dd/yy');
    //     $('input').prop('checked', false);
    //     $('#access1').prop('checked', true);
    //     $('#priority1').prop('checked', true);
    //     $('#mail_account_input').prop('disabled', true);
    //     $('#file_storage_access_input').prop('disabled', true);
    //     $('#in_house_access_input').prop('disabled', true);
    //     $('#control_no').find('option:first').prop('selected', 'selected');
    //     $('.btn-update').hide();
    //     $('.btn-save').show();
    //     $('#generatePdf').hide();
    //     $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid');
    //     loadNotedBy();
    // }

    // ! USER ACCESS FUNCTION
    // * ================ Global Variables ================
    let repairIndex = 0;
    let requestIndex = 0;
    let currentFocus = -1;
    let activeMessageSender;
    loadMessages();
    const search = $('#search');
    const resultList = $('.search-result-list');
    const searchBtn = $('#search_btn');

    $("#mac_address").mask("99:99:99:99:99:99");
    $("#ip_address").mask("999.999.99.999");

    $("#server_ip_edit").mask("99:99:99:99:99:99");
    $("#mac_address_edit").mask("999.999.99.999");

    const messageSender = $('#message_sender');
    const messageInput = $('#message_input');
    const messageSend = $('#message_send');
    const messageBody = $('#message_body');
    // * ================ B E H A V I O R S ================
    // * ~ Loading Animation ~
    $(window).on('load', function() {
        $('#loading').fadeOut(3000);
    });

    // * ~ Validation on input and change ~
    $(document).on("input change textarea", "input.is-invalid, select.is-invalid, textarea.is-invalid", function() {
        $(this).toggleClass('is-valid is-invalid');
    });

    resultList.on('click', 'li', e => {
        search.val($(e.currentTarget).text());
        resultList.hide();
    });

    $('#access_control_number').hide();
    $('#priority_urgent_section').hide();
    $('.request_section').hide();

    search.on('keydown', e => {
        const listItems = resultList.find('li');
        const keyCode = e.code || e.which;
        const isArrowDown = keyCode === 'ArrowDown' || keyCode === 40;
        const isArrowUp = keyCode === 'ArrowUp' || keyCode === 38;
        const isEnter = keyCode === 'Enter' || keyCode === 'NumpadEnter' || keyCode === 13;

        if (listItems.length === 0) return;

        if (isArrowDown || isArrowUp) {
            e.preventDefault();
            const increment = isArrowDown ? 1 : -1;
            currentFocus = (currentFocus + increment + listItems.length) % listItems.length;
            listItems.removeClass('focus').eq(currentFocus).addClass('focus');
        } else if (isEnter) {
            e.preventDefault();
            search.val(listItems.eq(currentFocus).text());
            resultList.hide();
        }
    });

    function generateSelect(object) {
        let option = `<option value="" selected>Choose...</option>`;
        object.forEach((value) => {
            option += `<option value="${value}">${value}</option>`;
        });
        return option;
    }

    function generateSelectKeyValue(object) {
        let option = `<option value="" selected>Choose...</option>`;
        $.each(object, (key, value) => {
            option += `<option value="${key}">${value}</option>`;
        });
        return option;
    }

    function generateList(object) {
        let list = '',
            status = {
                'On Hold': 'text-danger',
                'Pending': 'text-warning',
                'Ongoing': 'fa-fade text-success',
                'For Received': 'fa-fade text-info',
            };
        $.each(object, (key, value) => {
            let editable = '',
                icon = '';
            switch (key.substring(0, 3)) {
                case 'SHR':
                    $.each(value, (requested_by, request_status) => {
                        if (request_status == 'Pending' && requested_by == logged_user) {
                            editable = `id="edit_request"`
                            icon = `<i class="fa-solid fa-pen text-muted ms-2"></i>`
                        };
                        list += `<li class="list-queue list-group-item-action" data-reference="${key}" ${editable}>
                                <i class="fa-solid fa-square ${status[request_status]} me-2"></i>${key}${icon}
                            </li>`;
                    });
                    break;
                case 'ITR':
                    $.each(value, (requested_by, request_status) => {
                        if (request_status == 'On Hold' && requested_by == logged_user) {
                            editable = `id="edit_repair_request"`
                            icon = `<i class="fa-solid fa-pen text-muted ms-2"></i>`
                        };
                        list += `<li class="list-queue list-group-item-action" data-reference="${key}" ${editable}>
                                <i class="fa-solid fa-square ${status[request_status]} me-2"></i>${key}${icon}
                            </li>`;
                    });
                    break;
                case 'WRF':
                    $.each(value, (requested_by, request_status) => {
                        if (request_status == 'Pending' && requested_by == logged_user) {
                            editable = `id="edit_request"`
                            icon = `<i class="fa-solid fa-pen text-muted ms-2"></i>`
                        };
                        list += `<li class="list-queue list-group-item-action" data-reference="${key}" ${editable}>
                                <i class="fa-solid fa-square ${status[request_status]} me-2"></i>${key}${icon}
                            </li>`;
                    });
                    break;
                case 'SAF':
                    $.each(value, (requested_by, request_status) => {
                        if (request_status == 'Pending' && requested_by == logged_user) {
                            editable = `id="edit_request"`
                            icon = `<i class="fa-solid fa-pen text-muted ms-2"></i>`
                        };
                        list += `<li class="list-queue list-group-item-action" data-reference="${key}" ${editable}>
                                <i class="fa-solid fa-square ${status[request_status]} me-2"></i>${key}${icon}
                            </li>`;
                    });
                    break;
            }
        });
        return list;
    }

    function dateTime() {
        $('#datetime').html(
            new Date().toLocaleString("en-US", {
                dateStyle: 'full',
                timeStyle: 'medium',
            }).replace(/(.*)at(.*)/, "$1-$2")
        );
        setTimeout(dateTime, 1000);
    }

    function scrollToLastMessage() {
        setTimeout(() => {
            $('.message-bubble').last()[0].scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }, 250);
    }
    // messageSender.change(() => {
    //     const senderVal = messageSender.val();
    //     activeMessageSender = senderVal;
    //     messageInput.add(messageSend).prop('disabled', !senderVal);
    //     messageBody.html(senderVal ? loadMessages() : '<div class="message-bubble" id="receiver">First, select your request number so you may contact the IT team.</div>');
    //     scrollToLastMessage();
    // });

    messageSend.click(sendMessage);

    messageInput.keyup((event) => {
        if (event.keyCode === 13) {
            sendMessage();
        }
    });
    //  !=======HELPER FUNCTION 
    function onLoadInputs() {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'onLoadInputs'
            },
            success: function(res) {
                $('#item').html(generateSelect(res.item));
                $('#repair_item_edit').html(generateSelect(res.item));

                $('#area').html(generateSelect(res.area));
                $('#repair_area_edit').html(generateSelect(res.area));

                $('#department').html(generateSelectKeyValue(res.department));
                $('#repair_department_edit').html(generateSelectKeyValue(res.department));
                $('#request_department_edit').html(generateSelectKeyValue(res.department));
                $('#request_department').html(generateSelectKeyValue(res.department));
            }
        });
    }

    function loadEditRequest(dept_code, inObject) {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadEditRequest',
                dept_code: dept_code
            },
            success: function(res) {
                $.each(res, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${key}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${key}">${value}</option>`);
                    }
                });
            }
        });
    }

    function loadLocation(area, locationField) {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadLocation',
                area: $(area).val()
            },
            success: function(res) {
                if (res) {
                    $(locationField).html(generateSelect(res.location));
                }
            }
        });
    }

    function loadEmployeeDepartment(employee, requestedByField) {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadEmployeeDepartment',
                employee: employee
            },
            success: function(res) {
                $(requestedByField).val(res.department);
                loadEditRequest(res.department, "request_requested_by_edit");
                setTimeout(function() {
                    $('#request_requested_by_edit').val(employee);
                }, 200);
            }
        });
    }

    function loadEmployees(department, requestedByField) {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadEmployee',
                deptCode: $(department).val()
            },
            success: function(res) {
                $(requestedByField).html(generateSelect(res.employee));
            }
        });
    }

    function loadApprovedBy() {
        $('#request_department').change(function() {
            // $('#request_approved_by').prop('disabled', $(this).val() == '' ? 1 : 0);
            $.ajax({
                url: '../controller/itrepair_request_controller/helper_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'loadDepartmentHead',
                    deptCode: $(this).val()
                },
                success: function(res) {
                    let deptCode = $('#request_department').val();
                    let deptHeadCode = {
                        'ISD': 'CIS',
                        'SMD': 'VPS',
                        'ITD': 'VPI',
                        'PHD': 'VPP',
                        'HRD': 'SHO',
                        'PUD': 'PUM',
                        'PSD': 'VPS',
                        'PRD': 'EAM',
                        'FID': 'FIM',
                        'FMD': 'VPP',
                        'MSD': 'VPQ',
                        'RDD': 'VPQ'
                    };
                    let selected = "";
                    let option = `<option value="">Choose...</option>`;
                    $.each(res.deptHead, (key, value) => {
                        selected = key == deptHeadCode[deptCode] ? "selected" : "";
                        option += `<option value="${value}" ${selected}>${value}</option>`;
                    });
                    $('#request_approved_by').html(option);
                }
            });
        })
    }

    function loadNotedBy() {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadDepartmentHead'
            },
            success: function(res) {
                let selected = "";
                let selectedPos = "";
                let option = `<option value="">Choose...</option>`;
                let optionPos = `<option value="">Choose...</option>`;
                let option2 = `<option value="">Choose...</option>`;
                let posCode = "";

                $.ajax({
                    url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'getPosCode',
                        user_department: user_department
                    },
                    success: function(result) {
                        posCode = result.pos_code;
                        $.each(res.deptHead, function(key, value) {
                            selected = key === "VPI" ? "selected" : "";
                            selectedPos = key === posCode ? "selected" : "";
                            option += `<option value="${value}" ${selected}>${value}</option>`;
                            optionPos += `<option value="${value}" ${selectedPos}>${value}</option>`;
                            option2 += `<option value="${value}">${value}</option>`;
                        });
                        $('#request_noted_by, #request_approved_by').html(option2);
                        $('#request_noted_by').html(option);
                        $('#request_approved_by').html(optionPos);
                    },
                    error: function(xhr, status, error) {
                        console.log(error); // Print the error to the console for debugging
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(error); // Print the error to the console for debugging
            }
        });
    }

    function loadNotedByEdit() {
        $.ajax({
            url: '../controller/itrepair_request_controller/helper_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadDepartmentHead'
            },
            success: function(res) {
                let selected = "";
                let option = `<option value="">Choose...</option>`;
                $.each(res.deptHead, function(key, value) {
                    option += `<option value="${value}">${value}</option>`;
                });
                $('#request_approved_by_edit, #request_noted_by_edit').html(option);
            }
        });
    }

    function loadDepartmentEmployee(dept) {
        $('#request_requested_by_edit').html('<option value="">Choose...</option>');
        loadEditRequest(dept, "request_requested_by_edit");
    }

    // * ================ F U N C T I O N S ================

    // * ~ Function Calls ~
    dateTime();
    onLoadInputs();
    loadApprovedBy();
    loadNotedBy();

    loadNowRepairingOngoingRequest();
    setInterval(loadNowRepairingOngoingRequest, 2000);

    loadQueueList();
    setInterval(loadQueueList, 1000);

    loadMessageSender();
    setInterval(loadMessageSender, 2000);

    // * ================ C H A N G E  F U N C T I O N  B E H A V I O R ================

    $('#department').change(function() {
        $('#requested_by').prop('disabled', $(this).val() == '' ? 1 : 0);
        loadEmployees('#department', '#requested_by');
    });

    $('#request_department').change(function() {
        $('#request_requested_by').prop('disabled', $(this).val() == '' ? 1 : 0);
        loadEmployees('#request_department', '#request_requested_by');
    });

    $('#repair_department_edit').change(function() {
        $('#repair_requested_by_edit').prop('disabled', $(this).val() == '' ? 1 : 0);
        loadEmployees('#repair_department_edit', '#repair_requested_by_edit');
    });

    $('#area').change(function() {
        $('#location').prop('disabled', $(this).val() == '' ? 1 : 0);
        loadLocation('#area', '#location');
    });

    $('#repair_area_edit').change(function() {
        $('#repair_location_edit').prop('disabled', $(this).val() == '' ? 1 : 0);
        loadLocation('#repair_area_edit', '#repair_location_edit');
    });
    $('#requested_by').val(logged_user);
    userDetails();

    $('#repair_request_edit').click(() => {
        if (formValidation('repair_department_edit', 'repair_requested_by_edit', 'repair_area_edit', 'repair_location_edit', 'repair_item_edit', 'repair_remarks_edit', )) {
            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                data: {
                    action: 'updateOnHoldRepair',
                    queue_number: $('#repair_queue_number_edit').val(),
                    dept_code: $('#repair_department_edit').val(),
                    requested_by: $('#repair_requested_by_edit').val(),
                    area: $('#repair_area_edit').val(),
                    location: $('#repair_location_edit').val(),
                    item: $('#repair_item_edit').val(),
                    remarks: $('#repair_remarks_edit').val(),
                },
                success: res => {
                    if (res) {
                        $('#edit_repair_modal').modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Success',
                            text: 'Update Successful',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }
    });

    $('#request_list').on('click', '#edit_request', (e) => {
        const repairItem = e.currentTarget;
        const referenceNumber = $(repairItem).data('reference');
        let referenceType = referenceNumber.substring(0, 3);
        $('#edit_request_modal').modal('show');
        $('#request_queue_number_edit').val(referenceNumber);
        $('.repair_request_edit').val(referenceNumber);
        $('#priority_section').css('display', 'none');
        $('#service_section').css('display', 'none');
        $('#application_name_section').css('display', 'none');
        $('#server_edit').hide();
        $('#user_edit').hide();
        $('#access_revoke_edit').hide();
        $('#server_ip_edit').hide();
        $('#mac_address_edit').hide();
        $('#location_server_edit').hide();
        if (referenceType == 'WRF') {
            $('#request_software_type_edit').show();
            $('#request_date_needed_edit').show();
            $('#request_description_edit').show();
            $('#priority_section').css('display', 'block');
            $('#service_section').css('display', 'block');
            $('#application_name_section').css('display', 'block');
            $('.hide_purpose').css('display', 'none');
            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_edit_request_web',
                    referenceNumber: referenceNumber
                },
                success: function(resWeb) {
                    $('#request_request_type_edit').val('Software');
                    $('#request_software_type_edit').val('Website');
                    $('#request_date_needed_edit').val(resWeb.date_needed);
                    $('#priority').val(resWeb.web_priority);
                    $('#service_type').val(resWeb.service_type);
                    $('#application_name').val(resWeb.application_name);
                    $('#request_description_edit').val(resWeb.req_description);
                    $('#request_description_edit').val(resWeb.req_description);
                    loadEmployeeDepartment(resWeb.prepared_by, "#request_department_edit");
                    loadNotedByEdit();
                    setTimeout(() => {
                        $('#request_approved_by_edit').val(resWeb.approved_by);
                        $('#request_noted_by_edit').val(resWeb.noted_by);
                    }, 1000);
                }
            })
        } else if (referenceType == 'SHR') {
            $('#request_software_type_edit').show();
            $('#request_date_needed_edit').show();
            $('#request_description_edit').show();
            $('#priority_section').css('display', 'none');
            $('#service_section').css('display', 'none');
            $('#application_name_section').css('display', 'none');
            $('.hide_purpose').css('display', 'block');
            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_edit_request',
                    referenceNumber: referenceNumber
                },
                success: res => {
                    $('#request_request_type_edit').val(res.request_type);
                    if (res.request_type == 'Software') {
                        $('#software_section').show();
                        $('#request_software_type_edit').val(res.software_type);
                    } else {
                        $('#software_section').hide();
                    }
                    $('#request_date_needed_edit').val(res.date_needed);
                    $('#request_description_edit').val(res.description);
                    $('#request_purpose_edit').val(res.purpose);
                    loadEmployeeDepartment(res.prepared_by, "#request_department_edit");
                    loadNotedByEdit();
                    setTimeout(() => {
                        $('#request_approved_by_edit').val(res.approved_by);
                        $('#request_noted_by_edit').val(res.noted_by);
                    }, 1000);
                }
            });
        } else if (referenceType == 'SAF') {
            $('#request_software_type_edit').hide();
            $('#request_date_needed_edit').hide();
            $('#request_description_edit').hide();
            $('#server_edit').show();
            $('#user_edit').show();
            $('#access_revoke_edit').show();
            $('#server_ip_edit').show();
            $('#mac_address_edit').show();
            $('#location_server_edit').show();
            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_edit_request_server',
                    referenceNumber: referenceNumber
                },
                success: res => {
                    $('#request_request_type_edit').val('Server');
                    $('#server_edit').val(res.server_ip_address);
                    $('#user_edit').val(res.server_user_name);
                    $('#access_revoke_edit').val(res.server_revoke_access.toString());
                    $('#server_ip_edit').val(res.server_user_ip_address);
                    $('#mac_address_edit').val(res.server_user_mac_address);
                    $('#location_server_edit').val(res.server_user_location);
                    $('#request_purpose_edit').val(res.server_user_purpose);
                    loadEmployeeDepartment(res.prepared_by, "#request_department_edit");
                    loadNotedByEdit();
                    setTimeout(() => {
                        $('#request_approved_by_edit').val(res.approved_by);
                        $('#request_noted_by_edit').val(res.noted_by);
                    }, 1000);
                }
            });
        }
    });

    $('#repair_list').on('click', '#edit_repair_request', (e) => {
        const repairItem = e.currentTarget;
        const referenceNumber = $(repairItem).data('reference');

        $('#edit_repair_modal').modal('show');
        $.ajax({
            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'inputSearch',
                searchVal: referenceNumber
            },
            success: res => {
                clearAttributes();
                $('#repair_queue_number_edit').val(referenceNumber);

                $('#repair_department_edit').val(res.dept_code);
                loadEmployees('#repair_department_edit', '#repair_requested_by_edit');

                $('#repair_area_edit').val(res.area);
                loadLocation('#repair_area_edit', '#repair_location_edit');

                $('#repair_item_edit').val(res.item);

                $('#repair_remarks_edit').val(res.remarks);

                setTimeout(() => {
                    $('#repair_requested_by_edit').val(res.prepared_by);
                    $('#repair_location_edit').val(res.location);
                }, 250);
            }
        });
    });

    function updateEditRequest(queue_number) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to update this request?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let referenceType = queue_number.substring(0, 3);
                if (referenceType == 'WRF') {
                    $.ajax({
                        url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                        type: 'POST',
                        data: {
                            action: 'updateEditRequestWeb',
                            request_date_needed_edit: $('#request_date_needed_edit').val(),
                            priority: $('#priority').val(),
                            service_type: $('#service_type').val(),
                            application_name: $('#application_name').val(),
                            request_description_edit: $('#request_description_edit').val(),
                            request_requested_by_edit: $('#request_requested_by_edit').val(),
                            request_approved_by_edit: $('#request_approved_by_edit').val(),
                            request_noted_by_edit: $('#request_noted_by_edit').val(),
                            queue_number: queue_number
                        }
                    });
                } else if (referenceType == 'SAF') {
                    $.ajax({
                        url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                        type: 'POST',
                        data: {
                            action: 'updateEditRequestServer',
                            server_edit: $('#server_edit').val(),
                            user_edit: $('#user_edit').val(),
                            access_revoke_edit: $('#access_revoke_edit').val(),
                            server_ip_edit: $('#server_ip_edit').val(),
                            mac_address_edit: $('#mac_address_edit').val(),
                            location_server_edit: $('#location_server_edit').val(),
                            request_purpose_edit: $('#request_purpose_edit').val(),
                            request_requested_by_edit: $('#request_requested_by_edit').val(),
                            request_approved_by_edit: $('#request_approved_by_edit').val(),
                            request_noted_by_edit: $('#request_noted_by_edit').val(),
                            queue_number: queue_number
                        }
                    });
                } else {
                    $.ajax({
                        url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                        type: 'POST',
                        data: {
                            action: 'updateEditRequest',
                            request_request_type_edit: $('#request_request_type_edit').val(),
                            request_software_type_edit: $('#request_software_type_edit').val(),
                            request_date_needed_edit: $('#request_date_needed_edit').val(),
                            request_description_edit: $('#request_description_edit').val(),
                            request_purpose_edit: $('#request_purpose_edit').val(),
                            request_requested_by_edit: $('#request_requested_by_edit').val(),
                            request_approved_by_edit: $('#request_approved_by_edit').val(),
                            request_noted_by_edit: $('#request_noted_by_edit').val(),
                            queue_number: queue_number,
                        }
                    });
                }
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Updated Succesfully',
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#edit_request_modal').modal('hide');
            }
        });
    }

    function sendMessage() {
        const messageVal = messageInput.val().trim();
        if (messageVal) {
            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                data: {
                    action: 'messageSend',
                    message: messageVal,
                    sender: logged_user
                },
                // beforeSend: () => messageSend.addClass('disabled'),
            }).done(() => {
                messageInput.val('').focus();
                messageSend.removeClass('disabled');
                $('.message-bubble').last().css('animation', '.25s forwards expand-bounce');
                loadMessages();
                scrollToLastMessage();
            });
        }
    }

    function loadMessages() {
        return $.ajax({
            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadMessages',
                sender: logged_user
            },
        }).done(res => {
            if (res) {
                const message = Object.keys(res)
                    .reverse()
                    .map(key => `<div class="message-bubble" id="${Object.keys(res[key])[0]}">${res[key][Object.keys(res[key])[0]].message}</div>`)
                    .join('');
                messageBody.html(message);
                $('.message-bubble').last().css('animation', '');
                setTimeout(loadMessages, 2000);
            } else if (res == '') {
                messageBody.html('<div class="message-bubble" id="receiver">The IT staff is here to say hello. How can I assist you?</div>');
            }
        });
    }

    function loadMessageSender() {
        $('#message_sender').html(logged_user);
        $.ajax({
            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadMessageSender',
            },
            success: res => {
                if (res) {
                    $('#message_sender').html(generateSelect(res));
                    messageSender.val(activeMessageSender);
                } else {
                    $('#message_sender').html('<option value="" selected>Choose...</option>');
                    messageInput.add(messageSend).prop('disabled', true);
                    messageBody.html('<div class="message-bubble" id="receiver">First, select your request number so you may contact the IT team.</div>');
                }
            }
        })
    }

    function newRequest() {
        $('#submit_request_modal').modal('hide');
        setTimeout(() => {
            if ($('#request_type').val() == 'Software' || $('#request_type').val() == 'Hardware') {
                if ($('#request_software_type').val() == 'Website') {
                    if (formValidation('request_type', 'request_software_type', 'request_date_needed', 'web_priority', 'service_type', 'web_app_name', 'web_description', 'request_department', 'request_requested_by', 'request_approved_by', 'request_noted_by')) {
                        $.ajax({
                            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                action: 'new_web_app',
                                date_needed: $('#request_date_needed').val(),
                                web_priority: $('#web_priority').val(),
                                service_type: $('#service_type').val(),
                                application_name: $('#web_app_name').val(),
                                req_description: $('#web_description').val(),
                                prepared_by: $('#request_requested_by').val(),
                                approved_by: $('#request_approved_by').val(),
                                noted_by: $('#request_noted_by').val()
                            },
                            success: function(res) {
                                $('#queue_modal').modal('show');
                                $('#queue_number').html(res.substring(0, 11));
                                clearAttributes();
                            }
                        });
                    }
                } else {
                    if (formValidation('request_type', 'request_software_type', 'request_date_needed', 'request_item', 'request_description', 'request_purpose', 'request_requested_by', 'request_department', 'request_approved_by', 'request_noted_by')) {
                        $.ajax({
                            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                            type: 'POST',
                            data: {
                                action: 'newRequest',
                                requestType: $('#request_type').val(),
                                softwareType: $('#request_software_type').val(),
                                dateNeeded: $('#request_date_needed').val(),
                                item: $('#request_item').val(),
                                description: $('#request_description').val(),
                                purpose: $('#request_purpose').val(),
                                requestedBy: $('#request_requested_by').val(),
                                approvedBy: $('#request_approved_by').val(),
                                notedBy: $('#request_noted_by').val(),
                                appId: appId
                            },
                            success: function(res) {
                                console.log(res);
                                loadMessageSender();
                                $('#queue_modal').modal('show');
                                $('#queue_number').html(res.substring(0, 11));
                                clearAttributes();
                            }
                        });
                    }
                }
            } else if ($('#request_type').val() == 'Server') {
                if (formValidation('server_ip', 'user_name', 'revoke', 'ip_address', 'mac_address', 'location_server', 'request_purpose')) {
                    $.ajax({
                        url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                        type: 'POST',
                        data: {
                            action: 'new_server_request',
                            server_ip: $('#server_ip').val(),
                            user_name: $('#user_name').val(),
                            revoke: $('#revoke').val(),
                            ip_address: $('#ip_address').val(),
                            mac_address: $('#mac_address').val(),
                            location_server: $('#location_server').val(),
                            request_purpose: $('#request_purpose').val(),
                            requestedBy: $('#request_requested_by').val(),
                            approvedBy: $('#request_approved_by').val(),
                            notedBy: $('#request_noted_by').val(),
                        },
                        success: function(res) {
                            $('#queue_modal').modal('show');
                            $('#queue_number').html(res.substring(0, 11));
                            clearAttributes();
                        }
                    });
                }
            } else if ($('#request_type').val() == 'UserAccess') {
                if (formValidation('request_date_needed', 'purpose')) {
                    $.ajax({
                        url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                        type: 'POST',
                        data: {
                            action: 'new_server_request',
                        },
                        success: function(res) {
                            $('#queue_modal').modal('show');
                            clearAttributes();
                        }
                    });
                }
            }
        }, 500);
    }

    function newRepair() {
        $('#submit_repair_modal').modal('hide');
        setTimeout(() => {
            if (formValidation('item', 'area', 'location', 'deptCode', 'requested_by', 'remarks')) {
                $.ajax({
                    url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                    type: 'POST',
                    data: {
                        dept_code: $('#deptCode').val(),
                        action: 'newRepair',
                        item: $('#item').val(),
                        area: $('#area').val(),
                        location: $('#location').val(),
                        requested_by: $('#requested_by').val(),
                        remarks: $('#remarks').val(),
                        appId: appId
                    },
                    success: function(res) {
                        loadMessageSender();
                        $('#queue_modal').modal('show');
                        $('#queue_number').html(res.substring(0, 11));
                        clearAttributes();
                    }
                });
            }
        }, 500);
    }

    function loadQueueList() {
        $.ajax({
            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadQueueList',
                logged_user: logged_user
            },
            success: function(res) {
                $('#repair_list').html(generateList(res.repair));
                $('#request_list').html(generateList(res.request));
                $('#request_list').append(generateList(res.web_request));
                $('#request_list').append(generateList(res.server_request));
            }
        });
    }

    function loadNowRepairingOngoingRequest() {
        $.ajax({
            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadNowRepairingOngoingRequest'
            },
            success: function(res) {
                $('#now_repairing').html(res.repair[repairIndex] ?? "XXX-XXXX-XX");
                $('#ongoing_request').html(res.request[requestIndex] ?? "XXX-XXXX-XX");
                repairIndex = (repairIndex + 1) % res.repair.length;
                requestIndex = (requestIndex + 1) % res.request.length;
            },
            error: function() {
                setTimeout(loadNowRepairingOngoingRequest, 5000);
            }
        });
    }

    function userDetails() {
        $.ajax({
            url: '../controller/itrepair_request_controller/itrepair_request.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'userDetailsFunction',
                logged_user: logged_user
            },
            success: function(result) {
                $('#deptCode').val(result.dept_code);
                $('#department').val(result.department);
                $('#request_department').val(result.department);
                $('#request_requested_by').val(result.fullname);
                $('#requested_by').val(result.fullname);
            }
        })
    }

    search.on('input', e => {
        const searchVal = search.val().trim();
        if (searchVal.length >= 3) {
            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'liveSearch',
                    searchVal: searchVal
                },
                beforeSend: () => $('.search-loading').show(),
                success: res => {
                    $('.search-loading').hide();
                    if (res.status == 'success') {
                        let result = res.queueNumber.map(value => `<li>${value}</li>`).join('');
                        resultList.html(`<ul>${result}</ul>`).show();
                    } else resultList.html(`<div class="text-center">${res.message}</div>`).show();
                }
            });
        } else {
            resultList.html(``).hide()
        };
    });
    searchBtn.click(() => {
        if (search.val().trim() != '') {

            $.ajax({
                url: '../controller/itrepair_request_controller/itrepair_request.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'inputSearch',
                    searchVal: search.val()
                },
                success: function(data) {
                    console.log(data.prepared_by);
                    if (data != null) {
                        let bgStatus = {
                            'On Hold': 'bg-danger',
                            'Pending': 'bg-warning',
                            'Ongoing': 'bg-success'
                        }
                        if (data.type == 'ITR') {
                            $('#track_status').removeClass('bg-danger bg-warning bg-success').addClass(bgStatus[data.status]).text(data.status);
                            $('#track_queue_number').text(data.queue_number);
                            $('#track_requested_by').text(data.prepared_by);
                            $('#track_area').text(data.area);
                            $('#track_location').text(data.location);
                            $('#track_item').text(data.item);
                            $('#track_date_requested').text(data.date_requested);
                            $('#track_remarks').text(data.remarks);
                            $('#search_modal').modal('show');
                            search.val(``);
                            resultList.html(``)
                        } else if (data.type == 'SHR') {
                            $('#track_shr_status').removeClass('bg-danger bg-warning bg-success').addClass(bgStatus[data.status]).text(data.status);
                            $('#track_shr_queue_number').text(data.queue_number);
                            $('#track_shr_requested_by').text(data.requested_by);
                            $('#track_shr_request_type').text(data.request_type);
                            $('#track_shr_item').text(data.item);
                            $('#track_shr_description').text(data.description);
                            $('#track_shr_purpose').text(data.purpose);
                            $('#track_shr_date_requested').text(data.date_requested);
                            $('#track_shr_date_needed').text(data.date_needed);
                            $('#search_shr_modal').modal('show');
                            search.val(``);
                            resultList.html(``)
                        } else if (data.type == 'WRF') {
                            $('#track_wfr_status').removeClass('bg-danger bg-warning bg-success').addClass(bgStatus[data.status]).text(data.status);
                            $('#track_wrf_queue_number').text(data.control_no);
                            $('#track_wrf_requested_by').text(data.prepared_by);
                            $('#track_wrf_request_type').text('Software');
                            $('#track_wrf_priority').text(data.web_priority);
                            $('#track_wrf_service_type').text(data.service_type);
                            $('#track_wrf_description').text(data.req_description);
                            $('#track_wrf_application_name').text(data.application_name);
                            $('#track_wrf_date_requested').text(data.date_requested);
                            $('#track_wrf_date_needed').text(data.date_needed);
                            $('#search_wfr_modal').modal('show');
                            search.val(``);
                            resultList.html(``)
                        }
                    } else {
                        $('#search').focus();
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Invalid Input',
                            html: '<p>No matching record found, please try again.</p><p>Example: <b>ITR-0001-23</b></p>',
                            showConfirmButton: false,
                            timer: 3000

                        });
                    }
                }
            });
        }
    });

    // ?=====FUNTION TO MIGRATE TO OOP ////

    // * ~ Message Behavior ~
    $('.floating-message-btn').click(() => {
        $('.floating-message-btn').hide();
        $('.message-card').slideDown();
    });

    $('.message-close').click(() => {
        $('.floating-message-btn').fadeIn(2000);
        $('.message-card').slideUp();
    });
    var selectElement = document.getElementById("request_type");
    var optionToRemove = selectElement.querySelector("option[value='Server']");
    if (user_department == 'ITD' || user_department == 'ISD') {
        optionToRemove.style.display = "display";
    } else {
        optionToRemove.style.display = "none";
    }

    $('#server_ip_section').hide();
    $('#user_section').hide();
    $('#location_section').hide();
    $('#location_server_section').hide();
    // * ~ Request type field behavior ~
    $('#software_type_section').hide();
    $('#request_type').change(function() {
        if ($(this).val() == "Software") {
            $('#software_type_section').show().find('select').val('');
            $('#description_app_subs_section').show();
            $('#date_needed').show();
            $('#item_app_subs_section').show();
            $('#server_ip_section').hide();
            $('#user_section').hide();
            $('#location_section').hide();
            $('#location_server_section').hide();
            $('#purpose_section').show();
            $('#department_section').show();

            $('#access_control_number').hide();
            $('#priority_urgent_section').hide();
            $('.request_section').hide();

            $('.btn-save').hide();
            $('.btn-submit').show();

            userDetails();
        } else if ($(this).val() == "Hardware") {
            $('#software_type_section').hide().find('select').val('-');
            $('#description_app_subs_section').show();
            $('#item_app_subs_section').show();
            $('#date_needed').show();
            $('#server_ip_section').hide();
            $('#user_section').hide();
            $('#location_section').hide();
            $('#location_server_section').hide();
            $('#purpose_section').show();
            $('#department_section').show();

            $('#access_control_number').hide();
            $('#priority_urgent_section').hide();
            $('.request_section').hide();

            $('.btn-save').hide();
            $('.btn-submit').show();

            userDetails();
        } else if ($(this).val() == "Server") {
            $('#server_ip_section').show().find('select').val('');
            $('#user_section').show();
            $('#location_section').show();
            $('#date_needed').hide();
            $('#item_app_subs_section').hide();
            $('#software_type_section').hide();
            $('#description_app_subs_section').hide();
            $('#location_server_section').show();
            $('#department_section').show();
            $('#purpose_section').show();

            $('#access_control_number').hide();
            $('#priority_urgent_section').hide();
            $('.request_section').hide();

            $('.btn-save').hide();
            $('.btn-submit').show();

            userDetails();
        } else if ($(this).val() == "UserAccess") {
            $('#software_type_section').hide().find('select').val('');
            $('#description_app_subs_section').hide();
            $('#date_needed').show();
            $('#item_app_subs_section').hide();
            $('#server_ip_section').hide();
            $('#user_section').hide();
            $('#location_section').hide();
            $('#purpose_section').hide();
            $('#department_section').hide();
            $('#location_server_section').hide();

            $('#access_control_number').show();
            $('#priority_urgent_section').show();
            $('.request_section').show();

            $('.btn-save').show();
            $('.btn-submit').hide();
            userDetails();
        }
        // $(this).val() == "Software" ? $('#software_type_section').show().find('select').val('') : $('#software_type_section').hide().find('select').val('-');
    });

    // * ~ Software type field behavior ~
    $('#website_section').hide();
    $('#request_software_type').change(function() {
        if ($(this).val() == "Website") {
            $('#app_subs_section').hide();
            $('#website_section').show();
            $('#request_noted_by').val('Esperidion Castro');
        } else {
            $('#app_subs_section').show();
            $('#website_section').hide();
            $('#request_noted_by').val('Oliver Razalan');
        }
    });

    // ? TEXT COUNTER IN PURPOSE
    $('#request_purpose').keyup(function() {
        var characterCount = $(this).val().length,
            current = $('#current')
        current.text(characterCount);
        if (characterCount >= 50) {
            $('#current').addClass('text-danger');
            $('#maximum').addClass('text-danger');
        } else if (characterCount < 50) {
            $('#current').removeClass('text-danger');
            $('#maximum').removeClass('text-danger');
        }
    });

    //* ~ Form validation function ~
    function formValidation(...args) {
        let validated = true;
        $.each(args, function(i, e) {
            let element = $(`#${e}`);
            if (element.val().trim() == '') {
                invalidField(e, 'Field is required.');
                validated = false;
            } else {
                validField(e);
            }
        });
        return validated;
    }

    //* ~ Validation Error ~
    function invalidField(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    //* ~ Validation Success ~
    function validField(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }

    //* ~ Reset ~
    function clearAttributes() {
        $('#request_date_needed').val(currentDate).prop('min', currentDate);
        $('#request_noted_by').val('Oliver Razalan');
        $('textarea, input').removeClass('is-invalid is-valid').val('');
        $('input').removeClass('is-invalid is-valid');
        $('#location, #requested_by, #request_requested_by').prop('disabled', true);

        $('input').prop('checked', false);
        $('#access1').prop('checked', true);
        $('#priority1').prop('checked', true);
        $('#mail_account_input').prop('disabled', true);
        $('#file_storage_access_input').prop('disabled', true);
        $('#in_house_access_input').prop('disabled', true);
        $('.btn-update').hide();
        $('#generatePdf').hide();
        $('#control_no').find('option:first').prop('selected', 'selected');
        $('#purpose').val('');
        $('#mail_account_input').val('');
        $('#file_storage_access_input').val('');
        $('#in_house_access_input').val('');

        $('#request_date_needed').val(currentDate).prop('min', currentDate);
        $('#date_request').val(currentDate).prop('min', currentDate);
        userDetails();
        loadNotedBy();
    }
</script>
<script>
    hideCard();

    function showCard() {
        $('.app-card').slideDown();
        $('.app-circle-btn').fadeOut();
        $('.message-section').fadeOut();
    }

    function hideCard() {
        $('.app-card').slideUp();
        $('.app-circle-btn').fadeIn();
        $('.message-section').fadeIn();
    }
</script>