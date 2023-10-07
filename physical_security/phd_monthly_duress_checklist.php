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
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-physical" style="font-weight: lighter;">Monthly Duress Checklist</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Monthly Duress Checklist</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="duressModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Duress Monthly</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" class="form-control" id="process_count" value="0">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="duress_preview_table" class="table table-bordered table-striped fw-bold" width="100%">
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
                <div class="modal fade" id="duressModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-xl modal-dialog-centered  modal-dialog-scrollable modal-fullscreen-xl-down" role="document">
                        <div class="modal-content">
                            <div class="modal-header card-2">
                                <h4 class="modal-title text-uppercase text-light fw-bold"> Add Monthly Duress Checklist</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="duress_table" class="table table-bordered table-striped fw-bold">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th rowspan="2" style="text-align: center; vertical-align: middle; width:20%;">Location</th>
                                                    <th colspan="3" style="text-align: center; width:60%;">Action Taken</th>
                                                    <th rowspan="2" style="text-align: center; vertical-align: middle; width:9%;">Time Activated</th>
                                                    <th rowspan="2" style="text-align: center; vertical-align: middle; width:9%;">Time Verified</th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: center;">Active Duress</th>
                                                    <th style="text-align: center;">Outsource CMS</th>
                                                    <th style="text-align: center;">Response within 2mins.</th>
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
                                            <input type="text" id="performedBy" class="form-control fw-bold" value="<?php echo $_SESSION['fullname']; ?>" disabled>
                                            <div class="invalid-feedback"></div>
                                            <label for="notedBy" class="fw-bold">Prepared By:</label>
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
                                            <label for="notedBy" class="fw-bold">Checked By:</label>
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
    loadDuressTable();
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    let detailId = [];
    let location_name = [];
    let active = [];
    let outsource = [];
    let response = [];
    let btnActivate = [];
    let btnVerified = [];
    let userPrepared = [];
    var pagingcount = 0;

    function getJob(name) {
        loadJobPosition(name, 'checked_job_pos');
    }

    function loadDuressTable() {
        var duress_preview_table = $('#duress_preview_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'loadDuressTable'
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
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewDuressPdf('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteDuress('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        duress_preview_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            duress_preview_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function duressModal() {
        $('#checkedBy').css('display', 'block');
        $('#checkedByPreview').css('display', 'none');
        $('#duressModal').modal('show');
        $('.btn-update').css('display', 'none');
        $('.btn-assign-save').css('display', 'block');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#pagingcount').val('0');
        loadCms();
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
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
                    tableRow += '<td><select class="form-select fw-bold activeDur active' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">C</option>';
                    tableRow += '<option value="3" class="fw-bold text-secondary">A</option>';
                    tableRow += '<option value="4" class="fw-bold text-secondary">R</option>';
                    tableRow += '<option value="5" class="fw-bold text-secondary">L</option>';
                    tableRow += '<option value="6" class="fw-bold text-secondary">P</option>';
                    tableRow += '<option value="7" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold outsource outsource' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">C</option>';
                    tableRow += '<option value="3" class="fw-bold text-secondary">A</option>';
                    tableRow += '<option value="4" class="fw-bold text-secondary">R</option>';
                    tableRow += '<option value="5" class="fw-bold text-secondary">L</option>';
                    tableRow += '<option value="6" class="fw-bold text-secondary">P</option>';
                    tableRow += '<option value="7" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td style="vertical-align: middle; text-align: center;"><select class="form-select fw-bold response response' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">C</option>';
                    tableRow += '<option value="3" class="fw-bold text-secondary">A</option>';
                    tableRow += '<option value="4" class="fw-bold text-secondary">R</option>';
                    tableRow += '<option value="5" class="fw-bold text-secondary">L</option>';
                    tableRow += '<option value="6" class="fw-bold text-secondary">P</option>';
                    tableRow += '<option value="7" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td><input type="hidden" class="btnActivate' + count + ' form-control" value="" id="" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + value.location_name + '\')"><i class="fa-solid fa-qrcode fa-beat"></i></button></td>';
                    tableRow += '<td><input type="hidden" class="btnVerified' + count + ' form-control" value="" id="" disabled><button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnVerified" value="" id="btnVerified' + count + '" onclick="activateTime(' + count + ')" disabled><i class="fa-solid fa-stopwatch"></i></button></td>';
                    tableRow += '</tr>';
                    count++;
                });
                $('.data').append(tableRow);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
    }

    function saveFunc() {
        if (submitValidation('save')) {
            let checkedBy = document.getElementById('checkedBy').value;
            let notedBy = document.getElementById('notedBy').value;
            $.ajax({
                url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
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
                    $('.locations').each(function() {
                        var loc = $(this).val();
                        location_name.push([loc]);
                    });
                    $('.activeDur').each(function() {
                        var actived = $(this).val();
                        active.push([actived]);
                    });
                    $('.outsource').each(function() {
                        var outsourced = $(this).val();
                        outsource.push([outsourced]);
                    });
                    $('.response').each(function() {
                        var responsed = $(this).val();
                        response.push([responsed]);
                    });
                    $('.btnActivate').each(function() {
                        var btnActivated = $(this).val();
                        btnActivate.push([btnActivated]);
                    });
                    $('.btnVerified').each(function() {
                        var btnVerifieded = $(this).val();
                        btnVerified.push([btnVerifieded]);
                    });
                    console.log(location_name);
                    console.log(active);
                    console.log(outsource);
                    console.log(response);
                    console.log(btnActivate);
                    console.log(btnVerified);
                    for (let i = 0; i < location_name.length; i++) {
                        var strLocationArray = location_name[i];
                        var strLocation = strLocationArray.toString();

                        var strActiveArray = active[i];
                        var strActive = strActiveArray.toString();

                        var strOutsourceArray = outsource[i];
                        var strOutsource = strOutsourceArray.toString();

                        var strResponseArray = response[i];
                        var strResponse = strResponseArray.toString();

                        var strbtnActivateArray = btnActivate[i];
                        var strbtnActivate = strbtnActivateArray.toString();

                        var strbtnVerifiedArray = btnVerified[i];
                        var strbtnVerified = strbtnVerifiedArray.toString();
                        let performedBy = logged_user;
                        $.ajax({
                            url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_detail',
                                performedBy: performedBy,
                                strLocation: strLocation,
                                strActive: strActive,
                                strOutsource: strOutsource,
                                strResponse: strResponse,
                                strbtnActivate: strbtnActivate,
                                strbtnVerified: strbtnVerified,
                                generateRefno: result.paging_ref_no,
                                duressid: result.pagingheader_id
                            },
                            success: function(result) {

                            }
                        });
                    }
                    refreshProcessTable();
                    $('#duressModal').modal('hide');
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

    function btnPreview(duressid) {
        $('#checkedBy').css('display', 'none');
        $('#checkedByPreview').css('display', 'block');
        $('.btn-update').val(duressid);
        $('#duressModal').modal('show');
        $('.btn-update').css('display', 'block');
        $('.btn-assign-save').css('display', 'none');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#pagingcount').val('0');
        loadCms();
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_checked_by',
                duressid: duressid
            },
            success: function(result) {
                $('#checkedByPreview').val(result.result);
                setTimeout(function() {
                    loadJobPosition(result.result, 'checked_job_pos');
                }, 300);
            }
        })
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_details',
                duressid: duressid
            },
            success: function(result) {
                var count = 0;
                let tableRow = '';
                $.each(result, (key, value) => {
                    var actived = getActionCode(value.active_duress);
                    var outsource = getActionCode(value.outsource_cms);
                    var response = getActionCode(value.response_within_2mins);

                    var active = value.time_activated == null ? '<i class="fa-solid fa-qrcode fa-beat"></i>' : value.time_activated.substring(10, 16) + 'H';
                    var active2 = active == '<i class="fa-solid fa-qrcode fa-beat"></i>' ? '' : active;

                    var verified = value.time_verified == null ? '<i class="fa-solid fa-stopwatch"></i>' : value.time_verified.substring(10, 16) + 'H';
                    var time_verify = verified == '<i class="fa-solid fa-stopwatch"></i>' ? '' : verified;

                    tableRow += '<tr>';
                    tableRow += '<td style="vertical-align:middle;">' + value.location + '</td>';
                    tableRow += '<input type="hidden" class="form-control duressDetail fw-bold" value="' + value.duressdetailsid + '" disabled>';
                    tableRow += '<input type="hidden" class="form-control prepared fw-bold" value="' + value.performed + '" disabled>';
                    tableRow += '<td><select class="form-select fw-bold activeDur active' + count + '"  onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="' + value.active_duress + '">' + actived + '</option>';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">C</option>';
                    tableRow += '<option value="3" class="fw-bold text-secondary">A</option>';
                    tableRow += '<option value="4" class="fw-bold text-secondary">R</option>';
                    tableRow += '<option value="5" class="fw-bold text-secondary">L</option>';
                    tableRow += '<option value="6" class="fw-bold text-secondary">P</option>';
                    tableRow += '<option value="7" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td><select class="form-select fw-bold outsource outsource' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="' + value.outsource_cms + '">' + outsource + '</option>';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">C</option>';
                    tableRow += '<option value="3" class="fw-bold text-secondary">A</option>';
                    tableRow += '<option value="4" class="fw-bold text-secondary">R</option>';
                    tableRow += '<option value="5" class="fw-bold text-secondary">L</option>';
                    tableRow += '<option value="6" class="fw-bold text-secondary">P</option>';
                    tableRow += '<option value="7" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td><select class="form-select fw-bold response response' + count + '" onchange="status_change(this.value);" disabled> ';
                    tableRow += '<option value="' + value.response_within_2mins + '">' + response + '</option>';
                    tableRow += '<option value="0"></option>';
                    tableRow += '<option value="1" class="fw-bold text-secondary">/</option>';
                    tableRow += '<option value="2" class="fw-bold text-secondary">C</option>';
                    tableRow += '<option value="3" class="fw-bold text-secondary">A</option>';
                    tableRow += '<option value="4" class="fw-bold text-secondary">R</option>';
                    tableRow += '<option value="5" class="fw-bold text-secondary">L</option>';
                    tableRow += '<option value="6" class="fw-bold text-secondary">P</option>';
                    tableRow += '<option value="7" class="fw-bold text-secondary">N/A</option>';
                    tableRow += '</select></td>';
                    if (value.time_activated == null) {
                        tableRow += '<td><input type="hidden" class="btnActivateValue btnActivate' + count + ' form-control"  value="' + active2 + '" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold " value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + value.location_name + '\')">' + active.replace(':', ''); + '</button></td>';
                        tableRow += '<td><input type="hidden" class="btnVerifiedValue btnVerified' + count + ' form-control" value="' + time_verify + '" disabled><button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold " value="" id="btnVerified' + count + '" onclick="activateTime(' + count + ')" disabled>' + verified.replace(':', ''); + '</button></td>';
                    } else {
                        tableRow += '<td><input type="hidden" class="btnActivateValue btnActivate' + count + ' form-control"  value="' + active2 + '" disabled><button type="button" class="btn btn-dark btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Scan QR Code" onclick="scanQrCode(' + count + ',\'' + value.location_name + '\')" disabled>' + active.replace(':', ''); + '</button></td>';
                        if (value.performed == logged_user) {
                            tableRow += '<td><input type="hidden" class="btnVerifiedValue btnVerified' + count + ' form-control" value="' + time_verify + '" disabled><button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnVerified" value="" id="btnVerified' + count + '" onclick="activateTime(' + count + ')" disabled>' + verified.replace(':', ''); + '</button></td>';
                        } else {
                            if (verified != '<i class="fa-solid fa-stopwatch"></i>') {
                                tableRow += '<td><input type="hidden" class="btnVerifiedValue btnVerified' + count + ' form-control" value="' + time_verify + '"><button type="button" class="btn btn-secondary btn-sm col-sm-12 fw-bold btnVerified" value="" id="btnVerified' + count + '" onclick="activateTime(' + count + ')" disabled>' + verified.replace(':', ''); + '</button></td>';
                            } else {
                                tableRow += '<td><input type="hidden" class="btnVerifiedValue btnVerified' + count + ' form-control" value="' + time_verify + '"><button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnVerified" value="" id="btnVerified' + count + '" onclick="activateTime(' + count + ')">' + verified.replace(':', ''); + '</button></td>';
                            }
                        }
                    }
                    tableRow += '</tr>';
                    count++;
                });
                $('.data').append(tableRow);
            }
        });
    }

    function updateFunc(duressid) {
        // if (submitValidation('update')) {
        let checkedBy = document.getElementById('checkedBy').value;
        $.ajax({
            url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
            type: 'POST',
            data: {
                action: 'update_header',
                prepared_by: logged_user,
                duressid: duressid
            },
            success: function(result) {
                $('.locations').each(function() {
                    var loc = $(this).val();
                    location_name.push([loc]);
                });
                $('.duressDetail').each(function() {
                    var detail = $(this).val();
                    detailId.push([detail]);
                });

                $('.activeDur').each(function() {
                    var actived = $(this).val();
                    active.push([actived]);
                });
                $('.outsource').each(function() {
                    var outsourced = $(this).val();
                    outsource.push([outsourced]);
                });
                $('.response').each(function() {
                    var responsed = $(this).val();
                    response.push([responsed]);
                });
                $('.btnActivateValue').each(function() {
                    var btnActivated = $(this).val();
                    btnActivated != '' ? btnActivate.push([btnActivated.replace(':', '')]) : btnActivate.push([null]);
                });
                $('.btnVerifiedValue').each(function() {
                    var btnVerifieded = $(this).val();
                    btnVerifieded != '' ? btnVerified.push([btnVerifieded.replace(':', '')]) : btnVerified.push([null]);
                });
                $('.prepared').each(function() {
                    var preparedBy = $(this).val();
                    userPrepared.push([preparedBy]);
                });
                console.log(btnVerified);
                for (let i = 0; i < response.length; i++) {
                    var user = userPrepared[i];
                    var userPrepare = user.toString();

                    var details = detailId[i];
                    var detailsId = details.toString();

                    var strActiveArray = active[i];
                    var strActive = strActiveArray.toString();

                    var strOutsourceArray = outsource[i];
                    var strOutsource = strOutsourceArray.toString();

                    var strResponseArray = response[i];
                    var strResponse = strResponseArray.toString();

                    var strbtnActivateArray = btnActivate[i];
                    var strbtnActivate = strbtnActivateArray.toString();

                    var strbtnVerifiedArray = btnVerified[i];
                    var strbtnVerified = strbtnVerifiedArray.toString();
                    let performedBy = logged_user;
                    $.ajax({
                        url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'update_detail',
                            strActive: strActive,
                            strOutsource: strOutsource,
                            strResponse: strResponse,
                            strbtnActivate: strbtnActivate,
                            strbtnVerified: strbtnVerified,
                            userPrepare: userPrepare,
                            performedBy: performedBy,
                            duressid: detailsId
                        },
                        success: function(result) {}
                    });
                }
                refreshProcessTable();
                $('#duressModal').modal('hide');
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
        // }
    }

    function btnDeleteDuress(duressid) {
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
                    url: '../controller/phd_controller/phd_monthly_duress_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_details',
                        duressid: duressid
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

    function loadCms() {
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'checkedBy', 'physical_security');
    }

    function validationQrScanner(count) {
        $('#btnActivate' + count).val(formatMILITARY(new Date));
        $('#btnActivate' + count).html(formatMILITARY(new Date));
        $('.btnActivate' + count).val(formatMILITARY(new Date));
        $('.active' + count).prop('disabled', false);
        $('.outsource' + count).prop('disabled', false);
        $('.response' + count).prop('disabled', false);
    }

    function refreshProcessTable() {
        $('#duress_preview_table').DataTable().ajax.reload(null, false);
    }

    function btnPreviewDuressPdf(duressid) {
        strLink = "phd_monthly_duress_checklist_pdf.php?d=" + duressid;
        window.open(strLink, '_blank');
    }

    function status_change(selectValue) {
        if (selectValue == 0 && selectValue.length == 0) {
            pagingcount--;
        } else {
            pagingcount++;
        }
        $('#pagingcount').val(pagingcount);
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
                codeResult = 'C';
                break;
            case 3:
                codeResult = 'A';
                break;
            case 4:
                codeResult = 'R';
                break;
            case 5:
                codeResult = 'L';
                break;
            case 6:
                codeResult = 'P';
                break;
            case 7:
                codeResult = 'N/A';
                break;
        }
        return codeResult;
    }

    function activateTime(count) {
        remarksValidationCount = count;
        $('#btnVerified' + count).val(formatMILITARY(new Date));
        $('#btnVerified' + count).html(formatMILITARY(new Date));
        $('.btnVerified' + count).val(formatMILITARY(new Date));
        $('#btnVerified' + count).prop('disabled', true);
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

    function submitValidation(value) {
        let isValidated = true;
        if (value == 'save') {
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
        } else if (value == 'update') {
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
    }

    function btnClose() {
        $('#duressModal').modal('hide');
        clearValues();
    }

    function clearValues() {
        $("#duress_table").find("tr:gt(0)").remove();
        $('.data').html('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
        detailId = [];
        location_name = [];
        active = [];
        outsource = [];
        response = [];
        btnActivate = [];
        btnVerified = [];
        userPrepared = [];
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