<?php include './../includes/header.php';
session_start();
$BannerWebLive = $conn->db_conn_bannerweb(); //* BannerWeb Database connection
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$queryCheckApp = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$stmtCheckApp = $BannerWebLive->prepare($queryCheckApp);
$stmtCheckApp->execute();
while ($chkAppIdRow = $stmtCheckApp->fetch(PDO::FETCH_ASSOC)) {
    $chkAppId = $chkAppIdRow['app_id'];
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
                <span class="page-title-perso">Material Entry</span>
            </div>
            <div class="row mt-5">
                <div class="col-sm-6 mx-auto">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="fw-bold text-light align-content-center" id="process_division_title">Material List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addMaterialModal();"><i class="fa-solid fa-square-plus p-r-8"></i> Add Material</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="materialList_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Material Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Material Name</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Material List Section End =============== -->
            <!-- =============== Add Material Modal =============== -->
            <div class="modal fade" id="addUpdateMaterialModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4">
                            <h4 class="modal-title text-uppercase fw-bold text-light">New Material</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type="text" class="form-control fw-bold" id="material_name">
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Material Name</label>
                            </div>
                            <div class="form-floating mt-3">
                                <select class="form-select fw-bold" id="material_section">
                                    <option value="">Choose ...</option>
                                    <option value="Sticker Section">Sticker Section</option>
                                    <option value="Carrier Section">Carrier Section</option>
                                    <option value="Sim Pairing Section">Sim Pairing Section</option>
                                    <option value="Waybill Section">Waybill Section</option>
                                    <option value="Logsheet Checklist Section">Logsheet Checklist Section</option>
                                    <option value="Data Preparation Section">Data Preparation Section</option>
                                    <option value="Card and Form Section">Card and Form Section</option>
                                    <option value="Collateral Section">Collateral Section</option>
                                </select>
                                <div class="invalid-feedback"></div>
                                <label class="fw-bold">Material Section</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success btnSaveMaterial" onclick="saveMaterial();"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-success btnUpdateMaterial" onclick="updateMaterial(this);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Update</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Add Material Modal End =============== -->

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
?>
<script>
    loadMaterialListTable();

    function loadMaterialListTable() {
        var materialList_table = $('#materialList_table').DataTable({
            'serverSide': true,
            'processing': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/perso_material_entry_functions.php',
                type: 'POST',
                data: {
                    action: 'load_material_list_table'
                }
            },
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); //* =========== Hide tooltip every table draw ===========
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left'
            }, {
                targets: 1,
                className: 'dt-nowrap-center',
                width: '20%',
                orderable: false
            }]
        });
        setInterval(function() {
            materialList_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addMaterialModal() {
        $('#addUpdateMaterialModal').modal('show');
        $('.btnSaveMaterial').prop('disabled', false);
        $('.btnSaveMaterial').css('display', 'block');
        $('.btnUpdateMaterial').prop('disabled', true);
        $('.btnUpdateMaterial').css('display', 'none');
    }

    function saveMaterial() {
        if (submitValidation()) {
            var strMaterialName = document.getElementById('material_name').value;
            var strMaterialSection = document.getElementById('material_section').value;

            $.ajax({
                url: 'functions/perso_material_entry_functions.php',
                type: 'POST',
                data: {
                    action: 'save_material',
                    strMaterialName: strMaterialName,
                    strMaterialSection: strMaterialSection
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Material Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        clearAttributes();
                        $('#material_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Material Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        clearValues();
                        $('#materialList_table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }
    }

    function modifyMaterial(materialid) {
        $('#addUpdateMaterialModal').modal('show');
        $('.btnSaveMaterial').prop('disabled', true);
        $('.btnSaveMaterial').css('display', 'none');
        $('.btnUpdateMaterial').prop('disabled', false);
        $('.btnUpdateMaterial').css('display', 'block');
        $('.btnUpdateMaterial').val(materialid);

        $.ajax({
            url: 'functions/perso_material_entry_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'load_material_information',
                materialid: materialid
            },
            success: function(result) {
                $('#material_name').val(result.materialname);
                $('#material_section').val(result.materialSection);
            }
        });
    }

    function updateMaterial(val) {
        if (submitValidation()) {
            var materialid = val.value;
            var strMaterialName = document.getElementById('material_name').value;
            var strMaterialSection = document.getElementById('material_section').value;

            $.ajax({
                url: 'functions/perso_material_entry_functions.php',
                type: 'POST',
                data: {
                    action: 'update_material',
                    materialid: materialid,
                    strMaterialName: strMaterialName,
                    strMaterialSection: strMaterialSection
                },
                success: function(result) {
                    if (result == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Material Name Already Exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#material_name').focus();
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Material Successfully Updated.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#materialList_table').DataTable().ajax.reload(null, false);
                    }
                    clearAttributes();
                }
            });
        }
    }

    function deleteMaterial(materialid) {
        $.ajax({
            url: 'functions/perso_material_entry_functions.php',
            type: 'POST',
            data: {
                action: 'delete_material_name',
                materialid: materialid
            },
            success: function(result) {
                $('#materialList_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }

    function submitValidation() {
        var isValidated = true;
        var strMaterialName = document.getElementById('material_name').value;
        var strMaterialSection = document.getElementById('material_section').value;

        if (strMaterialName.length == 0) {
            showFieldError('material_name', 'Material Name must not be blank');
            if (isValidated) {
                $('#material_name').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('material_name');
        }

        if (strMaterialSection.length == 0) {
            showFieldError('material_section', 'Material Section must not be blank');
            if (isValidated) {
                $('#material_section').focus();
            }
            isValidated = false;
        } else {
            clearFieldError('material_section');
        }
        return isValidated;
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
        $('input[type=text]').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>