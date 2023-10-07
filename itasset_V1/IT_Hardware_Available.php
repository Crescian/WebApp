<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
} 
?>
<style>
    .app-card-wrapper {
        width: 450px;
        top: 25px;
        right: 10px;
    }

    @media only screen and (max-width: 480px) {
        .app-card-wrapper {
            width: 90%;
        }
    }

    .app-circle-btn-wrapper {
        bottom: 25px;
        right: 10px;
    }

    .app-circle-bars {
        padding: 10px;
        font-size: 20px;
    }
</style>

<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />

<!-- Insert your code here -->
<div class="container-fluid">

    <!-- ==================== CONTENT SECTION ==================== -->























    <!-- ==================== CONTENT SECTION END ==================== -->


    <!-- ==================== CARD SECTION ==================== -->
    <div class="position-absolute app-card-wrapper">
        <div class="card card-1 border-0 shadow app-card">
            <div class="d-flex justify-content-between justify-content-md-between mt-1 me-3 align-items-center">
                <button class="btn text-white fs-2" onclick="hideCard();"><i class="fa-solid fa-bars"></i></button>
                <a href="../Landing_Page.php" class="text-white fs-2">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
            <div class="position-absolute app-title-wrapper">
                <span class="fw-bold app-title text-nowrap">IT ASSET</span>
            </div>
            <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
            </div>
        </div>
    </div>
    <!-- ==================== CARD SECTION END ==================== -->

    <!-- ==================== CARD BUTTON SECTION ==================== -->
    <div class="position-absolute app-circle-btn-wrapper">
        <button class="btn btn-danger rounded-circle app-circle-btn" onclick="showCard();"><i class="fa-solid fa-bars app-circle-bars"></i></button>
    </div>
    <!-- ==================== CARD BUTTON SECTION END ==================== -->
</div>


<?php include './../includes/footer.php'; ?>
<script>
    hideCard();

    function showCard() {
        $('.app-card').fadeIn('fast');
        $('.app-circle-btn').fadeOut();
    }

    function hideCard() {
        $('.app-card').slideUp();
        $('.app-circle-btn').fadeIn();
    }
</script>