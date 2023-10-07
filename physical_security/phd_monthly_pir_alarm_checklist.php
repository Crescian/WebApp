<?php
include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
// * Check if module is within the application
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
    /* =========== Change Scrollbar Style - Justine 02112023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
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
                <span class="page-title-physical" style="font-weight: lighter;">Monthly Pir Alarm Checklist</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Monthly Pir Alarm</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="pirModal();"><i class="fa-solid fa-square-plus p-r-8"></i>Add Monthly Pir Alarm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="pir_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align:center; font-size:15px;">DATE</th>
                                                <th style="text-align:center; font-size:15px;">TITLE</th>
                                                <th style="font-size:15px;">PREPARED BY</th>
                                                <th style="font-size:15px;">CHECKED BY</th>
                                                <th style="font-size:15px;">NOTED BY</th>
                                                <th style="text-align:center; font-size:15px;">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th style="text-align:center; font-size:15px;">DATE</th>
                                                <th style="text-align:center; font-size:15px;">TITLE</th>
                                                <th style="font-size:15px;">PREPARED BY</th>
                                                <th style="font-size:15px;">CHECKED BY</th>
                                                <th style="font-size:15px;">NOTED BY</th>
                                                <th style="text-align:center; font-size:15px;">ACTION</th>
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
                <div class="modal fade" id="pirModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-xl modal-dialog-centered  modal-dialog-scrollable modal-fullscreen-xl-down" role=" document">
                        <div class="modal-content">
                            <div class="modal-header  card-2">
                                <h4 class="modal-title text-uppercase fw-bold text-light headModal"> Add Monthly Pir Alarm</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="pir_table" class="table table-bordered table-striped fw-bold">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th scope="col" style="text-align: center; width: 30%; vertical-align: middle;">Location</th>
                                                    <th scope="col" style="text-align: center; width: 10%;">Time Activated</th>
                                                    <th scope="col" style="text-align: center; width: 20%;">Motion Detected</th>
                                                    <th scope="col" style="text-align: center; width: 20%;">No Motion Detected</th>
                                                    <th scope="col" style="text-align: center; width: 20%;">Dual Presence</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data">
                                            </tbody>
                                            <tfoot class="custom_table_header_color_physical">
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" class="form-control fw-bold" id="pagingcount">
                                    <div class="col-sm">
                                        <div class="form-floating mb-1">
                                            <input type="text" id="performedBy" class="form-control fw-bold" value="<?php echo $_SESSION['fullname'] ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label for="performedBy" class="fw-bold">Prepared By:</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="fw-bold fs-13 ps-4" id="perform_job_pos"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-floating mb-1">
                                            <input type="text" name="checkedByPreview" id="checkedByPreview" class="form-control fw-bold" disabled>
                                            <select name="" id="checkedBy" onchange="getJob(this.value);" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                            <label for="Choose" class="fw-bold">Checked By:</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="fw-bold fs-13 ps-4" id="checked_job_pos"></label>
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
    loadCms();
    loadDuressTable();
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    let location_name = [];
    let btnActivate = [];
    let motion = [];
    let no_motion = [];
    let dual = [];
    let detailId = [];
    let preparedUser = [];
    var pagingcount = 0;

    function getJob(name) {
        loadJobPosition(name, 'checked_job_pos');
    }

    function loadDuressTable() {
        var pir_table = $('#pir_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'loadPirTable'
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
                targets: [2, 3],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 4,
                className: 'dt-body-middle-left',
                width: '20%'
            }, {
                targets: 5,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewPirPdf('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeletePir('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        pir_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            pir_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function pirModal() {
        $('#checkedBy').css('display', 'block');
        $('#checkedByPreview').css('display', 'none');
        $('#pirModal').modal('show');
        $('.btn-update').css('display', 'none');
        $('.btn-assign-save').css('display', 'block');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#pagingcount').val('0');
        loadCms();
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'generate_checklist'
            },
            success: function(result) {
                let tableRow = '';
                var count = 0;
                $.each(result, (key, value) => {
                    tableRow += '<tr>';
                    tableRow += '<td style="vertical-align:middle;"><input class="form-control locations fw-bold" value="' + value.location_name + '" disabled></td>';
                    tableRow += '<td><input type="hidden" class="btnActivate' + count + ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + value.location_name + '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                    tableRow += '<td><select class="form-select fw-bold motion motion' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold no-motion no-motion' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold dual dual' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '</tr>';
                    count++;
                });
                $('.data').append(tableRow);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function saveFunc() {
        if (submitValidation()) {
            let checkedBy = document.getElementById('checkedBy').value;
            let notedBy = document.getElementById('notedBy').value;
            $.ajax({
                url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'save_header',
                    prepared_by: logged_user,
                    checkedBy: checkedBy,
                    notedBy: notedBy
                },
                success: function(result) {
                    // alert(result);

                    $('.locations').each(function() {
                        var loc = $(this).val();
                        location_name.push([loc]);
                    });
                    $('.btnActivate').each(function() {
                        var btnActivated = $(this).val();
                        btnActivate.push([btnActivated]);
                    });
                    $('.motion').each(function() {
                        var mot = $(this).val();
                        motion.push([mot]);
                    });
                    $('.no-motion').each(function() {
                        var no = $(this).val();
                        no_motion.push([no]);
                    });
                    $('.dual').each(function() {
                        var duals = $(this).val();
                        dual.push([duals]);
                    });
                    console.log(location_name);
                    console.log(btnActivate);
                    console.log(motion);
                    console.log(no_motion);
                    console.log(dual);
                    for (let i = 0; i < location_name.length; i++) {
                        var strLocationArray = location_name[i];
                        var strLocation = strLocationArray.toString();

                        var strbtnActivateArray = btnActivate[i];
                        var strbtnActivate = strbtnActivateArray.toString();

                        var strMotionArray = motion[i];
                        var strMotion = strMotionArray.toString();

                        var strNomotionArray = no_motion[i];
                        var strNoMotion = strNomotionArray.toString();

                        var strDualArray = dual[i];
                        var strDual = strDualArray.toString();

                        let performedBy = logged_user;

                        $.ajax({
                            url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_detail',
                                performedBy: performedBy,
                                strLocation: strLocation,
                                strbtnActivate: strbtnActivate,
                                strMotion: strMotion,
                                strNoMotion: strNoMotion,
                                strDual: strDual,
                                generateRefno: result.pir_ref_no,
                                pirid: result.pirheader_id
                            },
                            success: function(result) {

                            }
                        });
                    }
                    refreshProcessTable();
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'updated Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#pirModal').modal('hide');
                    clearValues();
                }
            });
        }
    }

    function btnPreview(pirid) {
        $('#checkedBy').css('display', 'none');
        $('#checkedByPreview').css('display', 'block');
        $('.btn-update').val(pirid);
        $('#pagingcount').val('0');
        $('#pirModal').modal('show');
        $('.btn-update').css('display', 'block');
        $('.btn-assign-save').css('display', 'none');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_checked_by',
                pirid: pirid
            },
            success: function(result) {
                $('#checkedByPreview').val(result.result);
                setTimeout(function() {
                    loadJobPosition(result.result, 'checked_job_pos');
                }, 300);
            }
        })
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_details',
                pirid: pirid
            },
            success: function(result) {
                var count = 0;
                let tableRow = '';
                $.each(result, (key, value) => {
                    var motion = getActionCode(value.motion_detected);
                    var no_motion = getActionCode(value.no_motion_detected);
                    var dual = getActionCode(value.dual_presence);

                    var active = value.time_activated == null ? '<i class="fa-solid fa-qrcode fa-beat"></i>' : value.time_activated.substring(10, 16) + 'H';
                    var active2 = active == '<i class="fa-solid fa-qrcode fa-beat"></i>' ? '' : active;

                    tableRow += '<tr>';
                    tableRow += '<td style="vertical-align:middle;">' + value.location + '</td>';
                    tableRow += '<input type="hidden" class="form-control performed fw-bold" value="' + value.performed + '" disabled>';
                    tableRow += '<input type="hidden" class="form-control pirDetail fw-bold" value="' + value.pirdetailsid + '" disabled>';
                    if (value.time_activated == null) {
                        tableRow += '<td><input type="hidden" class="btnActivateValue btnActivate' + count + ' form-control" value="' + active2 + '" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold " value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + value.location_name + '\')">' + active.replace(':', ''); + '</button></td>';
                    } else {
                        tableRow += '<td><input type="hidden" class="btnActivateValue btnActivate' + count + ' form-control" value="' + active2 + '" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + value.location_name + '\')" disabled>' + active.replace(':', ''); + '</button></td>';
                    }
                    tableRow += '<td><select class="form-select fw-bold motion motion' + count + '" disabled> ';
                    tableRow += '<option value="' + value.motion_detected + '">' + motion + '</option>';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td><select class="form-select fw-bold no-motion no-motion' + count + '" disabled> ';
                    tableRow += '<option value="' + value.no_motion_detected + '">' + no_motion + '</option>';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td><select class="form-select fw-bold dual dual' + count + '" disabled> ';
                    tableRow += '<option value="' + value.dual_presence + '">' + dual + '</option>';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '</tr>';
                    count++;
                });
                $('.data').append(tableRow);
            }
        });
    }

    function updateFunc(pirid) {
        let checkedBy = document.getElementById('checkedBy').value;
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
            type: 'POST',
            data: {
                action: 'update_header',
                prepared_by: logged_user,
                pirid: pirid
            },
            success: function(result) {
                $('.performed').each(function() {
                    var user = $(this).val();
                    preparedUser.push([user]);
                });
                $('.pirDetail').each(function() {
                    var detail = $(this).val();
                    detailId.push([detail]);
                });
                $('.btnActivateValue').each(function() {
                    var btnActivated = $(this).val();
                    btnActivated != '' ? btnActivate.push([btnActivated.replace(':', '')]) : btnActivate.push([null]);
                });
                $('.motion').each(function() {
                    var mot = $(this).val();
                    motion.push([mot]);
                });
                $('.no-motion').each(function() {
                    var no = $(this).val();
                    no_motion.push([no]);
                });
                $('.dual').each(function() {
                    var duals = $(this).val();
                    dual.push([duals]);
                });
                console.log(btnActivate);
                console.log(preparedUser);
                for (let i = 0; i < motion.length; i++) {

                    var user = preparedUser[i];
                    var userPrepared = user.toString();

                    var strDetailArray = detailId[i];
                    var strDetail = strDetailArray.toString();

                    var strbtnActivateArray = btnActivate[i];
                    var strbtnActivate = strbtnActivateArray.toString();

                    var strMotionArray = motion[i];
                    var strMotion = strMotionArray.toString();

                    var strNoMotionArray = no_motion[i];
                    var strNoMotion = strNoMotionArray.toString();

                    var strDualArray = dual[i];
                    var strDual = strDualArray.toString();
                    let performedBy = logged_user;

                    $.ajax({
                        url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'update_detail',
                            strMotion: strMotion,
                            strNoMotion: strNoMotion,
                            strDual: strDual,
                            strbtnActivate: strbtnActivate,
                            userPrepared: userPrepared,
                            performedBy: performedBy,
                            strDetail: strDetail
                        },
                        success: function(result) {}
                    });
                }
                refreshProcessTable();
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'updated Succesfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#pirModal').modal('hide');
                clearValues();
            }
        });
    }

    function btnDeletePir(pirid) {
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
                    url: '../controller/phd_controller/phd_monthly_pir_alarm_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_details',
                        pirid: pirid
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

    function refreshProcessTable() {
        $('#pir_table').DataTable().ajax.reload(null, false);
    }

    function loadCms() {
        // loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'checkedBy', 'physical_security');
    }

    function status_change(selectValue) {
        if (selectValue == 0 && selectValue.length == 0) {
            pagingcount--;
        } else {
            pagingcount++;
        }
        $('#pagingcount').val(pagingcount);
    }

    function btnPreviewPirPdf(pirid) {
        strLink = "phd_monthly_pir_alarm_checklist_pdf.php?d=" + pirid;
        window.open(strLink, '_blank');
    }

    function validationQrScanner(count) {
        $('#btnActivate' + count).val(formatMILITARY(new Date));
        $('#btnActivate' + count).html(formatMILITARY(new Date));
        $('.btnActivate' + count).val(formatMILITARY(new Date));
        $('.motion' + count).prop('disabled', false);
        $('.no-motion' + count).prop('disabled', false);
        $('.dual' + count).prop('disabled', false);
    }

    function formatMILITARY(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        if (date.getMinutes < 10) getMinutes = '0' + getMinutes;
        var strTime = '0' + hours + '' + minutes + 'H';
        return strTime;
    }

    function getActionCode(code) {
        let codeResult;
        switch (code) {
            case 0:
                codeResult = '';
                break;
            case 1:
                codeResult = '/';
                break;
            case 2:
                codeResult = 'N/A';
                break;
        }
        return codeResult;
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

    function btnClose() {
        $('#pirModal').modal('hide');
        clearValues();
    }

    function submitValidation() {
        let isValidated = true;
        let checkedBy = document.getElementById('checkedBy').value;
        if (checkedBy.length == 0) {
            showFieldError('checkedBy', 'Check By must not be blank');
            if (isValidated) {
                $('#checkedBy').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('checkedBy');
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
        return isValidated;
    }

    function clearValues() {
        $("#duress_table").find("tr:gt(0)").remove();
        $('.data').html('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        location_name = [];
        btnActivate = [];
        motion = [];
        no_motion = [];
        dual = [];
        detailId = [];
        preparedUser = [];
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
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
        $('textarea').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>