<?php include './../includes/header.php';
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
                <span class="page-title-physical">Checklist Module</span>
                <div class="row mt-5 justify-content-center">
                    <div class="col">
                        <div class="col-xl-12">
                            <div class="card shadow mb-4">
                                <div class="card-header card-2 py-3">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h4 class="fw-bold text-light">Checklist Name</h4>
                                        </div>
                                        <div class="col-sm">
                                            <div class="row">
                                                <button class="btn btn-light fw-bold fs-18" onclick="checklistModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Checklist</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="checklist_name_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="custom_table_header_color_physical">
                                                    <tr>
                                                        <th>Checklist Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="custom_table_header_color_physical">
                                                    <tr>
                                                        <th>Checklist Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="col-xl-12">
                            <div class="card shadow">
                                <div class="card-header card-2 py-3">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h4 class="fw-bold text-light">Assign Checklist</h4>
                                        </div>
                                        <div class="col-sm">
                                            <div class="row">
                                                <button class="btn btn-light fw-bold fs-18" onclick="assignModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Assign</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="assign_table" class="table table-bordered table-striped fw-bold" width="100%">
                                                <thead class="custom_table_header_color_physical">
                                                    <tr>
                                                        <th>Checklist Name</th>
                                                        <th>Location Name</th>
                                                        <th>Category Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot class="custom_table_header_color_physical">
                                                    <tr>
                                                        <th>Checklist Name</th>
                                                        <th>Location Name</th>
                                                        <th>Category Name</th>
                                                        <th>Action</th>
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
            <div class="modal fade" id="addChecklistModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light add-checklist-header"> Add Checklist Name</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <!-- <input type="text" id="add_checklist_name" class="form-control fw-bold"> -->
                                <select name="" id="add_checklist_name" class="form-select fw-bold">
                                    <option value=""></option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="add_checklist_name" class="fw-bold">Checklist Name:</label>
                            </div>
                            <div class="row mb-1 px-3">
                                <button class="btn btn-success btn-sm col-sm-12 fw-bold btn-checklist-save" style="border-radius: 20px;" onclick="saveChecklist();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            </div>
                            <div class="row px-3">
                                <button class="btn btn-danger btn-sm col-sm-12 fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addAssignModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light add-assign-header"> Add Assign Name</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select name="" id="add_checklist_assign_name" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="add_checklist_assign_name" class="fw-bold">Checklist Name:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select name="" id="add_category_name" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="add_category_name" class="fw-bold">Category Name:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select name="" id="add_location_name" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="add_location_name" class="fw-bold">Location Name:(Optional)</label>
                            </div>
                            <div class="row mb-1 px-3">
                                <button class="btn btn-success btn-sm col-sm-12 fw-bold btn-assign-save" style="border-radius: 20px;" onclick="saveAssign();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            </div>
                            <div class="row px-3">
                                <button class="btn btn-danger btn-sm col-sm-12 fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
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
<?php include './../includes/footer.php'; ?>
<script>
    loadchecklist_table();
    loadassign_table();
    loadDropdown();
    let btnChecklist = 'save';
    let btnAssignlist = 'save';
    let checklistPreview;
    let assignPreview;

    function loadchecklist_table() {
        var checklist_name_table = $('#checklist_name_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_checklist_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_checklist_name'
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
                className: 'dt-body-middle-left',
                width: '95%',
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewChecklist('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteChecklist('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        checklist_name_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            checklist_name_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadassign_table() {
        var assign_table = $('#assign_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_checklist_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_assign_table'
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
                className: 'dt-body-middle-left',
                width: '37%',
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '37%',
            }, {
                targets: 2,
                className: 'dt-body-middle-left',
                width: '37%',
            }, {
                targets: 3,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewAssign('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteAssign('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        assign_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            assign_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    async function loadDropdown() {
        loadSelectWithId('checklist_name');
        loadSelectWithId('category_name');
        loadSelectWithId('location_name');
        $.ajax({
            url: '../controller/phd_controller/phd_checklist_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_chechklist'
            },
            success: function(result) {
                // console.log(result);
                // alert(result);
                $('#add_checklist_name').empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "";
                    let optionExists = ($(`#add_checklist_name option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#add_checklist_name').append(`<option value="${optionValue}">${optionText}</option>`);
                    }
                    $.each(result, (key, value) => {
                        $('#add_checklist_name').append(`<option value="${key}">${value}</option>`);
                    });
                }, 100);

            }
        })
    }

    function loadSelectWithId(category) {
        return new Promise(resolve => {
            var inObject;
            if (category == 'checklist_name') {
                inObject = 'add_checklist_assign_name';
            } else if (category == 'category_name') {
                inObject = 'add_category_name';
            } else if (category == 'location_name') {
                inObject = 'add_location_name';
            }
            $.ajax({
                url: '../controller/phd_controller/phd_checklist_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_select_value_with_id',
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
        });
    }

    function saveChecklist() {
        if (btnChecklist == 'save') {
            if (submitValidation('saveChecklist')) {
                let add_checklist_name = document.getElementById('add_checklist_name').value;
                $.ajax({
                    url: '../controller/phd_controller/phd_checklist_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'save_checklist_function',
                        add_checklist_name: add_checklist_name
                    },
                    success: function(result) {
                        if (result.result == 'empty') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Checklist Exist!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            refreshProcessTable();
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Save Succesfully!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            clearValues();
                        }
                    }

                });
            }
        } else if (btnChecklist == 'edit') {
            $('#add_checklist_name').prop('disabled', false);
            $('.btn-checklist-save').html('<i class="fa-regular fa-pen-to-square"></i> Update');
            $('.btn-checklist-save').addClass('btn-success').removeClass('btn-warning');
            $('.add-checklist-header').html('Update Checklist');
            btnChecklist = 'update';
        } else {
            // UPDATE FUNCTION
            let add_checklist_name = document.getElementById('add_checklist_name').value;
            $.ajax({
                url: '../controller/phd_controller/phd_checklist_contr.class.php',
                type: 'POST',
                data: {
                    action: 'update_checklist_function',
                    add_checklist_name: add_checklist_name,
                    checklistPreview: checklistPreview
                },
                success: function(result) {
                    refreshProcessTable();
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Update Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
        }
    }

    function btnPreviewChecklist(id) {
        checklistPreview = id;
        $.ajax({
            url: '../controller/phd_controller/phd_checklist_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_checklist',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#add_checklist_name').val(data.checklist_name);
            }
        });
        $('#addChecklistModal').modal('show');
        $('#add_checklist_name').prop('disabled', true);
        $('.btn-checklist-save').html('<i class="fa-regular fa-pen-to-square"></i> Edit');
        $('.btn-checklist-save').addClass('btn-warning').removeClass('btn-success');
        $('.add-checklist-header').html('Edit Checklist');
        btnChecklist = 'edit';
    }

    function saveAssign() {
        if (btnAssignlist == 'save') {
            if (submitValidation('saveAssign')) {
                let add_checklist_assign_name = document.getElementById('add_checklist_assign_name').value;
                let add_category_name = document.getElementById('add_category_name').value;
                let add_location_name = document.getElementById('add_location_name').value;
                $.ajax({
                    url: '../controller/phd_controller/phd_checklist_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'save_assign_function',
                        add_checklist_assign: add_checklist_assign_name,
                        add_category_name: add_category_name,
                        add_location_name: add_location_name
                    },
                    success: function(result) {
                        if (result.result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Assign Exist!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'success',
                                title: 'Save Succesfully!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            refreshProcessTable();
                            clearValues();
                        }
                    }
                });
            }
        } else if (btnAssignlist == 'edit') {
            $('#add_checklist_assign_name').prop('disabled', false);
            $('#add_category_name').prop('disabled', false);
            $('#add_location_name').prop('disabled', false);
            $('.btn-assign-save').html('<i class="fa-regular fa-pen-to-square"></i> Update');
            $('.btn-assign-save').addClass('btn-success').removeClass('btn-warning');
            $('.add-assign-header').html('Update Assign Checklist');
            btnAssignlist = 'update';
        } else {
            // UPDATE FUNCTION
            let add_checklist_assign_name = document.getElementById('add_checklist_assign_name').value;
            let add_category_name = document.getElementById('add_category_name').value;
            let add_location_name = document.getElementById('add_location_name').value;
            $.ajax({
                url: '../controller/phd_controller/phd_checklist_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_assign_function',
                    add_checklist_assign_name: add_checklist_assign_name,
                    add_category_name: add_category_name,
                    add_location_name: add_location_name,
                    assignPreview: assignPreview
                },
                success: function(result) {
                    if (result.result == 'existing') {
                        $('#addAssignModal').modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Data Exist!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        refreshProcessTable();
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Update Succesfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }
    }

    function btnPreviewAssign(id) {
        assignPreview = id;
        $.ajax({
            url: '../controller/phd_controller/phd_checklist_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_assign',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#add_category_name').val(data.phdloccat_id);
                $('#add_checklist_assign_name').val(data.phdchklist_id);
                $('#add_location_name').val(data.phdlocation_id);
            }
        });
        $('#addAssignModal').modal('show');
        $('#add_checklist_assign_name').prop('disabled', true);
        $('#add_category_name').prop('disabled', true);
        $('#add_location_name').prop('disabled', true);
        $('.btn-assign-save').html('<i class="fa-regular fa-pen-to-square"></i> Edit');
        $('.btn-assign-save').addClass('btn-warning').removeClass('btn-success');
        $('.add-assign-header').html('Edit Assign Checklist');
        btnAssignlist = 'edit';
    }



    function btnDeleteChecklist(id) {
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
                    url: '../controller/phd_controller/phd_checklist_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_checklist',
                        id: id
                    },
                    success: function(result) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        refreshProcessTable();
                    }
                });
            }
        });
    }

    function btnDeleteAssign(id) {
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
                    url: '../controller/phd_controller/phd_checklist_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_assign',
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

    function refreshProcessTable() {
        $('#checklist_name_table').DataTable().ajax.reload(null, false);
        $('#assign_table').DataTable().ajax.reload(null, false);
    }

    function submitValidation(val) {
        let isValidated = true;
        if (val == 'saveChecklist') {
            let add_checklist_name = document.getElementById('add_checklist_name').value;
            if (add_checklist_name.length == 0) {
                showFieldError('add_checklist_name', 'Location name must not be blank');
                if (isValidated) {
                    $('#add_checklist_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_checklist_name');
            }
            return isValidated;
        } else if (val == 'saveAssign') {
            let add_checklist_assign_name = document.getElementById('add_checklist_assign_name').value;
            let add_location_name = document.getElementById('add_location_name').value;
            if (add_checklist_assign_name.length == 0) {
                showFieldError('add_checklist_assign_name', 'Checklist name must not be blank');
                if (isValidated) {
                    $('#add_checklist_assign_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_checklist_assign_name');
            }
            return isValidated;
        }
    }

    function checklistModal() {
        loadDropdown();
        $('#addChecklistModal').modal('show');
        $('#add_checklist_name').prop('disabled', false);
        $('.btn-checklist-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Save');
        $('.btn-checklist-save').addClass('btn-success').removeClass('btn-warning');
        $('.add-checklist-header').html('Add Checklist');
        btnChecklist = 'save';
    }

    function assignModal() {
        loadDropdown();
        $('#addAssignModal').modal('show');
        $('#add_checklist_assign_name').prop('disabled', false);
        $('#add_category_name').prop('disabled', false);
        $('#add_location_name').prop('disabled', false);
        $('.btn-assign-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Save');
        $('.btn-assign-save').addClass('btn-success').removeClass('btn-warning');
        $('.add-assign-header').html('Save Assign Checklist');
        btnAssignlist = 'save';
    }

    function closeFunc() {
        $('#addChecklistModal').modal('hide');
        $('#addAssignModal').modal('hide');
        clearValues();
    }

    function clearValues() {
        $('#location_name').val('');
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
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