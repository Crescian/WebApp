<?php include './../includes/header.php';
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$data_base64 = base64_encode($sqlstring);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
$json_response = curl_exec($curl);
//* ====== Close Connection ======
curl_close($curl);
// * ======== Prepare Array ========
$data_result = json_decode($json_response, true);
foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #b811da;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <div class="row">
                <span class="page-title-ims">Document Control</span>
            </div>
            <!-- content section -->
            <div class="row mt-5 mb-4"> <!-- =========== User List Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row mt-3">
                                <div id="document_menu_tree"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== User List Section End =========== -->

            <!-- =============== Add Job Entry Modal =============== -->
            <div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-10">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="doc_entry_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col-sm-7">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="doc_department" id="doc_department"></select>
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_department" class="col-form-label fw-bold">Department:</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="doc_date">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_date" class="col-form-label fw-bold">Date:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="doc_level" id="doc_level">
                                            <option value="">Choose...</option>
                                            <option value="L1">Manual</option>
                                            <option value="L2">Procedure</option>
                                            <option value="L3">Work Instruction</option>
                                            <option value="L4">Records/Forms</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_level" class="col-form-label fw-bold">Level of Document:</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="doc_req_type" id="doc_req_type">
                                            <option value="">Choose...</option>
                                            <option value="A">Doc. Registration</option>
                                            <option value="B">Change Modification</option>
                                            <option value="C">Doc. Obsoletion</option>
                                            <option value="D">Doc. Requisition</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_req_type" class="col-form-label fw-bold">Request Type:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" name="doc_title" id="doc_title">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_title" class="col-form-label fw-bold">Document Title:</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center text-uppercase" name="doc_number" id="doc_number">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_number" class="col-form-label fw-bold">Document No.:</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" name="doc_revision" id="doc_revision">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_revision" class="col-form-label fw-bold">Revision:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm" id="doc_type_section">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" name="doc_type" id="doc_type">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_type" class="col-form-label fw-bold">Document Type:</label>
                                    </div>
                                </div>
                                <div class="col-sm" id="doc_mother_procedure_section">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" name="doc_mother_procedure" id="doc_mother_procedure">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_mother_procedure" class="col-form-label fw-bold">Mother Procedure:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="doc_owner_originator" id="doc_owner_originator"></select>
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_owner_originator" class="col-form-label fw-bold">Document Owner/Originator:</label>
                                    </div>
                                </div>
                                <div class="col-sm" id="doc_owner_user_section">
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold text-center" name="doc_owner_user" id="doc_owner_user">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_owner_user" class="col-form-label fw-bold">Owner/User:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <textarea class="form-control fw-bold" id="doc_reason_remarks" style="height: 100px; resize:none;"></textarea>
                                <div class="invalid-feedback"></div>
                                <label for="doc_reason_remarks" class="fw-bold">Reason for Request/Remarks:</label>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="file" class="form-control fw-bold" id="doc_pdf_file">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_pdf_file" class="col-form-label fw-bold">PDF File:</label>
                                    </div>
                                </div>
                                <div class="col-sm" id="doc_owner_user_section">
                                    <div class="form-floating mb-2">
                                        <input type="file" class="form-control fw-bold" id="doc_word_file">
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_word_file" class="col-form-label fw-bold">Word File:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="doc_prepared_by" id="doc_prepared_by"></select>
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_prepared_by" class="col-form-label fw-bold">Prepared by:</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" name="doc_approved_by" id="doc_approved_by"></select>
                                        <div class="invalid-feedback"></div>
                                        <label for="doc_approved_by" class="col-form-label fw-bold">Approved by:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 btnUpdateDocEntry" onclick="updateDocEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col-sm-12 btnSaveDocEntry" onclick="saveDocEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col-sm-12" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
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
            <div class="card card-10 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IMS XPRESS</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/input_validation.php';
include './../helper/select_values.php'; ?>
<script>
    let dateToday = new Date().toISOString().slice(0, 10);
    var fullname = '<?php echo $_SESSION['fullname']; ?>';
    var emp_dept_code = '<?php echo $_SESSION['dept_code']; ?>';
    let doc_pdf_file_value;
    let doc_word_file_value;

    loadSytemMenuTree();

    function addNewDocument(menuid) {

        window.open(`document_registration_word.php?id=${menuid}`, '_blank');


        // $('#addDocumentModal').modal('show');
        // $('#doc_entry_title').html('DOCUMENT ENTRY');
        // $('.btnUpdateDocEntry').prop('disabled', true).css('display', 'none');
        // $('.btnSaveDocEntry').prop('disabled', false).css('display', 'block').val(menuid);

        // $('#doc_type_section').css('display', 'none');
        // $('#doc_mother_procedure_section').css('display', 'none');
        // $('#doc_owner_user_section').css('display', 'none');
        // $('#doc_type').css('display', 'none');
        // $('#doc_mother_procedure').css('display', 'none');
        // $('#doc_owner_user').css('display', 'none');

        // $('#doc_date').val(dateToday);
        // loadDepartment('doc_department');
        // setTimeout(function() {
        //     $('#doc_department').val(emp_dept_code).change();
        // }, 200);
        // setTimeout(function() {
        //     $('#doc_prepared_by').val(fullname);
        // }, 400);
    }

    function saveDocEntry(menuid) {
        if (inputValidation('doc_department', 'doc_date', 'doc_level', 'doc_req_type', 'doc_title', 'doc_number', 'doc_revision', 'doc_reason_remarks', 'doc_prepared_by', 'doc_approved_by', 'doc_pdf_file', 'doc_word_file')) {
            $.ajax({
                url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_doc_registration',
                    doc_department: $('#doc_department').val(),
                    date_requested: $('#doc_date').val(),
                    doc_level: $('#doc_level').val(),
                    doc_req_type: $('#doc_req_type').val(),
                    doc_title: $('#doc_title').val(),
                    doc_number: $('#doc_number').val(),
                    doc_revision: $('#doc_revision').val(),
                    doc_reason_remarks: $('#doc_reason_remarks').val(),
                    doc_prepared_by: $('#doc_prepared_by').val(),
                    doc_approved_by: $('#doc_approved_by').val(),
                    doc_type: $('#doc_type').val(),
                    doc_mother_procedure: $('#doc_mother_procedure').val(),
                    doc_owner_originator: $('#doc_owner_originator').val(),
                    doc_owner_user: $('#doc_owner_user').val(),
                    menuid: menuid,
                    doc_pdf_file_value: doc_pdf_file_value,
                    doc_word_file_value: doc_word_file_value
                },
                success: result => {
                    $('#addDocumentModal').modal('hide');
                    loadSytemMenuTree();
                    clearValues();
                }
            });
        }
    }

    function loadDepartment(inObject) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_department'
            },
            success: result => {
                loadSelectValues(inObject, result)
            }
        });
    }

    $('#doc_department').change(function() {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_department_head',
                dept_code: $(this).val()
            },
            success: result => {
                let deptHeadCode = {
                    'ISD': 'CIS',
                    'SMD': 'VPS',
                    'ITD': 'VPI',
                    'PHD': 'VPP',
                    'HRD': 'SHO',
                    'PUD': 'PUM',
                    'PSD': 'VPS',
                    'PRD': 'EAM',
                    'FID': 'FIM',
                    'FMD': 'VPP',
                    'MSD': 'VPQ',
                    'RDD': 'VPQ'
                };
                $('#doc_approved_by').empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#doc_approved_by option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#doc_approved_by').append(`<option value="${optionValue}"> ${optionText}</option>`);
                    }
                    if (result != '') {
                        $.each(result, (key, value) => {
                            selected = key == deptHeadCode[$('#doc_department').val()] ? "selected" : "";
                            var optionExists = ($(`#doc_approved_by option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#doc_approved_by').append(`<option value="${key}" ${selected}>${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });

        $.ajax({
            url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_employee',
                dept_code: $(this).val()
            },
            success: result => {
                loadSelectValues('doc_prepared_by', result)
                loadSelectValues('doc_owner_originator', result)
            }
        });
    });

    $('#doc_level').change(function() {
        if ($(this).val() == 'L4') {
            $('#doc_type_section').css('display', 'block');
            $('#doc_mother_procedure_section').css('display', 'block');
            $('#doc_owner_user_section').css('display', 'block');
            $('#doc_type').css('display', 'block').val('');
            $('#doc_mother_procedure').css('display', 'block').val('');
            $('#doc_owner_user').css('display', 'block').val('');
        } else {
            $('#doc_type_section').css('display', 'none');
            $('#doc_mother_procedure_section').css('display', 'none');
            $('#doc_owner_user_section').css('display', 'none');
            $('#doc_type').css('display', 'none');
            $('#doc_mother_procedure').css('display', 'none');
            $('#doc_owner_user').css('display', 'none');
        }
    });

    $('#doc_pdf_file').change(function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            $.ajax({
                url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
                type: 'POST',
                data: {
                    action: 'load_base64',
                    data: e.target.result
                },
                success: function(result) {
                    doc_pdf_file_value = result;
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('#doc_word_file').change(function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            console.log(e.target.result);
            $.ajax({
                url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
                type: 'POST',
                data: {
                    action: 'load_base64',
                    data: e.target.result
                },
                success: function(result) {
                    doc_word_file_value = result;
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });

    function loadSytemMenuTree() {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_document_control_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_document_menu_tree'
            },
            success: function(result) {
                console.log(result);
                $('#document_menu_tree').jstree('destroy');
                if (result != 'empty') {
                    $('#document_menu_tree').jstree({
                        'core': {
                            'data': result,
                            'check_callback': true,
                            'themes': {
                                'stripes': true
                            }
                        },
                        'plugins': ["contextmenu", "types", "state"],
                        'types': {
                            'default': {
                                'icon': "fa-solid fa-folder-closed"
                            },
                            'file': {
                                'icon': "fa-solid fa-file-lines"
                            },
                            'f-open': {
                                'icon': 'fa-regular fa-folder-open'
                            },
                            'f-closed': {
                                'icon': "fa-solid fa-folder-closed"
                            }
                        },
                        'contextmenu': {
                            items: function($node) {
                                return {
                                    'new_doc': {
                                        'label': "<span>New Document</span>",
                                        'icon': "fa-solid fa-file-circle-plus",
                                        'action': function(obj) {
                                            addNewDocument($node.id);
                                        }
                                    }
                                }
                            }
                        }
                    }).bind('ready.jstree', function(e, data) {
                        $('#document_menu_tree').jstree('open_all')
                    }).bind("show_contextmenu.jstree", function(e, data) {
                        if (data.node.parent == "#") {
                            $.vakata.context.hide()
                        } else {

                            // var currentDirection = data.node.original.dir
                            // if (currentDirection) {
                            //     var hideCheck = currentDirection == "asc" ? "desc" : "asc"
                            //     $('.jstree-contextmenu li.' + hideCheck + ' i.dir-selected').hide()
                            // } else {
                            //     $('.jstree-contextmenu li i.dir-selected').hide()
                            // }
                        }
                    }).on('open_node.jstree', function(event, data) {
                        data.instance.set_type(data.node, 'f-open');
                    }).on('close_node.jstree', function(event, data) {
                        data.instance.set_type(data.node, 'f-closed');
                    });
                }
            }
        });
    }
    $('#doc_revision').keyup(function() {
        if (event.which >= 37 && event.which <= 40) { //* =========== skip for arrow keys ===========
            event.preventDefault();
        }
        $(this).val(function(index, value) {
            return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });

    function clearValues() {
        // $('select').find('option:first').prop('selected', 'selected');
        $('input').val('');
        $('textarea').val('');
        clearAttributes();
    }

    function clearAttributes() {
        $('textarea').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
        $('input').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>