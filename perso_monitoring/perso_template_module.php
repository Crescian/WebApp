<?php include './../includes/header.php';
session_start();
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
$perso = $conn->db_conn_personalization(); //* Personalization Database connection
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

function fill_process_select_box($perso)
{
    $output = '';
    $result_sql = "SELECT * FROM bpi_perso_process_list ORDER BY processid ASC";
    $result_stmt = $perso->prepare($result_sql);
    $result_stmt->execute();
    $output .= '<option value="">Choose...</option>';
    foreach ($result_stmt->fetchAll() as $row) {
        $output .= '<option value"' . $row["processid"] . '">' . $row["process_name"] . '</option>';
    }
    return $output;
    $perso = null; //* ======== Close Connection ========
}

function fill_material_select_box($perso)
{
    $output = '';
    $result_sql = "SELECT * FROM bpi_perso_material_list";
    $result_stmt = $perso->prepare($result_sql);
    $result_stmt->execute();
    $result_row = $result_stmt->fetchAll();
    $output .= '<option value="">Choose...</option>';
    foreach ($result_row as $row) {
        $output .= '<option value"' . $row["materialid"] . '">' . $row["material_name"] . '</option>';
    }
    return $output;
    $perso = null; //* ======== Close Connection ========
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
                <span class="page-title-perso">Template Module</span>
            </div>
            <div class="row mt-3 mb-4">
                <div class="col">
                    <div class="card shadow mt-4">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light" id="process_division_title">Template List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="addTemplate();"><i class="fa-solid fa-square-plus p-r-8"></i> New Template</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="templateList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Template Name</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Template Name</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Template List End ==================== -->
                </div>
                <div class="col">
                    <div class="card shadow mt-4">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <h4 class="fw-bold text-light" id="process_division_title">Customer List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="customerList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Customer List End ==================== -->
                </div>
            </div>
            <!-- =============== Add Template Modal =============== -->
            <div class="modal fade" id="addUpdateCloneTemplateModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">CREATE TEMPLATE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control fw-bold" id="template_name">
                                <div class="invalid-feedback"></div>
                                <label for="template_name" class="col-form-label fw-bold">Template Name :</label>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bold text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" class="form-control" id="process_count">
                                            <div id="process_error"></div>
                                            <div class="table-responsive">
                                                <table id="processList_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="13%" class="text-center">SEQ#</th>
                                                            <th>Name</th>
                                                            <th width="10%" class="text-center"><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add Process" onclick="addProcess();"><i class="fa-solid fa-plus"></i></button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bold text-light">Material List</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" class="form-control" id="material_count">
                                            <div id="material_error"></div>
                                            <div class="table-responsive">
                                                <table id="materialList_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="13%" class="text-center">No#</th>
                                                            <th>Name</th>
                                                            <th width="10%" class="text-center"><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add Material" onclick="addMaterial();"><i class="fa-solid fa-plus"></i></button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success col btnSaveTemplate" onclick="saveTemplate();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-success col btnUpdateTemplate" onclick="updateTemplate(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success col btnCloneTemplate" onclick="saveTemplate();"><i class="fa-solid fa-clone p-r-8"></i> Clone</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Template Modal End =============== -->
            <!-- =============== Assign Template Modal =============== -->
            <div class="modal fade" id="assignTemplateModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">ASSIGN TEMPLATE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-7 mb-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control fw-bold" id="assignedTemp_company" disabled>
                                        <label for="assignedTemp_company" class="col-form-label fw-bold">Company</label>
                                    </div>
                                </div>
                                <div class="col-sm mb-2">
                                    <div class="form-floating">
                                        <select class="form-select fw-bold" id="assignedTemp_jonumber" onclick="loadJobDescription();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <label for="assignedTemp_jonumber" class="col-form-label fw-bold">Job Order</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="assignedTemp_jobDescription" disabled>
                                <input type="hidden" class="form-control fw-bold" id="assignedTemp_orderid" disabled>
                                <div class="invalid-feedback"></div>
                                <label for="assignedTemp_jobDescription" class="col-form-label fw-bold">Description</label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm">
                                    <div class="table-responsive">
                                        <table id="assignedTemplate_table" class="table table-bordered table-striped fw-bold" width="100%">
                                            <thead class="customHeaderAdmin">
                                                <tr>
                                                    <th>Template Name</th>
                                                    <th style="text-align:center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="customHeaderAdmin">
                                                <tr>
                                                    <th>Template Name</th>
                                                    <th style="text-align:center;">Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="assigned_template_name" onclick="loadTemplateDetails();" disabled></select>
                                <div class="invalid-feedback"></div>
                                <label for="assigned_template_name" class="col-form-label fw-bold">Template Name </label>
                            </div>
                            <div class="row"><!-- =============== Process and Material List Row =============== -->
                                <div class="col-sm mb-3">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bolder text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="assign_processList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="13%" class="text-center">SEQ#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Process List End =============== -->
                                <div class="col-sm mb-3">
                                    <div class="card">
                                        <div class="card-header card-4">
                                            <h5 class="text-uppercase fw-bolder text-light">Material List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="assign_materialList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="13%" class="text-center">No#</th>
                                                            <th>Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Material List End =============== -->
                            </div><!-- =============== Process and Material List Row End =============== -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success col btnSaveAssignTemplate" onclick="saveAssignTemplate();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Assign Template</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Assign Template Modal End =============== -->
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
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include './../helper/perso_announcement.php';
include './../includes/footer.php'; ?>
<script>
    let seqNo = 0;
    var materialCount = 0;
    let array_process = [];
    let array_material = [];
    let prevIndexJonumber = '';
    let prevIndexTemplateDetails = '';
    loadTemplateListTable();
    loadCustomerListTable();

    function loadTemplateListTable() {
        var templateList_table = $('#templateList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_template_list_table'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '15%',
                orderable: false
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '25%',
                orderable: false
            }]
        });
        setInterval(function() {
            templateList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadCustomerListTable() {
        var customerList_table = $('#customerList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_customer_list_table'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false
            }]
        });
        setInterval(function() {
            customerList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAssignedTemplateTable(companyname, jonumber, orderid) {
        var assignedTemplate_table = $('#assignedTemplate_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'destroy': true,
            'serverSide': true,
            'processing': true,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_assigned_template_table',
                    companyname: companyname,
                    jonumber: jonumber,
                    orderid: orderid
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* ======= Hide tooltip every table draw =======
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false
            }]
        });
    }

    function addProcess() {
        seqNo = document.getElementById('process_count').value;
        seqNo++;
        $('#process_count').val(seqNo);
        var html = '';
        html += '<tr>';
        html += '<td><input type="text" name="sequence_number[]" class="form-control fw-bold text-center sequence_number" value="' + seqNo + '" disabled></td>';
        html += '<td><select name="process_name[]" class="form-select fw-bold process_name" id="process_name' + seqNo + '" onchange="processValidation(' + seqNo + ',this.value);"><?php echo fill_process_select_box($perso); ?></select></td>';
        html += '<td style="text-align:center;"><button type="button" name="removeProcess" class="btn btn-danger btn-sm btnRemoveProcess"><i class="fa-solid fa-minus"></i></button></td>';
        html += '</tr>';
        $('#processList_table').append(html);
    }
    //* ======= remove process =======
    $('#processList_table').on('click', '.btnRemoveProcess', function() {
        $(this).closest('tr').remove();
        reCalcSeqNoProcess();
        seqNo--;
        $('#process_count').val(seqNo);
    });

    function reCalcSeqNoProcess() {
        $('.sequence_number').each(function(i) {
            $(this).val(i + 1);
        });
    }

    function addMaterial(val) {
        materialCount = document.getElementById('material_count').value;
        materialCount++;
        $('#material_count').val(materialCount);
        var html = '';
        html += '<tr>';
        html += '<td><input type="text" name="material_number[]" class="form-control fw-bold text-center material_number" value="' + materialCount + '" disabled></td>';
        html += '<td><select name="material_name[]" class="form-select fw-bold material_name" id="material_name' + materialCount + '" onchange="materialValidation(' + materialCount + ',this.value);"><?php echo fill_material_select_box($perso); ?></select></td>';
        html += '<td style="text-align:center;"><button type="button" name="removeMaterial" class="btn btn-danger btn-sm btnRemoveMaterial"><i class="fa-solid fa-minus"></i></button></td>';
        html += '</tr>';
        $('#materialList_table').append(html);
    }
    //* ======= remove material =======
    $('#materialList_table').on('click', '.btnRemoveMaterial', function() {
        $(this).closest('tr').remove();
        reCalcSeqNoMaterial();
        materialCount--;
        $('#material_count').val(materialCount);
    });

    function reCalcSeqNoMaterial(val) {
        $('.material_number').each(function(i) {
            $(this).val(i + 1);
        });
    }

    function addTemplate() {
        $('#addUpdateCloneTemplateModal').modal('show');
        $('.btnSaveTemplate').prop('disabled', false).css('display', 'block');
        $('.btnUpdateTemplate').prop('disabled', true).css('display', 'none');
        $('.btnCloneTemplate').prop('disabled', true).css('display', 'none');
    }

    function saveTemplate() {
        if (submitValidation('saveTemplate')) {
            var template_name = document.getElementById('template_name').value;
            let arrayProcessName = [];
            let arrayMaterialName = [];

            $.ajax({
                url: 'functions/perso_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_template_name',
                    template_name: template_name
                },
                success: function(templateid) {
                    if (templateid == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Template Name Already Exist.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#template_name').focus();
                    } else {
                        //* ======== Save Process ========
                        $('.process_name').each(function() {
                            var processName = $(this).val();
                            arrayProcessName.push([processName]);
                        });
                        for (let i = 0; i < arrayProcessName.length; i++) {
                            var strData = arrayProcessName[i];
                            var process_sequence = i + 1;
                            var process_name = strData.toString();
                            $.ajax({
                                url: 'functions/perso_template_module_functions.php',
                                type: 'POST',
                                data: {
                                    action: 'save_template_process',
                                    templateid: templateid,
                                    process_name: process_name,
                                    process_sequence: process_sequence
                                }
                            });
                        }
                        //* ======== Save Material ========
                        $('.material_name').each(function() {
                            var materialName = $(this).val();
                            arrayMaterialName.push([materialName]);
                        });
                        for (let x = 0; x < arrayMaterialName.length; x++) {
                            var strData = arrayMaterialName[x];
                            var material_name = strData.toString();

                            $.ajax({
                                url: 'functions/perso_template_module_functions.php',
                                type: 'POST',
                                data: {
                                    action: 'save_template_material',
                                    templateid: templateid,
                                    material_name: material_name
                                }
                            });
                        }
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Template Successfully Save.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearValues();
                        $('#templateList_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function editTemplate(templateid) {
        $('#addUpdateCloneTemplateModal').modal('show');
        $('.btnUpdateTemplate').val(templateid);
        $('.btnUpdateTemplate').prop('disabled', false).css('display', 'block');
        $('.btnSaveTemplate').prop('disabled', true).css('display', 'none');
        $('.btnCloneTemplate').prop('disabled', true).css('display', 'none');
        //* ======== Load Template Name ========
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_template_name',
                templateid: templateid
            },
            success: function(result) {
                $('#template_name').val(result.template_name);
            }
        });
        //* ======== Load Template Process ========
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_template_process',
                templateid: templateid
            },
            success: function(result) {
                $("#processList_table").find("tr:gt(0)").remove();
                SeqNo = 0;
                setTimeout(function() {
                    $('#processList_table').append(result);
                    $('.process_name').each(function() {
                        SeqNo++;
                        $('#process_count').val(SeqNo);
                    });
                }, 200);
            }
        });
        //* ======== Load Template Material ========
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_template_material',
                templateid: templateid
            },
            success: function(result) {
                $("#materialList_table").find("tr:gt(0)").remove();
                modMaterialCount = 0;
                setTimeout(function() {
                    $('#materialList_table').append(result);
                    $('.material_name').each(function() {
                        modMaterialCount++;
                        $('#material_count').val(modMaterialCount);
                    });
                }, 200);
            }
        });
    }

    function updateTemplate(templateid) {
        if (submitValidation('updateTemplate')) {
            var template_name = document.getElementById('template_name').value;
            let arrayProcessName = [];
            let arrayMaterialName = [];

            $.ajax({
                url: 'functions/perso_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_template_name',
                    templateid: templateid,
                    template_name: template_name
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Template Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        $.ajax({
                            url: 'functions/perso_template_module_functions.php',
                            type: 'POST',
                            data: {
                                action: 'delete_process_material_update',
                                templateid: templateid
                            },
                            success: function(result) {
                                //* ======== Save Process ========
                                $('.process_name').each(function() {
                                    var processName = $(this).val();
                                    arrayProcessName.push([processName]);
                                });
                                for (let i = 0; i < arrayProcessName.length; i++) {
                                    var strData = arrayProcessName[i];
                                    var process_sequence = i + 1;
                                    var process_name = strData.toString();
                                    $.ajax({
                                        url: 'functions/perso_template_module_functions.php',
                                        type: 'POST',
                                        data: {
                                            action: 'save_template_process',
                                            templateid: templateid,
                                            process_name: process_name,
                                            process_sequence: process_sequence
                                        }
                                    });
                                }
                                //* ======== Save Material ========
                                $('.material_name').each(function() {
                                    var materialName = $(this).val();
                                    arrayMaterialName.push([materialName]);
                                });
                                for (let x = 0; x < arrayMaterialName.length; x++) {
                                    var strData = arrayMaterialName[x];
                                    var material_name = strData.toString();
                                    $.ajax({
                                        url: 'functions/perso_template_module_functions.php',
                                        type: 'POST',
                                        data: {
                                            action: 'save_template_material',
                                            templateid: templateid,
                                            material_name: material_name
                                        }
                                    });
                                }
                                Swal.fire({
                                    position: 'top',
                                    icon: 'success',
                                    title: 'Template Successfully Updated.',
                                    text: '',
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                $('#templateList_table').DataTable().ajax.reload(null, false);
                                clearAttributes();
                            }
                        });
                    }
                }
            });
        }
    }

    function cloneTemplate(templateid) {
        $('#addUpdateCloneTemplateModal').modal('show');
        $('.btnSaveTemplate').prop('disabled', true).css('display', 'none');
        $('.btnUpdateTemplate').prop('disabled', true).css('display', 'none');
        $('.btnCloneTemplate').prop('disabled', false).css('display', 'block');

        //* ======== Load Template Process ========
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_template_process',
                templateid: templateid
            },
            success: function(result) {
                $("#processList_table").find("tr:gt(0)").remove();
                SeqNo = 0;
                setTimeout(function() {
                    $('#processList_table').append(result);
                    $('.process_name').each(function() {
                        SeqNo++;
                        $('#process_count').val(SeqNo);
                    });
                }, 200);
            }
        });
        //* ======== Load Template Material ========
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_template_material',
                templateid: templateid
            },
            success: function(result) {
                $("#materialList_table").find("tr:gt(0)").remove();
                modMaterialCount = 0;
                setTimeout(function() {
                    $('#materialList_table').append(result);
                    $('.material_name').each(function() {
                        modMaterialCount++;
                        $('#material_count').val(modMaterialCount);
                    });
                }, 200);
            }
        });
    }

    function deleteTemplate(templateid) {
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'delete_template',
                templateid: templateid
            },
            success: function(result) {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Template Successfully Deleted.',
                    text: '',
                    showConfirmButton: false,
                    timer: 1000
                });
                $('#templateList_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function assignTemplate(companyname) {
        $('#assignTemplateModal').modal('show');
        $('#assignedTemp_company').val(companyname);
        loadJobOrderNumber(companyname);
        loadAssignTemplateName();
    }

    function loadJobOrderNumber(companyname) {
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_job_order_number',
                companyname: companyname
            },
            success: function(result) {
                $('#assignedTemp_jonumber').html(result);
            }
        });
    }

    function loadJobDescription() {
        var companyname = document.getElementById('assignedTemp_company').value;
        var currIndex = document.getElementById('assignedTemp_jonumber').selectedIndex;
        var currVal = document.getElementById('assignedTemp_jonumber').options;

        if (currIndex > 0) {
            if (prevIndexJonumber != currIndex) { //* ======== Toggle same Selection ========
                var jonumber = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/perso_template_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    cache: false,
                    data: {
                        action: 'load_job_description',
                        jonumber: jonumber
                    },
                    success: function(result) {
                        $('#assignedTemp_jobDescription').val(result.descriptions);
                        $('#assignedTemp_orderid').val(result.orderid);
                        $('#assigned_template_name').prop('disabled', false);
                        loadAssignedTemplateTable(companyname, jonumber, result.orderid);
                    }
                });
                prevIndexJonumber = currIndex;
            } else {
                prevIndexJonumber = '';
            }
        }
    }

    function saveAssignTemplate() {
        if (submitValidation('assignTemplate')) {
            var customer_name = document.getElementById('assignedTemp_company').value;
            var jonumber = document.getElementById('assignedTemp_jonumber').value;
            var orderid = document.getElementById('assignedTemp_orderid').value;
            var templateid = document.getElementById('assigned_template_name').value;

            $.ajax({
                url: 'functions/perso_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_assign_template',
                    customer_name: customer_name,
                    jonumber: jonumber,
                    orderid: orderid,
                    templateid: templateid
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Template Already Assigned.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        clearAttributes();
                        $('#assigned_template_name').find('option:first').prop('selected', 'selected');
                        $("#assign_processList_table").find("tr:gt(0)").remove();
                        $("#assign_materialList_table").find("tr:gt(0)").remove();
                        $('#assignedTemplate_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function removeAssignedTemplate(tempassignid) {
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'remove_assigned_template',
                tempassignid: tempassignid
            },
            success: function(result) {
                $('#assignedTemplate_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function loadAssignTemplateName() {
        $.ajax({
            url: 'functions/perso_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_assign_template_name'
            },
            success: function(result) {
                $('#assigned_template_name').html(result);
            }
        });
    }

    function loadTemplateDetails() {
        var currIndex = document.getElementById('assigned_template_name').selectedIndex;
        var currVal = document.getElementById('assigned_template_name').options;

        if (currIndex > 0) {
            if (prevIndexTemplateDetails != currIndex) { //* ======== Toggle same Selection ========
                var templateid = currVal[currIndex].value;
                //* ======== Load Template Process ========
                $.ajax({
                    url: 'functions/perso_template_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'load_assign_template_process',
                        templateid: templateid
                    },
                    success: function(result) {
                        $("#assign_processList_table").find("tr:gt(0)").remove();
                        SeqNo = 0;
                        setTimeout(function() {
                            $('#assign_processList_table').append(result);
                            $('.process_name').each(function() {
                                SeqNo++;
                                $('#process_count').val(SeqNo);
                            });
                        }, 200);
                    }
                });
                //* ======== Load Template Material ========
                $.ajax({
                    url: 'functions/perso_template_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'load_assign_template_material',
                        templateid: templateid
                    },
                    success: function(result) {
                        $("#assign_materialList_table").find("tr:gt(0)").remove();
                        modMaterialCount = 0;
                        setTimeout(function() {
                            $('#assign_materialList_table').append(result);
                            $('.material_name').each(function() {
                                modMaterialCount++;
                                $('#material_count').val(modMaterialCount);
                            });
                        }, 200);
                    }
                });
                prevIndexTemplateDetails = currIndex;
            } else {
                prevIndexTemplateDetails = '';
            }
        } else {
            $("#assign_processList_table").find("tr:gt(0)").remove();
            $("#assign_materialList_table").find("tr:gt(0)").remove();
        }
    }

    function processValidation(seqNo, value) {
        let checkIfExist = array_process.includes(value);
        array_process.push(value);
        if (checkIfExist === true) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Process Already Selected.',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            $('#process_name' + seqNo).find('option:first').prop('selected', 'selected');
        }
    }

    function materialValidation(materialCount, value) {
        let checkIfExist = array_material.includes(value);
        array_material.push(value);
        if (checkIfExist === true) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Material Already Selected.',
                text: '',
                showConfirmButton: false,
                timer: 1000
            });
            $('#material_name' + materialCount).find('option:first').prop('selected', 'selected');
        }
    }

    function clearValues() {
        $('#template_name').val('');
        $("#processList_table").find("tr:gt(0)").remove();
        $("#materialList_table").find("tr:gt(0)").remove();
        $("#assign_processList_table").find("tr:gt(0)").remove();
        $("#assign_materialList_table").find("tr:gt(0)").remove();
        $('#process_count').val(0);
        $('#material_count').val(0);
        seqNo = 0;
        materialCount = 0;
        array_process = [];
        array_material = [];
        clearAttributes();
    }

    function submitValidation(val) {
        var isValidated = true;
        if (val == 'assignTemplate') {
            var assignedTemp_jonumber = document.getElementById('assignedTemp_jonumber').value;
            var assigned_template_name = document.getElementById('assigned_template_name').value;

            if (assignedTemp_jonumber.length == 0) {
                showFieldError('assignedTemp_jonumber', 'Template Name must not be blank');
                if (isValidated) {
                    $('#assignedTemp_jonumber').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('assignedTemp_jonumber');
            }

            if (assigned_template_name.length == 0) {
                showFieldError('assigned_template_name', 'Template Name must not be blank');
                if (isValidated) {
                    $('#assigned_template_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('assigned_template_name');
            }
            return isValidated;
        } else {
            var strTemplateName = document.getElementById('template_name').value;
            var processCount = document.getElementById('process_count').value;
            var materialCount = document.getElementById('material_count').value;
            var procCount = 1;
            var matCount = 1;

            if (strTemplateName.length == 0) {
                showFieldError('template_name', 'Template Name must not be blank');
                if (isValidated) {
                    $('#template_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('template_name');
            }

            if (processCount == 0) {
                $('#process_error').removeClass('alert alert-success').addClass('alert alert-danger');
                $('#process_error').html('<i class="fa fa-exclamation-circle"></i><b> Please Input Process Name.</b>');
                $('#process_error').fadeIn(300);
                setTimeout(function() {
                    $('#process_error').fadeOut(1000);
                }, 1000);
                isValidated = false;
            }

            if (materialCount == 0) {
                $('#material_error').removeClass('alert alert-success').addClass('alert alert-danger');
                $('#material_error').html('<i class="fa fa-exclamation-circle"></i><b> Please Input Material Name.</b>');
                $('#material_error').fadeIn(300);
                setTimeout(function() {
                    $('#material_error').fadeOut(1000);
                }, 1000);
                isValidated = false;
            }

            $('.process_name').each(function() {
                var strProcessName = $(this).val();
                if (strProcessName.length == 0) {
                    $('#process_error').removeClass('alert alert-success').addClass('alert alert-danger');
                    $('#process_error').html('<i class="fa fa-exclamation-circle"></i><b> Please Input Process Name at Row: ' + procCount + '</b>');
                    $('#process_error').fadeIn(300);
                    setTimeout(function() {
                        $('#process_error').fadeOut(1000);
                    }, 1000);
                    return false;
                    isValidated = false;
                }
                procCount = procCount + 1;
            });

            $('.material_name').each(function() {
                var strMaterialName = $(this).val();
                if (strMaterialName.length == 0) {
                    $('#material_error').removeClass('alert alert-success').addClass('alert alert-danger');
                    $('#material_error').html('<i class="fa fa-exclamation-circle"></i><b> Please Input Material Name at Row: ' + matCount + '</b>');
                    $('#material_error').fadeIn(300);
                    setTimeout(function() {
                        $('#material_error').fadeOut(1000);
                    }, 1000);
                    return false;
                    isValidated = false;
                }
                matCount = matCount + 1;
            });
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
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>