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
                <span class="page-title-humanresources">Applicant Accounts</span>
            </div>
            <!-- content section -->
            <div class="row mt-5 mb-4">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header card-8 py-3">
                            <div class="row">
                                <span class="fw-bold fs-27 text-light">List</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="account_list_table" class="table table-bordered table-striped fw-bold" width="100%">
                                    <thead class="customHeaderHR">
                                        <tr>
                                            <th>Firstname</th>
                                            <th>Surname</th>
                                            <th>Email</th>
                                            <th style="text-align: center;">Birthday</th>
                                            <th style="text-align: center;">Gender</th>
                                            <th style="text-align: center;">Account Created</th>
                                            <th style="text-align: center;">Email Verified</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="customHeaderHR">
                                        <tr>
                                            <th>Firstname</th>
                                            <th>Surname</th>
                                            <th>Email</th>
                                            <th style="text-align: center;">Birthday</th>
                                            <th style="text-align: center;">Gender</th>
                                            <th style="text-align: center;">Account Created</th>
                                            <th style="text-align: center;">Email Verified</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
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
            <div class="card card-8 border-0 shadow">
                <div class="d-flex justify-content-between justify-content-md-end mt-1 me-3 align-items-center">
                    <button class="btn btn-transparent text-white d-block d-md-none fs-2" onclick="menuPanelClose();"><i class="fa-solid fa-bars"></i></button>
                    <a href="../Landing_Page.php" class="text-white fs-2">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="position-absolute app-title-wrapper">
                    <span class="fw-bold app-title text-nowrap">HUMAN RESOURCES</span>
                </div>
                <div class="card-body menu" style="height: 85vh; overflow-y:auto;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './../includes/footer.php'; ?>
<script>
    loadApplicantAccountTable();

    function loadApplicantAccountTable() {
        var account_list_table = $('#account_list_table').DataTable({
            'lengthMenu': [
                [10, 25, 50, 100],
                [10, 25, 50, 100],
            ],
            'autoWidth': false,
            'responsive': true,
            'deferRender': true,
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: '../controller/human_resources_controller/hr_applicant_account_contr.class.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'load_applicant_account_table'
                }
            },
            'columnDefs': [{
                targets: [0, 1],
                className: 'dt-body-middle-left',
                width: '15%'
            }, {
                targets: 2,
                className: 'dt-body-middle-left'
            }, {
                targets: [3, 4, 5],
                className: 'dt-body-middle-center',
                width: '8%'
            }, {
                targets: 6,
                className: 'dt-body-middle-center',
                width: '7%'
            }, {
                targets: 7,
                className: 'dt-nowrap-center',
                orderable: false,
                render: function(data, type, row, meta) {
                    return `<button type="button" class="btn col-sm btn-danger btnDelete" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Remove Account" onclick="removeAccount('${data[0]}');"><i class="fa-solid fa-trash-can fa-bounce"></i></button>`;
                }
            }]
        });
        account_list_table.on('draw', function() {
            setTimeout(function() {
                $('[data-bs-toggle="tooltip"]').tooltip(); //* ======== Initialize tooltip ========
                $('[id^="tooltip"]').remove(); //* ======== Remove tooltip every table draw ========
                $('[data-bs-toggle="tooltip"]').on('click', function() { //* ======= Hide tooltip upon click =======
                    $(this).tooltip('hide');
                });
            }, 200);
        });
        setInterval(function() {
            account_list_table.ajax.reload(null, false);
        }, 30000); //* ======= Reload Table Data Every X seconds with pagination retained =======
    }

    function removeAccount(applicantid) {
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
                    url: '../controller/human_resources_controller/hr_applicant_account_contr.class.php',
                    type: 'POST',
                    data: {
                        action: 'delete_applicant_account',
                        applicantid: applicantid
                    },
                    success: result => {
                        $('#account_list_table').DataTable().ajax.reload(null, false);
                        Swal.fire(
                            'Deleted!',
                            'Account deleted.',
                            'success'
                        )
                    }
                });
            }
        });
    }
</script>
</body>
<html>