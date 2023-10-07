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
    .card-repair:hover {
        cursor: pointer;
        background-color: #f0f0f0;
    }

    .card-repair.active {
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
                <span class="page-title-it">Repair Request Main</span>
            </div>

            <div class="row mb-4">
                <div class="col-sm-6 col-md-3 mb-4 mb-md-0">
                    <div class="card card-repair border-0 border-left-danger shadow active" onclick="loadTableRepair('On Hold')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-danger fw-bold">ON HOLD</span>
                                    <div class="fs-2 fw-bold" id="on_hold_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-hand fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3 mb-4 mb-md-0">
                    <div class="card card-repair border-0 border-left-warning shadow" onclick="loadTableRepair('Pending')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-warning fw-bold">PENDING</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-regular fa-hourglass-half fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3 mb-4 mb-md-0">
                    <div class="card card-repair border-0 border-left-primary shadow" onclick="loadTableRepair('Ongoing')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-6 text-primary fw-bold">ONGOING</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-spinner fa-spin"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3 mb-4 mb-md-0">
                    <div class="card card-repair border-0 border-left-success shadow" onclick="loadTableRepair('Done')">
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-danger" id="active_request"></h4>
                        <div>
                            <button class="btn btn-primary fw-bold" id="generate_report"><i class="fa-solid fa-calendar-days me-2"></i>General Report</button>
                            <button class="btn btn-success fw-bold" id="ta_report"><i class="fa-solid fa-calendar-days me-2"></i>Technical Accomplishment Report</button>
                        </div>
                    </div>

                    <div class="overflow-hidden">
                        <table class="table table-bordered table-hover table w-100 text-nowrap" id="repair_table">
                            <thead>
                                <tr>
                                    <th class="text-center">REFERENCE NO.</th>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">LOCATION</th>
                                    <th class="text-center">REQUESTED BY</th>
                                    <th class="text-center">DATE REQUESTED</th>
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
            <div class="modal-body pt-4 text-center">
                <h3 class="alert-title mb-4 fw-bold">Title</h3>
                <p class="alert-message fw-semibold fs-6 mb-0">This is a sample message.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0 alert-modal-btn">
                <!-- <button type="button" class="btn btn-link text-danger text-decoration-none col-6 m-0 border-end fw-bold alert-submit" id="alert-submit">Yes, submit</button> -->
                <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailsModalLabel">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="queue_number" readonly disabled>
                            <label class="fw-bold">Queue Number:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="date_requested" disabled>
                            <label class="fw-bold">Date Requested:</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="area" disabled>
                            <label class="fw-bold">Area:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="location" disabled>
                            <label class="fw-bold">Location:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="ip_address" disabled>
                            <label class="fw-bold">IP Address:</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="item" disabled>
                            <label class="fw-bold">Item:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="requested_by" disabled>
                            <label class="fw-bold">Requested by:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="repaired_by" disabled>
                            <label class="fw-bold">Repaired by:</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <textarea class="form-control fw-bold" style="height: 100px; resize: none;" id="remarks" disabled></textarea>
                    <label class="fw-bold">Remarks:</label>
                </div>
                <div class="form-floating mb-2">
                    <textarea class="form-control fw-bold" style="height: 100px; resize: none;" id="action_taken" disabled></textarea>
                    <label class="fw-bold">Action Taken:</label>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="datetime_acknowledge" disabled>
                            <label class="fw-bold">Datetime Acknowledge:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="datetime_repair" disabled>
                            <label class="fw-bold">Datetime Repair:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="datetime_accomplish" disabled>
                            <label class="fw-bold">Datetime Accomplish:</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="generateReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="generateReportModalLabel">Generate Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <input type="date" id="date_report_start" class="form-control" placeholder=" ">
                            <label for="">Date Start:</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <input type="date" id="date_report_end" class="form-control" placeholder=" " disabled>
                            <label for="">Date End:</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-dark w-100" id="generate_report_submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="taReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="taReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="taReportModalLabel">Technical Accomplishment Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <input type="date" id="date_ta_start" class="form-control" placeholder=" ">
                            <label for="">Date Start:</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <input type="date" id="date_ta_end" class="form-control" placeholder=" " disabled>
                            <label for="">Date End:</label>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <!-- <div class="col-md-12 mb-2">
                        <div class="form-floating">
                            <select class="form-select">
                                <option value="">Choose...</option>
                            </select>
                            <label for="">Requested By()</label>
                        </div>
                    </div> -->
                </div>

                <button type="button" class="btn btn-dark w-100" id="ta_report_submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php include './../includes/footer.php'; ?>

<script>
    //* Click event for displaying table based on status
    $('.card-repair').on('click', (event) => {
        $('.card-repair').removeClass('active');
        $(event.currentTarget).addClass('active');
    })

    //* Card header name of active table
    let activeRequestList = {
        'On Hold': 'On Hold',
        'Pending': 'Pending',
        'Ongoing': 'Ongoing',
        'Done': 'Accomplished',
    }
    let repairTable = "";

    //* Initialize Callbacks
    loadTableRepair();
    loadRepairCount();

    //* Cards for the number of request based on status
    function loadRepairCount() {
        $.ajax({
            url: 'functions/it_repair_main-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadRepairCount'
            },
            success: (res) => {
                $('#on_hold_count').text(res['On Hold'] ?? 0);
                $('#pending_count').text(res['Pending'] ?? 0);
                $('#ongoing_count').text(res['Ongoing'] ?? 0);
                $('#accomplish_count').text(res['Done'] ?? 0);
                setTimeout(loadRepairCount, 1500);
            }
        });

    }

    //* Datatable 
    function loadTableRepair(statusVal) {
        $('#active_request').text(`${activeRequestList[statusVal] ?? 'On Hold'} Request`);
        $('#repair_table').hide();
        repairTable = $('#repair_table').DataTable({
            responsive: true,
            autoWidth: false,
            destroy: true,
            // processing: true,
            serverSide: true,
            ajax: {
                url: 'functions/it_repair_main-function.php',
                type: 'POST',
                data: {
                    action: 'loadTableRepair',
                    statusVal: statusVal
                }
            },
            columnDefs: [{
                    targets: [0, 1, 3, 4, 5],
                    className: 'dt-body-middle-center'
                },{
                    targets: 2,
                    className: 'dt-body-middle-left'
                },{
                    targets: 6,
                    render: function(data, type, row, meta) {
                        const buttonMap = {
                            'On Hold': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Acknowledge" data-id="${data.id}" id="btn_acknowledge">
                                            <i class="fa-solid fa-handshake"></i>
                                        </button>
                                        <button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                        <button class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cancel" data-id="${data.id}" id="btn_cancel">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>`,
                            'Pending': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Repair" data-id="${data.id}" id="btn_repair">
                                            <i class="fa-solid fa-hammer"></i>
                                        </button>
                                        <button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button> 
                                        <button class="btn btn-success shadow" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print" data-id="${data.id}" id="btn_print">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </button>`,
                            'Ongoing': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Accomplish" data-id="${data.id}" data-sender="${data.sender}" id="btn_accomplish">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>`,
                            'Done': `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Details" data-id="${data.id}" id="btn_details">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>`
                        };
                        return buttonMap[data.status] || '';
                    },
                    orderable: false,
                    className: 'dt-nowrap-center'
                },
            ],
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').remove(); // ----- Hide tooltip every table draw -----
            },
        });

        $('#repair_table').fadeIn('1000');
        setInterval(function() {
            repairTable.ajax.reload(null, false); // user paging is not reset on reload
        }, 5000);
    }

    $('#repair_table').on('length.dt', function(e, settings, len) {
        $('[data-bs-toggle="tooltip"]').tooltip('hide');
    });

    //* Dynamic Alert Modal
    function alertModal(title, message, type) {
        $('#alertModal').modal('show');
        $('.alert-title').text(title);
        $('.alert-message').text(message)

        $('.alert-submit').remove();
        $('.alert-modal-btn').prepend($('<button>', {
            type: 'button',
            class: 'btn btn-link alert-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
            id: `alert_${type}_btn`,
            text: 'Yes, submit'
        }));

        if (type == 'accomplish') {
            $(`#alert_${type}_btn`).prop('disabled', 1)
            $('.alert-message').append($('<textarea>', {
                class: 'form-control mt-4',
                id: 'action_taken',
                placeholder: 'Action Taken:',
                height: '120px',
            })).on('input', '#action_taken', (e) => {
                $(`#alert_${type}_btn`).prop('disabled', $('#action_taken').val().trim() == '' ? 1 : 0)
            });
        }

        if (type == 'acknowledge') {
            const options = [{
                value: '',
                label: 'Priority :'
            }, {
                value: 'Critical',
                label: 'Critical'
            }, {
                value: 'High',
                label: 'High'
            }, {
                value: 'Medium',
                label: 'Medium'
            }, {
                value: 'Low',
                label: 'Low'
            }];

            const select = $('<select>', {
                class: 'form-select mt-4',
                id: 'priority',
            }).append(options.map(option => $('<option>', {
                value: option.value,
                text: option.label,
                hidden: option.value == '' ? true : false
            })));

            $(`#alert_${type}_btn`).prop('disabled', 1);
            $('.alert-message').append(select).on('change', '#priority', (e) => {
                $(`#alert_${type}_btn`).prop('disabled', $('#priority').val() == '' ? 1 : 0);
            });
        }
    }

    //* Update Status of Request
    function handleAction(action, id, sender) {
        alertModal(action[0].toUpperCase() + action.slice(1), `Are you sure you want to ${action} this request?`, action);
        $(`#alert_${action}_btn`).click(() => {
            let objData = "";
            switch (action) {
                case 'accomplish':
                    objData = {
                        action: action,
                        id: id,
                        sender: sender,
                        action_taken: $('#action_taken').val()
                    }
                    break;

                case 'acknowledge':
                    objData = {
                        action: action,
                        id: id,
                        priority: $('#priority').val()
                    }
                    break;

                default:
                    objData = {
                        action: action,
                        id: id,
                    }
                    break;
            }

            $.ajax({
                url: 'functions/it_repair_main-function.php',
                type: 'POST',
                data: objData,
                success: () => repairTable.ajax.reload(null, false),
                complete: () => $('#alertModal').modal('hide')
            });
        });
    }

    //* Action Button Events
    $('#repair_table').on('click', '#btn_acknowledge', (event) => handleAction('acknowledge', $(event.currentTarget).data('id')));
    $('#repair_table').on('click', '#btn_repair', (event) => handleAction('repair', $(event.currentTarget).data('id')));
    $('#repair_table').on('click', '#btn_accomplish', (event) => handleAction('accomplish', $(event.currentTarget).data('id'), $(event.currentTarget).data('sender')));
    $('#repair_table').on('click', '#btn_cancel', (event) => handleAction('cancel', $(event.currentTarget).data('id')));
    $('#repair_table').on('click', '#btn_print', (event) => window.open(`functions/it_repair_main-pdf.php?id=${$(event.currentTarget).data('id')}`, '_blank'));
    $('#repair_table').on('click', '#btn_details', (event) => {
        let id = $(event.currentTarget).data("id");
        $('#detailsModal').modal('show');
        $.ajax({
            url: 'functions/it_repair_main-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'details',
                id: id
            },
            success: (res) => {
                $('#queue_number').val(res.queue_number);
                $('#area').val(res.area);
                $('#location').val(res.location);
                $('#ip_address').val(res.ip_address);
                $('#item').val(res.item);
                $('#requested_by').val(res.requested_by);
                $('#repaired_by').val(res.repaired_by);
                $('#date_requested').val(res.date_requested);
                $('#remarks').val(res.remarks);
                $('#action_taken').val(res.action_taken);
                $('#datetime_acknowledge').val(res.datetime_acknowledge);
                $('#datetime_repair').val(res.datetime_repair);
                $('#datetime_accomplish').val(res.datetime_accomplish);
            }
        });
    });

    $('#generate_report').click(() => {
        $('#generateReportModal').modal('show');

        let start = $('#date_report_start');
        let end = $('#date_report_end');

        start.change(() => {
            end.attr('min', start.val());
            end.prop('disabled', !start.val());
        });

        $('#generate_report_submit').click(() => {
            if (start.val() == '' || end.val() == '') {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill out all required fields.',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                $('#generateReportModal').modal('hide');
                window.open(`functions/it_repair_main_report-pdf.php?start=${start.val()}&end=${end.val()}`, '_blank')
            }
        });
    });

    $('#ta_report').click(() => {
        $('#taReportModal').modal('show');

        let start = $('#date_ta_start');
        let end = $('#date_ta_end');

        start.change(() => {
            end.attr('min', start.val());
            end.prop('disabled', !start.val());
        });

        $('#ta_report_submit').click(() => {
            if (start.val() == '' || end.val() == '') {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill out all required fields.',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                $('#taReportModal').modal('hide');
                window.open(`functions/it_repair_main_ta_report-pdf.php?start=${start.val()}&end=${end.val()}`, '_blank')
            }
        });
    });
</script>