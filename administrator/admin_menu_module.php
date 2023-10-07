<?php include './../includes/header.php';
//* Banner Web Database connection
$BannerWebLive = $conn->db_conn_bannerweb();

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
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #eb0b95;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content scroll_color_admin overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-admin">Menu Module</span>
            </div>
            <div class="row mt-5 mb-4"> <!-- =========== Access List Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-7 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light">Access List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="grantAccess();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Grant Access</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <select class="form-select fw-bold" id="menu_department" onclick="loadJobTitle();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold">Department</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <select class="form-select fw-bold" id="menu_job_title" onclick="loadEmployee();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold">Job Title</label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-3">
                                        <select class="form-select fw-bold" id="menu_employee" onclick="load_user_access();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label class="fw-bold">Employee Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="swiper slide-container">
                                    <div class="swiper-wrapper card-wrapper"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== Access List Section End =========== -->
            <div class="row mb-4"> <!-- =========== Application List Section =========== -->
                <div class="col-sm-6 mb-3">
                    <div class="card shadow mb-4">
                        <div class="card-header card-7 py-3">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h4 class="fw-bold text-light">Application List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addApplicationModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New Application Entry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="appList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_admin">
                                        <tr>
                                            <th>Name</th>
                                            <th>Link</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_admin">
                                        <tr>
                                            <th>Name</th>
                                            <th>Link</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="card shadow mb-4">
                        <div class="card-header card-7 py-3">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="fw-bold text-light">Menu List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addMenuModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New Menu Entry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="menuList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="custom_table_header_color_admin">
                                        <tr>
                                            <th>Title</th>
                                            <th>Link</th>
                                            <th>Application</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="custom_table_header_color_admin">
                                        <tr>
                                            <th>Title</th>
                                            <th>Link</th>
                                            <th>Application</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== Application List Section End =========== -->
            <!-- =============== Application Entry Modal =============== -->
            <div class="modal fade" id="addUpdateApplicationEntryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="app_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="application_name">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Application Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="application_link">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Application Link</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnUpdateAppEntry" onclick="updateAppEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveAppEntry" onclick="saveAppEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Menu Modal =============== -->
            <div class="modal fade" id="addUpdateMenuModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="menu_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="menu_app_name">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Application Name</label>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input class="form-check-input" type="radio" name="flexRadioDate" id="chkParentMenu" checked><label class="form-check-label fw-bold fs-15 p-l-8" for="chkParentMenu">Parent Menu</label>
                                </div>
                                <div class="col">
                                    <input class="form-check-input" type="radio" name="flexRadioDate" id="chkSubParent"><label class="form-check-label fw-bold fs-15 p-l-8" for="chkSubParent">Sub Parent Menu</label>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="menu_parent_menu" disabled>
                                    <option value="0">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Parent Menu</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="menu_title">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Menu Title</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control fw-bold" id="menu_link">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Link</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnUpdateMenuEntry" onclick="updateMenuEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveMenuEntry" onclick="saveMenuEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Access Modal =============== -->
            <div class="modal fade" id="grantAccessModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-7">
                            <h4 class="modal-title text-uppercase fw-bold text-light">GRANT ACCESS</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-3">
                                <select class="form-select fw-bold" id="access_app_name" onclick="loadGrantAccessMenu();">
                                    <option value="">Choose...</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Application Name</label>
                            </div>
                            <div class="row">
                                <div id="grant_access_menu"></div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnSaveAccess" onclick="saveAccess();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col btnCloseAccess" data-bs-dismiss="modal" onclick="clearValues('grantAccess');"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
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
            <div class="card card-7 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">ADMINISTRATOR</span>
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
    let prevIndexDepartment = '';
    let prevIndexJobTitle = '';
    let prevIndexEmployee = '';
    let prevIndexAccessApp = '';

    loadAppTable();
    loadMenuTable();
    loadSelectValueWithId('bpi_department', 'dept_code', 'department', 'menu_department', 'banner_web_live'); //* Load Department

    const swiper = new Swiper('.swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        freeMode: true,
        grabCursor: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 50,
            },
        }
    });

    function loadAppTable() {
        var appList_table = $('#appList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'serverSide': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/admin_menu_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_application_table_data'
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
                targets: [0, 1],
                className: 'dt-body-middle-left',
                width: '45%'
            }, {
                targets: 2,
                className: 'dt-nowrap-center',
                width: '10%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditApplication" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Application" onclick="modifyApplication(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteApplication" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Application" onclick="deleteApplication(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        setInterval(function() {
            appList_table.ajax.reload(null, false);
        }, 30000); //* ======== Reload Table Data Every X seconds with pagination retained ========
    }

    function addApplicationModal() {
        $('#addUpdateApplicationEntryModal').modal('show');
        $('#app_modal_title').html('Application Entry');
        $('.btnUpdateAppEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveAppEntry').prop('disabled', false).css('display', 'block');
    }

    function saveAppEntry() {
        if (inputValidation('application_name', 'application_link')) {
            $.ajax({
                url: 'functions/admin_menu_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_application',
                    app_name: $('#application_name').val(),
                    app_link: $('#application_link').val()
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Application Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearAttributes();
                        $('#application_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Application Successfully Save.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearValues();
                        $('#appList_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function modifyApplication(appid) {
        $('#addUpdateApplicationEntryModal').modal('show');
        $('#app_modal_title').html('Application Update');
        $('.btnUpdateAppEntry').val(appid);
        $('.btnUpdateAppEntry').prop('disabled', false).css('display', 'block');
        $('.btnSaveAppEntry').prop('disabled', true).css('display', 'none');

        $.ajax({
            url: 'functions/admin_menu_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_app_info',
                appid: appid
            },
            success: function(result) {
                $('#application_name').val(result.app_name);
                $('#application_link').val(result.app_link);
            }
        });
    }

    function updateAppEntry(appid) {
        if (inputValidation('application_name', 'application_link')) {
            $.ajax({
                url: 'functions/admin_menu_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_application',
                    app_name: $('#application_name').val(),
                    app_link: $('#application_link').val(),
                    appid: appid
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Application Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#application_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Application Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#appList_table').DataTable().ajax.reload(null, false);
                    }
                    clearAttributes();
                }
            });
        }
    }

    function deleteApplication(appid) {
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
                    url: 'functions/admin_menu_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'remove_application',
                        appid: appid
                    },
                    success: function(result) {
                        if (result == 'success') {
                            $('#appList_table').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                'Deleted!',
                                'Application deleted.',
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
        });
    }

    function loadMenuTable() {
        var menuList_table = $('#menuList_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'serverSide': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/admin_menu_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load_menu_table_data'
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
                targets: [0, 1, 2],
                className: 'dt-body-middle-left',
                width: '30%'
            }, {
                targets: 3,
                className: 'dt-nowrap-center',
                width: '10%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditMenu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Menu" onclick="modifyMenu(${data});"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteMenu" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Menu" onclick="deleteMenu(${data});"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        setInterval(function() {
            menuList_table.ajax.reload(null, false);
        }, 30000); //* ======== Reload Table Data Every X seconds with pagination retained ========
    }

    function addMenuModal() {
        $('#addUpdateMenuModal').modal('show');
        $('#menu_modal_title').html('Menu Entry');
        loadSelectValueWithId('bpi_app_menu', 'appid', 'app_name', 'menu_app_name', 'banner_web_live'); //* Load Application
        $('.btnUpdateMenuEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveMenuEntry').prop('disabled', false).css('display', 'block');
    }

    function saveMenuEntry() {
        if (inputValidation('menu_app_name', 'menu_title')) {
            $.ajax({
                url: 'functions/admin_menu_module_functions.php',
                type: 'POST',
                data: {
                    action: 'save_menu',
                    app_id: $('#menu_app_name').val(),
                    app_menu_title: $('#menu_title').val(),
                    app_menu_link: $('#menu_link').val(),
                    app_menu_parent_id: $('#menu_parent_menu').val()
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Link Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearAttributes();
                        $('#menu_title').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Menu Successfully Save.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearValues();
                        $('#menuList_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function modifyMenu(appmenuid) {
        $('#addUpdateMenuModal').modal('show');
        $('#menu_modal_title').html('Menu Update');
        $('.btnUpdateMenuEntry').val(appmenuid);
        $('.btnUpdateMenuEntry').prop('disabled', false).css('display', 'block');
        $('.btnSaveMenuEntry').prop('disabled', true).css('display', 'none');
        loadSelectValueWithId('bpi_app_menu', 'appid', 'app_name', 'menu_app_name', 'banner_web_live'); //* Load Application

        $.ajax({
            url: 'functions/admin_menu_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_menu_info',
                appmenuid: appmenuid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#menu_app_name').val(result.app_id);
                }, 100);
                $('#menu_title').val(result.app_menu_title);
                $('#menu_link').val(result.app_menu_link);
                if (result.app_menu_parent_id == '0') {
                    $('#chkParentMenu').prop('checked', true);
                } else {
                    $('#chkSubParent').prop('checked', true);
                    setTimeout(function() {
                        loadSubParentMenu();
                    }, 100);
                    setTimeout(function() {
                        $('#menu_parent_menu').val(result.app_menu_parent_id);
                    }, 500);
                }
            }
        });
    }

    function updateMenuEntry(appmenuid) {
        if (inputValidation('menu_app_name', 'menu_title')) {
            $.ajax({
                url: 'functions/admin_menu_module_functions.php',
                type: 'POST',
                data: {
                    action: 'update_menu',
                    app_id: $('#menu_app_name').val(),
                    app_menu_title: $('#menu_title').val(),
                    app_menu_link: $('#menu_link').val(),
                    app_menu_parent_id: $('#menu_parent_menu').val(),
                    appmenuid: appmenuid
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Link Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearAttributes();
                        $('#menu_title').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Menu Successfully Save.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        clearValues();
                        $('#menuList_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function deleteMenu(appmenuid) {
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
                    url: 'functions/admin_menu_module_functions.php',
                    type: 'POST',
                    data: {
                        action: 'remove_menu',
                        appmenuid: appmenuid
                    },
                    success: function(result) {
                        if (result == 'success') {
                            $('#menuList_table').DataTable().ajax.reload(null, false);
                            Swal.fire(
                                'Deleted!',
                                'Menu deleted.',
                                'success'
                            )
                        } else {
                            Swal.fire(
                                'Warning!',
                                'Cannot Delete Menu. Currently In Use or Still Assigned',
                                'info'
                            )
                        }
                    }
                });
            }
        });
    }

    function loadJobTitle() {
        let currIndex = document.getElementById('menu_department').selectedIndex;
        let currVal = document.getElementById('menu_department').options;
        if (currIndex > 0) {
            if (prevIndexDepartment != currIndex) { //* ======== Toggle same Selection ========
                let dept_code = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/admin_menu_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_job_title',
                        dept_code: dept_code
                    },
                    success: function(result) {
                        $("#menu_job_title").empty();
                        setTimeout(function() {
                            optionText = "Choose...";
                            optionValue = "";
                            let optionExists = ($(`#menu_job_title option[value="${optionValue}"]`).length > 0);
                            if (!optionExists) {
                                $('#menu_job_title').append(`<option value="${optionValue}">${optionText}</option>`);
                            }

                            if (result.pos_code != 'empty') {
                                $.each(result, (key, value) => {
                                    var optionExists = ($(`#menu_job_title option[value="${key}"]`).length > 0);
                                    if (!optionExists) {
                                        $('#menu_job_title').append(`<option value="${key}">${value}</option>`);
                                    }
                                });
                            }
                        }, 100);
                    }
                });
                prevIndexDepartment = currIndex;
            } else {
                prevIndexDepartment = '';
                prevIndexJobTitle = '';
                prevIndexEmployee = '';
            }
        }
    }

    function loadEmployee() {
        let currIndex = document.getElementById('menu_job_title').selectedIndex;
        let currVal = document.getElementById('menu_job_title').options;
        if (currIndex > 0) {
            if (prevIndexJobTitle != currIndex) { //* ======== Toggle same Selection ========
                let pos_code = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/admin_menu_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_employee',
                        pos_code: pos_code,
                        dept_code: $('#menu_department').val()
                    },
                    success: function(result) {
                        $("#menu_employee").empty();
                        setTimeout(function() {
                            optionText = "Choose...";
                            optionValue = "";
                            let optionExists = ($(`#menu_employee option[value="${optionValue}"]`).length > 0);
                            if (!optionExists) {
                                $('#menu_employee').append(`<option value="${optionValue}">${optionText}</option>`);
                            }

                            if (result.empno != 'empty') {
                                $.each(result, (key, value) => {
                                    let optionExists = ($(`#menu_employee option[value="${key}"]`).length > 0);
                                    if (!optionExists) {
                                        $('#menu_employee').append(`<option value="${key}">${value}</option>`);
                                    }
                                });
                            }
                        }, 100);
                    }
                });
                prevIndexJobTitle = currIndex;
            } else {
                prevIndexJobTitle = '';
                prevIndexEmployee = '';
            }
        }
    }

    function load_user_access() {
        let currIndex = document.getElementById('menu_employee').selectedIndex;
        let currVal = document.getElementById('menu_employee').options;
        let html = '';
        if (currIndex > 0) {
            if (prevIndexEmployee != currIndex) { //* ======== Toggle same Selection ========
                let empno = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/admin_menu_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_user_access',
                        empno: empno
                    },
                    success: function(result) {
                        if (result.length != '0') {
                            $.each(result, (app_name, app_menu) => {
                                html += '<div class="swiper-slide access_user_card">';
                                html += '<div class="access_user_title">';
                                html += '<div class="access_user_overlay">';
                                html += '<h4>' + app_name + '</h4>';
                                html += '</div>';
                                html += '</div>';
                                html += '<div class="user_access_card_content">';
                                html += '<ul>';
                                $.each(app_menu, (app_menu_index, app_menu_value) => {
                                    html += '<li>' + app_menu_value + '</li>';
                                });
                                html += '</ul>';
                                html += '</div>';
                                html += '</div>';
                            });
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'info',
                                title: 'No Access Rights to Show',
                                text: '',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            $('#menu_job_title').focus();
                        }
                        $('.card-wrapper').html(html);
                    }
                });
                prevIndexEmployee = currIndex;
            } else {
                prevIndexEmployee = '';
                $('.card-wrapper').html(html);
            }
        }
    }

    function grantAccess() {
        if (inputValidation('menu_department', 'menu_job_title', 'menu_employee')) {
            $('#grantAccessModal').modal('show');
            loadSelectValueWithId('bpi_app_menu', 'appid', 'app_name', 'access_app_name', 'banner_web_live'); //* Load Application
        }
        setTimeout(function() {
            clearAttributes();
        }, 1500);
    }

    function loadGrantAccessMenu() {
        let currIndex = document.getElementById('access_app_name').selectedIndex;
        let currVal = document.getElementById('access_app_name').options;
        let appMenuData;
        if (currIndex > 0) {
            if (prevIndexAccessApp != currIndex) { //* ======== Toggle same Selection ========
                let appid = currVal[currIndex].value;
                $.ajax({
                    url: 'functions/admin_menu_module_functions.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_grant_access_menu',
                        appid: appid
                    },
                    success: function(result) {
                        $("#grant_access_menu").jstree("destroy");

                        if (result != 'empty') {
                            $('#grant_access_menu').jstree({
                                'plugins': ["wholerow", "checkbox"],
                                'core': {
                                    'data': result
                                }
                            });
                            $.ajax({
                                url: 'functions/admin_menu_module_functions.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    action: 'load_access_rigths',
                                    appid: appid,
                                    empno: $('#menu_employee').val()
                                },
                                success: function(result) {
                                    $.each(result, (key, value) => {
                                        $('#grant_access_menu').jstree('select_node', value)
                                    });
                                }
                            });
                        }
                    }
                });
                prevIndexAccessApp = currIndex;
            } else {
                prevIndexAccessApp = '';
            }
        }
    }

    function saveAccess() {
        let strMenuId = $('#grant_access_menu').jstree('get_selected', true);
        $('.btnSaveAccess').prop('disabled', true);
        $('.btnCloseAccess').prop('disabled', true);

        $.ajax({
            url: 'functions/admin_menu_module_functions.php',
            type: 'POST',
            data: {
                action: 'delete_access_rights',
                app_id: $('#access_app_name').val(),
                access_user: $('#menu_employee').val()
            },
            success: function(result) {
                for (let i = 0; i < strMenuId.length; i++) {
                    selectedNode = strMenuId[i];
                    var appmenuid = selectedNode.id;
                    $.ajax({
                        url: 'functions/admin_menu_module_functions.php',
                        type: 'POST',
                        data: {
                            action: 'save_access_rigths',
                            appmenuid: appmenuid,
                            app_id: $('#access_app_name').val(),
                            access_user: $('#menu_employee').val()
                        }
                    });
                    if (i == strMenuId.length - 1) {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Access Granted.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('.btnSaveAccess').prop('disabled', false);
                        $('.btnCloseAccess').prop('disabled', false);
                    }
                }
            }
        });
    }

    $('#chkParentMenu').click(function() {
        if (this.checked == true) {
            $('#menu_parent_menu').prop('disabled', true);
            $("#menu_parent_menu").empty();
            setTimeout(function() {
                optionText = "Choose...";
                optionValue = "0";
                let optionExists = ($(`#menu_parent_menu option[value="${optionValue}"]`).length > 0);
                if (!optionExists) {
                    $('#menu_parent_menu').append(`<option value="${optionValue}">${optionText}</option>`);
                }
            }, 100);
        }
    });

    $('#chkSubParent').click(function() {
        loadSubParentMenu();
    });

    function loadSubParentMenu() {
        $('#menu_parent_menu').prop('disabled', false);
        $.ajax({
            url: 'functions/admin_menu_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_parent_menu',
                app_id: $('#menu_app_name').val()
            },
            success: function(result) {
                $("#menu_parent_menu").empty();
                setTimeout(function() {
                    optionText = "Choose...";
                    optionValue = "0";
                    let optionExists = ($(`#menu_parent_menu option[value="${optionValue}"]`).length > 0);
                    if (!optionExists) {
                        $('#menu_parent_menu').append(`<option value="${optionValue}">${optionText}</option>`);
                    }

                    if (result.appmenuid != 'empty') {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#menu_parent_menu option[value="${key}"]`).length > 0);
                            if (!optionExists) {
                                $('#menu_parent_menu').append(`<option value="${key}">${value}</option>`);
                            }
                        });
                    }
                }, 100);
            }
        });
    }

    function clearValues(val) {
        if (val == 'grantAccess') {
            $("#grant_access_menu").jstree("destroy");
            $('#access_app_name').find('option:first').prop('selected', 'selected');
            $('#menu_employee').find('option:first').prop('selected', 'selected');
            prevIndexAccessApp = '';
            prevIndexEmployee = '';
        } else {
            prevIndexDepartment = '';
            prevIndexJobTitle = '';
            $('input').val('');
            $('select').find('option:first').prop('selected', 'selected');
            $('#chkParentMenu').prop('checked', true);
            $('#chkSubParent').prop('checked', false);
            $('#menu_parent_menu').prop('disabled', true);
            $("#menu_parent_menu").empty();
            setTimeout(function() {
                optionText = "Choose...";
                optionValue = "0";
                let optionExists = ($(`#menu_parent_menu option[value="${optionValue}"]`).length > 0);
                if (!optionExists) {
                    $('#menu_parent_menu').append(`<option value="${optionValue}">${optionText}</option>`);
                }
            }, 100);
            $('.card-wrapper').html('');
        }
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>