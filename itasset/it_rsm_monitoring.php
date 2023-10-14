<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    // $data_base64 = base64_encode($sqlstring);
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    // $json_response = curl_exec($curl);
    // //* ====== Close Connection ======
    // curl_close($curl);
    // // * ======== Prepare Array ========
    // $data_result = json_decode($json_response, true);
    // foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom right, #fa3c3c, #aa0000);
        border-radius: 100vw;
    }
</style>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it">RSM Monitoring</span>
            </div>
            <!-- ==================== CONTENT SECTION ==================== -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow active" onclick="loadTableNavigation('All')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-danger fw-bold">All</span>
                                    <div class="fs-2 fw-bold" id="all_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-regular fa-file-lines"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Ongoing')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-primary fw-bold">ONGOING</span>
                                    <div class="fs-2 fw-bold" id="on_going_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-regular fa-hourglass-half fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-success shadow" onclick="loadTableNavigation('Finished')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-success fw-bold">FINISHED</span>
                                    <div class="fs-2 fw-bold" id="finish_count"></div>
                                </div>
                                <div class="fs-1 text-success"><i class="fa-solid fa-circle-check fa-fade " style="--fa-animation-duration: 2s; --fa-fade-opacity: 0.6;"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header card-1 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-light" id="active_request"></h4>
                        <div>
                            <button class="btn btn-warning fw-bold" id="monthly_report"><i class="fa-solid fa-calendar-days me-2"></i>Monthly Report</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="all_table">
                        <table id="all_rsm_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">RSM NO.</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">RSM QTY</th>
                                    <th class="text-center">PURCHASE MEASURE</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">RSM NO.</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">RSM QTY</th>
                                    <th class="text-center">PURCHASE MEASURE</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive" id="on_going_table">
                        <table id="on_going_rsm_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">RSM NO.</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">RSM QTY</th>
                                    <th class="text-center">PURCHASE MEASURE</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">RSM NO.</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">RSM QTY</th>
                                    <th class="text-center">PURCHASE MEASURE</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive" id="finish_table">
                        <table id="finish_rsm_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">RSM NO.</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">RSM QTY</th>
                                    <th class="text-center">PURCHASE MEASURE</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderItAsset">
                                <tr>
                                    <th class="text-center">RSM NO.</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">CODE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">RSM QTY</th>
                                    <th class="text-center">PURCHASE MEASURE</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ==================== CONTENT SECTION END ==================== -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-danger rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
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
        <!-- =============== Generate Report Modal =============== -->
        <div class="modal fade" id="generateReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header card-1">
                        <h5 class="modal-title fw-bold text-light" id="generateReportModalLabel">Generate Report</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-2">
                            <input type="month" class="form-control fw-bold" id="date_report_month" name="date_report_month">
                            <div class="invalid-feedback"></div>
                            <label for="date_report_month" class="fw-bolder">Date:</label>
                        </div>
                    </div>
                    <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                        <button type="button" class="btn btn-dark col-sm" id="generate_report_submit"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                        <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    var logged_name = '<?php echo $_SESSION['fullname']; ?>';
    var access_lvl = '<?php echo $_SESSION['access_lvl']; ?>';
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var autoFillmonth = currentDate.getMonth() + 1;
    var formattedDate = year + '-' + (autoFillmonth < 10 ? '0' : '') + autoFillmonth;
    $('#monthly_report').click(() => {
        $('#generateReportModal').modal('show');
        document.getElementById('date_report_month').value = formattedDate;
        let month = $('#date_report_month');
        month.change(() => {
            end.attr('min', month.val());
            end.prop('disabled', !month.val());
        });
        $('#generate_report_submit').click(() => {
            if (month.val() == '') {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill out all required fields.',
                    showConfirmButton: false,
                    timer: 800
                });
            } else {
                $('#generateReportModal').modal('hide');
                window.open(`it_rsm_monitoring_pdf.php?month=${month.val()}`, '_blank')
            }
        });
    });
    //* Card header name of active table
    let activeRequestList = {
        'All': 'All',
        'Ongoing': 'On Going',
        'Finished': 'Finished'
    }
    loadTableRsm('All', 'all_rsm_table');
    loadTableRsm('Ongoing', 'on_going_rsm_table');
    loadTableRsm('Finished', 'finish_rsm_table');
    loadTableNavigation('All')
    loadRsmCount();


    function loadTableNavigation(statusVal) {
        if (statusVal == 'All') {
            $('#filter').show();
        } else {
            $('#filter').hide();
        }
        $('#active_request').text(`${activeRequestList[statusVal] ?? 'All'} Request`);
        switch (statusVal) {
            case 'All':
                $('#all_table').show();
                $('#on_going_table').hide();
                $('#finish_table').hide();
                break;
            case 'Ongoing':
                $('#all_table').hide();
                $('#on_going_table').show();
                $('#finish_table').hide();
                break;
            case 'Finished':
                $('#all_table').hide();
                $('#on_going_table').hide();
                $('#finish_table').show();
                break;
            default:
                $('#all_table').show();
                $('#on_going_table').hide();
                $('#finish_table').hide();
                break;
        }
    }

    function loadTableRsm(statusVal, table) {
        let inTable = $('#' + table).DataTable({
            'responsive': true,
            'autoWidth': false,
            'serverSide': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/itasset_controller/it_rsm_monitoring_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_table_rsm',
                    statusVal: statusVal
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '15%'
            }, {
                targets: 1,
                className: 'dt-body-middle-center',
                width: '15%'
            }, {
                targets: [4, 5],
                className: 'dt-body-middle-center',
                width: '5%'
            }, {
                targets: [2, 3, 6],
                className: 'dt-body-middle-left',
                width: '21.6%'
            }]
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
            inTable.ajax.reload(null, false); //* ======= Reload Table Data Every X seconds with pagination retained =======
        }, 5000);
    }

    function loadRsmCount() {
        $.ajax({
            url: '../controller/itasset_controller/it_rsm_monitoring_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_rsm_count'
            },
            success: (res) => {
                $('#all_count').text(res['all']);
                $('#on_going_count').text(res['ongoing']);
                $('#finish_count').text(res['finished']);
                setTimeout(loadRsmCount, 1500);
            }
        });
    }

    function clearValues() {
        $('input').val('');
        $('textarea').val('');
        $('#filterModal').modal('hide');
    }
</script>