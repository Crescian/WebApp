<?php include './../includes/navbar.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$sqlstring = "SELECT app_id FROM bpi_app_menu_module WHERE app_menu_link ILIKE '%" . $currentPage . "'";
$result_stmt = $BannerWebLive->prepare($sqlstring);
$result_stmt->execute();
$result_res = $result_stmt->fetchAll();
foreach ($result_res as $row) {
    $chkAppId = $row['app_id'];
}
if (!isset($_GET['app_id'])) {
    header('location: ../Landing_Page.php');
} else if ($_GET['app_id'] != $chkAppId) {
    header('location: ../Landing_Page.php');
} ?>
<!-- Insert your code here -->

<!-- Table -->
<div class="container-fluid px-5 py-4">
    <div class="card shadow-sm border-light mb-2">
        <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-primary">File<span class="text-secondary"> / Assign CPU </span></h4>
                <button class="btn btn-primary" onclick="newAssignCPU();"><i class="fa-solid fa-plus pe-2"></i>New Entry</button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-light">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table w-100 table-hover table-bordered" id="assign_cpu_table">
                    <thead>
                        <tr>
                            <th>EMPLOYEE</th>
                            <th>CPU NUMBER</th>
                            <th>DESCRIPTION</th>
                            <th>LOCATION</th>
                            <th>SWITCH</th>
                            <th>LAN CABLE</th>
                            <th>IP ADDRESS</th>
                            <th>DATE UPDATED</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for New and Edit Entry-->
<div class="modal fade" id="modal_assign_cpu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body fw-bold">
                <h4 class="modal-title mb-4 text-primary"></h4>
                <!-- <p class="text-muted mb-4">Fill out all fields.</p> -->

                <div class="mb-4" id="row_form">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="active_computer" checked>
                        <label class="form-check-label" for="active_computer">
                            Active Computer
                        </label>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="employee">Employee:</label>
                        <select class="form-select fw-bold" name="employee" id="employee">
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="cpu_control_number">CPU Control Number:</label>
                        <input type="text" class="form-control fw-bold disabled" name="cpu_control_number" id="cpu_control_number" disabled>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="description">Description:</label>
                        <input type="text" class="form-control fw-bold" name="description" id="description">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="location">Location:</label>
                        <input type="text" class="form-control fw-bold" name="location" id="location">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="date_updated">Date Updated:</label>
                        <input type="date" class="form-control fw-bold disabled" name="date_updated" id="date_updated" disabled>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="switch_tag">Switch Tag:</label>
                        <select class="form-select fw-bold" name="switch_tag" id="switch_tag">
                            <option value="" selected>Choose...</option>
                            <option value="OE-SW1">OE-SW1</option>
                            <option value="OE-SW2">OE-SW2</option>
                            <option value="OE-SW3">OE-SW3</option>
                            <option value="PG-SW1">PG-SW1</option>
                            <option value="PG-SW2">PG-SW2</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="lan_cable_tag">Lan Cable Tag:</label>
                        <input type="text" class="form-control fw-bold" name="lan_cable_tag" id="lan_cable_tag">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label" for="ip_address">IP Address:</label>
                        <input type="text" class="form-control fw-bold" name="ip_address" id="ip_address">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary fw-bold">Save</button>
                    <button type="button" class="btn btn-light text-primary fw-bold" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    loadTableAssignCPU();

    function modalBehavior(handler) {
        switch (handler) {
            case 'new':
                $('#modal_assign_cpu').modal('show');
                $('.modal-title').text('New Entry');
                $('#active_computer').prop('disabled', true);
                break;

            case 'update':
                $('#modal_assign_cpu').modal('show');
                $('.modal-title').text('Edit Entry');
                break;

            case 'delete':
                break;
        }
    }

    function loadTableAssignCPU() {
        let assign_cpu_table = $('#assign_cpu_table').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            order: [
                [1, 'desc']
            ],
            ajax: {
                url: './../functions/File_Assign_CPU_functions.php',
                type: 'POST',
                data: {
                    action: 'loadTableAssignCPU'
                }
            },
            drawCallback: function(settings, json) {
                $('[data-bs-toggle="tooltip"]').tooltip();
                $('[id^="tooltip"]').tooltip('hide'); // =========== Hide tooltip every table draw ===========
            },
            columnDefs: [{
                    targets: [0, 1, 4, 5, 6, 7, 8],
                    className: 'td-middle-center',
                },
                {
                    targets: [2, 3],
                    className: 'td-middle-left',
                },
                {
                    targets: [4, 5, 6, 7, 8],
                    width: '8%'
                },
                {
                    targets: 8,
                    orderable: false
                }
            ]
        });


    }

    function newAssignCPU() {
        modalBehavior('new');
    }

    function updateAssignCPU() {
        modalBehavior('update');
    }

    function deleteAssignCPU() {


    }

    function printAssignCPU() {


    }
</script>
<?php include './../includes/footer.php'; ?>