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
                <span class="page-title-physical">Location Module</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl">
                    <div class="card shadow mb-4">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Location List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="locateModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Location</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="location_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th>Location Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
                                                <th>Location Name</th>
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
                                    <h4 class="fw-bold text-light">Category List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button class="btn btn-light fw-bold fs-18" onclick="categoryModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Category</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="category_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_physical">
                                            <tr>
                                                <th>Category Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_physical">
                                            <tr>
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
            <div class="row mt-5 justify-content-center">
                <div class="col">
                    <div class="col-xl-12">
                        <div class="card shadow">
                            <div class="card-header card-2 py-3">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <h4 class="fw-bold text-light">Assign List</h4>
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
                                                    <th>Category Name</th>
                                                    <th>Location Name</th>
                                                    <th>Zone Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="custom_table_header_color_physical">
                                                <tr>
                                                    <th>Category Name</th>
                                                    <th>Location Name</th>
                                                    <th>Zone Name</th>
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
            <div class="modal fade" id="addLocationModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light add-location-header"> Add Location</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" id="add_location_name" class="form-control fw-bold">
                                <div class="invalid-feedback"></div>
                                <label for="add_location_name" class="fw-bold">Location Name:</label>
                            </div>
                            <div class="row mb-1 px-3">
                                <button class="btn btn-success btn-sm col-sm-12 fw-bold btn-loc-save" style="border-radius: 20px;" onclick="saveLocation();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            </div>
                            <div class="row px-3">
                                <button class="btn btn-danger btn-sm col-sm-12 fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light add-category-header"> Add Category</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" id="add_category_name" class="form-control fw-bold">
                                <div class="invalid-feedback"></div>
                                <label for="add_category_name" class="fw-bold">Category Name:</label>
                            </div>
                            <div class="row mb-1 px-3">
                                <button class="btn btn-success btn-sm col-sm-12 fw-bold btn-cat-save" style="border-radius: 20px;" onclick="saveCategory();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
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
                            <h4 class="modal-title text-uppercase fw-bold text-light add-assign-header"> Add Assign</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select name="" id="add_category_name_assign" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="add_category_name_assign" class="fw-bold">Category Name:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select name="" id="add_location_name_assign" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="add_location_name_assign" class="fw-bold">Location Name:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" id="zone_name" class="form-control fw-bold">
                                <div class="invalid-feedback"></div>
                                <label for="zone_name" class="fw-bold">Zone Name:</label>
                            </div>
                            <div class="d-grid gap-2 col-sm-12 mx-auto mb-2">
                                <button type="button" class="btn btn-success btn-sm fw-bold btn-assign-save" style="border-radius: 20px;" onclick="saveAssign();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                                <button type="button" class="btn btn-danger btn-sm fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                            </div>
                            <!-- <div class="row mb-1 px-3">
                                <button class="btn btn-success btn-sm col-sm-12 fw-bold btn-save" style="border-radius: 20px;" onclick="saveAssign();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            </div>
                            <div class="row px-3">
                                <button class="btn btn-danger btn-sm col-sm-12 fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                            </div> -->
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
    let location_table;
    let category_table;
    let assign_table;
    load_location_table();
    load_category_table();
    load_assign_table();
    let btnLocation = 'save';
    let btnCategory = 'save';
    let btnAssign = 'save';
    let locationPreview;
    let categoryPreview;
    let assignPreview;

    function locateModal() {
        $('#addLocationModal').modal('show');
        $('#add_location_name').prop('disabled', false);
        $('.btn-loc-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Save');
        $('.btn-loc-save').addClass('btn-success').removeClass('btn-warning');
        $('.add-location-header').html('Add Location');
        btnLocation = 'save';
    }

    function categoryModal() {
        $('#addCategoryModal').modal('show');
        $('#add_category_name').prop('disabled', false);
        $('.btn-cat-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Save');
        $('.btn-cat-save').addClass('btn-success').removeClass('btn-warning');
        $('.add-category-header').html('Add Category');
        btnCategory = 'save';
    }

    function assignModal() {
        loadDropdown();
        $('#addAssignModal').modal('show');
        $('#add_category_name_assign').prop('disabled', false);
        $('#add_location_name_assign').prop('disabled', false);
        $('#zone_name').prop('disabled', false);
        $('.btn-assign-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Save');
        $('.btn-assign-save').addClass('btn-success').removeClass('btn-warning');
        $('.add-assign-header').html('Add Assign');
        btnAssign = 'save';
    }

    function load_location_table() {
        var location_table = $('#location_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_location_contr.class.php',
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
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewLocation('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteLocation('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        location_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            location_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function load_category_table() {
        var category_table = $('#category_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_location_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_category_table'
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
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewCategory('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteCategory('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        category_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            category_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function load_assign_table() {
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
                url: '../controller/phd_controller/phd_location_contr.class.php',
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
                width: '42%',
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '42%',
            }, {
                targets: 2,
                className: 'dt-body-middle-left',
                width: '9%',
                orderable: false
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
            // $('[data-bs-toggle="tooltip"]').tooltip('hide'); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            assign_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function saveLocation() {
        if (btnLocation == 'save') {
            if (submitValidation('saveLocation')) {
                let add_location_name = document.getElementById('add_location_name').value;
                $.ajax({
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'save_location_function',
                        add_location_name: add_location_name
                    },
                    success: function(result) {
                        if (result.result == 'empty') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Location Exist!',
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
                            clearValues();
                            refreshProcessTable();
                        }
                    }
                });
            }
        } else if (btnLocation == 'edit') {
            $('#add_location_name').prop('disabled', false);
            $('.btn-loc-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Update');
            $('.btn-loc-save').addClass('btn-success').removeClass('btn-warning');
            $('.add-location-header').html('Update Location');
            btnLocation = 'update';
        } else {
            // UPDATE FUNCTION
            if (submitValidation('saveLocation')) {
                let add_location_name = document.getElementById('add_location_name').value;
                $.ajax({
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'location_update_function',
                        add_location_name: add_location_name,
                        locationPreview: locationPreview
                    },
                    success: function(result) {
                        $('#addLocationModal').modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Update Succesfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        refreshProcessTable();
                    }
                });
            }
        }
    }

    function saveCategory() {
        if (btnCategory == 'save') {
            if (submitValidation('saveCategory')) {
                let add_category_name = document.getElementById('add_category_name').value;
                $.ajax({
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'save_category',
                        add_category_name: add_category_name
                    },
                    success: function(result) {
                        if (result.result == 'empty') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Category Exist!',
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
                            clearValues();
                        }
                    }
                });
            }
        } else if (btnCategory == 'edit') {
            $('#add_category_name').prop('disabled', false);
            $('.btn-cat-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Update');
            $('.btn-cat-save').addClass('btn-success').removeClass('btn-warning');
            $('.add-category-header').html('Add Update');
            btnCategory = 'update';
        } else {
            // UPDATE FUNCTION
            let add_category_name = document.getElementById('add_category_name').value;
            $.ajax({
                url: '../controller/phd_controller/phd_location_contr.class.php',
                type: 'POST',
                data: {
                    action: 'category_update',
                    add_category_name: add_category_name,
                    categoryPreview: categoryPreview
                },
                success: function(result) {
                    refreshProcessTable();
                    $('#addCategoryModal').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Update Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }
            });
        }
    }

    function saveAssign() {
        if (btnAssign == 'save') {
            if (submitValidation('saveAssign')) {
                let add_category_name_assign = document.getElementById('add_category_name_assign').value;
                let add_location_name_assign = document.getElementById('add_location_name_assign').value;
                let zone_name = document.getElementById('zone_name').value;
                $.ajax({
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'save_assign_function',
                        add_category_name_assign: add_category_name_assign,
                        add_location_name_assign: add_location_name_assign,
                        zone_name: zone_name
                    },
                    success: function(result) {
                        if (result.result == 'empty') {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Assign Exist!',
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
        } else if (btnAssign == 'edit') {
            $('#add_category_name_assign').prop('disabled', false);
            $('#add_location_name_assign').prop('disabled', false);
            $('#zone_name').prop('disabled', false);
            $('.btn-assign-save').html('<i class="fa-solid fa-floppy-disk p-r-8"></i> Update');
            $('.btn-assign-save').addClass('btn-success').removeClass('btn-warning');
            $('.add-assign-header').html('Update Assign');
            btnAssign = 'update';
        } else {
            // UPDATE FUNCTION
            let add_category_name_assign = document.getElementById('add_category_name_assign').value;
            let add_location_name_assign = document.getElementById('add_location_name_assign').value;
            let zone_name = document.getElementById('zone_name').value;
            $.ajax({
                url: '../controller/phd_controller/phd_location_contr.class.php',
                type: 'POST',
                data: {
                    action: 'assign_update',
                    add_category_name_assign: add_category_name_assign,
                    add_location_name_assign: add_location_name_assign,
                    zone_name: zone_name,
                    assignPreview: assignPreview
                },
                success: function(result) {
                    if (result == 'empty') {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Assign Exist!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    refreshProcessTable();
                    $('#addAssignModal').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Update Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    }

    function btnPreviewAssign(id) {
        assignPreview = id;
        loadDropdown();
        $.ajax({
            url: '../controller/phd_controller/phd_location_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_assign',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#add_category_name_assign').val(data.phdloccat_id);
                $('#add_location_name_assign').val(data.phdlocation_id);
                $('#zone_name').val(data.zone_category_name);
            }
        });
        $('#addAssignModal').modal('show');
        $('.add-assign-header').html('Edit Category')
        $('#add_category_name_assign').prop('disabled', true);
        $('#add_location_name_assign').prop('disabled', true);
        $('#zone_name').prop('disabled', true);
        $('.btn-assign-save').html('<i class="fa-regular fa-pen-to-square"></i> Edit');
        $('.btn-assign-save').addClass('btn-warning').removeClass('btn-success');
        btnAssign = 'edit';
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
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'delete_assign',
                        id: id
                    },
                    success: function(result) {
                        refreshProcessTable();
                    }
                });
            }
        });
    }

    function btnPreviewCategory(id) {
        categoryPreview = id;
        $.ajax({
            url: '../controller/phd_controller/phd_location_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_category',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#add_category_name').val(data.category_name);
            }
        });
        $('#addCategoryModal').modal('show');
        $('.add-category-header').html('Edit Category')
        $('#add_category_name').prop('disabled', true);
        $('.btn-cat-save').html('<i class="fa-regular fa-pen-to-square"></i> Edit');
        $('.btn-cat-save').addClass('btn-warning').removeClass('btn-success');
        btnCategory = 'edit';
    }

    function btnDeleteCategory(id) {
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
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'delete_category',
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

    function btnPreviewLocation(id) {
        locationPreview = id;
        $.ajax({
            url: '../controller/phd_controller/phd_location_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'preview_location',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#add_location_name').val(data.location_name);
            }
        });
        $('#addLocationModal').modal('show');
        $('#add_location_name').prop('disabled', true);
        $('.btn-loc-save').html('<i class="fa-regular fa-pen-to-square"></i> Edit');
        $('.btn-loc-save').addClass('btn-warning').removeClass('btn-success');
        $('.add-location-header').html('Edit Location');
        btnLocation = 'edit';
    }

    function btnDeleteLocation(id) {
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
                    url: '../controller/phd_controller/phd_location_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'delete_location',
                        id: id
                    },
                    success: function(result) {
                        console.log(result.result);
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
                                'Cannot Delete Location Name. Currently In Use or Still Assigned',
                                'info'
                            )
                        }
                    }
                });
            }
        });
    }

    function loadDropdown() {
        $.ajax({
            url: '../controller/phd_controller/phd_location_contr.class.php',
            type: 'POST',
            data: {
                action: 'load_location'
            },
            success: function(result) {
                $('#add_location_name_assign').html(result);
            }
        });
        $.ajax({
            url: '../controller/phd_controller/phd_location_contr.class.php',
            type: 'POST',
            data: {
                action: 'load_category'
            },
            success: function(result) {
                $('#add_category_name_assign').html(result);
            }
        });
    }

    function refreshProcessTable() {
        $('#location_table').DataTable().ajax.reload(null, false);
        $('#category_table').DataTable().ajax.reload(null, false);
        $('#assign_table').DataTable().ajax.reload(null, false);
    }

    function closeFunc() {
        $('#addLocationModal').modal('hide');
        $('#addCategoryModal').modal('hide');
        $('#addAssignModal').modal('hide');
        clearValues();
    }

    function submitValidation(val) {
        let isValidated = true;
        if (val == 'saveLocation') {
            let add_location_name = document.getElementById('add_location_name').value;
            if (add_location_name.length == 0) {
                showFieldError('add_location_name', 'Location name must not be blank');
                if (isValidated) {
                    $('#add_location_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('location_name');
            }
            return isValidated;
        } else if (val == 'saveCategory') {
            let add_category_name = document.getElementById('add_category_name').value;
            if (add_category_name.length == 0) {
                showFieldError('add_category_name', 'Category name must not be blank');
                if (isValidated) {
                    $('#add_category_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('location_name');
            }
            return isValidated;
        } else if (val == 'saveAssign') {
            let add_category_name_assign = document.getElementById('add_category_name_assign').value;
            let add_location_name_assign = document.getElementById('add_location_name_assign').value;
            if (add_category_name_assign.length == 0) {
                showFieldError('add_category_name_assign', 'Category name must not be blank');
                if (isValidated) {
                    $('#add_category_name_assign').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_category_name_assign');
            }
            if (add_location_name_assign.length == 0) {
                showFieldError('add_location_name_assign', 'Location name must not be blank');
                if (isValidated) {
                    $('#add_location_name_assign').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('add_location_name_assign');
            }
            return isValidated;
        }
    }

    function clearValues() {
        $('#location_name').val('');
        checkVal = 'assign';
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