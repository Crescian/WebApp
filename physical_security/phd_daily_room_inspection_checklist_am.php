<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWebLive->prepare($queryCheckApp);
$stmtCheckApp->execute();
$chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC);
$chkAppId = $chkAppIdRow['app_id'];

if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    /* =========== Change Scrollbar Style - Justine 02012023 =========== */
    ::-webkit-scrollbar {
        width: 0.7vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #FF7A00;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-physical" style="font-weight: lighter;">Daily Room Inspection Checklist (AM)</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Daily Room Inspection</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="dailyModal();"><i class="fa-solid fa-square-plus p-r-8"></i>Add Daily Room Inspection</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="daily_room_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align:center; font-size:15px;">DATE</th>
                                                <th style="text-align:center; font-size:15px;">TITLE</th>
                                                <th style="font-size:13px;">PREPARED BY (MAIN PLANT)</th>
                                                <th style="font-size:13px;">PREPARED BY (ADMIN & LOBBY)</th>
                                                <th style="font-size:13px;">PREPARED BY (WAREHOUSE 2 & 3)</th>
                                                <th style="text-align:center; font-size:13px;">NOTED BY</th>
                                                <th style="text-align:center; font-size:13px;">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align:center; font-size:15px;">DATE</th>
                                                <th style="text-align:center; font-size:15px;">TITLE</th>
                                                <th style="font-size:13px;">PREPARED BY (MAIN PLANT)</th>
                                                <th style="font-size:13px;">PREPARED BY (ADMIN & LOBBY)</th>
                                                <th style="font-size:13px;">PREPARED BY (WAREHOUSE 2 & 3)</th>
                                                <th style="text-align:center; font-size:13px;">NOTED BY</th>
                                                <th style="text-align:center; font-size:13px;">ACTION</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="modal fade" id="dailyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-xl modal-dialog-centered  modal-dialog-scrollable modal-fullscreen-xl-down" role=" document">
                        <div class="modal-content">
                            <div class="modal-header  card-2">
                                <h4 class="modal-title text-uppercase fw-bold text-light headModal"> Add Daily Room Inspection</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="pir_table" class="table table-bordered table-striped fw-bold">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th scope="col" style="text-align: center; width: 30%; vertical-align: middle;">Rooms</th>
                                                    <th scope="col" style="text-align: center; width: 10%;">Time Checked</th>
                                                    <th scope="col" style="text-align: center; width: 13%;">Aircon Off</th>
                                                    <th scope="col" style="text-align: center; width: 12%;">Lights Off</th>
                                                    <th scope="col" style="text-align: center; width: 12%;">Door Locked</th>
                                                    <th scope="col" style="text-align: center; width: 12%;">Conv. Outlet Unplugged</th>
                                                    <th scope="col" style="text-align: center; width: 110%; vertical-align: middle;">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data">
                                            </tbody>
                                            <tfoot class="custom_table_header_color_physical">
                                        </table>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="d-flex justify-content-end">
                                        <span class="fw-bold">Instruction : If yes put ✔, if no put ✖</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-floating mb-1">
                                            <select name="" id="designation" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                                <option value="Main Plant">Main Plant</option>
                                                <option value="Admin & Lobby">Admin & Lobby</option>
                                                <option value="Warehouse 2 & 3">Warehouse 2 & 3</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                            <label for="main_plant" class="fw-bold">Location</label>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <input type="hidden" class="form-control fw-bold" id="pagingcount">
                                        <div class="form-floating mb-1">
                                            <input type="text" id="preparedUser" class="form-control fw-bold" value="<?php echo $_SESSION['fullname'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label for="main_plant" class="fw-bold">Prepared By:</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="fw-bold fs-13 ps-4" id="perform_job_pos"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-floating mb-1">
                                            <select name="" id="notedBy" class="form-select fw-bold" disabled>
                                                <option value="Roderick Gatbonton">Roderick Gatbonton</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                            <label for="notedBy" class="fw-bold">Noted By:</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="fw-bold fs-13 ps-4" id="noted_job_pos"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mb-3 px-3">
                                <button type="button" class="btn btn-warning btn-sm fw-bold btn-update" style="border-radius: 20px;" onclick="updateFunc(this.value);"><i class="fa-solid fa-pen-to-square fa-bounce p-r-8"></i>Update</button>
                                <button type="button" class="btn btn-success btn-sm fw-bold btn-assign-save" style="border-radius: 20px;" onclick="saveFunc();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                                <button type="button" class="btn btn-danger btn-sm fw-bold" style="border-radius: 20px;" onclick="btnClose();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Paging System Modal =============== -->
            <div class="modal fade" id="qr_scannerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-black justify-content-center">
                            <h4 class="modal-title text-uppercase fw-bold text-light">SCAN QR-CODE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3" style="padding: 0px; width: 100%; max-height: 300px; overflow:hidden; border: 1px solid gray">
                                <video id="video" style="width: 100%;"></video>
                            </div>
                            <div id="sourceSelectPanel" style="display:none">
                                <div class="form-floating mb-2">
                                    <select class="form-select fw-bold" id="sourceSelect"></select>
                                    <label for="paging_noted_by" class="fw-bold">Change video source:</label>
                                </div>
                            </div>
                            <!-- <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="result">
                                <label for="paging_noted_by" class="fw-bold">Result:</label>
                            </div> -->
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-secondary col" data-bs-dismiss="modal" id="closeModal"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
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
            <div class="card card-2 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">PHYSICAL SECURITY</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/select_values.php';
include './../helper/phd_scan_qr.php'; ?>
<script>
    loadDailyRoomTable();
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    let btnActivateArray = [];
    let btnActivateValueArray = [];
    let btnActivateValueValidationArray = [];
    let roomArray = [];
    let airconArray = [];
    let lightArray = [];
    let doorArray = [];
    let outletArray = [];
    let remarksArray = [];
    let category = [];
    let idDetails = [];
    let perform = [];
    var pagingcount = 0;

    function loadDailyRoomTable() {
        var daily_room_table = $('#daily_room_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_daily_room_table'
                },
                dataSrc: function(data) {
                    if (data == "") {
                        return [];
                    } else {
                        return data.data;
                    }
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '10%',
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '35%'
            }, {
                targets: [2, 3, 4],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 5,
                className: 'dt-body-middle-left',
                width: '20%',
            }, {
                targets: 6,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewDailyRoomPdf('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteDailyRoom('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        daily_room_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            daily_room_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }


    function dailyModal() {
        $('#dailyModal').modal('show');
        $('.btn-update').css('display', 'none');
        $('.btn-assign-save').css('display', 'block');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#pagingcount').val('0');
        $.ajax({
            url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'generate_details'
            },
            success: function(result) {
                let html = '';
                var count = 0;
                $.each(result, function(index, row) {
                    var removeExtraName = index == 'Main Plant (Daily Room)' ? index.substring(0, 10) : index;
                    html += '<tr>';
                    html += '<th colspan="7"><input class="form-control fw-bold text-uppercase" value="' + removeExtraName + '" disabled></th>';
                    html += '</tr>';
                    $.each(row, function(key, details) {
                        html += '<tr>';
                        html += '<td><input class="form-control room fw-bold" value="' + details.location_name + '" disabled>';
                        html += '<input type="hidden" name="dr_category_name[]" class="form-control fw-bold text-uppercase dr_category_name" value="' + index + '" disabled></td>';
                        html += '<td><input type="hidden" class="btnActivate' + count + ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + details.location_name + '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                        html += '<td style="vertical-align: middle; text-align: center;">';
                        html += '<select class="form-select fw-bold aircon aircon' + count + '" disabled>';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold lights lights' + count + '" disabled> ';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold door door' + count + '" disabled>';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold outlets outlets' + count + '" disabled> ';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td><textarea style="resize: none;" name="remarks" id="remarks' + count + '" cols="30" rows="1" class="form-control fw-bold remark remarks' + count + '" onchange="status_change(this.value);" disabled></textarea>';
                        html += '<div class="invalid-feedback"></div> ';
                        html += '</td>';
                        html += '</tr>';
                        count++;
                    });
                });
                $('.data').append(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function saveFunc() {
        if (submitValidation()) {
            let designation = document.getElementById('designation').value;
            let notedBy = document.getElementById('notedBy').value;
            $.ajax({
                url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_header',
                    prepared_by: logged_user,
                    designation: designation,
                    notedBy: notedBy
                },
                success: function(result) {
                    // var data = JSON.parse(JSON.stringify(itemData_List));
                    // alert(data.pagingheader_id);
                    // alert(result.pagingheader_id);
                    $('.btnActivate').each(function() {
                        var procActivate = $(this).val();
                        btnActivateArray.push([procActivate]);
                    });
                    $('.dr_category_name').each(function() {
                        var cat = $(this).val();
                        category.push([cat]);
                    });
                    $('.room').each(function() {
                        var proc = $(this).val();
                        roomArray.push([proc]);
                    });
                    $('.aircon').each(function() {
                        var proc1 = $(this).val();
                        airconArray.push([proc1]);
                    });
                    $('.lights').each(function() {
                        var proc2 = $(this).val();
                        lightArray.push([proc2]);
                    });
                    $('.door').each(function() {
                        var proc3 = $(this).val();
                        doorArray.push([proc3]);
                    });
                    $('.outlets').each(function() {
                        var proc4 = $(this).val();
                        outletArray.push([proc4]);
                    });
                    $('.remark').each(function() {
                        var procRemarks = $(this).val();
                        remarksArray.push([procRemarks]);
                    });
                    for (let i = 0; i < roomArray.length; i++) {
                        var strCategArray = category[i];
                        var strActivateArray = btnActivateArray[i];
                        var strRoomArray = roomArray[i];
                        var strAirconArray = airconArray[i];
                        var strlightArray = lightArray[i];
                        var strDoorArray = doorArray[i];
                        var strOutletArray = outletArray[i];
                        var strRemarksArray = remarksArray[i];

                        var strCategory = strCategArray.toString();
                        var strBtnActivate = strActivateArray.toString();
                        var strRoom = strRoomArray.toString();
                        var strAircon = strAirconArray.toString();
                        var strlight = strlightArray.toString();
                        var strDoor = strDoorArray.toString();
                        var strOutlet = strOutletArray.toString();
                        var strRemarks = strRemarksArray.toString();
                        let performedBy = logged_user;

                        $.ajax({
                            url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_detail',
                                performedBy: performedBy,
                                strCategory: strCategory,
                                strBtnActivate: strBtnActivate,
                                strRoom: strRoom,
                                strAircon: strAircon,
                                strlight: strlight,
                                strDoor: strDoor,
                                strOutlet: strOutlet,
                                strRemarks: strRemarks,
                                generateRefno: result.paging_ref_no,
                                dailyroomid: result.pagingheader_id
                            },
                            success: function(result) {
                                // alert(result);
                            }
                        });
                    }
                    refreshProcessTable();
                    $('#dailyModal').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Saved Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    clearValues();
                }
            });
        }
    }

    function btnPreview(dailyroomid) {
        $('.btn-update').val(dailyroomid);
        $('#dailyModal').modal('show');
        $('.btn-update').css('display', 'block');
        $('.btn-assign-save').css('display', 'none');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#pagingcount').val('0');

        $.ajax({
            url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load-noted-by',
                dailyroomid: dailyroomid
            },
            success: function(result) {
                setTimeout(function() {
                    loadJobPosition(logged_user, 'perform_job_pos');
                    loadJobPosition(result.result, 'noted_job_pos');
                }, 300);
            }
        })
        $.ajax({
            url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_details',
                dailyroomid: dailyroomid
            },
            success: function(result) {
                let html = '';
                var count = 0;
                $.each(result, function(index, row) {
                    html += '<tr>';
                    html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;">' + index + '</th>';
                    html += '</tr>';
                    $.each(row, function(key, details) {
                        var aircon = getActionCode(details.dr_aircon_off);
                        var lights = getActionCode(details.dr_lights_off);
                        var door = getActionCode(details.dr_door_locked);
                        var outlet = getActionCode(details.dr_conv_outlet_unplugged);
                        var active = details.dr_time_activated == null ? '<i class="fa-solid fa-qrcode fa-beat"></i>' : details.dr_time_activated.substring(10, 16) + 'H';
                        var active2 = active == '<i class="fa-solid fa-qrcode fa-beat"></i>' ? '' : active;
                        html += '<tr>';
                        html += '<td style="vertical-align:middle;">' + details.dr_rooms + '<input type="hidden" class="form-control prepared fw-bold" value="' + details.dr_prepared + '" disabled>';
                        html += '<input type="hidden" class="form-control drDetail fw-bold" value="' + details.drdetailid + '" disabled>';
                        html += '<input type="hidden" name="dr_category_name[]" class="form-control fw-bold text-uppercase dr_category_name" value="' + details.dr_category + '" disabled></td>';
                        if (details.dr_prepared != null) {
                            html += '<td><input type="hidden" class="btnActivate' + count + ' form-control btnActivateValue" value="' + active2 + '" disabled><button type="button" class="btn btn-secondary btn-sm col-sm-12 fw-bold btnActivate" value="' + count + '" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ')" disabled>' + active.replace(':', ''); + '</button></td>';
                        } else {
                            html += '<td><input type="hidden" class="btnActivate' + count + ' form-control btnActivateValue" value="' + active2 + '" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + details.dr_rooms + '\')">' + active.replace(':', ''); + '</button></td>';
                        }
                        html += '<td style="vertical-align: middle; text-align: center;">';
                        html += '<select class="form-select fw-bold aircon aircon' + count + '" value="" onchange="status_change(this.value);" disabled>';
                        html += '<option value="' + details.dr_aircon_off + '">' + aircon + '</option>';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold lights lights' + count + '" onchange="status_change(this.value);" disabled> ';
                        html += '<option value="' + details.dr_lights_off + '">' + lights + '</option>';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold door door' + count + '" onchange="status_change(this.value);" disabled>';
                        html += '<option value="' + details.dr_door_locked + '">' + door + '</option>';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold outlets outlets' + count + '" onchange="status_change(this.value);" disabled> ';
                        html += '<option value="' + details.dr_conv_outlet_unplugged + '">' + outlet + '</option>';
                        html += '<option value="0"></option>';
                        html += '<option value="1">✔</option>';
                        html += '<option value="2">✖</option>';
                        html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                        html += '</select></td>';
                        html += '<td><textarea style="resize: none;" name="remarks" id="remarks' + count + '" cols="30" rows="1" class="form-control fw-bold remark remarks' + count + '" onchange="status_change(this.value);" disabled>' + details.dr_remarks + '</textarea></td>';
                        html += '</tr>';
                        count++;
                    });
                });
                $('.data').append(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function updateFunc(dailyroomid) {
        if (submitValidation()) {
            let designation = document.getElementById('designation').value;
            $.ajax({
                url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_header',
                    logged_user: logged_user,
                    designation: designation,
                    dailyroomid: dailyroomid
                },
                success: function(id) {
                    $('.prepared').each(function() {
                        var prepared = $(this).val();
                        perform.push([prepared]);
                    });
                    $('.drDetail').each(function() {
                        var idData = $(this).val();
                        idDetails.push([idData]);
                    });
                    $('.btnActivateValue').each(function() {
                        var procActivate = $(this).val();
                        if (procActivate == '') {
                            btnActivateValueArray.push([null]);
                        } else {
                            btnActivateValueArray.push([procActivate])
                        };
                    });
                    $('.dr_category_name').each(function() {
                        var cat = $(this).val();
                        category.push([cat]);
                    });
                    $('.aircon').each(function() {
                        var proc1 = $(this).val();
                        airconArray.push([proc1]);
                    });
                    $('.lights').each(function() {
                        var proc2 = $(this).val();
                        lightArray.push([proc2]);
                    });
                    $('.door').each(function() {
                        var proc3 = $(this).val();
                        doorArray.push([proc3]);
                    });
                    $('.outlets').each(function() {
                        var proc4 = $(this).val();
                        outletArray.push([proc4]);
                    });
                    $('.remark').each(function() {
                        var procRemarks = $(this).val();
                        remarksArray.push([procRemarks]);
                    });
                    for (let i = 0; i < idDetails.length; i++) {
                        var strPerformArray = perform[i];
                        var strDetailsArray = idDetails[i];
                        var strCategArray = category[i];
                        var strActivateArray = btnActivateValueArray[i];
                        var strAirconArray = airconArray[i];
                        var strlightArray = lightArray[i];
                        var strDoorArray = doorArray[i];
                        var strOutletArray = outletArray[i];
                        var strRemarksArray = remarksArray[i];

                        var strPrepared = strPerformArray.toString();
                        var strDetails = strDetailsArray.toString();
                        var strCategory = strCategArray.toString();
                        var strBtnActivate = strActivateArray.toString();
                        var strAircon = strAirconArray.toString();
                        var strlight = strlightArray.toString();
                        var strDoor = strDoorArray.toString();
                        var strOutlet = strOutletArray.toString();
                        var strRemarks = strRemarksArray.toString();
                        let performedBy = logged_user;

                        $.ajax({
                            url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'update_details',
                                strPrepared: strPrepared,
                                strDetails: strDetails,
                                strCategory: strCategory,
                                strBtnActivate: strBtnActivate,
                                strAircon: strAircon,
                                strlight: strlight,
                                strDoor: strDoor,
                                strOutlet: strOutlet,
                                strRemarks: strRemarks,
                                performedBy: performedBy,
                                dailyroomid: dailyroomid
                            },
                            success: function(result) {
                                // alert(result);
                            }
                        });
                    }
                    refreshProcessTable();
                    $('#dailyModal').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'updated Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    clearValues();
                }
            });
        }
    }

    function btnDeleteDailyRoom(dailyid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to regress this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controller/phd_controller/phd_daily_room_inspection_checklist_am_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_details',
                        dailyid: dailyid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    function validationQrScanner(count) {
        $('#btnActivate' + count).val(formatMILITARY(new Date));
        $('#btnActivate' + count).html(formatMILITARY(new Date));
        $('.btnActivate' + count).val(formatMILITARY(new Date));
        $('.aircon' + count).prop('disabled', false);
        $('.lights' + count).prop('disabled', false);
        $('.door' + count).prop('disabled', false);
        $('.outlets' + count).prop('disabled', false);
        $('.remarks' + count).prop('disabled', false);
    }

    function refreshProcessTable() {
        $('#daily_room_table').DataTable().ajax.reload(null, false);
    }

    function status_change(selectValue) {
        if (selectValue == 0 && selectValue.length == 0) {
            pagingcount--;
        } else {
            pagingcount++;
        }
        $('#pagingcount').val(pagingcount);
    }

    function btnPreviewDailyRoomPdf(dailyid) {
        strLink = "phd_daily_room_inspection_checklist_pdf_am.php?d=" + dailyid;
        window.open(strLink, '_blank');
    }

    function getActionCode(code) {
        let codeResult;
        switch (code) {
            case 0:
                codeResult = '';
                break;
            case 1:
                codeResult = '✔';
                break;
            case 2:
                codeResult = '✖';
                break;
            case 3:
                codeResult = 'N/A';
                break;
        }
        return codeResult;
    }

    function btnClose() {
        $('#dailyModal').modal('hide');
        clearValues();
    }

    function formatMILITARY(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        if (minutes > 9) {
            var minutesZero = minutes;
        } else {
            var minutesZero = "0" + minutes;
        }
        if (hours > 9) {
            var hoursZero = hours;
        } else {
            var hoursZero = "0" + hours;
        }
        var strTime = hoursZero + '' + minutesZero + 'H';
        return strTime;
    }

    function submitValidation() {
        let isValidated = true;
        $('.room').each(function() {
            var proc = $(this).val();
            roomArray.push([proc]);
        });
        $('.btnActivate').each(function() {
            var procActivate = $(this).val();
            btnActivateArray.push([procActivate]);
        });
        $('.remark').each(function() {
            var procRemarks = $(this).val();
            remarksArray.push([procRemarks]);
        });
        for (let i = 0; i < roomArray.length; i++) {
            var strActivateArray = btnActivateArray[i];
            var activate = strActivateArray.toString();
            var strRemarksArray = remarksArray[i];
            var remarks = strRemarksArray.toString();
            if (activate != '') {
                if (remarks == '') {
                    $('#remarks' + i).addClass('is-invalid').removeClass('is-valid');
                    isValidated = false;
                }
            }
        }
        let designation = document.getElementById('designation').value;
        if (designation.length == 0) {
            showFieldError('designation', 'Category name must not be blank');
            if (isValidated) {
                $('#designation').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('designation');
        }
        if (pagingcount <= 0) {
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Please Fill Inputs',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            isValidated = false;
        }
        roomArray = [];
        btnActivateArray = [];
        remarksArray = [];
        return isValidated;
    }

    function clearValues() {
        $('#checkBy').val('');
        $("#pir_table").find("tr:gt(0)").remove();
        $('.data').html('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        btnActivateArray = [];
        btnActivateValueArray = [];
        btnActivateValueValidationArray = [];
        roomArray = [];
        airconArray = [];
        lightArray = [];
        doorArray = [];
        outletArray = [];
        remarksArray = [];
        category = [];
        idDetails = [];
        perform = [];
        $('#perform_job_pos').html('');
        $('#checked_job_pos').html('');
        $('#noted_job_pos').html('');
    }

    function showFieldError(element, message) {
        $('#' + element).addClass('is-invalid').removeClass('is-valid');
        $('#' + element).next().html(message);
        $('#' + element).next().show();
    }

    function clearFieldError(element) {
        $('#' + element).removeClass('is-invalid').addClass('is-valid');
        $('#' + element).attr('required');
        $('#' + element).next().html('');
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid');
        $('input').removeClass('is-valid');
        $('select').removeClass('is-invalid');
        $('select').removeClass('is-valid');
        $('textarea').removeClass('is-invalid');
        $('textarea').removeClass('is-valid');
    }
</script>
</body>
<html>