<?php include './../includes/header.php';
session_start();
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
                <span class="page-title-infosec">Web Application Request</span>
            </div>
            <!-- content section -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow active" onclick="loadTableNavigation('Pending')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-warning fw-bold">FOR APPROVAL</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-regular fa-hourglass-half fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Ongoing')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-primary fw-bold">IN PROCESS</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-spinner fa-spin"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-info shadow" onclick="loadTableNavigation('For Received')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-info fw-bold">FOR RECEIVED</span>
                                    <div class="fs-2 fw-bold" id="received_count"></div>
                                </div>
                                <div class="fs-1 text-info"><i class="fa-solid fa-envelope fa-bounce"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-success shadow" onclick="loadTableNavigation('Done')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-success fw-bold">ACCOMPLISHED</span>
                                    <div class="fs-2 fw-bold" id="accomplish_count"></div>
                                </div>
                                <div class="fs-1 text-success"><i class="fa-solid fa-circle-check fa-bounce"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header card-9 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-light" id="active_request"></h4>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ==================== Pending Request Table ==================== -->
                    <div class="table-responsive" id="pending_table">
                        <table id="pending_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== On Going Request Table ==================== -->
                    <div class="table-responsive" id="ongoing_table">
                        <table id="ongoing_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== For Received  Request Table ==================== -->
                    <div class="table-responsive" id="received_table">
                        <table id="for_received_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- ==================== Done Request Table ==================== -->
                    <div class="table-responsive" id="done_table">
                        <table id="done_request_table" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tfoot class="card-9 text-light">
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">DATE NEEDED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">APPLICATION NAME</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PRIORITY</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
            <!-- =============== Job Done Modal =============== -->
            <div class="modal fade" id="webRequestDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-9">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col" id="details_status"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="details_date_requested" disabled></input>
                                        <label for="details_date_requested" class="col-form-label fw-bold">Date Requested:</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="details_date_needed" disabled></input>
                                        <label for="details_date_needed" class="col-form-label fw-bold">Date Needed:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_priority" disabled></input>
                                <label for="details_priority" class="col-form-label fw-bold">Priority:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_service_type" disabled></input>
                                <label for="details_service_type" class="col-form-label fw-bold">Service Request Type:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_app_name" disabled></input>
                                <label for="details_app_name" class="col-form-label fw-bold">Application Name:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea id="details_description" class="form-control fw-bold" style="resize:none;height: 150px" disabled></textarea>
                                <label for="details_description" class="col-form-label fw-bold">Description:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_prepared_by" disabled></input>
                                <label for="details_prepared_by" class="col-form-label fw-bold">Prepared by:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_approved_by" disabled></input>
                                <label for="details_approved_by" class="col-form-label fw-bold">Approved by:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_received_by" disabled></input>
                                <label for="details_received_by" class="col-form-label fw-bold">Received by:</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control fw-bold" id="details_noted_by" disabled></input>
                                <label for="details_noted_by" class="col-form-label fw-bold">Noted by:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-danger btnArchiveClose" data-bs-dismiss="modal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
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
<script>
    //* Card header name of active table
    let activeRequestList = {
        'Pending': 'Pending',
        'Ongoing': 'Ongoing',
        'For Received': 'For Received',
        'Done': 'Accomplished'
    }
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    loadTableRequest('Pending', 'pending_request_table');
    loadTableRequest('Ongoing', 'ongoing_request_table');
    loadTableRequest('For Received', 'for_received_table');
    loadTableRequest('Done', 'done_request_table');
    loadTableNavigation('Pending')
    loadRequestCount();


    function loadTableNavigation(statusVal) {
        $('#active_request').text(`${activeRequestList[statusVal] ?? 'Pending'} Request`);
        switch (statusVal) {
            case 'Pending':
                $('#pending_table').show();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'Ongoing':
                $('#pending_table').hide();
                $('#ongoing_table').show();
                $('#received_table').hide();
                $('#done_table').hide();
                break;

            case 'For Received':
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').show();
                $('#done_table').hide();
                break;

            default:
                $('#pending_table').hide();
                $('#ongoing_table').hide();
                $('#received_table').hide();
                $('#done_table').show();
                break;
        }
    }

    function loadTableRequest(statusVal, inTable) {
        inTable = $('#' + inTable).DataTable({
            'responsive': true,
            'autoWidth': false,
            'serverSide': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/info_sec_controller/info_sec_web_app_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_table_request',
                    statusVal: statusVal
                }
            },
            'columnDefs': [{
                targets: [0, 2, 3],
                className: 'dt-body-middle-center'
            }, {
                targets: [1, 4, 5, 6, 7],
                className: 'dt-body-middle-left'
            }, {
                targets: 8,
                orderable: false,
                className: 'dt-nowrap-center',
                render: function(data, type, row, meta) {
                    let btnAction = '';
                    switch (data[1]) {
                        case 'Ongoing':
                            btnAction = `<button class="btn btn-success col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Accomplish" id="btn_acknowledge" onclick="accomplishWebRequest('${data[0]}');"><i class="fa-solid fa-circle-check fa-shake"></i></button>`;
                            break;
                        case 'For Received':
                            break;
                        case 'Done':
                            btnAction += `<button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print" id="btn_print" onclick="printRequest('${data[0]}')"><i class="fa-solid fa-file-pdf fa-flip"></i></button>`;
                            break;
                        default:
                            if (data[3] == '<?php echo $_SESSION['fullname']; ?>') {
                                btnAction += `<button class="btn btn-primary col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Process" id="btn_process" onclick="processRequest('${data[0]}')" ${data[2] == false ? 'disabled' : ''}>${data[2] == false ? '<i class="fa-solid fa-spinner" style="color: #0ae6ba;"></i>' : '<i class="fa-solid fa-spinner fa-spin"></i>'}</button>`;
                            }
                            break;
                    }
                    btnAction += ` <button class="btn btn-info col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" id="btn_print" onclick="viewRequest('${data[0]}')"><i class="fa-solid fa-circle-info fa-beat"></i></button>`;
                    return btnAction;
                }
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
            inTable.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadRequestCount() {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_web_app_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_request_count'
            },
            success: (res) => {
                $('#pending_count').text(res['Pending'] ?? 0); //? For Approval
                $('#ongoing_count').text(res['Ongoing'] ?? 0); //? In Process
                $('#received_count').text(res['For Received'] ?? 0); //? For Received
                $('#accomplish_count').text(res['Done'] ?? 0); //? Accomplish
                setTimeout(loadRequestCount, 1000);
            }
        });
    }

    function acknowledgeWebRequest(webappid) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_web_app_contr.class.php',
            type: 'POST',
            data: {
                action: 'acknowledge_request',
                webappid: webappid,
                logged_user: logged_user
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Web Request Acknowledged.',
                    text: '',
                    showConfirmButton: false,
                    timer: 800
                });
                $('#pending_request_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function processRequest(webappid) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_web_app_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'process_request',
                webappid: webappid,
                logged_user: logged_user
            },
            success: result => {
                if (result != 'same') {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Cant Process Request. Not the same Receiver.',
                        text: '',
                        showConfirmButton: false,
                        timer: 800
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Web Request In Process.',
                        text: '',
                        showConfirmButton: false,
                        timer: 800
                    });
                    $('#pending_request_table').DataTable().ajax.reload(null, false);
                }
            }
        });
    }

    function accomplishWebRequest(webappid) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_web_app_contr.class.php',
            type: 'POST',
            data: {
                action: 'accomplish_request',
                logged_user: logged_user,
                webappid: webappid
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Web Request Accomplished.',
                    text: '',
                    showConfirmButton: false,
                    timer: 800
                });
                $('#ongoing_request_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function printRequest(webappid) {
        window.open(`info_sec_web_request_pdf.php?id=${webappid}`, '_blank');
    }

    function viewRequest(webappid) {
        $('#webRequestDetailsModal').modal('show');
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_web_app_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_request_details',
                webappid: webappid
            },
            success: result => {
                $('#details_date_requested').val(result.date_requested);
                $('#details_date_needed').val(result.date_needed);
                $('#details_priority').val(result.web_priority);
                $('#details_service_type').val(result.service_type);
                $('#details_app_name').val(result.application_name);
                $('#details_description').val(result.req_description);
                $('#details_prepared_by').val(result.prepared_by);
                $('#details_approved_by').val(result.approved_by);
                $('#details_received_by').val(result.received_by);
                $('#details_noted_by').val(result.noted_by);
                $('#details_status').html(result.web_status);
            }
        });
    }
</script>
</body>
<html>