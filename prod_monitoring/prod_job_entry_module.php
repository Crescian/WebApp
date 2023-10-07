<?php include './../includes/header.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

// * Check if module is within the application
session_start();
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
        background-color: #291af5;
        border-radius: 100vw;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col content overflow-auto p-4 d-md-block" style="max-height: 100vh;">
            <!-- content section -->
            <div class="row">
                <span class="page-title-production">Job Entry</span>
            </div>
            <div class="row mt-5 mb-4"> <!-- =========== Job Entry Section =========== -->
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="fw-bold text-light" id="process_division_title">Job List</h4>
                                </div>
                                <div class="col-sm">
                                    <div class="row">
                                        <button type="button" class="btn btn-light col-sm-12 fw-bold fs-18" onclick="addEntryModal();"><i class="fa-solid fa-square-plus fa-bounce p-r-8" style="--fa-animation-duration: 2s;"></i> New Job Entry</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="jobEntry_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderProd">
                                        <tr>
                                            <th style="text-align:center;">Priority</th>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Start Transfer Date</th>
                                            <th style="text-align:center;">End Transfer Date</th>
                                            <th style="text-align:center;">Delivery Date</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderProd">
                                        <tr>
                                            <th style="text-align:center;">Priority</th>
                                            <th style="text-align:center;">Date Receive</th>
                                            <th>Customer</th>
                                            <th>J.O Number</th>
                                            <th>Description</th>
                                            <th>Filename</th>
                                            <th style="text-align:center;">Quantity</th>
                                            <th style="text-align:center;">Start Transfer Date</th>
                                            <th style="text-align:center;">End Transfer Date</th>
                                            <th style="text-align:center;">Delivery Date</th>
                                            <th style="text-align:center;">Status</th>
                                            <th style="text-align:center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- =========== Job Entry Section End =========== -->

            <!-- =============== Add Job Entry Modal =============== -->
            <div class="modal fade" id="addUpdateJobEntryModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header card-8">
                            <h4 class="modal-title text-uppercase fw-bold text-light" id="job_modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="company" onclick="loadJobOrder();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="company" class="col-form-label fw-bold">Company</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="jonumber" onclick="loadJobDescription();">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="jonumber" class="col-form-label fw-bold">Job Order</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="job_description" disabled>
                                        <input type="hidden" class="form-control fw-bold" id="orderid" disabled>
                                        <label for="job_description" class="col-form-label fw-bold">Description</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="job_template" onchange="loadTemplateDetails(this.value);">
                                            <option value="">Choose...</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="job_template" class="col-form-label fw-bold">Template Name</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="text" class="form-control fw-bold" id="job_filename">
                                        <div class="invalid-feedback"></div>
                                        <label for="job_filename" class="col-form-label fw-bold">Filename</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold text-center" id="job_quantity" oninput="genEquivSheets()">
                                                <div class="invalid-feedback"></div>
                                                <label for="job_quantity" class="col-form-label fw-bold">Quantity</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="outs_no" onchange="genEquivSheets();">
                                                    <option value="12">12</option>
                                                    <option value="20">20</option>
                                                    <option value="32">32</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label for="outs_no" class="col-form-label fw-bold">Outs No.</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <select class="form-select fw-bold" id="card_type">
                                                    <option value="">Choose...</option>
                                                    <option value="L">L</option>
                                                    <option value="PP">PP</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                                <label for="card_type" class="col-form-label fw-bold">Card Type</label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-floating mb-2">
                                                <input type="text" class="form-control fw-bold text-center" id="equivalent_sheets" disabled>
                                                <div class="invalid-feedback"></div>
                                                <label for="equivalent_sheets" class="col-form-label fw-bold">Equivalent in Sheets</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="date_receive">
                                        <div class="invalid-feedback"></div>
                                        <label for="date_receive" class="col-form-label fw-bold">Date Receive</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="delivery_date">
                                        <div class="invalid-feedback"></div>
                                        <label for="delivery_date" class="col-form-label fw-bold">Delivery Date</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="start_transfer_date">
                                        <div class="invalid-feedback"></div>
                                        <label for="start_transfer_date" class="col-form-label fw-bold">Start Transfer Date</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <input type="date" class="form-control fw-bold" id="end_transfer_date">
                                        <div class="invalid-feedback"></div>
                                        <label for="end_transfer_date" class="col-form-label fw-bold">End Transfer Date</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                        <select class="form-select fw-bold" id="job_priority">
                                            <option value="1">Priority 1</option>
                                            <option value="2">Priority 2</option>
                                            <option value="3">Priority 3</option>
                                            <option value="4">Priority 4</option>
                                            <option value="5">Priority 5</option>
                                            <option value="6">Priority 6</option>
                                            <option value="7">Priority 7</option>
                                            <option value="8">Priority 8</option>
                                            <option value="9">Priority 9</option>
                                            <option value="10">Priority 10</option>
                                            <option value="11">Priority 11</option>
                                            <option value="12">Priority 12</option>
                                            <option value="13">Priority 13</option>
                                            <option value="14">Priority 14</option>
                                            <option value="15">Priority 15</option>
                                            <option value="16">Priority 16</option>
                                            <option value="17">Priority 17</option>
                                            <option value="18">Priority 18</option>
                                            <option value="19">Priority 19</option>
                                            <option value="20">Priority 20</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                        <label for="job_priority" class="col-form-label fw-bold">Job Priority</label>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm d-flex align-items-center">
                                            <input class="form-check-input mt-3" type="checkbox" name="flexChkJob" id="job_chk_hold"><label class="form-check-label fw-bold fs-15 p-l-8 mt-3" for="job_chk_hold">Hold Job</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- =============== Add Job Entry Row End =============== -->
                            <div class="row"><!-- =============== Process List Row =============== -->
                                <div class="col-sm">
                                    <div class="card">
                                        <div class="card-header card-8">
                                            <h5 class="text-uppercase fw-bolder text-light">Process List</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="processList_table" class="table table-bordered table-striped table-hover" width="100%">
                                                    <thead class="customHeaderProd">
                                                        <tr>
                                                            <th width="10%" class="text-center">SEQ#</th>
                                                            <th>Name</th>
                                                            <th width="30%">Section</th>
                                                            <th width="25%">Card Side</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- =============== Process List End =============== -->
                            </div><!-- =============== Process List Row End =============== -->
                        </div>
                        <div class="d-grid gap-2 mb-3 px-3">
                            <button type="button" class="btn btn-success col btnUpdateJobEntry" onclick="updateJobEntry(this.value);"><i class="fa-solid fa-floppy-disk p-r-8"></i>Update</button>
                            <button type="button" class="btn btn-success col btnSaveJobEntry" onclick="saveJobEntry();"><i class="fa-solid fa-floppy-disk p-r-8"></i>Save</button>
                            <button type="button" class="btn btn-danger col" data-bs-dismiss="modal" onclick="clearValues();"><i class="fa-regular fa-circle-xmark p-r-8"></i>Close</button>
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
            <div class="card card-8 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">MANUFACTURING</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php';
include './../helper/input_validation.php';
?>
<script>
    let prevIndexCompany = '';
    let prevIndexJonumber = '';
    let dateToday = new Date().toISOString().slice(0, 10);

    loadJobEntryTable();

    function loadJobEntryTable() {
        var jobEntry_table = $('#jobEntry_table').DataTable({
            'lengthMenu': [
                [5, 25, 50, 100],
                [5, 25, 50, 100]
            ],
            'autoWidth': false,
            'responsive': true,
            'processing': true,
            'ajax': {
                url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_job_entry_table'
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
                targets: [0, 10],
                className: 'dt-body-middle-center',
                width: '5%'
            }, {
                targets: [1, 7, 8, 9],
                className: 'dt-body-middle-center',
                width: '8%'
            }, {
                targets: [2, 3, 4, 5],
                className: 'dt-body-middle-left'
            }, {
                targets: 6,
                className: 'dt-body-middle-right',
                width: '7%'
            }, {
                targets: 11,
                className: 'dt-nowrap-center',
                width: '8%',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm-6 btn-primary btnEditJob" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Job" onclick="modifyJob(\'${data}\');"><i class="fa-solid fa-pen-to-square fa-bounce"></i></button>
                        <button type="button" class="btn col-sm-6 btn-danger btnDeleteJob" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete Job" onclick="deleteJob(\'${data}\');"><i class="fa-solid fa-trash-can fa-shake"></i></button>`
                }
            }]
        });
        jobEntry_table.on('draw', function() {
            $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
            // $('[data-bs-toggle="tooltip"]').tooltip('hide'); //* ======== Hide tooltip every table draw ========
            $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                $(this).tooltip('hide');
            });
        });
        setInterval(function() {
            jobEntry_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function addEntryModal() {
        $('#addUpdateJobEntryModal').modal('show');
        $('#date_receive').val(dateToday);
        $('#releaseDate').attr('min', dateToday);
        $('.btnUpdateJobEntry').prop('disabled', true).css('display', 'none');
        $('.btnSaveJobEntry').prop('disabled', false).css('display', 'block');
        $('#job_modal_title').html('Add Job Information');
        loadSelectValue('prod_template_assign', 'customer_name', 'company', 'production');
    }

    function saveJobEntry() {
        if (inputValidation('company', 'jonumber', 'job_template', 'job_quantity', 'outs_no', 'card_type', 'date_receive', 'delivery_date', 'start_transfer_date', 'end_transfer_date', 'job_filename', 'job_priority')) {
            $.ajax({
                url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'save_job_entry',
                    customer_name: $('#company').val(),
                    jonumber: $('#jonumber').val(),
                    job_description: $('#job_description').val(),
                    orderid: $('#orderid').val(),
                    template_id: $('#job_template').val(),
                    job_quantity: $('#job_quantity').val(),
                    outs_no: $('#outs_no').val(),
                    card_type: $('#card_type').val(),
                    equiv_sheets: $('#equivalent_sheets').val(),
                    date_receive: $('#date_receive').val(),
                    delivery_date: $('#delivery_date').val(),
                    start_transfer_date: $('#start_transfer_date').val(),
                    end_transfer_date: $('#end_transfer_date').val(),
                    job_filename: $('#job_filename').val(),
                    job_chk_hold: $('#job_chk_hold').is(":checked"),
                    job_priority: $('#job_priority').val()
                },
                success: function(result) {
                    if (result.jobentryid == 'existing') {
                        Swal.fire({
                            position: 'top',
                            icon: 'info',
                            title: 'Filename Already Exist!',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#job_filename').focus();
                        clearAttributes();
                    } else {
                        $.ajax({
                            url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
                            type: 'POST',
                            data: {
                                action: 'save_job_process',
                                jobentry_id: result.jobentryid,
                                template_id: $('#job_template').val(),
                                job_chk_hold: $('#job_chk_hold').is(":checked"),
                                job_priority: $('#job_priority').val()
                            }
                        });
                        Swal.fire({
                            position: 'top',
                            icon: 'success',
                            title: 'Save Successfully.',
                            text: '',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        loadJobEntryTable();
                        clearValues();
                    }
                }
            });

        }
    }

    function modifyJob(jobentryid) {
        $('#addUpdateJobEntryModal').modal('show');
        $('#job_modal_title').html('Update Job Information');
        $('.btnUpdateJobEntry').prop('disabled', false).css('display', 'block');
        $('.btnSaveJobEntry').prop('disabled', true).css('display', 'none');
        $('.btnUpdateJobEntry').val(jobentryid);
        loadSelectValue('prod_template_assign', 'customer_name', 'company', 'production');
        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_job_information',
                jobentryid: jobentryid
            },
            success: function(result) {
                setTimeout(function() {
                    $('#company').val(result.customer_name);
                    loadJobOrder();
                }, 200);
                setTimeout(function() {
                    $('#jonumber').val(result.jonumber);
                    loadJobDescription();
                }, 400);
                setTimeout(function() {
                    $('#job_template').val(result.template_id);
                }, 800);

                loadTemplateDetails(result.template_id)
                $('#job_filename').val(result.job_filename);
                $('#job_quantity').val(result.job_quantity);
                $('#outs_no').val(result.outs_no);
                $('#card_type').val(result.card_type);
                $('#equivalent_sheets').val(result.equiv_sheets);
                $('#date_receive').val(result.date_receive);
                $('#delivery_date').val(result.delivery_date);
                $('#start_transfer_date').val(result.start_transfer_date);
                $('#end_transfer_date').val(result.end_transfer_date);
                $('#job_quantity,#equivalent_sheets').each(function() { //* ======== Format quantity with Commas ========
                    $(this).val(function(index, value) {
                        return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    });
                });
                if (result.job_hold == true) {
                    $('#job_chk_hold').prop('checked', true);
                } else {
                    $('#job_chk_hold').prop('checked', false);
                }
                if (result.job_status == 'On-Going' || result.job_status == 'Process Hold') {
                    $('#company').prop('disabled', true);
                    $('#jonumber').prop('disabled', true);
                    $('#job_template').prop('disabled', true);
                } else {
                    $('#company').prop('disabled', false);
                    $('#jonumber').prop('disabled', false);
                    $('#job_template').prop('disabled', false);
                }
            }
        });
    }

    function updateJobEntry(jobentryid) {

    }

    function deleteJob(jobentryid) {
        alert(jobentryid);
    }

    function loadJobOrder() {
        let currIndex = document.getElementById('company').selectedIndex;
        let currVal = document.getElementById('company').options;

        if (currIndex > 0) {
            if (prevIndexCompany != currIndex) { //* ======== Toggle same Selection ========
                let companyname = currVal[currIndex].value;
                $.ajax({
                    url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'load_job_order_number',
                        companyname: companyname
                    },
                    success: function(result) {
                        $.each(result, (key, value) => {
                            var optionExists = ($(`#jonumber option[value="${value}"]`).length > 0);
                            if (!optionExists) {
                                $('#jonumber').append(`<option value="${value}">${value}</option>`);
                            }
                        });
                    }
                });
                prevIndexCompany = currIndex;
            } else {
                prevIndexCompany = '';
            }
        }
    }

    function loadJobDescription() {
        var company = document.getElementById('company').value;
        var currIndex = document.getElementById('jonumber').selectedIndex;
        var currVal = document.getElementById('jonumber').options;

        if (currIndex > 0) {
            if (prevIndexJonumber != currIndex) { //* ======== Toggle same Selection ========
                var jonumber = currVal[currIndex].value;
                loadJoNumberDescription(jonumber, 'job_description', 'orderid');
                setTimeout(function() {
                    var orderid = document.getElementById('orderid').value;
                    loadTemplate(jonumber, orderid, company, 'job_template');
                }, 200);
                prevIndexJonumber = currIndex;
            } else {
                prevIndexJonumber = '';
            }
        }
    }

    function loadTemplate(jonumber, orderid, company, inObject) {
        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_template_name',
                company: company,
                jonumber: jonumber,
                orderid: orderid
            },
            success: function(result) {
                $.each(result, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${key}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${key}">${value}</option>`);
                    }
                });
            }
        });
    }

    function loadTemplateDetails(templateid) {
        $.ajax({
            url: '../controller/prod_monitoring_controller/prod_job_entry_contr.class.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_assign_template_process',
                templateid: templateid
            },
            success: function(result) {
                $("#processList_table").find("tr:gt(0)").remove();

                setTimeout(function() {
                    let tableRow = '';
                    $.each(result, (key, value) => {
                        tableRow += '<tr><td style="vertical-align:middle;text-align:center;font-weight: bold;">' + value.process_seq + '</td>';
                        tableRow += '<td style="vertical-align:middle;font-weight: bold;">' + value.process_name + '</td>';
                        tableRow += '<td style="vertical-align:middle;font-weight: bold;">' + value.section_name + '</td>';
                        tableRow += '<td style="vertical-align:middle;font-weight: bold;">' + value.card_side + '</td></tr>';
                    });
                    $('#processList_table').append(tableRow);
                }, 200);
            }
        });
    }

    function genEquivSheets() {
        var job_quantity = document.getElementById('job_quantity').value;
        var outs_no = document.getElementById('outs_no').value;
        job_quantity = job_quantity.replace(/,/g, "");
        $('#equivalent_sheets').val(Math.round(job_quantity / outs_no)).each(function() { //* ======== Format quantity with Commas ========
            $(this).val(function(index, value) {
                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });
        });
    }

    $('#job_quantity').keyup(function() {
        if (event.which >= 37 && event.which <= 40) { // =========== skip for arrow keys ===========
            event.preventDefault();
        }
        $(this).val(function(index, value) {
            return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });

    function clearValues() {
        $('input').val('');
        $('select').find('option:first').prop('selected', 'selected');
        $("#processList_table").find("tr:gt(0)").remove();
        clearAttributes();
        prevIndexCompany = '';
        prevIndexJonumber = '';
    }

    function clearAttributes() {
        $('input').removeClass('is-invalid is-valid');
        $('select').removeClass('is-invalid is-valid');
    }
</script>
</body>
<html>