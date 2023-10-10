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
session_start();
$emp_no = $_SESSION['empno'];
$sqlstring = "SELECT * FROM prl_employee WHERE empno = '" . $emp_no . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_Res = $result_stmt->fetch(PDO::FETCH_ASSOC);
$fullname = $result_Res['emp_fn'] . ' ' . $result_Res['emp_sn'];
?>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />
<div class="container-fluid">
    <div class="row">
        <div class="col content scroll_color_admin overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-admin">Announcement Module</span>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header card-7 py-3 d-flex flex-row justify-content-between">
                            <h6 class="h4 m-0 fw-bold text-light">Announcement</h6>
                            <button class="btn btn-light fw-bold fs-18" onclick="announceModal();"><i class="fa-solid fa-plus p-r-8"></i> Add Announcement</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="announcement_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="custom_table_header_color_admin">
                                            <tr>
                                                <th>Date</th>
                                                <th>Announce By</th>
                                                <th>Header</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="custom_table_header_color_admin">
                                            <tr>
                                                <th>Date</th>
                                                <th>Announce By</th>
                                                <th>Header</th>
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
            <div class="modal fade" id="announceModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content ">
                        <div class="modal-header ">
                            <h4 class="modal-title fw-bold custom_table_text_color_admin w-100 text-center">Create Post</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearValues();"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3" id="profile_image_division">
                                <div id="announcement_profile_image"></div>
                                <span class="fw-bold announcement_username">
                                    <?php echo $fullname ?>
                                </span>
                            </div>
                            <div class="row mb-1">
                                <div class="col-sm">
                                    <textarea name="" id="header_announce" rows="1" class="form-control fw-bold autoAdjustHead" onkeyup="stoppedTyping();" placeholder="Subject" style="border: none;  overflow: hidden; resize: none; background: transparent;"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <textarea name="" id="body_announce" rows="2" class="form-control fw-bold autoAdjust" placeholder="What's on your mind, <?php echo $fullname ?>?" style="border: none; overflow: hidden; resize: none; background: transparent;" required></textarea>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3" id="upload_div_image">
                                <div class="col" id="user_image_division"></div>
                            </div>
                            <div class="row box mt-3">
                                <div class="col-sm-9">
                                    <textarea name="" id="test" class="form-control fw-bold mt-2" style="border: none; border: 0; box-shadow: none; overflow: hidden; resize: none; cursor: default;border: none; outline: none;" cols="30" rows="1" class="fw-bold" placeholder="Add to your post"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <div class="post_icon_image">
                                        <label for="user_image" class="col-form-label text-success" style="font-size: 30px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Photo/Video"><i class="fa-regular fa-images"></i></label>
                                        <input type="file" id="user_image" name="user_image" style="display: none; visibility: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <button type="button" id="start_button" class="btn btn-secondary col-sm-11 fw-bold btn-save" onclick="btnAnnounceSave();" disabled>Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content ">
                        <div class="modal-header ">
                            <h4 class="modal-title fw-bold custom_table_text_color_admin w-100 text-center"> Post</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearValues();"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm">
                                    <textarea name="" id="prev_header" rows="1" class="form-control fw-bold autoAdjustUpdate" placeholder="Subject" style="border: none;  overflow: hidden; resize: none; background: transparent;"></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm">
                                    <textarea name="" id="prev_body" rows="1" class="form-control fw-bold autoAdjustHeadUpdate" style="border: none;  overflow: hidden; resize: none; background: transparent;"></textarea>
                                </div>
                            </div>
                            <div class="row box mt-3">
                                <div class="col-sm-9">
                                    <textarea name="" id="test2" class="form-control fw-bold mt-2" style="border: none; border: 0; box-shadow: none; overflow: hidden; resize: none; cursor: default;border: none; outline: none;" cols="30" rows="1" class="fw-bold" placeholder="Add to your post"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <div class="post_icon_image">
                                        <label for="user_image_preview" class="col-form-label text-success" style="font-size: 30px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Photo/Video"><i class="fa-regular fa-images"></i></label>
                                        <input type="file" id="user_image_preview" name="user_image" style="display: none; visibility: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3" id="">
                                <div class="col" id="preview_image_division"></div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <button type="button" id="update_button" class="btn btn-success col-sm-11 fw-bold" onclick="btnAnnounceUpdate();">Update</button>
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
            <div class="card card-7 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">Administrator</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    // ======================= Enable Tooltip =======================
    const textarea = document.querySelector(".autoAdjust");
    textarea.addEventListener("keyup", e => {
        textarea.style.height = "auto";
        let scHeight = e.target.scrollHeight;
        textarea.style.height = `${scHeight}px`;
    });
    const textareahead = document.querySelector(".autoAdjustHead");
    textareahead.addEventListener("keyup", e => {
        textareahead.style.height = "auto";
        let scHeight = e.target.scrollHeight;
        textareahead.style.height = `${scHeight}px`;
    });
    const textareaupdate = document.querySelector(".autoAdjustUpdate");
    textareaupdate.addEventListener("keyup", e => {
        textareaupdate.style.height = "auto";
        let scHeight = e.target.scrollHeight;
        textareaupdate.style.height = `${scHeight}px`;
    });
    const textareaheadupdate = document.querySelector(".autoAdjustHeadUpdate");
    textareaheadupdate.addEventListener("keyup", e => {
        textareaheadupdate.style.height = "auto";
        let scHeight = e.target.scrollHeight;
        textareaheadupdate.style.height = `${scHeight}px`;
    });

    $(document).ready(function() {
        $('#test').prop('readonly', true);
        $('#test2').prop('readonly', true);
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="tooltip"]').on('click', function() { // =========== Hide tooltip upon click ===========
            $(this).tooltip('hide');
        });
    });
    // ======================= Enable Tooltip End =======================
    let announcement_table;
    loadAnnounceTable();

    function stoppedTyping() {
        let header_announce = document.getElementById('header_announce').value;
        if (header_announce.length > 0) {
            $('.btn-save').addClass('btn-primary').removeClass('btn-secondary');
            document.getElementById('start_button').disabled = false;
        } else {
            $('.btn-save').addClass('btn-secondary').removeClass('btn-primary');
            document.getElementById('start_button').disabled = true;
        }
    }

    function loadAnnounceTable() {
        announcement_table = $('#announcement_table').DataTable({
            'serverSide': true,
            'paging': true,
            'autoWidth': false,
            'responsive': true,
            'ajax': {
                url: 'functions/announcement_module_functions.php',
                type: 'POST',
                data: {
                    action: 'loadAnnounceTable'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '15%',
            }, {
                targets: 1,
                className: 'dt-body-middle-left',
                width: '40%',
            }, {
                targets: 2,
                className: 'dt-body-middle-left',
                width: '40%',
                orderable: false
            }, {
                targets: 3,
                className: 'dt-nowrap-center',
                width: '5%',
                orderable: false
            }],
            'drawCallback': function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("[id^='tooltip']").tooltip('hide'); // =========== Hide tooltip every table draw ===========
            }
        });
        setInterval(function() {
            announcement_table.ajax.reload(null, false);
        }, 30000); // ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function announceModal() {
        $('#announceModal').modal('show');
        $('.btn-update').css('display', 'none');

        // $('#user_image_division').html('<img src="vendor/images/blank-profile-picture.png" alt="" id="upload_image">');
    }
    loadProfile();

    function loadProfile() {
        $.ajax({
            url: 'functions/announcement_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'loadProfileFunction',
                username: '<?= $emp_no ?>'
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#announcement_profile_image').html(data.image);
            }
        });
    }

    function btnAnnounceSave() {
        // let by = document.getElementById('by').value;
        let header_announce = document.getElementById('header_announce').value;
        let body_announce = document.getElementById('body_announce').value;
        let user_image_val = document.getElementById('user_image').value;
        let user_image = $('#upload_image').attr('value'); // image container
        if (user_image_val.length == 0) {
            saveFunction(header_announce, body_announce, user_image);
        } else {
            // =========== Validate if File Uploaded is Image ===========
            let image_property = document.getElementById('user_image').files[0]; // input file
            let image_name = image_property.name;
            let image_size = Math.round(image_property.size / 1024) + " MB";
            let image_extension = image_name.split('.').pop().toLowerCase();
            if (jQuery.inArray(image_extension, ['gif', 'jpg', 'jpeg', '']) == -1) {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Image',
                    text: 'Invalid Image File',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                saveFunction(header_announce, body_announce, user_image);
            }
        }
    }

    function saveFunction(header_announce, body_announce, user_image) {
        $.ajax({
            url: 'functions/announcement_module_functions.php',
            type: 'POST',
            data: {
                action: 'btnAnnounceSaveFunction',
                header_announce: header_announce,
                body_announce: body_announce,
                user_image: user_image,
                by: '<?= $emp_no ?>'
            },
            success: function(result) {
                announcement_table.ajax.reload(null, false);
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Save Succesfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('.btn-save').prop('disabled', true);
                $('.btn-save').addClass('btn-secondary').removeClass('btn-primary');
                clearValues();
                // $('#user_image_division').html('<img src="vendor/images/blank-profile-picture.png" alt="" id="upload_image">');
            }
        });
    }
    let previewVal = '';

    function btnPreview(id) {
        previewVal = id;
        $.ajax({
            url: 'functions/announcement_module_functions.php',
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            data: {
                action: 'btnPreviewFunctions',
                id: id
            },
            success: function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $('#prev_header').val(data.announce_header);
                $('#prev_body').val(data.announce_body);
                $('#preview_image_division').html(data.image);
            }
        });
        $('#previewModal').modal('show');
        $('#preview_image_division').html('<img src="vendor/images/blank-profile-picture.png" alt="" id="upload_image">');
    }

    function btnAnnounceUpdate() {
        let prev_header = document.getElementById('prev_header').value;
        let prev_body = document.getElementById('prev_body').value;
        let user_image_val = document.getElementById('user_image_preview').value;
        let user_image = $('#upload_image').attr('value'); // image container
        if (user_image_val.length == 0) {
            updateFunction(prev_header, prev_body, user_image);
        } else {
            // =========== Validate if File Uploaded is Image ===========
            let image_property = document.getElementById('user_image_preview').files[0]; // input file
            let image_name = image_property.name;
            let image_size = Math.round(image_property.size / 1024) + " MB";
            let image_extension = image_name.split('.').pop().toLowerCase();
            if (jQuery.inArray(image_extension, ['gif', 'jpg', 'jpeg', '']) == -1) {
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Image',
                    text: 'Invalid Image File',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                updateFunction(prev_header, prev_body, user_image);
            }
        }
    }

    function updateFunction(prev_header, prev_body, user_image) {
        $.ajax({
            url: 'functions/announcement_module_functions.php',
            type: 'POST',
            data: {
                action: 'btnAnnounceUpdateFunction',
                prev_header: prev_header,
                prev_body: prev_body,
                user_image: user_image,
                previewVal: previewVal
            },
            success: function(result) {
                announcement_table.ajax.reload(null, false);
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Updated Succesfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // $('#user_image_division').html('<img src="vendor/images/blank-profile-picture.png" alt="" id="upload_image">');
            }
        });
    }

    function btnDelete(id) {
        $.ajax({
            url: 'functions/announcement_module_functions.php',
            type: 'POST',
            data: {
                action: 'btnDeleteFunction',
                id: id
            },
            success: function(result) {
                announcement_table.ajax.reload(null, false);
            }
        });
    }

    function btnClose() {
        $('#announceModal').modal('hide');
    }

    // =========== Read Image and Display Courier Entry ===========
    $('#user_image').on('change', function() {
        let reader = new FileReader();
        reader.onload = function(e) {
            $.ajax({
                url: 'functions/announcement_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load-image-base64',
                    'image': e.target.result
                },
                success: function(result) {
                    $('#user_image_division').html('<img src="' + e.target.result + '" alt="" value="' + result + '" id="upload_image">');
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });
    // =========== Preview Read Image and Display Courier Entry ===========
    $('#user_image_preview').on('change', function() {
        let reader = new FileReader();
        reader.onload = function(e) {
            $.ajax({
                url: 'functions/announcement_module_functions.php',
                type: 'POST',
                data: {
                    action: 'load-image-base64',
                    'image': e.target.result
                },
                success: function(result) {
                    $('#preview_image_division').html('<img src="' + e.target.result + '" alt="" value="' + result + '" id="upload_image">');
                }
            });
        }
        reader.readAsDataURL(this.files[0]);
    });

    function clearValues() {
        $('input').val('');
        $('textarea').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $('#user_image_division').html("");
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