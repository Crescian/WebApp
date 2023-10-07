<?php include './../includes/header.php';
session_start();
?>
<style>
    ::-webkit-scrollbar {
        width: 0.7vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #4adede;
        border-radius: 200vw;
    }
</style>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fa-solid fa-envelope-open-text me-3"></i>Notifications
            <input type="hidden" id="req-count" value="0" placeholder="req-count">
            <input type="hidden" id="req-repair-count" value="0" placeholder="req-repair-count">
            <input type="hidden" id="received-count" value="0" placeholder="received-count">
            <input type="hidden" id="checked-count" value="0" placeholder="checked-count">
            <input type="hidden" id="approved-count" value="0" placeholder="approved-count">
            <input type="hidden" id="noted-count" value="0" placeholder="noted-count">
            <input type="hidden" id="total-count" value="0" placeholder="total-count">
            <input type="hidden" id="total-count-phd" value="0" placeholder="total-count-phd">
            <input type="hidden" id="total-count-info" value="0" placeholder="total-count-info">
            <input type="hidden" id="repr-count" value="0" placeholder="repr-count">
            <input type="hidden" id="navbar-location" value="" placeholder="navbar-location">

            <input type="hidden" id="db_name" class="form-control fw-bold">
            <input type="hidden" id="table_name" class="form-control fw-bold">
            <input type="hidden" id="table_id" class="form-control fw-bold">
            <input type="hidden" id="table_id_name" class="form-control fw-bold">
            <input type="hidden" id="notif_to" class="form-control fw-bold">
            <span class="remarks-request-value"></span>
        </h1>
        <a class="btn-back" href="../index.php">
            <h4><i class="fa-solid fa-arrow-left me-1"></i>Back</h4>
        </a>
    </div>
    <hr>
    <div class="row">
        <!-- ==================== Navbar Section ==================== -->
        <div class="col-md-3 d-flex justify-content-center d-none d-md-block">
            <ul class="notification-nav mt-md-5 mt-0 loadNavLink">
            </ul>
        </div>
        <!-- ==================== Navbar Section End ==================== -->
        <div class="col-md mt-md-5 mt-0">
            <div class="card shadow-lg g-0 card-hide" style="border-radius: 30px;">
                <div class="card-body">
                    <div class="row p-r-50 p-l-50 mb-3">
                        <div class="class d-flex justify-content-center">
                            <div class="lds-ripple">
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <!-- ========== Nav Tabs ========== -->
                        <ul class="nav nav-tabs nav-fill flex-column flex-sm-row" id="nav-item-append" role="tablist">
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="summary_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog  modal-dialog-scrollable modal-fullscreen-xl-down" role=" document">
        <div class="modal-content">
            <div class="modal-header customHeaderNotif">
                <h4 class="modal-title text-uppercase fw-bold text-light headModal"> Summary</h4>
            </div>
            <div class="modal-body repair_modal_body">
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">DETAILS</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="queue-value" disabled>
                            <label class="fw-bold" for="queue-value">Queue Number:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="location-value" disabled>
                            <label class="fw-bold" for="location-value">Location:</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="area-value" disabled>
                            <label class="fw-bold" for="area-value">Area:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="item-value" disabled>
                            <label class="fw-bold" for="item-value">Item:</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">REMARKS</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" class="form-control fw-bold" id="remarks-value" disabled>
                    <label class="fw-bold">Remarks:</label>
                </div>
                <div class="row hide-sign-status">
                    <div class="col d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">SignStatus</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-1"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="requested_by_itr" disabled>
                            <label class="fw-bold">Requested by:</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-4"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="repaired_by_itr" disabled>
                            <label class="fw-bold">Repaired by:</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-3"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="noted_by_itr" disabled>
                            <label class="fw-bold">Noted by:</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body request_modal_body">
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">DETAILS</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="request-type-value" disabled>
                            <label class="fw-bold">Request by:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="software-type-value" disabled>
                            <label class="fw-bold">Software Type:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="item-request-value" disabled>
                            <label class="fw-bold">Item:</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="date-needed-value" disabled>
                            <label class="fw-bold">Date Needed:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="date-requested-value" disabled>
                            <label class="fw-bold">Date Requested:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="status-value" disabled>
                            <label class="fw-bold">Status:</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">DESCRIPTION</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <textarea name="" cols="30" rows="10" class="form-control fw-bold" id="description-value" disabled></textarea>
                    <label class="fw-bold">Description:</label>
                </div>
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">PURPOSE</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <textarea name="" cols="30" rows="10" class="form-control fw-bold" id="purpose-value" disabled></textarea>
                    <label class="fw-bold">Purpose:</label>
                </div>
                <div class="row hide-sign-status">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">SignStatus</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-1"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="requested_by" disabled>
                            <label class="fw-bold">Requested by:</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-2"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="approved_by" disabled>
                            <label class="fw-bold">Approved by:</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-3"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="noted_by" disabled>
                            <label class="fw-bold">Noted by:</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-4"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="repaired_by" disabled>
                            <label class="fw-bold">Repaired by:</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body request_web_modal_body">
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">DETAILS</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="queue-value-web" disabled>
                            <label class="fw-bold">Queue Number:</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="service-type-web" disabled>
                            <label class="fw-bold">Service Type:</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="app-name-web" disabled>
                            <label class="fw-bold">Application Name:</label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="web-priority-web" disabled>
                            <label class="fw-bold">Web Priority:</label>
                        </div>
                    </div>
                </div>
                <div class="row hide-sign-status">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">SignStatus</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-1"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="requested_by_web" disabled>
                            <label class="fw-bold">Requested by:</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-2"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="approved_by_web" disabled>
                            <label class="fw-bold">Approved by:</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-3"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="noted_by_web" disabled>
                            <label class="fw-bold">Noted by:</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-4"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="repaired_by_web" disabled>
                            <label class="fw-bold">Repaired by:</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body checked_modal_body">
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">DETAILS</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="referrence-value" disabled>
                            <label class="fw-bold">Referrence Number:</label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold" id="date-created-value" disabled>
                            <label class="fw-bold">Date Created:</label>
                        </div>
                    </div>
                </div>
                <div class="row hide-sign-status">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">SignStatus</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-1"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="prepared_by_phd" disabled>
                            <label class="fw-bold">Prepared by:</label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-5"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="checked_by_phd" disabled>
                            <label class="fw-bold">Checked by:</label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-floating hide-sign-status">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success checked-status-3"><i class="fa-solid fa-check fa-bounce"></i></span>
                            <input type="text" class="form-control fw-bold" id="noted_by_phd" disabled>
                            <label class="fw-bold">Noted by:</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer"> -->
            <div class="d-grid gap-1 mb-3 px-3 p-t-15">
                <button type="button" class="hide-footer btn btn-success btn-sm fw-bold btn-accomplish" style="border-radius: 20px;" onclick="btnAcknowledge(this.value);"><i class="fa-solid fa-check fa-beat p-r-8"></i>Acknowledge</button>
                <button type="button" class="hide-footer-cancel btn btn-info btn-sm fw-bold btn-cancel" style="border-radius: 20px;" onclick="btnCancel(this.value);"><i class="fa-regular fa-thumbs-down p-r-8"></i>Cancel Acknowledge</button>
                <button type="button" class="hide-footer-received btn btn-info btn-sm fw-bold btn-received-accomplish" style="border-radius: 20px;" onclick="btnReceivedAcknowledge(this.value);"><i class="fa-solid fa-check fa-beat p-r-8"></i>Acknowledge</button>
                <button type="button" class="btn btn-danger btn-sm fw-bold" style="border-radius: 20px;" onclick="btnClose();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var fullname = '<?php echo $_SESSION['fullname']; ?>';
    var department = '<?php echo $_SESSION['dept_code'] ?>';

    loadnavTabs('request-status', 'request-tab', 'requestSection', 'Request', 'req')
    loadnavTabs('received-status', 'received', 'receivedSection', 'Received', 'received')
    loadnavTabs('checked-status', 'checked', 'checkedSection', 'Checked', 'checked')
    loadnavTabs('approved-status', 'approved', 'approvedSection', 'Approved', 'approved')
    loadnavTabs('noted-status', 'noted', 'notedSection', 'Noted', 'noted')

    loadTabPane('requestSection', 'request-tab', 'notif_table', 'data')
    loadTabPane('receivedSection', 'received-tab', 'received_table', 'data-received')
    loadTabPane('checkedSection', 'checked-tab', 'checked_table', 'data-checked')
    loadTabPane('approvedSection', 'approved-tab', 'approved_table', 'data-approved')
    loadTabPane('notedSection', 'noted-tab', 'noted_table', 'data-noted')

    function loadnavTabs(status, tabs, section, label, count_name) {
        let html = `
                <li class="nav-item" id="navbars" role="presentation">
                    <button type="button" class="nav-link nav-link-notification flex-sm-fill fs-5 ${status}" id="${tabs}" onclick="tabpane();" data-bs-toggle="tab" data-bs-target="#${section}" role="tab" aria-controls="processSection" aria-selected="true">${label}
                        <span class="position-relative top-0 start-25 translate-middle badge rounded-pill bg-danger fa-fade ${count_name}-count"></span>
                    </button>
                </li>`
        $('#nav-item-append').append(html);
    }

    function loadTabPane(section, tab, table_name, data) {
        let html = `
                <div class="tab-pane fade" id="${section}" role="tabpanel" aria-labelledby="${tab}">
                    <div class="table-responsive" style="border-radius: 30px;" id="onhold_table">
                        <table id="${table_name}" class="table table-bordered table-striped fw-bold" width="100%">
                            <thead class="customHeaderNotification">
                                <tr>
                                    <th class="text-center" style="width: 40%;">System</th>
                                    <th class="text-center" style="width: 40%;">Remarks</th>
                                    <th class="text-center" style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="${data}">
                            </tbody>
                            <tbody class="data-background position-static">
                            </tbody>
                            <tfoot class="customHeaderNotification">
                            </tfoot>
                        </table>
                    </div>
                </div>`
        $('#myTabContent').append(html);
    }

    $('button').prop('disabled', false);
    $('.checked-status-1').css('display', 'none');
    $('.checked-status-2').css('display', 'none');
    $('.checked-status-3').css('display', 'none');
    $('.checked-status-4').css('display', 'none');
    $('.checked-status-5').css('display', 'none');
    $('.card-hide').css('display', 'none');

    let receivedIntervalInstance;
    let approvedIntervalInstance;
    let notedIntervalInstance;
    let checkedIntervalInstance;
    let requestIntervalInstance;

    loadNavLink();
    setTimeout(function() {
        loadNavLinkTableData();
    }, 500);

    const btnClose = () => $('#summary_modal').modal('hide');

    // function formatDate(date) {
    const formatDate = (date) => {
        const currentDate = new Date();
        const yesterday = new Date(currentDate);
        yesterday.setDate(yesterday.getDate() - 1);
        const formattedDate = new Date(date);
        if (formattedDate.toDateString() === currentDate.toDateString()) {
            return 'Today';
        } else if (formattedDate.toDateString() === yesterday.toDateString()) {
            return 'Yesterday';
        } else {
            return formattedDate.toLocaleDateString();
        }
    }

    setTimeout(() => {
        $('.hide-tr').css('display', 'none');
        $('.lds-ripple').addClass('loader--hidden');
    }, 500);
    setInterval(() => {
        setTimeout(() => {
            scanNavlink();
        }, 500);
    }, 5000);

    function tabpane() {
        // const tabpane = () => {
        $('.lds-ripple').removeClass('loader--hidden');
        setTimeout(() => {
            $('.lds-ripple').addClass('loader--hidden');
        }, 300);
    }

    const btnCancel = id => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Cancelled!',
                    'Request has been cancelled.',
                    'success'
                )
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'cancelAcknowledge',
                        table: $('#db_name').val(),
                        table_name: $('#table_name').val(),
                        table_id: $('#table_id').val(),
                        table_id_name: $('#table_id_name').val(),
                        id: id
                    }
                });
                $('.request-acknowledge-' + id).html('<i class="fa-solid fa-ban fa-shake"></i>');
                $('.request-acknowledge-' + id).removeClass('btn-info').addClass('btn-danger');

                $('.approved-acknowledge-' + id).html('<i class="fa-solid fa-ban fa-shake"></i>');
                $('.approved-acknowledge-' + id).removeClass('btn-dark').addClass('btn-danger');

                $('.noted-acknowledge-' + id).html('<i class="fa-solid fa-ban fa-shake"></i>');
                $('.noted-acknowledge-' + id).removeClass('btn-dark').addClass('btn-danger');

                $('#summary_modal').modal('hide');
            }
        })
    }

    function loadNavLinkTableData() {
        // const loadNavLinkTableData = () => {
        $('.data').html('');
        $('.data-received').html('');
        $('.data-approved').html('');
        $('.data-noted').html('');
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_nav_link',
                fullname: fullname
            },
            success: result => {
                $.each(result, (key, value) => {
                    if (key === 0) {
                        setTimeout
                        setTimeout(() => {
                            $('.card-hide').css('display', 'block');
                        }, 50);
                        $('.card-hide').fadeOut('fast', () => {
                            $(this).fadeIn('fast');
                        });
                        loadRequestEmail(value.app_name);
                    }
                });
            }
        });
    }

    function loadNavLink() {
        // const loadNavLink = () => {
        $('.loadNavLink').html('');
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_nav_link',
                fullname: fullname
            },
            success: function(result) {
                let html = '';
                var loop_count = 0;
                var icon_adjust = 0;
                $.each(result, (key, value) => {
                    if (key >= 1) {
                        loadRequestEmail(value.app_name);
                    }
                    loop_count++;
                    let icon_preview = '';
                    icon_adjust += 4;
                    icon_preview = `<span class="position-absolute top-${icon_adjust} start-100 translate-middle badge rounded-pill bg-danger total-notif-${value.app_id}"></span>`;
                    html += `<li><a class="notification-nav-link" onclick="loadRequestEmail('${value.app_name}')"><input type="hidden" class="system_name_${loop_count}" id="system_name_${loop_count}" value="${value.app_name}">${value.app_name}${icon_preview}</a></li>`;
                });
                $('.loadNavLink').append(html);
                $('.notification-nav-link:first').addClass('active');
                $('.notification-nav-link').click(function(e) {
                    e.preventDefault();
                    $('.notification-nav-link').removeClass('active');
                    $(this).addClass('active').fadeIn();
                    $('.card-hide').fadeOut('fast');
                    $('.card-hide').delay('fast').fadeIn();
                });
            }
        });
    }

    function loadRequestEmail(category) {
        // const loadRequestEmail = category => {
        // clearInterval(receivedIntervalInstance); // Stop the interval
        clearInterval(approvedIntervalInstance); // Stop the interval
        clearInterval(notedIntervalInstance); // Stop the interval
        clearInterval(checkedIntervalInstance); // Stop the interval
        clearInterval(requestIntervalInstance); // Stop the interval

        $('.nav-item').css('display', 'none');
        $('.nav-link').removeClass('ishowmo');

        $('.nav-link-notification').removeClass('active');
        $('.tab-pane').removeClass('active');
        $('.tab-pane').removeClass('show');

        setTimeout(function() {
            const ishowmoButtons = document.querySelectorAll('.nav-item .ishowmo');
            ishowmoButtons.forEach((button, index) => {
                if (index === 0) {
                    button.classList.add('active');
                    if (button.id.trim() == 'request-tab') {
                        $('.tab-pane:eq(0)').addClass('active');
                        $('.tab-pane:eq(0)').addClass('show');
                    } else if (button.id.trim() == 'received-by-tab') {
                        $('.tab-pane:eq(1)').addClass('active');
                        $('.tab-pane:eq(1)').addClass('show');
                    } else if (button.id.trim() == 'checked-tab') {
                        $('.tab-pane:eq(2)').addClass('active');
                        $('.tab-pane:eq(2)').addClass('show');
                    } else if (button.id.trim() == 'approved-tab') {
                        $('.tab-pane:eq(3)').addClass('active');
                        $('.tab-pane:eq(3)').addClass('show');
                    } else if (button.id.trim() == 'noted-tab') {
                        $('.tab-pane:eq(4)').addClass('active');
                        $('.tab-pane:eq(4)').addClass('show');
                    }
                }
            });
        }, 400);

        function startTheScanRequest() {
            setTimeout(() => {
                scanRequest(category);
            }, 1000);
        }
        // loadReceivedEmail(category);
        loadCheckedEmail(category);
        loadApprovedEmail(category);
        loadNotedEmail(category);

        $('.req-count').html('');
        $('.checked-count').html('');
        $('.received-count').html('');
        $('.approved-count').html('');
        $('.noted-count').html('');

        $('#req-count').val(0);
        $('#received-count').val(0);
        $('#checked-count').val(0);
        $('#approved-count').val(0);
        $('#noted-count').val(0);
        $('#total-count').val(0);
        switch (category) {
            case 'Physical Security':
                $('#total-count-phd').val(0);
                break;
            case 'Info Security':
                $('#total-count-info').val(0);
                break;
        }

        $('.data').html('');
        $('.data-received').html('');
        $('.data-approved').html('');
        $('.data-noted').html('');

        let objData = "";
        objData = {
            action: 'email_notification',
            fullname: fullname,
            category: category
        }
        switch (category) {
            case 'IT Repair and Request':
                requestIntervalInstance = setInterval(startTheScanRequest, 5000);
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: objData,
                    success: result => {
                        var requestNotifCount = 0;
                        var repairNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var scanDetails = 0;
                        $.each(result, (date, row) => {
                            count++;
                            $.each(row, (key, details) => {
                                scanDetails++;
                            });
                        });
                        $.each(result, (date, row) => {
                            // $('.').css('display', 'block');
                            $('.nav-item:eq(0)').css('display', 'block');
                            $('.request-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date);
                            html += '<tr>';
                            html += '<th colspan="7" class="position-relative" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_uniq' + count + '" id="date_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                            html += '</tr>';
                            $.each(row, (key, details) => {
                                scanDetails--;
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="itr' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let table_name = details.table_name == 'tblit_repair' ? 'Repair Request Main' : 'Software and Hardware Request Main';
                                // let typeOfSoftware = details.table_database == 'info_security' ? '(Web)' : '';
                                html += '<tr>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="messageStatus' + scanDetails + '" id="messageStatus' + scanDetails + '" value="' + details.cancel_status + '"><input type="hidden" class="messageBehaviorRepairStatus' + scanDetails + '" id="messageBehaviorRepairStatus' + scanDetails + '" value="' + details.repair_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + scanDetails + '" id="messageBehaviorRequestStatus' + scanDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="from_uniq' + scanDetails + '" id="from_uniq' + scanDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                // ! ===== Status Action ===== ? //
                                switch (details.table_name) {
                                    case 'tblit_request':
                                        if (details.cancel_status == true) {
                                            $icon = '<i class="fa-solid fa-ban fa-shake"></i>';
                                        } else {
                                            if (details.approved_by_acknowledge == false) {
                                                $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                            } else {
                                                if (details.noted_by_acknowledge == false) {
                                                    $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                                } else {
                                                    if (details.repair_by_acknowledge == false) {
                                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                                    } else {
                                                        if (details.prepared_by_acknowledge == false) {
                                                            requestNotifCount++;
                                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                        } else {
                                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        break;
                                    case 'tblit_repair':
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            repairNotifCount++;
                                            $button_color = 'btn-dark';
                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                        }
                                        break;
                                }
                                // ! ===== Button Action ===== ? //
                                switch (details.table_name) {
                                    case 'tblit_request':
                                        if (details.cancel_status) {
                                            $button_color = 'btn-danger';
                                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                        } else {
                                            if (details.repair_by_acknowledge == true) {
                                                if (details.prepared_by_acknowledge == true) {
                                                    $button_color = 'btn-warning';
                                                    $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                                } else {
                                                    $button_color = 'btn-dark';
                                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                                }
                                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                            } else {
                                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn btn-info repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                            }
                                        }
                                        break;
                                    case 'tblit_repair':
                                        html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + '  repair-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                        break;
                                }
                                html += '</tr>';
                            });
                        });
                        $('.data').append(html);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                        if (repairNotifCount > 0 || requestNotifCount > 0) {
                            $('#req-count').val(requestNotifCount);
                            $('#req-repair-count').val(repairNotifCount);
                            let countRequestValue = parseInt($('#req-count').val(), 10);
                            let countRepairValue = parseInt($('#req-repair-count').val(), 10);
                            let countValue = countRequestValue + countRepairValue;
                            countValue == 0 ? $('.req-count').html('') : $('.req-count').html(countValue + '+');
                            let getTotal = parseInt($('#total-count').val(), 10) + countValue;
                            getTotal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(getTotal + '+');
                            $('#total-count').val(getTotal);
                        }
                    }
                });
                break;
            case 'Physical Security':
                requestIntervalInstance = setInterval(startTheScanRequest, 5000);
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: objData,
                    success: result => {
                        var requestNotifCount = 0;
                        var repairNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var scanDetails = 0;
                        $.each(result, (date, row) => {
                            count++;
                            $.each(row, (key, details) => {
                                scanDetails++;
                            });
                        });
                        $.each(result, (date, row) => {
                            // $('.').css('display', 'block');
                            $('.nav-item:eq(0)').css('display', 'block');
                            $('.request-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date);
                            html += '<tr>';
                            html += '<th colspan="7" class="position-relative" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_uniq' + count + '" id="date_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                            html += '</tr>';
                            $.each(row, (key, details) => {
                                scanDetails--;
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="phd' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let table_name = details.table_name == 'phd_time_sync_log_header' ? 'Time Synchronization Monitoring Log Sheet' : '';
                                html += '<tr>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorNotedStatus' + scanDetails + '" id="messageBehaviorNotedStatus' + scanDetails + '" value="' + details.noted_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + scanDetails + '" id="messageBehaviorRequestStatus' + scanDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="from_uniq' + scanDetails + '" id="from_uniq' + scanDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                html += '<td style="vertical-align: middle;">' + details.remarks + '</td>';
                                if (details.checked_by_acknowledge == false) {
                                    $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                } else {
                                    if (details.noted_by_acknowledge == false) {
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    } else {
                                        if (details.prepared_by_acknowledge == false) {
                                            requestNotifCount++;
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                        }
                                    }
                                }
                                switch (details.table_name) {
                                    case 'phd_time_sync_log_header':
                                        if (details.noted_by_acknowledge == true) {
                                            if (details.prepared_by_acknowledge == true) {
                                                $button_color = 'btn-warning';
                                                $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                            } else {
                                                $button_color = 'btn-dark';
                                                $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                            }
                                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                        } else {
                                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn btn-info request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                        }
                                        break;
                                }
                                html += '</tr>';
                            });
                        });
                        $('.data').append(html);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                        $('#req-count').val(requestNotifCount);
                        let countValue = parseInt($('#req-count').val(), 10);
                        countValue == 0 ? $('.req-count').html('') : $('.req-count').html(countValue + '+');
                        let getTotal = parseInt($('#total-count-phd').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-6').html('') : $('.total-notif-6').html(getTotal + '+');
                        $('#total-count-phd').val(getTotal);
                    }
                });
                break;
            case 'Info Security':
                requestIntervalInstance = setInterval(startTheScanRequest, 5000);
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: objData,
                    success: function(result) {
                        var requestNotifCount = 0;
                        var repairNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var scanDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                scanDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            // $('.').css('display', 'block');
                            $('.nav-item:eq(0)').css('display', 'block');
                            $('.request-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date);
                            html += '<tr>';
                            html += '<th colspan="7" class="position-relative" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_uniq' + count + '" id="date_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                            html += '</tr>';
                            $.each(row, function(key, details) {
                                scanDetails--;
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="infosec' + displayDate + '">';
                                    html += '</tr>';
                                }
                                let table_name = details.table_name == 'info_sec_web_app_request' ? 'Web Application' : '';
                                html += '<tr>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorRepairStatus' + scanDetails + '" id="messageBehaviorRepairStatus' + scanDetails + '" value="' + details.repair_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + scanDetails + '" id="messageBehaviorRequestStatus' + scanDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="from_uniq' + scanDetails + '" id="from_uniq' + scanDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                html += '<td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                                // ! ===== Status Action ===== ? //
                                if (details.approved_by_acknowledge == false) {
                                    $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                } else {
                                    if (details.noted_by_acknowledge == false) {
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    } else {
                                        if (details.repair_by_acknowledge == false) {
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        } else {
                                            if (details.prepared_by_acknowledge == false) {
                                                requestNotifCount++;
                                                $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                            } else {
                                                $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                            }
                                        }
                                    }
                                }
                                // ! ===== Button Action ===== ? //
                                if (details.cancel_status) {
                                    $button_color = 'btn-danger';
                                    $icon = '<i class="fa-solid fa-ban"></i>';
                                    html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                } else {
                                    if (details.repair_by_acknowledge == true) {
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $button_color = 'btn-dark';
                                            $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                        }
                                        html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    } else {
                                        html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn btn-info request-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'request\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                    }
                                }
                                html += '</tr>';
                            });
                        });
                        $('.data').append(html);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                        $('#req-count').val(requestNotifCount);
                        let countValue = parseInt($('#req-count').val(), 10);
                        countValue == 0 ? $('.req-count').html('') : $('.req-count').html(countValue + '+');
                        let getTotal = parseInt($('#total-count-info').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(getTotal + '+');
                        $('#total-count-info').val(getTotal);
                    }
                });
                break;
        }
    }

    // const loadCheckedEmail = category => {
    function loadCheckedEmail(category) {
        $('.data-checked').html('');

        function startTheScanChecked() {
            setTimeout(function() {
                scanChecked(category);
            }, 1000);
        }
        switch (category) {
            case 'Physical Security':
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_checked',
                        fullname: fullname,
                        category: category
                    },
                    success: function(result) {
                        var checkedNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var countDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                countDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            // $('.').css('display', 'block');
                            $('.nav-item:eq(2)').css('display', 'block');
                            $('.checked-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date); // Format the date
                            html += '<tr>';
                            html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_checked_uniq' + count + '" id="date_checked_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                            html += '</tr>';
                            $.each(row, function(key, details) {
                                countDetails--;
                                let table_name = details.table_name == 'phd_time_sync_log_header' ? 'Time Synchronization Monitoring Log Sheet' : '';
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="checked_' + displayDate + '">';
                                    html += '</tr>';
                                }
                                html += '<tr>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="checked_details_uniq' + countDetails + '" id="checked_details_uniq' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorCheckedStatus' + countDetails + '" id="messageBehaviorCheckedStatus' + countDetails + '" value="' + details.checked_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + countDetails + '" id="messageBehaviorRequestStatus' + countDetails + '" value="' + details.prepared_by_acknowledge + '">' + details.remarks + '</td>';

                                if (details.checked_by_acknowledge == false) {
                                    checkedNotifCount++;
                                    $button_color = 'btn-dark';
                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                } else {
                                    if (details.noted_by_acknowledge == true) {
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $button_color = 'btn-info';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        }
                                    } else {
                                        $button_color = 'btn-info';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    }
                                }
                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' btn-acknowledge-' + details.notificationid + ' checked-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'checked\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                html += '</tr>';
                            });
                        });
                        $('.data-checked').append(html);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                        $('#checked-count').val(checkedNotifCount);
                        let countValue = parseInt($('#checked-count').val(), 10);
                        countValue == 0 ? $('.checked-count').html('') : $('.checked-count').html(countValue + '+');

                        let getTotal = parseInt($('#total-count-phd').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-6').html('') : $('.total-notif-6').html(getTotal + '+');
                        $('#total-count-phd').val(getTotal);
                    }
                })
                checkedIntervalInstance = setInterval(startTheScanChecked, 5000);
                break;
            default:
                clearInterval(checkedIntervalInstance); // Stop the interval
        }
    }

    // const loadReceivedEmail = category => {
    function loadReceivedEmail(category) {
        function startTheScanReceived() {
            setTimeout(function() {
                scanReceived(category);
            }, 1000);
        }
        $('.data-received').html('');
        switch (category) {
            case 'Info Security':
                $.ajax({
                    url: '../controller/notification_controller/notification_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'email_received',
                        department: department,
                        fullname: fullname,
                        category: category
                    },
                    success: function(result) {
                        var receivedNotifCount = 0;
                        let html = '';
                        var count = 0;
                        var countDetails = 0;
                        $.each(result, function(date, row) {
                            count++;
                            $.each(row, function(key, details) {
                                countDetails++;
                            });
                        });
                        $.each(result, function(date, row) {
                            // $('.').css('display', 'block');
                            $('.nav-item:eq(1)').css('display', 'block');
                            $('.received-status').addClass('ishowmo');
                            count--;
                            let displayDate = formatDate(date); // Format the date
                            html += '<tr>';
                            html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_receive_uniq' + count + '" id="date_receive_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                            html += '</tr>';
                            $.each(row, function(key, details) {
                                countDetails--;
                                if (key === 0) {
                                    html += '<tr class="hide-tr" id="received_' + displayDate + '">';
                                    html += '</tr>';
                                }

                                let table_name = details.table_name == 'info_sec_web_app_request' ? 'Web Application' : '';
                                html += '<tr>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="receive_details_uniq' + countDetails + '" id="receive_details_uniq' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorReceivedWebStatus' + countDetails + '" id="messageBehaviorReceivedWebStatus' + countDetails + '" value="' + details.received_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';

                                if (details.received_by_acknowledge == false) {
                                    receivedNotifCount++;
                                    $button_color = 'btn-dark';
                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                } else {
                                    if (details.approved_by_acknowledge == true) {
                                        if (details.prepared_by_acknowledge == true) {
                                            $button_color = 'btn-warning';
                                            $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                        } else {
                                            $button_color = 'btn-info';
                                            $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                        }
                                    } else {
                                        $button_color = 'btn-info';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    }
                                }
                                html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' btn-acknowledge-' + details.notificationid + ' received-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'received\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                                html += '</tr>';
                            });
                        });
                        $('.data-received').append(html);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                        $('#received-count').val(receivedNotifCount);
                        let countValue = parseInt($('#received-count').val(), 10);
                        countValue == 0 ? $('.received-count').html('') : $('.received-count').html(countValue + '+');
                        // getTotalCount(countValue);
                        let getTotal = parseInt($('#total-count-info').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(getTotal + '+');
                        $('#total-count-info').val(getTotal);
                    }
                });
                receivedIntervalInstance = setInterval(startTheScanReceived, 5000);
                break;
            case 'Physical Security':
                clearInterval(receivedIntervalInstance); // Stop the interval
                $('.data-received').html('');
                break;
        }
    }

    const loadApprovedEmail = category => {
        $('.data-approved').html('');

        function startTheScanApproved() {
            setTimeout(function() {
                scanApproved(category);
            }, 1000);
        }
        if (category == 'IT Repair and Request' || category == 'Info Security') {
            approvedIntervalInstance = setInterval(startTheScanApproved, 5000);
            $.ajax({
                url: '../controller/notification_controller/notification_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'email_approved_and_noted',
                    fullname: fullname,
                    email_type: 'approved_by',
                    category: category
                },
                success: function(result) {
                    var approvedNotifCount = 0;
                    let html = '';
                    var count = 0;
                    var countDetails = 0;
                    $.each(result, function(date, row) {
                        count++;
                        $.each(row, function(key, details) {
                            countDetails++;
                        });
                    });
                    $.each(result, function(date, row) {
                        // $('.').css('display', 'block');
                        $('.nav-item:eq(3)').css('display', 'block');
                        $('.approved-status').addClass('ishowmo');
                        count--;
                        let displayDate = formatDate(date); // Format the dat
                        html += '<tr>';
                        html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_approved_uniq' + count + '" id="date_approved_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                        html += '</tr>';
                        $.each(row, function(key, details) {
                            countDetails--;
                            let table_name;
                            if (details.table_name == 'tblit_repair') {
                                table_name = 'Repair Request Main';
                            } else if (details.table_name == 'tblit_request') {
                                table_name = 'Software and Hardware Request Main';
                            } else if (details.table_name == 'info_sec_web_app_request') {
                                table_name = 'Web Application';
                            }

                            if (key === 0) {
                                html += '<tr class="hide-tr" id="approved_' + displayDate + '">';
                                html += '</tr>';
                            }
                            html += '<tr>';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorRepairStatus' + countDetails + '" id="messageBehaviorRepairStatus' + countDetails + '" value="' + details.repair_by_acknowledge + '"><input type="hidden" class="messageBehaviorRequestStatus' + countDetails + '" id="messageBehaviorRequestStatus' + countDetails + '" value="' + details.prepared_by_acknowledge + '"><input type="hidden" class="date_receive_details_uniq_' + countDetails + '" id="date_receive_details_uniq_' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                            // html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorApprovedStatus' + countDetails + '" id="messageBehaviorApprovedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="messageStatus' + countDetails + '" id="messageStatus' + countDetails + '" value="' + details.cancel_status + '"><input type="hidden" class="messageBehaviorApprovedStatus' + countDetails + '" id="messageBehaviorApprovedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                            if (details.cancel_status == true) {
                                $button_color = 'btn-danger';
                                $icon = '<i class="fa-solid fa-ban fa-shake"></i>';
                            } else {
                                if (details.approved_by_acknowledge == true) {
                                    if (details.prepared_by_acknowledge == true) {
                                        $button_color = 'btn-warning';
                                        $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                    } else {
                                        $button_color = 'btn-info';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    }
                                } else {
                                    approvedNotifCount++;
                                    $button_color = 'btn-dark';
                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                }
                            }
                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' approved-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'approved\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                            html += '</tr>';
                        });
                    });
                    $('.data-approved').append(html);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                    if (category == 'Info Security') {
                        $('#approved-count').val(approvedNotifCount);
                        let countValue = parseInt($('#approved-count').val(), 10);
                        countValue == 0 ? $('.approved-count').html('') : $('.approved-count').html(countValue + '+');
                        // getTotalCount(countValue);
                        let getTotal = parseInt($('#total-count-info').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(getTotal + '+');
                        $('#total-count-info').val(getTotal);
                    } else {

                        $('#approved-count').val(approvedNotifCount);
                        let countValue = parseInt($('#approved-count').val(), 10);
                        countValue == 0 ? $('.approved-count').html('') : $('.approved-count').html(countValue + '+');
                        let getTotal = parseInt($('#total-count').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(getTotal + '+');
                        $('#total-count').val(getTotal);
                    }
                }
            });
        } else if (category == 'Physical Security') {
            clearInterval(approvedIntervalInstance); // Stop the interval
            $('.data-approved').html('');
        }
    }

    const loadNotedEmail = category => {
        $('.data-noted').html('');

        function startTheScanNoted() {
            setTimeout(function() {
                scanNoted(category);
                buttonScanning(category);
            }, 1000);
        }
        if (category == 'IT Repair and Request' || category == 'Info Security') {
            clearInterval(notedIntervalInstance); // Stop the interval
            $.ajax({
                url: '../controller/notification_controller/notification_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'email_approved_and_noted',
                    fullname: fullname,
                    email_type: 'noted_by',
                    category: category
                },
                success: function(result) {
                    var notedNotifCount = 0;
                    let html = '';
                    var count = 0;
                    var countDetails = 0;
                    $.each(result, function(date, row) {
                        count++;
                        $.each(row, function(key, details) {
                            countDetails++;
                        });
                    });
                    $.each(result, function(date, row) {
                        // $('.').css('display', 'block');
                        $('.nav-item:eq(4)').css('display', 'block');
                        $('.noted-status').addClass('ishowmo');
                        count--;
                        let displayDate = formatDate(date); // Format the date
                        html += '<tr>';
                        html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_noted_uniq' + count + '" id="date_noted_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                        html += '</tr>';
                        $.each(row, function(key, details) {
                            countDetails--;
                            let table_name;
                            if (details.table_name == 'tblit_repair') {
                                table_name = 'Repair Request Main';
                            } else if (details.table_name == 'tblit_request') {
                                table_name = 'Software and Hardware Request Main';
                            } else if (details.table_name == 'info_sec_web_app_request') {
                                table_name = 'Web Application';
                            }
                            if (key === 0) {
                                html += '<tr class="hide-tr" id="noted_' + displayDate + '">';
                                html += '</tr>';
                            }
                            html += '<tr>';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="note_details_uniq_' + countDetails + '" id="note_details_uniq_' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + ' </td>';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="messageStatus' + countDetails + '" id="messageStatus' + countDetails + '" value="' + details.cancel_status + '"><input type="hidden" class="messageBehaviorApprovedStatus' + countDetails + '" id="messageBehaviorApprovedStatus' + countDetails + '" value="' + details.approved_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">&nbsp;&nbsp;&nbsp;&nbsp;' + details.remarks + '</td>';
                            if (details.cancel_status == true) {
                                $button_color = 'btn-danger';
                                $icon = '<i class="fa-solid fa-ban fa-shake"></i>';
                            } else {
                                if (details.noted_by_acknowledge == true) {
                                    if (details.prepared_by_acknowledge == true) {
                                        $button_color = 'btn-warning';
                                        $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                    } else {
                                        $button_color = 'btn-info';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    }
                                } else {
                                    if (details.approved_by_acknowledge == false) {
                                        $button_color = 'btn-warning';
                                        $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                    } else {
                                        notedNotifCount++;
                                        $button_color = 'btn-dark';
                                        $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                    }
                                }
                            }
                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' noted-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'noted\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                            html += '</tr>';
                        });
                    });
                    $('.data-noted').append(html);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                    if (category == 'Info Security') {
                        $('#noted-count').val(notedNotifCount);
                        let countValue = parseInt($('#noted-count').val(), 10);
                        countValue == 0 ? $('.noted-count').html('') : $('.noted-count').html(countValue + '+');
                        // getTotalCount(countValue);
                        let getTotal = parseInt($('#total-count-info').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(getTotal + '+');
                        $('#total-count-info').val(getTotal);
                    } else {

                        $('#noted-count').val(notedNotifCount);
                        let countValue = parseInt($('#noted-count').val(), 10);
                        countValue == 0 ? $('.noted-count').html('') : $('.noted-count').html(countValue + '+');
                        let getTotal = parseInt($('#total-count').val(), 10) + countValue;
                        getTotal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(getTotal + '+');
                        $('#total-count').val(getTotal);
                    }
                }
            });
            notedIntervalInstance = setInterval(startTheScanNoted, 5000);

        } else if (category == 'Physical Security') {
            clearInterval(notedIntervalInstance); // Stop the interval
            $('.data-noted').html('');
            $.ajax({
                url: '../controller/notification_controller/notification_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'email_approved_and_noted',
                    fullname: fullname,
                    email_type: 'noted_by',
                    category: category
                },
                success: function(result) {
                    var notedPhdCount = 0;
                    let html = '';
                    var count = 0;
                    var countDetails = 0;
                    $.each(result, function(date, row) {
                        count++;
                        $.each(row, function(key, details) {
                            countDetails++;
                        });
                    });
                    $.each(result, function(date, row) {
                        // $('.').css('display', 'block');
                        $('.nav-item:eq(4)').css('display', 'block');
                        $('.noted-status').addClass('ishowmo');
                        count--;
                        let displayDate = formatDate(date); // Format the date
                        html += '<tr>';
                        html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;"><input type="hidden" class="date_noted_uniq' + count + '" id="date_noted_uniq' + count + '" value="' + date + '">&nbsp;&nbsp;' + displayDate + '</th>';
                        html += '</tr>';
                        $.each(row, function(key, details) {
                            countDetails--;
                            let table_name = details.table_name == 'phd_time_sync_log_header' ? 'Time Synchronization Monitoring Log Sheet' : '';
                            if (key === 0) {
                                html += '<tr class="hide-tr" id="noted_phd' + displayDate + '">';
                                html += '</tr>';
                            }
                            html += '<tr>';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="note_details_uniq_' + countDetails + '" id="note_details_uniq_' + countDetails + '" value="' + details.notificationid + '">&nbsp;&nbsp;&nbsp;&nbsp;' + table_name + '</td>';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="messageBehaviorCheckedStatus' + countDetails + '" id="messageBehaviorCheckedStatus' + countDetails + '" value="' + details.checked_by_acknowledge + '"><input type="hidden" class="messageBehaviorNotedStatus' + countDetails + '" id="messageBehaviorNotedStatus' + countDetails + '" value="' + details.noted_by_acknowledge + '">' + details.remarks + '</td>';
                            if (details.noted_by_acknowledge == true) {
                                if (details.prepared_by_acknowledge == true) {
                                    $button_color = 'btn-warning';
                                    $icon = '<i class="fa-solid fa-envelope-open-text fa-fade"></i>';
                                } else {
                                    $button_color = 'btn-info';
                                    $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                }
                            } else {
                                if (details.checked_by_acknowledge == false) {
                                    $button_color = 'btn-warning';
                                    $icon = '<i class="fa-solid fa-circle-info fa-bounce"></i>';
                                } else {
                                    notedPhdCount++;
                                    $button_color = 'btn-dark';
                                    $icon = '<i class="fa-regular fa-envelope fa-shake"></i>';
                                }
                            }
                            html += '<td style="vertical-align: middle; text-align: center;"><button type="button" class="btn ' + $button_color + ' noted-acknowledge-' + details.notificationid + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnSummary(\'' + details.notificationid + '\',\'' + details.table_database + '\',\'' + details.table_name + '\',\'noted\',\'' + details.table_field_id + '\',\'' + details.table_field_id_name + '\',\'' + details.prepared_by_acknowledge + '\',\'' + details.checked_by_acknowledge + '\',\'' + details.approved_by_acknowledge + '\',\'' + details.noted_by_acknowledge + '\',\'' + details.repair_by_acknowledge + '\',\'' + details.received_by_acknowledge + '\',\'' + details.prepared_by + '\',\'' + details.checked_by + '\',\'' + details.approved_by + '\',\'' + details.noted_by + '\',\'' + details.repair_by + '\',\'' + details.received_by + '\',\'' + details.prepared_by_date + '\',\'' + details.field1 + '\',\'' + details.field2 + '\',\'' + details.field3 + '\',\'' + details.field4 + '\',\'' + details.field5 + '\',\'' + details.field6 + '\',\'' + details.field7 + '\');">' + $icon + '</button></td>';
                            html += '</tr>';
                        });
                    });
                    $('.data-noted').append(html);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                    $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========

                    $('#noted-count').val(notedPhdCount);
                    let countValue = parseInt($('#noted-count').val(), 10);
                    countValue == 0 ? $('.noted-count').html('') : $('.noted-count').html(countValue + '+');
                    // getTotalCount(countValue);
                    let getTotal = parseInt($('#total-count-phd').val(), 10) + countValue;
                    getTotal == 0 ? $('.total-notif-6').html('') : $('.total-notif-6').html(getTotal + '+');
                    $('#total-count-phd').val(getTotal);
                }
            });
            notedIntervalInstance = setInterval(startTheScanNoted, 5000);
        }
    }

    // ? ACKNOWLEDGE RECEIVED FUNCTIONS
    const btnReceivedAcknowledge = id => {
        $('.btn-acknowledge-' + id).html('<i class="fa-solid fa-envelope-open-text fa-fade"></i>');
        $('.btn-acknowledge-' + id).removeClass('btn-dark').addClass('btn-warning');

        let totalCountInReceive = parseInt($('#received-count').val(), 10);
        let subtractedCount = totalCountInReceive - 1;

        $('#received-count').val(subtractedCount);
        $('.received-count').html(subtractedCount == 0 ? '' : subtractedCount + '+');

        // ? Total Count Calculation
        let totalVal = parseInt($('#total-count-info').val(), 10) - 1;
        $('#total-count-info').val(totalVal)
        totalVal == 0 ? $('.total-notif-4').html('') : $('.total-notif-4').html(totalVal + '+');

        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            data: {
                action: 'received_acknowledge',
                id: id,
                table: $('#db_name').val(),
                table_id: $('#table_id').val(),
                table_id_name: $('#table_id_name').val(),
                table_name: $('#table_name').val(),
                fullname: fullname
            },
            success: function(result) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Email has been Succesfully Acknowledge',
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#summary_modal').modal('hide');
            }
        });
    }

    // ? ACKNOWLEDGE FUNCTIONS
    function btnAcknowledge(id) {
        let navbarLocation = $('#navbar-location').val();
        switch (navbarLocation) {
            case 'request':
                let table_name = $('#table_name').val();
                if (table_name == 'tblit_repair') {
                    $('.repair-acknowledge-' + id).html('<i class="fa-solid fa-envelope-open-text fa-fade"></i>');
                    $('.repair-acknowledge-' + id).removeClass('btn-dark').addClass('btn-warning');
                    let totalInValue = parseInt($('#req-count').val(), 10);
                    let totalValue = totalInValue - 1;

                    //  ? Request Total Count Calculation
                    $('#req-count').val(totalValue);
                    $('.req-count').html(totalValue == 0 ? '' : totalValue + '+');

                    // ? Total Count Calculation
                    let totalVal = parseInt($('#total-count').val(), 10) - 1;
                    $('#total-count').val(totalVal)
                    totalVal == 0 ? $('.total-notif-8').html('') : $('.total-notif-8').html(totalVal + '+');
                }
                $('.request-acknowledge-' + id).html('<i class="fa-solid fa-envelope-open-text fa-fade"></i>');
                $('.request-acknowledge-' + id).removeClass('btn-dark').addClass('btn-warning');
                break;
            case 'checked':
                $('.checked-acknowledge-' + id).html('<i class="fa-solid fa-circle-info fa-bounce"></i>');
                $('.checked-acknowledge-' + id).removeClass('btn-dark').addClass('btn-info');
                break;
            case 'approved':
                $('.noted-acknowledge-' + id).html('<i class="fa-regular fa-envelope fa-shake"></i>');
                $('.noted-acknowledge-' + id).removeClass('btn-warning').addClass('btn-dark');
                break;
            case 'noted':
                $('.noted-acknowledge-' + id).html('<i class="fa-solid fa-circle-info fa-bounce"></i>');
                $('.noted-acknowledge-' + id).removeClass('btn-dark').addClass('btn-info');
                break;
        }
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            data: {
                action: 'acknowledge',
                id: id,
                table: $('#db_name').val(),
                table_id: $('#table_id').val(),
                table_id_name: $('#table_id_name').val(),
                table_name: $('#table_name').val()
            },
            success: function(result) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Email has been Succesfully Acknowledge',
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#summary_modal').modal('hide');
            }
        });
    }

    // ? PREVIEW FUNCTION
    function btnSummary(id, db_table, table_name, btnLocation, tfid, tfid_name, prepared_acknowledge, checked_acknowledge, approved_acknowledge, noted_acknowledge, repair_acknowledge, received_acknowledge, prepared_by, checked_by, approved_by, noted_by, repaired_by, received_by, prepared_by_date, f1, f2, f3, f4, f5, f6, f7) {
        $('#navbar-location').val(btnLocation);
        $('.hide-footer-received').css('display', 'none');
        $('.hide-footer').css('display', 'none');
        $('.btn-cancel').hide();

        $('#table_id').val(tfid);
        $('#db_name').val(db_table);
        $('#table_id_name').val(tfid_name);
        $('#table_name').val(table_name)
        $('#notif_to').val(prepared_by);

        $('.repair_modal_body').css('display', 'none');
        $('.request_modal_body').css('display', 'none');
        $('.request_web_modal_body').css('display', 'none');
        $('.checked_modal_body').css('display', 'none');

        let objData = "";
        objData = {
            action: 'btnPreviewRequest',
            id: id,
            db_table: db_table,
            table_name: table_name,
            tfid: tfid,
            tfid_name: tfid_name,
            f1: f1,
            f2: f2,
            f3: f3,
            f4: f4,
            f5: f5,
            f6: f6,
            f7: f7
        }
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: objData,
            success: function(result) {
                // ? checked status
                result.prepared_by_acknowledge == true ? $('.checked-status-1').css('display', 'block') : $('.checked-status-1').css('display', 'none');
                result.approved_by_acknowledge == true ? $('.checked-status-2').css('display', 'block') : $('.checked-status-2').css('display', 'none');
                result.noted_by_acknowledge == true ? $('.checked-status-3').css('display', 'block') : $('.checked-status-3').css('display', 'none');
                result.checked_by_acknowledge == true ? $('.checked-status-5').css('display', 'block') : $('.checked-status-5').css('display', 'none');
                switch (btnLocation) {
                    case 'request':
                        result.approved_by_acknowledge == true && result.noted_by_acknowledge == true && result.repaired_by_acknowledge == true && result.prepared_by_acknowledge == false ?
                            $('.hide-footer').css('display', 'block') : $('.hide-footer').css('display', 'none');
                        switch (table_name) {
                            case 'tblit_repair':
                                result.repaired_by_acknowledge == true ? $('.checked-status-4').css('display', 'block') : $('.checked-status-4').css('display', 'none');
                                result.noted_by_acknowledge == false ? $('.checked-status-3').css('display', 'block') : $('.checked-status-3').css('display', 'none');
                                result.prepared_by_acknowledge == true ? $('.hide-footer').css('display', 'none') : $('.hide-footer').css('display', 'block');
                                $('#location-value').val(result.location);
                                $('#area-value').val(result.area);
                                $('#item-value').val(result.item);
                                $('#remarks-value').val(result.remarks);
                                $('#queue-value').val(result.queue_number);

                                $('#repaired_by_itr').val(repaired_by == 'null' ? '-' : repaired_by);
                                $('#noted_by_itr').val('Oliver Razalan');
                                $('#requested_by_itr').val(prepared_by);

                                $('.btn-accomplish').val(id);
                                $('.btn-cancel').val(id);
                                $('.repair_modal_body').css('display', 'block');
                                break;
                            case 'tblit_request':
                                result.repaired_by_acknowledge == true ? $('.checked-status-4').css('display', 'block') : $('.checked-status-4').css('display', 'none');
                                $('#request-type-value').val(result.request_type);
                                $('#software-type-value').val(result.software_type);
                                $('#date-needed-value').val(result.date_needed);
                                $('#date-requested-value').val(prepared_by_date);
                                $('#item-request-value').val(result.item);
                                // $('#repair-by-value').val(repaired_by == 'null' ? '-' : repaired_by);
                                $('#description-value').val(result.description);
                                $('#purpose-value').val(result.purpose);
                                $('#requested_by').val(prepared_by);
                                $('#approved_by').val(approved_by);
                                $('#noted_by').val(noted_by);
                                $('#repaired_by').val(result.repaired_by == null ? '-' : result.repaired_by);
                                $('.btn-accomplish').val(id);

                                $('#status-value').val(result.status);
                                $('.btn-cancel').val(id);

                                $('.request_modal_body').css('display', 'block');
                                break;
                            case 'info_sec_web_app_request':
                                result.approved_by_acknowledge == true && result.noted_by_acknowledge == true && result.prepared_by_acknowledge == false ?
                                    $('.hide-footer').css('display', 'block') : $('.hide-footer').css('display', 'none');
                                result.noted_by_acknowledge == true ? $('.checked-status-4').css('display', 'block') : $('.checked-status-4').css('display', 'none');
                                $('#queue-value-web').val(result.control_no);
                                $('#service-type-web').val(result.service_type);
                                $('#app-name-web').val(result.application_name);
                                $('#web-priority-web').val(result.web_priority);
                                $('#repaired_by_web').val(received_by == 'null' ? '-' : received_by);
                                $('#approved_by_web').val(approved_by);
                                $('#noted_by_web').val(noted_by);
                                $('#requested_by_web').val(prepared_by);
                                $('.btn-accomplish').val(id);
                                $('.btn-cancel').val(id);
                                $('.request_web_modal_body').css('display', 'block');
                                break;
                            case 'phd_time_sync_log_header':
                                result.checked_by_acknowledge == true && result.noted_by_acknowledge == true && result.prepared_by_acknowledge == false ?
                                    $('.hide-footer').css('display', 'block') : $('.hide-footer').css('display', 'none');
                                $('#referrence-value').val(result.timesync_ref_no);
                                $('#date-created-value').val(result.date_created);
                                $('#prepared_by_phd').val(prepared_by);
                                $('#checked_by_phd').val(checked_by);
                                $('#noted_by_phd').val(noted_by);
                                $('.btn-accomplish').val(id);
                                $('.btn-cancel').val(id);
                                $('.checked_modal_body').css('display', 'block');
                                break;
                        }
                        break;
                    case 'received':
                        result.received_by_acknowledge == true ? $('.hide-footer-received').css('display', 'none') : $('.hide-footer-received').css('display', 'block');
                        result.noted_by_acknowledge == true ? $('.checked-status-4').css('display', 'block') : $('.checked-status-4').css('display', 'none');
                        $('#queue-value-web').val(result.control_no);
                        $('#service-type-web').val(result.service_type);
                        $('#app-name-web').val(result.application_name);
                        $('#web-priority-web').val(result.web_priority);
                        $('#repaired_by_web').val(received_by == 'null' ? '-' : received_by);
                        $('#approved_by_web').val(approved_by);
                        $('#noted_by_web').val(noted_by);
                        $('#requested_by_web').val(prepared_by);
                        $('.btn-received-accomplish').val(id);
                        $('.request_web_modal_body').css('display', 'block');
                        break;
                    case 'checked':
                        result.checked_by_acknowledge == true ? $('.hide-footer').css('display', 'none') : $('.hide-footer').css('display', 'block');
                        $('#referrence-value').val(result.timesync_ref_no);
                        $('#date-created-value').val(result.date_created);
                        $('#prepared_by_phd').val(prepared_by);
                        $('#checked_by_phd').val(checked_by);
                        $('#noted_by_phd').val(noted_by);
                        $('.btn-accomplish').val(id);
                        $('.btn-cancel').val(id);
                        $('.checked_modal_body').css('display', 'block');
                        break;
                    case 'approved':
                        result.repaired_by_acknowledge == true ? $('.checked-status-4').css('display', 'block') : $('.checked-status-4').css('display', 'none');
                        // ? footer status
                        if (result.status == 'Cancelled' || result.approved_by_acknowledge == true) {
                            $('.hide-footer').css('display', 'none');
                            $('.btn-cancel').hide();
                        } else {
                            $('.btn-cancel').show();
                            result.approved_by_acknowledge == true ? $('.hide-footer').css('display', 'none') : $('.hide-footer').css('display', 'block');
                        }
                        switch (table_name) {
                            case 'tblit_request':
                                $('#request-type-value').val(result.request_type);
                                $('#software-type-value').val(result.software_type);
                                $('#date-needed-value').val(result.date_needed);
                                $('#date-requested-value').val(prepared_by_date);
                                $('#item-request-value').val(result.item);
                                $('#description-value').val(result.description);
                                $('#purpose-value').val(result.purpose);
                                // $('#repair-by-value').val(repaired_by == 'null' ? '-' : repaired_by);
                                $('.btn-accomplish').val(id);

                                $('#status-value').val(result.status);
                                $('.btn-cancel').val(id);

                                $('.request_modal_body').css('display', 'block');
                                $('#approved_by').val(approved_by);
                                $('#noted_by').val(noted_by);
                                $('#repaired_by').val(result.repaired_by == null ? '-' : result.repaired_by);
                                $('#requested_by').val(prepared_by);
                                break;
                            case 'info_sec_web_app_request':
                                $('#queue-value-web').val(result.control_no);
                                $('#service-type-web').val(result.service_type);
                                $('#app-name-web').val(result.application_name);
                                $('#web-priority-web').val(result.web_priority);
                                $('.btn-accomplish').val(id);
                                $('.btn-cancel').val(id);
                                $('.request_web_modal_body').css('display', 'block');
                                $('#repaired_by_web').val(received_by == 'null' ? '-' : received_by);
                                $('#approved_by_web').val(approved_by);
                                $('#noted_by_web').val(noted_by);
                                $('#requested_by_web').val(prepared_by);
                                break;
                        }
                        break;
                    case 'noted':
                        // ? footer status
                        result.noted_by_acknowledge == false && result.approved_by_acknowledge == false ||
                            result.noted_by_acknowledge == true && result.approved_by_acknowledge == true ?
                            $('.hide-footer').css('display', 'none') : $('.hide-footer').css('display', 'block');
                        result.repaired_by_acknowledge == true ? $('.checked-status-4').css('display', 'block') : $('.checked-status-4').css('display', 'none');
                        switch (table_name) {
                            case 'tblit_request':
                                $('#request-type-value').val(result.request_type);
                                $('#software-type-value').val(result.software_type);
                                $('#date-needed-value').val(result.date_needed);
                                $('#date-requested-value').val(prepared_by_date);
                                $('#item-request-value').val(result.item);
                                $('#description-value').val(result.description);
                                $('#purpose-value').val(result.purpose);
                                // $('#repair-by-value').val(repaired_by == 'null' ? '-' : repaired_by);
                                $('.btn-accomplish').val(id);

                                $('#status-value').val(result.status);
                                $('.btn-cancel').val(id);

                                $('.request_modal_body').css('display', 'block');
                                $('#approved_by').val(approved_by);
                                $('#noted_by').val(noted_by);
                                $('#repaired_by').val(result.repaired_by == null ? '-' : result.repaired_by);
                                $('#requested_by').val(prepared_by);
                                break;
                            case 'info_sec_web_app_request':
                                $('#queue-value-web').val(result.control_no);
                                $('#service-type-web').val(result.service_type);
                                $('#app-name-web').val(result.application_name);
                                $('#web-priority-web').val(result.web_priority);
                                $('.btn-accomplish').val(id);
                                $('.btn-cancel').val(id);
                                $('.request_web_modal_body').css('display', 'block');
                                $('#repaired_by_web').val(received_by == 'null' ? '-' : received_by);
                                $('#approved_by_web').val(approved_by);
                                $('#noted_by_web').val(noted_by);
                                $('#requested_by_web').val(prepared_by);
                                break;
                            case 'phd_time_sync_log_header':
                                result.noted_by_acknowledge == false && result.checked_by_acknowledge == false ||
                                    result.noted_by_acknowledge == true && result.checked_by_acknowledge == true ?
                                    $('.hide-footer').css('display', 'none') : $('.hide-footer').css('display', 'block');
                                $('#referrence-value').val(result.timesync_ref_no);
                                $('#date-created-value').val(result.date_created);
                                $('#prepared_by_phd').val(prepared_by);
                                $('#checked_by_phd').val(checked_by);
                                $('#noted_by_phd').val(noted_by);
                                $('.btn-accomplish').val(id);
                                $('.btn-cancel').val(id);
                                $('.checked_modal_body').css('display', 'block');
                                break;
                        }
                        break;
                }
            }
        });
        $('#summary_modal').modal('show');
    }
</script>
<script src="scan.js"></script>