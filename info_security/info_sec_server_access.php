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
                <span class="page-title-infosec">Server Access/Revoke Request</span>
            </div>
            <!-- content section -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow active" onclick="loadTableNavigation('Access')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-primary fw-bold">GRANTED ACCESS</span>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-user fa-beat"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow" onclick="loadTableNavigation('Revoke')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-danger fw-bold">REVOKED ACCESS</span>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-user-slash fa-flip"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="access_container">
                <div class="row mt-5">
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-warning shadow" id="pending_card" onclick="loadTableAccessRevokeData('Pending','access')">
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
                        <div class="card card_hover border-0 border-left-primary shadow" id="ongoing_card" onclick="loadTableAccessRevokeData('Ongoing','access')">
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
                        <div class="card card_hover border-0 border-left-info shadow" id="received_card" onclick="loadTableAccessRevokeData('For Received','access')">
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
                        <div class="card card_hover border-0 border-left-success shadow" id="accomplish_card" onclick="loadTableAccessRevokeData('Done','access')">
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
                <div class="row mt-5">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header card-9 py-3">
                                <div class="row">
                                    <span class="fw-bold fs-27 text-light" id="active_access_request"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="server_access_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderInfoSec">
                                            <tr>
                                                <th style="text-align:center;">Date Added</th>
                                                <th style="text-align:center;">Server</th>
                                                <th style="text-align:center;">I.P Address</th>
                                                <th style="text-align:center;">MAC Address</th>
                                                <th>User</th>
                                                <th>Location</th>
                                                <th>Purpose</th>
                                                <th style="text-align:center;">Date Requested</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderInfoSec">
                                            <tr>
                                                <th style="text-align:center;">Date Added</th>
                                                <th style="text-align:center;">Server</th>
                                                <th style="text-align:center;">I.P Address</th>
                                                <th style="text-align:center;">MAC Address</th>
                                                <th>User</th>
                                                <th>Location</th>
                                                <th>Purpose</th>
                                                <th style="text-align:center;">Date Requested</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="revoke_container">
                <div class="row mt-5">
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-warning shadow" id="revoke_pending_card" onclick="loadTableAccessRevokeData('Pending','revoke')">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate">
                                        <span class="fs-6 text-warning fw-bold">FOR APPROVAL</span>
                                        <div class="fs-2 fw-bold" id="revoke_pending_count"></div>
                                    </div>
                                    <div class="fs-1 text-warning"><i class="fa-regular fa-hourglass-half fa-pulse"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-primary shadow" id="revoke_ongoing_card" onclick="loadTableAccessRevokeData('Ongoing','revoke')">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate">
                                        <span class="fs-6 text-primary fw-bold">IN PROCESS</span>
                                        <div class="fs-2 fw-bold" id="revoke_ongoing_count"></div>
                                    </div>
                                    <div class="fs-1 text-primary"><i class="fa-solid fa-spinner fa-spin"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-info shadow" id="revoke_received_card" onclick="loadTableAccessRevokeData('For Received','revoke')">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate">
                                        <span class="fs-6 text-info fw-bold">FOR RECEIVED</span>
                                        <div class="fs-2 fw-bold" id="revoke_received_count"></div>
                                    </div>
                                    <div class="fs-1 text-info"><i class="fa-solid fa-envelope fa-bounce"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md mb-4 mb-md-0">
                        <div class="card card_hover border-0 border-left-success shadow" id="revoke_accomplish_card" onclick="loadTableAccessRevokeData('Done','revoke')">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate">
                                        <span class="fs-6 text-success fw-bold">ACCOMPLISHED</span>
                                        <div class="fs-2 fw-bold" id="revoke_accomplish_count"></div>
                                    </div>
                                    <div class="fs-1 text-success"><i class="fa-solid fa-circle-check fa-bounce"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header card-9 py-3">
                                <div class="row">
                                    <span class="fw-bold fs-27 text-light" id="active_revoke_request"></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="server_revoke_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="customHeaderInfoSec">
                                            <tr>
                                                <th style="text-align:center;">Date Revoke</th>
                                                <th style="text-align:center;">Server</th>
                                                <th style="text-align:center;">I.P Address</th>
                                                <th style="text-align:center;">MAC Address</th>
                                                <th>User</th>
                                                <th>Location</th>
                                                <th>Purpose</th>
                                                <th style="text-align:center;">Date Requested</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="customHeaderInfoSec">
                                            <tr>
                                                <th style="text-align:center;">Date Revoke</th>
                                                <th style="text-align:center;">Server</th>
                                                <th style="text-align:center;">I.P Address</th>
                                                <th style="text-align:center;">MAC Address</th>
                                                <th>User</th>
                                                <th>Location</th>
                                                <th>Purpose</th>
                                                <th style="text-align:center;">Date Requested</th>
                                                <th style="text-align:center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    let activeRequestList = { //* Card header name of active table
        'Pending': 'Pending',
        'Ongoing': 'Ongoing',
        'For Received': 'For Received',
        'Done': 'Accomplished'
    }

    function loadTableNavigation(inAction) {
        if (inAction == 'Access') {
            $('.access_container').css('display', 'block');
            $('.revoke_container').css('display', 'none');
        } else {
            $('.revoke_container').css('display', 'block');
            $('.access_container').css('display', 'none');
        }
    }

    loadTableNavigation('Access')
    loadTableAccessRevokeData('Pending', 'access');
    loadTableAccessRevokeData('Pending', 'revoke');
    loadAccessRevokeRequestCount('false');
    loadAccessRevokeRequestCount('true');


    loadFetchData();

    function loadFetchData() {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_server_access_contr.class.php',
            type: 'POST',
            // dataType: 'JSON',
            data: {
                action: 'fetchData'
            },
            success: result => {
                alert(result);
            }
        });
    }


    function loadTableAccessRevokeData(statusVal, inCategory) {
        var access_revoke_table;
        var access_revoke;
        if (inCategory == 'access') {
            $('#active_access_request').text(`${activeRequestList[statusVal] ?? 'Pending'} Request`);
            setCardActive(statusVal, 'access');
            access_revoke_table = 'server_access_table';
            access_revoke = 'false';
        } else {
            $('#active_revoke_request').text(`${activeRequestList[statusVal] ?? 'Pending'} Request`);
            setCardActive(statusVal, 'revoke');
            access_revoke_table = 'server_revoke_table';
            access_revoke = 'true';
        }

        var inTable = $('#' + access_revoke_table).DataTable({
            'autoWidth': false,
            'responsive': true,
            'destroy': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/info_sec_controller/info_sec_server_access_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_server_access_revoke_table_data',
                    access_status: statusVal,
                    access_revoke: access_revoke
                }
            },
            'columnDefs': [{
                targets: [0, 7],
                className: 'dt-body-middle-center',
                width: '10%'
            }, {
                targets: [1, 2, 3],
                className: 'dt-body-middle-center',
                width: '13%'
            }, {
                targets: [4, 5, 6],
                className: 'dt-body-middle-left'
            }, {
                targets: 8,
                className: 'dt-nowrap-center',
                width: '7%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btnAction = '';
                    switch (statusVal) {
                        case 'Pending':
                            if (data[4] == true) {
                                if (data[1] == true && data[2] == true) {
                                    if (data[3] == logged_user) {
                                        btnAction = `<button class="btn btn-primary col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Proceed" onclick="proceedRequest('${data[0]}')"><i class="fa-solid fa-circle-play fa-bounce"></i></button>`;
                                    } else {
                                        btnAction += `<button class="btn btn-info col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="viewAccessRequest('${data[0]}')"><i class="fa-solid fa-circle-info fa-beat"></i></button>`;
                                    }
                                } else {
                                    btnAction = `<button class="btn btn-secondary col" disabled><i class="fa-solid fa-circle-play"></i></button>`;
                                }
                            } else {
                                btnAction = `<button class="btn btn-primary col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Acknowledge" onclick="receiveRequest('${data[0]}')"><i class="fa-solid fa-clipboard-check fa-shake"></i></button>`;
                            }
                            break;
                        case 'Ongoing':
                            if (data[3] == logged_user) {
                                btnAction = `<button class="btn btn-success col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Done" onclick="accomplishRequest('${data[0]}')"><i class="fa-solid fa-circle-check fa-bounce"></i></button>`;
                            } else {
                                btnAction += `<button class="btn btn-info col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="viewAccessRequest('${data[0]}')"><i class="fa-solid fa-circle-info fa-beat"></i></button>`;
                            }
                            break;
                        case 'Done':
                            btnAction = `<button class="btn btn-dark col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="PDF" onclick="viewPdfRequest('${data[0]}')"><i class="fa-solid fa-file-pdf fa-bounce"></i></button>`;
                            break;
                        default:
                            btnAction += `<button class="btn btn-info col" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Details" onclick="viewAccessRequest('${data[0]}')"><i class="fa-solid fa-circle-info fa-beat"></i></button>`;
                            break;
                    }
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
        // setInterval(function() {
        //     inTable.ajax.reload(null, false);
        // }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAccessRevokeRequestCount(access_revoke) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_server_access_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_request_count',
                access_revoke: access_revoke
            },
            success: (res) => {
                if (access_revoke == 'false') {
                    $('#pending_count').text(res['Pending'] ?? 0); //? For Approval
                    $('#ongoing_count').text(res['Ongoing'] ?? 0); //? In Process
                    $('#received_count').text(res['For Received'] ?? 0); //? For Received
                    $('#accomplish_count').text(res['Done'] ?? 0); //? Accomplish
                } else {
                    $('#revoke_pending_count').text(res['Pending'] ?? 0); //? For Approval
                    $('#revoke_ongoing_count').text(res['Ongoing'] ?? 0); //? In Process
                    $('#revoke_received_count').text(res['For Received'] ?? 0); //? For Received
                    $('#revoke_accomplish_count').text(res['Done'] ?? 0); //? Accomplish
                }
                setTimeout(loadAccessRevokeRequestCount, 1000);
            }
        });
    }

    function receiveRequest(serveraccessid) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_server_access_contr.class.php',
            type: 'POST',
            data: {
                action: 'receive_request',
                serveraccessid: serveraccessid,
                receive_by: logged_user
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Server Access Request Acknowledged.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                loadTableAccessRevokeData('Pending');
            }
        });
    }

    function proceedRequest(serveraccessid) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_server_access_contr.class.php',
            type: 'POST',
            data: {
                action: 'proceed_request',
                serveraccessid: serveraccessid
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Processing Request.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                loadTableAccessRevokeData('Ongoing');
            }
        });
    }

    function accomplishRequest(serveraccessid) {
        $.ajax({
            url: '../controller/info_sec_controller/info_sec_server_access_contr.class.php',
            type: 'POST',
            data: {
                action: 'accomplish_request',
                serveraccessid: serveraccessid
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Request Accomplished.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                loadTableAccessRevokeData('For Received');
            }
        });
    }

    function viewAccessRequest(serveraccessid) {
        alert(serveraccessid);
    }

    function setCardActive(statusVal, inCategory) {
        if (inCategory == 'access') {
            switch (statusVal) {
                case 'Ongoing':
                    $('#pending_card').removeClass('active');
                    $('#ongoing_card').addClass('active');
                    $('#received_card').removeClass('active');
                    $('#accomplish_card').removeClass('active');
                    break;
                case 'For Received':
                    $('#pending_card').removeClass('active');
                    $('#ongoing_card').removeClass('active');
                    $('#received_card').addClass('active');
                    $('#accomplish_card').removeClass('active');
                    break;
                case 'Done':
                    $('#pending_card').removeClass('active');
                    $('#ongoing_card').removeClass('active');
                    $('#received_card').removeClass('active');
                    $('#accomplish_card').addClass('active');
                    break;
                default:
                    $('#pending_card').addClass('active');
                    $('#ongoing_card').removeClass('active');
                    $('#received_card').removeClass('active');
                    $('#accomplish_card').removeClass('active');
                    break;
            }
        } else {
            switch (statusVal) {
                case 'Ongoing':
                    $('#revoke_pending_card').removeClass('active');
                    $('#revoke_ongoing_card').addClass('active');
                    $('#revoke_received_card').removeClass('active');
                    $('#revoke_accomplish_card').removeClass('active');
                    break;
                case 'For Received':
                    $('#revoke_pending_card').removeClass('active');
                    $('#revoke_ongoing_card').removeClass('active');
                    $('#revoke_received_card').addClass('active');
                    $('#revoke_accomplish_card').removeClass('active');
                    break;
                case 'Done':
                    $('#revoke_pending_card').removeClass('active');
                    $('#revoke_ongoing_card').removeClass('active');
                    $('#revoke_received_card').removeClass('active');
                    $('#revoke_accomplish_card').addClass('active');
                    break;
                default:
                    $('#revoke_pending_card').addClass('active');
                    $('#revoke_ongoing_card').removeClass('active');
                    $('#revoke_received_card').removeClass('active');
                    $('#revoke_accomplish_card').removeClass('active');
                    break;
            }
        }
    }
</script>
</body>
<html>