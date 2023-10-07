<?php include './../includes/header.php';
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
            <div class="row mb-4">
                <span class="page-title-ims">System Module</span>
            </div>
            <!-- content section -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow active" onclick="loadTableNavigation('System Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-danger fw-bold">System Modules</span>
                                    <div class="fs-2 fw-bold" id="on_hold_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-file-shield fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow" onclick="loadTableNavigation('ISO Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-warning fw-bold">ISO Modules</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-solid fa-file-contract fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Document Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-primary fw-bold">Document Module</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-file-lines fa-flip"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row mb-2">
                        <h3 id="active_request_title"></h3>
                    </div>
                    <div id="system_module_section"> <!-- =========== System List Section =========== -->
                        <div class="row"> <!-- =========== System List Section =========== -->
                            <div class="col col-sm col-md col-lg col-xl-10 mx-auto">
                                <div class="card shadow mb-4">
                                    <div class="card-header card-10 py-3">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h4 class="fw-bold text-light">System Menu List</h4>
                                            </div>
                                            <div class="col-sm">
                                                <div class="row">
                                                    <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addSystemMenuModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New System Menu Entry</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="sys_mod_menu_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="card-10 text-light">
                                                    <tr>
                                                        <th>Menu Title</th>
                                                        <th>Menu Parent</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="card-10 text-light">
                                                    <tr>
                                                        <th>Menu Title</th>
                                                        <th>Menu Parent</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- =========== System List Section End =========== -->
                    <div id="iso_module_section">
                        <div class="row"> <!-- =========== ISO List Section =========== -->
                            <div class="col col-sm col-md col-lg col-xl-10 mx-auto">
                                <div class="card shadow mb-4">
                                    <div class="card-header card-10 py-3">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h4 class="fw-bold text-light">ISO Menu List</h4>
                                            </div>
                                            <div class="col-sm">
                                                <div class="row">
                                                    <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addIsoMenuModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New ISO Menu Entry</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="iso_mod_menu_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="card-10 text-light">
                                                    <tr>
                                                        <th>Menu Title</th>
                                                        <th>Menu Parent</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="card-10 text-light">
                                                    <tr>
                                                        <th>Menu Title</th>
                                                        <th>Menu Parent</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- =========== ISO List Section End =========== -->
                    <div id="document_module_section">
                        <div class="row"> <!-- =========== Document Module Section =========== -->
                            <div class="col col-sm col-md col-lg col-xl-10 mx-auto">
                                <div class="card shadow mb-4">
                                    <div class="card-header card-10 py-3">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h4 class="fw-bold text-light">Document List</h4>
                                            </div>
                                            <div class="col-sm">
                                                <div class="row">
                                                    <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addDocumentModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New Document Entry</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="document_menu_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="card-10 text-light">
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Link</th>
                                                        <th>Document Parent</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="card-10 text-light">
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Link</th>
                                                        <th>Document Parent</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== System Menu Modal =============== -->
            <div class="modal fade" id="addUpdateSysMenuModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-10">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="sys_menu_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_sys_parent_menu" onclick="switchParentMenu(this.checked,'switch_sys_sub_parent_menu','sys_parent_menu');" checked>
                                        <label class="form-check-label fw-bold" for="switch_sys_parent_menu">Menu Parent</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_sys_sub_parent_menu" onclick="switchSubParentMenu(this.checked,'switch_sys_parent_menu','sys_parent_menu');">
                                        <label class="form-check-label fw-bold" for="switch_sys_sub_parent_menu">Menu Sub-Parent</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="sys_parent_menu" disabled></select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold" for="sys_parent_menu">System Parent Menu</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="sys_menu_title">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold" for="sys_menu_title">System Menu Title</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnUpdateSysMenuEntry" onclick="updateSysMenuEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveSysMenuEntry" onclick="saveSysMenuEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== ISO Menu Modal =============== -->
            <div class="modal fade" id="addUpdateIsoMenuModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-10">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="iso_menu_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_iso_parent_menu" onclick="switchParentMenu(this.checked,'switch_iso_sub_parent_menu','iso_parent_menu');" checked>
                                        <label class="form-check-label fw-bold" for="switch_iso_parent_menu">Menu Parent</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_iso_sub_parent_menu" onclick="switchSubParentMenu(this.checked,'switch_iso_parent_menu','iso_parent_menu');">
                                        <label class="form-check-label fw-bold" for="switch_iso_sub_parent_menu">Menu Sub-Parent</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="iso_parent_menu" disabled></select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold" for="iso_parent_menu">ISO Parent Menu</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="iso_menu_title">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold" for="iso_menu_title">ISO Menu Title</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnUpdateIsoMenuEntry" onclick="updateIsoMenuEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveIsoMenuEntry" onclick="saveIsoMenuEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Document Menu Modal =============== -->
            <div class="modal fade" id="addUpdateMenuModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-10">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="doc_menu_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_doc_parent_menu" onclick="switchParentMenu(this.checked,'switch_doc_sub_parent_menu','doc_parent_menu');" checked>
                                        <label class="form-check-label fw-bold" for="switch_doc_parent_menu">Document Parent</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch_doc_sub_parent_menu" onclick="switchSubParentMenu(this.checked,'switch_doc_parent_menu','doc_parent_menu');">
                                        <label class="form-check-label fw-bold" for="switch_doc_sub_parent_menu">Document Sub-Parent</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="doc_parent_menu" disabled></select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold" for="doc_parent_menu">Document Parent Menu</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="doc_menu_title">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold" for="doc_menu_title">Document Menu Title</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnUpdateDocMenuEntry" onclick="updateDocMenuEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveDocMenuEntry" onclick="saveDocMenuEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
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
    loadTableNavigation('System Module')
    loadSysModMenuTable('sys_mod_menu_table', 'load_sys_mod_menu_table', 'sys_mod_menu');
    loadSysModMenuTable('iso_mod_menu_table', 'load_iso_mod_menu_table', 'iso_mod_menu');
    loadDocumentMenuTable();

    function loadTableNavigation(category) {
        switch (category) {
            case 'ISO Module':
                $('#active_request_title').html('ISO Module');
                $('#system_module_section').hide();
                $('#iso_module_section').show();
                $('#document_module_section').hide();
                break;
            case 'Document Module':
                $('#active_request_title').html('Document Module');
                $('#system_module_section').hide();
                $('#iso_module_section').hide();
                $('#document_module_section').show();
                break;
            default:
                $('#active_request_title').html('System Module');
                $('#system_module_section').show();
                $('#iso_module_section').hide();
                $('#document_module_section').hide();
                break;
        }
    }

    function loadSysModMenuTable(inTable, inAction, inCategory) {
        inTable = $('#' + inTable).DataTable({
            'lengthMenu': [
                [5, 10, 50, 100],
                [5, 10, 50, 100],
            ],
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: inAction
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                orderable: false
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    let tableAction;
                    switch (inCategory) {
                        case 'iso_mod_menu':
                            tableAction = `<button type="button" class="btn col-sm-6 btn-primary btnEditIsoModMenu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Menu" onclick="editIsoModMenuEntry(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                                <button type="button" class="btn col-sm-6 btn-danger btnDeleteIsoModMenu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete Menu" onclick="deleteIsoModMenuEntry(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`;
                            break;
                        default:
                            tableAction = `<button type="button" class="btn col-sm-6 btn-primary btnEditSysModMenu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Menu" onclick="editSysModMenuEntry(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                                <button type="button" class="btn col-sm-6 btn-danger btnDeleteSysModMenu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete Menu" onclick="deleteSysModMenuEntry(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`;
                            break;
                    }
                    return tableAction;
                }
            }]
        });
        inTable.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 200);
        });
        setInterval(function() {
            inTable.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadDocumentMenuTable() {
        var document_menu_table = $('#document_menu_table').DataTable({
            'lengthMenu': [
                [5, 10, 50, 100],
                [5, 10, 50, 100],
            ],
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_document_menu_table'
                }
            },
            'columnDefs': [{
                    targets: [0, 1, 2],
                    className: 'dt-body-middle-left'
                },
                {
                    targets: 3,
                    className: 'dt-nowrap-center',
                    width: '20%',
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `<button type="button" class="btn col-sm-6 btn-primary btnEditDocMenuEntry" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit Menu" onclick="editDocMenuEntry(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                                <button type="button" class="btn col-sm-6 btn-danger btnDeleteDocMenuEntry" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete Menu" onclick="deleteDocMenuEntry(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                    }
                }
            ]
        });
        document_menu_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 200);
        });
        setInterval(function() {
            document_menu_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }
    //* ================================ SYSTEM MODULE MENU SECTION ================================
    function addSystemMenuModal() {
        $('#addUpdateSysMenuModal').modal('show');
        $('#sys_mod_category_title').html('ADD MENU NAME');
        $('.btnUpdateSysMenuEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveSysMenuEntry').prop('disabled', false).css('display', 'block');
        loadSysModSelectValues('sys_parent_menu');
    }

    function saveSysMenuEntry() {
        if (inputValidation('sys_menu_title')) {
            saveMenuModule('sys_mod_menu_table', 'save_sys_menu_title', 'sys_parent_menu', 'sys_menu_title');
        }
    }

    function editSysModMenuEntry(sysmodmenuid) {
        $('#addUpdateSysMenuModal').modal('show');
        $('#sys_mod_category_title').html('ADD MENU NAME');
        $('.btnUpdateSysMenuEntry').prop('disabled', false).css('display', 'block').val(sysmodmenuid);
        $('.btnSaveSysMenuEntry').prop('disabled', true).css('display', 'none');
        loadSysModSelectValues('sys_parent_menu');
        loadMenuModules('load_sys_menu', 'switch_sys_parent_menu', 'switch_sys_sub_parent_menu', 'sys_parent_menu', 'sys_menu_title', sysmodmenuid)
    }

    function updateSysMenuEntry(sysmodmenuid) {
        if (inputValidation('sys_menu_title')) {
            updateMenuModule('sys_mod_menu_table', 'update_sys_menu_title', 'sys_parent_menu', 'sys_menu_title', sysmodmenuid);
        }
    }

    function deleteSysModMenuEntry(sysmodmenuid) {
        deleteMenuModules('sys_mod_menu_table', 'delete_sys_menu_title', sysmodmenuid);
    }
    //* ================================ ISO MODULE MENU SECTION ================================
    function addIsoMenuModal() {
        $('#addUpdateIsoMenuModal').modal('show');
        $('#iso_mod_category_title').html('ADD MENU NAME');
        $('.btnUpdateIsoMenuEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveIsoMenuEntry').prop('disabled', false).css('display', 'block');
        loadSysModSelectValues('iso_parent_menu');
    }

    function saveIsoMenuEntry() {
        if (inputValidation('iso_menu_title')) {
            saveMenuModule('iso_mod_menu_table', 'save_iso_menu_title', 'iso_parent_menu', 'iso_menu_title');
        }
    }

    function editIsoModMenuEntry(isomodmenuid) {
        $('#addUpdateIsoMenuModal').modal('show');
        $('#iso_mod_category_title').html('ADD MENU NAME');
        $('.btnUpdateIsoMenuEntry').prop('disabled', false).css('display', 'block').val(isomodmenuid);
        $('.btnSaveIsoMenuEntry').prop('disabled', true).css('display', 'none');
        loadSysModSelectValues('iso_parent_menu');
        loadMenuModules('load_iso_menu', 'switch_iso_parent_menu', 'switch_iso_sub_parent_menu', 'iso_parent_menu', 'iso_menu_title', isomodmenuid)
    }

    function updateIsoMenuEntry(isomodmenuid) {
        if (inputValidation('iso_menu_title')) {
            updateMenuModule('iso_mod_menu_table', 'update_iso_menu_title', 'iso_parent_menu', 'iso_menu_title', isomodmenuid);
        }
    }

    function deleteIsoModMenuEntry(isomodmenuid) {
        deleteMenuModules('iso_mod_menu_table', 'delete_iso_menu_title', isomodmenuid);
    }
    //* ================================ DOCUMENT MODULE SECTION ================================
    function addDocumentModal() {
        $('#addUpdateMenuModal').modal('show');
        $('.doc_menu_modal_title').html('ADD DOCUMENT MENU');
        $('.btnUpdateDocMenuEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveDocMenuEntry').prop('disabled', false).css('display', 'block');
        loadSysModSelectValues('doc_parent_menu');
    }

    function saveDocMenuEntry() {
        if (inputValidation('doc_menu_title')) {
            saveMenuModule('document_menu_table', 'save_document_title', 'doc_parent_menu', 'doc_menu_title');
        }
    }

    function editDocMenuEntry(docmenuid) {
        $('#addUpdateMenuModal').modal('show');
        $('.doc_menu_modal_title').html('ADD DOCUMENT MENU');
        $('.btnUpdateDocMenuEntry').prop('disabled', false).css('display', 'block').val(docmenuid);
        $('.btnSaveDocMenuEntry').prop('disabled', true).css('display', 'none');
        loadSysModSelectValues('doc_parent_menu');
        loadMenuModules('load_doc_menu', 'switch_doc_parent_menu', 'switch_doc_sub_parent_menu', 'doc_parent_menu', 'doc_menu_title', docmenuid)
    }

    function updateDocMenuEntry(docmenuid) {
        if (inputValidation('doc_menu_title')) {
            updateMenuModule('document_menu_table', 'update_doc_menu_title', 'doc_parent_menu', 'doc_menu_title', docmenuid);
        }
    }

    function deleteDocMenuEntry(docmenuid) {
        deleteMenuModules('document_menu_table', 'delete_doc_menu_title', docmenuid);
    }
    //* ================================ COMMON FUNCTION SECTION ================================
    function switchParentMenu(switchVal, inSwitch, inField) {
        if (switchVal == false) {
            $('#' + inSwitch).prop('checked', true);
            $('#' + inField).prop('disabled', false);
        } else {
            $('#' + inSwitch).prop('checked', false);
            $('#' + inField).prop('disabled', true);
        }
    }

    function switchSubParentMenu(switchVal, inSwitch, inField) {
        if (switchVal == true) {
            $('#' + inSwitch).prop('checked', false);
            $('#' + inField).prop('disabled', false);
        } else {
            $('#' + inSwitch).prop('checked', true);
            $('#' + inField).prop('disabled', true);
        }
        loadSysModSelectValues(inField);
    }

    function loadSysModSelectValues(inObject) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_values',
                category: inObject
            },
            success: result => {
                loadSelectValues(inObject, result)
            }
        });
    }

    function saveMenuModule(inTable, inAction, parent_id, menu_title) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: inAction,
                parent_menu_id: $('#' + parent_id).val(),
                menu_title: $('#' + menu_title).val()
            },
            success: result => {
                if (result == 'doc_sub_exist') {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'Sub-Parent Menu already exist on Parent Menu!.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#' + doc_menu_title).focus();
                } else if (result == 'doc_exist') {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'Menu Already Exist!.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#' + doc_menu_title).focus();
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Save Successfully.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#' + inTable).DataTable().ajax.reload(null, false);
                    $('#' + menu_title).val('');
                }
                clearAttributes();
            }
        });
    }

    function loadMenuModules(inAction, inParentObj, inSubParentObj, inField1, inField2, dataId) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: inAction,
                dataId: dataId
            },
            success: result => {
                setTimeout(function() {
                    $('#' + inField1).val(result.parentMenu);
                    $('#' + inField2).val(result.menuTitle);
                }, 200);
                if (result.parentMenu != '') {
                    $('#' + inSubParentObj).prop('checked', true);
                    $('#' + inField1).prop('disabled', false);
                    $('#' + inParentObj).prop('checked', false);
                }
            }
        });
    }

    function updateMenuModule(inTable, inAction, parent_id, menu_title, dataId) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: inAction,
                parent_menu_id: $('#' + parent_id).val(),
                menu_title: $('#' + menu_title).val(),
                dataId: dataId
            },
            success: result => {
                if (result == 'doc_sub_exist') {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'Sub-Parent Menu already exist on Parent Menu!.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#' + menu_title).focus();
                } else if (result == 'doc_exist') {
                    Swal.fire({
                        position: 'top',
                        icon: 'info',
                        title: 'Menu Already Exist!.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#' + menu_title).focus();
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Updated Successfully.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#' + inTable).DataTable().ajax.reload(null, false);
                }
                clearAttributes();
            }
        });
    }

    function deleteMenuModules(inTable, inAction, dataId) {
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
                    url: '../controller/ims_xpress_controller/ims_system_modules_contr.class.php',
                    type: 'POST',
                    data: {
                        action: inAction,
                        dataId: dataId
                    },
                    success: result => {
                        if (result == 1) {
                            $('#' + inTable).DataTable().ajax.reload(null, false);
                            Swal.fire(
                                'Deleted!',
                                'Data deleted.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Warning!',
                                'Cannot Delete Data. Currently In Use or Still Assigned',
                                'info'
                            )
                        }
                    }
                });
            }
        });
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('#switch_doc_parent_menu').prop('checked', true);
        $('#switch_sys_parent_menu').prop('checked', true);
        $('#switch_iso_parent_menu').prop('checked', true);

        $('#switch_doc_sub_parent_menu').prop('checked', false);
        $('#switch_sys_sub_parent_menu').prop('checked', false);
        $('#switch_iso_sub_parent_menu').prop('checked', false);
        $('#doc_parent_menu').prop('disabled', true);
        $('#sys_parent_menu').prop('disabled', true);
        $('#iso_parent_menu').prop('disabled', true);
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>