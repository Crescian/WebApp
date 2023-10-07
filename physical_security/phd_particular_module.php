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
                <span class="page-title-physical">Particular Module</span>
                <div class="row mt-5 justify-content-center">
                    <div class="col-xl">
                        <div class="card shadow mb-4">
                            <div class="card-header card-2 py-3">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h4 class="fw-bold text-light">Particular List</h4>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <button class="btn btn-light fw-bold fs-18" onclick="partlistModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Particular</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="particular_table" class="table table-bordered table-striped fw-bold" width="100%">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th>Particular Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="custom_table_header_color_physical">
                                                <tr>
                                                    <th>Particular Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl">
                        <div class="card shadow">
                            <div class="card-header card-2 py-3">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h4 class="fw-bold text-light">Assign Particular</h4>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
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
                                                    <th>Particular Name</th>
                                                    <th>Location Name</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="custom_table_header_color_physical">
                                                <tr>
                                                    <th>Particular Name</th>
                                                    <th>Location Name</th>
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
            <div class="modal fade" id="addParticularModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light add-particular-header"> Add Particular Name</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select name="" id="location_name" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="location_name" class="fw-bold">Location Name:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" id="add_particular_name" class="form-control fw-bold">
                                <div class="invalid-feedback"></div>
                                <label for="" class="fw-bold">Particular Name:</label>
                            </div>
                            <div class="row mb-1 px-3">
                                <button class="btn btn-success btn-sm col-sm-12 fw-bold btn-assign-save" style="border-radius: 20px;" onclick="saveParticularlist();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            </div>
                            <div class="row px-3">
                                <button class="btn btn-danger btn-sm col-sm-12 fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>=
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
    var particular_table;
    var assign_table;
    let btnParticular = 'save';
    let btnAssign = 'edit';
    let particularPreview;
    let assignPreview;
    loadparticular_table();
    loadassign_table();
    loadSelectWithId();

    function partlistModal() {
        $('#addParticularModal').modal('show');
        $('#add_particular_name').prop('disabled', false);
        $('#location_name').prop('disabled', false);
        $('.btn-assign-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Save');
        $('.btn-assign-save').addClass('btn-success').removeClass('btn-warning');
        $('.add-particular-header').html('Add Particular');
        btnParticular = 'save';
        loadSelectWithId();
    }

    function loadparticular_table() {
        var particular_table = $('#particular_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_particular_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_location_table'
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
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnParticularPreview('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnParticularDelete('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        particular_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            particular_table.ajax.reload(null, false);
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
                url: '../controller/phd_controller/phd_particular_contr.class.php',
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
                width: '47.5%',
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '47.5%',
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

    function loadSelectWithId() {
        return new Promise(resolve => {
            var inObject;
            inObject = 'location_name';
            $.ajax({
                url: '../controller/phd_controller/phd_particular_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_select_value_with_id'
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

    function saveParticularlist() {
        loadSelectWithId();
        if (btnParticular == 'save') {
            if (submitValidation('saveParticular')) {
                let add_particular_name = document.getElementById('add_particular_name').value;
                let location_name = document.getElementById('location_name').value;

                $.ajax({
                    url: '../controller/phd_controller/phd_particular_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'save_particular',
                        add_particular_name: add_particular_name,
                        location_name: location_name
                    },
                    success: function(result) {
                        if (result.result == 'existing') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Particular Exist!',
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
                        }
                    }
                });
            }
        } else if (btnParticular == 'edit') {
            $('#add_particular_name').prop('disabled', false);
            $('.btn-assign-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Update');
            $('.btn-assign-save').addClass('btn-success').removeClass('btn-warning');
            $('.add-assign-header').html('Update Particular');
            btnParticular = 'update';
        } else {
            // UPDATE FUNCTION
            let add_particular_name = document.getElementById('add_particular_name').value;
            $.ajax({
                url: '../controller/phd_controller/phd_particular_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'update_particular',
                    add_particular_name: add_particular_name,
                    particularPreview: particularPreview
                },
                success: function(result) {
                    if (result.result == 'empty') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Particular Exist!',
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
            })
        }
    }

    function btnParticularPreview(id) {
        particularPreview = id;
        $.ajax({
            url: '../controller/phd_controller/phd_particular_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_particular',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#add_particular_name').val(data.particular_name);
            }
        })
        $('#addParticularModal').modal('show');
        $('#add_particular_name').prop('disabled', true);
        $('#location_name').prop('disabled', true);
        $('.btn-assign-save').html('<i class="fa-regular fa-pen-to-square"></i> Edit');
        $('.btn-assign-save').addClass('btn-warning').removeClass('btn-success');
        $('.add-particular-header').html('Edit Particular');
        btnParticular = 'edit';
    }

    function btnParticularDelete(id) {
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
                    url: '../controller/phd_controller/phd_particular_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'delete_particular',
                        id: id
                    },
                    success: function(result) {
                        if (result.result == 'success') {
                            refreshProcessTable();
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Warning!',
                                'Cannot Delete Category Name. Currently In Use or Still Assigned',
                                'info'
                            )
                        }
                    }
                });
            }
        });
    }

    function closeFunc() {
        $('#addParticularModal').modal('hide');
        $('#addAssignModal').modal('hide');
        clearValues();
    }

    function refreshProcessTable() {
        $('#particular_table').DataTable().ajax.reload(null, false);
        $('#assign_table').DataTable().ajax.reload(null, false);
    }

    function submitValidation(val) {
        let isValidated = true;
        if (val == 'saveParticular') {
            let add_particular_name = document.getElementById('add_particular_name').value;
            let location_name = document.getElementById('location_name').value;
            if (add_particular_name.length == 0) {
                showFieldError('add_particular_name', 'Particular name must not be blank');
                if (isValidated) {
                    $('#add_particular_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_particular_name');
            }
            if (location_name.length == 0) {
                showFieldError('location_name', 'Location name must not be blank');
                if (isValidated) {
                    $('#location_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('location_name');
            }
            return isValidated;
        } else if (val == 'saveAssign') {
            let add_particular_assign_name = document.getElementById('add_particular_assign_name').value;
            let add_location_name = document.getElementById('add_location_name').value;
            if (add_particular_assign_name.length == 0) {
                showFieldError('add_particular_assign_name', 'Particular name must not be blank');
                if (isValidated) {
                    $('#add_particular_assign_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_particular_assign_name');
            }
            if (add_location_name.length == 0) {
                showFieldError('add_location_name', 'Location name must not be blank');
                if (isValidated) {
                    $('#add_location_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_location_name');
            }
            return isValidated;
        }
    }

    function clearValues() {
        $('input').val('');
        $('.btn-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i>Save');
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