<?php include './../includes/header.php';
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
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: linear-gradient(to bottom right, #1100ff -18.72%, #ff0000 120.42%);
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <div class="row">
                <span class="page-title-infosec">Firewall Email Notifications</span>
            </div>
            <!-- content section -->

            <div class="row row-cols-1 row-cols-sm-3 mt-4">
                <!-- PortScan Detected Card -->
                <div class="col mb-3">
                    <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">PortScan Detected</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="printing_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-brands fa-hubspot fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Electric Fence Card -->
                <div class="col mb-3">
                    <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">New Pattern Up2Dates Installed</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="embossing_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-list-check fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Emergency Evaluation Switch Card -->
                <div class="col mb-3">
                    <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Log Disk is filling up - please check</div>
                                    <div class="h4 mb-0 fw-bold text-gray-800" id="packaging_count"></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-hard-drive fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 mt-3">
                <!-- Interlocking RUD Card -->
                <div class="col mb-3">
                    <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Intrussion Prevention Alert (Packet dropped)</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-shield fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Electric Fence Card -->
                <div class="col mb-3">
                    <div class="card border-left-primary shadow h-100 py-2 card-body-hover-pointer">
                        <div class="card-body" onclick="">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="fs-20 fw-bold text-dark text-uppercase mb-1">Advanced Threat Protection Alert</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-shield-virus fa-bounce fa-3x text-gray-300" style="--fa-animation-duration: 3s;"></i>
                                </div>
                            </div>
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
            <div class="card card-9 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">INFO SECURITY</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script></script>
</body>
<html>