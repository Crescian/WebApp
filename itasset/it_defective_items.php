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
                <span class="page-title-it ">Defective Items</span>
            </div>
            <div class="card shadow border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-danger fw-bold">Items</h4>
                        <div><input type="text" id="defective_ref_no" class="form-control fw-bold" placeholder="Control Number:" disabled>
                            <button class="btn btn-danger fw-bold" id="generateAllPdf" onclick="generateAllPdf('Defective');"><i class="fa-solid fa-file-pdf"></i> Generate Defective PDF</button>
                            <button class="btn btn-secondary fw-bold" id="recycleDefective" onclick="loadTableDefectiveItems('Recycled');"><i class="fa-solid fa-recycle fa-spin" style="color: #0bddf9;"></i> Recycled</button>
                            <button class="btn btn-secondary fw-bold" id="BackFunction" onclick="BackFunction();"><i class="fa-solid fa-backward"></i> Back</button>
                            <!-- <button class="btn btn-secondary fw-bold" id="recycleDefective" onclick="recycleDefective('Defective');"><i class="fa-solid fa-recycle fa-spin" style="color: #0bddf9;"></i> Recycle</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table w-100" id="table_defective_items">
                            <thead>
                                <tr>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPTION</th>
                                    <th class="text-center">ISSUER</th>
                                    <th class="text-center">DATE ISSUED</th>
                                    <th class="text-center">STATUS</th>
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
<?php include './../includes/footer.php'; ?>
<script>
    $('#defective_ref_no').hide();

    function generateDefectiveRefno(inTable, inField, inObject) {
        $.ajax({
            url: '../controller/itasset_controller/it_defective_items_contr.class.php',
            type: 'POST',
            data: {
                action: 'generate_defective_refno',
                inTable: inTable,
                inField: inField
            },
            success: function(result) {
                console.log(result);
                $('#' + inObject).val('ITTCN-' + result);
            }
        });
    }

    function BackFunction() {
        $('#BackFunction').hide();
        loadTableDefectiveItems('Defective');
    }

    function generateAllPdf(filter) {
        generateDefectiveRefno('tblit_control_no', 'defective_control_no', 'defective_ref_no');
        setTimeout(() => {
            var defective_ref_no = document.getElementById('defective_ref_no').value;
            var removeITTCN = defective_ref_no.replace("ITTCN-", '');
            Swal.fire({
                title: 'Proceed to Recycle?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Recycle it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controller/itasset_controller/it_defective_items_contr.class.php',
                        type: 'POST',
                        data: {
                            action: 'recycleDefective',
                            filter: filter,
                            control_no: removeITTCN
                        }
                    });
                    loadTableDefectiveItems('Defective');
                }
            })
            window.open(`it_defective_items_pdf.php?control_no=${defective_ref_no}`, '_blank');
        }, 400)
    }
    //* ~ Validate fields  ~
    $(document).on("input change", "input.is-invalid, select.is-invalid", function() {
        $(this).toggleClass('is-valid is-invalid');
    });
    let tableDefectiveItems;
    loadTableDefectiveItems('Defective');

    function loadTableDefectiveItems(filter) {
        if (filter == 'Recycled') {
            $('#recycleDefective').hide();
            $('#BackFunction').show();
        } else {
            $('#recycleDefective').show();
            $('#BackFunction').hide();
        }
        tableDefectiveItems = $('#table_defective_items').DataTable({
            responsive: true,
            autoWidth: false,
            destroy: true,
            lengthChange: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: '../controller/itasset_controller/it_defective_items_contr.class.php',
                type: 'POST',
                data: {
                    action: 'loadTableDefective',
                    filter: filter
                }
            },
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); // -- --- Hide tooltip every table draw -----
            },
            columnDefs: [{
                    targets: 0,
                    className: 'dt-body-middle-center',
                    width: '5%'
                },
                {
                    targets: [1],
                    width: '21%'
                },
                {
                    targets: [2, 3],
                    width: '10%',
                    className: 'dt-body-middle-center',
                },
                {
                    targets: 4,
                    orderable: false,
                    className: 'dt-nowrap-center',
                    width: '5%'
                }
            ]
        });
    }

    //* ~ Form validation function ~
    // function formValidation(...args) {
    //     let machine = $(`#${arguments[0]}`).val();
    //     let item = $(`#${arguments[1]}`).val();
    //     let barcode_number = $(`#${arguments[2]}`).val();
    //     let issuer = $(`#${arguments[3]}`).val();
    //     let validated = true;

    //     if (machine.trim() == '') {
    //         validate(arguments[0], 'Machine is required field.');
    //         validated = false;
    //     } else {
    //         clearValidate(arguments[0]);
    //     }

    //     if (item.trim() == '') {
    //         validate(arguments[1], 'Item is required field.');
    //         validated = false;
    //     } else {
    //         clearValidate(arguments[1]);
    //     }

    //     if (barcode_number.trim() == '') {
    //         validate(arguments[2], 'Item is required field.');
    //         validated = false;
    //     } else {
    //         clearValidate(arguments[2]);
    //     }

    //     if (issuer.trim() == '') {
    //         validate(arguments[3], 'Issuer is required field.');
    //         validated = false;
    //     } else {
    //         clearValidate(arguments[3]);
    //     }

    //     return validated;
    // }

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

    //* ~ Reset ~
    function clearAttributes() {
        $('input:not([readonly]), select, textarea').removeClass('is-invalid is-valid').val('');
        $('#employee').prop('disabled', true);
    }
</script>