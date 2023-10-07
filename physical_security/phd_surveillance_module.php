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
                <span class="page-title-physical">Surveillance Module</span>
            </div>
            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col">
                    <div class="col-xl-12">
                        <div class="card shadow">
                            <div class="card-header card-2 py-3">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <h4 class="fw-bold text-light">Surveillance List</h4>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <button class="btn btn-light fw-bold fs-18" onclick="suveillanceModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Surveillance</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="surveillance_table" class="table table-bordered table-striped fw-bold" width="100%">
                                            <thead class="custom_table_header_color_physical">
                                                <tr>
                                                    <th>Surveillance Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="custom_table_header_color_physical">
                                                <tr>
                                                    <th>Surveillance Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="addSurveillanceModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header card-2">
                                            <h4 class="modal-title text-uppercase fw-bold text-light add-assign-header"> Add Assign</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-floating mb-3">
                                                <input type="text" id="surveillance_name" class="form-control fw-bold">
                                                <div class="invalid-feedback"></div>
                                                <label for="surveillance_name" class="fw-bold">Surveillance Name:</label>
                                            </div>
                                            <div class="d-grid gap-2 col-sm-12 mx-auto mb-2">
                                                <button type="button" class="btn btn-warning btn-sm fw-bold btn-update" style="border-radius: 20px;" onclick="updateFunc(this.value);"><i class="fa-solid fa-pen-to-square fa-bounce p-r-8"></i>Update</button>
                                                <button type="button" class="btn btn-success btn-sm fw-bold btn-assign-save" style="border-radius: 20px;" onclick="saveSurveillanceName();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                                                <button type="button" class="btn btn-danger btn-sm fw-bold" style="border-radius: 20px;" onclick="closeFunc();"><i class="fa-solid fa-xmark p-r-8"></i>Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    loadSurveillanceTable();

    function loadSurveillanceTable() {
        var surveillance_table = $('#surveillance_table').DataTable({
            'lengthMenu': [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/phd_controller/phd_surveillance_module_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_surveillance_table'
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
                    return `<button type="button" class="btn btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Preview" onclick="btnPreview('${data}');"><i class="fa-regular fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="btnDeleteSurveillance('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`
                }
            }]
        });
        surveillance_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            $('[id^="tooltip"]').remove(); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            surveillance_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function btnPreview(id) {
        $('.btn-update').css('display', 'block');
        $('.btn-assign-save').css('display', 'none');
        $('#addSurveillanceModal').modal('show');
        $('.btn-update').val(id);
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'preview_data',
                id: id
            },
            success: function(result) {
                $('#surveillance_name').val(result.result);
                // alert(result.result);
            }
        })
    }

    function updateFunc(id) {
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'update_data',
                surveillance_name: $('#surveillance_name').val(),
                id: id
            },
            success: function(result) {
                if (result.result == 'exist') {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Data Exist!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Update Succesfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    refreshProcessTable();
                }
            }
        })
    }

    function suveillanceModal() {
        $('#addSurveillanceModal').modal('show');
        $('.btn-update').css('display', 'none');
        $('.btn-assign-save').css('display', 'block');
    }

    function btnDeleteSurveillance(id) {
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
                    url: '../controller/phd_controller/phd_surveillance_module_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'delete_data',
                        id: id
                    },
                    success: function(result) {}
                })
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
            refreshProcessTable();
        });
    }

    function saveSurveillanceName() {
        $.ajax({
            url: '../controller/phd_controller/phd_surveillance_module_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'save_data',
                surveillance_name: $('#surveillance_name').val()
            },
            success: function(result) {
                if (result.result == 'exist') {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Data Exist!',
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
                }
            }
        })
    }

    function closeFunc() {
        $('#addSurveillanceModal').modal('hide');
        clearValues();
    }

    function refreshProcessTable() {
        $('#surveillance_table').DataTable().ajax.reload(null, false);
    }

    function clearValues() {
        $('input').val('');
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