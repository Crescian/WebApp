<?php include './../includes/header.php';
session_start();
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWebLive->prepare($queryCheckApp);
$stmtCheckApp->execute();
while ($chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC)) {
    $chkAppId = $chkAppIdRow['app_id'];
}

if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
}
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    /* =========== Change Scrollbar Style - Justine 01122023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #6b6bf0;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-perso">Process Entry</span>
            </div>
            <div class="row mt-5">
                <div class="col col-sm col-md col-lg col-xl-6 mx-auto">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Process List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addProcessModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Process</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="processList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Process Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Process Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Process List Section End =============== -->
            <!-- =============== Add Process Modal =============== -->
            <div class="modal fade" id="addProcessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">NEW PROCESS</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="process_name">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Process Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="process_division" onclick="loadProcessSection('addProcess');"></select>
                                <div class="invalid-feedback">
                                </div>
                                <label for="process_division" class="fw-bolder">Process Division</label>
                            </div>
                            <div class="form-floating mb-3 d-none" id="process_category_div">
                                <select class="form-select fw-bold" id="process_category">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="process_category" class="fw-bolder">Process Category</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select fw-bold" id="process_section">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="process_section" class="fw-bold">Process Section</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveProcess" onclick="saveProcess();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div> <!-- =============== Add Process Modal End =============== -->
            <!-- =============== Update Process Modal =============== -->
            <div class="modal fade" id="updateProcessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Update Process</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="col-form-label fw-bolder">Process Name</label>
                                    <input type="text" class="form-control fw-bold" id="mod_process_name" placeholder="Process Name">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="mod_process_division" class="col-form-label fw-bolder">Process Division</label>
                                    <select class="form-control fw-bold" id="mod_process_division" onclick="loadProcessSection('updateProcess');"></select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-3 d-none" id="mod_process_category_div">
                                <div class="col">
                                    <label for="mod_process_category" class="col-form-label fw-bolder">Process Category</label>
                                    <select class="form-control fw-bold" id="mod_process_category">
                                        <option value="">Choose...</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="mod_process_section" class="col-form-label fw-bolder">Process Section</label>
                                    <select class="form-control fw-bold" id="mod_process_section">
                                        <option value="">Choose...</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnUpdateProcess" onclick="updateProcess(this)"><i class="fa-solid fa-file-pen p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Update Process Modal End =============== -->
            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-4 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">PERSONALIZATION</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>
<?php
include './../helper/perso_announcement.php';
include './../includes/footer.php';
?>
<script>
    var prevIndexDivision = '';
    loadProcessListTable();

    function loadProcessListTable() {
        var processList_table = $('#processList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_process_entry_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_process_list_table'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* =========== Hide tooltip every table draw ===========
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditProcess" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Process" onclick="modifyProcess(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteProcess" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Process" onclick="deleteProcess(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        setInterval(function() {
            processList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addProcessModal() {
        $('#addProcessModal').modal('show');
        loadProcessDivision('addProcess');
    }

    function saveProcess() {
        if (submitValidation('addProcess')) {
            var strProcessName = document.getElementById('process_name').value;
            var strProcessDivision = document.getElementById('process_division').value;
            var strProcessSection = document.getElementById('process_section').value;
            var strProcessCategory = document.getElementById('process_category').value;

            $.ajax({
                url: 'functions/perso_process_entry_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_process',
                    strProcessName: strProcessName,
                    strProcessDivision: strProcessDivision,
                    strProcessSection: strProcessSection,
                    strProcessCategory: strProcessCategory
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Process Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#process_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Process Name Successfully Saved.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#processList_table').DataTable().ajax.reload(null, false);
                        clearValues();
                    }
                }
            });
        }
    }

    function modifyProcess(processid) {
        $.ajax({
            url: 'functions/perso_process_entry_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'load_process_information',
                processid: processid
            },
            success: function(result) {
                $('#mod_process_name').val(result.processname);
                loadProcessDivision('updateProcess');
                setTimeout(function() {
                    $('#mod_process_division').val(result.process_division);
                    loadProcessSection('updateProcess');
                }, 500);
                setTimeout(function() {
                    $('#mod_process_category').val(result.process_category);
                    $('#mod_process_section').val(result.process_section);
                }, 1000);
                $('.btnUpdateProcess').val(processid);
                $('#updateProcessModal').modal('show');
            }
        });
    }

    function updateProcess(val) {
        if (submitValidation('updateProcess')) {
            var processid = val.value;
            var strModProcessName = document.getElementById('mod_process_name').value;
            var strModProcessDivision = document.getElementById('mod_process_division').value;
            var strModProcessSection = document.getElementById('mod_process_section').value;
            var strModProcessCategory = document.getElementById('mod_process_category').value;

            $.ajax({
                url: 'functions/perso_process_entry_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_process',
                    processid: processid,
                    strModProcessName: strModProcessName,
                    strModProcessDivision: strModProcessDivision,
                    strModProcessSection: strModProcessSection,
                    strModProcessCategory: strModProcessCategory
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Process Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#mod_process_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Process Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#processList_table').DataTable().ajax.reload(null, false);
                        $('#updateProcessModal').modal('hide');
                        clearAttributes();
                    }
                }
            });
        }
    }

    function deleteProcess(processid) {
        $.ajax({
            url: 'functions/perso_process_entry_module_functions.php',
            type: 'POST',
            data: {
                action: 'delete_process_name',
                processid: processid
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Process Successfully Deleted.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#processList_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function loadProcessDivision(val) {
        $.ajax({
            url: 'functions/perso_process_entry_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_process_section'
            },
            success: function(result) {
                if (val == 'addProcess') {
                    $('#process_division').html(result);
                } else {
                    $('#mod_process_division').html(result);
                }
            }
        });
    }

    function loadProcessSection(val) {
        if (val == 'addProcess') {
            var currIndex = document.getElementById('process_division').selectedIndex;
            var currVal = document.getElementById('process_division').options;
        } else {
            var currIndex = document.getElementById('mod_process_division').selectedIndex;
            var currVal = document.getElementById('mod_process_division').options;
        }
        if (currIndex > 0) {
            if (prevIndexDivision != currIndex) { //* ======= Toggle same Selection =======
                var processDivision = currVal[currIndex].value;
                if (val == 'addProcess') {
                    $("#process_section").empty();
                    $('#process_category').empty();
                } else {
                    $("#mod_process_section").empty();
                    $('#mod_process_category').empty();
                }
                setTimeout(function() {
                    switch (processDivision) {
                        case 'Printing Division':
                            var optVal = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "Inkjet Section",
                                val: "Inkjet Section",
                            }, {
                                text: "Persomaster/Persoline Section",
                                val: "Persomaster/Persoline Section",
                            }]

                            // if (val == 'addProcess') {
                            //     $('#process_category_div').addClass('d-none');
                            // } else {
                            //     $('#mod_process_category_div').addClass('d-none');
                            // }
                            break
                        case 'Embossing Division':
                            var optVal = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "Embossing/Datacard Section",
                                val: "Embossing/Datacard Section",
                            }]

                            if (val == 'addProcess') {
                                $('#process_category_div').removeClass('d-none');
                            } else {
                                $('#mod_process_category_div').removeClass('d-none');
                            }
                            var optValEmbossing = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "For Print",
                                val: "For Print",
                            }, {
                                text: "For Packing",
                                val: "For Packing",
                            }]
                            for (var z = 0; z <= optValEmbossing.length - 1; z++) {
                                optionTextemboss = optValEmbossing[z].text;
                                optionValueemboss = optValEmbossing[z].val;
                                if (val == 'addProcess') {
                                    var optionExists = ($(`#process_category option[value="${optionValueemboss}"]`).length > 0);
                                } else {
                                    var optionExists = ($(`#mod_process_category option[value="${optionValueemboss}"]`).length > 0);
                                }
                                if (!optionExists) {
                                    if (val == 'addProcess') {
                                        $('#process_category').append(`<option value="${optionValueemboss}">${optionTextemboss}</option>`);
                                    } else {
                                        $('#mod_process_category').append(`<option value="${optionValueemboss}">${optionTextemboss}</option>`);
                                    }
                                }
                            }
                            // if (val == 'addProcess') {
                            //     $('#process_category_div').addClass('d-none');
                            // } else {
                            //     $('#mod_process_category_div').addClass('d-none');
                            // }
                            break;
                        case 'Packaging Division':
                            if (val == 'addProcess') {
                                $('#process_category_div').removeClass('d-none');
                            } else {
                                $('#mod_process_category_div').removeClass('d-none');
                            }

                            var optVal = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "Packaging Section",
                                val: "Packaging Section",
                            }, {
                                text: "QA/Non HSA Kitting Section",
                                val: "QA/Non HSA Kitting Section",
                            }, {
                                text: "HSA Kitting Section",
                                val: "HSA Kitting Section",
                            }]

                            var optValPackaging = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "For Packing",
                                val: "For Packing",
                            }, {
                                text: "For Kitting",
                                val: "For Kitting",
                            }]
                            for (var y = 0; y <= optValPackaging.length - 1; y++) {
                                optionTextpack = optValPackaging[y].text;
                                optionValuepack = optValPackaging[y].val;
                                if (val == 'addProcess') {
                                    var optionExists = ($(`#process_category option[value="${optionValuepack}"]`).length > 0);
                                } else {
                                    var optionExists = ($(`#mod_process_category option[value="${optionValuepack}"]`).length > 0);
                                }
                                if (!optionExists) {
                                    if (val == 'addProcess') {
                                        $('#process_category').append(`<option value="${optionValuepack}">${optionTextpack}</option>`);
                                    } else {
                                        $('#mod_process_category').append(`<option value="${optionValuepack}">${optionTextpack}</option>`);
                                    }
                                }
                            }
                            break;
                        case 'Vault Division':
                            var optVal = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "Vault Section",
                                val: "Vault Section",
                            }, {
                                text: "Packaging-Vault Section",
                                val: "Packaging-Vault Section",
                            }]
                            // if (val == 'addProcess') {
                            //     $('#process_category_div').addClass('d-none');
                            // } else {
                            //     $('#mod_process_category_div').addClass('d-none');
                            // }
                            break;
                        case 'Dispatching Division':
                            var optVal = [{
                                text: "Choose...",
                                val: "",
                            }, {
                                text: "Dispatching Section",
                                val: "Dispatching Section",
                            }]
                            // if (val == 'addProcess') {
                            //     $('#process_category_div').addClass('d-none');
                            // } else {
                            //     $('#mod_process_category_div').addClass('d-none');
                            // }
                            break;
                        default:
                            var optVal = [{
                                text: "Choose...",
                                val: "",
                            }]
                            // if (val == 'addProcess') {
                            //     $('#process_category_div').addClass('d-none');
                            // } else {
                            //     $('#mod_process_category_div').addClass('d-none');
                            // }
                            break;
                    }

                    for (var x = 0; x <= optVal.length - 1; x++) {
                        optionText = optVal[x].text;
                        optionValue = optVal[x].val;
                        if (val == 'addProcess') {
                            var optionExists = ($(`#process_section option[value="${optionValue}"]`).length > 0);
                        } else {
                            var optionExists = ($(`#mod_process_section option[value="${optionValue}"]`).length > 0);
                        }
                        if (!optionExists) {
                            if (val == 'addProcess') {
                                $('#process_section').append(`<option value="${optionValue}">${optionText}</option>`);
                            } else {
                                $('#mod_process_section').append(`<option value="${optionValue}">${optionText}</option>`);
                            }
                        }
                    }
                }, 100);
                prevIndexDivision = currIndex;
            } else {
                prevIndexDivision = '';
            }
        }
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('#process_category_div').addClass('d-none');
        $('#mod_process_category_div').addClass('d-none');
        clearAttributes();
    }

    function submitValidation(val) {
        var isValidated = true;
        if (val == 'addProcess') {
            var strProcessName = document.getElementById('process_name').value;
            var strProcessDivision = document.getElementById('process_division').value;
            var strProcessCategory = document.getElementById('process_category').value;
            var strProcessSection = document.getElementById('process_section').value;

            if (strProcessName.length == 0) {
                showFieldError('process_name', 'Process Name must not be blank');
                if (isValidated) {
                    $('#process_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('process_name');
            }

            if (strProcessDivision.length == 0) {
                showFieldError('process_division', 'Process Division must not be blank');
                if (isValidated) {
                    $('#process_division').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('process_division');
            }

            if (strProcessDivision == 'Packaging Division' || strProcessDivision == 'Embossing Division') {
                if (strProcessCategory.length == 0) {
                    showFieldError('process_category', 'Process Category must not be blank');
                    if (isValidated) {
                        $('#process_category').focus();
                    }
                    isValidated = false;
                } else {
                    clearFieldError('process_category');
                }
            }

            if (strProcessSection.length == 0) {
                showFieldError('process_section', 'Process Section must not be blank');
                if (isValidated) {
                    $('#process_section').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('process_section');
            }
            return isValidated;
        } else {
            var strModProcessName = document.getElementById('mod_process_name').value;
            var strModProcessDivision = document.getElementById('mod_process_division').value;
            var strModProcessCategory = document.getElementById('mod_process_category').value;
            var strModProcessSection = document.getElementById('mod_process_section').value;

            if (strModProcessName.length == 0) {
                showFieldError('mod_process_name', 'Process Name must not be blank');
                if (isValidated) {
                    $('#mod_process_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('mod_process_name');
            }

            if (strModProcessDivision.length == 0) {
                showFieldError('mod_process_division', 'Process Division must not be blank');
                if (isValidated) {
                    $('#mod_process_division').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('mod_process_division');
            }

            if (strModProcessDivision == 'Packaging Division' || strModProcessDivision == 'Embossing Division') {
                if (strModProcessCategory.length == 0) {
                    showFieldError('mod_process_category', 'Process Category must not be blank');
                    if (isValidated) {
                        $('#mod_process_category').focus();
                    }
                    isValidated = false;
                } else {
                    clearFieldError('mod_process_category');
                }
            }
            if (strModProcessSection.length == 0) {
                showFieldError('mod_process_section', 'Process Section must not be blank');
                if (isValidated) {
                    $('#mod_process_section').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('mod_process_section');
            }
            return isValidated;
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
        $('input[type=text]').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>