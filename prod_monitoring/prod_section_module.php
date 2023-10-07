<?php include './../includes/header.php';
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
                <span class="page-title-production">Section Module</span>
            </div>
            <div class="row mt-5">
                <div class="col-sm-6">
                    <div class="card shadow">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light align-content-center">Section List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addSectionModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Add Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sectionList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th>Section Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
                                        <tr>
                                            <th>Section Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card shadow">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light align-content-center">Assign Machine List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="assignSectionModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Assign Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="assignSectionList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th>Section Name</th>
                                            <th>Machine Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
                                        <tr>
                                            <th>Section Name</th>
                                            <th>Machine Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Section List Section End =============== -->
            <!-- =============== Section Assign Employee =============== -->
            <div class="row mt-5">
                <div class="col-sm-6">
                    <div class="card shadow">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h4 class="fw-bold text-light align-content-center">Assign Employee</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addSectionEmployeeModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Assign Job Title</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sectionAssignEmployee_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th>Section Name</th>
                                            <th>Job Title</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
                                        <tr>
                                            <th>Section Name</th>
                                            <th>Job Title</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Section Assign Employee End =============== -->

            <!-- =============== AddUpdate Section Modal =============== -->
            <div class="modal fade" id="addUpdateSectionModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="section_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="section_name">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Section Name</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnUpdateSection" onclick="updateSection(this.value);"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveSection" onclick="saveSection();"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark fa-shake p-r-8" style="--fa-animation-duration: 2s;"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div> <!-- =============== AddUpdate Section Modal End =============== -->
            <!-- =============== AddUpdate Assign Modal =============== -->
            <div class="modal fade" id="addUpdateAssignModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="assign_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="section_name_obj">
                                    <option value="">Choose</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder">Section Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="machine_name_obj">
                                    <option value="">Choose</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder">Machine Name</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnUpdateAssign" onclick="updateAssign(this.value);"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveAssign" onclick="saveAssign();"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark fa-shake p-r-8" style="--fa-animation-duration: 2s;"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div> <!-- =============== AddUpdate Assign Modal End =============== -->
            <!-- =============== Assign Section Employee Modal =============== -->
            <div class="modal fade" id="assignSectionEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="assign_employee_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <select class="form-select fw-bold" id="assign_section_name">
                                    <option value="">Choose</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder fs-18" for="assign_section_name">Section Name</label>
                            </div>
                            <div class="form-floating mt-2">
                                <select class="form-select fw-bold" id="section_job_title">
                                    <option value="">Choose</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bolder fs-18" for="section_job_title">Job Title</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 col-sm-11 mx-auto mb-2">
                            <button type="button" class="btn btn-success col-sm btnUpdateAssignEmployee" onclick="updateAssignSectionEmployee(this.value);"><i class="fa-regular fa-floppy-disk fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Update</button>
                            <button type="button" class="btn btn-success col-sm btnSaveAssignEmployee" onclick="saveAssignSectionEmployee();"><i class="fa-solid fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Assign Section Employee Modal End =============== -->

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
<?php include './../includes/footer.php';
include './../helper/input_validation.php'; ?>
<script>
    loadSectionListTable();
    loadAssignListTable();
    loadAssignEmployeeTable();

    function loadSectionListTable() {
        var sectionList_table = $('#sectionList_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_section_list_table'
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
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditSection" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Section" onclick="modifySection(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteSection" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Section" onclick="deleteSection(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        sectionList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            sectionList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAssignListTable() {
        var assignSectionList_table = $('#assignSectionList_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_assign_list_table'
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
                targets: [0, 1],
                className: 'dt-body-middle-left'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditAssign" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Assign" onclick="modifyAssign(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteAssign" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Assign" onclick="deleteAssign(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        assignSectionList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            assignSectionList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAssignEmployeeTable() {
        var sectionAssignEmployee_table = $('#sectionAssignEmployee_table').DataTable({
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_assign_employee_table'
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
                targets: [0, 1],
                className: 'dt-body-middle-left'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditAssignEmployee" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Assign" onclick="modifyAssignEmployee(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteAssignEmployee" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Assign" onclick="deleteAssignEmployee(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        sectionAssignEmployee_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            sectionAssignEmployee_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addSectionModal() {
        $('#addUpdateSectionModal').modal('show');
        $('.btnUpdateSection').prop('disabled', true).css('display', 'none');
        $('.btnSaveSection').prop('disabled', false).css('display', 'block');
        $('#section_modal_title').html('SECTION ENTRY');
    }

    function saveSection() {
        if (inputValidation('section_name')) {
            var section_name = document.getElementById('section_name').value;
            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_section_name',
                    section_name: section_name
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Section Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#section_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Section Successfully Save.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#sectionList_table').DataTable().ajax.reload(null, false);
                        $('#addUpdateSectionModal').modal('hide');
                        clearValues();
                    }
                }
            });
        }
    }

    function modifySection(sectionid) {
        $('.btnSaveSection').prop('disabled', true).css('display', 'none');
        $('.btnUpdateSection').prop('disabled', false).css('display', 'block').val(sectionid);
        $('#addUpdateSectionModal').modal('show');
        $('#section_modal_title').html('SECTION UPDATE');

        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_section_info',
                sectionid: sectionid
            },
            success: function(result) {
                $('#section_name').val(result.section_name);
            }
        });
    }

    function updateSection(sectionid) {
        if (inputValidation('section_name')) {
            var section_name = document.getElementById('section_name').value;

            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_section',
                    section_name: section_name,
                    sectionid: sectionid
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Section Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#section_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Section Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#sectionList_table').DataTable().ajax.reload(null, false);
                        $('#addUpdateSectionModal').modal('hide');
                        clearAttributes();
                    }
                }
            });
        }
    }

    function deleteSection(sectionid) {
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
                    url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'remove_section',
                        sectionid: sectionid
                    },
                    success: function(result) {
                        if (result.result == 'success') {
                            $('#sectionList_table').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                'Deleted!',
                                'Section deleted.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Warning!',
                                'Cannot Delete Section Name. Currently In Use or Still Assigned',
                                'info'
                            )
                        }
                    }
                });
            }
        })
    }

    function assignSectionModal() {
        $('#addUpdateAssignModal').modal('show');
        $('.btnUpdateAssign').prop('disabled', true).css('display', 'none');
        $('.btnSaveAssign').prop('disabled', false).css('display', 'block');
        $('#assign_modal_title').html('ASSIGN ENTRY');
        loadSelectWithId('assignSection');
        loadSelectWithId('assignMachine');
    }

    function saveAssign() {
        if (inputValidation('section_name_obj', 'machine_name_obj')) {
            var section_name_obj = document.getElementById('section_name_obj').value;
            var machine_name_obj = document.getElementById('machine_name_obj').value;

            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_assign',
                    section_id: section_name_obj,
                    machine_id: machine_name_obj
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Machine Already Exist in Section.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#machine_name_obj').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Machine Successfully Assigned.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#assignSectionList_table').DataTable().ajax.reload(null, false);
                        clearAttributes();
                        $('#machine_name_obj').find('option:first').prop('selected', 'selected');
                    }
                }
            });
        }
    }

    function modifyAssign(sectionassignid) {
        $('#addUpdateAssignModal').modal('show');
        $('.btnSaveAssign').prop('disabled', true).css('display', 'none');
        $('.btnUpdateAssign').prop('disabled', false).css('display', 'block');
        $('.btnUpdateAssign').val(sectionassignid);
        $('#assign_modal_title').html('ASSIGN UPDATE');
        loadSelectWithId('assignSection');
        loadSelectWithId('assignMachine');

        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_assign_info',
                sectionassignid: sectionassignid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#section_name_obj').val(result.section_id);
                    $('#machine_name_obj').val(result.machine_id);
                }, 300);
            }
        });
    }

    function updateAssign(sectionassignid) {
        if (inputValidation('section_name_obj', 'machine_name_obj')) {
            var section_name_obj = document.getElementById('section_name_obj').value;
            var machine_name_obj = document.getElementById('machine_name_obj').value;

            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_assign',
                    section_id: section_name_obj,
                    machine_id: machine_name_obj,
                    sectionassignid: sectionassignid
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Machine Already Exist in Section.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#machine_name_obj').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Machine Assign Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#assignSectionList_table').DataTable().ajax.reload(null, false);
                        $('#addUpdateAssignModal').modal('hide');
                        clearAttributes();
                    }
                }
            });
        }
    }

    function deleteAssign(sectionassignid) {
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
                    url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'remove_assign',
                        sectionassignid: sectionassignid
                    },
                    success: function(result) {
                        $('#assignSectionList_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Section deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function addSectionEmployeeModal() {
        $('#assignSectionEmployeeModal').modal('show');
        $('#assign_employee_modal_title').html('ASSIGN EMPLOYEE');
        $('.btnUpdateAssignEmployee').prop('disabled', true).css('display', 'none');
        $('.btnSaveAssignEmployee').prop('disabled', false).css('display', 'block');
        loadSelectWithId('assignEmployee');
        loadSelectWithId('assignJobTitle');
    }

    function saveAssignSectionEmployee() {
        if (inputValidation('assign_section_name', 'section_job_title')) {
            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_section_job_title',
                    section_id: $('#assign_section_name').val(),
                    pos_code: $('#section_job_title').val()
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Job Title Already Exist in Section.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#section_job_title').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Job Title Successfully Assigned.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#sectionAssignEmployee_table').DataTable().ajax.reload(null, false);
                        clearAttributes();
                        $('#section_job_title').find('option:first').prop('selected', 'selected');
                    }
                }
            });
        }
    }

    function modifyAssignEmployee(assignjobid) {
        $('#assignSectionEmployeeModal').modal('show');
        $('#assign_employee_modal_title').html('UPDATE ASSIGN EMPLOYEE');
        $('.btnUpdateAssignEmployee').val(assignjobid);
        $('.btnUpdateAssignEmployee').prop('disabled', false).css('display', 'block');
        $('.btnSaveAssignEmployee').prop('disabled', true).css('display', 'none');
        loadSelectWithId('assignEmployee');
        loadSelectWithId('assignJobTitle');

        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_assign_employee_info',
                assignjobid: assignjobid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#section_job_title').val(result.job_title)
                    $('#assign_section_name').val(result.section_id)
                }, 300);
            }
        });
    }

    function updateAssignSectionEmployee(assignjobid) {
        if (inputValidation('assign_section_name', 'section_job_title')) {
            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_section_job_title',
                    section_id: $('#assign_section_name').val(),
                    pos_code: $('#section_job_title').val(),
                    assignjobid: assignjobid
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Job Title Already Exist in Section.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#section_job_title').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Job Title Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#sectionAssignEmployee_table').DataTable().ajax.reload(null, false);
                        clearAttributes();
                    }
                }
            });
        }
    }

    function deleteAssignEmployee(assignjobid) {
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
                    url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'remove_section_job_title',
                        assignjobid: assignjobid
                    },
                    success: function(result) {
                        $('#sectionAssignEmployee_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Section Assigned Job Title deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function loadSelectWithId(category) {
        var inObject;
        if (category == 'assignSection') {
            inObject = 'section_name_obj';
        } else if (category == 'assignEmployee') {
            inObject = 'assign_section_name';
        } else if (category == 'assignMachine') {
            inObject = 'machine_name_obj';
        } else {
            inObject = 'section_job_title';
        }

        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_section_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_with_id',
                category: category
            },
            success: function(result) {
                $('#' + inObject).empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#` + inObject + ` option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${optionValue}">${optionText}</option>`);
                    }
                    $.each(result, (key, value) => {
                        $('#' + inObject).append(`<option value="${key}">${value}</option>`);
                    });
                }, 100);
            }
        });
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function clearAttributes() {
        $('input[type=text]').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>