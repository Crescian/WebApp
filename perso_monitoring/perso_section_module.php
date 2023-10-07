<?php include './../includes/header.php';
// * Check if module is within the application
session_start();
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
                <span class="page-title-perso">Section Module</span>
            </div>
            <div class="row mt-3 mb-4">
                <div class="col">
                    <div class="card shadow mt-4">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light" id="process_division_title">Section List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="addNewSectionModal();"><i class="fa-solid fa-square-plus p-r-8"></i> New Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="section_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Section</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Section</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Add Section End ==================== -->
                </div>
                <div class="col">
                    <div class="card shadow mt-4">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light" id="process_division_title">Section Assigned List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-15" onclick="assignSectionModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Assign Section</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="assignedSection_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Section</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Section</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Card Assign Section End ==================== -->
                </div>
            </div>
            <!-- =============== Add Section Modal =============== -->
            <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="add_section_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="section_name">
                                <div class="invalid-feedback"></div>
                                <label class="col-form-label fw-bold" for="section_name">Section Name:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveSection" onclick="saveSection();">Save</button>
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdateSection" onclick="updateSection(this.value);">Update</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Section Modal End =============== -->
            <!-- =============== Assign Section Modal =============== -->
            <div class="modal fade" id="assignSectionModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="assign_section_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="assign_section_name"></select>
                                <div class="invalid-feedback"></div>
                                <label class="col-form-label fw-bold" for="assign_section_name">Section Name:</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select fw-bold" id="section_job_title"></select>
                                <div class="invalid-feedback"></div>
                                <label class="col-form-label fw-bold" for="section_job_title">Job Title:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveAssignSection" onclick="saveAssignSection();">Save</button>
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdateAssignSection" onclick="updateAssignSection(this.value);">Update</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Assign Section Modal End =============== -->
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
include './../includes/footer.php';
include './../helper/input_validation.php';
include './../helper/select_values.php'; ?>
<script>
    loadSectionTable();
    loadAssignSectionTable();

    function loadSectionTable() {
        var section_table = $('#section_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_section_list_table'
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
                    return btnAction = `<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Section" onclick="editSection('${data}');"><i class="fa-solid fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Section" onclick="deleteSection('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
                }
            }]
        });
        section_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 800);
        });

        setInterval(function() {
            section_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAssignSectionTable() {
        var assignedSection_table = $('#assignedSection_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_section_assigned_list_table'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                orderable: false
            }, {
                targets: 1,
                className: 'dt-body-middle-left'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Section" onclick="editAssignSection('${data}');"><i class="fa-solid fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Section" onclick="deleteAssignSection('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
                }
            }]
        });
        assignedSection_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 800);
        });

        setInterval(function() {
            assignedSection_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addNewSectionModal() {
        $('#addSectionModal').modal('show');
        $('.btnSaveSection').prop('disabled', false).css('display', 'block');
        $('.btnUpdateSection').prop('disabled', true).css('display', 'none');
        $('#add_section_title').html('ADD SECTION');
    }

    function saveSection() {
        if (inputValidation('section_name')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_section',
                    section_name: $('#section_name').val()
                },
                success: result => {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Section Already Exist!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully added.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#section_table').DataTable().ajax.reload(null, false);
                        clearValues();
                    }
                }
            });
        }
    }

    function editSection(perso_sectionid) {
        $('#addSectionModal').modal('show');
        $('.btnSaveSection').prop('disabled', true).css('display', 'none');
        $('.btnUpdateSection').val(perso_sectionid).prop('disabled', false).css('display', 'block');
        $('#add_section_title').html('UPDATE SECTION');
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_section_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_section_info',
                perso_sectionid: perso_sectionid
            },
            success: result => {
                $('#section_name').val(result.perso_section_name);
            }
        });
    }

    function updateSection(perso_sectionid) {
        if (inputValidation('section_name')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_section_name',
                    perso_sectionid: perso_sectionid,
                    section_name: $('#section_name').val()
                },
                success: result => {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Section Already Exist!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#section_table').DataTable().ajax.reload(null, false);
                        clearAttributes();
                    }
                }
            });
        }
    }

    function deleteSection(sectionpersoid) {
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
                    url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'delete_section',
                        perso_sectionid: sectionpersoid
                    },
                    success: result => {
                        if (result.data == 0) {
                            Swal.fire(
                                'Warning!',
                                'Cannot Delete Section. Currently In Use or Still Assigned',
                                'error'
                            )
                        } else {
                            $('#section_table').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                'Deleted!',
                                'Section deleted.',
                                'success'
                            )
                        }
                    }
                });
            }
        });
    }

    function assignSectionModal() {
        $('#assignSectionModal').modal('show');
        $('#assign_section_title').html('ASSIGN SECTION');
        $('.btnSaveAssignSection').prop('disabled', false).css('display', 'block');
        $('.btnUpdateAssignSection').prop('disabled', true).css('display', 'none');
        loadSelectSectionValues();
        loadSelectJobTitleValues();
    }

    function saveAssignSection() {
        if (inputValidation('assign_section_name', 'section_job_title')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_assign_section',
                    perso_section_id: $('#assign_section_name').val(),
                    section_job_title: $('#section_job_title').val()
                },
                success: result => {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Job title already exist in section!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully added.',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        $('#assignedSection_table').DataTable().ajax.reload(null, false);
                        $('#section_job_title').find('option:first').prop('selected', 'selected');
                    }
                }
            });
        }
    }

    function editAssignSection(perso_sect_assignid) {
        $('#assignSectionModal').modal('show');
        $('#assign_section_title').html('UPDATE ASSIGN SECTION');
        $('.btnSaveAssignSection').prop('disabled', true).css('display', 'none');
        $('.btnUpdateAssignSection').val(perso_sect_assignid).prop('disabled', false).css('display', 'block');
        loadSelectSectionValues();
        loadSelectJobTitleValues();
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_section_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_section_assigned_info',
                perso_sect_assignid: perso_sect_assignid
            },
            success: result => {
                setTimeout(function() {
                    $('#assign_section_name').val(result.perso_section_id);
                    $('#section_job_title').val(result.perso_assigned_name);
                }, 200);
            }
        });
    }

    function updateAssignSection(perso_sect_assignid) {
        if (inputValidation('assign_section_name', 'section_job_title')) {
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_assign_section',
                    perso_section_id: $('#assign_section_name').val(),
                    section_job_title: $('#section_job_title').val(),
                    perso_sect_assignid: perso_sect_assignid
                },
                success: result => {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Job title already exist in section!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Successfully updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 800
                        });
                        $('#assignedSection_table').DataTable().ajax.reload(null, false);
                        clearAttributes();
                    }
                }
            });
        }
    }

    function deleteAssignSection(perso_sect_assignid) {
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
                    url: '../controller/perso_monitoring_controller/perso_section_contr.php',
                    type: 'POST',
                    data: {
                        action: 'delete_section_assigned',
                        perso_sect_assignid: perso_sect_assignid
                    },
                    success: result => {
                        $('#assignedSection_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Assigned section deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    function loadSelectSectionValues() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_section_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_section_values'
            },
            success: result => {
                loadSelectValues('assign_section_name', result);
            }
        });
    }

    function loadSelectJobTitleValues() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_section_contr.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_job_title_values'
            },
            success: result => {
                loadSelectValues('section_job_title', result);
            }
        });
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function clearAttributes() {
        $('select').removeClass('is-valid is-invalid');
        $('input').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>