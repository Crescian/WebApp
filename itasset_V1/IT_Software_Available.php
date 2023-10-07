<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
// date_default_timezone_set('Asia/Manila');
session_start();
// * Check if module is within the application
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    // $data_base64 = base64_encode($sqlstring);
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    // $json_response = curl_exec($curl);
    // //* ====== Close Connection ======
    // curl_close($curl);
    // // * ======== Prepare Array ========
    // $data_result = json_decode($json_response, true);
    // foreach ($data_result['data'] as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
} ?>

<style>
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom right, #fa3c3c, #aa0000);
        border-radius: 100vw;
    }
</style>
<link rel="stylesheet" type="text/css" href="../vendor/css/custom.menu.css" />

<!-- Insert your code here -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 content overflow-auto p-4" style="max-height: 100vh;">
            <div class="row mb-4 shadow">
                <span class="page-title-it">SOFTWARE ISSUANCE</span>
            </div>

            <div class="card shadow border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger fw-bold">AVAILABLE ITEMS</h4>
                        <div>
                            <button class="btn btn-danger fw-bold" onclick="newSoftwareAvailable();"><i class="fa-solid fa-plus"></i> New Entry</button>
                            <button type="button" class="btn btn-dark dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">
                                View By
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" id="filter_all" onclick="loadTableSoftwareAvailable('');" href="#">All</a></li>
                                <li><a class="dropdown-item active" id="filter_issued" onclick="loadTableSoftwareAvailable(true);" href="#">Active</a></li>
                                <li><a class="dropdown-item" id="filter_retrieved" onclick="loadTableSoftwareAvailable(false);" href="#">Inactive</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100" id="table_software_available">
                            <thead>
                                <tr>
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center">SOFTWARE</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">SERIAL</th>
                                    <th class="text-center">PROGRAMMER</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn card-1 text-light rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <!-- ==================== CARD SECTION ==================== -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-1 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IT ASSET</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
        <!-- ==================== CARD SECTION END ==================== -->
    </div>
</div>

<!-- Software Available Modal -->
<div class="modal fade" id="modalSoftwareAvailable" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSoftwareAvailableLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="position-absolute top-50 start-50 translate-middle bg-light shadow p-3 rounded-pill pre-loading" style="display: none;">
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
                </div>
                <h4 class="modal-title text-danger fw-bold mb-4" id="modalSoftwareAvailableLabel"><i class="fa-solid fa-file-circle-plus me-1"></i>New Entry</h4>

                <div class="form-floating mb-3">
                    <select class="form-select" id="software_type">
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="software_type" class="fw-bold">Type:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="software" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="software" class="fw-bold">Software:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="description" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="description" class="fw-bold">Description:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="serial" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="serial" class="fw-bold">Serial:</label>
                </div>

                <div class="form-floating mb-4 form-group-programmer" style="display:none">
                    <select class="form-select" id="programmer" value="-">
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="programmer" class="fw-bold">Programmer:</label>
                </div>
                <div class="d-grid gap-2 modal-btn">
                    <button type="button" class="btn btn-danger fw-bold rounded-pill" onclick="saveSoftwareAvailable();">Save</button>
                    <button type="button" class="btn btn-light text-danger fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="modalActionSoftwareAvailable" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalActionSoftwareAvailableLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-body py-4 text-center">
                <h3 class="alert-title mb-4 fw-bold">Title</h3>
                <p class="message fw-semibold fs-6">This is a sample message.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0 alert-modal-btn">
                <!-- <button type="button" class="btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold" id="">Yes, submit</button> -->
                <button type="button" class="btn btn-link text-secondary text-decoration-none col-6 m-0 fw-semibold" data-bs-dismiss="modal">No thanks</button>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    //* ====================== B E H A V I O R S ======================

    // * ~ Dropdown Active ~
    $('.dropdown-menu li a').on('click', function() {
        $('.dropdown-menu li a').removeClass('active');
        $(this).addClass('active')
    });

    // * ~ If sofware type is In House, programmer input field will appear ~
    $('#software_type').change(function() {
        $(this).val() == 'In House' ? $('.form-group-programmer').show().children('#programmer').removeClass('is-invalid is-valid').val('') : $('.form-group-programmer').hide().children('#programmer').val('-');
    });

    // * ~ Validation on input and change ~
    $(document).on("input change", "input.is-invalid, select.is-invalid", function() {
        $(this).toggleClass('is-valid is-invalid');
    });
    //* ====================== E N D  L I N E  O F  B E H A V I O R S ======================



    //* ====================== F U N C T I O N S ======================

    //* ~ Function calls ~
    let tableSoftwareAvailable;
    loadTableSoftwareAvailable(true);

    // //* ~ load table through serverside ~
    function loadTableSoftwareAvailable(filterValue) {

        tableSoftwareAvailable = $('#table_software_available').DataTable({
            responsive: true,
            autoWidth: false,
            destroy: true,
            lengthChange: false,
            // order: [
            //     [1, 'desc']
            // ],
            processing: true,
            serverSide: true,
            ajax: {
                url: 'functions/it_software_available-function.php',
                type: 'POST',
                data: {
                    action: 'loadTableSoftwareAvailable',
                    filterValue: filterValue
                }
            },
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); // -- --- Hide tooltip every table draw -----
            },
            columnDefs: [{
                    targets: '_all',
                    className: 'dt-body-middle-center'
                },
                {
                    targets: [5, 6],
                    orderable: false,
                    className: 'dt-nowrap-center'
                }
            ]
        });
        // // ----- Reload table every 30 seconds. -----
        // setInterval(function() {
        //     tableSoftwareAvailable.ajax.reload(null, false);
        // }, 30000);

    }

    //* ~ load input data upon creation ~
    function loadInputData() {
        $.ajax({
            url: 'functions/it_software_available-function.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadInputData',
            },
            success: (data) => {
                $('#software_type').html(data.softwareType);
                $('#programmer').html(data.programmer);
            }
        });
    }

    //* ~ New entry of Software Available ~
    function newSoftwareAvailable() {
        clearAttributes();
        loadInputData();
        $('#modalSoftwareAvailable').modal('show');
    }

    function saveSoftwareAvailable() {
        if (formValidation('software_type', 'software', 'description', 'serial', 'programmer')) {
            $.ajax({
                url: 'functions/it_software_available-function.php',
                type: 'POST',
                data: {
                    action: 'newSoftwareAvailable',
                    softwareType: $('#software_type').val(),
                    software: $('#software').val(),
                    description: $('#description').val(),
                    serial: $('#serial').val(),
                    programmer: $('#programmer').val()
                },
                success: function(res) {
                    if (res == true) {
                        $('#modalSoftwareAvailable').modal('hide');
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Success',
                            text: `Item Added Successful`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        tableSoftwareAvailable.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Failed',
                            text: res,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }
    }

    //* ~ Action Hardware Issuance ~
    function actionSoftwareAvailable(id, status) {
        $('#modalActionSoftwareAvailable').modal('show');
        let btn_status;
        switch (status) {
            case 'Active':
                $('.alert-title').html('<i class="fa-solid fa-check"></i> Activate');
                $('.message').text('Are you sure you want to activate this item?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'active_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#active_btn');
                break;

            case 'Inactive':
                $('.alert-title').html('<i class="fa-regular fa-circle-xmark"></i> Inactivate');
                $('.message').text('Are you sure you want to inactivate this item?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'inactive_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#inactive_btn');
                break;

            case 'Delete':
                $('.alert-title').html('<i class="fa-solid fa-trash"></i> Delete');
                $('.message').text('This data will be deleted permanently. Are you sure?');
                $('.btn-submit').remove();
                $('.alert-modal-btn').prepend($('<button>', {
                    type: 'button',
                    class: 'btn btn-link btn-submit text-danger text-decoration-none col-6 m-0 border-end fw-bold',
                    id: 'delete_btn',
                    text: 'Yes, submit'
                }));
                btn_status = $('#delete_btn');
                break;
        }
        btn_status.on('click', function() {
            $.ajax({
                url: 'functions/it_software_available-function.php',
                type: 'POST',
                data: {
                    action: 'actionSoftwareAvailable',
                    id: id,
                    status: status
                },
                success: function(res) {
                    // if (res == true) {
                    $('#modalActionSoftwareAvailable').modal('hide');
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Success',
                        text: `Item ${res}`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    tableSoftwareAvailable.ajax.reload(null, false);
                    // }
                }

            });
        });
    }

    //* ~ Form validation function ~
    function formValidation(...args) {
        let validated = true;
        $.each(args, function(i, e) {
            let element = $(`#${e}`);
            if (element.val().trim() == '') {
                invalidField(e, 'Field is required.');
                validated = false;
            } else {
                validField(e);
            }
        });
        return validated;
    }

    //* ~ Validation Error ~
    function invalidField(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    //* ~ Validation Success ~
    function validField(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }

    //* ~ Reset ~
    function clearAttributes() {
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid').val('');
        $('#employee').prop('disabled', true);
    }
</script>