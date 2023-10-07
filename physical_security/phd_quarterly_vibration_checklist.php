<?php
include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
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
function loadParticular($PHD)
{
    $sqlstring = "SELECT * FROM phd_particular INNER JOIN phd_location ON phd_location.locationid = phd_particular.location_id WHERE quarterly_vibration_checklist ='true' ORDER BY phd_particular ASC";
    $result_stmt = $PHD->prepare($sqlstring);
    $result_stmt->execute();
    $result_Res = $result_stmt->fetchAll();
    echo '<option value="">Choose...</option>';
    foreach ($result_Res as $row) {
        echo '<option value ="' . $row['particular_name'] . '">' . $row['particular_name'] . '</option>';
    }
}
session_start();
$emp_no = $_SESSION['empno'];
$sqlstring = "SELECT CONCAT(emp_fn,' ',emp_sn) AS fullname FROM prl_employee WHERE empno = :emp_no";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->bindParam(':emp_no', $emp_no);
$result_stmt->execute();
$result_Res = $result_stmt->fetch(PDO::FETCH_ASSOC);
$fullname = $result_Res['fullname'];
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
                <span class="page-title-physical" style="font-weight: lighter;">Quarterly Vibration Checklist</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Quaterly Vibration List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="quarterModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Quaterly Vibration</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" class="form-control" id="process_count" value="0">
                            <input type="hidden" class="form-control" id="generateCode">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="quarterly_table" class="table table-bordered table-striped fw-bold" width="100%">
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
                <div class="modal fade" id="vibrationModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-lg modal-dialog-centered  modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header card-2">
                                <h4 class="modal-title text-uppercase fw-bold text-light"> Add Vibration</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="pro_table" class="table table-bordered table-striped fw-bold">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th style="text-align:center; width: 30%;">Particular</th>
                                                    <th style="text-align:center; width: 27.5%;">Action Taken</th>
                                                    <th style="text-align:center; width: 22.5%;">Time Activated</th>
                                                    <th style="text-align:center; width: 20%;">Time Verified</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row p-2">
                                    <div class="d-flex justify-content-center">
                                        <span class="fw-bold">Action Code</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="fw-bold col-sm-4">/ = Check</span>
                                    <span class="fw-bold col-sm-4">A = Adjust</span>
                                    <span class="fw-bold col-sm-4">L = Lubricate</span>
                                </div>
                                <div class="row">
                                    <span class="fw-bold col-sm-4">C = Clean</span>
                                    <span class="fw-bold col-sm-4">R = Repair</span>
                                    <span class="fw-bold col-sm-4">P = Polish</span>
                                </div>
                                <hr>
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
include './../helper/select_values.php'; ?>
<script>
    generateReference();
    loadDropdown();
    loadQuarterTable();
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    let count = 0;
    let locationArray = [];
    let particularArray = [];
    let actionArray = [];
    let actionTimeArray = [];
    let btnActivateArray = [];
    let qvsid = [];
    let prepared = [];
    let verify = [];
    let timeStampFormat = [];
    let timeStampFormatVerify = [];

    function getJob(name) {
        loadJobPosition(name, 'checked_job_pos');
    }

    function loadQuarterTable() {
        var quarterly_table = $('#quarterly_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'loadQuarterTable'
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
                width: '40%'
            }, {
                targets: 2,
                className: 'dt-body-middle-left',
                width: '15%',
            }, {
                targets: 3,
                className: 'dt-body-middle-left',
                width: '13%'
            }, {
                targets: 4,
                className: 'dt-body-middle-left',
                width: '17%',
            }, {
                targets: 5,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btn;
                    if (data[1] == data[2]) {
                        btn = `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewQuarter('${data[0]}');"><i class="fa-solid fa-file-pdf fa-bounce"></i></button> 
                        <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" disabled><i class="fa-solid fa-pen-to-square"></i></button> `;
                    } else {
                        btn = `<button type="button" class="btn btn-secondary" disabled><i class="fa-solid fa-file-pdf"></i></button> 
                        <button type="button" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="btnPreview('${data[0]}');"><i class="fa-solid fa-pen-to-square fa-beat"></i></button> `;
                    }
                    btn += `<button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteQuarter('${data[0]}');"><i class="fa-solid fa-trash-can  fa-shake"></i></button>`;
                    return btn;
                }
            }]
        });
        quarterly_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            quarterly_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function getActionCode(code) {
        let codeResult;
        switch (code) {
            case '':
                codeResult = '';
                break;
            case 'Clean':
                codeResult = '/';
                break;
            case 'Adjust':
                codeResult = 'C';
                break;
            case 'Repair/Replacement':
                codeResult = 'A';
                break;
            case 'lubricate':
                codeResult = 'R';
                break;
            case 'Polish':
                codeResult = 'L';
                break;
            case 'Check':
                codeResult = 'P';
                break;
        }
        return codeResult;
    }

    function btnPreview(id) {
        $('#checkedBy').css('display', 'none');
        $('#checkedByPreview').css('display', 'block');
        $('.btn-update').val(id);
        $('.btn-update').css('display', 'block');
        $('.btn-assign-save').css('display', 'none');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $('#vibrationModal').modal('show');
        $.ajax({
            url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview-checked-by',
                id: id
            },
            success: function(result) {
                $('#checkedByPreview').val(result.result);
                setTimeout(function() {
                    loadJobPosition(result.result, 'checked_job_pos');
                }, 300);
            }
        });
        $.ajax({
            url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview-data',
                id: id
            },
            success: function(result) {
                let tableRow = '';
                var count = 0;
                $.each(result, (key, value) => {
                    // console.log(value.qvs_activated_time);
                    let actionCode = getActionCode(value.qvs_action_code);
                    let actionTime = value.qvs_action_time == null ? '' : value.qvs_action_time;
                    let actionTime2 = actionTime == '' ? '' : actionTime.substring(11, 16);
                    let actionTime3 = actionTime2 == '' ? '' : actionTime2.replace(':', '') + 'H';

                    let activatedTime = value.qvs_activated_time == null ? '' : value.qvs_activated_time;
                    let activatedTime2 = activatedTime.substring(11, 16);
                    let activatedTime3 = activatedTime2 == '' ? activatedTime2.replace('H', '') : activatedTime2.replace(':', '') + 'H';
                    let activatedTime4 = activatedTime3 == '' ? '<i class="fa-solid fa-stopwatch fa-fade p-r-8"></i>Activate' : activatedTime3;
                    tableRow += '<tr>';
                    tableRow += '<td style="width: 30%;">';
                    tableRow += '<span class="p-1" id="particular' + count + '">' + value.qvs_particular + '</span>';
                    tableRow += '<input type="hidden" class="form-control fw-bold particularContainerInput" id="particularContainerInputed" value="' + value.qvs_particular + '"><input type="hidden" class="form-control fw-bold prepared" value="' + value.qvs_prepared_by + '"><input type="hidden" class="form-control fw-bold verify" value="' + value.qvs_verified_by + '"><input type="hidden" class="form-control fw-bold qvsid" value="' + value.qvsdetailid + '">';
                    tableRow += '<input type="hidden" class="form-control fw-bold locationContainerInput" id="locationContainerInputed" value="' + value.qvs_location_name + '">';
                    tableRow += '</td>';
                    tableRow += '<td style="width: 27.5%;">';
                    if (value.qvs_action_code == '') {
                        tableRow += '<select name="" id="action[]" class="form-select fw-bold getAction" onchange="getTime(' + count + ');">';
                    } else {
                        tableRow += '<select name="" id="action[]" class="form-select fw-bold getAction" onchange="getTime(' + count + ');" disabled>';
                    }
                    tableRow += '<option value="' + value.qvs_action_code + '">' + actionCode + '</option>';
                    tableRow += '<option value="">Choose...</option>';
                    tableRow += '<option value="Check">/</option>';
                    tableRow += '<option value="Clean">C</option>';
                    tableRow += '<option value="Adjust">A</option>';
                    tableRow += '<option value="Repair/Replacement">R</option>';
                    tableRow += '<option value="lubricate">L</option>';
                    tableRow += '<option value="Polish">P</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td style="width: 22.5%;"><input type="text" id="actionTime' + count + '" value="' + actionTime3 + '" class="form-control fw-bold actionTime" disabled><input type="hidden" id="timeStampFormat' + count + '" value="' + value.qvs_action_time + '" class="form-control fw-bold timeStampFormat" disabled></td>';
                    tableRow += '<td style="width: 25%;"><input type="hidden" class="btnActivate' + count + ' form-control btnActivated" value="" id="">';
                    if (value.qvs_action_time == null) {
                        tableRow += '<button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnActivate" value="' + value.qvs_activated_time + '" id="btnActivate' + count + '" onclick="activateTime(' + count + ')" disabled><i class="fa-solid fa-stopwatch fa-fade p-r-8"></i>Activate</button>';
                    } else {
                        if (value.qvs_prepared_by != logged_user) {
                            if (activatedTime4 == '<i class="fa-solid fa-stopwatch fa-fade p-r-8"></i>Activate') {
                                tableRow += '<button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnActivate" value="' + value.qvs_activated_time + '" id="btnActivate' + count + '" onclick="activateTime(' + count + ')">' + activatedTime4 + '</button></td>';
                            } else {
                                tableRow += '<button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnActivate" value="' + value.qvs_activated_time + '" id="btnActivate' + count + '" onclick="activateTime(' + count + ')" disabled>' + activatedTime4 + '</button></td>';
                            }
                        } else {
                            tableRow += '<button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnActivate" value="' + value.qvs_activated_time + '" id="btnActivate' + count + '" onclick="activateTime(' + count + ')" disabled>' + activatedTime4 + '</button></td>';
                        }
                    }
                    tableRow += '</tr>';
                    count++;
                });
                $('.data').append(tableRow);
            }
        })
    }

    function quarterModal() {
        $('.btn-update').css('display', 'none');
        $('.btn-assign-save').css('display', 'block');
        $('#vibrationModal').modal('show');
        loadJobPosition(logged_user, 'perform_job_pos');
        $('#noted_job_pos').html('Vice President for Physical Security');
        $.ajax({
            url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'generate_particular'
            },
            success: function(result) {
                let tableRow = '';
                var count = 0;
                $.each(result, (key, value) => {
                    tableRow += '<tr>';
                    tableRow += '<td style="width: 30%;">';
                    tableRow += '<span class="p-1" id="particular' + count + '">' + value.particular_name + '</span>';
                    tableRow += '<input type="hidden" class="form-control fw-bold particularContainerInput" id="particularContainerInputed" value="' + value.particular_name + '">';
                    tableRow += '<input type="hidden" class="form-control fw-bold locationContainerInput" id="locationContainerInputed" value="' + value.location_name + '">';
                    tableRow += '</td>';
                    tableRow += '<td style="width: 27.5%;">';
                    tableRow += '<select name="" id="action[]" class="form-select fw-bold getAction" onchange="getTime(' + count + ');">';
                    tableRow += '<option value="">Choose...</option>';
                    tableRow += '<option value="Check">/</option>';
                    tableRow += '<option value="Clean">C</option>';
                    tableRow += '<option value="Adjust">A</option>';
                    tableRow += '<option value="Repair/Replacement">R</option>';
                    tableRow += '<option value="lubricate">L</option>';
                    tableRow += '<option value="Polish">P</option>';
                    tableRow += '</select></td>';
                    tableRow += '<td style="width: 22.5%;"><input type="text" id="actionTime' + count + '" class="form-control fw-bold actionTime" disabled><input type="hidden" id="timeStampFormat' + count + '" class="form-control fw-bold timeStampFormat" disabled></td>';
                    tableRow += '<td style="width: 25%;"><input type="hidden" class="btnActivate' + count + ' form-control btnActivated" value="" id=""><button type="button" class="btn btn-warning btn-sm col-sm-12 fw-bold btnActivate" value="" id="btnActivate' + count + '" onclick="activateTime(' + count + ')" disabled><i class="fa-solid fa-stopwatch fa-fade p-r-8"></i>Activate</button></td>';
                    tableRow += '</tr>';
                    count++;
                });
                $('.data').append(tableRow);
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });
        $('#checkedBy').css('display', 'block');
        $('#checkedByPreview').css('display', 'none');
    }

    function updateFunc(id) {
        let refno = document.getElementById('generateCode').value;
        $('.qvsid').each(function() {
            var id = $(this).val();
            qvsid.push([id]);
        });
        $('.getAction').each(function() {
            var proc2 = $(this).val();
            actionArray.push([proc2]);
        });
        $('.timeStampFormat').each(function() {
            var timeFormat = $(this).val();
            timeStampFormat.push([timeFormat]);
        });
        $('.prepared').each(function() {
            var prepareBy = $(this).val();
            prepared.push([prepareBy]);
        });
        $('.verify').each(function() {
            var verified = $(this).val();
            verify.push([verified]);
        });
        $('.btnActivated').each(function() {
            var activate = $(this).val();
            btnActivateArray.push([activate]);
        });

        $('.particularContainerInput').each(function() {
            var proc1 = $(this).val();
            particularArray.push([proc1]);
        });
        console.log(btnActivateArray);
        // console.log(qvsid);
        // console.log(actionArray);
        // console.log(timeStampFormat);
        // console.log(prepared);
        // console.log(verify);
        // console.log(particularArray);
        for (let i = 0; i < particularArray.length; i++) {
            var strQvsIdArray = qvsid[i];
            var strQvsId = strQvsIdArray.toString();
            var strActionArray = actionArray[i];
            var strAction = strActionArray.toString();
            var strTimeStampFormatArray = timeStampFormat[i];
            var strTimeStampFormat = strTimeStampFormatArray.toString();
            var strPreparedArray = prepared[i];
            var strPrepared = strPreparedArray.toString();
            var strActivateArray = btnActivateArray[i];
            var strActivate = strActivateArray.toString();
            var strVerifyArray = verify[i];
            var strVerify = strVerifyArray.toString();

            $.ajax({
                url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_detail',
                    strQvsId: strQvsId,
                    strAction: strAction,
                    strTimeStampFormat: strTimeStampFormat,
                    refno: refno,
                    strPrepared: strPrepared,
                    strActivate: strActivate,
                    strVerify: strVerify,
                    logged_user: logged_user
                },
                success: function(result) {

                }
            });
        }
        refreshProcessTable();
        $('#vibrationModal').modal('hide');
        Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Saved Succesfully!',
            showConfirmButton: false,
            timer: 1500
        });
        clearValues();
    }

    function saveFunc() {
        if (submitValidation('save')) {
            let refno = document.getElementById('generateCode').value;
            let performedBy = document.getElementById('performedBy').value;
            let checkedBy = document.getElementById('checkedBy').value;
            let notedBy = document.getElementById('notedBy').value;
            $.ajax({
                url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_header',
                    performedBy: performedBy,
                    checkedBy: checkedBy,
                    notedBy: notedBy,
                    refno: refno
                },
                success: function(qvsid) {
                    $('.locationContainerInput').each(function() {
                        var locate = $(this).val();
                        locationArray.push([locate]);
                    });
                    $('.particularContainerInput').each(function() {
                        var proc1 = $(this).val();
                        particularArray.push([proc1]);
                    });
                    $('.getAction').each(function() {
                        var proc2 = $(this).val();
                        actionArray.push([proc2]);
                    });
                    $('.actionTime').each(function() {
                        var proc3 = $(this).val();
                        actionTimeArray.push([proc3]);
                    });
                    $('.btnActivated').each(function() {
                        var activate = $(this).val();
                        btnActivateArray.push([activate]);
                    });
                    $('.timeStampFormat').each(function() {
                        var timeFormat = $(this).val();
                        timeStampFormat.push([timeFormat]);
                    });
                    console.log(timeStampFormat);
                    console.log(locationArray);
                    console.log(particularArray);
                    console.log(actionArray);
                    console.log(actionTimeArray);
                    console.log(btnActivateArray);
                    for (let i = 0; i < particularArray.length; i++) {
                        var strlocationArray = locationArray[i];
                        var strLocation = strlocationArray.toString();
                        var strPartConArray = particularArray[i];
                        var strParticular = strPartConArray.toString();
                        var strActionArray = actionArray[i];
                        var strAction = strActionArray.toString();
                        var strTimeArray = actionTimeArray[i];
                        var strTime = strTimeArray.toString();
                        var strActivateArray = btnActivateArray[i];
                        var strbtnActivateArray = strActivateArray.toString();
                        var strTimeStampFormatArray = timeStampFormat[i];
                        var strTimeStampFormat = strTimeStampFormatArray.toString();
                        $.ajax({
                            url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_detail',
                                strLocation: strLocation,
                                strParticular: strParticular,
                                strAction: strAction,
                                strTime: strTime,
                                strTimeStampFormat: strTimeStampFormat,
                                strbtnActivateArray: strbtnActivateArray,
                                refno: refno,
                                logged_user: logged_user,
                                qvsid: qvsid
                            },
                            success: function(result) {
                                console.log(result);
                            }
                        });
                    }
                    refreshProcessTable();
                    $('#vibrationModal').modal('hide');
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

    function btnDeleteQuarter(id) {
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
                    url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
                    type: 'POST',
                    type: 'POST',
                    data: {
                        action: 'delete_quarter',
                        id: id
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

    function generateReference() {
        $.ajax({
            url: '../controller/phd_controller/phd_quarterly_vibration_contr.class.php',
            type: 'POST',
            data: {
                action: 'generate_reference'
            },
            success: function(refno) {
                $('#generateCode').val(refno);
            }
        });
    }

    function loadDropdown() {
        loadSelectValue('phd_authorized_checked_by', 'checked_by_name', 'checkedBy', 'physical_security');
    }

    function loadSelectValue(inTable, inField, inObject, connection) {
        $.ajax({
            url: '../functions/common_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_values',
                inTable: inTable,
                inField: inField,
                connection: connection
            },
            success: function(result) {
                $.each(result, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${value}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${value}">${value}</option>`);
                    }
                });
            }
        });
    }

    function refreshProcessTable() {
        $('#quarterly_table').DataTable().ajax.reload(null, false);
    }

    function btnPreviewQuarter(id) {
        strLink = "quarterly_vs_checklist_pdf.php?d=" + id;
        window.open(strLink, '_blank');
    }

    function clearValues() {
        $("#quarter_table").find("tr:gt(0)").remove();
        $('.data').html('');
        $('select').find('option:first').prop('selected', 'selected');
        $('.btn-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i>Save');
        clearAttributes();
        count = 0;
        locationArray = [];
        particularArray = [];
        actionArray = [];
        actionTimeArray = [];
        btnActivateArray = [];
        qvsid = [];
        verify = [];
        timeStampFormat = [];
        timeStampFormatVerify = [];
        $('#perform_job_pos').html('');
        $('#checked_job_pos').html('');
        $('#noted_job_pos').html('');
    }

    function activateTime(id) {
        $('#btnActivate' + id).html(formatMILITARY(new Date));
        $('.btnActivate' + id).val(timestamp());
    }

    function formatMILITARY(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        if (date.getMinutes < 10) getMinutes = getMinutes;
        var strTime = hours + '' + minutes + 'H';
        return strTime;
    }

    function timestamp() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        };
        const formattedDateTime = now.toLocaleString('en-US', options);
        let changeFomat = formattedDateTime.replace(",", "");
        // let changeFomat2 = formattedDateTime.replace("/", "-");
        // alert(changeFomat);
        return changeFomat;
    }

    function getTime(id) {
        $('#timeStampFormat' + id).val(timestamp());
        $('#actionTime' + id).val(formatMILITARY(new Date));
    }

    function btnClose() {
        $('#vibrationModal').modal('hide');
        clearValues();
    }

    function submitValidation(val) {
        let isValidated = true;
        let checkedBy = document.getElementById('checkedBy').value;
        if (val == 'save') {
            if (checkedBy.length == 0) {
                showFieldError('checkedBy', 'Check By must not be blank');
                if (isValidated) {
                    $('#checkedBy').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('checkedBy');
            }
            return isValidated;
        }
    }

    function validationParticular(count, value) {
        let checkIfExist = array.includes(value)
        array.push(value);
        if (checkIfExist === true) {
            $('#particular_pro' + count).val('');
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Particular Already Pick!',
                showConfirmButton: false,
                timer: 1500
            });
        }
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