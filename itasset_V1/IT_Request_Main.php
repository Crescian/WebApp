<?php include './../includes/header.php';
$BannerWeb = $conn->db_conn_bannerweb(); //* Banner Web Database connection

// * Check if module is within the application

$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWeb->prepare($queryCheckApp);
$stmtCheckApp->execute();
$chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC);
$chkAppId = $chkAppIdRow['app_id'];

if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<style>
    .card-request:hover {
        cursor: pointer;
        background-color: #f0f0f0;
    }

    .card-request.active {
        box-shadow: 10px 10px 8px #888888 !important;
    }

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
                <span class="page-title-it">Software & Hardware Request Main</span>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card card-request border-0 border-left-danger shadow" onclick="loadTableRequest('Cancelled')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-danger fw-bold">CANCELLED</span>
                                    <div class="fs-2 fw-bold" id="cancelled_count">0</div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-ban fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card card-request border-0 border-left-warning shadow active" onclick="loadTableRequest('Pending')">
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

                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card card-request border-0 border-left-primary shadow" onclick="loadTableRequest('Ongoing')">
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

                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card card-request border-0 border-left-success shadow" onclick="loadTableRequest('Done')">
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

            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="overflow-hidden">
                        <table class="table table-bordered table-hover table w-100 text-nowrap" id="request_table">
                            <thead>
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
                                    <th class="text-center">REQUEST TYPE</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">PURPOSE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

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
    </div>
</div>

<div class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-body py-4 text-center">
                <h3 class="alert-title mb-4 fw-bold">Title</h3>
                <p class="alert-message fw-semibold fs-6">This is a sample message.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0 alert-modal-btn">
                <!-- <button type="button" class="btn btn-link text-danger text-decoration-none col-6 m-0 border-end fw-bold alert-submit" id="alert-submit">Yes, submit</button> -->
                <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailsModalLabel">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="queue_number" readonly disabled>
                            <label class="fw-bold">Reference Number:</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="date_requested" disabled>
                            <label class="fw-bold">Date Requested:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="date_needed" disabled>
                            <label class="fw-bold">Date Needed:</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="request_type" disabled>
                            <label class="fw-bold">Request Type:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="item" disabled>
                            <label class="fw-bold">Requested Item:</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <textarea class="form-control fw-bold" style="height: 125px; resize: none;" id="description" disabled></textarea>
                    <label class="fw-bold">Description:</label>
                </div>
                <div class="form-floating mb-2">
                    <textarea class="form-control fw-bold" style="height: 125px; resize: none;" id="purpose" disabled></textarea>
                    <label class="fw-bold">Purpose:</label>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="requested_by" disabled>
                            <label class="fw-bold">Requested By:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="approved_by" disabled>
                            <label class="fw-bold">Approved By:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="noted_by" disabled>
                            <label class="fw-bold">Noted By:</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include './../includes/footer.php'; ?>

<script>
    $('.card-request').on('click', (event) => {
        $('.card-request').removeClass('active');
        $(event.currentTarget).addClass('active');
    })

    let requestTable = "";
    loadTableRequest();
    loadRequestCount();

    function loadRequestCount() {
        $.ajax({
            url: 'functions/it_request_main-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadRequestCount'
            },
            success: (res) => {
                $('#cancelled_count').text(res['Cancelled'] ?? 0); // Cancelled
                $('#pending_count').text(res['Pending'] ?? 0); // For Approval
                $('#ongoing_count').text(res['Ongoing'] ?? 0); // In Process
                $('#accomplish_count').text(res['Done'] ?? 0); // Accomplish
                setTimeout(loadRequestCount, 1500);
            }
        });
    }


    function loadTableRequest(statusVal) {
        $('#request_table').hide();
        requestTable = $('#request_table').DataTable({
            responsive: true,
            autoWidth: false,
            destroy: true,
            // processing: true,
            serverSide: true,
            ajax: {
                url: 'functions/it_request_main-function.php',
                type: 'POST',
                data: {
                    action: 'loadTableRequest',
                    statusVal: statusVal
                }
            },
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').remove(); // -- --- Hide tooltip every table draw -----
            },
            columnDefs: [{
                    targets: [0, 1, 2, 3, 4],
                    className: 'dt-body-middle-center'
                },
                {
                    targets: [5, 6],
                    className: 'dt-body-middle-left'
                },
                {
                    targets: 7,
                    render: function(data, type, row, meta) {
                        const buttonMap = {
                            'Cancelled': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reapprove" data-id="${data.id}" id="btn_approve">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        </button>`,
                            'Pending': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Process" data-id="${data.id}" id="btn_request">
                                            <i class="fa-solid fa-spinner"></i>
                                        </button>
                                        <button class="btn btn-dark text-white shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                        <button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print" data-id="${data.id}" id="btn_print">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </button>
                                        <button class="btn btn-danger shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancel" data-id="${data.id}" id="btn_cancel">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>`,
                            'Ongoing': `<button class="btn btn-primary shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Accomplish" data-id="${data.id}" data-sender="${data.sender}" id="btn_accomplish">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-dark shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                        <button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print" data-id="${data.id}" id="btn_print">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </button>`,
                            'Done': `<button class="btn btn-dark shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                        <button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print" data-id="${data.id}" id="btn_print">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </button>`
                        };
                        return buttonMap[data.status] || '';
                    },

                    orderable: false,
                    className: 'dt-nowrap-center'
                },
            ]
        });
        $('#request_table').fadeIn('1000');
    }

    setInterval(function() {
        requestTable.ajax.reload(null, false); // user paging is not reset on reload
    }, 5000);

    function alertModal(title, message, type) {
        $('#alertModal').modal('show');
        $('.alert-title').text(title);
        $('.alert-message').text(message);
        $('.alert-submit').remove();
        $('.alert-modal-btn').prepend($('<button>', {
            type: 'button',
            class: 'btn btn-link alert-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
            id: `alert_${type}_btn`,
            text: 'Yes, submit'
        }));
    }

    function handleAction(action, id, sender) {
        alertModal(action[0].toUpperCase() + action.slice(1), `Are you sure you want to ${action} this request?`, action);
        $(`#alert_${action}_btn`).click(() => {
            let objData = action == 'accomplish' ? {
                action: action,
                id: id,
                sender: sender,
            } : {
                action: action,
                id: id,
            };

            $.ajax({
                url: 'functions/it_request_main-function.php',
                type: 'POST',
                data: objData,
                success: () => requestTable.ajax.reload(null, false),
                complete: () => $('#alertModal').modal('hide')
            });
        });
    }

    $('#request_table').on('click', '#btn_cancel', (event) => handleAction('cancel', $(event.currentTarget).data('id')));
    $('#request_table').on('click', '#btn_request', (event) => handleAction('process', $(event.currentTarget).data('id')));
    $('#request_table').on('click', '#btn_accomplish', (event) => handleAction('accomplish', $(event.currentTarget).data('id'), $(event.currentTarget).data('sender')));
    $('#request_table').on('click', '#btn_approve', (event) => handleAction('reapprove', $(event.currentTarget).data('id')));

    $('#request_table').on('click', '#btn_print', (event) => window.open(`functions/it_request_main-pdf.php?id=${$(event.currentTarget).data('id')}`, '_blank'));
    $('#request_table').on('click', '#btn_details', (event) => {
        let id = $(event.currentTarget).data("id");
        $('#detailsModal').modal('show');
        $.ajax({
            url: 'functions/it_request_main-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'details',
                id: id
            },
            success: (res) => {
                $('#queue_number').val(res.queue_number);
                $('#item').val(res.item);
                $('#request_type').val(res.request_type);
                $('#description').val(res.description);
                $('#purpose').val(res.purpose);
                $('#requested_by').val(res.requested_by);
                $('#approved_by').val(res.approved_by);
                $('#noted_by').val(res.noted_by);
                $('#date_requested').val(res.date_requested);
                $('#date_needed').val(res.date_needed);
            }
        });
    });
</script>