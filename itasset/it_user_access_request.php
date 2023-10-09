<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    // $data_base64 = base64_encode($sqlstring);
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    // $json_response = curl_exec($curl);
    // //* ====== Close Connection ======
    // curl_close($curl);
    // // * ======== Prepare Array ========
    // $data_result = json_decode($json_response, true);
    // foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
} ?>

<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom right, #fa3c3c, #aa0000);
        border-radius: 100vw;
    }
</style>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<!-- Insert your code here -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it ">User Access Request</span>
            </div>
            <div class="row d-flex- justify-content-center">
                <div class="card col-sm-8 shadow border-0 mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-danger fw-bold">Access Request</h4>
                            <div>
                                <button class="btn btn-danger fw-bold" id="generatePdf" onclick="generatePdf();"><i class="fa-solid fa-file-pdf"></i> Generate PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex- justify-content-center">
                <div class="card col-sm-8 shadow border-0">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm">
                                <div class="form-floating">
                                    <select name="" id="control_no" class="form-select fw-bold" onchange="preview();"></select>
                                    <label for="" class="fw-bold">Control No:</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm mb-2">
                                <div class="form-floating">
                                    <input type="date" id="date_request" value="<?php echo date('Y-m-d'); ?>" class="form-control fw-bold" disabled>
                                    <label for="" class="fw-bold">Date Request:</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-floating">
                                    <input type="date" id="date_needed" class="form-control fw-bold">
                                    <div class="invalid-feedback"></div>
                                    <label for="" class="fw-bold">Date Needed:</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm d-flex justify-content-center">
                            <hr color="red" size="2" width="15%" align="center">
                            <span class="fw-bold text-danger">ACCESS</span>
                            <hr color="red" size="2" width="85%" align="center">
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="access" id="access1" value="New" checked>
                                    <label class="form-check-label fw-bold" for="access1">
                                        New
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="access" id="access2" value="Additional">
                                    <label class="form-check-label fw-bold" for="access2">
                                        Additional
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="access" id="access3" value="Change">
                                    <label class="form-check-label fw-bold" for="access3">
                                        Change
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm d-flex justify-content-center">
                            <hr color="red" size="2" width="15%" align="center">
                            <span class="fw-bold text-danger">PRIORITY</span>
                            <hr color="red" size="2" width="85%" align="center">
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priority" id="priority1" value="Urgent" checked>
                                    <label class="form-check-label fw-bold" for="priority1">
                                        Urgent
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priority" id="priority2" value="For Scheduling">
                                    <label class="form-check-label fw-bold" for="priority2">
                                        For Scheduling
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="domainAccount">
                                    <label class="form-check-label fw-bold" for="domainAccount">
                                        Domain Account
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="mail_account" value="">
                                </div>
                                <input type="text" class="form-control" id="mail_account_input" placeholder="Mail Account" disabled>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="file_storage_access" value="">
                                </div>
                                <input type="text" class="form-control" id="file_storage_access_input" placeholder="File Storage Access" disabled>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" id="in_house_access" value="">
                                </div>
                                <input type="text" class="form-control" id="in_house_access_input" placeholder="In House Access" disabled>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="input-group mb-3">
                                <span class="input-group-text fw-bold">Purpose:</span>
                                <input type="text" class="form-control" id="purpose">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-floating mb-2">
                                    <select name="" id="preparedBy" class="form-select">
                                        <option value="">Choose...</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                    <label for="preparedBy" class="fw-bolder">Requested by:</label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-floating mb-2">
                                    <select name="" id="approvedBy" class="form-select">
                                        <option value="">Choose...</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                    <label for="approvedBy" class="fw-bolder">Approved by:</label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-floating mb-2">
                                    <select name="" id="notedBy" class="form-select">
                                        <option value="">Choose...</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                    <label for="notedBy" class="fw-bolder">Noted by:</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-success col-sm btn-save" onclick="save();"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                        <button type="button" class="btn btn-warning col-sm btn-update" onclick="update(this.value);"><i class="fa-solid fa-pen-to-square animation-trigger"></i> Update</button>
                        <button type="button" class="btn btn-danger col-sm" onclick="cancelBtn();"><i class="fa-regular fa-circle-xmark"></i> Cancel</button>
                    </div>
                    <!-- <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                </div> -->
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn card-1 text-light rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
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
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;"></div>
            </div>
        </div>
        <!-- ==================== CARD SECTION END ==================== -->
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>

    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var user_department = '<?php echo $_SESSION['dept_code']; ?>';
    $('.btn-update').hide();
    $('#generatePdf').hide();
    loadEmployee();

    function loadEmployee() {
        $.ajax({
            url: '../controller/itasset_controller/it_user_access_request_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadEmployee'
            },
            success: result => {
                $.each(result, (key, value) => {
                    $('#preparedBy').append(`<option value="${value}">${value}</option>`);
                });
            }
        });
    }

    function cancelBtn() {
        $('input[type=text]').val('');
        $('#date_needed').val('mm/dd/yy');
        $('input').prop('checked', false);
        $('#access1').prop('checked', true);
        $('#priority1').prop('checked', true);
        $('#mail_account_input').prop('disabled', true);
        $('#file_storage_access_input').prop('disabled', true);
        $('#in_house_access_input').prop('disabled', true);
        $('#control_no').find('option:first').prop('selected', 'selected');
        $('#preparedBy').find('option:first').prop('selected', 'selected');
        $('.btn-update').hide();
        $('.btn-save').show();
        $('#generatePdf').hide();
        loadNotedBy();
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid');
    }

    function preview() {
        var control_no = document.getElementById("control_no");
        var firstOption = control_no.options[0];
        if ($('#control_no').val() == firstOption.textContent.replace("UAF-", "")) {
            $('input[type=text]').val('');
            $('#date_needed').val('mm/dd/yy');
            $('.btn-update').hide();
            $('.btn-save').show();
            $('input').prop('checked', false);
            $('#access1').prop('checked', true);
            $('#priority1').prop('checked', true);

            $('#mail_account_input').prop('disabled', true);
            $('#file_storage_access_input').prop('disabled', true);
            $('#in_house_access_input').prop('disabled', true);
            $('#generatePdf').hide();
        } else {
            $.ajax({
                url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'previewControlPreview',
                    control_no: $('#control_no').val()
                },
                success: result => {
                    $('#date_needed').val(result.date_need)
                    $('#access1').val() == result.access ? $('#access1').prop('checked', true) : $('#access1').prop('checked', false);
                    $('#access2').val() == result.access ? $('#access2').prop('checked', true) : $('#access2').prop('checked', false);
                    $('#access3').val() == result.access ? $('#access3').prop('checked', true) : $('#access3').prop('checked', false);
                    $('#priority1').val() == result.priority ? $('#priority1').prop('checked', true) : $('#priority1').prop('checked', false);
                    $('#priority2').val() == result.priority ? $('#priority2').prop('checked', true) : $('#priority2').prop('checked', false);
                    result.domain_account == true ? $('#domainAccount').prop('checked', true) : $('#domainAccount').prop('checked', false);

                    result.mail_account == '' ? $('#mail_account').prop('checked', false) : $('#mail_account').prop('checked', true);
                    result.file_storage_access == '' ? $('#file_storage_access').prop('checked', false) : $('#file_storage_access').prop('checked', true);
                    result.in_house_access == '' ? $('#in_house_access').prop('checked', false) : $('#in_house_access').prop('checked', true);

                    result.mail_account == '' ? $('#mail_account_input').prop('disabled', true) : $('#mail_account_input').prop('disabled', false);
                    result.file_storage_access == '' ? $('#file_storage_access_input').prop('disabled', true) : $('#file_storage_access_input').prop('disabled', false);
                    result.in_house_access == '' ? $('#in_house_access_input').prop('disabled', true) : $('#in_house_access_input').prop('disabled', false);
                    $('#purpose').val(result.purpose);
                    $('#preparedBy').val(result.prepared_by);
                    $('#approvedBy').val(result.approved_by);
                    $('#notedBy').val(result.noted_by);
                    $('#mail_account_input').val(result.mail_account);
                    $('#file_storage_access_input').val(result.file_storage_access);
                    $('#in_house_access_input').val(result.in_house_access);
                }
            })
            $('.btn-update').show();
            $('.btn-save').hide();
            $('#generatePdf').show();
        }
    }

    function update() {
        var radioButtons = document.getElementsByName("access");
        var access;
        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                access = radioButtons[i].value;
                break;
            }
        }
        var radioButtons2 = document.getElementsByName("priority");
        var priority;
        for (var i = 0; i < radioButtons2.length; i++) {
            if (radioButtons2[i].checked) {
                priority = radioButtons2[i].value;
                break;
            }
        }
        var domainAccount = document.getElementById("domainAccount").checked;
        Swal.fire({
            title: 'Do you want to update the changes?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Update',
            denyButtonText: `Don't update`,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Update!', '', 'success')
                $.ajax({
                    url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
                    type: 'POST',
                    data: {
                        action: 'update',
                        control_no: $('#control_no').val(),
                        date_needed: $('#date_needed').val(),
                        access: access,
                        priority: priority,
                        domainAccount: domainAccount, // Pass the checkbox value, not the DOM element
                        mail_account: $('#mail_account_input').val(),
                        file_storage_access: $('#file_storage_access_input').val(),
                        in_house_access: $('#in_house_access_input').val(),
                        purpose: $('#purpose').val(),
                        preparedBy: $('#preparedBy').val(),
                        approvedBy: $('#approvedBy').val(),
                        notedBy: $('#notedBy').val()
                    },
                    success: result => {
                        cancelBtn();
                    }
                })
            } else if (result.isDenied) {
                Swal.fire('Changes are not update', '', 'info')
            }
        })
    }

    function toggleInputState(checkboxId, inputSelector) {
        var checkbox = document.getElementById(checkboxId);
        var inputField = document.querySelector(inputSelector);
        inputField.disabled = !checkbox.checked;
    }
    document.getElementById("mail_account").addEventListener("change", function() {
        toggleInputState("mail_account", ".form-control[placeholder='Mail Account']");
    });
    document.getElementById("file_storage_access").addEventListener("change", function() {
        toggleInputState("file_storage_access", ".form-control[placeholder='File Storage Access']");
    });
    document.getElementById("in_house_access").addEventListener("change", function() {
        toggleInputState("in_house_access", ".form-control[placeholder='In House Access']");
    });
    generateDefectiveRefno('tblit_control_no', 'user_access_control_no', 'control_no');
    loadNotedBy();

    function generateDefectiveRefno(inTable, inField, inObject) {
        $('#' + inObject).html('');
        $.ajax({
            url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
            type: 'POST',
            data: {
                action: 'generate_defective_refno',
                inTable: inTable,
                inField: inField
            },
            success: function(result) {
                $('#' + inObject).prepend(`<option value="${result}" class="text-primary fw-bold" selected>UAF-${result}</option>`);
            }
        });
    }

    function save() {
        if (formValidation('date_needed', 'purpose', 'preparedBy')) {
            var radioButtons = document.getElementsByName("access");
            var access;
            for (var i = 0; i < radioButtons.length; i++) {
                if (radioButtons[i].checked) {
                    access = radioButtons[i].value;
                    break;
                }
            }
            var radioButtons2 = document.getElementsByName("priority");
            var priority;
            for (var i = 0; i < radioButtons2.length; i++) {
                if (radioButtons2[i].checked) {
                    priority = radioButtons2[i].value;
                    break;
                }
            }
            var domainAccount = document.getElementById("domainAccount").checked;
            Swal.fire({
                title: 'Do you want to save the changes?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Saved!', '', 'success')
                    $.ajax({
                        url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
                        type: 'POST',
                        data: {
                            action: 'saveUserAccess',
                            control_no: $('#control_no').val(),
                            date_request: $('#date_request').val(),
                            date_needed: $('#date_needed').val(),
                            access: access,
                            priority: priority,
                            domainAccount: domainAccount, // Pass the checkbox value, not the DOM element
                            mail_account: $('#mail_account_input').val(),
                            file_storage_access: $('#file_storage_access_input').val(),
                            in_house_access: $('#in_house_access_input').val(),
                            purpose: $('#purpose').val(),
                            preparedBy: $('#preparedBy').val(),
                            approvedBy: $('#approvedBy').val(),
                            notedBy: $('#notedBy').val()
                        },
                        success: result => {
                            if (result == 'Exist') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Control Number Already Exist!',
                                });
                            } else {
                                generateDefectiveRefno('tblit_control_no', 'user_access_control_no', 'control_no');
                                loadControlNo();
                                $('input[type=text]').val('');
                                $('#date_needed').val('mm/dd/yy');
                                cancelBtn();
                            }
                        }
                    });
                    loadNotedBy();
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }
    }
    loadControlNo();

    function loadControlNo() {
        $('#control_no').html('');
        $.ajax({
            url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadControlNo'
            },
            success: result => {
                $.each(result, function(key, value) {
                    $('#control_no').append(`<option value="${value}" class="text-danger fw-bold">UAF-${value}</option>`);
                })
            }
        });
    }

    function loadNotedBy() {
        $.ajax({
            url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadDepartmentHead'
            },
            success: function(res) {
                let selected = "";
                let selectedPos = "";
                let option = `<option value="">Choose...</option>`;
                let optionPos = `<option value="">Choose...</option>`;
                let option2 = `<option value="">Choose...</option>`;
                let posCode = "";

                $.ajax({
                    url: "../controller/itasset_controller/it_user_access_request_contr.class.php",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'getPosCode',
                        user_department: user_department
                    },
                    success: function(result) {
                        posCode = result.pos_code;
                        $.each(res.deptHead, function(key, value) {
                            selected = key === "VPI" ? "selected" : "";
                            selectedPos = key === posCode ? "selected" : "";
                            option += `<option value="${value}" ${selected}>${value}</option>`;
                            optionPos += `<option value="${value}" ${selectedPos}>${value}</option>`;
                            option2 += `<option value="${value}">${value}</option>`;
                        });
                        $('#notedBy, #approvedBy').html(option2);
                        $('#notedBy').html(option);
                        // $('#approvedBy').html(optionPos);
                    },
                    error: function(xhr, status, error) {
                        console.log(error); // Print the error to the console for debugging
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log(error); // Print the error to the console for debugging
            }
        });
    }

    function generatePdf() {
        let contrl_no = $('#control_no').val();
        window.open(`it_user_access_request_pdf.php?control_no=${contrl_no}`, '_blank');
    }
    //* ~ Form validation function ~
    function formValidation(...args) {
        let date_needed = $(`#${arguments[0]}`).val();
        let purpose = $(`#${arguments[1]}`).val();
        let preparedBy = $(`#${arguments[2]}`).val();

        let validated = true;

        if (date_needed.trim() == '') {
            validate(arguments[0], 'Date Needed is required field.');
            validated = false;
        } else {
            clearValidate(arguments[0]);
        }

        if (purpose.trim() == '') {
            validate(arguments[1], 'Purpose is required field.');
            validated = false;
        } else {
            clearValidate(arguments[1]);
        }

        if (preparedBy.trim() == '') {
            validate(arguments[2], 'Prepared by is required field.');
            validated = false;
        } else {
            clearValidate(arguments[2]);
        }
        return validated;
    }
    //* ~ Validation Error ~
    function validate(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    //* ~ Validation Success ~
    function clearValidate(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }

    //* ~ Reset ~
    function clearAttributes() {
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid').val('');
        $('#employee').prop('disabled', true);
    }
</script>