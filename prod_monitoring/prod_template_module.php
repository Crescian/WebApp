<?php include './../includes/header.php';
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
$prod = $conn->db_conn_manufacturing(); //* Manufacturing Database connection

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

function fill_process_select_box($prod)
{
    $output = '';
    $result_sql = "SELECT * FROM prod_process_name ORDER BY processid ASC";
    $result_stmt = $prod->prepare($result_sql);
    $result_stmt->execute();
    $output .= '<option value="">Choose...</option>';
    foreach ($result_stmt->fetchAll() as $row) {
        $output .= '<option value="' . $row["processid"] . '">' . $row["process_name"] . '</option>';
    }
    return $output;
    $prod = null; //* ======== Close Connection ========
}


?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #291af5;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-production">Template Module</span>
            </div>
            <div class="row mt-3 mb-4">
                <div class="col">
                    <div class="card shadow mt-4">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light" id="process_division_title">Template List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="addTemplate();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New Template</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="templateList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th>Template Name</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
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
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <h4 class="fw-bold text-light" id="process_division_title">Customer List</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="customerList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
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
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="template_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control fw-bold" id="template_name">
                                <div class="invalid-feedback"></div>
                                <label for="template_name" class="col-form-label fw-bold">Template Name :</label>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-4">
                                    <div class="card">
                                        <div class="card-header card-8">
                                            <h5 class="text-uppercase fw-bold text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" class="form-control" id="process_count">
                                            <div id="process_error"></div>
                                            <div class="table-responsive">
                                                <table id="processList_table" class="table table-bordered table-striped table-hover fw-bold" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="10%" class="text-center">SEQ#</th>
                                                            <th>Name</th>
                                                            <th width="30%" class="text-center">Section</th>
                                                            <th width="20%">Card Side</th>
                                                            <th width="8%" class="text-center"><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add Process" onclick="addProcess();"><i class="fa-solid fa-plus fa-beat" style="--fa-animation-duration: 2s;"></i></button></th>
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
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnSaveTemplate" onclick="saveTemplate();"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Save</button>
                            <button type="button" class="btn btn-success col btnUpdateTemplate" onclick="updateTemplate(this.value);"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Update</button>
                            <button type="button" class="btn btn-success col btnCloneTemplate" onclick="saveTemplate();"><i class="fa-solid fa-clone fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Clone</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark fa-shake p-r-8" style="--fa-animation-duration: 2s;"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Template Modal End =============== -->
            <!-- =============== Assign Template Modal =============== -->
            <div class="modal fade" id="assignTemplateModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light">ASSIGN TEMPLATE</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-7 mb-2">
                                    <div class="form-floating">
                                        <input type="text" class="form-control fw-bold" id="assignTemp_company" disabled>
                                        <label for="assignTemp_company" class="col-form-label fw-bold">Company</label>
                                    </div>
                                </div>
                                <div class="col-sm mb-2">
                                    <div class="form-floating">
                                        <select class="form-select fw-bold" id="assignTemp_jonumber" onclick="loadJobDescription();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="assignTemp_jonumber" class="col-form-label fw-bold">Job Order</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="assignTemp_jobDescription" disabled>
                                <input type="hidden" class="form-control fw-bold" id="assignTemp_orderid" disabled>
                                <div class="invalid-feedback"></div>
                                <label for="assignTemp_jobDescription" class="col-form-label fw-bold">Description</label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm">
                                    <div class="table-responsive">
                                        <table id="assignTemplate_table" class="table table-bordered table-striped fw-bold" width="100%">
                                            <thead class="customHeaderProd">
                                                <tr>
                                                    <th>Template Name</th>
                                                    <th style="text-align:center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="customHeaderProd">
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
                                <select class="form-select fw-bold" id="assign_template_name" onclick="loadTemplateDetails();" disabled>
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="assign_template_name" class="col-form-label fw-bold">Template Name </label>
                            </div>
                            <div class="row"><!-- =============== Process List Row =============== -->
                                <div class="col-sm">
                                    <div class="card">
                                        <div class="card-header card-8">
                                            <h5 class="text-uppercase fw-bolder text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="assign_processList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="13%" class="text-center">SEQ#</th>
                                                            <th>Name</th>
                                                            <th width="40%" class="text-center">Section</th>
                                                            <th width="15%">Card Side</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Process List End =============== -->
                            </div><!-- =============== Process List Row End =============== -->
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnSaveAssignTemplate" onclick="saveAssignTemplate();"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Assign Template</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark fa-shake p-r-8" style="--fa-animation-duration: 2s;"></i> Close</button>
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
            <div class="card card-8 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">MANUFACTURING</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    let seqNo = 0;
    let array_process = [];
    let arrayProcessName = [];
    let arrayCardSide = [];
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
                url: 'functions/prod_template_module_functions.php',
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
                orderable: false,
                render: function(data, type, row, meta) {
                    let templateStatus;
                    if (data > 0) {
                        templateStatus = '<span class="badge bg-success col-sm-12 fs-6">In use</span>';
                    } else {
                        templateStatus = '<span class="badge bg-dark col-sm-12 fs-6">Unused</span>';
                    }
                    return templateStatus
                }
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '25%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let btnStatus;
                    if (data[0] > 0) {
                        btnStatus = `<button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-pen-to-square"></i></button>
                        <button type="button" class="btn btn-warning col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Clone Template" onclick="cloneTemplate(${data[1]});"><i class="fa-solid fa-clone fa-flip" style="--fa-animation-duration: 2s;"></i></button> 
                        <button type="button" class="btn btn-secondary col-sm" disabled><i class="fa-solid fa-trash-can"></i></button>`;
                    } else {
                        btnStatus = `<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Template" onclick="editTemplate(${data[1]});"><i class="fa-solid fa-pen-to-square fa-beat" style="--fa-animation-duration: 2s;"></i></button>
                        <button type="button" class="btn btn-warning col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Clone Template" onclick="cloneTemplate(${data[1]});"><i class="fa-solid fa-clone fa-flip" style="--fa-animation-duration: 2s;"></i></button>
                        <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Template" onclick="deleteTemplate(${data[1]});"><i class="fa-solid fa-trash-can fa-shake" style="--fa-animation-duration: 2s;"></i></button>`;
                    }
                    return btnStatus
                }
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
                url: 'functions/prod_template_module_functions.php',
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
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-primary col-sm-12 btnAssignTemplate" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign Template" onclick="assignTemplate('${data}');"><i class="fa-solid fa-plus fa-beat" style="--fa-animation-duration: 2s;"></i></button>`
                }
            }]
        });
        setInterval(function() {
            customerList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addProcess() {
        seqNo = document.getElementById('process_count').value;
        seqNo++;
        $('#process_count').val(seqNo);
        var html = '';
        html += '<tr>';
        html += '<td><input type="text" name="sequence_number[]" class="form-control fw-bold text-center sequence_number" value="' + seqNo + '" disabled></td>';
        html += '<td><select name="process_name[]" class="form-select fw-bold process_name" id="process_name' + seqNo + '" onchange="processValidation(' + seqNo + ',this.value);"><?php echo fill_process_select_box($prod); ?></select></td>';
        html += '<td><input type="text" name="process_section[]" class="form-control fw-bold text-center process_section' + seqNo + '" disabled></td>';
        html += '<td><select name="card_side[]" class="form-select fw-bold card_side" id="card_side' + seqNo + '"><option value="0">Choose...</option><option value="Front">Front</option><option value="Back">Back</option><option value="Front/Back">Front/Back</option><option value="N/A">N/A</option></select></td>';
        html += '<td style="text-align:center;"><button type="button" name="removeProcess" class="btn btn-danger btn-sm btnRemoveProcess"><i class="fa-solid fa-minus fa-shake" style="--fa-animation-duration: 2s;"></i></button></td>';
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

    function addTemplate() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#addUpdateCloneTemplateModal').modal('show');
        $('#template_modal_title').html('Create Template');
        $('.btnSaveTemplate').prop('disabled', false).css('display', 'block');
        $('.btnUpdateTemplate').prop('disabled', true).css('display', 'none');
        $('.btnCloneTemplate').prop('disabled', true).css('display', 'none');
    }

    function saveTemplate() {
        if (submitValidation('saveTemplate')) {
            var template_name = document.getElementById('template_name').value;

            $.ajax({
                url: 'functions/prod_template_module_functions.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_template_name',
                    template_name: template_name
                },
                success: function(result) {
                    if (result.templateid == 'existing') {
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
                        $('.card_side').each(function() {
                            var cardSide = $(this).val();
                            arrayCardSide.push([cardSide]);
                        });
                        for (let i = 0; i < arrayProcessName.length; i++) {
                            var strData = arrayProcessName[i];
                            var process_id = strData.toString();

                            var strCardSide = arrayCardSide[i];
                            var card_side = strCardSide.toString();

                            var process_seq = i + 1;
                            $.ajax({
                                url: 'functions/prod_template_module_functions.php',
                                type: 'POST',
                                data: {
                                    action: 'save_template_process',
                                    template_id: result.templateid,
                                    process_id: process_id,
                                    process_seq: process_seq,
                                    card_side: card_side
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
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#addUpdateCloneTemplateModal').modal('show');
        $('#template_modal_title').html('Edit Template');
        $('.btnSaveTemplate').prop('disabled', true).css('display', 'none');
        $('.btnUpdateTemplate').prop('disabled', false).css('display', 'block').val(templateid);
        $('.btnCloneTemplate').prop('disabled', true).css('display', 'none');

        //* ======== Load Template Name ========
        $.ajax({
            url: 'functions/prod_template_module_functions.php',
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
            url: 'functions/prod_template_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_template_process',
                templateid: templateid
            },
            success: function(result) {
                $("#processList_table").find("tr:gt(0)").remove();
                setTimeout(function() {
                    let tableRow = '';
                    $.each(result, (key, value) => {
                        tableRow += '<tr>';
                        tableRow += '<td><input type="text" name="sequence_number[]" class="form-control fw-bold text-center sequence_number" value="' + value.process_seq + '" disabled></td>';
                        tableRow += '<td><select name="process_name[]" class="form-select fw-bold process_name" id="process_name' + value.process_seq + '" onchange="processValidation(' + value.process_seq + ',this.value);"><?php echo fill_process_select_box($prod); ?></select></td>';
                        tableRow += '<td><input type="text" name="process_section[]" class="form-control fw-bold text-center process_section' + value.process_seq + '" value="' + value.section_name + '" disabled></td>';
                        tableRow += '<td><select name="card_side[]" class="form-select fw-bold card_side" id="card_side' + value.process_seq + '">';
                        tableRow += '<option value="0">Choose...</option>';
                        tableRow += '<option value="Front">Front</option>';
                        tableRow += '<option value="Back">Back</option>';
                        tableRow += '<option value="Front/Back">Front/Back</option>';
                        tableRow += '<option value="N/A">N/A</option>';
                        tableRow += '</select>';
                        tableRow += '</td>';
                        tableRow += '<td style="text-align:center;"><button type="button" name="removeProcess" class="btn btn-danger btn-sm btnRemoveProcess"><i class="fa-solid fa-minus"></i></button></td>';
                        tableRow += '</tr>';
                        setTimeout(function() {
                            $('#process_name' + value.process_seq).val(value.process_id);
                            $('#card_side' + value.process_seq).val(value.card_side);
                        }, 200);
                    });
                    SeqNo = 0;
                    $('#processList_table').append(tableRow);
                    $('.process_name').each(function() {
                        SeqNo++;
                        $('#process_count').val(SeqNo);
                    });
                }, 200);
            }
        });
    }

    function updateTemplate(templateid) {
        if (submitValidation('saveTemplate')) {
            var template_name = document.getElementById('template_name').value;

            $.ajax({
                url: 'functions/prod_template_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_template_name',
                    template_name: template_name,
                    templateid: templateid
                },
                success: function(result) {
                    if (result.templateName == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Template Name Already Exist.',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#template_name').focus();
                    } else {
                        $.ajax({
                            url: 'functions/prod_template_module_functions.php',
                            type: 'POST',
                            data: {
                                action: 'delete_process_update',
                                template_id: templateid
                            },
                            success: function(result) {
                                //* ======== Save Process ========
                                $('.process_name').each(function() {
                                    var processName = $(this).val();
                                    arrayProcessName.push([processName]);
                                });
                                $('.card_side').each(function() {
                                    var cardSide = $(this).val();
                                    arrayCardSide.push([cardSide]);
                                });

                                for (let i = 0; i < arrayProcessName.length; i++) {
                                    var strData = arrayProcessName[i];
                                    var process_id = strData.toString();

                                    var strCardSide = arrayCardSide[i];
                                    var card_side = strCardSide.toString();

                                    var process_seq = i + 1;

                                    $.ajax({
                                        url: 'functions/prod_template_module_functions.php',
                                        type: 'POST',
                                        data: {
                                            action: 'save_template_process',
                                            template_id: templateid,
                                            process_id: process_id,
                                            process_seq: process_seq,
                                            card_side: card_side
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
                                clearValues();
                                $('#addUpdateCloneTemplateModal').modal('hide');
                                $('#templateList_table').DataTable().ajax.reload(null, false);
                            }
                        });
                    }
                }
            });
        }
    }

    function cloneTemplate(templateid) {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#addUpdateCloneTemplateModal').modal('show');
        $('#template_modal_title').html('Clone Template');
        $('.btnSaveTemplate').prop('disabled', true).css('display', 'none');
        $('.btnUpdateTemplate').prop('disabled', true).css('display', 'none');
        $('.btnCloneTemplate').prop('disabled', false).css('display', 'block').val(templateid);

        //* ======== Load Template Process ========
        $.ajax({
            url: 'functions/prod_template_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_template_process',
                templateid: templateid
            },
            success: function(result) {
                $("#processList_table").find("tr:gt(0)").remove();
                setTimeout(function() {
                    let tableRow = '';
                    $.each(result, (key, value) => {
                        tableRow += '<tr>';
                        tableRow += '<td><input type="text" name="sequence_number[]" class="form-control fw-bold text-center sequence_number" value="' + value.process_seq + '" disabled></td>';
                        tableRow += '<td><select name="process_name[]" class="form-select fw-bold process_name" id="process_name' + value.process_seq + '" onchange="processValidation(' + value.process_seq + ',this.value);"><?php echo fill_process_select_box($prod); ?></select></td>';
                        tableRow += '<td><input type="text" name="process_section[]" class="form-control fw-bold text-center process_section' + value.process_seq + '" value="' + value.section_name + '" disabled></td>';
                        tableRow += '<td><select name="card_side[]" class="form-select fw-bold card_side" id="card_side' + value.process_seq + '">';
                        tableRow += '<option value="0">Choose...</option>';
                        tableRow += '<option value="Front">Front</option>';
                        tableRow += '<option value="Back">Back</option>';
                        tableRow += '<option value="Front/Back">Front/Back</option>';
                        tableRow += '<option value="N/A">N/A</option>';
                        tableRow += '</select>';
                        tableRow += '</td>';
                        tableRow += '<td style="text-align:center;"><button type="button" name="removeProcess" class="btn btn-danger btn-sm btnRemoveProcess"><i class="fa-solid fa-minus"></i></button></td>';
                        tableRow += '</tr>';
                        setTimeout(function() {
                            $('#process_name' + value.process_seq).val(value.process_id);
                            $('#card_side' + value.process_seq).val(value.card_side);
                        }, 200);
                    });
                    SeqNo = 0;
                    $('#processList_table').append(tableRow);
                    $('.process_name').each(function() {
                        SeqNo++;
                        $('#process_count').val(SeqNo);
                    });
                }, 200);
            }
        });
    }

    function deleteTemplate(templateid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'functions/prod_template_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'delete_template',
                        templateid: templateid
                    },
                    success: function(result) {
                        $('#templateList_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Template deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function assignTemplate(companyname) {
        $('#assignTemplateModal').modal('show');
        $('#assignTemp_company').val(companyname);
        loadJobOrderNumber(companyname);
        loadSelectValueWithId('prod_template_name', 'templateid', 'template_name', 'assign_template_name', 'production');
    }


    function loadJobOrderNumber(companyname) {
        $.ajax({
            url: 'functions/prod_template_module_functions.php',
            type: 'POST',
            data: {
                action: 'load_job_order_number',
                companyname: companyname
            },
            success: function(result) {
                $('#assignTemp_jonumber').html(result);
            }
        });
    }

    function loadJobDescription() {
        var companyname = document.getElementById('assignTemp_company').value;
        var currIndex = document.getElementById('assignTemp_jonumber').selectedIndex;
        var currVal = document.getElementById('assignTemp_jonumber').options;

        if (currIndex > 0) {
            if (prevIndexJonumber != currIndex) { //* ======== Toggle same Selection ========
                var jonumber = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/prod_template_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    cache: false,
                    data: {
                        action: 'load_job_description',
                        jonumber: jonumber
                    },
                    success: function(result) {
                        $('#assignTemp_jobDescription').val(result.descriptions);
                        $('#assignTemp_orderid').val(result.orderid);
                        $('#assign_template_name').prop('disabled', false);
                        loadAssignedTemplateTable(companyname, jonumber, result.orderid);
                    }
                });
                prevIndexJonumber = currIndex;
            } else {
                prevIndexJonumber = '';
            }
        }
    }

    function loadAssignedTemplateTable(companyname, jonumber, orderid) {
        var assignTemplate_table = $('#assignTemplate_table').DataTable({
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
                url: 'functions/prod_template_module_functions.php',
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
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-danger col-sm-12" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Remove Template" onclick="removeAssignedTemplate(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
    }

    function loadTemplateDetails() {
        var currIndex = document.getElementById('assign_template_name').selectedIndex;
        var currVal = document.getElementById('assign_template_name').options;

        if (currIndex > 0) {
            if (prevIndexTemplateDetails != currIndex) { //* ======== Toggle same Selection ========
                var templateid = currVal[currIndex].value;
                //* ======== Load Template Process ========
                $.ajax({
                    url: 'functions/prod_template_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_assign_template_process',
                        templateid: templateid
                    },
                    success: function(result) {
                        $("#assign_processList_table").find("tr:gt(0)").remove();

                        setTimeout(function() {
                            let tableRow = '';
                            $.each(result, (key, value) => {
                                tableRow += '<tr>';
                                tableRow += '<td><input type="text" class="form-control fw-bold text-center"  value="' + value.process_seq + '" disabled></td>';
                                tableRow += '<td><input type="text" class="form-control fw-bold" value="' + value.process_name + '" disabled></td>';
                                tableRow += '<td><input type="text" class="form-control fw-bold" value="' + value.section_name + '" disabled></td>';
                                tableRow += '<td><input type="text" class="form-control fw-bold" value="' + value.card_side + '" disabled></td>';
                                tableRow += '</tr>';
                            });
                            $('#assign_processList_table').append(tableRow);
                        }, 200);
                    }
                });
                prevIndexTemplateDetails = currIndex;
            } else {
                prevIndexTemplateDetails = '';
            }
        } else {
            $("#assign_processList_table").find("tr:gt(0)").remove();
        }

    }

    function saveAssignTemplate() {
        if (submitValidation('assignTemplate')) {
            var customer_name = document.getElementById('assignTemp_company').value;
            var jonumber = document.getElementById('assignTemp_jonumber').value;
            var orderid = document.getElementById('assignTemp_orderid').value;
            var templateid = document.getElementById('assign_template_name').value;

            $.ajax({
                url: 'functions/prod_template_module_functions.php',
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
                        $('#assign_template_name').find('option:first').prop('selected', 'selected');
                        $("#assign_processList_table").find("tr:gt(0)").remove();
                        $('#assignTemplate_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
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
        } else {
            $.ajax({
                url: 'functions/prod_template_module_functions.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_process_section',
                    processid: value
                },
                success: function(result) {
                    $('.process_section' + seqNo).val(result.section_name);
                }
            });
        }
    }

    function submitValidation(val) {
        var isValidated = true;
        if (val == 'assignTemplate') {
            var assignTemp_jonumber = document.getElementById('assignTemp_jonumber').value;
            var assign_template_name = document.getElementById('assign_template_name').value;

            if (assignTemp_jonumber.length == 0) {
                showFieldError('assignTemp_jonumber', 'JO Number must not be blank');
                if (isValidated) {
                    $('#assignTemp_jonumber').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('assignTemp_jonumber');
            }

            if (assign_template_name.length == 0) {
                showFieldError('assign_template_name', 'Template Name must not be blank');
                if (isValidated) {
                    $('#assign_template_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('assign_template_name');
            }
            return isValidated;
        } else {

            var template_name = document.getElementById('template_name').value;
            var processCount = document.getElementById('process_count').value;
            var procCount = 1;

            if (template_name.length == 0) {
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

    function clearValues() {
        $('#template_name').val('');
        $("#processList_table").find("tr:gt(0)").remove();
        $("#assign_processList_table").find("tr:gt(0)").remove();
        $('#assignTemp_company').val('');
        $('#assignTemp_jonumber').val('');
        $('#assignTemp_jobDescription').val('');
        $('#assign_template_name').val('');
        $('#assign_template_name').prop('disabled', true);
        $('#process_count').val(0);
        seqNo = 0;
        array_process = [];
        arrayProcessName = [];
        arrayCardSide = [];
        prevIndexJonumber = '';
        prevIndexTemplateDetails = '';
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>