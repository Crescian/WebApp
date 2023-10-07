<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
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
                <span class="page-title-it ">Switch Module</span>
            </div>
            <div class="row d-flex- justify-content-center">
                <div class="card card-1 shadow border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-danger fw-bold">Access Request</h4>
                            <div>
                                <button class="btn btn-light fw-bold" id="generatePdf" onclick="generatePdf();"><i class="fa-solid fa-file-pdf"></i> Generate PDF</button>
                                <button class="btn btn-danger fw-bold" id="assign" onclick="assign();"><i class="fa-solid fa-hand-holding-hand"></i> Assign</button>
                                <button class="btn btn-warning fw-bold" id="addSwitch" onclick="addSwitch();"><i class="fa-solid fa-network-wired"></i> Add Switch</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex- justify-content-center">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="table-responsive" id="all_table">
                            <table id="switch_table" class="table table-bordered table-striped fw-bold" width="100%">
                                <thead class="customHeaderItAsset">
                                    <tr>
                                        <th class="text-center">LAN CABLE</th>
                                        <th class="text-center">LOCATION</th>
                                        <th class="text-center">SWITCH</th>
                                        <th class="text-center">PORT</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tfoot class="customHeaderItAsset">
                                    <tr>
                                        <th class="text-center">LAN CABLE</th>
                                        <th class="text-center">LOCATION</th>
                                        <th class="text-center">SWITCH</th>
                                        <th class="text-center">PORT</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                    </div> -->
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
<!-- =============== SWITCH Modal =============== -->
<div class="modal fade" id="switchModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="switchModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-1">
                <h5 class="modal-title fw-bold text-light" id="switchModal">Assign Port Switch <i class="fa-solid fa-network-wired fa-beat"></i></h5>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" id="switch" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="switch" class="fw-bolder">Switch:</label>
                </div>
                <div class="form-floating mb-2">
                    <input type="number" id="port" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="port" class="fw-bolder">Port:</label>
                </div>
            </div>
            <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                <button type="button" class="btn btn-dark col-sm" id="switchAssign" onclick="switchAssign();"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- =============== SWITCH Modal =============== -->
<div class="modal fade" id="assignModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="assignModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-1">
                <h5 class="modal-title fw-bold text-light" id="assignModal">Assign <i class="fa-solid fa-hand-holding-hand fa-beat"></i></h5>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" id="lan_cable" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="lan_cable" class="fw-bolder">Lan Cable:</label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" id="location" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="location" class="fw-bolder">Location:</label>
                </div>
                <div class="form-floating mb-2">
                    <select name="switchLocation" id="switchLocation" class="form-select fw-bold" onchange="getThePort(this.value);" placeholder=" ">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="switchLocation" class="fw-bolder">Switch:</label>
                </div>
                <div class="form-floating mb-2">
                    <select name="portLocation" id="portLocation" class="form-select fw-bold" onchange="validataionPost(this.value);" placeholder=" " disabled>
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="portLocation" class="fw-bolder">Port:</label>
                </div>
            </div>
            <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                <button type="button" class="btn btn-dark col-sm" id="saveAssign" onclick="saveAssign();"><i class="fa-regular fa-floppy-disk p-r-8"></i>Submit</button>
                <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- =============== SWITCH Modal =============== -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-1">
                <h5 class="modal-title fw-bold text-light" id="editModal">Edit <i class="fa-solid fa-pen-to-square fa-beat"></i></h5>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" id="lan_cable_edit" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="lan_cable_edit" class="fw-bolder">Lan Cable:</label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" id="location_edit" class="form-control fw-bold" placeholder=" ">
                    <div class="invalid-feedback"></div>
                    <label for="location_edit" class="fw-bolder">Location:</label>
                </div>
                <div class="form-floating mb-2">
                    <select name="switchLocationEdit" id="switchLocationEdit" class="form-select fw-bold" onchange="getThePort(this.value);" placeholder=" ">
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="switchLocationEdit" class="fw-bolder">Switch:</label>
                </div>
                <div class="form-floating mb-2">
                    <select name="portLocationEdit" id="portLocationEdit" class="form-select fw-bold" onchange="validataionPost(this.value);" placeholder=" " disabled>
                        <option value="">Choose...</option>
                    </select>
                    <div class="invalid-feedback"></div>
                    <label for="portLocationEdit" class="fw-bolder">Port:</label>
                </div>
                <div class="form-floating mb-2 hide">
                    <input type="text" id="portReplica" class="form-control fw-bold">
                </div>
            </div>
            <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                <button type="button" class="btn btn-dark col-sm" id="updateAssign" onclick="updateAssign(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i>Update</button>
                <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    var logged_user = '<?php echo $_SESSION['fullname']; ?>';
    var user_department = '<?php echo $_SESSION['dept_code']; ?>';
    loadTableSwitch();
    $('.hide').hide();
    function loadTableSwitch() {
        let inTable = $('#switch_table').DataTable({
            'responsive': true,
            'autoWidth': false,
            'serverSide': true,
            'deferRender': true,
            'ajax': {
                url: '../controller/itasset_controller/it_switch_port_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_table_switch'
                }
            },
            'columnDefs': [{
                targets: 0,
                className: 'dt-body-middle-center',
                width: '20%'
            }, {
                targets: 1,
                className: 'dt-body-middle-center',
                width: '50%'
            }, {
                targets: [2, 3],
                className: 'dt-body-middle-center',
                width: '10%'
            }, {
                targets: 4,
                className: 'dt-body-middle-center',
                width: '11%',
                render: function(data, type, row, meta) {
                    let button = `<button class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="edit" data-id="${data}" id="btn_edit" onclick="editFunction(${data});"><i class="fa-solid fa-pen fa-beat"></i></button>`
                    return button;
                }
            }]
        });
        inTable.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 1000);
        });
        setInterval(function() {
            inTable.ajax.reload(null, false); //* ======= Reload Table Data Every X seconds with pagination retained =======
        }, 5000);
    }

    function generatePdf() {
        window.open(`it_switch_port_pdf.php?`, '_blank');
    }

    function saveAssign() {
        if (formValidationAssign('lan_cable', 'location', 'switchLocation', 'portLocation')) {
            $.ajax({
                url: '../controller/itasset_controller/it_switch_port_contr.class.php',
                type: 'POST',
                data: {
                    action: 'saveAssign',
                    lan_cable: $('#lan_cable').val(),
                    location: $('#location').val(),
                    switchLocation: $('#switchLocation').val(),
                    portLocation: $('#portLocation').val(),
                }
            });
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
            })
            $('#assignModal').modal('hide');
            clearValues();
        }
    }

    function editFunction(id) {
        $('#updateAssign').val(id);
        $('#portLocationEdit').prop('disabled', true);
        $('#portLocationEdit').html("");
        $('#switchLocationEdit').html("");
        $('#editModal').modal('show');
        loadSwitch();
        $.ajax({
            url: '../controller/itasset_controller/it_switch_port_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'editFunction',
                id: id
            },
            success: function(result) {
                $('#lan_cable_edit').val(result.lan_cable);
                $('#location_edit').val(result.location);
                $('#switchLocationEdit').val(result.switch);
                getThePort(result.switch);
                setTimeout(() => {
                    $('#portReplica').val(result.port);
                    $('#portLocationEdit').val(result.port);
                }, 500);
            }
        })
    }

    function updateAssign(id) {
        if (formValidationAssign('lan_cable_edit', 'location_edit', 'switchLocationEdit', 'portLocationEdit')) {
            let objData;
            Swal.fire({
                title: `Is port ${$('#portReplica').val()} is in good shape?`,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-check"></i> Yes',
                denyButtonText: `<i class="fa-solid fa-x"></i> No`,
            }).then((result) => {
                if (result.isConfirmed) {
                    objData = {
                        action: 'updateAssign',
                        lan_cable: $('#lan_cable_edit').val(),
                        location: $('#location_edit').val(),
                        switch: $('#switchLocationEdit').val(),
                        port: $('#portLocationEdit').val(),
                        status: 'true',
                        id: id,
                    }
                    Swal.fire('Update!', '', 'success')
                    clearValues();
                } else if (result.isDenied) {
                    objData = {
                        action: 'updateAssign',
                        lan_cable: $('#lan_cable_edit').val(),
                        location: $('#location_edit').val(),
                        switch: $('#switchLocationEdit').val(),
                        port: $('#portLocationEdit').val(),
                        portReplica: $('#portReplica').val(),
                        status: 'false',
                        id: id,
                    }
                    Swal.fire('Port set to broken!', '', 'success')
                    clearValues();
                }
                $.ajax({
                    url: '../controller/itasset_controller/it_switch_port_contr.class.php',
                    type: 'POST',
                    data: objData
                });
            });
            $('#editModal').modal('hide');
        }
    }
    let numbersWithSameValueOkay;
    let numbersWithSameValueNotOkay;
    let activateValidation = false;

    function getThePort(letter) {
        $('#portLocation').prop('disabled', false);
        $('#portLocationEdit').prop('disabled', false);
        $('#portLocation').html("");
        $('#portLocationEdit').html("");

        function findNumbersWithSameValue(array1, array2) {
            const result = array1.filter(num => array2.includes(num));
            return result;
        }

        $.ajax({
            url: '../controller/itasset_controller/it_switch_port_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'getThePort',
                letter: letter
            },
            success: function(result) {
                portLength = result.data;
                let arrayLength = [];
                for (var i = 1; i <= portLength; i++) {
                    arrayLength.push(i);
                }

                $('#portLocation').append(`<option value="">Choose...</option>`);
                $('#portLocationEdit').append(`<option value="">Choose...</option>`);

                let validationOkay = false;
                let validationNotOkay = false;

                if (result.portOkay !== undefined) {
                    numbersWithSameValueOkay = findNumbersWithSameValue(result.portOkay, arrayLength);
                    validationOkay = true;
                }

                if (result.portNotOkay !== undefined) {
                    numbersWithSameValueNotOkay = findNumbersWithSameValue(result.portNotOkay, arrayLength);
                    validationNotOkay = true;
                }

                for (var i = 1; i <= portLength; i++) {
                    if (!validationOkay && !validationNotOkay) {
                        $('#portLocation').append(`<option value="${i}">${i}</option>`);
                        $('#portLocationEdit').append(`<option value="${i}">${i}</option>`);
                    }
                    if (validationOkay && validationNotOkay) {
                        if (numbersWithSameValueOkay.includes(i)) {
                            $('#portLocation').append(`<option value="${i}" class="bg-success text-light">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}" class="bg-success text-light">${i}</option>`);
                        } else if (numbersWithSameValueNotOkay.includes(i)) {
                            $('#portLocation').append(`<option value="${i}" class="bg-danger text-light">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}" class="bg-danger text-light">${i}</option>`);
                        } else {
                            $('#portLocation').append(`<option value="${i}">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}">${i}</option>`);
                        }
                    } else if (validationOkay) {
                        if (numbersWithSameValueOkay.includes(i)) {
                            $('#portLocation').append(`<option value="${i}" class="bg-success text-light">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}" class="bg-success text-light">${i}</option>`);
                        } else {
                            $('#portLocation').append(`<option value="${i}">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}">${i}</option>`);
                        }
                    } else if (validationNotOkay) {
                        if (numbersWithSameValueNotOkay.includes(i)) {
                            $('#portLocation').append(`<option value="${i}" class="bg-danger text-light">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}" class="bg-danger text-light">${i}</option>`);
                        } else {
                            $('#portLocation').append(`<option value="${i}">${i}</option>`);
                            $('#portLocationEdit').append(`<option value="${i}">${i}</option>`);
                        }
                    }
                }
                activateValidation = validationOkay || validationNotOkay;
            }
        });
    }

    function validataionPost(port) {
        if (activateValidation) {
            let portInt = parseInt(port);
            if (numbersWithSameValueOkay.includes(portInt)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Port Is In Already Use!'
                });
                $('#portLocation').find('option:first').prop('selected', 'selected');
                $('#portLocationEdit').find('option:first').prop('selected', 'selected');
            } else if (numbersWithSameValueNotOkay.includes(portInt)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Warning',
                    text: 'Port Is Broken!'
                });
                $('#portLocation').find('option:first').prop('selected', 'selected');
                $('#portLocationEdit').find('option:first').prop('selected', 'selected');
            }
        }
    }

    function addSwitch() {
        $('#switchModal').modal('show');
        clearValues();
    }

    function switchAssign() {
        if (formValidation('switch', 'port')) {
            $.ajax({
                url: '../controller/itasset_controller/it_switch_port_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'switchAssign',
                    switch: $('#switch').val(),
                    port: $('#port').val()
                }
            });
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
            })
            $('#switchModal').modal('hide');
            clearValues();
        }
    }

    function loadSwitch() {
        $.ajax({
            url: '../controller/itasset_controller/it_switch_port_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'loadSwitch'
            },
            success: function(result) {
                console.log(result);
                $.each(result, (key, value) => {
                    $('#switchLocation').append(`<option value="${key}">${value}</option>`)
                    $('#switchLocationEdit').append(`<option value="${key}">${value}</option>`)
                })
            }
        });
    }

    function assign() {
        loadSwitch();
        clearValues();
        $('#assignModal').modal('show');
    }

    //* ~ Form validation function ~
    function formValidation(...args) {
        let data1 = $(`#${arguments[0]}`).val();
        let data2 = $(`#${arguments[1]}`).val();
        let validated = true;
        if (data1.trim() == '') {
            validate(arguments[0], 'Switch is required field.');
            validated = false;
        } else {
            clearValidate(arguments[0]);
        }

        if (data2.trim() == '') {
            validate(arguments[1], 'Port is required field.');
            validated = false;
        } else {
            clearValidate(arguments[1]);
        }
        return validated;
    }
    //* ~ Form validation function ~
    function formValidationAssign(...args) {
        let lan_cable = $(`#${arguments[0]}`).val();
        let location = $(`#${arguments[1]}`).val();
        let switchLocation = $(`#${arguments[2]}`).val();
        let portLocation = $(`#${arguments[3]}`).val();
        let validated = true;
        if (lan_cable.trim() == '') {
            validate(arguments[0], 'Lan Cable is required field.');
            validated = false;
        } else {
            clearValidate(arguments[0]);
        }
        
        if (location.trim() == '') {
            validate(arguments[1], 'Location is required field.');
            validated = false;
        } else {
            clearValidate(arguments[1]);
        }

        if (switchLocation.trim() == '') {
            validate(arguments[2], 'Switch is required field.');
            validated = false;
        } else {
            clearValidate(arguments[2]);
        }

        if (portLocation.trim() == '') {
            validate(arguments[3], 'Port is required field.');
            validated = false;
        } else {
            clearValidate(arguments[3]);
        }
        return validated;
    }
    //* ~ Validation Error ~
    function validate(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    //* ~ Validation Success ~
    function clearValidate(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }

    function clearValues() {
        $('input').val("");
        $('#switchLocation').html("<option value=''>Choose...</option>");
        $('#portLocation').html("<option value=''>Choose...</option>");
        $('#portLocation').prop('disabled', true);
        $('#portLocationEdit').prop('disabled', true);
        $('#portLocation').find('option:first').prop('selected', 'selected');
        $('#portLocationEdit').find('option:first').prop('selected', 'selected');
        $('#switchLocation').find('option:first').prop('selected', 'selected');
        $('#switchLocationEdit').find('option:first').prop('selected', 'selected');
        clearAttributes();
    }
    //* ~ Reset ~
    function clearAttributes() {
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid').val('');
        $('#employee').prop('disabled', true);
    }
</script>