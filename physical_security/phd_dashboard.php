<?php include './../includes/header.php';
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

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
$chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC);
$chkAppId = $chkAppIdRow['app_id'];

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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-physical">Dashboard</span>
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
<?php include './../includes/footer.php'; ?>
<script>
</script>
</body>
<html>