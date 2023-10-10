<?php include './../includes/header.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../vendor/images/Banner-Logo.png" sizes="16x16" type="image/png">

    <title>Notification</title>
</head>
<style>
    ::-webkit-scrollbar {
        width: 0.7vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #4adede;
        border-radius: 200vw;
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    /* .pagination {
        --bs-pagination-active-bg: #fff;
        --bs-pagination-active-color: black;
        --bs-pagination-active-border-color: #4adede;
        --bs-pagination-disabled-bg: #4adede !important;
    } */
</style>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fa-solid fa-envelope-open-text me-3"></i>Notifications
            <button class="btn btn-warning fw-bold btn-archive" value="archive" onclick="ArchiveFunction(this.value);">ARCHIVE <i class="fa-solid fa-box-archive fa-bounce"></i></button>
        </h1>
        <a class="btn-back" href="../index.php">
            <h4><i class="fa-solid fa-arrow-left me-1"></i>Back</h4>
        </a>
    </div>
    <hr>
    <div class="row">
        <input type="hidden" id="archive" value="not_archive" placeholder="appid-value">

        <input type="hidden" id="btn-location" value="" placeholder="location-value">
        <input type="hidden" id="appid-contain" value="" placeholder="appid-value">

        <input type="hidden" id="db_name" class="form-control fw-bold">
        <input type="hidden" id="table_name" class="form-control fw-bold">
        <input type="hidden" id="table_id" class="form-control fw-bold">
        <input type="hidden" id="table_id_name" class="form-control fw-bold">
        <span class="remarks-request-value"></span>
        <!-- ==================== Navbar Section ==================== -->
        <div class="col-md-3 d-flex justify-content-center d-md-block">
            <ul class="notification-nav mt-md-5 mt-0 loadNavLink"></ul>
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
                    <div class="headerTable mb-3">
                        <span>System</span>
                        <span>Remarks</span>
                        <span>Status</span>
                    </div>
                    <div class="tab-content" id="myTabContent">
                    </div>
                    <div class="footerTable mb-3">
                        <span>System</span>
                        <span>Remarks</span>
                        <span>Status</span>
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
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">DETAILS</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="grid-container">
                    <div id="loading" style="display: none;">Loading...</div>
                </div>
                <div class="row">
                    <div class="col-sm d-flex justify-content-center">
                        <hr color="red" size="2" width="15%" align="center">
                        <span class="fw-bold text-primary">SignStatus</span>
                        <hr color="red" size="2" width="85%" align="center">
                    </div>
                </div>
                <div class="row append-assignatory"></div>
            </div>
            <!-- <div class="modal-footer"> -->
            <div class="d-grid gap-1 mb-3 px-3 p-t-15 append-button"></div>
        </div>
    </div>
</div>
<script>
    var fullname = '<?php echo $_SESSION['fullname']; ?>';
    var department = '<?php echo $_SESSION['dept_code']; ?>';
    loadNavLink();
    loadTabPane('requestSection', 'request-tab', 'prepared_table', 'data');
    loadTabPane('checkedSection', 'checked-tab', 'checked_table', 'data-checked');
    // loadTabPane('receivedSection', 'received-tab', 'received_table', 'data-received')
    loadTabPane('approvedSection', 'approved-tab', 'approved_table', 'data-approved');
    loadTabPane('notedSection', 'noted-tab', 'noted_table', 'data-noted');

    function ArchiveFunction(value) {
        if (value == 'archive') {
            $('.btn-archive').html('BACK <i class="fa-solid fa-backward fa-bounce"></i>');
            $('.btn-archive').val('notifications');
            $('.loadNavLink').html('');
            $('#archive').val('archive');
            loadNavLink();
        } else if (value == 'notifications') {
            $('.btn-archive').html('ARCHIVE <i class="fa-solid fa-box-archive fa-bounce"></i>');
            $('.btn-archive').val('archive');
            $('.loadNavLink').html('');
            $('#archive').val('not_archive');
            loadNavLink();
        }
    }

    function loadTabPane(section, tab, table_name, data) {
        let html = `<div class="tab-pane fade" id="${section}" role="tabpanel" aria-labelledby="${tab}">
                        <div class="table-responsive" id="onhold_table">
                            <table id="${table_name}" class="table fw-bold" width="100%">
                                <thead class="customHeaderNotification">
                                <tr>
                                    <th class="text-center" style="width: 40%;">System</th>
                                    <th class="text-center" style="width: 40%;">Remarks</th>
                                    <th class="text-center" style="width: 20%;">Status</th>
                                </tr>
                                </thead>
                                <tbody class="${data}">
                                </tbody>
                                <tr>
                                    <th class="text-center" style="width: 40%;">System</th>
                                    <th class="text-center" style="width: 40%;">Remarks</th>
                                    <th class="text-center" style="width: 20%;">Status</th>
                                </tr>
                                <tfoot class="customHeaderNotification">
                                </tfoot>
                            </table>
                        </div>
                    </div>`
        $('#myTabContent').append(html);
    }

    function loadNavLink() {
        $('.loadNavLink', '#nav-item-append').html("");
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_nav_link',
                fullname,
                archive: $('#archive').val()
            },
            success: function(result) {
                result.length == 0 ? $('.card').hide() : $('.card').show();
                let html = '';
                var loop_count = 0;
                var icon_adjust = 0;
                $.each(result, (key, value) => {
                    if (key === 0) {
                        loadAssignatory(value.app_id);
                    }
                    setInterval(() => {
                        loadTotalCount(value.app_id);
                    }, 500);
                    loop_count++;
                    let icon_preview = '';
                    icon_adjust += 4;
                    icon_preview = `<span class="position-absolute top-${icon_adjust} start-100 translate-middle badge rounded-pill bg-danger total-notif-${value.app_id}"></span>`;
                    html += `<li><a class="notification-nav-link" onclick="loadAssignatory('${value.app_id}')"><input type="hidden" class="system_section_${value.app_id}" id="system_section_${value.app_id}" value="${value.app_name}">${value.app_name}${icon_preview}</a></li>`;
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
    let approvedIntervalInstance;
    let array2 = [];
    // let loadOnce = false;

    const properties = [{
            prop: 'prepared_by',
            section: 'requestSection',
            tab: 'request-tab',
            table: 'prepared_table'
        },
        {
            prop: 'checked_by',
            section: 'checkedSection',
            tab: 'checked-tab',
            table: 'checked_table'
        },
        {
            prop: 'received_by',
            section: 'receivedSection',
            tab: 'received-tab',
            table: 'received_table'
        },
        {
            prop: 'approved_by',
            section: 'approvedSection',
            tab: 'approved-tab',
            table: 'approved_table'
        },
        {
            prop: 'noted_by',
            section: 'notedSection',
            tab: 'noted-tab',
            table: 'noted_table'
        }
    ];

    // function ScanAssignatory(id) {
    //     $.ajax({
    //         url: '../controller/notification_controller/notification_module_contr.class.php',
    //         type: 'POST',
    //         dataType: 'JSON',
    //         data: {
    //             action: 'load_assignatory',
    //             fullname,
    //             id
    //         },
    //         success: function(result) {
    //             $.each(result, (key, value) => {
    //                 const keysArray = Object.keys(value);
    //                 const valuesArray = Object.values(value);
    //                 const filteredArray = keysArray.filter(item => item.includes('_by'));
    //                 let array = [];
    //                 let notMatchingItems = [];
    //                 for (let i = 0; i < filteredArray.length; i++) {
    //                     if (value[filteredArray[i]] == 1) {
    //                         array.push(filteredArray[i])
    //                     }
    //                 }
    //                 for (var i = 0; i < array.length; i++) {
    //                     if (array[i] !== array2[i]) {
    //                         array2 = [];
    //                         notMatchingItems.push(array[i]);
    //                         loadAssignatory(id);
    //                     }
    //                 }
    //             });
    //         }
    //     });
    // }

    function loadAssignatory(id) {
        // if (loadOnce) {
        //     clearInterval(approvedIntervalInstance); // Stop the interval
        // }

        // function startTheScanApproved() {
        //     setTimeout(function() {
        //         ScanAssignatory(id);
        //     }, 1000);
        // }
        // approvedIntervalInstance = setInterval(startTheScanApproved, 4000);
        // loadOnce = true;
        $('.grid-container', '.append-assignatory', '.append-button').html('');
        $('#prepared_table, #checked_table, #approved_table, #noted_table').empty();
        let boolArray = Array(5).fill(true);
        $('#nav-item-append').html("");
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_assignatory',
                fullname,
                id,
                archive: $('#archive').val()
            },
            success: function(result) {
                array2 = [];
                $.each(result, (key, value) => {
                    properties.forEach((property, index) => {
                        if (value[property.prop] == 1 && boolArray[index]) {
                            console.log(value);
                            loadnavTabs(value.table_name, value[property.prop], value.app_id, capitalizeFirstLetter(property.prop).replace('_by', ''), property.section, property.tab, property.table);
                            loadNotificationDetails(id, property.prop, property.table);
                            array2.push(property.prop);
                            boolArray[index] = false;
                        }
                    });
                });
                $('.tab-pane').removeClass('active show');
                const tabMappings = {
                    'request-tab': 0,
                    'checked-tab': 1,
                    'approved-tab': 2,
                    'noted-tab': 3
                };
                const ishowmoButtons = document.querySelectorAll('.nav-item button');
                ishowmoButtons.forEach((button, index) => {
                    const tabName = button.id.trim();
                    const tabPosition = tabMappings[tabName];
                    if (index === 0 && tabPosition !== undefined) {
                        button.classList.add('active');
                        $(`.tab-pane:eq(${tabPosition})`).addClass('active show');
                        $('#btn-location').val(capitalizeFirstLetter(tabName).replace('-tab', ''));
                    }
                });
            }
        });
        setTimeout(function() {
            loadNavTabsCount(id);
        }, 300);
    }

    function loadNotificationDetails(app_id, label, table) {
        let data_table = $('#' + table).DataTable({
            'lengthMenu': [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            'destroy': true,
            'deferRender': true,
            'serverSide': true,
            'autoWidth': false,
            'responsive': true,
            'language': {
                'emptyTable': "Your data is in the archive."
            },
            // 'processing': true,"
            // 'lengthChange': false,
            // 'info': false,
            // 'searching': false,
            'ajax': {
                url: '../controller/notification_controller/notification_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_request_table',
                    fullname: fullname,
                    app_id: app_id,
                    label: label,
                    archive: $('#archive').val()
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '25%'
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '55%'
            }, {
                targets: 2,
                className: 'dt-body-middle-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    const buttonReturn = getButtonHtml(data, table);
                    return `<button class="btn ${buttonReturn.button_color} btn-sm" id="load-process-${data.notificationid}" onclick="btnSummary('${data.notificationid}','${data.table_database}','${data.table_name}','${data.table_field_id}','${data.table_field_id_name}');">${buttonReturn.icon}</button>`;
                }
            }]
        });
        data_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            data_table.ajax.reload(null, false);
        }, 5000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    $('.lds-ripple').addClass('loader--hidden');
    let oneTimeAction = true;

    function btnSummary(notificationid, table_database, table_name, table_field_id, table_field_id_name) {
        // ? oneTimeAction is used to prevent bugs in multiple clicking in the onllick function
        if (oneTimeAction) {
            oneTimeAction = false;
            $('.lds-ripple').removeClass('loader--hidden');
            $('.grid-container').html('');
            $('.append-assignatory').html('');
            $('.append-button').html('');
            $('#appid-contain').val('');
            $('#table_id').val(table_field_id);
            $('#db_name').val(table_database);
            $('#table_id_name').val(table_field_id_name);
            $('#table_name').val(table_name)
            $.ajax({
                url: '../controller/notification_controller/notification_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'generateFields',
                    notificationid,
                    table_database,
                    table_name,
                    table_field_id,
                    table_field_id_name
                },
                success: function(result) {
                    console.log(result);
                    const keysArray = Object.keys(result);
                    const valuesArray = Object.values(result);
                    const filteredArray = keysArray.filter(item => item.includes('acknowledge'));
                    let navLocation = $('#btn-location').val();
                    let html = ``
                    $('#appid-contain').val(result.app_id);
                    // ? checked status generation
                    for (let i = 0; i < filteredArray.length; i++) {
                        let html = `
                        <div class="col-md col-sm mb-2">
                            <div class="form-floating">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">${result[filteredArray[i]] == false ? '': '<i class="fa-solid fa-check fa-bounce"></i>'}</span>
                                <input type="text" class="form-control fw-bold" id="${filteredArray[i]}" value="${result[filteredArray[i].replace("_acknowledge", " ").trim()]}" disabled>
                                <label class="fw-bold" for="${filteredArray[i]}">${capitalizeFirstLetter(filteredArray[i]).replace("_"," ").replace("acknowledge",'').replace("_",'')}:</label>
                            </div>
                        </div>`
                        $('.append-assignatory').append(html);
                    }
                    // ? inputs generation
                    for (const keysArrays of keysArray) {
                        if (keysArrays.substring(0, 5) == 'field') {
                            let html = `
                                <div class="grid-item form-floating mb-2">
                                    <input type="text" class="form-control fw-bold" id="${result[keysArrays]}" disabled>
                                    <label class="fw-bold" for="${result[keysArrays]}">${capitalizeFirstLetter(result[keysArrays]).replace("_"," ")}:</label>
                                </div>`
                            $('.grid-container').append(html);
                        }
                    }
                    // ? inputs data generation
                    let arrayContainer = [];
                    for (let i = 0; i < valuesArray.length; i++) {
                        if (typeof valuesArray[i] !== 'boolean' && typeof valuesArray[i] !== 'number') {
                            arrayContainer.push(valuesArray[i]);
                            $.ajax({
                                url: '../controller/notification_controller/notification_module_contr.class.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    action: 'fillData',
                                    table_database: table_database,
                                    table_name: table_name,
                                    table_field_id: table_field_id,
                                    table_field_id_name: table_field_id_name,
                                    data: valuesArray[i]
                                },
                                success: resultData => {
                                    const keysArrayData = Object.keys(resultData);
                                    const valuesArrayData = Object.values(resultData);
                                    let p = new Promise(resolve => {
                                        for (let i = 0; i < keysArrayData.length; i++) {
                                            const key = keysArrayData[i];
                                            const value = valuesArrayData[i];
                                            $('#' + key).val(value);
                                        }
                                        resolve();
                                    });
                                    p.then(() => {
                                        $('#summary_modal').modal('show');
                                        $('.lds-ripple').addClass('loader--hidden');
                                    });
                                }
                            })
                        }
                    }
                    // ? acknowledge button generation
                    let table_databse = $('#db_name').val();
                    let table_names = $('#table_name').val();
                    if (table_names == 'phd_event_monitoring_header') {
                        if (!result.cancel_status) {
                            if (!result.cancel_status) {
                                switch (navLocation) {
                                    case 'Request':
                                        if (result.noted_by_acknowledge && !result.prepared_by_acknowledge) {
                                            html += `<button type="button" class="btn btn-success btn-sm fw-bold" style="border-radius: 20px;" onclick="btnAcknowledge(${result.notificationid});"><i class="fa-solid fa-check fa-beat p-r-8"></i>Approved Acknowledge</button>`
                                        }
                                        break;
                                    case 'Noted':
                                        if (!result.noted_by_acknowledge) {
                                            html += `<button type="button" class="btn btn-success btn-sm fw-bold" style="border-radius: 20px;" onclick="btnAcknowledge(${result.notificationid});"><i class="fa-solid fa-check fa-beat p-r-8"></i>Approved Acknowledge</button>`
                                        }
                                        break;
                                }
                            }
                        }
                    } else {
                        if (!result.cancel_status) {
                            switch (navLocation) {
                                case 'Checked':
                                    if (!result.checked_by_acknowledge) {
                                        html += `<button type="button" class="btn btn-success btn-sm fw-bold" style="border-radius: 20px;" onclick="btnAcknowledge(${result.notificationid});"><i class="fa-solid fa-check fa-beat p-r-8"></i>Approved Acknowledge</button>`
                                    }
                                    break;
                                case 'Request':
                                    if (result.repair_by_acknowledge && !result.prepared_by_acknowledge || (table_databse == 'physical_security' && result.checked_by_acknowledge || table_databse == 'itassetdb_new' && result.approved_by_acknowledge) && result.noted_by_acknowledge && !result.prepared_by_acknowledge) {
                                        html += `<button type="button" class="btn btn-success btn-sm fw-bold" style="border-radius: 20px;" onclick="btnAcknowledge(${result.notificationid});"><i class="fa-solid fa-check fa-beat p-r-8"></i>Approved Acknowledge</button>`
                                    }
                                    break;
                                case 'Approved':
                                    if (!result.approved_by_acknowledge) {
                                        if (!result.approved_by_acknowledge && !result.noted_by_acknowledge) {
                                            html += `<button type="button" class="btn btn-success btn-sm fw-bold" style="border-radius: 20px;" onclick="btnAcknowledge(${result.notificationid});"><i class="fa-solid fa-check fa-beat p-r-8"></i>Approved Acknowledge</button>
                                            <button type="button" class="btn btn-info btn-sm fw-bold" style="border-radius: 20px;" onclick="btnCancel(${result.notificationid});"><i class="fa-regular fa-thumbs-down p-r-8"></i>Cancel Acknowledge</button>`
                                        }
                                    }
                                    break;
                                case 'Noted':
                                    if (result.approved_by_acknowledge && !result.noted_by_acknowledge || table_databse == 'physical_security' && result.checked_by_acknowledge && !result.noted_by_acknowledge) {
                                        html += `<button type="button" class="btn btn-success btn-sm fw-bold" style="border-radius: 20px;" onclick="btnAcknowledge(${result.notificationid});"><i class="fa-solid fa-check fa-beat p-r-8"></i>Approved Acknowledge</button>`
                                    }
                                    break;
                            }
                        }
                    }
                    html += `<button type="button" class="btn btn-danger btn-sm fw-bold" style="border-radius: 20px;" onclick="btnClose();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>`
                    $('.append-button').append(html);
                }
            })
        }
    }

    function btnAcknowledge(id) {
        oneTimeAction = true;
        const appid = $('#appid-contain').val();
        setTimeout(function() {
            loadNavTabsCount(appid);
        }, 300);
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
                console.log(result);
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Email has been Succesfully Acknowledge',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#summary_modal').modal('hide');
            }
        });
    }

    const btnCancel = id => {
        oneTimeAction = true;
        setTimeout(function() {
            loadNavTabsCount(id);
        }, 300);
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
                $('#summary_modal').modal('hide');
            }
        })
    }

    function loadTotalCount(app_id) {
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadCount',
                fullname,
                app_id
            },
            success: function(result) {
                $('.total-notif-' + app_id).html(result.total == 0 ? '' : result.total + '+');
            }
        });
    }

    function loadNavTabsCount(app_id) {
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadCount',
                fullname,
                app_id
            },
            success: function(result) {
                console.log(result);
                $('.Prepared-count').html(result.prepared_count == 0 ? '' : result.prepared_count + '+');
                $('.Checked-count').html(result.checked_count == 0 ? '' : result.checked_count + '+');
                $('.Received-count').html(result.received_count == 0 ? '' : result.received_count + '+');
                $('.Approved-count').html(result.approved_count == 0 ? '' : result.approved_count + '+');
                $('.Noted-count').html(result.noted_count == 0 ? '' : result.noted_count + '+');
            }
        });
    }

    function capitalizeFirstLetter(word) {
        return word.charAt(0).toUpperCase() + word.slice(1);
    }

    function getButtonHtml(data, label) {
        let button_color, icon;
        switch (label) {
            case 'checked_table':
                if (data.cancel_status) {
                    button_color = 'btn-danger';
                    icon = '<i class="fa-solid fa-ban fa-shake p-r-8 p-l-8"></i>';
                } else if (data.checked_by_acknowledge && data.noted_by_acknowledge && data.prepared_by_acknowledge) {
                    button_color = 'btn-warning';
                    icon = '<i class="fa-solid fa-envelope-open-text fa-fade p-r-8 p-l-8"></i>';
                } else if (!data.checked_by_acknowledge) {
                    button_color = 'btn-dark';
                    icon = '<i class="fa-regular fa-envelope fa-shake p-r-8 p-l-8"></i>';
                } else if (data.checked_by_acknowledge) {
                    button_color = 'btn-info';
                    icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                }
                break;
            case 'prepared_table':
                if (data.table_name == 'phd_event_monitoring_header') {
                    if (data.noted_by_acknowledge && data.prepared_by_acknowledge) {
                        button_color = 'btn-warning';
                        icon = '<i class="fa-solid fa-envelope-open-text fa-fade p-r-8 p-l-8"></i>';
                    } else if (data.noted_by_acknowledge && !data.prepared_by_acknowledge) {
                        button_color = 'btn-dark';
                        icon = '<i class="fa-regular fa-envelope fa-shake p-r-8 p-l-8"></i>';
                    } else {
                        button_color = 'btn-info';
                        icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                    }
                } else {
                    if (data.cancel_status) {
                        button_color = 'btn-danger';
                        icon = '<i class="fa-solid fa-ban fa-shake p-r-8 p-l-8"></i>';
                    } else if (data.repair_by_acknowledge && !data.prepared_by_acknowledge || (data.table_database == 'physical_security' && data.checked_by_acknowledge || data.table_database == 'itassetdb_new' && data.approved_by_acknowledge) && data.noted_by_acknowledge && !data.prepared_by_acknowledge) {
                        button_color = 'btn-dark';
                        icon = '<i class="fa-regular fa-envelope fa-shake p-r-8 p-l-8"></i>';
                    } else if (data.repair_by_acknowledge && data.prepared_by_acknowledge || (data.table_database == 'physical_security' && data.checked_by_acknowledge || data.table_database == 'itassetdb_new' && data.approved_by_acknowledge) && data.noted_by_acknowledge && data.prepared_by_acknowledge) {
                        button_color = 'btn-warning';
                        icon = '<i class="fa-solid fa-envelope-open-text fa-fade p-r-8 p-l-8"></i>';
                    } else {
                        button_color = 'btn-info';
                        icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                    }
                }
                break;
            case 'approved_table':
                if (data.cancel_status) {
                    button_color = 'btn-danger';
                    icon = '<i class="fa-solid fa-ban fa-shake p-r-8 p-l-8"></i>';
                } else if (data.approved_by_acknowledge && data.noted_by_acknowledge && (data.repair_by_acknowledge || data.table_database == 'itassetdb_new') && data.prepared_by_acknowledge) {
                    button_color = 'btn-warning';
                    icon = '<i class="fa-solid fa-envelope-open-text fa-fade p-r-8 p-l-8"></i>';
                } else if (!data.approved_by_acknowledge) {
                    button_color = 'btn-dark';
                    icon = '<i class="fa-regular fa-envelope fa-shake p-r-8 p-l-8"></i>';
                } else if (data.approved_by_acknowledge) {
                    button_color = 'btn-info';
                    icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                }
                break;
            case 'noted_table':
                if (data.table_name == 'phd_event_monitoring_header') {
                    if (data.noted_by_acknowledge && data.prepared_by_acknowledge) {
                        button_color = 'btn-warning';
                        icon = '<i class="fa-solid fa-envelope-open-text fa-fade p-r-8 p-l-8"></i>';
                    } else if (!data.noted_by_acknowledge) {
                        button_color = 'btn-dark';
                        icon = '<i class="fa-regular fa-envelope fa-shake p-r-8 p-l-8"></i>';
                    } else {
                        button_color = 'btn-info';
                        icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                    }
                } else {
                    if (data.cancel_status) {
                        button_color = 'btn-danger';
                        icon = '<i class="fa-solid fa-ban fa-shake p-r-8 p-l-8"></i>';
                    } else if (data.approved_by_acknowledge && data.noted_by_acknowledge && data.repair_by_acknowledge && data.prepared_by_acknowledge || (data.table_database == 'physical_security' && data.checked_by_acknowledge || data.table_database == 'itassetdb_new' && data.approved_by_acknowledge) && data.noted_by_acknowledge && data.prepared_by_acknowledge) {
                        button_color = 'btn-warning';
                        icon = '<i class="fa-solid fa-envelope-open-text fa-fade p-r-8 p-l-8"></i>';
                    } else if ((data.table_database == 'it_repair_request' && !data.approved_by_acknowledge || data.table_database == 'itassetdb_new' && !data.approved_by_acknowledge) || data.table_database == 'physical_security' && !data.checked_by_acknowledge) {
                        button_color = 'btn-warning';
                        icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                    } else if ((data.table_database == 'it_repair_request' || data.table_database == 'info_security' || data.table_database == 'itassetdb_new') && data.approved_by_acknowledge && !data.noted_by_acknowledge || data.table_database == 'physical_security' && data.checked_by_acknowledge && !data.noted_by_acknowledge) {
                        button_color = 'btn-dark';
                        icon = '<i class="fa-regular fa-envelope fa-shake p-r-8 p-l-8"></i>';
                    } else {
                        button_color = 'btn-info';
                        icon = '<i class="fa-solid fa-circle-info fa-bounce p-r-8 p-l-8"></i>';
                    }
                }
                break;
        }
        return {
            button_color,
            icon
        };
    }

    function loadnavTabs(table_name, assignatory, app_id, label, target, target2, data_table_name) {
        let html = `
                    <li class="nav-item" id="navbars" role="presentation">
                        <button type="button" class="nav-link nav-link-notification flex-sm-fill fs-5 ${target}" id="${target2}" onclick="getValLocation('${label == 'Prepared' ? 'Request' : label}')"; data-bs-toggle="tab" data-bs-target="#${target}" role="tab" aria-controls="${target}" aria-selected="true">${label}
                            <span class="position-relative top-0 start-25 translate-middle badge rounded-pill bg-danger fa-fade ${label}-count"></span>
                        </button>
                    </li>`
        $('#nav-item-append').append(html);
    }

    function getValLocation(location) {
        $('#btn-location').val(location);
    }
    const btnClose = () => {
        $('#summary_modal').modal('hide')
        oneTimeAction = true;
    }

    setInterval(() => {
        setTimeout(() => {
            scanNavlink();
        }, 500);
    }, 5000);

    function scanNavlink() {
        $.ajax({
            url: '../controller/notification_controller/notification_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_nav_link',
                fullname
            },
            success: function(result) {
                result.length == 0 ? $('.card').hide() : $('.card').show();
                let html = '';
                var loop_count = 0;
                var icon_adjust = 0;
                $.each(result, function(key, value) {
                    loop_count++;
                    let icon_preview = '';
                    icon_adjust += 4;
                    icon_preview = `<span class="position-absolute top-${icon_adjust} start-100 translate-middle badge rounded-pill bg-danger total-notif-${value.app_id}"></span>`;
                    let uniq = $('.system_section_' + value.app_id).val();
                    if (uniq != value.app_name) {
                        if (key === 0) {
                            loadAssignatory(value.app_id);
                        }
                        setInterval(() => {
                            loadTotalCount(value.app_id);
                        }, 500);
                        loadTotalCount(value.app_id);
                        html += `<li><a class="notification-nav-link" onclick="loadAssignatory('${value.app_id}')"><input type="hidden" class="system_section_${value.app_id}" id="system_section_${value.app_id}" value="${value.app_name}">${value.app_name}${icon_preview}</a></li>`;
                        $('.loadNavLink').append(html);
                        $('.notification-nav-link:first').addClass('active');
                        $('.notification-nav-link').click(function(e) {
                            e.preventDefault();
                            $('.notification-nav-link').removeClass('active');
                            $(this).addClass('active').fadeIn();
                        });
                    }
                });
            }
        });
    }
</script>