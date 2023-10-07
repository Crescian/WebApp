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
    ::-webkit-scrollbar {
        width: 0.5vw;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #b811da;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <div class="row">
                <span class="page-title-ims">MSD Forms</span>
            </div>
            <!-- content section -->
            <div class="row mb-3 mt-4">
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow active" onclick="loadTableNavigation('Document Registration')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-danger fw-bold">Document Registration</span>
                                    <div class="fs-2 fw-bold" id="on_hold_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-file-signature fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow" onclick="loadTableNavigation('ISO Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-warning fw-bold">ISO Modules</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-solid fa-file-contract fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Document Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-primary fw-bold">Document Module</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-file-lines fa-flip"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-danger shadow active" onclick="loadTableNavigation('System Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-danger fw-bold">System Modules</span>
                                    <div class="fs-2 fw-bold" id="on_hold_count"></div>
                                </div>
                                <div class="fs-1 text-danger"><i class="fa-solid fa-file-shield fa-shake"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-warning shadow" onclick="loadTableNavigation('ISO Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-warning fw-bold">ISO Modules</span>
                                    <div class="fs-2 fw-bold" id="pending_count"></div>
                                </div>
                                <div class="fs-1 text-warning"><i class="fa-solid fa-file-contract fa-pulse"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="card card_hover border-0 border-left-primary shadow" onclick="loadTableNavigation('Document Module')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-truncate">
                                    <span class="fs-20 text-primary fw-bold">Document Module</span>
                                    <div class="fs-2 fw-bold" id="ongoing_count"></div>
                                </div>
                                <div class="fs-1 text-primary"><i class="fa-solid fa-file-lines fa-flip"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="doc_registration_section"> <!-- =========== Document List Section =========== -->
                <div class="row"> <!-- =========== Document List Section =========== -->
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-header card-10 py-3">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <h4 class="fw-bold text-light">Document Registration List</h4>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="exportDocRegModal();"><i class="fa-solid fa-download fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> Export</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="doc_registration_table" class="table table-bordered table-striped fw-bold" width="100%">
                                        <thead class="card-10 text-light">
                                            <tr>
                                                <th style="text-align: center;">DRF No.</th>
                                                <th style="text-align: center;">Date of Request</th>
                                                <th style="text-align: center;">Effective Date</th>
                                                <th style="text-align: center;">Date Received</th>
                                                <th>Document Title</th>
                                                <th style="text-align: center;">Document No.</th>
                                                <th style="text-align: center;">Rev.</th>
                                                <th style="text-align: center;">Request Type</th>
                                                <th style="text-align: center;">Level of Doc.</th>
                                                <th style="text-align: center;">DDC No.</th>
                                                <th style="text-align: center;">Date Published/Obsolete</th>
                                                <th style="text-align: center;">Total Days</th>
                                                <th style="text-align: center;">Remarks</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="card-10 text-light">
                                            <tr>
                                                <th style="text-align: center;">DRF No.</th>
                                                <th style="text-align: center;">Date of Request</th>
                                                <th style="text-align: center;">Effective Date</th>
                                                <th style="text-align: center;">Date Received</th>
                                                <th>Document Title</th>
                                                <th style="text-align: center;">Document No.</th>
                                                <th style="text-align: center;">Rev.</th>
                                                <th style="text-align: center;">Request Type</th>
                                                <th style="text-align: center;">Level of Doc.</th>
                                                <th style="text-align: center;">DDC No.</th>
                                                <th style="text-align: center;">Date Published/Obsolete</th>
                                                <th style="text-align: center;">Total Days</th>
                                                <th style="text-align: center;">Remarks</th>
                                                <th style="text-align: center;">Status</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== Document List Section End =========== -->
            <!-- =============== Effective Date Modal =============== -->
            <div class="modal fade" id="docEffectiveDateModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-10">
                            <h4 class="modal-title text-uppercase fw-bold text-light">Effective Date</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mt-2">
                                <input type="date" class="form-control fw-bold" id="doc_effective_date">
                                <div class="invalid-feedback"></div>
                                <label for="doc_effective_date" class="fw-bold fs-18">Effective Date:</label>
                            </div>
                        </div>
                        <div class="gap-2 col-sm-12 ps-2 pe-2 mb-3 d-flex justify-content-center flex-column">
                            <button type="button" class="btn btn-success col-sm btnSaveDocEffectiveDate" onclick="saveDocEffectiveDate(this.value);"><i class="fa-regular fa-floppy-disk p-r-8"></i> Save</button>
                            <button type="button" class="btn btn-danger col-sm" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div><!-- =============== Effective Date Modal End =============== -->



            <!-- content section end -->
            <div class="position-absolute bottom-0 end-0 d-block d-md-none">
                <button class="btn btn-primary rounded-circle m-4 fs-4" onclick="menuNav();"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div> <!-- Closing tag of content -->
        <div class="col-12 col-sm-12 col-md-3 p-3 menu-card d-none d-md-block">
            <div class="card card-10 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">IMS XPRESS</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/input_validation.php'; ?>
<script>
    var access_level = '<?php echo $_SESSION['access_lvl']; ?>';
    var empno = '<?php echo $_SESSION['empno']; ?>';
    var emp_fullname = '<?php echo $_SESSION['fullname']; ?>';

    loadDocRegistration();

    function loadDocRegistration() {
        var doc_registration_table = $('#doc_registration_table').DataTable({
            'lengthMenu': [
                [5, 10, 50, 100],
                [5, 10, 50, 100],
            ],
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/ims_xpress_controller/ims_msd_forms_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_doc_registration'
                }
            },
            'columnDefs': [{
                targets: [0, 1, 2, 3, 5, 9, 10],
                className: 'dt-body-middle-center',
                width: '10%'
            }, {
                targets: [4, 12],
                className: 'dt-body-middle-left'
            }, {
                targets: [6, 11],
                className: 'dt-body-middle-center',
                width: '3%'
            }, {
                targets: [7, 8, 13],
                className: 'dt-body-middle-center',
                width: '5%'
            }, {
                targets: 14,
                className: 'dt-nowrap-center',
                orderable: false,
                render: function(data, type, row, meta) {
                    let tableAction = '';
                    switch (access_level) {
                        case 'IMS':
                        case 'JRM':
                            if (data[1] == '-') {
                                tableAction += `<button type="button" class="btn col-sm btn-primary btnAcknowledge" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Date Acknowledge" onclick="acknowledgeDocReg('${data[0]}');"><i class="fa-solid fa-circle-check fa-bounce"></i></button> `;
                            } else {
                                if (data[2] == '-') {
                                    tableAction += `<button type="button" class="btn col-sm btn-warning btnEffective" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Date Effective" onclick="effectiveDocReg('${data[0]}');"><i class="fa-solid fa-calendar-check fa-beat"></i></button> `;
                                } else {
                                    tableAction += `<button type="button" class="btn col-sm btn-success btnDone" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Done" onclick="doneDocReg('${data[0]}','${data[1]}');"><i class="fa-solid fa-clipboard-check fa-beat"></i></button> `;
                                }
                                if (data[3] != '-' && data[4] == emp_fullname) {
                                    tableAction += `<button type="button" class="btn col-sm btn-success btnReceived" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Received" onclick="receivedDocReg('${data[0]}');"><i class="fa-solid fa-circle-check fa-bounce"></i></button> `;
                                }
                            }
                            tableAction += `<button type="button" class="btn col-sm btn-info btnPreview" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Preview" onclick="previewDocReg('${data[0]}');"><i class="fa-solid fa-circle-info fa-bounce"></i></button>`;
                            break;
                        default:
                            if (data[3] != '-' && data[4] == emp_fullname) {
                                tableAction += `<button type="button" class="btn col-sm btn-success btnReceived" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Received" onclick="receivedDocReg('${data[0]}');"><i class="fa-solid fa-circle-check fa-bounce"></i></button> `;
                            }
                            tableAction += `<button type="button" class="btn col-sm btn-info btnPreview" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Preview" onclick="previewDocReg('${data[0]}');"><i class="fa-solid fa-circle-info fa-bounce"></i></button>`;
                            break;
                    }
                    return tableAction;
                }
            }]
        });
        doc_registration_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 200);
        });
        setInterval(function() {
            doc_registration_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function acknowledgeDocReg(docregisteredid) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_msd_forms_contr.class.php',
            type: 'POST',
            data: {
                action: 'acknowledge_doc_reg',
                docregisteredid: docregisteredid,
                emp_fullname: emp_fullname
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Document Acknowledged.',
                    text: '',
                    showConfirmButton: false,
                    timer: 500
                });
                $('#doc_registration_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function doneDocReg(docregisteredid, date_received) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_msd_forms_contr.class.php',
            type: 'POST',
            data: {
                action: 'done_doc_reg',
                docregisteredid: docregisteredid,
                date_received: date_received
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Document Save.',
                    text: '',
                    showConfirmButton: false,
                    timer: 500
                });
                $('#doc_registration_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function effectiveDocReg(docregisteredid) {
        $('#docEffectiveDateModal').modal('show');
        $('.btnSaveDocEffectiveDate').val(docregisteredid);
    }

    function saveDocEffectiveDate(docregisteredid) {
        if (inputValidation('doc_effective_date')) {
            $.ajax({
                url: '../controller/ims_xpress_controller/ims_msd_forms_contr.class.php',
                type: 'POST',
                data: {
                    action: 'save_effective_date',
                    docregisteredid: docregisteredid,
                    doc_effective_date: $('#doc_effective_date').val()
                },
                success: result => {
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'Effective Date Set Successfully.',
                        text: '',
                        showConfirmButton: false,
                        timer: 500
                    });
                    $('#doc_registration_table').DataTable().ajax.reload(null, false);
                }
            });
        }
    }

    function receivedDocReg(docregisteredid) {
        $.ajax({
            url: '../controller/ims_xpress_controller/ims_msd_forms_contr.class.php',
            type: 'POST',
            data: {
                action: 'document_acknowledge',
                docregisteredid: docregisteredid
            },
            success: result => {
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Document Acknowledge.',
                    text: '',
                    showConfirmButton: false,
                    timer: 500
                });
                $('#doc_registration_table').DataTable().ajax.reload(null, false);
            }
        });
    }

    function previewDocReg(docregisteredid) {
        strLink = "document_registration_word.php?d=" + docregisteredid;
        window.open(strLink, '_self');
    }
</script>
</body>
<html>