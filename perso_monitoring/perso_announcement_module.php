<?php include './../includes/header.php';
session_start();
// * Check if module is within the application
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
            <div class="row">
                <span class="page-title-perso">Perso Announcement</span>
            </div>
            <!-- content section -->
            <div class="row mt-5">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <span class="fw-bold fs-27 text-light">Announcement List</span>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addAnnouncementModal();"><i class="fa-solid fa-paper-plane fa-bounce p-r-8"></i> New Announcement Entry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="announcement_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date</th>
                                            <th>Announce By</th>
                                            <th>Subject</th>
                                            <th>Details</th>
                                            <th>Announce To</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date</th>
                                            <th>Announce By</th>
                                            <th>Subject</th>
                                            <th>Details</th>
                                            <th>Announce To</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header card-4 py-3">
                            <div class="row">
                                <span class="fw-bold fs-27 text-light">Announcement History</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="announcement_history_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th>Announce By</th>
                                            <th>Subject</th>
                                            <th>Details</th>
                                            <th style="text-align:center;">Announce To</th>
                                            <th style="text-align:center;">Acknowledge Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderAdmin">
                                        <tr>
                                            <th style="text-align:center;">Date Entry</th>
                                            <th>Announce By</th>
                                            <th>Subject</th>
                                            <th>Details</th>
                                            <th style="text-align:center;">Announce To</th>
                                            <th style="text-align:center;">Acknowledge Date</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- =============== Add Announcement Modal =============== -->
            <div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-4 d-flex justify-content-center">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="announcement_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div id="recipients_section">
                                        <div class="input-group mb-3">
                                            <button type="button" class="btn btn-dark" data-bs-toggle="dropdown" data-bs-toggle="tooltip"><i class="fa-solid fa-square-plus"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-dark scrollable_dropdown_menu" id="dropdown_section_list"></ul>
                                            <textarea class="form-control fw-bold border-1 border-black" placeholder="Recipients" id="announce_recipients" style="resize:none;"></textarea>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold border-1 border-black" id="announce_header">
                                        <div class="invalid-feedback"></div>
                                        <label for="announce_header" class="col-form-label fw-bold">Header:</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <textarea class="form-control fw-bold border-1 border-black" id="announce_details" style="resize:none;height: 180px"></textarea>
                                        <div class="invalid-feedback"></div>
                                        <label for="announce_details" class="col-form-label fw-bold">Details:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnSaveAnnouncement" onclick="saveAnnouncement();">Save</button>
                            <button type="button" class="btn btn-success col-sm-12 text-light fw-bold rounded-pill btnUpdateAnnouncement" onclick="updateAnnouncement(this.value);">Update</button>
                            <button type="button" class="btn btn-danger col-sm-12 text-light fw-bold rounded-pill" data-bs-dismiss="modal" onclick="clearValues();">Cancel</button>
                        </div>
                    </div>
                </div>
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
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>
<?php
include './../helper/perso_announcement.php';
include './../includes/footer.php';
include './../helper/input_validation.php';
?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var logged_user_empno = '<?php echo $_SESSION['empno']; ?>';
    var logged_user_job_title = '<?php echo $_SESSION['access_lvl']; ?>';

    loadAnnouncementList(logged_user_empno);
    loadAnnouncementHistoryList();

    function loadAnnouncementList(logged_user_empno) {
        var announcement_list_table = $('#announcement_list_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_announcement_list_table',
                    announce_by_empno: logged_user_empno
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-left',
                width: '10%'
            }, {
                targets: [1, 2],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 3,
                className: 'dt-body-middle-left'
            }, {
                targets: 4,
                className: 'dt-body-middle-left',
                width: '20%'
            }, {
                targets: 5,
                className: 'dt-nowrap-center',
                width: '10%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn btn-primary col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Announcement" onclick="editAnnouncement('${data}');"><i class="fa-solid fa-pen-to-square fa-shake" style="--fa-animation-duration: 2.5s;"></i></button>
                    <button type="button" class="btn btn-danger col-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Announcement" onclick="deleteAnnouncement('${data}');"><i class="fa-solid fa-trash-can fa-beat" style="--fa-animation-duration: 2.5s;"></i></button>`;
                }
            }]
        });
        announcement_list_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 800);
        });

        setInterval(function() {
            announcement_list_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function loadAnnouncementHistoryList() {
        var announcement_history_list_table = $('#announcement_history_list_table').DataTable({
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_announcement_history_list_table'
                }
            },
            'columnDefs': [{
                targets: [0, 5],
                className: 'dt-body-middle-center',
                width: '10%'
            }, {
                targets: [1, 2],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 3,
                className: 'dt-body-middle-left'
            }, {
                targets: 4,
                className: 'dt-body-middle-center',
                width: '15%'
            }]
        });
    }

    function addAnnouncementModal() {
        $('#addAnnouncementModal').modal('show');
        $('#recipients_section').css('display', 'block');
        $('#announcement_title').html('NEW ANNOUNCEMENT');
        $('.btnSaveAnnouncement').prop('disabled', false).css('display', 'block');
        $('.btnUpdateAnnouncement').prop('disabled', true).css('display', 'none');
        loadAnnounceRecipients();
    }

    function saveAnnouncement() {
        if (inputValidation('announce_recipients', 'announce_header', 'announce_details')) {
            var strRecipients = $('#announce_recipients').val().toString().split(';').filter(elm => {
                return (elm != null && elm !== false && elm !== "")
            });
            $.ajax({
                url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_announcement_header',
                    announce_recipients: $('#announce_recipients').val(),
                    announce_header: $('#announce_header').val(),
                    announce_body: $('#announce_details').val(),
                    announce_by: logged_user,
                    announce_by_empno: logged_user_empno,
                    announce_by_job_title: logged_user_job_title
                },
                success: announcementid => {
                    let i;
                    for (i = 0; i < strRecipients.length; i++) {
                        $.ajax({
                            url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_announcement_recipients',
                                announcementid: announcementid,
                                strRecipients: strRecipients[i]
                            }
                        });
                    }
                    if (i == strRecipients.length) {
                        $('#announcement_list_table').DataTable().ajax.reload(null, false);
                        $('#addAnnouncementModal').modal('hide');
                        clearValues();
                    }
                }
            });
        }
    }

    function loadAnnounceRecipients() {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_section_list'
            },
            success: result => {
                console.log(result);
                let section_html = '';
                $.each(result, (key, value) => {
                    section_html += `<li><a class="dropdown-item" href="#">` + value + `</a></li>`;
                });
                $('#dropdown_section_list').html(section_html);
                $('.dropdown-item').on("click", (function() {
                    var selected_item = $(this).text() + ';';
                    var announce_recipients = document.getElementById('announce_recipients').value.trim();
                    if (announce_recipients.indexOf(selected_item) != -1) {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'Recipient already exist.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else {
                        $('#announce_recipients').val(announce_recipients + selected_item);
                    }
                }));
            }
        });
    }

    function editAnnouncement(announcementid) {
        $('#addAnnouncementModal').modal('show');
        $('#recipients_section').css('display', 'none');
        $('#announcement_title').html('UPDATE ANNOUNCEMENT');
        $('.btnSaveAnnouncement').prop('disabled', true).css('display', 'none');
        $('.btnUpdateAnnouncement').val(announcementid).prop('disabled', false).css('display', 'block');
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_announcement_info',
                announcementid: announcementid
            },
            success: result => {
                $('#announce_header').val(result.announce_header);
                $('#announce_details').val(result.announce_body);
            }
        });
    }

    function updateAnnouncement(announcementid) {
        $.ajax({
            url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
            type: 'POST',
            data: {
                action: 'update_announcement',
                announce_header: $('#announce_header').val(),
                announce_details: $('#announce_details').val(),
                announcementid: announcementid
            },
            success: result => {
                $('#announcement_list_table').DataTable().ajax.reload(null, false);
                $('#addAnnouncementModal').modal('hide');
                clearValues();
            }
        });
    }

    function deleteAnnouncement(announcementid) {

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
                    url: '../controller/perso_monitoring_controller/perso_announcement_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_announcement',
                        announcementid: announcementid
                    },
                    success: result => {
                        $('#announcement_list_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Announcement deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }

    $('#announce_recipients').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    function clearValues() {
        $('input').val('');
        $('textarea').val('');
        clearAttributes();
    }

    function clearAttributes() {
        $('input').removeClass('is-valid is-invalid');
        $('textarea').removeClass('is-valid is-invalid');
    }
</script>
</body>
<html>