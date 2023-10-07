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
            <div class="row mb-4">
                <span class="page-title-infosec">SFTP File Retention</span>
            </div>
            <!-- content section -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-success shadow active" id="card_receive" onclick="loadTableNavigation('Received')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-success fw-bold">FILE RECEIVED</span>
                                    <div class="fs-2 fw-bold" id="file_received_count"></div>
                                </div>
                                <div class="fs-1 text-success"><i class="fa-solid fa-file-invoice fa-beat"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow" id="card_deleted" onclick="loadTableNavigation('Deleted')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-danger fw-bold">FILE DELETED</span>
                                    <div class="fs-2 fw-bold" id="file_deleted_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-file-circle-xmark fa-bounce"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header card-9 py-3">
                    <div class="row">
                        <div class="col-sm-10">
                            <h4 class="fw-bold text-light" id="sftp_table_title"></h4>
                        </div>
                        <div class="col-sm">
                            <div class="row">
                                <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="updateSftpRecord();"><i class="fa-solid fa-retweet p-r-8"></i> Update Record</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="pending_table">
                        <table id="sftp_file_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderInfoSec">
                                <tr>
                                    <th class="text-center">Date Received</th>
                                    <th>Company</th>
                                    <th>File name</th>
                                    <th class="text-center">File size</th>
                                    <th class="text-center">Retention Count</th>
                                    <th class="text-center">Date Deleted</th>
                                </tr>
                            </thead>
                            <tfoot class="customHeaderInfoSec">
                                <tr>
                                    <th class="text-center">Date Received</th>
                                    <th>Company</th>
                                    <th>File name</th>
                                    <th class="text-center">File size</th>
                                    <th class="text-center">Retention Count</th>
                                    <th class="text-center">Date Deleted</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- =============== Update SFTP Record Modal =============== -->
            <div class="modal fade" id="updateSftpRecordModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-9">
                            <h4 class="modal-title text-uppercase fw-bold text-light">UPDATE SFTP RECORD</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="date" class="form-control fw-bold" id="sftp_filter_date">
                                <div class="invalid-feedback"></div>
                                <label for="sftp_filter_date" class="col-form-label fw-bold">Filter Date:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdateSftpRecord" onclick="updateRecord();">Update</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
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
<?php include './../includes/footer.php';
include './../helper/input_validation.php'; ?>
<script>
    loadReceivedDeletedCount('Received');
    loadReceivedDeletedCount('Deleted');
    loadTableNavigation('Received');

    function loadTableNavigation(inCategory) {
        $('#sftp_table_title').html(inCategory + ' File List');
        var inTable = $('#sftp_file_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'destroy': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/info_sec_controller/info_sec_sftp_file_retention_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_sftp_file_table_data',
                    inCategory: inCategory
                }
            },
            'columnDefs': [{
                targets: [0, 5],
                className: 'dt-body-middle-center',
                width: '17%'
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '13%'
            }, {
                targets: [3, 4],
                className: 'dt-body-middle-center',
                width: '10%'
            }]
        });
    }

    function loadReceivedDeletedCount(inCategory) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_sftp_file_retention_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_received_deleted_count',
                inCategory: inCategory
            },
            success: result => {
                if (inCategory == 'Received') {
                    $('#file_received_count').text(result);
                } else {
                    $('#file_deleted_count').text(result);
                }
            }
        });
    }

    function updateSftpRecord() {
        var dateToday = new Date().toISOString().slice(0, 10);
        $('#updateSftpRecordModal').modal('show');
        $('#sftp_filter_date').val(dateToday);
    }

    function updateRecord() {
        if (inputValidation('sftp_filter_date')) {
            $.ajax({
                url: '../controller/info_sec_controller/info_sec_sftp_file_retention_contr.php',
                type: 'POST',
                data: {
                    action: 'fetch_email',
                    filter_date: $('#sftp_filter_date').val()
                },
                beforeSend: function() {
                    $('#updateSftpRecordModal').modal('hide');
                    Swal.fire({
                        position: 'center',
                        html: '<div class="mb-3"><img src="../vendor/images/loading_gif.gif"/></div><div><span class="fw-bold">Please wait while record is updating.</span></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                },
                success: result => {
                    //* Insert Into Perso File Certification Database 
                    $.ajax({
                        url: '../controller/info_sec_controller/info_sec_sftp_file_retention_contr.php',
                        type: 'POST',
                        data: {
                            action: 'insert_perso_file_deletion',
                            filter_date: $('#sftp_filter_date').val()
                        },
                        success: data => {
                            loadReceivedDeletedCount('Received');
                            loadReceivedDeletedCount('Deleted');
                        }
                    });
                    //* Update File Retention Count
                    $.ajax({
                        url: '../controller/info_sec_controller/info_sec_sftp_file_retention_contr.php',
                        type: 'POST',
                        data: {
                            action: 'update_file_retention'
                        },
                        success: result => {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Record Successfully Updated!',
                                text: '',
                                showConfirmButton: true,
                            });
                            $('#card_receive').addClass('active');
                            $('#card_deleted').removeClass('active');
                            loadTableNavigation('Received');
                            clearAttributes();
                        }
                    });
                }
            });
        }
    }

    function clearValues() {
        $('input').val('');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>