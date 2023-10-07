<?php
include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
// * Check if module is within the application
session_start();
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
                <span class="page-title-physical" style="font-weight: lighter;">Monthly Fire Alarm System Checklist (SMOKE)</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light" style="font-weight: lighter;">Monthly Fire Alarm System</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="fireModal();"><i class="fa-solid fa-square-plus p-r-8"></i>Add Monthly Fire Alarm System</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="fire_table" class="table table-bordered table-striped fw-bold" width="100%">
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
                <div class="modal fade" id="fireModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-xl modal-dialog-centered  modal-dialog-scrollable modal-fullscreen-xl-down" role="document">
                        <div class="modal-content">
                            <div class="modal-header card-2">
                                <h4 class="modal-title text-uppercase text-light fw-bold"> Add Monthly Fire Alarm System Smoke Detector Checklist</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="fireModaltable" class="table table-bordered table-striped fw-bold">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th scope="col" style="text-align: center; width: 5%;">Zone</th>
                                                    <th scope="col" style="text-align: center; width: 22.5%; vertical-align: middle;">Location</th>
                                                    <th scope="col" style="text-align: center; width: 15%;">Date Performed</th>
                                                    <th scope="col" style="text-align: center; width: 15%;">No. of Units</th>
                                                    <th scope="col" style="text-align: center; width: 15%;">Working</th>
                                                    <th scope="col" style="text-align: center; width: 27.5%;">Remarks</th>
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
                                            <input type="text" id="checkedByPreview" class="form-control fw-bold" disabled>
                                            <select name="" id="checkedBy" onchange="getJob(this.value);" class="form-select fw-bold">
                                                <option value="">Choose...</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                            <label for="checkedBy" class="fw-bold">Checked By:</label>
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
    loadFireSmokeTable();
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    let btnActivateArray = [];
    let category = [];
    let zone = [];
    let locations = [];
    let units = [];
    let working = [];
    let remark = [];
    let detailId = [];
    let prepared = [];
    let fireDetail = [];
    var pagingcount = 0;

    function getJob(name) {
        loadJobPosition(name, 'checked_job_pos');
    }

    function loadFireSmokeTable() {
        var fire_table = $('#fire_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_fire_table'
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
                width: '45%'
            }, {
                targets: [2, 3],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 4,
                className: 'dt-body-middle-left',
                width: '25%'
            }, {
                targets: 5,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewFirePdf('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteFire('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        fire_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            fire_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function fireModal() {
        $('#checkedBy').css('display', 'block');
        $('#checkedByPreview').css('display', 'none');
        $('#fireModal').modal('show');
        $('.btn-update').css('display', 'none');
        $('.btn-assign-save').css('display', 'block');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#pagingcount').val('0');
        loadCms();
        $.ajax({
            url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'generate_checklist'
            },
            success: function(result) {
                let html = '';
                var count = 0;
                $.each(result, function(category_name, zone) {
                    var removeExtraName = category_name == 'Main Plant (Smoke Detector)' ? category_name.substring(0, 10) : category_name;
                    html += '<tr>';
                    html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;">' + removeExtraName + '</th>';
                    html += '</tr>';
                    $.each(zone, function(zone_index, location_value) {
                        html += '<tr>';
                        html += '<td rowspan="' + location_value.length + '" style="text-align: center;"> ' + zone_index + '</td>';
                        $.each(location_value, function(location_index, location_details) {
                            html += '<td style="vertical-align:middle;"><input type="hidden" class="form-control unit fw-bold" value="' + location_details + '" disabled>' + location_details + ' ';
                            html += '<td style="vertical-align: middle;"><input type="hidden" class="btnActivate' + count + ' form-control btnActivateValue" disabled><button type="button" id="btnActivate' + count + '" class="btn btn-dark btn-sm col-sm-12 fw-bold" value="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + location_details + '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                            html += '<td style="vertical-align:middle;"><input type="number" style="text-align:center;" class="form-control fw-bold units units' + count + '" disabled></td>';
                            html += '<td><select class="form-select fw-bold working working' + count + '" style="text-align:center;" onchange="status_change(this.value);" disabled> ';
                            html += '<option value="0"></option>';
                            html += '<option value="1" class="fw-bold text-secondary">/</option>';
                            html += '<option value="2" class="fw-bold text-secondary">X</option>';
                            html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                            html += '</select><input type="hidden" class="form-control locations" value="' + location_details + '" disabled><input type="hidden" class="form-control zone" value="' + zone_index + '" disabled><input type="hidden" class="form-control category_name" value="' + removeExtraName + '" disabled>';
                            html += '<td><textarea style="resize: none;" name="remarks" id="remarks' + count + '" cols="30" rows="1" class="form-control fw-bold remark remarks' + count + '" onchange="status_change(this.value);" disabled></textarea>';
                            html += '<div class="invalid-feedback"></div>';
                            count++;
                            html += '</tr>';
                        });
                    });
                });
                $('.data').append(html);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function saveFunc() {
        if (submitValidation()) {
            let checkedBy = document.getElementById('checkedBy').value;
            let notedBy = document.getElementById('notedBy').value;
            $.ajax({
                url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
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
                    $('.btnActivateValue').each(function() {
                        var procActivate = $(this).val();
                        btnActivateArray.push([procActivate]);
                    });

                    $('.locations').each(function() {
                        var procActivate = $(this).val();
                        locations.push([procActivate]);
                    });

                    $('.units').each(function() {
                        var procActivate = $(this).val() == '' ? 0 : $(this).val();
                        units.push([procActivate]);
                    });

                    $('.zone').each(function() {
                        var zoneVal = $(this).val();
                        zone.push([zoneVal]);
                    });
                    $('.category_name').each(function() {
                        var procActivate = $(this).val();
                        category.push([procActivate]);
                    });
                    $('.working').each(function() {
                        var procActivate = $(this).val();
                        working.push([procActivate]);
                    });
                    $('.remark').each(function() {
                        var procActivate = $(this).val();
                        remark.push([procActivate]);
                    });

                    console.log(btnActivateArray);
                    console.log(locations);
                    console.log(units);
                    console.log(zone);
                    console.log(category);
                    console.log(working);
                    console.log(remark);

                    for (let i = 0; i < locations.length; i++) {
                        var strbtnActivateArray = btnActivateArray[i];
                        var strbtnActivate = strbtnActivateArray.toString();

                        var strLocationArray = locations[i];
                        var strLocation = strLocationArray.toString();

                        var strUnitsArray = units[i];
                        var strUnits = strUnitsArray.toString();

                        var strZoneArray = zone[i];
                        var strZone = strZoneArray.toString();

                        var strCategoryArray = category[i];
                        var strCategory = strCategoryArray.toString();

                        var strWorkingArray = working[i];
                        var strWorking = strWorkingArray.toString();

                        var strRemarkArray = remark[i];
                        var strRemark = strRemarkArray.toString();

                        let performedBy = logged_user;

                        $.ajax({
                            url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_detail',
                                performedBy: performedBy,
                                strbtnActivate: strbtnActivate,
                                strLocation: strLocation,
                                strZone: strZone,
                                strUnits: strUnits,
                                strCategory: strCategory,
                                strWorking: strWorking,
                                strRemark: strRemark,
                                fireid: result.fireheader_id
                            },
                            success: function(result) {
                                // alert(result);
                            }
                        });
                    }
                    btnActivateArray = [];
                    category = [];
                    zone = [];
                    locations = [];
                    units = [];
                    working = [];
                    remark = [];
                    detailId = [];
                    prepared = [];
                    fireDetail = [];
                    pagingcount = [];
                    clearValues();
                    refreshProcessTable();
                    $('#fireModal').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'updated Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    }

    function updateFunc(fireid) {
        // alert(fireid);
        let checkedBy = document.getElementById('checkedBy').value;
        $.ajax({
            url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
            type: 'POST',
            data: {
                action: 'update_header',
                prepared_by: logged_user,
                fireid: fireid
            },
            success: function(result) {
                $('.fireDetail').each(function() {
                    var procActivate = $(this).val();
                    fireDetail.push([procActivate]);
                });
                $('.btnActivateValue').each(function() {
                    var procActivate = $(this).val();
                    btnActivateArray.push([procActivate]);
                });

                $('.locations').each(function() {
                    var procActivate = $(this).val();
                    locations.push([procActivate]);
                });

                $('.units').each(function() {
                    var procActivate = $(this).val() == '' ? 0 : $(this).val();
                    units.push([procActivate]);
                });

                $('.zone').each(function() {
                    var zoneVal = $(this).val();
                    zone.push([zoneVal]);
                });
                $('.category_name').each(function() {
                    var procActivate = $(this).val();
                    category.push([procActivate]);
                });
                $('.working').each(function() {
                    var procActivate = $(this).val();
                    working.push([procActivate]);
                });
                $('.remark').each(function() {
                    var procActivate = $(this).val();
                    remark.push([procActivate]);
                });
                $('.prepared').each(function() {
                    var procActivate = $(this).val();
                    prepared.push([procActivate]);
                });
                console.log(locations);

                for (let i = 0; i < locations.length; i++) {
                    var strDetailArray = fireDetail[i];
                    var strDetails = strDetailArray.toString();

                    var strbtnActivateArray = btnActivateArray[i];
                    var strbtnActivate = strbtnActivateArray.toString();

                    var strLocationArray = locations[i];
                    var strLocation = strLocationArray.toString();

                    var strUnitsArray = units[i];
                    var strUnits = strUnitsArray.toString();

                    var strZoneArray = zone[i];
                    var strZone = strZoneArray.toString();

                    var strCategoryArray = category[i];
                    var strCategory = strCategoryArray.toString();

                    var strWorkingArray = working[i];
                    var strWorking = strWorkingArray.toString();

                    var strRemarkArray = remark[i];
                    var strRemark = strRemarkArray.toString();

                    var strPrepared = prepared[i];
                    var strPrepared = strPrepared.toString();

                    let performedBy = logged_user;
                    $.ajax({
                        url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'update_detail',
                            strDetails: strDetails,
                            strPrepared: strPrepared,
                            strbtnActivate: strbtnActivate,
                            strLocation: strLocation,
                            strUnits: strUnits,
                            strZone: strZone,
                            strCategory: strCategory,
                            strWorking: strWorking,
                            strRemark: strRemark,
                            performedBy: performedBy
                        },
                        success: function(result) {
                            // alert(result);
                        }
                    });
                }
                btnActivateArray = [];
                category = [];
                zone = [];
                locations = [];
                units = [];
                working = [];
                remark = [];
                detailId = [];
                prepared = [];
                fireDetail = [];
                pagingcount = [];
                refreshProcessTable();
                $('#fireModal').modal('hide');
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

    function btnPreview(fireid) {
        $('#checkedBy').css('display', 'none');
        $('#checkedByPreview').css('display', 'block');
        $('.btn-update').val(fireid);
        $('#fireModal').modal('show');
        $('.btn-update').css('display', 'block');
        $('.btn-assign-save').css('display', 'none');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#pagingcount').val('0');
        loadCms();
        $.ajax({
            url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_checked_by',
                fireid: fireid
            },
            success: function(result) {
                $('#checkedByPreview').val(result.result);
                loadJobPosition(result.result, 'checked_job_pos');
            }
        })
        $.ajax({
            url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_detail',
                fireid: fireid
            },
            success: function(result) {
                let html = '';
                var count = 0;
                var qrCount = 0;
                let annunciator = 0;
                let mcp = 0;
                let bell = 0;
                let existingCount = 0;
                $.each(result, function(category_name, zone) {
                    html += '<tr>';
                    html += '<th colspan="7" style="vertical-align:middle;background-color: #D6EEEE;">' + category_name + '</th>';
                    html += '</tr>';
                    $.each(zone, function(zone_index, location_value) {
                        html += '<tr>';
                        html += '<td rowspan="' + location_value.length + '" style="text-align:center;">' + zone_index + '</td>';
                        $.each(location_value, function(location_index, location_details) {
                            var status = getActionCode(location_details.status);
                            var noUnits = location_details.no_units == 0 ? null : location_details.no_units;
                            html += '<td style="vertical-align:middle;"><input type="hidden" class="form-control unit fw-bold" value="' + location_details.location + '" disabled>' + location_details.location + ' ';
                            if (location_details.date_performed != null) {
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="btnActivate' + count + ' form-control btnActivateValue" value="' + location_details.date_performed + '" disabled><button type="button" id="btnActivate' + count + '" class="btn btn-dark btn-sm col-sm-12 fw-bold" value="' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + location_details.location + '\')" disabled>' + location_details.date_performed + '</button></td>';
                            } else {
                                html += '<td style="vertical-align: middle;"><input type="hidden" class="btnActivate' + count + ' form-control btnActivateValue" value="' + location_details.date_performed + '" disabled><button type="button" id="btnActivate' + count + '" class="btn btn-dark btn-sm col-sm-12 fw-bold" value="' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + location_details.location + '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                            }
                            html += '<td style="vertical-align:middle; text-align:center;"><input type="number" style="text-align:center;" class="form-control fw-bold units units' + count + '" value="' + noUnits + '" disabled></td>';
                            html += '<td><select class="form-select fw-bold working working' + count + '" style="text-align:center;" disabled> ';
                            html += '<option value="' + location_details.status + '">' + status + '</option>';
                            html += '<option value="0"></option>';
                            html += '<option value="1" class="fw-bold text-secondary">/</option>';
                            html += '<option value="2" class="fw-bold text-secondary">X</option>';
                            html += '<option value="3" class="fw-bold text-secondary">N/A</option>';
                            html += '</select><input type="hidden" class="form-control prepared fw-bold" value="' + location_details.prepared_by + '" disabled><input type="hidden" class="form-control locations" value="' + location_details.location + '" disabled><input type="hidden" class="form-control zone" value="' + zone_index + '" disabled><input type="hidden" class="form-control category_name" value="' + category_name + '" disabled><input type="hidden" class="form-control fireDetail fw-bold" value="' + location_details.firealarmsmokedetailsid + '" disabled>';
                            html += '<td><textarea style="resize: none;" name="remarks" id="remarks' + count + '" cols="30" rows="1" class="form-control fw-bold remark remarks' + count + '" onchange="status_change(this.value);" disabled>' + location_details.remarks + '</textarea>';
                            html += '<div class="invalid-feedback"></div>';
                            html += '</tr>';
                            count++;
                        });
                    });
                });
                $('.data').append(html);
            }
        });
    }

    function btnDeleteFire(fireid) {
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
                    url: '../controller/phd_controller/phd_monthy_fire_alarm_system_sd_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_detail',
                        fireid: fireid
                    },
                    success: function(result) {
                        $('#fire_table').DataTable().ajax.reload(null, false);
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

    function btnPreviewFirePdf(firealarmid) {
        strLink = "phd_monthy_fire_alarm_system_checklist_sd_pdf.php?d=" + firealarmid;
        window.open(strLink, '_blank');
    }

    function loadCms() {
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'checkedBy', 'physical_security');
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

    function validationQrScanner(count) {
        const date = new Date();
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        // This arrangement can be altered based on how we want the date's format to appear.
        let currentDate = `${year}-${month}-${day}`;
        $('#btnActivate' + count).val(currentDate);
        $('#btnActivate' + count).html(currentDate);
        $('.btnActivate' + count).val(currentDate);
        $('.units' + count).prop('disabled', false);
        $('.working' + count).prop('disabled', false);
        $('.remarks' + count).prop('disabled', false);
    }

    function refreshProcessTable() {
        $('#fire_table').DataTable().ajax.reload(null, false);
    }

    function submitValidation() {
        let isValidated = true;
        $('.locations').each(function() {
            var proc = $(this).val();
            locations.push([proc]);
        });
        $('.btnActivateValue').each(function() {
            var procActivate = $(this).val();
            btnActivateArray.push([procActivate]);
        });
        $('.remark').each(function() {
            var procActivate = $(this).val();
            remark.push([procActivate]);
        });
        for (let i = 0; i < locations.length; i++) {
            var strActivateArray = btnActivateArray[i];
            var activate = strActivateArray.toString();
            var strRemarksArray = remark[i];
            var remarks = strRemarksArray.toString();
            if (activate != '') {
                if (remarks == '') {
                    $('#remarks' + i).addClass('is-invalid').removeClass('is-valid');
                    isValidated = false;
                }
            }
        }
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
        locations = [];
        remark = [];
        return isValidated;
    }

    function status_change(selectValue) {
        if (selectValue == 0 && selectValue.length == 0) {
            pagingcount--;
        } else {
            pagingcount++;
        }
        $('#pagingcount').val(pagingcount);
    }

    function formatMILITARY(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        if (date.getMinutes < 10) getMinutes = '0' + getMinutes;
        var strTime = '0' + hours + '' + minutes + 'H';
        return strTime;
    }

    function btnClose() {
        $('#fireModal').modal('hide');
        $("#fireModaltable").find("tr:gt(0)").remove();
        $('.data').html('');
        $('select').find('option:first').prop('selected', 'selected');
        clearValues();
    }

    function clearValues() {
        $("#fireModaltable").find("tr:gt(0)").remove();
        $('.data').html('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        btnActivateArray = [];
        category = [];
        zone = [];
        locations = [];
        units = [];
        working = [];
        remark = [];
        detailId = [];
        prepared = [];
        pagingcount = [];
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