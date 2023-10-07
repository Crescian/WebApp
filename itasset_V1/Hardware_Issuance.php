<?php include '../includes/navbar.php'; ?>
<!-- Insert your code here -->
<div class="container-fluid px-5 py-4">
    <div class="card shadow-sm border-light mb-2">
        <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
                <h4 class="text-primary">Hardware<span class="text-secondary"> / Issuance / Employee </span></h4>
                <button class="btn btn-primary"><i class="fa-solid fa-plus pe-2"></i>New Entry</button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-light">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table w-100 table-hover" id="hardware_issuance_table">
                    <thead>
                        <tr>
                            <th>EMPLOYEE</th>
                            <th>CPU NUMBER</th>
                            <th>ITEM</th>
                            <th>DESCRIPTION</th>
                            <th>ISSUER</th>
                            <th>DATE ISSUED</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                        </tr>
                        <tr>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                        </tr>
                        <tr>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                        </tr>
                        <tr>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                        </tr>
                        <tr>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                            <td>Sample</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>
    // $('#hardware_issuance_table').DataTable({
    //     responsive: true,
    //     serverSide: true,
    //     autoWidth: false,
    //     order: [
    //         [1, 'desc']
    //     ],
    //     ajax: {
    //         url: './../controllers/File_Assign_CPU_functions.php',
    //         type: 'POST',
    //         data: {
    //             action: 'loadTableCpu',
    //         }
    //     }
    //     // scrollY: 300,
    // });
</script>
<?php include '../includes/footer.php'; ?>