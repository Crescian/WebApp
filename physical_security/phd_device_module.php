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
    /* =========== Change Scrollbar Style - Justine 02162023 =========== */
    ::-webkit-scrollbar {
        width: 0.5vw;
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
                <span class="page-title-physical">Device Module</span>
            </div>
            <div class="row mt-4">
                <div class="col mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Device Category List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addDeviceCategoryModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Category</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="categoryNameList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Category Name</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Category Name</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Device Category List End ==================== -->
                </div>
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Assigned List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="assignCategoryDeviceModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Assign Device</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="categoryAssignList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Device Cat</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>Device</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Device Cat</th>
                                            <th>Location</th>
                                            <th>Category</th>
                                            <th>Device</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Assign Device List End ==================== -->
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-6 mb-3">
                    <div class="card shadow">
                        <div class="card-header card-2 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Assign Device Units</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addUnitsModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Assign Units</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="units_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Location</th>
                                            <th>Units</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_physical">
                                        <tr>
                                            <th>Location</th>
                                            <th>Units</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- ==================== Device Category List End ==================== -->
                </div>
            </div>
            <!-- =============== Category Entry Modal =============== -->
            <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Assign Units</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2">
                                <select name="" id="location_units" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="location_units" class="fw-bold">Location Name</label>
                            </div>
                            <div class="form-floating mt-2">
                                <select name="" id="units" class="form-select fw-bold">
                                    <option value="">Choose...</option>
                                    <option value="Annunciator">Annunciator</option>
                                    <option value="MCP">MCP</option>
                                    <option value="Bell">Bell</option>
                                    <option value="Blinker">Blinker</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="units" class="fw-bold">Units</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-warning btnUpdateUnitName" onclick="updateUnitName(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveUnitName" onclick="saveUnitName();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Category Entry Modal End ===============-->
            <!-- ==================== Table Section End ==================== -->
            <!-- =============== Category Entry Modal =============== -->
            <div class="modal fade" id="addDeviceCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Add Category</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control fw-bold" id="device_category_name">
                                <div class="invalid-feedback"></div>
                                <label for="device_category_name" class="fw-bold">Category Name</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-warning btnUpdateCategoryName" onclick="updateCategoryName(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveCategoryName" onclick="saveCategoryName();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Category Entry Modal End ===============-->
            <!-- =============== Assign Category Entry Modal =============== -->
            <div class="modal fade" id="assignDeviceCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-2">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Assign Device</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="assign_category_name">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="assign_category_name" class="fw-bold">Device Category Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="location_name">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="location_name" class="fw-bold">Location Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select fw-bold" id="category_name">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label for="category_name" class="fw-bold">Category Name</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input class="form-control fw-bold" id="device_name">
                                <div class="invalid-feedback"></div>
                                <label for="device_name" class="fw-bold">Device Name</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-warning btnUpdateAssignCategory" onclick="updateAssignCategory(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-success btnSaveAssignCategory" onclick="saveAssignCategory();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!--=============== Assign Category Entry Modal End ===============-->
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
<?php include './../includes/footer.php';
include './../helper/select_values.php'; ?>
<script>
    loadCategoryNameTable();
    loadAssignCategoryTable();
    loadUnitTable();

    function addUnitsModal() {
        $('#unitModal').modal('show');
        loadSelectDevice('location_units');
        $('.btnUpdateUnitName').css('display', 'none');
        $('.btnSaveUnitName').css('display', 'block');
    }

    function loadSelectDevice(inObject) {
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_values'
            },
            success: result => {
                loadSelectValues(inObject, result)
            }
        });
    }

    function addDeviceCategoryModal() {
        $('#addDeviceCategoryModal').modal('show');
        $('.btnUpdateCategoryName').css('display', 'none');
        $('.btnSaveUnitName').css('display', 'block');
    }

    function loadCategoryNameTable() {
        var categoryNameList_table = $('#categoryNameList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_device_contr.class.php',
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
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="removeCategoryName('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        categoryNameList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            categoryNameList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function btnPreviewCategory(id) {
        $('.btnUpdateCategoryName').val(id);
        $('#addDeviceCategoryModal').modal('show');
        $('.btnUpdateCategoryName').css('display', 'block');
        $('.btnSaveCategoryName').css('display', 'none');
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'btnPreviewCategoryFunction',
                id: id
            },
            success: function(result) {
                $('#device_category_name').val(result.result);
            }
        });
    }

    function updateCategoryName(id) {
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'updateCategoryNameFunction',
                category_name: $('#device_category_name').val(),
                id: id
            },
            success: function(result) {}
        });
        refreshProcessTable();
    }

    function saveCategoryName() {
        if (submitValidation('addCategory')) {
            var device_category_name = document.getElementById('device_category_name').value;
            $.ajax({
                url: '../controller/phd_controller/phd_device_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_category_name',
                    device_category_name: device_category_name
                },
                success: function(result) {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Added.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    clearValues();
                    refreshProcessTable();
                }
            });
        }
    }

    function removeCategoryName(devicecategoryid) {
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
                    url: '../controller/phd_controller/phd_device_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'remove_category_name',
                        devicecategoryid: devicecategoryid
                    },
                    success: function(result) {
                        if (result.result == 'success') {
                            refreshProcessTable();
                            Swal.fire(
                                'Deleted!',
                                'Category deleted.',
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
        })
    }

    function loadAssignCategoryTable() {
        var categoryAssignList_table = $('#categoryAssignList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_device_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_category_assigned_table'
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
                targets: [0, 1, 2, 3],
                className: 'dt-body-middle-left',
                width: '22%',
            }, {
                targets: 4,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewAssign('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="removeAssignCategory('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        categoryAssignList_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            categoryAssignList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function btnPreviewAssign(id) {
        $('.btnUpdateAssignCategory').val(id);
        $('#assignDeviceCategoryModal').modal('show');
        $('.btnUpdateAssignCategory').css('display', 'block');
        $('.btnSaveAssignCategory').css('display', 'none');
        loadSelectValueWithId('phd_device_category', 'devicecategoryid', 'device_category_name', 'assign_category_name', 'physical_security');
        loadSelectValueWithId('phd_location', 'phdlocationid', 'location_name', 'location_name', 'physical_security');
        loadSelectValueWithId('phd_location_category', 'phdloccatid', 'category_name', 'category_name', 'physical_security');
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'btnPreviewAssign',
                id: id
            },
            success: function(result) {
                setTimeout(function() {
                    $('#assign_category_name').val(result.devicecategory_id);
                    $('#location_name').val(result.phdlocation_id);
                    $('#category_name').val(result.phdloccat_id);
                    $('#device_name').val(result.device_name);
                }, 300);
            }
        });
    }

    function updateAssignCategory(id) {
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'updateAssignCategoryFunction',
                assign_category_name: $('#assign_category_name').val(),
                location_name: $('#location_name').val(),
                category_name: $('#category_name').val(),
                device_name: $('#device_name').val(),
                id: id
            },
            success: function(result) {}
        });
        refreshProcessTable();
    }

    function saveAssignCategory() {
        if (submitValidation('assignDevice')) {
            var assign_category_name = document.getElementById('assign_category_name').value;
            var location_name = document.getElementById('location_name').value;
            var category_name = document.getElementById('category_name').value;
            var device_name = document.getElementById('device_name').value;

            $.ajax({
                url: '../controller/phd_controller/phd_device_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_assign_device',
                    assign_category_name: assign_category_name,
                    location_name: location_name,
                    category_name: category_name,
                    device_name: device_name
                },
                success: function(result) {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Successfully Save.',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#location_name').find('option:first').prop('selected', 'selected');
                    $('#category_name').find('option:first').prop('selected', 'selected');
                    $('#device_name').val('');
                    clearAttributes();
                    refreshProcessTable();
                }
            });
        }
    }

    function removeAssignCategory(devicecatassignid) {
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
                    url: '../controller/phd_controller/phd_device_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_assigned_category',
                        devicecatassignid: devicecatassignid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Assigned Category deleted.',
                            'success'
                        )
                    }
                });
            }
        })
    }

    function loadUnitTable() {
        var units_table = $('#units_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_device_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_units_table'
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
                width: '30%',
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '30%'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '10%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreviewAssignUnits('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnUnitDelete('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        units_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            units_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function btnPreviewAssignUnits(id) {
        $('.btnUpdateUnitName').val(id);
        $('#unitModal').modal('show');
        $('.btnUpdateUnitName').css('display', 'block');
        $('.btnSaveUnitName').css('display', 'none');
        loadSelectDevice('location_units');
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'btnPreviewAssignUnits',
                id: id
            },
            success: function(result) {
                setTimeout(function() {
                    $('#location_units').val(result.phdlocation_name);
                }, 300);
                $('#units').val(result.units);
            }
        });

    }

    function updateUnitName(id) {
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'updateAssignUnitNameFunction',
                location_units: $('#location_units').val(),
                units: $('#units').val(),
                id: id
            },
            success: function(result) {}
        });
        refreshProcessTable();
    }

    function saveUnitName() {
        let location_units = document.getElementById('location_units').value;
        let units = document.getElementById('units').value;
        $.ajax({
            url: '../controller/phd_controller/phd_device_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'saveUnitNameFunction',
                location_units: location_units,
                units: units
            },
            success: function(result) {
                if (result.result == 'existing') {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'location and Units are Exist!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    refreshProcessTable();
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Saved Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    }

    function btnUnitDelete(assignunitsid) {
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
                    url: '../controller/phd_controller/phd_device_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'btnUnitDeleteFunction',
                        assignunitsid: assignunitsid
                    },
                    success: function(result) {
                        refreshProcessTable();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            }
        });
    }

    function refreshProcessTable() {
        $('#categoryNameList_table').DataTable().ajax.reload(null, false);
        $('#categoryAssignList_table').DataTable().ajax.reload(null, false);
        $('#units_table').DataTable().ajax.reload(null, false);
    }

    function assignCategoryDeviceModal() {
        $('#assignDeviceCategoryModal').modal('show');
        $('.btnUpdateAssignCategory').css('display', 'none');
        $('.btnSaveAssignCategory').css('display', 'block');
        loadSelectValueWithId('phd_device_category', 'devicecategoryid', 'device_category_name', 'assign_category_name', 'physical_security');
        loadSelectValueWithId('phd_location', 'phdlocationid', 'location_name', 'location_name', 'physical_security');
        loadSelectValueWithId('phd_location_category', 'phdloccatid', 'category_name', 'category_name', 'physical_security');
    }

    function submitValidation(val) {
        var isValidated = true;
        if (val == 'assignDevice') {
            var assign_category_name = document.getElementById('assign_category_name').value;
            var location_name = document.getElementById('location_name').value;
            var category_name = document.getElementById('category_name').value;
            var device_name = document.getElementById('device_name').value;

            if (assign_category_name.length == 0) {
                showFieldError('assign_category_name', 'Device Category Name must not be blank');
                if (isValidated) {
                    $('#assign_category_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('assign_category_name');
            }

            if (location_name.length == 0 && category_name.length == 0) {
                if (device_name.length == 0) {
                    showFieldError('device_name', 'Device Name must not be blank');
                    if (isValidated) {
                        $('#device_name').focus();
                    }
                    isValidated = false;
                } else {
                    clearFieldError('device_name');
                }
            }
            return isValidated;
        } else {
            var device_category_name = document.getElementById('device_category_name').value;

            if (device_category_name.length == 0) {
                showFieldError('device_category_name', 'Category Name must not be blank');
                if (isValidated) {
                    $('#device_category_name').focus();
                }
                isValidated = false;
            } else {
                clearFieldError('device_category_name');
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

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('select').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>