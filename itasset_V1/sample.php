<?php include './../includes/header.php';

$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_menu_id FROM bpi_main_menu WHERE menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWeb->prepare($queryCheckApp);
$stmtCheckApp->execute();
$chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC);
$chkAppId = $chkAppIdRow['app_menu_id'];

if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>


<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />

<!-- Insert your code here -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-5" style="max-height: 100vh;">
            <h1 class="text-danger text-center fw-bold">Dashboard</h1>
            <button class="btn btn-warning mb-2" onclick="newEntry()"><i class="fa-solid fa-plus"></i></button>

            <table class="table table-striped">
                <thead class=" table-warning">
                    <tr>
                        <td>#</td>
                        <td>Particular</td>
                        <td>Action</td>
                        <td>Time</td>
                        <td>Time</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn card-1 text-light rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <!-- ==================== CARD SECTION ==================== -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-1 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
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
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    var index = 1;

    function newEntry() {
        let row = `<tr>
                    <td>${index}</td>
                    <td>
                        <select class="form-select" id="particular${index}">
                        <option value="">Choose...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select" id="action${index}" onchange="generateTime(${index})">
                            <option value="">Choose...</option>
                            <option value="a">a</option>
                            <option value="b">b</option>
                            <option value="c">c</option>
                            <option value="d">d</option>
                            <option value="e">e</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control-plaintext" id="time${index}" placeholder="Time:" readonly>
                    </td>
                    <td>
                        <button class="btn btn-danger" id="delete_row"><i class="fa-solid fa-trash"></i></button>
                    </td>
                   </tr>`;

        $('tbody').append(row)
        index++;
    }

    function generateTime(index) {
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        $(`#time${index}`).val(`${hours}:${minutes}`);
    }

    $('tbody').on('click', '#delete_row', function() {
        $(this).closest('tr').remove();
    });
</script>